<?php

namespace Harbinger_Marketing;

class BirdEye_Reviews {

	const REVIEWS_TRANSIENT_NAME_PREFIX = 'birdeye_reviews_';
	const REVIEWS_SUMMARY_TRANSIENT_NAME_PREFIX = 'birdeye_reviews_summary_';

	protected $business_id;
	protected $api_key;

	public function __construct( $business_id, $api_key ) {
		$this->business_id = $business_id;
		$this->api_key = $api_key;
	}

	public function get_reviews( $count = 25, $only_5_star = false, $allow_empty = false ) {
		$reviews = get_transient( $this->get_reviews_transient_name() );

		if ( false === $reviews ) {
			$reviews = $this->refresh_reviews();
		}

		if ( empty( $reviews ) ) {
			return array();
		}

		$reviews_filtered = $reviews;

		if ( $only_5_star ) {
			$reviews_filtered = array_filter(
				$reviews_filtered,
				function ( $review ) {
					if ( $review->rating == 0 ) {
						$review->rating = 5;
					}

					return $review->rating == 5;
				}
			);
		}

		if ( !$allow_empty ) {
			$reviews_filtered = array_filter(
				$reviews_filtered,
				function ( $review ) {
					return trim( $review->comments );
				}
			);
		}

		return array_slice( $reviews_filtered, 0, $count );
	}

	public function get_reviews_summary() {
		$reviews = get_transient( $this->get_reviews_summary_transient_name() );

		if ( false === $reviews ) {
			$reviews = $this->refresh_reviews_summary();
		}

		if ( empty( $reviews ) ) {
			return array();
		}

		return $reviews;
	}

	protected function refresh_reviews() {
		$ch = curl_init();

		curl_setopt_array(
			$ch,
			array(
				CURLOPT_HTTPHEADER => array(
					'content-type: application/json',
					'accept:application/json'
				),
				CURLOPT_URL => 'https://api.birdeye.com/resources/v1/review/businessid/' . urlencode( $this->business_id ) . '?sindex=0&count=50&api_key=' . urlencode( $this->api_key ),
				CURLOPT_RETURNTRANSFER => 1
			)
		);

		$reviews = curl_exec( $ch );

		curl_close( $ch );

		if ( !$reviews ) {
			return array();
		}

		$reviews = json_decode( $reviews );

		set_transient( $this->get_reviews_transient_name(), $reviews, DAY_IN_SECONDS );

		return $reviews;
	}

	protected function refresh_reviews_summary() {
		$ch = curl_init();

		curl_setopt_array(
			$ch,
			array(
				CURLOPT_HTTPHEADER => array(
					'content-type: application/json',
					'accept:application/json'
				),
				CURLOPT_URL => 'https://api.birdeye.com/resources/v1/review/businessid/' . urlencode( $this->business_id ) . '/summary?sindex=0&count=50&api_key=' . urlencode( $this->api_key ),
				CURLOPT_RETURNTRANSFER => 1
			)
		);

		$reviews_summary = curl_exec( $ch );

		curl_close( $ch );

		if ( !$reviews_summary ) {
			return array();
		}

		$reviews_summary = json_decode( $reviews_summary );

		set_transient( $this->get_reviews_summary_transient_name(), $reviews_summary, DAY_IN_SECONDS );

		return $reviews_summary;
	}

	protected function get_reviews_transient_name() {
		return self::REVIEWS_TRANSIENT_NAME_PREFIX . $this->business_id;
	}

	protected function get_reviews_summary_transient_name() {
		return self::REVIEWS_SUMMARY_TRANSIENT_NAME_PREFIX . $this->business_id;
	}

}