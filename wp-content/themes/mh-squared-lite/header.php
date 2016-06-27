<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header class="mh-header">
	<div class="mh-container">
		<?php mh_squared_lite_logo(); ?>
	</div>
</header>
<div class="mh-container">
<nav class="main-nav clearfix">
	<?php wp_nav_menu(array('theme_location' => 'main_nav')); ?>
</nav>
<div class="slicknav clearfix"></div>
<div class="mh-wrapper">