<?php
// Cycle through our AJAX calls and handle the content
if ( $actual_action == 'updateitem' && user_access( 'tts_updateitem' ) ) {
	if ( $section == 'before' ) {
	}
} elseif ( $actual_action == 'deleteitem' && user_access( 'tts_deleteitem' ) ) {
	if ( $section == 'before' ) {
		// Delete and associated foreign items
		if ( $table == "tickets" ) {
			// Delete Ticket Entries
			$result     = $ftsdb->delete( DBTABLEPREFIX . 'entries', "ticket_id = :ticket_id", array(
				":ticket_id" => $actual_id
			) );
			$errorCount += ( $result ) ? 0 : 1;
		}
	}
}

//================================================
// Create a ticket 
//================================================
elseif ( $actual_action == "createTicket" && user_access( 'tts_tickets_create' ) ) {
	$errors        = 0;
	$datetimestamp = time();
	$title         = keeptasafe( $_POST['title'] );
	$cat_id        = intval( $_POST['cat_id'] );
	$user_id       = ( user_access( 'tts_tickets_change_user' ) ) ? intval( $_POST['user_id'] ) : $_SESSION['userid'];
	$tech_id       = ( user_access( 'tts_tickets_change_tech' ) ) ? intval( $_POST['tech_id'] ) : '';
	$text          = keeptasafe( $_POST['text'] );
	$is_client     = 0;
	$username      = getUsernameFromID( $user_id );
	$userIDField   = 'user_id';

	if ( isModuleActivated( 'CLMS' ) ) {
		$is_client   = 1;
		$user_id     = ( user_access( 'tts_tickets_change_client' ) ) ? intval( $_POST['client_id'] ) : $_SESSION['userid'];
		$username    = getClientNameFromID( $user_id );
		$userIDField = 'client_id';
	}

	$result   = $ftsdb->insert( DBTABLEPREFIX . 'tickets', array(
		"title"         => $title,
		"cat_id"        => $cat_id,
		$userIDField    => $user_id,
		"tech_id"       => $tech_id,
		"datetimestamp" => $datetimestamp,
	) );
	$ticketID = $ftsdb->lastInsertId();
	if ( ! $result ) {
		$errors ++;
	}

	$result = $ftsdb->insert( DBTABLEPREFIX . 'entries', array(
		"ticket_id"     => $ticketID,
		"user_id"       => $user_id,
		"is_client"     => $is_client,
		"text"          => $text,
		"datetimestamp" => $datetimestamp,
	) );
	if ( ! $result ) {
		$errors ++;
	}

	$content = ( $errors > 0 ) ? "	<span class=\"greenText bold\">" . $LANG['SUCCESS_CREATE_TICKET'] . "</span>" : "	<span class=\"redText bold\">" . $LANG['ERROR_CREATE_TICKET'] . "</span>";

	switch ( keepsafe( $_GET['reprinttable'] ) ) {
		case 1:
			$finalColumnData = ( user_access( 'tts_tickets_delete' ) ) ? createDeleteLinkWithImage( $ticketID, $ticketID . "_row", "tickets", "ticket" ) : "";

			$tableHTML = '
				<tr class="greenRow" id="' . $ticketID . '_row">
					<td>' . $ticketID . '</td>
					<td>' . $title . '</td>
					<td>' . $username . '</td>
					<td>' . getCatNameByID( $cat_id ) . '</td>
					<td>Ticket Not Assigned Yet</td>
					<td>' . makeDateTime( $datetimestamp ) . '</td>
					<td>' . getTicketStatus( 0 ) . '</td>
					<td class="center"><span class="btn-group"><a href="' . $ttsMenus['VIEWTICKET']['link'] . '&id=' . $ticketID . '"  class="btn btn-default"><i class="glyphicon glyphicon-edit"></i></a> ' . $finalColumnData . '</span></td>
				</tr>';

			echo $tableHTML;
			break;
		default:
			echo $content;
			break;
	}

	// Send ticket created email
	sendTicketEMail( $ticketID, $user_id, 0 );
}

//================================================
// Create a ticket entry
//================================================
elseif ( $actual_action == "createTicketEntry" && user_access( 'tts_tickets_create_entries' ) ) {
	$datetimestamp = time();
	$user_id       = $_SESSION['userid'];
	$is_client     = $_SESSION['is_client'];
	$text          = keeptasafe( $_POST['text'] );
	$username      = getUsernameFromID( $user_id );

	if ( isModuleActivated( 'CLMS' ) && $_SESSION['is_client'] ) {
		$username = getClientNameFromID( $user_id );
	}

	$result  = $ftsdb->insert( DBTABLEPREFIX . 'entries', array(
		"ticket_id"     => $actual_id,
		"user_id"       => $user_id,
		"is_client"     => $is_client,
		"text"          => $text,
		"datetimestamp" => $datetimestamp,
	) );
	$entryID = $ftsdb->lastInsertId();

	$content      = ( $result ) ? "	<span class=\"greenText bold\">" . $LANG['SUCCESS_CREATE_TICKET_ENTRY'] . "</span>" : "	<span class=\"redText bold\">" . $LANG['ERROR_CREATE_TICKET_ENTRY'] . "</span>";
	$deleteButton = ( user_access( 'tts_tickets_delete' ) ) ? "<br />" . createDeleteLinkWithImage( $entryID, $entryID . "_row", "entries", "entry" ) : "";

	switch ( keepsafe( $_GET['reprinttable'] ) ) {
		case 1:
			$tableHTML = returnTicketUpdateChatBubble( array(
				'id'            => $entryID,
				'user_id'       => $user_id,
				'is_client'     => intval( $_SESSION['is_client'] ),
				'datetimestamp' => $datetimestamp,
				'text'          => $text,
			) );

			echo $tableHTML;
			break;
		default:
			echo $content;
			break;
	}

	// Send ticket reply email
	sendTicketEMail( $actual_id, $user_id, 1 );
}

//================================================
// Search our tickets table
//================================================
elseif ( $actual_action == "searchTickets" && user_access( 'tts_tickets_search' ) ) {
	echo printTicketsTable( $_POST );
}

//================================================
// Gets a json array of ticket counts
//================================================
elseif ( $actual_action == "getTicketCounts" && user_access( 'tts_getTicketCounts' ) ) {
	$counts = array(
		'total'  => 0,
		'open'   => 0,
		'onHold' => 0,
		'closed' => 0,
	);

	$result = $ftsdb->select( DBTABLEPREFIX . "tickets", "1", array(
		':open'   => 0,
		':closed' => 1,
		':onHold' => 2,
	),
		"COUNT(id) AS total, 
		coalesce( (SELECT COUNT(id) FROM `" . DBTABLEPREFIX . "tickets` WHERE status = :open), 0 ) AS open, 
		coalesce( (SELECT COUNT(id) FROM `" . DBTABLEPREFIX . "tickets` WHERE status = :closed), 0 ) AS closed, 
		coalesce( (SELECT COUNT(id) FROM `" . DBTABLEPREFIX . "tickets` WHERE status = :onHold), 0 ) AS onHold"
	);

	if ( $result ) {
		foreach ( $result as $row ) {
			$counts = array(
				'total'  => $row['total'],
				'open'   => $row['open'],
				'onHold' => $row['onHold'],
				'closed' => $row['closed'],
			);
		}
		$result = null;
	}

	echo json_encode( $counts );
}