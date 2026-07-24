<?php
/**
 * Rol "Gestor Rafiki": puede crear/editar/publicar/eliminar Alojamientos
 * y Actividades y subir imágenes, pero no toca usuarios, plugins, temas
 * ni ajustes — para dar acceso al equipo del lodge sin darles una cuenta
 * de Administrador completa.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function rafiki_manager_capabilities() {
	$caps = array( 'read' => true, 'upload_files' => true );
	foreach ( array( 'alojamiento' => 'alojamientos', 'actividad' => 'actividades' ) as $singular => $plural ) {
		$caps[ "edit_{$singular}" ]                 = true;
		$caps[ "read_{$singular}" ]                 = true;
		$caps[ "delete_{$singular}" ]                = true;
		$caps[ "edit_{$plural}" ]                    = true;
		$caps[ "edit_others_{$plural}" ]             = true;
		$caps[ "publish_{$plural}" ]                 = true;
		$caps[ "read_private_{$plural}" ]            = true;
		$caps[ "delete_{$plural}" ]                  = true;
		$caps[ "delete_private_{$plural}" ]          = true;
		$caps[ "delete_published_{$plural}" ]        = true;
		$caps[ "delete_others_{$plural}" ]           = true;
		$caps[ "edit_private_{$plural}" ]            = true;
		$caps[ "edit_published_{$plural}" ]          = true;
	}
	return $caps;
}

/**
 * Crea/actualiza el rol "gestor_rafiki" y le da las mismas capacidades al
 * Administrador — 'alojamiento'/'actividad' usan un capability_type propio,
 * así que sin esto ni siquiera el rol Administrador puede editarlos
 * (WordPress no asigna capacidades de post types personalizados por defecto).
 * Se re-ejecuta si cambia rafiki_manager_capabilities().
 */
function rafiki_sync_manager_role() {
	$version = 2; // subir este número si cambian las capacidades de arriba
	if ( (int) get_option( 'rafiki_role_version' ) === $version ) return;

	remove_role( 'gestor_rafiki' );
	add_role( 'gestor_rafiki', 'Gestor Rafiki', rafiki_manager_capabilities() );

	$admin = get_role( 'administrator' );
	if ( $admin ) {
		foreach ( rafiki_manager_capabilities() as $cap => $grant ) {
			$admin->add_cap( $cap, $grant );
		}
	}

	update_option( 'rafiki_role_version', $version );
}
add_action( 'init', 'rafiki_sync_manager_role' );
