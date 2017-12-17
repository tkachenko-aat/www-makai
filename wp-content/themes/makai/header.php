<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
 
    <meta charset="<?php bloginfo('charset'); ?>"> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0" /> 
	
    <title> <?php wp_title( '-', true, 'right' ); bloginfo( 'name' ); ?> </title>
    
    <link rel="shortcut icon" href="<?php bloginfo('template_url') ?>/images/favicon.png" />
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,600" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400" rel="stylesheet">   
               
	<?php wp_head(); ?>    


	<?php 
    $image_25_src = get_template_directory_uri().'/images/logo-130x30.png';  $logo_src = $image_25_src;
    $attach_id = 0;
    $header_image_2 = get_theme_mod('header_image_data');  
        if($header_image_2) { if($header_image_2->attachment_id) {
        $attach_id = $header_image_2->attachment_id;
        $thumb_arr2 = wp_get_attachment_image_src($attach_id, '');  $logo_src = $thumb_arr2[0];
        } }
    if( (is_single() or is_page()) and has_post_thumbnail() ) {
    $attach_id = get_post_thumbnail_id();
    }
    if($attach_id) {
    $thumb_arr25 = wp_get_attachment_image_src($attach_id, '');
    $image_25_src = $thumb_arr25[0];
    }
    ?>
	<meta property="og:image" content="<?php echo $image_25_src; ?>" />

	<?php $apple_sizes_arr = array('57x57', '114x114', '72x72', '144x144', '60x60', '120x120', '76x76', '152x152'); ?>
    <?php foreach ($apple_sizes_arr as $size ) : ?>
	<link rel="apple-touch-icon" sizes="<?php echo $size ?>" href="<?php bloginfo('template_url') ?>/images/apple-icon.png">
	<?php endforeach; ?>

	<?php /* Активний пункт меню для всіх типів матеріалів і таксономій */ ?>
	<style type="text/css">
	/* Активний пункт */
	.top_menu ul.menu > .current-menu-item > a, 
	.top_menu ul.menu > .current-menu-parent > a,  
	<?php 
	$types_arr = get_post_types( array('public' => true ) );  unset($types_arr['attachment']); 
	$taxo_arr = get_taxonomies(array('public' => true));  unset($taxo_arr['post_format'], $taxo_arr['post_tag']); 
	$types_taxo_arr = array_merge( $types_arr, $taxo_arr );
	foreach ($types_taxo_arr as $p_type ) : ?>
	.top_menu ul.menu > .current-<?php echo $p_type ?>-parent > a, 
	.top_menu ul.menu > .current-<?php echo $p_type ?>-ancestor > a,
	<?php endforeach; ?>
	.top_menu ul.menu > .current-menu-ancestor > a
	{
		border-color:#ffc000 !important;
		
	}

	</style>

    
</head>


<body <?php body_class(); ?>>




<!-- BEGIN: wrapper -->    
<div class="wrapper" id="main-wrapper">
 
 
<!-- BEGIN: main content -->         
<section id="main-content"> 
 
         
	<header id="top">
     	<div class="header_inn">
        	<div class="inn">
     

                <div class="block logo" id="header-logo"> 
                     <a class="log_img" href="<?php bloginfo('url'); ?>">
                     		<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 190 50"><defs><style>.cls-1,.cls-2{font-size:54px;font-family:Lato-Regular, Lato;}.cls-2{letter-spacing:-0.03em;}</style></defs><title>Makai-Logo-Black</title><text class="cls-1" transform="translate(0 46.35) scale(1.05 1)">M</text><text class="cls-2" transform="translate(54 46.35) scale(1.05 1)">A</text><text class="cls-1" transform="translate(92.83 46.35) scale(1.05 1)">K</text><text class="cls-2" transform="translate(135.33 46.35) scale(1.05 1)">A</text><path d="M190.33,41.85h-4.26a1.9,1.9,0,0,1-1.19-.35,2.17,2.17,0,0,1-.68-.89l-3.81-9.34L168.5,3.15h5.57ZM178.86,27.48l-6.39-15.74q-.28-.7-.6-1.63t-.6-2C170.88,9.58,178.86,27.48,178.86,27.48Z" transform="translate(1 4.5)"></path>
                     		</svg>
                     </a>                    
                </div>  
    
         
                <div class="main-menu-conteiner"> 
                    <div class="main-menu">
                        <div id="menu1" class="top_menu"> 
							<?php wp_nav_menu( array( 'theme_location' => 'm1', 'fallback_cb' => false ) ); ?> 
                        </div>
                        <div id="menu2" class="top_menu"> 
							<?php wp_nav_menu( array( 'theme_location' => 'm2', 'fallback_cb' => false ) ); ?> 
                        </div>
                    </div>
                    <a class="menu-hamb" id="menu1-menu-hamb">
                    	<i class="ja ja-hamb"></i> <i class="ha ha-close"></i> <span><?php _e('Menu') ?>                    		
                    	</span></a>
                </div>
                
        	</div>		
		</div>	
	</header>        

  

			
	 <div class="page-content">   
