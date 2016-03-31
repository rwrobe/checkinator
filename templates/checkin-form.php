<?php
/**
 * Custom Plugin Template
 * File: checkin-form.php
 * @package Checkinator
 */


/** @var array  The list of personnel loaded from JSON */
$personnel_arr = get_option( 'ctr_personnel_list' ) ? get_option( 'ctr_personnel_list' ) : array();
/** @var WP_Post|WP_Error|bool $desk  If POST was successful, hide the form and display the success message. */
$desk = ctr_process_form( $personnel_arr );

get_header(); ?>

<form action="" id="check-in" method="POST" class="ctr-form<?php echo $desk ? ' hide' : ''; ?>" novalidate="novalidate">
	<span><?php esc_html_e( 'Hi! How can we help you?', 'checkinator' ); ?></span>

	<?php esc_html_e( 'Hi, my name is ', 'checkinator' ); ?>
	<input type="text" name="firstName" id="firstName" placeholder="first" minlength="2" class="text required" />

	<input type="text" name="lastName" id="lastName" placeholder="last" minlength="2" class="text required" />

	<?php esc_html_e( ' and I am here to see ', 'checkinator' ); ?>
	<select name="personnel" id="personnel" class="required">
		<?php
		/**
		 * Using the desk as the unique ID for staff, even though one could foresee
		 * two employees sharing the same desk number. We could fix this by generating
		 * a unique ID for each JSON entry and keying on that.
		 */
		foreach ( $personnel_arr as $staff ) : ?>
			<option value="<?php esc_attr_e( $staff['desk'] ); ?>"><?php esc_html_e( $staff['name'] ); ?></option>
		<?php endforeach; ?>
	</select>

	<?php wp_nonce_field( 'post_nonce', 'checkinator_nonce_field' ); ?>

	<fieldset class="ctr-submit-wrap">
		<input type="hidden" name="submitted" id="submitted" value="true" />
		<button type="submit" class="ctr-submit"><?php esc_html_e( 'Check In', 'checkinator' ) ?></button>
	</fieldset>

</form>

<?php if ( $desk && 'error' !== $desk ) { ?>
<div class="ctr-success-message">
	<h2><?php printf( esc_html__( 'Please proceed to desk %s.', 'checkinator' ),
		esc_html( $desk )
	); ?></h2>
</div>
<?php } else if ( $desk ) { ?>
	<div class="ctr-error-message">
		<h2><?php esc_html_e( "I'm sorry, but you can only check in twice per day. Please come back tomorrow.", 'checkinator' ); ?></h2>
	</div>
<?php } ?>
<?php get_footer(); ?>
