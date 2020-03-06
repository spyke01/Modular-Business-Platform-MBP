<?php
/***************************************************************************
 *                               notes.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/



//=================================================
// Print the Client Notes Table
//=================================================
function printNotesTable( $clientID = "", $allowModification = 1 ) {
	global $ftsdb, $clmsMenus, $mbp_config;

	// Prep our IN clause data
	$preparedInClause = $ftsdb->prepareInClauseVariable( getMyClientIDs() );
	$selectBindData   = $preparedInClause['data'];
	$selectBindData   = array_merge( $selectBindData, array(
		":client_id" => $clientID
	) );

	$extraSQL = ( $clientID != "" ) ? "client_id = :client_id" : "client_id IN (" . $preparedInClause['binds'] . ")";

	$result = $ftsdb->select( DBTABLEPREFIX . "notes", $extraSQL . " ORDER BY datetimestamp DESC", $selectBindData );

	//echo $sql;

	// Create our new table
	$table = new Table( '', '', '', "table table-striped table-bordered tablesorter", "notesTable" );

	// Create table title
	$table->addNewRow( array(
		array(
			'data'    => '<i class="glyphicons glyphicons-notes"></i> ' . __( 'Notes' ),
			"colspan" => "3"
		)
	), '', 'title1', 'thead' );

	// Create column headers
	$headerNameArray = array(
		array( 'type' => 'th', 'data' => "Note" ),
		array( 'type' => 'th', 'data' => "Noted On" )
	);

	if ( $allowModification == 1 ) {
		array_push( $headerNameArray, array( 'type' => 'th', 'data' => "" ) );
	}

	$table->addNewRow( $headerNameArray, '', 'title2', 'thead' );

	// Add our data
	if ( ! $result ) {
		$table->addNewRow( array(
			array(
				'data'    => "There are no notes for this client.",
				"colspan" => "3"
			)
		), "notesTableDefaultRow", "greenRow" );
	} else {
		foreach ( $result as $row ) {
			$rowColor    = ( $row['urgency'] != LOW ) ? "redRow" : "greenRow";
			$rowColor    = ( $row['urgency'] != HIGH && $rowColor == "redRow" ) ? "yellowRow" : $rowColor;
			$finalColumn = ( user_access( 'clms_notes_delete' ) ) ? createDeleteLinkWithImage( $row['id'], $row['id'] . "_row", "notes", "note" ) : "";

			// Build our final column array
			$rowDataArray = array(
				array( 'data' => '<div id="edit-notes-' . $row['id'] . '_note">' . bbcode( $row['note'] ) . '</div>' ),
				array( 'data' => makeDateTime( $row['datetimestamp'] ) )
			);

			if ( $allowModification == 1 ) {
				array_push( $rowDataArray, array( 'data' => $finalColumn, 'class' => 'center' ) );
			}

			$table->addNewRow( $rowDataArray, $row['id'] . "_row", $rowColor );
		}
		$result = null;
	}

	// Return the table's HTML
	return $table->returnTableHTML() . "
			<div id=\"notesTableUpdateNotice\"></div>";
}

//=================================================
// Returns the JQuery functions used to allow 
// in-place editing and table sorting
//=================================================
function returnNotesTableJQuery( $clientID = "", $allowModification = 1 ) {
	global $ftsdb, $clmsMenus, $mbp_config;

	$extraJQueryReadyScripts = ( $allowModification == 1 ) ? " headers: { 2: { sorter: false } }" : "";

	$JQueryReadyScripts = "
			$('#notesTable').tablesorter({
				" . $extraJQueryReadyScripts . "
			});";

	// Only allow modification of rows if we have permission
	if ( $allowModification == 1 && user_access( 'clms_notes_edit' ) ) {
		$JQueryReadyScripts = "
			var fields = $(\"#notesTable div[id^='edit-notes-']\").map(function() { return this.id; }).get();
			options = {
				type: 'textarea',
				loadurl: SITE_URL + '/ajax.php?action=getitem&table=notes&item=note'
			};
			addEditable( fields, options );";
	}

	return $JQueryReadyScripts;
}

//=================================================
// Create a form to add new notes
//=================================================
function printNewNoteForm( $clientID = "" ) {
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

	$formFields = apply_filters( 'form_fields_clms_notes_new', array(
		'client_id' => $clientIDField,
		'note'      => array(
			'text'  => 'Note',
			'type'  => 'textarea',
			'class' => 'required',
		),
		'urgency'   => array(
			'text'    => 'Urgency',
			'type'    => 'select',
			'options' => getDropdownArray( 'urgency' ),
		),
	) );

	return makeForm( 'newNote', il( $clmsMenus['NOTES']['link'] ), 'Add a Note', 'Make the Note!', $formFields, array(), 1 );
}

//=================================================
// Returns the JQuery functions used to run the 
// new note form
//=================================================
function returnNewNoteFormJQuery( $reprintTable = 0, $allowModification = 1 ) {
	$table = ( $reprintTable == 0 ) ? '' : 'notesTable';
	$url   = SITE_URL . "/ajax.php?action=createNote&reprinttable=" . $reprintTable . "&showButtons=" . $allowModification;

	return makeFormJQuery( 'newNote', $url, $table, 'note', '', '', '', 1 );
}