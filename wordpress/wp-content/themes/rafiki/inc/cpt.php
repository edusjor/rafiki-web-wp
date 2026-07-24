<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function rafiki_register_post_types() {

	register_post_type( 'alojamiento', array(
		'labels' => array(
			'name'               => 'Alojamientos',
			'singular_name'      => 'Alojamiento',
			'add_new_item'       => 'Agregar Alojamiento',
			'edit_item'          => 'Editar Alojamiento',
			'new_item'           => 'Nuevo Alojamiento',
			'view_item'          => 'Ver Alojamiento',
			'all_items'          => 'Todos los Alojamientos',
			'search_items'       => 'Buscar Alojamientos',
			'not_found'          => 'No se encontraron alojamientos',
			'menu_name'          => 'Alojamientos',
		),
		'public'       => true,
		'has_archive'  => true,
		'rewrite'      => array( 'slug' => 'stay' ),
		'menu_icon'    => 'dashicons-admin-home',
		'menu_position'=> 20,
		'supports'     => array( 'title', 'thumbnail' ),
		'show_in_rest' => false,
		'map_meta_cap'    => true,
		'capability_type' => array( 'alojamiento', 'alojamientos' ),
	) );

	register_post_type( 'actividad', array(
		'labels' => array(
			'name'               => 'Actividades',
			'singular_name'      => 'Actividad',
			'add_new_item'       => 'Agregar Actividad',
			'edit_item'          => 'Editar Actividad',
			'new_item'           => 'Nueva Actividad',
			'view_item'          => 'Ver Actividad',
			'all_items'          => 'Todas las Actividades',
			'search_items'       => 'Buscar Actividades',
			'not_found'          => 'No se encontraron actividades',
			'menu_name'          => 'Actividades',
		),
		'public'       => true,
		'has_archive'  => true,
		'rewrite'      => array( 'slug' => 'experiencias' ),
		'menu_icon'    => 'dashicons-palmtree',
		'menu_position'=> 21,
		'supports'     => array( 'title', 'thumbnail' ),
		'show_in_rest' => false,
		'map_meta_cap'    => true,
		'capability_type' => array( 'actividad', 'actividades' ),
	) );
}
add_action( 'init', 'rafiki_register_post_types' );

/**
 * Featured image = tarjeta/hero de la experiencia en las listas y en el
 * "Elige tu experiencia" de la portada. Lo hacemos requerido a ojos del
 * editor con una nota, no a nivel de guardado (evita bloquear el flujo).
 */
function rafiki_flush_rewrites_once() {
	if ( ! get_option( 'rafiki_rewrite_flushed' ) ) {
		rafiki_register_post_types();
		flush_rewrite_rules();
		update_option( 'rafiki_rewrite_flushed', 1 );
	}
}
add_action( 'init', 'rafiki_flush_rewrites_once', 20 );
