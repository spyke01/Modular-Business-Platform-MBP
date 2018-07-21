<?php
/***************************************************************************
 *                               appointments.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/



//=================================================
// Print the Appointment calendar
//=================================================
function printAppointmentCalendar() {
	global $ftsdb, $clmsMenus;
	// Gather variables from
	// user input and break them
	// down for usage in our script

	$date = ( ! isset( $_REQUEST['date'] ) ) ? mktime( 0, 0, 0, date( 'm' ), date( 'd' ), date( 'Y' ) ) : $_REQUEST['date'];

	$day   = date( 'd', $date );
	$month = date( 'm', $date );
	$year  = date( 'Y', $date );

	// Get the first day of the month
	$month_start = mktime( 0, 0, 0, $month, 1, $year );

	// Get friendly month name
	$month_name = date( 'M', $month_start );

	// Figure out which day of the week
	// the month starts on.
	$month_start_day = date( 'D', $month_start );

	switch ( $month_start_day ) {
		case "Sun":
			$offset = 0;
			break;
		case "Mon":
			$offset = 1;
			break;
		case "Tue":
			$offset = 2;
			break;
		case "Wed":
			$offset = 3;
			break;
		case "Thu":
			$offset = 4;
			break;
		case "Fri":
			$offset = 5;
			break;
		case "Sat":
			$offset = 6;
			break;
	}

	// determine how many days are in the last month.
	$num_days_last = ( $month == 1 || $month == 01 ) ? cal_days_in_month( 0, 12, ( $year - 1 ) ) : cal_days_in_month( 0, ( $month - 1 ), $year );

	// determine how many days are in the current month.
	$num_days_current = cal_days_in_month( 0, $month, $year );

	// Build an array for the current days
	// in the month
	for ( $i = 1; $i <= $num_days_current; $i ++ ) {
		$num_days_array[] = $i;
	}

	// Build an array for the number of days
	// in last month
	for ( $i = 1; $i <= $num_days_last; $i ++ ) {
		$num_days_last_array[] = $i;
	}

	// If the $offset from the starting day of the
	// week happens to be Sunday, $offset would be 0,
	// so don't need an offset correction.

	if ( $offset > 0 ) {
		$offset_correction = array_slice( $num_days_last_array, - $offset, $offset );
		$new_count         = array_merge( $offset_correction, $num_days_array );
		$offset_count      = count( $offset_correction );
	} // The else statement is to prevent building the $offset array.
	else {
		$offset_count = 0;
		$new_count    = $num_days_array;
	}

	// count how many days we have with the two
	// previous arrays merged together
	$current_num = count( $new_count );

	// Since we will have 5 HTML table rows (TR)
	// with 7 table data entries (TD)
	// we need to fill in 35 TDs
	// so, we will have to figure out
	// how many days to appened to the end
	// of the final array to make it 35 days.


	if ( $current_num > 35 ) {
		$num_weeks = 6;
		$outset    = ( 42 - $current_num );
	} elseif ( $current_num < 35 ) {
		$num_weeks = 5;
		$outset    = ( 35 - $current_num );
	}
	if ( $current_num == 35 ) {
		$num_weeks = 5;
		$outset    = 0;
	}
	// Outset Correction
	for ( $i = 1; $i <= $outset; $i ++ ) {
		$new_count[] = $i;
	}

	// Now let's "chunk" the $all_days array
	// into weeks. Each week has 7 days
	// so we will array_chunk it into 7 days.
	$weeks = array_chunk( $new_count, 7 );


	// Build Previous and Next Links
	$previous_link = "<a href=\"" . $clmsMenus['APPOINTMENTS']['link'] . "&date=";
	$previous_link .= ( $month == 1 || $month == 01 ) ? mktime( 0, 0, 0, 12, $day, ( $year - 1 ) ) : mktime( 0, 0, 0, ( $month - 1 ), $day, $year );
	$previous_link .= "\"><< Prev</a>";

	$next_link = "<a href=\"" . $clmsMenus['APPOINTMENTS']['link'] . "&date=";
	$next_link .= ( $month == 12 ) ? mktime( 0, 0, 0, 1, $day, ( $year + 1 ) ) : mktime( 0, 0, 0, ( $month + 1 ), $day, $year );
	$next_link .= "\">Next >></a>";

	// Build the heading portion of the calendar table
	$content = "
		<table class=\"calendar\" border=\"1\" cellpadding=\"1\" cellspacing=\"1\">
		<tr>
			<td colspan=\"7\" class=\"title1\" width=\"100%\">
					<div id=\"calendarNext\" class=\"right\" style=\"float: right;\">" . $next_link . "</div>
					<div id=\"calendarMonth\" class=\"center\" style=\"float: right;\">" . $month_name . " " . $year . "</div>
					<div id=\"calendarPrev\" class=\"left\">" . $previous_link . "</div>
			 </td>
		 <tr class=\"title2\">
			 <td>Sunday</td><td>Monday</td><td>Tuesday</td><td>Wednesday</td><td>Thursday</td><td>Friday</td><td>Saturday</td>
			</tr>";

	// Now we break each key of the array 
	// into a week and create a new table row for each
	// week with the days of that week in the table data

	$i = 0;
	foreach ( $weeks AS $week ) {
		$content .= "\n		<tr>";

		foreach ( $week as $d ) {// Prep our IN clause data
			$preparedInClause = $ftsdb->prepareInClauseVariable( getMyClientIDs() );
			$selectBindData   = $preparedInClause['data'];

			// Get our appointment for this day
			$sql    = ( $i < $offset_count ) ? "SELECT c.first_name, c.last_name, a.datetimestamp, a.urgency FROM `" . DBTABLEPREFIX . "appointments` a, `" . DBTABLEPREFIX . "clients` c WHERE a.client_id IN (" . $preparedInClause['binds'] . ") AND a.client_id = c.id AND SUBSTRING(FROM_UNIXTIME(`datetimestamp`) FROM 1 FOR 10) = SUBSTRING(FROM_UNIXTIME(" . mktime( 0, 0, 0, ( $month - 1 ), $d, $year ) . ") FROM 1 FOR 10)" : "SELECT c.first_name, c.last_name, a.datetimestamp, a.urgency FROM `" . DBTABLEPREFIX . "appointments` a, `" . DBTABLEPREFIX . "clients` c WHERE a.client_id IN (" . $preparedInClause['binds'] . ") AND a.client_id = c.id AND SUBSTRING(FROM_UNIXTIME(`datetimestamp`) FROM 1 FOR 10) = SUBSTRING(FROM_UNIXTIME(" . mktime( 0, 0, 0, $month, $d, $year ) . ") FROM 1 FOR 10)";
			$sql    = ( ( $i > $offset_count || $outset > 0 ) && ( $i >= ( $num_weeks * 7 ) - $outset ) ) ? "SELECT c.first_name, c.last_name, a.datetimestamp, a.urgency FROM `" . DBTABLEPREFIX . "appointments` a, `" . DBTABLEPREFIX . "clients` c WHERE a.client_id IN (" . $preparedInClause['binds'] . ") AND a.client_id = c.id AND SUBSTRING(FROM_UNIXTIME(`datetimestamp`) FROM 1 FOR 10) = SUBSTRING(FROM_UNIXTIME(" . mktime( 0, 0, 0, ( $month + 1 ), $d, $year ) . ") FROM 1 FOR 10)" : $sql;
			$result = $ftsdb->run( $sql, $selectBindData );

			$appointments = ""; // Reset our appointments variable

			foreach ( $result as $row ) {
				$rowColor = ( $row['urgency'] != LOW ) ? "redRow" : "greenRow";
				$rowColor = ( $row['urgency'] != HIGH && $rowColor == "redRow" ) ? "yellowRow" : $rowColor;

				$appointments .= "<div class=\"" . $rowColor . "\">" . makeTime( $row['datetimestamp'] ) . " : " . $row['first_name'] . " " . $row['last_name'] . "</div>";
			}

			// Print the actual table item
			if ( $i < $offset_count ) {
				$day_link = "<a href=\"" . $clmsMenus['APPOINTMENTS']['link'] . "&action=viewdate&date=" . mktime( 0, 0, 0, ( $month - 1 ), $d, $year ) . "\">" . $d . "</a>";
				$content  .= "\n			<td class=\"nonmonthdays\"><div class=\"date\">" . $day_link . "</div><div class=\"appointments\">" . $appointments . "</div></td>";
			}
			if ( ( $i >= $offset_count ) && ( $i < ( $num_weeks * 7 ) - $outset ) ) {
				$day_link = "<a href=\"" . $clmsMenus['APPOINTMENTS']['link'] . "&action=viewdate&date=" . mktime( 0, 0, 0, $month, $d, $year ) . "\">" . $d . "</a>";
				$content  .= ( $date == mktime( 0, 0, 0, $month, $d, $year ) ) ? "\n			<td class=\"today\"><div class=\"date\">" . $day_link . "</div><div class=\"appointments\">" . $appointments . "</div></td>" : "\n			<td class=\"monthdays\"><div class=\"date\">$day_link</div><div class=\"appointments\">$appointments</div></td>";
			} elseif ( ( $outset > 0 ) ) {
				if ( ( $i >= ( $num_weeks * 7 ) - $outset ) ) {
					$day_link = "<a href=\"" . $clmsMenus['APPOINTMENTS']['link'] . "&action=viewdate&date=" . mktime( 0, 0, 0, ( $month + 1 ), $d, $year ) . "\">" . $d . "</a>";
					$content  .= "\n			<td class=\"nonmonthdays\"><div class=\"date\">" . $day_link . "</div><div class=\"appointments\">" . $appointments . "</div></td>";
				}
			}

			$i ++;
		}
		$content .= "\n		</tr>";
	}

	// Close out your table and that's it!
	$content .= "
			</table>";

	return $content;
}

//=================================================
// Print the View Date Table
//=================================================
function printViewDateTable( $datetimestamp ) {
	global $ftsdb, $clmsMenus, $mbp_config;

	// Prep our IN clause data
	$preparedInClause = $ftsdb->prepareInClauseVariable( getMyClientIDs() );
	$selectBindData   = $preparedInClause['data'];
	$selectBindData   = array_merge( $selectBindData, array(
		":datetimestamp" => $datetimestamp
	) );

	$sql    = "SELECT c.first_name, c.last_name, a.* FROM `" . DBTABLEPREFIX . "appointments` a, `" . DBTABLEPREFIX . "clients` c WHERE a.client_id IN (" . $preparedInClause['binds'] . ") AND a.client_id = c.id AND SUBSTRING(FROM_UNIXTIME(`datetimestamp`) FROM 1 FOR 10) = SUBSTRING(FROM_UNIXTIME(:datetimestamp) FROM 1 FOR 10)";
	$result = $ftsdb->run( $sql, $selectBindData );

	// Create our new table
	$table = new tableClass( '', '', '', "table table-striped table-bordered", "viewDateTable" );

	// Create table title
	$table->addNewRow( array(
		array(
			'data'    => "Appointments for " . makeDate( $datetimestamp ),
			"colspan" => "6"
		)
	), "", "title1" );

	// Create column headers
	$table->addNewRow(
		array(
			array( 'type' => 'th', 'data' => "Time" ),
			array( 'type' => 'th', 'data' => "Place" ),
			array( 'type' => 'th', 'data' => "Attire" ),
			array( 'type' => 'th', 'data' => "Client" ),
			array( 'type' => 'th', 'data' => "Description" ),
			array( 'type' => 'th', 'data' => "" )
		), "", "title2"
	);

	// Add our data
	if ( ! $result ) {
		$table->addNewRow( array(
			array(
				'data'    => "There are no appointments scheduled for this date.",
				"colspan" => "6"
			)
		), "viewDateTableDefaultRow", "greenRow" );
	} else {
		foreach ( $result as $row ) {
			$rowColor    = ( $row['urgency'] != LOW ) ? "redRow" : "greenRow";
			$rowColor    = ( $row['urgency'] != HIGH && $rowColor == "redRow" ) ? "yellowRow" : $rowColor;
			$finalColumn = ( user_access( 'clms_appointments_delete' ) ) ? createDeleteLinkWithImage( $row['id'], $row['id'] . "_row", "appointments", "appointment" ) : "";

			$table->addNewRow(
				array(
					array( 'data' => "<div id=\"" . $row['id'] . "_datetimestamp\">" . makeDateTime( $row['datetimestamp'] ) . "</div>" ),
					array( 'data' => "<div id=\"" . $row['id'] . "_place\">" . $row['place'] . "</div>" ),
					array( 'data' => "<div id=\"" . $row['id'] . "_attire\">" . $row['attire'] . "</div>" ),
					array( 'data' => $row['first_name'] . " " . $row['last_name'] ),
					array( 'data' => "<div id=\"" . $row['id'] . "_description\">" . bbcode( $row['description'] ) . "</div>" ),
					array( 'data' => $finalColumn, 'class' => 'center' )
				), $row['id'] . "_row", $rowColor
			);
		}
		$result = null;
	}

	// Return the table's HTML
	return $table->returnTableHTML() . "
			<div id=\"viewDateTableUpdateNotice\"></div>";
}

//=================================================
// Print the Client Appointments Table
//=================================================
function printAppointmentsTable( $clientID = "" ) {
	global $ftsdb, $clmsMenus, $mbp_config;

	// Prep our IN clause data
	$preparedInClause = $ftsdb->prepareInClauseVariable( getMyClientIDs() );
	$selectBindData   = $preparedInClause['data'];
	$selectBindData   = array_merge( $selectBindData, array(
		":id" => $clientID,
	) );

	$extraSQL = ( $clientID != "" ) ? "client_id = :id AND " : "";

	$result = $ftsdb->select( DBTABLEPREFIX . "appointments", $extraSQL . "client_id IN (" . $preparedInClause['binds'] . ") ORDER BY datetimestamp ASC", $selectBindData );
	//echo $sql;

	// Create our new table
	$table = new tableClass( '', '', '', "table table-striped table-bordered tablesorter", "appointmentsTable" );

	// Create table title
	$table->addNewRow( array( array( 'data' => "Appointments", "colspan" => "5" ) ), '', 'title1', 'thead' );

	// Create column headers
	$table->addNewRow(
		array(
			array( 'type' => 'th', 'data' => "Time" ),
			array( 'type' => 'th', 'data' => "Place" ),
			array( 'type' => 'th', 'data' => "Attire" ),
			array( 'type' => 'th', 'data' => "Description" ),
			array( 'type' => 'th', 'data' => "" )
		), '', 'title2', 'thead'
	);

	// Add our data
	if ( ! $result ) {
		$table->addNewRow( array(
			array(
				'data'    => "There are no appointments scheduled for this client.",
				"colspan" => "5"
			)
		), "appointmentsTableDefaultRow", "greenRow" );
	} else {
		foreach ( $result as $row ) {
			$rowColor    = ( $row['urgency'] != LOW ) ? "redRow" : "greenRow";
			$rowColor    = ( $row['urgency'] != HIGH && $rowColor == "redRow" ) ? "yellowRow" : $rowColor;
			$finalColumn = ( user_access( 'clms_appointments_delete' ) ) ? createDeleteLinkWithImage( $row['id'], $row['id'] . "_row", "appointments", "appointment" ) : "";

			$table->addNewRow(
				array(
					array( 'data' => "<div id=\"" . $row['id'] . "_datetimestamp\">" . makeDateTime( $row['datetimestamp'] ) . "</div>" ),
					array( 'data' => "<div id=\"" . $row['id'] . "_place\">" . $row['place'] . "</div>" ),
					array( 'data' => "<div id=\"" . $row['id'] . "_attire\">" . $row['attire'] . "</div>" ),
					array( 'data' => "<div id=\"" . $row['id'] . "_description\">" . bbcode( $row['description'] ) . "</div>" ),
					array( 'data' => $finalColumn, 'class' => 'center' )
				), $row['id'] . "_row", $rowColor
			);
		}
		$result = null;
	}

	// Return the table's HTML
	return $table->returnTableHTML() . "
			<div id=\"appointmentsTableUpdateNotice\"></div>";
}

//=================================================
// Returns the JQuery functions used to run the 
// Appointments table
//=================================================
function returnAppointmentsTableJQuery() {
	$JQueryReadyScripts = "
			$('#appointmentsTable').tablesorter({ headers: { 4: { sorter: false } } });";

	return $JQueryReadyScripts;
}

//=================================================
// Print the Todays Appointments Table
//=================================================
function printAppointmentsForTodayTable() {
	global $ftsdb, $clmsMenus, $mbp_config;

	// Prep our IN clause data
	$preparedInClause = $ftsdb->prepareInClauseVariable( getMyClientIDs() );
	$selectBindData   = $preparedInClause['data'];

	$result = $ftsdb->select( DBTABLEPREFIX . "appointments", "client_id IN (" . $preparedInClause['binds'] . ") AND SUBSTRING(FROM_UNIXTIME(`datetimestamp`) FROM 1 FOR 10) = SUBSTRING(FROM_UNIXTIME(" . time() . ") FROM 1 FOR 10) ORDER BY datetimestamp ASC", $selectBindData );

	//echo $sql;

	// Create our new table
	$table = new tableClass( '', '', '', "table table-striped table-bordered tablesorter", "appointmentsForTodayTable" );

	// Create table title
	$table->addNewRow( array( array( 'data' => "Appointments for Today", "colspan" => "5" ) ), '', 'title1', 'thead' );

	// Create column headers
	$table->addNewRow(
		array(
			array( 'type' => 'th', 'data' => "Time" ),
			array( 'type' => 'th', 'data' => "Place" ),
			array( 'type' => 'th', 'data' => "Attire" ),
			array( 'type' => 'th', 'data' => "Client" ),
			array( 'type' => 'th', 'data' => "Description" )
		), '', 'title2', 'thead'
	);

	// Add our data
	if ( ! $result ) {
		$table->addNewRow( array(
			array(
				'data'    => "There are no appointments scheduled for today.",
				"colspan" => "5"
			)
		), "", "greenRow" );
	} else {
		foreach ( $result as $row ) {
			$rowColor = ( $row['urgency'] != LOW ) ? "redRow" : "greenRow";
			$rowColor = ( $row['urgency'] != HIGH && $rowColor == "redRow" ) ? "yellowRow" : $rowColor;

			$table->addNewRow(
				array(
					array( 'data' => makeDateTime( $row['datetimestamp'] ) ),
					array( 'data' => $row['place'] ),
					array( 'data' => $row['attire'] ),
					array( 'data' => getClientNameFromID( $row['client_id'] ) ),
					array( 'data' => bbcode( $row['description'] ) )
				), $row['id'] . "_row", $rowColor
			);
		}
		$result = null;
	}

	// Return the table's HTML
	return $table->returnTableHTML();
}

//=================================================
// Returns the JQuery functions used to run the 
// AppointmentsForToday table
//=================================================
function returnAppointmentsForTodayTableJQuery() {
	$JQueryReadyScripts = "
			$('#appointmentsForTodayTable').tablesorter();";

	return $JQueryReadyScripts;
}

//=================================================
// Create a form to add new appointments
//=================================================
function printNewAppointmentForm( $date, $clientID = "" ) {
	global $clmsMenus, $mbp_config;

	$currentDate = ( trim( $date ) != "" ) ? @gmdate( 'm/d/Y', $date + ( 3600 * '-5.00' ) ) : $date;

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

	$formFields = apply_filters( 'form_fields_clms_appointments_new', array(
		'client_id'     => $clientIDField,
		'datetimestamp' => array(
			'text'    => 'Date and Time',
			'type'    => 'text',
			'default' => $currentDate,
		),
		'place'         => array(
			'text' => 'Place',
			'type' => 'text',
		),
		'attire'        => array(
			'text' => 'Attire',
			'type' => 'text',
		),
		'description'   => array(
			'text' => 'Description',
			'type' => 'textarea',
		),
		'urgency'       => array(
			'text'    => 'Urgency',
			'type'    => 'select',
			'options' => getDropdownArray( 'urgency' ),
		),
	) );

	return makeForm( 'newAppointment', il( $clmsMenus['APPOINTMENTS']['link'] ), 'Add an Appointment', 'Make the Appointment!', $formFields, array(), 1 );
}

//=================================================
// Returns the JQuery functions used to run the 
// new appointment form
//=================================================
function returnNewAppointmentFormJQuery( $reprintTable = 0, $allowModification = 1 ) {
	$customSuccessFunction = ( $reprintTable == 0 ) ? "
					// Update the proper div with the returned data
					$('#newAppointmentResponse').html(data);
					$('#newAppointmentResponse').effect('highlight',{},500);"
		: "
					// Clear the default row
					$('#appointmentsTableDefaultRow').remove();
					// Update the table with the new row
					$('#appointmentsTable > tbody:last').append(data);
					$('#appointmentsTableUpdateNotice').html('" . tableUpdateNoticeHTML() . "');
					// Show a success message
					$('#newAppointmentResponse').html(returnSuccessMessage('appointment'));";
	$customSuccessFunction = ( $reprintTable == 2 ) ? "		
					// Clear the default row
					$('#viewDateTableDefaultRow').remove();
					// Update the table with the new row
					$('#viewDateTable > tbody:last').append(data);
					$('#viewDateTableUpdateNotice').html('" . tableUpdateNoticeHTML() . "');
					// Show a success message
					$('#newAppointmentResponse').html(returnSuccessMessage('appointment'));" : $extraJQuery;
	$customSuccessFunction = ( $reprintTable == 3 ) ? "			
					// Update the proper div with the returned data
					$('#updateMeAppointments').html(data);
					jQuery.get('" . SITE_URL . "/ajax.php?action=printAppointmentCalendar', function(data) {
						// Update the proper div with the returned data
						$('#updateMeAppointments').html(data);
					});
					// Show a success message
					$('#newAppointmentResponse').html(returnSuccessMessage('appointment'));" : $extraJQuery;

	$JQueryReadyScripts = "
		$('#datetimestamp').datetimepicker({
			showButtonPanel: true,
			ampm: true
		});
		" . makeFormJQuery( 'newAppointment', SITE_URL . "/ajax.php?action=createAppointment&reprinttable=" . $reprintTable . "&showButtons=" . $allowModification, '', '', '', $customSuccessFunction, 1 );

	return $JQueryReadyScripts;
}