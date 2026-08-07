<?php
/**
 * Seeds real Rafiki Safari Lodge content (pulled from rafikisafari.com)
 * as Accommodation / Activity / Package posts, with real sideloaded
 * media. Safe to re-run — skips an image/post if one with the same
 * source URL / title already exists. Also removes the old demo posts
 * that were created under the retired Spanish post type slugs
 * ('alojamiento' / 'actividad') before they existed as English CPTs.
 *
 * Usage: wp eval-file seed/seed-demo-content.php
 */

require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

/* ==================================================================== */
/* Clean up posts left over from the old Spanish CPT slugs               */
/* ==================================================================== */

$orphans = get_posts( array(
	'post_type'      => array( 'alojamiento', 'actividad' ),
	'posts_per_page' => -1,
	'post_status'    => 'any',
	'fields'         => 'ids',
) );
foreach ( $orphans as $orphan_id ) {
	wp_delete_post( $orphan_id, true );
	WP_CLI::log( "Deleted legacy post #$orphan_id" );
}

/* ==================================================================== */
/* Helpers                                                                */
/* ==================================================================== */

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
		WP_CLI::warning( 'Could not import ' . $url . ': ' . $id->get_error_message() );
		return 0;
	}
	update_post_meta( $id, '_rafiki_seed_source', $url );
	$cache[ $url ] = $id;
	WP_CLI::log( 'Imported: ' . $url . ' -> attachment #' . $id );
	return $id;
}

function rafiki_gallery_from_urls( $urls, $alt ) {
	$rows = array();
	foreach ( $urls as $url ) {
		$id = rafiki_seed_image( $url );
		if ( $id ) $rows[] = array( 'image' => $id, 'alt' => $alt );
	}
	return $rows;
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
		WP_CLI::log( "Already exists \"$title\" (#$post_id), updating fields." );
	} else {
		$post_id = wp_insert_post( array(
			'post_type'   => $post_type,
			'post_title'  => $title,
			'post_status' => 'publish',
		), true );
		if ( is_wp_error( $post_id ) ) {
			WP_CLI::error( "Could not create \"$title\": " . $post_id->get_error_message() );
			return 0;
		}
		WP_CLI::success( "Created \"$title\" (#$post_id)." );
	}

	foreach ( $meta as $key => $value ) {
		update_post_meta( $post_id, $key, $value );
	}

	$gallery = get_post_meta( $post_id, 'rafiki_gallery', true );
	if ( ! empty( $gallery[0]['image'] ) ) {
		set_post_thumbnail( $post_id, $gallery[0]['image'] );
	}

	return $post_id;
}

/* ==================================================================== */
/* ACCOMMODATIONS                                                        */
/* ==================================================================== */

$tents_id = rafiki_seed_post( 'accommodation', 'Luxury Safari Tents', array(
	'rafiki_subtitle'     => '"The experience of camping without having to rough it." Tents imported from South Africa on raised wooden platforms, right in the jungle.',
	'rafiki_badges'       => array(
		array( 'text' => 'Sleeps 4–5' ),
		array( 'text' => 'Private porch' ),
		array( 'text' => 'Private bathroom' ),
	),
	'rafiki_price'        => '198',
	'rafiki_price_unit'   => '/ night',
	'rafiki_price_note'   => 'Green season · double occupancy',
	'rafiki_quick_facts'  => array(
		array( 'label' => 'Capacity', 'value' => '4–5 people' ),
		array( 'label' => 'Beds', 'value' => '2 singles + 1 double' ),
		array( 'label' => 'Bathroom', 'value' => 'Private, tiled' ),
		array( 'label' => 'View', 'value' => 'Forest / some lakeview' ),
		array( 'label' => 'Electricity', 'value' => 'Hydro-electric, 24h' ),
		array( 'label' => 'Extra person', 'value' => '$25 / night' ),
	),
	'rafiki_intro_eyebrow' => 'Stay / Luxury Safari Tents',
	'rafiki_intro_title'   => 'CAMPING, WITHOUT GIVING UP ANYTHING',
	'rafiki_intro_text'    => "Our 14 safari-style tents combine the romance of sleeping in the wild with the comforts of a modern hotel room. Each one sits on a raised wooden platform surrounded by forest, with its own view and the sounds of nature as the soundtrack.\nAfter a day of adventure, you come back to a spacious tent with fan and electricity, a private bathroom with full amenities, and a porch with rocking chairs and a coffee table to watch the evening fall over the Savegre Valley.\nStandard tents have two single beds and one double bed. A few special units include bunk beds and can sleep up to 5 people — ideal for families.",
	'rafiki_gallery'       => rafiki_gallery_from_urls( array(
		'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-tents-web_19.jpeg',
		'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-tents-web_1.jpeg',
		'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-tents-web_3.jpeg',
		'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-tents-web_6.jpeg',
		'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-tents-web_9.jpeg',
		'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-tents-web_12.jpeg',
	), 'Luxury safari tent at Rafiki' ),
	'rafiki_amenities_title' => "EVERYTHING YOUR TENT INCLUDES",
	'rafiki_amenities'     => array(
		array( 'icon' => 'tent', 'title' => 'Imported from South Africa', 'text' => 'High-end canvas on a raised structure.' ),
		array( 'icon' => 'wood', 'title' => 'Wooden platform', 'text' => 'Raised off the ground, with a direct forest view.' ),
		array( 'icon' => 'porch', 'title' => 'Private porch', 'text' => 'With rocking chairs and a coffee table facing the jungle.' ),
		array( 'icon' => 'bolt', 'title' => 'Electricity & fan', 'text' => 'Hydro-electric generator, available 24 hours.' ),
		array( 'icon' => 'bath', 'title' => 'Private tiled bathroom', 'text' => 'Shower, hot water and full amenities.' ),
		array( 'icon' => 'heart', 'title' => 'Hotel-grade bedding', 'text' => 'Full comfort after a day of adventure.' ),
	),
	'rafiki_room_options'  => array(
		array( 'image' => rafiki_seed_image( 'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-tents-web.jpeg' ), 'title' => 'Tents 1 & 2 — Lakeview', 'text' => 'The closest tents to the main lodge, with a prime view of the sunset over the valley. Ideal for couples chasing the best sunset on the property.' ),
		array( 'image' => rafiki_seed_image( 'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-property-and-food-web_22.jpeg' ), 'title' => 'Tent 3 — Family', 'text' => 'Sleeps up to 5 thanks to extra bunk beds. Ground-level balcony, perfect for families with young kids.' ),
	),
	'rafiki_rates'         => array(
		array( 'season' => 'High — Dec 16, 2026 to Apr 30, 2027', 'double' => '$265 / night', 'extra' => '$25 / night', 'upgrade' => '$30 / night' ),
		array( 'season' => 'Green — May 1 to Dec 15, 2026', 'double' => '$198 / night', 'extra' => '$25 / night', 'upgrade' => '$30 / night' ),
	),
	'rafiki_testimonial_source' => 'tripadvisor',
	'rafiki_testimonial_text'   => 'Lovely cabins, excellent food. The tents exceeded our expectations — we never thought camping could feel this comfortable.',
	'rafiki_testimonial_author' => 'TaikoM',
	'rafiki_cta_title'    => 'READY TO SLEEP IN THE JUNGLE?',
	'rafiki_cta_text'     => 'Check availability for your safari tent and build your ideal package.',
	'rafiki_cta_image'    => rafiki_seed_image( 'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-tents-web_8.jpeg' ),
) );

$lodge_id = rafiki_seed_post( 'accommodation', 'Main Lodge', array(
	'rafiki_subtitle'     => 'A traditional Costa Rican "rancho" with an African twist — the base camp and starting point of every adventure.',
	'rafiki_badges'       => array(
		array( 'text' => 'Open-air rancho' ),
		array( 'text' => 'Valley views' ),
		array( 'text' => 'Base camp' ),
	),
	'rafiki_quick_facts'  => array(
		array( 'label' => 'Type', 'value' => 'Central open-air rancho' ),
		array( 'label' => 'Function', 'value' => 'Base camp & gathering space' ),
		array( 'label' => 'Views', 'value' => 'Savegre Valley' ),
		array( 'label' => 'Best for', 'value' => 'Sunsets & birding' ),
	),
	'rafiki_intro_eyebrow' => 'Stay / Main Lodge',
	'rafiki_intro_title'   => 'WHERE THE ADVENTURE BEGINS AND ENDS',
	'rafiki_intro_text'    => "The Main Lodge is the heart of Rafiki: a traditional rancho with an African twist, built to catch every bit of the Savegre Valley's energy. Guests say the weight of the world slips off the moment they step onto its tiled floor.\nIt's a central gathering space between activities — reception, gift store, bar and restaurant, library, and a porch with a bird feeder where the jungle comes to you.\nPositioned right on the edge of the plateau, it's also one of the best spots on the property for sunset views and birdwatching, with rocking chairs, hammocks, a hot tub and a water slide for the kids.",
	'rafiki_gallery'       => rafiki_gallery_from_urls( array(
		'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-property-and-food-web_18.jpeg',
		'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-property-and-food-web_14a.jpg',
		'https://rafikisafari.com/wp/wp-content/uploads/2026/02/IMG_1448-scaled.jpeg',
		'https://rafikisafari.com/wp/wp-content/uploads/2026/02/IMG_9905-scaled.jpg',
		'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-property-and-food-web_12.jpeg',
		'https://rafikisafari.com/wp/wp-content/uploads/2026/02/IMG_6113.jpg',
	), 'Main Lodge at Rafiki Safari Lodge' ),
	'rafiki_amenities_title' => 'WHAT YOU\'LL FIND AT THE LODGE',
	'rafiki_amenities'     => array(
		array( 'icon' => 'meal', 'title' => 'Bar & restaurant', 'text' => 'Home-cooked meals and tropical cocktails.' ),
		array( 'icon' => 'wave', 'title' => 'Hot tub & water slide', 'text' => 'A favorite with kids and adults alike.' ),
		array( 'icon' => 'porch', 'title' => 'Bird-feeder porch', 'text' => 'Rocking chairs and hammocks over the valley.' ),
		array( 'icon' => 'leaf', 'title' => 'Library & board games', 'text' => 'A relaxed spot between adventures.' ),
		array( 'icon' => 'mountain', 'title' => 'Prime birding location', 'text' => 'Right on the edge of the plateau.' ),
		array( 'icon' => 'guide', 'title' => 'Reception & gift store', 'text' => 'Everything you need, all in one place.' ),
	),
	'rafiki_testimonial_source' => 'google',
	'rafiki_testimonial_text'   => 'A beautiful, African-inspired lodge for the bird- and wildlife lover. The guides are incredible and the whole place just knocked our socks off.',
	'rafiki_testimonial_author' => 'Yooperchick',
	'rafiki_cta_title'    => 'READY TO EXPERIENCE THE LODGE?',
	'rafiki_cta_text'     => 'Check availability and start planning your stay at Rafiki.',
	'rafiki_cta_image'    => rafiki_seed_image( 'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-property-and-food-web_19.jpeg' ),
) );

$beach_id = rafiki_seed_post( 'accommodation', 'Beach Camp', array(
	'rafiki_is_beach_camp' => '1',
	'rafiki_subtitle'     => 'A perfect blend of adventure and relaxation. Sloths, monkeys and surf on Playa Matapalo.',
	'rafiki_badges'       => array(
		array( 'text' => '4 beach tents' ),
		array( 'text' => '45 min from the lodge' ),
		array( 'text' => 'Pool on the beach' ),
	),
	'rafiki_price_note'   => 'Bed & breakfast · see Packages for rates',
	'rafiki_quick_facts'  => array(
		array( 'label' => 'Location', 'value' => 'Playa Matapalo' ),
		array( 'label' => 'Distance from lodge', 'value' => '45 minutes' ),
		array( 'label' => 'Tents', 'value' => '4 luxury safari tents' ),
		array( 'label' => 'Meals', 'value' => 'Breakfast included (kitchen access for the rest)' ),
	),
	'rafiki_intro_eyebrow' => 'Stay / Beach Camp',
	'rafiki_intro_title'   => 'JUNGLE IN THE MORNING, OCEAN BY THE AFTERNOON',
	'rafiki_intro_text'    => "Rafiki Beach Camp sits right on Playa Matapalo, 45 minutes from the main lodge, with four luxury safari tents wrapped around a swimming pool just steps from the sand.\nExpect hammocks under the palms, sloths and monkeys overhead, and dolphins or pelicans offshore. Guests get bed and breakfast, with kitchen facilities available and plenty of nearby dining options for the rest of the day.\nIt's also the launch point for the sea kayak route through the mangroves and the Savegre River estuary, and a short drive from Manuel Antonio National Park, Marino Ballena National Park and Hacienda Barú.",
	'rafiki_gallery'       => rafiki_gallery_from_urls( array(
		'https://rafikisafari.com/wp/wp-content/uploads/2016/12/sunsetbeach.jpg',
		'https://rafikisafari.com/wp/wp-content/uploads/2016/12/resident-sloth.jpg',
		'https://rafikisafari.com/wp/wp-content/uploads/2016/12/palmtreeswide.jpg',
		'https://rafikisafari.com/wp/wp-content/uploads/2016/12/beach-pool.jpg',
		'https://rafikisafari.com/wp/wp-content/uploads/2016/12/kayak-trip-small.jpg',
		'https://rafikisafari.com/wp/wp-content/uploads/2026/03/IMG_8992-scaled.jpg',
	), 'Rafiki Beach Camp at Playa Matapalo' ),
	'rafiki_amenities_title' => 'WHAT\'S WAITING AT THE BEACH',
	'rafiki_amenities'     => array(
		array( 'icon' => 'tent', 'title' => '4 luxury safari tents', 'text' => 'Steps from the sand, wrapped around the pool.' ),
		array( 'icon' => 'wave', 'title' => 'Pool on the beach', 'text' => 'Cool off without leaving the sand.' ),
		array( 'icon' => 'heart', 'title' => 'Hammocks under the palms', 'text' => 'The definition of doing nothing, well.' ),
		array( 'icon' => 'guide', 'title' => 'Mangrove kayak tours', 'text' => 'Launch straight from camp.' ),
		array( 'icon' => 'leaf', 'title' => 'Wildlife at your doorstep', 'text' => 'Sloths, monkeys, dolphins and pelicans.' ),
		array( 'icon' => 'meal', 'title' => 'Guest kitchen access', 'text' => 'Plus nearby dining options nearby.' ),
	),
	'rafiki_testimonial_source' => 'facebook',
	'rafiki_testimonial_text'   => 'The beauty of Rafiki alone is worth the visit — and finishing the trip at the Beach Camp was the perfect way to end it.',
	'rafiki_testimonial_author' => 'Frank T.',
	'rafiki_cta_title'    => 'READY FOR JUNGLE AND OCEAN?',
	'rafiki_cta_text'     => 'Ask us about combining Beach Camp with your lodge stay.',
	'rafiki_cta_image'    => rafiki_seed_image( 'https://rafikisafari.com/wp/wp-content/uploads/2016/12/beach-pool.jpg' ),
) );

$braai_id = rafiki_seed_post( 'accommodation', 'Lekker Bar and Braai', array(
	'rafiki_subtitle'     => 'Enjoy delicious home-cooked meals while we keep your spirits up at the Lekker Bar — the centerpiece of the Main Lodge.',
	'rafiki_badges'       => array(
		array( 'text' => 'Horseshoe bar' ),
		array( 'text' => 'South African braai' ),
		array( 'text' => '3 meals daily' ),
	),
	'rafiki_price_note'   => 'Included with your stay — no separate booking needed',
	'rafiki_quick_facts'  => array(
		array( 'label' => 'Breakfast', 'value' => '7:00–9:30am (coffee/tea from 6:15am)' ),
		array( 'label' => 'Lunch', 'value' => '11:00am–3:00pm' ),
		array( 'label' => 'Dinner', 'value' => '5:00–8:00pm' ),
		array( 'label' => 'Menu', 'value' => 'Red meat, white meat or vegetarian nightly' ),
	),
	'rafiki_intro_eyebrow' => 'Stay / Lekker Bar and Braai',
	'rafiki_intro_title'   => 'HOME-COOKED MEALS, RAFIKI STYLE',
	'rafiki_intro_text'    => "\"Lekker\" is the Afrikaans word for awesome, cool, fun, great or sweet — and it's exactly what you'll find at the bar and restaurant at the heart of the Main Lodge. The massive horseshoe bar is built to get guests talking, pouring tropical cocktails, a full beverage selection, and natural juices and smoothies for the kids.\nThe kitchen blends South African BBQ (braai) traditions with local Costa Rican cuisine, drawing on 20 years of home-cooked experience. Every dinner offers a choice of red meat, white meat or a vegetarian entrée, with desserts to follow, and the kitchen is happy to accommodate plant-based diets.",
	'rafiki_gallery'       => rafiki_gallery_from_urls( array(
		'https://rafikisafari.com/wp/wp-content/uploads/2026/02/IMG_1190-scaled.jpg',
		'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-property-and-food-web_3.jpeg',
		'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-property-and-food-web_2.jpeg',
		'https://rafikisafari.com/wp/wp-content/uploads/2026/02/rafiki-property-and-food-web_9.jpeg',
		'https://rafikisafari.com/wp/wp-content/uploads/2026/02/IMG_6088-scaled.jpg',
	), 'Lekker Bar and Braai at Rafiki Safari Lodge' ),
	'rafiki_amenities_title' => "WHAT'S ON THE MENU",
	'rafiki_amenities'     => array(
		array( 'icon' => 'meal', 'title' => 'Horseshoe bar', 'text' => 'Tropical cocktails and a full beverage selection.' ),
		array( 'icon' => 'leaf', 'title' => 'South African braai', 'text' => 'Blended with local Costa Rican dishes.' ),
		array( 'icon' => 'heart', 'title' => 'Plant-based options', 'text' => 'The kitchen happily accommodates most diets.' ),
		array( 'icon' => 'guide', 'title' => 'Three nightly choices', 'text' => 'Red meat, white meat or vegetarian, plus dessert.' ),
		array( 'icon' => 'wave', 'title' => 'Kids\' juices & smoothies', 'text' => 'Natural, made fresh at the bar.' ),
	),
	'rafiki_cta_title'    => 'READY TO EAT LIKE A RAFIKI?',
	'rafiki_cta_text'     => 'The Lekker Bar and Braai is included with every stay at the lodge.',
	'rafiki_cta_image'    => rafiki_seed_image( 'https://rafikisafari.com/wp/wp-content/uploads/2026/02/IMG_1190-scaled.jpg' ),
) );

/* ==================================================================== */
/* ACTIVITIES                                                            */
/* ==================================================================== */

$rafting_id = rafiki_seed_post( 'activity', 'White Water Rafting', array(
	'rafiki_subtitle'     => 'Raft the cleanest river in Central America. Class II-III rapids, hidden waterfalls and incredible views of the rainforest — leaving straight from the lodge.',
	'rafiki_badges'       => array(
		array( 'text' => 'Class II–III' ),
		array( 'text' => 'From $105 / person' ),
		array( 'text' => 'Kids 6+' ),
	),
	'rafiki_price'        => '105',
	'rafiki_price_unit'   => '/ person',
	'rafiki_price_note'   => 'Includes lunch & tax · transportation extra',
	'rafiki_quick_facts'  => array(
		array( 'label' => 'Difficulty', 'value' => 'Class II–III' ),
		array( 'label' => 'Water temperature', 'value' => '25–27°C' ),
		array( 'label' => 'Minimum age', 'value' => '6 years' ),
		array( 'label' => 'Includes', 'value' => 'Gear, guide, lunch' ),
		array( 'label' => 'Departs from', 'value' => 'Directly from the lodge' ),
	),
	'rafiki_intro_eyebrow' => 'Adventure / Rafting',
	'rafiki_intro_title'   => 'THE ONLY LODGE ON THE RIVER WITH ITS OWN PUT-IN',
	'rafiki_intro_text'    => "The Savegre River runs clean from the Talamanca mountain range, and Rafiki is the only lodge with direct access to its rapids. It's a class II-III run: exciting for beginners and families, with calm pools between rapids to soak in the scenery.\nThe adventure starts with a 5km 4x4 drive through our private reserve, with wildlife interpretation along the way. After rafting, we hike to a spring-fed waterfall to swim and cool off before heading back to the lodge for a hot lunch.\nWater stays between 25-27°C year round, with rounded rocks and no undercuts — ideal for anyone comfortable in the water and in good physical shape.",
	'rafiki_gallery'       => rafiki_gallery_from_urls( array(
		'https://rafikisafari.com/wp/wp-content/uploads/2026/02/im2b.jpeg',
		'https://rafikisafari.com/wp/wp-content/uploads/2026/02/IMG_3784-scaled.jpeg',
		'https://rafikisafari.com/wp/wp-content/uploads/2026/02/rafting2-scaled.jpg',
		'https://rafikisafari.com/wp/wp-content/uploads/2026/02/waterfall-scaled.jpg',
		'https://rafikisafari.com/wp/wp-content/uploads/2026/02/IMG_7928-scaled.jpg',
		'https://rafikisafari.com/wp/wp-content/uploads/2016/11/raftingfun.jpg',
	), 'Rafting on the Savegre River' ),
	'rafiki_amenities_title' => "WHAT'S INCLUDED",
	'rafiki_amenities'     => array(
		array( 'icon' => 'guide', 'title' => 'Safety gear', 'text' => 'Helmets, life jackets and paddles.' ),
		array( 'icon' => 'shield', 'title' => 'Professional guide', 'text' => 'Local, certified guides who grew up in the area.' ),
		array( 'icon' => 'truck', 'title' => '4x4 transport', 'text' => '5km through the private reserve, round trip.' ),
		array( 'icon' => 'wave', 'title' => 'Waterfall stop', 'text' => 'Free swim at a spring-fed waterfall.' ),
		array( 'icon' => 'meal', 'title' => 'Home-cooked lunch', 'text' => 'Back at the lodge, included in the price.' ),
		array( 'icon' => 'tax', 'title' => 'Taxes included', 'text' => '13% Costa Rica sales tax.' ),
	),
	'rafiki_itinerary'     => array(
		array( 'title' => '4x4 safari through the reserve', 'text' => '5km through our 600-acre private reserve, with wildlife interpretation along the way.' ),
		array( 'title' => 'Safety briefing & gear', 'text' => 'Our guides hand out helmets, vests and paddles, and cover basic paddling technique.' ),
		array( 'title' => 'Class II–III rapids', 'text' => 'We run the Savegre River through short rapids and big pools, surrounded by rainforest.' ),
		array( 'title' => 'Waterfall hike', 'text' => 'We stop at Quebrada Arroyo to swim under a spring-fed waterfall.' ),
		array( 'title' => 'Snack & final rapids', 'text' => 'A quick snack before floating the remaining rapids and taking in the scenery.' ),
		array( 'title' => 'Return & hot lunch', 'text' => 'Back to the lodge by 4x4 for a home-cooked lunch included in the price.' ),
	),
	'rafiki_included_packages' => array(
		array( 'tag' => '2 nights', 'name' => 'Safarito', 'text' => 'A quick 2-night taste of Rafiki, with one adventure of your choice.', 'price' => '575', 'price_note' => 'for 2 people' ),
		array( 'tag' => '3 nights', 'name' => 'Rafiki Safari', 'text' => 'The classic way to see Rafiki: lodging plus 2 adventures of your choice.', 'price' => '963', 'price_note' => 'for 2 people' ),
		array( 'tag' => '5 nights', 'name' => 'Savegre Adventure', 'text' => 'Jungle and ocean in one trip, with 4 activities included.', 'price' => '1,715', 'price_note' => 'for 2 people' ),
		array( 'tag' => '6 nights', 'name' => 'Super Lekker Safari', 'text' => 'Our signature surf-and-turf adventure, fully inclusive.', 'price' => '1,567', 'price_note' => 'per person' ),
	),
	'rafiki_testimonial_source' => 'tripadvisor',
	'rafiki_testimonial_text'   => 'The rafting was a life-changing experience. The whole family loved it, kids included.',
	'rafiki_testimonial_author' => 'TaikoM',
	'rafiki_cta_title'    => 'READY TO RUN THE RIVER?',
	'rafiki_cta_text'     => 'Check availability for your rafting day or add it to a full package.',
	'rafiki_cta_image'    => rafiki_seed_image( 'https://rafikisafari.com/wp/wp-content/uploads/2026/02/raffting5-scaled.jpeg' ),
) );

$horseback_id = rafiki_seed_post( 'activity', 'Horseback Riding', array(
	'rafiki_subtitle'     => 'Ride through the tropical forest on one of our sturdy ponies, tracing the river basin to a natural swimming hole deep in the jungle.',
	'rafiki_badges'       => array(
		array( 'text' => 'Valley Loop $105' ),
		array( 'text' => 'All skill levels' ),
		array( 'text' => 'Bilingual wranglers' ),
	),
	'rafiki_price'        => '105',
	'rafiki_price_unit'   => '/ person',
	'rafiki_price_note'   => 'Valley Loop · Ultimate Safari combo (+ rafting) $160/person',
	'rafiki_quick_facts'  => array(
		array( 'label' => 'Route', 'value' => 'Valley Loop to Posa del Encanto' ),
		array( 'label' => 'Duration', 'value' => 'Half day' ),
		array( 'label' => 'Includes', 'value' => 'Guide, wrangler, lunch' ),
		array( 'label' => 'Combo with rafting', 'value' => '$160 / person' ),
	),
	'rafiki_intro_eyebrow' => 'Adventure / Horseback Riding',
	'rafiki_intro_title'   => 'THE WAY THE SAVEGRE VALLEY HAS ALWAYS TRAVELED',
	'rafiki_intro_text'    => "Take a ride through the tropical forest on one of our sturdy ponies, tracing the river basin to \"Posa del Encanto\" — a natural swimming hole on the Savegre River — before continuing through Rafiki's forested trails.\nHorses are still central to life and transportation in the Savegre Valley. We partner with the Duarte family, local outfitters since 2002, whose well-trained mounts and bilingual guides and wranglers make every ride feel effortless, whether you're a first-timer or an experienced rider.\nWant more adrenaline? Combine horseback riding with whitewater rafting for our \"Ultimate Safari\" combo.",
	'rafiki_gallery'       => rafiki_gallery_from_urls( array(
		'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-activities-web_50.jpeg',
		'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-activities-web_13.jpeg',
		'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-activities-web_11.jpeg',
		'https://rafikisafari.com/wp/wp-content/uploads/2016/12/horseback-ceibadoctored-1.jpg',
		'https://rafikisafari.com/wp/wp-content/uploads/2016/12/horsesgirl.jpg',
		'https://rafikisafari.com/wp/wp-content/uploads/2016/12/horses-kids.jpg',
	), 'Horseback riding at Rafiki Safari Lodge' ),
	'rafiki_amenities_title' => "WHAT'S INCLUDED",
	'rafiki_amenities'     => array(
		array( 'icon' => 'guide', 'title' => 'Bilingual guide & wrangler', 'text' => 'Local outfitters, partners since 2002.' ),
		array( 'icon' => 'meal', 'title' => 'Lunch included', 'text' => 'On the Valley Loop route.' ),
		array( 'icon' => 'heart', 'title' => 'Well-trained horses', 'text' => 'Sturdy ponies suited to every skill level.' ),
		array( 'icon' => 'tax', 'title' => 'Taxes included', 'text' => '13% Costa Rica sales tax.' ),
	),
	'rafiki_included_packages' => array(
		array( 'tag' => '2 nights', 'name' => 'Safarito', 'text' => 'A quick 2-night taste of Rafiki, with one adventure of your choice.', 'price' => '575', 'price_note' => 'for 2 people' ),
		array( 'tag' => '3 nights', 'name' => 'Rafiki Safari', 'text' => 'The classic way to see Rafiki: lodging plus 2 adventures of your choice.', 'price' => '963', 'price_note' => 'for 2 people' ),
		array( 'tag' => '5 nights', 'name' => 'Savegre Adventure', 'text' => 'Jungle and ocean in one trip, with 4 activities included.', 'price' => '1,715', 'price_note' => 'for 2 people' ),
		array( 'tag' => '6 nights', 'name' => 'Super Lekker Safari', 'text' => 'Our signature surf-and-turf adventure, fully inclusive.', 'price' => '1,567', 'price_note' => 'per person' ),
	),
	'rafiki_testimonial_source' => 'google',
	'rafiki_testimonial_text'   => 'We\'ve been back 5 times in 15 years. All the guides grew up in the area, and that\'s what makes the trip truly special.',
	'rafiki_testimonial_author' => 'Lance R.',
	'rafiki_cta_title'    => 'READY TO SADDLE UP?',
	'rafiki_cta_text'     => 'Check availability for your ride or add it to a full package.',
	'rafiki_cta_image'    => rafiki_seed_image( 'https://rafikisafari.com/wp/wp-content/uploads/2016/12/horses-kids.jpg' ),
) );

$hiking_id = rafiki_seed_post( 'activity', 'Hiking', array(
	'rafiki_subtitle'     => 'Immerse yourself in a tropical paradise on foot — the most intimate way to experience Rafiki\'s private reserve.',
	'rafiki_badges'       => array(
		array( 'text' => 'Waterfall hike $105' ),
		array( 'text' => '1–5 km options' ),
		array( 'text' => '340-ft suspension bridge' ),
	),
	'rafiki_price'        => '105',
	'rafiki_price_unit'   => '/ person',
	'rafiki_price_note'   => 'Waterfall hike · includes lunch & tax · transportation extra',
	'rafiki_quick_facts'  => array(
		array( 'label' => 'Difficulty', 'value' => 'Challenging — be ready to sweat' ),
		array( 'label' => 'Distance options', 'value' => '1–5 km' ),
		array( 'label' => 'Includes', 'value' => 'Guide, lunch' ),
		array( 'label' => 'Highlight', 'value' => '340-ft suspension bridge' ),
	),
	'rafiki_intro_eyebrow' => 'Adventure / Hiking',
	'rafiki_intro_title'   => 'THE MOST INTIMATE WAY TO EXPERIENCE THE FOREST',
	'rafiki_intro_text'    => "Rafiki sits in a lowland tropical forest, and the most intimate way to experience it is on foot. Choose a guided waterfall hike or explore Rafiki's private reserve on your own through a network of trails ranging from 1 to 5 kilometers.\nThe guided Waterfall Hike is our signature route: a river crossing, a private trail with valley views, a 340-foot suspension bridge, stream navigation, a swim under a waterfall, and lunch at Los Campesinos, a local rural-tourism project.\nExpect phenomenal flora and fauna throughout — hardwoods, exotic palms, and ocean vistas from the ridgeline. It's challenging in places, so come ready to sweat.",
	'rafiki_gallery'       => rafiki_gallery_from_urls( array(
		'https://rafikisafari.com/wp/wp-content/uploads/2025/08/crossing.jpg',
		'https://rafikisafari.com/wp/wp-content/uploads/2017/01/chillarroyo.jpg',
		'https://rafikisafari.com/wp/wp-content/uploads/2017/01/streamcalm.jpg',
		'https://rafikisafari.com/wp/wp-content/uploads/2017/01/maybewater.jpg',
		'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-place-wildlife-web_20.jpeg',
		'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-place-wildlife-web_3.jpeg',
	), 'Hiking at Rafiki Safari Lodge' ),
	'rafiki_amenities_title' => "WHAT'S INCLUDED",
	'rafiki_amenities'     => array(
		array( 'icon' => 'guide', 'title' => 'Professional guide', 'text' => 'Knows every trail and every creature on it.' ),
		array( 'icon' => 'mountain', 'title' => 'Suspension bridge', 'text' => '340 feet, with valley views.' ),
		array( 'icon' => 'wave', 'title' => 'Waterfall swim', 'text' => 'Cool off before lunch.' ),
		array( 'icon' => 'meal', 'title' => 'Lunch at Los Campesinos', 'text' => 'A local rural-tourism project.' ),
		array( 'icon' => 'tax', 'title' => 'Taxes included', 'text' => '13% Costa Rica sales tax.' ),
	),
	'rafiki_included_packages' => array(
		array( 'tag' => '2 nights', 'name' => 'Safarito', 'text' => 'A quick 2-night taste of Rafiki, with one adventure of your choice.', 'price' => '575', 'price_note' => 'for 2 people' ),
		array( 'tag' => '3 nights', 'name' => 'Rafiki Safari', 'text' => 'The classic way to see Rafiki: lodging plus 2 adventures of your choice.', 'price' => '963', 'price_note' => 'for 2 people' ),
		array( 'tag' => '5 nights', 'name' => 'Savegre Adventure', 'text' => 'Jungle and ocean in one trip, with 4 activities included.', 'price' => '1,715', 'price_note' => 'for 2 people' ),
		array( 'tag' => '6 nights', 'name' => 'Super Lekker Safari', 'text' => 'Our signature surf-and-turf adventure, fully inclusive.', 'price' => '1,567', 'price_note' => 'per person' ),
	),
	'rafiki_testimonial_source' => 'facebook',
	'rafiki_testimonial_text'   => 'We spent absolutely fantastic days at Rafiki — being this far away from everything let us feel truly one with nature.',
	'rafiki_testimonial_author' => 'Jarl',
	'rafiki_cta_title'    => 'READY TO HIT THE TRAIL?',
	'rafiki_cta_text'     => 'Check availability for your hike or add it to a full package.',
	'rafiki_cta_image'    => rafiki_seed_image( 'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-place-wildlife-web_20.jpeg' ),
) );

$kayaking_id = rafiki_seed_post( 'activity', 'Kayaking', array(
	'rafiki_subtitle'     => 'Launch on the river, float through the mangroves, and land on the beach — a tide-timed journey from jungle to sea.',
	'rafiki_badges'       => array(
		array( 'text' => '~3 km route' ),
		array( 'text' => 'Tide-dependent' ),
		array( 'text' => 'River to sea' ),
	),
	'rafiki_price_note'   => 'Contact us for current pricing',
	'rafiki_quick_facts'  => array(
		array( 'label' => 'Route', 'value' => 'Savegre River to sea via mangroves' ),
		array( 'label' => 'Distance', 'value' => '~3 km' ),
		array( 'label' => 'Departs', 'value' => 'Tide-dependent, 5:00am–2:00pm' ),
		array( 'label' => 'Includes', 'value' => 'Kayak, safety briefing, snack stop' ),
	),
	'rafiki_intro_eyebrow' => 'Adventure / Kayaking',
	'rafiki_intro_title'   => 'FROM RIVER TO SEA THROUGH THE MANGROVES',
	'rafiki_intro_text'    => "Launch on the Savegre River, float through the mangrove estuary, and land on the beach — roughly 3 kilometers of paddling packed with wildlife and scenery.\nThe tour is tide-dependent, with departures ranging from 5:00am to 2:00pm, timed about 3 hours before high tide. After a safety briefing, we guide you through the mangrove forest, with a snack stop at Playa El Rey inside Manuel Antonio National Park and plenty of context on the mangrove ecosystem along the way.",
	'rafiki_gallery'       => rafiki_gallery_from_urls( array(
		'https://rafikisafari.com/wp/wp-content/uploads/2016/12/kayak-trip-small.jpg',
		'https://rafikisafari.com/wp/wp-content/uploads/2016/12/palmtreeswide.jpg',
		'https://rafikisafari.com/wp/wp-content/uploads/2016/12/sunsetbeach.jpg',
	), 'Kayaking through the mangroves at Rafiki' ),
	'rafiki_amenities_title' => "WHAT'S INCLUDED",
	'rafiki_amenities'     => array(
		array( 'icon' => 'shield', 'title' => 'Safety briefing', 'text' => 'Full orientation before launch.' ),
		array( 'icon' => 'guide', 'title' => 'Sea kayak equipment', 'text' => 'Guided passage through the mangrove forest.' ),
		array( 'icon' => 'meal', 'title' => 'Snack stop', 'text' => 'At Playa El Rey, Manuel Antonio National Park.' ),
		array( 'icon' => 'leaf', 'title' => 'Mangrove ecosystem tour', 'text' => 'Learn the estuary as you paddle through it.' ),
	),
	'rafiki_included_packages' => array(
		array( 'tag' => '5 nights', 'name' => 'Savegre Adventure', 'text' => 'Jungle and ocean in one trip — kayak straight to Beach Camp.', 'price' => '1,715', 'price_note' => 'for 2 people' ),
		array( 'tag' => '6 nights', 'name' => 'Super Lekker Safari', 'text' => 'Our signature surf-and-turf adventure, fully inclusive.', 'price' => '1,567', 'price_note' => 'per person' ),
	),
	'rafiki_cta_title'    => 'READY TO PADDLE TO THE COAST?',
	'rafiki_cta_text'     => 'Ask us about timing your kayak tour with the tides.',
	'rafiki_cta_image'    => rafiki_seed_image( 'https://rafikisafari.com/wp/wp-content/uploads/2016/12/kayak-trip-small.jpg' ),
) );

$birding_id = rafiki_seed_post( 'activity', 'Birding', array(
	'rafiki_subtitle'     => 'Over 300 recorded species in Costa Rica\'s Pacific lowland rainforest — toucans, motmots, hummingbirds, trogons, scarlet macaws and more.',
	'rafiki_badges'       => array(
		array( 'text' => '300+ species' ),
		array( 'text' => 'All experience levels' ),
		array( 'text' => 'Pacific lowland rainforest' ),
	),
	'rafiki_price_note'   => 'Contact us for current pricing',
	'rafiki_quick_facts'  => array(
		array( 'label' => 'Species recorded', 'value' => '300+' ),
		array( 'label' => 'Best spot', 'value' => 'Main deck & reception feeders' ),
		array( 'label' => 'Habitat', 'value' => 'Pacific lowland rainforest' ),
		array( 'label' => 'Guides', 'value' => 'Novice to expert birders welcome' ),
	),
	'rafiki_intro_eyebrow' => 'Adventure / Birding',
	'rafiki_intro_title'   => 'ONE OF COSTA RICA\'S MOST DIVERSE ECOSYSTEMS',
	'rafiki_intro_text'    => "Rafiki sits in the Savegre Valley, a watershed that drops from over 3,000 meters to the Pacific coast in just 45 kilometers — one of the country's richest ecosystems, connected to Chirripó and Los Quetzales National Parks and, further out, the Osa Peninsula.\nOur guided birding excursions welcome novice and experienced birders alike, exploring rainforest that has recorded over 300 species, including toucans, motmots, hummingbirds, trogons, scarlet macaws and woodpeckers. Early surveys by Costa Rica's National Museum identified 352 species on and around the property.\nThe lodge's main deck is one of the best vantage points — treetops, lake views and open sky — with feeders set up right at reception.",
	'rafiki_gallery'       => rafiki_gallery_from_urls( array(
		'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-place-wildlife-web_11.jpeg',
		'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-place-wildlife-web_9.jpeg',
		'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-place-wildlife-web_3.jpeg',
		'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-place-wildlife-web_2.jpeg',
		'https://rafikisafari.com/wp/wp-content/uploads/2016/10/whitehawk-1.jpg',
		'https://rafikisafari.com/wp/wp-content/uploads/2016/10/tigerheron.jpg',
	), 'Birding at Rafiki Safari Lodge' ),
	'rafiki_amenities_title' => "WHAT'S INCLUDED",
	'rafiki_amenities'     => array(
		array( 'icon' => 'leaf', 'title' => '300+ species recorded', 'text' => 'One of the richest birding sites in the region.' ),
		array( 'icon' => 'guide', 'title' => 'Expert local guides', 'text' => 'Comfortable with beginners and serious listers alike.' ),
		array( 'icon' => 'porch', 'title' => 'Main deck viewing', 'text' => 'Treetops, lake and open sky from one spot.' ),
		array( 'icon' => 'meal', 'title' => 'Reception feeders', 'text' => 'Birds come to you between outings.' ),
	),
	'rafiki_testimonial_source' => 'google',
	'rafiki_testimonial_text'   => 'A beautiful, African-inspired lodge for the bird- and wildlife lover. The guides are incredible.',
	'rafiki_testimonial_author' => 'Yooperchick',
	'rafiki_cta_title'    => 'READY TO SPOT SOMETHING RARE?',
	'rafiki_cta_text'     => 'Ask us about our guided birding excursions.',
	'rafiki_cta_image'    => rafiki_seed_image( 'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-place-wildlife-web_11.jpeg' ),
) );

$fishing_id = rafiki_seed_post( 'activity', 'Fishing', array(
	'rafiki_subtitle'     => 'Fly fish one of the cleanest rivers in Central America, with 8+ freshwater and saltwater species on the line.',
	'rafiki_badges'       => array(
		array( 'text' => 'Full day' ),
		array( 'text' => '8+ species' ),
		array( 'text' => 'Fly fishing' ),
	),
	'rafiki_price_note'   => 'Contact us for current pricing',
	'rafiki_quick_facts'  => array(
		array( 'label' => 'Location', 'value' => 'Savegre River' ),
		array( 'label' => 'Species', 'value' => '8+ fresh & saltwater' ),
		array( 'label' => 'Duration', 'value' => 'Full day' ),
		array( 'label' => 'Gear', 'value' => '13-ft raft with oar frame' ),
	),
	'rafiki_intro_eyebrow' => 'Adventure / Fishing',
	'rafiki_intro_title'   => 'ONE OF THE CLEANEST RIVERS IN CENTRAL AMERICA',
	'rafiki_intro_text'    => "The Savegre River is a fly fisherman's playground — one of the cleanest rivers in Central America, home to more than 8 freshwater and saltwater species. Machaca, a hard-fighting relative of the piranha, is a local favorite that rewards a bit of strategy.\nWe fish from a 13-foot whitewater raft with an oar frame, which gets us into deep pools and slower stretches near the lodge that you simply can't reach on foot. A full day out with a guide who knows exactly where the fish are holding.",
	'rafiki_gallery'       => rafiki_gallery_from_urls( array(
		'https://rafikisafari.com/wp/wp-content/uploads/2017/03/fish2-1.jpg',
		'https://rafikisafari.com/wp/wp-content/uploads/2017/03/fish3-1.jpg',
		'https://rafikisafari.com/wp/wp-content/uploads/2017/03/fish4-1.jpg',
	), 'Fly fishing on the Savegre River' ),
	'rafiki_amenities_title' => "WHAT'S INCLUDED",
	'rafiki_amenities'     => array(
		array( 'icon' => 'guide', 'title' => '13-ft raft with oar frame', 'text' => 'Reach deep pools and slow water others can\'t.' ),
		array( 'icon' => 'shield', 'title' => 'Professional guidance', 'text' => 'Tropical fly fishing, patterns and technique.' ),
		array( 'icon' => 'leaf', 'title' => '8+ species', 'text' => 'Including the hard-fighting machaca.' ),
	),
	'rafiki_cta_title'    => 'READY TO CAST A LINE?',
	'rafiki_cta_text'     => 'Ask us about current conditions and availability.',
	'rafiki_cta_image'    => rafiki_seed_image( 'https://rafikisafari.com/wp/wp-content/uploads/2017/03/fish2-1.jpg' ),
) );

$massage_id = rafiki_seed_post( 'activity', 'Massage', array(
	'rafiki_subtitle'     => 'Tropical massage in the privacy of your own tent — Swedish, bamboo, chocolate and coconut treatments, plus foot rubs and pedicures, by a certified therapist.',
	'rafiki_badges'       => array(
		array( 'text' => 'In-tent service' ),
		array( 'text' => 'Certified therapist' ),
		array( 'text' => 'Post-adventure recovery' ),
	),
	'rafiki_price_note'   => 'Contact us for current pricing',
	'rafiki_quick_facts'  => array(
		array( 'label' => 'Where', 'value' => 'In your tent' ),
		array( 'label' => 'Therapist', 'value' => 'Certified — Yerlin Tapia' ),
		array( 'label' => 'Styles', 'value' => 'Swedish, bamboo, chocolate, coconut, foot rub/pedicure' ),
		array( 'label' => 'Great after', 'value' => 'Rafting, riding or hiking' ),
	),
	'rafiki_intro_eyebrow' => 'Adventure / Massage',
	'rafiki_intro_title'   => 'RECOVER AS WELL AS YOU ADVENTURE',
	'rafiki_intro_text'    => "Rafiki brings massage therapy right to the privacy of your luxury tent, with treatments including Swedish massage, bamboo therapy, chocolate aromatherapy and coconut oil treatments.\nOur certified therapist, Yerlin Tapia, tailors each session to your day: deep-tissue bamboo work after rafting, coconut shell therapy following a horseback ride, or — if all you need is a foot rub or a pedicure to get you back on your feet — a foot treatment after a long hike.",
	'rafiki_gallery'       => rafiki_gallery_from_urls( array(
		'https://rafikisafari.com/wp/wp-content/uploads/2016/11/coco.jpg',
		'https://rafikisafari.com/wp/wp-content/uploads/2016/11/bamboo.jpg',
		'https://rafikisafari.com/wp/wp-content/uploads/2016/11/chocolate.jpg',
		'https://rafikisafari.com/wp/wp-content/uploads/2016/11/massage.jpg',
	), 'Tropical massage at Rafiki Safari Lodge' ),
	'rafiki_amenities_title' => 'TREATMENT STYLES',
	'rafiki_amenities'     => array(
		array( 'icon' => 'heart', 'title' => 'Swedish massage', 'text' => 'Classic full-body relaxation.' ),
		array( 'icon' => 'wood', 'title' => 'Bamboo therapy', 'text' => 'Deep-tissue work with heated bamboo.' ),
		array( 'icon' => 'meal', 'title' => 'Chocolate aromatherapy', 'text' => 'A sweet, warming treatment.' ),
		array( 'icon' => 'leaf', 'title' => 'Coconut oil treatment', 'text' => 'Nourishing and locally sourced.' ),
		array( 'icon' => 'heart', 'title' => 'Foot rub & pedicure', 'text' => 'For when that\'s all you need to feel human again.' ),
		array( 'icon' => 'tent', 'title' => 'In-tent privacy', 'text' => 'No need to leave your space.' ),
	),
	'rafiki_cta_title'    => 'READY TO UNWIND?',
	'rafiki_cta_text'     => 'Ask us to book a session during your stay.',
	'rafiki_cta_image'    => rafiki_seed_image( 'https://rafikisafari.com/wp/wp-content/uploads/2016/11/massage.jpg' ),
) );

/* ==================================================================== */
/* PACKAGES                                                              */
/* ==================================================================== */

rafiki_seed_post( 'package', 'Safarito', array(
	'rafiki_subtitle'     => 'This 2-night Costa Rica adventure package goes by quickly — but you won\'t be rushed, except down the river.',
	'rafiki_badges'       => array(
		array( 'text' => '2 nights' ),
		array( 'text' => '1 activity per person' ),
		array( 'text' => 'From $575 for 2' ),
	),
	'rafiki_price'        => '575',
	'rafiki_price_unit'   => 'for 2 people',
	'rafiki_price_note'   => 'Green season · High season from $705 · 13% tax included',
	'rafiki_quick_facts'  => array(
		array( 'label' => 'Duration', 'value' => '2 nights' ),
		array( 'label' => 'Activities', 'value' => '1 per person (rafting, hiking or horseback)' ),
		array( 'label' => 'Meals', 'value' => 'Breakfast & lunch with tour' ),
		array( 'label' => 'Perks', 'value' => 'Water slide, hot tub, Lekker Bar' ),
	),
	'rafiki_intro_eyebrow' => 'Packages / Safarito',
	'rafiki_intro_title'   => 'A QUICK TASTE OF RAFIKI',
	'rafiki_intro_text'    => "This 2-night package is the fastest way to experience Rafiki without feeling rushed — except down the river. Choose one adventure per person from whitewater rafting, hiking or horseback riding, with lodging, breakfast and lunch included.\nWant more? Turn it into our \"Ultimate Safari\" by combining horseback riding with whitewater rafting.",
	'rafiki_gallery'       => rafiki_gallery_from_urls( array(
		'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-activities-web_25.jpeg',
		'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-activities-web_44.jpeg',
		'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-activities-web_13.jpeg',
	), 'Safarito package at Rafiki' ),
	'rafiki_amenities_title' => "WHAT'S INCLUDED",
	'rafiki_amenities'     => array(
		array( 'icon' => 'tent', 'title' => '2 nights lodging', 'text' => 'In a luxury safari tent.' ),
		array( 'icon' => 'guide', 'title' => '1 activity per person', 'text' => 'Rafting, hiking or horseback riding.' ),
		array( 'icon' => 'meal', 'title' => 'Breakfast & lunch', 'text' => 'Included with your tour day.' ),
		array( 'icon' => 'wave', 'title' => 'Water slide & hot tub', 'text' => 'Free time at the Main Lodge.' ),
		array( 'icon' => 'tax', 'title' => 'Taxes included', 'text' => '13% Costa Rica sales tax.' ),
	),
	'rafiki_itinerary'     => array(
		array( 'title' => 'Day 1 — Arrival', 'text' => 'Arrive before dusk and settle in, with dinner at the Lekker Bar.' ),
		array( 'title' => 'Day 2 — Adventure day', 'text' => 'Coffee at 6:00am, breakfast, your morning activity, then the water slide and spa time in the afternoon.' ),
		array( 'title' => 'Day 3 — Departure', 'text' => 'Breakfast and check-out, with an optional gift-shop stop or waterfall hike.' ),
	),
	'rafiki_group_pricing' => array(
		array( 'guests' => '2 people', 'green' => '$575', 'high' => '$705' ),
		array( 'guests' => '3 people', 'green' => '$725', 'high' => '$855' ),
		array( 'guests' => '4 people', 'green' => '$875', 'high' => '$1,005' ),
	),
	'rafiki_cta_title'    => 'READY FOR YOUR QUICK GETAWAY?',
	'rafiki_cta_text'     => 'Check availability for the Safarito package.',
	'rafiki_cta_image'    => rafiki_seed_image( 'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-activities-web_25.jpeg' ),
) );

rafiki_seed_post( 'package', 'Rafiki Safari', array(
	'rafiki_subtitle'     => 'The 3-night Rafiki Safari package gives you the freedom to relax — most of the planning is already done for you.',
	'rafiki_badges'       => array(
		array( 'text' => '3 nights' ),
		array( 'text' => '2 activities per person' ),
		array( 'text' => 'From $963 for 2' ),
	),
	'rafiki_price'        => '963',
	'rafiki_price_unit'   => 'for 2 people',
	'rafiki_price_note'   => 'Green season · High season from $1,155 · 13% tax included',
	'rafiki_quick_facts'  => array(
		array( 'label' => 'Duration', 'value' => '3 nights' ),
		array( 'label' => 'Activities', 'value' => '2 per person, your choice' ),
		array( 'label' => 'Meals', 'value' => 'Breakfast & lunch with tour' ),
		array( 'label' => 'Upgrade', 'value' => 'Ultimate Safari add-on available' ),
	),
	'rafiki_intro_eyebrow' => 'Packages / Rafiki Safari',
	'rafiki_intro_title'   => 'THE FREEDOM TO RELAX',
	'rafiki_intro_text'    => "A ton of your adventure is already planned for you — you'll just have to pick your 2 favorite trips from whitewater rafting, hiking or horseback riding. Three nights of lodging, breakfast and lunch on tour days, all included.\nWant to go further? Upgrade to our \"Ultimate Safari\" combo, pairing horseback riding with whitewater rafting.",
	'rafiki_gallery'       => rafiki_gallery_from_urls( array(
		'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-activities-web_25.jpeg',
		'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-activities-web_44.jpeg',
		'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-activities-web_13.jpeg',
	), 'Rafiki Safari package' ),
	'rafiki_amenities_title' => "WHAT'S INCLUDED",
	'rafiki_amenities'     => array(
		array( 'icon' => 'tent', 'title' => '3 nights lodging', 'text' => 'In a luxury safari tent.' ),
		array( 'icon' => 'guide', 'title' => '2 activities per person', 'text' => 'Rafting, hiking or horseback riding.' ),
		array( 'icon' => 'meal', 'title' => 'Breakfast & lunch', 'text' => 'Included on tour days.' ),
		array( 'icon' => 'tax', 'title' => 'Taxes included', 'text' => '13% Costa Rica sales tax.' ),
	),
	'rafiki_itinerary'     => array(
		array( 'title' => 'Day 1 — Arrival', 'text' => 'Arrival, lodge orientation, a first look around, and dinner.' ),
		array( 'title' => 'Day 2 — First adventure', 'text' => 'Coffee at 6:30am, breakfast, your first adventure (4–6 hours), free afternoon.' ),
		array( 'title' => 'Day 3 — Second adventure', 'text' => 'Your second adventure, then time to relax into the evening.' ),
		array( 'title' => 'Day 4 — Departure', 'text' => 'Check-out at 11:00am, with optional morning activities.' ),
	),
	'rafiki_group_pricing' => array(
		array( 'guests' => '2 people', 'green' => '$963', 'high' => '$1,155' ),
		array( 'guests' => '3 people', 'green' => '$1,233', 'high' => '$1,425' ),
		array( 'guests' => '4 people', 'green' => '$1,503', 'high' => '$1,695' ),
	),
	'rafiki_cta_title'    => 'READY FOR TWO ADVENTURES?',
	'rafiki_cta_text'     => 'Check availability for the Rafiki Safari package.',
	'rafiki_cta_image'    => rafiki_seed_image( 'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-activities-web_44.jpeg' ),
) );

rafiki_seed_post( 'package', 'Savegre Adventure', array(
	'rafiki_subtitle'     => 'A 5-night glamping adventure in the magnificent Savegre Valley, combining jungle thrills with beach relaxation.',
	'rafiki_badges'       => array(
		array( 'text' => '5 nights' ),
		array( 'text' => '4 activities' ),
		array( 'text' => 'From $1,715 for 2' ),
	),
	'rafiki_price'        => '1,715',
	'rafiki_price_unit'   => 'for 2 people',
	'rafiki_price_note'   => 'Green season · High season from $1,950 · 13% tax included',
	'rafiki_quick_facts'  => array(
		array( 'label' => 'Duration', 'value' => '5 nights (3 jungle + 2 beach)' ),
		array( 'label' => 'Activities', 'value' => '4 total' ),
		array( 'label' => 'Includes', 'value' => 'Sea kayak transfer to Beach Camp' ),
		array( 'label' => 'Highlight', 'value' => 'Longest canopy cable in Central America' ),
	),
	'rafiki_intro_eyebrow' => 'Packages / Savegre Adventure',
	'rafiki_intro_title'   => 'MOUNTAIN THRILLS, THEN COASTAL CALM',
	'rafiki_intro_text'    => "Five nights of glamping in the magnificent Savegre Valley, combining jungle adventure with beach relaxation. Choose 2 activities from whitewater rafting, hiking or horseback riding at the lodge, then sea-kayak through the mangroves to Beach Camp for a canopy tour finale.\nBreakfast is included at both locations, along with all 4 activities and their meals.",
	'rafiki_gallery'       => rafiki_gallery_from_urls( array(
		'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-activities-web_25.jpeg',
		'https://rafikisafari.com/wp/wp-content/uploads/2016/12/ohface.jpg',
		'https://rafikisafari.com/wp/wp-content/uploads/2026/03/IMG_1825-scaled-1.jpg',
		'https://rafikisafari.com/wp/wp-content/uploads/2016/12/beach-pool.jpg',
	), 'Savegre Adventure package' ),
	'rafiki_amenities_title' => "WHAT'S INCLUDED",
	'rafiki_amenities'     => array(
		array( 'icon' => 'tent', 'title' => '3 nights Lodge + 2 nights Beach Camp', 'text' => 'Jungle and ocean, back to back.' ),
		array( 'icon' => 'guide', 'title' => '4 guided activities', 'text' => 'Rafting/hiking/horseback, kayaking, canopy tour.' ),
		array( 'icon' => 'meal', 'title' => 'Breakfast at both locations', 'text' => 'Plus meals with every activity.' ),
		array( 'icon' => 'tax', 'title' => 'Taxes included', 'text' => '13% Costa Rica sales tax.' ),
	),
	'rafiki_itinerary'     => array(
		array( 'title' => 'Day 1 — Arrival', 'text' => 'Welcome drink and a first look around the lodge.' ),
		array( 'title' => 'Day 2 — Whitewater rafting', 'text' => '13km on the Savegre River.' ),
		array( 'title' => 'Day 3 — Horseback or waterfall hike', 'text' => 'Your choice, including the suspension bridge route.' ),
		array( 'title' => 'Day 4 — Transfer to Beach Camp', 'text' => 'Sea kayaks through the mangroves to the coast.' ),
		array( 'title' => 'Day 5 — Canopy tour', 'text' => 'The longest cable in Central America.' ),
		array( 'title' => 'Day 6 — Departure', 'text' => 'Check-out from Beach Camp.' ),
	),
	'rafiki_group_pricing' => array(
		array( 'guests' => '2 people', 'green' => '$1,715', 'high' => '$1,950' ),
		array( 'guests' => '3 people', 'green' => '$2,305', 'high' => '$2,520' ),
		array( 'guests' => '4 people', 'green' => '$2,885', 'high' => '$3,075' ),
	),
	'rafiki_cta_title'    => 'READY FOR JUNGLE AND OCEAN?',
	'rafiki_cta_text'     => 'Check availability for the Savegre Adventure package.',
	'rafiki_cta_image'    => rafiki_seed_image( 'https://rafikisafari.com/wp/wp-content/uploads/2016/12/beach-pool.jpg' ),
) );

rafiki_seed_post( 'package', 'Super Lekker Safari', array(
	'rafiki_subtitle'     => 'Our signature surf-and-turf adventure — mountain and beach, five activities, and every transfer from San José included.',
	'rafiki_badges'       => array(
		array( 'text' => '6 nights' ),
		array( 'text' => '5 activities' ),
		array( 'text' => 'From $1,567 / person' ),
	),
	'rafiki_price'        => '1,567',
	'rafiki_price_unit'   => '/ person (4 people)',
	'rafiki_price_note'   => '2 people: $1,850/person · 3 people: $1,664/person · 13% tax included',
	'rafiki_quick_facts'  => array(
		array( 'label' => 'Duration', 'value' => '6 nights (4 lodge + 2 beach)' ),
		array( 'label' => 'Activities', 'value' => '5 per person' ),
		array( 'label' => 'Transfers', 'value' => 'Included from San José airport' ),
		array( 'label' => 'Highlight', 'value' => 'Night hike + canopy tour' ),
	),
	'rafiki_intro_eyebrow' => 'Packages / Super Lekker Safari',
	'rafiki_intro_title'   => 'OUR SIGNATURE SURF & TURF ADVENTURE',
	'rafiki_intro_text'    => "Six nights combining mountain and beach: whitewater rafting, horseback riding through the private reserve, a waterfall hike with suspension bridge, a night hike, sea kayaking to Beach Camp, and a canopy/zipline tour in the Manuel Antonio area.\nAll meals are included except beach camp dinners, along with every transfer — starting with pickup from San José.",
	'rafiki_gallery'       => rafiki_gallery_from_urls( array(
		'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-activities-web_25.jpeg',
		'https://rafikisafari.com/wp/wp-content/uploads/2016/12/dawnpatrol.jpg',
		'https://rafikisafari.com/wp/wp-content/uploads/2016/12/ohface.jpg',
		'https://rafikisafari.com/wp/wp-content/uploads/2026/03/IMG_1825-scaled-1.jpg',
	), 'Super Lekker Safari package' ),
	'rafiki_amenities_title' => "WHAT'S INCLUDED",
	'rafiki_amenities'     => array(
		array( 'icon' => 'tent', 'title' => '6 nights lodging', 'text' => '4 at the Lodge, 2 at Beach Camp.' ),
		array( 'icon' => 'guide', 'title' => '5 activities per person', 'text' => 'Rafting, riding, hiking, kayaking, canopy.' ),
		array( 'icon' => 'meal', 'title' => 'All meals included', 'text' => 'Except dinners at Beach Camp.' ),
		array( 'icon' => 'truck', 'title' => 'All transfers included', 'text' => 'Starting from San José airport.' ),
		array( 'icon' => 'tax', 'title' => 'Taxes included', 'text' => '13% Costa Rica sales tax.' ),
	),
	'rafiki_itinerary'     => array(
		array( 'title' => 'Day 1 — Arrival', 'text' => 'Welcome drink, with an optional night hike.' ),
		array( 'title' => 'Day 2 — Rafting + night hike', 'text' => 'Whitewater rafting by day, wildlife spotting after dark.' ),
		array( 'title' => 'Day 3 — Horseback riding', 'text' => 'Through the private forest reserve.' ),
		array( 'title' => 'Day 4 — Waterfall hike', 'text' => 'Suspension bridge crossing, with lunch on the trail.' ),
		array( 'title' => 'Day 5 — Transfer to Beach Camp', 'text' => 'Sea kayaks from the Savegre River through the mangroves.' ),
		array( 'title' => 'Day 6 — Canopy tour & departure', 'text' => 'Zipline through the Manuel Antonio area before heading out.' ),
	),
	'rafiki_group_pricing' => array(
		array( 'guests' => '2 people', 'green' => '$1,850 / person', 'high' => '—' ),
		array( 'guests' => '3 people', 'green' => '$1,664 / person', 'high' => '—' ),
		array( 'guests' => '4 people', 'green' => '$1,567 / person', 'high' => '—' ),
	),
	'rafiki_testimonial_source' => 'tripadvisor',
	'rafiki_testimonial_text'   => 'The beauty of Rafiki alone is worth the visit. I can\'t recommend the Rafiki Safari Lodge enough.',
	'rafiki_testimonial_author' => 'Frank T.',
	'rafiki_cta_title'    => 'READY FOR THE FULL EXPERIENCE?',
	'rafiki_cta_text'     => 'Check availability for the Super Lekker Safari package.',
	'rafiki_cta_image'    => rafiki_seed_image( 'https://rafikisafari.com/wp/wp-content/uploads/2016/12/ohface.jpg' ),
) );

/* ==================================================================== */
/* PAGES — Bring Your Group / Why Rafiki / Meet Rafiki / Plan Your Trip /  */
/* Rafiki Journal. Each is a WordPress Page assigned to its matching       */
/* page-templates/template-*.php file, which holds all the real content.  */
/* ==================================================================== */

function rafiki_seed_page( $title, $slug, $template ) {
	$existing = get_page_by_path( $slug, OBJECT, 'page' );
	if ( $existing ) {
		$post_id = $existing->ID;
		WP_CLI::log( "Page \"$title\" already exists (#$post_id)." );
	} else {
		$post_id = wp_insert_post( array(
			'post_type'   => 'page',
			'post_title'  => $title,
			'post_name'   => $slug,
			'post_status' => 'publish',
		), true );
		if ( is_wp_error( $post_id ) ) {
			WP_CLI::error( "Could not create page \"$title\": " . $post_id->get_error_message() );
			return 0;
		}
		WP_CLI::success( "Created page \"$title\" (#$post_id)." );
	}
	update_post_meta( $post_id, '_wp_page_template', $template );
	return $post_id;
}

rafiki_seed_page( 'Bring Your Group', 'bring-your-group', 'page-templates/template-bring-your-group.php' );
rafiki_seed_page( 'Why Rafiki', 'why-rafiki', 'page-templates/template-why-rafiki.php' );
rafiki_seed_page( 'Meet Rafiki', 'meet-rafiki', 'page-templates/template-meet-rafiki.php' );
rafiki_seed_page( 'Plan Your Trip', 'plan-your-trip', 'page-templates/template-plan-your-trip.php' );
rafiki_seed_page( 'Rafiki Journal', 'rafiki-journal', 'page-templates/template-journal.php' );

/* ==================================================================== */
/* RAFIKI JOURNAL — placeholder articles                                */
/*                                                                        */
/* These 3 posts are draft copy in Rafiki's voice, written to match the   */
/* article angles from the positioning brief (one per priority avatar:    */
/* families, comparison-shoppers, retreat leaders). They are NOT based    */
/* on verified facts about the property beyond what's already elsewhere   */
/* on this site — the client should fact-check every specific before      */
/* publishing, and this is clearly flagged in the first paragraph of      */
/* each post so nobody mistakes a draft for finished copy.                */
/* ==================================================================== */

function rafiki_seed_journal_post( $title, $category_name, $excerpt, $paragraphs, $image_url ) {
	$found = get_posts( array(
		'post_type'      => 'post',
		'title'          => $title,
		'posts_per_page' => 1,
		'post_status'    => 'any',
		'fields'         => 'ids',
	) );

	$category_id = 0;
	$term = term_exists( $category_name, 'category' );
	if ( $term ) {
		$category_id = (int) ( is_array( $term ) ? $term['term_id'] : $term );
	} else {
		$new_term = wp_insert_term( $category_name, 'category' );
		if ( ! is_wp_error( $new_term ) ) $category_id = (int) $new_term['term_id'];
	}

	$content = '';
	foreach ( $paragraphs as $p ) {
		$content .= '<p>' . $p . "</p>\n";
	}

	if ( $found ) {
		$post_id = $found[0];
		WP_CLI::log( "Journal post \"$title\" already exists (#$post_id), updating." );
		wp_update_post( array( 'ID' => $post_id, 'post_content' => $content, 'post_excerpt' => $excerpt ) );
	} else {
		$post_id = wp_insert_post( array(
			'post_type'     => 'post',
			'post_title'    => $title,
			'post_content'  => $content,
			'post_excerpt'  => $excerpt,
			'post_status'   => 'publish',
			'post_category' => $category_id ? array( $category_id ) : array(),
		), true );
		if ( is_wp_error( $post_id ) ) {
			WP_CLI::error( "Could not create journal post \"$title\": " . $post_id->get_error_message() );
			return 0;
		}
		WP_CLI::success( "Created journal post \"$title\" (#$post_id)." );
	}

	if ( $category_id ) wp_set_post_categories( $post_id, array( $category_id ) );

	$img_id = rafiki_seed_image( $image_url );
	if ( $img_id ) set_post_thumbnail( $post_id, $img_id );

	return $post_id;
}

rafiki_seed_journal_post(
	'Why Families Reconnect Differently at Rafiki',
	'Families',
	"Multi-generational trips are hard to plan and easy to forget. Here's why Rafiki keeps bringing the same families back, year after year.",
	array(
		'<em>[Draft article — written for the site launch, not yet fact-checked against real guest stories. Replace the placeholder details below with a specific family\'s trip once the client can share one.]</em>',
		'Every family trip starts with the same problem: too many ages, too many opinions, and a shrinking window where everyone can actually get away at once. By the time the group agrees on a destination, half the excitement is gone.',
		'What we hear from families who\'ve stayed at Rafiki is different. It isn\'t about the tents, or the rafting, or the pool with the water slide — though those help. It\'s that there\'s finally nowhere else to be. No errands. No separate schedules. Just breakfast together at the Lekker Bar, and then a full day ahead with nothing else competing for anyone\'s attention.',
		'One guest put it simply: it was the first time in years the whole family had been in the same place at the same time, not glancing at a phone. That\'s not a rafting review. That\'s what the trip was actually for.',
		'If you\'re the one trying to get three generations to agree on a vacation, that\'s the case for Rafiki: not another list of activities, but one place where everyone — six-year-olds and grandparents included — has something to do, and somewhere to come back together at the end of the day.',
	),
	'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-property-and-food-web_16.jpeg'
);

rafiki_seed_journal_post(
	'Why Rafiki Isn\'t a Safari',
	'Why Rafiki',
	'No lions. No jeeps circling a fenced enclosure. Here\'s what "safari" actually means at Rafiki, and why that\'s the point.',
	array(
		'<em>[Draft article — the story of the name and the South African tents should be corrected/expanded by Loki directly; the version below is a reasonable placeholder based on public information about the property.]</em>',
		'The name "safari" tends to set the wrong expectation. People picture Africa — jeeps, guaranteed sightings, animals that show up on cue for a photo. Rafiki isn\'t that, and was never trying to be.',
		'The "safari" here refers to the style of the tents — imported from South Africa, raised on wooden platforms, built for sleeping surrounded by forest rather than behind four walls — not to a promise of wildlife on demand. What you actually get is 600 acres of private Costa Rican rainforest along the Savegre River, where animals come and go because it\'s their home, not because they\'re fenced in for guests.',
		'That means some mornings you\'ll watch a troop of monkeys cross right over the lodge, and other mornings you won\'t see much beyond birdsong. Both are the real forest. If what you want is a guaranteed animal checklist, a wildlife park will serve you better. If what you want is nature that isn\'t performing for you, that\'s exactly what Rafiki is built around.',
	),
	'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-place-wildlife-web_9.jpeg'
);

rafiki_seed_journal_post(
	'What Retreat Leaders Need Before Choosing a Venue',
	'Retreats & Groups',
	'Beyond a pretty yoga deck: what actually makes or breaks a retreat venue, from someone who has to answer for how it goes.',
	array(
		'<em>[Draft article — written from a generic retreat-leader perspective. Should be reviewed against real retreats hosted at Rafiki, with specifics swapped in once available (capacity numbers, past retreat photos, leader testimonials).]</em>',
		'If you lead retreats, the venue isn\'t decor — it\'s a co-facilitator. A space that feels corporate undoes half the work before anyone sits down. A space that feels genuinely apart from daily life does some of the work for you.',
		'The questions that actually matter rarely show up on a venue\'s photo gallery: Is there a real outdoor space for a circle, not just a conference room with a mat rolled out? Can the kitchen accommodate a group with mixed dietary needs without a week\'s notice? Is the WiFi weak enough that people actually put their phones down?',
		'At Rafiki, groups get an open-air lodge built for gathering, meals the kitchen adjusts for the table rather than a fixed banquet menu, and a setting where "unplugging" isn\'t a suggestion — it\'s just what happens when the signal drops off on the way in.',
		'If you\'re scouting a venue for your next retreat, that\'s the honest pitch: not a spa with a schedule, but a place where the setting does some of the holding, so you can focus on the people in front of you.',
	),
	'https://rafikisafari.com/wp/wp-content/uploads/2025/12/rafiki-property-and-food-web_12.jpeg'
);

WP_CLI::success( 'Demo content seeded.' );
