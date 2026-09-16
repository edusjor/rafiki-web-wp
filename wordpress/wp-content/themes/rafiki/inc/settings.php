<?php
/**
 * General theme settings: the WhatsApp number used by the secondary
 * "Book via WhatsApp" button on every accommodation/activity/package
 * and by the generic WhatsApp CTAs across the site (the "primary"
 * booking link, by contrast, is set per-tour — see the
 * rafiki_booking_link meta box in inc/meta-boxes.php) — plus the
 * online booking toggle and bank/SINPE instructions used by
 * inc/booking.php when it's on.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function rafiki_settings_init() {
	register_setting( 'rafiki_settings', 'rafiki_whatsapp_number', array(
		'type'              => 'string',
		'sanitize_callback' => function ( $value ) {
			return preg_replace( '/[^0-9]/', '', (string) $value );
		},
		'default' => '50683689944',
	) );

	register_setting( 'rafiki_settings', 'rafiki_beds24_url', array(
		'type'              => 'string',
		'sanitize_callback' => 'esc_url_raw',
		'default'           => 'https://www.beds24.com/booking.php?propid=7859',
	) );

	register_setting( 'rafiki_settings', 'rafiki_booking_enabled', array(
		'type'              => 'boolean',
		'sanitize_callback' => function ( $value ) {
			return $value ? '1' : '0';
		},
		'default' => '0',
	) );

	register_setting( 'rafiki_settings', 'rafiki_group_inquiry_email', array(
		'type'              => 'string',
		'sanitize_callback' => 'sanitize_email',
		'default'           => '',
	) );

	foreach ( array( 'rafiki_bank_account_holder', 'rafiki_bank_name', 'rafiki_bank_account_number', 'rafiki_sinpe_phone' ) as $option ) {
		register_setting( 'rafiki_settings', $option, array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => '',
		) );
	}

	register_setting( 'rafiki_settings', 'rafiki_bank_instructions', array(
		'type'              => 'string',
		'sanitize_callback' => 'sanitize_textarea_field',
		'default'           => '',
	) );
}
add_action( 'admin_init', 'rafiki_settings_init' );

/** Whether the online booking system (WooCommerce checkout, on-site payment) is turned on. */
function rafiki_booking_enabled() {
	return '1' === get_option( 'rafiki_booking_enabled', '0' );
}

/**
 * Site-wide booking URL (Beds24). This is the default target for every
 * "Book Now" button on accommodations, packages and activities. A post can
 * override it with its own "Booking Link" field — see rafiki_booking_link().
 */
function rafiki_beds24_url() {
	$url = trim( (string) get_option( 'rafiki_beds24_url', '' ) );
	return $url ? $url : 'https://www.beds24.com/booking.php?propid=7859';
}

function rafiki_settings_menu() {
	add_options_page( 'Rafiki Settings', 'Rafiki', 'manage_options', 'rafiki-settings', 'rafiki_settings_page' );
}
add_action( 'admin_menu', 'rafiki_settings_menu' );

function rafiki_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) return;
	?>
	<div class="wrap">
		<h1>General Settings — Rafiki</h1>
		<form method="post" action="options.php">
			<?php settings_fields( 'rafiki_settings' ); ?>
			<table class="form-table">
				<tr>
					<th scope="row"><label for="rafiki_whatsapp_number">WhatsApp Number</label></th>
					<td>
						<input type="text" id="rafiki_whatsapp_number" name="rafiki_whatsapp_number"
							value="<?php echo esc_attr( get_option( 'rafiki_whatsapp_number', '50683689944' ) ); ?>" class="regular-text">
						<p class="description">
							Numbers only. Used by the "Book via WhatsApp" button on every accommodation/activity/package
							and by the WhatsApp buttons elsewhere on the site. An 8-digit Costa Rican number automatically
							gets the country code (506) added.
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="rafiki_beds24_url">Booking Link (Beds24)</label></th>
					<td>
						<input type="url" id="rafiki_beds24_url" name="rafiki_beds24_url"
							value="<?php echo esc_attr( get_option( 'rafiki_beds24_url', 'https://www.beds24.com/booking.php?propid=7859' ) ); ?>" class="regular-text" placeholder="https://www.beds24.com/booking.php?propid=7859">
						<p class="description">
							Where every "Book Now" button goes (accommodations, packages and activities). A single
							accommodation or package can point somewhere else by filling its own "Booking Link" field
							in the "Page Content" box; if that field is empty it uses this link.
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="rafiki_group_inquiry_email">Group &amp; Retreat Inquiries Email</label></th>
					<td>
						<input type="email" id="rafiki_group_inquiry_email" name="rafiki_group_inquiry_email"
							value="<?php echo esc_attr( get_option( 'rafiki_group_inquiry_email', '' ) ); ?>" class="regular-text" placeholder="<?php echo esc_attr( get_option( 'admin_email' ) ); ?>">
						<p class="description">
							Where submissions from the "Bring Your Group" page form are sent. Leave blank to use the site's
							admin email (<?php echo esc_html( get_option( 'admin_email' ) ); ?>).
						</p>
					</td>
				</tr>
			</table>

			<h2>Online Booking &amp; Payments</h2>
			<table class="form-table">
				<tr>
					<th scope="row"><label for="rafiki_booking_enabled">Enable Online Booking</label></th>
					<td>
						<label>
							<input type="checkbox" id="rafiki_booking_enabled" name="rafiki_booking_enabled" value="1" <?php checked( rafiki_booking_enabled() ); ?>>
							Let customers pick a date and pay online (card, bank transfer, SINPE Móvil)
						</label>
						<p class="description">
							Requires WooCommerce to be installed and active — see <code>wordpress/PLUGINS.md</code>.
							When off (or WooCommerce isn't active), every tour just shows its external "Check Availability"
							link and the WhatsApp button, exactly as before.
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="rafiki_bank_account_holder">Bank Account Holder</label></th>
					<td><input type="text" id="rafiki_bank_account_holder" name="rafiki_bank_account_holder" value="<?php echo esc_attr( get_option( 'rafiki_bank_account_holder', '' ) ); ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th scope="row"><label for="rafiki_bank_name">Bank Name</label></th>
					<td><input type="text" id="rafiki_bank_name" name="rafiki_bank_name" value="<?php echo esc_attr( get_option( 'rafiki_bank_name', '' ) ); ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th scope="row"><label for="rafiki_bank_account_number">Account / IBAN Number</label></th>
					<td><input type="text" id="rafiki_bank_account_number" name="rafiki_bank_account_number" value="<?php echo esc_attr( get_option( 'rafiki_bank_account_number', '' ) ); ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th scope="row"><label for="rafiki_sinpe_phone">SINPE Móvil Number</label></th>
					<td><input type="text" id="rafiki_sinpe_phone" name="rafiki_sinpe_phone" value="<?php echo esc_attr( get_option( 'rafiki_sinpe_phone', '' ) ); ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th scope="row"><label for="rafiki_bank_instructions">Extra Instructions</label></th>
					<td>
						<textarea id="rafiki_bank_instructions" name="rafiki_bank_instructions" rows="4" class="large-text"><?php echo esc_textarea( get_option( 'rafiki_bank_instructions', '' ) ); ?></textarea>
						<p class="description">Shown at checkout under "Bank Transfer / SINPE Móvil" along with the fields above (e.g. SWIFT code, notes on how long confirmation takes).</p>
					</td>
				</tr>
			</table>
			<?php submit_button( 'Save Changes' ); ?>
		</form>
	</div>
	<?php
}
