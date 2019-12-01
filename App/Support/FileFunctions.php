<?php
/***************************************************************************
 *                               FileFunctions.php
 *                            -------------------
 *   begin                : Monday, Aug 20, 2012
 *   copyright            : (C) 2012 Paden Clayton
 *
 *
 ***************************************************************************/


namespace App\Support;

class FileFunctions {
	
	//=================================================
	// Download a file
	//=================================================
	function downloadFile($file, $to) {
		// Download file utilizing fopen
		$fp = fopen($to, 'w+');
		
		if ( ! $fp ) {
			return array('status' => 'error', 'message' => sprintf( 'Could not open handle for fopen() to %s', $to ));
		}
		
		$ch = curl_init($file);
		curl_setopt($ch, CURLOPT_FILE, $fp);		
	 
		curl_exec($ch);
	 
		curl_close($ch);
		fclose($fp);
		
		return array('status' => 'success');
	}
	
	//=================================================
	// Unzip a file to the specified directory
	//=================================================
	function unzip_file($file, $to) {
		// Give the function plenty of memory usage
		increase_memory_limit();
	
		$needed_dirs = array();
		$to = addTrailingSlash($to);
	
		// Determine any parent dir's needed (of the upgrade directory)
		if ( ! is_dir($to) ) { //Only do parents if no children exist
			$path = preg_split('![/\\\]!', removeTrailingSlash($to));
			for ( $i = count($path); $i >= 0; $i-- ) {
				if ( empty($path[$i]) )
					continue;
	
				$dir = implode('/', array_slice($path, 0, $i+1) );
				if ( preg_match('!^[a-z]:$!i', $dir) ) // Skip it if it looks like a Windows Drive letter.
					continue;
	
				if ( ! is_dir($dir) )
					$needed_dirs[] = $dir;
				else
					break; // A folder exists, therefor, we dont need the check the levels below this
			}
		}
	
		if ( class_exists('ZipArchive') ) {
			return $this->unzip_file_ziparchive($file, $to, $needed_dirs);
		}
		
		// If all else fails use PclZip
		return $this->unzip_file_pclzip($file, $to, $needed_dirs);
	}
	
	//=================================================
	// Unzip a file using ZipArchive
	//=================================================
	function unzip_file_ziparchive($file, $to, $needed_dirs = array() ) {
		$z = new ZipArchive();
	
		// PHP4-compat - php4 classes can't contain constants
		$zopen = $z->open($file, /* ZIPARCHIVE::CHECKCONS */ 4);
		if ( true !== $zopen )
			return array('status' => 'error', 'message' => 'Incompatible Archive.');
	
		$uncompressed_size = 0;

		for ( $i = 0; $i < $z->numFiles; $i++ ) {
			if ( ! $info = $z->statIndex($i) )
				return array('status' => 'error', 'message' => 'Could not retrieve file from archive.');
	
			if ( '__MACOSX/' === substr($info['name'], 0, 9) ) // Skip the OS X-created __MACOSX directory
				continue;
				
			if ( '_notes/' === substr($info['name'], 0, 7) ) // Skip the dreamweaver created _notes directory
				continue;
				
			$uncompressed_size += $info['size'];
	
			if ( '/' == substr($info['name'], -1) ) // directory
				$needed_dirs[] = $to . removeTrailingSlash($info['name']);
			else
				$needed_dirs[] = $to . removeTrailingSlash(dirname($info['name']));
		}
	
		/*
		 * disk_free_space() could return false. Assume that any falsey value is an error.
		 * A disk that has zero free bytes has bigger problems.
		 * Require we have enough space to unzip the file and copy its contents, with a 10% buffer.
		 */
		if ( defined( 'IN_CRON' ) && IN_CRON ) {
			$available_space = @disk_free_space( BASEPATH );
			if ( $available_space && ( $uncompressed_size * 2.1 ) > $available_space )
				return array( 'status' => 'error', 'message' => 'Could not copy files. You may have run out of disk space.' );
		}
	
		$needed_dirs = array_unique($needed_dirs);
		foreach ( $needed_dirs as $dir ) {
			// Check the parent folders of the folders all exist within the creation array.
			if ( removeTrailingSlash($to) == $dir ) // Skip over the working directory, We know this exists (or will exist)
				continue;
			if ( strpos($dir, $to) === false ) // If the directory is not within the working directory, Skip it
				continue;
	
			$parent_folder = dirname($dir);
			while ( !empty($parent_folder) && removeTrailingSlash($to) != $parent_folder && !in_array($parent_folder, $needed_dirs) ) {
				$needed_dirs[] = $parent_folder;
				$parent_folder = dirname($parent_folder);
			}
		}
		asort($needed_dirs);
	
		// Create those directories if need be:
		//print_r($needed_dirs);
		foreach ( $needed_dirs as $_dir ) {
			if ( ! @mkdir($_dir, FS_CHMOD_DIR) && ! is_dir($_dir) ) // Only check to see if the Dir exists upon creation failure. Less I/O this way.
				return array('status' => 'error', 'message' => "Could not create directory. $_dir");
		}
		unset($needed_dirs);
	
		for ( $i = 0; $i < $z->numFiles; $i++ ) {
			if ( ! $info = $z->statIndex($i) )
				return array('status' => 'error', 'message' => 'Could not retrieve file from archive.');
	
			if ( '/' == substr($info['name'], -1) ) // directory
				continue;
	
			if ( '__MACOSX/' === substr($info['name'], 0, 9) ) // Don't extract the OS X-created __MACOSX directory files
				continue;
	
			$contents = $z->getFromIndex($i);
			if ( false === $contents )
				return array('status' => 'error', 'message' => 'Could not extract file ('. $info['name'] . ') from archive.');
	
			if ( ! $this->put_contents( $to . $info['name'], $contents, FS_CHMOD_FILE) )
				return array('status' => 'error', 'message' => 'Could not copy file ('. $to . $info['filename'] . ').');
		}
	
		$z->close();
	
		return array('status' => 'success');
	}
	
	//=================================================
	// Unzip a file using PclZip
	//=================================================
	function unzip_file_pclzip($file, $to, $needed_dirs = array()) {
		mbstring_binary_safe_encoding();
	
		require_once(BASEPATH . '/includes/classes/class-pclzip.php');
	
		$archive = new PclZip($file);
	
		$archive_files = $archive->extract(PCLZIP_OPT_EXTRACT_AS_STRING);
	
		reset_mbstring_encoding();
	
		// Is the archive valid?
		if ( !is_array($archive_files) )
			return array('status' => 'error', 'message' => 'Incompatible Archive.' . $archive->errorInfo(true));
	
		if ( 0 == count($archive_files) )
			return array('status' => 'error', 'message' => 'Empty archive.');
	
		$uncompressed_size = 0;
	
		// Determine any children directories needed (From within the archive)
		foreach ( $archive_files as $file ) {
			if ( '__MACOSX/' === substr($file['filename'], 0, 9) ) // Skip the OS X-created __MACOSX directory
				continue;
				
			if ( '_notes/' === substr($info['filename'], 0, 7) ) // Skip the dreamweaver created _notes directory
				continue;
	
			$uncompressed_size += $file['size'];

			$needed_dirs[] = $to . removeTrailingSlash( $file['folder'] ? $file['filename'] : dirname($file['filename']) );
		}
	
		/*
		 * disk_free_space() could return false. Assume that any falsey value is an error.
		 * A disk that has zero free bytes has bigger problems.
		 * Require we have enough space to unzip the file and copy its contents, with a 10% buffer.
		 */
		if ( defined( 'IN_CRON' ) && IN_CRON ) {
			$available_space = @disk_free_space( BASEPATH );
			if ( $available_space && ( $uncompressed_size * 2.1 ) > $available_space )
				return array( 'status' => 'error', 'message' => 'Could not copy files. You may have run out of disk space.' );
		}
	
		$needed_dirs = array_unique($needed_dirs);
		foreach ( $needed_dirs as $dir ) {
			// Check the parent folders of the folders all exist within the creation array.f
			if ( removeTrailingSlash($to) == $dir ) // Skip over the working directory, We know this exists (or will exist)
				continue;
			if ( strpos($dir, $to) === false ) // If the directory is not within the working directory, Skip it
				continue;
	
			$parent_folder = dirname($dir);
			while ( !empty($parent_folder) && removeTrailingSlash($to) != $parent_folder && !in_array($parent_folder, $needed_dirs) ) {
				$needed_dirs[] = $parent_folder;
				$parent_folder = dirname($parent_folder);
			}
		}
		asort($needed_dirs);
	
		// Create those directories if need be:
		foreach ( $needed_dirs as $_dir ) {
			if ( ! @mkdir($_dir, FS_CHMOD_DIR) && ! is_dir($_dir) ) // Only check to see if the dir exists upon creation failure. Less I/O this way.
				return array('status' => 'error', 'message' => 'Could not create directory. $_dir');
		}
		unset($needed_dirs);
	
		// Extract the files from the zip
		foreach ( $archive_files as $file ) {
			if ( $file['folder'] )
				continue;
	
			if ( '__MACOSX/' === substr($file['filename'], 0, 9) ) // Don't extract the OS X-created __MACOSX directory files
				continue;
	
			if ( ! $this->put_contents( $to . $file['filename'], $file['content'], FS_CHMOD_FILE) )
				return array('status' => 'error', 'message' => 'Could not copy file ('. $to . $info['filename'] . ').');
		}
		
		return array('status' => 'success');
	}
	
	//=================================================
	// Put the contents of the file to the specified location
	//=================================================
	function put_contents($file, $contents, $mode = false ) {
		if ( ! ($fp = @fopen($file, 'w')) )
			return false;
		@fwrite($fp, $contents);
		@fclose($fp);
		chmod($file, $mode);
		return true;
	}
	
	//=================================================
	// Copy a file
	//=================================================
	function copy($source, $destination, $overwrite = false) {
		if ( ! $overwrite && $this->exists($destination) )
			return false;

		$rtval = copy($source, $destination);
		return $rtval;
	}
	
	//=================================================
	// Copy a directory
	//=================================================
	function copy_dir($from, $to) {	
		$dirlist = $this->dirlist($from);
	
		$from = addTrailingSlash($from);
		$to = addTrailingSlash($to);
	
		foreach ( (array) $dirlist as $filename => $fileinfo ) {	
			if ( 'f' == $fileinfo['type'] ) {
				if ( ! $this->copy($from . $filename, $to . $filename, true) ) {
					// If copy failed, chmod file to 0644 and try again.
					chmod($to . $filename, 0644);
					if ( ! $this->copy($from . $filename, $to . $filename, true) )
						return array('status' => 'error', 'message' => "Could not copy file ($to $filename).");
				}
			} elseif ( 'd' == $fileinfo['type'] ) {
				if ( !is_dir($to . $filename) ) {
					if ( !mkdir($to . $filename, FS_CHMOD_DIR) )
						return array('status' => 'error', 'message' => "Could not create directory ($to $filename).");
				}
				$result = $this->copy_dir($from . $filename, $to . $filename);
				if ( $result['status'] == 'error' )
					return $result;
			}
		}
		return array('status' => 'success');
	}

	//=================================================
	// Move a file
	//=================================================
	function move($source, $destination, $overwrite = false) {
		if ( ! $overwrite && $this->exists($destination) )
			return false;

		// try using rename first.  if that fails (for example, source is read only) try copy
		if ( @rename($source, $destination) )
			return true;

		if ( $this->copy($source, $destination, $overwrite) && $this->exists($destination) ) {
			$this->delete($source);
			return true;
		} else {
			return false;
		}
	}

	//=================================================
	// Move a directory
	//=================================================
	function move_dir($source, $destination) {
		$dirlist = $this->dirlist($source);
	
		$from = addTrailingSlash($source);
		$to = addTrailingSlash($destination);
	
		foreach ( (array) $dirlist as $filename => $fileinfo ) {	
			if ( is_file($from . $filename) ) {
				if ( ! $this->copy($from . $filename, $to . $filename, true) ) {
					// If copy failed, chmod file to 0644 and try again.
					chmod($to . $filename, 0644);
					if ( ! $this->copy($from . $filename, $to . $filename, true) )
						return array('status' => 'error', 'message' => "Could not copy file ($to $filename).");
				}
			} elseif ( is_dir($from . $filename) ) {
				if ( !is_dir($to . $filename) ) {
					if ( !mkdir($to . $filename) )
						return array('status' => 'error', 'message' => "Could not create directory ($to $filename).");
				}
				$result = $this->move_dir($from . $filename, $to . $filename);
				if ( $result['status'] == 'error' )
					return $result;
			}
			
			// Delete the cource file
			$this->delete($from . $filename);
		}
		return array('status' => 'success');
	}

	//=================================================
	// Delete a file
	//=================================================
	function delete($file, $recursive = false, $type = false) {
		if ( empty($file) ) //Some filesystems report this as /, which can cause non-expected recursive deletion of all files in the filesystem.
			return false;
		$file = str_replace('\\', '/', $file); //for win32, occasional problems deleting files otherwise
		
		if ( is_file($file) )
			return @unlink($file);
		if ( ! $recursive && is_dir($file) )
			return @rmdir($file);

		//At this point its a folder, and we're in recursive mode
		$file = addTrailingSlash($file);
		$filelist = $this->dirlist($file, true);

		$retval = true;
		if ( is_array( $filelist ) ) {
			foreach ( $filelist as $filename => $fileinfo ) {
				if ( ! $this->delete($file . $filename, $recursive, $fileinfo['type']) )
					$retval = false;
			}
		}

		if ( file_exists($file) && ! @rmdir($file) )
			$retval = false;
			
		return $retval;
	}

	/**
	 * Gets file owner
	 *
	 * @param string $file Path to the file.
	 * @return string|bool Username of the user or false on error.
	 */
	public function owner($file) {
		$owneruid = @fileowner($file);
		if ( ! $owneruid )
			return false;
		if ( ! function_exists('posix_getpwuid') )
			return $owneruid;
		$ownerarray = posix_getpwuid($owneruid);
		return $ownerarray['name'];
	}

	/**
	 * Gets file permissions
	 *
	 * FIXME does not handle errors in fileperms()
	 *
	 * @param string $file Path to the file.
	 * @return string Mode of the file (last 3 digits).
	 */
	public function getchmod($file) {
		return substr( decoct( @fileperms( $file ) ), -3 );
	}
	
	/**
	 * Return the *nix-style file permissions for a file.
	 *
	 * From the PHP documentation page for fileperms().
	 *
	 * @link http://docs.php.net/fileperms
	 *
	 * @access public
	 * @since 2.5.0
	 *
	 * @param string $file String filename.
	 * @return string The *nix-style representation of permissions.
	 */
	public function gethchmod( $file ){
		$perms = $this->getchmod($file);
		if (($perms & 0xC000) == 0xC000) // Socket
			$info = 's';
		elseif (($perms & 0xA000) == 0xA000) // Symbolic Link
			$info = 'l';
		elseif (($perms & 0x8000) == 0x8000) // Regular
			$info = '-';
		elseif (($perms & 0x6000) == 0x6000) // Block special
			$info = 'b';
		elseif (($perms & 0x4000) == 0x4000) // Directory
			$info = 'd';
		elseif (($perms & 0x2000) == 0x2000) // Character special
			$info = 'c';
		elseif (($perms & 0x1000) == 0x1000) // FIFO pipe
			$info = 'p';
		else // Unknown
			$info = 'u';

		// Owner
		$info .= (($perms & 0x0100) ? 'r' : '-');
		$info .= (($perms & 0x0080) ? 'w' : '-');
		$info .= (($perms & 0x0040) ?
					(($perms & 0x0800) ? 's' : 'x' ) :
					(($perms & 0x0800) ? 'S' : '-'));

		// Group
		$info .= (($perms & 0x0020) ? 'r' : '-');
		$info .= (($perms & 0x0010) ? 'w' : '-');
		$info .= (($perms & 0x0008) ?
					(($perms & 0x0400) ? 's' : 'x' ) :
					(($perms & 0x0400) ? 'S' : '-'));

		// World
		$info .= (($perms & 0x0004) ? 'r' : '-');
		$info .= (($perms & 0x0002) ? 'w' : '-');
		$info .= (($perms & 0x0001) ?
					(($perms & 0x0200) ? 't' : 'x' ) :
					(($perms & 0x0200) ? 'T' : '-'));
		return $info;
	}

	/**
	 * Convert *nix-style file permissions to a octal number.
	 *
	 * Converts '-rw-r--r--' to 0644
	 * From "info at rvgate dot nl"'s comment on the PHP documentation for chmod()
 	 *
	 * @link http://docs.php.net/manual/en/function.chmod.php#49614
	 *
	 * @access public
	 * @since 2.5.0
	 *
	 * @param string $mode string The *nix-style file permission.
	 * @return int octal representation
	 */
	public function getnumchmodfromh( $mode ) {
		$realmode = '';
		$legal =  array('', 'w', 'r', 'x', '-');
		$attarray = preg_split('//', $mode);

		for ($i=0; $i < count($attarray); $i++)
		   if ($key = array_search($attarray[$i], $legal))
			   $realmode .= $legal[$key];

		$mode = str_pad($realmode, 10, '-', STR_PAD_LEFT);
		$trans = array('-'=>'0', 'r'=>'4', 'w'=>'2', 'x'=>'1');
		$mode = strtr($mode,$trans);

		$newmode = $mode[0];
		$newmode .= $mode[1] + $mode[2] + $mode[3];
		$newmode .= $mode[4] + $mode[5] + $mode[6];
		$newmode .= $mode[7] + $mode[8] + $mode[9];
		return $newmode;
	}

	public function group($file) {
		$gid = @filegroup($file);
		if ( ! $gid )
			return false;
		if ( ! function_exists('posix_getgrgid') )
			return $gid;
		$grouparray = posix_getgrgid($gid);
		return $grouparray['name'];
	}

	function exists($file) {
		return @file_exists($file);
	}

	function is_file($file) {
		return @is_file($file);
	}

	function is_dir($path) {
		return @is_dir($path);
	}

	function is_readable($file) {
		return @is_readable($file);
	}

	function is_writable($file) {
		return @is_writable($file);
	}

	function atime($file) {
		return @fileatime($file);
	}

	function mtime($file) {
		return @filemtime($file);
	}

	function size($file) {
		return @filesize($file);
	}

	function touch($file, $time = 0, $atime = 0) {
		if ($time == 0)
			$time = time();
		if ($atime == 0)
			$atime = time();
		return @touch($file, $time, $atime);
	}

	function mkdir($path, $chmod = false, $chown = false, $chgrp = false) {
		// safe mode fails with a trailing slash under certain PHP versions.
		$path = untrailingslashit($path);
		if ( empty($path) )
			return false;

		if ( ! $chmod )
			$chmod = FS_CHMOD_DIR;

		if ( ! @mkdir($path) )
			return false;
		$this->chmod($path, $chmod);
		if ( $chown )
			$this->chown($path, $chown);
		if ( $chgrp )
			$this->chgrp($path, $chgrp);
		return true;
	}

	function rmdir($path, $recursive = false) {
		return $this->delete($path, $recursive);
	}
	
	//=================================================
	// Get a list of files
	//=================================================
	function dirlist($path, $include_hidden = true, $recursive = false) {
		if ( $this->is_file($path) ) {
			$limit_file = basename($path);
			$path = dirname($path);
		} else {
			$limit_file = false;
		}

		if ( ! $this->is_dir($path) )
			return false;

		$dir = @dir($path);
		if ( ! $dir )
			return false;

		$ret = array();

		while (false !== ($entry = $dir->read()) ) {
			$struc = array();
			$struc['name'] = $entry;

			if ( '.' == $struc['name'] || '..' == $struc['name'] )
				continue;

			if ( ! $include_hidden && '.' == $struc['name'][0] )
				continue;

			if ( $limit_file && $struc['name'] != $limit_file)
				continue;

			$struc['perms'] 	= $this->gethchmod($path.'/'.$entry);
			$struc['permsn']	= $this->getnumchmodfromh($struc['perms']);
			$struc['number'] 	= false;
			$struc['owner']    	= $this->owner($path.'/'.$entry);
			$struc['group']    	= $this->group($path.'/'.$entry);
			$struc['size']    	= $this->size($path.'/'.$entry);
			$struc['lastmodunix']= $this->mtime($path.'/'.$entry);
			$struc['lastmod']   = date('M j',$struc['lastmodunix']);
			$struc['time']    	= date('h:i:s',$struc['lastmodunix']);
			$struc['type']		= $this->is_dir($path.'/'.$entry) ? 'd' : 'f';

			if ( 'd' == $struc['type'] ) {
				if ( $recursive )
					$struc['files'] = $this->dirlist($path . '/' . $struc['name'], $include_hidden, $recursive);
				else
					$struc['files'] = array();
			}

			$ret[ $struc['name'] ] = $struc;
		}
		$dir->close();
		unset($dir);
		return $ret;
	}
}