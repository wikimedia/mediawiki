<?php
/**
 * A repository for files accessible via the local filesystem.
 *
 * @file
 * @ingroup FileRepo
 */

/**
 * A repository for files accessible via the local filesystem. Does not support
 * database access or registration.
 * @ingroup FileRepo
 */
class FSRepo extends FileRepo {
	var $directory, $deletedDir, $deletedHashLevels, $fileMode;
	var $fileFactory = array( 'UnregisteredLocalFile', 'newFromTitle' );
	var $oldFileFactory = false;
	var $pathDisclosureProtection = 'simple';

	function __construct( $info ) {
		parent::__construct( $info );

		// Required settings
		$this->directory = $info['directory'];
		$this->url = $info['url'];

		// Optional settings
		$this->hashLevels = isset( $info['hashLevels'] ) ? $info['hashLevels'] : 2;
		$this->deletedHashLevels = isset( $info['deletedHashLevels'] ) ?
			$info['deletedHashLevels'] : $this->hashLevels;
		$this->deletedDir = isset( $info['deletedDir'] ) ? $info['deletedDir'] : false;
		$this->fileMode = isset( $info['fileMode'] ) ? $info['fileMode'] : 0644;
		if ( isset( $info['thumbDir'] ) ) {
			$this->thumbDir =  $info['thumbDir'];
		} else {
			$this->thumbDir = "{$this->directory}/thumb";
		}
		if ( isset( $info['thumbUrl'] ) ) {
			$this->thumbUrl = $info['thumbUrl'];
		} else {
			$this->thumbUrl = "{$this->url}/thumb";
		}
	}

	/**
	 * Get the public root directory of the repository.
	 */
	function getRootDirectory() {
		return $this->directory;
	}

	/**
	 * Get the public root URL of the repository
	 */
	function getRootUrl() {
		return $this->url;
	}

	/**
	 * Returns true if the repository uses a multi-level directory structure
	 */
	function isHashed() {
		return (bool)$this->hashLevels;
	}

	/**
	 * Get the local directory corresponding to one of the three basic zones
	 */
	function getZonePath( $zone ) {
		switch ( $zone ) {
			case 'public':
				return $this->directory;
			case 'temp':
				return "{$this->directory}/temp";
			case 'deleted':
				return $this->deletedDir;
			case 'thumb':
				return $this->thumbDir;
			default:
				return false;
		}
	}

	/**
	 * @see FileRepo::getZoneUrl()
	 */
	function getZoneUrl( $zone ) {
		switch ( $zone ) {
			case 'public':
				return $this->url;
			case 'temp':
				return "{$this->url}/temp";
			case 'deleted':
				return parent::getZoneUrl( $zone ); // no public URL
			case 'thumb':
				return $this->thumbUrl;
			default:
				return parent::getZoneUrl( $zone );
		}
	}

	/**
	 * Get a URL referring to this repository, with the private mwrepo protocol.
	 * The suffix, if supplied, is considered to be unencoded, and will be
	 * URL-encoded before being returned.
	 */
	function getVirtualUrl( $suffix = false ) {
		$path = 'mwrepo://' . $this->name;
		if ( $suffix !== false ) {
			$path .= '/' . rawurlencode( $suffix );
		}
		return $path;
	}

	/**
	 * Get the local path corresponding to a virtual URL
	 */
	function resolveVirtualUrl( $url ) {
		if ( substr( $url, 0, 9 ) != 'mwrepo://' ) {
			throw new MWException( __METHOD__.': unknown protoocl' );
		}

		$bits = explode( '/', substr( $url, 9 ), 3 );
		if ( count( $bits ) != 3 ) {
			throw new MWException( __METHOD__.": invalid mwrepo URL: $url" );
		}
		list( $repo, $zone, $rel ) = $bits;
		if ( $repo !== $this->name ) {
			throw new MWException( __METHOD__.": fetching from a foreign repo is not supported" );
		}
		$base = $this->getZonePath( $zone );
		if ( !$base ) {
			throw new MWException( __METHOD__.": invalid zone: $zone" );
		}
		return $base . '/' . rawurldecode( $rel );
	}

	/**
	 * Store a batch of files
	 *
	 * @param $triplets Array: (src,zone,dest) triplets as per store()
	 * @param $flags Integer: bitwise combination of the following flags:
	 *     self::DELETE_SOURCE     Delete the source file after upload
	 *     self::OVERWRITE         Overwrite an existing destination file instead of failing
	 *     self::OVERWRITE_SAME    Overwrite the file if the destination exists and has the
	 *                             same contents as the source
	 */
	function storeBatch( $triplets, $flags = 0 ) {
		wfDebug( __METHOD__  . ': Storing ' . count( $triplets ) . 
			" triplets; flags: {$flags}\n" );
		
		// Try creating directories
		if ( !wfMkdirParents( $this->directory ) ) {
			return $this->newFatal( 'upload_directory_missing', $this->directory );
		}
		if ( !is_writable( $this->directory ) ) {
			return $this->newFatal( 'upload_directory_read_only', $this->directory );
		}
		
		// Validate each triplet 
		$status = $this->newGood();
		foreach ( $triplets as $i => $triplet ) {
			list( $srcPath, $dstZone, $dstRel ) = $triplet;

			// Resolve destination path
			$root = $this->getZonePath( $dstZone );
			if ( !$root ) {
				throw new MWException( "Invalid zone: $dstZone" );
			}
			if ( !$this->validateFilename( $dstRel ) ) {
				throw new MWException( 'Validation error in $dstRel' );
			}
			$dstPath = "$root/$dstRel";
			$dstDir = dirname( $dstPath );

			// Create destination directories for this triplet
			if ( !is_dir( $dstDir ) ) {
				if ( !wfMkdirParents( $dstDir ) ) {
					return $this->newFatal( 'directorycreateerror', $dstDir );
				}
				if ( $dstZone == 'deleted' ) {
					$this->initDeletedDir( $dstDir );
				}
			}

			// Resolve source 
			if ( self::isVirtualUrl( $srcPath ) ) {
				$srcPath = $triplets[$i][0] = $this->resolveVirtualUrl( $srcPath );
			}
			if ( !is_file( $srcPath ) ) {
				// Make a list of files that don't exist for return to the caller
				$status->fatal( 'filenotfound', $srcPath );
				continue;
			}
			
			// Check overwriting
			if ( !( $flags & self::OVERWRITE ) && file_exists( $dstPath ) ) {
				if ( $flags & self::OVERWRITE_SAME ) {
					$hashSource = sha1_file( $srcPath );
					$hashDest = sha1_file( $dstPath );
					if ( $hashSource != $hashDest ) {
						$status->fatal( 'fileexistserror', $dstPath );
					}
				} else {
					$status->fatal( 'fileexistserror', $dstPath );
				}
			}
		}

		// Windows does not support moving over existing files, so explicitly delete them
		$deleteDest = wfIsWindows() && ( $flags & self::OVERWRITE );

		// Abort now on failure
		if ( !$status->ok ) {
			return $status;
		}

		// Execute the store operation for each triplet
		foreach ( $triplets as $i => $triplet ) {
			list( $srcPath, $dstZone, $dstRel ) = $triplet;
			$root = $this->getZonePath( $dstZone );
			$dstPath = "$root/$dstRel";
			$good = true;

			if ( $flags & self::DELETE_SOURCE ) {
				if ( $deleteDest ) {
					unlink( $dstPath );
				}
				if ( !rename( $srcPath, $dstPath ) ) {
					$status->error( 'filerenameerror', $srcPath, $dstPath );
					$good = false;
				}
			} else {
				if ( !copy( $srcPath, $dstPath ) ) {
					$status->error( 'filecopyerror', $srcPath, $dstPath );
					$good = false;
				}
				if ( !( $flags & self::SKIP_VALIDATION ) ) {
					wfSuppressWarnings();
					$hashSource = sha1_file( $srcPath );
					$hashDest = sha1_file( $dstPath );
					wfRestoreWarnings();
					
					if ( $hashDest === false || $hashSource !== $hashDest ) {
						wfDebug( __METHOD__ . ': File copy validation failed: ' . 
							"$srcPath ($hashSource) to $dstPath ($hashDest)\n" );
						
						$status->error( 'filecopyerror', $srcPath, $dstPath );
						$good = false;
					}
				}
			}
			if ( $good ) {
				$this->chmod( $dstPath );
				$status->successCount++;
			} else {
				$status->failCount++;
			}
			$status->success[$i] = $good;
		}
		return $status;
	}
	
	/**
	 * Deletes a batch of (zone, rel) pairs. It will try to delete each pair,
	 * but ignores any errors doing so.
	 * 
	 * @param $pairs array Pair of (zone, rel) pairs to delete
	 */
	function cleanupBatch( $pairs ) {
		foreach ( $pairs as $pair ) {
			list( $zone, $rel ) = $pair;
			$root = $this->getZonePath( $zone );
			$path = "$root/$rel";
			
			wfSuppressWarnings();
			unlink( $path );
			wfRestoreWarnings();
		}
	}

	function append( $srcPath, $toAppendPath, $flags = 0 ) {
		$status = $this->newGood();

		// Resolve the virtual URL
		if ( self::isVirtualUrl( $srcPath ) ) {
			$srcPath = $this->resolveVirtualUrl( $srcPath );
		}
		// Make sure the files are there
		if ( !is_file( $srcPath ) )
			$status->fatal( 'filenotfound', $srcPath );

		if ( !is_file( $toAppendPath ) )
			$status->fatal( 'filenotfound', $toAppendPath );

		if ( !$status->isOk() ) return $status;

		// Do the append
		$chunk = file_get_contents( $toAppendPath );
		if( $chunk === false ) {
			$status->fatal( 'fileappenderrorread', $toAppendPath );
		}

		if( $status->isOk() ) {
			if ( file_put_contents( $srcPath, $chunk, FILE_APPEND ) ) {
				$status->value = $srcPath;
			} else {
				$status->fatal( 'fileappenderror', $toAppendPath,  $srcPath);
			}
		}

		if ( $flags & self::DELETE_SOURCE ) {
			unlink( $toAppendPath );
		}

		return $status;
	}

	/**
	 * Checks existence of specified array of files.
	 *
	 * @param $files Array: URLs of files to check
	 * @param $flags Integer: bitwise combination of the following flags:
	 *     self::FILES_ONLY     Mark file as existing only if it is a file (not directory)
	 * @return Either array of files and existence flags, or false
	 */
	function fileExistsBatch( $files, $flags = 0 ) {
		if ( !file_exists( $this->directory ) || !is_readable( $this->directory ) ) {
			return false;
		}
		$result = array();
		foreach ( $files as $key => $file ) {
			if ( self::isVirtualUrl( $file ) ) {
				$file = $this->resolveVirtualUrl( $file );
			}
			if( $flags & self::FILES_ONLY ) {
				$result[$key] = is_file( $file );
			} else {
				$result[$key] = file_exists( $file );
			}
		}

		return $result;
	}

	/**
	 * Take all available measures to prevent web accessibility of new deleted
	 * directories, in case the user has not configured offline storage
	 */
	protected function initDeletedDir( $dir ) {
		// Add a .htaccess file to the root of the deleted zone
		$root = $this->getZonePath( 'deleted' );
		if ( !file_exists( "$root/.htaccess" ) ) {
			file_put_contents( "$root/.htaccess", "Deny from all\n" );
		}
		// Seed new directories with a blank index.html, to prevent crawling
		file_put_contents( "$dir/index.html", '' );
	}

	/**
	 * Pick a random name in the temp zone and store a file to it.
	 * @param $originalName String: the base name of the file as specified
	 *     by the user. The file extension will be maintained.
	 * @param $srcPath String: the current location of the file.
	 * @return FileRepoStatus object with the URL in the value.
	 */
	function storeTemp( $originalName, $srcPath ) {
		$date = gmdate( "YmdHis" );
		$hashPath = $this->getHashPath( $originalName );
		$dstRel = "$hashPath$date!$originalName";
		$dstUrlRel = $hashPath . $date . '!' . rawurlencode( $originalName );

		$result = $this->store( $srcPath, 'temp', $dstRel );
		$result->value = $this->getVirtualUrl( 'temp' ) . '/' . $dstUrlRel;
		return $result;
	}

	/**
	 * Remove a temporary file or mark it for garbage collection
	 * @param $virtualUrl String: the virtual URL returned by storeTemp
	 * @return Boolean: true on success, false on failure
	 */
	function freeTemp( $virtualUrl ) {
		$temp = "mwrepo://{$this->name}/temp";
		if ( substr( $virtualUrl, 0, strlen( $temp ) ) != $temp ) {
			wfDebug( __METHOD__.": Invalid virtual URL\n" );
			return false;
		}
		$path = $this->resolveVirtualUrl( $virtualUrl );
		wfSuppressWarnings();
		$success = unlink( $path );
		wfRestoreWarnings();
		return $success;
	}

	/**
	 * Publish a batch of files
	 * @param $triplets Array: (source,dest,archive) triplets as per publish()
	 * @param $flags Integer: bitfield, may be FileRepo::DELETE_SOURCE to indicate
	 *        that the source files should be deleted if possible
	 */
	function publishBatch( $triplets, $flags = 0 ) {
		// Perform initial checks
		if ( !wfMkdirParents( $this->directory ) ) {
			return $this->newFatal( 'upload_directory_missing', $this->directory );
		}
		if ( !is_writable( $this->directory ) ) {
			return $this->newFatal( 'upload_directory_read_only', $this->directory );
		}
		$status = $this->newGood( array() );
		foreach ( $triplets as $i => $triplet ) {
			list( $srcPath, $dstRel, $archiveRel ) = $triplet;

			if ( substr( $srcPath, 0, 9 ) == 'mwrepo://' ) {
				$triplets[$i][0] = $srcPath = $this->resolveVirtualUrl( $srcPath );
			}
			if ( !$this->validateFilename( $dstRel ) ) {
				throw new MWException( 'Validation error in $dstRel' );
			}
			if ( !$this->validateFilename( $archiveRel ) ) {
				throw new MWException( 'Validation error in $archiveRel' );
			}
			$dstPath = "{$this->directory}/$dstRel";
			$archivePath = "{$this->directory}/$archiveRel";

			$dstDir = dirname( $dstPath );
			$archiveDir = dirname( $archivePath );
			// Abort immediately on directory creation errors since they're likely to be repetitive
			if ( !is_dir( $dstDir ) && !wfMkdirParents( $dstDir ) ) {
				return $this->newFatal( 'directorycreateerror', $dstDir );
			}
			if ( !is_dir( $archiveDir ) && !wfMkdirParents( $archiveDir ) ) {
				return $this->newFatal( 'directorycreateerror', $archiveDir );
			}
			if ( !is_file( $srcPath ) ) {
				// Make a list of files that don't exist for return to the caller
				$status->fatal( 'filenotfound', $srcPath );
			}
		}

		if ( !$status->ok ) {
			return $status;
		}

		foreach ( $triplets as $i => $triplet ) {
			list( $srcPath, $dstRel, $archiveRel ) = $triplet;
			$dstPath = "{$this->directory}/$dstRel";
			$archivePath = "{$this->directory}/$archiveRel";

			// Archive destination file if it exists
			if( is_file( $dstPath ) ) {
				// Check if the archive file exists
				// This is a sanity check to avoid data loss. In UNIX, the rename primitive
				// unlinks the destination file if it exists. DB-based synchronisation in
				// publishBatch's caller should prevent races. In Windows there's no
				// problem because the rename primitive fails if the destination exists.
				if ( is_file( $archivePath ) ) {
					$success = false;
				} else {
					wfSuppressWarnings();
					$success = rename( $dstPath, $archivePath );
					wfRestoreWarnings();
				}

				if( !$success ) {
					$status->error( 'filerenameerror',$dstPath, $archivePath );
					$status->failCount++;
					continue;
				} else {
					wfDebug(__METHOD__.": moved file $dstPath to $archivePath\n");
				}
				$status->value[$i] = 'archived';
			} else {
				$status->value[$i] = 'new';
			}

			$good = true;
			wfSuppressWarnings();
			if ( $flags & self::DELETE_SOURCE ) {
				if ( !rename( $srcPath, $dstPath ) ) {
					$status->error( 'filerenameerror', $srcPath, $dstPath );
					$good = false;
				}
			} else {
				if ( !copy( $srcPath, $dstPath ) ) {
					$status->error( 'filecopyerror', $srcPath, $dstPath );
					$good = false;
				}
			}
			wfRestoreWarnings();

			if ( $good ) {
				$status->successCount++;
				wfDebug(__METHOD__.": wrote tempfile $srcPath to $dstPath\n");
				// Thread-safe override for umask
				$this->chmod( $dstPath );
			} else {
				$status->failCount++;
			}
		}
		return $status;
	}

	/**
	 * Move a group of files to the deletion archive.
	 * If no valid deletion archive is configured, this may either delete the
	 * file or throw an exception, depending on the preference of the repository.
	 *
	 * @param $sourceDestPairs Array of source/destination pairs. Each element
	 *        is a two-element array containing the source file path relative to the
	 *        public root in the first element, and the archive file path relative
	 *        to the deleted zone root in the second element.
	 * @return FileRepoStatus
	 */
	function deleteBatch( $sourceDestPairs ) {
		$status = $this->newGood();
		if ( !$this->deletedDir ) {
			throw new MWException( __METHOD__.': no valid deletion archive directory' );
		}

		/**
		 * Validate filenames and create archive directories
		 */
		foreach ( $sourceDestPairs as $pair ) {
			list( $srcRel, $archiveRel ) = $pair;
			if ( !$this->validateFilename( $srcRel ) ) {
				throw new MWException( __METHOD__.':Validation error in $srcRel' );
			}
			if ( !$this->validateFilename( $archiveRel ) ) {
				throw new MWException( __METHOD__.':Validation error in $archiveRel' );
			}
			$archivePath = "{$this->deletedDir}/$archiveRel";
			$archiveDir = dirname( $archivePath );
			if ( !is_dir( $archiveDir ) ) {
				if ( !wfMkdirParents( $archiveDir ) ) {
					$status->fatal( 'directorycreateerror', $archiveDir );
					continue;
				}
				$this->initDeletedDir( $archiveDir );
			}
			// Check if the archive directory is writable
			// This doesn't appear to work on NTFS
			if ( !is_writable( $archiveDir ) ) {
				$status->fatal( 'filedelete-archive-read-only', $archiveDir );
			}
		}
		if ( !$status->ok ) {
			// Abort early
			return $status;
		}

		/**
		 * Move the files
		 * We're now committed to returning an OK result, which will lead to
		 * the files being moved in the DB also.
		 */
		foreach ( $sourceDestPairs as $pair ) {
			list( $srcRel, $archiveRel ) = $pair;
			$srcPath = "{$this->directory}/$srcRel";
			$archivePath = "{$this->deletedDir}/$archiveRel";
			$good = true;
			if ( file_exists( $archivePath ) ) {
				# A file with this content hash is already archived
				if ( !@unlink( $srcPath ) ) {
					$status->error( 'filedeleteerror', $srcPath );
					$good = false;
				}
			} else{
				if ( !@rename( $srcPath, $archivePath ) ) {
					$status->error( 'filerenameerror', $srcPath, $archivePath );
					$good = false;
				} else {
					$this->chmod( $archivePath );
				}
			}
			if ( $good ) {
				$status->successCount++;
			} else {
				$status->failCount++;
			}
		}
		return $status;
	}

	/**
	 * Get a relative path for a deletion archive key,
	 * e.g. s/z/a/ for sza251lrxrc1jad41h5mgilp8nysje52.jpg
	 */
	function getDeletedHashPath( $key ) {
		$path = '';
		for ( $i = 0; $i < $this->deletedHashLevels; $i++ ) {
			$path .= $key[$i] . '/';
		}
		return $path;
	}

	/**
	 * Call a callback function for every file in the repository.
	 * Uses the filesystem even in child classes.
	 */
	function enumFilesInFS( $callback ) {
		$numDirs = 1 << ( $this->hashLevels * 4 );
		for ( $flatIndex = 0; $flatIndex < $numDirs; $flatIndex++ ) {
			$hexString = sprintf( "%0{$this->hashLevels}x", $flatIndex );
			$path = $this->directory;
			for ( $hexPos = 0; $hexPos < $this->hashLevels; $hexPos++ ) {
				$path .= '/' . substr( $hexString, 0, $hexPos + 1 );
			}
			if ( !file_exists( $path ) || !is_dir( $path ) ) {
				continue;
			}
			$dir = opendir( $path );
			while ( false !== ( $name = readdir( $dir ) ) ) {
				call_user_func( $callback, $path . '/' . $name );
			}
		}
	}

	/**
	 * Call a callback function for every file in the repository
	 * May use either the database or the filesystem
	 */
	function enumFiles( $callback ) {
		$this->enumFilesInFS( $callback );
	}

	/**
	 * Get properties of a file with a given virtual URL
	 * The virtual URL must refer to this repo
	 */
	function getFileProps( $virtualUrl ) {
		$path = $this->resolveVirtualUrl( $virtualUrl );
		return File::getPropsFromPath( $path );
	}

	/**
	 * Path disclosure protection functions
	 *
	 * Get a callback function to use for cleaning error message parameters
	 */
	function getErrorCleanupFunction() {
		switch ( $this->pathDisclosureProtection ) {
			case 'simple':
				$callback = array( $this, 'simpleClean' );
				break;
			default:
				$callback = parent::getErrorCleanupFunction();
		}
		return $callback;
	}

	function simpleClean( $param ) {
		if ( !isset( $this->simpleCleanPairs ) ) {
			global $IP;
			$this->simpleCleanPairs = array(
				$this->directory => 'public',
				"{$this->directory}/temp" => 'temp',
				$IP => '$IP',
				dirname( __FILE__ ) => '$IP/extensions/WebStore',
			);
			if ( $this->deletedDir ) {
				$this->simpleCleanPairs[$this->deletedDir] = 'deleted';
			}
		}
		return strtr( $param, $this->simpleCleanPairs );
	}

	/**
	 * Chmod a file, supressing the warnings.
	 * @param $path String: the path to change
	 */
	protected function chmod( $path ) {
		wfSuppressWarnings();
		chmod( $path, $this->fileMode );
		wfRestoreWarnings();
	}

}
