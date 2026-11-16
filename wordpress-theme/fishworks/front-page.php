<?php
/**
 * Fishworks home page. Edit content in Appearance → Customize → Fishworks.
 */
get_header();
$fw_lure = fw_lure_url();
?>
<main id="main">
	<section class="hero">
		<div class="logo"><?php echo fw_logo( 'fishworks' ); // phpcs:ignore ?></div>
		<h1 class="screen-reader-text"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h1>
		<p class="label"><?php echo esc_html( fw_mod( 'fw_hero_tag' ) ); ?></p>
		<a href="#reserve" class="btn"><?php echo esc_html( fw_mod( 'fw_hero_btn' ) ); ?></a>
		<span class="scroll" aria-hidden="true"></span>
	</section>

	<div class="bleed"><?php echo fw_image( 'fw_bleed_img', '100vw' ); // phpcs:ignore ?></div>

	<section class="intro wrap" id="food">
		<p class="label reveal"><?php echo esc_html( fw_mod( 'fw_intro_label' ) ); ?></p>
		<h2 class="serif reveal"><?php echo fw_rich( 'fw_intro_heading' ); // phpcs:ignore ?></h2>
		<p class="reveal"><?php echo fw_rich( 'fw_intro_text' ); // phpcs:ignore ?></p>
	</section>

	<section class="wrap gallery">
		<?php
		$fw_sizes = array(
			1 => '(max-width:860px) 100vw, 40vw',
			2 => '(max-width:860px) 100vw, 58vw',
			3 => '(max-width:860px) 50vw, 33vw',
			4 => '(max-width:860px) 50vw, 33vw',
			5 => '(max-width:860px) 100vw, 33vw',
		);
		for ( $i = 1; $i <= 5; $i++ ) :
			$cap = fw_mod( "fw_g{$i}_cap" );
			?>
			<figure class="g<?php echo (int) $i; ?> reveal">
				<?php echo fw_image( "fw_g{$i}_img", $fw_sizes[ $i ] ); // phpcs:ignore ?>
				<?php if ( $cap ) : ?><figcaption><?php echo esc_html( $cap ); ?></figcaption><?php endif; ?>
			</figure>
		<?php endfor; ?>
	</section>

	<section class="split">
		<div class="img"><?php echo fw_image( 'fw_split_img', '(max-width:860px) 100vw, 50vw' ); // phpcs:ignore ?></div>
		<div class="txt">
			<p class="label reveal"><?php echo esc_html( fw_mod( 'fw_split_label' ) ); ?></p>
			<h2 class="serif reveal"><?php echo fw_rich( 'fw_split_heading' ); // phpcs:ignore ?></h2>
			<p class="reveal"><?php echo fw_rich( 'fw_split_text' ); // phpcs:ignore ?></p>
			<p class="reveal" style="margin-top:30px"><a href="#reserve" class="btn btn-green">Reserve</a></p>
		</div>
	</section>

	<section class="reserve" id="reserve">
		<div class="wrap grid">
			<div>
				<p class="label">Reservations</p>
				<h2 class="serif"><?php echo fw_rich( 'fw_res_heading' ); // phpcs:ignore ?></h2>
				<p><?php echo fw_rich( 'fw_res_text' ); // phpcs:ignore ?></p>
			</div>
			<?php echo fw_booking_widget(); // phpcs:ignore ?>
		</div>
	</section>

	<?php if ( $fw_lure && fw_mod( 'fw_show_door' ) ) : ?>
	<a class="door" href="<?php echo esc_url( $fw_lure ); ?>">
		<div class="lure"><?php echo fw_logo( 'lure' ); // phpcs:ignore ?></div>
		<p class="label"><?php echo esc_html( fw_mod( 'fw_door_text' ) ); ?></p>
		<span class="go"><?php echo esc_html( fw_mod( 'fw_door_link' ) ); ?> &rarr;</span>
	</a>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/visit' ); ?>
</main>
<?php
get_footer();
