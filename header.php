<?php
global $options;
global $defaultoptions;
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]> <html <?php language_attributes(); ?> class="ie6"> <![endif]-->
<!--[if IE 7 ]>    <html <?php language_attributes(); ?> class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <html <?php language_attributes(); ?> class="ie8"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->
    <head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width" />
	<title><?php wp_title('|', true, 'right'); ?></title>
	<?php wp_head(); ?>
    </head>

    <body <?php body_class(); ?>>  <!-- begin: body -->
	<div class="page_margins">  <!-- begin: page_margins -->
	    <div id="seite" class="page">  <!-- begin: seite -->
		<a name="seitenmarke" id="seitenmarke"></a>

		<header>
		    <div id="kopf">
<!-- begin: kopf -->
			<div id="logo">

			    <?php if (!is_home()) { ?>
    				<a href="<?php echo home_url('/'); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home" class="logo">
				    <?php }?>
		
						
						

			<div id="logo_left">
			<img border="0" src="<?php echo get_template_directory_uri();?>/img/logo/MVB_Logo.png" alt="Logo">				
				</div>
			<div id="logo_right">
			<img border="0" src="<?php echo get_template_directory_uri();?>/img/logo/MVB_Wappen.png" alt="Logo">				
				</div>
					<div class="site-name" style="color:#<?php echo esc_attr(get_header_textcolor()); ?>">

						<?php bloginfo('name'); ?>
						<span class="description"><?php echo html_entity_decode(get_bloginfo('description')); ?></span>
					</div>



				<?php if (!is_home()) { ?>
    				</a>
<?php } ?>

			</div>


<?php
		/*	 echo '<div id="breadcrumb">';
			 echo '<h2>'._e('Sie befinden sich hier:', 'tf2013').'</h2>';
			 echo '<p>';
 			 if (function_exists('tf2013_breadcrumbs')) tf2013_breadcrumbs(); 
			 echo '</p></div>';
		*/
?>


			<div id="hauptmenu" class="zielgruppen-menue" role="navigation">
			    <?php if (has_nav_menu('targetmenu')) { ?>
					<h2 class="skip"><a id="hauptmenumarke" name="hauptmenumarke"></a>Zielgruppennavigation</h2>
					<?php
					wp_nav_menu(array('theme_location' => 'targetmenu', 'fallback_cb' => '', 'depth' => 1));
				 }?>
			</div><!-- #target-navigation -->
		    </div>
		</header>  <!-- end: kopf -->

		<hr id="nachkopf" />

		<div id="main">  <!-- begin: main -->

		    <div id="menu">  <!-- begin: menu -->
			<div id="bereichsmenu">
			    <h2><a name="bereichsmenumarke" id="bereichsmenumarke">Navigation</a></h2>
			    <?php
			    if (has_nav_menu('primary')) {
				wp_nav_menu(array('container' => 'ul', 'menu_class' => 'menu',
				    'menu_id' => 'navigation', 'theme_location' => 'primary', 'walker' => ''));
			    } else {
				?>
    			    <ul id="navigation" class="menu">
    <?php
    wp_page_menu(array(
	'sort_column' => 'menu_order, post_title',
	'echo' => 1,
	'show_home' => 1));
    ?>
    			    </ul>

<?php } ?>
			</div>

		    <?php
		    if (is_active_sidebar('kurzinfo-area')) {
				dynamic_sidebar('kurzinfo-area');
		    } ?>

		    </div>  <!-- end: menu -->


<?php get_sidebar(); ?>

		    <div id="content">  <!-- begin: content -->
			<a name="contentmarke" id="contentmarke"></a>
<?php
	if(!is_home())
	{
			echo '<div id="titel">';
			echo '<h1>';
			echo tf2013_contenttitle();
			echo '</h1></div>';
	}

?>
