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
require_once get_template_directory() . '/inc/contact-form.php';
require_once get_template_directory() . '/inc/redirects.php';

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

/**
 * Per-page SEO title + meta description. No SEO plugin is installed, so
 * these are set directly for the pages that have copy-approved SEO titles.
 * Anything not matched below falls back to WordPress's default title tag
 * and gets no meta description.
 */
function rafiki_seo_current_page() {
	if ( is_front_page() ) {
		return array(
			'title'       => 'Rafiki Safari Lodge | Jungle Stay Near Manuel Antonio & Dominical, Costa Rica',
			'description' => 'Add two or three nights of rainforest, rafting, horseback riding, hiking and safari-tent stays to your Costa Rica road trip at Rafiki Safari Lodge.',
		);
	}
	if ( is_post_type_archive( 'accommodation' ) ) {
		return array(
			'title'       => 'Safari Tents in Costa Rica | Stay at Rafiki Safari Lodge',
			'description' => 'Stay in a safari tent surrounded by Costa Rica rainforest at Rafiki Safari Lodge, near the South Pacific route from Manuel Antonio, Dominical and Uvita.',
		);
	}
	if ( is_post_type_archive( 'package' ) ) {
		return array(
			'title'       => 'Costa Rica Adventure Packages | Rafiki Safari Lodge',
			'description' => 'Choose the Rafiki journey that fits your Costa Rica trip — from a two-night rainforest detour to a six-night forest and Pacific adventure.',
		);
	}
	if ( is_page( 'why-rafiki' ) ) {
		return array(
			'title'       => 'Our Story | Rafiki Safari Lodge, Costa Rica',
			'description' => "Discover how a South African family idea became Rafiki Safari Lodge — a rainforest lodge, local workplace and conservation story in Costa Rica's lower Savegre Valley.",
		);
	}
	if ( is_page( 'ecological-mission' ) ) {
		return array(
			'title'       => 'Conservation & Community | Rafiki Safari Lodge Costa Rica',
			'description' => "Discover Rafiki Safari Lodge's work around the Paso de la Danta Biological Corridor, Baird's tapir conservation, local employment and community-based tourism in Costa Rica's Savegre Valley.",
		);
	}
	if ( is_page( 'lekker-bar-braai' ) ) {
		return array(
			'title'       => 'Lekker Bar & Braai | Food at Rafiki Safari Lodge, Costa Rica',
			'description' => 'Breakfast, lunch and dinner at Rafiki Safari Lodge\'s Lekker Bar & Braai — home-style Costa Rican food, South African roots, served where the day comes back together.',
		);
	}
	if ( is_page( 'plan-your-trip' ) ) {
		return array(
			'title'       => 'Before You Get Here | Plan Your Rafiki Stay, Costa Rica',
			'description' => 'Everything to know before staying at Rafiki Safari Lodge — getting there, the safari tents, food, weather and what a typical day looks like.',
		);
	}
	if ( is_singular( 'activity' ) ) {
		$seo = array(
			'white-water-rafting' => array(
				'title'       => 'Whitewater Rafting in Costa Rica | Savegre River at Rafiki Safari Lodge',
				'description' => 'Raft Class II–III rapids on the Savegre River while staying at Rafiki Safari Lodge. A family-friendly Costa Rica rafting experience surrounded by tropical forest.',
			),
			'aqua-hike' => array(
				'title'       => 'Aqua Hike Costa Rica | Waterfalls & Los Campesinos | Rafiki',
				'description' => "Hike from the Savegre River into the rainforest, swim below a waterfall, cross a suspension bridge and visit Los Campesinos during Rafiki's Aqua Hike.",
			),
			'horseback-riding' => array(
				'title'       => 'Horseback Riding in Costa Rica | Savegre Valley at Rafiki',
				'description' => "Ride through the Savegre Valley with local guides and the Duarte family's horses while staying at Rafiki Safari Lodge in Costa Rica.",
			),
			'fishing' => array(
				'title'       => 'Fishing on the Savegre River | Rafiki Safari Lodge Costa Rica',
				'description' => 'Fish the tropical waters of the lower Savegre River from Rafiki Safari Lodge, exploring deep pools and targeting hard-fighting freshwater species by raft.',
			),
			'massage' => array(
				'title'       => 'Massage in the Rainforest | Rafiki Safari Lodge, Costa Rica',
				'description' => 'Book an in-tent massage surrounded by rainforest at Rafiki Safari Lodge — no spa music, no hallway, just the forest going quiet around you.',
			),
		);
		$slug = get_post_field( 'post_name' );
		if ( isset( $seo[ $slug ] ) ) {
			return $seo[ $slug ];
		}
	}
	return null;
}

function rafiki_seo_title( $title ) {
	$seo = rafiki_seo_current_page();
	return $seo ? $seo['title'] : $title;
}
add_filter( 'pre_get_document_title', 'rafiki_seo_title' );

function rafiki_seo_meta_description() {
	$seo = rafiki_seo_current_page();
	if ( $seo ) {
		echo '<meta name="description" content="' . esc_attr( $seo['description'] ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'rafiki_seo_meta_description', 1 );

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
