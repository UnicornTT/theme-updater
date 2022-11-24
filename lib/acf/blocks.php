<?php
add_action( 'acf/init', 'hmt_acf_blocks_init' );
function hmt_acf_blocks_init() {

	$enabled_services = get_field('global_enable_services', 'option');
	$enabled_projects = get_field('global_enable_projects', 'option');
	$enabled_testimonials = get_field('global_enable_testimonials', 'option');
	$enabled_equipment = get_field('global_enable_equipment', 'option');
	$enabled_industries = get_field('global_enable_industries', 'option');
	$enabled_service_areas = get_field('global_enable_service_areas', 'option');
	$enabled_vacancies = get_field('global_enable_vacancies', 'option');
	$enabled_events = get_field('global_enable_events', 'option');
	$enabled_posts = get_field('global_enable_posts', 'option');

	if ( function_exists( 'acf_register_block_type' ) ) {

		hmt_widgets_acf_blocks_init();

		// Enqueue Google maps API
		$google_api_key = get_field( 'google_api_key', 'option' );

		/*** Block "section-intro" ***/
		acf_register_block_type( [
			'name' => 'section-intro',
			'title' => __( 'Hero', THEME_TEXTDOMAIN ),
			'icon' => hmt_get_svg_inline( TEMPLATEPATH . '/assets/admin/img/icons/icon-header.svg' ),
			'align' => 'full',
			'description' => __( 'Make your first impression.', THEME_TEXTDOMAIN ),
			'category' => 'sections',
			'keywords' => array('hero', 'banner', 'intro', 'lander'),
			//'post_types' => array('post', 'page'),
			'supports' => [
				'align' => ['full'],
				'anchor' => true,
				'defaultStylePicker' => false,
			],
			'render_template' => TEMPLATEPATH . '/template-parts/blocks/section-intro/section-intro.php',
			'enqueue_style' => get_template_directory_uri() . '/assets/theme/css/blocks/section-intro.css',
			'example' => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						'block_preview_images' => [
							get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-intro-v1.jpg',
						]
					]
				]
			]
		] );

		/*** Block "section-partners" ***/
		acf_register_block_type( [
			'name' => 'section-partners',
			'title' => __( 'Partners', THEME_TEXTDOMAIN ),
			'icon' => 'groups',
			'align' => 'full',
			'description' => __( 'Show partner logos as a continuous slider.', THEME_TEXTDOMAIN ),
			'category' => 'sections',
			'keywords' => array('partners'),
			//'post_types' => array( 'post', 'page' ),
			'supports' => [
				'align' => ['full'],
				'anchor' => true,
				'defaultStylePicker' => false,
			],
			'render_template' => TEMPLATEPATH . '/template-parts/blocks/section-partners/section-partners.php',
			'enqueue_script' => get_template_directory_uri() . '/assets/theme/js/blocks/section-partners.js',
			'enqueue_assets' => function () {
				wp_enqueue_style( THEME_TEXTDOMAIN . '-section-partners', get_template_directory_uri() . '/assets/theme/css/blocks/section-partners.css' );
			},
			'example' => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						'block_preview_images' => [
							get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-partners.jpg',
						]
					]
				]
			]
		] );

		/*** Block "section-about-us" ***/
		acf_register_block_type( [
			'name' => 'section-about-us',
			'title' => __( 'About Us', THEME_TEXTDOMAIN ),
			'icon' => 'info-outline',
			'align' => 'full',
			'description' => __( 'Showcase what is unique about your organization and its values.', THEME_TEXTDOMAIN ),
			'category' => 'sections',
			'keywords' => array('about us', 'about', 'us'),
			//'post_types' => array( 'post', 'page' ),
			'supports' => [
				'align' => ['full'],
				'anchor' => true,
				'defaultStylePicker' => false,
			],
			'render_template' => TEMPLATEPATH . '/template-parts/blocks/section-about-us/section-about-us.php',
			'enqueue_assets' => function () {
				wp_enqueue_style( THEME_TEXTDOMAIN . '-section-about-us', get_template_directory_uri() . '/assets/theme/css/blocks/section-about-us.css' );
			},
			'example' => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						'block_preview_images' => [
							get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-about-us.jpg',
						]
					]
				]
			]
		] );


		if( $enabled_projects && $enabled_projects == 'enabled'):
			/*** Block "section-our-work" ***/
			acf_register_block_type( [
				'name' => 'section-our-work',
				'title' => __( 'Featured Projects', THEME_TEXTDOMAIN ),
				'icon' => 'open-folder',
				'align' => 'full',
				'description' => __( 'Showcase completed projects the organization is proud of.', THEME_TEXTDOMAIN ),
				'category' => 'sections',
				'keywords' => array('our work', 'work', 'our'),
				'supports' => [
					'align' => ['full'],
					'anchor' => true,
					'defaultStylePicker' => false,
				],
				'enqueue_assets' => function () {
					wp_enqueue_style( THEME_TEXTDOMAIN . '-section-our-work', get_template_directory_uri() . '/assets/theme/css/blocks/section-our-work.css' );
				},
				'render_template' => TEMPLATEPATH . '/template-parts/blocks/section-our-work/section-our-work.php',
				'enqueue_script' => get_template_directory_uri() . '/assets/theme/js/blocks/section-our-work.js',
				'example' => [
					'attributes' => [
						'mode' => 'preview',
						'data' => [
							'block_preview_images' => [
								get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-our-work-v1.jpg',
							]
						]
					]
				]
			] );

			/*** Block "section-portfolio" ***/
			acf_register_block_type( [
				'name' => 'section-portfolio',
				'title' => __( 'Portfolio', THEME_TEXTDOMAIN ),
				'icon' => 'portfolio',
				'align' => 'full',
				'description' => __( 'Showcase completed projects the organization is proud of.', THEME_TEXTDOMAIN ),
				'category' => 'sections',
				'keywords' => array('portfolio', 'projects'),
				'supports' => [
					'align' => ['full'],
					'anchor' => true,
					'defaultStylePicker' => false,
				],
				'render_template' => TEMPLATEPATH . '/template-parts/blocks/section-portfolio/section-portfolio.php',
				'enqueue_assets' => function () use ( $google_api_key ) {
					wp_enqueue_style( THEME_TEXTDOMAIN . '-section-portfolio', get_template_directory_uri() . '/assets/theme/css/blocks/section-portfolio.css' );
					wp_enqueue_script( THEME_TEXTDOMAIN . '-section-portfolio', get_template_directory_uri() . '/assets/theme/js/blocks/section-portfolio.js', array('jquery', THEME_TEXTDOMAIN . '-swiper', THEME_TEXTDOMAIN . '-scrollbar'), '', true );
					wp_enqueue_script( THEME_TEXTDOMAIN . '-google-map', "https://maps.googleapis.com/maps/api/js?key={$google_api_key}&callback=initMap&language=en", [], false, true );
				},
				'example' => [
					'attributes' => [
						'mode' => 'preview',
						'data' => [
							'block_preview_images' => [
								get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-protfolio-v1.jpg',
								get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-protfolio-v2.jpg',
							]
						]
					]
				]
			] );
		endif;

		if( $enabled_testimonials && $enabled_testimonials == 'enabled'):
			/*** Block "section-testimonials" ***/
			acf_register_block_type( [
				'name' => 'section-testimonials',
				'title' => __( 'Testimonials', THEME_TEXTDOMAIN ),
				'icon' => 'testimonial',
				'align' => 'full',
				'description' => __( 'Showcase reviews and testimonials that comment on the quality of your work.', THEME_TEXTDOMAIN ),
				'category' => 'sections',
				'keywords' => array('testimonials', 'testimonial'),
				'supports' => [
					'align' => ['full'],
					'anchor' => true,
					'defaultStylePicker' => false,
				],
				'enqueue_assets' => function () {
					wp_enqueue_style( THEME_TEXTDOMAIN . '-section-testimonials', get_template_directory_uri() . '/assets/theme/css/blocks/section-testimonials.css' );
				},
				'render_template' => TEMPLATEPATH . '/template-parts/blocks/section-testimonials/section-testimonials.php',
				'enqueue_script' => get_template_directory_uri() . '/assets/theme/js/blocks/section-testimonials.js',
				'example' => [
					'attributes' => [
						'mode' => 'preview',
						'data' => [
							'block_preview_images' => [
								get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-testimonials-v1.jpg',
								get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-testimonials-v2.jpg',
								get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-testimonials-v3.jpg',
							]
						]
					]
				]
			] );
		endif;

		if( $enabled_equipment && $enabled_equipment == 'enabled'):
			/*** Block "section-our-equipment" ***/
			acf_register_block_type( [
				'name' => 'section-our-equipment',
				'title' => __( 'Equipment', THEME_TEXTDOMAIN ),
				'icon' => 'hammer',
				'align' => 'full',
				'description' => __( 'Showcase the types of equipment you have.', THEME_TEXTDOMAIN ),
				'category' => 'sections',
				'keywords' => array('equipment'),
				'supports' => [
					'align' => ['full'],
					'anchor' => true,
					'defaultStylePicker' => false,
				],
				'enqueue_assets' => function () {
					wp_enqueue_style( THEME_TEXTDOMAIN . '-section-our-equipment', get_template_directory_uri() . '/assets/theme/css/blocks/section-our-equipment.css' );
				},
				'render_template' => TEMPLATEPATH . '/template-parts/blocks/section-our-equipment/section-our-equipment.php',
				'enqueue_script' => get_template_directory_uri() . '/assets/theme/js/blocks/section-our-equipment.js',
				'example' => [
					'attributes' => [
						'mode' => 'preview',
						'data' => [
							'block_preview_images' => [
								get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-equipment-v1.jpg',
							]
						]
					]
				]
			] );
		endif;

		/*** Block "section-contact" ***/
		acf_register_block_type( [
			'name' => 'section-contact',
			'title' => __( 'Contact Us', THEME_TEXTDOMAIN ),
			'icon' => 'email',
			'align' => 'full',
			'description' => __( 'Make it easy for customers to contact you.', THEME_TEXTDOMAIN ),
			'category' => 'sections',
			'keywords' => array('contact us', 'contact'),
			'supports' => [
				'align' => ['full'],
				'anchor' => true,
				'defaultStylePicker' => false,
			],
			'render_template' => TEMPLATEPATH . '/template-parts/blocks/section-contact/section-contact.php',
			'enqueue_assets' => function () {
				wp_enqueue_style( THEME_TEXTDOMAIN . '-section-contact', get_template_directory_uri() . '/assets/theme/css/blocks/section-contact.css' );
				wp_enqueue_script( THEME_TEXTDOMAIN . '-section-contact', get_template_directory_uri() . '/assets/theme/js/blocks/section-contact.js', array('jquery', THEME_TEXTDOMAIN . '-swiper'), '', true );
			},
			'example' => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						'block_preview_images' => [
							get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-contact.jpg',
						]
					]
				]
			]
		] );

		if( $enabled_services && $enabled_services == 'enabled'):
			/*** Block "section-services" ***/
			acf_register_block_type( [
				'name' => 'section-services',
				'title' => __( 'Services', THEME_TEXTDOMAIN ),
				'icon' => 'admin-tools',
				'align' => 'full',
				'description' => __( 'Briefly highlight the services that you offer.', THEME_TEXTDOMAIN ),
				'category' => 'sections',
				'keywords' => array('Service Hero', 'Service', 'Services'),
				'supports' => [
					'align' => ['full'],
					'anchor' => true,
					'defaultStylePicker' => false,
				],
				'enqueue_assets' => function () {
					wp_enqueue_style( THEME_TEXTDOMAIN . '-section-services', get_template_directory_uri() . '/assets/theme/css/blocks/section-services.css' );
					wp_enqueue_script( THEME_TEXTDOMAIN . '-section-services', get_template_directory_uri() . '/assets/theme/js/blocks/section-services.js', array('jquery', THEME_TEXTDOMAIN . '-swiper'), '', true );
				},
				'render_template' => TEMPLATEPATH . '/template-parts/blocks/section-services/section-services.php',
				'example' => [
					'attributes' => [
						'mode' => 'preview',
						'data' => [
							'block_preview_images' => [
								get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-service-hero-v1.jpg',
								get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-service-hero-v2.jpg',
								get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-service-hero-v3.jpg',
								get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-service-hero-v4.jpg',
								get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-services.jpg',
							]
						]
					]
				]
			] );
		endif;

		/*** Block "section-residential-commercial" ***/
		acf_register_block_type( [
			'name' => 'section-residential-commercial',
			'title' => __( 'Service Types', THEME_TEXTDOMAIN ),
			'icon' => 'clipboard',
			'align' => 'full',
			'description' => __( 'Showcase the types of services that are offered.', THEME_TEXTDOMAIN ),
			'category' => 'sections',
			'keywords' => array('Service Types', 'Residential', 'Commercial', 'Service', 'Types'),
			//'post_types' => array( 'post', 'page' ),
			'supports' => [
				'align' => ['full'],
				'anchor' => true,
				'defaultStylePicker' => false,
			],
			'render_template' => TEMPLATEPATH . '/template-parts/blocks/section-residential-commercial/section-residential-commercial.php',
			'enqueue_assets' => function () {
				wp_enqueue_style( THEME_TEXTDOMAIN . '-section-residential-commercial', get_template_directory_uri() . '/assets/theme/css/blocks/section-residential-commercial.css' );
			},
			'example' => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						'block_preview_images' => [
							get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-residential-commercial.jpg',
						]
					]
				]
			]
		] );

		/*** Block "section-our-team" ***/
		acf_register_block_type( [
			'name' => 'section-our-team',
			'title' => __( 'Our Team', THEME_TEXTDOMAIN ),
			'icon' => 'admin-users',
			'align' => 'full',
			'description' => __( "Gives prospects and clients an idea of who exactly they'll be working with.", THEME_TEXTDOMAIN ),
			'category' => 'sections',
			'keywords' => array('Our Team', 'Our', 'Team'),
			'supports' => [
				'align' => ['full'],
				'anchor' => true,
				'defaultStylePicker' => false,
			],
			'enqueue_assets' => function () {
				wp_enqueue_style( THEME_TEXTDOMAIN . '-section-our-team', get_template_directory_uri() . '/assets/theme/css/blocks/section-our-team.css' );
			},
			'render_template' => TEMPLATEPATH . '/template-parts/blocks/section-our-team/section-our-team.php',
			'enqueue_script' => get_template_directory_uri() . '/assets/theme/js/blocks/section-our-team.js',
			'example' => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						'block_preview_images' => [
							get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-our-team-v1.jpg',
							get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-our-team-v2.jpg',
						]
					]
				]
			]
		] );

		/*** Block "section-leadership" ***/
		acf_register_block_type( [
			'name' => 'section-leadership',
			'title' => __( 'Leadership', THEME_TEXTDOMAIN ),
			'icon' => 'businessman',
			'align' => 'full',
			'description' => __( 'Showcase the leaders of the organization and information about them.', THEME_TEXTDOMAIN ),
			'category' => 'sections',
			'keywords' => array('Leadership'),
			'supports' => [
				'align' => ['full'],
				'anchor' => true,
				'defaultStylePicker' => false,
			],
			'enqueue_assets' => function () {
				wp_enqueue_style( THEME_TEXTDOMAIN . '-section-leadership', get_template_directory_uri() . '/assets/theme/css/blocks/section-leadership.css' );
			},
			'render_template' => TEMPLATEPATH . '/template-parts/blocks/section-leadership/section-leadership.php',
			'enqueue_script' => get_template_directory_uri() . '/assets/theme/js/blocks/section-leadership.js',
			'example' => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						'block_preview_images' => [
							get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-leadership-v1.jpg',
							get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-leadership-v2.jpg',
						]
					]
				]
			]
		] );

		/*** Block "section-content-block" ***/
		acf_register_block_type( [
			'name' => 'section-content-block',
			'title' => __( 'Content Block', THEME_TEXTDOMAIN ),
			'icon' => 'tagcloud',
			'align' => 'full',
			'description' => __( 'Add various types of textual content.', THEME_TEXTDOMAIN ),
			'category' => 'sections',
			'keywords' => array('Content Block', 'Content', 'Block'),
			'supports' => [
				'align' => ['full'],
				'anchor' => true,
				'defaultStylePicker' => false,
			],
			'enqueue_assets' => function () {
				wp_enqueue_style( THEME_TEXTDOMAIN . '-section-content-block', get_template_directory_uri() . '/assets/theme/css/blocks/section-content-block.css' );
			},
			'render_template' => TEMPLATEPATH . '/template-parts/blocks/section-content/section-content-block.php',
			'enqueue_script' => get_template_directory_uri() . '/assets/theme/js/blocks/section-content-block.js',
			'example' => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						'block_preview_images' => [
							get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-content.jpg',
						]
					]
				]
			]
		] );

		if( $enabled_posts && $enabled_posts == 'enabled'):
			/*** Block "section-resources" ***/
			acf_register_block_type( [
				'name' => 'section-resources',
				'title' => __( 'Resources', THEME_TEXTDOMAIN ),
				'icon' => 'schedule',
				'align' => 'full',
				'description' => __( 'Showcase the latest blog posts.', THEME_TEXTDOMAIN ),
				'category' => 'sections',
				'keywords' => array('resources', 'blog'),
				//'post_types' => array( 'post', 'page' ),
				'supports' => [
					'align' => ['full'],
					'anchor' => true,
					'defaultStylePicker' => false,
				],
				'render_template' => TEMPLATEPATH . '/template-parts/blocks/section-resources/section-resources.php',
				'enqueue_assets' => function () {
					wp_enqueue_style( THEME_TEXTDOMAIN . '-section-resources', get_template_directory_uri() . '/assets/theme/css/blocks/section-resources.css' );
					wp_enqueue_script( THEME_TEXTDOMAIN . '-section-resources', get_template_directory_uri() . '/assets/theme/js/blocks/section-resources.js', array('jquery', THEME_TEXTDOMAIN . '-swiper'), '', true );
				},
				'example' => [
					'attributes' => [
						'mode' => 'preview',
						'data' => [
							'block_preview_images' => [
								get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-resources.jpg',
							]
						]
					]
				]
			] );
		endif;

		/*** Block "section-our-history" ***/
		acf_register_block_type( [
			'name' => 'section-our-history',
			'title' => __( 'History', THEME_TEXTDOMAIN ),
			'icon' => 'backup',
			'align' => 'full',
			'description' => __( 'Information about the history of the organization.', THEME_TEXTDOMAIN ),
			'category' => 'sections',
			'keywords' => array('history'),
			'supports' => [
				'align' => ['full'],
				'anchor' => true,
				'defaultStylePicker' => false,
			],
			'render_template' => TEMPLATEPATH . '/template-parts/blocks/section-our-history/section-our-history.php',
			'enqueue_assets' => function () {
				wp_enqueue_style( THEME_TEXTDOMAIN . '-section-our-history', get_template_directory_uri() . '/assets/theme/css/blocks/section-our-history.css' );
				wp_enqueue_script( THEME_TEXTDOMAIN . '-section-our-history', get_template_directory_uri() . '/assets/theme/js/blocks/section-our-history.js', array('jquery', THEME_TEXTDOMAIN . '-swiper'), '', true );
			},
			'example' => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						'block_preview_images' => [
							get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-our-history.jpg',
						]
					]
				]
			]
		] );

		/*** Block "section-value-prop" ***/
		acf_register_block_type( [
			'name' => 'section-value-prop',
			'title' => __( 'Value Propositions', THEME_TEXTDOMAIN ),
			'icon' => 'star-filled',
			'align' => 'full',
			'description' => __( 'Summarize why a customer should choose your product or service.', THEME_TEXTDOMAIN ),
			'category' => 'sections',
			'keywords' => array('value', 'prop'),
			'supports' => [
				'align' => ['full'],
				'anchor' => true,
				'defaultStylePicker' => false,
			],
			'enqueue_assets' => function () {
				wp_enqueue_style( THEME_TEXTDOMAIN . '-section-value-prop', get_template_directory_uri() . '/assets/theme/css/blocks/section-value-prop.css' );
			},
			'render_template' => TEMPLATEPATH . '/template-parts/blocks/section-value-prop/section-value-prop.php',
			'enqueue_script' => get_template_directory_uri() . '/assets/theme/js/blocks/section-value-prop.js',
			'example' => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						'block_preview_images' => [
							get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-value-prop.jpg'
						]
					]
				]
			]
		] );

		/*** Block "section-social-feed" ***/
		acf_register_block_type( [
			'name' => 'section-social-feed',
			'title' => __( 'Social Feed', THEME_TEXTDOMAIN ),
			'icon' => 'share',
			'align' => 'full',
			'description' => __( 'Display Instagram or manually-uploaded media.', THEME_TEXTDOMAIN ),
			'category' => 'sections',
			'keywords' => array('Social Feed', 'Social', 'Feed'),
			'supports' => [
				'align' => ['full'],
				'anchor' => true,
				'defaultStylePicker' => false,
			],
			'enqueue_assets' => function () {
				wp_enqueue_style( THEME_TEXTDOMAIN . '-section-social-feed', get_template_directory_uri() . '/assets/theme/css/blocks/section-social-feed.css' );
			},
			'render_template' => TEMPLATEPATH . '/template-parts/blocks/section-social-feed/section-social-feed.php',
			'enqueue_script' => get_template_directory_uri() . '/assets/theme/js/blocks/section-social-feed.js',
			'example' => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						'block_preview_images' => [
							get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-social-feed-v1.jpg',
							get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-social-feed-v2.jpg',
						]
					]
				]
			]
		] );

		/*** Block "section-careers" ***/
		acf_register_block_type( [
			'name' => 'section-careers',
			'title' => __( 'Careers Hero', THEME_TEXTDOMAIN ),
			'icon' => 'nametag',
			'align' => 'full',
			'description' => __( 'Showcases the career opportunities in the organization.', THEME_TEXTDOMAIN ),
			'category' => 'sections',
			'keywords' => array('Careers'),
			'supports' => [
				'align' => ['full'],
				'anchor' => true,
				'defaultStylePicker' => false,
			],
			'enqueue_assets' => function () {
				wp_enqueue_style( THEME_TEXTDOMAIN . '-section-careers', get_template_directory_uri() . '/assets/theme/css/blocks/section-careers.css' );
			},
			'render_template' => TEMPLATEPATH . '/template-parts/blocks/section-careers/section-careers.php',
			'enqueue_script' => get_template_directory_uri() . '/assets/theme/js/blocks/section-careers.js',
			'example' => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						'block_preview_images' => [
							get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-careers-v2.jpg',
						]
					]
				]
			]
		] );

		/*** Block "section-progression-chart" ***/
		acf_register_block_type( [
			'name' => 'section-progression-chart',
			'title' => __( 'Progression Chart', THEME_TEXTDOMAIN ),
			'icon' => 'analytics',
			'align' => 'full',
			'description' => __( 'Showcases the career opportunities in the organization as a progression chart.', THEME_TEXTDOMAIN ),
			'category' => 'sections',
			'keywords' => array('Progression Chart', 'Progression', 'Chart'),
			'supports' => [
				'align' => ['full'],
				'anchor' => true,
				'defaultStylePicker' => false,
			],
			'enqueue_assets' => function () {
				wp_enqueue_style( THEME_TEXTDOMAIN . '-section-progression-chart', get_template_directory_uri() . '/assets/theme/css/blocks/section-progression-chart.css' );
			},
			'render_template' => TEMPLATEPATH . '/template-parts/blocks/section-progression-chart/section-progression-chart.php',
			'enqueue_script' => get_template_directory_uri() . '/assets/theme/js/blocks/section-progression-chart.js',
			'example' => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						'block_preview_images' => [
							get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-careers-v1.jpg',
						]
					]
				]
			]
		] );

		/*** Block "section-seo-content" ***/
		acf_register_block_type( [
			'name' => 'section-seo-content',
			'title' => __( 'SEO Content', THEME_TEXTDOMAIN ),
			'icon' => 'text',
			'align' => 'full',
			'description' => __( 'Content that helps your web pages to rank high in the search engines.', THEME_TEXTDOMAIN ),
			'category' => 'sections',
			'keywords' => array('SEO', 'content', 'SEO content'),
			'supports' => [
				'align' => ['full'],
				'anchor' => true,
				'defaultStylePicker' => false,
			],
			'enqueue_assets' => function () {
				wp_enqueue_style( THEME_TEXTDOMAIN . '-section-seo-content', get_template_directory_uri() . '/assets/theme/css/blocks/section-seo-content.css' );
			},
			'render_template' => TEMPLATEPATH . '/template-parts/blocks/section-seo-content/section-seo-content.php',
			'example' => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						'block_preview_images' => [
							get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-seo-content-v1.jpg',
							get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-seo-content-v2.jpg',
						]
					]
				]
			]
		] );

		/*** Block "section-our-process" ***/
		acf_register_block_type( [
			'name' => 'section-our-process',
			'title' => __( 'Our Process', THEME_TEXTDOMAIN ),
			'icon' => 'admin-generic',
			'align' => 'full',
			'description' => __( 'Showcase the processes in the organization.', THEME_TEXTDOMAIN ),
			'category' => 'sections',
			'keywords' => array('Our process', 'Our', 'Process'),
			'supports' => [
				'align' => ['full'],
				'anchor' => true,
				'defaultStylePicker' => false,
			],
			'enqueue_assets' => function () {
				wp_enqueue_style( THEME_TEXTDOMAIN . '-section-our-process', get_template_directory_uri() . '/assets/theme/css/blocks/section-our-process.css' );
			},
			'render_template' => TEMPLATEPATH . '/template-parts/blocks/section-our-process/section-our-process.php',
			'enqueue_script' => get_template_directory_uri() . '/assets/theme/js/blocks/section-our-process.js',
			'example' => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						'block_preview_images' => [
							get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-our-process-v1.jpg',
							get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-our-process-v2.jpg',
						]
					]
				]
			]
		] );

		if( $enabled_industries && $enabled_industries == 'enabled'):
			/*** Block "section-industries-served" ***/
			acf_register_block_type( [
				'name' => 'section-industries-served',
				'title' => __( 'Industries Served', THEME_TEXTDOMAIN ),
				'icon' => 'admin-multisite',
				'align' => 'full',
				'description' => __( 'Show the industries that your organization serves.', THEME_TEXTDOMAIN ),
				'category' => 'sections',
				'keywords' => array('Industries Served', 'Industries', 'Served'),
				'supports' => [
					'align' => ['full'],
					'anchor' => true,
					'defaultStylePicker' => false,
				],
				'enqueue_assets' => function () {
					wp_enqueue_style( THEME_TEXTDOMAIN . '-section-industries-served', get_template_directory_uri() . '/assets/theme/css/blocks/section-industries-served.css' );
				},
				'render_template' => TEMPLATEPATH . '/template-parts/blocks/section-industries-served/section-industries-served.php',
				// 'enqueue_script' => get_template_directory_uri() . '/assets/theme/js/blocks/section-industries-served.js',
				'example' => [
					'attributes' => [
						'mode' => 'preview',
						'data' => [
							'block_preview_images' => [
								get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-industries-served-v1.jpg',
								get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-industries-served-v2.jpg',
							]
						]
					]
				]
			] );
		endif;

		if( $enabled_events && $enabled_events == 'enabled'):
			/*** Block "section-events-hero" ***/
			acf_register_block_type( [
				'name' => 'section-events-hero',
				'title' => __( 'Events', THEME_TEXTDOMAIN ),
				'icon' => 'calendar-alt',
				'align' => 'full',
				'description' => __( 'Showcase the events at your organization.', THEME_TEXTDOMAIN ),
				'category' => 'sections',
				'keywords' => array('Events Hero', 'Events', 'Hero'),
				'supports' => [
					'align' => ['full'],
					'anchor' => true,
					'defaultStylePicker' => false,
				],
				'render_template' => TEMPLATEPATH . '/template-parts/blocks/section-events-hero/section-events-hero.php',
				'enqueue_assets' => function () {
					wp_enqueue_style( THEME_TEXTDOMAIN . '-section-events-hero', get_template_directory_uri() . '/assets/theme/css/blocks/section-events-hero.css' );
					wp_enqueue_style( THEME_TEXTDOMAIN . '-section-calendar-events', get_template_directory_uri() . '/assets/theme/css/blocks/section-calendar-events.css' );
					wp_enqueue_script( THEME_TEXTDOMAIN . '-section-events-hero', get_template_directory_uri() . '/assets/theme/js/blocks/section-events-hero.js', array('jquery', THEME_TEXTDOMAIN . '-swiper'), '', true );
				},
				'example' => [
					'attributes' => [
						'mode' => 'preview',
						'data' => [
							'block_preview_images' => [
								get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-events-hero.jpg',
							]
						]
					]
				]
			] );
		endif;

		/*** Block "section-subscribe-our-newsletter" ***/
		acf_register_block_type( [
			'name' => 'section-subscribe-our-newsletters',
			'title' => __( 'Subscribe to Newsletter', THEME_TEXTDOMAIN ),
			'icon' => 'bell',
			'align' => 'full',
			'description' => __( 'Invite the user to subscribe to your newsletter.', THEME_TEXTDOMAIN ),
			'category' => 'sections',
			'keywords' => array('Subscribe To Our Newsletter', 'Subscribe', 'Our', 'Newsletters'),
			//'post_types' => array( 'post', 'page' ),
			'supports' => [
				'align' => ['full'],
				'anchor' => true,
				'defaultStylePicker' => false,
			],
			'render_template' => TEMPLATEPATH . '/template-parts/blocks/section-subscribe-our-newsletters/section-subscribe-our-newsletters.php',
			'enqueue_assets' => function () {
				wp_enqueue_style( THEME_TEXTDOMAIN . '-section-subscribe-our-newsletter', get_template_directory_uri() . '/assets/theme/css/blocks/section-subscribe-our-newsletters.css' );
			},
			'example' => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						'block_preview_images' => [
							get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-subscribe-our-newsletters.jpg',
						]
					]
				]
			]
		] );

		/*** Block "section-benefits" ***/
		acf_register_block_type( [
			'name' => 'section-benefits',
			'title' => __( 'Benefits', THEME_TEXTDOMAIN ),
			'icon' => 'awards',
			'align' => 'full',
			'description' => __( 'Showcase the benefits your organization offers.', THEME_TEXTDOMAIN ),
			'category' => 'sections',
			'keywords' => array('Benefits'),
			//'post_types' => array( 'post', 'page' ),
			'supports' => [
				'align' => ['full'],
				'anchor' => true,
				'defaultStylePicker' => false,
			],
			'render_template' => TEMPLATEPATH . '/template-parts/blocks/section-benefits/section-benefits.php',
			'enqueue_assets' => function () {
				wp_enqueue_style( THEME_TEXTDOMAIN . '-section-benefits', get_template_directory_uri() . '/assets/theme/css/blocks/section-benefits.css' );
			},
			'example' => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						'block_preview_images' => [
							get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-benefits.jpg',
						]
					]
				]
			]
		] );

		if( $enabled_service_areas && $enabled_service_areas == 'enabled'):
			/*** Block "section-service-areas" ***/
			acf_register_block_type( [
				'name' => 'section-service-areas',
				'title' => __( 'Service Areas', THEME_TEXTDOMAIN ),
				'icon' => 'location-alt',
				'align' => 'full',
				'description' => __( 'Show the areas in which the organization offers their services.', THEME_TEXTDOMAIN ),
				'category' => 'sections',
				'keywords' => array('Service Areas Section', 'Service', 'Areas', 'Map'),
				//'post_types' => array( 'post', 'page' ),
				'supports' => [
					'align' => ['full'],
					'anchor' => true,
					'defaultStylePicker' => false,
				],
				'render_template' => TEMPLATEPATH . '/template-parts/blocks/section-service-areas/section-service-areas.php',
				'enqueue_assets' => function () use ( $google_api_key ) {
					wp_enqueue_style( THEME_TEXTDOMAIN . '-section-service-areas', get_template_directory_uri() . '/assets/theme/css/blocks/section-service-areas.css' );
					wp_enqueue_script( THEME_TEXTDOMAIN . '-section-service-areas', get_template_directory_uri() . '/assets/theme/js/blocks/section-service-areas.js', array('jquery', THEME_TEXTDOMAIN . '-swiper', THEME_TEXTDOMAIN . '-scrollbar'), '', true );
					wp_enqueue_script( THEME_TEXTDOMAIN . '-google-map', "https://maps.googleapis.com/maps/api/js?key={$google_api_key}&callback=initMap&language=en", [], false, true );
				},
				'example' => [
					'attributes' => [
						'mode' => 'preview',
						'data' => [
							'block_preview_images' => [
								get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-service-areas-v1.jpg',
								get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-service-areas-v2.jpg',
							]
						]
					]
				]
			] );
		endif;

		if( $enabled_vacancies && $enabled_vacancies == 'enabled'):
			/*** Block "section-open-position" ***/
			acf_register_block_type([
				'name' => 'section-open-position',
				'title' => __( 'Vacancies', THEME_TEXTDOMAIN ),
				'icon' => 'id',
				'align' => 'full',
				'description' => __( 'Showcase the open positions the company has.', THEME_TEXTDOMAIN ),
				'category' => 'sections',
				'keywords' => array( 'Open position', 'Open', 'Position'),
				'supports' => [
					'align' => ['full'],
					'anchor' => true,
					'defaultStylePicker' => false,
				],
				'enqueue_assets' => function() {
					wp_enqueue_style( THEME_TEXTDOMAIN . '-section-open-position', get_template_directory_uri() . '/assets/theme/css/blocks/section-open-position.css' );
				},
				'render_template' => TEMPLATEPATH . '/template-parts/blocks/section-open-position/section-open-position.php',
				'enqueue_script' => get_template_directory_uri() . '/assets/theme/js/blocks/section-open-position.js',
				'example' => [
					'attributes' => [
						'mode' => 'preview',
						'data' => [
							'block_preview_images' => [
								get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-open-position-v1.jpg',
								get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-section-open-position-v2.jpg',
							]
						]
					]
				]
			]);
		endif;
	}
}

function hmt_widgets_acf_blocks_init() {

	$enabled_posts = get_field('global_enable_posts', 'option');
	
	if( $enabled_posts && $enabled_posts == 'enabled'):
		/*** Block "widget-resources-search" ***/
		acf_register_block_type( [
			'name' => 'widget-resources-search',
			'title' => __( 'Search', THEME_TEXTDOMAIN ),
			'icon' => 'search',
			'align' => 'full',
			'description' => __( 'Help visitors find what they are looking for.', THEME_TEXTDOMAIN ),
			'category' => 'theme-widgets',
			'keywords' => array('search', 'resources'),
			//'post_types' => array( 'post', 'page' ),
			'supports' => [
				'align' => ['full'],
				'anchor' => true,
				'defaultStylePicker' => false,
			],
			'render_template' => TEMPLATEPATH . '/template-parts/widgets/widget-resources-search/widget-resources-search.php',
			'enqueue_assets' => function () {

			},
			'example' => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						'block_preview_images' => [
							get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-widget-resources-search.jpg',
						]
					]
				]
			]
		] );

		/*** Block "widget-resources-categories" ***/
		acf_register_block_type( [
			'name' => 'widget-resources-categories',
			'title' => __( 'Categories', THEME_TEXTDOMAIN ),
			'icon' => 'category',
			'align' => 'full',
			'description' => __( 'Display a list of all blog categories.', THEME_TEXTDOMAIN ),
			'category' => 'theme-widgets',
			'keywords' => array('categories', 'resources'),
			//'post_types' => array( 'post', 'page' ),
			'supports' => [
				'align' => ['full'],
				'anchor' => true,
				'defaultStylePicker' => false,
			],
			'render_template' => TEMPLATEPATH . '/template-parts/widgets/widget-resources-categories/widget-resources-categories.php',
			'enqueue_assets' => function () {

			},
			'example' => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						'block_preview_images' => [
							get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-widget-resources-categories.jpg',
						]
					]
				]
			]
		] );

		/*** Block "widget-resources-tags" ***/
		acf_register_block_type( [
			'name' => 'widget-resources-tags',
			'title' => __( 'Tags', THEME_TEXTDOMAIN ),
			'icon' => 'tag',
			'align' => 'full',
			'description' => __( 'Display a list of all blog tags.', THEME_TEXTDOMAIN ),
			'category' => 'theme-widgets',
			'keywords' => array('tags', 'resources'),
			//'post_types' => array( 'post', 'page' ),
			'supports' => [
				'align' => ['full'],
				'anchor' => true,
				'defaultStylePicker' => false,
			],
			'render_template' => TEMPLATEPATH . '/template-parts/widgets/widget-resources-tags/widget-resources-tags.php',
			'enqueue_assets' => function () {

			},
			'example' => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						'block_preview_images' => [
							get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-widget-resources-tags.jpg',
						]
					]
				]
			]
		] );

		/*** Block "widget-resources-recent" ***/
		acf_register_block_type( [
			'name' => 'widget-resources-recent',
			'title' => __( 'Recent Posts', THEME_TEXTDOMAIN ),
			'icon' => 'paperclip',
			'align' => 'full',
			'description' => __( 'Showcase your most recent posts.', THEME_TEXTDOMAIN ),
			'category' => 'theme-widgets',
			'keywords' => array('recent', 'resources'),
			'supports' => [
				'align' => ['full'],
				'anchor' => true,
				'defaultStylePicker' => false,
			],
			'render_template' => TEMPLATEPATH . '/template-parts/widgets/widget-resources-recent/widget-resources-recent.php',
			'enqueue_assets' => function () {

			},
			'example' => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						'block_preview_images' => [
							get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-widget-resources-recent.jpg',
						]
					]
				]
			]
		] );
	endif;

	/*** Block "widget-footer-contact-link" ***/
	acf_register_block_type( [
		'name' => 'widget-footer-contact-link',
		'title' => __( 'Logo and Button', THEME_TEXTDOMAIN ),
		'icon' => 'editor-help',
		'align' => 'full',
		'description' => __( 'Showcase your logo and add a custom button.', THEME_TEXTDOMAIN ),
		'category' => 'theme-widgets',
		'keywords' => array('footer'),
		'supports' => [
			'align' => ['full'],
			'anchor' => true,
			'defaultStylePicker' => false,
		],
		'render_template' => TEMPLATEPATH . '/template-parts/widgets/widget-footer-contact-link/widget-footer-contact-link.php',
		'enqueue_assets' => function () {

		},
		'example' => [
			'attributes' => [
				'mode' => 'preview',
				'data' => [
					'block_preview_images' => [
						get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-widget-footer-contact-link.jpg',
					]
				]
			]
		]
	] );

	/*** Block "widget-footer-menu-column" ***/
	acf_register_block_type( [
		'name' => 'widget-footer-menu-column',
		'title' => __( 'Menu', THEME_TEXTDOMAIN ),
		'icon' => 'columns',
		'align' => 'full',
		'description' => __( 'Display a menu.', THEME_TEXTDOMAIN ),
		'category' => 'theme-widgets',
		'keywords' => array('footer'),
		'supports' => [
			'align' => ['full'],
			'anchor' => true,
			'defaultStylePicker' => false,
		],
		'render_template' => TEMPLATEPATH . '/template-parts/widgets/widget-footer-menu-column/widget-footer-menu-column.php',
		'enqueue_assets' => function () {

		},
		'example' => [
			'attributes' => [
				'mode' => 'preview',
				'data' => [
					'block_preview_images' => [
						get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-widget-footer-menu-column.jpg',
					]
				]
			]
		]
	] );

	/*** Block "widget-footer-big-menu" ***/
	acf_register_block_type( [
		'name' => 'widget-footer-big-menu',
		'title' => __( 'Multi-Column Menu', THEME_TEXTDOMAIN ),
		'icon' => 'welcome-widgets-menus',
		'align' => 'full',
		'description' => __( 'Display a menu in multiple columns.', THEME_TEXTDOMAIN ),
		'category' => 'theme-widgets',
		'keywords' => array('footer', 'menu', 'big'),
		'supports' => [
			'align' => ['full'],
			'anchor' => true,
			'defaultStylePicker' => false,
		],
		'render_template' => TEMPLATEPATH . '/template-parts/widgets/widget-footer-big-menu/widget-footer-big-menu.php',
		'enqueue_assets' => function () {

		},
		'example' => [
			'attributes' => [
				'mode' => 'preview',
				'data' => [
					'block_preview_images' => [
						get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-widget-footer-big-menu.jpg',
					]
				]
			]
		]
	] );

	/*** Block "widget-footer-contact-info" ***/
	acf_register_block_type( [
		'name' => 'widget-footer-contacts',
		'title' => __( 'Contact Info', THEME_TEXTDOMAIN ),
		'icon' => 'phone',
		'align' => 'full',
		'description' => __( ' Display your contact information.', THEME_TEXTDOMAIN ),
		'category' => 'theme-widgets',
		'keywords' => array('footer', 'contact', 'info'),
		'supports' => [
			'align' => ['full'],
			'anchor' => true,
			'defaultStylePicker' => false,
		],
		'render_template' => TEMPLATEPATH . '/template-parts/widgets/widget-footer-contact-info/widget-footer-contact-info.php',
		'enqueue_assets' => function () {

		},
		'example' => [
			'attributes' => [
				'mode' => 'preview',
				'data' => [
					'block_preview_images' => [
						get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-widget-footer-contact-info.jpg',
					]
				]
			]
		]
	] );

	/*** Block "widget-footer-contact-form" ***/
	acf_register_block_type( [
		'name' => 'widget-footer-contact-form',
		'title' => __( 'Form', THEME_TEXTDOMAIN ),
		'icon' => 'forms',
		'align' => 'full',
		'description' => __( 'Add a form to help users connect with you.', THEME_TEXTDOMAIN ),
		'category' => 'theme-widgets',
		'keywords' => array('footer', 'contact', 'form'),
		'supports' => [
			'align' => ['full'],
			'anchor' => true,
			'defaultStylePicker' => false,
		],
		'render_template' => TEMPLATEPATH . '/template-parts/widgets/widget-footer-contact-form/widget-footer-contact-form.php',
		'enqueue_assets' => function () {

		},
		'example' => [
			'attributes' => [
				'mode' => 'preview',
				'data' => [
					'block_preview_images' => [
						get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-widget-footer-contact-form.jpg',
					]
				]
			]
		]
	] );

	/*** Block "widget-footer-media" ***/
	acf_register_block_type( [
		'name' => 'widget-footer-media',
		'title' => __( 'Media', THEME_TEXTDOMAIN ),
		'icon' => 'format-video',
		'align' => 'full',
		'description' => __( 'Display a video or an image.', THEME_TEXTDOMAIN ),
		'category' => 'theme-widgets',
		'keywords' => array('footer', 'media', 'video', 'image', 'popup'),
		'supports' => [
			'align' => ['full'],
			'anchor' => true,
			'defaultStylePicker' => false,
		],
		'render_template' => TEMPLATEPATH . '/template-parts/widgets/widget-footer-media/widget-footer-media.php',
		'enqueue_assets' => function () {

		},
		'example' => [
			'attributes' => [
				'mode' => 'preview',
				'data' => [
					'block_preview_images' => [
						get_template_directory_uri() . '/assets/admin/img/block-previews/block-preview-widget-footer-media.jpg',
					]
				]
			]
		]
	] );
}


