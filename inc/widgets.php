<?php
/*
 * tf2013 Widgets
 * Proudly made with a lot of coffee
 */


function tf2013_widgets_init() {

       // Kurzinfo
        register_sidebar( array(
                'name' => __( 'Kurzinfo', 'tf2013' ),
                'id' => 'kurzinfo-area',
                'description' => __( 'Bereich unterhalb des Menus links', 'tf2013' ),
                'before_widget' => '<div id="kurzinfo">',
                'after_widget' => '</div>',
                'before_title' => '<h2>',
                'after_title' => '</h2>',
        ) );
       // Sidebar
        register_sidebar( array(
                'name' => __( 'Sidebar', 'tf2013' ),
                'id' => 'sidebar-area',
                'description' => __( 'Klassische Sidebar (rechts)', 'tf2013' ),
                'before_widget' => '<div class="widget">',
                'after_widget' => '</div>',
                'before_title' => '<h2>',
                'after_title' => '</h2>',
        ) );
	
	// Zusatzinfo (Footer)
        register_sidebar( array(
                'name' => __( 'Inhaltsinfo', 'tf2013' ),
                'id' => 'inhaltsinfo-area',
                'description' => __( 'Feststehender Inhalt vor den eigentlichen Content', 'tf2013' ),
                'before_widget' => '<div class="widget">',
                'after_widget' => '</div>',
                'before_title' => '<h2>',
                'after_title' => '</h2>',
        ) );
	
	// Zusatzinfo (Footer)
        register_sidebar( array(
                'name' => __( 'Zusatzinfo', 'tf2013' ),
                'id' => 'zusatzinfo-area',
                'description' => __( 'Zusatzinfo im Footer', 'tf2013' ),
                'before_widget' => '<div class="widget">',
                'after_widget' => '</div>',
                'before_title' => '<h2>',
                'after_title' => '</h2>',
        ) );

     

}
add_action( 'widgets_init', 'tf2013_widgets_init' );


/**
 * Adds Newsletter_Widget widget.
 */


/**
 * Adds Newsletter_Widget widget.
 */
class FAULinkliste_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'FAULinkliste_Widget', // Base ID
                        __( 'FAU  Linkliste', 'tf2013' ),
			array( 'description' => __( 'Linkliste mit verschiedenen Webangeboten des RRZE und der FAU', 'tf2013' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {            
            extract( $args );
            $bereich =  $instance['bereich'] ;
            if ((!isset($bereich)) || (empty($bereich))) {
                $bereich = $defaultoptions['default_footerlink_key'];
            }
		echo $before_widget;
                global $default_footerlink_liste; 
                
                $title =   $default_footerlink_liste[$bereich]['title'];
                $url =   $default_footerlink_liste[$bereich]['url'];
  
                  if ((isset($url)) && (strlen($url)>5)) {
                        echo $before_title.'<a href="'.$url.'">'.$title.'</a>'.$after_title;
                  } else {
                        echo $before_title.$title.$after_title;
                  }
                  echo '<ul class="FAULinkliste">';
                  
                  foreach($default_footerlink_liste[$bereich]['sublist'] as $i => $value) {
                       echo '<li><a href="'.$value.'">';                                                                                                        
                       echo $i.'</a></li>';
                       echo "\n";
                 }            
                 echo '</ul>';     
               
               echo $after_widget;
                
                
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
                $instance['bereich'] = strip_tags( $new_instance['bereich'] );
		return $instance;
	}

	/**
	 * Back-end widget form.
	 * @see WP_Widget::form()
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		
                if ( isset( $instance[ 'bereich' ] ) ) {
			$bereich = $instance[ 'bereich' ];
		} else {
			$bereich = $defaultoptions['default_footerlink_key'];
		}                 
 
                global $default_footerlink_liste;
                echo "<select name=\"".$this->get_field_name( 'bereich' )."\">\n";

                foreach($default_footerlink_liste as $i => $value) {   
                    echo "\t\t\t\t";
                    echo '<option value="'.$i.'"';
                    if ( $i == $bereich ) {
                        echo ' selected="selected"';
                    }                                                                                                                                                                
                    echo '>';
                    if (!is_array($value)) {
                        echo $value;
                    } else {
                        echo $i;
                    }     
                    echo '</option>';                                                                                                                                                              
                    echo "\n";                                            
                }  
                echo "</select><br>\n";                                   
                echo "\t\t\t";
                echo "<label for=\"".$this->get_field_name( 'bereich' )."\">".__( 'Bereich ausw&auml;hlen.', 'tf2013' )."</label>\n"; 
      
	}

} // class Partei Linkliste Widget
//
// register widget
add_action( 'widgets_init', create_function( '', 'register_widget( "FAULinkliste_Widget" );' ) );

?>
