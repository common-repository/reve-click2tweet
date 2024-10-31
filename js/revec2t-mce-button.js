/*
** js/revec2t-mce-button.js @ Reve ClickToTweet Plugin
** The TinyMCE plugin for the WordPress editor button
** Used by inc/admin.php
*/

( function() {
    
	tinymce.create( 'tinymce.plugins.revec2t_mce_button', {

        // The url holds the absolute url of the mce plugin directory
        init : function(ed, url) {
			
            // Adds the button:     
            ed.addButton( 'revec2t_button', {
				tooltip: 	'Reve Click2Tweet shortcode',
                image: 		url.replace('/js', '') + '/img/admin-button-blue.svg',
				cmd: 		'revec2t_button_command'
				});

            // The button command:
            ed.addCommand( 'revec2t_button_command', function() {
                var selected_text	= ed.selection.getContent();
                var return_text 	= '[revec2t text="' + selected_text + '"]';
                ed.execCommand('mceInsertContent', 0, return_text);
				});

        	}, // :/init()

        createControl : function(n, cm) {
            return null;
        	}, // :/createControl()

        getInfo : function() {
            return {
                longname: 	'TinyMCE button for Reve Click2Tweet WordPress Plugin',
                author: 	'Fernando García',
                version : 	'1.0'
            	};
			} // :/getInfo()
    
	}); // :/tinymce.create()

	// Adds to the plugin manager:
    tinymce.PluginManager.add('revec2t_mce_button', tinymce.plugins.revec2t_mce_button);

})();