<?php
/**
 * The header for theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ACStarter
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link href="https://fonts.googleapis.com/css?family=Noto+Sans+KR:100,300,400,500,700,900" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo get_bloginfo('template_url') ?>/css/jquery.fancybox.min.css" />
<script defer src="<?php bloginfo( 'template_url' ); ?>/assets/svg-with-js/js/fontawesome-all.js"></script>
<script type="text/javascript">
	var assetsURL = '<?php echo get_bloginfo( 'template_url' ); ?>/';
</script>

<?php wp_head(); ?>
<?php
$classes = ( is_front_page() ) ? 'homepage':'subpage';
?>
</head>

<body <?php body_class($classes); ?>>
<div class="sitebg"></div>
<div id="page" class="site clear">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'acstarter' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
		<div class="wrapper">
			<?php if( get_custom_logo() ) { ?>
	            <div class="logo">
	            	<?php the_custom_logo(); ?>
	            </div>
	        <?php } else { ?>
	            <h1 class="logo">
		            <a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a>
	            </h1>
	        <?php } ?>

			<nav id="site-navigation" class="main-navigation" role="navigation">
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'MENU', 'acstarter' ); ?><span></span></button>
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu' ) ); ?>
			</nav><!-- #site-navigation -->
		</div><!-- wrapper -->
	</header><!-- #masthead -->

	<?php if( is_front_page() ) { ?>
		<?php get_template_part('template-parts/banner'); ?>
	<?php } ?>

	<div id="content" class="site-content wrapper">
