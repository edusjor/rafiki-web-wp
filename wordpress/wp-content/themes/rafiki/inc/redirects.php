<?php
/**
 * 301 redirects from the old site, which lived under rafikisafari.com/wp/.
 *
 * Every old URL had the /wp/ prefix and most pages were renamed, so each
 * known old path maps to its new equivalent below. Anything else under
 * /wp/ (e.g. the migrated Journal articles, which kept their slugs) falls
 * through to the same path without the prefix.
 *
 * Done in PHP rather than .htaccess so it ships with the theme and works
 * on any server. Runs on `init`, before WordPress resolves the request.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/** Old path (relative to /wp/, no slashes at either end) => new path. */
function rafiki_legacy_redirect_map() {
	return array(
		''                              => '/',
		'https-www-rafikisafari-com-wp' => '/',

		// Stay
		'lodging'                       => '/stay/',
		'main-lodge'                    => '/stay/main-lodge/',
		'luxury-safari-tents'           => '/stay/luxury-safari-tents/',
		'staging/luxury-safari-tents'   => '/stay/luxury-safari-tents/',
		'beach-camp'                    => '/stay/beach-camp/',
		'lekker-bar-and-braai'          => '/stay/lekker-bar-and-braai/',
		'rates'                         => '/stay/',
		'book-now'                      => '/stay/',

		// Experiences
		'activities'                    => '/experiences/',
		'staging/activities'            => '/experiences/',
		'rafting'                       => '/experiences/white-water-rafting/',
		'horseback-riding'              => '/experiences/horseback-riding/',
		'hiking'                        => '/experiences/hiking/',
		'birding'                       => '/experiences/birding/',
		'kayaking'                      => '/experiences/kayaking/',
		'fishing'                       => '/experiences/fishing/',
		'massage'                       => '/experiences/massage/',

		// Packages
		'packages'                      => '/packages/',
		'staging/packages'              => '/packages/',
		'packages/safarito-package'     => '/packages/safarito/',
		'safarito-package'              => '/packages/safarito/',
		'packages/rafiki-safari'        => '/packages/rafiki-safari/',
		'rafiki-safari'                 => '/packages/rafiki-safari/',
		'packages/savegreadventure'     => '/packages/savegre-adventure/',
		'packages/superlekker'          => '/packages/super-lekker-safari/',
		'super-lekker'                  => '/packages/super-lekker-safari/',
		'specials'                      => '/packages/',

		// About / mission / planning
		'about-us'                      => '/why-rafiki/',
		'ecological_mission'            => '/ecological-mission/',
		'our-footprint'                 => '/ecological-mission/',
		'savegre-biosphere-reserve'     => '/ecological-mission/',
		'groups-and-private-venues'     => '/bring-your-group/',
		'travel-guide'                  => '/plan-your-trip/',
		'contact'                       => '/contact/',

		// News listing pages (the articles themselves keep their slugs)
		'news'                          => '/rafiki-journal/',
		'category/news'                 => '/rafiki-journal/',
		'2612-2'                        => '/a-visit-from-costa-rica-vacations/',
	);
}

function rafiki_legacy_redirects() {
	if ( is_admin() || wp_doing_ajax() || ( defined( 'WP_CLI' ) && WP_CLI ) ) return;

	$path = wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '', PHP_URL_PATH );
	if ( ! $path || ! preg_match( '#^/wp(/.*)?$#', $path, $m ) ) return;

	$rest = trim( isset( $m[1] ) ? $m[1] : '', '/' );
	$map  = rafiki_legacy_redirect_map();

	if ( isset( $map[ $rest ] ) ) {
		$target = $map[ $rest ];
	} else {
		// Unknown old URL: same path without the /wp/ prefix.
		$target = '/' . $rest . ( '' === $rest || pathinfo( $rest, PATHINFO_EXTENSION ) ? '' : '/' );
	}

	wp_redirect( home_url( $target ), 301, 'Rafiki legacy redirect' );
	exit;
}
add_action( 'init', 'rafiki_legacy_redirects', 0 );
