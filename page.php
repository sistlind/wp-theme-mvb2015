
<?php
get_header(); ?>
	
	
<?php 
	   if ( have_posts() ) while ( have_posts() ) : the_post();         
		$custom_fields = get_post_custom();

				
	    the_content(); 
	   


	    wp_link_pages( array( 'before' => '' . __( 'Seiten:', 'tf2013' ), 'after' => '' ) );                   
	    edit_post_link( __( 'Bearbeiten', 'tf2013' ), '', '' );
        endwhile; 
	?>
	
<?php get_footer(); ?>