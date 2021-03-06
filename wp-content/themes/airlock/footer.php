<?php
	global $apollo13;
?>
			<div class="cleared"></div>
		</div><!-- #mid -->
		<?php 
		$request_page = str_replace(home_url( '/' ), '', $_SERVER['REQUEST_URI']);
		if ($request_page == '/' || $request_page == '/index.php'){?>
		<div class="layer_fix J_layer_div" style="display: block;">
		    <div style="display: block;" class="layer_con">
		        <div style="margin:27px 10px 8px 0;" class="layer_text">
		              <div style="height: 59px;">
		              
		              </div>
		        </div>
		         
		        <div class="layer_btn_div">
			        <div class="layer_big">
			            <input type="button" class="layer_pinterest" value="pinterest" />
			        </div>
			        <div class="layer_big">
			            <input type="button" class="layer_facebook" value="facebook" />
			        </div>
			        <div class="layer_big">
			            <input type="button" class="layer_twitter" value="twitter" />
			        </div>
			        <div class="layer_big">
			            <input type="button" class="layer_google" value="google" />
			        </div>
		        </div>
		      	<div class="layer_par"></div>
		        <div class="clear"></div>
		    </div>
		    <div class="layer_close" data-bn-ipg="rl-close">
		        <a onfocus="this.blur();" href="javascript:void(0)" id="J_layer_close">Close</a>
		    </div>
	    </div>
		<?php }?>
        <?php
        	$hp_foot = $apollo13->get_option( 'design_options', 'hp_footer_switch' );
        	$normal_foot = $apollo13->get_option( 'settings', 'theme_footer_switcher' );
			if( ( $hp_foot == 'off' && defined( 'ORG_IS_FRONT_PAGE' ) && ORG_IS_FRONT_PAGE ) 
				|| 
				( $normal_foot == 'off' && !defined( 'ORG_IS_FRONT_PAGE' ) ) 
			):
			//no footer
			else:
        ?>     
		<div id="footer">
			<div id="footer-nav">
				<!-- <a href="#" id="top-link"><span><?php _e( 'Top of Page', TPL_SLUG ); ?></span></a> -->
				<a href="#" id="top-link"><span><?php _e( 'Haut de Page', TPL_SLUG ); ?></span></a>
				<?php 
					if ( has_nav_menu( 'header-menu' ) ): 
				/* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu.  The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.  */
						wp_nav_menu( array( 
							'container' => false,
							'link_before'     => '<span>',
							'link_after'      => '</span>',
						 	'theme_location'  => 'header-menu',
							'before'          => '<span class="sep">/</span>',
							'depth'           => 1 ) 
						); 
					else: 
						wp_nav_menu( array( 
							'container' => false,
							'link_before' => '<span>',
							'link_after'  => '</span>',
							'before'      => '<span class="sep">/</span>',
							'depth'       => 1  ) 
						);
					endif;
				 ?>
				<div class="cleared"></div>
			</div>
			<div id="footer-pad">
				<div class="align-left"><?php echo $apollo13->get_option( 'footer_options', 'footer_copyright' ) ?></div>
				<div class="align-right"><?php echo $apollo13->get_option( 'footer_options', 'footer_bottom' ) ?></div>
				<div class="cleared"></div>
			</div>
		</div>
		<?php endif; ?>
	</div><!-- #root -->
	
	<?php echo $apollo13->get_option( 'settings', 'ga_code' ) ?>
<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>	
</body>
</html>