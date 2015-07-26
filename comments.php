<?php 
global $defaultoptions;
global $options;

if ( post_password_required() ) : ?>
    <p><?php _e("Dieser Artikel ist Passwortgesch&uuml;tzt. Bitte gib das Passwort ein um die Kommentare zu sehen.", 'tf2013'); ?></p>
    <?php return;
endif; 
if ( have_comments() ) : ?>
    <h2 id="comments-title"><?php _e("Kommentare", 'tf2013'); ?></h2>
     <p>   
    <?php printf( _n( 'Ein Kommentar zu %2$s', '%1$s Kommentare zu %2$s', get_comments_number(), 'tf2013' ), number_format_i18n( get_comments_number() ), '' . get_the_title() . '' ); ?>
    </p>
    <?php 
    if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :  
           previous_comments_link( __( '&larr; &Auml;ltere Kommentare', 'tf2013' ) ); 
           next_comments_link( __( 'Neuere Kommentare &rarr;', 'tf2013' ) ); 
    endif; ?>
    <ol>
            <?php wp_list_comments( array( 'callback' => 'tf2013_comment' ) ); ?>
    </ol>

    <?php 
     if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : 
             previous_comments_link( __( '&larr; &Auml;ltere Kommentare', 'tf2013' ) ); 
             next_comments_link( __( 'Neuere Kommentare &rarr;', 'tf2013' ) ); 
     endif; 
     if ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' )  ) : ?>
	<p><?php _e("Das Kommentieren dieses Artikels ist nicht (mehr) m&ouml;glich.", 'tf2013'); ?></p>
<?php
    endif; 
 endif; 
     
$comment_before = '';
if (isset($options['comments_disclaimer'])) {
    $comment_before = '<div class="comment-disclaimer">'.$options['comments_disclaimer'] .'</div>';
}


if (isset($options['anonymize-user']) && ($options['anonymize-user']==1)) {
    // Emailadresse kann/soll weggelassen werden
    
    if ($options['anonymize-user-commententries']==1) {
        // Nur Autorname        
         $comments_args = array( 'fields' => array(
        'author' => '<p class="comment-form-author">' . '<label for="author">' . __( 'Name', 'tf2013' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .
	            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>'
       ),
        'comment_notes_before' => $comment_before,
        );
        comment_form( $comments_args); 
    } elseif ($options['anonymize-user-commententries']==2) {
        // Name + URL
         $comments_args = array( 'fields' => array(
        'author' => '<p class="comment-form-author">' . '<label for="author">' . __( 'Name', 'tf2013' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .
	            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>',
        'url'    => '<p class="comment-form-url"><label for="url">' . __( 'Website', 'tf2013' ) . '</label>'.
	            '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>'
       ),
        'comment_notes_before' => $comment_before,
        );
        comment_form( $comments_args); 
    } else {
        // WP-Default (Name+Email+URL)
        
          $comment_before = $comments_before. $defaultoptions['default_comment_notes_before'];         
          comment_form( array( 'comment_notes_before' => $comment_before ) ); 
    }           
        
} else {
  
     $comment_before =  $comment_before. $defaultoptions['default_comment_notes_before'];    
     comment_form( array( 'comment_notes_before' => $comment_before ) ); 
}
?> 