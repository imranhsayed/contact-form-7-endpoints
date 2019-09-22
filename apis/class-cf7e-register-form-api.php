<?php
/**
 * Register Contact Form 7 API
 *
 * @package contact-form-7-endpoints
 */

/**
 * Class CF7E_Register_Form_Api
 */
class CF7E_Register_Form_Api {
	/**
	 * CF7E_Register_Form_Api constructor.
	 */
	function __construct() {

		add_action( 'rest_api_init', array( $this, 'cf7e_register_form_endpoints' ) );

	}

	/**
	 * Register posts endpoints.
	 */
	function cf7e_register_form_endpoints() {

		/**
		 * Handle Get Case Studies Posts Request: GET Request
		 *
		 * This endpoint takes 'categories_child_id', 'audience_child_id' both optionally in query params of the request.
		 * Returns the user object on success
		 * Also handles error by returning the relevant error if the fields are empty.
		 *
		 * Example: http://example.com/wp-json/cf7e/v1/cases?categories_child_id=37&audience_child_id=38
		 */
		register_rest_route(
			'cf7e/v1',
			'/cases',
			array(
				'methods'  => 'GET',
				'callback' => array( $this, 'cf7e_rest_get_cases_endpoint_handler' ),
			)
		);
	}


	/**
	 * Get Case Studies CPT posts call back.
	 *
	 * It will return posts with given term ids, else the default posts.
	 *
	 * @param WP_REST_Request $request request object.
	 *
	 * @return WP_Error|WP_REST_Response response object.
	 */
	function cf7e_rest_get_cases_endpoint_handler( WP_REST_Request $request ) {

		$response      = [];
		$parameters    = $request->get_params();
		$cases_page_no = ! empty( $parameters['page_no'] ) ? intval( sanitize_text_field( $parameters['page_no'] ) ) : 1;

		$cases_term_ids = [];

		if ( ! empty( $parameters['audience_child_id'] ) ) {
			array_push( $cases_term_ids, sanitize_text_field( $parameters['audience_child_id'] ) );
		}

		if ( ! empty( $parameters['categories_child_id'] ) ) {
			array_push( $cases_term_ids, sanitize_text_field( $parameters['categories_child_id'] ) );
		}

		// Error Handling.
		$error = new WP_Error();

		$cases_data = $this->get_cases( $cases_term_ids, $cases_page_no );

		// If posts found.
		if ( ! is_wp_error( $cases_data['cases_posts'] ) && ! empty( $cases_data['cases_posts'] ) ) {
			$response['status']      = 200;
			$response['cases_posts'] = $cases_data['cases_posts'];
			$response['found_posts'] = $cases_data['found_posts'];

			$total_found_posts      = intval( $cases_data['found_posts'] );
			$response['page_count'] = $this->calculate_page_count( $total_found_posts, 9 );

		} else {
			// If posts not found.
			$error->add( 406, __( 'Case Studies Posts not found', 'rest-api-endpoints' ) );
			return $error;
		}

		return new WP_REST_Response( $response );
	}

	/**
	 * Calculate page count.
	 *
	 * @param int $total_found_posts Total posts found.
	 * @param int $post_per_page Post per page count.
	 *
	 * @return int
	 */
	function calculate_page_count( $total_found_posts, $post_per_page ) {

		return ( (int) ( $total_found_posts / $post_per_page ) ) + ( ( $total_found_posts % $post_per_page ) ? 1 : 0 );
	}

	/**
	 * Get case studies cpt posts.
	 * Call back function: Not to be called directory.
	 * Use get_profiles_by_slug() instead.
	 *
	 * @param array   $cases_term_ids Category term id array.
	 * @param integer $page_no page no.
	 * @return array Case Studies posts.
	 */
	public function get_cases_callback( $cases_term_ids = [], $page_no = 1 ) {

		$taxonomy_query_args = [];

		if ( ! empty( $cases_term_ids ) && is_array( $cases_term_ids ) ) {

			$cases_taxonomy_query = [
				'taxonomy' => 'taxonomy-case-type',
				'field'    => 'id',
				'terms'    => $cases_term_ids,
			];

			array_push( $taxonomy_query_args, $cases_taxonomy_query );

		}

		$args = [
			'post_type'              => 'case-studies',
			'post_status'            => 'publish',
			'posts_per_page'         => 9,
			'fields'                 => 'ids',
			'paged'                  => $page_no,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => true,
			'tax_query'              => $taxonomy_query_args, //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		];

		$latest_cases_by_category = new \WP_Query( $args );

		$cases_posts = $this->get_required_cases_data( $latest_cases_by_category->posts );
		$found_posts = $latest_cases_by_category->found_posts;

		return [
			'cases_posts' => $cases_posts,
			'found_posts' => $found_posts,
		];
	}

	/**
	 * Construct a cases post array that contains, title, excerpt and featured image.
	 *
	 * @param {array} $cases_post_ids Case Studies post ids.
	 *
	 * @return array
	 */
	function get_required_cases_data( $cases_post_ids ) {

		$cases_posts = [];

		if ( ! empty( $cases_post_ids ) && is_array( $cases_post_ids ) ) {
			foreach ( $cases_post_ids as $cases_post_id ) {

				$cases_post                       = [];
				$cases_post['post_title']         = get_the_title( $cases_post_id );
				$cases_post['post_excerpt']       = get_the_excerpt( $cases_post_id );
				$cases_post['permalink']          = get_the_permalink( $cases_post_id );
				$attach_url                       = cf7e_get_attachment_image_url( $cases_post_id );
				$cases_post['featured_image_url'] = ! empty( $attach_url ) ? $attach_url : NINETRADE_IMG_URI . 'default/368x204-slider.png';

				array_push( $cases_posts, $cases_post );
			}
		}

		return $cases_posts;
	}

	/**
	 * Get cached story ids by category slug.
	 *
	 * @param array   $cases_term_ids Category term id array.
	 * @param integer $page_no Page no.
	 *
	 * @return array Cached Case Studies posts.
	 */
	public function get_cases( $cases_term_ids = [], $page_no = 1 ) {

		if ( function_exists( 'cf7e_get_cache_key' ) ) {

			$audience_child_term_id   = ( ! empty( $cases_term_ids[0] ) ) ? $cases_term_ids[0] : '';
			$categories_child_term_id = ( ! empty( $cases_term_ids[1] ) ) ? $cases_term_ids[1] : '';

			// Unique key for query.
			$cache_key  = cf7e_get_cache_key( sprintf( 'cases_%1$s_%2$s', $audience_child_term_id, $categories_child_term_id ) );
			$cache      = new \Ninetrade\Features\Inc\Cache( $cache_key );
			$expires_in = MINUTE_IN_SECONDS * 60;

			return $cache->expires_in( $expires_in )->updates_with( [ $this, 'get_cases_callback' ], [ $cases_term_ids, $page_no ] )->get();

		} else {

			return [];

		}
	}
}

new CF7E_Register_Form_Api();
