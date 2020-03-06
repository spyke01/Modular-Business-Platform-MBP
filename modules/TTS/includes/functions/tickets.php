<?php
/***************************************************************************
 *                               tickets.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/



//=========================================================
// Gets a status name from a statusid
//=========================================================
function getTicketStatus( $status ) {
	global $TICKET_STATUS;

	if ( $status < count( $TICKET_STATUS ) ) {
		return $TICKET_STATUS[ $status ];
	} else {
		return "Unknown Status";
	}
}

//=========================================================
// Gets a tickets owner from a userid
//=========================================================
function getTicketOwnerFromID( $ticketID ) {
	$idField = ( isModuleActivated( 'CLMS' ) ) ? 'client_id' : 'user_id';

	return getDatabaseItem( 'tickets', $idField, $ticketID );
}

//=========================================================
// Gets a tickets tech from a userid
//=========================================================
function getTicketTechFromID( $ticketID ) {
	return getDatabaseItem( 'tickets', 'tech_id', $ticketID );
}

//=========================================================
// Gets a tickets title from a userid
//=========================================================
function getTicketTitleFromID( $ticketID ) {
	return getDatabaseItem( 'tickets', 'title', $ticketID );
}

//=========================================================
// Sends update emails for tickets
// UpdateTypes
//    0 = New Ticket
// 	  1 = Ticket Reply
//=========================================================
function sendTicketEMail( $ticketID, $userID, $updateType ) {
	global $ftsdb, $mbp_config, $ttsMenus, $LANG;

	// Find out if we are emailing the admins or the users
	$ticketOwnerID = getTicketOwnerFromID( $ticketID );
	$ticketTechID  = getTicketTechFromID( $ticketID );
	$name          = ( $_SESSION['is_client'] ) ? getClientNameFromID( $userID ) : getUsernameFromID( $userID );
	$status        = getTicketStatus( $status );
	$lastEntry     = "";
	$sendMessage   = 1;

	if ( $userID == $ticketOwnerID ) {
		// The user was the one who made the update
		$sendMessage  = ( $mbp_config['ftsmbp_tts_sendUpdateNoticeToTechs'] == 1 ) ? 1 : 0;
		$emailAddress = ( $updateType == 1 ) ? getEmailAddressFromID( $ticketTechID ) : "";
		$emailAddress = ( $emailAddress == "" ) ? $mbp_config['ftsmbp_admin_email'] : $emailAddress;
	} else {
		// An admin was the one who made the update
		$sendMessage = ( $mbp_config['ftsmbp_tts_sendUpdateNoticeToClients'] == 1 ) ? 1 : 0;
		if ( isModuleActivated( 'CLMS' ) ) {
			$emailAddress = ( $updateType == 1 ) ? getClientEmailAddressFromID( $ticketOwnerID ) : "";
			$emailAddress = ( $emailAddress == "" ) ? getClientEmailAddressFromID( $userID ) : $emailAddress;
		} else {
			$emailAddress = ( $updateType == 1 ) ? getEmailAddressFromID( $ticketOwnerID ) : "";
			$emailAddress = ( $emailAddress == "" ) ? getEmailAddressFromID( $userID ) : $emailAddress;
		}
	}

	// Get the latest entry
	$result = $ftsdb->select( DBTABLEPREFIX . "entries", "ticket_id = :ticket_id ORDER BY datetimestamp DESC LIMIT 1", array(
		":ticket_id" => $ticketID
	), 'text' );

	// Add our data
	if ( $result ) {
		foreach ( $result as $row ) {
			$lastEntry = bbcode( $row['text'] );
		}
		$result = null;
	}

	// Create our subject and message
	$mainURL        = "http://" . $_SERVER['HTTP_HOST'] . rtrim( dirname( $_SERVER['PHP_SELF'] ), '/\\' ) . "/";
	$viewTicketURL  = "http://" . $_SERVER['HTTP_HOST'] . rtrim( dirname( $_SERVER['PHP_SELF'] ), '/\\' ) . "/" . $ttsMenus['VIEWTICKET']['link'] . "&id=" . $ticketID;
	$mainLink       = "<a href=\"$mainURL\">$mainURL</a>";
	$viewTicketLink = "<a href=\"$viewTicketURL\">$viewTicketURL</a>";
	$tags           = array(
		'SITE_NAME'       => $mbp_config['ftsmbp_site_name'],
		'MAIN_URL'        => $mainLink,
		'VIEW_TICKET_URL' => $viewTicketLink,
		'NAME'            => $name,
		'ID'              => $ticketID,
		'STATUS'          => $status,
		'LAST_ENTRY'      => $lastEntry,
	);
	$success        = emailMessage( $email_address,
		parseForTagsFromArray( getEmailTemplateSubjectFromID( 'mbp-account-created' ), $dataArray ),
		parseForTagsFromArray( getEmailTemplateMessageFromID( 'mbp-account-created' ), $dataArray )
	);

	$template_id = ( $updateType == 0 ) ? 'tts-ticket-created' : 'tts-ticket-updated';

	// Send the message
	if ( $sendMessage ) {
		$success = emailMessage( $emailAddress,
			parseForTagsFromArray( getEmailTemplateSubjectFromID( $template_id ), $tags, 0 ),
			parseForTagsFromArray( getEmailTemplateMessageFromID( $template_id ), $tags, 0 )
		);
	}
}

//=================================================
// Print the Tickets Table
//=================================================
function printViewTicketTable( $ticketID ) {
	global $ftsdb, $menuvar, $ttsMenus, $mbp_config, $LANG;

	$result = $ftsdb->select( DBTABLEPREFIX . "tickets", "id = :id", array(
		":id" => $ticketID
	) );

	// Create our new table
	$table = new Table( '', '', '', "table table-striped table-bordered tablesorter", "viewTicketTable" );

	// Create table title
	$table->addNewRow( array(
		array(
			'data'    => $LANG['TABLETITLES_TICKET'] . " #" . $ticketID . "",
			"colspan" => "8"
		)
	), '', 'title1', 'thead' );

	// Create section title
	$table->addNewRow( array(
		array(
			'data'    => $LANG['TABLEHEADERS_TICKET_INFORMATION'],
			"colspan" => "2"
		)
	), '', 'title2', 'thead' );

	// Add our data
	if ( ! $result ) {
		$table->addNewRow( array(
			array(
				'data'    => $LANG['ERROR_NO_TICKET_INFORMATION'],
				"colspan" => "2"
			)
		), "", "greenRow" );
	} else {
		foreach ( $result as $row ) {
			$ticketUserTitle = $LANG['TABLEHEADERS_USER'];
			$ticketUser      = ( user_access( 'tts_tickets_change_user' ) ) ? createDropdown( "users", "user_id", $row['user_id'], "ajaxGetWithProgress('updateUserSpinner', '" . SITE_URL . "/ajax.php?action=updateitem&table=tickets&item=user_id&id=" . $ticketID . "&value=' + $('#user_id').val())", "" ) . "<span id=\"updateUserSpinner\" style=\"display: none;\">" . progressSpinnerHTML() . "</span>" : getUsernameFromID( $row['user_id'] );
			if ( isModuleActivated( 'CLMS' ) ) {
				$ticketUserTitle = 'Client';
				$ticketUser      = ( user_access( 'tts_tickets_change_client' ) ) ? createDropdown( "clients", "client_id", $row['client_id'], "ajaxGetWithProgress('updateUserSpinner', '" . SITE_URL . "/ajax.php?action=updateitem&table=tickets&item=client_id&id=" . $ticketID . "&value=' + $('#client_id').val())", "" ) . "<span id=\"updateUserSpinner\" style=\"display: none;\">" . progressSpinnerHTML() . "</span>" : getClientNameFromID( $row['client_id'] );
			}
			$ticketTech   = ( user_access( 'tts_tickets_change_tech' ) ) ? createDropdown( "techs", "tech_id", $row['tech_id'], "ajaxGetWithProgress('updateTechSpinner', '" . SITE_URL . "/ajax.php?action=updateitem&table=tickets&item=tech_id&id=" . $ticketID . "&value=' + $('#tech_id').val())", "" ) . "<span id=\"updateTechSpinner\" style=\"display: none;\">" . progressSpinnerHTML() . "</span>" : getUsernameFromID( $row['tech_id'] );
			$ticketStatus = ( user_access( 'tts_tickets_change_status' ) ) ? createDropdown( "ticketstatus", "status", $row['status'], "ajaxGetWithProgress('updateStatusSpinner', '" . SITE_URL . "/ajax.php?action=updateitem&table=tickets&item=status&id=" . $ticketID . "&value=' + $('#status').val())", "" ) . "<span id=\"updateStatusSpinner\" style=\"display: none;\">" . progressSpinnerHTML() . "</span>" : getTicketStatus( $row['status'] );

			// Add our data
			$table->addNewRow(
				array(
					array( 'type' => 'th', 'data' => $LANG['TABLEHEADERS_TITLE'] ),
					array( 'data' => $row['title'] )
				), "", "row1", "thead"
			);
			$table->addNewRow(
				array(
					array( 'type' => 'th', 'data' => $ticketUserTitle ),
					array( 'data' => $ticketUser )
				), "", "row2", "thead"
			);
			$table->addNewRow(
				array(
					array( 'type' => 'th', 'data' => $LANG['TABLEHEADERS_PROBLEM_CATEGORY'] ),
					array( 'data' => getCatNameByID( $row['cat_id'] ) )
				), "", "row1", "thead"
			);
			$table->addNewRow(
				array(
					array( 'type' => 'th', 'data' => $LANG['TABLEHEADERS_TECHNICIAN'] ),
					array( 'data' => $ticketTech )
				), "", "row2", "thead"
			);
			$table->addNewRow(
				array(
					array( 'type' => 'th', 'data' => $LANG['TABLEHEADERS_DATE_CREATED'] ),
					array( 'data' => makeShortDateTime( $row['datetimestamp'] ) )
				), "", "row1", "thead"
			);
			$table->addNewRow(
				array(
					array( 'type' => 'th', 'data' => $LANG['TABLEHEADERS_STATUS'] ),
					array( 'data' => $ticketStatus )
				), "", "row2", "thead"
			);
		}
		$result = null;
	}

	// Create section title
	$table->addNewRow( array(
		array(
			'data'    => $LANG['TABLEHEADERS_TICKET_ENTRIES'],
			"colspan" => "8"
		)
	), '', 'title2', 'thead' );

	$result = $ftsdb->select( DBTABLEPREFIX . "entries", "ticket_id = :ticket_id ORDER BY datetimestamp ASC", array(
		":ticket_id" => $ticketID
	) );

	// Add our data
	if ( ! $result ) {
		$table->addNewRow( array(
			array(
				'data'    => $LANG['ERROR_NO_TICKET_ENTRIES'],
				"colspan" => "8"
			)
		), "viewTicketTableDefaultRow", "greenRow" );
	} else {
		$x = 1;

		foreach ( $result as $row ) {
			$username     = ( $row['is_client'] ) ? getClientNameFromID( $row['user_id'] ) : getUsernameFromID( $row['user_id'] );
			$deleteButton = ( user_access( 'tts_tickets_delete' ) ) ? "<br />" . createDeleteLinkWithImage( $row['id'], $row['id'] . "_row", "entries", "entry" ) : "";
			$table->addNewRow(
				array(
					array(
						'data'  => "<strong>" . $username . "</strong><br />" . makeShortDateTime( $row['datetimestamp'] ) . $deleteButton,
						'class' => 'center'
					),
					array( 'data' => bbcode( $row['text'] ) )
				), $row['id'] . "_row", "row" . $x
			);

			$x = ( $x == 1 ) ? 2 : 1;
		}
		$result = null;
	}

	// Return the table's HTML
	return $table->returnTableHTML() . "
			<div id=\"viewTicketTableUpdateNotice\"></div>";
}

//=================================================
// Returns the JQuery functions used to run the 
// tickets table
//=================================================
function returnViewTicketTableJQuery() {
	$JQueryReadyScripts = "";

	return $JQueryReadyScripts;
}

//=================================================
// Print the Tickets Entries Table
//=================================================
function printViewTicketEntriesTable( $ticketID ) {
	global $ftsdb, $menuvar, $ttsMenus, $mbp_config, $LANG;
	$startingEntry = $replies = array();
	$returnVar     = '';

	$ticketTitle = getDatabaseItem( 'tickets', 'title', $ticketID );

	$result = $ftsdb->select( DBTABLEPREFIX . "entries", "ticket_id = :ticket_id ORDER BY datetimestamp ASC", array(
		":ticket_id" => $ticketID
	) );

	// Split our data into the proper arrays
	if ( $result ) {
		$x = 1;

		foreach ( $result as $row ) {
			if ( $x == 1 ) {
				$startingEntry = $row;
			} else {
				$replies[] = $row;
			}

			$x ++;
		}
		$result = null;
	}

	// Create our starting ticket entry table
	if ( count( $startingEntry ) == 0 ) {
		$returnVar .= returnBoxHTML( $ticketTitle, __( 'There was an error while accessing the information for this ticket.' ) );
	} else {
		$returnVar .= returnBoxHTML( $ticketTitle, bbcode( $startingEntry['text'] ) );
	}

	// Create our ticket replies table
	if ( count( $replies ) == 0 ) {
		$returnVar .= '<br />' . returnBoxHTML( __( 'Ticket Updates' ), __( '<div class="alert alert-warning" id="viewTicketTableDefaultRow">There are no entries for this ticket in the system.</div>' ), 'viewTicketEntriesTable' );
	} else {
		$updates = $lastUserID = '';
		$imgSide = 'left';

		foreach ( $replies as $key => $row ) {
			$updates .= returnTicketUpdateChatBubble( $row, $imgSide );

			if ( $lastUserID != $updateData['user_id'] ) {
				$imgSide = ( $imgSide == 'left' ) ? 'right' : 'left';
			}
			$lastUserID = $updateData['user_id'];
		}

		$returnVar .= '<br />' . returnBoxHTML( __( 'Ticket Updates' ), $updates, 'viewTicketEntriesTable' );
	}

	// Return the table's HTML
	return $returnVar . '
		<div id="viewTicketEntriesTableUpdateNotice"></div>';
}

//=================================================
// Returns the JQuery functions used to run the 
// tickets table
//=================================================
function returnViewTicketEntriesTableJQuery() {
	$JQueryReadyScripts = "";

	return $JQueryReadyScripts;
}

//=================================================
// Returns the HTML block for a ticket update
//=================================================
function returnTicketUpdateChatBubble( $updateData, $imgSide = 'left' ) {
	$columns     = $imgCol = $updateCol = '';
	$updateClass = ( $imgSide == 'left' ) ? 'alert' : 'alert alert-info';

	if ( ( $updateData['is_client'] ) ) {
		$userData = getClient( $updateData['user_id'] );
		$username = $userData['first_name'] . ' ' . $userData['last_name'];
	} else {
		$userData = getUser( $updateData['user_id'] );
		$username = $userData['username'];
	}

	$deleteButton = ( user_access( 'tts_tickets_delete' ) ) ? createDeleteLinkWithImage( $updateData['id'], $updateData['id'] . "_row", "entries", "entry" ) : '';

	// Build our columns					
	$imgCol    = '
			<div class="col-xs-3 col-sm-2">
				<img class="img-circle" alt="Avatar" src="' . get_gravatar( $userData['email_address'], 40 ) . '"><br />
				<strong>' . $username . '</strong>
			</div>';
	$updateCol = '
			<div class="col-xs-9 col-sm-10">
				<div class="speechbubble ' . $updateClass . '">
					<span class="pull-right">' . makeShortDateTime( $updateData['datetimestamp'] ) . ' ' . $deleteButton . '</span>
					' . bbcode( $updateData['text'] ) . '
				</div>
			</div>';

	// Determine how to display the data
	$columns = ( $imgSide == 'left' ) ? $imgCol . $updateCol : $updateCol . $imgCol;

	return '
		<div class="row" id="' . $updateData['id'] . '_row">
			' . $columns . '
		</div>';
}

//=================================================
// Print the Tickets Details Table
//=================================================
function printViewTicketDetailsTable( $ticketID ) {
	global $ftsdb, $menuvar, $ttsMenus, $mbp_config, $LANG;

	$result = $ftsdb->select( DBTABLEPREFIX . "tickets", "id = :id", array(
		":id" => $ticketID
	) );

	// Create our new table
	$table = new Table( '', '', '', "table table-striped table-bordered tablesorter", "viewTicketDetailsTable" );

	// Create table title
	$table->addNewRow( array( array( 'data' => __( 'Details' ), "colspan" => "8" ) ), '', 'title1', 'thead' );

	// Add our data
	if ( ! $result ) {
		$table->addNewRow( array(
			array(
				'data'    => $LANG['ERROR_NO_TICKET_INFORMATION'],
				"colspan" => "2"
			)
		), "", "greenRow" );
	} else {
		foreach ( $result as $row ) {
			$ticketUserTitle = $LANG['TABLEHEADERS_USER'];
			$ticketUser      = ( user_access( 'tts_tickets_change_user' ) ) ? createDropdown( "users", "user_id", $row['user_id'], "ajaxGetWithProgress('updateUserSpinner', '" . SITE_URL . "/ajax.php?action=updateitem&table=tickets&item=user_id&id=" . $ticketID . "&value=' + $('#user_id').val())", "" ) . "<span id=\"updateUserSpinner\" style=\"display: none;\">" . progressSpinnerHTML() . "</span>" : getUsernameFromID( $row['user_id'] );
			if ( isModuleActivated( 'CLMS' ) ) {
				$ticketUserTitle = 'Client';
				$ticketUser      = ( user_access( 'tts_tickets_change_client' ) ) ? createDropdown( "clients", "client_id", $row['client_id'], "ajaxGetWithProgress('updateUserSpinner', '" . SITE_URL . "/ajax.php?action=updateitem&table=tickets&item=client_id&id=" . $ticketID . "&value=' + $('#client_id').val())", "" ) . "<span id=\"updateUserSpinner\" style=\"display: none;\">" . progressSpinnerHTML() . "</span>" : getClientNameFromID( $row['client_id'] );
			}
			$ticketTech   = ( user_access( 'tts_tickets_change_tech' ) ) ? createDropdown( "techs", "tech_id", $row['tech_id'], "ajaxGetWithProgress('updateTechSpinner', '" . SITE_URL . "/ajax.php?action=updateitem&table=tickets&item=tech_id&id=" . $ticketID . "&value=' + $('#tech_id').val())", "" ) . "<span id=\"updateTechSpinner\" style=\"display: none;\">" . progressSpinnerHTML() . "</span>" : getUsernameFromID( $row['tech_id'] );
			$ticketStatus = ( user_access( 'tts_tickets_change_status' ) ) ? createDropdown( "ticketstatus", "status", $row['status'], "ajaxGetWithProgress('updateStatusSpinner', '" . SITE_URL . "/ajax.php?action=updateitem&table=tickets&item=status&id=" . $ticketID . "&value=' + $('#status').val())", "" ) . "<span id=\"updateStatusSpinner\" style=\"display: none;\">" . progressSpinnerHTML() . "</span>" : getTicketStatus( $row['status'] );

			// Add our data
			$table->addNewRow(
				array(
					array( 'type' => 'th', 'data' => __( 'Ticket ID' ) ),
					array( 'data' => $ticketID )
				), "", "row2", "thead"
			);
			$table->addNewRow(
				array(
					array( 'type' => 'th', 'data' => __( $ticketUserTitle ) ),
					array( 'data' => $ticketUser )
				), "", "row2", "thead"
			);
			$table->addNewRow(
				array(
					array( 'type' => 'th', 'data' => __( 'Problem Category' ) ),
					array( 'data' => getCatNameByID( $row['cat_id'] ) )
				), "", "row1", "thead"
			);
			$table->addNewRow(
				array(
					array( 'type' => 'th', 'data' => __( 'Technician' ) ),
					array( 'data' => $ticketTech )
				), "", "row2", "thead"
			);
			$table->addNewRow(
				array(
					array( 'type' => 'th', 'data' => __( 'Date Created' ) ),
					array( 'data' => makeShortDateTime( $row['datetimestamp'] ) )
				), "", "row1", "thead"
			);
			$table->addNewRow(
				array(
					array( 'type' => 'th', 'data' => __( 'Status' ) ),
					array( 'data' => $ticketStatus )
				), "", "row2", "thead"
			);
		}
		$result = null;
	}

	// Return the table's HTML
	return $table->returnTableHTML() . "
			<div id=\"viewTicketDetailsTableUpdateNotice\"></div>";
}

//=================================================
// Returns the JQuery functions used to run the 
// tickets table
//=================================================
function returnViewTicketDetailsTableJQuery() {
	$JQueryReadyScripts = "";

	return $JQueryReadyScripts;
}

//=================================================
// Print the Search Tickets Form
//=================================================
function printSearchTicketsTable( $getVars = array() ) {
	global $menuvar, $ttsMenus, $mbp_config, $LANG;

	$formFields = array(
		array(
			'type'  => 'html',
			'value' => __( 'Choose any or all of the following to search by.' )
		),
		'id'    => array(
			'text'         => __( 'ID' ),
			'type'         => 'text',
			'currentValue' => intval( $getVars['id'] ),
		),
		'title' => array(
			'text'         => __( 'Title' ),
			'type'         => 'text',
			'currentValue' => keeptasafe( $getVars['title'] ),
		),
	);
	if ( user_access( 'tts_tickets_search_by_user' ) ) {
		if ( isModuleActivated( 'CLMS' ) ) {
			$formFields['client_id'] = array(
				'text'         => __( 'Client' ),
				'type'         => 'select',
				'options'      => getDropdownArray( 'clients' ),
				'currentValue' => intval( $getVars['client_id'] ),
			);
		} else {
			$formFields['user_id'] = array(
				'text'         => __( 'User' ),
				'type'         => 'select',
				'options'      => getDropdownArray( 'users' ),
				'currentValue' => intval( $getVars['user_id'] ),
			);
		}

	}
	// We don't want the order messed with so do the individual assignments
	$formFields['tech_id'] = array(
		'text'         => __( 'Technician' ),
		'type'         => 'select',
		'options'      => getDropdownArray( 'techs' ),
		'currentValue' => intval( $getVars['tech_id'] ),
	);
	apply_filters( 'form_fields_tts_tickets_search', $formFields );

	return makeForm( 'searchTickets', il( $ttsMenus['TICKETS']['link'] ), __( 'Search Tickets' ), __( 'Search!' ), $formFields, array(), 1 );
}

//=================================================
// Returns the JQuery functions used to run the 
// search ticket form
//=================================================
function returnSearchTicketsTableJQuery() {
	return makeFormJQuery( 'searchTickets', SITE_URL . '/ajax.php?action=searchTickets', '', '', '', '', 'updateMeTickets' );
}

//=================================================
// Print the Tickets Table
//=================================================
function printTicketsTable( $getVars = array() ) {
	global $ftsdb, $menuvar, $ttsMenus, $mbp_config, $LANG;

	// Allow us to pick ticket state
	$ticketStatus     = $getVars['status'];
	$ticketStatusText = ( $ticketStatus != "" ) ? " - " . getTicketStatus( $ticketStatus ) . " " . $LANG['TABLETITLES_CURRENT_TICKETS_ONLY'] : " - " . $LANG['TABLETITLES_CURRENT_ALL_TICKETS'];

	// Allow Searching
	$search_ticketID       = intval( $getVars['id'] );
	$search_ticketTitle    = keeptasafe( $getVars['title'] );
	$search_ticketUserID   = intval( $getVars['user_id'] );
	$search_ticketClientID = intval( $getVars['client_id'] );
	$search_ticketTechID   = intval( $getVars['tech_id'] );

	// Create extra SQL for our query
	$extraSQL = "";
	if ( ( user_access( 'tts_tickets_search_by_user' ) ) ) {
		if ( isModuleActivated( 'CLMS' ) ) {
			$extraSQL .= ( $search_ticketClientID != "" ) ? " AND client_id = :search_ticketClientID" : "";
		} else {
			$extraSQL .= ( $search_ticketUserID != "" ) ? " AND user_id = :search_ticketUserID" : "";
		}
	} else {
		if ( isModuleActivated( 'CLMS' ) ) {
			$extraSQL .= " AND client_id = :userID";
		} else {
			$extraSQL .= " AND user_id = :userID";
		}
	}
	if ( $search_ticketID != "" ) {
		$extraSQL .= " AND id = :search_ticketID";
	}
	if ( $search_ticketTitle != "" ) {
		$extraSQL .= " AND title LIKE :search_ticketTitle";
	}
	if ( $search_ticketTechID != "" ) {
		$extraSQL .= " AND tech_id = :search_ticketTechID";
	}
	if ( $ticketStatus != "" ) {
		$extraSQL .= " AND status = :ticketStatus";
	}

	// Execute our custom query
	$result = $ftsdb->select( DBTABLEPREFIX . "tickets", '1' . $extraSQL . " ORDER BY title ASC", array(
		":search_ticketClientID" => $search_ticketClientID,
		":search_ticketUserID"   => $search_ticketUserID,
		":userID"                => $_SESSION['userid'],
		":search_ticketID"       => $search_ticketID,
		":search_ticketTitle"    => '%' . $search_ticketTitle . '%',
		":search_ticketTechID"   => $search_ticketTechID,
		":ticketStatus"          => $ticketStatus,
	) );

	$numRows = ( $result ) ? count( $result ) : 0;

	// Create our new table
	$table = new Table( '', '', '', "table table-striped table-bordered tablesorter", "ticketsTable" );

	// Create table title
	$table->addNewRow( array(
		array(
			'data'    => $LANG['TABLETITLES_CURRENT_TICKETS'] . " (" . $numRows . ")" . $ticketStatusText,
			"colspan" => "8"
		)
	), '', 'title1', 'thead' );

	// Create column headers
	$table->addNewRow(
		array(
			array( 'type' => 'th', 'data' => $LANG['TABLEHEADERS_ID'], 'class' => 'visible-lg' ),
			array( 'type' => 'th', 'data' => $LANG['TABLEHEADERS_TITLE'] ),
			array( 'type' => 'th', 'data' => $LANG['TABLEHEADERS_USER'], 'class' => 'hidden-sm' ),
			array( 'type' => 'th', 'data' => $LANG['TABLEHEADERS_PROBLEM_CATEGORY'], 'class' => 'visible-lg' ),
			array( 'type' => 'th', 'data' => $LANG['TABLEHEADERS_TECHNICIAN'] ),
			array( 'type' => 'th', 'data' => $LANG['TABLEHEADERS_DATE_CREATED'], 'class' => 'visible-lg' ),
			array( 'type' => 'th', 'data' => $LANG['TABLEHEADERS_STATUS'] ),
			array( 'type' => 'th', 'data' => "" )
		), '', 'title2', 'thead'
	);

	// Add our data
	if ( ! $result ) {
		$table->addNewRow( array(
			array(
				'data'    => $LANG['ERROR_NO_TICKETS'],
				"colspan" => "8"
			)
		), "ticketsTableDefaultRow", "greenRow" );
	} else {
		foreach ( $result as $row ) {
			$rowColor = ( $row['status'] == 0 ) ? "greenRow" : "redRow";
			$rowColor = ( $row['status'] == 2 ) ? "yellowRow" : $rowColor;
			$username = ( isModuleActivated( 'CLMS' ) ) ? getClientNameFromID( $row['user_id'] ) : getUsernameFromID( $row['user_id'] );

			$table->addNewRow(
				array(
					array( 'data' => $row['id'], 'class' => 'visible-lg' ),
					array( 'data' => '<a href="' . $ttsMenus['VIEWTICKET']['link'] . '&id=' . $row['id'] . '">' . $row['title'] . '</a>' ),
					array( 'data' => $username, 'class' => 'hidden-sm' ),
					array( 'data' => getCatNameByID( $row['cat_id'] ), 'class' => 'visible-lg' ),
					array( 'data' => getUsernameFromID( $row['tech_id'] ) ),
					array( 'data' => makeShortDateTime( $row['datetimestamp'] ), 'class' => 'visible-lg' ),
					array( 'data' => getTicketStatus( $row['status'] ) ),
					array(
						'data'  => '<span class="btn-group">' . $finalColumn . '<a href="' . $ttsMenus['VIEWTICKET']['link'] . '&id=' . $row['id'] . '"  class="btn btn-default"><i class="glyphicon glyphicon-edit"></i></a> ' . createDeleteLinkWithImage( $row['id'], $row['id'] . '_row', 'tickets', 'ticket' ) . '</span>',
						'class' => 'center'
					)
				), $row['id'] . '_row', $rowColor
			);
		}
		$result = null;
	}

	// Return the table's HTML
	return $table->returnTableHTML() . "
			<div id=\"ticketsTableUpdateNotice\"></div>";
}

//=================================================
// Returns the JQuery functions used to run the 
// tickets table
//=================================================
function returnTicketsTableJQuery() {
	$JQueryReadyScripts = "
			$('#ticketsTable').tablesorter({ headers: { 7: { sorter: false } } });";

	return $JQueryReadyScripts;
}

//=================================================
// Create a form to add new tickets
//=================================================
function printNewTicketForm( $clientID = '' ) {
	global $menuvar, $mbp_config, $LANG;

	$formFields = array(
		'title'  => array(
			'text'  => __( 'Title' ),
			'type'  => 'text',
			'class' => 'required',
		),
		'cat_id' => array(
			'text'    => __( 'Problem Category ' ),
			'type'    => 'select',
			'options' => getDropdownArray( 'techs' ),
		),
	);
	if ( user_access( 'tts_tickets_change_user' ) ) {
		if ( isModuleActivated( 'CLMS' ) ) {
			$formFields['client_id'] = array(
				'text'         => __( 'Client' ),
				'type'         => 'select',
				'options'      => getDropdownArray( 'clients' ),
				'currentValue' => $clientID,
			);
		} else {
			$formFields['user_id'] = array(
				'text'    => __( 'User' ),
				'type'    => 'select',
				'options' => getDropdownArray( 'users' ),
			);
		}
	}
	if ( user_access( 'tts_tickets_change_tech' ) ) {
		$formFields['tech_id'] = array(
			'text'    => __( 'Technician' ),
			'type'    => 'select',
			'options' => getDropdownArray( 'techs' ),
		);
	}
	// We don't want the order messed with so do the individual assignments
	$formFields['text'] = array(
		'text' => __( 'Problem' ),
		'type' => 'textarea',
	);
	apply_filters( 'form_fields_tts_tickets_new', $formFields );

	return makeForm( 'newTicket', il( $ttsMenus['TICKETS']['link'] ), '<i class="glyphicons glyphicons-pen"></i> ' . __( 'New Ticket' ), 'Create Ticket', $formFields, array(), 1, 1 );
}

//=================================================
// Returns the JQuery functions used to run the 
// new ticket form
//=================================================
function returnNewTicketFormJQuery( $reprintTable = 0, $allowModification = 1 ) {
	$table = ( $reprintTable == 0 ) ? '' : 'ticketsTable';

	return makeFormJQuery( 'newTicket', SITE_URL . "/ajax.php?action=createTicket&reprinttable=" . $reprintTable . "&showButtons=" . $allowModification, $table, 'ticket' );
}

//=================================================
// Create a form to add new tickets
//=================================================
function printNewTicketEntryForm() {
	global $menuvar, $mbp_config, $LANG;

	$userLabel  = ( $_SESSION['is_client'] ) ? 'Name' : 'User';
	$userText   = ( $_SESSION['is_client'] ) ? $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] : $_SESSION['username'];
	$formFields = apply_filters( 'form_fields_tts_ticket_entries_new', array(
		'user_id' => array(
			'text'  => $userLabel,
			'type'  => 'plainText',
			'value' => $userText,
		),
		'text'    => array(
			'text'  => 'Message',
			'type'  => 'textarea',
			'class' => 'required',
		),
	) );

	return makeForm( 'newTicketEntry', il( $ttsMenus['TICKETS']['link'] ), '<i class="glyphicons glyphicons-pen"></i> ' . __( 'Reply' ), __( 'Submit' ), $formFields, array(), 1 );
}

//=================================================
// Returns the JQuery functions used to run the 
// new ticket form
//=================================================
function returnNewTicketEntryFormJQuery( $ticketID, $reprintTable = 0 ) {
	$customSuccessFunction = ( $reprintTable == 0 ) ? '
		// Update the proper div with the returned data
		$("#newTicketEntryResponse").html(data);
		$("#newTicketEntryResponse").effect("highlight",{},500);'
		: '
		// Clear the default row
		$("#viewTicketEntriesTableDefaultRow").remove();
		// Update the table with the new row
		$(".viewTicketEntriesTable").append(data);
		// Show a success message
		$("#newTicketEntryResponse").html(returnSuccessMessage("ticket entry"));';

	$url = SITE_URL . "/ajax.php?action=createTicketEntry&reprinttable=" . $reprintTable . "&id=" . $ticketID;

	return makeFormJQuery( 'newTicketEntry', $url, '', '', '', $customSuccessFunction );
}