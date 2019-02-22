<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package ACStarter
 */

get_header(); ?>

	<div id="primary" class="full-content-area clear">
		<main id="main" class="site-main error-404 not-found" role="main">
			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e( 'Sorry... That page can&rsquo;t be found.', 'acstarter' ); ?></h1>
				<p>It looks like nothing was found at this location. Maybe try one of the links below.</p>
			</header><!-- .page-header -->
			<?php get_template_part('template-parts/content','sitemap'); ?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
