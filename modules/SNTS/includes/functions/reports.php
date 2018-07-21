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
// Print the Serials Report
//=================================================
function printSerialsReport( $startDatetimestamp = "", $stopDatetimestamp = "", $titleSuffix = "" ) {
	global $ftsdb;

	$extraSQL = ( $startDatetimestamp == "" || $stopDatetimestamp == "" ) ? "1" : "datetimestamp >= :startDatetimestamp AND datetimestamp < :stopDatetimestamp";
	$result   = $ftsdb->select( DBTABLEPREFIX . "serials", $extraSQL . " ORDER BY serial DESC", array(
		":startDatetimestamp" => $startDatetimestamp,
		":stopDatetimestamp"  => $stopDatetimestamp
	) );

	// Create our new table
	$table = new tableClass( '', '', '', "table table-striped table-bordered tablesorter", "serialsReportTable" );

	// Create table title
	$table->addNewRow( array( array( 'data' => 'Serials', "colspan" => "9" ) ), '', 'title1', 'thead' );

	// Create column headers
	$table->addNewRow(
		array(
			array( 'type' => 'th', 'data' => "Serial Number" ),
			array( 'type' => 'th', 'data' => "Category" ),
			array( 'type' => 'th', 'data' => "Location" ),
			array( 'type' => 'th', 'data' => "Registered To" ),
			array( 'type' => 'th', 'data' => "Added By" ),
			array( 'type' => 'th', 'data' => "Added On" ),
			array( 'type' => 'th', 'data' => "Expires" ),
		), '', 'title2', 'thead'
	);

	// Add our data
	if ( ! $result ) {
		$table->addNewRow( array(
			array(
				'data' => "There are no serials in the system.",
				"colspan" => "9"
			)
		), "serialsReportTableDefaultRow", "greenRow" );
	} else {
		foreach ( $result as $row ) {
			$date = ( is_numeric( $row['datetimestamp'] ) ) ? makeShortDate( $row['datetimestamp'], 0 ) : $row['datetimestamp'];
			$table->addNewRow(
				array(
					array( 'data' => "<div id=\"" . $row['id'] . "_serial\">" . $row['serial'] . "</div>" ),
					array( 'data' => getCatNameByID( $row['cat_id'] ) ),
					array( 'data' => "<div id=\"" . $row['id'] . "_location\">" . $row['location'] . "</div>" ),
					array( 'data' => ( ( isModuleActivated( 'CLMS' ) ) ? getClientNameFromID( $row['client_id'] ) : "<div id=\"" . $row['id'] . "_owner\">" . $row['owner'] . "</div>" ) ),
					array( 'data' => "<div id=\"" . $row['id'] . "_added_by\">" . $row['added_by'] . "</div>" ),
					array( 'data' => $date ),
					array( 'data' => makeShortDate( $row['expires'], 0 ) )
				), $row['id'] . "_row"
			);
		}
		$result = null;
	}

	// Return the table's HTML
	return $table->returnTableHTML();
}

//=================================================
// Returns the JQuery functions used to run the 
// Serials report
//=================================================
function returnSerialsReportJQuery() {
	$JQueryReadyScripts = "
			$('#serialsReportTable').tablesorter({ widgets: ['zebra'] });";

	return $JQueryReadyScripts;
}