/* Filestack callback handlers */

/*
 * Called whenever user selects a file. The callback has a file parameter object
 * that contains the filename, mimetype, size in bytes, and source.
 */
window.filestackOnFileSelected = function(file) {
    // console.log(file);
}

/*
 * Called when a file begins uploading. The callback has a file parameter object
 * that contains the filename, mimetype, size in bytes, and source.
*/
window.filestackOnFileUploadStarted = function(file) {
    // console.log(file);
}

/*
 * Called during multi-part upload progress events. Progress events fire on every
 * XHR progress event, but these progress events will not fire while background
 * uploads are happening. They will also not fire for files selected from cloud
 * sources like dropbox. They only fire for files selected from the local file system.
 *
 * The callback has two parameters, file which is an object containing file metadata
 * (filename, mimetype, size in bytes, and source) and the progressEvent which contains
 * the following:
 *
 * totalPercent  The percent (as an integer) of the file that has been uploaded.
 * totalBytes  An integer stating the total number of bytes uploaded for this file.
 */
window.filestackOnFileUploadProgress = function(file, progressEvent) {
    // console.log(file);
    // console.log(progressEvent);
}

/*
 * Called when a file is done uploading. The callback has a file parameter object
 * that contains the filename, mimetype, size in bytes, and source.
 */
window.filestackOnUploadFinished = function(file) {
    // console.log(file);

    /* uncomment out below to see example code to refresh the page after upload */

    // setTimeout(function(){
    //   window.location.reload(true);
    // }, 1000);

}

/*
 * Called when uploading a file fails. The callback has two parameters: file, an
 * object that contains file metadata (filename, mimetype, size in bytes, and source),
 * and error, the Error instance for this upload.
 */
window.filestackOnFileUploadFailed = function(file, error) {
    // console.log(file);
    console.log(error);
}


/*
 * Called when the upload begins. This callback has a files parameter array that
 * contains all files selected for upload.
 */
window.filestackOnUploadStarted = function() {
    // console.log("filestackOnUploadStarted called...");
}

/*
 * Called when the file uploader modal is opened.
 */
window.filestackOnOpen = function() {
    // console.log("filestackOnOpen called...");
alert("Yayayayayaya let's upload somesing!");
}

/*
 * Called when the file uploader modal is closed.
 */
window.filestackOnClose = function() {
    // console.log("filestackOnClose called...");
}
