
/**
 *
 * custom Ajax function for saving the media to wordpress after picker uploads it
 *
 **/
function create_wordpress_media( metadata, postID, mediaCategory)
{
    getMeta(metadata.url).done(function(result){
        metadata.filesize = metadata.size;
        metadata.width = result.w;
        metadata.height = result.h;
        metadata.media_category = mediaCategory;

        filestack_data                  = {};
        filestack_data.action              = "filestack_store_local";
        filestack_data._ajax_nonce      = window.filestack_ajax.nonce;
        filestack_data.post_id          = postID;
        filestack_data.post_data           = {};
        filestack_data.post_data.metadata   = metadata;

        /* create a new wordpress media post type and link to the uploaded file */
        /* Switch to browse tab if in admin panel */

        if (jQuery("body").hasClass("wp-admin")) {
            if (typeof wpActiveEditor !== "undefined"){
                var elem = jQuery(wp.media.editor.get(wpActiveEditor)
                    .views._views[".media-frame-router"][0]
                    ._views.browse.el)
                    if (elem) {
                      elem
                      .triggerHandler("click");
                    }
            }
        }

        FF_AJAX.make_ajax_request(window.filestack_ajax.ajaxurl, filestack_data);

        // Refresh the media library after upload
        if (jQuery("body").hasClass("wp-admin")) {
            if (wp.media.frame.content.get()!==null) {
               wp.media.frame.content.get().collection.props.set({ignore: (+ new Date())});
               wp.media.frame.content.get().options.selection.reset();
            } else {
               wp.media.frame.library.props.set({ignore: (+ new Date())});
            }
        }
    });

}


/**
 *
 * Create the filestackForWordpress local object
 *
 **/


filestackForWordpress = {}
filestackForWordpress.pick = function( filestack_options )
{
    if( filestack_options === undefined ){ filestack_options = {} }

    var postID = filestack_options.postID || null;
    var mediaCategory = filestack_options.mediaCategory || null;
    var options = {};

    if (window.filestack_ajax.from_sources) {
        options.fromSources = window.filestack_ajax.from_sources;
    }
    if (window.filestack_ajax.accept) {
        if (noEmptyValues(window.filestack_ajax.accept)) {
            options.accept = window.filestack_ajax.accept;
        }
    }
    if (window.filestack_ajax.maxsize) {
        options.maxSize = Number(window.filestack_ajax.maxsize);
    }
    if (window.filestack_ajax.maxfiles) {
        options.maxFiles = Number(window.filestack_ajax.maxfiles);
    }
    if (window.filestack_ajax.imagemax) {
        options.imageMax = filestack_stringToArray(window.filestack_ajax.imagemax, true);
    }
    if (window.filestack_ajax.imagedim) {
        options.imageDim = filestack_stringToArray(window.filestack_ajax.imagedim, true);
    }
    if (window.filestack_ajax.language) {
        options.lang = window.filestack_ajax.language;
    }

    var store_to = {};
    var has_storeto = false;

    if (window.filestack_ajax.cloud_storage) {
        has_storeto = true;
        store_to.location = window.filestack_ajax.cloud_storage;
    }
    if (window.filestack_ajax.cloud_folder) {
        has_storeto = true;
        store_to.container = window.filestack_ajax.cloud_folder;
    }
    if (window.filestack_ajax.cloud_path) {
        has_storeto = true;
        store_to.path = window.filestack_ajax.cloud_path;
    }
    if (window.filestack_ajax.cloud_region) {
        has_storeto = true;
        store_to.region = window.filestack_ajax.cloud_region;
    }
    if (window.filestack_ajax.cloud_access) {
        has_storeto = true;
        store_to.access = window.filestack_ajax.cloud_access;
    }

    if (has_storeto) {
        options.storeTo = store_to;
    }

    options.onFileSelected = window.filestackOnFileSelected;
    options.onFileUploadStarted = window.filestackOnFileUploadStarted;
    options.onFileUploadProgress = window.filestackOnFileUploadProgress;
    options.onFileUploadFinished = window.filestackOnUploadFinished;
    options.onFileUploadFailed = window.filestackOnFileUploadFailed;
    options.onUploadStarted = window.filestackOnUploadStarted;
    options.onOpen = window.filestackOnOpen;
    options.onClose = window.filestackOnClose;

    filestack.pick(options).then(function (result) {
        var files_uploaded = result.filesUploaded;
        var files_failed = result.filesFailed;

        for (var i = 0; i < files_uploaded.length; i++) {
            create_wordpress_media( files_uploaded[i], postID, mediaCategory);
        }
  });
}
window.fpforwp = filestackForWordpress;

if (window.filestack_ajax.security_policy && window.filestack_ajax.security_signature) {
    window.filestack = filestack.init(window.filestack_ajax.apikey, {
        'policy': window.filestack_ajax.security_policy,
        'signature': window.filestack_ajax.security_signature
    });
} else {
    window.filestack = filestack.init(window.filestack_ajax.apikey);
}


// Utils functions

function noEmptyValues(array_vars) {
    for(var i=0; i<array_vars.length; i++) {
        if (!array_vars[i]) {
            return false;
        }
    }
    return true;
}

function filestack_stringToArray(string_val, to_numeric) {
    string_val = string_val.replace('[', '');
    string_val = string_val.replace(']', '');
    array_vars = string_val.split(',');
    for(var i=0; i<array_vars.length; i++) {
        array_vars[i] = array_vars[i].trim();
        if (to_numeric) {
            array_vars[i] = Number(array_vars[i]);
        }
    }

    return array_vars;
}

function getMeta(url){
    var r = jQuery.Deferred();

  jQuery('<img/>').attr('src', url).load(function(){
     var s = {w:this.width, h:this.height};
     r.resolve(s)
  });
  return r;
}

/**
 *
 * attach the filestack client to any button with class=fp-pick
 *
 **/

jQuery(document).ready(function($)
{
    jQuery("body").on("click", ".fp-pick", function(e)
    {
        e.preventDefault();
        if( isNaN(window.filestack_ajax.perms) ){
            alert(window.filestack_ajax.perms);
        }
        else {
            fpforwp.pick( {
                postID : jQuery(this).attr('data-postid'),
                mediaCategory: jQuery(this).attr('data-media_category'),
            });
        }
    });
});
