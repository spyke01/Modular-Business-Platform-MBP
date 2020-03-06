<?php
/***************************************************************************
 *                               clients.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/



//=========================================================
// Gets a list of client IDs for the current user
//=========================================================
function getMyClientIDs() {
	global $ftsdb, $mbp_config;
	$returnVar = '';

	$accessAllClients = user_access( 'clms_clients_manage_all_clients' );
	$extraSQL         = ( isset( $mbp_config['ftsmbp_clms_only_access_own_clients'] ) && ( ! $mbp_config['ftsmbp_clms_only_access_own_clients'] || ( $mbp_config['ftsmbp_clms_only_access_own_clients'] && $accessAllClients ) ) ) ? '' : "user_id = :user_id";

	$result = $ftsdb->select( DBTABLEPREFIX . "clients", $extraSQL, array(
		":user_id" => $_SESSION['userid']
	), 'id' );

	if ( $result ) {
		foreach ( $result as $row ) {
			$returnVar .= ',' . $row['id'];
		}
		$result = null;
	}

	return ltrim( $returnVar, ',' );
}

//==================================================
// Returns an array of the client data
//==================================================
function getClient( $clientID ) {
	return getDatabaseArray( 'clients', $clientID );
}

//=========================================================
// Gets a clients name from a clientid
//=========================================================
function getClientNameFromID( $clientID ) {
	global $ftsdb;

	$result = $ftsdb->select( DBTABLEPREFIX . "clients", "id = :id LIMIT 1", array(
		":id" => $clientID
	), 'first_name, last_name' );

	if ( $result ) {
		foreach ( $result as $row ) {
			return $row['first_name'] . " " . $row['last_name'];
		}
		$result = null;
	}
}

//=========================================================
// Gets a username from a userid
//=========================================================
function getClientUsernameFromID( $clientID ) {
	return getDatabaseItem( 'clients', 'username', $clientID );
}

//=========================================================
// Gets a clients email address from a clientid
//=========================================================
function getClientEmailAddressFromID( $clientID ) {
	return getDatabaseItem( 'clients', 'email_address', $clientID );
}

//=========================================================
// Gets a clients company from a clientid
//=========================================================
function getClientCompanyFromID( $clientID ) {
	return getDatabaseItem( 'clients', 'company', $clientID );
}

//=========================================================
// Gets a clients email address from an invoiceid
//=========================================================
function getClientEmailAddressFromInvoiceID( $invoiceID ) {
	$clientID = getDatabaseItem( 'invoices', 'client_id', $invoiceID );

	return getDatabaseItem( 'clients', 'email_address', $clientID );
}

//=========================================================
// Gets a clients email address from an orderid
//=========================================================
function getClientEmailAddressFromOrderID( $orderID ) {
	$clientID = getDatabaseItem( 'orders', 'client_id', $orderID );

	return getDatabaseItem( 'clients', 'email_address', $clientID );
}

//=========================================================
// Gets a clients message from a clientid
//=========================================================
function getClientMessageFromID( $clientID ) {
	return getDatabaseItem( 'clients', 'message', $clientID );
}

//=========================================================
// Returns client info block
//=========================================================
function returnClientInfoBlock( $clientID ) {
	global $ftsdb, $mbp_config;

	$clientInfoBlock = "";

	$result = $ftsdb->select( DBTABLEPREFIX . "clients", "id = :id LIMIT 1", array(
		":id" => $clientID
	) );

	if ( $result ) {
		foreach ( $result as $row ) {
			$clientInfoBlock .= ( $row['first_name'] != "" ) ? $row['first_name'] . " " . $row['last_name'] . "<br />" : "";
			$clientInfoBlock .= ( $row['title'] != "" ) ? $row['title'] . "<br />" : "";
			$clientInfoBlock .= ( $row['company'] != "" ) ? $row['company'] . "<br />" : "";
			$clientInfoBlock .= ( $row['street1'] != "" ) ? $row['street1'] . "<br />" : "";
			$clientInfoBlock .= ( $row['street2'] != "" ) ? $row['street2'] . "<br />" : "";
			$clientInfoBlock .= ( $row['city'] != "" ) ? $row['city'] . ", " . $row['state'] . " " . $row['zip'] . "<br />" : "";
			$clientInfoBlock .= ( $row['daytime_phone'] != "" ) ? "Daytime Phone: " . $row['daytime_phone'] . "<br />" : "";
			$clientInfoBlock .= ( $row['nighttime_phone'] != "" ) ? "Nighttime Phone: " . $row['nighttime_phone'] . "<br />" : "";
			$clientInfoBlock .= ( $row['cell_phone'] != "" ) ? "Cell Phone: " . $row['cell_phone'] . "<br />" : "";
			$clientInfoBlock .= ( $row['ftsmbp_clms_invoice_fax'] != "" ) ? "Fax: " . $row['ftsmbp_clms_invoice_fax'] . "<br />" : "";
			$clientInfoBlock .= ( $row['email_address'] != "" ) ? "Email: " . $row['email_address'] . "<br />" : "";
			$clientInfoBlock .= ( $row['website'] != "" ) ? "Website: " . $row['website'] . "<br />" : "";
		}
		$result = null;
	}

	return $clientInfoBlock;
}

//=========================================================
// Gets a list of letters that client's names start with
//=========================================================
function getClientNameLimiter( $current = 'ALL' ) {
	global $ftsdb, $clmsMenus;

	$returnVar = "";
	$current   = ( empty( $current ) ) ? 'ALL' : $current;

	// Prep our IN clause data
	$preparedInClause = $ftsdb->prepareInClauseVariable( getMyClientIDs() );
	$selectBindData   = $preparedInClause['data'];

	// Allow non admins to see some items
	$result = $ftsdb->select( DBTABLEPREFIX . "clients", "id IN (" . $preparedInClause['binds'] . ") AND UCASE(LEFT(company, 1)) != '' ORDER BY company", $selectBindData, 'DISTINCT UCASE(LEFT(company, 1)) AS startsWith, company' );

	if ( $result ) {
		foreach ( $result as $row ) {
			$returnVar .= '
				<a href="' . $clmsMenus['CLIENTS']['link'] . '&startsWith=' . $row['startsWith'] . '" class="btn btn-default' . ( ( $current == $row['startsWith'] ) ? ' active' : '' ) . '">' . $row['startsWith'] . '</a>';
		}
		$result = null;
	}

	$returnVar = '
		<div class="btn-group">
			' . $returnVar . '
			<a href="' . $clmsMenus['CLIENTS']['link'] . '&startsWith=ALL" class="btn btn-default' . ( ( $current == "ALL" ) ? ' active' : '' ) . '">ALL</a>
		</div>
		<br /><br />';

	return $returnVar;
}

//=================================================
// Print the Clients Table
//=================================================
function printClientsTable( $startsWith = 'ALL' ) {
	global $ftsdb, $clmsMenus, $mbp_config;

	// Views
	$extraSQL                      = ( ! empty( $startsWith ) && $startsWith != 'ALL' ) ? " AND UCASE(LEFT(company, 1)) = UCASE(:startsWith)" : "";
	$preparedInClause              = $ftsdb->prepareInClauseVariable( getMyClientIDs() );
	$selectBindData                = $preparedInClause['data'];
	$selectBindData[':startsWith'] = $startsWith;

	$result = $ftsdb->select( DBTABLEPREFIX . "clients", "id IN (" . $preparedInClause['binds'] . ")" . $extraSQL . " ORDER BY company ASC", $selectBindData );

	// Create our new table
	$table = new Table( '', '', '', "table table-striped table-bordered tablesorter", "clientsTable" );

	// Create table title
	$table->addNewRow( array(
		array(
			'data'    => "Current Clients (" . ( ( $result ) ? count( $result ) : 0 ) . ")",
			"colspan" => "5"
		)
	), '', 'title1', 'thead' );

	// Create column headers
	$table->addNewRow(
		array(
			array( 'type' => 'th', 'data' => "Full Name" ),
			array( 'type' => 'th', 'data' => "Company Name" ),
			array( 'type' => 'th', 'data' => "Type of Client" ),
			array( 'type' => 'th', 'data' => "Total Order Value" ),
			array( 'type' => 'th', 'data' => "" )
		), '', 'title2', 'thead'
	);

	// Add our data
	if ( ! $result ) {
		$table->addNewRow( array(
			array(
				'data'    => "There are no clients in the system.",
				"colspan" => "5"
			)
		), "clientsTableDefaultRow", "greenRow" );
	} else {
		foreach ( $result as $row ) {
			$finalColumn = ( user_access( 'clms_clients_edit' ) ) ? "<a href=\"" . $clmsMenus['CLIENTS']['link'] . "&action=editclient&id=" . $row['id'] . "\" class=\"btn btn-default\"><i class=\"glyphicon glyphicon-edit\"></i></a> " : "";
			$finalColumn .= ( user_access( 'clms_clients_delete' ) ) ? createDeleteLinkWithImage( $row['id'], $row['id'] . "_row", "clients", "client" ) : "";

			$table->addNewRow(
				array(
					array( 'data' => $row['last_name'] . ", " . $row['first_name'] ),
					array( 'data' => $row['company'] ),
					array( 'data' => getCatNameByID( $row['cat_id'] ) ),
					array( 'data' => formatCurrency( getTotalInvoiceSumByClientID( $row['id'] ) ), "class" => "right" ),
					array( 'data' => '<span class="btn-group">' . $finalColumn . '</span>', 'class' => 'center' )
				), $row['id'] . "_row", ""
			);
		}
		$result = null;
	}

	// Return the table's HTML
	return getClientNameLimiter( $startsWith ) . $table->returnTableHTML() . "
			<div id=\"clientsTableUpdateNotice\"></div>";
}

//=================================================
// Returns the JQuery functions used to run the 
// clients table
//=================================================
function returnClientsTableJQuery() {
	$JQueryReadyScripts = "
			$('#clientsTable').tablesorter({ widgets: ['zebra'], headers: { 3: { sorter: false } } });";

	return $JQueryReadyScripts;
}

//=================================================
// Print the Highest Paying Clients Table
//=================================================
function printHighestPayingClientsTable( $invoiceLimit = 5 ) {
	global $ftsdb, $clmsMenus, $mbp_config;

	$result = $ftsdb->select( '`' . DBTABLEPREFIX . "invoices` i", "i.id IN (:ids) GROUP BY i.client_id ORDER BY total_ordered DESC LIMIT :limit1", array(
		":ids"    => getMyClientIDs(),
		":limit1" => $invoiceLimit
	), 'sum(coalesce((SELECT SUM((ip.price + ip.profit + ip.shipping ) * ip.qty) FROM `' . DBTABLEPREFIX . 'invoices_products` ip WHERE ip.invoice_id = i.id), 0)) - i.discount AS total_ordered, i.client_id' );

	// Create our new table
	$table = new Table( '', '', '', "table table-striped table-bordered tablesorter", "highestPayingClientsTable" );

	// Create table title
	$table->addNewRow( array( array( 'data' => "Highest Paying Clients", "colspan" => "5" ) ), '', 'title1', 'thead' );

	// Create column headers
	$table->addNewRow(
		array(
			array( 'type' => 'th', 'data' => "Client" ),
			array( 'type' => 'th', 'data' => "Total" )
		), '', 'title2', 'thead'
	);

	// Add our data
	if ( ! $result ) {
		$table->addNewRow( array(
			array(
				'data'    => "There are no orders in the system.",
				"colspan" => "3"
			)
		), "", "greenRow" );
	} else {
		foreach ( $result as $row ) {
			$table->addNewRow(
				array(
					array( 'data' => getClientNameFromID( $row['client_id'] ) ),
					array( 'data' => formatCurrency( $row['total_ordered'] ) )
				), "", ""
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
function returnHighestPayingClientsTableJQuery() {
	$JQueryReadyScripts = "
			$('#highestPayingClientsTable').tablesorter({ widgets: ['zebra'] });";

	return $JQueryReadyScripts;
}

//=================================================
// Create a form to add new clients
//=================================================
function printNewClientForm() {
	global $clmsMenus, $mbp_config;

	// Handle the General Information Tab
	$formFields_generalInformation = array();

	if ( user_access( 'clms_clients_manage_owner' ) ) {
		$formFields_generalInformation['user_id'] = array(
			'text'         => 'User',
			'type'         => 'select',
			'options'      => getDropdownArray( 'users' ),
			'currentValue' => $_SESSION['userid'],
			'class'        => 'required',
		);
	}

	$formFields_generalInformation['cat_id']     = array(
		'text'    => 'Category',
		'type'    => 'select',
		'options' => getDropdownArray( 'clientcategories' ),
		'class'   => 'required',
	);
	$formFields_generalInformation['first_name'] = array(
		'text'  => 'First Name',
		'type'  => 'text',
		'class' => 'required'
	);
	$formFields_generalInformation['last_name']  = array(
		'text'  => 'Last Name',
		'type'  => 'text',
		'class' => 'required'
	);
	$formFields_generalInformation['title']      = array( 'text' => 'Title', 'type' => 'text' );
	$formFields_generalInformation['company']    = array( 'text' => 'Company', 'type' => 'text' );
	$formFields_generalInformation['street1']    = array( 'text' => 'Street (Line 1)', 'type' => 'text' );
	$formFields_generalInformation['street2']    = array( 'text' => 'Street (Line 2)', 'type' => 'text' );
	$formFields_generalInformation['city']       = array( 'text' => TXT_CITY, 'type' => 'text' );
	$formFields_generalInformation['state']      = array( 'text' => TXT_STATE, 'type' => 'text' );
	$formFields_generalInformation['zip']        = array( 'text' => TXT_ZIP, 'type' => 'text' );

	// Handle the Contact Information Tab
	$formFields_contactInformation = array(
		'daytime_phone'    => array(
			'text' => 'Daytime Phone',
			'type' => 'text',
		),
		'nighttime_phone'  => array(
			'text' => 'Nighttime Phone',
			'type' => 'text',
		),
		'cell_phone'       => array(
			'text' => 'Cell Phone',
			'type' => 'text',
		),
		'email_address'    => array(
			'text' => 'Email Address',
			'type' => 'text',
		),
		'website'          => array(
			'text' => 'Website',
			'type' => 'text',
		),
		'found_us_through' => array(
			'text' => 'Found Us Through',
			'type' => 'text',
		),
		'preffered_client' => array(
			'text'  => 'Preferred Customer',
			'type'  => 'checkbox',
			'value' => '1'
		),
	);

	// Handle the Login Information Tab
	$formFields_loginInformation = array(
		'username'  => array(
			'text' => 'Username',
			'type' => 'text',
		),
		'password'  => array(
			'text' => 'Password',
			'type' => 'password',
		),
		'password2' => array(
			'text' => 'Confirm Password',
			'type' => 'password',
		),
	);

	// Assemble our tabs
	$tabbedFormFields = array(
		'generalInformation' => array(
			'title'   => 'General Information',
			'tabData' => $formFields_generalInformation,
		),
		'contactInformation' => array(
			'title'   => 'Contact Information',
			'tabData' => $formFields_contactInformation,
		),
	);
	if ( user_access( 'clms_clients_manage_client_login' ) ) {
		$tabbedFormFields['loginInformation'] = array(
			'title'   => 'Login Information',
			'tabData' => $formFields_loginInformation,
		);
	}
	$tabbedFormFields = apply_filters( 'form_fields_clms_clients_new', $tabbedFormFields );

	return makeForm( 'newClient', il( $clmsMenus['CLIENTS']['link'] ), 'New Client', 'Create Client', $tabbedFormFields, array(), 1 );
}

//=================================================
// Returns the JQuery functions used to run the 
// new client form
//=================================================
function returnNewClientFormJQuery( $reprintTable = 0, $allowModification = 1 ) {
	$extraJQuery = ( $reprintTable == 0 ) ? "
					// Update the proper div with the returned data
					$('#newClientResponse').html(data);
					$('#newClientResponse').effect('highlight',{},500);"
		: "
					// Clear the default row
					$('#clientsTableDefaultRow').remove();
					// Update the table with the new row
					$('#clientsTable > tbody:last').append(data);
					$('#clientsTableUpdateNotice').html('" . tableUpdateNoticeHTML() . "');
					// Show a success message
					$('#newClientResponse').html(returnSuccessMessage('client'));";

	$JQueryReadyScripts = "
		var v = jQuery(\"#newClientForm\").validate({
			errorElement: \"div\",
			errorClass: \"validation-advice\",";

	if ( user_access( 'clms_clients_manage_client_login' ) ) {
		$JQueryReadyScripts .= "
			rules: {
				password2: {
					equalTo: '#password'
				}
			},";
	}

	$JQueryReadyScripts .= "
			submitHandler: function(form) {	
				$('#newClientResponse').html('" . progressSpinnerHTML() . "');		
				jQuery.post('" . SITE_URL . "/ajax.php?action=createClient&reprinttable=" . $reprintTable . "&showButtons=" . $allowModification . "', $('#newClientForm').serialize(), function(data) {
					" . $extraJQuery . "
				});
			}
		});
		$('#clearFormButton').click(function () {
			bootbox.confirm('Are you sure you want to clear this form?', function(result) {
				if ( result == true ) {
					$('#newClientForm').clearForm();
				}
			});
		});";

	return $JQueryReadyScripts;
}

//=================================================
// Create a form to edit clients
//=================================================
function printEditClientForm( $clientID ) {
	global $ftsdb, $clmsMenus, $mbp_config;

	// Prep our IN clause data
	$preparedInClause = $ftsdb->prepareInClauseVariable( getMyClientIDs() );
	$selectBindData   = $preparedInClause['data'];
	$selectBindData   = array_merge( $selectBindData, array(
		":id" => $clientID
	) );

	$result = $ftsdb->select( DBTABLEPREFIX . "clients", "id = :id AND id IN (" . $preparedInClause['binds'] . ") LIMIT 1", $selectBindData );

	if ( ! $result ) {
		$content = '
			<div class="box">
				<div class="box-header">
					<h3><i class="glyphicon glyphicon-warning"></i> ' . __( 'Error' ) . '</h3>
				</div>
				<div class="box-content bold redText">
					<p>' . __( "There was an error while accessing the client's details you are trying to update. You are now being redirected back to the Clients page." ) . '</p>
					<meta http-equiv="refresh" content="5;url=' . $clmsMenus['CLIENTS']['link'] . '">
				</div>
			</div>';
	} else {
		$row = $result[0];

		// Handle the General Information Tab
		$formFields_generalInformation = array();

		if ( user_access( 'clms_clients_manage_owner' ) ) {
			$formFields_generalInformation['user_id'] = array(
				'text'    => 'User',
				'type'    => 'select',
				'options' => getDropdownArray( 'users' ),
				'class'   => 'required',
			);
		}

		$formFields_generalInformation['cat_id']     = array(
			'text'    => 'Category',
			'type'    => 'select',
			'options' => getDropdownArray( 'clientcategories' ),
			'class'   => 'required',
		);
		$formFields_generalInformation['first_name'] = array(
			'text'  => 'First Name',
			'type'  => 'text',
			'class' => 'required'
		);
		$formFields_generalInformation['last_name']  = array(
			'text'  => 'Last Name',
			'type'  => 'text',
			'class' => 'required'
		);
		$formFields_generalInformation['title']      = array( 'text' => 'Title', 'type' => 'text' );
		$formFields_generalInformation['company']    = array( 'text' => 'Company', 'type' => 'text' );
		$formFields_generalInformation['street1']    = array( 'text' => 'Street (Line 1)', 'type' => 'text' );
		$formFields_generalInformation['street2']    = array( 'text' => 'Street (Line 2)', 'type' => 'text' );
		$formFields_generalInformation['city']       = array( 'text' => TXT_CITY, 'type' => 'text' );
		$formFields_generalInformation['state']      = array( 'text' => TXT_STATE, 'type' => 'text' );
		$formFields_generalInformation['zip']        = array( 'text' => TXT_ZIP, 'type' => 'text' );

		// Handle the Contact Information Tab
		$formFields_contactInformation = array(
			'daytime_phone'    => array(
				'text' => 'Daytime Phone',
				'type' => 'text',
			),
			'nighttime_phone'  => array(
				'text' => 'Nighttime Phone',
				'type' => 'text',
			),
			'cell_phone'       => array(
				'text' => 'Cell Phone',
				'type' => 'text',
			),
			'email_address'    => array(
				'text' => 'Email Address',
				'type' => 'text',
			),
			'website'          => array(
				'text' => 'Website',
				'type' => 'text',
			),
			'found_us_through' => array(
				'text' => 'Found Us Through',
				'type' => 'text',
			),
			'preffered_client' => array(
				'text'  => 'Preferred Customer',
				'type'  => 'checkbox',
				'value' => '1'
			),
		);

		// Handle the Login Information Tab
		$formFields_loginInformation = array(
			'username'  => array(
				'text' => 'Username',
				'type' => 'text',
			),
			'password'  => array(
				'text' => 'Password',
				'type' => 'password',
			),
			'password2' => array(
				'text' => 'Confirm Password',
				'type' => 'password',
			),
		);

		// Assemble our tabs
		$tabbedFormFields = array(
			'generalInformation' => array(
				'title'   => 'General Information',
				'tabData' => $formFields_generalInformation,
			),
			'contactInformation' => array(
				'title'   => 'Contact Information',
				'tabData' => $formFields_contactInformation,
			),
		);
		if ( user_access( 'clms_clients_manage_client_login' ) ) {
			$tabbedFormFields['loginInformation'] = array(
				'title'   => 'Login Information',
				'tabData' => $formFields_loginInformation,
			);
		}
		$tabbedFormFields = apply_filters( 'form_fields_clms_clients_edit', $tabbedFormFields );

		$content = makeForm( 'editClient', il( $clmsMenus['CLIENTS']['link'] . "&action=editclient&id=" . $actual_id ), 'Edit Client', 'Update Client\'s Details', $tabbedFormFields, $row, 1 );
		$result  = null;
	}

	return $content;
}

//=================================================
// Returns the JQuery functions used to run the 
// edit client form
//=================================================
function returnEditClientFormJQuery( $clientID ) {

	$JQueryReadyScripts = "
		var v = jQuery(\"#editClientForm\").validate({
			errorElement: \"div\",
			errorClass: \"validation-advice\",";

	if ( user_access( 'clms_clients_manage_client_login' ) ) {
		$JQueryReadyScripts .= "
			rules: {
				password2: {
					equalTo: '#password'
				}
			},";
	}

	$JQueryReadyScripts .= "
			submitHandler: function(form) {	
				$('#editClientResponse').html('" . progressSpinnerHTML() . "');		
				jQuery.post('" . SITE_URL . "/ajax.php?action=updateClient&id=" . $clientID . "', $('#editClientForm').serialize(), function(data) {
					// Update the proper div with the returned data
					$('#editClientResponse').html(data);
					$('#editClientResponse').effect('highlight',{},500);
				});
			}
		});";

	return $JQueryReadyScripts;
}