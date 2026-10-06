<?php
/**
 * Adds the new client rafting photos (bundled in the theme's
 * assets/img/photos/rafting-*.webp) to the front of the White Water
 * Rafting gallery. The first gallery photo is also the page's hero image
 * (see rafiki_lead_image_url()), so "rafting-family-rapids" becomes the hero.
 *
 * Safe to re-run: photos already in the gallery are not added twice.
 *
 *   wp eval-file /seed/add-rafting-photos.php
 */

require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

$photos = array(
	'rafting-family-rapids' => 'Family rafting through the rapids of the Savegre River',
	'rafting-splash'        => 'Raft crashing through a rapid on the Savegre River',
	'rafting-canyon-wall'   => 'Rafters laughing as they pass the jungle canyon wall',
	'rafting-group-rapids'  => 'Group of rafts running the Savegre rapids',
	'rafting-river-aerial'  => 'Rafts on the clear green Savegre River from above',
);

$post = get_page_by_path( 'white-water-rafting', OBJECT, 'activity' );
if ( ! $post ) {
	WP_CLI::error( 'White Water Rafting activity not found — run seed-demo-content.php first.' );
}

$gallery = get_post_meta( $post->ID, 'rafiki_gallery', true );
$gallery = is_array( $gallery ) ? $gallery : array();
$present = wp_list_pluck( $gallery, 'image' );

$new_rows = array();
foreach ( $photos as $name => $alt ) {
	$ref = 'theme:' . $name;
	$ids = get_posts( array(
		'post_type'      => 'attachment',
		'meta_key'       => '_rafiki_seed_source',
		'meta_value'     => $ref,
		'posts_per_page' => 1,
		'fields'         => 'ids',
	) );
	$id = $ids ? $ids[0] : 0;

	if ( ! $id ) {
		$path = get_template_directory() . '/assets/img/photos/' . $name . '.webp';
		if ( ! file_exists( $path ) ) {
			WP_CLI::warning( "Missing $path" );
			continue;
		}
		$tmp = wp_tempnam( $name . '.webp' );
		copy( $path, $tmp );
		$id = media_handle_sideload( array( 'name' => $name . '.webp', 'tmp_name' => $tmp ), $post->ID );
		if ( is_wp_error( $id ) ) {
			@unlink( $tmp );
			WP_CLI::warning( "Could not import $name: " . $id->get_error_message() );
			continue;
		}
		update_post_meta( $id, '_rafiki_seed_source', $ref );
		update_post_meta( $id, '_wp_attachment_image_alt', $alt );
		WP_CLI::log( "Imported $name -> attachment #$id" );
	}

	if ( ! in_array( $id, array_map( 'intval', $present ), true ) ) {
		$new_rows[] = array( 'image' => $id, 'alt' => $alt );
	}
}

if ( $new_rows ) {
	update_post_meta( $post->ID, 'rafiki_gallery', array_merge( $new_rows, $gallery ) );
	WP_CLI::success( count( $new_rows ) . ' rafting photos added to the front of the gallery.' );
} else {
	WP_CLI::success( 'Rafting gallery already has these photos — nothing to do.' );
}

