<?php
/**
 * @file
 * @ingroup FileBackend
 * @author Aaron Schulz
 */

/**
 * Class for a file system (FS) based file backend.
 * 
 * All "containers" each map to a directory under the backend's base directory.
 * For backwards-compatibility, some container paths can be set to custom paths.
 * The wiki ID will not be used in any custom paths, so this should be avoided.
 * 
 * Having directories with thousands of files will diminish performance.
 * Sharding can be accomplished by using FileRepo-style hash paths.
 *
 * Status messages should avoid mentioning the internal FS paths.
 * PHP warnings are assumed to be logged rather than output.
 *
 * @ingroup FileBackend
 * @since 1.19
 */
class FSFileBackend extends FileBackendStore {
	protected $basePath; // string; directory holding the container directories
	/** @var Array Map of container names to root paths */
	protected $containerPaths = array(); // for custom container paths
	protected $fileMode; // integer; file permission mode

	protected $hadWarningErrors = array();

	/**
	 * @see FileBackendStore::__construct()
	 * Additional $config params include:
	 *    basePath       : File system directory that holds containers.
	 *    containerPaths : Map of container names to custom file system directories.
	 *                     This should only be used for backwards-compatibility.
	 *    fileMode       : Octal UNIX file permissions to use on files stored.
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );

		// Remove any possible trailing slash from directories
		if ( isset( $config['basePath'] ) ) {
			$this->basePath = rtrim( $config['basePath'], '/' ); // remove trailing slash
		} else {
			$this->basePath = null; // none; containers must have explicit paths
		}

		if ( isset( $config['containerPaths'] ) ) {
			$this->containerPaths = (array)$config['containerPaths'];
			foreach ( $this->containerPaths as &$path ) {
				$path = rtrim( $path, '/' );  // remove trailing slash
			}
		}

		$this->fileMode = isset( $config['fileMode'] )
			? $config['fileMode']
			: 0644;
	}

	/**
	 * @see FileBackendStore::resolveContainerPath()
	 */
	protected function resolveContainerPath( $container, $relStoragePath ) {
		// Check that container has a root directory
		if ( isset( $this->containerPaths[$container] ) || isset( $this->basePath ) ) {
			// Check for sane relative paths (assume the base paths are OK)
			if ( $this->isLegalRelPath( $relStoragePath ) ) {
				return $relStoragePath;
			}
		}
		return null;
	}

	/**
	 * Sanity check a relative file system path for validity
	 * 
	 * @param $path string Normalized relative path
	 * @return bool
	 */
	protected function isLegalRelPath( $path ) {
		// Check for file names longer than 255 chars
		if ( preg_match( '![^/]{256}!', $path ) ) { // ext3/NTFS
			return false;
		}
		if ( wfIsWindows() ) { // NTFS
			return !preg_match( '![:*?"<>|]!', $path );
		} else {
			return true;
		}
	}

	/**
	 * Given the short (unresolved) and full (resolved) name of
	 * a container, return the file system path of the container.
	 * 
	 * @param $shortCont string
	 * @param $fullCont string
	 * @return string|null 
	 */
	protected function containerFSRoot( $shortCont, $fullCont ) {
		if ( isset( $this->containerPaths[$shortCont] ) ) {
			return $this->containerPaths[$shortCont]; 
		} elseif ( isset( $this->basePath ) ) {
			return "{$this->basePath}/{$fullCont}";
		}
		return null; // no container base path defined
	}

	/**
	 * Get the absolute file system path for a storage path
	 * 
	 * @param $storagePath string Storage path
	 * @return string|null
	 */
	protected function resolveToFSPath( $storagePath ) {
		list( $fullCont, $relPath ) = $this->resolveStoragePathReal( $storagePath );
		if ( $relPath === null ) {
			return null; // invalid
		}
		list( $b, $shortCont, $r ) = FileBackend::splitStoragePath( $storagePath );
		$fsPath = $this->containerFSRoot( $shortCont, $fullCont ); // must be valid
		if ( $relPath != '' ) {
			$fsPath .= "/{$relPath}";
		}
		return $fsPath;
	}

	/**
	 * @see FileBackendStore::isPathUsableInternal()
	 */
	public function isPathUsableInternal( $storagePath ) {
		$fsPath = $this->resolveToFSPath( $storagePath );
		if ( $fsPath === null ) {
			return false; // invalid
		}
		$parentDir = dirname( $fsPath );

		if ( file_exists( $fsPath ) ) {
			$ok = is_file( $fsPath ) && is_writable( $fsPath );
		} else {
			$ok = is_dir( $parentDir ) && is_writable( $parentDir );
		}

		return $ok;
	}

	/**
	 * @see FileBackendStore::doStoreInternal()
	 */
	protected function doStoreInternal( array $params ) {
		$status = Status::newGood();

		$dest = $this->resolveToFSPath( $params['dst'] );
		if ( $dest === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );
			return $status;
		}

		if ( file_exists( $dest ) ) {
			if ( !empty( $params['overwrite'] ) ) {
				$ok = unlink( $dest );
				if ( !$ok ) {
					$status->fatal( 'backend-fail-delete', $params['dst'] );
					return $status;
				}
			} else {
				$status->fatal( 'backend-fail-alreadyexists', $params['dst'] );
				return $status;
			}
		}

		$ok = copy( $params['src'], $dest );
		if ( !$ok ) {
			$status->fatal( 'backend-fail-store', $params['src'], $params['dst'] );
			return $status;
		}

		$this->chmod( $dest );

		return $status;
	}

	/**
	 * @see FileBackendStore::doCopyInternal()
	 */
	protected function doCopyInternal( array $params ) {
		$status = Status::newGood();

		$source = $this->resolveToFSPath( $params['src'] );
		if ( $source === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['src'] );
			return $status;
		}

		$dest = $this->resolveToFSPath( $params['dst'] );
		if ( $dest === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );
			return $status;
		}

		if ( file_exists( $dest ) ) {
			if ( !empty( $params['overwrite'] ) ) {
				$ok = unlink( $dest );
				if ( !$ok ) {
					$status->fatal( 'backend-fail-delete', $params['dst'] );
					return $status;
				}
			} else {
				$status->fatal( 'backend-fail-alreadyexists', $params['dst'] );
				return $status;
			}
		}

		$ok = copy( $source, $dest );
		if ( !$ok ) {
			$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
			return $status;
		}

		$this->chmod( $dest );

		return $status;
	}

	/**
	 * @see FileBackendStore::doMoveInternal()
	 */
	protected function doMoveInternal( array $params ) {
		$status = Status::newGood();

		$source = $this->resolveToFSPath( $params['src'] );
		if ( $source === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['src'] );
			return $status;
		}

		$dest = $this->resolveToFSPath( $params['dst'] );
		if ( $dest === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );
			return $status;
		}

		if ( file_exists( $dest ) ) {
			if ( !empty( $params['overwrite'] ) ) {
				// Windows does not support moving over existing files
				if ( wfIsWindows() ) {
					$ok = unlink( $dest );
					if ( !$ok ) {
						$status->fatal( 'backend-fail-delete', $params['dst'] );
						return $status;
					}
				}
			} else {
				$status->fatal( 'backend-fail-alreadyexists', $params['dst'] );
				return $status;
			}
		}

		$ok = rename( $source, $dest );
		clearstatcache(); // file no longer at source
		if ( !$ok ) {
			$status->fatal( 'backend-fail-move', $params['src'], $params['dst'] );
			return $status;
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::doDeleteInternal()
	 */
	protected function doDeleteInternal( array $params ) {
		$status = Status::newGood();

		$source = $this->resolveToFSPath( $params['src'] );
		if ( $source === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['src'] );
			return $status;
		}

		if ( !is_file( $source ) ) {
			if ( empty( $params['ignoreMissingSource'] ) ) {
				$status->fatal( 'backend-fail-delete', $params['src'] );
			}
			return $status; // do nothing; either OK or bad status
		}

		$ok = unlink( $source );
		if ( !$ok ) {
			$status->fatal( 'backend-fail-delete', $params['src'] );
			return $status;
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::doCreateInternal()
	 */
	protected function doCreateInternal( array $params ) {
		$status = Status::newGood();

		$dest = $this->resolveToFSPath( $params['dst'] );
		if ( $dest === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );
			return $status;
		}

		if ( file_exists( $dest ) ) {
			if ( !empty( $params['overwrite'] ) ) {
				$ok = unlink( $dest );
				if ( !$ok ) {
					$status->fatal( 'backend-fail-delete', $params['dst'] );
					return $status;
				}
			} else {
				$status->fatal( 'backend-fail-alreadyexists', $params['dst'] );
				return $status;
			}
		}

		$bytes = file_put_contents( $dest, $params['content'] );
		if ( $bytes === false ) {
			$status->fatal( 'backend-fail-create', $params['dst'] );
			return $status;
		}

		$this->chmod( $dest );

		return $status;
	}

	/**
	 * @see FileBackendStore::doPrepareInternal()
	 */
	protected function doPrepareInternal( $fullCont, $dirRel, array $params ) {
		$status = Status::newGood();
		list( $b, $shortCont, $r ) = FileBackend::splitStoragePath( $params['dir'] );
		$contRoot = $this->containerFSRoot( $shortCont, $fullCont ); // must be valid
		$dir = ( $dirRel != '' ) ? "{$contRoot}/{$dirRel}" : $contRoot;
		if ( !wfMkdirParents( $dir ) ) { // make directory and its parents
			$status->fatal( 'directorycreateerror', $params['dir'] );
		} elseif ( !is_writable( $dir ) ) {
			$status->fatal( 'directoryreadonlyerror', $params['dir'] );
		} elseif ( !is_readable( $dir ) ) {
			$status->fatal( 'directorynotreadableerror', $params['dir'] );
		}
		return $status;
	}

	/**
	 * @see FileBackendStore::doSecureInternal()
	 */
	protected function doSecureInternal( $fullCont, $dirRel, array $params ) {
		$status = Status::newGood();
		list( $b, $shortCont, $r ) = FileBackend::splitStoragePath( $params['dir'] );
		$contRoot = $this->containerFSRoot( $shortCont, $fullCont ); // must be valid
		$dir = ( $dirRel != '' ) ? "{$contRoot}/{$dirRel}" : $contRoot;
		// Seed new directories with a blank index.html, to prevent crawling...
		if ( !empty( $params['noListing'] ) && !file_exists( "{$dir}/index.html" ) ) {
			$bytes = file_put_contents( "{$dir}/index.html", '' );
			if ( !$bytes ) {
				$status->fatal( 'backend-fail-create', $params['dir'] . '/index.html' );
				return $status;
			}
		}
		// Add a .htaccess file to the root of the container...
		if ( !empty( $params['noAccess'] ) ) {
			if ( !file_exists( "{$contRoot}/.htaccess" ) ) {
				$bytes = file_put_contents( "{$contRoot}/.htaccess", "Deny from all\n" );
				if ( !$bytes ) {
					$storeDir = "mwstore://{$this->name}/{$shortCont}";
					$status->fatal( 'backend-fail-create', "{$storeDir}/.htaccess" );
					return $status;
				}
			}
		}
		return $status;
	}

	/**
	 * @see FileBackendStore::doCleanInternal()
	 */
	protected function doCleanInternal( $fullCont, $dirRel, array $params ) {
		$status = Status::newGood();
		list( $b, $shortCont, $r ) = FileBackend::splitStoragePath( $params['dir'] );
		$contRoot = $this->containerFSRoot( $shortCont, $fullCont ); // must be valid
		$dir = ( $dirRel != '' ) ? "{$contRoot}/{$dirRel}" : $contRoot;
		wfSuppressWarnings();
		if ( is_dir( $dir ) ) {
			rmdir( $dir ); // remove directory if empty
		}
		wfRestoreWarnings();
		return $status;
	}

	/**
	 * @see FileBackendStore::doFileExists()
	 */
	protected function doGetFileStat( array $params ) {
		$source = $this->resolveToFSPath( $params['src'] );
		if ( $source === null ) {
			return false; // invalid storage path
		}

		$this->trapWarnings(); // don't trust 'false' if there were errors
		$stat = is_file( $source ) ? stat( $source ) : false; // regular files only
		$hadError = $this->untrapWarnings();

		if ( $stat ) {
			return array(
				'mtime' => wfTimestamp( TS_MW, $stat['mtime'] ),
				'size'  => $stat['size']
			);
		} elseif ( !$hadError ) {
			return false; // file does not exist
		} else {
			return null; // failure
		}
	}

	/**
	 * @see FileBackendStore::doClearCache()
	 */
	protected function doClearCache( array $paths = null ) {
		clearstatcache(); // clear the PHP file stat cache
	}

	/**
	 * @see FileBackendStore::getFileListInternal()
	 */
	public function getFileListInternal( $fullCont, $dirRel, array $params ) {
		list( $b, $shortCont, $r ) = FileBackend::splitStoragePath( $params['dir'] );
		$contRoot = $this->containerFSRoot( $shortCont, $fullCont ); // must be valid
		$dir = ( $dirRel != '' ) ? "{$contRoot}/{$dirRel}" : $contRoot;
		$exists = is_dir( $dir );
		if ( !$exists ) {
			wfDebug( __METHOD__ . "() given directory does not exist: '$dir'\n" );
			return array(); // nothing under this dir
		}
		$readable = is_readable( $dir );
		if ( !$readable ) {
			wfDebug( __METHOD__ . "() given directory is unreadable: '$dir'\n" );
			return null; // bad permissions?
		}
		return new FSFileBackendFileList( $dir );
	}

	/**
	 * @see FileBackendStore::getLocalReference()
	 */
	public function getLocalReference( array $params ) {
		$source = $this->resolveToFSPath( $params['src'] );
		if ( $source === null ) {
			return null;
		}
		return new FSFile( $source );
	}

	/**
	 * @see FileBackendStore::getLocalCopy()
	 */
	public function getLocalCopy( array $params ) {
		$source = $this->resolveToFSPath( $params['src'] );
		if ( $source === null ) {
			return null;
		}

		// Create a new temporary file with the same extension...
		$ext = FileBackend::extensionFromPath( $params['src'] );
		$tmpFile = TempFSFile::factory( wfBaseName( $source ) . '_', $ext );
		if ( !$tmpFile ) {
			return null;
		}
		$tmpPath = $tmpFile->getPath();

		// Copy the source file over the temp file
		$ok = copy( $source, $tmpPath );
		if ( !$ok ) {
			return null;
		}

		$this->chmod( $tmpPath );

		return $tmpFile;
	}

	/**
	 * Chmod a file, suppressing the warnings
	 *
	 * @param $path string Absolute file system path
	 * @return bool Success
	 */
	protected function chmod( $path ) {
		wfSuppressWarnings();
		$ok = chmod( $path, $this->fileMode );
		wfRestoreWarnings();

		return $ok;
	}

	/**
	 * Listen for E_WARNING errors and track whether any happen
	 *
	 * @return bool
	 */
	protected function trapWarnings() {
		$this->hadWarningErrors[] = false; // push to stack
		set_error_handler( array( $this, 'handleWarning' ), E_WARNING );
		return false; // invoke normal PHP error handler
	}

	/**
	 * Stop listening for E_WARNING errors and return true if any happened
	 *
	 * @return bool
	 */
	protected function untrapWarnings() {
		restore_error_handler(); // restore previous handler
		return array_pop( $this->hadWarningErrors ); // pop from stack
	}

	private function handleWarning() {
		$this->hadWarningErrors[count( $this->hadWarningErrors ) - 1] = true;
		return true; // suppress from PHP handler
	}
}

/**
 * Wrapper around RecursiveDirectoryIterator that catches
 * exception or does any custom behavoir that we may want.
 * Do not use this class from places outside FSFileBackend.
 *
 * @ingroup FileBackend
 */
class FSFileBackendFileList implements Iterator {
	/** @var RecursiveIteratorIterator */
	protected $iter;
	protected $suffixStart; // integer
	protected $pos = 0; // integer

	/**
	 * @param $dir string file system directory
	 */
	public function __construct( $dir ) {
		$dir = realpath( $dir ); // normalize
		$this->suffixStart = strlen( $dir ) + 1; // size of "path/to/dir/"
		try {
			# Get an iterator that will return leaf nodes (non-directories)
			if ( MWInit::classExists( 'FilesystemIterator' ) ) { // PHP >= 5.3
				# RecursiveDirectoryIterator extends FilesystemIterator.
				# FilesystemIterator::SKIP_DOTS default is inconsistent in PHP 5.3.x.
				$flags = FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::SKIP_DOTS;
				$this->iter = new RecursiveIteratorIterator( 
					new RecursiveDirectoryIterator( $dir, $flags ) );
			} else { // PHP < 5.3
				# RecursiveDirectoryIterator extends DirectoryIterator
				$this->iter = new RecursiveIteratorIterator( 
					new RecursiveDirectoryIterator( $dir ) );
			}
		} catch ( UnexpectedValueException $e ) {
			$this->iter = null; // bad permissions? deleted?
		}
	}

	public function current() {
		// Return only the relative path and normalize slashes to FileBackend-style
		// Make sure to use the realpath since the suffix is based upon that
		return str_replace( '\\', '/',
			substr( realpath( $this->iter->current() ), $this->suffixStart ) );
	}

	public function key() {
		return $this->pos;
	}

	public function next() {
		try {
			$this->iter->next();
		} catch ( UnexpectedValueException $e ) {
			$this->iter = null;
		}
		++$this->pos;
	}

	public function rewind() {
		$this->pos = 0;
		try {
			$this->iter->rewind();
		} catch ( UnexpectedValueException $e ) {
			$this->iter = null;
		}
	}

	public function valid() {
		return $this->iter && $this->iter->valid();
	}
}
