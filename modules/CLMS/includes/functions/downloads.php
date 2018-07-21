<?php
/***************************************************************************
 *                               downloads.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/



//=================================================
// Print the Downloads Table
//=================================================
function printDownloadsTable( $clientID = "", $allowModification = 1 ) {
	global $ftsdb, $clmsMenus, $mbp_config;

	// Prep our IN clause data
	$preparedInClause = $ftsdb->prepareInClauseVariable( getMyClientIDs() );
	$selectBindData   = $preparedInClause['data'];
	$selectBindData   = array_merge( $selectBindData, array(
		":clientID" => $clientID
	) );

	$extraSQL = ( $clientID != "" ) ? " AND c.id = :clientID" : " AND c.id IN (" . $preparedInClause['binds'] . ")";

	$result = $ftsdb->select( "`" . DBTABLEPREFIX . "downloads` d, `" . DBTABLEPREFIX . "clients` c", "c.id = d.client_id" . $extraSQL . " ORDER BY c.last_name, d.name ASC", $selectBindData, 'd.*' );

	// Create our new table
	$table = new tableClass( '', '', '', "table table-striped table-bordered tablesorter", "downloadsTable" );

	// Create table title
	$table->addNewRow( array(
		array(
			'data'    => '<i class="glyphicon glyphicon-download"></i> ' . __( 'Downloads' ),
			"colspan" => "4"
		)
	), '', 'title1', 'thead' );

	// Create column headers
	$headerNameArray = array(
		array( 'type' => 'th', 'data' => "File" ),
		array( 'type' => 'th', 'data' => "Serial Number" ),
		array( 'type' => 'th', 'data' => "Uploaded On" )
	);

	if ( $allowModification == 1 ) {
		array_push( $headerNameArray, array( 'type' => 'th', 'data' => "" ) );
	}

	$table->addNewRow( $headerNameArray, '', 'title2', 'thead' );

	// Add our data
	if ( ! $result ) {
		$table->addNewRow( array(
			array(
				'data'    => "There are no downloads for this client.",
				"colspan" => "4"
			)
		), "downloadsTableDefaultRow", "greenRow" );
	} else {
		foreach ( $result as $row ) {
			$finalColumn = ( user_access( 'clms_downloads_delete' ) ) ? createDeleteLinkWithImage( $row['id'], $row['id'] . "_row", "downloads", "download" ) : "";

			// Build our final column array
			$rowDataArray = array(
				array( 'data' => '<a href="' . $row['url'] . '">' . $row['name'] . '</a>' ),
				array( 'data' => '<div id="edit-downloads-' . $row['id'] . '_serial_number">' . $row['serial_number'] . '</div>' ),
				array( 'data' => makeDateTime( $row['datetimestamp'] ) )
			);

			if ( $allowModification == 1 ) {
				array_push( $rowDataArray, array( 'data' => $finalColumn, 'class' => 'center' ) );
			}

			$table->addNewRow( $rowDataArray, $row['id'] . "_row", "" );
		}
		$result = null;
	}

	// Return the table's HTML
	return $table->returnTableHTML() . "
			<div id=\"downloadsTableUpdateNotice\"></div>";
}

//=================================================
// Returns the JQuery functions used to allow 
// in-place editing and table sorting
//=================================================
function returnDownloadsTableJQuery( $clientID = "", $allowModification = 1 ) {
	global $ftsdb, $clmsMenus, $mbp_config;

	$extraJQueryReadyScripts = ( $allowModification == 1 ) ? ", headers: { 3: { sorter: false } }" : "";

	$JQueryReadyScripts = "
			$('#downloadsTable').tablesorter({ widgets: ['zebra']" . $extraJQueryReadyScripts . " });";

	// Only allow modification of rows if we have permission
	if ( $allowModification == 1 && user_access( 'clms_downloads_edit' ) ) {
		$JQueryReadyScripts = "
			var fields = $(\"#downloadsTable div[id^='edit-downloads-']\").map(function() { return this.id; }).get();
			addEditable( fields );";
	}

	return $JQueryReadyScripts;
}

//=================================================
// Create a form to add new orders
//=================================================
function printNewDownloadForm( $clientID = "" ) {
	global $clmsMenus, $mbp_config;

	if ( ! empty( $clientID ) ) {
		$clientIDField = array(
			'type'  => 'hidden',
			'value' => $clientID
		);
	} else {
		$clientIDField = array(
			'text'    => 'Client',
			'type'    => 'select',
			'options' => getDropdownArray( 'clients' ),
		);
	}

	$formFields = apply_filters( 'form_fields_clms_downloads_new', array(
		'client_id'        => $clientIDField,
		'uplodedFilesName' => array(
			'type'  => 'hidden',
			'value' => ''
		),
		'name'             => array(
			'text'  => 'Download Name',
			'type'  => 'text',
			'class' => 'required',
		),
		'file'             => array(
			'text' => 'Upload File',
			'type' => 'file',
		),
		array(
			'type'  => 'html',
			'value' => '-or-',
		),
		'url'              => array(
			'text' => 'File URL',
			'type' => 'text',
		),
		'serial_number'    => array(
			'text' => 'Serial Number',
			'type' => 'text',
		),
	) );

	return makeForm( 'newDownload', il( $clmsMenus['DOWNLOADS']['link'] ), 'New Download', 'Create Download', $formFields, array(), 1 );
}

//=================================================
// Returns the JQuery functions used to run the 
// new download form
//=================================================
function returnNewDownloadFormJQuery( $reprintTable = 0, $allowModification = 1 ) {
	$timestamp = time();

	$table = ( $reprintTable == 0 ) ? '' : 'downloadsTable';

	$JQueryReadyScripts = "
		$(\"#file\").uploadify({
			'formData'     : {
				'timestamp' : '" . $timestamp . "',
				'token'     : '" . md5( 'unique_salt' . $timestamp ) . "'
			},
			'swf'            : '" . SITE_URL . "/themes/jquery/uploadify/uploadify.swf',
			'uploader'       : '" . SITE_URL . "/uploadify.php',
			'auto'           : true,
			'onUploadSuccess' : function(file, data, response) {
				$('#uplodedFilesName').val(data);
			}
		});
		" . makeFormJQuery( 'newDownload', SITE_URL . "/ajax.php?action=createDownload&reprinttable=" . $reprintTable . "&showButtons=" . $allowModification, $table, 'download', '', '', 1 );

	return $JQueryReadyScripts;
}