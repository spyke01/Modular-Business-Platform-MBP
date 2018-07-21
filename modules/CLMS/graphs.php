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


global $ftsdb, $selectedGraph, $graphType, $daterange, $start_date, $stop_date, $currentTime, $startDate, $endDate, $graphSuffix, $graphType;
$arrData = array();

//===================================================		
// Fill our arrayData variable
//===================================================		
if ( $selectedGraph == "invoicedVsPaid" ) {
	# Return the chart data
	echo json_encode( array(
		'labels' => array(
			'Invoiced',
			'Paid',
		),
		'data'   => array(
			getInvoiceTotal( $startDate, $endDate ),
			getPaidInvoiceTotal( $startDate, $endDate ),
		),
	) );

} elseif ( $selectedGraph == "totalPayments" ) {
	# Return the chart data
	echo json_encode( array(
		'labels' => array(
			'Paid',
		),
		'data'   => array(
			getPaidInvoiceTotal( $startDate, $endDate ),
		),
	) );

} elseif ( $selectedGraph == "totalProfit" ) {
	# Return the chart data
	echo json_encode( array(
		'labels' => array(
			'Profit',
		),
		'data'   => array(
			getPaidInvoiceTotal( $startDate, $endDate ) - getInvoiceTotalWithoutProfit( $startDate, $endDate, STATUS_INVOICE_PAID ),
		),
	) );

} elseif ( $selectedGraph == "invoicesByStatus" ) {    # Return the chart data
	echo json_encode( array(
		'labels' => array(
			STATUS_INVOICE_AWAITING_PAYMENT_STATUS_TXT,
			STATUS_INVOICE_PAID_STATUS_TXT,
			STATUS_INVOICE_VOID_STATUS_TXT
		),
		'data'   => array(
			getInvoiceTotal( $startDate, $endDate, STATUS_INVOICE_AWAITING_PAYMENT ),
			getInvoiceTotal( $startDate, $endDate, STATUS_INVOICE_PAID ),
			getInvoiceTotal( $startDate, $endDate, STATUS_INVOICE_VOID ),
		),
	) );
} elseif ( $selectedGraph == "invoicesByClientCategory" ) {
	// Labels
	$labels = array( 'Uncategorized' );

	// Data
	$data = array( 0 );

	$extraSQL = ( $startDatetimestamp == "" || $stopDatetimestamp == "" ) ? "" : " WHERE i.datetimestamp >= :startDatetimestamp AND i.datetimestamp < :stopDatetimestamp";
	$sql      = "SELECT cat.name, SUM((ip.price + +ip.profit + ip.shipping) * ip.qty) AS totalCost FROM `" . DBTABLEPREFIX . "invoices_products` ip LEFT JOIN `" . DBTABLEPREFIX . "invoices` i ON i.id = ip.invoice_id LEFT JOIN `" . DBTABLEPREFIX . "clients` c ON c.id = i.client_id LEFT JOIN `" . DBTABLEPREFIX . "categories` cat ON cat.id = c.cat_id" . $extraSQL . " GROUP BY c.cat_id ORDER BY cat.name ASC";
	$result   = $ftsdb->run( $sql, array(
		":startDatetimestamp" => $startDatetimestamp,
		":stopDatetimestamp"  => $stopDatetimestamp,
	) );

	if ( $result ) {
		foreach ( $result as $row ) {
			// Labels
			$labels[] = $row['name'];

			// Data
			$data[] = (int) $row['totalCost'];
		}
		$result = null;
	}

	# Return the chart data
	echo json_encode( array(
		'labels' => $labels,
		'data'   => $data,
	) );
}