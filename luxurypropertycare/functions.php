<?php 
add_action( 'wp_enqueue_scripts', 'enqueue_child_theme_styles');
function enqueue_child_theme_styles() {
wp_enqueue_style( 'Parents_theme_style', get_template_directory_uri().'/style.css' );
	
if( get_post_type() == 'project' ){
	wp_enqueue_style( 'flex_maincss', get_stylesheet_directory_uri().'/assets/owlcarousel.min.css',array(),'2.3.4' );
	wp_enqueue_script( 'flex_mainjs', get_stylesheet_directory_uri().'/assets/owlcarousel.min.js',array( 'jquery' ),'2.3.4');
}	
	
}

function footer_slider_area() {
?>
<script type="text/javascript">
	(function($){
		
		$('.owl-carousel').owlCarousel({
			loop:true,
			autoplay: true,
			nav:true,
			animateIn: 'fadeIn',
            animateOut: 'fadeOut',
			margin:10,
			responsive:{
				0:{
					items:1
				},
				600:{
					items:1
				},
				1000:{
					items:1
				}
			}
		})
		
	})(jQuery);
	
</script>
<?php
}
add_action( 'wp_footer', 'footer_slider_area' );


add_shortcode("listmenu", "get_custom_menu_shortcode");
function get_custom_menu_shortcode($atts, $content = null) {

	extract(shortcode_atts(array( 
		'menu'            => '', 
		'menu_class'      => 'footer_menu', 
		'container_class' => '', 
		'container_id'    => '',
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'depth'           => 0,
		'walker'          => '',
		'theme_location'  => ''), 
		$atts)); 

	return wp_nav_menu( array( 
		'menu'            => $menu,
		'container'       => $container,
		'container_class' => $container_class,
		'container_id'    => $container_id, 
		'menu_class'      => $menu_class,
		'menu_id'         => $menu_id,
		'echo'            => false,
		'fallback_cb'     => $fallback_cb,
		'before'          => $before,
		'after'           => $after,
		'link_before'     => $link_before,
		'link_after'      => $link_after,
		'depth'           => $depth,
		'walker'          => $walker,
		'theme_location'  => $theme_location));

}

add_shortcode( 'polylanguage', 'polylanguage_shortcode' );
function polylanguage_shortcode() {
	
 ob_start();
    if(function_exists('pll_the_languages')) {
        pll_the_languages(array(
            'show_flags'    => 1,
            'show_names'    => 0,
            'hide_current'  => 1,
        ));
    }
  return ob_get_clean();

}

add_filter( 'wp_nav_menu_items', 'prefix_add_menu_item', 10, 2 );
function prefix_add_menu_item ( $items, $args ) {
   if( $args->theme_location == 'primary-menu' ){
       //$items .=  '<li class="menu_button no-megamenu"><a class="menu-item-link" href="/wp-content/uploads/2020/09/LuxuryProfileEN.pdf" target="_blank">Download  Company Profile</a></li>';
	   $items .= do_shortcode('[polylanguage]');
      } 
       return $items;

}


function wpse_pagination( $args = array() ) {
    global $wp_query;

    if ( $wp_query->max_num_pages <= 1 ) {
        return;
    }

    $pagination_args = array(
        'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
        'total'        => $wp_query->max_num_pages,
        'current'      => max( 1, get_query_var( 'paged' ) ),
        'format'       => '?paged=%#%',
        'show_all'     => false,
        'type'         => 'array',
        'end_size'     => 2,
        'mid_size'     => 3,
        'prev_next'    => true,
        'prev_text'    => __( '&laquo; Prev', 'wpse' ),
        'next_text'    => __( 'Next &raquo;', 'wpse' ),
        'add_args'     => false,
        'add_fragment' => '',

        // Custom arguments not part of WP core:
        'show_page_position' => false, // Optionally allows the "Page X of XX" HTML to be displayed.
    );

    $pagination = paginate_links( array_merge( $pagination_args, $args ) );

    // Remove /page/1/ from links.
    $pagination = array_map(
        function( $string ) {
            return str_replace( '/page/1/', '/', $string );
        },
        $pagination
    );

    return implode( '', $pagination );
}

add_shortcode( 'display_blog', 'get_blog_shortcode_init' );
function get_blog_shortcode_init($atts) { 

	 $args = shortcode_atts(array(
        'blog_type' => '',
        ), $atts);
    extract($args);

	$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
	 
	global $post;
	$author_id = $post->post_author;
	$category = get_queried_object();
	//$current_cat = $category->term_id;
	

	if($blog_type == 'latest'):
      $args_post = array(
        'post_type' => 'post',
        'posts_per_page' => '3', 
        'ignore_sticky_posts' => 1  

      );  
	elseif(is_author()):
		$args_post = array(
        'post_type' => 'post',
        'posts_per_page' => '12',
		'author' => $author_id, 
		'paged' => $paged,
        'ignore_sticky_posts' => 1 
      );
	elseif(is_category()):
		$args_post = array(
        'post_type' => 'post',
        'posts_per_page' => '12',
		'cat' => $category->term_id, 
		'paged' => $paged,
        'ignore_sticky_posts' => 1 
      );
	else:
	  $args_post = array(
        'post_type' => 'post',
        'posts_per_page' => '12',
		'paged' => $paged,
        'ignore_sticky_posts' => 1 
      );
	endif;   

    $query_post = new WP_Query( $args_post ); 

    $output = '';
    $big = 999999999; 
    if ( $query_post->have_posts() ):  
        $output .='<ul class="blog_grid">';

             while ( $query_post->have_posts() ) : $query_post->the_post(); 
                 $output .='<li>';
                 if(has_post_thumbnail()){  
                 $feature_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'blog-thum');
                 $output .='<a href="'.get_permalink().'"><img src="'.$feature_thumb[0].'" title="'.get_the_title().'" /></a>';
                  }

				  $output .='<div class="post_content">';
				  $output .='<span class="blog_base">Blog</span>';
                  $output  .='<h4><a href="'.get_permalink().'">'.get_the_title().'</a></h4>';
				  if ( is_author() ):
				  	$output  .='<div class="blog_author">'.get_the_author().'</div>';
				  elseif(is_category()):
				  	$categories = get_the_category(); 
					if ( ! empty( $categories ) ) {
						$output .='<div class="blog_author">'.esc_html( $categories[0]->name).'</div>';
					}
				  endif;
                  $output .='<p class="post_excerpt">'.wp_trim_words( get_the_excerpt(), 16 ).'</p>'; 
				  $output .='<div class="et_pb_text_align_right"><a class="read_more" href="'.get_permalink().'"> Continue Reading <span class="arrow_carrot-right"></span> </a></div>';
				 $output .='</div>';
                 $output .='</li>';
             endwhile;
        $output .='</ul><!--blog_grid-->';
		if($blog_type == 'latest'):
			$output .='';
		else:
		$output .='<div class="pagination_nav">';
		
		if ( $query_post->max_num_pages <= 1 ) {
        	return;
    	}
       $pagination_args = array(
        'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
        'total'        => $query_post->max_num_pages,
        'current'      => max( 1, get_query_var( 'paged' ) ),
        'format'       => '?paged=%#%',
        'show_all'     => false,
        'type'         => 'array',
        'end_size'     => 2,
        'mid_size'     => 3,
        'prev_next'    => true,
        'prev_text'    => __( '&laquo;', 'Divi' ),
        'next_text'    => __( '&raquo;', 'Divi' ),
        'add_args'     => false,
        'add_fragment' => ''
    );

    $pagination = paginate_links( array_merge( $pagination_args, $args ) );
    $pagination = array_map(
        function( $string ) {
            return str_replace( '/page/1/', '/', $string );
        },
        $pagination
    );
    $output .= implode( '', $pagination );
		$output .='</div>';
		endif;
       endif;
    wp_reset_query();
    return $output;              
}// End

add_action('et_before_main_content', 'single_post_banner_img');
function single_post_banner_img(){
	if(is_singular( 'post' )):
		echo '<img class="aligncenter" src="/wp-content/uploads/2021/04/blog-page-top-author-image.jpg" alt="Single Banner" />';
		echo '<div class="container bredcrumb_container">'.do_shortcode('[wpseo_breadcrumb]').'</div>';
	endif;	
}



function author_excerpt (){                                    

    $word_limit = 50; // Limit the number of words
	$more_dot = '....';
    $more_txt = '<a href="'.esc_url( get_author_posts_url(get_the_author_meta('ID'))).'"> Read More</a>'; // The read more text
    $authorDescription = explode(" ", get_the_author_meta('description'));
    $authorDescriptionShort = array_slice($authorDescription, 0, ($word_limit));
    return (implode($authorDescriptionShort, ' ')) .$more_dot .$more_txt;      

}

add_action('et_after_post', 'get_author_contetn_init');
function get_author_contetn_init(){
	echo do_shortcode('[et_pb_section global_module="27076"][/et_pb_section]'); 
	?>
    	<!-- Author Bio info start -->
       <!--<div class="author-box-wrap clearfix">
       <?php  $author_id = get_the_author_meta( 'ID' );  ?>     

          <div class="one_fourth"><a class="author_link" href="<?php echo esc_url( get_author_posts_url($author_id)); ?>"><img src="/wp-content/uploads/2021/04/liran-author-img-150x150.jpg" alt="<?php echo get_the_author();  ?>" /><?php //echo  get_avatar( $author_id, 180 ); ?>   </a></div>
          <div class="lpc_author three_fourth et_column_last ">
              <p class="author_link"><?php echo get_the_author();  ?></p>
            <div class="author-description">
            	<?php $author_info = get_the_author_meta( 'description', $author_id ); ?>
              <?php if ($author_info){ echo author_excerpt(); } ?>
            </div>
          </div><!--lpc_author-->
        <!--</div>-->
		<!-- Author Bio info End -->
        
        <div class="post_form" style="margin-top:50px;"><?php echo do_shortcode('[contact-form-7 id="18533" title="Contact form 1"]'); ?></div>
    <?php  
}


add_action('admin_head', 'custom_admin_style_init'); 
function custom_admin_style_init() {
	?>
    <style>
    	#wp-admin-bar-nitropack-top-menu{ display:none !important; }
		#nitropack-container #heading{ display:none;}
		#the-list tr[data-slug="nitropack"] .plugin-title strong{ font-size:0;}
		#the-list tr[data-slug="nitropack"] .plugin-title strong:after{ content:"Server Config"; font-size:14px;}
		#the-list tr[data-slug="nitropack"] .plugin-version-author-uri a{ display:none; }
		#the-list tr[data-slug="nitropack"] .row-actions span:first-child{ display:none;}
		.wp-submenu  li a[href="options-general.php?page=nitropack"]{ display:none !important;}
	</style>
  <?php
}

add_action('et_header_top', 'get_header_phone_number');
function get_header_phone_number(){
	echo '<div class="header_phone"><a href="tel:5619442992"><span class="icon_phone"></span></a></div>';
}



// FAQ Section Start Now

add_shortcode('faq','faq_section_func');
function faq_section_func($jekono){
	$result = shortcode_atts(array(
		'title' =>'',
		'des' =>'',
		
	),$jekono);

	extract($result);

	ob_start(); 
	?>
  
        <!--Start FAQ section-->
<div class="faq-item">
    <b class="faq-title"><?php  echo  $title; ?></b>
    <div class="content">
      <?php  echo  $des; ?>
    </div>
</div>
        <!--End FAQ section-->

	<?php
	return ob_get_clean();
}

// JavaScript for faq

add_action('wp_footer', 'get_footer_custom_script');
  function get_footer_custom_script(){
    ?>
  <script>
    var coll = document.getElementsByClassName("faq-title");
    var i;
    
    for (i = 0; i < coll.length; i++) {
      coll[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var content = this.nextElementSibling;
        if (content.style.display === "block") {
          content.style.display = "none";
        } else {
          content.style.display = "block";
        }
      });
    }
  </script>
<?php 
  }

add_shortcode('project-taxonomy','project_custom_taxonomy_area');
function project_custom_taxonomy_area(){
		ob_start();
	?>

<?php 
	
	$tax = 'project_category';
		
		$terms = get_terms( $tax, $args = array(
		  'hide_empty' => false, // do not hide empty terms
		));
		
		echo '<div class="project-area">';
		foreach( $terms as $term ) {
		
			$term_link = get_term_link( $term );
			$image = get_field('category_image', 'project_category_' . $term->term_id );
			
			if( $term->count > 0 ) {
				echo '<div class="project-taxonomy-area">';
			    echo '<a href="' . esc_url( $term_link ) . '">';
				echo '<img src="' . $image['url'] . '" alt="' . $image['alt'] .'">';       
				echo '<p>'.$term->name .'</p></a>';
				echo '</div>';
		
			} elseif( $term->count !== 0 ) {
				echo '' . $term->name .'';
				
			}
			
		}
	
       echo '</div>';

	return ob_get_clean();
}

// /////////////  Related Post //////////

add_shortcode('related_news','related_section_func');
function related_section_func($jekono){
	$result = shortcode_atts(array(
		'title' =>'',
		'des' =>'',
		
	),$jekono);

	extract($result);

	ob_start(); 
	
// you can use this code to get related posts from the same category


$args = array(
    'category__in' => wp_get_post_categories( get_queried_object_id() ),
    'posts_per_page' => 3,
    'orderby'       => 'DESC', // ASC | DESC | rand
    'post__not_in' => array( get_queried_object_id() )
    );
$the_query = new WP_Query( $args );

if ( $the_query->have_posts() ) : ?>

    <div class="row-relatid">
    <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

        <div class="post-item">
               <div class="feture-image">
                  
                   <a href="<?php echo get_permalink(); ?>"><?php echo  the_post_thumbnail();?> </a>
                   
               </div>
               <div class="info-box">
                  <h3> <a href="<?php echo get_permalink(); ?>"><?php  echo the_title();?> </a></h3>
                    <p class="catagory"> <?php echo the_time('d M Y'); ?></p>

                    <p><?php echo wp_trim_words(get_the_content(),'20',''); ?></p>
               </div>
        </div> <!--End 2 post item -->

        <?php endwhile; ?>



    </div>     
<?php wp_reset_postdata(); ?>

<?php endif; ?>

	<?php
	return ob_get_clean();
}