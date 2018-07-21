<?php
/***************************************************************************
 *                               serials.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/



//=================================================
// Print the Serials Table
//=================================================
function printSerialsTable( $clientID = "", $cat_id = "", $searchVars = array() ) {
	global $ftsdb, $sntsMenus, $mbp_config;
	$extraFields = $extraTables = $extraSQL = '';
	$tableTitle  = 'Serials';

	// List by category
	if ( ! empty( $cat_id ) ) {
		$tableTitle  = ( $cat_id == 'uncategorized' ) ? 'Uncategorized Serials' : getCatNameByID( $cat_id );
		$tableSuffix = ( $cat_id == 'uncategorized' ) ? '' : $cat_id;
		$extraSQL    .= ( $cat_id == 'uncategorized' ) ? " AND s.cat_id =''" : " AND s.cat_id = :cat_id";
	}

	if ( isModuleActivated( 'CLMS' ) && $mbp_config['ftsmbp_snts_useClientAsOwner'] ) {
		$extraFields = "";
		$extraTables = " LEFT JOIN `" . DBTABLEPREFIX . "clients` c ON c.id = s.client_id";
		$extraSQL    .= ( ! empty( $clientID ) ) ? " AND c.id = :clientID" : "";
	}

	// Handle searches
	$extraSQL .= ( ! empty( $searchVars['search_serial'] ) ) ? " AND serial LIKE :search_serial" : "";
	$extraSQL .= ( ! empty( $searchVars['search_location'] ) ) ? " AND location LIKE :search_location" : "";
	$extraSQL .= ( ! empty( $searchVars['search_added_by'] ) ) ? " AND first_name LIKE :search_added_by" : "";

	$result = $ftsdb->select( "`" . DBTABLEPREFIX . "serials` s" . $extraTables, "1" . $extraSQL . " ORDER BY s.serial", array(
		":cat_id"          => $cat_id,
		":clientID"        => $clientID,
		":search_serial"   => '%' . $searchVars['search_serial'] . '%',
		":search_location" => '%' . $searchVars['search_location'] . '%',
		":search_added_by" => '%' . $searchVars['search_added_by'] . '%',
	), 's.*' . $extraFields );

	// Create our new table
	$table = new tableClass( '', '', '', "table table-striped table-bordered tablesorter serialsTable", "serialsTable" . $tableSuffix );

	// Create table title
	$table->addNewRow( array( array( 'data' => $tableTitle, "colspan" => "8" ) ), '', 'title1', 'thead' );

	// Create column headers
	$headerNameArray = array(
		array( 'type' => 'th', 'data' => "Serial Number" ),
		array( 'type' => 'th', 'data' => "Category" ),
		array( 'type' => 'th', 'data' => "Location", 'class' => 'hidden-sm' ),
		array( 'type' => 'th', 'data' => "Registered To", 'class' => 'hidden-sm' ),
		array( 'type' => 'th', 'data' => "Added By", 'class' => 'visible-lg' ),
		array( 'type' => 'th', 'data' => "Added On", 'class' => 'visible-lg' ),
		array( 'type' => 'th', 'data' => "Expires", 'class' => 'visible-lg' ),
		array( 'type' => 'th', 'data' => "" )
	);

	if ( $allowModification == 1 ) {
		array_push( $headerNameArray, array( 'type' => 'th', 'data' => "" ) );
	}

	$table->addNewRow( $headerNameArray, '', 'title2', 'thead' );

	// Add our data
	if ( ! $result ) {
		$table->addNewRow( array(
			array(
				'data'    => "There are no serials in the system.",
				"colspan" => "8"
			)
		), "serialsTableDefaultRow" . $tableSuffix, "greenRow" );
	} else {
		foreach ( $result as $row ) {
			$finalColumn = ( user_access( 'snts_serials_edit' ) ) ? "<a href=\"" . $sntsMenus['SERIALS']['link'] . "&action=editserial&id=" . $row['id'] . "\" class=\"btn btn-default\"><i class=\"glyphicon glyphicon-edit\"></i></a> " : "";
			$finalColumn .= ( user_access( 'snts_serials_delete' ) ) ? createDeleteLinkWithImage( $row['id'], $row['id'] . "_row", "serials", "serial" ) : "";

			// Build our final column array
			$date         = ( is_numeric( $row['datetimestamp'] ) ) ? makeShortDate( $row['datetimestamp'], 0 ) : $row['datetimestamp'];
			$rowDataArray = array(
				array( 'data' => '<div id="edit-serials-' . $row['id'] . '_serial">' . $row['serial'] . '</div>' ),
				array( 'data' => getCatNameByID( $row['cat_id'] ) ),
				array(
					'data'  => '<div id="edit-serials-' . $row['id'] . '_location">' . $row['location'] . '</div>',
					'class' => 'hidden-sm'
				),
				array(
					'data'  => ( ( isModuleActivated( 'CLMS' ) && $mbp_config['ftsmbp_snts_useClientAsOwner'] ) ? getClientNameFromID( $row['client_id'] ) : '<div id="edit-serials-' . $row['id'] . '_owner">' . nl2br( $row['owner'] ) . '</div>' ),
					'class' => 'hidden-sm'
				),
				array(
					'data'  => '<div id="edit-serials-' . $row['id'] . '_added_by">' . $row['added_by'] . '</div>',
					'class' => 'visible-lg'
				),
				array( 'data' => $date, 'class' => 'visible-lg' ),
				array( 'data' => makeShortDate( $row['expires'], 0 ), 'class' => 'visible-lg' ),
				array( 'data' => '<span class="btn-group">' . $finalColumn . '</span>', 'class' => 'center' )
			);

			$table->addNewRow( $rowDataArray, $row['id'] . "_row", "" );
		}
		$result = null;
	}

	// Return the table's HTML
	return $table->returnTableHTML() . "
			<div id=\"serialsTableUpdateNotice" . $tableSuffix . "\"></div>";
}

//=================================================
// Returns the JQuery functions used to allow 
// in-place editing and table sorting
//=================================================
function returnSerialsTableJQuery( $clientID = "" ) {
	global $ftsdb, $sntsMenus, $mbp_config;

	$JQueryReadyScripts = "
			$('.serialsTable').tablesorter({ widgets: ['zebra'], headers: { 5: { sorter: false } } });";

	// Only allow modification of rows if we have permission

	// Only allow modification of rows if we have permission
	if ( user_access( 'snts_serials_edit' ) ) {
		$JQueryReadyScripts = "
			var fields = $(\"#updateMeSerials div[id^='edit-serials-']\").map(function() { return this.id; }).get();
			addEditable( fields );";
	}

	return $JQueryReadyScripts;
}

//=================================================
// Print the Serials Tables
//=================================================
function printSerialsTables( $clientID = "", $searchVars = array() ) {
	global $ftsdb, $sntsMenus, $mbp_config;
	$returnVar = printSerialsTable( $clientID, 'uncategorized' ) . "<br />";

	$result = $ftsdb->select( DBTABLEPREFIX . "categories", "type='6' ORDER BY name" );

	// Add our data
	if ( $result ) {
		foreach ( $result as $row ) {
			$returnVar .= printSerialsTable( $clientID, $row['id'], $searchVars ) . "<br />";
		}
		$result = null;
	}

	// Return the table's HTML
	return $returnVar;
}

//=================================================
// Print the Serials Table
//=================================================
function printSearchSerialsForm( $searchVars ) {
	global $sntsMenus, $mbp_config;

	$formFields = apply_filters( 'form_fields_snts_serials_search', array(
		array(
			'type'  => 'html',
			'value' => '<strong>Choose any or all of the following to search by.</strong>',
		),
		'search_serial'   => array(
			'text'  => 'Serial Number',
			'type'  => 'text',
			'class' => 'required',
			'value' => keeptasafe( $searchVars['search_serial'] ),
		),
		'search_location' => array(
			'text'  => 'Location',
			'type'  => 'text',
			'value' => keeptasafe( $searchVars['search_location'] ),
		),
		'search_added_by' => array(
			'text'  => 'Added By',
			'type'  => 'text',
			'value' => keeptasafe( $searchVars['search_added_by'] ),
		),
	) );

	return makeForm( 'searchSerials', il( $sntsMenus['SERIALS']['link'] ), 'Search Serials', 'Search!', $formFields, array(), 1 );
}

//=================================================
// Returns the JQuery functions used to run the 
// new order form
//=================================================
function returnSearchSerialsFormJQuery( $clientID = "" ) {
	$customSuccessFunction = "
		$('#updateMeSerials').html(data);
		" . returnSerialsTableJQuery( $clientID );

	return makeFormJQuery( 'searchSerials', SITE_URL . '/ajax.php?action=searchSerials', '', '', '', $customSuccessFunction );
}

//=================================================
// Create a form to add new serials
//=================================================
function printNewSerialForm( $clientID = "" ) {
	global $sntsMenus, $mbp_config;

	$currentDate = @gmdate( 'm/d/Y', time() + ( 3600 * $mbp_config['ftsmbp_time_zone'] ) );

	if ( isModuleActivated( 'CLMS' ) && $mbp_config['ftsmbp_snts_useClientAsOwner'] ) {
		$registeredToID   = 'client_id';
		$registeredToData = array(
			'text'         => 'Resgistered To',
			'type'         => 'select',
			'options'      => getDropdownArray( 'clients' ),
			'currentValue' => $clientID,
			'class'        => 'required',
		);
	} else {
		$registeredToID   = 'owner';
		$registeredToData = array(
			'text'  => 'Resgistered To',
			'type'  => 'textarea',
			'class' => 'required',
		);
	}

	$formFields = apply_filters( 'form_fields_snts_serials_new', array(
		'serial'        => array(
			'text'  => 'Serial Number',
			'type'  => 'text',
			'class' => 'required',
		),
		'cat_id'        => array(
			'text'    => 'Category',
			'type'    => 'select',
			'options' => getDropdownArray( 'serialcategories' ),
		),
		$registeredToID => $registeredToData,
		'location'      => array(
			'text' => 'Location',
			'type' => 'text',
		),
		'description'   => array(
			'text' => 'Description',
			'type' => 'textarea',
		),
		'added_by'      => array(
			'text' => 'Added By',
			'type' => 'text',
		),
		'datetimestamp' => array(
			'text'    => 'Added On',
			'type'    => 'text',
			'default' => $currentDate,
		),
		'expires'       => array(
			'text'    => 'Expires',
			'type'    => 'text',
			'default' => $currentDate,
		)
	) );

	return makeForm( 'newSerial', il( $sntsMenus['SERIALS']['link'] ), 'New Serial', 'Create Serial', $formFields, array(), 1 );
}

//=================================================
// Returns the JQuery functions used to run the 
// new serial form
//=================================================
function returnNewSerialFormJQuery( $reprintTable = 0 ) {
	$customSuccessFunction = ( $reprintTable == 0 ) ? "
					// Update the proper div with the returned data
					$('#newSerialResponse').html(data);
					$('#newSerialResponse').effect('highlight',{},500);"
		: "
					// Clear the default row
					$('#serialsTableDefaultRow' + $('#newSerialForm #cat_id').val()).remove();
					// Update the table with the new row
					$('#serialsTable' + $('#newSerialForm #cat_id').val() + ' > tbody:last').append(data);
					$('#serialsTableUpdateNotice' + $('#newSerialForm #cat_id').val()).html('" . tableUpdateNoticeHTML() . "');
					// Show a success message
					$('#newSerialResponse').html(returnSuccessMessage('serial'));";

	$JQueryReadyScripts = "
		$('#newSerialForm #datetimestamp, #newSerialForm #expires').datepicker({
			showButtonPanel: true
		});";

	$url                = SITE_URL . "/ajax.php?action=createSerial&reprinttable=" . $reprintTable;
	$JQueryReadyScripts .= makeFormJQuery( 'newSerial', $url, '', '', '', $customSuccessFunction, '', 1 );

	return $JQueryReadyScripts;
}

//=================================================
// Create a form to edit a serial
//=================================================
function printEditSerialForm( $serialID ) {
	global $ftsdb, $sntsMenus, $mbp_config;

	$result = $ftsdb->select( DBTABLEPREFIX . "serials", "id = :id LIMIT 1", array(
		":id" => $serialID
	) );

	if ( $result && count( $result ) == 0 ) {
		$page_content = "<span class=\"center\">There was an error while accessing the serial's details you are trying to update. You are being redirected to the main page.</span>
						<meta http-equiv=\"refresh\" content=\"5;url=" . $sntsMenus['SERIALS']['link'] . "\">";
	} else {
		$row = $result[0];

		if ( isModuleActivated( 'CLMS' ) && $mbp_config['ftsmbp_snts_useClientAsOwner'] ) {
			$registeredToID   = 'client_id';
			$registeredToData = array(
				'text'         => 'Resgistered To',
				'type'         => 'select',
				'options'      => getDropdownArray( 'clients' ),
				'currentValue' => $clientID,
				'class'        => 'required',
			);
		} else {
			$registeredToID   = 'owner';
			$registeredToData = array(
				'text'  => 'Resgistered To',
				'type'  => 'textarea',
				'class' => 'required',
			);
		}
		if ( is_numeric( $row['datetimestamp'] ) ) {
			$row['datetimestamp'] = makeShortDate( $row['datetimestamp'], 0 );
		}
		if ( is_numeric( $row['expires'] ) ) {
			$row['expires'] = makeShortDate( $row['expires'], 0 );
		}

		$formFields = apply_filters( 'form_fields_snts_serials_edit', array(
			'serial'        => array(
				'text'  => 'Serial Number',
				'type'  => 'text',
				'class' => 'required',
			),
			'cat_id'        => array(
				'text'    => 'Category',
				'type'    => 'select',
				'options' => getDropdownArray( 'serialcategories' ),
			),
			$registeredToID => $registeredToData,
			'location'      => array(
				'text' => 'Location',
				'type' => 'text',
			),
			'description'   => array(
				'text' => 'Description',
				'type' => 'textarea',
			),
			'added_by'      => array(
				'text' => 'Added By',
				'type' => 'text',
			),
			'datetimestamp' => array(
				'text' => 'Added On',
				'type' => 'text',
			),
			'expires'       => array(
				'text' => 'Expires',
				'type' => 'text',
			)
		) );

		return makeForm( 'editSerial', il( $sntsMenus['SERIALS']['link'] . "&action=editserial&id=" . $serialID ), 'Edit Serial', 'Update Serial', $formFields, $row, 1 );

		$result = null;
	}

	return $content;
}

//=================================================
// Returns the JQuery functions used to run the 
// edit page form
//=================================================
function returnEditSerialFormJQuery( $serialID ) {
	$JQueryReadyScripts = "		
		$('#datetimestamp, #expires').datepicker({
			showButtonPanel: true
		});";
	$JQueryReadyScripts .= makeFormJQuery( 'editSerial', SITE_URL . "/ajax.php?action=updateSerial&id=" . $serialID );

	return $JQueryReadyScripts;
}

//=================================================
// Create a form to import new serials
//=================================================
function printImportSerialsForm() {
	global $sntsMenus, $mbp_config;

	$formFields = apply_filters( 'form_fields_snts_serials_import', array(
		'templateLink' => array(
			'text'  => 'Upload Template File',
			'type'  => 'htmlWithLabel',
			'value' => '<div id="templateLink"><a href="' . SITE_URL . '/modules/SNTS/files/importTemplate.csv">Download</a></div>'
		),
		'importFile'   => array(
			'text'  => 'Upload File',
			'type'  => 'file',
			'class' => 'required',
		),
	) );

	return makeForm( 'importSerials', il( $sntsMenus['SERIALS']['link'] . "&action=importserials" ), '<i class="glyphicons glyphicons-file-import"></i> Import Serials', 'Import', $formFields, array(), 0, 0, 'multipart/form-data' );
}

//=================================================
// Returns the JQuery functions used to run the 
// import serials form
//=================================================
function returnImportSerialsFormJQuery() {
	return makeFormJQuery( 'importSerials' );
}