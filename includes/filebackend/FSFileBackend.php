<?php
/**
 * File system based backend.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup FileBackend
 * @author Aaron Schulz
 */

/**
 * @brief Class for a file system (FS) based file backend.
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
	protected $fileOwner; // string; required OS username to own files
	protected $currentUser; // string; OS username running this script

	protected $hadWarningErrors = array();

	/**
	 * @see FileBackendStore::__construct()
	 * Additional $config params include:
	 *   - basePath       : File system directory that holds containers.
	 *   - containerPaths : Map of container names to custom file system directories.
	 *                      This should only be used for backwards-compatibility.
	 *   - fileMode       : Octal UNIX file permissions to use on files stored.
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

		$this->fileMode = isset( $config['fileMode'] ) ? $config['fileMode'] : 0644;
		if ( isset( $config['fileOwner'] ) && function_exists( 'posix_getuid' ) ) {
			$this->fileOwner = $config['fileOwner'];
			$info = posix_getpwuid( posix_getuid() );
			$this->currentUser = $info['name']; // cache this, assuming it doesn't change
		}
	}

	/**
	 * @see FileBackendStore::resolveContainerPath()
	 * @param $container string
	 * @param $relStoragePath string
	 * @return null|string
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
	 * @return bool
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

		if ( $this->fileOwner !== null && $this->currentUser !== $this->fileOwner ) {
			$ok = false;
			trigger_error( __METHOD__ . ": PHP process owner is not '{$this->fileOwner}'." );
		}

		return $ok;
	}

	/**
	 * @see FileBackendStore::doStoreInternal()
	 * @return Status
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

		if ( !empty( $params['async'] ) ) { // deferred
			$cmd = implode( ' ', array( wfIsWindows() ? 'COPY' : 'cp',
				wfEscapeShellArg( $this->cleanPathSlashes( $params['src'] ) ),
				wfEscapeShellArg( $this->cleanPathSlashes( $dest ) )
			) );
			$status->value = new FSFileOpHandle( $this, $params, 'Store', $cmd, $dest );
		} else { // immediate write
			$ok = copy( $params['src'], $dest );
			// In some cases (at least over NFS), copy() returns true when it fails
			if ( !$ok || ( filesize( $params['src'] ) !== filesize( $dest ) ) ) {
				if ( $ok ) { // PHP bug
					unlink( $dest ); // remove broken file
					trigger_error( __METHOD__ . ": copy() failed but returned true." );
				}
				$status->fatal( 'backend-fail-store', $params['src'], $params['dst'] );
				return $status;
			}
			$this->chmod( $dest );
		}

		return $status;
	}

	/**
	 * @see FSFileBackend::doExecuteOpHandlesInternal()
	 */
	protected function _getResponseStore( $errors, Status $status, array $params, $cmd ) {
		if ( $errors !== '' && !( wfIsWindows() && $errors[0] === " " ) ) {
			$status->fatal( 'backend-fail-store', $params['src'], $params['dst'] );
			trigger_error( "$cmd\n$errors", E_USER_WARNING ); // command output
		}
	}

	/**
	 * @see FileBackendStore::doCopyInternal()
	 * @return Status
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

		if ( !empty( $params['async'] ) ) { // deferred
			$cmd = implode( ' ', array( wfIsWindows() ? 'COPY' : 'cp',
				wfEscapeShellArg( $this->cleanPathSlashes( $source ) ),
				wfEscapeShellArg( $this->cleanPathSlashes( $dest ) )
			) );
			$status->value = new FSFileOpHandle( $this, $params, 'Copy', $cmd, $dest );
		} else { // immediate write
			$ok = copy( $source, $dest );
			// In some cases (at least over NFS), copy() returns true when it fails
			if ( !$ok || ( filesize( $source ) !== filesize( $dest ) ) ) {
				if ( $ok ) { // PHP bug
					unlink( $dest ); // remove broken file
					trigger_error( __METHOD__ . ": copy() failed but returned true." );
				}
				$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
				return $status;
			}
			$this->chmod( $dest );
		}

		return $status;
	}

	/**
	 * @see FSFileBackend::doExecuteOpHandlesInternal()
	 */
	protected function _getResponseCopy( $errors, Status $status, array $params, $cmd ) {
		if ( $errors !== '' && !( wfIsWindows() && $errors[0] === " " ) ) {
			$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
			trigger_error( "$cmd\n$errors", E_USER_WARNING ); // command output
		}
	}

	/**
	 * @see FileBackendStore::doMoveInternal()
	 * @return Status
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

		if ( !empty( $params['async'] ) ) { // deferred
			$cmd = implode( ' ', array( wfIsWindows() ? 'MOVE' : 'mv',
				wfEscapeShellArg( $this->cleanPathSlashes( $source ) ),
				wfEscapeShellArg( $this->cleanPathSlashes( $dest ) )
			) );
			$status->value = new FSFileOpHandle( $this, $params, 'Move', $cmd );
		} else { // immediate write
			$ok = rename( $source, $dest );
			clearstatcache(); // file no longer at source
			if ( !$ok ) {
				$status->fatal( 'backend-fail-move', $params['src'], $params['dst'] );
				return $status;
			}
		}

		return $status;
	}

	/**
	 * @see FSFileBackend::doExecuteOpHandlesInternal()
	 */
	protected function _getResponseMove( $errors, Status $status, array $params, $cmd ) {
		if ( $errors !== '' && !( wfIsWindows() && $errors[0] === " " ) ) {
			$status->fatal( 'backend-fail-move', $params['src'], $params['dst'] );
			trigger_error( "$cmd\n$errors", E_USER_WARNING ); // command output
		}
	}

	/**
	 * @see FileBackendStore::doDeleteInternal()
	 * @return Status
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

		if ( !empty( $params['async'] ) ) { // deferred
			$cmd = implode( ' ', array( wfIsWindows() ? 'DEL' : 'unlink',
				wfEscapeShellArg( $this->cleanPathSlashes( $source ) )
			) );
			$status->value = new FSFileOpHandle( $this, $params, 'Copy', $cmd );
		} else { // immediate write
			$ok = unlink( $source );
			if ( !$ok ) {
				$status->fatal( 'backend-fail-delete', $params['src'] );
				return $status;
			}
		}

		return $status;
	}

	/**
	 * @see FSFileBackend::doExecuteOpHandlesInternal()
	 */
	protected function _getResponseDelete( $errors, Status $status, array $params, $cmd ) {
		if ( $errors !== '' && !( wfIsWindows() && $errors[0] === " " ) ) {
			$status->fatal( 'backend-fail-delete', $params['src'] );
			trigger_error( "$cmd\n$errors", E_USER_WARNING ); // command output
		}
	}

	/**
	 * @see FileBackendStore::doCreateInternal()
	 * @return Status
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

		if ( !empty( $params['async'] ) ) { // deferred
			$tempFile = TempFSFile::factory( 'create_', 'tmp' );
			if ( !$tempFile ) {
				$status->fatal( 'backend-fail-create', $params['dst'] );
				return $status;
			}
			$bytes = file_put_contents( $tempFile->getPath(), $params['content'] );
			if ( $bytes === false ) {
				$status->fatal( 'backend-fail-create', $params['dst'] );
				return $status;
			}
			$cmd = implode( ' ', array( wfIsWindows() ? 'COPY' : 'cp',
				wfEscapeShellArg( $this->cleanPathSlashes( $tempFile->getPath() ) ),
				wfEscapeShellArg( $this->cleanPathSlashes( $dest ) )
			) );
			$status->value = new FSFileOpHandle( $this, $params, 'Create', $cmd, $dest );
			$tempFile->bind( $status->value );
		} else { // immediate write
			$bytes = file_put_contents( $dest, $params['content'] );
			if ( $bytes === false ) {
				$status->fatal( 'backend-fail-create', $params['dst'] );
				return $status;
			}
			$this->chmod( $dest );
		}

		return $status;
	}

	/**
	 * @see FSFileBackend::doExecuteOpHandlesInternal()
	 */
	protected function _getResponseCreate( $errors, Status $status, array $params, $cmd ) {
		if ( $errors !== '' && !( wfIsWindows() && $errors[0] === " " ) ) {
			$status->fatal( 'backend-fail-create', $params['dst'] );
			trigger_error( "$cmd\n$errors", E_USER_WARNING ); // command output
		}
	}

	/**
	 * @see FileBackendStore::doPrepareInternal()
	 * @return Status
	 */
	protected function doPrepareInternal( $fullCont, $dirRel, array $params ) {
		$status = Status::newGood();
		list( $b, $shortCont, $r ) = FileBackend::splitStoragePath( $params['dir'] );
		$contRoot = $this->containerFSRoot( $shortCont, $fullCont ); // must be valid
		$dir = ( $dirRel != '' ) ? "{$contRoot}/{$dirRel}" : $contRoot;
		$existed = is_dir( $dir ); // already there?
		if ( !wfMkdirParents( $dir ) ) { // make directory and its parents
			$status->fatal( 'directorycreateerror', $params['dir'] ); // fails on races
		} elseif ( !is_writable( $dir ) ) {
			$status->fatal( 'directoryreadonlyerror', $params['dir'] );
		} elseif ( !is_readable( $dir ) ) {
			$status->fatal( 'directorynotreadableerror', $params['dir'] );
		}
		if ( is_dir( $dir ) && !$existed ) {
			// Respect any 'noAccess' or 'noListing' flags...
			$status->merge( $this->doSecureInternal( $fullCont, $dirRel, $params ) );
		}
		return $status;
	}

	/**
	 * @see FileBackendStore::doSecureInternal()
	 * @return Status
	 */
	protected function doSecureInternal( $fullCont, $dirRel, array $params ) {
		$status = Status::newGood();
		list( $b, $shortCont, $r ) = FileBackend::splitStoragePath( $params['dir'] );
		$contRoot = $this->containerFSRoot( $shortCont, $fullCont ); // must be valid
		$dir = ( $dirRel != '' ) ? "{$contRoot}/{$dirRel}" : $contRoot;
		// Seed new directories with a blank index.html, to prevent crawling...
		if ( !empty( $params['noListing'] ) && !file_exists( "{$dir}/index.html" ) ) {
			$bytes = file_put_contents( "{$dir}/index.html", $this->indexHtmlPrivate() );
			if ( $bytes === false ) {
				$status->fatal( 'backend-fail-create', $params['dir'] . '/index.html' );
				return $status;
			}
		}
		// Add a .htaccess file to the root of the container...
		if ( !empty( $params['noAccess'] ) && !file_exists( "{$contRoot}/.htaccess" ) ) {
			$bytes = file_put_contents( "{$contRoot}/.htaccess", $this->htaccessPrivate() );
			if ( $bytes === false ) {
				$storeDir = "mwstore://{$this->name}/{$shortCont}";
				$status->fatal( 'backend-fail-create', "{$storeDir}/.htaccess" );
				return $status;
			}
		}
		return $status;
	}

	/**
	 * @see FileBackendStore::doPublishInternal()
	 * @return Status
	 */
	protected function doPublishInternal( $fullCont, $dirRel, array $params ) {
		$status = Status::newGood();
		list( $b, $shortCont, $r ) = FileBackend::splitStoragePath( $params['dir'] );
		$contRoot = $this->containerFSRoot( $shortCont, $fullCont ); // must be valid
		$dir = ( $dirRel != '' ) ? "{$contRoot}/{$dirRel}" : $contRoot;
		// Unseed new directories with a blank index.html, to allow crawling...
		if ( !empty( $params['listing'] ) && is_file( "{$dir}/index.html" ) ) {
			$exists = ( file_get_contents( "{$dir}/index.html" ) === $this->indexHtmlPrivate() );
			if ( $exists && !unlink( "{$dir}/index.html" ) ) { // reverse secure()
				$status->fatal( 'backend-fail-delete', $params['dir'] . '/index.html' );
				return $status;
			}
		}
		// Remove the .htaccess file from the root of the container...
		if ( !empty( $params['access'] ) && is_file( "{$contRoot}/.htaccess" ) ) {
			$exists = ( file_get_contents( "{$contRoot}/.htaccess" ) === $this->htaccessPrivate() );
			if ( $exists && !unlink( "{$contRoot}/.htaccess" ) ) { // reverse secure()
				$storeDir = "mwstore://{$this->name}/{$shortCont}";
				$status->fatal( 'backend-fail-delete', "{$storeDir}/.htaccess" );
				return $status;
			}
		}
		return $status;
	}

	/**
	 * @see FileBackendStore::doCleanInternal()
	 * @return Status
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
	 * @return array|bool|null
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
	 * @see FileBackendStore::doDirectoryExists()
	 * @return bool|null
	 */
	protected function doDirectoryExists( $fullCont, $dirRel, array $params ) {
		list( $b, $shortCont, $r ) = FileBackend::splitStoragePath( $params['dir'] );
		$contRoot = $this->containerFSRoot( $shortCont, $fullCont ); // must be valid
		$dir = ( $dirRel != '' ) ? "{$contRoot}/{$dirRel}" : $contRoot;

		$this->trapWarnings(); // don't trust 'false' if there were errors
		$exists = is_dir( $dir );
		$hadError = $this->untrapWarnings();

		return $hadError ? null : $exists;
	}

	/**
	 * @see FileBackendStore::getDirectoryListInternal()
	 * @return Array|null
	 */
	public function getDirectoryListInternal( $fullCont, $dirRel, array $params ) {
		list( $b, $shortCont, $r ) = FileBackend::splitStoragePath( $params['dir'] );
		$contRoot = $this->containerFSRoot( $shortCont, $fullCont ); // must be valid
		$dir = ( $dirRel != '' ) ? "{$contRoot}/{$dirRel}" : $contRoot;
		$exists = is_dir( $dir );
		if ( !$exists ) {
			wfDebug( __METHOD__ . "() given directory does not exist: '$dir'\n" );
			return array(); // nothing under this dir
		} elseif ( !is_readable( $dir ) ) {
			wfDebug( __METHOD__ . "() given directory is unreadable: '$dir'\n" );
			return null; // bad permissions?
		}
		return new FSFileBackendDirList( $dir, $params );
	}

	/**
	 * @see FileBackendStore::getFileListInternal()
	 * @return array|FSFileBackendFileList|null
	 */
	public function getFileListInternal( $fullCont, $dirRel, array $params ) {
		list( $b, $shortCont, $r ) = FileBackend::splitStoragePath( $params['dir'] );
		$contRoot = $this->containerFSRoot( $shortCont, $fullCont ); // must be valid
		$dir = ( $dirRel != '' ) ? "{$contRoot}/{$dirRel}" : $contRoot;
		$exists = is_dir( $dir );
		if ( !$exists ) {
			wfDebug( __METHOD__ . "() given directory does not exist: '$dir'\n" );
			return array(); // nothing under this dir
		} elseif ( !is_readable( $dir ) ) {
			wfDebug( __METHOD__ . "() given directory is unreadable: '$dir'\n" );
			return null; // bad permissions?
		}
		return new FSFileBackendFileList( $dir, $params );
	}

	/**
	 * @see FileBackendStore::getLocalReference()
	 * @return FSFile|null
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
	 * @return null|TempFSFile
	 */
	public function getLocalCopy( array $params ) {
		$source = $this->resolveToFSPath( $params['src'] );
		if ( $source === null ) {
			return null;
		}

		// Create a new temporary file with the same extension...
		$ext = FileBackend::extensionFromPath( $params['src'] );
		$tmpFile = TempFSFile::factory( 'localcopy_', $ext );
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
	 * @see FileBackendStore::directoriesAreVirtual()
	 * @return bool
	 */
	protected function directoriesAreVirtual() {
		return false;
	}

	/**
	 * @see FileBackendStore::doExecuteOpHandlesInternal()
	 * @return Array List of corresponding Status objects
	 */
	protected function doExecuteOpHandlesInternal( array $fileOpHandles ) {
		$statuses = array();

		$pipes = array();
		foreach ( $fileOpHandles as $index => $fileOpHandle ) {
			$pipes[$index] = popen( "{$fileOpHandle->cmd} 2>&1", 'r' );
		}

		$errs = array();
		foreach ( $pipes as $index => $pipe ) {
			// Result will be empty on success in *NIX. On Windows,
			// it may be something like "        1 file(s) [copied|moved].".
			$errs[$index] = stream_get_contents( $pipe );
			fclose( $pipe );
		}

		foreach ( $fileOpHandles as $index => $fileOpHandle ) {
			$status = Status::newGood();
			$function = '_getResponse' . $fileOpHandle->call;
			$this->$function( $errs[$index], $status, $fileOpHandle->params, $fileOpHandle->cmd );
			$statuses[$index] = $status;
			if ( $status->isOK() && $fileOpHandle->chmodPath ) {
				$this->chmod( $fileOpHandle->chmodPath );
			}
		}

		clearstatcache(); // files changed
		return $statuses;
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
	 * Return the text of an index.html file to hide directory listings
	 *
	 * @return string
	 */
	protected function indexHtmlPrivate() {
		return '';
	}

	/**
	 * Return the text of a .htaccess file to make a directory private
	 *
	 * @return string
	 */
	protected function htaccessPrivate() {
		return "Deny from all\n";
	}

	/**
	 * Clean up directory separators for the given OS
	 *
	 * @param $path string FS path
	 * @return string
	 */
	protected function cleanPathSlashes( $path ) {
		return wfIsWindows() ? strtr( $path, '/', '\\' ) : $path;
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

	/**
	 * @return bool
	 */
	private function handleWarning() {
		$this->hadWarningErrors[count( $this->hadWarningErrors ) - 1] = true;
		return true; // suppress from PHP handler
	}
}

/**
 * @see FileBackendStoreOpHandle
 */
class FSFileOpHandle extends FileBackendStoreOpHandle {
	public $cmd; // string; shell command
	public $chmodPath; // string; file to chmod

	/**
	 * @param $backend
	 * @param $params array
	 * @param $call
	 * @param $cmd
	 * @param $chmodPath null
	 */
	public function __construct( $backend, array $params, $call, $cmd, $chmodPath = null ) {
		$this->backend = $backend;
		$this->params = $params;
		$this->call = $call;
		$this->cmd = $cmd;
		$this->chmodPath = $chmodPath;
	}
}

/**
 * Wrapper around RecursiveDirectoryIterator/DirectoryIterator that
 * catches exception or does any custom behavoir that we may want.
 * Do not use this class from places outside FSFileBackend.
 *
 * @ingroup FileBackend
 */
abstract class FSFileBackendList implements Iterator {
	/** @var Iterator */
	protected $iter;
	protected $suffixStart; // integer
	protected $pos = 0; // integer
	/** @var Array */
	protected $params = array();

	/**
	 * @param $dir string file system directory
	 * @param $params array
	 */
	public function __construct( $dir, array $params ) {
		$dir = realpath( $dir ); // normalize
		$this->suffixStart = strlen( $dir ) + 1; // size of "path/to/dir/"
		$this->params = $params;

		try {
			$this->iter = $this->initIterator( $dir );
		} catch ( UnexpectedValueException $e ) {
			$this->iter = null; // bad permissions? deleted?
		}
	}

	/**
	 * Return an appropriate iterator object to wrap
	 *
	 * @param $dir string file system directory
	 * @return Iterator
	 */
	protected function initIterator( $dir ) {
		if ( !empty( $this->params['topOnly'] ) ) { // non-recursive
			# Get an iterator that will get direct sub-nodes
			return new DirectoryIterator( $dir );
		} else { // recursive
			# Get an iterator that will return leaf nodes (non-directories)
			# RecursiveDirectoryIterator extends FilesystemIterator.
			# FilesystemIterator::SKIP_DOTS default is inconsistent in PHP 5.3.x.
			$flags = FilesystemIterator::CURRENT_AS_SELF | FilesystemIterator::SKIP_DOTS;
			return new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator( $dir, $flags ),
				RecursiveIteratorIterator::CHILD_FIRST // include dirs
			);
		}
	}

	/**
	 * @see Iterator::key()
	 * @return integer
	 */
	public function key() {
		return $this->pos;
	}

	/**
	 * @see Iterator::current()
	 * @return string|bool String or false
	 */
	public function current() {
		return $this->getRelPath( $this->iter->current()->getPathname() );
	}

	/**
	 * @see Iterator::next()
	 * @return void
	 */
	public function next() {
		try {
			$this->iter->next();
			$this->filterViaNext();
		} catch ( UnexpectedValueException $e ) {
			$this->iter = null;
		}
		++$this->pos;
	}

	/**
	 * @see Iterator::rewind()
	 * @return void
	 */
	public function rewind() {
		$this->pos = 0;
		try {
			$this->iter->rewind();
			$this->filterViaNext();
		} catch ( UnexpectedValueException $e ) {
			$this->iter = null;
		}
	}

	/**
	 * @see Iterator::valid()
	 * @return bool
	 */
	public function valid() {
		return $this->iter && $this->iter->valid();
	}

	/**
	 * Filter out items by advancing to the next ones
	 */
	protected function filterViaNext() {}

	/**
	 * Return only the relative path and normalize slashes to FileBackend-style.
	 * Uses the "real path" since the suffix is based upon that.
	 *
	 * @param $path string
	 * @return string
	 */
	protected function getRelPath( $path ) {
		return strtr( substr( realpath( $path ), $this->suffixStart ), '\\', '/' );
	}
}

class FSFileBackendDirList extends FSFileBackendList {
	protected function filterViaNext() {
		while ( $this->iter->valid() ) {
			if ( $this->iter->current()->isDot() || !$this->iter->current()->isDir() ) {
				$this->iter->next(); // skip non-directories and dot files
			} else {
				break;
			}
		}
	}
}

class FSFileBackendFileList extends FSFileBackendList {
	protected function filterViaNext() {
		while ( $this->iter->valid() ) {
			if ( !$this->iter->current()->isFile() ) {
				$this->iter->next(); // skip non-files and dot files
			} else {
				break;
			}
		}
	}
}
