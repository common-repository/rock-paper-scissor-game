<?php
/*
Plugin Name: RPS Game
Plugin URI: http://www.farbundstil.de/games/1036-wordpress-game-plugin.php
Description: Put a simple rock paper scissor game on your site
Version: 1.1
Author: Marcel Hollerbach
Author URI: http://www.farbundstil.de

Instructions

Requires at least Wordpress: 2.1.3

1. Upload the rockpaperscissor folder to your wordpress plugins directory (./wp-content/plugins)
2. Login to the Wordpress admin panel and activate the plugin "RPS Game"
3. Create a new post or page and enter the tag [RPS]

That's it ... Have fun!

Changelog: Added no follow at link - Improved pictures

*/

define("RPS_REGEXP", "/\[RPS]/");

// Pre-2.6 compatibility
if ( !defined('WP_CONTENT_URL') )
	define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');

define('RPS_URLPATH', WP_CONTENT_URL.'/plugins/'.plugin_basename( dirname(__FILE__)).'/' );

add_action('wp_head', 'RPS_addcss', 1);

//Add stylesheet to site
function RPS_addcss(){

    echo "<link rel=\"stylesheet\" href=\"". RPS_URLPATH. "rps_style.css\"  type=\"text/css\" media=\"screen\" />";

}
	
function RPS_plugin_callback($match)
{
    
    $left_hands[0] = "<img src=\"". RPS_URLPATH. "images/paper_left.jpg\">";
    $left_hands[1] = "<img src=\"". RPS_URLPATH. "images/rock_left.jpg\">";
    $left_hands[2] = "<img src=\"". RPS_URLPATH. "images/scissor_left.jpg\">";
    
    $right_hands[0] = "<img src=\"". RPS_URLPATH. "images/paper_right.jpg\">";
    $right_hands[1] = "<img src=\"". RPS_URLPATH. "images/rock_right.jpg\">";
    $right_hands[2] = "<img src=\"". RPS_URLPATH. "images/scissor_right.jpg\">";
    
    if($_REQUEST['rps'] == "paper") $user_selection = 0;
    if($_REQUEST['rps'] == "rock") $user_selection = 1;
    if($_REQUEST['rps'] == "scissor") $user_selection = 2;
    
    $logic[0] = "paper";
    $logic[1] = "rock";
    $logic[2] = "scissor";
    $random_select = rand(0,2);
    
    if($logic[$random_select] == $_REQUEST['rps']){
        $game_message = "Draw";
        $game_status = "won";
    }
    if($logic[$random_select] == "paper" && $_REQUEST['rps'] == "scissor"){
        $game_message = "Scissor beats paper - You win!";
        $game_status = "won";
    }
    if($logic[$random_select] == "scissor" && $_REQUEST['rps'] == "paper"){
        $game_message = "Scissor beats paper - You lost!";
        $game_status = "lost";
    }
    if($logic[$random_select] == "rock" && $_REQUEST['rps'] == "paper"){
        $game_message = "Paper beats rock - You win!";
        $game_status = "won";
    }
    if($logic[$random_select] == "paper" && $_REQUEST['rps'] == "rock"){
        $game_message = "Paper beats rock - You lost!";
        $game_status = "lost";
    }
    if($logic[$random_select] == "rock" && $_REQUEST['rps'] == "scissor"){
        $game_message = "Rock beats scissor - You lost!";
        $game_status = "lost";
    }
    if($logic[$random_select] == "scissor" && $_REQUEST['rps'] == "rock"){
        $game_message = "Rock beats scissor - You win!";
        $game_status = "won";
    }
    
    if($game_status == "won") $info_box = "info_column_won";
    if($game_status == "lost") $info_box = "info_column_lost";
    if(!$game_status){
        $info_box = "info_column_neutral";
        $game_message = "Please select rock, scissor or paper to start";
    }
    
    
	//$output = "Hallo Welt" .$_REQUEST['rps'];
	$click_link = get_permalink();
	
	$output .= "<div id=\"rps\">
	               <div class=\"". $info_box ."\">
	                   " . $game_message . "
	               </div>
	               <div class=\"left_column\">
	                   " . $left_hands[$user_selection] . "
	               </div>
	               <div class=\"right_column\">
	                   " . $right_hands[$random_select] . "
	               </div>
	               <div class=\"select_column\">
	                   Please choose:<br />
	                   <a href=\"" . $click_link . "?rps=rock\" rel=\"nofollow\">Rock</a> 
	                   -
	                   <a href=\"" . $click_link . "?rps=scissor\" rel=\"nofollow\">Scissor</a> 
	                    - 
	                   <a href=\"" . $click_link . "?rps=paper\" rel=\"nofollow\">Paper</a> 
	               </div>
    	       </div>";

	return ($output);
}

function RPS_plugin($content)
{
	return (preg_replace_callback(RPS_REGEXP, 'RPS_plugin_callback', $content));
}

add_filter('the_content', 'RPS_plugin');
add_filter('comment_text', 'RPS_plugin');



?>
