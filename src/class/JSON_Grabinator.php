<?php
/**
 * Grab, decode and store the personnel JSON file on plugin activation
 *
 * (WPCS) Using Capital/underscore names as a personal convention for class files
 *
 * @package Checkinator
 */

namespace notne\JSON_Grabinator;

if ( ! class_exists( 'JSON_Grabinator' ) ) :
	class JSON_Grabinator {
		/** @var string  The text domain for localization. */
		private $textdomain = 'checkinator';
		/* @var $personnel_json string  Location of the JSON file that holds the personnel list */
		private $personnel_json = '';
		/** @var string An admin message to notify if curl_init is not allowed/does not exist */
		private $warning_msg = '';

		public function __construct() {
			$this->personnel_json = CTR_BASE_DIR . '/assets/coworkers.json';

			register_activation_hook( CTR_BASE_FILE, array( &$this, 'init' ) );
			register_deactivation_hook( CTR_BASE_FILE, array( &$this, 'tear_down' ) );

			add_action( 'init', array( &$this, 'init' ) );
			add_action( 'admin_notices', array( &$this, 'admin_notices' ) );
			add_action( 'admin_init', array( &$this, 'admin_notices_ignore' ) );
		}

		/**
		 * Read the JSON and save it.
		 */
		public function init() {
			$personnel_decode = array(); // Declare empty variable to type
			$json             = $this->get_json( $this->personnel_json );

			if ( $json ) {
				$personnel_decode = json_decode( $json, true );
				update_option( 'ctr_personnel_list', $personnel_decode );
			} else {
				$this->warning_msg = esc_attr__( 'We had trouble grabbing the personnel list from the server.', $this->textdomain );
			}
		}

		/**
		 * Remove the options from wp_options to allow names to be re-saved on activation.
		 */
		public function tear_down() {
			/** Erase the wp_options string */
			update_option( 'ctr_personnel_list', '' );
			/** Clear admin notice meta */
		}

		/**
		 * Retrieve the contents of the JSON file.
		 *
		 * @var string      The path to the personnel JSON file.
		 * @return array    The decoded JSON
		 */
		public function get_json( $path ) {

			$response = wp_remote_get( 'https://gist.githubusercontent.com/jjeaton/21f04d41287119926eb4/raw/4121417bda0860f662d471d1d22b934a0af56eca/coworkers.json' );

			/** Check for 200 status */
			if ( '200' === wp_remote_retrieve_response_code( $response ) ) {
				return false;
			}

			$output = wp_remote_retrieve_body( $response );

			/** Previous cURL method for packaged JSON
			if ( ! function_exists( 'curl_init' ) ) {
				return false;
			}

			$ch = curl_init();

			// Disable SSL verification
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
			// Will return the response, if false it print the response
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			// Set the url
			curl_setopt( $ch, CURLOPT_URL, $path );

			$output = curl_exec( $ch );

			curl_close( $ch ); */

			return $output;
		}

		/**
		 * Print an admin notice if CURL not allowed/present
		 */
		public function admin_notices() {

			if ( ! $this->warning_msg ) {
				return;
			}

			global $current_user;
			$uid = $current_user->ID;

			/** Check to see if the user has already dismissed the notice. */
			if ( ! get_user_meta( $uid, 'ctr_thanks_but_no_thanks' ) ) {
				printf( '<div class="updated"><p>%1$s <br /><br /> <a href="%2$s">Got it, thanks.</a></p></div>',
					wp_kses_post( $this->warning_msg ),
					'?ctr_thanks_but_no_thanks=0'
				);
			}
		}

		/**
		 * Dismiss the admin notice
		 */
		public function admin_notices_ignore() {
			global $current_user;
			$uid = $current_user->ID;

			/* If user clicks to ignore the notice, add that to their user meta */
			if ( isset( $_GET['ctr_thanks_but_no_thanks'] ) && '0' == $_GET['ctr_thanks_but_no_thanks'] ) {
				add_user_meta( $uid, 'ctr_thanks_but_no_thanks', 1, true );
			}
		}
	}

	$jgr = new JSON_Grabinator;
endif;