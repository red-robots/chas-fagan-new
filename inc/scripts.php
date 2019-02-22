<?php
/**
 * Enqueue scripts and styles.
 */
function acstarter_scripts() {
	wp_enqueue_style( 'acstarter-style', get_stylesheet_uri() );

	wp_deregister_script('jquery');
		wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js', false, '1.10.2', true);
		wp_enqueue_script('jquery');

	wp_enqueue_script( 
			'mousewheel', 
			get_template_directory_uri() . '/assets/js/vendors/jquery.mousewheel.js', 
			array(), '20120206', 
			true 
		);

	wp_enqueue_script( 
			'acstarter-blocks', 
			get_template_directory_uri() . '/assets/js/vendors.min.js', 
			array(), '20120206', 
			true 
		);

	wp_enqueue_script( 
			'infinite-scroll', 
			get_template_directory_uri() . '/assets/js/vendors/jquery.infinitescroll.min.js', 
			array(), '20120206', 
			true 
		);


	wp_enqueue_script( 
			'acstarter-custom', 
			get_template_directory_uri() . '/assets/js/custom.js', 
			array(), '20120206', 
			true 
		);

	wp_enqueue_script( 
		'font-awesome', 
		'https://use.fontawesome.com/8f931eabc1.js', 
		array(), '20180424', 
		true 
	);

	wp_enqueue_script( 
		'load_more', 
		get_template_directory_uri() . '/assets/js/loadmore.js', 
		array('jquery'), '20190201', 
		true 
	);
    wp_localize_script('load_more', 'ajax_url', array('ajaxurl' => admin_url('admin-ajax.php')));

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'acstarter_scripts' );