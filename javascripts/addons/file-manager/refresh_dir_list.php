<?php

require_once( 'config.php' );
require_once( 'functions.php' );

$output = [];

$output["success"] = 1;
$output["html"]    = Dirtree( LIBRARY_FOLDER_PATH );;


header( "Content-type: text/plain;" );
echo json_encode( $output );
exit();
