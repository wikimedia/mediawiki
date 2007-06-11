<?php

/**
 * A repository for files accessible via the local filesystem. Does not support
 * database access or registration.
 *
 * TODO: split off abstract base FileRepo
 */

class FSRepo {
	const DELETE_SOURCE = 1;

	var $directory, $url, $hashLevels, $thumbScriptUrl, $transformVia404;
	var $descBaseUrl, $scriptDirUrl, $articleUrl, $fetchDescription, $initialCapital;
	var $fileFactory = array( 'UnregisteredLocalFile', 'newFromTitle' );
	var $oldFileFactory = false;

	function __construct( $info ) {
		// Required settings
		$this->name = $info['name'];
		$this->directory = $info['directory'];
		$this->url = $info['url'];
		$this->hashLevels = $info['hashLevels'];
		$this->transformVia404 = !empty( $info['transformVia404'] );

		// Optional settings
		$this->initialCapital = true; // by default
		foreach ( array( 'descBaseUrl', 'scriptDirUrl', 'articleUrl', 'fetchDescription', 
			'thumbScriptUrl', 'initialCapital' ) as $var ) 
		{
			if ( isset( $info[$var] ) ) {
				$this->$var = $info[$var];
			}
		}
	}

	/**
	 * Create a new File object from the local repository
	 * @param mixed $title Title object or string
	 * @param mixed $time Time at which the image is supposed to have existed. 
	 *                    If this is specified, the returned object will be an 
	 *                    instance of the repository's old file class instead of
	 *                    a current file. Repositories not supporting version 
	 *                    control should return false if this parameter is set.
	 */
	function newFile( $title, $time = false ) {
		if ( !($title instanceof Title) ) {
			$title = Title::makeTitleSafe( NS_IMAGE, $title );
			if ( !is_object( $title ) ) {
				return null;
			}
		}
		if ( $time ) {
			if ( $this->oldFileFactory ) {
				return call_user_func( $this->oldFileFactory, $title, $this, $time );
			} else {
				return false;
			}
		} else {
			return call_user_func( $this->fileFactory, $title, $this );
		}
	}

	/**
	 * Find an instance of the named file that existed at the specified time
	 * Returns false if the file did not exist. Repositories not supporting 
	 * version control should return false if the time is specified.
	 *
	 * @param mixed $time 14-character timestamp, or false for the current version
	 */
	function findFile( $title, $time = false ) {
		# First try the current version of the file to see if it precedes the timestamp
		$img = $this->newFile( $title );
		if ( !$img ) {
			return false;
		}
		if ( $img->exists() && ( !$time || $img->getTimestamp() <= $time ) ) {
			return $img;
		}
		# Now try an old version of the file
		$img = $this->newFile( $title, $time );
		if ( $img->exists() ) {
			return $img;
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
	 * Get the URL of thumb.php
	 */
	function getThumbScriptUrl() {
		return $this->thumbScriptUrl;
	}

	/**
	 * Returns true if the repository can transform files via a 404 handler
	 */
	function canTransformVia404() {
		return $this->transformVia404;
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
	 */
	function getVirtualUrl( $suffix = false ) {
		$path = 'mwrepo://';
		if ( $suffix !== false ) {
			$path .= '/' . $suffix;
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
		list( $host, $zone, $rel ) = $bits;
		if ( $host !== '' ) {
			throw new MWException( __METHOD__.": fetching from a foreign repo is not supported" );
		}
		$base = $this->getZonePath( $zone );
		if ( !$base ) {
			throw new MWException( __METHOD__.": invalid zone: $zone" );
		}
		return $base . '/' . urldecode( $rel );
	}

	/**
	 * Store a file to a given destination.
	 */
	function store( $srcPath, $dstZone, $dstRel, $flags = 0 ) {
		$root = $this->getZonePath( $dstZone );
		if ( !$root ) {
			throw new MWException( "Invalid zone: $dstZone" );
		}
		$dstPath = "$root/$dstRel";

		if ( !is_dir( dirname( $dstPath ) ) ) {
			wfMkdirParents( dirname( $dstPath ) );
		}
			
		if ( substr( $srcPath, 0, 9 ) == 'mwrepo://' ) {
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
		$temp = 'mwrepo:///temp';
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
	 * @param string $dstPath The destination relative path
	 * @param string $archivePath The relative path where the existing file is to
	 *        be archived, if there is one.
	 * @param integer $flags Bitfield, may be FSRepo::DELETE_SOURCE to indicate
	 *        that the source file should be deleted if possible
	 */
	function publish( $srcPath, $dstPath, $archivePath, $flags = 0 ) {
		if ( substr( $srcPath, 0, 9 ) == 'mwrepo://' ) {
			$srcPath = $this->resolveVirtualUrl( $srcPath );
		}
		$dstDir = dirname( $dstPath );
		if ( !is_dir( $dstDir ) ) wfMkdirParents( $dstDir );

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
		if ( $this->isHashed() ) {
			$hash = md5( $name );
			$path = '';
			for ( $i = 1; $i <= $this->hashLevels; $i++ ) {
				$path .= substr( $hash, 0, $i ) . '/';
			}
			return $path;
		} else {
			return '';
		}
	}

	/**
	 * Get the name of this repository, as specified by $info['name]' to the constructor
	 */
	function getName() {
		return $this->name;
	}

	/**
	 * Get the file description page base URL, or false if there isn't one.
	 * @private
	 */
	function getDescBaseUrl() {
		if ( is_null( $this->descBaseUrl ) ) {
			if ( !is_null( $this->articleUrl ) ) {
				$this->descBaseUrl = str_replace( '$1', 
					urlencode( Namespace::getCanonicalName( NS_IMAGE ) ) . ':', $this->articleUrl );
			} elseif ( !is_null( $this->scriptDirUrl ) ) {
				$this->descBaseUrl = $this->scriptDirUrl . '/index.php?title=' . 
					urlencode( Namespace::getCanonicalName( NS_IMAGE ) ) . ':';
			} else {
				$this->descBaseUrl = false;
			}
		}
		return $this->descBaseUrl;
	}

	/**
	 * Get the URL of an image description page. May return false if it is
	 * unknown or not applicable. In general this should only be called by the 
	 * File class, since it may return invalid results for certain kinds of 
	 * repositories. Use File::getDescriptionUrl() in user code.
	 *
	 * In particular, it uses the article paths as specified to the repository
	 * constructor, whereas local repositories use the local Title functions.
	 */
	function getDescriptionUrl( $name ) {
		$base = $this->getDescBaseUrl();
		if ( $base ) {
			return $base . wfUrlencode( $name );
		} else {
			return false;
		}
	}

	/**
	 * Get the URL of the content-only fragment of the description page. For 
	 * MediaWiki this means action=render. This should only be called by the 
	 * repository's file class, since it may return invalid results. User code 
	 * should use File::getDescriptionText().
	 */
	function getDescriptionRenderUrl( $name ) {
		if ( isset( $this->scriptDirUrl ) ) {
			return $this->scriptDirUrl . '/index.php?title=' . 
				wfUrlencode( Namespace::getCanonicalName( NS_IMAGE ) . ':' . $name ) .
				'&action=render';
		} else {
			$descBase = $this->getDescBaseUrl();
			if ( $descBase ) {
				return wfAppendQuery( $descBase . wfUrlencode( $name ), 'action=render' );
			} else {
				return false;
			}
		}
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
	 * Call a callaback function for every file in the repository
	 * May use either the database or the filesystem
	 */
	function enumFiles( $callback ) {
		$this->enumFilesInFS( $callback );
	}

	/**
	 * Get the name of an image from its title object
	 */
	function getNameFromTitle( $title ) {
		global $wgCapitalLinks;
		if ( $this->initialCapital != $wgCapitalLinks ) {
			global $wgContLang;
			$name = $title->getUserCaseDBKey();
			if ( $this->initialCapital ) {
				$name = $wgContLang->ucfirst( $name );
			}
		} else {
			$name = $title->getDBkey();
		}
		return $name;
	}
}

?>
