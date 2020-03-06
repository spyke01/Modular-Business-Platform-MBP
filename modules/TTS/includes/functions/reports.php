<?php
/***************************************************************************
 *                               reports.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/



//=================================================
// Print the Tickets Report
//=================================================
function printTicketsReport( $startDatetimestamp = "", $stopDatetimestamp = "", $titleSuffix = "" ) {
	global $ftsdb, $LANG;

	$extraSQL = ( $startDatetimestamp == "" || $stopDatetimestamp == "" ) ? "1" : "datetimestamp >= :startDatetimestamp AND datetimestamp < :stopDatetimestamp";
	$result   = $ftsdb->select( DBTABLEPREFIX . "tickets", $extraSQL . " ORDER BY datetimestamp DESC", array(
		":startDatetimestamp" => $startDatetimestamp,
		":stopDatetimestamp"  => $stopDatetimestamp,
	) );

	// Create our new table
	$table = new Table( '', '', '', "table table-striped table-bordered tablesorter", "ticketsReportTable" );

	// Create table title
	$table->addNewRow( array(
		array(
			'data'    => $LANG['TABLETITLES_TICKETS'] . $titleSuffix,
			"colspan" => "9"
		)
	), '', 'title1', 'thead' );

	// Create column headers
	$table->addNewRow(
		array(
			array( 'type' => 'th', 'data' => $LANG['TABLEHEADERS_ID'] ),
			array( 'type' => 'th', 'data' => $LANG['TABLEHEADERS_TITLE'] ),
			array(
				'type' => 'th',
				'data' => ( ( isModuleActivated( 'CLMS' ) ) ? 'Client' : $LANG['TABLEHEADERS_USER'] )
			),
			array( 'type' => 'th', 'data' => $LANG['TABLEHEADERS_PROBLEM_CATEGORY'] ),
			array( 'type' => 'th', 'data' => $LANG['TABLEHEADERS_TECHNICIAN'] ),
			array( 'type' => 'th', 'data' => $LANG['TABLEHEADERS_DATE_CREATED'] ),
			array( 'type' => 'th', 'data' => $LANG['TABLEHEADERS_STATUS'] )
		), '', 'title2', 'thead'
	);

	// Add our data
	if ( ! $result ) {
		$table->addNewRow( array(
			array(
				'data'    => $LANG['ERROR_NO_TICKETS'],
				"colspan" => "9"
			)
		), "ticketsReportTableDefaultRow", "greenRow" );
	} else {
		foreach ( $result as $row ) {
			$rowColor = ( $row['status'] == 0 ) ? "greenRow" : "redRow";
			$rowColor = ( $row['status'] == 2 ) ? "yellowRow" : $rowColor;

			$table->addNewRow(
				array(
					array( 'data' => $row['id'] ),
					array( 'data' => "<a href=\"" . $ttsMenus['VIEWTICKET']['link'] . "&id=" . $row['id'] . "\">" . $row['title'] . "</a>" ),
					array( 'data' => ( ( isModuleActivated( 'CLMS' ) ) ? getClientNameFromID( $row['client_id'] ) : getUsernameFromID( $row['user_id'] ) ) ),
					array( 'data' => getCatNameByID( $row['cat_id'] ) ),
					array( 'data' => getUsernameFromID( $row['tech_id'] ) ),
					array( 'data' => makeShortDateTime( $row['datetimestamp'] ) ),
					array( 'data' => getTicketStatus( $row['status'] ) )
				), $row['id'] . "_row", $rowColor
			);
		}
		$result = null;
	}

	// Return the table's HTML
	return $table->returnTableHTML();
}

//=================================================
// Returns the JQuery functions used to run the 
// Invoices report
//=================================================
function returnTicketsReportJQuery() {
	$JQueryReadyScripts = "
			$('#ticketsReportTable').tablesorter({ widgets: ['zebra'] });";

	return $JQueryReadyScripts;
}

//=================================================
// Print the Ticket Entries Report
//=================================================
function printTicketEntriesReport( $startDatetimestamp = "", $stopDatetimestamp = "", $titleSuffix = "" ) {
	global $ftsdb, $LANG;
	$ticketID = "";
	$x        = 1;

	$extraSQL = ( $startDatetimestamp == "" || $stopDatetimestamp == "" ) ? "1" : "datetimestamp >= :startDatetimestamp AND datetimestamp < :stopDatetimestamp";
	$result   = $ftsdb->select( DBTABLEPREFIX . "entries", $extraSQL . " ORDER BY datetimestamp DESC, ticket_id", array(
		":startDatetimestamp" => $startDatetimestamp,
		":stopDatetimestamp"  => $stopDatetimestamp,
	) );

	// Create our new table
	$table = new Table( '', '', '', "table table-striped table-bordered tablesorter", "ticketEntriesReportTable" );

	// Create table title
	$table->addNewRow( array(
		array(
			'data'    => $LANG['TABLETITLES_TICKET_ENTRIES'] . $titleSuffix,
			"colspan" => "3"
		)
	), '', 'title1', 'thead' );

	// Create column headers
	$table->addNewRow(
		array(
			array( 'type' => 'th', 'data' => $LANG['TABLEHEADERS_DATE_CREATED'] ),
			array( 'type' => 'th', 'data' => $LANG['TABLEHEADERS_USER'] ),
			array( 'type' => 'th', 'data' => $LANG['TABLEHEADERS_ENTRY'] )
		), "", "title2 noWrap", "thead"
	);

	// Add our data
	if ( ! $result ) {
		$table->addNewRow( array(
			array(
				'data'    => $LANG['ERROR_NO_TICKET_ENTRIES'],
				"colspan" => "3"
			)
		), "ticketEntriesReportTableDefaultRow", "greenRow" );
	} else {
		foreach ( $result as $row ) {
			if ( $ticketID != $row['ticket_id'] ) {
				$status   = getTicketStatus( $row['ticket_id'] );
				$rowColor = ( $status == 0 ) ? "greenRow" : "redRow";
				$rowColor = ( $status == 2 ) ? "yellowRow" : $rowColor;

				$table->addNewRow( array(
					array(
						'type'    => 'th',
						'data'    => getTicketTitleFromID( $row['ticket_id'] ),
						"colspan" => "3"
					)
				), "ticketEntriesReportTableDefaultRow", $rowColor );
				$x = 1;
			}

			$table->addNewRow( array(
				array( 'data' => makeShortDateTime( $row['datetimestamp'] ) ),
				array( 'data' => ( ( $row['is_client'] ) ? getClientNameFromID( $row['user_id'] ) : getUsernameFromID( $row['user_id'] ) ) ),
				array( 'data' => bbcode( $row['text'] ) )
			), "", "row" . $x );

			$x        = ( $x == 1 ) ? 2 : 1;
			$ticketID = $row['ticket_id'];
		}
		$result = null;
	}

	// Return the table's HTML
	return $table->returnTableHTML();
}

//=================================================
// Returns the JQuery functions used to run the 
// Ticket Entries report
//=================================================
function returnTicketEntriesReportJQuery() {
	$JQueryReadyScripts = "";

	return $JQueryReadyScripts;
}