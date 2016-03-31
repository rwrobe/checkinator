<?php
/**
 * Template tags used in the Checkinator
 *
 * @package Checkinator
 */


/**
 * Logic to handle the post submission.
 *
 * @param $personnel_arr array The decoded JSON to validate form submission.
 *
 * @return bool|int|WP_Error  The ID of the new log entry
 */
function ctr_process_form( $personnel_arr ) {

	/** @todo Will need to manually inspect POST using var_export */
	/** Check here for valid nonce. */
	if ( isset( $_POST['checkinator_nonce_field'] ) && wp_verify_nonce( $_POST['checkinator_nonce_field'], 'post_nonce' ) ) {

		$first_name = isset( $_POST['firstName'] ) ? esc_html( wp_unslash( $_POST['firstName'] ) ) : '';
		$last_name  = isset( $_POST['lastName'] ) ? esc_html( wp_unslash( $_POST['lastName'] ) ) : '';
		$full_name  = ucfirst( $first_name ) . ' ' . ucfirst( $last_name );
		$desk       = isset( $_POST['personnel'] ) ? esc_html( wp_unslash( $_POST['personnel'] ) ) : '';
		$here_for   = '';

		/** Find the desk number to return to the form. */
		foreach ( $personnel_arr as $staff ) {
			if ( $desk == $staff['desk'] ) { // Because of different variable types, we use loose comparison here
				$here_for = esc_attr( $staff['name'] );
			}
		}

		/** If we didn't find the desk in the array, there might be something funny going on. */
		if ( ! $here_for ) {
			return false;
		}

		/** @var $post_str The post title for the visitors CPT
		 *
		 * For simplicity, I've decided to simply make a new post with a single string,
		 * rather than adding meta fields. With more time, I would save visitor names in
		 * the visitor CPT, then simply add another timestamp . This would allow us to
		 * welcome back visitors and hook custom actions into new user registration,
		 * among other advantages.
		 */
		$post_str = sprintf( esc_html__( '%5$s: %1$s %2$s here for %3$s (Desk %4$s)', 'checkinator' ),
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
			'post_status'  => 'pending',
		);

		/** Create an entry for the first name and last name in wp_options, to limit check-ins to twice per day */
		$todays_checkins = get_option( 'ctr_todays_checkins' ) ? get_option( 'ctr_todays_checkins' ) : array();

		/** Does it appear twice already? */
		$i = 0;
		foreach ( $todays_checkins as $checkin ) {
			if ( $checkin === $full_name ) {
				$i++;
			}
		}

		/** Add the entry in wp_options if it's empty or if the name appears once or not at all, otherwise bail. */
		if ( false === $todays_checkins ) {
			update_option( 'ctr_todays_checkins', array( $full_name ) );
		} else if ( $i < 2 ) {
			$todays_checkins[] = $full_name;
			update_option( 'ctr_todays_checkins', $todays_checkins );
		} else {
			return 'error';
		}

		/** Insert the post and return something only if it succeeds */
		$success = wp_insert_post( $post_arr );
		if ( $success && ! is_wp_error( $success ) ) {
			do_action( 'ctr_new_log_entry' );
			return $desk;
		}
	}

	return false;
}

/**
 * Cron job to empty the names in wp_options every 24 hours.
 */
function ctr_empty_todays_checkins() {
	update_option( 'ctr_todays_checkins', '' );
}
add_action( 'ctr_empty_checkins',  'ctr_empty_todays_checkins' );

/**
 * Set up cron job
 */
function ctr_set_up_cron() {
	if ( ! wp_next_scheduled( 'ctr_empty_checkins' ) ) {
		wp_schedule_event( time(), 'daily', 'ctr_empty_checkins' );
	}
}
register_activation_hook( __FILE__, 'ctr_set_up_cron' );

/**
 * Tear down cron job on plugin activation
 */
function ctr_tear_down_cron() {
	$timestamp = wp_next_scheduled( 'ctr_empty_checkins' );
	wp_unschedule_event( $timestamp, 'ctr_empty_checkins' );
}
register_deactivation_hook( __FILE__, 'ctr_tear_down_cron' );
