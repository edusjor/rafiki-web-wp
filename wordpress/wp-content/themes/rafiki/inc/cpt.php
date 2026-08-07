<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function rafiki_register_post_types() {

	register_post_type( 'accommodation', array(
		'labels' => array(
			'name'          => 'Accommodations',
			'singular_name' => 'Accommodation',
			'add_new_item'  => 'Add Accommodation',
			'edit_item'     => 'Edit Accommodation',
			'new_item'      => 'New Accommodation',
			'view_item'     => 'View Accommodation',
			'all_items'     => 'All Accommodations',
			'search_items'  => 'Search Accommodations',
			'not_found'     => 'No accommodations found',
			'menu_name'     => 'Accommodations',
		),
		'public'          => true,
		'has_archive'     => true,
		'rewrite'         => array( 'slug' => 'stay' ),
		'menu_icon'       => 'dashicons-admin-home',
		'menu_position'   => 20,
		'supports'        => array( 'title', 'thumbnail' ),
		'show_in_rest'    => false,
		'map_meta_cap'    => true,
		'capability_type' => array( 'accommodation', 'accommodations' ),
	) );

	register_post_type( 'activity', array(
		'labels' => array(
			'name'          => 'Activities',
			'singular_name' => 'Activity',
			'add_new_item'  => 'Add Activity',
			'edit_item'     => 'Edit Activity',
			'new_item'      => 'New Activity',
			'view_item'     => 'View Activity',
			'all_items'     => 'All Activities',
			'search_items'  => 'Search Activities',
			'not_found'     => 'No activities found',
			'menu_name'     => 'Activities',
		),
		'public'          => true,
		'has_archive'     => true,
		'rewrite'         => array( 'slug' => 'experiences' ),
		'menu_icon'       => 'dashicons-palmtree',
		'menu_position'   => 21,
		'supports'        => array( 'title', 'thumbnail' ),
		'show_in_rest'    => false,
		'map_meta_cap'    => true,
		'capability_type' => array( 'activity', 'activities' ),
	) );

	register_post_type( 'package', array(
		'labels' => array(
			'name'          => 'Packages',
			'singular_name' => 'Package',
			'add_new_item'  => 'Add Package',
			'edit_item'     => 'Edit Package',
			'new_item'      => 'New Package',
			'view_item'     => 'View Package',
			'all_items'     => 'All Packages',
			'search_items'  => 'Search Packages',
			'not_found'     => 'No packages found',
			'menu_name'     => 'Packages',
		),
		'public'          => true,
		'has_archive'     => true,
		'rewrite'         => array( 'slug' => 'packages' ),
		'menu_icon'       => 'dashicons-tickets-alt',
		'menu_position'   => 22,
		'supports'        => array( 'title', 'thumbnail' ),
		'show_in_rest'    => false,
		'map_meta_cap'    => true,
		'capability_type' => array( 'package', 'packages' ),
	) );
}
add_action( 'init', 'rafiki_register_post_types' );

/**
 * Featured image is used as the card/hero image on archive listings and
 * the homepage "Choose Your Experience" grid when no gallery is set.
 */
function rafiki_flush_rewrites_once() {
	if ( (int) get_option( 'rafiki_rewrite_flushed' ) !== 2 ) {
		rafiki_register_post_types();
		flush_rewrite_rules();
		update_option( 'rafiki_rewrite_flushed', 2 );
	}
}
add_action( 'init', 'rafiki_flush_rewrites_once', 20 );
