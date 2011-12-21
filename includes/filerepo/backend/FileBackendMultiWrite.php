<?php
/**
 * @file
 * @ingroup FileBackend
 * @author Aaron Schulz
 */

/**
 * This class defines a multi-write backend. Multiple backends can be
 * registered to this proxy backend and it will act as a single backend.
 * Use this when all access to those backends is through this proxy backend.
 * At least one of the backends must be declared the "master" backend.
 * 
 * The order that the backends are defined sets the priority of which
 * backend is read from or written to first. Functions like fileExists()
 * and getFileProps() will return information based on the first backend
 * that has the file. Special cases are listed below:
 *     a) getFileTimestamp() will always check only the master backend to
 *        avoid confusing and inconsistent results.
 * 
 * All write operations are performed on all backends.
 * If an operation fails on one backend it will be rolled back from the others.
 *
 * @ingroup FileBackend
 */
class FileBackendMultiWrite extends FileBackendBase {
	/** @var Array Prioritized list of FileBackend objects */
	protected $fileBackends = array(); // array of (backend index => backends)
	protected $masterIndex = -1; // index of master backend

	/**
	 * Construct a proxy backend that consists of several internal backends.
	 * $config contains:
	 *     'name'        : The name of the proxy backend
	 *     'lockManager' : Registered name of the file lock manager to use
	 *     'backends'    : Array of backend config and multi-backend settings.
	 *                     Each value is the config used in the constructor of a
	 *                     FileBackend class, but with these additional settings:
	 *                         'class'         : The name of the backend class
	 *                         'isMultiMaster' : This must be set for one backend.
	 * @param $config Array
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );
		// Construct backends here rather than via registration
		// to keep these backends hidden from outside the proxy.
		foreach ( $config['backends'] as $index => $config ) {
			if ( !isset( $config['class'] ) ) {
				throw new MWException( 'No class given for a backend config.' );
			}
			$class = $config['class'];
			$this->fileBackends[$index] = new $class( $config );
			if ( !empty( $config['isMultiMaster'] ) ) {
				if ( $this->masterIndex >= 0 ) {
					throw new MWException( 'More than one master backend defined.' );
				}
				$this->masterIndex = $index;
			}
		}
		if ( $this->masterIndex < 0 ) { // need backends and must have a master
			throw new MWException( 'No master backend defined.' );
		}
	}

	final public function doOperations( array $ops, array $opts = array() ) {
		$status = Status::newGood();

		$performOps = array(); // list of FileOp objects
		$filesLockEx = $filesLockSh = array(); // storage paths to lock
		// Build up a list of FileOps. The list will have all the ops
		// for one backend, then all the ops for the next, and so on.
		// These batches of ops are all part of a continuous array.
		// Also build up a list of files to lock...
		foreach ( $this->fileBackends as $index => $backend ) {
			$backendOps = $this->substOpPaths( $ops, $backend );
			$performOps = array_merge( $performOps, $backend->getOperations( $backendOps ) );
			if ( $index == 0 && empty( $opts['nonLocking'] ) ) {
				// Set "files to lock" from the first batch so we don't try to set all
				// locks two or three times over (depending on the number of backends).
				// A lock on one storage path is a lock on all the backends.
				foreach ( $performOps as $index => $fileOp ) {
					$filesLockSh = array_merge( $filesLockSh, $fileOp->storagePathsRead() );
					$filesLockEx = array_merge( $filesLockEx, $fileOp->storagePathsChanged() );
				}
				// Optimization: if doing an EX lock anyway, don't also set an SH one
				$filesLockSh = array_diff( $filesLockSh, $filesLockEx );
				// Lock the paths under the proxy backend's name
				$this->unsubstPaths( $filesLockSh );
				$this->unsubstPaths( $filesLockEx );
			}
		}

		// Try to lock those files for the scope of this function...
		$scopeLockS = $this->getScopedFileLocks( $filesLockSh, LockManager::LOCK_UW, $status );
		$scopeLockE = $this->getScopedFileLocks( $filesLockEx, LockManager::LOCK_EX, $status );
		if ( !$status->isOK() ) {
			return $status; // abort
		}

		// Clear any cache entries (after locks acquired)
		foreach ( $this->fileBackends as $backend ) {
			$backend->clearCache();
		}
		// Actually attempt the operation batch...
		$status->merge( FileOp::attemptBatch( $performOps, $opts ) );

		return $status;
	}

	/**
	 * Substitute the backend name in storage path parameters
	 * for a set of operations with a that of a given backend.
	 * 
	 * @param $ops Array List of file operation arrays
	 * @param $backend FileBackend
	 * @return Array
	 */
	protected function substOpPaths( array $ops, FileBackend $backend ) {
		$newOps = array(); // operations
		foreach ( $ops as $op ) {
			$newOp = $op; // operation
			foreach ( array( 'src', 'srcs', 'dst' ) as $par ) {
				if ( isset( $newOp[$par] ) ) {
					$newOp[$par] = preg_replace(
						'!^mwstore://' . preg_quote( $this->name ) . '/!',
						'mwstore://' . $backend->getName() . '/',
						$newOp[$par] // string or array
					);
				}
			}
			$newOps[] = $newOp;
		}
		return $newOps;
	}

	/**
	 * Replace the backend part of storage paths with this backend's name
	 * 
	 * @param &$paths Array
	 * @return void 
	 */
	protected function unsubstPaths( array &$paths ) {
		foreach ( $paths as &$path ) {
			$path = preg_replace( '!^mwstore://([^/]+)!', "mwstore://{$this->name}", $path );
		}
	}

	function prepare( array $params ) {
		$status = Status::newGood();
		foreach ( $this->backends as $backend ) {
			$realParams = $this->substOpPaths( $params, $backend );
			$status->merge( $backend->prepare( $realParams ) );
		}
		return $status;
	}

	function secure( array $params ) {
		$status = Status::newGood();
		foreach ( $this->backends as $backend ) {
			$realParams = $this->substOpPaths( $params, $backend );
			$status->merge( $backend->secure( $realParams ) );
		}
		return $status;
	}

	function clean( array $params ) {
		$status = Status::newGood();
		foreach ( $this->backends as $backend ) {
			$realParams = $this->substOpPaths( $params, $backend );
			$status->merge( $backend->clean( $realParams ) );
		}
		return $status;
	}

	function fileExists( array $params ) {
		# Hit all backends in case of failed operations (out of sync)
		foreach ( $this->backends as $backend ) {
			$realParams = $this->substOpPaths( $params, $backend );
			if ( $backend->fileExists( $realParams ) ) {
				return true;
			}
		}
		return false;
	}

	function getFileTimestamp( array $params ) {
		// Skip non-master for consistent timestamps
		$realParams = $this->substOpPaths( $params, $backend );
		return $this->backends[$this->masterIndex]->getFileTimestamp( $realParams );
	}

	function getFileSha1Base36( array $params ) {
		# Hit all backends in case of failed operations (out of sync)
		foreach ( $this->backends as $backend ) {
			$realParams = $this->substOpPaths( $params, $backend );
			$hash = $backend->getFileSha1Base36( $realParams );
			if ( $hash !== false ) {
				return $hash;
			}
		}
		return false;
	}

	function getFileProps( array $params ) {
		# Hit all backends in case of failed operations (out of sync)
		foreach ( $this->backends as $backend ) {
			$realParams = $this->substOpPaths( $params, $backend );
			$props = $backend->getFileProps( $realParams );
			if ( $props !== null ) {
				return $props;
			}
		}
		return null;
	}

	function streamFile( array $params ) {
		$status = Status::newGood();
		foreach ( $this->backends as $backend ) {
			$realParams = $this->substOpPaths( $params, $backend );
			$subStatus = $backend->streamFile( $realParams );
			$status->merge( $subStatus );
			if ( $subStatus->isOK() ) {
				// Pass isOK() despite fatals from other backends
				$status->setResult( true );
				return $status;
			} else { // failure
				if ( headers_sent() ) {
					return $status; // died mid-stream...so this is already fubar
				} elseif ( strval( ob_get_contents() ) !== '' ) {
					ob_clean(); // output was buffered but not sent; clear it
				}
			}
		}
		return $status;
	}

	function getLocalReference( array $params ) {
		# Hit all backends in case of failed operations (out of sync)
		foreach ( $this->backends as $backend ) {
			$realParams = $this->substOpPaths( $params, $backend );
			$fsFile = $backend->getLocalReference( $realParams );
			if ( $fsFile ) {
				return $fsFile;
			}
		}
		return null;
	}

	function getLocalCopy( array $params ) {
		# Hit all backends in case of failed operations (out of sync)
		foreach ( $this->backends as $backend ) {
			$realParams = $this->substOpPaths( $params, $backend );
			$tmpFile = $backend->getLocalCopy( $realParams );
			if ( $tmpFile ) {
				return $tmpFile;
			}
		}
		return null;
	}

	function getFileList( array $params ) {
		foreach ( $this->backends as $index => $backend ) {
			# Get results from the first backend
			$realParams = $this->substOpPaths( $params, $backend );
			return $backend->getFileList( $realParams );
		}
		return array(); // sanity
	}
}
