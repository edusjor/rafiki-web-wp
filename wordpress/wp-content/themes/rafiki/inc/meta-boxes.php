<?php
/**
 * Hand-rolled meta boxes for "alojamiento" / "actividad".
 *
 * No ACF / paid plugin dependency: scalar fields save as single postmeta
 * keys, and repeatable fields (badges, datos rápidos, amenidades,
 * galería, variantes, tarifas, itinerario, paquetes) go through one
 * generic repeater engine (rafiki_render_repeater / rafiki_save_repeater)
 * driven by a small column schema, so every "list of rows" field in the
 * theme shares the same admin UI and save/sanitize logic.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ------------------------------------------------------------------ */
/* Field schemas                                                       */
/* ------------------------------------------------------------------ */

function rafiki_repeater_schemas() {
	return array(
		'rafiki_badges' => array(
			'label'   => 'Badges (bajo el título del hero)',
			'columns' => array(
				array( 'key' => 'texto', 'label' => 'Texto', 'type' => 'text' ),
			),
		),
		'rafiki_datos_rapidos' => array(
			'label'   => 'Datos Rápidos (ficha lateral)',
			'columns' => array(
				array( 'key' => 'label', 'label' => 'Etiqueta', 'type' => 'text' ),
				array( 'key' => 'valor', 'label' => 'Valor', 'type' => 'text' ),
			),
		),
		'rafiki_amenidades' => array(
			'label'   => 'Amenidades / Lo que incluye',
			'columns' => array(
				array( 'key' => 'icono', 'label' => 'Ícono', 'type' => 'select', 'options' => 'icons' ),
				array( 'key' => 'titulo', 'label' => 'Título', 'type' => 'text' ),
				array( 'key' => 'texto', 'label' => 'Texto', 'type' => 'text' ),
			),
		),
		'rafiki_galeria' => array(
			'label'   => 'Galería de Fotos',
			'columns' => array(
				array( 'key' => 'imagen', 'label' => 'Imagen', 'type' => 'image' ),
				array( 'key' => 'alt', 'label' => 'Descripción (alt)', 'type' => 'text' ),
			),
		),
		'rafiki_variantes' => array(
			'label'      => 'Variantes / Elige tu Tienda',
			'post_types' => array( 'alojamiento' ),
			'columns'    => array(
				array( 'key' => 'imagen', 'label' => 'Imagen', 'type' => 'image' ),
				array( 'key' => 'titulo', 'label' => 'Título', 'type' => 'text' ),
				array( 'key' => 'texto', 'label' => 'Texto', 'type' => 'textarea' ),
			),
		),
		'rafiki_tarifas' => array(
			'label'      => 'Tabla de Tarifas',
			'post_types' => array( 'alojamiento' ),
			'columns'    => array(
				array( 'key' => 'temporada', 'label' => 'Temporada', 'type' => 'text' ),
				array( 'key' => 'doble', 'label' => 'Doble Ocupación', 'type' => 'text' ),
				array( 'key' => 'extra', 'label' => 'Persona Extra', 'type' => 'text' ),
				array( 'key' => 'upgrade', 'label' => 'Upgrade', 'type' => 'text' ),
			),
		),
		'rafiki_itinerario' => array(
			'label'      => 'Itinerario / Cómo es tu Día',
			'post_types' => array( 'actividad' ),
			'columns'    => array(
				array( 'key' => 'titulo', 'label' => 'Título del paso', 'type' => 'text' ),
				array( 'key' => 'texto', 'label' => 'Descripción', 'type' => 'textarea' ),
			),
		),
		'rafiki_paquetes' => array(
			'label'      => 'Paquetes que Incluyen esta Actividad',
			'post_types' => array( 'actividad' ),
			'columns'    => array(
				array( 'key' => 'etiqueta', 'label' => 'Etiqueta (ej. "3 noches")', 'type' => 'text' ),
				array( 'key' => 'nombre', 'label' => 'Nombre del Paquete', 'type' => 'text' ),
				array( 'key' => 'texto', 'label' => 'Descripción', 'type' => 'textarea' ),
				array( 'key' => 'precio', 'label' => 'Precio', 'type' => 'text' ),
				array( 'key' => 'precio_nota', 'label' => 'Nota del precio', 'type' => 'text' ),
			),
		),
	);
}

function rafiki_scalar_fields() {
	return array(
		array( 'key' => 'rafiki_subtitulo', 'label' => 'Subtítulo del Hero', 'type' => 'textarea' ),
		array( 'key' => 'rafiki_precio', 'label' => 'Precio', 'type' => 'text' ),
		array( 'key' => 'rafiki_precio_unidad', 'label' => 'Unidad del precio (ej. "/ noche")', 'type' => 'text' ),
		array( 'key' => 'rafiki_precio_nota', 'label' => 'Nota bajo el precio', 'type' => 'text' ),
		array( 'key' => 'rafiki_link_reserva', 'label' => 'Link de Reserva de este tour (botón "Reservar Ahora")', 'type' => 'url' ),
		array( 'key' => 'rafiki_intro_eyebrow', 'label' => 'Eyebrow de la intro (ej. "Stay / Tiendas Safari")', 'type' => 'text' ),
		array( 'key' => 'rafiki_intro_titulo', 'label' => 'Título de la intro', 'type' => 'text' ),
		array( 'key' => 'rafiki_intro_texto', 'label' => 'Texto de la intro (un párrafo por línea)', 'type' => 'textarea_big' ),
		array( 'key' => 'rafiki_amenidades_titulo', 'label' => 'Título de la sección de amenidades', 'type' => 'text' ),
		array( 'key' => 'rafiki_testimonio_fuente', 'label' => 'Fuente del testimonio', 'type' => 'select', 'options' => array( 'google' => 'Google', 'tripadvisor' => 'TripAdvisor', 'facebook' => 'Facebook' ) ),
		array( 'key' => 'rafiki_testimonio_texto', 'label' => 'Texto del testimonio', 'type' => 'textarea' ),
		array( 'key' => 'rafiki_testimonio_autor', 'label' => 'Autor del testimonio', 'type' => 'text' ),
		array( 'key' => 'rafiki_cta_titulo', 'label' => 'Título del banner final', 'type' => 'text' ),
		array( 'key' => 'rafiki_cta_texto', 'label' => 'Texto del banner final', 'type' => 'text' ),
		array( 'key' => 'rafiki_cta_imagen', 'label' => 'Imagen del banner final', 'type' => 'image' ),
	);
}

/* ------------------------------------------------------------------ */
/* Register meta boxes                                                 */
/* ------------------------------------------------------------------ */

function rafiki_add_meta_boxes() {
	foreach ( array( 'alojamiento', 'actividad' ) as $post_type ) {
		add_meta_box( 'rafiki_scalar', 'Contenido de la Página', 'rafiki_render_scalar_box', $post_type, 'normal', 'high' );
	}
	foreach ( rafiki_repeater_schemas() as $meta_key => $schema ) {
		$post_types = isset( $schema['post_types'] ) ? $schema['post_types'] : array( 'alojamiento', 'actividad' );
		foreach ( $post_types as $post_type ) {
			add_meta_box( 'rafiki_' . $meta_key, $schema['label'], function ( $post ) use ( $meta_key, $schema ) {
				rafiki_render_repeater( $post->ID, $meta_key, $schema );
			}, $post_type, 'normal', 'default' );
		}
	}
}
add_action( 'add_meta_boxes', 'rafiki_add_meta_boxes' );

/* ------------------------------------------------------------------ */
/* Render: scalar fields box                                           */
/* ------------------------------------------------------------------ */

function rafiki_render_scalar_box( $post ) {
	wp_nonce_field( 'rafiki_save_meta', 'rafiki_meta_nonce' );
	echo '<table class="form-table rafiki-scalar-table">';
	foreach ( rafiki_scalar_fields() as $field ) {
		$value = get_post_meta( $post->ID, $field['key'], true );
		echo '<tr><th style="width:260px;"><label for="' . esc_attr( $field['key'] ) . '">' . esc_html( $field['label'] ) . '</label></th><td>';
		rafiki_render_field_input( $field['key'], $field['type'], $value, isset( $field['options'] ) ? $field['options'] : null );
		echo '</td></tr>';
	}
	echo '</table>';
}

/**
 * Renders one input element for a given field type. Shared between the
 * scalar box and each repeater row.
 */
function rafiki_render_field_input( $name, $type, $value, $options = null ) {
	switch ( $type ) {
		case 'textarea':
			echo '<textarea name="' . esc_attr( $name ) . '" rows="2" class="widefat">' . esc_textarea( $value ) . '</textarea>';
			break;
		case 'textarea_big':
			echo '<textarea name="' . esc_attr( $name ) . '" rows="6" class="widefat">' . esc_textarea( $value ) . '</textarea>';
			break;
		case 'url':
			echo '<input type="url" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" class="widefat" placeholder="https://...">';
			break;
		case 'select':
			$choices = ( $options === 'icons' ) ? rafiki_icon_choices() : (array) $options;
			echo '<select name="' . esc_attr( $name ) . '">';
			foreach ( $choices as $key => $label ) {
				echo '<option value="' . esc_attr( $key ) . '"' . selected( $value, $key, false ) . '>' . esc_html( $label ) . '</option>';
			}
			echo '</select>';
			break;
		case 'image':
			$image_url = $value ? wp_get_attachment_image_url( $value, 'medium' ) : '';
			echo '<div class="rafiki-image-field">';
			echo '<img class="rafiki-image-preview" src="' . esc_url( $image_url ) . '" style="' . ( $image_url ? '' : 'display:none;' ) . 'max-width:140px;height:auto;display:block;margin-bottom:6px;border-radius:4px;">';
			echo '<input type="hidden" class="rafiki-image-value" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '">';
			echo '<button type="button" class="button rafiki-image-select">Elegir imagen</button> ';
			echo '<button type="button" class="button rafiki-image-clear"' . ( $image_url ? '' : ' style="display:none;"' ) . '>Quitar</button>';
			echo '</div>';
			break;
		default:
			echo '<input type="text" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" class="widefat">';
	}
}

/* ------------------------------------------------------------------ */
/* Render: generic repeater                                            */
/* ------------------------------------------------------------------ */

function rafiki_render_repeater( $post_id, $meta_key, $schema ) {
	$rows = get_post_meta( $post_id, $meta_key, true );
	if ( ! is_array( $rows ) ) $rows = array();

	echo '<div class="rafiki-repeater" data-meta-key="' . esc_attr( $meta_key ) . '">';
	echo '<div class="rafiki-repeater-rows">';
	foreach ( $rows as $i => $row ) {
		rafiki_render_repeater_row( $meta_key, $i, $row, $schema['columns'] );
	}
	echo '</div>';

	echo '<template class="rafiki-repeater-template">';
	rafiki_render_repeater_row( $meta_key, '__INDEX__', array(), $schema['columns'] );
	echo '</template>';

	echo '<p><button type="button" class="button button-primary rafiki-repeater-add">+ Agregar fila</button></p>';
	echo '</div>';
}

function rafiki_render_repeater_row( $meta_key, $index, $row, $columns ) {
	echo '<div class="rafiki-repeater-row">';
	echo '<div class="rafiki-repeater-row-fields">';
	foreach ( $columns as $col ) {
		$name  = $meta_key . '[' . $index . '][' . $col['key'] . ']';
		$value = isset( $row[ $col['key'] ] ) ? $row[ $col['key'] ] : '';
		echo '<div class="rafiki-repeater-field"><label>' . esc_html( $col['label'] ) . '</label>';
		rafiki_render_field_input( $name, $col['type'], $value, isset( $col['options'] ) ? $col['options'] : null );
		echo '</div>';
	}
	echo '</div>';
	echo '<button type="button" class="button-link-delete rafiki-repeater-remove">Eliminar fila</button>';
	echo '</div>';
}

/* ------------------------------------------------------------------ */
/* Save                                                                 */
/* ------------------------------------------------------------------ */

function rafiki_save_meta( $post_id ) {
	if ( ! isset( $_POST['rafiki_meta_nonce'] ) || ! wp_verify_nonce( $_POST['rafiki_meta_nonce'], 'rafiki_save_meta' ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	$post_type = get_post_type( $post_id );
	if ( ! in_array( $post_type, array( 'alojamiento', 'actividad' ), true ) ) return;

	foreach ( rafiki_scalar_fields() as $field ) {
		$raw = isset( $_POST[ $field['key'] ] ) ? wp_unslash( $_POST[ $field['key'] ] ) : '';
		$clean = rafiki_sanitize_value( $raw, $field['type'] );
		update_post_meta( $post_id, $field['key'], $clean );
	}

	foreach ( rafiki_repeater_schemas() as $meta_key => $schema ) {
		$post_types = isset( $schema['post_types'] ) ? $schema['post_types'] : array( 'alojamiento', 'actividad' );
		if ( ! in_array( $post_type, $post_types, true ) ) continue;

		$posted = isset( $_POST[ $meta_key ] ) && is_array( $_POST[ $meta_key ] ) ? wp_unslash( $_POST[ $meta_key ] ) : array();
		$clean_rows = array();
		foreach ( $posted as $row ) {
			$clean_row = array();
			$has_value = false;
			foreach ( $schema['columns'] as $col ) {
				$raw = isset( $row[ $col['key'] ] ) ? $row[ $col['key'] ] : '';
				$val = rafiki_sanitize_value( $raw, $col['type'] );
				if ( $val !== '' ) $has_value = true;
				$clean_row[ $col['key'] ] = $val;
			}
			if ( $has_value ) $clean_rows[] = $clean_row;
		}
		update_post_meta( $post_id, $meta_key, $clean_rows );
	}
}
add_action( 'save_post', 'rafiki_save_meta' );

function rafiki_sanitize_value( $raw, $type ) {
	switch ( $type ) {
		case 'textarea':
		case 'textarea_big':
			return sanitize_textarea_field( $raw );
		case 'image':
			return absint( $raw );
		case 'url':
			return esc_url_raw( $raw );
		case 'select':
			return sanitize_key( $raw );
		default:
			return sanitize_text_field( $raw );
	}
}

/* ------------------------------------------------------------------ */
/* Admin assets                                                        */
/* ------------------------------------------------------------------ */

function rafiki_admin_assets( $hook ) {
	global $post_type;
	if ( ! in_array( $post_type, array( 'alojamiento', 'actividad' ), true ) ) return;
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) return;
	wp_enqueue_media();
	wp_enqueue_style( 'rafiki-admin', get_template_directory_uri() . '/assets/css/admin.css', array(), '1.0' );
	wp_enqueue_script( 'rafiki-admin', get_template_directory_uri() . '/assets/js/admin-repeater.js', array( 'jquery' ), '1.0', true );
}
add_action( 'admin_enqueue_scripts', 'rafiki_admin_assets' );
