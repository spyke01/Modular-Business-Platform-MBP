<?php
/***************************************************************************
 *                               seoClients.php
 *                            -------------------
 *   begin                : Saturday, Sept 18, 2013
 *   copyright            : (C) 2013 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/



//==================================================
// Returns an array of the website data
//==================================================
function getSEOClient( $clientID ) {
	return getDatabaseArray( 'seo_clients', $clientID );
}

//=================================================
// Returns the actual status text based on the status code
//=================================================
function returnSEOClientStatusText( $status ) {
	global $WEBSITE_STATUS;

	return $WEBSITE_STATUS[ $status ];
}

//=================================================
// Returns a stylized status label based on the status code
//=================================================
function returnSEOClientStatusLabel( $status ) {
	global $WEBSITE_STATUS, $WEBSITE_STATUS_COLOR;

	return '<span class="label ' . $WEBSITE_STATUS_COLOR[ $status ] . '">' . $WEBSITE_STATUS[ $status ] . '</span>';
}

//=================================================
// Returns the status code for a website
//=================================================
function getSEOClientStatus( $clientID ) {
	return getDatabaseItem( 'seo_clients', 'status', $clientID );
}

//=================================================
// Changes the status for a website
//=================================================
function updateSEOClientStatus( $clientID, $status ) {
	global $ftsdb;

	$result = $ftsdb->update( DBTABLEPREFIX . 'seo_clients', array(
		'status' => $status,
	),
		"id = :id", array(
			":id" => $clientID
		)
	);
}

//=================================================
// Adds a website to the removal queue
//=================================================
function deleteSEOClient( $clientID ) {
	global $ftsdb;

	$result = $ftsdb->delete( DBTABLEPREFIX . "seo_clients", 'id = :id', array(
			':id' => $clientID,
		)
	);
	$result = $ftsdb->delete( DBTABLEPREFIX . "seo_clients_tasks", 'client_id = :client_id', array(
			':client_id' => $clientID,
		)
	);

	return $log;
}

//==================================================
// Gets the total number of SEO Clients in the systen
//==================================================
function getSEOClientCount() {
	global $ftsdb;

	$totalSEOClients = 0;
	$results         = $ftsdb->select( DBTABLEPREFIX . "seo_clients", "", array(), 'COUNT(id) AS totalSEOClients' );
	if ( $results && count( $results ) > 0 ) {
		$totalSEOClients = $results[0]['totalSEOClients'];
	}
	$results = null;

	return $totalSEOClients;
}

//=========================================================
// Checks if a license allows for multiple SEO Clients
//=========================================================	
function canHaveMultipleSEOClients() {
	global $mbp_config;

	return ( empty( $mbp_config['ftsmbp_mySEO_serial'] ) || $mbp_config['ftsmbp_mySEO_serial'] == 'FREE_VERSION' ) ? 0 : 1;
}

//=================================================
// Print the SEO Clients Table
//=================================================
function printSEOClientsTable( $status = '' ) {
	global $ftsdb, $mySEOMenus, $mbp_config;

	// Prep our IN clause data
	$preparedInClause = $ftsdb->prepareInClauseVariable( $status );
	$selectBindData   = $preparedInClause['data'];
	$selectBindData   = array_merge( $selectBindData, array(
		':status' => $status,
	) );

	// Prep our WHERE clause data
	$extraSQL = "id > -1 AND status != 10 AND status != 11";
	$extraSQL .= ( ! empty( $status ) ) ? ' AND status IN (' . $preparedInClause['binds'] . ')' : '';

	$result = $ftsdb->select( DBTABLEPREFIX . "seo_clients", $extraSQL . " ORDER BY name", $selectBindData );

	// Create our new table
	$table = new Table( '', '', '', "table table-striped table-bordered tablesorter", "seoClientsTable" );

	// Create table title
	$table->addNewRow( array(
		array(
			'data'    => 'SEO Clients' . ' (' . count( $result ) . ')',
			"colspan" => "6"
		)
	), '', 'title1', 'thead' );

	// Create column headers
	$table->addNewRow(
		array(
			array( 'type' => 'th', 'data' => 'Name' ),
			array( 'type' => 'th', 'data' => 'URL' ),
			array( 'type' => 'th', 'data' => 'Status' ),
			array( 'type' => 'th', 'data' => 'Optimization Level' ),
			array( 'type' => 'th', 'data' => '' )
		), '', 'title2', 'thead'
	);

	// Add our data
	if ( ! $result ) {
		$table->addNewRow(
			array(
				array( 'data' => 'There are no clients in the system.', 'colspan' => '6' )
			), 'seoClientsTableDefaultRow', 'greenRow'
		);
	} else {
		foreach ( $result as $row ) {
			$finalColumn = '';

			// Only show these links if we aren't deleting the site
			if ( user_access( 'mySEO_seo_clients_tasks' ) ) {
				$finalColumn .= '<a href="' . $mySEOMenus['SEOCLIENTS']['link'] . '&amp;action=clientSEODashboard&amp;id=' . $row['id'] . '"  class="btn btn-default" title="Tasks"><i class="glyphicon glyphicon-tasks"></i></a> ';
			}
			if ( user_access( 'mySEO_reports_mySEOTasks_access' ) ) {
				$finalColumn .= '<a href="' . $mySEOMenus['VIEWSEOTASKSREPORT']['link'] . '&amp;id=' . $row['id'] . '"  class="btn btn-default" title="Reports"><i class="glyphicon glyphicon-save"></i></a> ';
			}
			if ( user_access( 'mySEO_seo_clients_edit' ) ) {
				$finalColumn .= '<a href="' . $mySEOMenus['SEOCLIENTS']['link'] . '&amp;action=editSEOClient&amp;id=' . $row['id'] . '"  class="btn btn-default" title="Edit"><i class="glyphicon glyphicon-edit"></i></a> ';
			}
			if ( user_access( 'mySEO_seo_clients_delete' ) ) {
				$finalColumn .= createDeleteLinkWithImage( $row['id'], $row['id'] . '_row', 'seo_clients', 'website' );
			}

			$table->addNewRow(
				array(
					array( 'data' => $row['name'] ),
					array( 'data' => $row['url'] ),
					array( 'data' => returnSEOClientStatusLabel( $row['status'] ) ),
					array( 'data' => getWebsiteOptimizationPointsProgressBar( $row['id'] ) ),
					array( 'data' => '<span class="btn-group">' . $finalColumn . '</span>', 'class' => 'center' ),
				), $row['id'] . '_row', ''
			);
		}
		$result = null;
	}

	// Return the table's HTML
	return $table->returnTableHTML() . '
			<div id="seoClientsTableUpdateNotice"></div>';
}

//=================================================
// Returns the JQuery functions used to run the 
// seo_clients table
//=================================================
function returnSEOClientsTableJQuery() {
	$JQueryReadyScripts = "
		$('#seoClientsTable').tablesorter({ widgets: ['zebra'], headers: { 4: { sorter: false } } });";

	return $JQueryReadyScripts;
}

//=================================================
// Create a form to edit SEO Clients
//=================================================
function printEditSEOClientForm( $clientID ) {
	global $menuvar, $ftsdb;

	$result = $ftsdb->select( DBTABLEPREFIX . "seo_clients", "id = :id LIMIT 1", array(
		":id" => $clientID
	) );

	if ( $result && count( $result ) == 0 ) {
		$page_content = "<span class=\"center\">There was an error while accessing the website's details you are trying to update. You are being redirected to the main page.</span>
						<meta http-equiv=\"refresh\" content=\"5;url=" . $menuvar['SEOCLIENTS'] . "\">";
	} else {
		$row = $result[0];

		$formFields = apply_filters( 'form_fields_website_website_edit', array(
			'name'          => array(
				'text'  => 'Name',
				'type'  => 'text',
				'class' => 'required',
			),
			'url'           => array(
				'text'  => 'Website URL',
				'type'  => 'text',
				'class' => 'required',
			),
			'email_address' => array(
				'text' => 'Email Address',
				'type' => 'text',
			),
			'phone'         => array(
				'text' => 'Phone Number',
				'type' => 'text',
			),
		) );

		$content = makeForm( 'editSEOClient', il( $menuvar['SEOCLIENTS'] ), 'Edit SEO Client', 'Save Client', $formFields, $row, 1 );

		$result = null;
	}

	return $content;
}

//=================================================
// Returns the JQuery functions used to run the 
// edit SEO Client form
//=================================================
function returnEditSEOClientFormJQuery( $clientID ) {
	return makeFormJQuery( 'editSEOClient', SITE_URL . "/ajax.php?action=editSEOClient&id=" . $clientID );
}

//=================================================
// Create a form to add new SEO Clients
//=================================================
function printNewSEOClientForm() {
	global $menuvar;

	$formFields = apply_filters( 'form_fields_website_website_new', array(
		'name'          => array(
			'text'  => 'Name',
			'type'  => 'text',
			'class' => 'required',
		),
		'url'           => array(
			'text'  => 'Website URL',
			'type'  => 'text',
			'class' => 'required',
		),
		'email_address' => array(
			'text' => 'Email Address',
			'type' => 'text',
		),
		'phone'         => array(
			'text' => 'Phone Number',
			'type' => 'text',
		),
	) );

	return makeForm( 'newSEOClient', il( $menuvar['SEOCLIENTS'] ), 'New SEO Client', 'Create Client', $formFields, array(), 1 );
}

//=================================================
// Returns the JQuery functions used to run the 
// new SEO Client form
//=================================================
function returnNewSEOClientFormJQuery( $reprintTable = 0, $allowModification = 1 ) {
	$table = ( $reprintTable == 0 ) ? '' : 'seoClientsTable';

	return makeFormJQuery( 'newSEOClient', SITE_URL . "/ajax.php?action=createSEOClient&reprinttable=" . $reprintTable . "&showButtons=" . $allowModification, $table, 'client' );
}
