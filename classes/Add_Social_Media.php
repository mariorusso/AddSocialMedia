<?php
class Add_Social_Media{
	
	public function __construct() {
		
		$this->SOCIAL_MEDIA_PATH = plugin_dir_path(__FILE__) . '../';
			
		//Actions 
		add_filter('the_content', [$this, 'add_social_icons']);
		
		add_action('admin_menu', [$this, 'register_addsocialicons_options']);
		
		add_action('admin_menu', [$this, 'create_addsocialicons_admin_page']);
		
		add_action('admin_menu', [$this, 'add_social_icons_sections']);
		
		add_action('admin_menu', [$this, 'add_social_icons_field']);
		
		add_action('wp_head', [$this, 'social_css']);
		
		add_action('wp_footer', [$this, 'facescript']);
		
		add_action('wp_footer', [$this, 'twitscript']);
	}
	
	//Add Social Icons function
	function add_social_icons($content){
		
		//make $post global
		global $post;
		
		//Set options to the DB field social_options
		$options = get_option('socialicons_options');
		
		//check if the title field is true, else set to false.
		if($options['social_title']){
		  	$title = $options['social_title'];
		}else{
			$title = false;
		}
		
		//check if the twitter field is true and assign the code to it, else set to false.	
		if($options['twitter_code']){
		  	$twitter = "<a href='https://twitter.com/share' class='twitter-share-button'>Tweet</a>";
		}else{
			$twitter = false;
		}
		
		//check if the facebook field is true and assign the code to it, else set to false.	
		if($options['facebook_code']){
			$facebook = "<div class='fb-like' data-href='https://developers.facebook.com/docs/plugins/' data-layout='standard' data-action='like' data-show-faces='false' data-share='true'></div>";
		}else{
			$facebook = false;
		}	
		
		//Define the $social variable with the social icons content.	
		$social = "<div>
						<h3>$title</h3>
						<div class='twitter-div'>$twitter</div>
				    	<div class='facebook-div'>$facebook</div>
				    </div>";
		
		//Check if the post type is post. else show only the content.
		if($post->post_type == 'post'){
			return $content . $social;		
		}else{
			return $content;
		}
				
	}
	
	//CSS function to the socialicons.
	function social_css() {
		$css = "<style>
					div.twitter-div{
						width: 90px;
						float: left;
					}
				</style>";
		echo $css;
	}
	
	//Twitter script function.
	function twitscript(){
		$twit_script = "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
		echo $twit_script;
	}
	
	//Facebook script function.
	function facescript(){
		
		$face_script = "<div id='fb-root'></div>
		<script>(function(d, s, id) {
	  	var js, fjs = d.getElementsByTagName(s)[0];
	  	if (d.getElementById(id)) return;
	  	js = d.createElement(s); js.id = id;
	  	js.src = '//connect.facebook.net/en/sdk.js#xfbml=1&version=v2.0';
	  	fjs.parentNode.insertBefore(js, fjs);
		}	(document, 'script', 'facebook-jssdk'));</script>";
		
		echo $face_script;
	}
	
	
	// Register Plugin Settings 
	function register_addsocialicons_options(){
		register_setting(
			'socialiconsadmin', //Option Group
			'socialicons_options', //Name of the field in db
			[$this, 'sanatize_socialicons'] //Callback function to sanatize the input
		);
	}
	
	// Callback function to sanatize input
	function sanatize_socialicons($input){
		return $input;
	}
	
	//Add options page
	function create_addsocialicons_admin_page(){
		add_menu_page('ADD Social Icons Plugin', //Page Title
					  'ADD Social Icons', //Menu Title
					  'manage_options', //Capabilities required 
					  'addsocialicons', //slug/page 
					  [$this, 'add_social_icons_options'] //callback to output menu
						  );
	}
	
	//Add basic content to menu page 
	function add_social_icons_options(){
		echo "<div class='wrap'>";
		echo "<h2>ADD Social Icons Settings</h2>";
		
		echo'<form method="post" action="options.php">';
		
		settings_fields('socialiconsadmin'); //option group
		do_settings_sections('addsocialicons'); //page slug
		
		submit_button();
		
		echo '</form><!--End of form-->';
		echo "</div><!--End of wrap div-->";
		
	}
	
	//Add sections
	function add_social_icons_sections(){
		add_settings_section(
			'general_section', //Id of the section - UNIQUE in the plugin.
			'General Options', // Section Title - Appear in the page.
			'', //callback function - output Instructions to the user. 
			'addsocialicons' //slug of menu page that should appear. 
		);
	}
	
	//Add Settings Field 
	function add_social_icons_field(){
		
		//Add title field
		add_settings_field(
			'social_title', //id of the field
			'Title', //Title - Label of the field 
			[$this, 'create_title_field'], //callback function to output the field.
			'addsocialicons', //slug of the menu page
			'general_section' //Section that the field should be in. 
		);
		
		//Add twitter field
		add_settings_field(
			'twitter_code', //id of the field
			'Twitter', //Title - Label of the field 
			[$this, 'create_twitter_field'], //callback function to output the field.
			'addsocialicons', //slug of the menu page
			'general_section' //Section that the field should be in. 
		);
		
		//Add facebook field
		add_settings_field(
			'facebook_code', //id of the field
			'Facebook', //Title - Label of the field 
			[$this, 'create_facebook_field'], //callback function to output the field.
			'addsocialicons', //slug of the menu page
			'general_section' //Section that the field should be in. 
		);
	}
	
	
	// output title field
	function create_title_field() {
			
			$settings = get_option('socialicons_options');
			if(isset($settings['social_title'])){
				$value = $settings['social_title'];
			}else {
				$value = '';
			}
			
			$data['value'] = $value;
			$this->load_view('title_view' , $data); 
		}
	
	// output twitter field
	function create_twitter_field() {
		
		
		$options = get_option('socialicons_options');
		if($options['twitter_code']){
			$checked = 'checked';
		}else{
			$checked = '';
		}	
		$data['checked'] = $checked;
		$this->load_view('twitter_view' , $data);
	
		}
	
	// output facebook field
	function create_facebook_field() {
		
		$options = get_option('socialicons_options');
		
		if($options['facebook_code']){
			$checked = 'checked';
		}else{
			$checked = '';
		}	
		
		$data['checked'] = $checked;
		$this->load_view('facebook_view' , $data);
		
	}
	
	private function load_view($view, $data = []){
		extract($data);	
		$SOCIAL_MEDIA_PATH = plugin_dir_path(__FILE__) . '../view/';
		require ($SOCIAL_MEDIA_PATH . $view . '.php' );
		
	}
}	
