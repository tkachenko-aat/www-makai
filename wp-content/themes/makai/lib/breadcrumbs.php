<?php // breadcrumbs 
function breadcrumbs() {
  $delimiter = '<span class="zakarlu"><i class="ha ha-arrow small ha-arrow-right"></i></span>'; //  
  // $home = bloginfo('name'); // 
  $showCurrent = 1; // 1 - показувати назву поточної стор.,  0 - не показувати
  $before = '<span class="current">';  $after = '</span>'; 

  global $post; global $wp_query;
  // $homeLink = get_settings('siteurl');
    echo '<div class="title_content" id="crumbs"><a href="'; bloginfo('url'); echo '">'; 
	// bloginfo('name'); 
	_e('Home');
	echo '</a>' . $delimiter . ' ';
    
	if ( is_tax() or is_category() ) {  // is_category()
	$queried_object = $wp_query->queried_object;
	$taxonomy = $queried_object->taxonomy;
	$term4 = $queried_object;
	// $term4 = get_term($queried_object->term_id, $taxonomy);
	$term4_arr = array();
			while ($term4->parent) {
		$term4 = get_term($term4->parent, $taxonomy);
		$term4_arr[] = $term4;
			} // end while
		krsort($term4_arr);
	foreach ($term4_arr as $key4 => $term) :
		echo '<a href="'; echo get_term_link( $term ) . '">' . $term->name . '</a>'; echo $delimiter;
	endforeach;
	  if ($showCurrent == 1) echo $before . $queried_object->name . $after;
	}
	
	elseif ( is_single() && !is_attachment() ) {       
		$main_post_id = $post->ID;
	if($post->post_parent) { ////
		$parent4_arr = array();
		$parent4 = $post->post_parent;
			while ($parent4) {
			$parent4_arr[] = $parent4;
			$post_4 = get_post($parent4);
			$parent4  = $post_4->post_parent;			
			} // end while
		krsort($parent4_arr);  $parent4_arr = array_values($parent4_arr);
		$main_post_id = $parent4_arr[0]; // highest parent post
	} // if($post->post_parent)
		$taxonomy_names = get_object_taxonomies( $post );  $taxonomy = $taxonomy_names[0];
		$terms = wp_get_post_terms( $main_post_id, $taxonomy );  $term4 = $terms[0];		
		if($term4) { ///
		$term4_arr = array();
		$term4_arr[] = $term4;
			while ($term4->parent) {	
		$term4 = get_term($term4->parent, $taxonomy);
		$term4_arr[] = $term4;
			} // end while
		krsort($term4_arr);
		foreach ($term4_arr as $key4 => $term) :
		echo '<a href="'; echo get_term_link( $term ) . '">' . $term->name . '</a>'; echo $delimiter;
		endforeach;
		} // if($term4)
	if($post->post_parent) { 
		foreach ($parent4_arr as $key2 => $post_id) : 
		echo '<a href="' . get_permalink($post_id) . '">' . get_the_title($post_id) . '</a>' . $delimiter;
		endforeach;
	} // if($post->post_parent)
	  if ($showCurrent == 1) echo $before . get_the_title() . $after; 
    } 


	elseif ( is_page() && !$post->post_parent ) {
      if ($showCurrent == 1) echo $before . get_the_title() . $after;
    } 
	elseif ( is_page() && $post->post_parent ) {
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      for ($i = 0; $i < count($breadcrumbs); $i++) {
        echo $breadcrumbs[$i];
        if ($i != count($breadcrumbs)-1) echo ' ' . $delimiter . ' ';
      }
      if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
    } 
	
	elseif ( is_search() ) {
      echo $before . '"' . get_search_query() . '"' . $after;
    } elseif ( is_day() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('d') . $after;
    } elseif ( is_month() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('F') . $after;
    } elseif ( is_year() ) {
      echo $before . get_the_time('Y') . $after;
    } 	
	 
	elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
      $post_type = get_post_type_object(get_post_type());
      echo $before . $post_type->labels->singular_name . $after;

    } elseif ( is_attachment() ) {
      /*  $parent = get_post($post->post_parent);
      $cat = get_the_category($parent->ID); $cat = $cat[0];
      echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
      echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
      if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;  */
    } 
	
	elseif ( is_tag() ) {
      echo $before . '"' . single_tag_title('', false) . '"' . $after;
    } elseif ( is_author() ) {
      global $author;
      $userdata = get_userdata($author);
      echo $before . '' . $userdata->display_name . $after;
    } elseif ( is_404() ) {
      echo $before . 'Error 404' . $after;
    }
/* 
    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' <span class="cur_page">(';
      echo __('Page') . ' ' . get_query_var('paged');
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')</span>';
    }
 */
    echo '</div>';
}
 // end breadcrumbs()
?>