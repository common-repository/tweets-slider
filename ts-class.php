<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
 * Function for get all options
 * @WP_Query()
 * 
 * */
if(!function_exists('get_ts_admin_options')){ 
	function get_ts_admin_options() {
		global $wpdb;
		$tsOptions = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE 'ts_%'");
								
		foreach ($tsOptions as $option) {
			$tsOptions[$option->option_name] =  $option->option_value;
		}
	
		return $tsOptions;	
	}
}
/* Get Plugin Options */	
$pluginOptions=get_ts_admin_options();	
/*Is plugin active*/
$isEnable='';
if(isset($pluginOptions['ts_active']) && $pluginOptions['ts_active']!=''){$isEnable=$pluginOptions['ts_active'];}
if($isEnable){
/** shortcode */
add_shortcode( 'tweets_slider', 'ts_slider_func' );
}
if(!function_exists('ts_add_link_on_url_func')):
function ts_add_link_on_url_func($content) {   
    return preg_replace('![^\'"=](((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', ' <a href="$1" target="_blank">$1</a> ', $content);
} 
endif;

if(!function_exists('getConnectionWithAccessToken')):
function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
$connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
return $connection;
}
endif;

if(!function_exists('ts_slider_func')):
function ts_slider_func( $attr ) {
	
require_once("lib/twitteroauth.php"); //Path to twitteroauth library
$tweetsNum = get_option('ts_twwets_num');
$twitteruser = get_option('ts_twitteruser');
$notweets = $tweetsNum ? $tweetsNum : 10;
$consumerkey = get_option('ts_consumerkey');
$consumersecret = get_option('ts_consumersecret');
$accesstoken = get_option('ts_accesstoken');
$accesstokensecret = get_option('ts_accesstokensecret');
$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
$tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$twitteruser."&count=".$notweets);

if(!isset($tweets->errors))
{
$disableslider =get_option('ts_noslider') ? get_option('ts_noslider') : 0;
	
if(!$disableslider){
$content='<div id="tweets-slider-sec"><div id="tweets-slider" class="tg-preloader">';
$i=0;
foreach($tweets as $tweet):
$updatedcontent=nl2br(ts_add_link_on_url_func($tweet->text));	
$content.='<div id="slide'.$i.'" class="ts-slide-item">
                <h2 class="ts-slide-un"> <a href="https://twitter.com/@'.$tweet->user->screen_name.'" target="_blank">@'.$tweet->user->screen_name.'</a></h2>
                <h3 class="ts-slide-date">'.date('F jS Y h:m:s',strtotime($tweet->created_at)).'</h3>
                <p class="ts-slide-text" id="afterheadline">'.$updatedcontent.'</p></div>';
$i++;
endforeach;
$content.='</div></div>';
}else{
/* End slider and start normal tweets item */
$content='<div id="tweets-items-sec">';
$content.='<h2 class="ts-slide-un"><a href="https://twitter.com/@'.$twitteruser.'" target="_blank"><img src="'. esc_url( plugins_url( 'images/twitter.png', __FILE__ ) ).'" /> @'.$twitteruser.'</a></h2>';
$i=0;
foreach($tweets as $tweet):
$updatedcontent=nl2br(ts_add_link_on_url_func($tweet->text));	
$content.='<div id="slide'.$i.'" class="tweets-items">
                <p class="ts-slide-text" id="afterheadline">'.$updatedcontent.'</p>
                 <h3 class="ts-slide-date">'.date('F jS Y h:m:s',strtotime($tweet->created_at)).'</h3>
                </div><hr>';
$i++;
endforeach;
$content.='</div>';

}

}else
{
	$erroMsg =$tweets->errors;
	$content= '<p align="center">'.$erroMsg[0]->message.'</p>';
	
}

return $content;
}
endif;
add_action('wp_footer','ts_add_inline_js'); // inline js
if(!function_exists('ts_add_inline_js')):
function ts_add_inline_js()
{
wp_register_style( 'ts_style', plugins_url( 'css/ts.css',__FILE__ ) );
wp_enqueue_style( 'ts_style' );
$ts_speed=get_option('ts_speed') ? get_option('ts_speed') : '500';
$disableslider =get_option('ts_noslider') ? get_option('ts_noslider') : 0;
if(!$disableslider){
echo "<script>jQuery(function() {
			jQuery('#tweets-slider > div:gt(0)').hide();
			setInterval(function() {
			  jQuery('#tweets-slider > div:first')
			    .fadeOut(2000)
			    .next()
			    .fadeIn(1500)
			    .end()
			    .appendTo('#tweets-slider');
			},  ".$ts_speed.");
		});
</script>";
}
}
endif;
