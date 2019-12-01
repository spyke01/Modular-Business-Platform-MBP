<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

$uploadDirectory = "files/uploads/";

if ( ! is_dir( $uploadDirectory ) ) {
	mkdir( $uploadDirectory );
}

$verifyToken = md5( 'unique_salt' . $_POST['timestamp'] );

if ( ! empty( $_FILES ) && $_POST['token'] == $verifyToken ) {
	// Uploadify creates its own session so we can't verify sessions here, we could if we specified a session id everytime we start a session for a user
	$tempFile         = $_FILES['Filedata']['tmp_name'];
	$uploadedFilename = $_FILES['Filedata']['name'];

	// Get our file name and extension so we can modify the name
	$fileName = basename( $uploadedFilename );

	// Validate the file type
	$fileTypes = [
		'jpg',
		'jpeg',
		'gif',
		'png',
		'tiff',
		'doc',
		'docx',
		'ppt',
		'pptx',
		'pps',
		'ppsx',
		'odt',
		'pdf',
		'txt',
		'xls',
		'xlsx',
		'csv',
		'psd',
		'ai',
		'mp3',
		'm4a',
		'ogg',
		'wav',
		'mp4',
		'mov',
		'wmv',
		'avi',
		'mpg',
		'ogv',
		'3gp',
		'3g2',
	]; // File extensions
	$fileParts = pathinfo( $uploadedFilename );
	$fileExt   = $fileParts['extension'];

	if ( in_array( $fileExt, $fileTypes ) ) {
		// Append a timestamp on our filename			
		$targetFile = $uploadDirectory . $fileName . "_" . date( "mdyhi", time() ) . "." . $fileExt;

		// Create the directory if it doesn't exist
		//mkdir($uploadDirectory, 0755, true);

		// Move the temp file
		move_uploaded_file( $tempFile, $targetFile );

		// Spit back our filename
		echo $targetFile;
	} else {
		echo 'Invalid file type.';
	}
}