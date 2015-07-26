<?php
get_header();
global $options;
global $wp_query;
?>


<?php if (have_posts()) : ?>
	<?php
	/* Run the loop for the search to output the results.
	 * If you want to overload this in a child theme then include a file
	 * called loop-search.php and that will be used instead.
	 */

	$i = 0;
	$col = 0;

	$numentries = $options['category-num-article-fullwidth'] + $options['category-num-article-halfwidth'];
	$col_count = 3;
	$cols = array();

	global $query_string;
	query_posts($query_string . '&cat=$thisCat');

	while (have_posts() && $i < $numentries) : the_post();
		$i++;
		ob_start();
		if (( isset($options['category-num-article-fullwidth'])) && ($options['category-num-article-fullwidth'] >= $i )) {
			tf2013_post_teaser($options['category-teaser-titleup'], $options['category-teaser-datebox'], $options['category-teaser-dateline'], $options['category-teaser-maxlength'], $options['teaser-thumbnail_fallback'], $options['category-teaser-floating']);
		} else {
			tf2013_post_teaser($options['category-teaser-titleup-halfwidth'], $options['category-teaser-datebox-halfwidth'], $options['category-teaser-dateline-halfwidth'], $options['category-teaser-maxlength-halfwidth'], $options['teaser-thumbnail_fallback'], $options['category-teaser-floating-halfwidth']);
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
		$z = 1;
		foreach ($cols as $key => $col) {
			if (( isset($options['category-num-article-fullwidth'])) && ($options['category-num-article-fullwidth'] > $key )) {
				echo $col;
			} else {
				if (( isset($options['category-num-article-fullwidth'])) && ($options['category-num-article-fullwidth'] == $key ) && ($options['category-num-article-fullwidth'] > 0)) {
					echo '<hr>';
				}
				echo '<div class="column' . $z . '">' . $col . '</div>';
				$z++;
				if ($z > 2) {
					$z = 1;
					echo '<hr style="clear: both;">';
				}
			}
		}
		?>
	</div>

		<?php if ($wp_query->max_num_pages > 1) : ?>
		<div class="archiv-nav"><p>
		<?php next_posts_link(__('&larr; &Auml;ltere Beitr&auml;ge', 'tf2013')); ?>
		<?php previous_posts_link(__('Neuere Beitr&auml;ge &rarr;', 'tf2013')); ?>
			</p></div>
			<?php endif; ?>



<?php else : ?>
	<h2><?php _e("Nichts gefunden", 'tf2013'); ?></h2>
	<p>
	<?php _e("Es konnten keine Seiten oder Artikel gefunden werden, die zu der Sucheingabe passten. Bitte versuchen Sie es nochmal mit einer  anderen Suche.", 'tf2013'); ?>

	</p>
		<?php get_search_form(); ?>

	<p>
	<?php _e("Alternativ verwenden Sie einen der folgenden Links.", 'tf2013'); ?>

	</p>

	<div class="widget">
		<h3><?php _e("Archiv nach Monaten", 'tf2013'); ?></h3>
	<?php wp_get_archives('type=monthly'); ?>
	</div>

	<div  class="widget">
		<h3><?php _e("Artikel nach Schlagworten", 'tf2013'); ?></h3>
		<div class="tagcloud">
	<?php wp_tag_cloud(array('smallest' => 12, 'largest' => 28)); ?>
		</div>
	</div>
	<div class="widget">
		<h3><?php _e("&Uuml;bersicht aller Kategorien", 'tf2013'); ?></h3>
		<ul>
	<?php wp_list_categories('title_li='); ?>
		</ul>
	</div>


<?php endif; ?>


<?php get_footer();
