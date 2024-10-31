<?php
/*
** uninstall.php @ Reve Click2Tweet Plugin
** Used by wp uninstall/delete the plugin
*/
if ( !defined('WP_UNINSTALL_PLUGIN') ) exit;

// Simply deletes the unique wp_options entry
delete_option('revec2t_options');		