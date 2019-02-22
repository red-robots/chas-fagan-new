<?php
get_header(); 
$obj = get_queried_object();
$taxonomy = (isset($obj->taxonomy)) ? $obj->taxonomy : '';
$category_name = (isset($obj->name)) ? $obj->name : '';
$term_slug = (isset($obj->slug) && $obj->slug) ? $obj->slug : '';
$term_id = (isset($obj->term_id) && $obj->term_id) ? $obj->term_id : '';
$perpage = 9;
$page = ( get_query_var( 'pg' ) ) ? absint( get_query_var( 'pg' ) ) : 1;
$popup_categories = array(3,4);
?>

<input type="hidden" id="perpage" value="<?php echo $perpage?>">
<input type="hidden" id="termID" value="<?php echo $term_id?>">
<input type="hidden" id="taxonomy" value="<?php echo $taxonomy?>">
<input type="hidden" id="currentPage" value="1">

<div id="primary" class="full-content-area clear artworkList">
	<header class="page-header">
		<h1 class="page-title"><?php echo $category_name; ?></h1>
	</header>

	<?php
	$next_page = admin_url() . 'admin-ajax.php?action=load_more_images&taxonomy='.$taxonomy.'&term_id='.$term_id.'&perpage='.$perpage.'&page=' . $page;
	$galleries = get_galleries($taxonomy,$term_id,$page,$perpage);
	if ( $galleries )  { ?>
		<div class="art-post-entries clear">
			<div id="results" class="grid artworks clear are-images-unloaded">
				<div class="grid__col-sizer"></div>
				<div class="grid__gutter-sizer"></div>
				<?php echo $galleries; ?>
			</div>
		</div>
		<nav id="page-nav" class="navigation"><a href="<?php echo $next_page?>"></a></nav>


		<div id="galleries_hidden" style="display:none;">
			<?php 
			$args = array(
		        'posts_per_page'=> -1,
		        'post_type'     => 'artwork',
		        'post_status'   => 'publish',
		        'paged'         => $page,
		        'tax_query' => array(
		            array(
		                'taxonomy' => $taxonomy,
		                'terms' => $term_id,
		                'include_children' => false 
		            )
		        )
		    );
		    $gg_items = get_posts($args);
		    if($gg_items) { ?>
		    	<?php foreach ($gg_items as $g) { 
		    	$g_id = $g->ID; 
		    	$post_thumbnail_id = get_post_thumbnail_id( $g_id );
	            $image_src = wp_get_attachment_image_src($post_thumbnail_id,'large');
	            if($image_src) {
	            	$image_thumb = wp_get_attachment_image_src($post_thumbnail_id,'medium');
	                $image_alt = get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true);
	            } else {
	                $image_alt = '';
	            }
	            $sub_title = get_field('second_line_title'); 
	            $short_description = get_field('short_description');  ?>

			    	<?php if($image_src) {  
			    		$fileName = basename($image_src[0]);
                    	$slug_name = sanitize_title_with_dashes($fileName); ?>
			    		<?php if( in_array($term_id, $popup_categories) ) {  ?>
			    			<a data-slug="<?php echo $slug_name;?>" class="colorbox referlink" rel="gal" title="<?php echo $image_alt; ?>" href="<?php echo $image_src[0];?>"></a>
			    		<?php } ?>
			    	<?php } ?>

		    	<?php } ?>
		    <?php } ?>
		</div>
	<?php } ?>
</div>



<?php
get_footer();