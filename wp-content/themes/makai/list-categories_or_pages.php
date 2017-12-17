
<?php // Блок Підсторінки / Сусідні сторінки
if(is_page()) : 
$children = wp_list_pages('title_li=&child_of='.$post->ID.'&echo=0'); // підсторінки даної сторінки
if(!$children and $post->post_parent) { $children = wp_list_pages('title_li=&child_of='.$post->post_parent.'&echo=0'); } // "сусідні" сторінки даної сторінки
	 if ($children) { ?>
     <div class="block widget list_pages cats_tree">
<div class="block-title"> 
<span><?php _e('Pages') ?></span> 
<a class="toogle-b"></a>
</div>
<div class="block-content">
      <ul>
	<?php echo $children; ?> 
  	  </ul>
</div>      
     </div>        
<?php } 
endif; ?>
 
<?php 
$taxonomy = '';
/// Блок "Підкатегорії / Сусідні категорії" (сторінка категорії, сторінка товару)
if(is_archive()) : 
// $queried_object = $wp_query->queried_object; 
$term_id = $queried_object->term_id;
$taxonomy = $queried_object->taxonomy;
$curr_id = $term_id;
if(count(get_term_children($term_id, $taxonomy))) { $parent_id = $term_id; }
else { $parent_id = $queried_object->parent; }
elseif(is_single()) : 
//  	$post_type = get_post_type( $post );   
//  		$taxonomy_names = get_object_taxonomies( $post );  $taxonomy = $taxonomy_names[0];
//  		$terms = wp_get_post_terms($post->ID, $taxonomy);
//  		$term_4 = $terms[0];
$curr_id = $term_4->term_id;
$parent_id = $term_4->parent;
endif;

$child_terms = array();
if($taxonomy and $parent_id) {
	$child_terms = get_terms( $taxonomy, array('parent' => $parent_id, 'hide_empty' => false) ); //
}
?>
<?php if(count($child_terms)) : ?>


<nav id="options" class="work-nav">
 <ul id="filters" class="option-set" data-option-key="filter">
 
<li><a href="#filter" class="selected">All</a></li>
<?php foreach ($child_terms as $cat) : ?>
<li>
<a href="#filter" data-option-value=".<?php echo $cat->slug ?>" >

<?php echo $cat->name ?>
</a>
</li>
<?php endforeach; ?>

</ul>



</nav>
<?php endif; // ($child_terms) ?>