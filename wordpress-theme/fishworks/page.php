<?php
/**
 * Standard page in the Fishworks style (Menu, Functions, Contact etc).
 */
get_header();
?>
<main id="main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<header class="page-head wrap">
			<p class="label"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
			<h1 class="serif"><?php the_title(); ?></h1>
		</header>
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="page-feature"><?php the_post_thumbnail( 'full', array( 'sizes' => '(max-width:1240px) 100vw, 1240px' ) ); ?></div>
		<?php endif; ?>
		<article class="entry">
			<?php the_content(); ?>
		</article>
	<?php endwhile; ?>
	<?php get_template_part( 'template-parts/visit' ); ?>
</main>
<?php
get_footer();
