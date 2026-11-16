<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="screen-reader-text" href="#main">Skip to content</a>

<header class="site-header" id="top">
	<nav class="wrap" aria-label="Main">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mark" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> home"><?php echo fw_logo( 'fishworks' ); // phpcs:ignore ?></a>
		<div class="links">
			<button class="menu-toggle" aria-expanded="false" aria-controls="primary-menu">Menu</button>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'nav-list',
					'menu_id'        => 'primary-menu',
					'depth'          => 1,
					'fallback_cb'    => 'fw_fallback_menu',
				)
			);
			?>
			<a href="<?php echo esc_url( fw_reserve_url() ); ?>" class="btn">Reserve</a>
		</div>
	</nav>
</header>
