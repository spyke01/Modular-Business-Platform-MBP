<?php
/***************************************************************************
 *                               ftsdb.php
 *                            -------------------
 *   begin                : Monday, July 29, 2013
 *   copyright            : ( C ) 2013 Paden Clayton
 *   email                : sales@fasttracksites.com
 *
 *
 ***************************************************************************/


class ftsdb extends PDO {
	public $error;
	private $sql;
	private $bind;
	private $errorCallbackFunction;
	private $errorMsgFormat;
	private $profileCallbackFunction;
	private $profileMsgFormat;
	public  $profile;
	private $profileData = array();
	public  $rowCount = 0;
	private $startTime;

	/**
	 * Connects to the database server and selects a database
	 *
	 * Does the actual database connection and sets our base settings.
	 *
	 * @param string $dsn 					Data Source Name (DSN) string
	 * @param string $user 					The database username
	 * @param string $passwd 				The database password
	 * @param string $profile 				Should we profile the queries for speed testings and debugging
	 */
	public function __construct( $dsn, $user = "", $passwd = "", $profile = 0 ) {
		$options = array( 
			PDO::ATTR_PERSISTENT => true, 
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		);
		$this->profile = $profile;
			
		try {
			parent::__construct( $dsn, $user, $passwd, $options );
		} catch ( PDOException $e ) {
			$this->error = $e->getMessage();
			echo $e->getMessage();
		}
	}

	/**
	 * Prepares our Bind Array
	 *
	 * Make sure that our bind parameter is an actual array.
	 *
	 * @param  array $bind 					The data for our bound variables
	 *
	 * @return array 						Our proper bind array
	 */
	private function cleanup( $bind ) {
		if( !is_array( $bind ) ) {
			if( !empty( $bind ) )
				$bind = array( $bind );
			else
				$bind = array();
		}
		return $bind;
	}

	/**
	 * Debugging Function
	 *
	 * Debug function which will call our error callback 
	 * function with information about an error.
	 */
	private function debug() {
		if( !empty( $this->errorCallbackFunction ) ) {
			$error = array( "Error" => $this->error );
			if( !empty( $this->sql ) )
				$error["SQL Statement"] = $this->sql;
			if( !empty( $this->bind ) )
				$error["Bind Parameters"] = trim( print_r( $this->bind, true ) );

			$backtrace = debug_backtrace();
			if( !empty( $backtrace ) ) {
				foreach( $backtrace as $info ) {
					if( $info["file"] != __FILE__ )
						$error["Backtrace"] = $info["file"] . " at line " . $info["line"];	
				}		
			}

			$msg = "";
			if( $this->errorMsgFormat == "html" ) {
				if( !empty( $error["Bind Parameters"] ) )
					$error["Bind Parameters"] = "<pre>" . $error["Bind Parameters"] . "</pre>";
				$msg .= "\n" . '<div class="db-error">' . "\n\t<h3>SQL Error</h3>";
				foreach( $error as $key => $val )
					$msg .= "\n\t<label>" . $key . ":</label>" . $val;
				$msg .= "\n\t</div>\n</div>";
			} elseif( $this->errorMsgFormat == "text" ) {
				$msg .= "SQL Error\n" . str_repeat( "-", 50 );
				foreach( $error as $key => $val )
					$msg .= "\n\n$key:\n$val";
			}

			$func = $this->errorCallbackFunction;
			$func( $msg );
		}
	}

	/**
	 * Delete a Table Row
	 *
	 * Easy method to delete a table row using bound paramaeters.
	 *
	 * @param  string $table 				The name of the calling function
	 * @param  string $where 				The name of the calling function
	 * @param  array  $bind 					The data for our bound variables
	 *
	 * @return int 						The number of rows affected
	 */
	public function delete( $table, $where, $bind = array() ) {
		$sql = "DELETE FROM " . $table . " WHERE " . $where . ";";
		return $this->run( $sql, $bind );
	}

	/**
	 * Filters our bound info against the actual columns
	 *
	 * This function makes sure that we are only passing field names 
	 * that actually exist in our database table.
	 *
	 * @param  string $table 				The name of the table we are making changes to
	 * @param  array  $info 					The data we are updating
	 *
	 * @return array 						The list of bound data for actual columns
	 */
	private function filter( $table, $info ) {
		// Check the sql to make sure we aren't passing unneeded bound parameters
		/*
		echo $this->sql;
		echo 'before: ' . var_export($info, true);
		foreach( $info as $key => $value)
			if (stristr($this->sql, $key) === FALSE) unset($info[$key]);	
		
		echo 'after: ' . var_export($info, true);	
		*/
		//echo $table;
		//print_r(array_keys( $info ));
		
		// If we use a shorthand table like `MBP_config` c it breaks the DESCRIBE method so lets trip it off
		$pos = strrpos($table, '`');
		if ($pos !== false)
			$table = substr($table, 0, $pos);
			
		$table = trim( $table, '`' );
		
		$driver = $this->getAttribute( PDO::ATTR_DRIVER_NAME );
		if( $driver == 'sqlite' ) {
			$sql = "PRAGMA table_info( '" . $table . "' );";
			$key = "name";
		} elseif( $driver == 'mysql' ) {
			$sql = "DESCRIBE `" . $table . "`;";
			$key = "Field";
		} else {	
			$sql = "SELECT column_name FROM information_schema.columns WHERE table_name = '" . $table . "';";
			$key = "column_name";
		}	

		if( false !== ( $list = $this->run( $sql ) ) ) {
			$fields = array();
			foreach( $list as $record )
				$fields[] = $record[$key];
			//print_r($fields);
			//print_r(array_keys( $info ));
			return array_values( array_intersect( $fields, array_keys( $info ) ) );
		}
		return array();
	}

	/**
	 * Filters our bound info against the sql
	 *
	 * This function makes sure that we are only passing field names 
	 * that actually exist in our sql string.
	 *
	 * @param  array  $info 					The data we are updating
	 * @param  string $info 					Our SQL query
	 *
	 * @return array 						The list of bound data for actual columns and the new sql query
	 */
	private function filterBindsAgainstSQL( $info, $sql ) {
		// We can't use binds on LIMIT since we set our binds via the execute() method so we parse for LIMIT here and handle it accordingly
		// LIMIT :limit#
		preg_match( '/LIMIT :(?P<limit>[0-9a-zA-Z]*)/', $sql, $matches );
		if (count($matches)) {
			//print_r($matches);
			$sql = str_replace( $matches[0], 'LIMIT ' . intval( $info[':' . $matches['limit']] ), $sql );
		}		
		// LIMIT #, :limit#
		preg_match( '/LIMIT (?P<limit1>[0-9]*),\s?:(?P<limit2>[0-9a-zA-Z]*)/', $sql, $matches );
		if (count($matches)) {
			//print_r($matches);
			$sql = str_replace( $matches[0], 'LIMIT ' . $matches['limit1'] . ',' . intval( $info[':' . $matches['limit2']] ), $sql );
		}
		
		// Check the sql to make sure we aren't passing unneeded bound parameters
		//echo $sql;
		//echo 'before: ' . var_export( $info, true );
		foreach( (array)$info as $key => $value)
			if ( stristr($sql, $key) === FALSE ) unset( $info[$key] );	
		
		//echo 'after: ' . var_export($info, true);
		
		return array('info' => $info, 'sql' => $sql);
	}

	/**
	 * Insert a Table Row
	 *
	 * Easy method to insert a table row using bound paramaeters.
	 *
	 * @param  string $table 				The name of the table we are making changes to
	 * @param  array  $info 					The data we are updating
	 *
	 * @return int 						The number of rows affected
	 */
	public function insert( $table, $info ) {
		$fields = $this->filter( $table, $info );
		$sql = "INSERT INTO " . $table . " ( `" . implode( $fields, "`, `" ) . "` ) VALUES ( :" . implode( $fields, ", :" ) . " );";
		$bind = array();
		foreach( $fields as $field )
			$bind[":$field"] = $info[$field];
		return $this->run( $sql, $bind );
	}

	/**
	 * Prepare an Array for Insert
	 * 
	 * When using the IN clause for a set of data such as 1,2,3,4 with a single bind parameter 
	 * PDO would make this into '1,2,3,4' which is not the same as '1','2','3','4' . This function
	 * breaks the data into individual binds for proper usage.
	 *
	 *
	 * @param  string $data 					The normal in clause data string
	 * @param  string $prefix 				The prefix to use on bound parameters
	 *
	 * @return array 						The prepared binds and their data
	 */
	public function prepareInClauseVariable( $data, $prefix = 'inItem' ) {
		$returnVar = array( 'binds' => array() );
		if ( !is_array( $data ) ) $data = explode(',', $data);
		
		for($i=0; $i<count($data); $i++){
			$returnVar['binds'][] = ':' . $prefix . $i;
			$returnVar['data'][':' . $prefix . $i] = $data[$i];
		}
		$returnVar['binds'] = implode(',', $returnVar['binds']);
		
		return $returnVar;
	}

	/**
	 * Profiling Function
	 *
	 * Returns our profiling information so we can debug queries.
	 */
	public function profile() {
		if( !empty( $this->profileCallbackFunction ) && $this->profile == 1 ) {
			$msg = "";
			if( $this->profileMsgFormat == "html" ) {
				$msg .= "\n" . '<div class="db-profile">' . "\n\t<h3>SQL Profile</h3>";
				foreach( $this->profileData as $sqlProfile )
					$msg .= "\n\t<label>" . $sqlProfile['sql'] . ":</label>" . $sqlProfile['time'];
				$msg .= "\n\t</div>\n</div>";
			} elseif( $this->profileMsgFormat == "text" ) {
				$msg .= "SQL Profile\n" . str_repeat( "-", 50 );
				foreach( $this->profileData as $sqlProfile )
					$msg .= "\n\n" . $sqlProfile['sql'] . " :\n" . $sqlProfile['time'];
			}

			$func = $this->profileCallbackFunction;
			$func( $msg );
		}
	}

	/**
	 * Runs a Prepared SQL Query
	 *
	 * All of our custom functions pass through here so it is 
	 * the central point for preperation and execution.
	 *
	 * @param  string $sql 					The sql we are running
	 * @param  array  $bind 					The data for our bound variables
	 * @param  string $fetchStyle 			Allows you to choose a PDO fetch style
	 *
	 * @return int 						The number of rows affected
	 */
	public function run( $sql, $bind = array(), $fetchStyle = PDO::FETCH_ASSOC ) {
		if ( $this->profile ) { $this->startTimer(); }
		
		$this->sql = trim( $sql );
		$this->bind = $this->cleanup( $bind );
		$this->error = "";

		try {
			$pdostmt = $this->prepare( $this->sql );
			if( $result = $pdostmt->execute( $this->bind ) !== false ) {
				if( preg_match( "/^(" . implode( "|", array( "select", "describe", "pragma" ) ) . ") /i", $this->sql ) ) {
					$data = $pdostmt->fetchAll( $fetchStyle );
					if ( $this->profile ) { $this->profileData[] = array( 'sql' => $this->sql, 'time' => $this->stopTimer() ); }
					return $data;
				} elseif( preg_match( "/^(" . implode( "|", array( "delete", "insert", "update" ) ) . ") /i", $this->sql ) ) {
					$this->rowCount = $pdostmt->rowCount();
					if ( $this->profile ) { $this->profileData[] = array( 'sql' => $this->sql, 'time' => $this->stopTimer() ); }
					return true;
				} else { return $result; }
			}	
		} catch ( PDOException $e ) {
			$this->error = $e->getMessage();	
			$this->debug();
			return false;
		}
	}

	/**
	 * Select Row(s) From the Database
	 *
	 * Easy method to select table row(s) using bound paramaeters.
	 *
	 * @param  string $table 				The name of the table we are making changes to
	 * @param  string $where 				The where clause
	 * @param  array  $bind 					The data for our bound variables
	 * @param  string $fields 				The fields we are selecting
	 * @param  string $fetchStyle 			Allows you to choose a PDO fetch style
	 *
	 * @return array 						The row data
	 */
	public function select( $table, $where = "", $bind = array(), $fields = "*", $fetchStyle = PDO::FETCH_ASSOC ) {
		$sql = "SELECT " . $fields . " FROM " . $table;
		if( !empty( $where ) )
			$sql .= " WHERE " . $where;
		$sql .= ";";
		
		// Filter our SQL and Bind info
		$filteredData = $this->filterBindsAgainstSQL( $bind, $sql );
		$bind = $filteredData['info'];
		$sql = $filteredData['sql'];
		//echo "$sql " . var_export($bind, true) . "<br />";
		return $this->run( $sql, $bind, $fetchStyle );
	}

	/**
	 * Sets the Callback Function for Debugging
	 *
	 * @param  string $errorCallbackFunction  	The name of the function to call
	 * @param  string $errorMsgFormat  		What type of error message we should display
	 */
	public function setErrorCallbackFunction( $errorCallbackFunction, $errorMsgFormat = "html" ) {
		//Variable functions for won't work with language constructs such as echo and print, so these are replaced with print_r.
		if( in_array( strtolower( $errorCallbackFunction ), array( "echo", "print" ) ) )
			$errorCallbackFunction = "print_r";

		if( function_exists( $errorCallbackFunction ) ) {
			$this->errorCallbackFunction = $errorCallbackFunction;	
			if( !in_array( strtolower( $errorMsgFormat ), array( "html", "text" ) ) )
				$errorMsgFormat = "html";
			$this->errorMsgFormat = $errorMsgFormat;	
		}	
	}

	/**
	 * Sets the Callback Function for Profiling
	 *
	 * @param  string $profileCallbackFunction 	The name of the function to call
	 * @param  string $profileMsgFormat 		What type of error message we should display
	 */
	public function setProfileCallbackFunction( $profileCallbackFunction, $profileMsgFormat = "html" ) {
		//Variable functions for won't work with language constructs such as echo and print, so these are replaced with print_r.
		if( in_array( strtolower( $profileCallbackFunction ), array( "echo", "print" ) ) )
			$profileCallbackFunction = "print_r";

		if( function_exists( $profileCallbackFunction ) ) {
			$this->profileCallbackFunction = $profileCallbackFunction;	
			if( !in_array( strtolower( $profileMsgFormat ), array( "html", "text" ) ) )
				$profileMsgFormat = "html";
			$this->profileMsgFormat = $profileMsgFormat;	
		}	
	}

	/**
	 * Update a Table Row
	 *
	 * Easy method to update a table row using bound paramaeters.
	 *
	 * @param  string $table 				The name of the table we are making changes to
	 * @param  array  $info 					The data we are updating
	 * @param  string $where 				The where clause
	 * @param  array  $bind 					The data for our bound variables
	 *
	 * @return int 						The number of rows affected
	 */
	public function update( $table, $info, $where, $bind = array() ) {
		$fields = $this->filter( $table, $info );
		$fieldSize = sizeof( $fields );

		$sql = "UPDATE " . $table . " SET ";
		for( $f = 0; $f < $fieldSize; ++$f ) {
			if( $f > 0 )
				$sql .= ", ";
			$sql .= '`' . $fields[$f] . "` = :update_" . $fields[$f]; 
		}
		$sql .= " WHERE " . $where . ";";

		$bind = $this->cleanup( $bind );
		
		// Filter our SQL and Bind info
		$filteredData = $this->filterBindsAgainstSQL( $bind, $sql );
		$bind = $filteredData['info'];
		$sql = $filteredData['sql'];
		
		// Add our fields
		foreach( $fields as $field )
			$bind[":update_$field"] = $info[$field];
			
		//echo "$sql " . var_export($bind, true) . "<br />";
		
		return $this->run( $sql, $bind );
	}

	/**
	 * Start the Timer
	 *
	 * Starts a timer so we can profile a query for sped tests 
	 * and debugging purposes.
	 */
	public function startTimer() {
		$this->startTime = microtime( true );
	}

	/**
	 * Stops the Timer
	 *
	 * Stops a timer and returns the run time so we can use it.
	 *
	 * @return int  						The run time
	 */
	public function stopTimer() {
		return ( microtime( true ) - $this->startTime );
	}
}