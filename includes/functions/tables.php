<?php 
/***************************************************************************
 *                               tables.php
 *                            -------------------
 *   begin                : Tuseday, March 25, 2014
 *   copyright            : (C) 2014 Paden Clayton
 *
 *
 ***************************************************************************/


$tableColumns = array();
 
/*	 EMAIL USERS
---------------------------------------------------------------*/
$tableColumns['table_email_templates'] = array(
	'name'    			=> __( 'Name' ),
	'subject'     		=> __( 'Subject' ),
	'added_by'     		=> __( 'Added By' ),
	'final'    			=> __( '' )
);
 
/*	 LOGGING
---------------------------------------------------------------*/
$tableColumns['table_log_events_report'] = array(
	'type_text'    		=> __( 'Event' ),
	'created'     		=> __( 'On' ),
	'message'     		=> __( 'Message' ),
	'duration'     		=> __( 'Duration' ),
	'assoc_id'    		=> __( 'Assoc ID' ),
	'assoc_id2'     	=> __( 'Assoc ID 2' ),
	'assoc_id3'     	=> __( 'Assoc ID 3' )
);
 
/*	 USERS
---------------------------------------------------------------*/
$tableColumns['table_users'] = array(
	'username'     		=> __( 'Username' ),
	'email_address'    	=> __( 'Email Address' ),
	'full_name'     	=> __( 'Full Name' ),
	'signup_date'    	=> __( 'Signup Date' ),
	'user_level'    	=> __( 'User Level' ),
	'final'    			=> __( '' )
);

$tableColumns['table_users_report'] = array(
	'user_level'    	=> __( 'User Level' ),
	'first_name'     	=> __( 'Last Name' ),
	'last_name'     	=> __( 'First Name' ),
	'company'     		=> __( 'Company' ),
	'email_address'    	=> __( 'Email Address' ),
	'website'     		=> __( 'Website' ),
	'username'     		=> __( 'Username' ),
	'notes'    			=> __( 'Notes' )
);

/*   TODO: Custom columns won't work since we do a pull based on column names */
/*	 CODE TO PROCESS THEM FOR DATATABLES
---------------------------------------------------------------*/
function returnDataTablesJSON( $aColumns, $sTable = 'ajax', $sIndexColumn = 'id' ) {
	global $server, $serverPort, $dbuser, $dbpass, $dbname;

	// SQL server connection information
	$sql_details = array(
		'user' => $dbuser,
		'pass' => $dbpass,
		'db'   => $dbname,
		'host' => $server . ':' . $serverPort
	);

	require( BASEPATH . '/includes/classes/DataTablesSsp.class.php' );

	echo json_encode(
		DataTablesSSP::simple( $_GET, $sql_details, $sTable, $sIndexColumn, $aColumns )
	);


	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	$filterData = array();
	
	/* 
	 * Local functions
	 */
	function fatal_error ( $sErrorMessage = '' ) {
		header( $_SERVER['SERVER_PROTOCOL'] .' 500 Internal Server Error' );
		die( $sErrorMessage );
	}	
	
	/* 
	 * Paging
	 */
	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' ) {
		$sLimit = "LIMIT ".intval( $_GET['iDisplayStart'] ).", ".
			intval( $_GET['iDisplayLength'] );
	}
	
	
	/*
	 * Ordering
	 */
	$sOrder = "";
	if ( isset( $_GET['iSortCol_0'] ) ) {
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ ) {
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" ) {
				$sOrder .= "`".$aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."` ".
					($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" ) {
			$sOrder = "";
		}
	}
	
	
	/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	$sWhere = "";
	if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
		$sWhere = "WHERE (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
			$sWhere .= "`".$aColumns[$i]."` LIKE :sSearch OR ";
			$filterData[':sSearch'] = '%' . $_GET['sSearch'] . '%';
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ')';
	}
	
	/* Individual column filtering */
	for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
		if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' ) {
			if ( $sWhere == "" ) {
				$sWhere = "WHERE ";
			} else {
				$sWhere .= " AND ";
			}
			$fieldname = 'sSearch_' . $i;
			$sWhere .= "`".$aColumns[$i]."` LIKE :$fieldname ";
			$filterData[':' . $fieldname] = '%' . $_GET[$fieldname] . '%';
		}
	}
	
	
	/*
	 * SQL queries
	 * Get data to display
	 */
	$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS `".str_replace(" , ", " ", implode("`, `", $aColumns))."`
		FROM   $sTable
		$sWhere
		$sOrder
		$sLimit
		";
	$rResult = mysql_query( $sQuery ) or fatal_error( 'MySQL Error: ' . mysql_errno() );
	$rResult = $ftsdb->run( $sQuery, $filterData );
	if ( !$rResult ) { fatal_error( 'MySQL Error: ' . $ftsdb->error  ); }
	
	/* Data set length after filtering */
	$sQuery = "
		SELECT FOUND_ROWS()
	";
	$rResultFilterTotal = $ftsdb->run($sQuery);
	if ( !$rResultFilterTotal ) { fatal_error( 'MySQL Error: ' . $ftsdb->error  ); }
	$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
	$iFilteredTotal = $aResultFilterTotal[0];
	
	/* Total data set length */
	$sQuery = "
		SELECT COUNT(`".$sIndexColumn."`)
		FROM   $sTable
	";
	$rResultTotal = $ftsdb->run($sQuery);
	if ( !$rResultTotal ) { fatal_error( 'MySQL Error: ' . $ftsdb->error  ); }
	$aResultTotal = mysql_fetch_array($rResultTotal);
	$iTotal = $aResultTotal[0];
	
	
	/*
	 * Output
	 */
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);
	
	foreach ( $rResult as $aRow ) {
		$row = array();
		for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
			if ( $aColumns[$i] == "version" ) {
				/* Special output formatting for 'version' column */
				$row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
			} else if ( $aColumns[$i] != ' ' ) {
				/* General output */
				$row[] = $aRow[ $aColumns[$i] ];
			}
		}
		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output );
}