<?php
/**
 * Template Name: Artwork page
 *
 */

get_header(); ?>

<div id="primary" class="full-content-area clear">
	<?php while ( have_posts() ) : the_post();
		$has_image = (get_the_post_thumbnail()) ? true : false;
		$col_class = ($has_image) ? 'has-image':'no-image';
		?>
		<header class="page-header">
			<h1 class="page-title"><?php the_title() ?></h1>
		</header>

		<?php if( get_the_content() ) { ?>
		<div class="entry-content-wrapper <?php echo $col_class?>">
			<div class="entry-content"><?php the_content() ?></div>
			<?php if($has_image) { ?>
			<div class="imagediv"><?php echo get_the_post_thumbnail(); ?></div>
			<?php } ?>
		</div> 
		<?php } ?>
	<?php endwhile;  ?>

	<?php
	$terms = get_terms( array(
		    'taxonomy' => 'arttypes',
		    'hide_empty' => false,
		));
	if($terms) { ?>
	<div class="art-post-entries clear categorylist">
		<div id="container" class="grid masonry clear">
			<?php foreach($terms as $t) { 
				$term_id = $t->term_id;
				$term_name = $t->name;
				$term_slug = $t->slug;
				$args = array(
			    	'showposts' => 1,
			        'post_type' => 'artwork',
			        'post_status' => 'publish',
			        'tax_query' => array(
			            array(
			                'taxonomy' => 'arttypes',
			                'terms' => $term_id,
			                'include_children' => false 
			            )
			        )
				);
				$items = get_posts( $args );  ?>
				<?php if($items) { 
					$obj = $items[0]; 
					$post_id = $obj->ID;
					$pagelink = get_term_link($term_id);
					$categoryImage = get_field('artwork_featured_image',$t);
					$featuredImage = '';
					$image_alt = '';
					// $imageSRC = '';
					// $image_alt = '';
					// if($project_page_id) {
					// 	$post_thumbnail_id = get_post_thumbnail_id( $project_page_id );
					// 	$image = wp_get_attachment_image_src($post_thumbnail_id,'large');
					// 	$imageSRC = $image[0];
					// 	$image_alt = get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true);
					// }

					// if($imageSRC) {
					// 	$featuredImage = $imageSRC;
					// } else {
					// 	$post_thumbnail_id = get_post_thumbnail_id( $post_id );
					// 	$image = wp_get_attachment_image_src($post_thumbnail_id,'large');
					// 	$image_alt = get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true);
					// 	$featuredImage = $image[0];
					// } 

					if($categoryImage) {
						$featuredImage = $categoryImage['url'];
						$image_alt = $categoryImage['title'];
					} else {
						$post_thumbnail_id = get_post_thumbnail_id($post_id);
						$image = wp_get_attachment_image_src($post_thumbnail_id,'large');
						$image_alt = get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true);
						$featuredImage = $image[0];
					} 

						// echo '<pre>';
						// print_r($featuredImage);
						// echo '</pre>';
					?>

					<?php if($featuredImage) { ?>
						<div class="box box-with-link item" title="<?php echo $term_name; ?>" data-url="<?php echo $pagelink; ?>">
							<div class="inside clear">
								<figure class="effect-zoe">
									<img class="featimg" src="<?php echo $featuredImage; ?>" alt="<?php echo $image_alt; ?>" />
									<figcaption>
										<p class="title1"><?php echo $term_name; ?></p>
									</figcaption>
								</figure>
							</div>
						</div>
					<?php } ?>

				<?php } ?>	

			<?php } ?>	
		</div>
	</div>
	<?php } ?>
	
</div><!-- #primary -->

<?php
get_footer();
