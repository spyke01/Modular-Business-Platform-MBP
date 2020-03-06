<?php
/***************************************************************************
 *                               versions.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/



//=================================================
// Print the Call Home Table
//=================================================
function printCallHomeTable() {
	global $ftsdb, $ftsMenus, $mbp_config;

	$result = $ftsdb->select( DBTABLEPREFIX . 'callhome', "1 ORDER BY application, location" );

	// Create our new table
	$table = new Table( '', '', '', "table table-striped table-bordered tablesorter callHomeTable", "callHomeTable" );

	// Create table title
	$table->addNewRow( array( array( 'data' => 'CallHome', "colspan" => "7" ) ), '', 'title1', 'thead' );

	// Create column headers
	$headerNameArray = array(
		array( 'type' => 'th', 'data' => "App" ),
		array( 'type' => 'th', 'data' => "Version" ),
		array( 'type' => 'th', 'data' => "License" ),
		array( 'type' => 'th', 'data' => "Licensed To" ),
		array( 'type' => 'th', 'data' => "Location" ),
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
				'data'    => "There are no call backs stored in the system.",
				"colspan" => "7"
			)
		), "callHomeTableDefaultRow", "greenRow" );
	} else {
		foreach ( $result as $row ) {
			$finalColumn = ( user_access( 'fts_callhome_delete' ) ) ? createDeleteLinkWithImage( $row['id'], $row['id'] . "_row", "callhome", "call back" ) : "";

			// Build our final column array
			$rowDataArray = array(
				array( 'data' => "<div id=\"" . $row['id'] . "_application\">" . $row['application'] . "</div>" ),
				array( 'data' => "<div id=\"" . $row['id'] . "_version\">" . $row['version'] . "</div>" ),
				array( 'data' => "<div id=\"" . $row['id'] . "_license\">" . $row['license'] . "</div>" ),
				array( 'data' => "<div id=\"" . $row['id'] . "_licensedto\">" . $row['licensedto'] . "</div>" ),
				array( 'data' => "<div id=\"" . $row['id'] . "_location\">" . $row['location'] . "</div>" ),
				array( 'data' => $finalColumn, 'class' => 'center' )
			);

			$table->addNewRow( $rowDataArray, $row['id'] . "_row", "" );
		}
		$result = null;
	}

	// Return the table's HTML
	return $table->returnTableHTML() . "
			<div id=\"callHomeTableUpdateNotice\"></div>";
}

//=================================================
// Returns the JQuery functions used to allow 
// in-place editing and table sorting
//=================================================
function returnCallHomeTableJQuery( $clientID = "" ) {
	global $ftsMenus, $mbp_config;

	$JQueryReadyScripts = "
			$('.callHomeTable').tablesorter({ widgets: ['zebra'], headers: { 3: { sorter: false } } });";

	return $JQueryReadyScripts;
}

//=================================================
// Print the Versions Table
//=================================================
function printVersionsTable() {
	global $ftsdb, $ftsMenus, $mbp_config;

	$result = $ftsdb->select( DBTABLEPREFIX . 'versions', "1 ORDER BY app, type" );

	// Create our new table
	$table = new Table( '', '', '', "table table-striped table-bordered tablesorter versionsTable", "versionsTable" );

	// Create table title
	$table->addNewRow( array( array( 'data' => 'Versions', "colspan" => "7" ) ), '', 'title1', 'thead' );

	// Create column headers
	$headerNameArray = array(
		array( 'type' => 'th', 'data' => "App" ),
		array( 'type' => 'th', 'data' => "Type" ),
		array( 'type' => 'th', 'data' => "Version" ),
		array( 'type' => 'th', 'data' => "Update URL" ),
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
				'data'    => "There are no versions in the system.",
				"colspan" => "7"
			)
		), "versionsTableDefaultRow", "greenRow" );
	} else {
		foreach ( $result as $row ) {
			$finalColumn = ( user_access( 'fts_versions_edit' ) ) ? "<a href=\"" . $ftsMenus['VERSIONS']['link'] . "&action=editversion&id=" . $row['id'] . "\" class=\"btn btn-default\"><i class=\"glyphicon glyphicon-edit\"></i></a> " : "";
			$finalColumn .= ( user_access( 'fts_versions_delete' ) ) ? createDeleteLinkWithImage( $row['id'], $row['id'] . "_row", "versions", "version" ) : "";

			// Build our final column array
			$rowDataArray = array(
				array( 'data' => '<div id="edit-versions-' . $row['id'] . '_app">' . $row['app'] . '</div>' ),
				array( 'data' => ( ( $row['type'] == 0 ) ? 'Free' : 'Professional' ) ),
				array( 'data' => '<div id="edit-versions-' . $row['id'] . '_version">' . $row['version'] . '</div>' ),
				array( 'data' => '<div id="edit-versions-' . $row['id'] . '_update_url">' . $row['update_url'] . '</div>' ),
				array( 'data' => '<span class="btn-group">' . $finalColumn . '</span>', 'class' => 'center' )
			);

			$table->addNewRow( $rowDataArray, $row['id'] . "_row", "" );
		}
		$result = null;
	}

	// Return the table's HTML
	return $table->returnTableHTML() . "
			<div id=\"versionsTableUpdateNotice\"></div>";
}

//=================================================
// Returns the JQuery functions used to allow 
// in-place editing and table sorting
//=================================================
function returnVersionsTableJQuery( $clientID = "" ) {
	global $ftsdb, $ftsMenus, $mbp_config;

	$JQueryReadyScripts = "
			$('.versionsTable').tablesorter({ widgets: ['zebra'], headers: { 3: { sorter: false } } });";

	// Only allow modification of rows if we have permission
	if ( user_access( 'fts_versions_edit' ) ) {
		$JQueryReadyScripts = "
			var fields = $(\"#versionsTable div[id^='edit-versions']\").map(function() { return this.id; }).get();
			addEditable( fields );";
	}

	return $JQueryReadyScripts;
}

//=================================================
// Create a form to add new versions
//=================================================
function printNewVersionForm( $clientID = "" ) {
	global $ftsMenus, $mbp_config;

	$formFields = apply_filters( 'form_fields_fts_versions_new', array(
		'app'        => array(
			'text'  => 'App',
			'type'  => 'text',
			'class' => 'required',
		),
		'type'       => array(
			'text'    => 'Type',
			'type'    => 'select',
			'options' => getDropdownArray( 'versiontypes' ),
		),
		'version'    => array(
			'text' => 'Version Number',
			'type' => 'text',
		),
		'update_url' => array(
			'text' => 'Update URL',
			'type' => 'text',
		),
	) );

	return makeForm( 'newVersion', il( $ftsMenus['VERSIONS']['link'] ), 'New Version', 'Create Version', $formFields, array(), 1 );
}

//=================================================
// Returns the JQuery functions used to run the 
// new version form
//=================================================
function returnNewVersionFormJQuery( $reprintTable = 0 ) {
	$table = ( $reprintTable == 0 ) ? '' : 'versionsTable';
	$url   = SITE_URL . "/ajax.php?action=createVersion&reprinttable=" . $reprintTable . "&showButtons=" . $allowModification;

	return makeFormJQuery( 'newVersion', $url, $table, 'version' );
}

//=================================================
// Create a form to edit a version
//=================================================
function printEditVersionForm( $versionID ) {
	global $ftsdb, $ftsMenus, $mbp_config;

	$result = $ftsdb->select( DBTABLEPREFIX . 'versions', "id = :id LIMIT 1", array(
		":id" => $versionID
	) );

	if ( $result && count( $result ) == 0 ) {
		$page_content = "<span class=\"center\">There was an error while accessing the version's details you are trying to update. You are being redirected to the main page.</span>
						<meta http-equiv=\"refresh\" content=\"5;url=" . $ftsMenus['VERSIONS']['link'] . "\">";
	} else {
		$row = $result[0];

		$formFields = apply_filters( 'form_fields_fts_versions_edit', array(
			'app'        => array(
				'text'  => 'App',
				'type'  => 'text',
				'class' => 'required',
			),
			'type'       => array(
				'text'    => 'Type',
				'type'    => 'select',
				'options' => getDropdownArray( 'versiontypes' ),
			),
			'version'    => array(
				'text' => 'Version Number',
				'type' => 'text',
			),
			'update_url' => array(
				'text' => 'Update URL',
				'type' => 'text',
			),
		) );

		return makeForm( 'editVersion', il( $ftsMenus['VERSIONS']['link'] . "&action=editversion&id=" . $versionID ), 'Edit Version', 'Update Version', $formFields, $row, 1 );

		$result = null;
	}

	return $content;
}

//=================================================
// Returns the JQuery functions used to run the 
// edit page form
//=================================================
function returnEditVersionFormJQuery( $versionID ) {
	$JQueryReadyScripts = "		
		$('#datetimestamp').datepicker({
			showButtonPanel: true
		});	";
	$JQueryReadyScripts .= makeFormJQuery( 'editVersion', SITE_URL . "/ajax.php?action=updateVersion&id=" . $versionID );

	return $JQueryReadyScripts;
}