<?php

/**
 * Base class for file repositories
 * Do not instantiate, use a derived class.
 */
abstract class FileRepo {
	const DELETE_SOURCE = 1;

	var $thumbScriptUrl, $transformVia404;
	var $descBaseUrl, $scriptDirUrl, $articleUrl, $fetchDescription, $initialCapital;

	/** 
	 * Factory functions for creating new files
	 * Override these in the base class
	 */
	var $fileFactory = false, $oldFileFactory = false;

	function __construct( $info ) {
		// Required settings
		$this->name = $info['name'];
		
		// Optional settings
		$this->initialCapital = true; // by default
		foreach ( array( 'descBaseUrl', 'scriptDirUrl', 'articleUrl', 'fetchDescription', 
			'thumbScriptUrl', 'initialCapital' ) as $var ) 
		{
			if ( isset( $info[$var] ) ) {
				$this->$var = $info[$var];
			}
		}
		$this->transformVia404 = !empty( $info['transformVia404'] );
	}

	/**
	 * Determine if a string is an mwrepo:// URL
	 */
	static function isVirtualUrl( $url ) {
		return substr( $url, 0, 9 ) == 'mwrepo://';
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

	static function getHashPathForLevel( $name, $levels ) {
		if ( $levels == 0 ) {
			return '';
		} else {
			$hash = md5( $name );
			$path = '';
			for ( $i = 1; $i <= $levels; $i++ ) {
				$path .= substr( $hash, 0, $i ) . '/';
			}
			return $path;
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
					wfUrlencode( Namespace::getCanonicalName( NS_IMAGE ) ) . ':', $this->articleUrl );
			} elseif ( !is_null( $this->scriptDirUrl ) ) {
				$this->descBaseUrl = $this->scriptDirUrl . '/index.php?title=' . 
					wfUrlencode( Namespace::getCanonicalName( NS_IMAGE ) ) . ':';
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
	 * Store a file to a given destination.
	 */
	abstract function store( $srcPath, $dstZone, $dstRel, $flags = 0 );

	/**
	 * Pick a random name in the temp zone and store a file to it.
	 * Returns the URL, or a WikiError on failure.
	 * @param string $originalName The base name of the file as specified 
	 *     by the user. The file extension will be maintained.
	 * @param string $srcPath The current location of the file.
	 */
	abstract function storeTemp( $originalName, $srcPath );

	/**
	 * Remove a temporary file or mark it for garbage collection
	 * @param string $virtualUrl The virtual URL returned by storeTemp
	 * @return boolean True on success, false on failure
	 * STUB
	 */
	function freeTemp( $virtualUrl ) {
		return true;
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
	abstract function publish( $srcPath, $dstRel, $archiveRel, $flags = 0 );

	/**
	 * Get properties of a file with a given virtual URL
	 * The virtual URL must refer to this repo
	 * Properties should ultimately be obtained via File::getPropsFromPath()
	 */
	abstract function getFileProps( $virtualUrl );

	/**
	 * Call a callback function for every file in the repository
	 * May use either the database or the filesystem
	 * STUB
	 */
	function enumFiles( $callback ) {
		throw new MWException( 'enumFiles is not supported by ' . get_class( $this ) );
	}

	/**
	 * Determine if a relative path is valid, i.e. not blank or involving directory traveral
	 */
	function validateFilename( $filename ) {
		if ( strval( $filename ) == '' ) {
			return false;
		}
		if ( wfIsWindows() ) {
			$filename = strtr( $filename, '\\', '/' );
		}
		/**
		 * Use the same traversal protection as Title::secureAndSplit()
		 */
		if ( strpos( $filename, '.' ) !== false &&
		     ( $filename === '.' || $filename === '..' ||
		       strpos( $filename, './' ) === 0  ||
		       strpos( $filename, '../' ) === 0 ||
		       strpos( $filename, '/./' ) !== false ||
		       strpos( $filename, '/../' ) !== false ) )
		{
			return false;
		} else {
			return true;
		}
	}
}
?>
