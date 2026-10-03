<?php
/**
 * Small inline-SVG icon set shared by the front-end templates and the
 * admin "amenidades" repeater's icon picker, so both stay in sync.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function rafiki_icon_choices() {
	return array(
		'leaf'     => 'Leaf (generic)',
		'tent'     => 'Tent',
		'wood'     => 'Wood platform',
		'porch'    => 'Porch',
		'bolt'     => 'Electricity',
		'bath'     => 'Bathroom',
		'heart'    => 'Heart',
		'shield'   => 'Safety',
		'guide'    => 'Guide / compass',
		'truck'    => '4x4 / transport',
		'wave'     => 'Water / waterfall',
		'meal'     => 'Meal',
		'tax'      => 'Taxes / price',
		'mountain' => 'Mountain / hike',
		'family'   => 'Family',
		'check'    => 'Checkmark',
	);
}

function rafiki_icon( $key = 'leaf' ) {
	$paths = array(
		'tent'     => '<path d="M24 6 L42 40 H6 Z" fill="none" stroke="currentColor" stroke-width="2"/>',
		'wood'     => '<rect x="6" y="20" width="36" height="6" fill="none" stroke="currentColor" stroke-width="2"/><path d="M10 26 L10 40 M38 26 L38 40" stroke="currentColor" stroke-width="2"/>',
		'porch'    => '<rect x="8" y="8" width="32" height="32" rx="4" fill="none" stroke="currentColor" stroke-width="2"/><path d="M8 30 L40 30" stroke="currentColor" stroke-width="2"/>',
		'bolt'     => '<circle cx="24" cy="24" r="18" fill="none" stroke="currentColor" stroke-width="2"/><path d="M24 6 L24 24 L34 30" stroke="currentColor" stroke-width="2"/>',
		'bath'     => '<rect x="10" y="6" width="28" height="36" rx="3" fill="none" stroke="currentColor" stroke-width="2"/><path d="M16 16 h16 M16 24 h16 M16 32 h10" stroke="currentColor" stroke-width="2"/>',
		'heart'    => '<path d="M24 42 C10 32 4 22 4 15 C4 8 10 4 16 4 C20 4 23 6 24 10 C25 6 28 4 32 4 C38 4 44 8 44 15 C44 22 38 32 24 42 Z" fill="none" stroke="currentColor" stroke-width="2"/>',
		'shield'   => '<path d="M24 4 C34 4 42 12 42 22 C42 34 24 44 24 44 C24 44 6 34 6 22 C6 12 14 4 24 4 Z" fill="none" stroke="currentColor" stroke-width="2"/>',
		'guide'    => '<circle cx="24" cy="24" r="20" fill="none" stroke="currentColor" stroke-width="2"/><path d="M24 4 L28 22 L24 44 L20 22 Z" fill="none" stroke="currentColor" stroke-width="2"/>',
		'truck'    => '<path d="M6 30 L24 8 L42 30 Z" fill="none" stroke="currentColor" stroke-width="2"/><path d="M6 30 h36" stroke="currentColor" stroke-width="2"/>',
		'wave'     => '<path d="M4 30 Q12 22 20 30 T36 30 T44 30" stroke="currentColor" stroke-width="2" fill="none"/><path d="M4 38 Q12 30 20 38 T36 38 T44 38" stroke="currentColor" stroke-width="2" fill="none"/>',
		'meal'     => '<rect x="8" y="16" width="32" height="20" rx="2" fill="none" stroke="currentColor" stroke-width="2"/><path d="M16 16 V10 h16 v6" stroke="currentColor" stroke-width="2" fill="none"/>',
		'tax'      => '<circle cx="24" cy="24" r="20" fill="none" stroke="currentColor" stroke-width="2"/><path d="M14 24 h20 M24 14 v20" stroke="currentColor" stroke-width="2"/>',
		'mountain' => '<path d="M4 38 L18 14 L26 26 L32 16 L44 38 Z" fill="none" stroke="currentColor" stroke-width="2"/>',
		'family'   => '<circle cx="14" cy="16" r="5" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="34" cy="16" r="5" fill="none" stroke="currentColor" stroke-width="2"/><path d="M4 40 C4 30 9 25 14 25 C19 25 24 30 24 40" fill="none" stroke="currentColor" stroke-width="2"/><path d="M24 40 C24 30 29 25 34 25 C39 25 44 30 44 40" fill="none" stroke="currentColor" stroke-width="2"/>',
		'leaf'     => '<path d="M24 4 C30 14 36 20 36 28 C36 36.8 30.8 42 24 42 C17.2 42 12 36.8 12 28 C12 20 18 14 24 4 Z" fill="none" stroke="currentColor" stroke-width="2"/>',
		'check'    => '<path d="M8 25 L19 36 L40 12" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>',
		'phone'    => '<rect x="14" y="5" width="20" height="38" rx="4" fill="none" stroke="currentColor" stroke-width="2"/><path d="M21 37 h6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
		'mail'     => '<rect x="6" y="11" width="36" height="26" rx="3" fill="none" stroke="currentColor" stroke-width="2"/><path d="M7 13 L24 26 L41 13" fill="none" stroke="currentColor" stroke-width="2"/>',
		'pin'      => '<path d="M24 43 C24 43 10 28 10 19 A14 14 0 0 1 38 19 C38 28 24 43 24 43 Z" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="24" cy="19" r="5" fill="none" stroke="currentColor" stroke-width="2"/>',
	);
	$path = isset( $paths[ $key ] ) ? $paths[ $key ] : $paths['leaf'];
	return '<svg viewBox="0 0 48 48" aria-hidden="true">' . $path . '</svg>';
}
