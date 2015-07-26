<?php
    $querystring = esc_attr(apply_filters('the_search_query', get_search_query()));
    $searchstring = __('Suchbegriff eingeben', 'tf2013');
    if (empty($querystring)) { $querystring = $searchstring; }
?>

        <div id="suche">
          <h2><a name="suche"><?php _e('Suche', 'tf2013'); ?></a></h2>
            <form method="get" id="searchform" action="<?php echo esc_url( home_url() ); ?>">
               <p>
	               <label for="suchbegriff"><?php _e('Suche', 'tf2013'); ?>:</label>
	               <input id="suchbegriff" name="s" type="text" value="<?php echo $querystring; ?>"
				   onfocus="if(this.value=='<?php echo $querystring; ?>')this.value='';"
				   onblur="if(this.value=='')this.value='<?php echo $querystring; ?>';"
				   maxlength="100" />
	               <input type="submit" value="<?php _e('Suchen', 'tf2013'); ?>" />
               </p>
            </form>
        </div>