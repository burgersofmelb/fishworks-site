<?php
/**
 * Fallback template: posts, archives, search and 404.
 */
get_header();
?>
<main id="main">
	<header class="page-head wrap">
		<p class="label"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
		<h1 class="serif">
			<?php
			if ( is_404() ) {
				echo 'Page not found';
			} elseif ( is_search() ) {
				echo 'Search: ' . esc_html( get_search_query() );
			} elseif ( is_archive() ) {
				echo esc_html( wp_strip_all_tags( get_the_archive_title() ) );
			} elseif ( is_singular() ) {
				single_post_title();
			} else {
				echo 'News';
			}
			?>
		</h1>
	</header>

	<?php if ( is_404() || ! have_posts() ) : ?>
		<div class="entry" style="text-align:center">
			<p>Sorry, there’s nothing here.</p>
			<p><a class="btn" href="<?php echo esc_url( home_url( '/' ) ); ?>">Back to Fishworks</a></p>
		</div>
	<?php elseif ( is_singular() ) : ?>
		<?php
		while ( have_posts() ) :
			the_post();
			if ( has_post_thumbnail() ) {
				echo '<div class="page-feature">';
				the_post_thumbnail( 'full' );
				echo '</div>';
			}
			echo '<article class="entry">';
			the_content();
			echo '</article>';
		endwhile;
		?>
	<?php else : ?>
		<div class="posts">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article>
					<p class="label"><?php echo esc_html( get_the_date() ); ?></p>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<div class="entry" style="padding:0;margin:0"><?php the_excerpt(); ?></div>
				</article>
			<?php endwhile; ?>
		</div>
		<div class="pagination"><?php the_posts_pagination(); ?></div>
	<?php endif; ?>
</main>
<?php
get_footer();
