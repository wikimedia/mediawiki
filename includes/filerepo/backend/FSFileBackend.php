<?php
/**
 * @file
 * @ingroup FileBackend
 * @author Aaron Schulz
 */

/**
 * Class for a file system based file backend.
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
	function __construct( array $config ) {
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

		list( $c, $dest ) = $this->resolveStoragePath( $params['dst'] );
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

		list( $c, $source ) = $this->resolveStoragePath( $params['src'] );
		if ( $source === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['src'] );
			return $status;
		}

		list( $c, $dest ) = $this->resolveStoragePath( $params['dst'] );
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

		list( $c, $source ) = $this->resolveStoragePath( $params['src'] );
		if ( $source === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['src'] );
			return $status;
		}
		list( $c, $dest ) = $this->resolveStoragePath( $params['dst'] );
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

		list( $c, $source ) = $this->resolveStoragePath( $params['src'] );
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

		list( $c, $dest ) = $this->resolveStoragePath( $params['dst'] );
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
	 * @see FileBackend::prepare()
	 */
	function prepare( array $params ) {
		$status = Status::newGood();
		list( $c, $dir ) = $this->resolveStoragePath( $params['dir'] );
		if ( $dir === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dir'] );
			return $status; // invalid storage path
		}
		if ( !wfMkdirParents( $dir ) ) {
			$status->fatal( 'directorycreateerror', $params['dir'] );
			return $status;
		} elseif ( !is_writable( $dir ) ) {
			$status->fatal( 'directoryreadonlyerror', $params['dir'] );
			return $status;
		} elseif ( !is_readable( $dir ) ) {
			$status->fatal( 'directorynotreadableerror', $params['dir'] );
			return $status;
		}
		return $status;
	}

	/**
	 * @see FileBackend::secure()
	 */
	function secure( array $params ) {
		$status = Status::newGood();
		list( $c, $dir ) = $this->resolveStoragePath( $params['dir'] );
		if ( $dir === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dir'] );
			return $status; // invalid storage path
		}
		if ( !wfMkdirParents( $dir ) ) {
			$status->fatal( 'directorycreateerror', $params['dir'] );
			return $status;
		}
		// Add a .htaccess file to the root of the deleted zone
		if ( !empty( $params['noAccess'] ) && !file_exists( "{$dir}/.htaccess" ) ) {
			wfSuppressWarnings();
			$ok = file_put_contents( "{$dir}/.htaccess", "Deny from all\n" );
			wfRestoreWarnings();
			if ( !$ok ) {
				$status->fatal( 'backend-fail-create', $params['dir'] . '/.htaccess' );
				return $status;
			}
		}
		// Seed new directories with a blank index.html, to prevent crawling
		if ( !empty( $params['noListing'] ) && !file_exists( "{$dir}/index.html" ) ) {
			wfSuppressWarnings();
			$ok = file_put_contents( "{$dir}/index.html", '' );
			wfRestoreWarnings();
			if ( !$ok ) {
				$status->fatal( 'backend-fail-create', $params['dir'] . '/index.html' );
				return $status;
			}
		}
		return $status;
	}

	/**
	 * @see FileBackend::clean()
	 */
	function clean( array $params ) {
		$status = Status::newGood();
		list( $c, $dir ) = $this->resolveStoragePath( $params['dir'] );
		if ( $dir === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dir'] );
			return $status; // invalid storage path
		}
		wfSuppressWarnings();
		if ( is_dir( $dir ) ) {
			rmdir( $dir ); // remove directory if empty
		}
		wfRestoreWarnings();
		return $status;
	}

	/**
	 * @see FileBackend::fileExists()
	 */
	function fileExists( array $params ) {
		list( $c, $source ) = $this->resolveStoragePath( $params['src'] );
		if ( $source === null ) {
			return false; // invalid storage path
		}
		wfSuppressWarnings();
		$exists = is_file( $source );
		wfRestoreWarnings();
		return $exists;
	}

	/**
	 * @see FileBackend::getFileTimestamp()
	 */
	function getFileTimestamp( array $params ) {
		list( $c, $source ) = $this->resolveStoragePath( $params['src'] );
		if ( $source === null ) {
			return false; // invalid storage path
		}
		$fsFile = new FSFile( $source );
		return $fsFile->getTimestamp();
	}

	/**
	 * @see FileBackend::getFileList()
	 */
	function getFileList( array $params ) {
		list( $c, $dir ) = $this->resolveStoragePath( $params['dir'] );
		if ( $dir === null ) { // invalid storage path
			return null;
		}
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
	function getLocalReference( array $params ) {
		list( $c, $source ) = $this->resolveStoragePath( $params['src'] );
		if ( $source === null ) {
			return null;
		}
		return new FSFile( $source );
	}

	/**
	 * @see FileBackend::getLocalCopy()
	 */
	function getLocalCopy( array $params ) {
		list( $c, $source ) = $this->resolveStoragePath( $params['src'] );
		if ( $source === null ) {
			return null;
		}

		// Get source file extension
		$i = strrpos( $source, '.' );
		$ext = strtolower( $i ? substr( $source, $i + 1 ) : '' );
		// Create a new temporary file...
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

	/**
	 * Get an FSFileIterator from a file system directory
	 * 
	 * @param $dir string
	 */
	public function __construct( $dir ) {
		try {
			$this->iter = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $dir ) );
		} catch ( UnexpectedValueException $e ) {
			$this->iter = null; // bad permissions? deleted?
		}
	}

	public function current() {
		return $this->iter->current();
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
