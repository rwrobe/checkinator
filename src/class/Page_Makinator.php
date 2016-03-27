<?php
/**
 * Makes the page
 */

namespace notne\Page_Makinator;

if( ! class_exists( 'Page_Makinator' ) ) :
	class Page_Makinator {

		/** @var string  The text domain for localization. */
		private $textdomain  = 'checkinator';
		/** @var string  The title of the page made on activation */
		private $title       = '';
		/** @var string  The page slug */
		private $slug        = 'visit';
		/** @var string  The user ID */
		private $uid         = '';

		public function __construct() {
			$this->title        = esc_attr__( 'Check-In Form', $this->textdomain );

			add_action( 'admin_init', array( &$this, 'init' ) );
			add_action( 'admin_notices', array( &$this, 'admin_notices' ) );

			add_filter( 'template_include', array( &$this, 'set_template' ) );

			register_deactivation_hook( CTR_BASE_FILE, array( &$this, 'tear_down' ) );
		}

		/**
		 * Ask the user for permission to create the page, if it does not already exist
		 */
		public function init() {
			global $current_user;
			$this->uid   = $current_user->ID;
			$hide_notice = get_user_meta( $this->uid, 'ctr_create_page' );

			if( get_page_by_path( $this->slug ) ){
				return;
			}

			if( $hide_notice && 0 == $hide_notice[0] ){
				return;
			}

			if( isset( $_GET['ctr_create_page'] ) && 0 == $_GET['ctr_create_page'] ) {
				update_user_meta( $this->uid, 'ctr_create_page', $_GET['ctr_create_page'] );
			}

			if( isset( $_GET['ctr_create_page'] ) && 1 == $_GET['ctr_create_page'] ) {
				$this->make_page();
			}
		}

		/**
		 * Add the notice to the admin
		 */
		public function admin_notices(){
			if( get_page_by_path( $this->slug ) ){
				return;
			}

			$meta_exists = get_user_meta( $this->uid, 'ctr_create_page' );

			if ( ! $meta_exists || 0 != $meta_exists[0] ) {
				printf( '<div class="updated"><p>%1$s <br /><br /> <a href="%2$s">%3$s</a><br /><br /><a href="%4$s">%5$s</a></p></div>',
					sprintf( __( 'Checkinator needs a page called <i>%s</i> to work properly. Would you like to create this page now?', $this->textdomain ),
						$this->title
					),
					'?ctr_create_page=1',
					esc_attr__( 'Yes, please.', $this->textdomain ),
					'?ctr_create_page=0',
					esc_attr__( 'No, I will do it myself.', $this->textdomain )
				);
			}
		}

		/**
		 * Create the check-in page.
		 */
		public function make_page(){

			$checkin_form = array(
				'post_type'     => 'page',
				'post_title'    => $this->title,
				'post_name'     => $this->slug,
				'post_content'  => '',
				'post_status'   => 'publish',
				'post_author'   => 1,
			);

			if( ! get_page_by_path( $this->slug ) ){
				$id = wp_insert_post( $checkin_form );
				update_post_meta( $id, '_wp_page_template', CTR_BASE_DIR . '/templates/checkin-form.php' );
			}

		}

		/**
		 * Override the default template for the page.
		 *
		 * @param $template
		 *
		 * @return string
		 */
		public function set_template( $template ) {
			global $post;
			$checkin = get_page_by_path( $this->slug );

			if( $post->ID == $checkin->ID ) {
				$template = CTR_PLUGIN_PATH . '/templates/checkin-form.php';
			}

			return $template;
		}

		public function tear_down(){
			delete_user_meta( $this->uid, 'ctr_create_page' );
		}
	}

	$pmr = new Page_Makinator;
endif;