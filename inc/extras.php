<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package ACStarter
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function acstarter_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

    $browsers = ['is_iphone', 'is_chrome', 'is_safari', 'is_NS4', 'is_opera', 'is_macIE', 'is_winIE', 'is_gecko', 'is_lynx', 'is_IE', 'is_edge'];
    $classes[] = join(' ', array_filter($browsers, function ($browser) {
        return $GLOBALS[$browser];
    }));

	return $classes;
}
add_filter( 'body_class', 'acstarter_body_classes' );

function add_query_vars_filter( $vars ) {
  $vars[] = "pg";
  return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter' );

function generate_sitemap($pageWithCats=null) {
    global $wpdb;
    $lists = array();
    $menus = wp_get_nav_menu_items('top-menu');
    $menu_orders = array();
    $menu_with_children = array();
    if($menus) {
        $i=0;
        foreach($menus as $m) {
            $page_id = $m->object_id;
            $menu_title = $m->title;
            $page_url = $m->url;
            $post_parent = $m->post_parent;
            $submenu = array();
            if($post_parent) {
                $submenu = array(
                        'id'=>$page_id,
                        'title'=>$menu_title,
                        'url'=>$page_url
                    );
                $menu_with_children[$post_parent][$page_id] = $submenu;
            } else {
                $menu_orders[$page_id] = $menu_title;
            } 
            $i++;
        }
    }
    
    $results = $wpdb->get_results( "SELECT ID,post_title FROM {$wpdb->prefix}posts WHERE post_type = 'page' AND post_status='publish' AND post_parent=0 ORDER BY post_title ASC", OBJECT );
    $childPages = $wpdb->get_results( "SELECT ID,post_title,post_parent as parent_id FROM {$wpdb->prefix}posts WHERE post_type = 'page' AND post_status='publish' AND post_parent>0 ORDER BY post_title ASC", OBJECT );
    $childrenList = array();
    $childrenAll = array();
    /* child pages */
    if($childPages) {
        foreach($childPages as $cp) {
            $pId = $cp->parent_id;
            $iD = $cp->ID;
            $childrenAll[$iD] = array(
                                'id'=>$cp->ID,
                                'title'=>$cp->post_title,
                                'url'=>get_permalink($iD)
                            );
            $childrenList[$pId][] = array(
                                'id'=>$cp->ID,
                                'title'=>$cp->post_title,
                                'url'=>get_permalink($cp->ID)
                            );
        }
    }

    if($results) {
        foreach($results as $row) {
            $id = $row->ID;
            $page_title = $row->post_title;
            $page_link = get_permalink($id);
            if(array_key_exists($id,$menu_orders)) {
                $page_title = $menu_orders[$id];
            }
            
            $lists[$id]['parent_id'] = $id;
            $lists[$id]['parent_title'] = $page_title;
            $lists[$id]['parent_url'] = $page_link;
            
            if($menu_with_children) {

                $ii_childrens = array();
                if(array_key_exists($id,$menu_with_children)) {
                    $ii_childrens = $menu_with_children[$id];
                    $lists[$id]['children'] = $ii_childrens;
                }

                /* Show children page even if does not exist on Menu dropdown */
                if($childrenList && array_key_exists($id, $childrenList)) {
                    $cc_children = $childrenList[$id];
                    $exist_children = $lists[$id]['children'];
                    
                    foreach($cc_children as $cd) {
                        $x_id = $cd['id'];
                        if(!array_key_exists($x_id, $ii_childrens)) {
                            $addon[$x_id] = $cd;
                            $exist_children[$x_id] = $cd;
                        }
                    } 

                    $lists[$id]['children'] = $exist_children;
                }

            } else {
                if($childrenList && array_key_exists($id, $childrenList)) {
                    $c_obj = $childrenList[$id];
                    $lists[$id]['children'] = $c_obj;
                }
            }


            if($pageWithCats) {
            	foreach($pageWithCats as $p) {
            		$pageid = $p['id'];
            		$taxo = $p['taxonomy'];
            		if($pageid==$id) {
            			$o_terms = get_terms( array(
						    'taxonomy' => $taxo,
						    'hide_empty' => false,
						) );
						if($o_terms){
							foreach ($o_terms as $t) {
								$term_id = $t->term_id;
								$term_name = $t->name;
							}
							$lists[$id]['categories'] = $o_terms;
						}
            		}
            	}
            }

            $cat_args = array('hide_empty' => 1, 'parent' => 0, 'exclude'=>array(1));
            $i_parent_ID = 8; /* Artwork page */
            $artwork_terms = get_terms( array(
                'taxonomy' => 'arttypes',
                'hide_empty' => false,
            ));
            if($id == $i_parent_ID) {
                $lists[$id]['categories'] = $artwork_terms;
            }
        }

    }
    
    return $lists;
    
}

// add_action("wp_ajax_load_more", "load_more");
// add_action("wp_ajax_nopriv_load_more", "load_more");
// function load_more() {
//     $page = $_POST["page"]+1;
//     $taxonomy = $_POST["taxonomy"];
//     $term_id = $_POST["term_id"];
//     $perpage = $_POST["perpage"];
//     $content = get_galleries($taxonomy,$term_id,$page,$perpage);
//     if($content) {
//         $response = array('content'=>$content,'page' => $page);
//     } else {
//         $response = array('content'=>'','page' => -1);
//     }
//     echo json_encode($response);
//     die();
// }

add_action("wp_ajax_load_more_images", "load_more_images");
add_action("wp_ajax_nopriv_load_more_images", "load_more_images");
function load_more_images() {
    $page = $_GET["page"];
    $taxonomy = $_GET["taxonomy"];
    $term_id = $_GET["term_id"];
    $perpage = ($_GET["perpage"]) ? $_GET["perpage"] : 9;
    $content = get_galleries($taxonomy,$term_id,$page,$perpage);
    echo $content;
    die();
}



function get_galleries($taxonomy,$term_id,$page=1,$perpage=9) {
    $term = get_term($term_id);
    $slug = $term->slug;
    $term_link = get_term_link($term_id);
    $popup_categories = array(3,4);
    //$next_page = admin_url() . 'admin-ajax.php?action=load_more_images&taxonomy='.$taxonomy.'&term_id='.$term_id.'&perpage='.$perpage.'&page=' . $page;
    ob_start();
    $args = array(
        'posts_per_page'=> $perpage,
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

    $is_new = ($page>1) ? true : false;
    $is_new = false;
    $items = new WP_Query( $args );
    if ( $items->have_posts() )  { ?>
        <?php $ctr=1; while ( $items->have_posts() ) : $items->the_post(); 
            $image = get_the_post_thumbnail(); 
            $post_id = get_the_ID();
            $post_thumbnail_id = get_post_thumbnail_id( $post_id );
            $image_src = wp_get_attachment_image_src($post_thumbnail_id,'large');
            if($image_src) {
                $image_alt = get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true);
            } else {
                $image_alt = '';
            }
            $sub_title = get_field('second_line_title'); 
            $short_description = get_field('short_description'); 
            $pagelink = get_permalink(); 
            ?>
            <?php if($image) {  ?>
                <?php if( in_array($term_id, $popup_categories) ) { 
                    $fileName = basename($image_src[0]);
                    $slug_name = sanitize_title_with_dashes($fileName); ?>

                    <?php /* Pop-up image */ ?>
                    <div data-page="<?php echo $page; ?>" class="items_group_<?php echo $page; ?> grid__item box item<?php echo ($is_new) ? ' newEntry':'';?>">
                        <div class="inside clear">
                            <a id="<?php echo $slug_name?>"  class="effect-zoe popup-image popUp2" rel="gal" title="<?php echo $image_alt; ?>" href="<?php echo $image_src[0]?>">
                                <?php the_post_thumbnail('medium'); ?>
                            </a>
                        </div>
                    </div>

                <?php $ctr++; } else { ?>

                    <?php /* Open new page */ ?>
                    <a data-page="<?php echo $page; ?>" class="items_group_<?php echo $page; ?> grid__item box box-with-link item<?php echo ($is_new) ? ' newEntry':'';?>" href="<?php echo $pagelink; ?>">
                        <span class="inside clear">
                            <figure class="effect-zoe">
                                <?php the_post_thumbnail('medium'); ?>
                                <figcaption>
                                    <p class="title1"><?php echo get_the_title(); ?></p>
                                    <p class="title2"><?php echo $sub_title; ?></p>
                                    <?php if($short_description) { ?>
                                    <div class="description"><?php echo $short_description; ?></div>
                                    <?php } ?>
                                </figcaption>
                            </figure>
                        </span>
                    </a>

                <?php } ?>

            <?php } ?>
        <?php endwhile; wp_reset_postdata(); ?>
    <?php } ?>

    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
