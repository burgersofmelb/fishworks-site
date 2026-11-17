<?php
/**
 * Fishworks theme functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'FW_VERSION', '1.1.0' );

require_once get_template_directory() . '/inc/enquiry.php';

/* -------------------------------------------------------------------------
 * Defaults (used until something is changed in Appearance → Customize)
 * ---------------------------------------------------------------------- */
function fw_defaults() {
	return array(
		// Venue details.
		'fw_address'       => "31 Fitzroy Street\nSt Kilda VIC 3182",
		'fw_maps_url'      => 'https://maps.google.com/?q=31+Fitzroy+Street,+St+Kilda+VIC+3182',
		'fw_hours'         => "Opening hours\ncoming soon",
		'fw_phone'         => '',
		'fw_email'         => '',
		'fw_instagram'     => '',
		'fw_obee_account'  => 'fishworks',
		'fw_voucher_url'   => 'https://vouchers.obeeapp.com/fishworks/gift-voucher',
		'fw_enquiry_email' => 'info@fishworks.co',
		'fw_enquiry_thanks'=> 'Our team at Fishworks & Lure has received your enquiry and will contact you soon.',

		// Home page.
		'fw_hero_tag'      => '31 Fitzroy Street · St Kilda',
		'fw_hero_btn'      => 'Book a table',
		'fw_bleed_img'     => '',
		'fw_intro_label'   => 'Fishworks',
		'fw_intro_heading' => 'Oysters on ice, whole fish off the grill.',
		'fw_intro_text'    => 'Seafood cooked simply and served to share, on Fitzroy Street in St Kilda.',
		'fw_g1_img'        => '', 'fw_g1_cap' => 'Oysters',
		'fw_g2_img'        => '', 'fw_g2_cap' => 'Crayfish',
		'fw_g3_img'        => '', 'fw_g3_cap' => 'Prawns',
		'fw_g4_img'        => '', 'fw_g4_cap' => 'Scallops',
		'fw_g5_img'        => '', 'fw_g5_cap' => 'From the grill',
		'fw_split_img'     => '',
		'fw_split_label'   => 'The table',
		'fw_split_heading' => "Bring people.\nOrder the whole fish.",
		'fw_split_text'    => 'Plates made for the middle of the table, and a wine list to go with them.',
		'fw_res_heading'   => 'Book a table.',
		'fw_res_text'      => 'Choose a date and time below. For larger groups, get in touch and we’ll look after you.',
		'fw_show_door'     => true,
		'fw_door_text'     => 'Also at 31 Fitzroy Street',
		'fw_door_link'     => 'Enter Lure',

		// Lure page.
		'fw_lure_tag'      => 'After dark at Fishworks.',
		'fw_lure_label'    => '31 Fitzroy Street · St Kilda',
		'fw_lure_s1_img'   => '',
		'fw_lure_s2_img'   => '',
		'fw_lure_s3_img'   => '',
		'fw_lure_heading'  => 'Low light, cold glasses, <em>one more.</em>',
		'fw_lure_marquee'  => 'Champagne, Oysters, Late, Lure',
		'fw_lure_cta_label'=> 'Come in',
		'fw_lure_cta_text' => 'Tables and bar seats are booked through Fishworks.',
		'fw_lure_cta_btn'  => 'Book now',
		'fw_lure_cta_url'  => '',
	);
}

/** Bundled photo used when no image has been chosen in the Customizer. */
function fw_default_images() {
	return array(
		'fw_bleed_img'   => array( 'spread', 'Whole fish, crumbed fish and steak across a marble table' ),
		'fw_g1_img'      => array( 'oysters', 'A dozen oysters with mignonette and lemon on ice' ),
		'fw_g2_img'      => array( 'crayfish', 'Grilled crayfish with herb butter, fries and rocket' ),
		'fw_g3_img'      => array( 'prawns', 'Grilled king prawns with garlic, chilli and lemon' ),
		'fw_g4_img'      => array( 'scallops', 'Baked scallops in the shell with crumb and chilli' ),
		'fw_g5_img'      => array( 'steak', 'Grilled steak on the bone with broccolini' ),
		'fw_split_img'   => array( 'whole-fish', 'Whole grilled fish with chilli, coriander and lemon' ),
		'fw_lure_s1_img' => array( 'champagne', 'Champagne bottles on a marble bar' ),
		'fw_lure_s2_img' => array( 'fish-red-wine', 'A glass of red wine beside a whole grilled fish' ),
		'fw_lure_s3_img' => array( 'crayfish-champagne', 'Crayfish and fries with a flute of champagne' ),
	);
}

function fw_mod( $key ) {
	$d = fw_defaults();
	return get_theme_mod( $key, isset( $d[ $key ] ) ? $d[ $key ] : '' );
}

/** Text that may contain line breaks and <em>. */
function fw_rich( $key ) {
	return nl2br( wp_kses( fw_mod( $key ), array( 'em' => array(), 'br' => array(), 'strong' => array() ) ) );
}

/** Output an image from the Customizer (media ID) or the bundled default. */
function fw_image( $key, $sizes = '100vw', $lazy = true ) {
	$id   = absint( get_theme_mod( $key ) );
	$defs = fw_default_images();
	$alt  = isset( $defs[ $key ] ) ? $defs[ $key ][1] : '';
	if ( $id && wp_attachment_is_image( $id ) ) {
		$img_alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
		return wp_get_attachment_image(
			$id,
			'full',
			false,
			array(
				'sizes'   => $sizes,
				'alt'     => $img_alt ? $img_alt : get_the_title( $id ),
				'loading' => $lazy ? 'lazy' : 'eager',
			)
		);
	}
	if ( ! isset( $defs[ $key ] ) ) {
		return '';
	}
	$base = get_template_directory_uri() . '/assets/img/' . $defs[ $key ][0];
	return sprintf(
		'<img src="%1$s-1400.jpg" srcset="%1$s-800.jpg 800w, %1$s-1400.jpg 1400w" sizes="%2$s" alt="%3$s" width="1400" height="1750"%4$s>',
		esc_url( $base ),
		esc_attr( $sizes ),
		esc_attr( $alt ),
		$lazy ? ' loading="lazy"' : ''
	);
}

/** Inline SVG logo: 'fishworks' or 'lure'. */
function fw_logo( $which = 'fishworks' ) {
	$file = get_template_directory() . '/assets/img/' . ( 'lure' === $which ? 'lure' : 'fishworks' ) . '-logo.svg';
	return file_exists( $file ) ? file_get_contents( $file ) : esc_html( get_bloginfo( 'name' ) ); // phpcs:ignore
}

/** URL of the first published page using a given template. */
function fw_template_url( $template ) {
	$pages = get_pages(
		array(
			'meta_key'   => '_wp_page_template',
			'meta_value' => $template,
			'number'     => 1,
		)
	);
	return $pages ? get_permalink( $pages[0] ) : '';
}

function fw_lure_url() {
	return fw_template_url( 'template-lure.php' );
}

function fw_private_dining_url() {
	return fw_template_url( 'template-private-dining.php' );
}

function fw_reserve_url() {
	return home_url( '/#reserve' );
}

/* -------------------------------------------------------------------------
 * Setup
 * ---------------------------------------------------------------------- */
add_action(
	'after_setup_theme',
	function () {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
		register_nav_menus(
			array(
				'primary' => __( 'Main menu', 'fishworks' ),
			)
		);
	}
);

add_action(
	'wp_enqueue_scripts',
	function () {
		$uri  = get_template_directory_uri();
		$lure = is_page_template( 'template-lure.php' );

		if ( $lure ) {
			wp_enqueue_style( 'fw-fonts', 'https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500&family=Instrument+Serif:ital@1&display=swap', array(), null );
			wp_enqueue_style( 'fw-lure', $uri . '/assets/css/lure.css', array(), FW_VERSION );
		} else {
			wp_enqueue_style( 'fw-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&family=Instrument+Serif:ital@0;1&display=swap', array(), null );
			wp_enqueue_style( 'fw-main', $uri . '/assets/css/fishworks.css', array(), FW_VERSION );
		}
		wp_enqueue_script( 'fw-site', $uri . '/assets/js/site.js', array(), FW_VERSION, true );
		wp_register_script( 'obee', 'https://bookings.obeeapp.com/iframe.js', array(), null, true );
		wp_register_script( 'fw-enquiry', $uri . '/assets/js/enquiry.js', array(), FW_VERSION, true );
	}
);

add_filter(
	'wp_resource_hints',
	function ( $urls, $type ) {
		if ( 'preconnect' === $type ) {
			$urls[] = 'https://fonts.googleapis.com';
			$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
		}
		return $urls;
	},
	10,
	2
);

/** Mark the menu item that points at the Lure page so it gets the neon dot. */
add_filter(
	'nav_menu_css_class',
	function ( $classes, $item ) {
		if ( 'page' === $item->object && 'template-lure.php' === get_page_template_slug( $item->object_id ) ) {
			$classes[] = 'lure-link';
		}
		return $classes;
	},
	10,
	2
);

/** Menu shown until a menu is assigned under Appearance → Menus. */
function fw_fallback_menu() {
	$home = home_url( '/' );
	$lure = fw_lure_url();
	echo '<ul class="nav-list">';
	echo '<li><a href="' . esc_url( $home . '#food' ) . '">Food</a></li>';
	echo '<li><a href="' . esc_url( $home . '#visit' ) . '">Visit</a></li>';
	if ( $lure ) {
		echo '<li class="lure-link"><a href="' . esc_url( $lure ) . '">Lure</a></li>';
	}
	$pd = fw_private_dining_url();
	if ( $pd ) {
		echo '<li' . ( is_page() && get_permalink() === $pd ? ' class="current-menu-item"' : '' ) . '><a href="' . esc_url( $pd ) . '">Private dining</a></li>';
	}
	$vouchers = fw_mod( 'fw_voucher_url' );
	if ( $vouchers ) {
		echo '<li><a href="' . esc_url( $vouchers ) . '" target="_blank" rel="noopener">Gift vouchers</a></li>';
	}
	echo '</ul>';
}

/* -------------------------------------------------------------------------
 * Booking widget: [fishworks_booking] can be used on any page.
 * ---------------------------------------------------------------------- */
function fw_booking_widget() {
	wp_enqueue_script( 'obee' );
	return sprintf(
		'<div class="widget"><div class="obee-widget" account="%s" hide-header="false" min-height="450" add-height="50" scrolltop="true" widget-type="booking"></div></div>',
		esc_attr( fw_mod( 'fw_obee_account' ) )
	);
}
add_shortcode( 'fishworks_booking', 'fw_booking_widget' );

/* -------------------------------------------------------------------------
 * Customizer
 * ---------------------------------------------------------------------- */
add_action(
	'customize_register',
	function ( $wp ) {
		$d = fw_defaults();

		$wp->add_panel( 'fw_panel', array( 'title' => 'Fishworks', 'priority' => 20 ) );
		$wp->add_section( 'fw_info', array( 'title' => 'Venue details', 'panel' => 'fw_panel' ) );
		$wp->add_section( 'fw_home', array( 'title' => 'Home page', 'panel' => 'fw_panel' ) );
		$wp->add_section( 'fw_lure', array( 'title' => 'Lure page', 'panel' => 'fw_panel' ) );

		$text = function ( $key, $label, $section, $type = 'text', $desc = '' ) use ( $wp, $d ) {
			$wp->add_setting(
				$key,
				array(
					'default'           => $d[ $key ],
					'sanitize_callback' => 'textarea' === $type ? 'fw_sanitize_rich' : ( 'url' === $type ? 'esc_url_raw' : 'sanitize_text_field' ),
				)
			);
			$wp->add_control( $key, array( 'label' => $label, 'section' => $section, 'type' => $type, 'description' => $desc ) );
		};
		$image = function ( $key, $label, $section ) use ( $wp ) {
			$wp->add_setting( $key, array( 'default' => '', 'sanitize_callback' => 'absint' ) );
			$wp->add_control(
				new WP_Customize_Media_Control(
					$wp,
					$key,
					array( 'label' => $label, 'section' => $section, 'mime_type' => 'image', 'description' => 'Leave empty to use the shoot photo.' )
				)
			);
		};

		// Venue details.
		$text( 'fw_address', 'Address', 'fw_info', 'textarea' );
		$text( 'fw_maps_url', 'Google Maps link', 'fw_info', 'url' );
		$text( 'fw_hours', 'Opening hours', 'fw_info', 'textarea', 'One line per day or range.' );
		$text( 'fw_phone', 'Phone', 'fw_info' );
		$text( 'fw_email', 'Email', 'fw_info', 'email' );
		$text( 'fw_instagram', 'Instagram link', 'fw_info', 'url' );
		$text( 'fw_obee_account', 'Obee booking account', 'fw_info', 'text', 'Used by the booking widget and [fishworks_booking].' );
		$text( 'fw_voucher_url', 'Gift voucher link', 'fw_info', 'url', 'Leave empty to hide the Gift vouchers link.' );
		$text( 'fw_enquiry_email', 'Private dining enquiries go to', 'fw_info', 'email' );
		$text( 'fw_enquiry_thanks', 'Message shown after an enquiry is sent', 'fw_info', 'textarea' );

		// Home page.
		$text( 'fw_hero_tag', 'Hero line under logo', 'fw_home' );
		$text( 'fw_hero_btn', 'Hero button text', 'fw_home' );
		$image( 'fw_bleed_img', 'Full-width photo', 'fw_home' );
		$text( 'fw_intro_label', 'Intro small label', 'fw_home' );
		$text( 'fw_intro_heading', 'Intro heading', 'fw_home', 'textarea' );
		$text( 'fw_intro_text', 'Intro text', 'fw_home', 'textarea' );
		for ( $i = 1; $i <= 5; $i++ ) {
			$image( "fw_g{$i}_img", "Gallery photo {$i}", 'fw_home' );
			$text( "fw_g{$i}_cap", "Gallery caption {$i}", 'fw_home' );
		}
		$image( 'fw_split_img', 'Feature photo', 'fw_home' );
		$text( 'fw_split_label', 'Feature small label', 'fw_home' );
		$text( 'fw_split_heading', 'Feature heading', 'fw_home', 'textarea' );
		$text( 'fw_split_text', 'Feature text', 'fw_home', 'textarea' );
		$text( 'fw_res_heading', 'Reservations heading', 'fw_home' );
		$text( 'fw_res_text', 'Reservations text', 'fw_home', 'textarea' );
		$wp->add_setting( 'fw_show_door', array( 'default' => true, 'sanitize_callback' => 'wp_validate_boolean' ) );
		$wp->add_control( 'fw_show_door', array( 'label' => 'Show the Lure band on the home page', 'section' => 'fw_home', 'type' => 'checkbox' ) );
		$text( 'fw_door_text', 'Lure band text', 'fw_home' );
		$text( 'fw_door_link', 'Lure band link text', 'fw_home' );

		// Lure page.
		$text( 'fw_lure_tag', 'Line under the neon logo', 'fw_lure' );
		$text( 'fw_lure_label', 'Small address line', 'fw_lure' );
		$image( 'fw_lure_s1_img', 'Photo 1', 'fw_lure' );
		$image( 'fw_lure_s2_img', 'Photo 2', 'fw_lure' );
		$image( 'fw_lure_s3_img', 'Photo 3', 'fw_lure' );
		$text( 'fw_lure_heading', 'Big heading', 'fw_lure', 'textarea', 'Wrap words in <em>…</em> to make them neon. The text under it comes from the Lure page itself (Pages → Lure).' );
		$text( 'fw_lure_marquee', 'Scrolling words', 'fw_lure', 'text', 'Comma separated.' );
		$text( 'fw_lure_cta_label', 'Booking small label', 'fw_lure' );
		$text( 'fw_lure_cta_text', 'Booking text', 'fw_lure', 'textarea' );
		$text( 'fw_lure_cta_btn', 'Booking button text', 'fw_lure' );
		$text( 'fw_lure_cta_url', 'Booking button link', 'fw_lure', 'url', 'Leave empty to use the Fishworks booking section.' );
	}
);

function fw_sanitize_rich( $value ) {
	return wp_kses( $value, array( 'em' => array(), 'br' => array(), 'strong' => array() ) );
}
