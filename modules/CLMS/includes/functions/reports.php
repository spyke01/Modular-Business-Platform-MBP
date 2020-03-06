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
// Print the Accounts Aging Report
//=================================================
function printInvoiceAccountsAgingReport() {
	global $ftsdb;


	// Prep our IN clause data
	$preparedInClause = $ftsdb->prepareInClauseVariable( getMyClientIDs() );
	$selectBindData   = $preparedInClause['data'];

	$sql    = " SELECT i.client_id,
		    SUM(
				IF(DATEDIFF(CURDATE(), DATE(FROM_UNIXTIME(i.datetimestamp))) BETWEEN 0 AND 30, 
					(
						coalesce((SELECT SUM((ip.price + ip.profit + ip.shipping ) * ip.qty) - i.discount FROM `" . DBTABLEPREFIX . "invoices_products` ip WHERE ip.invoice_id = i.id), 0)
						- 
						coalesce((SELECT SUM(ipa.paid) FROM `" . DBTABLEPREFIX . "invoices_payments` ipa WHERE ipa.invoice_id = i.id), 0)
					),
					0
				)
			) AS pastDueAmount_1,
			SUM(IF(DATEDIFF(CURDATE(), DATE(FROM_UNIXTIME(i.datetimestamp))) BETWEEN 31 AND 60, 
					(
						coalesce((SELECT SUM((ip.price + ip.profit + ip.shipping ) * ip.qty) - i.discount FROM `" . DBTABLEPREFIX . "invoices_products` ip WHERE ip.invoice_id = i.id), 0)
						- 
						coalesce((SELECT SUM(ipa.paid) FROM `" . DBTABLEPREFIX . "invoices_payments` ipa WHERE ipa.invoice_id = i.id), 0)
					),
					0
				)
			) AS pastDueAmount_2,
		    SUM(IF(DATEDIFF(CURDATE(), DATE(FROM_UNIXTIME(i.datetimestamp))) BETWEEN 61 AND 90, 
					(
						coalesce((SELECT SUM((ip.price + ip.profit + ip.shipping ) * ip.qty) - i.discount FROM `" . DBTABLEPREFIX . "invoices_products` ip WHERE ip.invoice_id = i.id), 0)
						- 
						coalesce((SELECT SUM(ipa.paid) FROM `" . DBTABLEPREFIX . "invoices_payments` ipa WHERE ipa.invoice_id = i.id), 0)
					),
					0
				)
			) AS pastDueAmount_3,
		    SUM(IF(DATEDIFF(CURDATE(), DATE(FROM_UNIXTIME(i.datetimestamp))) > 90,
					(
						coalesce((SELECT SUM((ip.price + ip.profit + ip.shipping ) * ip.qty) - i.discount FROM `" . DBTABLEPREFIX . "invoices_products` ip WHERE ip.invoice_id = i.id), 0)
						- 
						coalesce((SELECT SUM(ipa.paid) FROM `" . DBTABLEPREFIX . "invoices_payments` ipa WHERE ipa.invoice_id = i.id), 0)
					),
					0
				)
			) AS pastDueAmount_4 
		FROM `" . DBTABLEPREFIX . "invoices` i WHERE i.status = '" . STATUS_INVOICE_AWAITING_PAYMENT . "' AND i.client_id IN (" . $preparedInClause['binds'] . ") GROUP BY i.client_id";
	$result = $ftsdb->run( $sql, $selectBindData );

	// Create our new table
	$table = new Table( '', '', '', "table table-striped table-bordered tablesorter", "accountsAgingReportTable" );

	// Create table title
	$table->addNewRow( array( array( 'data' => "Accounts Aging", "colspan" => "5" ) ), '', 'title1', 'thead' );

	// Create column headers
	$table->addNewRow(
		array(
			array( 'type' => 'th', 'data' => "Client" ),
			array( 'type' => 'th', 'data' => "< 30 Days" ),
			array( 'type' => 'th', 'data' => "31 - 60 Days" ),
			array( 'type' => 'th', 'data' => "61 - 90 Days" ),
			array( 'type' => 'th', 'data' => "> 90 Days" )
		), '', 'title2', 'thead'
	);

	// Add our data
	if ( ! $result ) {
		$table->addNewRow( array(
			array(
				'data'    => "There are no unpaid invoices.",
				"colspan" => "5"
			)
		), "accountsAgingReportTableDefaultRow", "greenRow" );
	} else {
		foreach ( $result as $row ) {
			$table->addNewRow( array(
				array( 'data' => getClientNameFromID( $row['client_id'] ) ),
				array( 'data' => formatCurrency( $row['pastDueAmount_1'] ) ),
				array( 'data' => formatCurrency( $row['pastDueAmount_2'] ) ),
				array( 'data' => formatCurrency( $row['pastDueAmount_3'] ) ),
				array( 'data' => formatCurrency( $row['pastDueAmount_4'] ) )
			), "", "" );
		}
		$result = null;
	}

	// Return the table's HTML
	return $table->returnTableHTML();
}

//=================================================
// Returns the JQuery functions used to run the 
// accounts aging report
//=================================================
function returnInvoiceAccountsAgingReportJQuery() {
	$JQueryReadyScripts = "
			$('#accountsAgingReportTable').tablesorter({ widgets: ['zebra'] });";

	return $JQueryReadyScripts;
}

//=================================================
// Print the Client Details Report
//=================================================
function printClientDetailsReport() {
	global $ftsdb;

	// Prep our IN clause data
	$preparedInClause = $ftsdb->prepareInClauseVariable( getMyClientIDs() );
	$selectBindData   = $preparedInClause['data'];

	$result = $ftsdb->select( "`" . DBTABLEPREFIX . "clients` c LEFT JOIN `" . DBTABLEPREFIX . "categories` cat ON c.cat_id = cat.id", "c.id IN (" . $preparedInClause['binds'] . ") ORDER BY cat.name, c.last_name, c.first_name", $selectBindData, 'c.*, cat.name' );

	// Create our new table
	$table = new Table( '', '', '', "table table-striped table-bordered tablesorter", "clientDetailsReportTable" );

	// Create table title
	$table->addNewRow( array( array( 'data' => "Client Details", "colspan" => "18" ) ), '', 'title1', 'thead' );

	// Create column headers
	$table->addNewRow(
		array(
			array( 'type' => 'th', 'data' => "Client Category" ),
			array( 'type' => 'th', 'data' => "Last Name" ),
			array( 'type' => 'th', 'data' => "First Name" ),
			array( 'type' => 'th', 'data' => "Title" ),
			array( 'type' => 'th', 'data' => "Company" ),
			array( 'type' => 'th', 'data' => "Address 1" ),
			array( 'type' => 'th', 'data' => "Address 2" ),
			array( 'type' => 'th', 'data' => TXT_CITY ),
			array( 'type' => 'th', 'data' => TXT_STATE ),
			array( 'type' => 'th', 'data' => TXT_ZIP ),
			array( 'type' => 'th', 'data' => "Daytime Phone" ),
			array( 'type' => 'th', 'data' => "Nighttime Phone" ),
			array( 'type' => 'th', 'data' => "Cell Phone" ),
			array( 'type' => 'th', 'data' => "Email Address" ),
			array( 'type' => 'th', 'data' => "Website" ),
			array( 'type' => 'th', 'data' => "Username" ),
			array( 'type' => 'th', 'data' => "Preffered Client" ),
			array( 'type' => 'th', 'data' => "Found Us Through" )
		), "", "title2 noWrap", "thead"
	);

	// Add our data
	if ( ! $result ) {
		$table->addNewRow( array(
			array(
				'data'    => "There are no clients in the system.",
				"colspan" => "18"
			)
		), "clientDetailsReportTableDefaultRow", "greenRow" );
	} else {
		foreach ( $result as $row ) {
			$table->addNewRow( array(
				array( 'data' => $row['name'] ),
				array( 'data' => $row['last_name'] ),
				array( 'data' => $row['first_name'] ),
				array( 'data' => $row['title'] ),
				array( 'data' => $row['company'] ),
				array( 'data' => $row['street1'] ),
				array( 'data' => $row['street2'] ),
				array( 'data' => $row['city'] ),
				array( 'data' => $row['state'] ),
				array( 'data' => $row['zip'] ),
				array( 'data' => $row['daytime_phone'] ),
				array( 'data' => $row['nighttime_phone'] ),
				array( 'data' => $row['cell_phone'] ),
				array( 'data' => $row['email_address'] ),
				array( 'data' => $row['website'] ),
				array( 'data' => $row['username'] ),
				array( 'data' => $row['preffered_client'] ),
				array( 'data' => returnYesNo( $row['found_us_through'] ) )
			), "", "noWrap" );
		}
		$result = null;
	}

	// Return the table's HTML
	return $table->returnTableHTML();
}

//=================================================
// Returns the JQuery functions used to run the 
// Client Details report
//=================================================
function returnClientDetailsReportJQuery() {
	$JQueryReadyScripts = "
			$('#clientDetailsReportTable').tablesorter({ widgets: ['zebra'] });";

	return $JQueryReadyScripts;
}

//=================================================
// Print the Invoices Report
//=================================================
function printInvoicesReport() {
	global $ftsdb;

	// Prep our IN clause data
	$preparedInClause = $ftsdb->prepareInClauseVariable( getMyClientIDs() );
	$selectBindData   = $preparedInClause['data'];

	$result = $ftsdb->select( "`" . DBTABLEPREFIX . "invoices` i LEFT JOIN `" . DBTABLEPREFIX . "clients` c ON i.client_id = c.id", "c.id IN (" . $preparedInClause['binds'] . ") ORDER BY i.status, c.last_name, c.first_name", $selectBindData, 'i.*, c.first_name, c.last_name' );

	// Create our new table
	$table = new Table( '', '', '', "table table-striped table-bordered tablesorter", "invoicesReportTable" );

	// Create table title
	$table->addNewRow( array( array( 'data' => "Invoices", "colspan" => "9" ) ), '', 'title1', 'thead' );

	// Create column headers
	$table->addNewRow(
		array(
			array( 'type' => 'th', 'data' => "Date" ),
			array( 'type' => 'th', 'data' => "Invoice Number" ),
			array( 'type' => 'th', 'data' => "Status" ),
			array( 'type' => 'th', 'data' => "Client" ),
			array( 'type' => 'th', 'data' => "Description" ),
			array( 'type' => 'th', 'data' => "Note" ),
			array( 'type' => 'th', 'data' => "Invoice Total" ),
			array( 'type' => 'th', 'data' => "Total Paid" ),
			array( 'type' => 'th', 'data' => "Balance" )
		), "", "title2 noWrap", "thead"
	);

	// Add our data
	if ( ! $result ) {
		$table->addNewRow( array(
			array(
				'data'    => "There are no invoices in the system.",
				"colspan" => "9"
			)
		), "invoicesReportTableDefaultRow", "greenRow" );
	} else {
		foreach ( $result as $row ) {
			$invoiceTotal = getInvoiceProductsTotal( $row['id'] ) - $row['discount'];
			$totalPaid    = getInvoiceTotalAmountPaid( $row['id'] );

			$table->addNewRow( array(
				array( 'data' => makeShortDate( $row['datetimestamp'] ) ),
				array( 'data' => $row['id'] ),
				array( 'data' => printInvoiceStatus( $row['status'] ) ),
				array( 'data' => $row['last_name'] . ", " . $row['first_name'] ),
				array( 'data' => $row['description'] ),
				array( 'data' => bbcode( $row['note'] ) ),
				array( 'data' => formatCurrency( $invoiceTotal ) ),
				array( 'data' => formatCurrency( $totalPaid ) ),
				array( 'data' => formatCurrency( $invoiceTotal - $totalPaid ) )
			), "", "noWrap" );
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
function returnInvoicesReportJQuery() {
	$JQueryReadyScripts = "
			$('#invoicesReportTable').tablesorter({ widgets: ['zebra'] });";

	return $JQueryReadyScripts;
}

//=================================================
// Print the Invoice Payments Report
//=================================================
function printInvoicePaymentsReport() {
	global $ftsdb;

	// Prep our IN clause data
	$preparedInClause = $ftsdb->prepareInClauseVariable( getMyClientIDs() );
	$selectBindData   = $preparedInClause['data'];

	$result = $ftsdb->select( "`" . DBTABLEPREFIX . "invoices_payments` ipa LEFT JOIN `" . DBTABLEPREFIX . "invoices` i ON ipa.invoice_id = i.id LEFT JOIN `" . DBTABLEPREFIX . "clients` c ON i.client_id = c.id", "c.id IN (" . $preparedInClause['binds'] . ") ORDER BY i.status, c.last_name, c.first_name, ipa.datetimestamp", $selectBindData, 'ipa.*, i.id AS invoiceID, c.first_name, c.last_name' );

	// Create our new table
	$table = new Table( '', '', '', "table table-striped table-bordered tablesorter", "invoicePaymentsReportTable" );

	// Create table title
	$table->addNewRow( array( array( 'data' => "Invoice Payments", "colspan" => "9" ) ), '', 'title1', 'thead' );

	// Create column headers
	$table->addNewRow(
		array(
			array( 'type' => 'th', 'data' => "Date" ),
			array( 'type' => 'th', 'data' => "Invoice Number" ),
			array( 'type' => 'th', 'data' => "Client" ),
			array( 'type' => 'th', 'data' => "Payment Type" ),
			array( 'type' => 'th', 'data' => "Amount Paid" )
		), "", "title2 noWrap", "thead"
	);

	// Add our data
	if ( ! $result ) {
		$table->addNewRow( array(
			array(
				'data'    => "There are no payments in the system.",
				"colspan" => "9"
			)
		), "invoicePaymentsReportTableDefaultRow", "greenRow" );
	} else {
		foreach ( $result as $row ) {
			$table->addNewRow( array(
				array( 'data' => makeShortDateTime( $row['datetimestamp'] ) ),
				array( 'data' => $row['invoiceID'] ),
				array( 'data' => $row['last_name'] . ", " . $row['first_name'] ),
				array( 'data' => printInvoicePaymentType( $row['type'] ) ),
				array( 'data' => formatCurrency( $row['paid'] ) )
			), "", "noWrap" );
		}
		$result = null;
	}

	// Return the table's HTML
	return $table->returnTableHTML();
}

//=================================================
// Returns the JQuery functions used to run the 
// Invoice Payments report
//=================================================
function returnInvoicePaymentsReportJQuery() {
	$JQueryReadyScripts = "
			$('#invoicePaymentsReportTable').tablesorter({ widgets: ['zebra'] });";

	return $JQueryReadyScripts;
}

//=================================================
// Print the Serial Numbers Report
//=================================================
function printSerialNumbersReport() {
	global $ftsdb;

	// Prep our IN clause data
	$preparedInClause = $ftsdb->prepareInClauseVariable( getMyClientIDs() );
	$selectBindData   = $preparedInClause['data'];

	$result = $ftsdb->select( "`" . DBTABLEPREFIX . "downloads` d LEFT JOIN `" . DBTABLEPREFIX . "clients` c ON d.client_id = c.id", "c.id IN (" . $preparedInClause['binds'] . ") ORDER BY c.last_name, c.first_name", $selectBindData, 'c.first_name, c.last_name, d.name, d.serial_number, d.datetimestamp' );

	// Create our new table
	$table = new Table( '', '', '', "table table-striped table-bordered tablesorter", "serialNumbersReportTable" );

	// Create table title
	$table->addNewRow( array( array( 'data' => "Serial Numbers", "colspan" => "5" ) ), '', 'title1', 'thead' );

	// Create column headers
	$table->addNewRow(
		array(
			array( 'type' => 'th', 'data' => "Client" ),
			array( 'type' => 'th', 'data' => "Download Name" ),
			array( 'type' => 'th', 'data' => "Serial Number" ),
			array( 'type' => 'th', 'data' => "Uploaded On" )
		), '', 'title2', 'thead'
	);

	// Add our data
	if ( ! $result ) {
		$table->addNewRow( array(
			array(
				'data'    => "There are no serial numbers in the system.",
				"colspan" => "5"
			)
		), "serialNumbersReportTableDefaultRow", "greenRow" );
	} else {
		foreach ( $result as $row ) {
			$table->addNewRow( array(
				array( 'data' => $row['last_name'] . ", " . $row['first_name'] ),
				array( 'data' => $row['name'] ),
				array( 'data' => $row['serial_number'] ),
				array( 'data' => makeShortDate( $row['datetimestamp'] ) )
			), "", "" );
		}
		$result = null;
	}

	// Return the table's HTML
	return $table->returnTableHTML();
}

//=================================================
// Returns the JQuery functions used to run the 
// serial numbers report
//=================================================
function returnSerialNumbersReportJQuery() {
	$JQueryReadyScripts = "
			$('#serialNumbersReportTable').tablesorter({ widgets: ['zebra'] });";

	return $JQueryReadyScripts;
}