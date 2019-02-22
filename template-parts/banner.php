<section class="banner-section clear">
	<div class="wrapper">
	<?php  $wp_query = new WP_Query(array('post_status'=>'private','pagename'=>'home')); 
	if ( have_posts() ) : the_post(); 
		if( $slides = get_field('slides') ) { 
			$count = count($slides);
			$slideshow = ($slides>1) ? ' slideshow':'';
		?>
		<div id="home_slides" class="flexslider<?php echo $slideshow?>">
			<ul class="slides">
				<?php foreach ($slides as $ss) { 
				$image = $ss['slide_image']; ?>
				<?php if($image) { ?>
				<li class="slide">
					<img src="<?php echo $image['url']?>" alt="<?php echo $image['title']?>" />
				</li>
				<?php } ?>
				<?php } ?>
			</ul>
		</div>
		<?php } ?>
	<?php endif; ?>
	</div>
</section>