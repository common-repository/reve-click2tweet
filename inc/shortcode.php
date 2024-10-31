<?php
/*
** inc/shortcode.php @ Reve Click2Tweet Plugin
** The shortcode functions
** Used by reve-click2tweet.php
*/
if ( !defined('ABSPATH') ) exit;


/*
** Registers the shortcode
** Used by init hook
*/
if ( !function_exists('revec2t_add_shortcode') ):
	
	add_action('init','revec2t_add_shortcode');
	
	function revec2t_add_shortcode() {
		
		add_shortcode('revec2t', 'revec2t_shortcode');	
	
	} // :/function

endif;


/*
** The shortcode callback function
** Used by revec2t_add_shortcode()
*/
if ( !function_exists('revec2t_shortcode') ):	
	
	function revec2t_shortcode($atts=array(), $content='', $tag='' ) {
	
		// Params for the render function:
		$params = revec2t_create_params($atts);	
				
		// Generates all the html code:
		$html = revec2t_render($params);
		return $html;
		
	} // :/function

endif;


/*
** Sets and validates all the params used by the render function
** Used by revec2t_shortcode()
*/
if ( !function_exists('revec2t_create_params') ):	
	
	function revec2t_create_params( $atts=array() ) {
		
		global $revec2t;
		$options = $revec2t['options'];
		$params = array();
		
		// The label (in shortcode or options, max=64):
		$params['label'] = isset($atts['label']) ? trim( strip_tags($atts['label']) ) : '';
		if ( $params['label']==='0' ):
			$params['label'] = '';
		elseif ( empty($params['label']) ):
			$params['label'] = isset($options['label']) ? $options['label'] : '';
		else:
			$params['label'] = mb_substr($params['label'],0,64);
		endif;
		
		// The icon option (boolean 0/1):
		$params['icon'] = isset( $atts['icon'] ) ? trim($atts['icon']) : '';
		if ( $params['icon']==='0' ):
			$params['icon'] = 0;
		elseif ( empty($params['icon']) ):
			$params['icon'] = empty($options['icon']) ? 0 : 1; 	
		else:
			$params['icon'] = 1;
		endif;
				
		// The skin (in shortcode or options, max=64):
		$params['skin'] = isset( $atts['skin'] ) ? trim( strip_tags($atts['skin']) ) : '';
		if ( $params['skin']==='0' ):
			$params['skin'] = '';
		elseif ( empty($params['skin']) ):
			$params['skin'] = isset($options['skin']) ? $options['skin'] : '';
		else:
			$params['skin'] = mb_substr($params['skin'],0,64);	
		endif;
		
		// The hashtags (in shortcode or options, max=64):
		$params['hashtags'] = isset( $atts['hashtags'] ) ? trim( strip_tags($atts['hashtags']) ) : '';
		if ( $params['hashtags']==='0' ):
			$params['hashtags'] = '';
		elseif ( empty($params['hashtags']) ):
			$params['hashtags'] = isset($options['hashtags']) ? $options['hashtags'] : '';
		else:
			$params['hashtags'] = mb_substr($params['hashtags'],0,64);	
		endif;
		
		// The via (in shortcode or options, max=15):
		$params['via'] = isset( $atts['via'] ) ? trim( strip_tags($atts['via']) ) : '';
		if ( $params['via']==='0' ):
			$params['via'] = '';
		elseif ( empty($params['via']) ):
			$params['via'] = isset($options['via']) ? $options['via'] : '';
		else:	
			$params['via'] = mb_substr($params['via'],0,15);
		endif;
		
		// The short url option (boolean 0/1):
		$params['short'] = isset( $atts['short'] ) ? trim($atts['short']) : '';
		if ( $params['short']==='0' ):
			$params['short'] = 0;
		elseif ( empty($params['short']) ):
			$params['short'] = empty($options['short']) ? 0 : 1; 	
		else:
			$params['short'] = 1;
		endif;
		
		// The URL to share (max 280):
		$params['url'] = empty($atts['url']) ? '' : esc_url($atts['url']);
		if ( empty($params['url']) ):
			$params['url'] = revec2t_get_url( $params['short'] );	
		else:
			$params['url'] = mb_substr($params['url'],0,280);	
		endif;
		
		// The text to submit and the text to show (tweet):
		$params['text'] = isset($atts['text']) ? trim( strip_tags($atts['text']) ) : '';
		if ( $params['text']==='0' ):
			$params['text'] = '';
			$params['tweet'] = '';	
		elseif ( empty($params['text']) || $params['text']==$params['url'] ):
			$params['text'] = '';
			$params['tweet'] = esc_url( $params['url'] );
			// Fixes bug with embeded content (Elementor):
			$params['tweet'] = str_replace(':','&#58;',$params['tweet']);
		else:
			$params['text'] = mb_substr($params['text'],0,280);
			$params['tweet'] = $params['text'];
		endif;
		
		// The Twitter share encoded url:
		$params['twitter_url'] = revec2t_twitter_url($params);
		
		return $params;
		
	} // :/function

endif;


/*
** Gets the current page url to share
** Used by revec2t_validate_params()
*/
if ( !function_exists('revec2t_get_url') ):	
	
	function revec2t_get_url($short) {
	
		if ( !empty($short) && is_singular() ):
			// Short urls only if option and possible:
			$url = wp_get_shortlink();
		else:
			// Use the global $wp request variable:
			global $wp;
			$request = $wp->request;
			$url = home_url($request);
		endif;

		if ( empty($url) ) $url = home_url();

		// Returns:
		return $url;

		// Testings...
		/*
		global $wp;
  		$url1 = home_url( add_query_arg(array(),$wp->request) );
		$url2 = get_permalink();
		$url3 = wp_get_shortlink();
		$url4 = get_home_url(NULL, $wp->request, NULL);
		echo '<p>1.- home_url: ' .$url1.'</p>';
		echo '<p>2.- get_permalink: ' .$url2.'</p>';
		echo '<p>3.- wp_get_shortlink: ' .$url3.'</p>';
		echo '<p>4.- get_home_url: ' .$url4.'</p>';
		*/
		
	} // :/function
	
endif;


/*
** Sets the Twitter API encoded url
** Used by revec2t_validate_params()
*/
if ( !function_exists('revec2t_twitter_url') ):	

	function revec2t_twitter_url( $params ) {
	
		$queries = array();
	
		if ( !empty($params['url']) ) 		$queries['url'] = $params['url'];
		if ( !empty($params['text']) ) 		$queries['text'] = $params['text'];
		if ( !empty($params['hashtags']) ) 	$queries['hashtags'] = $params['hashtags'];
		if ( !empty($params['via']) )		$queries['via'] = $params['via'];
		
		// Creates encoded query string and adds to the Twitter API base URL:
		$query = http_build_query($queries);
		$url = 'https://twitter.com/intent/tweet?'.$query;
		unset($queries);
		
		return $url;
		
	} // :/function
	
endif;


/*
** Returns all the html code
** Used by revec2t_shortcode()
*/
if ( !function_exists('revec2t_render') ):	
	
	function revec2t_render($params) {
	
		// If not visual elements:
		if ( !$params['tweet'] && !$params['icon'] && !$params['label'] ) return;
	
		global $revec2t;
		$n = "\n";
		$html = '';
				
		// Begin html: the full box container and the link
		$box_class = empty($params['skin']) ? '' : ' revec2t-'.$params['skin'];
		$box_class = 'revec2t-box'.$box_class;
		
		$html .= '<div class="'.$box_class.'">'.$n;
		$html .= '<a class="revec2t-link" target="_blank" href="' .$params['twitter_url']. '">'.$n;
			
			if ( !empty($params['tweet']) ):
				
				// The div content and the tweet text:
				$html .= '<div class="revec2t-content">'.$n;
				$html .= '<p class="revec2t-tweet">'.$params['tweet'].'</p>'.$n;
				$html .= '</div>'.$n;	
			
			endif;
		
			if ( !empty($params['label']) || !empty($params['icon']) ):
				
				// The bar container and the button:
				$html .= '<div class="revec2t-bar">'.$n;
				$html .= '<span class="revec2t-button">'.$n;	
				
				if ( !empty($params['icon']) ):
					$html .= '<span class="revec2t-icon">&nbsp;</span>'.$n;
				endif;
				
				if ( !empty($params['label']) ):
					$html .= '<span class="revec2t-label">'.$params['label'].'</span>'.$n;
				endif;
				
				// Close button and bar:
				$html .= '</span>'.$n;
				$html .= '</div>'.$n;	
			
			endif; // :/if bar/button
			
		// Close html, box and link:
		$html .= '</a>'.$n;
		$html .= '</div>'.$n;
		
		return $html;
		
	} // :/function

endif;