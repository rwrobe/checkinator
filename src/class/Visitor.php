<?php
/**
 * Add Visitor custom post type
 *
 * @package Checkinator
 */


namespace notne\Visitor;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists( 'Visitor' ) ) :
	class Visitor {

		/** @var string The text domain, for localization */
		protected $textdomain = '';
		/** @var string The slug for the CPT */
		protected $slug = '';

		public function __construct()	{
			$this->textdomain	= 'checkinator';
			$this->slug	        = 'visitor';

			/** Create CPT on init */
			add_action( 'init', array( &$this, 'visitor_init' ) );

			/** Flushing rewrites is an expensive operation, so we run it on activation/deactivation */
			register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
			register_activation_hook( __FILE__, 'flush_rewrites' );
		}

		/**
		 * Add the CPT labels and params to the $posts array.
		 */
		public function visitor_init() {

			/** Define the settings */
			$settings = array(
				'labels'			 => array(
					'name'				 => __( 'Visitors', $this->textdomain ),
					'singular_name'		 => __( 'Visitor', $this->textdomain ),
					'add_new'			 => __( 'Add New', $this->textdomain ),
					'add_new_item'		 => __( 'Add New Visitor', $this->textdomain ),
					'edit'				 => __( 'Edit', $this->textdomain ),
					'edit_item'			 => __( 'Edit Visitor', $this->textdomain ),
					'new_item'			 => __( 'New Visitor', $this->textdomain ),
					'view'				 => __( 'View Visitor', $this->textdomain ),
					'view_item'			 => __( 'View Visitor', $this->textdomain ),
					'search_items'		 => __( 'Search Visitors', $this->textdomain ),
					'not_found'			 => __( 'No visitors found', $this->textdomain ),
					'not_found_in_trash' => __( 'No visitors found in Trash', $this->textdomain ), /** Chuckle */
					'parent'			 => __( 'Parent Visitor', $this->textdomain ),
				),
				'public'				 => true,
				'publicly_queryable'	 => true,
				/**
				 * I've decided for now to hide the typical post UI, thinking that the visitor log shouldn't
				 * have edit/delete options. This may be better handled in the capabilities param, but for now,
				 * I'll go with this approach.
				 */
				'show_ui'				 => false,
				'query_var'				 => true,
				'capability_type'		 => 'post',
				'hierarchical'	 		 => false,
				'menu_position'			 => null,
				'menu_icon' 			 => 'dashicons-id',
				'supports'				 => array( 'title', 'revisions' ),
				'rewrite'				 => array(
					'slug' => $this->slug
				)
			);

			/** Register the post type */
			register_post_type( $this->slug, $settings );
		}

		public function flush_rewrites(){

			//defines the post type so the rules can be flushed.
			$this->visitor_init();

			//and flush the rules.
			flush_rewrite_rules();
		}

	}

	$vtl = new Visitor();

endif;
?>