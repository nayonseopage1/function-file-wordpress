<?php get_header(); ?>
<div class="container">
	<?php 
		$terms = get_the_terms( $post->ID, 'project_category' );
		if ( !empty( $terms ) ){
			// get the first term
			$term = array_shift( $terms );
			echo '<h2 class="tx-slug">';
			echo $term->slug;
			echo '</h2>';
		}
	?>
	
 <div class="taxonomy-post-sec">
<?php 

	$post_type = 'project';
    $taxonomies = get_object_taxonomies( array( 'post_type' => $post_type ) );
    foreach( $taxonomies as $taxonomy ) :

		$terms = get_the_terms( $post->ID, 'project_category' ); ?>
        <?php foreach( $terms as $term ) : ?>
         
            <?php
            $args = array(
              'post_type' => $post_type,
              'posts_per_page' => -1,  //show all posts
              'tax_query' => array(
                array(
                    'taxonomy' => $taxonomy,
                    'field' => 'slug',
                    'terms' => $term->slug
                  )
                )
              );
            $posts_colours = new WP_Query($args);

            if( $posts_colours->have_posts() ): while( $posts_colours->have_posts() ) : $posts_colours->the_post(); ?>
               <div class="taxonomy-post-area">
				  
				   <div class='taxonomy-image-area'>
                 		<?php the_post_thumbnail(); ?>
				   </div>
				   <p class="tx-button"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
				</div>

            <?php endwhile; endif; ?>

        <?php endforeach;

    endforeach; ?>

</div>
	</div>


<?php get_footer(); ?>