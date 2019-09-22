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
		 * Handle Contact form registration  Posts Request: POST Request
		 *
		 * This endpoint takes 'name', 'email', 'subject', 'message' both optionally in query params of the request.
		 * Returns the response with success true on success
		 * Also handles error by returning the relevant error if the fields are empty.
		 *
		 * Example: http://example.com/wp-json/cf7e/v1/enquiry
		 */
		register_rest_route(
			'cf7e/v1',
			'/enquiry',
			array(
				'methods'  => 'POST',
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

		// Error Handling.
		$error = new WP_Error();

		$cf7e_name = ! empty( $parameters['name'] ) ? sanitize_text_field( $parameters['name'] ) : '';
		$cf7e_email = ! empty( $parameters['email'] ) ? sanitize_text_field( $parameters['email'] ) : '';
		$cf7e_subject = ! empty( $parameters['subject'] ) ? sanitize_text_field( $parameters['subject'] ) : '';
		$cf7e_message = ! empty( $parameters['message'] ) ? sanitize_text_field( $parameters['message'] ) : '';

		// Error Handling.
		$error = new WP_Error();
		if ( empty( $cf7e_name ) ) {
			$error->add(
				400,
				__( "Name field is required", 'contact-form-7-endpoints' ),
				array( 'status' => 400 )
			);
			return $error;
		}

		if ( empty( $cf7e_email ) ) {
			$error->add(
				400,
				__( "Email field is required", 'contact-form-7-endpoints' ),
				array( 'status' => 400 )
			);
			return $error;
		}

		if ( empty( $cf7e_subject ) ) {
			$error->add(
				400,
				__( "Subject field is required", 'contact-form-7-endpoints' ),
				array( 'status' => 400 )
			);
			return $error;
		}

		if ( empty( $cf7e_message ) ) {
			$error->add(
				400,
				__( "Message field is required", 'contact-form-7-endpoints' ),
				array( 'status' => 400 )
			);
			return $error;
		}

//		$response_received_after_saving_into_database = ...;

		// If data saved into database.
//		if ( ! is_wp_error( $response_received_after_saving_into_database ) && ! empty( $response_received_after_saving_into_database ) ) {
//			$response['code'] = 200;
//			$response['success'] = true;
//		} else {
//			// If posts not found.
//			$error->add( 406, __( 'Error registration could not be submitted', 'contact-form-7-endpoints' ) );
//			return $error;
//		}

		return new WP_REST_Response( $response );
	}

}

new CF7E_Register_Form_Api();
