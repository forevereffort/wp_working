<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php
	$sfs = intval( get_query_var( 'sfs' ) );

	if( ( is_home() || is_front_page() ) && $sfs == 0 ){
		if( have_posts() ){
			while( have_posts() ){
				the_post();
?>
<div class="home-hero-section">
	<header>
		<div class="header-container">
			<div class="header-row">
				<div class="logo">
					<a href="<?php echo site_url(); ?>"><img src="<?php the_field( 'logo', 'option'); ?>" alt="logo" /></a>
				</div>
				<div class="humbeger-btn">
					<span></span>
					<span></span>
					<span></span>
				</div>
				<?php
					wp_nav_menu(
						array(
							'container' => 'nav',
							'container_class' => 'primary-menu',
							'theme_location' => 'primary',
						)
					);
				?>
			</div>
			<div class="primary-mobile-wrapper" style="display: none;">
				<?php
					wp_nav_menu(
						array(
							'container' => 'nav',
							'container_class' => 'primary-menu',
							'theme_location'  => 'primary',
						)
					);
				?>
			</div>
		</div>
	</header>
	<div class="home-hero-container">
		<div class="home-hero-content">
			<div class="home-hero-title">
				<h2><?php the_field('hero_title'); ?></h2>
			</div>
			<div class="home-hero-btn">
				<a href="<?php the_field('hero_button_link'); ?>"><?php the_field('hero_button_text'); ?></a>
			</div>
		</div>
	</div>
	<div class="home-hero-bkg"></div>
</div>
<?php
			}
		}
	} else {
?>
<header>
	<div class="header-container">
		<div class="header-row">
			<div class="logo">
				<a href="<?php echo site_url(); ?>"><img src="<?php the_field( 'logo', 'option'); ?>" alt="logo" /></a>
			</div>
			<div class="humbeger-btn">
				<span></span>
				<span></span>
				<span></span>
			</div>
			<?php
				wp_nav_menu(
					array(
						'container' => 'nav',
						'container_class' => 'primary-menu',
						'theme_location' => 'primary',
					)
				);
			?>
		</div>
		<div class="primary-mobile-wrapper" style="display: none;">
			<?php
				wp_nav_menu(
					array(
						'container' => 'nav',
						'container_class' => 'primary-menu',
						'theme_location'  => 'primary',
					)
				);
			?>
		</div>
	</div>
</header>
<?php
	}
?>