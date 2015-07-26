<?php
/*
 * Footer
 */
?>
			<hr id="vorfooter" />
	      	</div>  <!-- end: content -->
     	</div>  <!-- end: main -->
		<footer><div id="footer">  <!-- begin: footer -->
			<div id="footerinfos">  <!-- begin: footerinfos -->
				<?php if(is_blogs_fau_de()) { ?>
				<nav role="navigation">
                        <div id="tecmenu">   <!-- begin: tecmenu -->
		        	<h2 class="skip"><a name="hilfemarke" id="hilfemarke">Technisches Menu</a></h2>
					<div class="menu-tecmenue-container">
						<ul id="menu-tecmenue" class="menu">
						<li><a href="http://blogs.fau.de/">Blogs@FAU</a></li>
						<li><a href="http://www.portal.uni-erlangen.de/forums/viewforum/94">Forum</a></li>
						<li><a href="http://blogs.fau.de/hilfe/">Hilfe</a></li>
						<li><a href="<?php bloginfo('url'); ?>/kontakt/">Kontakt</a></li>
						<li><a href="http://blogs.fau.de/impressum/">Impressum</a></li>
						<li><a href="http://blogs.fau.de/nutzungsbedingungen/">Nutzungsbedingungen</a></li>
						</ul>
					</div>
	        	</div>  <!-- end: tecmenu -->
               </nav>

	       <?php } elseif ( has_nav_menu( 'tecmenu' ) ) { ?>
	       <nav role="navigation">
			<div id="tecmenu">   <!-- begin: tecmenu -->
		        	<h2 class="skip"><a name="hilfemarke" id="hilfemarke">Technisches Menu</a></h2>
					<?php wp_nav_menu( array( 'theme_location' => 'tecmenu', 'fallback_cb' => '' ) );?>
	        	</div>  <!-- end: tecmenu -->
		</nav>
	       <?php } ?>
              <div id="zusatzinfo" class="noprint">  <!-- begin: zusatzinfo -->
		<a id="zusatzinfomarke" name="zusatzinfomarke"></a>
		    <?php if ( is_active_sidebar( 'zusatzinfo-area' ) ) {
			    dynamic_sidebar( 'zusatzinfo-area' );
			 } ?>


		<p class="skip"><a href="#seitenmarke">Zum Seitenanfang</a></p>
		</div>  <!-- end: zusatzinfo -->




           </div> <!-- end: footerinfos -->
        </div></footer>   <!-- end: footer -->

    </div>  <!-- end: seite -->
  </div>  <!-- end: page_margins  -->
    <?php wp_footer(); ?>
    </body> <!-- end: body -->
</html>