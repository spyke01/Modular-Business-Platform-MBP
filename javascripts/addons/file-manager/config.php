<?php


/* Define our Paths */
$path = dirname(__FILE__);
$path = str_replace( '/javascripts/addons/file-manager', '', $path ); // We need our main MBP directory
define('ABSPATH', $path . '/');
define('BASEPATH', rtrim(ABSPATH, '/'));

include BASEPATH . '/includes/header.php';

//session_start();

/** Full path to the folder that images will be used as library and upload. Include trailing slash */
define('FOLDER_PATH', '../../../files/uploads/');

/** Full URL to the folder that images will be used as library and upload. Include trailing slash and protocol (i.e. http://) */
define('FOLDER_URL', 'http://' . $_SERVER['HTTP_HOST'] . rtrim( dirname( str_replace( '/javascripts/addons/file-manager', '', $_SERVER['PHP_SELF'] ) ), '/\\' ) . '/files/uploads/');

/** The extensions for to use in validation */
define('ALLOWED_IMG_EXTENSIONS', 'gif,jpg,jpeg,png,jpe,pdf');

/** Should the files be renamed to a random name when uploading */
define('RENAME_UPLOADED_FILES', true);

/** Number of folders/images to display per page */
define('ROWS_PER_PAGE', 12);


/** Should Images be resized on upload. You will then need to set at least one of the dimensions sizes below */
define('RESIZE_ON_UPLOAD', false);

/** If resizing, width */
define('RESIZE_WIDTH', 300);
/** If resizing, height */
define('RESIZE_HEIGHT', 300);


/** Should a thumbnail be created? */
define('THUMBNAIL_ON_UPLOAD', false);

/** If thumbnailing, thumbnail postfix */
define('THUMBNAIL_POSTFIX', '_thumb');
/** If thumbnailing, maximum width */
define('THUMBNAIL_WIDTH', 100);
/** If thumbnailing, maximum height */
define('THUMBNAIL_HEIGHT', 100);
/** If thumbnailing, hide thumbnails in listings */
define('THUMBNAIL_HIDE', true);



/**  Use these 9 functions to check cookies and sessions for permission. 
Simply write your code and return true or false */


/** If you would like each user to have their own folder and only upload 
 * to that folder and get images from there, you can use this funtion to 
 * set the folder name base on user ids or usernames. NB: make sure it return 
 * a valid folder name. */
function CurrentUserFolder() {
	return $_SESSION['username'];
}


function CanAcessLibrary(){
	return user_access('file_manager_access_library');
}

function CanAcessUploadForm(){
	return user_access('file_manager_access_upload_form');
}

function CanAcessAllRecent(){
	return user_access('file_manager_access_recent');
}

function CanCreateFolders(){
	return user_access('file_manager_folders_create');
}

function CanDeleteFiles(){
	return user_access('file_manager_files_delete');
}

function CanDeleteFolder(){
	return user_access('file_manager_folders_delete');
}

function CanRenameFiles(){
	return user_access('file_manager_files_rename');
}

function CanRenameFolder(){
	return user_access('file_manager_folders_rename');
}


define("ENCRYPTION_KEY", "!@#$%^&*");