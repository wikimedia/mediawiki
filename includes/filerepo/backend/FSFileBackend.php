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
 * Likewise, error suppression should be used to avoid path disclosure.
 *
 * @ingroup FileBackend
 * @since 1.19
 */
class FSFileBackend extends FileBackend {
	protected $basePath; // string; directory holding the container directories
	/** @var Array Map of container names to root paths */
	protected $containerPaths = array(); // for custom container paths
	protected $fileMode; // integer; file permission mode

	/**
	 * @see FileBackend::__construct()
	 * Additional $config params include:
	 *    basePath       : File system directory that holds containers.
	 *    containerPaths : Map of container names to custom file system directories.
	 *                     This should only be used for backwards-compatibility.
	 *    fileMode       : Octal UNIX file permissions to use on files stored.
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );
		if ( isset( $config['basePath'] ) ) {
			if ( substr( $this->basePath, -1 ) === '/' ) {
				$this->basePath = substr( $this->basePath, 0, -1 ); // remove trailing slash
			}
		} else {
			$this->basePath = null; // none; containers must have explicit paths
		}
		$this->containerPaths = (array)$config['containerPaths'];
		foreach ( $this->containerPaths as &$path ) {
			if ( substr( $path, -1 ) === '/' ) {
				$path = substr( $path, 0, -1 ); // remove trailing slash
			}
		}
		$this->fileMode = isset( $config['fileMode'] )
			? $config['fileMode']
			: 0644;
	}

	/**
	 * @see FileBackend::resolveContainerPath()
	 */
	protected function resolveContainerPath( $container, $relStoragePath ) {
		if ( isset( $this->containerPaths[$container] ) || isset( $this->basePath ) ) {
			return $relStoragePath; // container has a root directory
		}
		return null;
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
	 * @see FileBackend::isPathUsableInternal()
	 */
	public function isPathUsableInternal( $storagePath ) {
		$fsPath = $this->resolveToFSPath( $storagePath );
		if ( $fsPath === null ) {
			return false; // invalid
		}
		$parentDir = dirname( $fsPath );

		wfSuppressWarnings();
		if ( file_exists( $fsPath ) ) {
			$ok = is_file( $fsPath ) && is_writable( $fsPath );
		} else {
			$ok = is_dir( $parentDir ) && is_writable( $parentDir );
		}
		wfRestoreWarnings();

		return $ok;
	}

	/**
	 * @see FileBackend::doStoreInternal()
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
				wfSuppressWarnings();
				$ok = unlink( $dest );
				wfRestoreWarnings();
				if ( !$ok ) {
					$status->fatal( 'backend-fail-delete', $params['dst'] );
					return $status;
				}
			} else {
				$status->fatal( 'backend-fail-alreadyexists', $params['dst'] );
				return $status;
			}
		}

		wfSuppressWarnings();
		$ok = copy( $params['src'], $dest );
		wfRestoreWarnings();
		if ( !$ok ) {
			$status->fatal( 'backend-fail-store', $params['src'], $params['dst'] );
			return $status;
		}

		$this->chmod( $dest );

		return $status;
	}

	/**
	 * @see FileBackend::doCopyInternal()
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
				wfSuppressWarnings();
				$ok = unlink( $dest );
				wfRestoreWarnings();
				if ( !$ok ) {
					$status->fatal( 'backend-fail-delete', $params['dst'] );
					return $status;
				}
			} else {
				$status->fatal( 'backend-fail-alreadyexists', $params['dst'] );
				return $status;
			}
		}

		wfSuppressWarnings();
		$ok = copy( $source, $dest );
		wfRestoreWarnings();
		if ( !$ok ) {
			$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
			return $status;
		}

		$this->chmod( $dest );

		return $status;
	}

	/**
	 * @see FileBackend::doMoveInternal()
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
					wfSuppressWarnings();
					$ok = unlink( $dest );
					wfRestoreWarnings();
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

		wfSuppressWarnings();
		$ok = rename( $source, $dest );
		clearstatcache(); // file no longer at source
		wfRestoreWarnings();
		if ( !$ok ) {
			$status->fatal( 'backend-fail-move', $params['src'], $params['dst'] );
			return $status;
		}

		return $status;
	}

	/**
	 * @see FileBackend::doDeleteInternal()
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

		wfSuppressWarnings();
		$ok = unlink( $source );
		wfRestoreWarnings();
		if ( !$ok ) {
			$status->fatal( 'backend-fail-delete', $params['src'] );
			return $status;
		}

		return $status;
	}

	/**
	 * @see FileBackend::doCreateInternal()
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
				wfSuppressWarnings();
				$ok = unlink( $dest );
				wfRestoreWarnings();
				if ( !$ok ) {
					$status->fatal( 'backend-fail-delete', $params['dst'] );
					return $status;
				}
			} else {
				$status->fatal( 'backend-fail-alreadyexists', $params['dst'] );
				return $status;
			}
		}

		wfSuppressWarnings();
		$ok = file_put_contents( $dest, $params['content'] );
		wfRestoreWarnings();
		if ( !$ok ) {
			$status->fatal( 'backend-fail-create', $params['dst'] );
			return $status;
		}

		$this->chmod( $dest );

		return $status;
	}

	/**
	 * @see FileBackend::doPrepareInternal()
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
	 * @see FileBackend::doSecureInternal()
	 */
	protected function doSecureInternal( $fullCont, $dirRel, array $params ) {
		$status = Status::newGood();
		list( $b, $shortCont, $r ) = FileBackend::splitStoragePath( $params['dir'] );
		$contRoot = $this->containerFSRoot( $shortCont, $fullCont ); // must be valid
		$dir = ( $dirRel != '' ) ? "{$contRoot}/{$dirRel}" : $contRoot;
		// Seed new directories with a blank index.html, to prevent crawling...
		if ( !empty( $params['noListing'] ) && !file_exists( "{$dir}/index.html" ) ) {
			wfSuppressWarnings();
			$ok = file_put_contents( "{$dir}/index.html", '' );
			wfRestoreWarnings();
			if ( !$ok ) {
				$status->fatal( 'backend-fail-create', $params['dir'] . '/index.html' );
				return $status;
			}
		}
		// Add a .htaccess file to the root of the container...
		if ( !empty( $params['noAccess'] ) ) {
			if ( !file_exists( "{$contRoot}/.htaccess" ) ) {
				wfSuppressWarnings();
				$ok = file_put_contents( "{$dirRoot}/.htaccess", "Deny from all\n" );
				wfRestoreWarnings();
				if ( !$ok ) {
					$storeDir = "mwstore://{$this->name}/{$shortCont}";
					$status->fatal( 'backend-fail-create', "{$storeDir}/.htaccess" );
					return $status;
				}
			}
		}
		return $status;
	}

	/**
	 * @see FileBackend::doCleanInternal()
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
	 * @see FileBackend::doFileExists()
	 */
	protected function doGetFileStat( array $params ) {
		$source = $this->resolveToFSPath( $params['src'] );
		if ( $source === null ) {
			return false; // invalid storage path
		}

		$this->trapWarnings();
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
	 * @see FileBackend::getFileListInternal()
	 */
	public function getFileListInternal( $fullCont, $dirRel, array $params ) {
		list( $b, $shortCont, $r ) = FileBackend::splitStoragePath( $params['dir'] );
		$contRoot = $this->containerFSRoot( $shortCont, $fullCont ); // must be valid
		$dir = ( $dirRel != '' ) ? "{$contRoot}/{$dirRel}" : $contRoot;
		wfSuppressWarnings();
		$exists = is_dir( $dir );
		wfRestoreWarnings();
		if ( !$exists ) {
			return array(); // nothing under this dir
		}
		wfSuppressWarnings();
		$readable = is_readable( $dir );
		wfRestoreWarnings();
		if ( !$readable ) {
			return null; // bad permissions?
		}
		return new FSFileBackendFileList( $dir );
	}

	/**
	 * @see FileBackend::getLocalReference()
	 */
	public function getLocalReference( array $params ) {
		$source = $this->resolveToFSPath( $params['src'] );
		if ( $source === null ) {
			return null;
		}
		return new FSFile( $source );
	}

	/**
	 * @see FileBackend::getLocalCopy()
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
		wfSuppressWarnings();
		$ok = copy( $source, $tmpPath );
		wfRestoreWarnings();
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
	 * Suppress E_WARNING errors and track whether any happen
	 *
	 * @return void
	 */
	protected function trapWarnings() {
		$this->hadWarningErrors[] = false; // push to stack
		set_error_handler( array( $this, 'handleWarning' ), E_WARNING );
	}

	/**
	 * Unsuppress E_WARNING errors and return true if any happened
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

	/**
	 * @param $dir string file system directory
	 */
	public function __construct( $dir ) {
		$dir = realpath( $dir ); // normalize
		$this->suffixStart = strlen( $dir ) + 1; // size of "path/to/dir/"
		try {
			$flags = FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::SKIP_DOTS;
			$this->iter = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator( $dir, $flags ) );
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
		return $this->iter->key();
	}

	public function next() {
		try {
			$this->iter->next();
		} catch ( UnexpectedValueException $e ) {
			$this->iter = null;
		}
	}

	public function rewind() {
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
