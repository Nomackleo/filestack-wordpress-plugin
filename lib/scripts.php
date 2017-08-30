<?php

/**
*
* Enqueue javascripts & CSS
*
* @since 1.0.0
*
**/

add_action('init', 'filestack_scripts');
function filestack_scripts()
{
	wp_register_style('filestack-style', FILESTACK_PLUGIN_URL . 'css/filestack_style.css');
	wp_register_script('filestack', FILESTACK_PLUGIN_URL . 'js/filestack-0.9.0.js');
	wp_register_script('filestack_callbacks', FILESTACK_PLUGIN_URL . 'js/filestack-callbacks.js');
	wp_register_script('filestack_for_wordpress', FILESTACK_PLUGIN_URL . 'js/filestack_for_wordpress.js', array('filestack', 'jquery'));
	wp_register_script('cross_browser_ajax', FILESTACK_PLUGIN_URL . 'js/cross_browser_ajax.js', array('jquery'));

	if( !empty($_GLOBALS['filestack']) ){
		$filestack = $_GLOBALS['filestack'];
	}
	else{
		$filestack = new filestack();
	}

	if( is_numeric($filestack->filestack_options['media_owner']) ){
		$perms = $filestack->filestack_options['media_owner'];
	}
	elseif ( current_user_can( 'upload_files' ) ){
		$perms = get_current_user_id();
	}
	else{
		$perms = __("You don't have permission to upload files, please log in to continue", 'filestack');
	}

	wp_localize_script( 'filestack_for_wordpress', 'filestack_ajax',
		array(
			'ajaxurl' 			=> admin_url( 'admin-ajax.php' ),
			'nonce'   			=> wp_create_nonce('filestack-media'),
			'apikey'  			=> $filestack->filestack_options['api_key'],
			'security_policy' 		=> $filestack->filestack_options['security_policy'],
			'security_signature'  => $filestack->filestack_options['security_signature'],
			'from_sources'	=> $filestack->filestack_options['from_sources'],
			'accept'				=> $filestack->filestack_options['accept'],
			'maxsize'				=> $filestack->filestack_options['maxsize'],
			'maxfiles'			=> $filestack->filestack_options['maxfiles'],
			'imagemax'			=> $filestack->filestack_options['imagemax'],
			'imagedim'			=> $filestack->filestack_options['imagedim'],
			'language' 			=> $filestack->filestack_options['language'],
			'cloud_storage' => $filestack->filestack_options['cloud_storage'],
			'cloud_folder' 	=> $filestack->filestack_options['cloud_folder'],
			'cloud_path'		=> $filestack->filestack_options['cloud_path'],
			'cloud_region'	=> $filestack->filestack_options['cloud_region'],
			'cloud_access'	=> $filestack->filestack_options['cloud_access'],
			'perms' 				=> $perms,
		)
	);

	add_action('wp_enqueue_scripts', function(){
		// Enqueue a CSS on the front end
		wp_enqueue_style('filestack-style');
		wp_enqueue_script('filestack');
		wp_enqueue_script('filestack_callbacks');
		wp_enqueue_script('filestack_for_wordpress');
    wp_enqueue_script('cross_browser_ajax');
    wp_enqueue_script('filestack_debug');
	});

	add_action('admin_enqueue_scripts', function(){
		// Enqueue a CSS on admin pages
		wp_enqueue_style('filestack-style');
		wp_enqueue_script('filestack');
		wp_enqueue_script('filestack_for_wordpress');
        wp_enqueue_script('cross_browser_ajax');
        wp_enqueue_script('filestack_debug');
	});

}
