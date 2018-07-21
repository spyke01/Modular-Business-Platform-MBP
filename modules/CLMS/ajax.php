<?php
// Cycle through our AJAX calls and handle the content
if ( $actual_action == 'updateitem' && user_access( 'clms_updateitem' ) ) {
	if ( $section == 'before' ) {

	}
} elseif ( $actual_action == 'deleteitem' && user_access( 'clms_deleteitem' ) ) {
	if ( $section == 'before' ) {
		// Delete and associated foreign items
		if ( $table == "clients" ) {
			// Prep our IN clause data
			$preparedInClause = $ftsdb->prepareInClauseVariable( getMyClientIDs() );
			$selectBindData   = $preparedInClause['data'];
			$selectBindData   = array_merge( $selectBindData, array(
				":client_id" => $actual_id
			) );

			// Delete Appointments
			$result     = $ftsdb->delete( DBTABLEPREFIX . 'appointments', "client_id = :client_id AND client_id IN (" . $preparedInClause['binds'] . ")", $selectBindData );
			$errorCount += ( $result ) ? 0 : 1;

			// Select all associated Invoices so we can kill their foreign items
			$result     = $ftsdb->select( DBTABLEPREFIX . "invoices", "client_id = :client_id AND client_id IN (" . $preparedInClause['binds'] . ")", $selectBindData, 'id' );
			$errorCount += ( $result ) ? 0 : 1;

			if ( $result ) {
				foreach ( $result as $row ) {
					// Delete Payments
					$result     = $ftsdb->delete( DBTABLEPREFIX . 'invoices_payments', "invoice_id = :invoice_id", array(
						":invoice_id" => $row['id']
					) );
					$errorCount += ( $result ) ? 0 : 1;

					// Delete Invoice Products
					$result     = $ftsdb->delete( DBTABLEPREFIX . 'invoices_products', "invoice_id = :invoice_id", array(
						":invoice_id" => $row['id']
					) );
					$errorCount += ( $result ) ? 0 : 1;
				}
				$result = null;
			}

			// Delete Invoices
			$result     = $ftsdb->delete( DBTABLEPREFIX . 'invoices', "client_id = :client_id AND client_id IN (" . $preparedInClause['binds'] . ")", $selectBindData );
			$errorCount += ( $result ) ? 0 : 1;

			// Delete Notes
			$result     = $ftsdb->delete( DBTABLEPREFIX . 'notes', "client_id = :client_id AND client_id IN (" . $preparedInClause['binds'] . ")", $selectBindData );
			$errorCount += ( $result ) ? 0 : 1;
		}
		if ( $table == "invoices" ) {
			// Delete Payments
			$result     = $ftsdb->delete( DBTABLEPREFIX . 'invoices_payments', "invoice_id = :invoice_id", array(
				":invoice_id" => $actual_id
			) );
			$errorCount += ( $result ) ? 0 : 1;

			// Delete Invoice Products
			$result     = $ftsdb->delete( DBTABLEPREFIX . 'invoices_products', "invoice_id = :invoice_id", array(
				":invoice_id" => $actual_id
			) );
			$errorCount += ( $result ) ? 0 : 1;
		}
		if ( $table == "invoices_payments" ) {
			// Check to see if our invoice is no longer paid in full and if so then change its status
			$result     = $ftsdb->update( '`' . DBTABLEPREFIX . "invoices` i", array(
				"status" => STATUS_INVOICE_AWAITING_PAYMENT
			),
				"coalesce((SELECT SUM((ip.price + ip.profit + ip.shipping ) * ip.qty) - i.discount FROM `" . DBTABLEPREFIX . "invoices_products` ip WHERE ip.invoice_id = i.id), 0) - coalesce((SELECT SUM(ipa.paid) FROM `" . DBTABLEPREFIX . "invoices_payments` ipa WHERE ipa.invoice_id = i.id), 0) > 0"
			);
			$errorCount += ( $result ) ? 0 : 1;
		}
	}
}

//================================================
// Update our appointments in the database
//================================================
elseif ( $actual_action == 'createAppointment' && user_access( 'clms_appointments_create' ) ) {
	$datetimestamp = strtotime( keepsafe( $_GET['datetimestamp'] ) );
	$client_id     = intval( $_POST['client_id'] );
	$place         = keeptasafe( $_POST['place'] );
	$attire        = keeptasafe( $_POST['attire'] );
	$description   = keeptasafe( $_POST['description'] );
	$urgency       = keeptasafe( $_POST['urgency'] );

	$result        = $ftsdb->insert( DBTABLEPREFIX . 'appointments', array(
		"datetimestamp" => $datetimestamp,
		"client_id"     => $client_id,
		"place"         => $place,
		"attire"        => $attire,
		"description"   => $description,
		"urgency"       => $urgency,
	) );
	$appointmentID = $ftsdb->lastInsertId();

	$content = ( $result ) ? "	<span class=\"greenText bold\">Successfully created appointment for " . makeDateTime( $datetimestamp ) . " with " . getClientNameFromID( $client_id ) . "!</span>" : "	<span class=\"redText bold\">Failed to create appointment" . makeDateTime( $datetimestamp ) . " with " . getClientNameFromID( $client_id ) . "!!!<br />$sql</span>";

	$rowColor = ( $urgency != LOW ) ? "redRow" : "greenRow";
	$rowColor = ( $urgency != HIGH && $rowColor == "redRow" ) ? "yellowRow" : $rowColor;

	switch ( keepsafe( $_GET['reprinttable'] ) ) {
		case 1:
			$finalColumn = ( user_access( 'clms_appointments_delete' ) ) ? createDeleteLinkWithImage( $appointmentID, $appointmentID . "_row", "appointments", "appointment" ) : "";

			$tableHTML = "
				<tr class=\"" . $rowColor . "\" id=\"" . $appointmentID . "_row\">
					<td>" . makeDateTime( $datetimestamp ) . "</td>
					<td>" . $place . "</td>
					<td>" . $attire . "</td>
					<td>" . $description . "</td>
					<td class=\"center\">" . $finalColumn . "</td>
				</tr>";

			echo $tableHTML;
			break;
		case 2:
			$finalColumn = ( user_access( 'clms_appointments_delete' ) ) ? createDeleteLinkWithImage( $appointmentID, $appointmentID . "_row", "appointments", "appointment" ) : "";

			$tableHTML = "
				<tr class=\"even\" id=\"" . $appointmentID . "_row\">
					<td>" . makeDateTime( $datetimestamp ) . "</td>
					<td>" . $place . "</td>
					<td>" . $attire . "</td>
					<td>" . getClientNameFromID( $client_id ) . "</td>
					<td>" . $description . "</td>
					<td class=\"center\">" . $finalColumn . "</td>
				</tr>";

			echo $tableHTML;
			break;
		case 3:
			echo printAppointmentCalendar();
			break;
		default:
			echo $content;
			break;
	}
}

//================================================
// Update our calendar
//================================================
elseif ( $actual_action == 'printAppointmentCalendar' && user_access( 'clms_appointments_printCalendar' ) ) {
	echo printAppointmentCalendar();
}

//================================================
// Add our client to the database
//================================================
elseif ( $actual_action == 'createClient' && user_access( 'clms_clients_create' ) ) {
	$handleLogin = user_access( 'clms_clients_manage_client_login' );

	$clientData = array(
		'user_id'          => ( ( user_access( 'clms_clients_manage_owner' ) ) ? intval( $_POST['user_id'] ) : $_SESSION['userid'] ),
		'cat_id'           => intval( $_POST['cat_id'] ),
		'first_name'       => keeptasafe( $_POST['first_name'] ),
		'last_name'        => keeptasafe( $_POST['last_name'] ),
		'title'            => keeptasafe( $_POST['title'] ),
		'company'          => keeptasafe( $_POST['company'] ),
		'street1'          => keeptasafe( $_POST['street1'] ),
		'street2'          => keeptasafe( $_POST['street2'] ),
		'city'             => keeptasafe( $_POST['city'] ),
		'state'            => keeptasafe( $_POST['state'] ),
		'zip'              => keeptasafe( $_POST['zip'] ),
		'daytime_phone'    => keeptasafe( $_POST['daytime_phone'] ),
		'nighttime_phone'  => keeptasafe( $_POST['nighttime_phone'] ),
		'cell_phone'       => keeptasafe( $_POST['cell_phone'] ),
		'email_address'    => sanitize_email( $_POST['email_address'] ),
		'website'          => keeptasafe( $_POST['website'] ),
		'found_us_through' => keeptasafe( $_POST['found_us_through'] ),
		'preffered_client' => keeptasafe( $_POST['preffered_client'] ),
	);

	if ( $handleLogin ) {
		$clientData['username'] = keeptasafe( $_POST['username'] );
		$password               = keeptasafe( $_POST['password'] );
		$password2              = keeptasafe( $_POST['password2'] );
		$clientData['password'] = ( $password != "" ) ? md5( $password ) : "";
	}

	if ( ! $handleLogin || ( $handleLogin && $password == $password2 ) ) {
		$result   = $ftsdb->insert( DBTABLEPREFIX . 'clients', $clientData );
		$clientID = $ftsdb->lastInsertId();

		$content = ( $result ) ? "	<span class=\"greenText bold\">Successfully created client (" . $clientData['last_name'] . ", " . $clientData['first_name'] . ")!</span>" : "	<span class=\"redText bold\">Failed to create client (" . $clientData['last_name'] . ", " . $clientData['first_name'] . ")!!!</span>";

		switch ( keepsafe( $_GET['reprinttable'] ) ) {
			case 1:
				$finalColumn = ( user_access( 'clms_clients_edit' ) ) ? "<a href=\"" . $clmsMenus['CLIENTS']['link'] . "&action=editclient&id=" . $clientID . "\" class=\"btn btn-default\"><i class=\"glyphicon glyphicon-edit\"></i></a> " : "";
				$finalColumn .= ( user_access( 'clms_clients_delete' ) ) ? createDeleteLinkWithImage( $clientID, $clientID . "_row", "clients", "client" ) : "";

				$tableHTML = '
					<tr class="even" id="' . $clientID . '_row">
						<td>' . $clientData['last_name'] . ', ' . $clientData['first_name'] . '</td>
						<td>' . $clientData['company'] . '</td>
						<td>' . getCatNameByID( $clientData['cat_id'] ) . '</td>
						<td>' . formatCurrency( 0 ) . '</td>
						<td class="center"><span class="btn-group">' . $finalColumn . '</span></td>
					</tr>';

				echo $tableHTML;
				break;
			default:
				echo $content;
				break;
		}
	} else {
		$content = "<span class=\"redText bold\">The passwords you supplied do not match. Please fix this.</span>";
		echo $content;
	}
}

//================================================
// Update our client in the database
//================================================
elseif ( $actual_action == 'updateClient' && user_access( 'clms_clients_edit' ) ) {
	$handleLogin = user_access( 'clms_clients_manage_client_login' );

	$clientData = array(
		'user_id'          => ( ( user_access( 'clms_clients_manage_owner' ) ) ? intval( $_POST['user_id'] ) : $_SESSION['userid'] ),
		'cat_id'           => intval( $_POST['cat_id'] ),
		'first_name'       => keeptasafe( $_POST['first_name'] ),
		'last_name'        => keeptasafe( $_POST['last_name'] ),
		'title'            => keeptasafe( $_POST['title'] ),
		'company'          => keeptasafe( $_POST['company'] ),
		'street1'          => keeptasafe( $_POST['street1'] ),
		'street2'          => keeptasafe( $_POST['street2'] ),
		'city'             => keeptasafe( $_POST['city'] ),
		'state'            => keeptasafe( $_POST['state'] ),
		'zip'              => keeptasafe( $_POST['zip'] ),
		'daytime_phone'    => keeptasafe( $_POST['daytime_phone'] ),
		'nighttime_phone'  => keeptasafe( $_POST['nighttime_phone'] ),
		'cell_phone'       => keeptasafe( $_POST['cell_phone'] ),
		'email_address'    => sanitize_email( $_POST['email_address'] ),
		'website'          => keeptasafe( $_POST['website'] ),
		'found_us_through' => keeptasafe( $_POST['found_us_through'] ),
		'preffered_client' => keeptasafe( $_POST['preffered_client'] ),
	);

	if ( $handleLogin ) {
		$clientData['username'] = keeptasafe( $_POST['username'] );
		$password               = keeptasafe( $_POST['password'] );
		$password2              = keeptasafe( $_POST['password2'] );
		$clientData['password'] = ( $password != "" ) ? md5( $password ) : "";
	}

	if ( ! $handleLogin || ( $handleLogin && $password == $password2 ) ) {
		// Prep our IN clause data
		$preparedInClause = $ftsdb->prepareInClauseVariable( getMyClientIDs() );
		$selectBindData   = $preparedInClause['data'];
		$selectBindData   = array_merge( $selectBindData, array(
			":id" => $actual_id
		) );

		$result = $ftsdb->update( DBTABLEPREFIX . "clients", $clientData, '`id` = :id AND `id` IN (' . $preparedInClause['binds'] . ')', $selectBindData );

		$content = ( $result ) ? "	<span class=\"greenText bold\">Successfully updated client!</span>" : "	<span class=\"redText bold\">Failed to update client!!!</span>";
		echo $content;
	} else {
		$content = "<span class=\"redText bold\">The passwords you supplied do not match. Please fix this.</span>";
		echo $content;
	}
}

//================================================
// Add our downloads to the database
//================================================
elseif ( $actual_action == 'createDownload' && user_access( 'clms_downloads_create' ) ) {
	$datetimestamp    = time();
	$client_id        = intval( $_POST['client_id'] );
	$name             = keeptasafe( $_POST['name'] );
	$uplodedFilesName = keeptasafe( $_POST['uplodedFilesName'] );
	$url              = keeptasafe( $_POST['url'] );
	$serial_number    = keeptasafe( $_POST['serial_number'] );

	// If we uploaded a file then use it instead of our URL data
	$url = ( $uplodedFilesName != "" ) ? $uplodedFilesName : $url;

	$result     = $ftsdb->insert( DBTABLEPREFIX . 'downloads', array(
		"datetimestamp" => $datetimestamp,
		"client_id"     => $client_id,
		"name"          => $name,
		"url"           => $url,
		"serial_number" => $serial_number,
	) );
	$downloadID = $ftsdb->lastInsertId();

	$content = ( $result ) ? "	<span class=\"greenText bold\">Successfully created download " . $name . " for " . getClientNameFromID( $client_id ) . "!</span>" : "	<span class=\"redText bold\">Failed to create download " . $name . " for " . getClientNameFromID( $client_id ) . "!!!</span>";

	switch ( keepsafe( $_GET['reprinttable'] ) ) {
		case 1:
			$finalColumn = ( user_access( 'clms_downloads_delete' ) ) ? createDeleteLinkWithImage( $downloadID, $downloadID . "_row", "downloads", "download" ) : "";

			$tableHTML = "
				<tr class=\"even\" id=\"" . $downloadID . "_row\">
					<td><a href=\"" . $url . "\">" . keeptasafe( $name ) . "</a></td>
					<td>" . $serial_number . "</td>
					<td>" . makeDateTime( $datetimestamp ) . "</td>
					<td class=\"center\">" . $finalColumn . "</td>
				</tr>";

			echo $tableHTML;
			break;
		default:
			echo $content;
			break;
	}
}

//================================================
// Add our invoices to the database
//================================================
elseif ( $actual_action == 'createInvoice' && user_access( 'clms_invoices_create' ) ) {
	$invoiceTotal  = 0;
	$datetimestamp = time();
	$client_id     = intval( $_POST['client_id'] );
	$description   = keeptasafe( $_POST['description'] );
	$discount      = keeptasafe( $_POST['discount'] );
	$note          = keeptasafe( $_POST['note'] );

	$result    = $ftsdb->insert( DBTABLEPREFIX . 'invoices', array(
		"datetimestamp" => $datetimestamp,
		"client_id"     => $client_id,
		"description"   => $description,
		"discount"      => $discount,
		"note"          => $note,
	) );
	$invoiceID = $ftsdb->lastInsertId();

	foreach ( $_POST['products'] as $key => $product_id ) {
		$qty = intval( $_GET['qty'][ $key ] );

		$result = $ftsdb->select( DBTABLEPREFIX . "products", "id = :id LIMIT 1", array(
			":id" => $product_id
		) );

		if ( $result ) {
			foreach ( $result as $row ) {
				$invoiceTotal += ( $row['price'] + $row['profit'] + $row['shipping'] ) * $qty;

				$result2 = $ftsdb->insert( DBTABLEPREFIX . 'invoices_products', array(
					"invoice_id" => $invoiceID,
					"name"       => $row['name'],
					"price"      => $row['price'],
					"profit"     => $row['profit'],
					"qty"        => $qty,
					"shipping"   => $row['shipping'],
				) );
			}
			$result = null;
		}
	}

	$content = ( $result ) ? "	<span class=\"greenText bold\">Successfully created invoice #" . $invoiceID . "!</span>" : "	<span class=\"redText bold\">Failed to create invoice!!!</span>";

	switch ( keepsafe( $_GET['reprinttable'] ) ) {
		case 1:
			$finalColumn = ( user_access( 'clms_invoices_accessDetails' ) ) ? "<a href=\"" . $clmsMenus['VIEWINVOICE']['link'] . "&id=" . $invoiceID . "\" class=\"btn btn-default\"><i class=\"glyphicon glyphicon-edit\"></i></a> " : "";
			$finalColumn .= ( user_access( 'clms_invoices_viewPayments' ) ) ? "<a href=\"" . $clmsMenus['INVOICEPAYMENT']['link'] . "&id=" . $invoiceID . "\" class=\"btn btn-default\"><i class=\"glyphicons glyphicons-usd\"></i></a> " : "";
			$finalColumn .= ( user_access( 'clms_invoices_email' ) ) ? "<a href=\"" . $clmsMenus['EMAILINVOICE']['link'] . "&id=" . $invoiceID . "\" class=\"btn btn-default\"><i class=\"glyphicon glyphicon-envelope\"></i></a> " : "";
			$finalColumn .= ( user_access( 'clms_invoices_delete' ) ) ? createDeleteLinkWithImage( $invoiceID, $invoiceID . "_row", "invoices", "invoice" ) : "";

			$tableHTML = '
				<tr class="even" id="' . $invoiceID . '_row">
					<td>' . makeDateTime( $datetimestamp ) . '</td>
					<td>' . $description . '</td>
					<td>' . formatCurrency( $invoiceTotal ) . '</td>
					<td>' . formatCurrency( $discount ) . '</td>
					<td>' . formatCurrency( 0 ) . '</td>
					<td>' . formatCurrency( $invoiceTotal - $discount ) . '</td>
					<td>' . printInvoiceStatus( STATUS_INVOICE_AWAITING_PAYMENT ) . '</td>
					<td class="center"><span class="btn-group">' . $finalColumn . '</span></td>
				</tr>';

			echo $tableHTML;
			break;
		default:
			echo $content;
			break;
	}
}

//================================================
// Returns the table row HTML for the invoice 
// products table
//================================================
elseif ( $actual_action == 'returnInvoiceProductTableRowHTML' && user_access( 'clms_invoices_returnProductTableRowHTML' ) ) {
	echo returnInvoiceProductTableRowHTML( $actual_id );
}

//================================================
// Returns the line total on an invoice
//================================================
elseif ( $actual_action == 'getInvoiceLineTotal' && user_access( 'clms_invoices_getLineTotal' ) ) {
	$invoiceTotal = 0;

	$result = $ftsdb->select( DBTABLEPREFIX . "invoices_products", "id = :id LIMIT 1", array(
		":id" => $actual_id
	), 'SUM((price + profit + shipping) * qty) AS invoiceTotal' );

	// Pull our data
	if ( $result ) {
		foreach ( $result as $row ) {
			$invoiceTotal = $row['invoiceTotal'];
		}
		$result = null;
	}

	echo formatCurrency( $invoiceTotal );
}

//================================================
// Returns the subtotal on an invoice
//================================================
elseif ( $actual_action == 'getInvoiceSubtotal' && user_access( 'clms_invoices_getSubtotal' ) ) {
	$invoiceSubtotal = 0;

	$result = $ftsdb->select( DBTABLEPREFIX . "invoices_products", "invoice_id = :invoice_id LIMIT 1", array(
		":invoice_id" => $actual_id
	), 'SUM((price + profit + shipping) * qty) AS invoiceTotal' );

	// Pull our data
	if ( $result ) {
		foreach ( $result as $row ) {
			$invoiceSubtotal += $row['invoiceTotal'];
		}
		$result = null;
	}

	echo formatCurrency( $invoiceSubtotal );
}

//================================================
// Returns the total due on an invoice
//================================================
elseif ( $actual_action == 'getInvoiceTotalDue' && user_access( 'clms_invoices_getTotalDue' ) ) {
	$totalDue = 0;

	$result = $ftsdb->select( DBTABLEPREFIX . "invoices", "id = :id LIMIT 1", array(
		":id" => $actual_id
	) );

	// Pull our data
	if ( $result ) {
		foreach ( $result as $row ) {
			$invoiceTotal = 0;

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
			$totalDue = $invoiceTotal - $row['discount'] - getInvoiceTotalAmountPaid( $row['id'] );
		}
		$result = null;
	}

	echo formatCurrency( $totalDue );
}

//================================================
// Update our invoices payments in the database
//================================================
elseif ( $actual_action == 'createInvoicePayment' && user_access( 'clms_invoices_createPayment' ) ) {
	$datetimestamp = time();
	$type          = keepsafe( $_POST['type'] );
	$paid          = keepsafe( $_POST['paid'] );

	$result           = $ftsdb->insert( DBTABLEPREFIX . 'invoices_payments', array(
		"datetimestamp" => $datetimestamp,
		"invoice_id"    => $actual_id,
		'type'          => $type,
		"paid"          => $paid,
	) );
	$invoicePaymentID = $ftsdb->lastInsertId();

	$content = ( $result ) ? "	<span class=\"greenText bold\">Successfully created invoice payment of " . formatCurrency( $paid ) . " for invoice #" . $actual_id . "!</span>" : "	<span class=\"redText bold\">Failed to create invoice payment of " . formatCurrency( $paid ) . " for invoice #" . $actual_id . "!!!</span>";

	// Check to see if our invoice is now paid and if so then change its status
	$result = $ftsdb->update( '`' . DBTABLEPREFIX . "invoices` i", array(
		"status" => STATUS_INVOICE_PAID
	),
		"coalesce((SELECT SUM((ip.price + ip.profit + ip.shipping ) * ip.qty) - i.discount FROM `" . DBTABLEPREFIX . "invoices_products` ip WHERE ip.invoice_id = i.id), 0) - coalesce((SELECT SUM(ipa.paid) FROM `" . DBTABLEPREFIX . "invoices_payments` ipa WHERE ipa.invoice_id = i.id), 0) <= 0"
	);

	switch ( keepsafe( $_GET['reprinttable'] ) ) {
		case 1:
			$finalColumn = ( user_access( 'clms_invoices_deletePayment' ) ) ? createInvoicePaymentDeleteLinkWithImage( $invoicePaymentID, $invoicePaymentID . "_row", "invoices_payments", "payment", $invoiceID ) : "";

			$tableHTML = "
				<tr class=\"even\" id=\"" . $invoicePaymentID . "_row\">
					<td>" . makeDateTime( $datetimestamp ) . "</td>
					<td>" . printInvoicePaymentType( $type ) . "</td>
					<td>" . formatCurrency( $paid ) . "</td>
					<td class=\"center\">" . $finalColumn . "</td>
				</tr>";

			echo $tableHTML;
			break;
		default:
			echo $content;
			break;
	}
}

//================================================
// Returns the HTML for the view invoice page
//================================================
elseif ( $actual_action == 'reprintInvoice' && user_access( 'clms_invoices_reprint' ) ) {
	echo printInvoice( $actual_id );
}

//================================================
// Send an invoice by email
//================================================
elseif ( $actual_action == 'emailInvoice' && user_access( 'clms_invoices_email' ) ) {
	$id            = intval( $_GET['id'] );
	$email_address = sanitize_email( $_POST['email_address'] );
	$message       = nl2br( $_POST['message'] );

	$result = emailMessage( $email_address, $mbp_config['ftsmbp_clms_invoice_company_name'] . " Invoice #" . $id, $message . "<br /><br />" . printEmailInvoice( $id ) );

	$content = ( $result ) ? "	<span class=\"greenText bold\">Successfully sent invoice by email to " . $email_address . "!</span>" : "	<span class=\"redText bold\">Failed to send invoice by email to " . $email_address . "!!!</span>";

	echo $content;
}

//================================================
// Update our notes in the database
//================================================
elseif ( $actual_action == 'createNote' && user_access( 'clms_notes_create' ) ) {
	$datetimestamp = time();
	$client_id     = intval( $_POST['client_id'] );
	$note          = keeptasafe( $_POST['note'] );
	$urgency       = keeptasafe( $_POST['urgency'] );

	$result = $ftsdb->insert( DBTABLEPREFIX . 'notes', array(
		"datetimestamp" => $datetimestamp,
		"client_id"     => $client_id,
		"note"          => $note,
		"urgency"       => $urgency,
	) );
	$noteID = $ftsdb->lastInsertId();

	$content = ( $result ) ? "	<span class=\"greenText bold\">Successfully created note for " . getClientNameFromID( $client_id ) . "!</span>" : "	<span class=\"redText bold\">Failed to create note for " . getClientNameFromID( $client_id ) . "!!!</span>";

	switch ( keepsafe( $_GET['reprinttable'] ) ) {
		case 1:
			$rowColor = ( $urgency != LOW ) ? "redRow" : "greenRow";
			$rowColor = ( $urgency != HIGH && $rowColor == "redRow" ) ? "yellowRow" : $rowColor;

			$finalColumn = ( user_access( 'clms_notes_delete' ) ) ? createDeleteLinkWithImage( $noteID, $noteID . "_row", "notes", "note" ) : "";

			$tableHTML = "
				<tr class=\"" . $rowColor . "\" id=\"" . $noteID . "_row\">
					<td>" . bbcode( $note ) . "</td>
					<td>" . makeDateTime( $datetimestamp ) . "</td>
					<td class=\"center\">" . $finalColumn . "</td>
				</tr>";

			echo $tableHTML;
			break;
		default:
			echo $content;
			break;
	}
}

//================================================
// Update our products in the database
//================================================
elseif ( $actual_action == 'createProduct' && user_access( 'clms_products_create' ) ) {
	$name     = keeptasafe( $_POST['name'] );
	$price    = keepsafe( $_POST['price'] );
	$profit   = keepsafe( $_POST['profit'] );
	$shipping = keepsafe( $_POST['shipping'] );

	$result    = $ftsdb->insert( DBTABLEPREFIX . 'products', array(
		"name"     => $name,
		"price"    => $price,
		"profit"   => $profit,
		"shipping" => $shipping
	) );
	$productID = $ftsdb->lastInsertId();

	$content = ( $result ) ? "	<span class=\"greenText bold\">Successfully created product (" . $name . ")!</span>" : "	<span class=\"redText bold\">Failed to create product (" . $name . ")!!!</span>";

	switch ( keepsafe( $_GET['reprinttable'] ) ) {
		case 1:
			$finalColumn = ( user_access( 'clms_products_delete' ) ) ? createDeleteLinkWithImage( $productID, $productID . "_row", "products", "product" ) : "";

			$tableHTML = "
				<tr class=\"even\" id=\"" . $productID . "_row\">
					<td>" . $name . "</td>
					<td>" . formatCurrency( $price ) . "</td>
					<td>" . formatCurrency( $profit ) . "</td>
					<td>" . formatCurrency( $shipping ) . "</td>
					<td>" . formatCurrency( $price + $profit + $shipping ) . "</td>
					<td class=\"center\">" . $finalColumn . "</td>
				</tr>";

			echo $tableHTML;
			break;
		default:
			echo $content;
			break;
	}
}