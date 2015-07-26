<?php get_header();
  global $options;

     if ( is_active_sidebar( 'inhaltsinfo-area' ) ) {
    	 dynamic_sidebar( 'inhaltsinfo-area' );
    }

      $i = 0;
      $col = 0;

      $numentries = $options['num-article-startpage-fullwidth'] + $options['num-article-startpage-halfwidth'];
      $col_count = 3;
      $cols = array();
      while (have_posts() && $i<$numentries) : the_post();
      $i++;
      ob_start();
      if (( isset($options['num-article-startpage-fullwidth']))
                && ($options['num-article-startpage-fullwidth']>=$i )) {
		 tf2013_post_teaser($options['teaser-titleup'],$options['teaser-datebox'],$options['teaser-dateline'],$options['teaser_maxlength'],$options['teaser-thumbnail_fallback'],$options['teaser-floating']);
      } else {
		 tf2013_post_teaser($options['teaser-titleup-halfwidth'],$options['teaser-datebox-halfwidth'],$options['teaser-dateline-halfwidth'],$options['teaser-maxlength-halfwidth'],$options['teaser-thumbnail_fallback'],$options['teaser-floating-halfwidth']);
      }
      $output = ob_get_contents();
      ob_end_clean();
      if (isset($output)) {
        $cols[$col++] = $output;
      }
      endwhile;
      ?>
      <div class="columns">
        <?php
        $z=1;
        foreach($cols as $key => $col) {
            if (( isset($options['num-article-startpage-fullwidth']))
                && ($options['num-article-startpage-fullwidth']>$key )) {
                    echo $col;
                } else {
                     if (( isset($options['num-article-startpage-fullwidth']))
                            && ($options['num-article-startpage-fullwidth']==$key )
                             && ($options['num-article-startpage-fullwidth']>0 )) {
                         echo '<hr class="trenner">';
                        }
                    echo '<div class="column'.$z.'">' . $col . '</div>';
                    $z++;
                    if ($z>2) {
                        $z=1;
                        echo '<hr class="trenner">';
                    }
                }
        }
        ?>
      </div>
<?php  if ($wp_query->max_num_pages > 1) : ?>
	<div class="post-nav"><ul>
		<li class="back"><?php next_posts_link(__('&larr; &Auml;ltere Beitr&auml;ge', 'tf2013')); ?></li>
		<li class="forward"><?php previous_posts_link(__('Neuere Beitr&auml;ge &rarr;', 'tf2013')); ?></li>
	</ul></div>
<?php endif; ?>

      <?php if ( ! have_posts() ) : ?>
       <h2><?php _e("Nichts gefunden", 'tf2013'); ?></h2>
        <p>
            <?php _e("Es konnten keine Artikel gefunden werden. Bitte versuchen Sie es nochmal mit einer Suche.", 'tf2013'); ?>
        </p>
        <?php get_search_form(); ?>
        <hr>
      <?php endif; ?>



<?php get_footer(); ?>