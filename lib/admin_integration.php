<?php

/**
 * Displays the filpicker uploader link in the admin area.
 *
 * @package WordPress
 * @subpackage Filestack Wordpress Upload Plugin
 * @since 1.0.0
 */

add_action('post-upload-ui', 'filestack_media_upload');
function filestack_media_upload()
{
    $browser_uploader = admin_url( 'media-new.php?browser-uploader'  );

	if ( $post = get_post() )
		$browser_uploader .= '&amp;post_id=' . intval( $post->ID );
	elseif ( ! empty( $GLOBALS['post_ID'] ) )
		$browser_uploader .= '&amp;post_id=' . intval( $GLOBALS['post_ID'] );

	?>
	<p class="filestackio_upload">
        <button class="fp-pick button-secondary"
		style="z-index: 1; position: relative"><?php _e( 'Filestack Uploader', 'filetack'); ?></button>
	</p>
	<?php
}


/**
 * Fixes the attachment url (so it doesn't look in the local uploads directory)
 *
 * @package WordPress
 * @subpackage filestack.io Plugin
 * @since 1.0.0
 */

add_filter('wp_get_attachment_url', 'filestack_get_attachment_url', 9, 2);
function filestack_get_attachment_url($url, $postID)
{
	$filestack_url = get_post_meta($postID, 'filestack_url', true);

	if( !empty($filestack_url) ){
		return $filestack_url;
	}
	else{
		return $url;
	}
}



/**
 * Add the plugins settings page
 *
 * @package WordPress
 * @subpackage filestack.io Plugin
 * @since 1.0.0
 */

function filestack_add_menu_page(){
	function filestack_menu_page(){
		$options_page_url = FILESTACK_PLUGIN_PATH . '/lib/admin-options.php';
		if(file_exists($options_page_url)){
			include_once($options_page_url);
		}
	};
	add_submenu_page( 'options-general.php', 'Filestack', 'Filestack', 'switch_themes', 'filestack', 'filestack_menu_page' );
};
add_action( 'admin_menu', 'filestack_add_menu_page' );


/**
 * Add a link to the settings page in the plugins section
 *
 * @package WordPress
 * @subpackage filestack.io Plugin
 * @since 1.0.0
 */

function filestack_plugin_settings_link($links) {
  $settings_link = __('<a href="options-general.php?page=filestack">Settings</a>', 'filestack');
  array_unshift($links, $settings_link);
  return $links;
}
add_filter("plugin_action_links_" . FILESTACK_PLUGIN_BASENAME, 'filestack_plugin_settings_link' );
