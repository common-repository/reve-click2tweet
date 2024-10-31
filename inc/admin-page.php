<?php
/*
** inc/admin-page.php @ Reve Click2Tweet Plugin
** Displays the admin page
** Used by inc/admin.php
*/
if ( !defined('ABSPATH') ) exit;

global $revec2t;

// *** The global active options and all options:
$options = $revec2t['options'];
$all_options = revec2t_get_all_options();

// *** Gets the options labels:
$labels = revec2t_options_labels($all_options);

// *** Checks the capability (if can edit or only read):
$capability = current_user_can( $revec2t['capability'] );

// *** Checks if submitted form:
$submitted = ( isset($_POST['submit']) && $_POST['submit']=='submit' );

// *** All the links array:
$links['admin_page']	= get_admin_url().get_admin_page_parent().'?page='.$revec2t['page_slug'];
$links['donate_page']	= 'http://www.promostudio.es/support-revec2t';
$links['plugin_page']	= 'https://wordpress.org/plugins/reve-click2tweet/';
$links['review_page']	= 'https://wordpress.org/support/plugin/reve-click2tweet/reviews/';

// *** Other auxiliar vars:
$text_default = __('Default value: ', 'reve-click2tweet');
$n = "\n";

// *** If not capability:
if ( $capability==false ):
	$notice['class']	= 'notice-error';
	$notice['text'] 	= __( 'You can only read this page. To manage the plugin options please contact the site administrator.','reve-click2tweet' );

// *** If submitted form:
elseif ( $submitted==true ):

	$submit_options = array();	
	foreach( $all_options as $key=>$values ):
		if ( isset( $_POST[$key] ) ):
			$submit_options[$key] = stripslashes( $_POST[$key] );
		else:
			// Restore default:
			$submit_options[$key] = $options[$key];
		endif;	
	endforeach;
	
	// echo '<p>submit_options: '; print_r($submit_options); echo '</p>';
	
	// The clean and validation function (submit_options passed by reference):
	$validate_error = revec2t_validate_options( $submit_options, $all_options );
	if ( empty( $validate_error) ):
		
		revec2t_update_options($submit_options);
		// Update globals:
		$revec2t['options'] = $options = $submit_options;
		unset($submit_options);		
		
		$notice['class']	= 'notice-success';
		$notice['text'] 	= __( 'Options succesfully saved.', 'reve-click2tweet');

	else:
		
		$notice['class']	= 'notice-error';
		$notice['text'] 	= __( 'Options NOT saved. Please check this error and try again: ','reve-click2tweet') .'<b>'.$validate_error.'</b>';

	endif; // :/validate true/false

// *** If capability and not submitted:	
else:
	// Default notice
	$notice['text'] 	= __( 'Are you happy with this plugin? So, %1splease make a donation%2s to contribute totally free plugins development.', 'reve-click2tweet');
	$notice['text']		= sprintf( $notice['text'], '<a target="_blank" href="'.$links['donate_page'].'">', '</a>' );
	// $notice['text']		= '<a target="_blank" href="'.$links['donate_page'].'">'.$notice['text'].'</a>'; 
	$notice['class']	= 'notice-info';

endif; 

?>
<div class="wrap">

	<h1 style="font-weight:bold;color:#1da1f3;"><?php echo $revec2t['page_title']; ?></h1>

	<div class="notice <?php echo $notice['class']; ?> notice is-dismissible"> 
		<p>
        	<b><?php echo $notice['text']; ?></b>
        </p>
		<button type="button" class="notice-dismiss">
    		<span class="screen-reader-text">[x]</span>
    	</button>
	</div><!-- /.notice -->

	<hr>
 
 	<?php
	// THE SHORTCODE PREVIEW
    echo '<h2>'. __('The [revec2t] shortcode preview', 'reve-click2tweet') .'</h2>';
	echo '<p>';
	echo __('Note that this is a preview into the administration page. The exact appearance in frontend depends on your WordPress theme.', 'reve-click2tweet');
	echo '</p>';
	// The shortcode html, url and text atts:
	$atts['text'] = 'Add totally custom, responsive and fast Click to tweet boxes to your WordPress site.';
	$atts['url'] = $links['plugin_page'];
	$atts['hashtags'] = 'wordpress,marketing,twitter';
	$preview = revec2t_shortcode($atts);
	?>
	<div class="postbox" style="padding:15px 30px;font-size:15px;">
    	<div>
		<?php
		// Adds the inline css:
		if ( !empty($options['css']) ):
			echo '<style type="text/css" scoped>'.$options['css'].'</style>';
    	endif;
		// Prints preview:
		echo $preview; 
		unset($atts,$preview);
		?>
        </div>
	</div>
	<hr>
    
	<form method="post">
    
    <?php
	// BEGIN SECTION: THE APPEARANCE OPTIONS
    echo '<h2>'. __('The appearance options', 'reve-click2tweet') .'</h2>';
   	// echo '<p>'. __('Set here the plugin CSS file, the icon and the label for the Reve Click2Tweet box. Or simply leave the default values.','reve-click2tweet') .'</p>';
    ?>
	<table class="form-table">
		<tbody>
    
    	<?php
        // *** BEGIN KEY ***
		$KEY = 'label';
		if ( !empty($all_options[$KEY]) && !empty($labels[$KEY]) ):
		?>
        <tr>           
			<th scope="row">
            	<label for="reve_<?php echo $KEY; ?>"><?php echo $labels[$KEY]['title']; ?></label>
            </th> 
            <td>
                <input type="text" name="<?php echo $KEY; ?>" id="reve_<?php echo $KEY; ?>" class="regular-text" maxlength="64" value="<?php echo $options[$KEY]; ?>">	
                <p class="description">
                	<?php
					$description  = __('Enter here the call to action label.', 'reve-click2tweet');
			 		$description .= ' '. __('Only text (not HTML) up to 64 characters.', 'reve-click2tweet');
					$description .= $n. __('If you want, you can modify this text in each shortcode with the label attribute.', 'reve-click2tweet');
					$description .= $n.$text_default.$labels[$KEY][0]; 
					echo nl2br($description);
					?>
                </p> 	
            </td>      
        </tr>
        <?php
		// END KEY:
		endif;
		?>
        
        <?php
        // *** BEGIN KEY ***
		$KEY = 'icon';
		if ( !empty($all_options[$KEY]) && !empty($labels[$KEY]) ):
		?>
        <tr>
            <th scope="row">
            	<label for="revec2t_<?php echo $KEY; ?>"><?php echo $labels[$KEY]['title']; ?></label>
            </th> 
            <td>
                <select name="<?php echo $KEY; ?>" id="revec2t_<?php echo $KEY; ?>" class="regular-text">
					<?php
                    foreach ( $all_options[$KEY] as $index=>$value ):
						if ( $index > 0 ):
							$selected = ( $options[$KEY]==$all_options[$KEY][$index] ) ? ' selected="selected"' : '';
							echo '<option value="' .$value. '"'.$selected.'>' .$labels[$KEY][$index]. '</option>';
						endif; 
                    endforeach;
                    ?>
                </select> 
                <p class="description">
                	<?php
                    $description 	= __('This option allows you to show or hide the Twitter icon.', 'reve-click2tweet');
					$description .= $n. __('Also you can change this option in each shortcode through the icon attribute.','reve-click2tweet');
					$description   .= $n.$text_default. $labels[$KEY][0];
					echo nl2br($description);
					?>
                </p> 	
            </td> 
        </tr>
        <?php
		// END KEY:
		endif;
		?>
               
		<?php
        // *** BEGIN KEY ***
		$KEY = 'skin';
		if ( !empty($all_options[$KEY]) && !empty($labels[$KEY]) ):
		?>
        <tr>
            <th scope="row">
            	<label for="revec2t_<?php echo $KEY; ?>"><?php echo $labels[$KEY]['title']; ?></label>
            </th> 
            <td>
                <select name="<?php echo $KEY; ?>" id="revec2t_<?php echo $KEY; ?>" class="regular-text">
					<?php
                    foreach ( $all_options[$KEY] as $index=>$value ):
						if ( $index > 0 ):
							$selected = ( $options[$KEY]==$all_options[$KEY][$index] ) ? ' selected="selected"' : '';
							echo '<option value="' .$value. '"'.$selected.'>' .$labels[$KEY][$index]. '</option>';
						endif; 
                    endforeach;
                    ?>
                </select> 
                <p class="description">
                	<?php 
					$description  = __('Reve Click2Tweet comes with various skins that modify the box appearance.', 'reve-click2tweet');
					$description .= $n. __('You can select a skin or none, if you want to add your own custom CSS.','reve-click2tweet');
					$description .= $n.$text_default. $labels[$KEY][0];
					echo nl2br($description);
					?>
                </p> 	
            </td> 
        </tr>
        <?php
		// END KEY:
		endif;
		?>    
        
        <?php
        // *** BEGIN KEY ***
		$KEY = 'css';
		// Only if implemented options and texts:
		if ( !empty( $all_options[$KEY] ) && !empty( $labels[$KEY] ) ):
		?>
        <tr>
            <th scope="row">
            	<label for="revec2t_<?php echo $KEY; ?>"><?php echo $labels[$KEY]['title']; ?></label>
            </th> 
            <td>
                <textarea name="<?php echo $KEY; ?>" id="revec2t_<?php echo $KEY; ?>" rows="6" maxlength="1024" class="regular-text"><?php echo $options[$KEY]; ?></textarea>
                <p class="description">
                	<?php 
					$description  = __('You can enter here your custom CSS styles, as in any CSS file.', 'reve-click2tweet');
					$description .= $n. __('Note that this will overwrite the plugin CSS defaults and skin styles.','reve-click2tweet');
					$description .= $n.$text_default. $labels[$KEY][0];
					echo nl2br($description);
					?>	
                </p> 	
            </td> 
        </tr>
        <?php
		// END KEY:
		endif;
		?>    
         
        
    	
        </tbody>
    </table><!-- /.form-table -->
    <hr>
    
    <?php
	// BEGIN SECTION: THE FUNCIONALITY OPTIONS
    echo '<h2>'. __('The funcionality options', 'reve-click2tweet') .'</h2>';
	// echo '<p>' . __('Set here the default hashtags and via values, and also if you want shorts URLs.', 'reve-click2tweet'). '</p>';
    ?>
	<table class="form-table">
		<tbody>
    	
        <?php
        // *** BEGIN KEY ***
		$KEY = 'hashtags';
		if ( !empty($all_options[$KEY]) && !empty($labels[$KEY]) ):
		?>
        <tr>           
			<th scope="row">
            	<label for="reve_<?php echo $KEY; ?>"><?php echo $labels[$KEY]['title']; ?></label>
            </th> 
            <td>
                <input type="text" name="<?php echo $KEY; ?>" id="reve_<?php echo $KEY; ?>" class="regular-text" maxlength="64" value="<?php echo $options[$KEY]; ?>">	
                <p class="description">
                	<?php 
					$description  = __('Enter the hashtags, without # and comma separated, to set default hashtags to all shortcodes.', 'reve-click2tweet');
			 		$description .= $n. __('You can customize this value in each shortcode with the hashtags attribute.', 'reve-click2tweet');
					$description .= $n.$text_default.$labels[$KEY][0]; 
					echo nl2br($description);
					?>
                </p> 	
            </td>     
        </tr>
        <?php
		// END KEY:
		endif;
		?>    
               
    	<?php
        // *** BEGIN KEY ***
		$KEY = 'via';
		if ( !empty($all_options[$KEY]) && !empty($labels[$KEY]) ):
		?>
        <tr>           
			<th scope="row">
            	<label for="reve_<?php echo $KEY; ?>"><?php echo $labels[$KEY]['title']; ?></label>
            </th> 
            <td>
                <input type="text" name="<?php echo $KEY; ?>" id="reve_<?php echo $KEY; ?>" class="regular-text" maxlength="15" value="<?php echo $options[$KEY]; ?>">	
                <p class="description">
                	<?php 
					$description  = __('Enter your Twitter account, without @, if you want to set a default via parameter to all shortcodes.', 'reve-click2tweet');
			 		$description .= $n. __('You can customize this value in each shortcode with the via attribute.', 'reve-click2tweet');
					$description .= $n.$text_default.$labels[$KEY][0]; 
					echo nl2br($description);
					?>
                </p> 	
            </td>     
        </tr>
        <?php
		// END KEY:
		endif;
		?>    
    
    	<?php
        // *** BEGIN KEY ***
		$KEY = 'short';
		if ( !empty($all_options[$KEY]) && !empty($labels[$KEY]) ):
		?>
        <tr>
            <th scope="row">
            	<label for="revec2t_<?php echo $KEY; ?>"><?php echo $labels[$KEY]['title']; ?></label>
            </th> 
            <td>
                <select name="<?php echo $KEY; ?>" id="revec2t_<?php echo $KEY; ?>" class="regular-text">
					<?php
                    foreach ( $all_options[$KEY] as $index=>$value ):
						if ( $index > 0 ):
							$selected = ( $options[$KEY]==$all_options[$KEY][$index] ) ? ' selected="selected"' : '';
							echo '<option value="' .$value. '"'.$selected.'>' .$labels[$KEY][$index]. '</option>';
						endif; 
                    endforeach;
                    ?>
                </select> 
                <p class="description">
                	<?php 
					$description  = __('Select if you want to generate short WordPress URLs based in the post id, when possible.', 'reve-click2tweet');
			 		$description .= $n. __('Note that you can modify the default URL in each shortcode with the url attribute.', 'reve-click2tweet');
					$description .= $n.$text_default.$labels[$KEY][0]; 
					echo nl2br($description);
					?>
                </p> 	
            </td> 
        </tr>
        <?php
		// END KEY:
		endif;
		?>    
    
    	<?php
        // *** BEGIN KEY ***
		$KEY = 'editor';
		if ( !empty($all_options[$KEY]) && !empty($labels[$KEY]) ):
		?>
        <tr>
            <th scope="row">
            	<label for="revec2t_<?php echo $KEY; ?>"><?php echo $labels[$KEY]['title']; ?></label>
            </th> 
            <td>
                <select name="<?php echo $KEY; ?>" id="revec2t_<?php echo $KEY; ?>" class="regular-text">
					<?php
                    foreach ( $all_options[$KEY] as $index=>$value ):
						if ( $index > 0 ):
							$selected = ( $options[$KEY]==$all_options[$KEY][$index] ) ? ' selected="selected"' : '';
							echo '<option value="' .$value. '"'.$selected.'>' .$labels[$KEY][$index]. '</option>';
						endif; 
                    endforeach;
                    ?>
                </select> 
                <p class="description">
                	<?php 
					$description  = __('Select if you want to display the Reve Click2Tweet shortcode button in the admin editor bar.', 'reve-click2tweet');
			 		$description .= $n.$text_default.$labels[$KEY][0]; 
					echo nl2br($description);
					?>
                </p> 	
            </td> 
        </tr>
        <?php
		// END KEY:
		endif;
		?>       
    	</tbody>
    </table>
    
    <?php
    // THE FORM BUTTONS:
	$disabled = empty($capability) ? 'disabled="disabled"' : '';
	?>
    <p class="textright">
        <a class="button button-large button-default" href="<?php echo $links['admin_page']; ?>"><?php echo __('Cancel','reve-click2tweet'); ?></a>&nbsp;
        <button type="submit" name="submit" id="reve_submit" <?php echo $disabled; ?> value="submit" class="button button-large button-primary">
        <?php echo '&nbsp;'. __('Save changes','reve-click2tweet') .'&nbsp;'; ?>
        </button>
    </p>
    
	</form>
	
    <hr>

	<?php
	// BEGIN SECTION: THE SHORTCODE INSTRUCTIONS
	echo '<h2>'. __('The [revec2t] shortcode instructions', 'reve-click2tweet') .'</h2>';
	?>
    <h4><?php echo __('How to use:','reve-click2tweet'); ?></h4>
    <p>
    <?php
    echo __('Simply insert the shortcode [revec2t] in any post, page or HTML/text widget.','reve-click2tweet');
	echo ' '. __('To add it you can use the Reve Click2Tweet editor button, if option is enabled (by default).','reve-click2tweet');
	?>
    </p>
    
    <p>
    <?php 
	echo __('The shortcode will be replaced by the linkable box that allows your visitors to share any content on Twitter.','reve-click2tweet');
	?>
    </p>
    
    <p>
    <?php
    echo __('You can use the following attributes into the shortcode: text, url, hashtags, via, label, icon and short.','reve-click2tweet');
	?>
    </p>
    
    <p>
    <?php
    echo __('All attributes are optional. If you don\'t set the text, the shortcode will display the URL to share.','reve-click2tweet');
	echo ' '. __('Also, you can set manually the URL, or the shortcode will generate the current page URL.','reve-click2tweet'); ?> 
    </p>
    
    <p>
    <?php
    echo __('The attributes hashtags, via, label, icon and short allows you to custom each shortcode.','reve-click2tweet');
	echo ' '. __('If set, they will overwrite the saved options.','reve-click2tweet');
    echo ' '. __('Also, if you use the special value 0 to any attribute, the saved option will be deactivated.','reve-click2tweet');
	?>
    </p>
    
    <p>
    <?php
	echo __('Usually you only need to use the text attribute to set the text of the tweet, and allow the shortcode to do the rest.','reve-click2tweet');
	?>
    </p>
    
    <h4><?php echo __('Some examples:','reve-click2tweet'); ?></h4>
    
    <p>
        <code>[revec2t]</code> : 
        <?php echo __('The simplest example. As you don\'t set the text, the current page URL will be shown in its place.','reve-click2tweet'); ?>     
    </p>
    
    
    <p>
        <code>[revec2t text="Text of the tweet"]</code> :
        <?php echo __('The recommended use. Displays the box with the text, the current URL and the saved options.','reve-click2tweet'); ?> 
    </p>
    
    <p>
        <code>[revec2t text="Text of the tweet" url="http://..."]</code> : 
        <?php echo __('To use a custom URL. Note that URLs must be valid.','reve-click2tweet'); ?>
    </p>
    
    <p>
        <code>[revec2t text="Text of the tweet" label="Click here"]</code> 
        : <?php echo __('Changes the default call to action label.','reve-click2tweet'); ?>
    </p>
    
    <p>
        <code>[revec2t text="Text of the tweet" hashtags="hashtag1,hashtag2"]</code> : 
        <?php echo __('Sets the hashtags of the tweet, or changes the default hashtags parameter, if set.','reve-click2tweet'); ?>
    </p>
    
    <p>
        <code>[revec2t text="Text of the tweet" via="twitter_user"]</code> : 
        <?php echo __('Sets or changes the default via parameter.','reve-click2tweet'); ?>
    </p>
    
    <p>
        <code>[revec2t text="Text of the tweet" via="0" hashtags="0"]</code> : 
        <?php echo __('Disables the default via and hashtags parameters, if set in options.','reve-click2tweet'); ?>
    </p>
    
    <h4><?php echo __('About the Twitter API:','reve-click2tweet'); ?></h4>
    
    <p>
    <?php
    echo __('Note that the Twitter API will receive and validate all the submitted params.','reve-click2tweet');
	echo ' '.__('So, is a best practice to test each shortcode when it is published, especially if the text or the URL are too long.','reve-click2tweet');
	?>   
    </p>
    
    <p>
    <?php
    echo __('Currently the maximum length of a tweet is 280 characters, including the text, the URL, the via parameter, the hashtags and the blanks.','reve-click2tweet');
    ?>
    </p>
    
    <hr>

	<?php
	// BEGIN SECTION: CONTRIBUTE
	echo '<h2>'. __('Contribute development', 'reve-click2tweet') .'</h2>';
	?>
    <p>
    <?php 
	echo __('To make a standard WordPress plugin, even small, we spend dozens and dozens of hours programming lines of code, testing it and solving issues.','reve-click2tweet');
	?>
    </p>
    
    <p>
    <?php 
	echo __('So, please think of contribute development giving us one minute of your time and/or a few bucks of your wallet.','reve-click2tweet');
	echo ' '.__('This is very little for you, but a lot for us.','reve-click2tweet');
	?>
    </p>
       
    <h4><?php echo __('You can contribute as follow:', 'reve-click2tweet'); ?></h4>
    
    <p>
    <a target="_blank" href="<?php echo $links['review_page']; ?>">
    <?php
    echo __('If you like this plugin, give us a five stars rating clicking here.','reve-click2tweet');
	?>
    </a>
    </p>

	<p>
    <a target="_blank" href="<?php echo $links['donate_page']; ?>">
    <?php
    echo __('If you make this plugin profitable, give us any Paypal donation clicking here.','reve-click2tweet');
	?>
    </a>
    </p>

	<hr>

	<?php
    // *** DEPURATION INFO
    $depuration = 0;
    if ( $depuration ):
        echo '<h2>'. __('Depuration info', 'reve-click2tweet'). '</h2>';
        echo '<p><b>$revec2t:</b><br>'; print_r( $revec2t ); echo '</p>';
        echo '<p><b>$options:</b><br>'; print_r( $options ); echo '</p>';
        echo '<p><b>$all_options:</b><br>'; print_r( $all_options ); echo '</p>';
        echo '<hr>';
    endif;
    ?>

	<?php
	// Unset local vars:
	unset( $options, $all_options, $labels, $links, $notice);
	?>
    
</div><!-- /.wrap -->