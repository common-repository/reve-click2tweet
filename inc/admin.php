<?php
/*
** inc/admin.php @ Reve Click2Tweet Plugin
** The admin page functions
*/
if ( !defined('ABSPATH') ) exit;


// The global plugin admin vars:
$revec2t['menu_title'] 		= __('Reve Click2Tweet','reve-click2tweet');
$revec2t['page_title']		= __('Reve Click2Tweet Plugin','reve-click2tweet');
$revec2t['page_slug']		= 'reve-click2tweet';
$revec2t['capability']		= 'manage_options';


/*
** Registers the admin menu and page
** Used by admin_menu hook
*/
if ( !function_exists('revec2t_admin_menu') ):

	add_action('admin_menu','revec2t_admin_menu');

	function revec2t_admin_menu() {	
		
		global $revec2t;
		
		add_submenu_page(
			'options-general.php',
			$revec2t['page_title'],
			$revec2t['menu_title'],
			$revec2t['capability'],
			$revec2t['page_slug'],
			'revec2t_admin_load_page'
			);
	
	} // :/function	

endif;


/*
** Loads the admin menu page
** Used by revec2t_admin_menu()
*/
if ( !function_exists('revec2t_admin_load_page') ):
	
	function revec2t_admin_load_page() {
		
		global $revec2t;
		require_once( $revec2t['directory'].'inc/admin-page.php' );
	
	} // :/function

endif; 


/*
** Registers the revec2t mce plugin, if option
** Used by mce_external_plugins hook
*/
if ( !empty($revec2t['options']['editor']) && !function_exists('revec2t_admin_mce_plugin') ):

	add_filter('mce_external_plugins', 'revec2t_admin_mce_plugin');
	
	function revec2t_admin_mce_plugin($external_plugins) {
		
		global $revec2t;
		$external_plugins['revec2t_mce_button'] =  $revec2t['directory_uri'].'js/revec2t-mce-button.js';
		return $external_plugins;
	
	} // :/function()

endif;


/*
** Registers the revec2t mce button, if option
** Used by mce_buttons hook
*/
if ( !empty($revec2t['options']['editor']) && !function_exists('revec2t_admin_mce_button') ):

	add_filter('mce_buttons', 'revec2t_admin_mce_button');

	function revec2t_admin_mce_button($mce_buttons) {
	
		array_push($mce_buttons, 'revec2t_button');
		return $mce_buttons;
	
	} // :/function()

endif;