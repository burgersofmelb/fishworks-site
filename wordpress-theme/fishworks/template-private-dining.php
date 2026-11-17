<?php
/**
 * Template Name: Private Dining
 *
 * Enquiry page. The intro text on the left comes from this page's content;
 * the form sends to the address set in Appearance → Customize → Fishworks →
 * Venue details.
 */
get_header();
?>
<main id="main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<header class="page-head wrap">
			<p class="label">Fishworks &amp; Lure</p>
			<h1 class="serif"><?php the_title(); ?></h1>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="page-feature"><?php the_post_thumbnail( 'full', array( 'sizes' => '(max-width:1240px) 100vw, 1240px' ) ); ?></div>
		<?php else : ?>
			<div class="page-feature"><?php echo fw_image( 'fw_bleed_img', '(max-width:1240px) 100vw, 1240px', false ); // phpcs:ignore ?></div>
		<?php endif; ?>

		<section class="pd wrap">
			<div class="pd-intro">
				<p class="label">Enquire</p>
				<div class="entry">
					<?php
					if ( trim( get_the_content() ) ) {
						the_content();
					} else {
						echo '<p>Celebrating something, or bringing the team together? Tell us about your group and our team will be in touch to plan the details.</p>';
					}
					?>
				</div>
			</div>
			<div class="pd-form">
				<?php echo fw_enquiry_form(); // phpcs:ignore ?>
			</div>
		</section>
	<?php endwhile; ?>
	<?php get_template_part( 'template-parts/visit' ); ?>
</main>
<?php
get_footer();
