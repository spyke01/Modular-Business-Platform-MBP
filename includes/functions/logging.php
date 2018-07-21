<?php 
/***************************************************************************
 *                               logging.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/


 

/**
 * Returns the translated and filtered text for a log event type.
 * 
 * @access public
 * @param mixed $type
 * @return void
 */
function returnLogTypeText( $type ) {
	global $LOG_TYPES; 
	
	return apply_filters( 'returnLogTypeText', __( $LOG_TYPES[$type] ), $type );
}
 
/**
 * Adds a log event in the database.
 * 
 * @param mixed $dataArray		The log event data
 * @return int					The id of the log event
 */
function addLogEvent( $dataArray ) {
	global $ftsdb, $mbp_config; 
	
	if ( !$mbp_config['ftsmbp_enable_logging'] ) 
		return;
	
	// Make sure we have a created date and time
	if ( !isset( $dataArray['created'] ) ) {
		$dataArray['created'] = date("Y-m-d H:i:s");
	}
	
	$result = $ftsdb->insert( DBTABLEPREFIX . 'logging', $dataArray );	
	
	return $ftsdb->lastInsertId();
}

/**
 * Deletes a log event from the database.
 * 
 * @param mixed $id				The ID of the log event
 * @return void
 */
function deleteLogEvent( $id ) {
	global $ftsdb; 
	
	$result = $ftsdb->delete( DBTABLEPREFIX . 'logging', "id = :id", array(
		":id" => $id
	) );
}

/**
 * Returns the value of a log event in the database.
 * 
 * @param mixed $id				The ID of the log event
 * @return array				The log event data
 */
function getLogEvent( $id ) {
	$data = array();
	
	$results = $ftsdb->select( DBTABLEPREFIX . "logging", "id = :id", array(
		":id" => $id,
	) );
	if ( count( $results ) == 0 ) {
		$data = $results[0];
	}
	$results = NULL;
			
	return $data;
}

/**
 * Updates a log event in the database.
 * 
 * @param mixed $id				The ID of the log event
 * @param mixed $dataArray		The log event data
 * @return mixed				The result from the db call execution
 */
function updateLogEvent( $id, $dataArray ) {
	global $ftsdb, $mbp_config; 
	
	if ( !$mbp_config['ftsmbp_enable_logging'] ) 
		return; 
	
	$result = $ftsdb->update( DBTABLEPREFIX . 'logging', $dataArray, "id = :id", array(
			":id" => $id
		)
	);
	
	return $result;
}


/**
 * pruneLogs function.
 * 
 * Cleans up the logging database to minimize clutter and unneeded usage
 *
 * @return void
 */
function pruneLogs() {
	global $ftsdb, $mbp_config; 
	
	if ( $mbp_config['ftsmbp_logging_prune'] == 0 ) 
		return; 

	$result = $ftsdb->delete( DBTABLEPREFIX . 'logging', "created <= DATE_SUB( CURDATE( ) , INTERVAL :months MONTH )", array(
		":months" => $mbp_config['ftsmbp_logging_prune']
	) );	
}