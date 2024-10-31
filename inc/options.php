<?php
/* 
** inc/options.php @ Reve Click2Tweet Plugin
** Manages the plugin options
*/
if ( !defined('ABSPATH') ) exit;


/*
** Gets the active plugin saved options
** Used by reve-click2tweet.php & admin-page.php
*/
if ( !function_exists('revec2t_get_options') ):
	
	function revec2t_get_options() {
		
		$json_options = get_option('revec2t_options');
		$options = json_decode($json_options, true);
				
		// Ensures keys integrity:
		$all_options = revec2t_get_all_options();
		foreach ( $all_options as $key=>$value ):
			
			if ( !isset( $options[$key] ) ):
				// Sets default and calls update:
				$options[$key] = $all_options[$key][0];
				$update_options = true;	
			else:
				$options[$key] = trim( $options[$key] );
			endif;
				
		endforeach;
	
		if ( !empty( $update_options ) ) revec2t_update_options($options);
	
		unset ($json_options,$all_options);
		return $options;
		
	} // :/function
		
endif;


/*
** Updates the plugin options
** Used by revec2t_get_options() & admin-page.php
*/
if ( !function_exists('revec2t_update_options') ):
	
	function revec2t_update_options($options) {
		
		// Note that options must be validated		
		$json_options = json_encode($options);
		update_option('revec2t_options', $json_options, false );
		unset( $json_options );
	
	} // :/function	

endif;


/*
** Gets all the plugin options and defaults
** Used by revec2t_get_options() & admin-page.php
*/
if ( !function_exists('revec2t_get_all_options') ):
	
	function revec2t_get_all_options() {
		
		$all_options = array(
			
			// Label default options (string):
			'label'			=> array( __('Click to tweet','reve-click2tweet') ),
			// Icon default options (boolean):
			'icon'			=> array( 1, 0,1 ),
			// Skin default options (string):
			'skin'			=> array( 'modern-1', '','classic-1','classic-2','modern-1','modern-2'),
			// The custom css option:
			'css'			=> array( '' ),
			// Hashtags default options (string):
			'hashtags'		=> array( '' ),
			// Via default options (string):
			'via'			=> array( '' ),
			// Short url default options (boolean):
			'short'			=> array(1, 0,1),
			// Editor button default options (boolean):
			'editor' 		=> array(1, 0,1),
			);
		
		return $all_options;
	
	} // :/function	

endif; 


/*
** The options validation function
** Used by admin-page.php
*/
if ( !function_exists('revec2t_validate_options') ):

	function revec2t_validate_options(&$options, $all_options=NULL) {
				
		if ( empty($all_options) ) $all_options = revec2t_get_all_options();
				
		// Label validation:
		if ( isset($all_options['label']) ):
			$options['label'] = empty($options['label']) ? '' : trim( strip_tags($options['label']) );
			if ( mb_strlen($options['label']) > 64 ) return 'label string too long';	
		endif;
		
		// Icon validation:
		if ( isset($all_options['icon']) ):
			$options['icon'] = empty( $options['icon'] ) ? 0 : 1;	
		endif;
				
		// Skin validation:
		if ( isset($all_options['skin']) ):
			$options['skin'] = empty($options['skin']) ? '' : trim($options['skin']);
			if ( !in_array($options['skin'],$all_options['skin']) ) return 'invalid skin string';
		endif;
		
		// CSS validation:
		if ( isset($all_options['css']) ):
			$options['css'] = empty($options['css']) ? '' : trim($options['css']);
			if ( mb_strlen($options['css']) > 1024 ) return 'css string too long';
		endif;
		
		// Hashtags validation:
		if ( isset($all_options['hashtags']) ):
			$options['hashtags'] = empty($options['hashtags']) ? '' : trim( strip_tags($options['hashtags']) );
			if ( mb_strlen($options['hashtags']) > 64 ) return 'hashtags string too long';
		endif;
		
		// Via validation:
		if ( isset($all_options['via']) ):
			$options['via'] = empty($options['via']) ? '' : trim( strip_tags($options['via']) );
			if ( mb_strlen($options['via']) > 15 ) return 'via string too long';
		endif;
			
		// Short url validation:
		if ( isset($all_options['short']) ):
			$options['short'] = empty( $options['short'] ) ? 0 : 1;	
		endif;
		
		// Editor button validation:
		if ( isset($all_options['editor']) ):
			$options['editor'] = empty( $options['editor'] ) ? 0 : 1;
		endif;
			
		// Return empty (all ok):
		return false;
	
	} // :/function

endif;


/*
** Loads all options labels
** Used by admin-page.php
*/
if ( !function_exists('revec2t_options_labels') ):
	
	function revec2t_options_labels($all_options=NULL) {
			
		if ( empty($all_options) ) $all_options = revec2t_get_all_options();
		
		// Auxiliar strings:
		$none 		= __('(None)', 'reve-click2tweet');
		$active		= __('Enabled', 'reve-click2tweet');
		$inactive	= __('Disabled', 'reve-click2tweet');
		$n 			= "\n";
		
		if ( isset($all_options['label']) ):
			$labels['label']['title'] = __('Default call to action label', 'reve-click2tweet');
			foreach ( $all_options['label'] as $index=>$value ):
				$labels['label'][$index] = empty($value) ? $none : $value;
			endforeach;		
		endif;
				
		if ( isset($all_options['icon']) ):
			$labels['icon']['title'] = __('Show Twitter icon', 'reve-click2tweet');
			foreach ( $all_options['icon'] as $index=>$value ):
				$labels['icon'][$index] = empty($value) ? $inactive: $active;
			endforeach;	
		endif;
		
		if ( isset($all_options['skin']) ):
			$labels['skin']['title'] = __('Skin style', 'reve-click2tweet');
			foreach ( $all_options['skin'] as $index=>$value ):
				$labels['skin'][$index] = empty($value) ? $none : $value;
			endforeach;
		endif;
		
		if ( isset($all_options['css']) ):
			$labels['css']['title'] = __('Custom CSS', 'reve-click2tweet');
			foreach ( $all_options['css'] as $index=>$value ):
				$labels['css'][$index] = empty($value) ? $none : $value;
			endforeach;
		endif;
		
		if ( isset($all_options['hashtags']) ):
			$labels['hashtags']['title'] = __('Default Twitter hashtags', 'reve-click2tweet');
			foreach ( $all_options['hashtags'] as $index=>$value ):
				$labels['hashtags'][$index] = empty($value) ? $none : $value;
			endforeach;		
		endif;
				
		if ( isset($all_options['via']) ):
			$labels['via']['title'] = __('Default Twitter via user', 'reve-click2tweet');
			foreach ( $all_options['via'] as $index=>$value ):
				$labels['via'][$index] = empty($value) ? $none : $value;
			endforeach;		
		endif;
		
		if ( isset($all_options['short']) ):
			$labels['short']['title'] = __('WordPress short URLs', 'reve-click2tweet');
			foreach ( $all_options['short'] as $index=>$value ):
				$labels['short'][$index] = empty($value) ? $inactive : $active;
			endforeach;	
		endif;
				
		if ( isset($all_options['editor']) ):
			$labels['editor']['title'] = __('Editor button', 'reve-click2tweet');
			foreach ( $all_options['editor'] as $index=>$value ):
				$labels['editor'][$index] = empty($value) ? $inactive : $active;
			endforeach;	
		endif;
			
		return $labels;
	
	} // :/function
	
endif;