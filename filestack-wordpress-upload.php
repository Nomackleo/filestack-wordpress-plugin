<?php

/*
Plugin Name:  Filestack Wordpress Upload
Description:  Allow your users to upload images and media assets from Facebook, Google Drive, Webcam and more without ever leaving your WordPress site. Simple, fast, powerful.
Text Domain:  filestack
Version:      1.0.0
Author:       Filestack
Author URI:   https://www.filestack.com
Contributors: hueyl77, kminnick

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

define('FILESTACK_VERSION', '1.0.0');

if(!defined('FILESTACK_DEBUG')){define('FILESTACK_DEBUG', false);}

define('FILESTACK_PLUGIN_URL', plugin_dir_url( __FILE__ ));
define('FILESTACK_PLUGIN_PATH', plugin_dir_path(__FILE__) );
define('FILESTACK_PLUGIN_BASENAME', plugin_basename(__FILE__));

require_once( FILESTACK_PLUGIN_PATH . '/lib/filestack.class.php');
require_once( FILESTACK_PLUGIN_PATH . '/lib/scripts.php');
require_once( FILESTACK_PLUGIN_PATH . '/lib/shortcodes.php');
require_once( FILESTACK_PLUGIN_PATH . '/lib/admin_integration.php');
require_once( FILESTACK_PLUGIN_PATH . '/lib/filestack_ajax.php');

$_GLOBALS['filestack'] = new Filestack();
