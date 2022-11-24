<?php
if ( ! class_exists( 'HM_Private_Theme_Updater' ) ) {
	class HM_Private_Theme_Updater{
		public $version;
		public $theme_slug;
		public $cache_key;
		public $cache_allowed;

		public function __construct() {
			var_dump('AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA');
			$this->theme_slug    = 'base';
			$this->version       = wp_get_theme( $this->theme_slug )->get('Version');
			$this->cache_key     = 'hm_theme_update_cache';
			$this->cache_allowed = true;

			add_filter( 'themes_api', array( $this, 'info' ), 20, 3 );
			add_filter( 'site_transient_update_themes', array( $this, 'update' ) );
			add_action( 'upgrader_process_complete', array( $this, 'cache' ), 10, 2 );

			themes_api( 'theme_information', (object) array(
				'slug' => $this->theme_slug,
			) );
		}

		public function request() {
			$remote = get_transient( $this->cache_key );

			if ( false === $remote || ! $this->cache_allowed ) {
				$remote = wp_remote_get(
					// Needs to point to the update server themes json file
					'https://github.com/UnicornTT/theme-updater/blob/master/latest.json',
					array(
						'timeout' => 10,
						'headers' => array(
							'Accept' => 'application/json'
						)
					)
				);

				if (
					is_wp_error( $remote )
					|| 200 !== wp_remote_retrieve_response_code( $remote )
					|| empty( wp_remote_retrieve_body( $remote ) )
				) {
					return false;
				}

				set_transient( $this->cache_key, $remote, 30 * MINUTE_IN_SECONDS );
			}

			$remote = json_decode( wp_remote_retrieve_body( $remote ), true );

			return $remote;
		}

		function info( $response, $action, $args ) {
			if ( 'theme_information' !== $action || $this->theme_slug !== $args->slug ) {
				return $response;
			}

			$remote = $this->request();

			if ( ! $remote ) {
				return $response;
			}

			$response = array(
				'name'    => $remote->name,
				'slug'    => $remote->slug,
				'author'  => $remote->author,
				'version' => $remote->version,
			);

			return $response;
		}

		public function update( $transient ) {
			if ( empty( $transient->checked ) ) {
				return $transient;
			}

			$remote = $this->request();

			if (
				$remote
				&& version_compare( $this->version, $remote['version'], '<' )
				&& version_compare( $remote['requires'], get_bloginfo( 'version' ), '<=' )
				&& version_compare( $remote['requires_php'], PHP_VERSION, '<' )
			) {
				$response = array(
					'slug'        => $this->theme_slug,
					'new_version' => $remote['version'],
					'package'     => $remote['download_url'],
				);

				$transient->response[ $this->theme_slug ] = $response;
			}

			return $transient;
		}

		public function cache( $upgrader, $options ) {
			if (
				$this->cache_allowed
				&& 'update' === $options['action']
				&& 'theme' === $options['type']
			) {
				delete_transient( $this->cache_key );
			}
		}
	}

	new HM_Private_Theme_Updater();
}
