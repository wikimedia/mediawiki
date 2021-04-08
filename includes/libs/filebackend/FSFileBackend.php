<?php
/**
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
 */

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
 */

use Wikimedia\AtEase\AtEase;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @brief Class for a file system (FS) based file backend.
 *
 * All "containers" each map to a directory under the backend's base directory.
 * For backwards-compatibility, some container paths can be set to custom paths.
 * The domain ID will not be used in any custom paths, so this should be avoided.
 *
 * Having directories with thousands of files will diminish performance.
 * Sharding can be accomplished by using FileRepo-style hash paths.
 *
 * StatusValue messages should avoid mentioning the internal FS paths.
 * PHP warnings are assumed to be logged rather than output.
 *
 * @ingroup FileBackend
 * @since 1.19
 */
class FSFileBackend extends FileBackendStore {
	/** @var MapCacheLRU Cache for known prepared/usable directorries */
	protected $usableDirCache;

	/** @var string Directory holding the container directories */
	protected $basePath;

	/** @var array Map of container names to root paths for custom container paths */
	protected $containerPaths;

	/** @var int Directory permission mode */
	protected $dirMode;
	/** @var int File permission mode */
	protected $fileMode;
	/** @var string Required OS username to own files */
	protected $fileOwner;

	/** @var bool Simpler version of PHP_OS_FAMILY */
	protected $os;
	/** @var string OS username running this script */
	protected $currentUser;

	/** @var bool[] Map of (stack index => whether a warning happened) */
	private $warningTrapStack = [];

	/**
	 * @see FileBackendStore::__construct()
	 * Additional $config params include:
	 *   - basePath       : File system directory that holds containers.
	 *   - containerPaths : Map of container names to custom file system directories.
	 *                      This should only be used for backwards-compatibility.
	 *   - fileMode       : Octal UNIX file permissions to use on files stored.
	 *   - directoryMode  : Octal UNIX file permissions to use on directories created.
	 * @param array $config
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );

		if ( PHP_OS_FAMILY === 'Windows' ) {
			$this->os = 'Windows';
		} elseif ( PHP_OS_FAMILY === 'BSD' || PHP_OS_FAMILY === 'Darwin' ) {
			$this->os = 'BSD';
		} else {
			$this->os = 'Linux';
		}
		// Remove any possible trailing slash from directories
		if ( isset( $config['basePath'] ) ) {
			$this->basePath = rtrim( $config['basePath'], '/' ); // remove trailing slash
		} else {
			$this->basePath = null; // none; containers must have explicit paths
		}

		$this->containerPaths = [];
		foreach ( ( $config['containerPaths'] ?? [] ) as $container => $fsPath ) {
			$this->containerPaths[$container] = rtrim( $fsPath, '/' ); // remove trailing slash
		}

		$this->fileMode = $config['fileMode'] ?? 0644;
		$this->dirMode = $config['directoryMode'] ?? 0777;
		if ( isset( $config['fileOwner'] ) && function_exists( 'posix_getuid' ) ) {
			$this->fileOwner = $config['fileOwner'];
			// Cache this, assuming it doesn't change
			$this->currentUser = posix_getpwuid( posix_getuid() )['name'];
		}

		$this->usableDirCache = new MapCacheLRU( self::CACHE_CHEAP_SIZE );
	}

	public function getFeatures() {
		return self::ATTR_UNICODE_PATHS;
	}

	protected function resolveContainerPath( $container, $relStoragePath ) {
		// Check that container has a root directory
		if ( isset( $this->containerPaths[$container] ) || isset( $this->basePath ) ) {
			// Check for sane relative paths (assume the base paths are OK)
			if ( $this->isLegalRelPath( $relStoragePath ) ) {
				return $relStoragePath;
			}
		}

		return null; // invalid
	}

	/**
	 * Sanity check a relative file system path for validity
	 *
	 * @param string $fsPath Normalized relative path
	 * @return bool
	 */
	protected function isLegalRelPath( $fsPath ) {
		// Check for file names longer than 255 chars
		if ( preg_match( '![^/]{256}!', $fsPath ) ) { // ext3/NTFS
			return false;
		}
		if ( $this->os === 'Windows' ) { // NTFS
			return !preg_match( '![:*?"<>|]!', $fsPath );
		} else {
			return true;
		}
	}

	/**
	 * Given the short (unresolved) and full (resolved) name of
	 * a container, return the file system path of the container.
	 *
	 * @param string $shortCont
	 * @param string $fullCont
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
	 * @param string $storagePath Storage path
	 * @return string|null
	 */
	protected function resolveToFSPath( $storagePath ) {
		list( $fullCont, $relPath ) = $this->resolveStoragePathReal( $storagePath );
		if ( $relPath === null ) {
			return null; // invalid
		}
		list( , $shortCont, ) = FileBackend::splitStoragePath( $storagePath );
		$fsPath = $this->containerFSRoot( $shortCont, $fullCont ); // must be valid
		if ( $relPath != '' ) {
			$fsPath .= "/{$relPath}";
		}

		return $fsPath;
	}

	public function isPathUsableInternal( $storagePath ) {
		$fsPath = $this->resolveToFSPath( $storagePath );
		if ( $fsPath === null ) {
			return false; // invalid
		}

		if ( $this->fileOwner !== null && $this->currentUser !== $this->fileOwner ) {
			trigger_error( __METHOD__ . ": PHP process owner is not '{$this->fileOwner}'." );
			return false;
		}

		$fsDirectory = dirname( $fsPath );
		$usable = $this->usableDirCache->get( $fsDirectory, MapCacheLRU::TTL_PROC_SHORT );
		if ( $usable === null ) {
			AtEase::suppressWarnings();
			$usable = is_dir( $fsDirectory ) && is_writable( $fsDirectory );
			AtEase::restoreWarnings();
			$this->usableDirCache->set( $fsDirectory, $usable ? 1 : 0 );
		}

		return $usable;
	}

	protected function doCreateInternal( array $params ) {
		$status = $this->newStatus();

		$fsDstPath = $this->resolveToFSPath( $params['dst'] );
		if ( $fsDstPath === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );

			return $status;
		}

		if ( !empty( $params['async'] ) ) { // deferred
			$tempFile = $this->newTempFileWithContent( $params );
			if ( !$tempFile ) {
				$status->fatal( 'backend-fail-create', $params['dst'] );

				return $status;
			}
			$cmd = $this->makeCopyCommand( $tempFile->getPath(), $fsDstPath, false );
			$handler = function ( $errors, StatusValue $status, array $params, $cmd ) {
				if ( $errors !== '' && !( $this->os === 'Windows' && $errors[0] === " " ) ) {
					$status->fatal( 'backend-fail-create', $params['dst'] );
					trigger_error( "$cmd\n$errors", E_USER_WARNING ); // command output
				}
			};
			$status->value = new FSFileOpHandle( $this, $params, $handler, $cmd );
			$tempFile->bind( $status->value );
		} else { // immediate write
			$created = false;
			// Use fwrite+rename since (a) this clears xattrs, (b) threads still reading the old
			// inode are unaffected since it writes to a new inode, and (c) new threads reading
			// the file will either totally see the old version or totally see the new version
			$fsStagePath = $this->makeStagingPath( $fsDstPath );
			$this->trapWarningsIgnoringNotFound();
			$stageHandle = fopen( $fsStagePath, 'xb' );
			if ( $stageHandle ) {
				$bytes = fwrite( $stageHandle, $params['content'] );
				$created = ( $bytes === strlen( $params['content'] ) );
				fclose( $stageHandle );
				$created = $created ? rename( $fsStagePath, $fsDstPath ) : false;
			}
			$hadError = $this->untrapWarnings();
			if ( $hadError || !$created ) {
				$status->fatal( 'backend-fail-create', $params['dst'] );

				return $status;
			}
			$this->chmod( $fsDstPath );
		}

		return $status;
	}

	protected function doStoreInternal( array $params ) {
		$status = $this->newStatus();

		$fsSrcPath = $params['src']; // file system path
		$fsDstPath = $this->resolveToFSPath( $params['dst'] );
		if ( $fsDstPath === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );

			return $status;
		}

		if ( $fsSrcPath === $fsDstPath ) {
			$status->fatal( 'backend-fail-internal', $this->name );

			return $status; // sanity
		}

		if ( !empty( $params['async'] ) ) { // deferred
			$cmd = $this->makeCopyCommand( $fsSrcPath, $fsDstPath, false );
			$handler = function ( $errors, StatusValue $status, array $params, $cmd ) {
				if ( $errors !== '' && !( $this->os === 'Windows' && $errors[0] === " " ) ) {
					$status->fatal( 'backend-fail-store', $params['src'], $params['dst'] );
					trigger_error( "$cmd\n$errors", E_USER_WARNING ); // command output
				}
			};
			$status->value = new FSFileOpHandle( $this, $params, $handler, $cmd );
		} else { // immediate write
			$stored = false;
			// Use fwrite+rename since (a) this clears xattrs, (b) threads still reading the old
			// inode are unaffected since it writes to a new inode, and (c) new threads reading
			// the file will either totally see the old version or totally see the new version
			$fsStagePath = $this->makeStagingPath( $fsDstPath );
			$this->trapWarningsIgnoringNotFound();
			$srcHandle = fopen( $fsSrcPath, 'rb' );
			if ( $srcHandle ) {
				$stageHandle = fopen( $fsStagePath, 'xb' );
				if ( $stageHandle ) {
					$bytes = stream_copy_to_stream( $srcHandle, $stageHandle );
					$stored = ( $bytes !== false && $bytes === fstat( $srcHandle )['size'] );
					fclose( $stageHandle );
					$stored = $stored ? rename( $fsStagePath, $fsDstPath ) : false;
				}
				fclose( $srcHandle );
			}
			$hadError = $this->untrapWarnings();
			if ( $hadError || !$stored ) {
				$status->fatal( 'backend-fail-store', $params['src'], $params['dst'] );

				return $status;
			}
			$this->chmod( $fsDstPath );
		}

		return $status;
	}

	protected function doCopyInternal( array $params ) {
		$status = $this->newStatus();

		$fsSrcPath = $this->resolveToFSPath( $params['src'] );
		if ( $fsSrcPath === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['src'] );

			return $status;
		}

		$fsDstPath = $this->resolveToFSPath( $params['dst'] );
		if ( $fsDstPath === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );

			return $status;
		}

		if ( $fsSrcPath === $fsDstPath ) {
			return $status; // no-op
		}

		$ignoreMissing = !empty( $params['ignoreMissingSource'] );

		if ( !empty( $params['async'] ) ) { // deferred
			$cmd = $this->makeCopyCommand( $fsSrcPath, $fsDstPath, $ignoreMissing );
			$handler = function ( $errors, StatusValue $status, array $params, $cmd ) {
				if ( $errors !== '' && !( $this->os === 'Windows' && $errors[0] === " " ) ) {
					$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
					trigger_error( "$cmd\n$errors", E_USER_WARNING ); // command output
				}
			};
			$status->value = new FSFileOpHandle( $this, $params, $handler, $cmd );
		} else { // immediate write
			$copied = false;
			// Use fwrite+rename since (a) this clears xattrs, (b) threads still reading the old
			// inode are unaffected since it writes to a new inode, and (c) new threads reading
			// the file will either totally see the old version or totally see the new version
			$fsStagePath = $this->makeStagingPath( $fsDstPath );
			$this->trapWarningsIgnoringNotFound();
			$srcHandle = fopen( $fsSrcPath, 'rb' );
			if ( $srcHandle ) {
				$stageHandle = fopen( $fsStagePath, 'xb' );
				if ( $stageHandle ) {
					$bytes = stream_copy_to_stream( $srcHandle, $stageHandle );
					$copied = ( $bytes !== false && $bytes === fstat( $srcHandle )['size'] );
					fclose( $stageHandle );
					$copied = $copied ? rename( $fsStagePath, $fsDstPath ) : false;
				}
				fclose( $srcHandle );
			}
			$hadError = $this->untrapWarnings();
			if ( $hadError || ( !$copied && !$ignoreMissing ) ) {
				$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );

				return $status;
			}
			if ( $copied ) {
				$this->chmod( $fsDstPath );
			}
		}

		return $status;
	}

	protected function doMoveInternal( array $params ) {
		$status = $this->newStatus();

		$fsSrcPath = $this->resolveToFSPath( $params['src'] );
		if ( $fsSrcPath === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['src'] );

			return $status;
		}

		$fsDstPath = $this->resolveToFSPath( $params['dst'] );
		if ( $fsDstPath === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );

			return $status;
		}

		if ( $fsSrcPath === $fsDstPath ) {
			return $status; // no-op
		}

		$ignoreMissing = !empty( $params['ignoreMissingSource'] );

		if ( !empty( $params['async'] ) ) { // deferred
			$cmd = $this->makeMoveCommand( $fsSrcPath, $fsDstPath, $ignoreMissing );
			$handler = function ( $errors, StatusValue $status, array $params, $cmd ) {
				if ( $errors !== '' && !( $this->os === 'Windows' && $errors[0] === " " ) ) {
					$status->fatal( 'backend-fail-move', $params['src'], $params['dst'] );
					trigger_error( "$cmd\n$errors", E_USER_WARNING ); // command output
				}
			};
			$status->value = new FSFileOpHandle( $this, $params, $handler, $cmd );
		} else { // immediate write
			// Use rename() here since (a) this clears xattrs, (b) any threads still reading the
			// old inode are unaffected since it writes to a new inode, and (c) this is fast and
			// atomic within a file system volume (as is normally the case)
			$this->trapWarningsIgnoringNotFound();
			$moved = rename( $fsSrcPath, $fsDstPath );
			$hadError = $this->untrapWarnings();
			if ( $hadError || ( !$moved && !$ignoreMissing ) ) {
				$status->fatal( 'backend-fail-move', $params['src'], $params['dst'] );

				return $status;
			}
		}

		return $status;
	}

	protected function doDeleteInternal( array $params ) {
		$status = $this->newStatus();

		$fsSrcPath = $this->resolveToFSPath( $params['src'] );
		if ( $fsSrcPath === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['src'] );

			return $status;
		}

		$ignoreMissing = !empty( $params['ignoreMissingSource'] );

		if ( !empty( $params['async'] ) ) { // deferred
			$cmd = $this->makeUnlinkCommand( $fsSrcPath, $ignoreMissing );
			$handler = function ( $errors, StatusValue $status, array $params, $cmd ) {
				if ( $errors !== '' && !( $this->os === 'Windows' && $errors[0] === " " ) ) {
					$status->fatal( 'backend-fail-delete', $params['src'] );
					trigger_error( "$cmd\n$errors", E_USER_WARNING ); // command output
				}
			};
			$status->value = new FSFileOpHandle( $this, $params, $handler, $cmd );
		} else { // immediate write
			$this->trapWarningsIgnoringNotFound();
			$deleted = unlink( $fsSrcPath );
			$hadError = $this->untrapWarnings();
			if ( $hadError || ( !$deleted && !$ignoreMissing ) ) {
				$status->fatal( 'backend-fail-delete', $params['src'] );

				return $status;
			}
		}

		return $status;
	}

	/**
	 * @param string $fullCont
	 * @param string $dirRel
	 * @param array $params
	 * @return StatusValue
	 */
	protected function doPrepareInternal( $fullCont, $dirRel, array $params ) {
		$status = $this->newStatus();
		list( , $shortCont, ) = FileBackend::splitStoragePath( $params['dir'] );
		$contRoot = $this->containerFSRoot( $shortCont, $fullCont ); // must be valid
		$fsDirectory = ( $dirRel != '' ) ? "{$contRoot}/{$dirRel}" : $contRoot;
		// Create the directory and its parents as needed...
		$created = false;
		AtEase::suppressWarnings();
		$alreadyExisted = is_dir( $fsDirectory ); // already there?
		if ( !$alreadyExisted ) {
			$created = mkdir( $fsDirectory, $this->dirMode, true );
			if ( !$created ) {
				$alreadyExisted = is_dir( $fsDirectory ); // another thread made it?
			}
		}
		$isWritable = $created ?: is_writable( $fsDirectory ); // assume writable if created here
		AtEase::restoreWarnings();
		if ( !$alreadyExisted && !$created ) {
			$this->logger->error( __METHOD__ . ": cannot create directory $fsDirectory" );
			$status->fatal( 'directorycreateerror', $params['dir'] ); // fails on races
		} elseif ( !$isWritable ) {
			$this->logger->error( __METHOD__ . ": directory $fsDirectory is read-only" );
			$status->fatal( 'directoryreadonlyerror', $params['dir'] );
		}
		// Respect any 'noAccess' or 'noListing' flags...
		if ( $created ) {
			$status->merge( $this->doSecureInternal( $fullCont, $dirRel, $params ) );
		}

		if ( $status->isOK() ) {
			$this->usableDirCache->set( $fsDirectory, 1 );
		}

		return $status;
	}

	protected function doSecureInternal( $fullCont, $dirRel, array $params ) {
		$status = $this->newStatus();
		list( , $shortCont, ) = FileBackend::splitStoragePath( $params['dir'] );
		$contRoot = $this->containerFSRoot( $shortCont, $fullCont ); // must be valid
		$fsDirectory = ( $dirRel != '' ) ? "{$contRoot}/{$dirRel}" : $contRoot;
		// Seed new directories with a blank index.html, to prevent crawling...
		if ( !empty( $params['noListing'] ) && !is_file( "{$fsDirectory}/index.html" ) ) {
			$this->trapWarnings();
			$bytes = file_put_contents( "{$fsDirectory}/index.html", $this->indexHtmlPrivate() );
			$this->untrapWarnings();
			if ( $bytes === false ) {
				$status->fatal( 'backend-fail-create', $params['dir'] . '/index.html' );
			}
		}
		// Add a .htaccess file to the root of the container...
		if ( !empty( $params['noAccess'] ) && !is_file( "{$contRoot}/.htaccess" ) ) {
			AtEase::suppressWarnings();
			$bytes = file_put_contents( "{$contRoot}/.htaccess", $this->htaccessPrivate() );
			AtEase::restoreWarnings();
			if ( $bytes === false ) {
				$storeDir = "mwstore://{$this->name}/{$shortCont}";
				$status->fatal( 'backend-fail-create', "{$storeDir}/.htaccess" );
			}
		}

		return $status;
	}

	protected function doPublishInternal( $fullCont, $dirRel, array $params ) {
		$status = $this->newStatus();
		list( , $shortCont, ) = FileBackend::splitStoragePath( $params['dir'] );
		$contRoot = $this->containerFSRoot( $shortCont, $fullCont ); // must be valid
		$fsDirectory = ( $dirRel != '' ) ? "{$contRoot}/{$dirRel}" : $contRoot;
		// Unseed new directories with a blank index.html, to allow crawling...
		if ( !empty( $params['listing'] ) && is_file( "{$fsDirectory}/index.html" ) ) {
			$exists = ( file_get_contents( "{$fsDirectory}/index.html" ) === $this->indexHtmlPrivate() );
			if ( $exists && !$this->unlink( "{$fsDirectory}/index.html" ) ) { // reverse secure()
				$status->fatal( 'backend-fail-delete', $params['dir'] . '/index.html' );
			}
		}
		// Remove the .htaccess file from the root of the container...
		if ( !empty( $params['access'] ) && is_file( "{$contRoot}/.htaccess" ) ) {
			$exists = ( file_get_contents( "{$contRoot}/.htaccess" ) === $this->htaccessPrivate() );
			if ( $exists && !$this->unlink( "{$contRoot}/.htaccess" ) ) { // reverse secure()
				$storeDir = "mwstore://{$this->name}/{$shortCont}";
				$status->fatal( 'backend-fail-delete', "{$storeDir}/.htaccess" );
			}
		}

		return $status;
	}

	protected function doCleanInternal( $fullCont, $dirRel, array $params ) {
		$status = $this->newStatus();
		list( , $shortCont, ) = FileBackend::splitStoragePath( $params['dir'] );
		$contRoot = $this->containerFSRoot( $shortCont, $fullCont ); // must be valid
		$fsDirectory = ( $dirRel != '' ) ? "{$contRoot}/{$dirRel}" : $contRoot;

		$this->rmdir( $fsDirectory );

		return $status;
	}

	protected function doGetFileStat( array $params ) {
		$fsSrcPath = $this->resolveToFSPath( $params['src'] );
		if ( $fsSrcPath === null ) {
			return self::$RES_ERROR; // invalid storage path
		}

		$this->trapWarnings(); // don't trust 'false' if there were errors
		$stat = is_file( $fsSrcPath ) ? stat( $fsSrcPath ) : false; // regular files only
		$hadError = $this->untrapWarnings();

		if ( is_array( $stat ) ) {
			$ct = new ConvertibleTimestamp( $stat['mtime'] );

			return [
				'mtime' => $ct->getTimestamp( TS_MW ),
				'size' => $stat['size']
			];
		}

		return $hadError ? self::$RES_ERROR : self::$RES_ABSENT;
	}

	protected function doClearCache( array $paths = null ) {
		if ( is_array( $paths ) ) {
			foreach ( $paths as $path ) {
				$fsPath = $this->resolveToFSPath( $path );
				if ( $fsPath !== null ) {
					clearstatcache( true, $fsPath );
					$this->usableDirCache->clear( $fsPath );
				}
			}
		} else {
			clearstatcache( true ); // clear the PHP file stat cache
			$this->usableDirCache->clear();
		}
	}

	protected function doDirectoryExists( $fullCont, $dirRel, array $params ) {
		list( , $shortCont, ) = FileBackend::splitStoragePath( $params['dir'] );
		$contRoot = $this->containerFSRoot( $shortCont, $fullCont ); // must be valid
		$fsDirectory = ( $dirRel != '' ) ? "{$contRoot}/{$dirRel}" : $contRoot;

		$this->trapWarnings(); // don't trust 'false' if there were errors
		$exists = is_dir( $fsDirectory );
		$hadError = $this->untrapWarnings();

		return $hadError ? self::$RES_ERROR : $exists;
	}

	/**
	 * @see FileBackendStore::getDirectoryListInternal()
	 * @param string $fullCont
	 * @param string $dirRel
	 * @param array $params
	 * @return array|FSFileBackendDirList|null
	 */
	public function getDirectoryListInternal( $fullCont, $dirRel, array $params ) {
		list( , $shortCont, ) = FileBackend::splitStoragePath( $params['dir'] );
		$contRoot = $this->containerFSRoot( $shortCont, $fullCont ); // must be valid
		$fsDirectory = ( $dirRel != '' ) ? "{$contRoot}/{$dirRel}" : $contRoot;

		$list = new FSFileBackendDirList( $fsDirectory, $params );
		$error = $list->getLastError();
		if ( $error !== null ) {
			if ( $this->isFileNotFoundError( $error ) ) {
				$this->logger->info( __METHOD__ . ": non-existant directory: '$fsDirectory'" );

				return []; // nothing under this dir
			} elseif ( is_dir( $fsDirectory ) ) {
				$this->logger->warning( __METHOD__ . ": unreadable directory: '$fsDirectory'" );

				return self::$RES_ERROR; // bad permissions?
			} else {
				$this->logger->warning( __METHOD__ . ": unreachable directory: '$fsDirectory'" );

				return self::$RES_ERROR;
			}
		}

		return $list;
	}

	/**
	 * @see FileBackendStore::getFileListInternal()
	 * @param string $fullCont
	 * @param string $dirRel
	 * @param array $params
	 * @return array|FSFileBackendFileList|null
	 */
	public function getFileListInternal( $fullCont, $dirRel, array $params ) {
		list( , $shortCont, ) = FileBackend::splitStoragePath( $params['dir'] );
		$contRoot = $this->containerFSRoot( $shortCont, $fullCont ); // must be valid
		$fsDirectory = ( $dirRel != '' ) ? "{$contRoot}/{$dirRel}" : $contRoot;

		$list = new FSFileBackendFileList( $fsDirectory, $params );
		$error = $list->getLastError();
		if ( $error !== null ) {
			if ( $this->isFileNotFoundError( $error ) ) {
				$this->logger->info( __METHOD__ . ": non-existent directory: '$fsDirectory'" );

				return []; // nothing under this dir
			} elseif ( is_dir( $fsDirectory ) ) {
				$this->logger->warning( __METHOD__ .
					": unreadable directory: '$fsDirectory': $error" );

				return self::$RES_ERROR; // bad permissions?
			} else {
				$this->logger->warning( __METHOD__ .
					": unreachable directory: '$fsDirectory': $error" );

				return self::$RES_ERROR;
			}
		}

		return $list;
	}

	protected function doGetLocalReferenceMulti( array $params ) {
		$fsFiles = []; // (path => FSFile)

		foreach ( $params['srcs'] as $src ) {
			$source = $this->resolveToFSPath( $src );
			if ( $source === null ) {
				$fsFiles[$src] = self::$RES_ERROR; // invalid path
				continue;
			}

			$this->trapWarnings(); // don't trust 'false' if there were errors
			$isFile = is_file( $source ); // regular files only
			$hadError = $this->untrapWarnings();

			if ( $isFile ) {
				$fsFiles[$src] = new FSFile( $source );
			} elseif ( $hadError ) {
				$fsFiles[$src] = self::$RES_ERROR;
			} else {
				$fsFiles[$src] = self::$RES_ABSENT;
			}
		}

		return $fsFiles;
	}

	protected function doGetLocalCopyMulti( array $params ) {
		$tmpFiles = []; // (path => TempFSFile)

		foreach ( $params['srcs'] as $src ) {
			$source = $this->resolveToFSPath( $src );
			if ( $source === null ) {
				$tmpFiles[$src] = self::$RES_ERROR; // invalid path
				continue;
			}
			// Create a new temporary file with the same extension...
			$ext = FileBackend::extensionFromPath( $src );
			$tmpFile = $this->tmpFileFactory->newTempFSFile( 'localcopy_', $ext );
			if ( !$tmpFile ) {
				$tmpFiles[$src] = self::$RES_ERROR;
				continue;
			}

			$tmpPath = $tmpFile->getPath();
			// Copy the source file over the temp file
			$this->trapWarnings(); // don't trust 'false' if there were errors
			$isFile = is_file( $source ); // regular files only
			$copySuccess = $isFile ? copy( $source, $tmpPath ) : false;
			$hadError = $this->untrapWarnings();

			if ( $copySuccess ) {
				$this->chmod( $tmpPath );
				$tmpFiles[$src] = $tmpFile;
			} elseif ( $hadError ) {
				$tmpFiles[$src] = self::$RES_ERROR; // copy failed
			} else {
				$tmpFiles[$src] = self::$RES_ABSENT;
			}
		}

		return $tmpFiles;
	}

	protected function directoriesAreVirtual() {
		return false;
	}

	/**
	 * @param FSFileOpHandle[] $fileOpHandles
	 *
	 * @return StatusValue[]
	 */
	protected function doExecuteOpHandlesInternal( array $fileOpHandles ) {
		$statuses = [];

		$pipes = [];
		foreach ( $fileOpHandles as $index => $fileOpHandle ) {
			$pipes[$index] = popen( $fileOpHandle->cmd, 'r' );
		}

		$errs = [];
		foreach ( $pipes as $index => $pipe ) {
			// Result will be empty on success in *NIX. On Windows,
			// it may be something like "        1 file(s) [copied|moved].".
			$errs[$index] = stream_get_contents( $pipe );
			fclose( $pipe );
		}

		foreach ( $fileOpHandles as $index => $fileOpHandle ) {
			$status = $this->newStatus();
			$function = $fileOpHandle->callback;
			$function( $errs[$index], $status, $fileOpHandle->params, $fileOpHandle->cmd );
			$statuses[$index] = $status;
		}

		return $statuses;
	}

	/**
	 * @param string $fsPath Absolute file system path
	 * @return string Absolute file system path on the same device
	 */
	private function makeStagingPath( $fsPath ) {
		$time = dechex( time() ); // make it easy to find old orphans
		$hash = \Wikimedia\base_convert( md5( basename( $fsPath ) ), 16, 36, 25 );
		$unique = \Wikimedia\base_convert( bin2hex( random_bytes( 16 ) ), 16, 36, 25 );

		return dirname( $fsPath ) . "/.{$time}_{$hash}_{$unique}.tmpfsfile";
	}

	/**
	 * @param string $fsSrcPath Absolute file system path
	 * @param string $fsDstPath Absolute file system path
	 * @param bool $ignoreMissing Whether to no-op if the source file is non-existant
	 * @return string Command
	 */
	private function makeCopyCommand( $fsSrcPath, $fsDstPath, $ignoreMissing ) {
		// Use copy+rename since (a) this clears xattrs, (b) threads still reading the old
		// inode are unaffected since it writes to a new inode, and (c) new threads reading
		// the file will either totally see the old version or totally see the new version
		$fsStagePath = $this->makeStagingPath( $fsDstPath );
		$encSrc = escapeshellarg( $this->cleanPathSlashes( $fsSrcPath ) );
		$encStage = escapeshellarg( $this->cleanPathSlashes( $fsStagePath ) );
		$encDst = escapeshellarg( $this->cleanPathSlashes( $fsDstPath ) );
		if ( $this->os === 'Windows' ) {
			// https://docs.microsoft.com/en-us/windows-server/administration/windows-commands/copy
			// https://docs.microsoft.com/en-us/windows-server/administration/windows-commands/move
			$cmdWrite = "COPY /B /Y $encSrc $encStage 2>&1 && MOVE /Y $encStage $encDst 2>&1";
			$cmd = $ignoreMissing ? "IF EXIST $encSrc $cmdWrite" : $cmdWrite;
		} else {
			// https://manpages.debian.org/buster/coreutils/cp.1.en.html
			// https://manpages.debian.org/buster/coreutils/mv.1.en.html
			$cmdWrite = "cp $encSrc $encStage 2>&1 && mv $encStage $encDst 2>&1";
			$cmd = $ignoreMissing ? "test -f $encSrc && $cmdWrite" : $cmdWrite;
			// Clean up permissions on any newly created destination file
			$octalPermissions = '0' . decoct( $this->fileMode );
			if ( strlen( $octalPermissions ) == 4 ) {
				$cmd .= " && chmod $octalPermissions $encDst 2>/dev/null";
			}
		}

		return $cmd;
	}

	/**
	 * @param string $fsSrcPath Absolute file system path
	 * @param string $fsDstPath Absolute file system path
	 * @param bool $ignoreMissing Whether to no-op if the source file is non-existant
	 * @return string Command
	 */
	private function makeMoveCommand( $fsSrcPath, $fsDstPath, $ignoreMissing = false ) {
		// https://manpages.debian.org/buster/coreutils/mv.1.en.html
		// https://docs.microsoft.com/en-us/windows-server/administration/windows-commands/move
		$encSrc = escapeshellarg( $this->cleanPathSlashes( $fsSrcPath ) );
		$encDst	= escapeshellarg( $this->cleanPathSlashes( $fsDstPath ) );
		if ( $this->os === 'Windows' ) {
			$writeCmd = "MOVE /Y $encSrc $encDst 2>&1";
			$cmd = $ignoreMissing ? "IF EXIST $encSrc $writeCmd" : $writeCmd;
		} else {
			$writeCmd = "mv -f $encSrc $encDst 2>&1";
			$cmd = $ignoreMissing ? "test -f $encSrc && $writeCmd" : $writeCmd;
		}

		return $cmd;
	}

	/**
	 * @param string $fsPath Absolute file system path
	 * @param bool $ignoreMissing Whether to no-op if the file is non-existant
	 * @return string Command
	 */
	private function makeUnlinkCommand( $fsPath, $ignoreMissing = false ) {
		// https://manpages.debian.org/buster/coreutils/rm.1.en.html
		// https://docs.microsoft.com/en-us/windows-server/administration/windows-commands/del
		$encSrc = escapeshellarg( $this->cleanPathSlashes( $fsPath ) );
		if ( $this->os === 'Windows' ) {
			$writeCmd = "DEL /Q $encSrc 2>&1";
			$cmd = $ignoreMissing ? "IF EXIST $encSrc $writeCmd" : $writeCmd;
		} else {
			$cmd = $ignoreMissing ? "rm -f $encSrc 2>&1" : "rm $encSrc 2>&1";
		}

		return $cmd;
	}

	/**
	 * Chmod a file, suppressing the warnings
	 *
	 * @param string $fsPath Absolute file system path
	 * @return bool Success
	 */
	protected function chmod( $fsPath ) {
		if ( $this->os === 'Windows' ) {
			return true;
		}

		AtEase::suppressWarnings();
		$ok = chmod( $fsPath, $this->fileMode );
		AtEase::restoreWarnings();

		return $ok;
	}

	/**
	 * Unlink a file, suppressing the warnings
	 *
	 * @param string $fsPath Absolute file system path
	 * @return bool Success
	 */
	protected function unlink( $fsPath ) {
		AtEase::suppressWarnings();
		$ok = unlink( $fsPath );
		AtEase::restoreWarnings();
		clearstatcache( true, $fsPath );

		return $ok;
	}

	/**
	 * Remove an empty directory, suppressing the warnings
	 *
	 * @param string $fsDirectory Absolute file system path
	 * @return bool Success
	 */
	protected function rmdir( $fsDirectory ) {
		AtEase::suppressWarnings();
		$ok = rmdir( $fsDirectory ); // remove directory if empty
		AtEase::restoreWarnings();
		clearstatcache( true, $fsDirectory );

		return $ok;
	}

	/**
	 * @param array $params Parameters for FileBackend 'create' operation
	 * @return TempFSFile|null
	 */
	protected function newTempFileWithContent( array $params ) {
		$tempFile = $this->tmpFileFactory->newTempFSFile( 'create_', 'tmp' );
		if ( !$tempFile ) {
			return null;
		}

		AtEase::suppressWarnings();
		if ( file_put_contents( $tempFile->getPath(), $params['content'] ) === false ) {
			$tempFile = null;
		}
		AtEase::restoreWarnings();

		return $tempFile;
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
	 * @param string $fsPath FS path
	 * @return string
	 */
	protected function cleanPathSlashes( $fsPath ) {
		return ( $this->os === 'Windows' ) ? strtr( $fsPath, '/', '\\' ) : $fsPath;
	}

	/**
	 * Listen for E_WARNING errors and track whether any that happen
	 *
	 * @param string|null $regexIgnore Optional regex of errors to ignore
	 */
	protected function trapWarnings( $regexIgnore = null ) {
		$this->warningTrapStack[] = false;
		set_error_handler( function ( $errno, $errstr ) use ( $regexIgnore ) {
			if ( $regexIgnore === null || !preg_match( $regexIgnore, $errstr ) ) {
				$this->logger->error( $errstr );
				$this->warningTrapStack[count( $this->warningTrapStack ) - 1] = true;
			}
			return true; // suppress from PHP handler
		}, E_WARNING );
	}

	/**
	 * Track E_WARNING errors but ignore any that correspond to ENOENT "No such file or directory"
	 */
	protected function trapWarningsIgnoringNotFound() {
		$this->trapWarnings( $this->getFileNotFoundRegex() );
	}

	/**
	 * Stop listening for E_WARNING errors and get whether any happened
	 *
	 * @return bool Whether any warnings happened
	 */
	protected function untrapWarnings() {
		restore_error_handler();

		return array_pop( $this->warningTrapStack );
	}

	/**
	 * Get a regex matching file not found errors
	 *
	 * @return string
	 */
	protected function getFileNotFoundRegex() {
		static $regex;
		if ( $regex === null ) {
			// "No such file or directory": string literal in spl_directory.c etc.
			$alternatives = [ ': No such file or directory' ];
			if ( $this->os === 'Windows' ) {
				// 2 = The system cannot find the file specified.
				// 3 = The system cannot find the path specified.
				$alternatives[] = ' \(code: [23]\)';
			}
			if ( function_exists( 'pcntl_strerror' ) ) {
				$alternatives[] = preg_quote( ': ' . pcntl_strerror( 2 ), '/' );
			} elseif ( function_exists( 'socket_strerror' ) && defined( 'SOCKET_ENOENT' ) ) {
				$alternatives[] = preg_quote( ': ' . socket_strerror( SOCKET_ENOENT ), '/' );
			}
			$regex = '/(' . implode( '|', $alternatives ) . ')$/';
		}
		return $regex;
	}

	/**
	 * Determine whether a given error message is a file not found error.
	 *
	 * @param string $error
	 * @return bool
	 */
	protected function isFileNotFoundError( $error ) {
		return (bool)preg_match( $this->getFileNotFoundRegex(), $error );
	}
}
