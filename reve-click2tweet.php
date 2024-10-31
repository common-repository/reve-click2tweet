<?php
/*
Plugin Name:		Reve Click2Tweet
Version:			1.3.0
Description:		Add totally custom, responsive and fast Click to tweet boxes to your WordPress site.
Author:				Fernando Reve
Author URI:			http://www.promostudio.es
Text Domain:		reve-click2tweet
Domain Path:		languages/
License:			GPL2 
*/
if ( !defined('ABSPATH') ) exit;


// *** The global plugin config array:
$revec2t['dirname']			= 'reve-click2tweet'; // The same... dirname( plugin_basename(__FILE__) )
$revec2t['directory']		= plugin_dir_path(__FILE__);
$revec2t['directory_uri']	= plugin_dir_url(__FILE__);
$revec2t['is_admin']		= is_admin();
	

// *** The options funcionality and the global key options:
require_once( $revec2t['directory'].'inc/options.php' );
$revec2t['options'] = revec2t_get_options();


// *** The shortcode funcionality:
require_once( $revec2t['directory'].'inc/shortcode.php' );


// *** The admin funcionality:
if ( $revec2t['is_admin'] )
require_once( $revec2t['directory'].'inc/admin.php' );


/*
** Sets the text domain
** Used by plugins_loaded hook
*/ 
if ( !function_exists('revec2t_load_textdomain') ):
	
	add_action('plugins_loaded','revec2t_load_textdomain');

	function revec2t_load_textdomain() {
		
		global $revec2t;
		// Note that path must be relative:
		load_plugin_textdomain( 'reve-click2tweet', false, $revec2t['dirname'].'/languages' );
		
	} // :/function

endif;


/*
** Enqueues the frontend scripts & styles
** Used by wp_enqueue_scripts
*/
if ( !function_exists('revec2t_enqueue_scripts') ):
	
	add_action( 'wp_enqueue_scripts', 'revec2t_enqueue_scripts' );
	add_action( 'admin_enqueue_scripts', 'revec2t_enqueue_scripts' );

	function revec2t_enqueue_scripts() {
		
		global $revec2t;
		
		// Enqueues plugin css:
		wp_enqueue_style( 'revec2t', $revec2t['directory_uri'].'css/revec2t.css', NULL, '', 'all' );
		
		// Enqueues custom css inline styles (admin enqueues in page):
		if ( !$revec2t['is_admin'] && !empty($revec2t['options']['css']) )
			wp_add_inline_style( 'revec2t', $revec2t['options']['css'] );	
	
	} // :/function

endif;