<?php
/**
 * Custom Plugin Template
 * File: checkin-form.php
 * @package Checkinator
 */

/** Logic to handle post submission */

/** @var array  The list of personnel loaded from JSON */
$personnel_arr = get_option( 'personnel_list' ) ? get_option( 'personnel_list' ) : array();

get_header(); ?>

<form action="" id="check-in" method="POST">

	<fieldset>
		<label for="first-name"><?php esc_attr_e( 'First Name', 'checkinator' ) ?></label>

		<input type="text" name="first-name" id="first-name" class="required" />
	</fieldset>

	<fieldset>
		<label for="last-name"><?php esc_attr_e( 'Last Name', 'checkinator' ) ?></label>

		<input type="text" name="last-name" id="last-name" class="required" />
	</fieldset>

	<fieldset>
		<label for="personnel"><?php esc_attr_e( 'Here to See', 'checkinator' ) ?></label>

		<select name="personnel" id="personnel" class="required">
			<?php foreach( $personnel_arr as $staff ) : ?>
				<option value="<?php esc_attr_e( $staff['desk'] ); ?>"><?php esc_attr_e( $staff['name'] ); ?></option>
			<?php endforeach; ?>
		</select>
	</fieldset>

	<fieldset>
		<input type="hidden" name="submitted" id="submitted" value="true" />

		<button type="submit"><?php esc_attr_e( 'Check In', 'checkinator' ) ?></button>
	</fieldset>

</form>

<div class="success-message">
	<h2><?php _e( 'Please proceed to desk ', 'checkinator' ); ?></h2>
</div>


<?php get_footer(); ?>
