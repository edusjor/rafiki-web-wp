<?php
/**
 * Hand-rolled meta boxes for "accommodation" / "activity" / "package".
 *
 * No ACF / paid plugin dependency: scalar fields save as single postmeta
 * keys, and repeatable fields (badges, quick facts, amenities, gallery,
 * room options, rates, itinerary, included packages, group pricing) go
 * through one generic repeater engine (rafiki_render_repeater /
 * rafiki_save_meta) driven by a small column schema, so every "list of
 * rows" field in the theme shares the same admin UI and save/sanitize
 * logic.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function rafiki_post_types() {
	return array( 'accommodation', 'activity', 'package' );
}

/* ------------------------------------------------------------------ */
/* Field schemas                                                       */
/* ------------------------------------------------------------------ */

function rafiki_repeater_schemas() {
	return array(
		'rafiki_badges' => array(
			'label'   => 'Badges (under the hero title)',
			'columns' => array(
				array( 'key' => 'text', 'label' => 'Text', 'type' => 'text' ),
			),
		),
		'rafiki_quick_facts' => array(
			'label'   => 'Quick Facts (sidebar card)',
			'columns' => array(
				array( 'key' => 'label', 'label' => 'Label', 'type' => 'text' ),
				array( 'key' => 'value', 'label' => 'Value', 'type' => 'text' ),
			),
		),
		'rafiki_amenities' => array(
			'label'   => 'Amenities / What\'s Included',
			'columns' => array(
				array( 'key' => 'icon', 'label' => 'Icon', 'type' => 'select', 'options' => 'icons' ),
				array( 'key' => 'title', 'label' => 'Title', 'type' => 'text' ),
				array( 'key' => 'text', 'label' => 'Text', 'type' => 'text' ),
			),
		),
		'rafiki_gallery' => array(
			'label'   => 'Photo Gallery',
			'columns' => array(
				array( 'key' => 'image', 'label' => 'Image', 'type' => 'image' ),
				array( 'key' => 'alt', 'label' => 'Description (alt text)', 'type' => 'text' ),
			),
		),
		'rafiki_room_options' => array(
			'label'      => 'Room Options',
			'post_types' => array( 'accommodation' ),
			'columns'    => array(
				array( 'key' => 'image', 'label' => 'Image', 'type' => 'image' ),
				array( 'key' => 'title', 'label' => 'Title', 'type' => 'text' ),
				array( 'key' => 'text', 'label' => 'Text', 'type' => 'textarea' ),
			),
		),
		'rafiki_rates' => array(
			'label'      => 'Rates Table',
			'post_types' => array( 'accommodation' ),
			'columns'    => array(
				array( 'key' => 'season', 'label' => 'Season', 'type' => 'text' ),
				array( 'key' => 'double', 'label' => 'Double Occupancy', 'type' => 'text' ),
				array( 'key' => 'extra', 'label' => 'Extra Person', 'type' => 'text' ),
				array( 'key' => 'upgrade', 'label' => 'Upgrade', 'type' => 'text' ),
			),
		),
		'rafiki_itinerary' => array(
			'label'      => 'Itinerary / Day by Day',
			'post_types' => array( 'activity', 'package' ),
			'columns'    => array(
				array( 'key' => 'title', 'label' => 'Step Title', 'type' => 'text' ),
				array( 'key' => 'text', 'label' => 'Description', 'type' => 'textarea' ),
			),
		),
		'rafiki_included_packages' => array(
			'label'      => 'Packages That Include This Activity',
			'post_types' => array( 'activity' ),
			'columns'    => array(
				array( 'key' => 'tag', 'label' => 'Tag (e.g. "3 nights")', 'type' => 'text' ),
				array( 'key' => 'name', 'label' => 'Package Name', 'type' => 'text' ),
				array( 'key' => 'text', 'label' => 'Description', 'type' => 'textarea' ),
				array( 'key' => 'price', 'label' => 'Price', 'type' => 'text' ),
				array( 'key' => 'price_note', 'label' => 'Price Note', 'type' => 'text' ),
			),
		),
		'rafiki_group_pricing' => array(
			'label'      => 'Pricing by Group Size',
			'post_types' => array( 'package' ),
			'columns'    => array(
				array( 'key' => 'guests', 'label' => 'Group Size (e.g. "2 people")', 'type' => 'text' ),
				array( 'key' => 'green', 'label' => 'Green Season', 'type' => 'text' ),
				array( 'key' => 'high', 'label' => 'High Season', 'type' => 'text' ),
			),
		),
		'rafiki_blocked_dates' => array(
			'label'   => 'Blocked Dates (Online Booking) — dates guests can\'t select. For accommodations, nights already booked by a paid, non-cancelled order are blocked automatically on top of this list.',
			'columns' => array(
				array( 'key' => 'date', 'label' => 'Date', 'type' => 'date' ),
			),
		),
	);
}

function rafiki_scalar_fields() {
	return array(
		array( 'key' => 'rafiki_is_beach_camp', 'label' => 'This is the Beach Camp accommodation (drives the site-wide "Beach Camp" links in the menu and homepage — set this instead of relying on the post title)', 'type' => 'select', 'options' => array( '' => 'No', '1' => 'Yes' ) ),
		array( 'key' => 'rafiki_subtitle', 'label' => 'Hero Subtitle', 'type' => 'textarea' ),
		array( 'key' => 'rafiki_price', 'label' => 'Price', 'type' => 'text' ),
		array( 'key' => 'rafiki_price_unit', 'label' => 'Price Unit (e.g. "/ night")', 'type' => 'text' ),
		array( 'key' => 'rafiki_price_note', 'label' => 'Note Under the Price', 'type' => 'text' ),
		array( 'key' => 'rafiki_booking_link', 'label' => 'Booking Link ("Book Now" button) — leave empty to use the site-wide Beds24 link from Rafiki Settings', 'type' => 'url' ),
		array( 'key' => 'rafiki_price_amount', 'label' => 'Online Booking Price (numeric — nightly rate for accommodations, per-person/per-booking price for activities & packages)', 'type' => 'number' ),
		array( 'key' => 'rafiki_deposit_type', 'label' => 'Payment Type', 'type' => 'select', 'options' => array(
			'full'             => 'Pay in full',
			'fixed_total'      => 'Fixed deposit (total)',
			'fixed_per_person' => 'Fixed deposit (per person)',
			'percent'          => 'Percentage deposit',
		) ),
		array( 'key' => 'rafiki_deposit_amount', 'label' => 'Deposit Amount ($, used for "Fixed deposit" options)', 'type' => 'number' ),
		array( 'key' => 'rafiki_deposit_percent', 'label' => 'Deposit Percent (%, used for "Percentage deposit")', 'type' => 'percent' ),
		array( 'key' => 'rafiki_intro_eyebrow', 'label' => 'Intro Eyebrow (e.g. "Stay / Luxury Tents")', 'type' => 'text' ),
		array( 'key' => 'rafiki_intro_title', 'label' => 'Intro Title', 'type' => 'text' ),
		array( 'key' => 'rafiki_intro_text', 'label' => 'Intro Text (one paragraph per line)', 'type' => 'textarea_big' ),
		array( 'key' => 'rafiki_amenities_title', 'label' => 'Amenities Section Title', 'type' => 'text' ),
		array( 'key' => 'rafiki_testimonial_source', 'label' => 'Testimonial Source', 'type' => 'select', 'options' => array( 'google' => 'Google', 'tripadvisor' => 'TripAdvisor', 'facebook' => 'Facebook' ) ),
		array( 'key' => 'rafiki_testimonial_text', 'label' => 'Testimonial Text', 'type' => 'textarea' ),
		array( 'key' => 'rafiki_testimonial_author', 'label' => 'Testimonial Author', 'type' => 'text' ),
		array( 'key' => 'rafiki_cta_title', 'label' => 'Closing Banner Title', 'type' => 'text' ),
		array( 'key' => 'rafiki_cta_text', 'label' => 'Closing Banner Text', 'type' => 'text' ),
		array( 'key' => 'rafiki_cta_image', 'label' => 'Closing Banner Image', 'type' => 'image' ),
	);
}

/* ------------------------------------------------------------------ */
/* Register meta boxes                                                 */
/* ------------------------------------------------------------------ */

function rafiki_add_meta_boxes() {
	foreach ( rafiki_post_types() as $post_type ) {
		add_meta_box( 'rafiki_scalar', 'Page Content', 'rafiki_render_scalar_box', $post_type, 'normal', 'high' );
	}
	foreach ( rafiki_repeater_schemas() as $meta_key => $schema ) {
		$post_types = isset( $schema['post_types'] ) ? $schema['post_types'] : rafiki_post_types();
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
		case 'number':
			echo '<input type="number" step="0.01" min="0" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" class="widefat">';
			break;
		case 'percent':
			echo '<input type="number" step="1" min="0" max="100" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" class="widefat">';
			break;
		case 'date':
			echo '<input type="date" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '">';
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
			echo '<button type="button" class="button rafiki-image-select">Choose Image</button> ';
			echo '<button type="button" class="button rafiki-image-clear"' . ( $image_url ? '' : ' style="display:none;"' ) . '>Remove</button>';
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

	echo '<p><button type="button" class="button button-primary rafiki-repeater-add">+ Add Row</button></p>';
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
	echo '<button type="button" class="button-link-delete rafiki-repeater-remove">Remove Row</button>';
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
	if ( ! in_array( $post_type, rafiki_post_types(), true ) ) return;

	foreach ( rafiki_scalar_fields() as $field ) {
		$raw = isset( $_POST[ $field['key'] ] ) ? wp_unslash( $_POST[ $field['key'] ] ) : '';
		$clean = rafiki_sanitize_value( $raw, $field['type'] );
		update_post_meta( $post_id, $field['key'], $clean );
	}

	foreach ( rafiki_repeater_schemas() as $meta_key => $schema ) {
		$post_types = isset( $schema['post_types'] ) ? $schema['post_types'] : rafiki_post_types();
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
		case 'number':
			return is_numeric( $raw ) ? (string) round( (float) $raw, 2 ) : '';
		case 'percent':
			return is_numeric( $raw ) ? (string) max( 0, min( 100, round( (float) $raw ) ) ) : '';
		case 'date':
			$d = DateTime::createFromFormat( 'Y-m-d', (string) $raw );
			return ( $d && $d->format( 'Y-m-d' ) === $raw ) ? $raw : '';
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
	if ( ! in_array( $post_type, rafiki_post_types(), true ) ) return;
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) return;
	wp_enqueue_media();
	wp_enqueue_style( 'rafiki-admin', get_template_directory_uri() . '/assets/css/admin.css', array(), '1.0' );
	wp_enqueue_script( 'rafiki-admin', get_template_directory_uri() . '/assets/js/admin-repeater.js', array( 'jquery' ), '1.0', true );
}
add_action( 'admin_enqueue_scripts', 'rafiki_admin_assets' );
