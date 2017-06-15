<?php
/**
 * Controller for handling settings
 *
 * @package MT/Controller
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class MT_Controller_Settings
 */
class MT_Controller_Settings extends MT_Controller_Model {

	/**
	 * Setup
	 */
	public function setup() {
		$this->add_route()
			->handler( 'index', array( $this, 'get_items' ) )
			->handler( 'update', array( $this, 'create_item' ) );
	}

	/**
	 * Get Settings
	 *
	 * @param WP_REST_Request $request The request.
	 * @return WP_REST_Response
	 */
	public function get_items( $request ) {
		$model = $this->model_definition->get_data_store()->get_entity( null );
		if ( empty( $model ) ) {
			return $this->not_found( __( 'Settings not found' ) );
		}

		return $this->ok( $this->prepare_dto( $model ) );
	}

	/**
	 * Create or Update settings.
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response
	 */
	public function create_item( $request ) {
		return $this->create_or_update( $request );
	}

	/**
	 * Create or Update a Model
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response
	 */
	protected function create_or_update( $request ) {
		$is_update = $request->get_method() !== 'POST';
		$model_to_update = $this->model_definition->get_data_store()->get_entity( null );
		if ( empty( $model_to_update ) ) {
			return $this->not_found( 'Model does not exist' );
		}

		$model = $this->get_model_definition()
			->update_model_from_array( $model_to_update, $request->get_params(), true );

		if ( is_wp_error( $model ) ) {
			return $this->bad_request( $model );
		}

		$validation = $model->validate();
		if ( is_wp_error( $validation ) ) {
			return $this->bad_request( $validation );
		}

		$id_or_error = $this->model_data_store->upsert( $model );

		if ( is_wp_error( $id_or_error ) ) {
			return $this->bad_request( $id_or_error );
		}

		$dto = $this->prepare_dto( array(
			'id' => absint( $id_or_error ),
		) );

		return $is_update ? $this->ok( $dto ) : $this->created( $dto );
	}
}
