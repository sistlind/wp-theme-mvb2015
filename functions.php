<?php
/**
 * Techfak2013 Theme Optionen
 *
 * @source http://github.com/RRZE-Webteam/Design-RRZE-Techfak2013
 * @creator xwolf
 * @version 1.1
 * @licence GPL
 */
require( get_template_directory() . '/inc/constants.php' );
$options = get_option('tf2013_theme_options');
$options = tf2013_compatibility($options);

// ** bw 2012-08-12 wordpress reverse proxy x-forwarded-for ip fix ** //
if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	$xffaddrs = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
	$_SERVER['REMOTE_ADDR'] = $xffaddrs[0];
}



if (!isset($content_width))
	$content_width = $defaultoptions['content-width'];
require_once ( get_template_directory() . '/inc/theme-options.php' );

add_action('after_setup_theme', 'tf2013_setup');

if (!function_exists('tf2013_setup')):

	function tf2013_setup() {
		global $defaultoptions;
		global $options;
		// This theme styles the visual editor with editor-style.css to match the theme style.
		add_editor_style();
		// This theme uses post thumbnails
		add_theme_support('post-thumbnails');
		// Add default posts and comments RSS feed links to head
		add_theme_support('automatic-feed-links');

		$args = array(
			'default-color' => $defaultoptions['background-header-color'],
			'background_repeat' => 'no-repeat',
			'background_position_x' => 'left',
			'background_position_y' => 'top',
			'width' => 1173,
			'height' => 135,
			'default-image' => get_template_directory_uri() . '/grafiken/techfak-2013/header-techfak-3b.png',
			'uploads' => true,
			'random-default' => false,
			'flex-height' => false,
			'flex-width' => false,
			'header-text' => true,
			'default-text-color' => '#003366',
		);
		add_theme_support('custom-header', $args);

		if (function_exists('add_theme_support')) {
			add_theme_support('post-thumbnails');
			set_post_thumbnail_size(150, 150); // default Post Thumbnail dimensions
		}

		if (function_exists('add_image_size')) {
			add_image_size('teaser-thumb', $options['teaser-thumbnail_width'], $options['teaser-thumbnail_height'], $options['teaser-thumbnail_crop']); //300 pixels wide (and unlimited height)
		}


		// Make theme available for translation
		// Translations can be filed in the /languages/ directory
		load_theme_textdomain('tf2013', get_template_directory() . '/languages');
		$locale = get_locale();
		$locale_file = get_template_directory() . "/languages/$locale.php";
		if (is_readable($locale_file))
			require_once( $locale_file );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(array(
			'primary' => __('Hauptnavigation', 'tf2013'),
			'targetmenu' => __('Zielgruppenmenu', 'tf2013'),
		));
		if( !is_blogs_fau_de() )
			register_nav_menu ('tecmenu', __('Technische Navigation (Kontakt, Impressum, etc)', 'tf2013'));

		if ($options['login_errors'] == 0) {
			/** Abschalten von Fehlermeldungen auf der Loginseite */
			add_filter('login_errors', create_function('$a', "return null;"));
		}
		/** Entfernen der Wordpressversionsnr im Header */
		remove_action('wp_head', 'wp_generator');

		/* Zulassen von Shortcodes in Widgets */
		add_filter('widget_text', 'do_shortcode');
	}

endif;

require( get_template_directory() . '/inc/widgets.php' );

function tf2013_scripts() {

	wp_register_script('themeadminscripts', get_template_directory_uri() . '/js/admin.js', array('jquery'));
	wp_enqueue_script('themeadminscripts');
	if ( is_singular() ) wp_enqueue_script( "comment-reply" );
}

add_action('admin_enqueue_scripts', 'tf2013_scripts');

function tf2013_admin_style() {

	wp_register_style('themeadminstyle', get_template_directory_uri() . '/css/admin.css');
	wp_enqueue_style('themeadminstyle');
	wp_enqueue_media();
	wp_register_script('themeadminscripts', get_template_directory_uri() . '/js/admin.js', array('jquery'));
	wp_enqueue_script('themeadminscripts');

	if (is_admin()) {
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('wp-link');
	}
}

add_action('admin_enqueue_scripts', 'tf2013_admin_style');

/* Refuse spam-comments on media */

function filter_media_comment_status($open, $post_id) {
	$post = get_post($post_id);
	if ($post->post_type == 'attachment') {
		return false;
	}
	return $open;
}

add_filter('comments_open', 'filter_media_comment_status', 10, 2);

function tf2013_compatibility($oldoptions) {
	global $defaultoptions;

	if (!is_array($oldoptions)) {
		$oldoptions = array();
	}
	$newoptions = array_merge($defaultoptions, $oldoptions);

	return $newoptions;
}

if (!function_exists('get_tf2013_options')) :
	/*
	 * Erstes Bild aus einem Artikel auslesen, wenn dies vorhanden ist
	 */

	function get_tf2013_options($field) {
		global $defaultoptions;
		if (!isset($field)) {
			$field = 'tf2013_theme_options';
		}
		$orig = get_option($field);
		if (!is_array($orig)) {
			$orig = array();
		}
		$alloptions = array_merge($defaultoptions, $orig);
		return $alloptions;
	}

endif;



/*
 * Adds optional styles in header
 */

function tf2013_add_basemod_styles() {
	global $options;
	global $defaultoptions;

	if (!is_admin()) {
		$theme = wp_get_theme();
		wp_register_style('tf2013', get_bloginfo('stylesheet_url'), false, $theme['Version']);
		wp_enqueue_style('tf2013');
	}
	if ((isset($options['aktiv-basemod_zusatzinfo'])) && ($options['aktiv-basemod_zusatzinfo'] == 1)) {
		wp_enqueue_style('basemod_zusatzinfo', $defaultoptions['src_basemod_zusatzinfo']);
	}
	if ((isset($options['aktiv-basemod_links'])) && ($options['aktiv-basemod_links'] == 1)) {
		wp_enqueue_style('basemod_links', $defaultoptions['src_basemod_links']);
	}
	if ((isset($options['aktiv-basemod_sidebar'])) && ($options['aktiv-basemod_sidebar'] == 1)) {
		wp_enqueue_style('basemod_sidebar', $defaultoptions['src_basemod_sidebar']);
	}

	if (/* (isset($options['farbvarianten'])) && */ (isset($options['src_' . $options['farbvarianten']]))) {
		wp_register_style('farbvarianten', $defaultoptions['src_' . $options['farbvarianten']]);
		wp_enqueue_style('farbvarianten');
	}

	if ((isset($options['aktiv-socialmediabuttons'])) && ($options['aktiv-socialmediabuttons'] == 1)) {
		wp_enqueue_style('basemod_socialmediabuttons', $defaultoptions['src_socialmediabuttons']);
	}
}

add_action('wp_enqueue_scripts', 'tf2013_add_basemod_styles');

function tf2013_admin_head() {
	echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/css/admin.css" />';
}

add_action('admin_head', 'tf2013_admin_head');



/*
 * Breadcrumb
 */

function tf2013_breadcrumbs() {
	global $defaultoptions;
	global $options;
	$delimiter = '<img width="20" height="9" alt=" &raquo; " src="' . $defaultoptions['src-breadcrumb-image'] . '">';
	$home = $options['text-startseite']; // text for the 'Home' link
	$before = '<span class="current">'; // tag before the current crumb
	$after = '</span>'; // tag after the current crumb

	if (!is_home() && !is_front_page() || is_paged()) {

		global $post;
		$homeLink = home_url('/');
		echo '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';

		if (is_category()) {
			global $wp_query;
			$cat_obj = $wp_query->get_queried_object();
			$thisCat = $cat_obj->term_id;
			$thisCat = get_category($thisCat);
			$parentCat = get_category($thisCat->parent);
			if ($thisCat->parent != 0)
				echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
			echo $before . __('Artikel der Kategorie ', 'tf2013') . '"' . single_cat_title('', false) . '"' . $after;
		} elseif (is_day()) {
			echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
			echo '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
			echo $before . get_the_time('d') . $after;
		} elseif (is_month()) {
			echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
			echo $before . get_the_time('F') . $after;
		} elseif (is_year()) {
			echo $before . get_the_time('Y') . $after;
		} elseif (is_single() && !is_attachment()) {
			if (get_post_type() != 'post') {
				$post_type = get_post_type_object(get_post_type());
				$slug = $post_type->rewrite;
				echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
				echo $before . get_the_title() . $after;
			} else {
				$cat = get_the_category();
				$cat = $cat[0];
				echo is_wp_error($cat_parents = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ')) ? '' : $cat_parents;
				echo $before . get_the_title() . $after;
			}
		} elseif (!is_single() && !is_page() && !is_search() && get_post_type() != 'post' && !is_404()) {
			$post_type = get_post_type_object(get_post_type());
			echo $before . $post_type->labels->singular_name . $after;
		} elseif (is_attachment()) {
			$parent = get_post($post->post_parent);
			$cat = get_the_category($parent->ID);
			$cat = $cat[0];
			echo is_wp_error($cat_parents = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ')) ? '' : $cat_parents;
			echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
			echo $before . get_the_title() . $after;
		} elseif (is_page() && !$post->post_parent) {
			echo $before . get_the_title() . $after;
		} elseif (is_page() && $post->post_parent) {
			$parent_id = $post->post_parent;
			$breadcrumbs = array();
			while ($parent_id) {
				$page = get_page($parent_id);
				$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
				$parent_id = $page->post_parent;
			}
			$breadcrumbs = array_reverse($breadcrumbs);
			foreach ($breadcrumbs as $crumb)
				echo $crumb . ' ' . $delimiter . ' ';
			echo $before . get_the_title() . $after;
		} elseif (is_search()) {
			echo $before . __('Suche nach ', 'tf2013') . '"' . get_search_query() . '"' . $after;
		} elseif (is_tag()) {
			echo $before . __('Artikel mit Schlagwort ', 'tf2013') . '"' . single_tag_title('', false) . '"' . $after;
		} elseif (is_author()) {
			global $author;
			$userdata = get_userdata($author);
			echo $before . __('Artikel von ', 'tf2013') . $userdata->display_name . $after;
		} elseif (is_404()) {
			echo $before . '404' . $after;
		}
		/*
		  if ( get_query_var('paged') ) {
		  if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
		  echo __('Page', 'tf2013') . ' ' . get_query_var('paged');
		  if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
		  }
		 */
	} elseif (is_home() || is_front_page()) {
		echo $before . $home . $after;
	}
}

function tf2013_contenttitle() {
	global $defaultoptions;
	global $options;
	$before = '';
	$after = '';
	$delimiter = ': ';
	$home = $options['text-startseite']; // text for the 'Home' link

	if (!is_home() && !is_front_page() || is_paged()) {
		global $post;
		$homeLink = home_url('/');

		if (is_category()) {
			global $wp_query;
			$cat_obj = $wp_query->get_queried_object();
			$thisCat = $cat_obj->term_id;
			$thisCat = get_category($thisCat);
			$parentCat = get_category($thisCat->parent);
			if ($thisCat->parent != 0)
				echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
			echo $before . __('Artikel der Kategorie ', 'tf2013') . '"' . single_cat_title('', false) . '"' . $after;
		} elseif (is_day()) {
			echo $before . get_the_time(_x('j. F Y','Date format for Daily Archive Title','tf2013')) . $after;
		} elseif (is_month()) {
			echo $before . get_the_time('F Y') . $after;
		} elseif (is_year()) {
			echo $before . get_the_time('Y') . $after;
		} elseif (is_single() && !is_attachment()) {
			echo $before . get_the_title() . $after;
		} elseif (!is_single() && !is_page() && !is_search() && get_post_type() != 'post' && !is_404()) {
			$post_type = get_post_type_object(get_post_type());
			echo $before . $post_type->labels->singular_name . $after;
		} elseif (is_attachment()) {
			echo $before . get_the_title() . $after;
		} elseif (is_page() && !$post->post_parent) {
			echo $before . get_the_title() . $after;
		} elseif (is_page() && $post->post_parent) {
			echo $before . get_the_title() . $after;
		} elseif (is_search()) {
			echo $before . __('Suche nach ', 'tf2013') . '"' . get_search_query() . '"' . $after;
		} elseif (is_tag()) {
			echo $before . __('Artikel mit Schlagwort ', 'tf2013') . '"' . single_tag_title('', false) . '"' . $after;
		} elseif (is_author()) {
			global $author;
			$userdata = get_userdata($author);
			echo $before . __('Artikel von ', 'tf2013') . $userdata->display_name . $after;
		} elseif (is_404()) {
			echo $before . '404' . $after;
		} else {
			echo $before . get_the_title() . $after;
		}
	} elseif (is_home() || is_front_page()) {
		//echo $before . $home . $after;
	}
}

if (!function_exists('tf2013_filter_wp_title')) :
	/*
	 * Sets the title
	 */

	function tf2013_filter_wp_title($title, $separator) {
		// Don't affect wp_title() calls in feeds.
		if (is_feed())
			return $title;

		// The $paged global variable contains the page number of a listing of posts.
		// The $page global variable contains the page number of a single post that is paged.
		// We'll display whichever one applies, if we're not looking at the first page.
		global $paged, $page;

		if (is_search()) {
			// If we're a search, let's start over:
			$title = sprintf(__('Suchergebnisse f&uuml;r %s', 'tf2013'), '"' . get_search_query() . '"');
			// Add a page number if we're on page 2 or more:
			if ($paged >= 2)
				$title .= " $separator " . sprintf(__('Seite %s', 'tf2013'), $paged);
			// Add the site name to the end:
			$title .= " $separator " . get_bloginfo('name', 'display');
			// We're done. Let's send the new title back to wp_title():
			return $title;
		}

		// Otherwise, let's start by adding the site name to the end:
		$title .= get_bloginfo('name', 'display');

		// If we have a site description and we're on the home/front page, add the description:
		$site_description = get_bloginfo('description', 'display');
		if ($site_description && ( is_home() || is_front_page() ))
			$title .= " $separator " . $site_description;

		// Add a page number if necessary:
		if ($paged >= 2 || $page >= 2)
			$title .= " $separator " . sprintf(__('Seite %s', 'tf2013'), max($paged, $page));

		// Return the new title to wp_title():
		return $title;
	}

endif;

add_filter('wp_title', 'tf2013_filter_wp_title', 10, 2);

function tf2013_excerpt_length($length) {
	global $defaultoptions;
	return $defaultoptions['teaser_maxlength'];
}

add_filter('excerpt_length', 'tf2013_excerpt_length');

function tf2013_continue_reading_link() {
	return ' <a class="nobr weiter" title="' . strip_tags(get_the_title()) . '" href="' . get_permalink() . '">' . __('Weiterlesen <span class="meta-nav">&rarr;</span>', 'tf2013') . '</a>';
}

function tf2013_auto_excerpt_more($more) {
	return ' &hellip;' . tf2013_continue_reading_link();
}

add_filter('excerpt_more', 'tf2013_auto_excerpt_more');

function tf2013_custom_excerpt_more($output) {
	if (has_excerpt() && !is_attachment()) {
		$output .= tf2013_continue_reading_link();
	}
	return $output;
}

add_filter('get_the_excerpt', 'tf2013_custom_excerpt_more');

function tf2013_remove_gallery_css($css) {
	return preg_replace("#<style type='text/css'>(.*?)</style>#s", '', $css);
}

add_filter('gallery_style', 'tf2013_remove_gallery_css');


function is_blogs_fau_de() {
	$http_host = filter_input(INPUT_SERVER, 'HTTP_HOST');
	if( $http_host == 'blogs.fau.de')
		return true;
	else
		return false;
}


if (!function_exists('tf2013_comment')) :

/**
 * Template for comments and pingbacks.
 */
function tf2013_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	global $defaultoptions;
	global $options;

	switch ($comment->comment_type) :
		case '' :
		?>
			<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
				<div id="comment-<?php comment_ID(); ?>">
					<div class="comment-details">
						<div class="comment-author vcard">
							<?php
							printf(
								__('%s <span class="says">meinte am</span>', 'tf2013'),
								sprintf('<cite class="fn">%s</cite>', get_comment_author_link())
							);
							?>
						</div><!-- .comment-author .vcard -->
						<?php if ($comment->comment_approved == '0') : ?>
							<em><?php _e('Der Kommentar wartet auf die Freischaltung.', 'tf2013'); ?></em>
							<br />
						<?php endif; ?>

						<div class="comment-meta commentmetadata"><a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
								<?php
								/* translators: 1: date, 2: time */
								printf(__('%1$s um %2$s', 'tf2013'), get_comment_date(), get_comment_time());
								?></a> Folgendes:<?php edit_comment_link(__('(Edit)', 'tf2013'), ' ');
								?>
						</div><!-- .comment-meta .commentmetadata -->
					</div>

					<div class="comment-body"><?php comment_text(); ?></div>
					<?php if ($options['aktiv-commentreplylink']) { ?>
						<div class="reply">
							<?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
						</div> <!-- .reply -->
					<?php } ?>


				</div><!-- #comment-##  -->

				<?php
				break;
		case 'pingback' :
		case 'trackback' :
		?>
			<li class="post pingback">
				<p><?php _e('Pingback:', 'tf2013'); ?> <?php comment_author_link(); ?><?php edit_comment_link(__('(Edit)', 'tf2013'), ' '); ?></p>
			<?php
			break;
	endswitch;
}

endif;

function tf2013_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
}

add_action('widgets_init', 'tf2013_remove_recent_comments_style');


if (!function_exists('tf2013_post_teaser')) :

/**
 * Erstellung eines Artikelteasers
 */
	function tf2013_post_teaser($titleup = 1, $showdatebox = 1, $showdateline = 0, $teaserlength = 200, $thumbfallback = 1, $usefloating = 0) {
	global $defaultoptions;
	global $options;
	global $post;

	$sizeclass = '';
	$leftbox = '';
	if ($showdatebox == 0) {
		$showdatebox = 1;
	}
	if ($showdatebox != 5) {
		$sizeclass = 'ym-column withthumb';
		// Generate Thumb/Pic or Video first to find out which class we need

		$leftbox .= '<div class="infoimage">';
		$sizeclass = 'ym-column withthumb';
		$thumbnailcode = '';
		$firstpic = '';
		$firstvideo = '';
		if (has_post_thumbnail()) {
			$thumbnailcode = get_the_post_thumbnail($post->ID, 'teaser-thumb');
		}

		$firstpic = get_tf2013_firstpicture();
		$firstvideo = get_tf2013_firstvideo();
		$fallbackimg = '<img src="' . $defaultoptions['src-teaser-thumbnail_default'] . '" alt="">';
		$output = '';
		if ($showdatebox == 1) {
			if ((isset($thumbnailcode)) && (strlen(trim($thumbnailcode)) > 10)) {
				$output = $thumbnailcode;
			} elseif ((isset($firstpic)) && (strlen(trim($firstpic)) > 10)) {
				$output = $firstpic;
			} elseif ((isset($firstvideo)) && (strlen(trim($firstvideo)) > 10)) {
				$output = $firstvideo;
				$sizeclass = 'ym-column withvideo';
			} else {
				$output = $fallbackimg;
			}
		} elseif ($showdatebox == 2) {

			if ((isset($firstpic)) && (strlen(trim($firstpic)) > 10)) {
				$output = $firstpic;
			} elseif ((isset($thumbnailcode)) && (strlen(trim($thumbnailcode)) > 10)) {
				$output = $thumbnailcode;
			} elseif ((isset($firstvideo)) && (strlen(trim($firstvideo)) > 10)) {
				$output = $firstvideo;
				$sizeclass = 'ym-column withvideo';
			} else {
				$output = $fallbackimg;
			}
		} elseif ($showdatebox == 3) {
			if ((isset($firstvideo)) && (strlen(trim($firstvideo)) > 10)) {
				$output = $firstvideo;
				$sizeclass = 'ym-column withvideo';
			} elseif ((isset($thumbnailcode)) && (strlen(trim($thumbnailcode)) > 10)) {
				$output = $thumbnailcode;
			} elseif ((isset($firstpic)) && (strlen(trim($firstpic)) > 10)) {
				$output = $firstpic;
			} else {
				$output = $fallbackimg;
			}
		} elseif ($showdatebox == 4) {
			if ((isset($firstvideo)) && (strlen(trim($firstvideo)) > 10)) {
				$output = $firstvideo;
				$sizeclass = 'ym-column withvideo';
			} elseif ((isset($firstpic)) && (strlen(trim($firstpic)) > 10)) {
				$output = $firstpic;
			} elseif ((isset($thumbnailcode)) && (strlen(trim($thumbnailcode)) > 10)) {
				$output = $thumbnailcode;
			} else {
				$output = $fallbackimg;
			}
		} else {
			$output = $fallbackimg;
		}


		$leftbox .= $output;
		$leftbox .= '</div>';
	} else {
		$sizeclass = 'ym-column';
	}
	?>
	<div <?php post_class($sizeclass); ?> id="post-<?php the_ID(); ?>" >

		<?php if ($titleup == 1) { ?>
			<div class="post-title ym-cbox"><h2>
					<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute('echo=0'); ?>">
						<?php the_title(); ?>
					</a>
				</h2></div>
			<div class="ym-column">
				<?php
			}
			/*
			 * 1 = Thumbnail (or: first picture, first video, fallback picture),
			 * 2 = First picture (or: thumbnail, first video, fallback picture),
			 * 3 = First video (or: thumbnail, first picture, fallback picture),
			 * 4 = First video (or: first picture, thumbnail, fallback picture),
			 * 5 = Nothing */

			if ($showdatebox < 5) {
				echo '<div class="post-info ym-col1"><div class="ym-cbox">';

				echo $leftbox;

				echo '</div></div>';
				echo '<div class="post-entry ym-col3">';
				echo '<div class="ym-cbox';
				if ($usefloating == 0) {
					echo ' ym-clearfix';
				}
				echo '">';
			} else {
				echo '<div class="post-entry ym-cbox">';
			}
			if ($titleup == 0) {
				?>
				<div class="post-title"><h2>
						<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute('echo=0'); ?>">
							<?php the_title(); ?>
						</a>
					</h2></div>
			<?php
			}

			if ($showdateline == 1) {
				?>
				<p class="pubdateinfo"><?php tf2013_post_pubdateinfo(0); ?></p>
			<?php
			}

			echo get_tf2013_custom_excerpt($teaserlength);
			?>
		<?php if ($showdatebox < 5) { ?>
			</div>
			<div class="ym-ie-clearing">&nbsp;</div>
	<?php } ?>
	</div>

	<?php
	if ($titleup == 1) {
		echo '</div>';
	}
	echo '</div>';
}

endif;





if (!function_exists('tf2013_post_pubdateinfo')) :

	/**
	 * Fusszeile unter Artikeln: Ver&ouml;ffentlichungsdatum
	 */
	function tf2013_post_pubdateinfo($withtext = 1) {
		if ($withtext == 1) {
			echo '<span class="meta-prep">';
			echo __('Ver&ouml;ffentlicht am', 'tf2013');
			echo '</span> ';
		}
		printf('%1$s', sprintf('<span class="entry-date">%1$s</span>', get_the_date()
				)
		);
	}

endif;


if (!function_exists('tf2013_post_autorinfo')) :

	/**
	 * Fusszeile unter Artikeln: Autorinfo
	 */
	function tf2013_post_autorinfo() {
		printf(__(' <span class="meta-prep-author">von</span> %1$s ', 'tf2013'), sprintf('<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span> ', get_author_posts_url(get_the_author_meta('ID')), sprintf(esc_attr__('Artikel von %s', 'tf2013'), get_the_author()), get_the_author()
				)
		);
	}

endif;


if (!function_exists('tf2013_post_taxonominfo')) :

	/**
	 * Fusszeile unter Artikeln: Taxonomie
	 */
	function tf2013_post_taxonominfo() {
		$tag_list = get_the_tag_list('', ', ');
		if ($tag_list) {
			$posted_in = __('<br />Kategorien:  %1$s | Schlagworte: %2$s<br/>Hier der <a href="%3$s" title="Permalink zu %4$s" rel="bookmark">permanente Link</a> zu diesem Artikel.', 'tf2013');
		} elseif (is_object_in_taxonomy(get_post_type(), 'category')) {
			$posted_in = __('<br />Kategorien:  %1$s<br /><a href="%3$s" title="Permalink zu %4$s" rel="bookmark">Permanenter Link</a> zu diesem Artikel.', 'tf2013');
		} else {
			$posted_in = __('<a href="%3$s" title="Permalink zu %4$s" rel="bookmark">Permanenter Link/<a> zu diesem Artikel.', 'tf2013');
		}
		// Prints the string, replacing the placeholders.
		printf(
				$posted_in, get_the_category_list(', '), $tag_list, get_permalink(), the_title_attribute('echo=0')
		);
	}

endif;


// this function initializes the iframe elements
// maybe wont work on multisite installations. please use plugins instead.
function tf2013_change_mce_options($initArray) {
	$ext = 'iframe[align|longdesc|name|width|height|frameborder|scrolling|marginheight|marginwidth|src]';
	if (isset($initArray['extended_valid_elements'])) {
		$initArray['extended_valid_elements'] .= ',' . $ext;
	} else {
		$initArray['extended_valid_elements'] = $ext;
	}
	// maybe; set tiny paramter verify_html
	$initArray['verify_html'] = false;
	return $initArray;
}

add_filter('tiny_mce_before_init', 'tf2013_change_mce_options');

if (!function_exists('get_tf2013_firstpicture')) :
	/*
	 * Erstes Bild aus einem Artikel auslesen, wenn dies vorhanden ist
	 */

	function get_tf2013_firstpicture() {
		global $post;
		$first_img = '';
		ob_start();
		ob_end_clean();
		$matches = array();
		preg_match('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
		if ((is_array($matches)) && (isset($matches[1]))) {
			$first_img = $matches[1];
			if (!empty($first_img)) {
				$site_link = home_url();
				$first_img = preg_replace("%$site_link%i", '', $first_img);
				$imagehtml = '<img src="' . $first_img . '" alt="" >';
				return $imagehtml;
			}
		}
	}

endif;


if (!function_exists('get_tf2013_firstvideo')) :
	/*
	 * Erstes Bild aus einem Artikel auslesen, wenn dies vorhanden ist
	 */

	function get_tf2013_firstvideo($width = 300, $height = 169, $nocookie = 1, $searchplain = 1) {
		global $post;
		ob_start();
		ob_end_clean();
		$matches = array();
		preg_match('/src="([^\'"]*www\.youtube[^\'"]+)/i', $post->post_content, $matches);
		if ((is_array($matches)) && (isset($matches[1]))) {
			$entry = $matches[1];
			if (!empty($entry)) {
				if ($nocookie == 1) {
					$entry = preg_replace('/youtube.com\/watch\?v=/', 'youtube-nocookie.com/embed/', $entry);
				}
				$htmlout = '<iframe width="' . $width . '" height="' . $height . '" src="' . $entry . '" allowfullscreen></iframe>';
				return $htmlout;
			}
		}
		// Schau noch nach YouTube-URLs die Plain im text sind. Hilfreich fuer
		// Installationen auf Multisite ohne iFrame-Unterstützung
		if ($searchplain == 1) {
			preg_match('/\b(https?:\/\/www\.youtube[\/a-z0-9\.\-\?=]+)/i', $post->post_content, $matches);
			if ((is_array($matches)) && (isset($matches[1]))) {
				$entry = $matches[1];
				if (!empty($entry)) {
					if ($nocookie == 1) {
						$entry = preg_replace('/youtube.com\/watch\?v=/', 'youtube-nocookie.com/embed/', $entry);
					}
					$htmlout = '<iframe width="' . $width . '" height="' . $height . '" src="' . $entry . '" allowfullscreen></iframe>';
					return $htmlout;
				}
			}
		}
		return;
	}

endif;

if (!function_exists('get_tf2013_custom_excerpt')) :
	/*
	 * Erstellen des Extracts
	 */

	function get_tf2013_custom_excerpt($length = 0, $continuenextline = 1, $removeyoutube = 1) {
		global $options;
		global $post;

		if (has_excerpt()) {
			return get_the_excerpt();
		} else {
			$excerpt = get_the_content();
			if (!isset($excerpt)) {
				$excerpt = __('Kein Inhalt', 'tf2013');
			}
		}
		if ($length == 0) {
			$length = $options['teaser_maxlength'];
		}
		if ($removeyoutube == 1) {
			$excerpt = preg_replace('/^\s*([^\'"]*www\.youtube[\/a-z0-9\.\-\?=]+)/i', '', $excerpt);
			// preg_match('/^\s*([^\'"]*www\.youtube[\/a-z0-9\.\-\?=]+)/i', $excerpt, $matches);
		}

		$excerpt = strip_shortcodes($excerpt);
		$excerpt = strip_tags($excerpt);
		if (mb_strlen($excerpt) < 5) {
			$excerpt = __('Kein Inhalt', 'tf2013');
		}

		if (mb_strlen($excerpt) > $length) {
			$the_str = mb_substr($excerpt, 0, $length);
			$the_str .= "...";
		} else {
			$the_str = $excerpt;
		}
		$the_str = '<p>' . $the_str;
		if ($continuenextline == 1) {
			$the_str .= '<br>';
		}
		$the_str .= tf2013_continue_reading_link();
		$the_str .= '</p>';
		return $the_str;
	}

endif;


if (!function_exists('get_tf2013_buttons')) :

	/**
	 * Displays Buttons
	 */
	function get_tf2013_buttons() {
		global $options;
		if (isset($options['aktiv-buttons']) && ($options['aktiv-buttons'] == 1)) {
			if (isset($options['aktiv-button1']) && ($options['aktiv-button1'] == 1) && isset($options['url-button1'])) {
				echo '<a href="' . $options['url-button1'] . '" class="button breit ' . $options['color-button1'] . '">' . $options['title-button1'] . '</a>';
				echo "\n";
			}

			if (isset($options['aktiv-button2']) && ($options['aktiv-button2'] == 1) && isset($options['url-button2'])) {
				echo '<a href="' . $options['url-button2'] . '" class="button breit ' . $options['color-button2'] . '">' . $options['title-button2'] . '</a>';
				echo "\n";
			}
		}
	}

endif;

if (!function_exists('get_tf2013_socialmediaicons')) :

	/**
	 * Displays Social Media Icons
	 */
	function get_tf2013_socialmediaicons() {
		global $options;
		global $default_socialmedia_liste;
		$zeigeoption = $options['aktiv-socialmediabuttons'];

		if ($zeigeoption != 1) {
			return;
		}
		$result = '';
		$links = '';
		$result .= '<div class="socialmedia_iconbar">';
		$result .= '<ul class="socialmedia">';
		foreach ($default_socialmedia_liste as $entry => $listdata) {
			$value = '';
			$active = 0;
			if (isset($options['sm-list'][$entry]['content'])) {
				$value = $options['sm-list'][$entry]['content'];
			} else {
				$value = $default_socialmedia_liste[$entry]['content'];
			}
			if (isset($options['sm-list'][$entry]['active'])) {
				$active = $options['sm-list'][$entry]['active'];
			}
			if (($active == 1) && ($value)) {
				$links .= '<li><a class="icon_' . $entry . '" href="' . $value . '">';
				$links .= $listdata['name'] . '</a></li>';
				$links .= "\n";
			}
		}

		if (strlen($links) > 1) {
			$result .= $links;
			$result .= '</ul>';
			$result .= '</div>';
			echo $result;
		} else {
			return;
		}
	}

endif;


if (!function_exists('short_title')) :
	/*
	 * Erstellen des Kurztitels
	 */

	function short_title($after = '...', $length = 6, $textlen = 10) {
		$thistitle = get_the_title();
		$mytitle = explode(' ', get_the_title());
		if ((count($mytitle) > $length) || (mb_strlen($thistitle) > $textlen)) {
			while (((count($mytitle) > $length) || (mb_strlen($thistitle) > $textlen)) && (count($mytitle) > 1)) {
				array_pop($mytitle);
				$thistitle = implode(" ", $mytitle);
			}
			$morewords = 1;
		} else {
			$morewords = 0;
		}
		if (mb_strlen($thistitle) > $textlen) {
			$thistitle = mb_substr($thistitle, 0, $textlen);
			$morewords = 1;
		}
		if ($morewords == 1) {
			$thistitle .= $after;
		}
		return $thistitle;
	}

endif;


/* Interne Links relativ ausgeben */

add_action('template_redirect', 'rw_relative_urls');

function rw_relative_urls() {
	// Don't do anything if:
	// - In feed
	// - In sitemap by WordPress SEO plugin
	if (is_feed() || get_query_var('sitemap'))
		return;
	$filters = array(
		'post_link',
		'post_type_link',
		'page_link',
		'attachment_link',
		'get_shortlink',
		'post_type_archive_link',
		'get_pagenum_link',
		'get_comments_pagenum_link',
		'term_link',
		'search_link',
		'day_link',
		'month_link',
		'year_link',
	);
	foreach ($filters as $filter) {
		add_filter($filter, 'wp_make_link_relative');
	}
}
