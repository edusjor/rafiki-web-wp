<?php
/**
 * Migrates the 8 "News" articles from the old site (rafikisafari.com/wp/)
 * into the Rafiki Journal as regular posts.
 *
 * - Slugs are kept from the old site so /wp/<slug>/ redirects straight to
 *   /<slug>/ (see the theme's inc/redirects.php). The one exception is the
 *   old auto-generated "2612-2", which gets a readable slug plus its own
 *   redirect entry.
 * - The old posts were WPBakery shortcode soup (and rendered broken on the
 *   old site), so the content below is the same text, cleaned up by hand.
 * - Images are bundled in seed/journal/ (or the theme's photo folder) so
 *   this keeps working after the old site is shut down.
 *
 * Safe to re-run: existing posts are left untouched unless
 * RAFIKI_SEED_OVERWRITE is defined, same as seed-demo-content.php.
 *
 *   wp eval-file /seed/import-old-journal.php
 *   wp eval 'define("RAFIKI_SEED_OVERWRITE", true); include "/seed/import-old-journal.php";'
 */

require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

/**
 * Imports a local image once and returns its attachment ID.
 * $ref is "journal:<file>" (seed/journal/) or "theme:<name>" (theme photos).
 */
function rafiki_journal_image( $ref, $alt = '' ) {
	static $cache = array();
	if ( isset( $cache[ $ref ] ) ) return $cache[ $ref ];

	$existing = get_posts( array(
		'post_type'      => 'attachment',
		'meta_key'       => '_rafiki_seed_source',
		'meta_value'     => $ref,
		'posts_per_page' => 1,
		'fields'         => 'ids',
	) );
	if ( $existing ) return $cache[ $ref ] = $existing[0];

	list( $kind, $name ) = explode( ':', $ref, 2 );
	$path = 'theme' === $kind
		? get_template_directory() . '/assets/img/photos/' . $name . '.webp'
		: __DIR__ . '/journal/' . $name;
	if ( ! file_exists( $path ) ) {
		WP_CLI::warning( "Missing image file for $ref ($path)" );
		return $cache[ $ref ] = 0;
	}

	$tmp = wp_tempnam( basename( $path ) );
	copy( $path, $tmp );
	$id = media_handle_sideload( array( 'name' => basename( $path ), 'tmp_name' => $tmp ), 0 );
	if ( is_wp_error( $id ) ) {
		@unlink( $tmp );
		WP_CLI::warning( "Could not import $ref: " . $id->get_error_message() );
		return $cache[ $ref ] = 0;
	}
	update_post_meta( $id, '_rafiki_seed_source', $ref );
	if ( $alt ) update_post_meta( $id, '_wp_attachment_image_alt', $alt );
	WP_CLI::log( "Imported $ref -> attachment #$id" );
	return $cache[ $ref ] = $id;
}

/** Replaces {{img:file|alt}}, {{gallery:file,file,...|alt}} and {{logo:file|alt}} tokens. */
function rafiki_journal_render( $content ) {
	return preg_replace_callback( '/\{\{(img|gallery|logo):([^|}]+)\|([^}]*)\}\}/', function ( $m ) {
		list( , $type, $files, $alt ) = $m;
		$html = '';
		foreach ( array_map( 'trim', explode( ',', $files ) ) as $file ) {
			$id = rafiki_journal_image( 'journal:' . $file, $alt );
			if ( ! $id ) continue;
			$html .= sprintf( '<img src="%s" alt="%s" loading="lazy">', esc_url( wp_get_attachment_image_url( $id, 'large' ) ), esc_attr( $alt ) );
		}
		$class = array( 'img' => 'journal-figure', 'gallery' => 'journal-gallery', 'logo' => 'journal-press-logo' )[ $type ];
		return $html ? '<figure class="' . $class . '">' . $html . '</figure>' : '';
	}, $content );
}

function rafiki_journal_category( $name ) {
	$term = term_exists( $name, 'category' );
	if ( ! $term ) $term = wp_insert_term( $name, 'category' );
	return is_wp_error( $term ) ? 0 : (int) $term['term_id'];
}

$overwrite = defined( 'RAFIKI_SEED_OVERWRITE' ) && RAFIKI_SEED_OVERWRITE;

$articles = array(
	array(
		'slug'     => 'rafki-hosted-the-4th-leg-of-the-egravica-enduro-series',
		'title'    => 'Rafiki Hosted the 4th Leg of the Egravica Enduro Series',
		'date'     => '2026-05-22 15:09:41',
		'category' => 'News from the Lodge',
		'featured' => array( 'journal:enduro-1.webp', 'Egravica Enduro riders on the podium at Rafiki' ),
		'excerpt'  => 'Rain, rafting and a weekend of racing: Rafiki hosted the 4th leg of the Egravica Enduro Series, and the Rafiki "Bushmaster" team kept its lead.',
		'content'  => <<<'HTML'
<p>Rafiki hosted the 4th leg of the Egravica Enduro Series last weekend. The conditions were amazing for the race. It rained on Saturday afternoon, making things a bit more interesting. We took the riders and their families on a rafting trip, and had a fantastic evening before race day.</p>
{{gallery:enduro-2.webp,enduro-3.webp,enduro-4.webp,enduro-5.webp|Egravica Enduro weekend at Rafiki Safari Lodge}}
<p>The Rafiki "Bushmaster" team came away with 3 golds, 3 silver and 2 bronze medals, keeping our lead on the series.</p>
<p>Congratulations to all of the riders, and thanks for helping us save the forests of the Savegre Valley!</p>
{{gallery:enduro-6.webp,enduro-7.webp,enduro-8.webp,enduro-9.webp|Egravica Enduro podium at Rafiki Safari Lodge}}
HTML
	),
	array(
		'slug'     => 'a-visit-from-costa-rica-vacations',
		'title'    => 'A Visit from Costa Rica Vacations',
		'date'     => '2026-05-05 18:38:44',
		'category' => 'News from the Lodge',
		'featured' => array( 'theme:property-and-food-19', 'Aerial view of the Main Lodge and pool in the rainforest' ),
		'excerpt'  => 'Costa Rica Vacations (Namu Travel) has worked with Rafiki for over 20 years — they just visited and made an updated video of the lodge.',
		'content'  => <<<'HTML'
<p>We just had a visit from one of our partners. Costa Rica Vacations (Namu Travel) has been working with Rafiki for over 20 years. They made this fun updated video of the lodge. Enjoy!</p>
HTML
	),
	array(
		'slug'     => 'new-safari-truck',
		'title'    => 'New Safari Truck',
		'date'     => '2026-05-05 16:49:06',
		'category' => 'News from the Lodge',
		'featured' => array( 'journal:safari-truck.webp', 'Rafiki\'s new safari truck loaded with rafting guests' ),
		'excerpt'  => 'Meet Lorrie: our new 4x4 safari truck, built around 20+ years of rafting days on the Savegre.',
		'content'  => <<<'HTML'
<p>We are thrilled with the new Safari Truck. The Isuzu NPR chassis with 4WD has been a great base. Through the 20+ years of operation, our research and development team has taken into account our clients, the terrain, and the necessities of the whitewater rafting business.</p>
<p>The truck has bleacher seats for up to 20 people in the back, a custom-designed retractable staircase, and lots of room for dry storage up front. Her name is Lorrie — come meet her!</p>
HTML
	),
	array(
		'slug'     => 'news-from-the-lodge',
		'title'    => 'Back on Line',
		'date'     => '2026-04-29 18:19:59',
		'category' => 'News from the Lodge',
		'featured' => array( 'journal:back-on-line.webp', 'Sunset from the deck of the Main Lodge' ),
		'excerpt'  => 'Welcome to the new website — a short update on the years since the pandemic, and why Rafiki is fired up for the season ahead.',
		'content'  => <<<'HTML'
<p>Welcome to the new website. Rafiki has been diligently working on recovery since the all too famous pandemic 6 years ago. It was a challenge trying to stay afloat for 2 years with no business. We unfortunately had to say goodbye to Carlo and Janel, who have moved back to the United States. My dear mother passed away in 2023, and my father moved out of the country in late 2024. With all of these changes, life has been hectic. Hence our radio silence…</p>
<p>We are still here and fired up for the upcoming rainy season. We find more and more guests enjoying the ability to escape the craziness in the world with a cup of coffee, and the sounds of the tropical birds!</p>
HTML
	),
	array(
		'slug'     => 'rafiki-on-wonderlust',
		'title'    => 'Rafiki on Wonderlust',
		'date'     => '2026-03-18 00:33:51',
		'category' => 'In the Press',
		'featured' => array( 'theme:tent-sunset', 'Sunset over the valley from a safari tent' ),
		'excerpt'  => '"Paradise Regained" — a traveler\'s memory of meeting Constant, Rafiki\'s founder, years before the lodge became what it is today.',
		'content'  => <<<'HTML'
{{logo:logo-wonderlust.png|Wonderlust}}
<h2>Paradise Regained</h2>
<p>"On the first day I walked across the main road and noticed there was a chiropractor. Figured I'd give him a try. I walked into his office and he introduced himself as Constant from South Africa. He and his family had bought about 300 hectares of land comprising a mountain valley and river. His dream was to bring more Tapirs [large mammals related to horses and rhinoceroses] and Jaguars into the country, with the help of local government. As I was leaving his office, he asked me if I would like him to organize some tours and he arranged a zip line, horseback riding, nature trails and rafting, all at Rafiki lodge.</p>
<p>"The following year I returned with a girlfriend looking for him. His wife was distraught and told me that he was battling a bad case of Dengue fever. I took a room in Rafiki lodge but my girlfriend made life miserable because it was too buggy for her, so we checked out and went to stay at a place in town.</p>
<p>"To this day I never knew what happened with Constant."</p>
<p><a class="btn btn-primary" href="https://wonderlusttravel.com/rafiki-safari-lodge-costa-rica/" target="_blank" rel="noopener">Read more on Wonderlusttravel.com →</a></p>
HTML
	),
	array(
		'slug'     => 'rafiki-on-the-traveling-muggles-website',
		'title'    => 'Rafiki on the "Traveling Muggles" Website',
		'date'     => '2026-03-17 21:39:39',
		'category' => 'In the Press',
		'featured' => array( 'theme:tent-bridge', 'Footbridge up to a safari tent' ),
		'excerpt'  => '"Into the Jungle": a family travel blog on bringing the kids deep into the Costa Rican jungle at Rafiki.',
		'content'  => <<<'HTML'
{{logo:logo-traveling-muggles.png|The Traveling Muggles}}
<h2>Into the Jungle: Rafiki Safari Lodge</h2>
<p>"I consider myself to be an adventurous traveler, but it was time that I bring my family on the ultimate adventure deep into the Costa Rican Jungle. This brought us to Rafiki Safari Lodge, the most unique jungle adventure that we had the privilege to experience! Save this for your next family travel destination and…"</p>
<p><a class="btn btn-primary" href="https://travelingmuggles.com/2025/05/06/into-the-jungle-rafiki-safari-lodge/" target="_blank" rel="noopener">Read more on The Traveling Muggles →</a></p>
HTML
	),
	array(
		'slug'     => 'rafiki-safari-lodge-on-we-travel-responsible-a-danish-travel-website',
		'title'    => 'Rafiki Safari Lodge on "We Travel Responsible" — a Danish Travel Website',
		'date'     => '2026-03-17 20:47:07',
		'category' => 'In the Press',
		'featured' => array( 'theme:raftingfun', 'Rafting on the Savegre River' ),
		'excerpt'  => 'African safari style and rafting in the Costa Rican jungle: a translated review from Denmark\'s We Travel.',
		'content'  => <<<'HTML'
{{logo:logo-we-travel.png|We Travel Responsibly}}
<p><em>A translated version of the review. The original (in Danish) is on <a href="https://www.wetravel.dk/costa-rica/hoteller/rafiki-safari-lodge-savegre-dalen" target="_blank" rel="noopener">wetravel.dk</a>.</em></p>
<h2>African safari style and rafting in the Costa Rican jungle</h2>
<p>Rafiki Safari Lodge is a very positive experience and surprise. A bit of a mecca for nature lovers and active families: Rafting, hiking through the jungle to beautiful waterfalls, a trip further up into the mountains with lunch with a local family. And we absolutely love that the foundation of the lodge's existence is the preservation of nature and local communities.</p>
<h2>A family dream that came true</h2>
<p>Rafiki Safari Lodge is the result of the Boshoff family's dream of creating a natural paradise in collaboration with the local community. With tents and activities like on a safari, but with the jungle-covered Savegre Valley in Costa Rica as the setting.</p>
<blockquote><p>I've never been on safari, but I've been in the jungle a lot. The owner bought the tents in South Africa, so they're authentic enough. And it's simply amazing to fall asleep in a tent and wake up to the sounds and smells of the jungle.</p><cite>Karin, We Travel's expert on Costa Rica</cite></blockquote>
<p>Today, the second generation runs Rafiki. Lautjie and his wife Maureen are the welcoming hosts — and the driving force behind the local commitment and the designation of the area as a UNESCO biosphere reserve.</p>
<p>Lautjie, a biologist by background, has fought for the conservation of nature in the Savegre Valley. This has happened in close collaboration with the local community in Santo Domingo with the shared mission of making sustainable tourism a long-term source of income. For the benefit of both nature, wildlife and local communities.</p>
<p>Most of the employees come from Santo Domingo. Rafiki is actually the largest employer in the area. Many have been part of the project from the very beginning and are deeply involved in running the place.</p>
<p>The pride and ownership you will experience from the staff would be hard to find from employees who came from afar. And Rafiki's impact on the small local community is truly enormous — because it has brought jobs and education to a place where there were not many opportunities otherwise. Rafiki trains its own nature and rafting guides, who thus have the opportunity to service guests in their own backyard.</p>
<h2>Rafting, horse riding and jungle treks</h2>
<p>One of the highlights of Rafiki is rafting through the jungle on the clear Savegre River. It's not wild rafting, but a child-friendly version, where the clean and clear river, the surroundings and the beautiful waterfall you stop at are just as important as the speed in the rubber boat.</p>
<p>The area also offers plenty of jungle trails that can be explored both with and without a guide, and both on foot and on horseback.</p>
<h2>Natural pool and restaurant with a view</h2>
<p>The open restaurant is the heart of the camp. The food can best be described as local and honest, but don't expect great gourmet experiences. On the other hand, the restaurant provides the perfect setting for a sun-downer with direct views of the river, jungle and valley.</p>
<p>The kids will quickly find their way to the water slide that snakes from the restaurant down to the natural pool.</p>
<h2>Do you have more time? Take the kayak to Rafiki Beach Camp</h2>
<p>On Matapalo Beach on the coast, Rafiki has set up 4 safari tents and created a small beach camp. You can actually kayak through the jungle and mangrove all the way from Rafiki Safari Camp to the beach — and replace the song of cicadas with the sound of the waves.</p>
<p>Matapalo is by no means a fancy beach destination. There are only locals, a few restaurants and a supermarket. But it is a fun stop on the road and a pleasant alternative to the overcrowded beaches further north.</p>
HTML
	),
	array(
		'slug'     => 'rafiki-featured-on-pura-vida-traveling',
		'title'    => 'Rafiki Featured on Pura Vida Traveling',
		'date'     => '2025-12-24 07:41:49',
		'category' => 'In the Press',
		'featured' => array( 'theme:property-and-food-22', 'Luxury safari tent deck surrounded by rainforest' ),
		'excerpt'  => '"The Ultimate Eco-Lodge Experience": Pura Vida Traveling\'s guide to staying at Rafiki — tents, activities, food and conservation.',
		'content'  => <<<'HTML'
{{logo:logo-pura-vida-traveling.png|Pura Vida Traveling}}
<h2>Rafiki Safari Lodge, Costa Rica: The Ultimate Eco-Lodge Experience</h2>
<p>Costa Rica is renowned for its breathtaking landscapes, lush rainforests, and abundant wildlife. If you're looking for an immersive nature experience that combines adventure, comfort, and sustainability, Rafiki Safari Lodge is the PERFECT destination. We had the chance to stay there for one night, and we were delighted. Located in the heart of the rainforest near Manuel Antonio and Dominical, this eco-lodge offers a unique blend of African-style safari tents, outdoor adventures, and conservation efforts. In this guide, we'll explore everything you need to know about Rafiki Safari Lodge, including accommodations, activities, dining, and why it should be on your travel itinerary.</p>
<p><a class="btn btn-primary" href="https://www.puravidatraveling.com/post/rafiki-safari-lodge-costa-rica-experience" target="_blank" rel="noopener">Go to the article →</a></p>
HTML
	),
);

foreach ( $articles as $a ) {
	$cat_id   = rafiki_journal_category( $a['category'] );
	$existing = get_page_by_path( $a['slug'], OBJECT, 'post' );

	if ( $existing && ! $overwrite ) {
		$post_id = $existing->ID;
		WP_CLI::log( "Kept \"{$a['title']}\" (#$post_id) — exists already, content left untouched." );
	} else {
		$data = array(
			'post_type'     => 'post',
			'post_title'    => $a['title'],
			'post_name'     => $a['slug'],
			'post_content'  => rafiki_journal_render( $a['content'] ),
			'post_excerpt'  => $a['excerpt'],
			'post_status'   => 'publish',
			'post_date'     => $a['date'],
			'post_category' => $cat_id ? array( $cat_id ) : array(),
		);
		if ( $existing ) $data['ID'] = $existing->ID;
		$post_id = $existing ? wp_update_post( $data, true ) : wp_insert_post( $data, true );
		if ( is_wp_error( $post_id ) ) {
			WP_CLI::warning( "Could not save \"{$a['title']}\": " . $post_id->get_error_message() );
			continue;
		}
		WP_CLI::success( ( $existing ? 'Updated' : 'Created' ) . " \"{$a['title']}\" (#$post_id) at /{$a['slug']}/" );
	}

	if ( ! has_post_thumbnail( $post_id ) || $overwrite ) {
		$thumb = rafiki_journal_image( $a['featured'][0], $a['featured'][1] );
		if ( $thumb ) set_post_thumbnail( $post_id, $thumb );
	}
}

// WordPress's default "Hello world!" sample post would show up in the Journal.
$hello = get_page_by_path( 'hello-world', OBJECT, 'post' );
if ( $hello ) {
	wp_delete_post( $hello->ID, true );
	WP_CLI::log( 'Deleted the default "Hello world!" post.' );
}

// Contact page (template: page-templates/template-contact.php).
if ( ! get_page_by_path( 'contact', OBJECT, 'page' ) ) {
	$contact_id = wp_insert_post( array(
		'post_type'   => 'page',
		'post_title'  => 'Contact',
		'post_name'   => 'contact',
		'post_status' => 'publish',
	), true );
	if ( ! is_wp_error( $contact_id ) ) {
		update_post_meta( $contact_id, '_wp_page_template', 'page-templates/template-contact.php' );
		WP_CLI::success( "Created page \"Contact\" (#$contact_id)." );
	}
}

WP_CLI::success( 'Old journal articles imported.' );
