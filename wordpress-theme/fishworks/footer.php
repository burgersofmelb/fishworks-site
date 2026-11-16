<footer class="site-footer">
	<div class="wrap">
		<span class="mark"><?php echo fw_logo( 'fishworks' ); // phpcs:ignore ?></span>
		<span><?php echo esc_html( str_replace( "\n", ', ', fw_mod( 'fw_address' ) ) ); ?></span>
		<span>&copy; <span class="yr"><?php echo esc_html( gmdate( 'Y' ) ); ?></span> <?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
