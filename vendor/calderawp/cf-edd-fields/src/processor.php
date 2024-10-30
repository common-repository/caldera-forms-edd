<?php
/**
 * EDD SL License Processor
 *
 * @package CF_EDD
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CalderaWP LLC
 */

namespace calderawp\cfeddfields;


use calderawp\cfeddfields\fields\license;

class processor extends \Caldera_Forms_Processor_Processor {


	/**
	 * @inheritdoc
	 */
	public function pre_processor( array $config, array $form, $proccesid ) {
		$this->set_data_object_initial( $config, $form );
		$value = $this->data_object->get_value( 'edd_licensed_downloads' );
		$_user = $this->data_object->get_value( 'edd_licensed_downloads_user' );
		if ( 0 < absint( $_user ) ) {
			$user = $_user;
		}else{
			$user = get_current_user_id();
		}


		$downloads = license::get_downloads_by_licensed_user( $user );

		if ( ! is_array( $downloads ) || ! in_array( $value, array_keys( $downloads ) ) ) {
			if( '' != $this->data_object->get_value( 'edd_licensed_downloads_none' ) ? $message = $this->data_object->get_value( 'edd_licensed_downloads_none' ) : $message = __( 'No license downloads found for this user.', 'cf-edd' ) );
			return array(
				'type'=>'error',
				'note' => $message

			);
		}

		$this->setup_transata( $proccesid );
	}


	public function processor( array $config, array $form, $proccesid ) {}


}
