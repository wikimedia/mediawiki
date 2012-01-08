<?php
/**
 * @file
 * @ingroup FileBackend
 * @author Aaron Schulz
 */

/**
 * Class for a file system based file backend.
 * Containers are just directories and container sharding is not supported.
 * Also, for backwards-compatibility, the wiki ID prefix is not used.
 * Users of this class should set wiki-specific container paths as needed.
 *
 * Status messages should avoid mentioning the internal FS paths.
 * Likewise, error suppression should be used to avoid path disclosure.
 *
 * @ingroup FileBackend
 */
class FSFileBackend extends FileBackend {
	/** @var Array Map of container names to paths */
	protected $containerPaths = array();
	protected $fileMode; // file permission mode

	/**
	 * @see FileBackend::__construct()
	 * Additional $config params include:
	 *    containerPaths : Map of container names to absolute file system paths
	 *    fileMode       : Octal UNIX file permissions to use on files stored
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );
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
		// Get absolute path given the container base dir
		if ( isset( $this->containerPaths[$container] ) ) {
			return $this->containerPaths[$container] . "/{$relStoragePath}";
		}
		return null;
	}

	/**
	 * @see FileBackend::doStoreInternal()
	 */
	protected function doStoreInternal( array $params ) {
		$status = Status::newGood();

		list( $c, $dest ) = $this->resolveStoragePathReal( $params['dst'] );
		if ( $dest === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );
			return $status;
		}
		if ( file_exists( $dest ) ) {
			if ( !empty( $params['overwriteDest'] ) ) {
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
		} else {
			if ( !wfMkdirParents( dirname( $dest ) ) ) {
				$status->fatal( 'directorycreateerror', $params['dst'] );
				return $status;
			}
		}

		wfSuppressWarnings();
		$ok = copy( $params['src'], $dest );
		wfRestoreWarnings();
		if ( !$ok ) {
			$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
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

		list( $c, $source ) = $this->resolveStoragePathReal( $params['src'] );
		if ( $source === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['src'] );
			return $status;
		}

		list( $c, $dest ) = $this->resolveStoragePathReal( $params['dst'] );
		if ( $dest === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );
			return $status;
		}

		if ( file_exists( $dest ) ) {
			if ( !empty( $params['overwriteDest'] ) ) {
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
		} else {
			if ( !wfMkdirParents( dirname( $dest ) ) ) {
				$status->fatal( 'directorycreateerror', $params['dst'] );
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

		list( $c, $source ) = $this->resolveStoragePathReal( $params['src'] );
		if ( $source === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['src'] );
			return $status;
		}
		list( $c, $dest ) = $this->resolveStoragePathReal( $params['dst'] );
		if ( $dest === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );
			return $status;
		}

		if ( file_exists( $dest ) ) {
			if ( !empty( $params['overwriteDest'] ) ) {
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
		} else {
			if ( !wfMkdirParents( dirname( $dest ) ) ) {
				$status->fatal( 'directorycreateerror', $params['dst'] );
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

		list( $c, $source ) = $this->resolveStoragePathReal( $params['src'] );
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

		list( $c, $dest ) = $this->resolveStoragePathReal( $params['dst'] );
		if ( $dest === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );
			return $status;
		}

		if ( file_exists( $dest ) ) {
			if ( !empty( $params['overwriteDest'] ) ) {
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
		} else {
			if ( !wfMkdirParents( dirname( $dest ) ) ) {
				$status->fatal( 'directorycreateerror', $params['dst'] );
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
	 * @see FileBackend::doPrepare()
	 */
	protected function doPrepare( $container, $dir, array $params ) {
		$status = Status::newGood();
		if ( !wfMkdirParents( $dir ) ) {
			$status->fatal( 'directorycreateerror', $params['dir'] );
		} elseif ( !is_writable( $dir ) ) {
			$status->fatal( 'directoryreadonlyerror', $params['dir'] );
		} elseif ( !is_readable( $dir ) ) {
			$status->fatal( 'directorynotreadableerror', $params['dir'] );
		}
		return $status;
	}

	/**
	 * @see FileBackend::doSecure()
	 */
	protected function doSecure( $container, $dir, array $params ) {
		$status = Status::newGood();
		if ( !wfMkdirParents( $dir ) ) {
			$status->fatal( 'directorycreateerror', $params['dir'] );
			return $status;
		}
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
		list( $b, $container, $r ) = FileBackend::splitStoragePath( $params['dir'] );
		$dirRoot = $this->containerPaths[$container]; // real path
		if ( !empty( $params['noAccess'] ) && !file_exists( "{$dirRoot}/.htaccess" ) ) {
			wfSuppressWarnings();
			$ok = file_put_contents( "{$dirRoot}/.htaccess", "Deny from all\n" );
			wfRestoreWarnings();
			if ( !$ok ) {
				$storeDir = "mwstore://{$this->name}/{$container}";
				$status->fatal( 'backend-fail-create', "$storeDir/.htaccess" );
				return $status;
			}
		}
		return $status;
	}

	/**
	 * @see FileBackend::doClean()
	 */
	protected function doClean( $container, $dir, array $params ) {
		$status = Status::newGood();
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
		list( $c, $source ) = $this->resolveStoragePathReal( $params['src'] );
		if ( $source === null ) {
			return false; // invalid storage path
		}

		wfSuppressWarnings();
		if ( is_file( $source ) ) { // regular file?
			$stat = stat( $source );
		} else {
			$stat = false;
		}
		wfRestoreWarnings();

		if ( $stat ) {
			return array(
				'mtime' => wfTimestamp( TS_MW, $stat['mtime'] ),
				'size'  => $stat['size']
			);
		} else {
			return false;
		}
	}

	/**
	 * @see FileBackend::getFileListInternal()
	 */
	public function getFileListInternal( $container, $dir, array $params ) {
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
		return new FSFileIterator( $dir );
	}

	/**
	 * @see FileBackend::getLocalReference()
	 */
	public function getLocalReference( array $params ) {
		list( $c, $source ) = $this->resolveStoragePathReal( $params['src'] );
		if ( $source === null ) {
			return null;
		}
		return new FSFile( $source );
	}

	/**
	 * @see FileBackend::getLocalCopy()
	 */
	public function getLocalCopy( array $params ) {
		list( $c, $source ) = $this->resolveStoragePathReal( $params['src'] );
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
}

/**
 * Wrapper around RecursiveDirectoryIterator that catches
 * exception or does any custom behavoir that we may want.
 *
 * @ingroup FileBackend
 */
class FSFileIterator implements Iterator {
	/** @var RecursiveIteratorIterator */
	protected $iter;
	protected $suffixStart; // integer

	/**
	 * Get an FSFileIterator from a file system directory
	 * 
	 * @param $dir string
	 */
	public function __construct( $dir ) {
		$this->suffixStart = strlen( realpath( $dir ) ) + 1; // size of "path/to/dir/"
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
		return str_replace( '\\', '/', substr( $this->iter->current(), $this->suffixStart ) );
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
