<?php
/***************************************************************************
 *                               invoices.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/



//=========================================================
// Prints the status of an invoice
//=========================================================
function printInvoiceStatus( $statusID ) {
	switch ( $statusID ) {
		case STATUS_INVOICE_PAID:
			return STATUS_INVOICE_PAID_STATUS_TXT;
			break;
		case STATUS_INVOICE_VOID:
			return STATUS_INVOICE_VOID_STATUS_TXT;
			break;
		default:
			return STATUS_INVOICE_AWAITING_PAYMENT_STATUS_TXT;
			break;
	}
}

//=========================================================
// Prints the type of payment used
//=========================================================
function printInvoicePaymentType( $paymenttype ) {
	global $FTS_PAYMENTTYPES;

	if ( $paymenttype >= count( $FTS_PAYMENTTYPES ) ) {
		return "N/A";
	} else {
		return $FTS_PAYMENTTYPES[ $paymenttype ];
	}
}

//=========================================================
// Gets a total of an invoices products from a invoiceid
//=========================================================
function getInvoiceProductsTotal( $invoiceID ) {
	global $ftsdb;

	$invoiceTotal = 0;

	$result = $ftsdb->select( DBTABLEPREFIX . "invoices_products", "invoice_id = :invoice_id", array(
		":invoice_id" => $invoiceID
	), 'SUM((price + profit + shipping) * qty) AS productsTotal' );

	if ( $result ) {
		foreach ( $result as $row ) {
			$invoiceTotal += $row['productsTotal'];
		}
		$result = null;
	}

	return $invoiceTotal;
}

//=========================================================
// Gets a total of an invoice values from a clientid
//=========================================================
function getTotalInvoiceSumByClientID( $clientID ) {
	global $ftsdb;

	$orderTotal = 0;

	$result = $ftsdb->select( "`" . DBTABLEPREFIX . "invoices_products` ip LEFT JOIN `" . DBTABLEPREFIX . "invoices` i ON i.id = ip.invoice_id", "i.client_id = :client_id", array(
		":client_id" => $clientID
	), 'SUM((ip.price + ip.profit + ip.shipping) * ip.qty) - i.discount AS productsTotal' );

	if ( $result ) {
		foreach ( $result as $row ) {
			$orderTotal += $row['productsTotal'];
		}
		$result = null;
	}

	return $orderTotal;
}

//=========================================================
// Gets the total amount paid based on a date range and id
//=========================================================
function getPaidInvoiceTotal( $startDatetimestamp, $stopDatetimestamp ) {
	global $ftsdb;

	$extraSQL = ( $startDatetimestamp == "" || $stopDatetimestamp == "" ) ? "" : " AND ipa.datetimestamp >= :startDatetimestamp AND ipa.datetimestamp < :stopDatetimestamp";
	$result   = $ftsdb->select( '`' . DBTABLEPREFIX . 'invoices_payments` ipa LEFT JOIN `' . DBTABLEPREFIX . 'invoices` i ON i.id = ipa.invoice_id', "i.status != " . STATUS_INVOICE_AWAITING_PAYMENT . "" . $extraSQL, array(
		":startDatetimestamp" => $startDatetimestamp,
		":stopDatetimestamp"  => $stopDatetimestamp,
	), 'SUM(ipa.paid) AS totalPaid' );

	if ( $result ) {
		foreach ( $result as $row ) {
			return $row['totalPaid'];
		}
		$result = null;
	} else {
		return "0";
	}
}

//=========================================================
// Gets the total invoice amount based on a date range and id
//=========================================================
function getInvoiceTotal( $startDatetimestamp, $stopDatetimestamp, $invoiceStatus = "" ) {
	global $ftsdb;

	$extraSQL = ( $startDatetimestamp == "" || $stopDatetimestamp == "" ) ? "" : " AND i.datetimestamp >= :startDatetimestamp AND i.datetimestamp < :stopDatetimestamp";
	$extraSQL .= ( ! is_numeric( $invoiceStatus ) ) ? "" : " AND i.status = :invoiceStatus";
	$result   = $ftsdb->select( '`' . DBTABLEPREFIX . 'invoices_products` ip LEFT JOIN `' . DBTABLEPREFIX . 'invoices` i ON i.id = ip.invoice_id', '1' . $extraSQL, array(
		":startDatetimestamp" => $startDatetimestamp,
		":stopDatetimestamp"  => $stopDatetimestamp,
		":invoiceStatus"      => $invoiceStatus,
	), 'SUM((ip.price + ip.profit + ip.shipping) * ip.qty) AS totalCost' );

	if ( $result ) {
		foreach ( $result as $row ) {
			return $row['totalCost'];
		}
		$result = null;
	} else {
		return "0";
	}
}

//=========================================================
// Gets the total invoice amount without profit based on a
// date range and id
//=========================================================
function getInvoiceTotalWithoutProfit( $startDatetimestamp, $stopDatetimestamp, $invoiceStatus = "" ) {
	global $ftsdb;

	$extraSQL = ( $startDatetimestamp == "" || $stopDatetimestamp == "" ) ? "" : " AND i.datetimestamp >= :startDatetimestamp AND i.datetimestamp < :stopDatetimestamp";
	$extraSQL .= ( ! is_numeric( $invoiceStatus ) ) ? "" : " AND i.status = :invoiceStatus";
	$result   = $ftsdb->select( '`' . DBTABLEPREFIX . 'invoices_products` ip LEFT JOIN `' . DBTABLEPREFIX . 'invoices` i ON i.id = ip.invoice_id', 'WHERE 1' . $extraSQL, array(
		":startDatetimestamp" => $startDatetimestamp,
		":stopDatetimestamp"  => $stopDatetimestamp,
		":invoiceStatus"      => $invoiceStatus,
	), 'SUM((ip.price + ip.shipping) * ip.qty) AS totalCostWithoutProfit' );

	if ( $result ) {
		foreach ( $result as $row ) {
			return $row['totalCostWithoutProfit'];
		}
		$result = null;
	} else {
		return "0";
	}
}

//=========================================================
// Prints the HTML of an invoice
//=========================================================
function printInvoice( $invoiceID ) {
	global $ftsdb, $clmsMenus, $mbp_config;
	$invoiceTotal = 0;

	$result = $ftsdb->select( DBTABLEPREFIX . "invoices", "id = :id LIMIT 1", array(
		":id" => $invoiceID
	) );

	// Check and build our invoice
	if ( ! $result ) {
		$invoiceHTML = "Invoice #" . $invoiceID . " does not exist!";
	} else {
		foreach ( $result as $row ) {
			$result2 = $ftsdb->select( DBTABLEPREFIX . "invoices_products", "invoice_id = :invoice_id ORDER BY name ASC", array(
				":invoice_id" => $invoiceID
			) );

			// Create our new table
			$table = new tableClass( '', '', '', "table table-striped table-bordered", "invoiceProductsTable" );

			// Create column headers
			$table->addNewRow(
				array(
					array( 'type' => 'th', 'data' => "Item" ),
					array( 'type' => 'th', 'data' => "Price" ),
					array( 'type' => 'th', 'data' => "Qty" ),
					array( 'type' => 'th', 'data' => "Total" )
				), '', 'title1', 'thead'
			);

			// Add our data
			if ( ! $result2 ) {
				$table->addNewRow( array(
					array(
						'data'    => "There are no products for this invoice.",
						"colspan" => "4"
					)
				), "", "greenRow" );
			} else {
				$x = 1;
				foreach ( $result2 as $row2 ) {
					$baseProductPrice = $row2['price'] + $row2['profit'] + $row2['shipping'];
					$lineTotal        = $baseProductPrice * $row2['qty'];
					$invoiceTotal     += $lineTotal;

					$table->addNewRow( array(
						array( 'data' => $row2['name'] ),
						array( 'data' => formatCurrency( $baseProductPrice ) ),
						array( 'data' => '<div id="edit-invoices_products-' . $row2['id'] . '_qty">' . $row2['qty'] . '</div>' ),
						array( 'data' => '<div id="' . $row2['id'] . '_lineTotal">' . formatCurrency( $lineTotal ) . '</div>' )
					), "", "row" . $x
					);

					$x = ( $x == 1 ) ? 2 : 1;
				}
				$result2 = null;
			}

			// Tally up our total
			$amountPaid = getInvoiceTotalAmountPaid( $invoiceID );
			$totalDue   = $invoiceTotal - $row['discount'] - $amountPaid;

			// Return the table's HTML
			$invoiceHTML = '
				<div id="invoice">
					<div id="companyInfoBlock">' . returnCompanyInfoBlock() . '</div>
					<div>
						<div id="invoiceDetailsBlock">
							<span>Invoice #</span> ' . $invoiceID . '<br />
							<span>Invoice Date</span> ' . makeDate( $row['datetimestamp'] ) . '<br />
							<strong><span>Amount Due</span> <span class="' . $row['id'] . '_totalDue noFloat">' . formatCurrency( $totalDue ) . '</span></strong>
						</div>
						<div id="clientInfoBlock">' . returnClientInfoBlock( $row['client_id'] ) . '</div>
					</div>
					<br class="clear" />
					' . $table->returnTableHTML() . '
					<div id="invoiceTotalsBlockWrapper">
						<div id="invoiceTotalsBlock">
							<span>Subtotal</span> <span id="edit-invoices-' . $row['id'] . '_subtotal" class="noFloat">' . formatCurrency( $invoiceTotal ) . '</span><br />
							<span>Discount</span> <span id="edit-invoices-' . $row['id'] . '_discount" class="noFloat">' . formatCurrency( $row['discount'] ) . '</span><br />
							<span>Paid</span> ' . formatCurrency( $amountPaid ) . '<br />
							<strong><span>Amount Due</span> <span class="' . $row['id'] . '_totalDue noFloat">' . formatCurrency( $totalDue ) . '</span></strong>
						</div>
					</div>
				</div>';
		}
		$result = null;
	}

	return $invoiceHTML;
}

//=================================================
// Returns the JQuery functions used to run the 
// invoice
//=================================================
function returnInvoiceJQuery( $invoiceID = "", $allowModification = 1 ) {
	global $ftsdb;

	$JQueryReadyScripts = "
			$('#invoicesTable').tablesorter({ widgets: ['zebra'], headers: { 7: { sorter: false } } });";

	// Only allow modification of rows if we have permission
	if ( $allowModification == 1 ) {
		$JQueryReadyScripts = "
			var fields = $(\"#invoicesTable span[id^='edit-invoices-']\").map(function() { return this.id; }).get();
			options = {
				callback: function(value, settings) {
					updateInvoiceTotalDueAmount(dbID, '" . progressSpinnerHTML() . "');
				}
			};
			addEditable( fields, options );
			
			var fields = $(\"#invoicesTable span[id^='edit-invoices_products']\").map(function() { return this.id; }).get();
			options = {
				callback: function(value, settings) {
					updateInvoiceLineTotalAmount(dbID, '" . progressSpinnerHTML() . "');
					updateInvoiceTotals(dbID, '" . progressSpinnerHTML() . "'); // BUG: Need to use invoice ID here instead of invoice product
				}
			};
			addEditable( fields, options );";
	}

	return $JQueryReadyScripts;
}

//=========================================================
// Prints the HTML of an emailable invoice
//=========================================================
function printEmailInvoice( $invoiceID ) {
	global $ftsdb, $clmsMenus, $mbp_config;
	$invoiceTotal = 0;

	$result = $ftsdb->select( DBTABLEPREFIX . "invoices", "id = :id LIMIT 1", array(
		":id" => $invoiceID
	) );

	// Check and build our invoice
	if ( ! $result ) {
		$invoiceHTML = "Invoice #" . $invoiceID . " does not exist!";
	} else {
		foreach ( $result as $row ) {
			$result2 = $ftsdb->select( DBTABLEPREFIX . "invoices_products", "invoice_id = :invoice_id ORDER BY name ASC", array(
				":invoice_id" => $invoiceID
			) );

			// Create our new table
			$table = new tableClass( '', '', '', "table table-striped table-bordered", "invoiceProductsTable" );

			// Create column headers
			$table->addNewRow(
				array(
					array( 'type' => 'th', 'data' => "Item" ),
					array( 'type' => 'th', 'data' => "Price" ),
					array( 'type' => 'th', 'data' => "Qty" ),
					array( 'type' => 'th', 'data' => "Total" )
				), '', 'title1', 'thead'
			);

			// Add our data
			if ( ! $result2 ) {
				$table->addNewRow( array(
					array(
						'data'    => "There are no products for this invoice.",
						"colspan" => "4"
					)
				), "", "greenRow" );
			} else {
				$x = 1;
				foreach ( $result2 as $row2 ) {
					$baseProductPrice = $row2['price'] + $row2['profit'] + $row2['shipping'];
					$lineTotal        = $baseProductPrice * $row2['qty'];
					$invoiceTotal     += $lineTotal;

					$table->addNewRow(
						array(
							array( 'data' => $row2['name'] ),
							array( 'data' => formatCurrency( $baseProductPrice ) ),
							array( 'data' => "<div id=\"" . $row2['id'] . "_qty\">" . $row2['qty'] . "</div>" ),
							array( 'data' => "<div id=\"" . $row2['id'] . "_lineTotal\">" . formatCurrency( $lineTotal ) . "</div>" )
						), "", "row" . $x
					);

					$x = ( $x == 1 ) ? 2 : 1;
				}
				$result2 = null;
			}

			// Tally up our total
			$amountPaid = getInvoiceTotalAmountPaid( $invoiceID );
			$totalDue   = $invoiceTotal - $row['discount'] - $amountPaid;

			// Return the table's HTML
			$invoiceHTML = '
				<div id="invoice">
					<div id="companyInfoBlock">' . returnCompanyInfoBlock() . '</div>
					<br /><br />
					<div id="clientInfoBlock">' . returnClientInfoBlock( $row['client_id'] ) . '</div>
					<br /><br />
					<div id="invoiceDetailsBlock">
						<span>Invoice #</span> ' . $invoiceID . '<br />
						<span>Invoice Date</span> ' . makeDate( $row['datetimestamp'] ) . '<br />
						<strong><span>Amount Due</span> <span class="' . $row['id'] . '_totalDue noFloat">' . formatCurrency( $totalDue ) . '</span></strong>
					</div>
					<br /><br />
					' . $table->returnTableHTML() . '
					<br /><br />
					<div id="invoiceTotalsBlockWrapper">
						<div id="invoiceTotalsBlock">
							<span>Subtotal</span> <span id="' . $row['id'] . '_subtotal" class="noFloat">' . formatCurrency( $invoiceTotal ) . '</span><br />
							<span>Discount</span> <span id="' . $row['id'] . '_discount" class="noFloat">' . formatCurrency( $row['discount'] ) . '</span><br />
							<span>Paid</span> ' . formatCurrency( $amountPaid ) . '<br />
							<strong><span>Amount Due</span> <span class="' . $row['id'] . '_totalDue noFloat">' . formatCurrency( $totalDue ) . '</span></strong>
						</div>
					</div>
				</div>';
		}
		$result = null;
	}

	return $invoiceHTML;
}

//=========================================================
// Gets a total amount paid on an invoice from a invoiceid
//=========================================================
function getInvoiceTotalAmountPaid( $invoiceID ) {
	global $ftsdb;

	$result = $ftsdb->select( DBTABLEPREFIX . "invoices_payments", "invoice_id = :invoice_id", array(
		":invoice_id" => $invoiceID
	), 'SUM(paid) AS totalPaid' );

	if ( $result ) {
		foreach ( $result as $row ) {
			return $row['totalPaid'];
		}
		$result = null;
	}
}

//=================================================
// Print the Invoices Table
//=================================================
function printInvoicesTable( $clientID = "", $allowModification = 1 ) {
	global $ftsdb, $clmsMenus, $mbp_config;

	$extraSQL = ( $clientID != "" ) ? " AND c.id = :id" : '';

	$result = $ftsdb->select( "`" . DBTABLEPREFIX . "invoices` i, `" . DBTABLEPREFIX . "clients` c", "c.id = i.client_id" . $extraSQL . " ORDER BY c.last_name, i.datetimestamp ASC", array(
		":id" => $clientID
	), 'i.*' );

	// Create our new table
	$table = new tableClass( '', '', '', "table table-striped table-bordered tablesorter", "invoicesTable" );

	// Create table title
	$table->addNewRow( array( array( 'data' => "Invoices", "colspan" => "8" ) ), '', 'title1', 'thead' );

	// Create column headers
	$table->addNewRow(
		array(
			array( 'type' => 'th', 'data' => "Date" ),
			array( 'type' => 'th', 'data' => "Description" ),
			array( 'type' => 'th', 'data' => "Total Cost" ),
			array( 'type' => 'th', 'data' => "Discount" ),
			array( 'type' => 'th', 'data' => "Total Paid" ),
			array( 'type' => 'th', 'data' => "Total Left" ),
			array( 'type' => 'th', 'data' => "Status" ),
			array( 'type' => 'th', 'data' => "" )
		), '', 'title2', 'thead'
	);

	// Add our data
	if ( ! $result ) {
		$table->addNewRow( array(
			array(
				'data'    => "There are no invoices for this client.",
				"colspan" => "8"
			)
		), "invoicesTableDefaultRow", "greenRow" );
	} else {
		foreach ( $result as $row ) {
			$invoiceTotal = 0;
			$finalColumn  = ( user_access( 'clms_invoices_accessDetails' ) ) ? "<a href=\"" . $clmsMenus['VIEWINVOICE']['link'] . "&id=" . $row['id'] . "\" class=\"btn btn-default\"><i class=\"glyphicon glyphicon-edit\"></i></a> " : "";
			$finalColumn  .= ( user_access( 'clms_invoices_viewPayments' ) ) ? "<a href=\"" . $clmsMenus['INVOICEPAYMENT']['link'] . "&id=" . $row['id'] . "\" class=\"btn btn-default\"><i class=\"glyphicons glyphicons-usd\"></i></a> " : "";
			$finalColumn  .= ( user_access( 'clms_invoices_email' ) ) ? "<a href=\"" . $clmsMenus['EMAILINVOICE']['link'] . "&id=" . $row['id'] . "\" class=\"btn btn-default\"><i class=\"glyphicon glyphicon-envelope\"></i></a> " : "";
			$finalColumn  .= ( user_access( 'clms_invoices_delete' ) ) ? createDeleteLinkWithImage( $row['id'], $row['id'] . "_row", "invoices", "invoice" ) : "";

			$result2 = $ftsdb->select( DBTABLEPREFIX . "invoices_products", "invoice_id = :invoice_id", array(
				":invoice_id" => $row['id']
			), 'SUM((price + profit + shipping) * qty) AS invoiceTotal' );

			if ( $result2 ) {
				foreach ( $result2 as $row2 ) {
					$invoiceTotal = $row2['invoiceTotal'];
				}
				$result2 = null;
			}

			// Tally up our total
			$amountPaid = getInvoiceTotalAmountPaid( $row['id'] );
			$totalDue   = $invoiceTotal - $row['discount'] - $amountPaid;

			// Add our row data
			$table->addNewRow( array(
				array( 'data' => makeDateTime( $row['datetimestamp'] ) ),
				array( 'data' => '<div id="edit-invoices-' . $row['id'] . '_description">' . $row['description'] . '</div>' ),
				array( 'data' => formatCurrency( $invoiceTotal ) ),
				array( 'data' => '<div id="edit-invoices-' . $row['id'] . '_discount">' . formatCurrency( $row['discount'] ) . '</div>' ),
				array( 'data' => formatCurrency( $amountPaid ) ),
				array( 'data' => '<div id="edit-invoices-' . $row['id'] . '_totalDue">' . formatCurrency( $totalDue ) . '</div>' ),
				array( 'data' => printInvoiceStatus( $row['status'] ) ),
				array( 'data' => '<span class="btn-group">' . $finalColumn . '</span>' )
			), $row['id'] . "_row", "" );
		}
		$result = null;
	}

	// Return the table's HTML
	return $table->returnTableHTML() . "
			<div id=\"invoicesTableUpdateNotice\"></div>";
}

//=================================================
// Returns the JQuery functions used to run the 
// client invoices table
//=================================================
function returnInvoicesTableJQuery( $clientID = "", $allowModification = 1 ) {
	global $ftsdb;

	$JQueryReadyScripts = "
			$('#invoicesTable').tablesorter({ widgets: ['zebra'], headers: { 7: { sorter: false } } });";

	// Only allow modification of rows if we have permission

	// Only allow modification of rows if we have permission
	if ( $allowModification == 1 && user_access( 'clms_invoices_edit' ) ) {
		$JQueryReadyScripts = "
			var fields = $(\"#invoicesTable div[id^='edit-invoices-']\").map(function() { return this.id; }).get();
			options = {
				callback: function(value, settings) {
					updateInvoiceTotalDueAmount(dbID, '" . progressSpinnerHTML() . "');
				}
			};
			addEditable( fields, options );";
	}

	return $JQueryReadyScripts;
}

//=================================================
// Create a form to add new orders
//=================================================
function printNewInvoiceForm( $clientID = "" ) {
	global $clmsMenus, $mbp_config;

	$clientIDSelect = ( $clientID != "" ) ? "<input type=\"hidden\" name=\"client_id\" value=\"" . $clientID . "\" />" : "<div><label for=\"client_id\">Client <span>- Required</span></label> " . createDropdown( "clients", "client_id", "", "" ) . "</div>";

	// Create our new table
	$table = new tableClass( 0, 1, 1, "", "addInvoiceProductsTable" );

	// Create column headers
	$table->addNewRow(
		array(
			array( 'type' => 'th', 'data' => "Item" ),
			array( 'type' => 'th', 'data' => "Qty" ),
			array( 'type' => 'th', 'data' => "" )
		), "", ""
	);

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

	$formFields = apply_filters( 'form_fields_clms_invoices_new', array(
		'client_id'   => $clientIDField,
		'description' => array(
			'text'  => 'Description',
			'type'  => 'text',
			'class' => 'required',
		),
		'products'    => array(
			'text'  => 'Invoice Products',
			'type'  => 'htmlWithLabel',
			'value' => '
				<div id="products">
					' . $table->returnTableHTML() . '
				</div>',
		),
		'discount'    => array(
			'text'    => 'Discount',
			'type'    => 'text',
			'prepend' => '$',
		),
		'note'        => array(
			'text' => 'Note',
			'type' => 'textarea',
		),
	) );

	return makeForm( 'newInvoice', il( $clmsMenus['INVOICES']['link'] ), 'New Invoice', 'Create Invoice', $formFields, array(), 1 );
}

//=================================================
// Returns the JQuery functions used to run the 
// new invoice form
//=================================================
function returnNewInvoiceFormJQuery( $reprintTable = 0, $allowModification = 1 ) {
	$table = ( $reprintTable == 0 ) ? '' : 'invoicesTable';

	$JQueryReadyScripts = "
		invoicesAddProductRow(" . $allowModification . ");";

	$url                = SITE_URL . "/ajax.php?action=createInvoice&reprinttable=" . $reprintTable . "&showButtons=" . $allowModification;
	$JQueryReadyScripts .= makeFormJQuery( 'newInvoice', $url, $table, 'invoice', '', '', '', 1 );

	return $JQueryReadyScripts;
}

//=================================================
// Returns the table row HTML for the invoice 
// products table
//=================================================
function returnInvoiceProductTableRowHTML( $rowNumber, $productID = "", $qty = 1 ) {
	$content = "
		<tr>
			<td>" . createDropdown( "productswithprice", "products[" . $rowNumber . "]", $productID, "" ) . "</td>
			<td><input type=\"text\" name=\"qty[" . $rowNumber . "]\" id=\"qty\" size=\"10\" value=\"" . $qty . "\" /></td>
			<td><a href=\"\" class=\"addProduct\" onclick=\"invoicesAddProductRow(this); return false;\"><img src=\"" . SITE_URL . "/themes/default/icons/add.png\" alt=\"add\" /></a> <a href=\"\" class=\"deleteProduct\" onclick=\"invoicesRemoveProductRow(this); return false;\"><img src=\"" . SITE_URL . "/themes/default/icons/delete.png\" alt=\"delete\" /></a><span class=\"spinner\" style=\"display: none;\">" . progressSpinnerHTML() . "</span></td>
		</tr>";

	return $content;
}

//=================================================
// Create a form to add new orders
//=================================================
function printEmailInvoiceForm( $invoiceID = "" ) {
	global $clmsMenus, $mbp_config;

	$formFields = apply_filters( 'form_fields_clms_invoices_email', array(
		'id'            => array(
			'type'  => 'hidden',
			'value' => $invoiceID,
		),
		'email_address' => array(
			'text'  => 'Email Address',
			'type'  => 'text',
			'class' => 'required',
			'value' => getClientEmailAddressFromInvoiceID( $invoiceID ),
		),
		'message'       => array(
			'text'  => 'Message',
			'type'  => 'textarea',
			'class' => 'required',
		),
	) );

	return makeForm( 'emailInvoice', il( $clmsMenus['INVOICES']['link'] ), 'Make a Payment', 'Make Payment', $formFields, array(), 1 );
}

//=================================================
// Returns the JQuery functions used to run the 
// new invoice form
//=================================================
function returnEmailInvoiceFormJQuery() {
	return makeFormJQuery( 'emailInvoice', SITE_URL . '/ajax.php?action=emailInvoice', '', 'category', '', '', 1 );
}

//=================================================
// Print the Invoices Payments Table
//=================================================
function printInvoicePaymentsTable( $invoiceID = "", $allowModification = 1 ) {
	global $ftsdb, $clmsMenus, $mbp_config;

	$result = $ftsdb->select( DBTABLEPREFIX . "invoices_payments", "invoice_id = :invoice_id ORDER BY datetimestamp ASC", array(
		":invoice_id" => $invoiceID
	) );

	// Create our new table
	$table = new tableClass( '', '', '', "table table-striped table-bordered tablesorter", "invoicePaymentsTable" );

	// Create table title
	$table->addNewRow( array( array( 'data' => "Invoice Payment History", "colspan" => "4" ) ), '', 'title1', 'thead' );

	// Create column headers
	$table->addNewRow(
		array(
			array( 'type' => 'th', 'data' => "Date" ),
			array( 'type' => 'th', 'data' => "Payment Type" ),
			array( 'type' => 'th', 'data' => "Total Paid" ),
			array( 'type' => 'th', 'data' => "" )
		), '', 'title2', 'thead'
	);

	// Add our data
	if ( ! $result ) {
		$table->addNewRow( array(
			array(
				'data'    => "There are no payments for this invoice.",
				"colspan" => "4"
			)
		), "invoicePaymentsTableDefaultRow", "greenRow" );
	} else {
		foreach ( $result as $row ) {
			$finalColumn = ( user_access( 'clms_invoices_deletePayment' ) ) ? createInvoicePaymentDeleteLinkWithImage( $row['id'], $row['id'] . "_row", "invoices_payments", "payment", $invoiceID ) : "";

			$rowDataArray = array(
				array( 'data' => makeDateTime( $row['datetimestamp'] ) ),
				array( 'data' => printInvoicePaymentType( $row['type'] ) ),
				array( 'data' => formatCurrency( $row['paid'] ) )
			);

			if ( $allowModification == 1 ) {
				array_push( $rowDataArray, array( 'data' => $finalColumn, 'class' => 'center' ) );
			} else {
				array_push( $rowDataArray, array( 'data' => "" ) );
			}

			$table->addNewRow( $rowDataArray, $row['id'] . "_row", "" );
		}
		$result = null;
	}

	// Return the table's HTML
	return $table->returnTableHTML();
}

//=================================================
// Returns the JQuery functions used to run the 
// client invoices table
//=================================================
function returnInvoicePaymentsTableJQuery() {
	$JQueryReadyScripts = "
			$('#invoicePaymentsTable').tablesorter({ widgets: ['zebra'], headers: { 3: { sorter: false } } });";

	return $JQueryReadyScripts;
}

//=================================================
// Create a form to add new orders
//=================================================
function printMakeInvoicePaymentForm( $invoiceID = "" ) {
	global $clmsMenus, $mbp_config;

	$formFields = apply_filters( 'form_fields_clms_invoices_make_payment', array(
		'id'   => array(
			'type'  => 'hidden',
			'value' => $invoiceID,
		),
		'type' => array(
			'text'    => 'Payment Type',
			'type'    => 'select',
			'options' => getDropdownArray( 'paymenttypes' ),
		),
		'paid' => array(
			'text'    => 'Amount Paid',
			'type'    => 'text',
			'prepend' => '$',
			'class'   => 'required',
		),
	) );

	return makeForm( 'makeInvoicePayment', il( $clmsMenus['INVOICES']['link'] ), 'Make a Payment', 'Make Payment', $formFields, array(), 1 );
}

//=================================================
// Returns the JQuery functions used to run the 
// new invoice form
//=================================================
function returnMakeInvoicePaymentFormJQuery( $invoiceID = "", $reprintTable = 0, $allowModification = 1 ) {
	$customSuccessFunction = ( $reprintTable == 0 ) ? "
		// Update the proper div with the returned data
		$('#makeInvoicePaymentResponse').html(data);
		$('#makeInvoicePaymentResponse').effect('highlight',{},500);"
		: "
		// Clear the default row
		$('#invoicePaymentsTableDefaultRow').remove();
		// Update the table with the new row
		$('#invoicePaymentsTable > tbody:last').append(data);
		// Update the invoice to show the payment
		jQuery.get('" . SITE_URL . "/ajax.php?action=reprintInvoice&id=" . $invoiceID . "', function(data) {
			$('#updateMeViewInvoice').html(data);
		});
		// Show a success message
		$('#makeInvoicePaymentResponse').html(returnSuccessMessage('payment'));";

	return makeFormJQuery( 'makeInvoicePayment', SITE_URL . "/ajax.php?action=createInvoicePayment&reprinttable=" . $reprintTable . "&showButtons=" . $allowModification, '', '', '', $customSuccessFunction, 1 );
}

//=================================================
// Print the Largest Invoices Table
//=================================================
function printLargestInvoicesTable( $invoiceLimit = 5 ) {
	global $ftsdb, $clmsMenus, $mbp_config;

	$result = $ftsdb->select( '`' . DBTABLEPREFIX . "invoices` i", "1 ORDER BY total DESC LIMIT :limit1", array(
		":limit1" => $invoiceLimit
	), 'coalesce((SELECT SUM((ip.price + ip.profit + ip.shipping ) * ip.qty) FROM `' . DBTABLEPREFIX . 'invoices_products` ip WHERE ip.invoice_id = i.id), 0) - i.discount AS total, i.datetimestamp, i.client_id' );

	// Create our new table
	$table = new tableClass( '', '', '', "table table-striped table-bordered tablesorter", "largestInvoicesTable" );

	// Create table title
	$table->addNewRow( array( array( 'data' => "Largest Invoices", "colspan" => "5" ) ), '', 'title1', 'thead' );

	// Create column headers
	$table->addNewRow(
		array(
			array( 'type' => 'th', 'data' => "Date and Time" ),
			array( 'type' => 'th', 'data' => "Client" ),
			array( 'type' => 'th', 'data' => "Total" )
		), '', 'title2', 'thead'
	);

	// Add our data
	if ( ! $result ) {
		$table->addNewRow( array(
			array(
				'data'    => "There are no invoices in the system.",
				"colspan" => "3"
			)
		), "", "greenRow" );
	} else {
		foreach ( $result as $row ) {
			$table->addNewRow(
				array(
					array( 'data' => makeDateTime( $row['datetimestamp'] ) ),
					array( 'data' => getClientNameFromID( $row['client_id'] ) ),
					array( 'data' => formatCurrency( $row['total'] ) )
				), $row['id'] . "_row", ""
			);
		}
		$result = null;
	}

	// Return the table's HTML
	return $table->returnTableHTML();
}

//=================================================
// Returns the JQuery functions used to run the 
// LargestOrders table
//=================================================
function returnLargestInvoicesTableJQuery() {
	$JQueryReadyScripts = "
			$('#largestInvoicesTable').tablesorter({ widgets: ['zebra'] });";

	return $JQueryReadyScripts;
}