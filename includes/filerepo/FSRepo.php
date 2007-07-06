<?php

/**
 * A repository for files accessible via the local filesystem. Does not support
 * database access or registration.
 */

class FSRepo extends FileRepo {
	var $directory, $url, $hashLevels;
	var $fileFactory = array( 'UnregisteredLocalFile', 'newFromTitle' );
	var $oldFileFactory = false;

	function __construct( $info ) {
		parent::__construct( $info );

		// Required settings
		$this->directory = $info['directory'];
		$this->url = $info['url'];
		$this->hashLevels = $info['hashLevels'];
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
				return $GLOBALS['wgFileStore']['deleted']['directory'];
			default:
				return false;
		}
	}

	/**
	 * Get the URL corresponding to one of the three basic zones
	 */
	function getZoneUrl( $zone ) {
		switch ( $zone ) {
			case 'public':
				return $this->url;
			case 'temp':
				return "{$this->url}/temp";
			case 'deleted':
				return $GLOBALS['wgFileStore']['deleted']['url'];
			default:
				return false;
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
	 * Store a file to a given destination.
	 */
	function store( $srcPath, $dstZone, $dstRel, $flags = 0 ) {
		if ( !is_writable( $this->directory ) ) {
			return new WikiErrorMsg( 'upload_directory_read_only', wfEscapeWikiText( $this->directory ) );
		}
		$root = $this->getZonePath( $dstZone );
		if ( !$root ) {
			throw new MWException( "Invalid zone: $dstZone" );
		}
		$dstPath = "$root/$dstRel";

		if ( !is_dir( dirname( $dstPath ) ) ) {
			wfMkdirParents( dirname( $dstPath ) );
		}
		
		if ( self::isVirtualUrl( $srcPath ) ) {
			$srcPath = $this->resolveVirtualUrl( $srcPath );
		}

		if ( $flags & self::DELETE_SOURCE ) {
			if ( !rename( $srcPath, $dstPath ) ) {
				return new WikiErrorMsg( 'filerenameerror', wfEscapeWikiText( $srcPath ), 
					wfEscapeWikiText( $dstPath ) );
			}
		} else {
			if ( !copy( $srcPath, $dstPath ) ) {
				return new WikiErrorMsg( 'filecopyerror', wfEscapeWikiText( $srcPath ),
					wfEscapeWikiText( $dstPath ) );
			}
		}
		chmod( $dstPath, 0644 );
		return true;
	}

	/**
	 * Pick a random name in the temp zone and store a file to it.
	 * Returns the URL, or a WikiError on failure.
	 * @param string $originalName The base name of the file as specified 
	 *     by the user. The file extension will be maintained.
	 * @param string $srcPath The current location of the file.
	 */
	function storeTemp( $originalName, $srcPath ) {
		$date = gmdate( "YmdHis" );
		$hashPath = $this->getHashPath( $originalName );
		$dstRel = "$hashPath$date!$originalName";
		$dstUrlRel = $hashPath . $date . '!' . rawurlencode( $originalName );

		$result = $this->store( $srcPath, 'temp', $dstRel );
		if ( WikiError::isError( $result ) ) {
			return $result;
		} else {
			return $this->getVirtualUrl( 'temp' ) . '/' . $dstUrlRel;
		}
	}

	/**
	 * Remove a temporary file or mark it for garbage collection
	 * @param string $virtualUrl The virtual URL returned by storeTemp
	 * @return boolean True on success, false on failure
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
	 * Copy or move a file either from the local filesystem or from an mwrepo://
	 * virtual URL, into this repository at the specified destination location.
	 *
	 * @param string $srcPath The source path or URL
	 * @param string $dstRel The destination relative path
	 * @param string $archiveRel The relative path where the existing file is to
	 *        be archived, if there is one. Relative to the public zone root.
	 * @param integer $flags Bitfield, may be FileRepo::DELETE_SOURCE to indicate
	 *        that the source file should be deleted if possible
	 */
	function publish( $srcPath, $dstRel, $archiveRel, $flags = 0 ) {
		if ( !is_writable( $this->directory ) ) {
			return new WikiErrorMsg( 'upload_directory_read_only', wfEscapeWikiText( $this->directory ) );
		}
		if ( substr( $srcPath, 0, 9 ) == 'mwrepo://' ) {
			$srcPath = $this->resolveVirtualUrl( $srcPath );
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
		if ( !is_dir( $dstDir ) ) wfMkdirParents( $dstDir );

		// Check if the source is missing before we attempt to move the dest to archive
		if ( !is_file( $srcPath ) ) {
			return new WikiErrorMsg( 'filenotfound', wfEscapeWikiText( $srcPath ) );
		}

		if( is_file( $dstPath ) ) {
			$archiveDir = dirname( $archivePath );
			if ( !is_dir( $archiveDir ) ) wfMkdirParents( $archiveDir );
			wfSuppressWarnings();
			$success = rename( $dstPath, $archivePath );
			wfRestoreWarnings();

			if( ! $success ) {
				return new WikiErrorMsg( 'filerenameerror', wfEscapeWikiText( $dstPath ),
				  wfEscapeWikiText( $archivePath ) );
			}
			else wfDebug(__METHOD__.": moved file $dstPath to $archivePath\n");
			$status = 'archived';
		}
		else {
			$status = 'new';
		}

		$error = false;
		wfSuppressWarnings();
		if ( $flags & self::DELETE_SOURCE ) {
			if ( !rename( $srcPath, $dstPath ) ) {
				$error = new WikiErrorMsg( 'filerenameerror', wfEscapeWikiText( $srcPath ), 
				wfEscapeWikiText( $dstPath ) );
			}
		} else {
			if ( !copy( $srcPath, $dstPath ) ) {
				$error = new WikiErrorMsg( 'filerenameerror', wfEscapeWikiText( $srcPath ), 
					wfEscapeWikiText( $dstPath ) );
			}
		}
		wfRestoreWarnings();

		if( $error ) {
			return $error;
		} else {
			wfDebug(__METHOD__.": wrote tempfile $srcPath to $dstPath\n");
		}

		chmod( $dstPath, 0644 );
		return $status;
	}
	
	/**
	 * Get a relative path including trailing slash, e.g. f/fa/
	 * If the repo is not hashed, returns an empty string
	 */
	function getHashPath( $name ) {
		return FileRepo::getHashPathForLevel( $name, $this->hashLevels );
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
}


