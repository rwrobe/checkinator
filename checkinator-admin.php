<?php
/**
 * The log page, available in the admin area.
 *
 * @package Checkinator
 */

add_action( 'admin_menu', 'ctr_create_menu' );

function ctr_create_menu() {
	add_menu_page(
		'Checkinator Visitor Log',
		'Visitor Log',
		'administrator',
		'visitor-log',
		'ctr_log_page',
		'dashicons-book-alt'
	);
}


/**
 * The admin page for the visitor log
 */
function ctr_log_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Only administrators are allowed to view the log', 'checkinator' ) );
	}

	$args = array(
		'post_type'         => 'visitor',
		'posts_per_page'    => 50,
	);

	$visitors = new WP_Query( $args );
	?>
	<div class="wrap">
		<h2>Checkinator Visitor Log</h2>

		<ul id="log">
			<?php if ( $visitors->have_posts() ) : while ( $visitors->have_posts() ) : $visitors->the_post(); ?>
			<li><?php the_title_attribute(); ?></li>
			<?php endwhile; endif; ?>
		</ul>
	</div>
<?php }
