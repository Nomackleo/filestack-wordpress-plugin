<?php

/**
 * Ajax function for putting filestack.io files into the media section
 *
 * @since 1.0.0
 */
function filestack_store_local(){
	$filestack = new Filestack();
	$filestack->store_local();
}
add_action( 'wp_ajax_filestack_store_local', 'filestack_store_local' );
add_action( 'wp_ajax_nopriv_filestack_store_local', 'filestack_store_local' );