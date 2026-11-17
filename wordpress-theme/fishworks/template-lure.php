<?php
/**
 * Template Name: Lure
 *
 * The Lure page: its own dark, neon look. The text under the big heading
 * comes from this page's content; everything else is in
 * Appearance → Customize → Fishworks → Lure page.
 */
$fw_cta_url = fw_mod( 'fw_lure_cta_url' );
$fw_cta_url = $fw_cta_url ? $fw_cta_url : fw_reserve_url();
$fw_words   = array_filter( array_map( 'trim', explode( ',', fw_mod( 'fw_lure_marquee' ) ) ) );
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#0b0909">
<?php wp_head(); ?>
</head>
<body <?php body_class( 'lure' ); ?>>
<?php wp_body_open(); ?>

<header>
	<nav class="wrap" aria-label="Lure">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="back">&larr; <?php echo esc_html( get_bloginfo( 'name' ) ); ?></a>
		<div class="links">
			<?php if ( fw_private_dining_url() ) : ?><a href="<?php echo esc_url( fw_private_dining_url() ); ?>" class="hide-sm">Private dining</a><?php endif; ?>
			<?php if ( fw_mod( 'fw_voucher_url' ) ) : ?><a href="<?php echo esc_url( fw_mod( 'fw_voucher_url' ) ); ?>" class="hide-sm" target="_blank" rel="noopener">Gift vouchers</a><?php endif; ?>
			<a href="<?php echo esc_url( $fw_cta_url ); ?>" class="btn">Reserve</a>
		</div>
	</nav>
</header>

<main id="main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<section class="hero">
			<div class="logo"><?php echo fw_logo( 'lure' ); // phpcs:ignore ?></div>
			<h1 class="screen-reader-text"><?php the_title(); ?></h1>
			<p class="tag serif"><?php echo esc_html( fw_mod( 'fw_lure_tag' ) ); ?></p>
			<p class="label"><?php echo esc_html( fw_mod( 'fw_lure_label' ) ); ?></p>
		</section>

		<section class="strip">
			<figure><?php echo fw_image( 'fw_lure_s1_img', '(max-width:760px) 50vw, 33vw' ); // phpcs:ignore ?></figure>
			<figure><?php echo fw_image( 'fw_lure_s2_img', '(max-width:760px) 50vw, 33vw' ); // phpcs:ignore ?></figure>
			<figure><?php echo fw_image( 'fw_lure_s3_img', '(max-width:760px) 100vw, 33vw' ); // phpcs:ignore ?></figure>
		</section>

		<section class="about wrap">
			<p class="label reveal"><?php the_title(); ?></p>
			<h2 class="serif reveal"><?php echo fw_rich( 'fw_lure_heading' ); // phpcs:ignore ?></h2>
			<div class="entry reveal">
				<?php
				if ( trim( get_the_content() ) ) {
					the_content();
				} else {
					echo '<p>More on Lure soon. Until then, book a table at Fishworks and ask about it when you arrive.</p>';
				}
				?>
			</div>
		</section>
	<?php endwhile; ?>

	<?php if ( $fw_words ) : ?>
		<?php
		$fw_line = '';
		foreach ( array_merge( $fw_words, $fw_words ) as $w ) {
			$fw_line .= esc_html( $w ) . ' <b>·</b> ';
		}
		?>
		<div class="marquee" aria-hidden="true"><span><?php echo $fw_line; // phpcs:ignore ?>&nbsp;</span><span><?php echo $fw_line; // phpcs:ignore ?>&nbsp;</span></div>
	<?php endif; ?>

	<section class="cta">
		<p class="label"><?php echo esc_html( fw_mod( 'fw_lure_cta_label' ) ); ?></p>
		<p><?php echo fw_rich( 'fw_lure_cta_text' ); // phpcs:ignore ?></p>
		<a href="<?php echo esc_url( $fw_cta_url ); ?>" class="btn"><?php echo esc_html( fw_mod( 'fw_lure_cta_btn' ) ); ?></a>
	</section>
</main>

<footer>
	<div class="wrap">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>">&larr; Back to <?php echo esc_html( get_bloginfo( 'name' ) ); ?></a>
		<span><?php echo esc_html( str_replace( "\n", ', ', fw_mod( 'fw_address' ) ) ); ?></span>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
