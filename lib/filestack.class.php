<?php

/**
 *
 * Filestack Class
 *
 * @package WordPress
 * @subpackage Filestack Wordpress Upload Plugin
 *
**/
class Filestack {

	var $filestack_options 	= array();
	var $from_sources	= [
		'local_file_system' 	=> 'Computer or Device',
		'imagesearch' 				=> 'Web Search',
		'facebook' 						=> 'Facebook',
		'instagram' 					=> 'Instagram',
		'dropbox' 						=> 'Dropbox',
		'flickr' 							=> 'Flickr',
		'github' 							=> 'Github',
		'box' 								=> 'Box',
		'gmail' 							=> 'Gmail',
		'google_drive' 				=> 'Google Drive',
		'evernote' 						=> 'Evernote',
		'onedrive' 						=> 'OneDrive',
		'clouddrive' 					=> 'Clouddrive',
		'picasa' 							=> 'Picasa',
		'url' 								=> 'URL',
		'webcam' 							=> 'Take Photo',
		'video' 							=> 'Record Video',
		'audio' 							=> 'Record Audio'
	];

	var $filestack_storage		= array(
									'' 		    	=> 'None',
									's3' 				=> 'Amazon s3',
									'dropbox' 	=> 'Dropbox',
									'rackspace' => 'Rackspace Cloud Files',
                  'azure' 		=> 'Azure',
                  'gcs'     	=> 'Google Cloud'
								);

	var $filestack_languages = [
		'en'		=> 'English',
		'zh' 		=> 'Chinese',
		'da'		=> 'Danish',
		'nl'		=> 'Dutch',
		'fr'		=> 'French',
		'de'		=> 'German',
		'he'		=> 'Hebrew',
		'it' 		=> 'Italian',
		'ja' 		=> 'Japanese',
		'pl'		=> 'Polish',
		'pt'		=> 'Portuguese',
		'ru'		=> 'Russian',
		'es'		=> 'Spanish'
	];

	var $filestack_defaults    = array(
									'api_key' 						=> '',
									'security_policy' 		=> '',
									'security_signature' 	=> '',
									'media_owner' 				=> 1,
									'from_sources' 				=> [
																							'local_file_system',
																							'imagesearch',
																							'facebook',
																							'instagram',
																							'dropbox',
																							'google_drive',
																							'url'
																					 ],
									'accept' 					=> array('image/*'),
									'maxsize' 				=> 10240, // 10MB
									'maxfiles' 				=> 1,
									'imagemax'				=> null,
									'imagedim'				=> null,
									'language' 				=> 'en',
									'cloud_storage'		=> '',
									'cloud_folder'		=> '',
									'cloud_path'			=> '',
									'cloud_region'		=> '',
									'cloud_access'		=> 'private'
								);

	function __construct() {
		$this->filestack_options = get_option('filestack_options');
		if( empty($this->filestack_options) )
		{
			$this->filestack_options = $this->filestack_defaults;
		}
	}

	function store_local(){

		if( is_numeric($this->filestack_options['media_owner']) ){
			$currentuser = $this->filestack_options['media_owner'];
		}
		elseif ( current_user_can( 'upload_files' ) ){
			$currentuser = get_current_user_id();
		}
		else{
			return new WP_Error('not_allowed', __("You don't have permission to upload files, please log in to continue", 'filestack'));
		}

		check_ajax_referer( 'filestack-media' );

		$filename = $_REQUEST['post_data']['metadata']['filename'];
		$title = preg_replace('/[^\da-z\-]/i', ' ', $filename);

		$attachment = array(
		 'post_author' 		=> $currentuser,
		 'post_date' 		=> date('Y-m-d H:i:s'),
		 'post_type' 		=> 'attachment',
		 'post_title' 		=> $title,
		 'post_parent' 		=> (!empty($_REQUEST['post_id'])?$_REQUEST['post_id']:null),
		 'post_status' 		=> 'inherit',
		 'post_mime_type' 	=> $_REQUEST['post_data']['metadata']['mimetype'],
		);

		$attachment_id = wp_insert_post( $attachment, true );

		add_post_meta($attachment_id, '_wp_attached_file', $title, true );
		add_post_meta($attachment_id, '_wp_attachment_metadata', $_REQUEST['post_data']['metadata'], true );
		add_post_meta($attachment_id, 'filestack_url', $_REQUEST['post_data']['metadata']['url'], true );

		if (!empty($_REQUEST['post_data']['metadata']['media_category'])) {
			$media_category_id = $_REQUEST['post_data']['metadata']['media_category'];
			wp_set_post_terms( $attachment_id, $media_category_id, 'media_category', true );
		}
	}

}
