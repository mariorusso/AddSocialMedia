<?php
/*
Plugin Name: Add Social Media Icons
Plugin URI: http://issd.ca/
Description: Add Social Media icons in the bottom of the post.
Version: 1.0
Author: Mario Russo
Author URI: http://russomario.com
*/

 $MY_PATH = plugin_dir_path(__FILE__);
 
 require($MY_PATH . 'classes/Add_Social_Media.php');
 
 new Add_Social_Media;

