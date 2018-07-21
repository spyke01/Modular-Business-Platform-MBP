<?php
/***************************************************************************
 *                               graphs.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/



//=========================================================
// Gets the total number of tickets based on a date range
//=========================================================
function getTicketCount( $startDatetimestamp, $stopDatetimestamp, $ticketStatus = "" ) {
	global $ftsdb;

	$extraSQL = ( $startDatetimestamp == "" || $stopDatetimestamp == "" ) ? "" : " AND datetimestamp >= :startDatetimestamp AND datetimestamp < :stopDatetimestamp";
	$extraSQL .= ( ! is_numeric( $ticketStatus ) ) ? "" : " AND status = :ticketStatus";
	$result   = $ftsdb->select( DBTABLEPREFIX . "tickets", "1" . $extraSQL, array(
		":startDatetimestamp" => $startDatetimestamp,
		":stopDatetimestamp"  => $stopDatetimestamp,
		":ticketStatus"       => $ticketStatus
	), 'COUNT(id) AS totalTickets' );

	if ( $result ) {
		foreach ( $result as $row ) {
			return $row['totalTickets'];
		}
		$result = null;
	} else {
		return "0";
	}
}