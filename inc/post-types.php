<?php 
/* Custom Post Types */

//DASH ICONS = https://developer.wordpress.org/resource/dashicons/
add_action('init', 'js_custom_init', 1);
function js_custom_init() {
    $post_types = array(
        array(
            'post_type' => 'artwork',
            'menu_name' => 'Artworks',
            'plural'    => 'Artworks',
            'single'    => 'Artwork',
            'menu_icon' => 'dashicons-star-filled',
            'supports'  => array('title','editor','thumbnail')
        ),
    );
    
    if($post_types) {
        foreach ($post_types as $p) {
            $p_type = ( isset($p['post_type']) && $p['post_type'] ) ? $p['post_type'] : ""; 
            $single_name = ( isset($p['single']) && $p['single'] ) ? $p['single'] : "Custom Post"; 
            $plural_name = ( isset($p['plural']) && $p['plural'] ) ? $p['plural'] : "Custom Post"; 
            $menu_name = ( isset($p['menu_name']) && $p['menu_name'] ) ? $p['menu_name'] : $p['plural']; 
            $menu_icon = ( isset($p['menu_icon']) && $p['menu_icon'] ) ? $p['menu_icon'] : "dashicons-admin-post"; 
            $supports = ( isset($p['supports']) && $p['supports'] ) ? $p['supports'] : array('title','editor','custom-fields','thumbnail'); 
            $taxonomies = ( isset($p['taxonomies']) && $p['taxonomies'] ) ? $p['taxonomies'] : array(); 
            $parent_item_colon = ( isset($p['parent_item_colon']) && $p['parent_item_colon'] ) ? $p['parent_item_colon'] : ""; 
            $menu_position = ( isset($p['menu_position']) && $p['menu_position'] ) ? $p['menu_position'] : 20; 
            
            if($p_type) {
                
                $labels = array(
                    'name' => _x($plural_name, 'post type general name'),
                    'singular_name' => _x($single_name, 'post type singular name'),
                    'add_new' => _x('Add New', $single_name),
                    'add_new_item' => __('Add New ' . $single_name),
                    'edit_item' => __('Edit ' . $single_name),
                    'new_item' => __('New ' . $single_name),
                    'view_item' => __('View ' . $single_name),
                    'search_items' => __('Search ' . $plural_name),
                    'not_found' =>  __('No ' . $plural_name . ' found'),
                    'not_found_in_trash' => __('No ' . $plural_name . ' found in Trash'), 
                    'parent_item_colon' => $parent_item_colon,
                    'menu_name' => $menu_name
                );
            
            
                $args = array(
                    'labels' => $labels,
                    'public' => true,
                    'publicly_queryable' => true,
                    'show_ui' => true, 
                    'show_in_menu' => true, 
                    'query_var' => true,
                    'rewrite' => true,
                    'capability_type' => 'post',
                    'has_archive' => false, 
                    'hierarchical' => false, // 'false' acts like posts 'true' acts like pages
                    'menu_position' => $menu_position,
                    'menu_icon'=> $menu_icon,
                    'supports' => $supports
                ); 
                
                register_post_type($p_type,$args); // name used in query
                
            }
            
        }
    }
}

// Add new taxonomy, make it hierarchical (like categories)
add_action( 'init', 'ii_custom_taxonomies', 0 );
function ii_custom_taxonomies() {
        $posts = array(
            array(
                'post_type' => 'artwork',
                'menu_name' => 'Art Types',
                'plural'    => 'Art Types',
                'single'    => 'Art Type',
                'taxonomy'  => 'arttypes',
                'show_admin_column'=>true
            )
        );
    
    if($posts) {
        foreach($posts as $p) {
            $p_type = ( isset($p['post_type']) && $p['post_type'] ) ? $p['post_type'] : ""; 
            $single_name = ( isset($p['single']) && $p['single'] ) ? $p['single'] : "Custom Post"; 
            $plural_name = ( isset($p['plural']) && $p['plural'] ) ? $p['plural'] : "Custom Post"; 
            $menu_name = ( isset($p['menu_name']) && $p['menu_name'] ) ? $p['menu_name'] : $p['plural'];
            $taxonomy = ( isset($p['taxonomy']) && $p['taxonomy'] ) ? $p['taxonomy'] : "";
            $show_admin_column = ( isset($p['show_admin_column']) && $p['show_admin_column'] ) ? true : false;
            $slug = ( isset($p['slug']) && $p['slug'] ) ? $p['slug'] : $taxonomy;
            
            if( $taxonomy && $p_type ) {
                $labels = array(
                    'name' => _x( $menu_name, 'taxonomy general name' ),
                    'singular_name' => _x( $single_name, 'taxonomy singular name' ),
                    'search_items' =>  __( 'Search ' . $plural_name ),
                    'popular_items' => __( 'Popular ' . $plural_name ),
                    'all_items' => __( 'All ' . $plural_name ),
                    'parent_item' => __( 'Parent ' .  $single_name),
                    'parent_item_colon' => __( 'Parent ' . $single_name . ':' ),
                    'edit_item' => __( 'Edit ' . $single_name ),
                    'update_item' => __( 'Update ' . $single_name ),
                    'add_new_item' => __( 'Add New ' . $single_name ),
                    'new_item_name' => __( 'New ' . $single_name ),
                  );

              register_taxonomy($taxonomy,$p_type, array(
                'hierarchical' => true,
                'labels' => $labels,
                'query_var'=>true,
                'show_ui' => true,
                'rewrite' => array( 'slug' => $slug ),
                'show_admin_column'=>$show_admin_column,
                'public' => true,
                '_builtin' => true
              ));
            }
            
        }
    }
}

// Add the custom columns to the position post type:
add_filter( 'manage_posts_columns', 'set_custom_cpt_columns' );
function set_custom_cpt_columns($columns) {
    global $wp_query;
    $query = isset($wp_query->query) ? $wp_query->query : '';
    $post_type = ( isset($query['post_type']) ) ? $query['post_type'] : '';

    if($post_type=='artwork') {
        unset( $columns['date'] );
        unset( $columns['taxonomy-arttypes'] );
        $columns['featured_image'] = __( 'Image', 'acstarter' );
        $columns['taxonomy-arttypes'] = __( 'Category', 'acstarter' );
        $columns['date'] = __( 'Date', 'acstarter' );
    }
    
    return $columns;
}

// Add the data to the custom columns for the book post type:
add_action( 'manage_posts_custom_column' , 'custom_post_column', 10, 2 );
function custom_post_column( $column, $post_id ) {
    global $wp_query;
    $query = isset($wp_query->query) ? $wp_query->query : '';
    $post_type = ( isset($query['post_type']) ) ? $query['post_type'] : '';
    
    if($post_type=='artwork') {
        switch ( $column ) {
            case 'featured_image' :
                $post_thumbnail_id = get_post_thumbnail_id( $post_id );
                $img = wp_get_attachment_image_src($post_thumbnail_id,'thumbnail');
                $img_src = ($img) ? $img[0] : '';
                $the_photo = '<span class="tmphoto" style="display:inline-block;width:50px;height:50px;background:#e2e1e1;text-align:center;">';
                if($img_src) {
                   $the_photo .= '<img src="'.$img_src.'" alt="" style="width:100%;height:auto" />';
                } else {
                    $the_photo .= '<i class="dashicons dashicons-businessman" style="font-size:33px;position:relative;top:8px;left:-6px;opacity:0.3;"></i>';
                }
                $the_photo .= '</span>';
                echo $the_photo;
        }
    }

}

/* ARTWORKS - Custom Taxonomy Column */
add_filter("manage_edit-arttypes_columns", 'custom_tax_columns'); 
function custom_tax_columns($theme_columns) {
    global $wp_query;
    $new_columns = array(
        'cb' => '<input type="checkbox" />',
        'name' => __('Name'),
        'tax_featured_image' => 'Featured Image',
//      'description' => __('Description'),
        'slug' => __('Slug'),
        'posts' => __('Posts')
        );
    return $new_columns;
}


add_filter("manage_arttypes_custom_column", 'manage_theme_columns', 10, 3);
function manage_theme_columns($out, $column_name, $theme_id) {
    $term = get_term($theme_id, 'arttypes');
    $projectPage = get_field('artwork_featured_image',$term);
    switch ($column_name) {
        case 'tax_featured_image': 
            $out = '<span class="tmphoto" style="display:inline-block;width:50px;height:50px;background:#e6e6e6;text-align:center;">';
            if($projectPage) {
                // $post_thumbnail_id = get_post_thumbnail_id( $project_page_id );
                // $image = wp_get_attachment_image_src($post_thumbnail_id,'large');
                // $imageSRC = $image[0];
                // $image_alt = get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true);
                $imageSRC = $projectPage['url'];
                $imageAlt = $projectPage['title'];
                $out .= '<img src="'.$imageSRC.'" alt="'.$imageAlt.'" style="width:100%;height:auto" />';
            } else {
                $out .= '<i class="dashicons dashicons-format-image" style="font-size:25px;position:relative;top:14px;left:-2px;opacity:0.2;"></i>';
            }
            $out .= '</span>';
            break;
 
        default:
            break;
    }
    return $out;    
}


