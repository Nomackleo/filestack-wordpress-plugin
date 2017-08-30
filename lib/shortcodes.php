<?php

add_shortcode( 'filestack', 'filestack_show' );
function filestack_show($atts) {
	global $post;

	extract(shortcode_atts(array(
		'post_id' 			=> $post->ID,
		'button_title'	=> __('Upload a File', 'filestack')
	), $atts));

	if( !empty($_GLOBALS['filestack']) ){
		$filestack = $_GLOBALS['filestack'];
	}
	else{
		$filestack = new filestack();
	}

	print "<button class='fp-pick' " .
		"data-postid='{$post_id}'";

	if (!empty($atts['media_category'])) {
		print " data-media_category='{$atts['media_category']}'";
	}

	print ">{$button_title}</button>";
}