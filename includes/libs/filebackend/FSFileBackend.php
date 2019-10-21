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

	/** @var bool Whether the OS is Windows (otherwise assumed Unix-like) */
	protected $isWindows;
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

		$this->isWindows = ( strtoupper( substr( PHP_OS, 0, 3 ) ) === 'WIN' );
		// Remove any possible trailing slash from directories
		if ( isset( $config['basePath'] ) ) {
			$this->basePath = rtrim( $config['basePath'], '/' ); // remove trailing slash
		} else {
			$this->basePath = null; // none; containers must have explicit paths
		}

		$this->containerPaths = [];
		foreach ( ( $config['containerPaths'] ?? [] ) as $container => $path ) {
			$this->containerPaths[$container] = rtrim( $path, '/' ); // remove trailing slash
		}

		$this->fileMode = $config['fileMode'] ?? 0644;
		$this->dirMode = $config['directoryMode'] ?? 0777;
		if ( isset( $config['fileOwner'] ) && function_exists( 'posix_getuid' ) ) {
			$this->fileOwner = $config['fileOwner'];
			// cache this, assuming it doesn't change
			$this->currentUser = posix_getpwuid( posix_getuid() )['name'];
		}
	}

	public function getFeatures() {
		if ( $this->isWindows && version_compare( PHP_VERSION, '7.1', 'lt' ) ) {
			// PHP before 7.1 used 8-bit code page for filesystem paths on Windows;
			// See https://www.php.net/manual/en/migration71.windows-support.php
			return 0;
		} else {
			return self::ATTR_UNICODE_PATHS;
		}
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
	 * @param string $path Normalized relative path
	 * @return bool
	 */
	protected function isLegalRelPath( $path ) {
		// Check for file names longer than 255 chars
		if ( preg_match( '![^/]{256}!', $path ) ) { // ext3/NTFS
			return false;
		}
		if ( $this->isWindows ) { // NTFS
			return !preg_match( '![:*?"<>|]!', $path );
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

	protected function doCreateInternal( array $params ) {
		$status = $this->newStatus();

		$dest = $this->resolveToFSPath( $params['dst'] );
		if ( $dest === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );

			return $status;
		}

		if ( !empty( $params['async'] ) ) { // deferred
			$tempFile = $this->stageContentAsTempFile( $params );
			if ( !$tempFile ) {
				$status->fatal( 'backend-fail-create', $params['dst'] );

				return $status;
			}
			$cmd = implode( ' ', [
				$this->isWindows ? 'COPY /B /Y' : 'cp', // (binary, overwrite)
				escapeshellarg( $this->cleanPathSlashes( $tempFile->getPath() ) ),
				escapeshellarg( $this->cleanPathSlashes( $dest ) )
			] );
			$handler = function ( $errors, StatusValue $status, array $params, $cmd ) {
				if ( $errors !== '' && !( $this->isWindows && $errors[0] === " " ) ) {
					$status->fatal( 'backend-fail-create', $params['dst'] );
					trigger_error( "$cmd\n$errors", E_USER_WARNING ); // command output
				}
			};
			$status->value = new FSFileOpHandle( $this, $params, $handler, $cmd, $dest );
			$tempFile->bind( $status->value );
		} else { // immediate write
			$this->trapWarnings();
			$bytes = file_put_contents( $dest, $params['content'] );
			$this->untrapWarnings();
			if ( $bytes === false ) {
				$status->fatal( 'backend-fail-create', $params['dst'] );

				return $status;
			}
			$this->chmod( $dest );
		}

		return $status;
	}

	protected function doStoreInternal( array $params ) {
		$status = $this->newStatus();

		$dest = $this->resolveToFSPath( $params['dst'] );
		if ( $dest === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );

			return $status;
		}

		if ( !empty( $params['async'] ) ) { // deferred
			$cmd = implode( ' ', [
				$this->isWindows ? 'COPY /B /Y' : 'cp', // (binary, overwrite)
				escapeshellarg( $this->cleanPathSlashes( $params['src'] ) ),
				escapeshellarg( $this->cleanPathSlashes( $dest ) )
			] );
			$handler = function ( $errors, StatusValue $status, array $params, $cmd ) {
				if ( $errors !== '' && !( $this->isWindows && $errors[0] === " " ) ) {
					$status->fatal( 'backend-fail-store', $params['src'], $params['dst'] );
					trigger_error( "$cmd\n$errors", E_USER_WARNING ); // command output
				}
			};
			$status->value = new FSFileOpHandle( $this, $params, $handler, $cmd, $dest );
		} else { // immediate write
			$this->trapWarnings();
			$ok = copy( $params['src'], $dest );
			$this->untrapWarnings();
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

	protected function doCopyInternal( array $params ) {
		$status = $this->newStatus();

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

		if ( !is_file( $source ) ) {
			if ( empty( $params['ignoreMissingSource'] ) ) {
				$status->fatal( 'backend-fail-copy', $params['src'] );
			}

			return $status; // do nothing; either OK or bad status
		}

		if ( !empty( $params['async'] ) ) { // deferred
			$cmd = implode( ' ', [
				$this->isWindows ? 'COPY /B /Y' : 'cp', // (binary, overwrite)
				escapeshellarg( $this->cleanPathSlashes( $source ) ),
				escapeshellarg( $this->cleanPathSlashes( $dest ) )
			] );
			$handler = function ( $errors, StatusValue $status, array $params, $cmd ) {
				if ( $errors !== '' && !( $this->isWindows && $errors[0] === " " ) ) {
					$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
					trigger_error( "$cmd\n$errors", E_USER_WARNING ); // command output
				}
			};
			$status->value = new FSFileOpHandle( $this, $params, $handler, $cmd, $dest );
		} else { // immediate write
			$this->trapWarnings();
			$ok = ( $source === $dest ) ? true : copy( $source, $dest );
			$this->untrapWarnings();
			// In some cases (at least over NFS), copy() returns true when it fails
			if ( !$ok || ( filesize( $source ) !== filesize( $dest ) ) ) {
				if ( $ok ) { // PHP bug
					$this->trapWarnings();
					unlink( $dest ); // remove broken file
					$this->untrapWarnings();
					trigger_error( __METHOD__ . ": copy() failed but returned true." );
				}
				$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );

				return $status;
			}
			$this->chmod( $dest );
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
			// https://manpages.debian.org/buster/coreutils/mv.1.en.html
			// https://docs.microsoft.com/en-us/windows-server/administration/windows-commands/move
			$encSrc = escapeshellarg( $this->cleanPathSlashes( $fsSrcPath ) );
			$encDst	= escapeshellarg( $this->cleanPathSlashes( $fsDstPath ) );
			if ( $this->isWindows ) {
				$writeCmd = "MOVE /Y $encSrc $encDst";
				$cmd = $ignoreMissing ? "IF EXIST $encSrc $writeCmd" : $writeCmd;
			} else {
				$writeCmd = "mv -f $encSrc $encDst";
				$cmd = $ignoreMissing ? "test -f $encSrc && $writeCmd" : $writeCmd;
			}
			$handler = function ( $errors, StatusValue $status, array $params, $cmd ) {
				if ( $errors !== '' && !( $this->isWindows && $errors[0] === " " ) ) {
					$status->fatal( 'backend-fail-move', $params['src'], $params['dst'] );
					trigger_error( "$cmd\n$errors", E_USER_WARNING ); // command output
				}
			};
			$status->value = new FSFileOpHandle( $this, $params, $handler, $cmd );
		} else { // immediate write
			// Use rename() here since (a) this clears xattrs, (b) any threads still reading the
			// old inode are unaffected since it writes to a new inode, and (c) this is fast and
			// atomic within a file system volume (as is normally the case)
			$this->trapWarnings( '/: No such file or directory$/' );
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
			// https://manpages.debian.org/buster/coreutils/rm.1.en.html
			// https://docs.microsoft.com/en-us/windows-server/administration/windows-commands/del
			$encSrc = escapeshellarg( $this->cleanPathSlashes( $fsSrcPath ) );
			if ( $this->isWindows ) {
				$writeCmd = "DEL /Q $encSrc";
				$cmd = $ignoreMissing ? "IF EXIST $encSrc $writeCmd" : $writeCmd;
			} else {
				$cmd = $ignoreMissing ? "rm -f $encSrc" : "rm $encSrc";
			}
			$handler = function ( $errors, StatusValue $status, array $params, $cmd ) {
				if ( $errors !== '' && !( $this->isWindows && $errors[0] === " " ) ) {
					$status->fatal( 'backend-fail-delete', $params['src'] );
					trigger_error( "$cmd\n$errors", E_USER_WARNING ); // command output
				}
			};
			$status->value = new FSFileOpHandle( $this, $params, $handler, $cmd );
		} else { // immediate write
			$this->trapWarnings( '/: No such file or directory$/' );
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
		$dir = ( $dirRel != '' ) ? "{$contRoot}/{$dirRel}" : $contRoot;
		$existed = is_dir( $dir ); // already there?
		// Create the directory and its parents as needed...
		AtEase::suppressWarnings();
		if ( !$existed && !mkdir( $dir, $this->dirMode, true ) && !is_dir( $dir ) ) {
			$this->logger->error( __METHOD__ . ": cannot create directory $dir" );
			$status->fatal( 'directorycreateerror', $params['dir'] ); // fails on races
		} elseif ( !is_writable( $dir ) ) {
			$this->logger->error( __METHOD__ . ": directory $dir is read-only" );
			$status->fatal( 'directoryreadonlyerror', $params['dir'] );
		} elseif ( !is_readable( $dir ) ) {
			$this->logger->error( __METHOD__ . ": directory $dir is not readable" );
			$status->fatal( 'directorynotreadableerror', $params['dir'] );
		}
		AtEase::restoreWarnings();
		// Respect any 'noAccess' or 'noListing' flags...
		if ( is_dir( $dir ) && !$existed ) {
			$status->merge( $this->doSecureInternal( $fullCont, $dirRel, $params ) );
		}

		return $status;
	}

	protected function doSecureInternal( $fullCont, $dirRel, array $params ) {
		$status = $this->newStatus();
		list( , $shortCont, ) = FileBackend::splitStoragePath( $params['dir'] );
		$contRoot = $this->containerFSRoot( $shortCont, $fullCont ); // must be valid
		$dir = ( $dirRel != '' ) ? "{$contRoot}/{$dirRel}" : $contRoot;
		// Seed new directories with a blank index.html, to prevent crawling...
		if ( !empty( $params['noListing'] ) && !file_exists( "{$dir}/index.html" ) ) {
			$this->trapWarnings();
			$bytes = file_put_contents( "{$dir}/index.html", $this->indexHtmlPrivate() );
			$this->untrapWarnings();
			if ( $bytes === false ) {
				$status->fatal( 'backend-fail-create', $params['dir'] . '/index.html' );
			}
		}
		// Add a .htaccess file to the root of the container...
		if ( !empty( $params['noAccess'] ) && !file_exists( "{$contRoot}/.htaccess" ) ) {
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
		$dir = ( $dirRel != '' ) ? "{$contRoot}/{$dirRel}" : $contRoot;
		// Unseed new directories with a blank index.html, to allow crawling...
		if ( !empty( $params['listing'] ) && is_file( "{$dir}/index.html" ) ) {
			$exists = ( file_get_contents( "{$dir}/index.html" ) === $this->indexHtmlPrivate() );
			if ( $exists && !$this->unlink( "{$dir}/index.html" ) ) { // reverse secure()
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
		$dir = ( $dirRel != '' ) ? "{$contRoot}/{$dirRel}" : $contRoot;
		AtEase::suppressWarnings();
		rmdir( $dir ); // remove directory if empty
		AtEase::restoreWarnings();

		return $status;
	}

	protected function doGetFileStat( array $params ) {
		$source = $this->resolveToFSPath( $params['src'] );
		if ( $source === null ) {
			return self::$RES_ERROR; // invalid storage path
		}

		$this->trapWarnings(); // don't trust 'false' if there were errors
		$stat = is_file( $source ) ? stat( $source ) : false; // regular files only
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
		clearstatcache(); // clear the PHP file stat cache
	}

	protected function doDirectoryExists( $fullCont, $dirRel, array $params ) {
		list( , $shortCont, ) = FileBackend::splitStoragePath( $params['dir'] );
		$contRoot = $this->containerFSRoot( $shortCont, $fullCont ); // must be valid
		$dir = ( $dirRel != '' ) ? "{$contRoot}/{$dirRel}" : $contRoot;

		$this->trapWarnings(); // don't trust 'false' if there were errors
		$exists = is_dir( $dir );
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
		$dir = ( $dirRel != '' ) ? "{$contRoot}/{$dirRel}" : $contRoot;

		$this->trapWarnings(); // don't trust 'false' if there were errors
		$exists = is_dir( $dir );
		$isReadable = $exists ? is_readable( $dir ) : false;
		$hadError = $this->untrapWarnings();

		if ( $isReadable ) {
			return new FSFileBackendDirList( $dir, $params );
		} elseif ( $exists ) {
			$this->logger->warning( __METHOD__ . ": given directory is unreadable: '$dir'" );

			return self::$RES_ERROR; // bad permissions?
		} elseif ( $hadError ) {
			$this->logger->warning( __METHOD__ . ": given directory was unreachable: '$dir'" );

			return self::$RES_ERROR;
		} else {
			$this->logger->info( __METHOD__ . ": given directory does not exist: '$dir'" );

			return []; // nothing under this dir
		}
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
		$dir = ( $dirRel != '' ) ? "{$contRoot}/{$dirRel}" : $contRoot;

		$this->trapWarnings(); // don't trust 'false' if there were errors
		$exists = is_dir( $dir );
		$isReadable = $exists ? is_readable( $dir ) : false;
		$hadError = $this->untrapWarnings();

		if ( $exists && $isReadable ) {
			return new FSFileBackendFileList( $dir, $params );
		} elseif ( $exists ) {
			$this->logger->warning( __METHOD__ . ": given directory is unreadable: '$dir'\n" );

			return self::$RES_ERROR; // bad permissions?
		} elseif ( $hadError ) {
			$this->logger->warning( __METHOD__ . ": given directory was unreachable: '$dir'\n" );

			return self::$RES_ERROR;
		} else {
			$this->logger->info( __METHOD__ . ": given directory does not exist: '$dir'\n" );

			return []; // nothing under this dir
		}
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
		$octalPermissions = '0' . decoct( $this->fileMode );
		foreach ( $fileOpHandles as $index => $fileOpHandle ) {
			$cmd = "{$fileOpHandle->cmd} 2>&1";
			// Add a post-operation chmod command for permissions cleanup if applicable
			if (
				!$this->isWindows &&
				$fileOpHandle->chmodPath !== null &&
				strlen( $octalPermissions ) == 4
			) {
				$encPath = escapeshellarg( $fileOpHandle->chmodPath );
				$cmd .= " && chmod $octalPermissions $encPath 2>/dev/null";
			}
			$pipes[$index] = popen( $cmd, 'r' );
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
			$function = $fileOpHandle->call;
			$function( $errs[$index], $status, $fileOpHandle->params, $fileOpHandle->cmd );
			$statuses[$index] = $status;
		}

		clearstatcache(); // files changed

		return $statuses;
	}

	/**
	 * Chmod a file, suppressing the warnings
	 *
	 * @param string $path Absolute file system path
	 * @return bool Success
	 */
	protected function chmod( $path ) {
		if ( $this->isWindows ) {
			return true;
		}

		AtEase::suppressWarnings();
		$ok = chmod( $path, $this->fileMode );
		AtEase::restoreWarnings();

		return $ok;
	}

	/**
	 * Unlink a file, suppressing the warnings
	 *
	 * @param string $path Absolute file system path
	 * @return bool Success
	 */
	protected function unlink( $path ) {
		AtEase::suppressWarnings();
		$ok = unlink( $path );
		AtEase::restoreWarnings();

		return $ok;
	}

	/**
	 * @param array $params Operation parameters with 'content' and 'headers' fields
	 * @return TempFSFile|null
	 */
	protected function stageContentAsTempFile( array $params ) {
		$content = $params['content'];
		$tempFile = $this->tmpFileFactory->newTempFSFile( 'create_', 'tmp' );
		if ( !$tempFile ) {
			return null;
		}

		AtEase::suppressWarnings();
		$tmpPath = $tempFile->getPath();
		if ( file_put_contents( $tmpPath, $content ) === false ) {
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
	 * @param string $path FS path
	 * @return string
	 */
	protected function cleanPathSlashes( $path ) {
		return $this->isWindows ? strtr( $path, '/', '\\' ) : $path;
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
	 * Stop listening for E_WARNING errors and get whether any happened
	 *
	 * @return bool Whether any warnings happened
	 */
	protected function untrapWarnings() {
		restore_error_handler();

		return array_pop( $this->warningTrapStack );
	}
}
