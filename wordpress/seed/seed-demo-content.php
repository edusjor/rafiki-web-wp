<?php
/**
 * One-off content seeder: recreates the "Tiendas Safari de Lujo" and
 * "White Water Rafting" demo entries from the original static prototype,
 * as real CPT posts with real sideloaded media. Safe to re-run — it
 * skips a post/image if one with the same title/URL already exists.
 *
 * Usage: wp eval-file seed/seed-demo-content.php
 */

require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

function rafiki_seed_image( $url ) {
	static $cache = array();
	if ( isset( $cache[ $url ] ) ) return $cache[ $url ];

	$existing = get_posts( array(
		'post_type'      => 'attachment',
		'meta_key'       => '_rafiki_seed_source',
		'meta_value'     => $url,
		'posts_per_page' => 1,
		'fields'         => 'ids',
	) );
	if ( $existing ) {
		$cache[ $url ] = $existing[0];
		return $existing[0];
	}

	$id = media_sideload_image( $url, 0, null, 'id' );
	if ( is_wp_error( $id ) ) {
		WP_CLI::warning( 'No se pudo importar ' . $url . ': ' . $id->get_error_message() );
		return 0;
	}
	update_post_meta( $id, '_rafiki_seed_source', $url );
	$cache[ $url ] = $id;
	WP_CLI::log( 'Importada: ' . $url . ' -> attachment #' . $id );
	return $id;
}

function rafiki_seed_post( $post_type, $title, $meta ) {
	$found = get_posts( array(
		'post_type'      => $post_type,
		'title'          => $title,
		'posts_per_page' => 1,
		'post_status'    => 'any',
		'fields'         => 'ids',
	) );

	if ( $found ) {
		$post_id = $found[0];
		WP_CLI::log( "Ya existe \"$title\" (#$post_id), actualizando campos." );
	} else {
		$post_id = wp_insert_post( array(
			'post_type'   => $post_type,
			'post_title'  => $title,
			'post_status' => 'publish',
		), true );
		if ( is_wp_error( $post_id ) ) {
			WP_CLI::error( "No se pudo crear \"$title\": " . $post_id->get_error_message() );
			return;
		}
		WP_CLI::success( "Creado \"$title\" (#$post_id)." );
	}

	foreach ( $meta as $key => $value ) {
		update_post_meta( $post_id, $key, $value );
	}

	$gallery = get_post_meta( $post_id, 'rafiki_galeria', true );
	if ( ! empty( $gallery[0]['imagen'] ) ) {
		set_post_thumbnail( $post_id, $gallery[0]['imagen'] );
	}

	return $post_id;
}

/* ==================================================================== */
/* Alojamiento: Tiendas Safari de Lujo                                   */
/* ==================================================================== */

$tents_gallery_urls = array(
	'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-tents-web_19.jpeg',
	'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-tents-web_1.jpeg',
	'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-tents-web_3.jpeg',
	'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-tents-web_6.jpeg',
	'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-tents-web_9.jpeg',
	'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-tents-web_12.jpeg',
);
$tents_gallery = array();
foreach ( $tents_gallery_urls as $url ) {
	$id = rafiki_seed_image( $url );
	if ( $id ) $tents_gallery[] = array( 'imagen' => $id, 'alt' => 'Tienda safari de lujo en Rafiki' );
}

$variant_img_1 = rafiki_seed_image( 'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-tents-web.jpeg' );
$variant_img_2 = rafiki_seed_image( 'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-property-and-food-web_22.jpeg' );
$cta_img_tents = rafiki_seed_image( 'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-tents-web_8.jpeg' );

rafiki_seed_post( 'alojamiento', 'Tiendas Safari de Lujo', array(
	'rafiki_subtitulo'       => '"La experiencia de acampar sin tener que renunciar a nada." Tiendas importadas de Sudáfrica sobre plataformas de madera, en medio de la selva.',
	'rafiki_badges'          => array(
		array( 'texto' => 'Hasta 4–5 personas' ),
		array( 'texto' => 'Porche privado' ),
		array( 'texto' => 'Baño privado' ),
	),
	'rafiki_precio'          => '198',
	'rafiki_precio_unidad'   => '/ noche',
	'rafiki_precio_nota'     => 'Temporada verde · doble ocupación',
	'rafiki_datos_rapidos'   => array(
		array( 'label' => 'Capacidad', 'valor' => '4–5 personas' ),
		array( 'label' => 'Camas', 'valor' => '2 individuales + 1 doble' ),
		array( 'label' => 'Baño', 'valor' => 'Privado, embaldosado' ),
		array( 'label' => 'Vista', 'valor' => 'Bosque / algunas al lago' ),
		array( 'label' => 'Electricidad', 'valor' => 'Hidroeléctrica, 24h' ),
		array( 'label' => 'Persona extra', 'valor' => '$25 / noche' ),
	),
	'rafiki_intro_eyebrow'   => 'Stay / Tiendas Safari',
	'rafiki_intro_titulo'    => 'ACAMPAR, PERO SIN RENUNCIAR A NADA',
	'rafiki_intro_texto'     => "Nuestras 14 tiendas estilo safari combinan el romance de dormir en plena naturaleza con las comodidades de un hotel moderno. Cada una está montada sobre una plataforma de madera elevada, rodeada de selva, con su propia perspectiva del bosque y los sonidos de la naturaleza como banda sonora.\nDespués de un día de aventura, vuelves a un espacio amplio, con ventilador y electricidad, baño privado con todas las amenidades y un porche con mecedoras y mesa de café para ver caer la tarde sobre el valle del Savegre.\nLas tiendas estándar tienen dos camas individuales y una cama doble. Algunas unidades especiales incluyen literas y pueden alojar hasta 5 personas — ideales para familias.",
	'rafiki_galeria'         => $tents_gallery,
	'rafiki_amenidades_titulo' => 'TODO LO QUE INCLUYE TU TIENDA',
	'rafiki_amenidades'      => array(
		array( 'icono' => 'tent', 'titulo' => 'Tiendas importadas de Sudáfrica', 'texto' => 'Lona de alta gama sobre estructura elevada.' ),
		array( 'icono' => 'wood', 'titulo' => 'Plataforma de madera', 'texto' => 'Elevada del suelo, con vista directa al bosque.' ),
		array( 'icono' => 'porch', 'titulo' => 'Porche privado', 'texto' => 'Con mecedoras y mesa de café frente a la selva.' ),
		array( 'icono' => 'bolt', 'titulo' => 'Electricidad y ventilador', 'texto' => 'Generador hidroeléctrico, disponible 24 horas.' ),
		array( 'icono' => 'bath', 'titulo' => 'Baño privado embaldosado', 'texto' => 'Ducha, agua caliente y amenidades completas.' ),
		array( 'icono' => 'heart', 'titulo' => 'Ropa de cama tipo hotel', 'texto' => 'Confort completo después de un día de aventura.' ),
	),
	'rafiki_variantes'       => array(
		array( 'imagen' => $variant_img_1, 'titulo' => 'Tiendas 1 & 2 — Lakeview', 'texto' => 'Las más cercanas al lodge principal, con vista privilegiada a la puesta de sol sobre el valle. Ideales para parejas que buscan el mejor atardecer de la propiedad.' ),
		array( 'imagen' => $variant_img_2, 'titulo' => 'Tienda 3 — Familiar', 'texto' => 'Duerme hasta 5 personas gracias a sus literas adicionales. Balcón a nivel de suelo, perfecta para familias con niños pequeños.' ),
	),
	'rafiki_tarifas'         => array(
		array( 'temporada' => 'Alta — 16 dic 2025 al 30 abr 2026', 'doble' => '$260 / noche', 'extra' => '$25 / noche', 'upgrade' => '$30 / noche' ),
		array( 'temporada' => 'Verde — 1 may 2026 al 15 dic 2026', 'doble' => '$198 / noche', 'extra' => '$25 / noche', 'upgrade' => '$30 / noche' ),
	),
	'rafiki_testimonio_fuente' => 'tripadvisor',
	'rafiki_testimonio_texto'  => 'Cabañas encantadoras, comida excelente. Las tiendas superaron nuestras expectativas — nunca pensamos que acampar podía sentirse tan cómodo.',
	'rafiki_testimonio_autor'  => 'TaikoM',
	'rafiki_cta_titulo'      => '¿LISTO PARA DORMIR EN LA SELVA?',
	'rafiki_cta_texto'       => 'Consulta disponibilidad para tu tienda safari y arma tu paquete ideal.',
	'rafiki_cta_imagen'      => $cta_img_tents,
) );

/* ==================================================================== */
/* Actividad: White Water Rafting                                        */
/* ==================================================================== */

$rafting_gallery_urls = array(
	'https://rafikisafari.com/wp/wp-content/uploads/2026/02/im2b.jpeg',
	'https://rafikisafari.com/wp/wp-content/uploads/2026/02/IMG_3784-scaled.jpeg',
	'https://rafikisafari.com/wp/wp-content/uploads/2026/02/rafting2-scaled.jpg',
	'https://rafikisafari.com/wp/wp-content/uploads/2026/02/waterfall-scaled.jpg',
	'https://rafikisafari.com/wp/wp-content/uploads/2026/02/IMG_7928-scaled.jpg',
	'https://rafikisafari.com/wp/wp-content/uploads/2016/11/raftingfun.jpg',
);
$rafting_gallery = array();
foreach ( $rafting_gallery_urls as $url ) {
	$id = rafiki_seed_image( $url );
	if ( $id ) $rafting_gallery[] = array( 'imagen' => $id, 'alt' => 'Rafting en el río Savegre, Rafiki Safari Lodge' );
}

$cta_img_rafting = rafiki_seed_image( 'https://rafikisafari.com/wp/wp-content/uploads/2026/02/raffting5-scaled.jpeg' );

rafiki_seed_post( 'actividad', 'White Water Rafting', array(
	'rafiki_subtitulo'       => 'Rafting en el río más limpio de Centroamérica. Rápidos clase II-III, cascadas escondidas y vistas increíbles de la selva tropical — saliendo directo desde el lodge.',
	'rafiki_badges'          => array(
		array( 'texto' => 'Clase II–III' ),
		array( 'texto' => 'Desde $105 / persona' ),
		array( 'texto' => 'Niños 6+' ),
	),
	'rafiki_precio'          => '105',
	'rafiki_precio_unidad'   => '/ persona',
	'rafiki_precio_nota'     => 'Incluye almuerzo e impuestos · transporte aparte',
	'rafiki_datos_rapidos'   => array(
		array( 'label' => 'Dificultad', 'valor' => 'Clase II–III' ),
		array( 'label' => 'Temperatura del agua', 'valor' => '25–27°C' ),
		array( 'label' => 'Edad mínima', 'valor' => '6 años' ),
		array( 'label' => 'Incluye', 'valor' => 'Equipo, guía, almuerzo' ),
		array( 'label' => 'Punto de salida', 'valor' => 'Directo desde el lodge' ),
	),
	'rafiki_intro_eyebrow'   => 'Adventure / Rafting',
	'rafiki_intro_titulo'    => 'EL ÚNICO LODGE EN EL RÍO CON SU PROPIO PUT-IN',
	'rafiki_intro_texto'     => "El río Savegre baja limpio desde la Cordillera de Talamanca, y Rafiki es el único lodge con acceso directo a sus rápidos. Es un recorrido de clase II-III: emocionante para principiantes y familias, con pozas tranquilas entre rápido y rápido para disfrutar del paisaje.\nLa aventura empieza con un manejo en 4x4 de 5km por nuestra reserva privada, con interpretación de flora y fauna en el camino. Después del rafting, caminamos hasta una cascada alimentada por un manantial para nadar y refrescarnos antes de volver al lodge para un almuerzo caliente.\nEl agua se mantiene entre 25-27°C todo el año, con rocas redondeadas y sin obstrucciones — ideal para quienes se sienten cómodos en el agua y están en buena condición física.",
	'rafiki_galeria'         => $rafting_gallery,
	'rafiki_amenidades_titulo' => 'LO QUE INCLUYE',
	'rafiki_amenidades'      => array(
		array( 'icono' => 'guide', 'titulo' => 'Equipo de seguridad', 'texto' => 'Cascos, chalecos salvavidas y remos.' ),
		array( 'icono' => 'shield', 'titulo' => 'Guía profesional', 'texto' => 'Guías locales certificados que crecieron en la zona.' ),
		array( 'icono' => 'truck', 'titulo' => 'Transporte 4x4', 'texto' => '5km por la reserva privada, ida y vuelta.' ),
		array( 'icono' => 'wave', 'titulo' => 'Parada en cascada', 'texto' => 'Nado libre en una cascada de manantial.' ),
		array( 'icono' => 'meal', 'titulo' => 'Almuerzo casero', 'texto' => 'Al volver al lodge, comida típica incluida.' ),
		array( 'icono' => 'tax', 'titulo' => 'Impuestos incluidos', 'texto' => '13% de impuesto de ventas de Costa Rica.' ),
	),
	'rafiki_itinerario'      => array(
		array( 'titulo' => 'Safari en 4x4 por la reserva', 'texto' => '5km de manejo a través de nuestra reserva privada de 600 acres, con interpretación de fauna en el camino.' ),
		array( 'titulo' => 'Charla de seguridad y equipo', 'texto' => 'Nuestros guías reparten cascos, chalecos y remos, y explican las técnicas básicas de remado.' ),
		array( 'titulo' => 'Rápidos clase II–III', 'texto' => 'Bajamos el río Savegre entre rápidos cortos y pozas grandes, rodeados de selva tropical.' ),
		array( 'titulo' => 'Caminata a la cascada', 'texto' => 'Paramos en la Quebrada Arroyo para nadar en una cascada alimentada por manantial.' ),
		array( 'titulo' => 'Snack y últimos rápidos', 'texto' => 'Un snack antes de flotar los rápidos restantes disfrutando del paisaje.' ),
		array( 'titulo' => 'Regreso y almuerzo caliente', 'texto' => 'Volvemos al lodge en 4x4 para un almuerzo casero incluido en el precio.' ),
	),
	'rafiki_paquetes'        => array(
		array( 'etiqueta' => '3 noches · 2 actividades', 'nombre' => 'Rafiki Safari', 'texto' => 'La forma clásica de conocer Rafiki: hospedaje, rafting y una actividad más a elegir.', 'precio' => '375', 'precio_nota' => 'por persona' ),
		array( 'etiqueta' => '3 noches selva + 2 playa', 'nombre' => 'Savegre Adventure', 'texto' => 'Selva y océano en un solo viaje, con 4 actividades incluidas — ideal para familias.', 'precio' => '1,715', 'precio_nota' => 'para 2 personas' ),
		array( 'etiqueta' => '6 noches · todo incluido', 'nombre' => 'Super Lekker Safari', 'texto' => '6 actividades, comidas y traslados desde el aeropuerto de San José incluidos.', 'precio' => '1,567', 'precio_nota' => 'por persona' ),
	),
	'rafiki_testimonio_fuente' => 'tripadvisor',
	'rafiki_testimonio_texto'  => 'El rafting fue una experiencia que nos cambió la vida. Toda la familia lo disfrutó al máximo, incluidos los niños.',
	'rafiki_testimonio_autor'  => 'TaikoM',
	'rafiki_cta_titulo'      => '¿LISTO PARA BAJAR EL RÍO?',
	'rafiki_cta_texto'       => 'Consulta disponibilidad para tu día de rafting o súmalo a un paquete completo.',
	'rafiki_cta_imagen'      => $cta_img_rafting,
) );

WP_CLI::success( 'Contenido de demo sembrado.' );
