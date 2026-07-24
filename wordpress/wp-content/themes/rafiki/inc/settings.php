<?php
/**
 * Configuración General del tema: por ahora solo el número de WhatsApp
 * usado por el botón secundario "Reservar por WhatsApp" en cada
 * alojamiento/actividad, y en los CTAs genéricos del sitio. El link de
 * reserva "principal" en cambio es por-tour (ver meta box rafiki_link_reserva
 * en inc/meta-boxes.php).
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function rafiki_settings_init() {
	register_setting( 'rafiki_settings', 'rafiki_whatsapp_numero', array(
		'type'              => 'string',
		'sanitize_callback' => function ( $value ) {
			return preg_replace( '/[^0-9]/', '', (string) $value );
		},
		'default' => '86829454',
	) );
}
add_action( 'admin_init', 'rafiki_settings_init' );

function rafiki_settings_menu() {
	add_options_page( 'Configuración Rafiki', 'Rafiki', 'manage_options', 'rafiki-settings', 'rafiki_settings_page' );
}
add_action( 'admin_menu', 'rafiki_settings_menu' );

function rafiki_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) return;
	?>
	<div class="wrap">
		<h1>Configuración General — Rafiki</h1>
		<form method="post" action="options.php">
			<?php settings_fields( 'rafiki_settings' ); ?>
			<table class="form-table">
				<tr>
					<th scope="row"><label for="rafiki_whatsapp_numero">Número de WhatsApp</label></th>
					<td>
						<input type="text" id="rafiki_whatsapp_numero" name="rafiki_whatsapp_numero"
							value="<?php echo esc_attr( get_option( 'rafiki_whatsapp_numero', '86829454' ) ); ?>" class="regular-text">
						<p class="description">
							Solo números. Se usa en el botón "Reservar por WhatsApp" de cada alojamiento/actividad
							y en los botones de WhatsApp del resto del sitio. Un número costarricense de 8 dígitos
							recibe automáticamente el código de país (506).
						</p>
					</td>
				</tr>
			</table>
			<?php submit_button( 'Guardar Cambios' ); ?>
		</form>
	</div>
	<?php
}
