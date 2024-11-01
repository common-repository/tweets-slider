<?php
/*
  Plugin Name: Tweets Slider
  Plugin URI: http://www.mrwebsolution.in/
  Description: "Tweets Slider" is a plugin to add your twitter tweets as slider on your website. This plugin provide an option to publish your latest tweets on your site.
  Author:MR WEB SOLUTION
  Author URI: http://www.mrwebsolution.in/
  License URI: license.txt
  Version: 1.3
 */
/*  Copyright 2018-19  tweets-slider  (email : raghunath.0087@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/** Register and create an option page */
add_action('admin_menu','ts_admin_menu_init');
if(!function_exists('ts_admin_menu_init')):
function ts_admin_menu_init(){
add_options_page( 'Tweets Slider', 'Tweets Slider', 'manage_options', 'tweets-slider-settings', 'ts_slider_admin_option_page_func' ); 

}
endif;
/** Define Action for register "Gallery" Options */
add_action('admin_init','ts_register_settings_init');
if(!function_exists('ts_register_settings_init')):
function ts_register_settings_init(){
 register_setting('ts_setting_options','ts_active');
 register_setting('ts_setting_options','ts_speed'); 
 register_setting('ts_setting_options','ts_noslider'); 
 register_setting('ts_setting_options','ts_twwets_num'); 
 register_setting('ts_setting_options','ts_controlbar');
 register_setting('ts_setting_options','ts_twitteruser');
 register_setting('ts_setting_options','ts_limit');
 register_setting('ts_setting_options','ts_consumerkey');
 register_setting('ts_setting_options','ts_consumersecret');
 register_setting('ts_setting_options','ts_accesstoken');
 register_setting('ts_setting_options','ts_accesstokensecret');
} 
endif;
// Add settings link to plugin list page in admin
if(!function_exists('ts_add_plugin_settings_link')):
function ts_add_plugin_settings_link( $links ) {
            $settings_link = '<a href="options-general.php?page=tweets-slider-settings">' . __( 'Settings', 'ts' ) . '</a>';
            array_unshift( $links, $settings_link );
            return $links;
  }
endif;
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'ts_add_plugin_settings_link' );
/* 
*Display the Options form for Responsive Gallery
*/
function ts_slider_admin_option_page_func(){ ?>
	<div style="width: 80%; padding: 10px; margin: 10px;"> 
	<h1>Tweets Slider Settings</h1>
<!-- Start Options Form -->
	<form action="options.php" method="post" id="ts-sidebar-admin-form">
	<div id="ts-tab-menu"><a id="ts-general" class="ts-tab-links active" >General</a> <a id="ts-shortcodes" class="ts-tab-links" >Shortcodes</a> <a  id="ts-support" class="ts-tab-links">Support</a> </div>
	<div class="ts-setting">
	<!-- General Setting -->	
	<div class="first ts-tab" id="div-ts-general">
	<h2>General Settings</h2>
	<p> <input type="checkbox" id="ts_active" name="ts_active" value='1' <?php checked(get_option('ts_active'),'1');?>/><label><?php _e('Enable:');?></label></p>
	<p><label><?php _e('Define Speed:');?></label><input type="text" id="ts_speed" name="ts_speed" size="10" value="<?php echo get_option('ts_speed'); ?>" placeholder="2000"> </p>	
	<p><label><?php _e('Number of tweets:');?></label><input type="text" id="ts_twwets_num" name="ts_twwets_num" size="10" value="<?php echo get_option('ts_twwets_num'); ?>" placeholder="10"> </p>
	<hr>
	<h2>Twitter Settings</h2>
	<i><a href="https://apps.twitter.com/" target="_blank">Create a new APP OR get exist APP access details</a></i>
	<p><label><?php _e('Username:');?></label><input type="text" id="ts_twitteruser" name="ts_twitteruser" size="40" value="<?php echo get_option('ts_twitteruser'); ?>" placeholder="define your twitter username"> </p>
	<p><label><?php _e('Consumer key:');?></label><input type="text" id="ts_consumerkey" name="ts_consumerkey" size="40" value="<?php echo get_option('ts_consumerkey'); ?>" placeholder="define your consumer key"> </p>
	<p><label><?php _e('Consumer Secret:');?></label><input type="text" id="ts_consumersecret" name="ts_consumersecret" size="40" value="<?php echo get_option('ts_consumersecret'); ?>" placeholder="enter your consumer secret"> </p>
	<p><label><?php _e('Access Token:');?></label><input type="text" id="ts_accesstoken" name="ts_accesstoken" size="40" value="<?php echo get_option('ts_accesstoken'); ?>" placeholder="define your access token"> </p>
	<p><label><?php _e('Access Token Secret:');?></label><input type="text" id="ts_accesstokensecret" name="ts_accesstokensecret" size="40" value="<?php echo get_option('ts_accesstokensecret'); ?>" placeholder="define your access token secret"> </p>
	<hr>
	<p><input type="checkbox" id="ts_noslider" name="ts_noslider" value="1" <?php checked(get_option('ts_noslider'),1);?>> <label><?php _e('Disable Slider');?></label></p>
	
	
	</div>
	<!-- Shortcodes -->
	<div class="author ts-tab" id="div-ts-shortcodes">
	<h2>Shortcodes</h2>
	<p><b>[tweets_slider]</b>:<br> You can add tweets gallery using this shortcode on any page.<br><br>You can add shortcode into your templates files , just need to add given below code into your template files and update category slug <pre>if(function_exists('ts_slider_func')){<br> echo do_shortcode('[tweets_slider]');<br>} <br> </pre></p>
	</div>
	<!-- Support -->
	<div class="last author ts-tab" id="div-ts-support">
	<h2>Plugin Support</h2>
	<table>
	<tr>
	<td width="50%"><p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZEMSYQUZRUK6A" target="_blank" style="font-size: 17px; font-weight: bold;"><img src="<?php echo  plugins_url( 'images/btn_donate_LG.gif' , __FILE__ );?>" title="Donate for this plugin"></a></p>
	<p><strong>Plugin Author:</strong><br><a href="mailto:raghunath.0087@gmail.com" target="_blank" class="contact-author">Contact Author</a></p></td>
	<td><p><strong>Our Other Plugins:</strong><br>
					  <ol>
					<li><a href="https://wordpress.org/plugins/custom-share-buttons-with-floating-sidebar" target="_blank">Custom Share Buttons With Floating Sidebar</a></li>
					<li><a href="https://wordpress.org/plugins/seo-manager/" target="_blank">SEO Manager</a></li>
							<li><a href="https://wordpress.org/plugins/protect-wp-admin/" target="_blank">Protect WP-Admin</a></li>
							<li><a href="https://wordpress.org/plugins/wp-sales-notifier/" target="_blank">WP Sales Notifier</a></li>
							<li><a href="https://wordpress.org/plugins/wp-tracking-manager/" target="_blank">WP Tracking Manager</a></li>
							<li><a href="https://wordpress.org/plugins/wp-categories-widget/" target="_blank">WP Categories Widget</a></li>
							<li><a href="https://wordpress.org/plugins/wp-protect-content/" target="_blank">WP Protect Content</a></li>
							<li><a href="https://wordpress.org/plugins/wp-version-remover/" target="_blank">WP Version Remover</a></li>
							<li><a href="https://wordpress.org/plugins/wp-posts-widget/" target="_blank">WP Post Widget</a></li>
							<li><a href="https://wordpress.org/plugins/wp-importer" target="_blank">WP Importer</a></li>
							<li><a href="https://wordpress.org/plugins/wp-csv-importer/" target="_blank">WP CSV Importer</a></li>
							<li><a href="https://wordpress.org/plugins/wp-testimonial/" target="_blank">WP Testimonial</a></li>
							<li><a href="https://wordpress.org/plugins/wc-sales-count-manager/" target="_blank">WooCommerce Sales Count Manager</a></li>
							<li><a href="https://wordpress.org/plugins/wp-social-buttons/" target="_blank">WP Social Buttons</a></li>
							<li><a href="https://wordpress.org/plugins/wp-youtube-gallery/" target="_blank">WP Youtube Gallery</a></li>
							<li><a href="https://wordpress.org/plugins/tweets-slider/" target="_blank">Tweets Slider</a></li>
							<li><a href="https://wordpress.org/plugins/rg-responsive-gallery/" target="_blank">RG Responsive Slider</a></li>
							<li><a href="https://wordpress.org/plugins/cf7-advance-security" target="_blank">Contact Form 7 Advance Security WP-Admin</a></li>
							<li><a href="https://wordpress.org/plugins/wp-easy-recipe/" target="_blank">WP Easy Recipe</a></li>
					</ol>
	</td>
	</tr>
	</table>
	</div>
	</div>
    <span class="submit-btn"><?php echo get_submit_button('Save Settings','button-primary','submit','','');?></span>
    <?php settings_fields('ts_setting_options'); ?>
	</form>
<!-- End Options Form -->
	</div>
<?php
}
/** add js into admin footer */
if(isset($_GET['page']) && $_GET['page']=='tweets-slider-settings'):
add_action('admin_footer','init_ts_admin_scripts');
endif;
if(!function_exists('init_ts_admin_scripts')):
function init_ts_admin_scripts()
{
wp_register_style( 'ts_admin_style', plugins_url( 'css/ts-admin.css',__FILE__ ) );
wp_enqueue_style( 'ts_admin_style' );
echo $script='<script type="text/javascript">
	/* Responsive Gallery js for admin */
	jQuery(document).ready(function(){
		jQuery(".ts-tab").hide();
		jQuery("#div-ts-general").show();
	    jQuery(".ts-tab-links").click(function(){
		var divid=jQuery(this).attr("id");
		jQuery(".ts-tab-links").removeClass("active");
		jQuery(".ts-tab").hide();
		jQuery("#"+divid).addClass("active");
		jQuery("#div-"+divid).fadeIn();
		});
		
		jQuery("#ts-sidebar-admin-form").submit(function(){
		 var $el = jQuery("#ts_active");
		 var $vlue1,$vlue2,$vlue3,$vlue4,$vlue5;
		 $vlue2 = jQuery("#ts_consumerkey").val();
		 $vlue3 = jQuery("#ts_consumersecret").val();
		 $vlue4 = jQuery("#ts_accesstoken").val();
		 $vlue5 = jQuery("#ts_accesstokensecret").val();
		 $vlue1 = jQuery("#ts_twitteruser").val();
		 if(($el[0].checked) && ($vlue5=="" || $vlue1=="" || $vlue2=="" || $vlue3=="" || $vlue4==""))
		 {
			 alert("You will need to enter all access details of twitter");
			 jQuery("#ts_active").attr("checked", false);
			 return false;
			 }
		 });
		})
	</script>';

	}	
endif;	

/** include file */
require dirname(__FILE__).'/ts-class.php';

/** register_activation_hook */
/** Delete exits options during activation the plugins */
if( function_exists('register_activation_hook') ){
   register_activation_hook(__FILE__,'init_activation_ts_plugins');   
}
//Disable free version after activate the plugin
if(!function_exists('init_activation_ts_plugins')):
function init_activation_ts_plugins(){
//
}
endif;
/* 
*Delete an options during disable the plugins 
*/
if( function_exists('register_uninstall_hook') )
register_uninstall_hook(__FILE__,'ts_plugin_uninstall');   
//Delete all Custom Tweets options after delete the plugin from admin
if(!function_exists('ts_plugin_uninstall')):
function ts_plugin_uninstall(){
delete_option('ts_active');
}
endif;
/** register_deactivation_hook */
/** Delete exits options during deactivation the plugins */
if( function_exists('register_deactivation_hook') ){
   register_deactivation_hook(__FILE__,'init_deactivation_ts_plugins');   
}
//Delete all options after uninstall the plugin
if(!function_exists('init_deactivation_ts_plugins')):
function init_deactivation_ts_plugins(){
delete_option('ts_active');
}
endif;
