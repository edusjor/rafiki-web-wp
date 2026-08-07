<?php
/**
 * "Content Manager" role: can create/edit/publish/delete Accommodations,
 * Activities and Packages, and upload media — but can't touch users,
 * plugins, themes, or settings. Lets lodge staff manage the site without
 * a full Administrator account.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function rafiki_manager_capabilities() {
	$caps = array( 'read' => true, 'upload_files' => true );
	foreach ( array( 'accommodation' => 'accommodations', 'activity' => 'activities', 'package' => 'packages' ) as $singular => $plural ) {
		$caps[ "edit_{$singular}" ]           = true;
		$caps[ "read_{$singular}" ]           = true;
		$caps[ "delete_{$singular}" ]         = true;
		$caps[ "edit_{$plural}" ]             = true;
		$caps[ "edit_others_{$plural}" ]      = true;
		$caps[ "publish_{$plural}" ]          = true;
		$caps[ "read_private_{$plural}" ]     = true;
		$caps[ "delete_{$plural}" ]           = true;
		$caps[ "delete_private_{$plural}" ]   = true;
		$caps[ "delete_published_{$plural}" ] = true;
		$caps[ "delete_others_{$plural}" ]    = true;
		$caps[ "edit_private_{$plural}" ]     = true;
		$caps[ "edit_published_{$plural}" ]   = true;
	}
	return $caps;
}

/**
 * Creates/updates the "content_manager" role and grants the Administrator
 * role the same capabilities — these post types use custom capability
 * types, so without this even Administrators can't edit them (WordPress
 * doesn't grant custom post type capabilities by default).
 * Re-runs automatically whenever rafiki_manager_capabilities() changes.
 */
function rafiki_sync_manager_role() {
	$version = 3; // bump this if the capabilities above change
	if ( (int) get_option( 'rafiki_role_version' ) === $version ) return;

	remove_role( 'gestor_rafiki' );
	remove_role( 'content_manager' );
	add_role( 'content_manager', 'Content Manager', rafiki_manager_capabilities() );

	$admin = get_role( 'administrator' );
	if ( $admin ) {
		foreach ( rafiki_manager_capabilities() as $cap => $grant ) {
			$admin->add_cap( $cap, $grant );
		}
	}

	update_option( 'rafiki_role_version', $version );
}
add_action( 'init', 'rafiki_sync_manager_role' );
