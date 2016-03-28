<?php
/**
 * Custom Plugin Template
 * File: checkin-form.php
 * @package Checkinator
 */

/**
 * Check if the user is a subscriber. We don't want to open a form like this to the web,
 * so I'm controlling access via user roles. Simply close new user registration and log in
 * at the beginning of the day.
 */
if( ! is_user_logged_in() ) {
	wp_die( sprintf( '%1$s <a href="%2$s" alt="%3$s">%4$s</a>',
		__( "I'm sorry, you'll need to be logged in to use this form.", 'checkinator' ),
		wp_login_url( get_permalink() ),
		__( 'WP Login', 'checkinator' ),
		__( 'Log in now.', 'checkinator' )
	) );
}

/** @var array  The list of personnel loaded from JSON */
$personnel_arr = get_option( 'personnel_list' ) ? get_option( 'personnel_list' ) : array();
/** @var bool $desk  If POST was successful, hide the form and display the success message. */
$desk = ctr_process_form( $personnel_arr );
get_header(); ?>
<form action="" id="check-in" method="POST"<?php echo $desk ? ' class="hide"' : ''; ?> novalidate="novalidate">

	<fieldset id="first">
		<label for="firstName"><?php esc_attr_e( 'First Name', 'checkinator' ) ?></label>
		<input type="text" name="firstName" id="firstName" minlength="2" class="text required" />
	</fieldset>

	<fieldset id="last">
		<label for="lastName"><?php esc_attr_e( 'Last Name', 'checkinator' ) ?></label>
		<input type="text" name="lastName" id="lastName" minlength="2" class="text required" />
	</fieldset>

	<fieldset id="desk">
		<label for="personnel"><?php esc_attr_e( 'Here to See', 'checkinator' ) ?></label>
		<select name="personnel" id="personnel" class="required">
			<?php
			/**
			 * Using the desk as the unique ID for staff, even though one could foresee
			 * two employees sharing the same desk number. We could fix this by generating
			 * a unique ID for each JSON entry and keying on that.
			 */
			foreach( $personnel_arr as $staff ) : ?>
				<option value="<?php esc_attr_e( $staff['desk'] ); ?>"><?php esc_attr_e( $staff['name'] ); ?></option>
			<?php endforeach; ?>
		</select>
	</fieldset>

	<?php wp_nonce_field( 'post_nonce', 'checkinator_nonce_field' ); ?>

	<fieldset>
		<input type="hidden" name="submitted" id="submitted" value="true" />
		<button type="submit" class="submit"><?php esc_attr_e( 'Check In', 'checkinator' ) ?></button>
	</fieldset>

</form>

<div class="success-message<?php echo $desk ? '' : ' hide'; ?>">
	<h2><?php if( $desk) {
			printf( __( 'Please proceed to desk %s.', 'checkinator' ),
				$desk
			);
		}?></h2>
</div>


<?php get_footer(); ?>
