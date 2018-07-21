<?php
/***************************************************************************
 *                               graphs.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Paden Clayton
 *   email                : sales@fasttracksites.com
 *
 *
 ***************************************************************************/


global $ftsdb, $selectedGraph, $graphType, $daterange, $start_date, $stop_date, $currentTime, $startDate, $endDate, $graphSuffix, $graphType, $LANG;
$arrData = $labels = $datasets = array();

//===================================================		
// Fill our arrayData variable
//===================================================		
if ( $selectedGraph == "totalTickets" ) {
	# Return the chart data
	echo json_encode( array(
		'labels' => array( $LANG['TICKETS'] ),
		'data'   => array( (int) getTicketCount( $startDate, $endDate ) ),
	) );
} elseif ( $selectedGraph == "ticketsByStatus" ) {
	# Return the chart data
	echo json_encode( array(
		'labels' => array(
			$LANG['TICKETS'],
			$LANG['CLOSED'],
			$LANG['ON_HOLD']
		),
		'data'   => array(
			(int) getTicketCount( $startDate, $endDate, 0 ),
			(int) getTicketCount( $startDate, $endDate, 1 ),
			(int) getTicketCount( $startDate, $endDate, 2 ),
		),
	) );
} elseif ( $selectedGraph == "ticketsByProblemCategory" ) {
	// Labels
	$labels = array( 'Uncategorized' );

	// Data
	$data = array( 0 );

	$extraSQL = ( $startDatetimestamp == "" || $stopDatetimestamp == "" ) ? "1" : "t.datetimestamp >= :startDatetimestamp AND t.datetimestamp < :stopDatetimestamp";
	$result   = $ftsdb->select( "`" . DBTABLEPREFIX . "tickets` t LEFT JOIN `" . DBTABLEPREFIX . "categories` cat ON cat.id = t.cat_id", $extraSQL . " GROUP BY t.cat_id ORDER BY cat.name ASC", array(
		":startDatetimestamp" => $startDatetimestamp,
		":stopDatetimestamp"  => $stopDatetimestamp,
	), 'cat.name, COUNT(t.id) AS totalTickets' );
	//echo $sql . "<br />";

	if ( $result ) {
		foreach ( $result as $row ) {
			// Labels
			$labels[] = $row['name'];

			// Data
			$data[] = (int) $row['totalTickets'];
		}
		$result = null;
	}

	# Return the chart data
	echo json_encode( array(
		'labels' => $labels,
		'data'   => $data,
	) );
}