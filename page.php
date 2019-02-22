<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ACStarter
 */

get_header(); ?>

	<div id="primary" class="full-content-area clear">
		<main id="main" class="site-main" role="main">
			<?php while ( have_posts() ) : the_post();
				$has_image = (get_the_post_thumbnail()) ? true : false;
				$col_class = ($has_image) ? 'has-image':'no-image';
				?>
				<header class="page-header">
					<h1 class="page-title"><?php the_title() ?></h1>
				</header>

				<div class="entry-content-wrapper <?php echo $col_class?>">
					<div class="entry-content"><?php the_content() ?></div>
					<?php if($has_image) { ?>
					<div class="imagediv"><?php echo get_the_post_thumbnail(); ?></div>
					<?php } ?>
				</div> 

			<?php endwhile;  ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
