<?php
/**
 * Template tags used in the Checkinator
 *
 * @package Checkinator
 */


/**
 * Logic to handle the post submission.
 *
 * @param array The decoded JSON to validate form submission.
 *
 * @return bool|int|WP_Error    The ID of the new log entry
 */
function ctr_process_form( $personnel_arr ){

	$first_name = isset( $_POST['firstName'] ) ? esc_attr( $_POST['firstName'] ) : '';
	$last_name  = isset( $_POST['lastName'] ) ? esc_attr( $_POST['lastName'] ) : '';
	$desk       = isset( $_POST['personnel'] ) ? esc_attr( $_POST['personnel'] ) : '';
	$here_for   = '';

	/** Check here for valid nonce. */
	if( isset( $_POST['checkinator_nonce_field'] ) && wp_verify_nonce( $_POST['checkinator_nonce_field'], 'post_nonce' ) ) {

		/** If the desk isn't in the array, the $desk var becomes false, which skips inserting the post. */
		foreach ( $personnel_arr as $staff ) {
			if ( $desk == $staff['desk'] ) {
				$here_for = esc_attr( $staff['name'] );
			}
		}

		/** If we didn't find the desk in the array, there might be something funny going on. */
		if( ! $here_for )
			return false;

		/** @var $post_str  The post title for the visitors CPT
		 *
		 * For simplicity, I've decided to simply make a new post with a single string,
		 * rather than adding meta fields. With more time, I would save visitor names in
		 * the visitor CPT, then simply add another timestamp . This would allow us to
		 * welcome back visitors and hook custom actions into new user registration,
		 * among other advantages.
		 */
		$post_str = sprintf( __( '%5$s: %1$s %2$s here for %3$s (Desk %4$s)', 'checkinator' ),
			ucfirst( $first_name ),
			ucfirst( $last_name ),
			$here_for,
			$desk,
			date( 'd-m-Y, H:i:s' )
		);

		/** @var array  Build the post params */
		$post_arr = array(
			'post_title'   => $post_str,
			'post_content' => '',
			'post_type'    => 'visitor',
			'post_status'  => 'pending'
		);

		/** Insert the post and return something only if it succeeds */
		$success = wp_insert_post( $post_arr );
		if( $success && ! is_wp_error( $success ) ) {
			return $desk;
		}
	}

	return false;
}