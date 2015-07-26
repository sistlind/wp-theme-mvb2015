<?php           
 
  echo '<aside><div id="sidebar">  <!-- begin: sidebar -->';
  
  

  get_tf2013_buttons();

  get_tf2013_socialmediaicons();

    
   if ( is_active_sidebar( 'sidebar-area' ) ) { 
	 dynamic_sidebar( 'sidebar-area' ); 
		   
    } 
		
    echo '</div></aside>';
?>		