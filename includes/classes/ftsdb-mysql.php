<?php
/***************************************************************************
 *                               ftsdb-mysql.php
 *                            -------------------
 *   begin                : Monday, July 29, 2013
 *   copyright            : ( C ) 2013 Paden Clayton
 *   email                : sales@fasttracksites.com
 *
 *
 ***************************************************************************/



class ftsdb_mysql {	
	private $__boundParams = array();
	private $__query = '';
	private $__result = null;
	private $dbh;
	private $dbhost;
	private $dbname;
	private $dbpass;
	private $dbuser;
	private $error;
	private $sql;
	private $bind;
	private $errorCallbackFunction;
	private $errorMsgFormat;
	private $insert_id;
	private $profileCallbackFunction;
	private $profileMsgFormat;
	public  $profile;
	private $profileData = array();
	private $real_escape = true;
	public  $rowCount = 0;
	private $startTime;
	
	// PDO Variables
	const FETCH_ASSOC = PDO_FETCH_ASSOC;
	const FETCH_NUM = PDO_FETCH_NUM;
	const FETCH_BOTH = PDO_FETCH_BOTH;
	const FETCH_OBJ = PDO_FETCH_OBJ;
	const FETCH_LAZY = PDO_FETCH_LAZY;
	const FETCH_BOUND = PDO_FETCH_BOUND;
	const ATTR_SERVER_VERSION = PDO_ATTR_SERVER_VERSION;
	const ATTR_CLIENT_VERSION = PDO_ATTR_CLIENT_VERSION;
	const ATTR_SERVER_INFO = PDO_ATTR_SERVER_INFO;
	const ATTR_PERSISTENT = PDO_ATTR_PERSISTENT;

	/**
	 * Sets our variables and issues a database connect call.
	 *
	 * @param string $dsn 					Data Source Name (DSN) string
	 * @param string $user 					The database username
	 * @param string $passwd 				The database password
	 * @param string $profile 				Should we profile the queries for speed testings and debugging
	 */
	public function __construct( $dbhost, $dbname, $dbuser, $dbpass, $serverPort = '3306', $profile = 0 ) {
		$this->dbhost = $dbhost;
		$this->dbname = $dbname;
		$this->dbpass = $dbpass;
		$this->dbuser = $dbuser;
		$this->profile = $profile;
			
		$this->db_connect();
	}
	
	function __uquery( &$query ) {
		if ( !@$query = mysql_query( $query, $this->dbh ) ) {
			$this->__setErrors( 'SQLER' );
			$query = null;
		}
		return $query;
	}

	/**
	 * Real escape, using mysql_real_escape_string() or addslashes()
	 *
	 * @see mysql_real_escape_string()
	 * @see addslashes()
	 *
	 * @param  string $string to escape
	 *
	 * @return string escaped
	 */
	private function _real_escape( $string ) {
		if ( $this->dbh && $this->real_escape )
			return mysql_real_escape_string( $string, $this->dbh );
		else
			return addslashes( $string );
	}
	
	/**
	* Public method:
	*	Replace ? or :named values to execute prepared query
	*       	this->bindParam( $mixed:Mixed, &$variable:Mixed, $type:Integer, $lenght:Integer ):Void
	* @Param	Mixed		Integer or String to replace prepared value
	* @Param	Mixed		variable to replace
	* @Param	Integer		this variable is not used but respects PDO original accepted parameters
	* @Param	Integer		this variable is not used but respects PDO original accepted parameters
	*/
	function bindParam($mixed, &$variable, $type = null, $lenght = null) {
		if(is_string($mixed))
			$this->__boundParams[$mixed] = $variable;
		else
			array_push($this->__boundParams, $variable);
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
	 * Connects to the database server and selects a database
	 *
	 * Does the actual database connection and sets our base settings.
	 */
	public function db_connect( ) {
		$this->dbh = mysql_connect( $this->dbhost, $this->dbuser, $this->dbpass, true );
		
		if ( !$this->dbh ) {
			echo 'Unable to connect to database: ' . mysql_error();

			return;
		}

		$this->selectDB( $this->dbname, $this->dbh );
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
	* Excecutes a query and returns true on success or false.
	*	this->exec( $array:Array ):Boolean
	* @Param	Array		If present, it should contain all replacements for prepared query
	* @Return	Boolean		true if query has been done without errors, false otherwise
	*/
	public function execute( $array = array() ) {
		if ( count( $this->__boundParams ) > 0 )
			$array = &$this->__boundParams;
			
		$__query = $this->__query;
		
		if ( count( $array ) > 0 ) {
			foreach( $array as $k => $v ) {
				if ( !is_int($k) || substr( $k, 0, 1 ) === ':' ) {
					if ( !isset( $tempf ) )
						$tempf = $tempr = array();
					array_push( $tempf, $k );
					array_push( $tempr, '"'.mysql_escape_string($v).'"' );
				}
			}
			if (isset($tempf))
				$__query = str_replace( $tempf, $tempr, $__query );
		}
		
		if ( is_null( $this->__result = &$this->__uquery( $__query ) ) )
			$keyvars = false;
		else
			$keyvars = true;
			
		$this->__boundParams = array();
		
		return $keyvars;
	}
	
	/**
	* Returns, if present, next row of executed query or false.
	*	this->fetch( $mode:Integer, $cursor:Integer, $offset:Integer ):Mixed
	* @Param	Integer		PDO_FETCH_* constant to know how to read next row, default PDO_FETCH_BOTH
	* 				NOTE: if $mode is omitted is used default setted mode, PDO_FETCH_BOTH
	* @Param	Integer		this variable is not used but respects PDO original accepted parameters
	* @Param	Integer		this variable is not used but respects PDO original accepted parameters
	* @Return	Mixed		Next row of executed query or false if there is nomore.
	*/
	public function fetch( $mode = PDO_FETCH_BOTH, $cursor = null, $offset = null ) {
		if ( func_num_args() == 0 )
			$mode = &$this->__fetchmode;
		$result = false;
		if ( !is_null( $this->__result ) ) {
			switch( $mode ) {
				case PDO_FETCH_NUM:
					$result = mysql_fetch_row( $this->__result );
					break;
				case PDO_FETCH_ASSOC:
					$result = mysql_fetch_assoc( $this->__result );
					break;
				case PDO_FETCH_OBJ:
					$result = mysql_fetch_object( $this->__result );	
					break;
				case PDO_FETCH_BOTH:
				default:
					$result = mysql_fetch_array( $this->__result );
					break;
			}
		}
		if (!$result)
			$this->__result = null;
		return $result;
	}
	
	/**
	* Returns an array with all rows of executed query.
	*	this->fetchAll( $mode:Integer ):Array
	* @Param	Integer		PDO_FETCH_* constant to know how to read all rows, default PDO_FETCH_BOTH
	* 				NOTE: this doesn't work as fetch method, then it will use always PDO_FETCH_BOTH
	*                                    if this param is omitted
	* @Return	Array		An array with all fetched rows
	*/
	public function fetchAll( $mode = PDO_FETCH_BOTH ) {
		$result = array();
		if( !is_null( $this->__result ) ) {
			switch( $mode ) {
				case PDO_FETCH_NUM:
					while ( $r = mysql_fetch_row( $this->__result ) )
						array_push($result, $r);
					break;
				case PDO_FETCH_ASSOC:
					while ( $r = mysql_fetch_assoc( $this->__result ) )
						array_push($result, $r);
					break;
				case PDO_FETCH_OBJ:
					while ( $r = mysql_fetch_object( $this->__result ) )
						array_push($result, $r);
					break;
				case PDO_FETCH_BOTH:
				default:
					while ( $r = mysql_fetch_array( $this->__result ) )
						array_push($result, $r);
					break;
			}
		}
		$this->__result = null;
		return $result;
	}
	
	/**
	* Returns, if present, first column of next row of executed query
	*	this->fetchSingle( void ):Mixed
	* @Return	Mixed		Null or next row's first column
	*/
	public function fetchSingle() {
		$result = null;
		if(!is_null($this->__result)) {
			$result = @mysql_fetch_row( $this->__result );
			if ( $result )
				$result = $result[0];
			else
				$this->__result = null;
		}
		return $result;
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
		
		$sql = "DESCRIBE `" . $table . "`;";
		$key = "Field";

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
		foreach( $info as $key => $value)
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
	* Public method:
	*    Returns last inserted id
	*           this->lastInsertId( void ):Number
	* @Return    Number        Last inserted id
	*/
	function lastInsertId() {
		return mysql_insert_id( $this->dbh );
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
		$returnVar = array();
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
	* Returns number of last affected database rows
	*     this->rowCount( void ):Integer
	* @Return	Integer		number of last affected rows
	* 				NOTE: works with INSERT, UPDATE and DELETE query type
	*/
	public function rowCount() {
		return mysql_affected_rows( $this->dbh );
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
	public function run( $sql, $bind = array(), $fetchStyle = FETCH_ASSOC ) {
		if ( $this->profile ) { $this->startTimer(); }
		
		$this->sql = trim( $sql );
		$this->bind = $this->cleanup( $bind );
		$this->error = "";

		try {
			$pdostmt = $this->prepare( $this->sql );
			if( $result = $this->execute( $this->bind ) !== false ) {
				if( preg_match( '/^\s*(select|describe|pragma) /i', $this->sql ) ) {
					$data = $this->fetchAll( $fetchStyle );
					if ( $this->profile ) { $this->profileData[] = array( 'sql' => $this->sql, 'time' => $this->stopTimer() ); }
					return $data;
				} elseif( preg_match( '/^\s*(delete|insert|update) /i', $this->sql ) ) {
					$this->rowCount = $pdostmt->rowCount();
					if ( $this->profile ) { $this->profileData[] = array( 'sql' => $this->sql, 'time' => $this->stopTimer() ); }
					return true;
				} else { return $result; }
			}	
		} catch ( Exception $e ) {
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
	public function select( $table, $where = "", $bind = array(), $fields = "*", $fetchStyle = FETCH_ASSOC ) {
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
	 * Selects a database using the current database connection.
	 *
	 * The database name will be changed based on the current database
	 * connection. On failure, the execution will bail and display an DB error.
	 *
	 * @since 0.71
	 *
	 * @param string $db MySQL database name
	 * @param resource $dbh Optional link identifier.
	 * @return null Always null.
	 */
	function selectDB( $db, $dbh = null ) {
		if ( is_null($dbh) )
			$dbh = $this->dbh;

		if ( !@mysql_select_db( $db, $dbh ) ) {
			echo 'Unable to select database';
			return;
		}
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