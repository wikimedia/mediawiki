<?php
/**
 * Simulation of a backend storage in memory.
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

namespace Wikimedia\FileBackend;

use Wikimedia\AtEase\AtEase;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Simulation of a backend storage in memory.
 *
 * All data in the backend is automatically deleted at the end of PHP execution.
 * Since the data stored here is volatile, this is only useful for staging or testing.
 *
 * @ingroup FileBackend
 * @since 1.23
 */
class MemoryFileBackend extends FileBackendStore {
	/** @var array Map of (file path => (data,mtime) */
	protected $files = [];

	/** @inheritDoc */
	public function getFeatures() {
		return self::ATTR_UNICODE_PATHS;
	}

	/** @inheritDoc */
	public function isPathUsableInternal( $storagePath ) {
		return ( $this->resolveHashKey( $storagePath ) !== null );
	}

	/** @inheritDoc */
	protected function doCreateInternal( array $params ) {
		$status = $this->newStatus();

		$dst = $this->resolveHashKey( $params['dst'] );
		if ( $dst === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );

			return $status;
		}

		$this->files[$dst] = [
			'data' => $params['content'],
			'mtime' => ConvertibleTimestamp::convert( TS_MW, time() )
		];

		return $status;
	}

	/** @inheritDoc */
	protected function doStoreInternal( array $params ) {
		$status = $this->newStatus();

		$dst = $this->resolveHashKey( $params['dst'] );
		if ( $dst === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );

			return $status;
		}

		AtEase::suppressWarnings();
		$data = file_get_contents( $params['src'] );
		AtEase::restoreWarnings();
		if ( $data === false ) { // source doesn't exist?
			$status->fatal( 'backend-fail-store', $params['src'], $params['dst'] );

			return $status;
		}

		$this->files[$dst] = [
			'data' => $data,
			'mtime' => ConvertibleTimestamp::convert( TS_MW, time() )
		];

		return $status;
	}

	/** @inheritDoc */
	protected function doCopyInternal( array $params ) {
		return $this->copyInMemory( $params, 'copy' );
	}

	/** @inheritDoc */
	protected function doMoveInternal( array $params ) {
		return $this->copyInMemory( $params, 'move' );
	}

	/**
	 * @param array $params
	 * @param string $action whether it's 'copy' or 'move'
	 * @return \StatusValue
	 */
	private function copyInMemory( array $params, string $action ) {
		$status = $this->newStatus();

		$src = $this->resolveHashKey( $params['src'] );
		if ( $src === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['src'] );

			return $status;
		}

		$dst = $this->resolveHashKey( $params['dst'] );
		if ( $dst === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );

			return $status;
		}

		if ( !isset( $this->files[$src] ) ) {
			if ( empty( $params['ignoreMissingSource'] ) ) {
				// Error codes: backend-fail-copy, backend-fail-move
				$status->fatal( 'backend-fail-' . $action, $params['src'], $params['dst'] );
			}

			return $status;
		}

		$this->files[$dst] = [
			'data' => $this->files[$src]['data'],
			'mtime' => ConvertibleTimestamp::convert( TS_MW, time() )
		];

		if ( $action === 'move' ) {
			unset( $this->files[$src] );
		}
		return $status;
	}

	/** @inheritDoc */
	protected function doDeleteInternal( array $params ) {
		$status = $this->newStatus();

		$src = $this->resolveHashKey( $params['src'] );
		if ( $src === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['src'] );

			return $status;
		}

		if ( !isset( $this->files[$src] ) ) {
			if ( empty( $params['ignoreMissingSource'] ) ) {
				$status->fatal( 'backend-fail-delete', $params['src'] );
			}

			return $status;
		}

		unset( $this->files[$src] );

		return $status;
	}

	/** @inheritDoc */
	protected function doGetFileStat( array $params ) {
		$src = $this->resolveHashKey( $params['src'] );
		if ( $src === null ) {
			return self::RES_ERROR; // invalid path
		}

		if ( isset( $this->files[$src] ) ) {
			return [
				'mtime' => $this->files[$src]['mtime'],
				'size' => strlen( $this->files[$src]['data'] ),
			];
		}

		return self::RES_ABSENT;
	}

	/** @inheritDoc */
	protected function doGetLocalCopyMulti( array $params ) {
		$tmpFiles = []; // (path => TempFSFile)
		foreach ( $params['srcs'] as $srcPath ) {
			$src = $this->resolveHashKey( $srcPath );
			if ( $src === null ) {
				$fsFile = self::RES_ERROR;
			} elseif ( !isset( $this->files[$src] ) ) {
				$fsFile = self::RES_ABSENT;
			} else {
				// Create a new temporary file with the same extension...
				$ext = FileBackend::extensionFromPath( $src );
				$fsFile = $this->tmpFileFactory->newTempFSFile( 'localcopy_', $ext );
				if ( $fsFile ) {
					$bytes = file_put_contents( $fsFile->getPath(), $this->files[$src]['data'] );
					if ( $bytes !== strlen( $this->files[$src]['data'] ) ) {
						$fsFile = self::RES_ERROR;
					}
				}
			}
			$tmpFiles[$srcPath] = $fsFile;
		}

		return $tmpFiles;
	}

	/** @inheritDoc */
	protected function doDirectoryExists( $container, $dir, array $params ) {
		$prefix = rtrim( "$container/$dir", '/' ) . '/';
		foreach ( $this->files as $path => $data ) {
			if ( str_starts_with( $path, $prefix ) ) {
				return true;
			}
		}

		return false;
	}

	/** @inheritDoc */
	public function getDirectoryListInternal( $container, $dir, array $params ) {
		$dirs = [];
		$prefix = rtrim( "$container/$dir", '/' ) . '/';
		$prefixLen = strlen( $prefix );
		foreach ( $this->files as $path => $data ) {
			if ( str_starts_with( $path, $prefix ) ) {
				$relPath = substr( $path, $prefixLen );
				if ( !str_contains( $relPath, '/' ) ) {
					continue; // just a file
				}
				$parts = array_slice( explode( '/', $relPath ), 0, -1 ); // last part is file name
				if ( !empty( $params['topOnly'] ) ) {
					$dirs[$parts[0]] = 1; // top directory
				} else {
					$current = '';
					foreach ( $parts as $part ) { // all directories
						$dir = ( $current === '' ) ? $part : "$current/$part";
						$dirs[$dir] = 1;
						$current = $dir;
					}
				}
			}
		}

		return array_keys( $dirs );
	}

	/** @inheritDoc */
	public function getFileListInternal( $container, $dir, array $params ) {
		$files = [];
		$prefix = rtrim( "$container/$dir", '/' ) . '/';
		$prefixLen = strlen( $prefix );
		foreach ( $this->files as $path => $data ) {
			if ( str_starts_with( $path, $prefix ) ) {
				$relPath = substr( $path, $prefixLen );
				if (
					$relPath === '' ||
					( !empty( $params['topOnly'] ) && str_contains( $relPath, '/' ) )
				) {
					continue;
				}
				$files[] = $relPath;
			}
		}

		return $files;
	}

	/** @inheritDoc */
	protected function directoriesAreVirtual() {
		return true;
	}

	/**
	 * Get the absolute file system path for a storage path
	 *
	 * @param string $storagePath
	 * @return string|null
	 */
	protected function resolveHashKey( $storagePath ) {
		[ $fullCont, $relPath ] = $this->resolveStoragePathReal( $storagePath );
		if ( $relPath === null ) {
			return null; // invalid
		}

		return ( $relPath !== '' ) ? "$fullCont/$relPath" : $fullCont;
	}
}

/** @deprecated class alias since 1.43 */
class_alias( MemoryFileBackend::class, 'MemoryFileBackend' );
