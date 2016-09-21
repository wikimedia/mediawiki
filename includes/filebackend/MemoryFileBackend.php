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
 * @author Aaron Schulz
 */

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

	public function getFeatures() {
		return self::ATTR_UNICODE_PATHS;
	}

	public function isPathUsableInternal( $storagePath ) {
		return true;
	}

	protected function doCreateInternal( array $params ) {
		$status = $this->newStatus();

		$dst = $this->resolveHashKey( $params['dst'] );
		if ( $dst === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );

			return $status;
		}

		$this->files[$dst] = [
			'data' => $params['content'],
			'mtime' => wfTimestamp( TS_MW, time() )
		];

		return $status;
	}

	protected function doStoreInternal( array $params ) {
		$status = $this->newStatus();

		$dst = $this->resolveHashKey( $params['dst'] );
		if ( $dst === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );

			return $status;
		}

		MediaWiki\suppressWarnings();
		$data = file_get_contents( $params['src'] );
		MediaWiki\restoreWarnings();
		if ( $data === false ) { // source doesn't exist?
			$status->fatal( 'backend-fail-store', $params['src'], $params['dst'] );

			return $status;
		}

		$this->files[$dst] = [
			'data' => $data,
			'mtime' => wfTimestamp( TS_MW, time() )
		];

		return $status;
	}

	protected function doCopyInternal( array $params ) {
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
				$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
			}

			return $status;
		}

		$this->files[$dst] = [
			'data' => $this->files[$src]['data'],
			'mtime' => wfTimestamp( TS_MW, time() )
		];

		return $status;
	}

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

	protected function doGetFileStat( array $params ) {
		$src = $this->resolveHashKey( $params['src'] );
		if ( $src === null ) {
			return null;
		}

		if ( isset( $this->files[$src] ) ) {
			return [
				'mtime' => $this->files[$src]['mtime'],
				'size' => strlen( $this->files[$src]['data'] ),
			];
		}

		return false;
	}

	protected function doGetLocalCopyMulti( array $params ) {
		$tmpFiles = []; // (path => TempFSFile)
		foreach ( $params['srcs'] as $srcPath ) {
			$src = $this->resolveHashKey( $srcPath );
			if ( $src === null || !isset( $this->files[$src] ) ) {
				$fsFile = null;
			} else {
				// Create a new temporary file with the same extension...
				$ext = FileBackend::extensionFromPath( $src );
				$fsFile = TempFSFile::factory( 'localcopy_', $ext, $this->tmpDirectory );
				if ( $fsFile ) {
					$bytes = file_put_contents( $fsFile->getPath(), $this->files[$src]['data'] );
					if ( $bytes !== strlen( $this->files[$src]['data'] ) ) {
						$fsFile = null;
					}
				}
			}
			$tmpFiles[$srcPath] = $fsFile;
		}

		return $tmpFiles;
	}

	protected function doDirectoryExists( $container, $dir, array $params ) {
		$prefix = rtrim( "$container/$dir", '/' ) . '/';
		foreach ( $this->files as $path => $data ) {
			if ( strpos( $path, $prefix ) === 0 ) {
				return true;
			}
		}

		return false;
	}

	public function getDirectoryListInternal( $container, $dir, array $params ) {
		$dirs = [];
		$prefix = rtrim( "$container/$dir", '/' ) . '/';
		$prefixLen = strlen( $prefix );
		foreach ( $this->files as $path => $data ) {
			if ( strpos( $path, $prefix ) === 0 ) {
				$relPath = substr( $path, $prefixLen );
				if ( $relPath === false ) {
					continue;
				} elseif ( strpos( $relPath, '/' ) === false ) {
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

	public function getFileListInternal( $container, $dir, array $params ) {
		$files = [];
		$prefix = rtrim( "$container/$dir", '/' ) . '/';
		$prefixLen = strlen( $prefix );
		foreach ( $this->files as $path => $data ) {
			if ( strpos( $path, $prefix ) === 0 ) {
				$relPath = substr( $path, $prefixLen );
				if ( $relPath === false ) {
					continue;
				} elseif ( !empty( $params['topOnly'] ) && strpos( $relPath, '/' ) !== false ) {
					continue;
				}
				$files[] = $relPath;
			}
		}

		return $files;
	}

	protected function directoriesAreVirtual() {
		return true;
	}

	/**
	 * Get the absolute file system path for a storage path
	 *
	 * @param string $storagePath Storage path
	 * @return string|null
	 */
	protected function resolveHashKey( $storagePath ) {
		list( $fullCont, $relPath ) = $this->resolveStoragePathReal( $storagePath );
		if ( $relPath === null ) {
			return null; // invalid
		}

		return ( $relPath !== '' ) ? "$fullCont/$relPath" : $fullCont;
	}
}
