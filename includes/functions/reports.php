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
// Print the Latest Log Entries Report
//=================================================
function printLatestLogEntriesReport() {
	global $ftsdb, $tableColumns;

	$result = $ftsdb->select( DBTABLEPREFIX . "logging", "1 ORDER BY id DESC LIMIT 500" );

	// Prep our table columns
	$columns      = apply_filters( 'table_log_events_report_columns', $tableColumns['table_log_events_report'] );
	$numOfColumns = count( $columns );

	// Create our new table
	$table = new Table( '', '', '', "table table-striped table-bordered", "latestLogEntriesReportTable" );

	// Create table title
	$table->addNewRow( [ [ 'data' => "Last 500 Log Entries", "colspan" => $numOfColumns ] ], '', 'title1', 'thead' );

	// Create column headers
	$table->addNewRow( $table->generateTableColumns( $columns ), '', 'title2', 'thead' );

	// Add our data
	if ( ! $result ) {
		// handled by datatables
		//$table->addNewRow([['data' => "There are no log events in the system.", "colspan" => $numOfColumns, 'class' => 'dataTables_empty' ]], "latestLogEntriesReportTableDefaultRow", "greenRow");
	} else {
		foreach ( $result as $row ) {
			$rowData = [];

			foreach ( $columns as $column_name => $column_display_name ) {
				switch ( $column_name ) {
					case 'type_text':
						$rowData[] = [ 'data' => returnLogTypeText( $row['type'] ) ];
						break;
					case 'created':
						$rowData[] = [ 'data' => $row['created'] ];
						break;
					case 'message':
						$rowData[] = [ 'data' => $row['message'] ];
						break;
					case 'duration':
						$duration = 'N/A';
						if ( ! empty( $row['stop'] ) && ! empty( $row['start'] ) ) {
							$duration = $row['stop'] - $row['start'];
							$duration = ( $duration > 0 ) ? time_elapsed( $row['stop'] - $row['start'] ) : $duration . 's';
						}
						$rowData[] = [ 'data' => $duration ];
						break;
					case 'assoc_id':
						$rowData[] = [ 'data' => $row['assoc_id'] ];
						break;
					case 'assoc_id2':
						$rowData[] = [ 'data' => $row['assoc_id2'] ];
						break;
					case 'assoc_id3':
						$rowData[] = [ 'data' => $row['assoc_id3'] ];
						break;
					default:
						$rowData[] = apply_filters( 'table_log_events_report_custom_column', '', $column_name, $row['id'] );
				}
			}
			$table->addNewRow( $rowData, $row['id'] . '_row', '' );
		}
		$result = null;
	}

	// Return the table's HTML
	return $table->returnTableHTML();
}

//=================================================
// Returns the JQuery functions used to run the 
// Latest Log Entries report
//=================================================
function returnLatestLogEntriesReportJQuery() {
	/*
	$JQueryReadyScripts = "
			$('#latestLogEntriesReportTable').dataTable({
				'sEmptyTable': 'There are no log events in the system.',
				'bProcessing': true,
		        'bServerSide': true,
		        'sAjaxSource': '" . SITE_URL . "/ajax.php?action=dataTables&dataset=latestLogEntriesReport'
			});";	
	*/
	$JQueryReadyScripts = "
			$('#latestLogEntriesReportTable').dataTable({
				'sEmptyTable': 'There are no log events in the system.'
			});";

	return $JQueryReadyScripts;
}

//=================================================
// Print the User Details Report
//=================================================
function printUserDetailsReport() {
	global $ftsdb, $tableColumns;

	$result = $ftsdb->select( USERSDBTABLEPREFIX . "users", "1 ORDER BY user_level, last_name, first_name" );

	// Prep our table columns
	$columns      = apply_filters( 'table_users_report_columns', $tableColumns['table_users_report'] );
	$numOfColumns = count( $columns );

	// Create our new table
	$table = new Table( '', '', '', "table table-striped table-bordered", "userDetailsReportTable" );

	// Create table title
	$table->addNewRow( [ [ 'data' => "User Details", "colspan" => $numOfColumns ] ], '', 'title1', 'thead' );

	// Create column headers
	$table->addNewRow( $table->generateTableColumns( $columns ), '', 'title2', 'thead' );

	// Add our data
	if ( ! $result ) {
		// handled by datatables
		//$table->addNewRow([ [ 'data' => "There are no users in the system.", "colspan" => $numOfColumns]], "userDetailsReportTableDefaultRow", "greenRow");
	} else {
		foreach ( $result as $row ) {
			$rowData = [];

			foreach ( $columns as $column_name => $column_display_name ) {
				switch ( $column_name ) {
					case 'user_level':
						$rowData[] = [ 'data' => returnUserlevelText( $row['user_level'] ) ];
						break;
					case 'last_name':
						$rowData[] = [ 'data' => $row['last_name'] ];
						break;
					case 'first_name':
						$rowData[] = [ 'data' => $row['first_name'] ];
						break;
					case 'company':
						$rowData[] = [ 'data' => $row['company'] ];
						break;
					case 'email_address':
						$rowData[] = [ 'data' => $row['email_address'] ];
						break;
					case 'website':
						$rowData[] = [ 'data' => $row['website'] ];
						break;
					case 'username':
						$rowData[] = [ 'data' => $row['username'] ];
						break;
					case 'notes':
						$rowData[] = [ 'data' => bbcode( $row['notes'] ) ];
						break;
					default:
						$rowData[] = apply_filters( 'table_users_report_custom_column', '', $column_name, $row['id'] );
				}
			}
			$table->addNewRow( $rowData, $row['id'] . '_row', '' );
		}
		$result = null;
	}

	// Return the table's HTML
	return $table->returnTableHTML();
}

//=================================================
// Returns the JQuery functions used to run the 
// User Details report
//=================================================
function returnUserDetailsReportJQuery() {
	/*		
	$JQueryReadyScripts = "
			$('#userDetailsReportTable').dataTable({
				'sEmptyTable': 'There are no users in the system.',
				'bProcessing': true,
		        'bServerSide': true,
		        'sAjaxSource': '" . SITE_URL . "/ajax.php?action=dataTables&dataset=userDetailsReport'
			});";
	*/
	$JQueryReadyScripts = "
			$('#userDetailsReportTable').dataTable({
				'sEmptyTable': 'There are no users in the system.'
			});";

	return $JQueryReadyScripts;
}