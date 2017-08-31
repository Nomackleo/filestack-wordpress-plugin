=== Filestack Wordpress Upload ===
Contributors: hueyl77, kminnick
Tags: file upload filestack media cdn uploader facebook dropbox google-drive box skydrive instagram picasa instagram flickr github evernote alfresco
Requires at least: 4.0.1
Tested up to: 4.7
Stable tag: 1.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allow your users to upload images and media assets from Facebook, Google Drive, Webcam and more without ever leaving your WordPress site. Simple, fast, powerful.

== Description ==

With Filestack WordPress Upload, you or your users can upload files directly from local and cloud drives including Facebook, Instagram, Google Drive, Google Image Search, Google Photos, Dropbox, Box image URL, Webcam, Videocam, and URL screenshots.

Simply put the shortcake [Filestack] to call the file uploader and set the location with the uploaded file should appear. ????

You can display the files and media assets immediately on your post or page. The integrated Filestack CDN optimizes for page load time to ensure high performance.

You or your users can crop or edit the image or file within the file uploader, or you can use file transformations to crop, resize, compress, tag, filter, border, or more file transformations to programmatically optimize the images and media assets.

You can store assets conveniently with Filestack or in your own cloud storage location, including Amazon S3, Microsoft Azure, Dropbox, Rackspace and Google Cloud Storage.

### Filestack Wordpress Upload Features:

1. Upload Integrations with Facebook, Instagram, Google Drive, Google Image Search, Google Photos, Dropbox, Box image URL, Webcam, Videocam, and URL screenshots.
1. Multi-File upload
1. Large file upload up to 5TB.
1. In-app image transformations to crop, circle crop, and rotate image
1. Asynchronous uploads
1. Integrated CDN for fast delivery
1. Cloud Storage Integrations with Amazon S3, Microsoft Azure, Dropbox, Rackspace and Google Cloud Storage.

### Links
[https://www.filestack.com](https://www.filestack.com)
[https://www.filestack.com/docs/javascript-api/pick-v3](https://www.filestack.com/docs/javascript-api/pick-v3)
[Free API Key](https://dev.filestack.com/register)


### Languages
1. English: 'en'
1. Chinese: 'zh'
1. Danish: 'da'
1. Dutch: 'nl'
1. French: 'fr'
1. German: 'de'
1. Hebrew: 'he'
1. Italian: 'it'
1. Japanese: 'ja'
1. Polish: 'pl'
1. Portuguese: 'pt'
1. Russian: 'ru'
1. Spanish: 'es'

### Questions
You can contact Support at support@filestack.com and send general questions to hello@filestack.com. We love hearing from you!


== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Add your Filestack API Key
1. Add the shortcode [filestack] in any blog post or page to display the upload button
1. Or click the Filestack button in the Media section to Upload Files
1. Select Insert Into Post to insert CDN resource

== Frequently Asked Questions ==

= Do I need a Filestack account? =

Yes, however Filestack offers a free plan with up to 250 file uploads per month. Filestack offers a Starter Plan at $49/mo with 8,000 uploads per month, and higher plans if your file uploading needs expand that. Sign up for Filestack here - https://www.filestack.com/pricing.

= Does it work with the wordpress media uploader? =

Yes.

= Is it easily added to pages or posts for signed-in & non signed-in users to upload files? =

Yes. Filestack provides a seamless experience for you and your users to easily upload and manage files.

= What is the shortcode for displaying the uploader in a blog post or page? =

[filestack]

= Can I customize the appearance of the upload button or upload modal? =

Yes.  See instructions on the settings page of the plugin.

= Do the uploaded files have CDN endpoints? =

Yes they do.  The response metadata will return the cdn url for you to use in your callback handlers.

= Do I have to do anything to configure Filestack Storage or CDN =

No, Filestack Storage and CDN will work automatically, without any effort on your part. If you wish to customize the storage location or use your own CDN, you can change the default configuration. See more in the Filestack documentation - https://www.filestack.com/docs/cloud-storage/s3.

= Where are the uploaded files stored? =

By default, uploaded files are stored in Filestack's S3 bucket.  However, you can configure
your Filestack account to store in your own S3 bucket or upgrade to store in one of the
following cloud storage services:

1. Rackspace
1. Google Cloud
1. Microsoft Azure
1. Dropbox

== Screenshots ==

1. File Uploader Modal
1. Search the Web
1. Settings Page
1. Integrated Cloud Services
1. Cropping Tool
1. Circle Tool
1. Response Metadata

== Changelog ==

= 1.0.3 =
* Bugfix: undefined callbacks on Media Assets page

= 1.0.2 =
* Updated Plugin Title

= 1.0.1 =
* Added assets screenshots banners and icons

= 1.0.0 =
* Initial Wordpress.com version

