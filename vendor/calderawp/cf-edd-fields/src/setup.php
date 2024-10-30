<?php
/**
 * Make everything go
 *
 * @package CF_EDD
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CalderaWP LLC
 */
namespace calderawp\cfeddfields;


use calderawp\cfeddfields\fields\license;

class setup {

	/**
	 * @var string
	 */
	protected static $slug = 'edd-licensed-downloads';

	/**
	 * Add hooks
	 */
	public static function add_hooks(){
		add_action( 'caldera_forms_pre_load_processors', [ __CLASS__, 'add_processor' ] );
		add_filter( 'caldera_forms_render_get_field', [ __CLASS__, 'init_license_field' ], 99, 2 );
	}

	/**
	 * Remove hooks
	 */
	public static function remove_hooks(){
		remove_action( 'caldera_forms_pre_load_processors', [ __CLASS__, 'add_processor' ] );
		remove_filter( 'caldera_forms_render_get_field', [ __CLASS__, 'init_license_field' ], 99 );
	}

	/**
	 * Load the EDD SL processor
	 *
	 * @uses "caldera_forms_pre_load_processors" action
	 */
	public static function add_processor(){

		$config = [
			"name"				=>	__( 'EDD: Licensed Downloads', 'cf-edd'),
			"description"		=>	__( 'Populate a select field with a user\'s licensed downloads.', 'cf-edd'),
			"icon"				=>	plugin_dir_url( __FILE__ )  . '/icon.png',
			"author"			=>	"Josh Pollock for CalderaWP LLC",
			"author_url"		=>	"https://CalderaWP.com",
			"template"			=>	__DIR__ . '/config-licensed-downloads.php',
		];

		new processor( $config, self::processor_fields(), self::$slug );

	}

	public static function processor_fields(){
		return [
			[
				'id' => 'edd_licensed_downloads',
				'label' => __( 'Field', 'cf-edd' ),
				'type' => 'advanced',
				'desc' => __( 'Choose a select field to populate with licensed downloads for user specified in next option.', 'cf-edd' ),
				'allow_types' => [
					'dropdown', 'select2', 'radio', 'checkbox'
				],
				'exclude' => [
					'system', 'variables'
				]

			],
			[
				'id' => 'edd_licensed_downloads_user',
				'label' => __( 'User ID', 'cf-edd' ),
				'desc' => __( 'User ID to get licensed downloads for. Leave empty for current user ID. ', 'cf-edd' ),
				'type' => 'text',
				'required' => false,
				'magic' => 'true',

			],
			[
				'id' => 'edd_licensed_downloads_none',
				'label' => __( 'Empty Message', 'cf-edd' ),
				'type' => 'text',
				'required' => false,
				'magic' => 'true',
				'desc' => __( 'No licensed Downloads Found Message', 'cf-form-connector' ),
			]
		];
	}

	/**
	 * Setup license field for EDD SL processor
	 *
	 * @uses "caldera_forms_render_get_field" filter
	 *
	 * @param array $field
	 * @param array $form
	 *
	 * @return array
	 */
	public static function init_license_field( $field, $form ){

		if( $processors = \Caldera_Forms::get_processor_by_type( 'edd-licensed-downloads', $form ) ){
			foreach( $processors as $processor ){
				if( $field['ID'] === $processor['config']['edd_licensed_downloads'] ){
					$user_id = null;
					if ( ! empty( $config[ 'config' ][ 'edd_licensed_downloads_user' ] ) && 0 < absint( $config[ 'config' ][ 'edd_licensed_downloads_user' ] ) ) {
						$user_id = $config[ 'config' ][ 'edd_licensed_downloads_user' ];
					}elseif ( is_user_logged_in() ){
						$user_id = get_current_user_id();
					}

					$downloads = license::get_downloads_by_licensed_user( $user_id );
					$field[ 'config' ][ 'option' ] = array();
					if ( ! empty( $downloads ) ) {
						foreach( $downloads as $id => $title ) {
							$field[ 'config' ][ 'option' ][] = array(
								'label' => esc_html( $title ),
								'value' => (string) $id,
							);
						}
					}

					//Worksround for https://github.com/CalderaWP/Caldera-Forms/issues/1074
                    $field[ 'config' ]['show_values'] = 1;


					break;
				}

			}

		}
		return $field;

	}

}