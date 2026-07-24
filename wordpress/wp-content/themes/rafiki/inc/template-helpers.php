<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function rafiki_rows( $post_id, $meta_key ) {
	$rows = get_post_meta( $post_id, $meta_key, true );
	return is_array( $rows ) ? $rows : array();
}

/** Un párrafo por línea no vacía. */
function rafiki_paragraphs( $text ) {
	$lines = array_filter( array_map( 'trim', explode( "\n", (string) $text ) ) );
	$html  = '';
	foreach ( $lines as $line ) {
		$html .= '<p>' . esc_html( $line ) . '</p>';
	}
	return $html;
}

function rafiki_testimonial_source_svg( $source ) {
	$icons = array(
		'google' => '<svg viewBox="0 0 24 24" width="24" height="24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>',
		'tripadvisor' => '<svg viewBox="0 0 24 24" width="24" height="24"><circle cx="12" cy="12" r="11" fill="#34E0A1"/><circle cx="8" cy="12" r="3.2" fill="#fff"/><circle cx="16" cy="12" r="3.2" fill="#fff"/><circle cx="8" cy="12" r="1.3" fill="#1a1a1a"/><circle cx="16" cy="12" r="1.3" fill="#1a1a1a"/></svg>',
		'facebook' => '<svg viewBox="0 0 24 24" width="24" height="24"><circle cx="12" cy="12" r="11" fill="#1877F2"/><path fill="#fff" d="M13.5 21v-7.2h2.4l.36-2.8h-2.76V9.1c0-.81.22-1.36 1.39-1.36h1.48V5.2c-.26-.03-1.14-.11-2.16-.11-2.14 0-3.6 1.31-3.6 3.7v2.21H8.2v2.8h2.41V21h2.89z"/></svg>',
	);
	return isset( $icons[ $source ] ) ? $icons[ $source ] : $icons['google'];
}

function rafiki_testimonial_source_label( $source ) {
	$labels = array( 'google' => 'Reseña de Google', 'tripadvisor' => 'Reseña de TripAdvisor', 'facebook' => 'Reseña de Facebook' );
	return isset( $labels[ $source ] ) ? $labels[ $source ] : 'Reseña';
}

/** Número de WhatsApp desde Configuración General, normalizado con código de país. */
function rafiki_whatsapp_number() {
	$digits = get_option( 'rafiki_whatsapp_numero', '86829454' );
	$digits = preg_replace( '/[^0-9]/', '', (string) $digits );
	if ( 8 === strlen( $digits ) ) {
		$digits = '506' . $digits;
	}
	return $digits;
}

function rafiki_whatsapp_link( $message = '' ) {
	$url = 'https://wa.me/' . rafiki_whatsapp_number();
	if ( $message ) {
		$url .= '?text=' . rawurlencode( $message );
	}
	return $url;
}

function rafiki_whatsapp_icon_svg() {
	return '<svg viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.36A10 10 0 1 0 12 2zm5.9 14.2c-.25.7-1.45 1.34-2 1.42-.5.08-1.15.11-1.86-.12-.43-.14-.98-.32-1.68-.63-2.96-1.28-4.9-4.24-5.04-4.44-.15-.2-1.2-1.6-1.2-3.05 0-1.45.76-2.16 1.03-2.46.27-.3.6-.37.8-.37h.57c.18 0 .43-.07.67.51.25.6.85 2.07.92 2.22.07.15.12.33.02.53-.1.2-.15.32-.3.5-.15.18-.32.4-.45.53-.15.15-.31.32-.13.62.18.3.8 1.32 1.72 2.14 1.18 1.05 2.18 1.38 2.48 1.53.3.15.48.13.65-.08.18-.2.75-.87.95-1.17.2-.3.4-.25.67-.15.28.1 1.75.83 2.05 .98.3.15.5.22.57.35.08.13.08.75-.17 1.45z"/></svg>';
}

/** Link de reserva de un alojamiento/actividad: el que puso el admin, o WhatsApp con el nombre del tour como respaldo. */
function rafiki_booking_link( $post_id ) {
	$link = get_post_meta( $post_id, 'rafiki_link_reserva', true );
	return $link ? $link : rafiki_whatsapp_link( 'Hola! Quiero reservar: ' . get_the_title( $post_id ) );
}

/** Devuelve la URL de la primera imagen de la galería, o el featured image, como fallback para heros/CTAs. */
function rafiki_lead_image_url( $post_id, $size = 'large' ) {
	$gallery = rafiki_rows( $post_id, 'rafiki_galeria' );
	if ( ! empty( $gallery[0]['imagen'] ) ) {
		$url = wp_get_attachment_image_url( $gallery[0]['imagen'], $size );
		if ( $url ) return $url;
	}
	if ( has_post_thumbnail( $post_id ) ) {
		return get_the_post_thumbnail_url( $post_id, $size );
	}
	return '';
}
