<?php

require_once( FILESTACK_PLUGIN_PATH . '/filestack-wordpress-upload.php');

$filestack = new filestack();

function filestack_sanitize_admin_inputs($filestack_options, $filestack_options_post, $filestack)
{
	foreach( $filestack->filestack_defaults as $field => $default){
		if( isset($filestack_options_post[$field]) && $field == 'from_sources' ){
			$from_sources = array();
			foreach( $filestack->from_sources as $source_name => $source_display ){
				if( in_array($source_name, $filestack_options_post['from_sources']) ){ array_push($from_sources, $source_name); }
			}
			$filestack_options[$field] = $from_sources;
		}
		elseif (isset($filestack_options_post[$field]) && $field == 'accept') {
				$filestack_options['accept'] = explode(',', $filestack_options_post['accept']);
		}
		elseif( isset($filestack_options_post[$field]) ){
			$filestack_options[$field] = sanitize_text_field( $filestack_options_post[$field] );
		}
		elseif( empty($filestack_options[$field]) ) {
			$filestack_options[$field] = $default;
		}
	}
	return $filestack_options;
}

// get the options (if any) stored in the DB
$filestack_options = get_option('filestack_options');

// get any updates to those options sent from the form
$filestack_options_post = isset($_POST['filestack_options']) ? $_POST['filestack_options'] : false;

// overwrite options if needed - also set defaults for options that are not set
$filestack_options = filestack_sanitize_admin_inputs($filestack_options, $filestack_options_post, $filestack);

// if the admin made changes, update the DB
if($filestack_options_post){
	update_option('filestack_options', $filestack_options);
}

?>

<style>
.options-form-wrap,
.shortcode-notes-wrap,
.callbacks-notes-wrap {
	padding: 5px 25px 20px;
	background-color: white;
	border: 1px solid #dedede;
	border-radius: 5px;
}

.shortcode-notes .col1,
.callbacks-notes .col1 {
	min-width: 320px;
	vertical-align: top;
}

.shortcode-notes td,
.callbacks-notes td {
	padding-bottom: 15px;
}

.options-table th {
	text-align: left;
	padding-top: 20px;
}

.options-table th, td {
	vertical-align: top;
}

td.col2 {
	padding-left: 20px;
	width: 50%;
}

.options-table .text-field {
	padding: 6px 10px;
  border-radius: 4px;
  width: 100%;
}

.fromSources-list li {
	float: left;
	width: 160px;
	margin-left: 20px;
}

.source_cbox_label {
	display: inline-block;
  padding-bottom: 3px;
}

.section-title {
	margin: 10px 0;
}

hr.section-separator {
	margin: 20px 0;
}

@media screen and (max-width: 782px) {
	.wrap {
		margin: 0;
	}

	.options-form-wrap,
	.shortcode-notes-wrap,
	.callbacks-notes-wrap {
		padding: 10px;
	}

	td, th {
		display: block;
	}

	td.col2 {
		padding: 10px;
		width: 90%;
	}

	.options-table .text-field,
	select.large-text {
		width: 90%;
	}
}
</style>

<div class="wrap">

	<div class="icon32" id="icon-options-general"><br></div>
	<h2><?php _e('Filestack Wordpress Upload Settings', 'filestack') ?></h2>

	<br/>

	<div id="filestack-options-form">

		<?php if(!$filestack_options['api_key']): ?>
			<div class="updated" id="message"><p><strong>Alert!</strong> You must get an API Key from Filestack to start<br />If you don't already have an account, you can <a target="_blank" href="https://dev.filestack.com/register">sign up for one here</a></p></div>
		<?php endif; ?>

		<div class="options-form-wrap">
			<form action="" id="filestack-form" method="post">
				<table class="options-table">
        <tbody>
	        <tr>
	          <th colspan="2">
	          	<label><?php _e('Enter your Filestack API Key', 'filestack') ?></label>
	          </th>
	        </tr>
	        <tr>
	        	<td class="col1">
	            <input type="text" class="text-field code" value="<?php echo $filestack_options['api_key']; ?>" id="filestack-api_key" name="filestack_options[api_key]"
								placeholder='Your API Key'/>
	          </td>
	          <td class="col2">
	          	<i>Required - <a href="https://dev.filestack.com/register" target="_blank">Get a free apikey</a></i>
	          </td>
	        </tr>

	        <tr>
	          <th colspan="2"><label>Security Policy and Signature</th>
	        </tr>

	        <tr>
	        	<td class="col1">
            	<input type="text" class="text-field code"
            		value="<?php echo $filestack_options['security_policy']; ?>"
            		id="filestack-security_policy"
            		name="filestack_options[security_policy]"
            		placeholder="Security policy. e.g. eyJoY...wNH0=" />
          	</td>
          	<td class="col2">
            	Base64 url-encoded security policy.  You must entered a security policy and signature if you have this feature turned on in the <a href="https://dev.filestack.com" target="_blank">Filestack Dev Portal</a>.
          	</td>
	        </tr>

	        <tr>
	        	<td class="col1">
            	<input type="text" class="text-field code"
            		value="<?php echo $filestack_options['security_signature']; ?>"
            		id="filestack-security_signature"
            		name="filestack_options[security_signature]"
            		placeholder="Security Signature.  e.g. 4098f....62c18"/>
          	</td>
          	<td class="col2">
            	Security generated signature.  See <a href="https://www.filestack.com/docs/security/signing-policies" target="_blank">Signing Policies</a> in the Filestack documentation.
          	</td>
	        </tr>

	        <tr>
	          <th colspan="2"><label>Media Owner.</th>
	        </tr>

	        <tr>
	        	<td class="col1">
	            <?php $users = get_users( array('orderby' => 'id', 'order' => 'DESC') ); ?>
	            <select id="filestack-media_owner" name="filestack_options[media_owner]" class="large-text" style="width:300px">
	              <option value="logged_in" <?php if( 'logged_in' == $filestack_options['media_owner'] ){print "selected='selected'";} ?>>User must be logged in to wordpress to upload files</option>
	            <?php foreach( $users as $user ): ?>
	              <option value="<?php print $user->ID ?>" <?php if( $user->ID == $filestack_options['media_owner'] ){print "selected='selected'";} ?>><?php print $user->data->display_name ?></option>
	            <?php endforeach; ?>
	            </select>
          	</td>
	          <td class="col2">
	            Define what wordpress user will own the files that are uploaded.<br/>
	          </td>
	        </tr>

	        <!-- Uploader Options -->

	        <tr>
	          <td colspan="2">
	          	<hr class="section-separator" />
	          	<h3 class="section-title">Uploader Options</h3>
	          </td>
	        </tr>

	        <tr>
	          <th colspan="2"><label>Specify which sources are displayed on the left panel, and in which order, by name.</label></th>
	        </tr>

	        <tr>
	        	<td colspan="2">
	        		<ul class="fromSources-list">
	        			<?php foreach( $filestack->from_sources as $source_name => $source_display ): ?>
									<li>
										<input type="checkbox" id="<?php print $source_name ?>_cbox"
											name="filestack_options[from_sources][]" class="source_cbox"
											value="<?php print $source_name ?>"
											<?php if( in_array($source_name, $filestack_options['from_sources'])): ?>
												checked
											<?php endif; ?>
										/>
										<label for="<?php print $source_name ?>_cbox"
											class="source_cbox_label">
											<?php print $source_display ?>
											</label>
									</li>
								<?php endforeach; ?>
	        		</ul>
	        	</td>
	        </tr>

	        <tr>
	          <th colspan="2"><label>Specify the type of file that the user is allowed to upload.</th>
	        </tr>

	        <tr>
	        	<td class="col1">
	            <input type="text" class="text-field code"
	            	value="<?php echo join(',', $filestack_options['accept']); ?>"
	            	id="filestack-mimetypes" name="filestack_options[accept]"
	            	placeholder="e.g. image/*" />
	          </td>
	          <td class="col2">
	          	This can be an extension or a mimetype. e.g.<br>
	          	<i>'image/*' or ['image/*', '.pdf', 'video/mp4', …]</i>
	          </td>
	        </tr>

	        <tr>
	          <th colspan="2"><label>Max File Size <small>(optional)</small></th>
	        </tr>

	        <tr>
	        	<td class="col1">
	            <input type="text" class="text-field code"
	            	value="<?php echo $filestack_options['maxsize']; ?>"
	            	id="filestack-maxSize" name="filestack_options[maxsize]"
	            	placeholder="e.g. 10485760" />
	          </td>
	          <td class="col2">
	          	Limit uploads to be at most maxSize, specified in bytes.
	          </td>
	        </tr>

	        <tr>
	          <th colspan="2"><label>Max Number of Files <small>(optional)</small></th>
	        </tr>

	        <tr>
	        	<td class="col1">
	            <input type="text" class="text-field code"
	            	value="<?php echo $filestack_options['maxfiles']; ?>"
	            	id="filestack-maxSize" name="filestack_options[maxfiles]"
	            	placeholder="e.g. 10" />
	          </td>
	          <td class="col2">
	          	Specify the maximum number of files that the user can upload at a time. By default, maxfiles is set to 1.
	          </td>
	        </tr>

	        <tr>
	          <th colspan="2"><label>Max image dimensions <small>(optional)</small></th>
	        </tr>

	        <tr>
	        	<td class="col1">
	            <input type="text" class="text-field code"
	            	value="<?php echo $filestack_options['imagemax']; ?>"
	            	id="filestack-imagemax" name="filestack_options[imagemax]"
	            	placeholder="e.g. [800, 600]" />
	          </td>
	          <td class="col2">
	          	Specify maximum image dimensions [width, height] e.g. [800, 600]. Images larger than the specified dimensions will be resized to the maximum size while maintaining the original aspect ratio.
	          </td>

	          <tr>
	          <th colspan="2"><label>Set image dimensions <small>(optional)</small></th>
	        </tr>

	        <tr>
	        	<td class="col1">
	            <input type="text" class="text-field code"
	            	value="<?php echo $filestack_options['imagedim']; ?>"
	            	id="filestack-imagedim" name="filestack_options[imagedim]"
	            	placeholder="e.g. [800, 600]" />
	          </td>
	          <td class="col2">
	          	Specify image dimensions [width, height]. e.g. [800, 600]. Images smaller or larger than the specified dimensions will be resized to this dimesnion size while maintaining the original aspect ratio.
	          </td>
	        </tr>

	        <tr>
	          <th colspan="2"><label>Languages <small>(optional)</small></th>
	        </tr>

	        <tr>
	        	<td class="col1">
	            <select id="filestack-language" name="filestack_options[language]" class="large-text" style="width:300px">
	            <?php foreach( $filestack->filestack_languages as $code => $language ): ?>
	              <option value="<?php print $code ?>" <?php if( $code == $filestack_options['language'] ){print "selected='selected'";} ?>><?php print $language ?></option>
	            <?php endforeach; ?>
	            </select>
	          </td>
	          <td class="col2">
	            Select the human language.
	          </td>
	        </tr>

	        <!-- Storage Options -->

	        <tr>
	          <td colspan="2">
	          	<hr class="section-separator" />
	          	<h3 class="section-title">Storage Options</h3>
	          </td>
	        </tr>

	        <tr>
	          <th colspan="2"><label>Cloud Storage <small>(optional)</small></th>
	        </tr>

	        <tr>
	        	<td class="col1">
	            <select id="filestack-cloud_storage" name="filestack_options[cloud_storage]" class="large-text" style="width:300px">
	            <?php foreach( $filestack->filestack_storage as $type => $desc ): ?>
	              <option value="<?php print $type ?>" <?php if( $type == $filestack_options['cloud_storage'] ){print "selected='selected'";} ?>><?php print $desc ?></option>
	            <?php endforeach; ?>
	            </select>
	          </td>
	          <td class="col2">
	            Copy uploads to your own S3, Dropbox, etc.<br/>
	            Requires additional setup & costs at filestack.com
	          </td>
	        </tr>

	        <tr>
	          <th colspan="2"><label>Cloud Folder/Container <small>(optional)</small>.</th>
	        </tr>

	        <tr>
	        	<td class="col1">
            	<input type="text" class="text-field code" value="<?php echo $filestack_options['cloud_folder']; ?>"
            		id="filestack-cloud_folder"
            		name="filestack_options[cloud_folder]"
            		placeholder="e.g. assets_bucket" />
          	</td>
          	<td class="col2">
            	Bucket/Container to use inside of your cloud storage
          	</td>
	        </tr>

	        <tr>
	          <th colspan="2"><label>Cloud Path <small>(optional)</small>.</th>
	        </tr>

	        <tr>
	        	<td class="col1">
            	<input type="text" class="text-field code" value="<?php echo $filestack_options['cloud_path']; ?>"
            		id="filestack-cloud_path"
            		name="filestack_options[cloud_path]"
            		placeholder="e.g. /myfiles/1234.png" />
          	</td>
          	<td class="col2">
            	The path to store the file at within the specified file store. For S3, this is the key where the file will be stored at.
          	</td>
	        </tr>

	        <tr>
	          <th colspan="2"><label>Cloud Region <small>(optional)</small>.</th>
	        </tr>

	        <tr>
	        	<td class="col1">
            	<input type="text" class="text-field code" value="<?php echo $filestack_options['cloud_region']; ?>"
            		id="filestack-cloud_region"
            		name="filestack_options[cloud_region]"
            		placeholder="e.g. us-east-1" />
          	</td>
          	<td class="col2">
            	The region where your storage container is located. This setting currently applies only to S3 buckets.
          	</td>
	        </tr>

	        <tr>
	          <th colspan="2"><label>Access</th>
	        </tr>

	        <tr>
	        	<td class="col1">
	            <select id="filestack-cloud_public_access" name="filestack_options[cloud_access]" class="large-text" style="width:100%">
	              <option value="public"
	              <?php if( $filestack_options['cloud_access'] == 'public'): ?>
									selected
								<?php endif; ?>>Public</option>
	              <option value="private"
	              <?php if( $filestack_options['cloud_access'] == 'private'): ?>
									selected
								<?php endif; ?>>Private</option>
	            </select>
          	</td>
	          <td class="col2">
	            Define what wordpress user will own the files that are uploaded.<br/>
	          </td>
	        </tr>

	        <tr>
          <td colspan="2">
            <input type="submit" value="Save Settings" class="button-primary"/>
          </td>
        </tr>
        </tbody>
        </table>
			</form>
		</div>

		<p style="text-align: left;">
			<h3>Callback Handlers</h3>
		</p>

		<div class="callbacks-notes-wrap">
			<table class="callbacks-notes">
				<tbody>
				<tr>
					<td colspan="2">
						You can add javascript code to the following callback functions
						to handle the responses from Filestack after an event is triggered.
						<br/>
						These callback functions are declared in the file:<br/>
						<br/>
						<a href="/wp-admin/plugin-editor.php?file=filestack-upload%2Fjs%2Ffilestack-callbacks.js&plugin=filestack-upload%2Ffilestack-wordpress-upload.php"><i>/wp-content/plugins/filestack-upload/js/filestack-callbacks.js</i></a>
						<br/>
						<hr/>
					</td>
				</tr>
				<tr>
					<td class="col1" valign="top">
						filestackOnFileSelected(file)
					</td>
					<td class="col2" valign="top">
						 Called whenever user selects a file. The callback has a file parameter object that contains the filename, mimetype, size in bytes, and source.
					</td>
				</tr>
				<tr>
					<td class="col1" valign="top">
						filestackOnFileUploadStarted(file)
					</td>
					<td class="col2" valign="top">
						  Called when a file begins uploading. The callback has a file parameter object that contains the filename, mimetype, size in bytes, and source.
					</td>
				</tr>

				<tr>
					<td class="col1" valign="top">
						filestackOnFileUploadProgress(file, progressEvent)
					</td>
					<td class="col2" valign="top">
						  Called during multi-part upload progress events. Progress events fire on every XHR progress event, but these progress events will not fire while background uploads are happening. They will also not fire for files selected from cloud sources like google drive. They only fire for files selected from the local file system.<br/>
						  <br/>
							The callback has two parameters, file which is an object containing file metadata (filename, mimetype, size in bytes, and source) and the progressEvent which contains the following:<br/>
							<br/>
							totalPercent	The percent (as an integer) of the file that has been uploaded.<br/>
							totalBytes	An integer stating the total number of bytes uploaded for this file.
					</td>
				</tr>

				<tr>
					<td class="col1" valign="top">
						filestackOnUploadFinished(file)
					</td>
					<td class="col2" valign="top">
						  Called when a file is done uploading. The callback has a file parameter object that contains the filename, mimetype, size in bytes, and source.
					</td>
				</tr>

				<tr>
					<td class="col1" valign="top">
						filestackOnFileUploadFailed(file, error)
					</td>
					<td class="col2" valign="top">
						  Called when uploading a file fails. The callback has two parameters: file, an object that contains file metadata (filename, mimetype, size in bytes, and source), and error, the Error instance for this upload.
					</td>
				</tr>

				<tr>
					<td class="col1" valign="top">
						filestackOnUploadStarted()
					</td>
					<td class="col2" valign="top">
						  Called when the upload begins. This callback has a files parameter array that contains all files selected for upload.
					</td>
				</tr>

				<tr>
					<td class="col1" valign="top">
						filestackOnOpen()
					</td>
					<td class="col2" valign="top">
						  Called when the file uploader modal is opened.
					</td>
				</tr>

				<tr>
					<td class="col1" valign="top">
						filestackOnClose()
					</td>
					<td class="col2" valign="top">
						  Called when the file uploader modal is closed.
					</td>
				</tr>
				</tbody>
			</table>
		</div>

		<p style="text-align: left;">
			<h3>Shortcode Examples</h3>
		</p>

		<div class="shortcode-notes-wrap">
			<table class="shortcode-notes">
				<tbody>
				<tr>
					<td class="col1">
						[filestack]
					</td>
					<td class="col2">
						Drop this in a page or post to allow users the ability to attach images to that page or post
					</td>
				</tr>
				<tr>
					<td class="col1">
						[filestack button_title='Upload a Photo' post_id=1]
					</td>
					<td class="col2">
						In this example, we customize the button title and define a different post to attach the uploaded images to
					</td>
				</tr>
				<tr>
					<td class="col1">
						[filestack post_id=1 media_category='2345']
					</td>
					<td class="col2">
						In this example, we assign the media_category taxonomy value to the uploaded file.  This is useful if you want to display only images in this
						category via the <i>[gallery]</i> shortcode using plugins such as <a href="https://wordpress.org/plugins/enhanced-media-library/" target="_blank">Enhanced Media Library</a>.<br/>
						<i>e.g. the shortcode: <br/>
						[gallery media_category="2054" order="DESC" orderby="ID"]</i><br/>
						will display the gallery of all the media tagged in that category.
					</td>
				</tr>

				</tbody>
			</table>
		</div>

		<p style="text-align: left;">
			<h3>Customizing Appearance Using CSS</h3>
		</p>

		<div class="callbacks-notes-wrap">
			<table class="callbacks-notes">
				<tbody>
				<tr>
					<td colspan="2">
						You can customize the appearance of the Upload button and Modal by overriding
						their css attributes in the following file:<br/>
						<br>
						<a href="/wp-admin/plugin-editor.php?file=filestack-upload%2Fcss%2Ffilestack_style.css&plugin=filestack-upload%2Ffilestack-wordpress-upload.php"><i>/wp-content/plugins/filestack-upload/css/filestack_style.css</i></a>.
						<br/>
						<hr/>
					</td>
				</tr>
				<tr>
					<td class="col1" valign="top">
						Upload Button
					</td>
					<td class="col2" valign="top">
						Example:<br/>
<pre>.fp-pick {
  width: 100px;
  height: 80px;
  padding: 20px;
  margin: 50px;
  background-color: #ff0000;
  color: white;
  font-family: verdana;
  font-size: 18px;
  border: 2px solid green;
  border-radius: 10px;
  text-shadow: 2px 2px 4px black;
  box-shadow: 1px 2px 4px gray;
}

.fp-pick:hover {
  background: pink;
  color: red;
}</pre>
					</td>
				</tr>

				<tr>
					<td class="col1" valign="top">
						Upload Modal (Window)
					</td>
					<td class="col2" valign="top">
						Example:<br/>
<pre>.fsp-header {
  background-color: black !important;
  color: white !important;
}

.fsp-modal__sidebar {
  background-color: pink !important;
}

.fsp-source-list__item.active {
  background-color: pink !important;
}

.fsp-drop-area-container {
  background-color: red !important;
}

.fsp-drop-area {
  background-color: green !important;
}

.fsp-text__title {
  color: white !important;
  text-transform: uppercase;
  font-size: 24px !important;
}</pre>
					</td>
				</tr>
				</tbody>
			</table>
		</div>

	</div>

</div>
