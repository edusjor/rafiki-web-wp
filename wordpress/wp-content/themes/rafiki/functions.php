<?php
if ( ! defined( 'ABSPATH' ) ) exit;

require_once get_template_directory() . '/inc/icons.php';
require_once get_template_directory() . '/inc/cpt.php';
require_once get_template_directory() . '/inc/meta-boxes.php';
require_once get_template_directory() . '/inc/template-helpers.php';
require_once get_template_directory() . '/inc/admin-users.php';
require_once get_template_directory() . '/inc/settings.php';
require_once get_template_directory() . '/inc/booking.php';
require_once get_template_directory() . '/inc/group-inquiry.php';

function rafiki_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'woocommerce' ); // Cart/Checkout/My Account pages render via page.php + the_content(); no-op if WooCommerce isn't active.
	add_image_size( 'rafiki-card', 700, 420, true );
}
add_action( 'after_setup_theme', 'rafiki_theme_setup' );

function rafiki_assets() {
	$css_path = get_template_directory() . '/assets/css/style.css';
	$js_path  = get_template_directory() . '/assets/js/main.js';

	wp_enqueue_style( 'rafiki-fonts', 'https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700;800&display=swap', array(), null );
	wp_enqueue_style( 'rafiki-style', get_template_directory_uri() . '/assets/css/style.css', array(), file_exists( $css_path ) ? filemtime( $css_path ) : '1.0' );
	wp_enqueue_script( 'rafiki-main', get_template_directory_uri() . '/assets/js/main.js', array(), file_exists( $js_path ) ? filemtime( $js_path ) : '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'rafiki_assets' );

/** Useful columns in each CPT's admin list. */
function rafiki_admin_columns( $columns ) {
	$new = array();
	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['rafiki_price_col'] = 'Price';
		}
	}
	return $new;
}
add_filter( 'manage_accommodation_posts_columns', 'rafiki_admin_columns' );
add_filter( 'manage_activity_posts_columns', 'rafiki_admin_columns' );
add_filter( 'manage_package_posts_columns', 'rafiki_admin_columns' );

function rafiki_admin_column_content( $column, $post_id ) {
	if ( 'rafiki_price_col' === $column ) {
		$price = get_post_meta( $post_id, 'rafiki_price', true );
		$unit  = get_post_meta( $post_id, 'rafiki_price_unit', true );
		echo $price ? '$' . esc_html( $price ) . ' ' . esc_html( $unit ) : '—';
	}
}
add_action( 'manage_accommodation_posts_custom_column', 'rafiki_admin_column_content', 10, 2 );
add_action( 'manage_activity_posts_custom_column', 'rafiki_admin_column_content', 10, 2 );
add_action( 'manage_package_posts_custom_column', 'rafiki_admin_column_content', 10, 2 );
