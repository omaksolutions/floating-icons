<?php
/*
Plugin Name:	Floating Icons
Plugin URI:		https://www.slickpopup.com/free-wordpress-floating-icons-plugin
Description:	Floating Icons enhances the user experience during special ocassions or at times when you are offering some sales or discount. It grabs user attention by greeting the floating icons which are moving across the screen.
Author URI:		https://www.omaksolutions.com 
Author:			Om Ak Solutions 
Version:		1.0.0
Text Domain:	floating-icons
*/

define( 'OMFE_VERSION', '1.0.0' );
define( 'OMFE_REQUIRED_WP_VERSION', '3.0.1' );
define( 'OMFE_PLUGIN', __FILE__ ); 
define( 'OMFE_PLUGIN_BASENAME', plugin_basename( OMFE_PLUGIN ) );
define( 'OMFE_PLUGIN_NAME', trim( dirname( OMFE_PLUGIN_BASENAME ), '/' ) );
define( 'OMFE_PLUGIN_DIR', untrailingslashit( dirname( OMFE_PLUGIN ) ) );
require_once(OMFE_PLUGIN_DIR . '/adminside/main.php');


if ( !class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/adminside/admin/ReduxCore/framework.php' ) ) {
	require_once( OMFE_PLUGIN_DIR . '/adminside/admin/ReduxCore/framework.php' );
}

if ( !isset( $redux_demo ) && file_exists( dirname( __FILE__ ) . '/adminside/admin/admin-init.php' ) ) {
	require_once( OMFE_PLUGIN_DIR . '/adminside/admin/admin-init.php' );
}

add_action('wp_enqueue_scripts', 'omfe_add_scripts');
function omfe_add_scripts() {
	wp_enqueue_style('font-awesome', omfe_plugin_url( 'libs/css/font-awesome.min.css', dirname(__FILE__)));
	wp_enqueue_script('custom', omfe_plugin_url( 'libs/js/custom.js', dirname(__FILE__)));
}

add_action('admin_enqueue_scripts', 'omfe_admin_scripts');
function omfe_admin_scripts() {
	wp_enqueue_script('custom', omfe_plugin_url( 'libs/js/admin-ajax.js', dirname(__FILE__)));
}

/**
 * Set Plugin URL Path (SSL/non-SSL)
 * @param  string - $path
 * @return string - $url 
 * Return https or non-https URL from path
 */
function omfe_plugin_url( $path = '' ) {
	$url = plugins_url( $path, OMFE_PLUGIN );
	if ( is_ssl() && 'http:' == substr( $url, 0, 5 ) ) {
		$url = 'https:' . substr( $url, 5 );
	}
	return $url;
} 

/*//////////////////////////////////
// Uninstall Hook
////////////////////////////////////*/
register_uninstall_hook(__FILE__, 'omfe_on_uninstall'); 
function omfe_on_uninstall(){
	delete_option('omfe_opts');
	delete_option('omfe_install_date');
	delete_option('omfe_review_notice');
}

/////////////////////////////////////
// Activation Hook
/////////////////////////////////////
register_activation_hook(__FILE__, 'omfe_on_activate'); 
function omfe_on_activate(){
	update_option('omfe_install_date', current_time('Y-m-d H:i:s')); 
	set_transient( 'omfe_activated', 1 );
}


/////////////////////////////////////
// De-Activation Hook
/////////////////////////////////////
register_deactivation_hook(__FILE__, 'omfe_on_deactivate'); 
function omfe_on_deactivate(){
	update_option('omfe_install_date', current_time('Y-m-d H:i:s')); 
	set_transient( 'omfe_activated', 1 );
}

add_action( 'admin_notices', 'omfe_admin_notices' );
function omfe_admin_notices() {
	
	// Get omfe_install_date from options
	$install_date = get_option('omfe_install_date') ? get_option('omfe_install_date') : current_time('Y-m-d H:i:s'); 
	
	$review_notice = get_option('omfe_review_notice');
	// review_notice - numeric counter for multiplying 14 days
	// -1 means never, 0 means ask-later
	$review_notice =  (isset($review_notice) AND !empty($review_notice)) ? $review_notice : 1; 	
	
	$install_date_object = DateTime::createFromFormat('Y-m-d H:i:s', $install_date);
	$today = DateTime::createFromFormat('U', current_time('U')); 
	$diff = $today->diff($install_date_object); 
	$diff->d = $diff->m * 30 + $diff->d;
	
	// Proceed if user chosen "Ask Never" last time
	if($review_notice!=-1) {
		// Show only if user chosen "Ask Later" 14 days ago
		if($diff->d >= 14*$review_notice) {
			echo '<div class="notice notice-success">
				<h2 style="margin:0.5em 0;">Hope you are enjoying - <span style="color:#0073aa;">Floating Icons</span></h2>
				<p>
				'.__( 'Thanks for using one of the best WordPress Plugin. We hope that it has been useful for you and would like you to leave review on WordPres.org website, it will help us improve the product features.', 'floating-icons' ).'
				<br><br>
				<a class="button-primary" href="https://wordpress.org/support/plugin/floating-icons/reviews/">Leave a Review</a>
				&nbsp;<a class="button-link omfe-dismissable is-dismissible" data-btn="ask-later" href="#">Ask Later</a> |
				<a class="button-link omfe-dismissable" data-btn="ask-never" href="#">Never Show Again</a></p>
			</div>';		
		}
	}
}


/**
 * Show a notice to anyone who has just updated this plugin
 * This notice shouldn't display to anyone who has just installed the plugin for the first time
 */
function omfe_display_update_notice() {
	// Check the transient to see if we've just updated the plugin
	if( get_transient( 'omfe_updated' ) ) {
		echo '<div class="notice notice-success is-dismissible">
			<h2 style="margin:0.5em 0;">Thanks for updating - <span style="color:#0073aa;">Floating Icons</span></h2>
			<p>
			'.__( 'Floating Icons enhances the user experience during special ocassions or at times when you are offering some sales or discount. It grabs user attention by greeting the floating icons which are moving across the screen.', 'floating-icons' ).'</p>
			
			<p>
			'.__( 'Perfect for festive effects, sales and discount season, for crazy-bloggers or anyone who is willing to show some effects to greet the user.', 'floating-icons' ).'
			
			<span style="display: block; margin: 0.5em 0.5em 0 0; clear: both; font-weight: bold;">
				<a href="'.admin_url('/admin.php?page=floating_icons').'">'.__( "Go to Settings Page", "floating-icons" ).'</a>
				 | <a target="_blank" href="https://www.slickpopup.com/free-wordpress-floating-icons-plugin">'.__( "View Demo", "floating-icons" ).'</a>
			</span>					
			</p>
		</div>';
		
		// Save omfe_install_date for already existing users (before: 1.5.3)
		if(!get_option('omfe_install_date'))
			update_option('omfe_install_date', current_time('Y-m-d H:i:s')); 			
		
		delete_transient( 'omfe_updated' );
	}
}
add_action( 'admin_notices', 'omfe_display_update_notice' );

/**
 * Show a notice to anyone who has just installed the plugin for the first time
 * This notice shouldn't display to anyone who has just updated this plugin
 */
function omfe_display_install_notice() {
	// Check the transient to see if we've just activated the plugin
	if( get_transient( 'omfe_activated' ) ) {
		
		echo '<div class="notice notice-success is-dismissible">
			<h2 style="margin:0.5em 0;">Thanks for installing - <span style="color:#0073aa;">Floating Icons</span></h2>
			<p>
			'.__( 'Floating Icons enhances the user experience during special ocassions or at times when you are offering some sales or discount. It grabs user attention by greeting the floating icons which are moving across the screen.', 'floating-icons' ).'</p>
			
			<p>
			'.__( 'Perfect for festive effects, sales and discount season, for crazy-bloggers or anyone who is willing to show some effects to greet the user.', 'floating-icons' ).'
			
			<span style="display: block; margin: 0.5em 0.5em 0 0; clear: both; font-weight: bold;">
				<a href="'.admin_url('/admin.php?page=floating_icons').'">'.__( "Go to Settings Page", "floating-icons" ).'</a>
				 | <a target="_blank" href="https://www.slickpopup.com/free-wordpress-floating-icons-plugin">'.__( "View Demo", "floating-icons" ).'</a>
			</span>			
			</p>
		</div>';
		
		// Delete the transient so we don't keep displaying the activation message
		delete_transient( 'omfe_activated' );
	}
}
add_action( 'admin_notices', 'omfe_display_install_notice' );

add_filter( 'plugin_action_links_' . OMFE_PLUGIN_BASENAME, 'omafe_add_action_links' );
function omafe_add_action_links($links) {
	$mylinks = array(
		'<a href="' . admin_url( 'admin.php?page=floating_icons' ) . '">Settings</a>',
	);
	return array_merge( $links, $mylinks );
}



/*
* omfe_notice_dismissable
* Ajax action to do tasks on notice dismissable
* Require class: sp-dismissable
*/
add_action( 'wp_ajax_omfe_notice_dismissable', 'omfe_notice_dismissable' );
function omfe_notice_dismissable() {
	
	$data_btn = isset($_POST['dataBtn']) ? $_POST['dataBtn'] : '';
	
	if(empty($data_btn)) return; 
	
	$today = DateTime::createFromFormat('U', current_time('U')); 
	
	switch($data_btn) {
		case 'ask-later': 
			$ask_later = get_option('omfe_review_notice') ? get_option('omfe_review_notice') : 0; 
			$updated = update_option('omfe_review_notice', ++$ask_later); 
			break; 
		case 'ask-never': 
			$updated = update_option('omfe_review_notice', -1); 
			break; 
	}
	
	$ajaxy = ($updated) ? 'Updated' : 'Not updated'; 
	wp_send_json_success($ajaxy); 
	wp_die(); 
}

?>