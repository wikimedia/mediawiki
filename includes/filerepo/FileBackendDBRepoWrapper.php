<?php
/**
 * Proxy backend that manages file layout rewriting for FileRepo.
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
 * @ingroup FileRepo
 * @ingroup FileBackend
 * @author Aaron Schulz
 */

/**
 * @brief Proxy backend that manages file layout rewriting for FileRepo.
 *
 * LocalRepo may be configured to store files under their title names or by SHA-1.
 * This acts as a shim in the later case, providing backwards compatability for
 * most callers. All "public"/"deleted" zone files actually go in an "original"
 * container and are never changed.
 *
 * This requires something like thumb_handler.php and img_auth.php for client viewing of files.
 *
 * @ingroup FileRepo
 * @ingroup FileBackend
 * @since 1.24
 */
class FileBackendDBRepoWrapper extends FileBackend {
	/** @var FileBackend */
	protected $backend;
	/** @var string */
	protected $repoName;
	/** @var Closure */
	protected $dbHandleFunc;

	/** @var ProcessCacheLRU */
	protected $resolvedPathCache;

	/** @var Array Map of (index => DBConnRef) */
	protected $dbs;

	public function __construct( array $config ) {
		$config['name'] = $config['backend']->getName();
		$config['wikiId'] = $config['backend']->getWikiId();
		parent::__construct( $config );
		$this->backend = $config['backend'];
		$this->repoName = $config['repoName'];
		$this->dbHandleFunc = $config['dbHandleFactory'];
		$this->resolvedPathCache = new ProcessCacheLRU( 100 );
	}

	/**
	 * Get a connection to the repo file registry DB
	 *
	 * @param integer $index
	 * @return DBConnRef
	 */
	protected function getDB( $index ) {
		if ( !isset( $this->db[$index] ) ) {
			$func = $this->dbHandleFunc;
			$this->db[$index] = $func( $index );
		}
		return $this->db[$index];
	}

	/**
	 * Translate a legacy "title" path to it's "sha1" counterpart
	 *
	 * E.g. mwstore://local-backend/local-public/a/ab/<name>.jpg
	 * => mwstore://local-backend/local-original/x/y/z/<sha1>.jpg
	 *
	 * @param string $path
	 * @param bool $latest
	 * @return string
	 */
	public function getBackendPath( $path, $latest = true ) {
		$paths = $this->getBackendPaths( array( $path ), $latest );
		return current( $paths );
	}

	/**
	 * Translate legacy "title" paths to their "sha1" counterparts
	 *
	 * E.g. mwstore://local-backend/local-public/a/ab/<name>.jpg
	 * => mwstore://local-backend/local-original/x/y/z/<sha1>.jpg
	 *
	 * @param array $paths
	 * @param bool $latest
	 * @return array Translated paths in same order
	 */
	public function getBackendPaths( array $paths, $latest = true ) {
		$db = $this->getDB( $latest ? DB_MASTER : DB_SLAVE );
		$origBasePath = $this->backend->getContainerStoragePath( "{$this->repoName}-original" );

		// @TODO: batching
		$resolved = array();
		foreach ( $paths as $i => $path ) {
			if ( !$latest && $this->resolvedPathCache->has( $path, 'target', 10 ) ) {
				$resolved[$i] = $this->resolvedPathCache->get( $path, 'target' );
				continue;
			}

			list( , $container, $rel ) = FileBackend::splitStoragePath( $path );

			if ( $container === "{$this->repoName}-public" ) {
				$name = basename( $path );
				$ext = FileBackend::extensionFromPath( $path );
				if ( strpos( $path, '!' ) !== false ) {
					$sha1 = $db->selectField( 'oldimage', 'oi_sha1',
						array( 'oi_archive_name' => $name ),
						__METHOD__
					);
				} else {
					$sha1 = $db->selectField( 'image', 'img_sha1',
						array( 'img_name' => $name ),
						__METHOD__
					);
				}
				if ( !strlen( $sha1 ) ) {
					$resolved[$i] = $path; // give up
					continue;
				}
				$resolved[$i] = "$origBasePath/{$sha1[0]}/{$sha1[1]}/{$sha1[2]}/{$sha1}.{$ext}";
				$this->resolvedPathCache->set( $path, 'target', $resolved[$i] );
			} elseif ( $container === "{$this->repoName}-deleted" ) {
				$name = basename( $path );
				$resolved[$i] = "$origBasePath/{$name[0]}/{$name[1]}/{$name[2]}/{$name}";
				$this->resolvedPathCache->set( $path, 'target', $resolved[$i] );
			} else {
				$resolved[$i] = $path;
			}
		}

		$res = array();
		foreach ( $paths as $i => $path ) {
			$res[$i] = $resolved[$i];
		}

		return $res;
	}

	/**
	 * Translate legacy "title" source paths to their "sha1" counterparts
	 *
	 * This leaves destination paths alone since we don't want those to mutate
	 *
	 * @param array $ops
	 * @return array
	 */
	protected function mungeOpPaths( array $ops ) {
		// Ops that use 'src' and do not mutate core file data there
		static $srcRefOps = array( 'store', 'copy', 'describe' );
		foreach ( $ops as &$op ) {
			if ( isset( $op['src'] ) && in_array( $op['op'], $srcRefOps ) ) {
				$op['src'] = $this->getBackendPath( $op['src'] );
			}
			if ( isset( $op['srcs'] ) ) {
				$op['srcs'] = $this->getBackendPaths( $op['srcs'] );
			}
		}
		return $ops;
	}

	protected function doOperationsInternal( array $ops, array $opts ) {
		return $this->backend->doOperationsInternal( $this->mungeOpPaths( $ops ), $opts );
	}

	protected function doQuickOperationsInternal( array $ops ) {
		return $this->backend->doQuickOperationsInternal( $this->mungeOpPaths( $ops ) );
	}

	protected function doPrepare( array $params ) {
		return $this->backend->doPrepare( $params );
	}

	protected function doSecure( array $params ) {
		return $this->backend->doSecure( $params );
	}

	protected function doPublish( array $params ) {
		return $this->backend->doPublish( $params );
	}

	protected function doClean( array $params ) {
		return $this->backend->doClean( $params );
	}

	public function concatenate( array $params ) {
		$params['srcs'] = $this->getBackendPaths( $params['srcs'], !empty( $params['latest'] ) );

		return $this->backend->concatenate( $params );
	}

	public function fileExists( array $params ) {
		$params['src'] = $this->getBackendPath( $params['src'], !empty( $params['latest'] ) );

		return $this->backend->fileExists( $params );
	}

	public function getFileTimestamp( array $params ) {
		$params['src'] = $this->getBackendPath( $params['src'], !empty( $params['latest'] ) );

		return $this->backend->getFileTimestamp( $params );
	}

	public function getFileSize( array $params ) {
		$params['src'] = $this->getBackendPath( $params['src'], !empty( $params['latest'] ) );

		return $this->backend->getFileSize( $params );
	}

	public function getFileStat( array $params ) {
		$params['src'] = $this->getBackendPath( $params['src'], !empty( $params['latest'] ) );

		return $this->backend->getFileStat( $params );
	}

	public function getFileXAttributes( array $params ) {
		$params['src'] = $this->getBackendPath( $params['src'], !empty( $params['latest'] ) );

		return $this->backend->getFileXAttributes( $params );
	}

	public function getFileSha1Base36( array $params ) {
		$params['src'] = $this->getBackendPath( $params['src'], !empty( $params['latest'] ) );

		return $this->backend->getFileSha1Base36( $params );
	}

	public function getFileProps( array $params ) {
		$params['src'] = $this->getBackendPath( $params['src'], !empty( $params['latest'] ) );

		return $this->backend->getFileProps( $params );
	}

	public function streamFile( array $params ) {
		$params['src'] = $this->getBackendPath( $params['src'], !empty( $params['latest'] ) );

		return $this->backend->streamFile( $params );
	}

	public function getFileContentsMulti( array $params ) {
		$origPaths = $params['srcs'];
		$params['srcs'] = $this->getBackendPaths( $params['srcs'], !empty( $params['latest'] ) );
		$pathMap = array_combine( $params['srcs'], $origPaths );

		$contentsM = $this->backend->getFileContentsMulti( $params );

		$contents = array(); // (path => data) mapping using the proxy backend's paths
		foreach ( $contentsM as $path => $data ) {
			$contents[$pathMap[$path]] = $data;
		}

		return $contents;
	}

	public function getLocalReferenceMulti( array $params ) {
		$origPaths = $params['srcs'];
		$params['srcs'] = $this->getBackendPaths( $params['srcs'], !empty( $params['latest'] ) );
		$pathMap = array_combine( $params['srcs'], $origPaths );

		$tempFilesM = $this->backend->getLocalReferenceMulti( $params );

		$fsFiles = array(); // (path => FSFile) mapping using the proxy backend's paths
		foreach ( $tempFilesM as $path => $tempFile ) {
			$fsFiles[$pathMap[$path]] = $tempFile;
		}

		return $fsFiles;
	}

	public function getLocalCopyMulti( array $params ) {
		$origPaths = $params['srcs'];
		$params['srcs'] = $this->getBackendPaths( $params['srcs'], !empty( $params['latest'] ) );
		$pathMap = array_combine( $params['srcs'], $origPaths );

		$tempFilesM = $this->backend->getLocalCopyMulti( $params );

		$tempFiles = array(); // (path => TempFSFile) mapping using the proxy backend's paths
		foreach ( $tempFilesM as $path => $tempFile ) {
			$tempFiles[$pathMap[$path]] = $tempFile;
		}

		return $tempFiles;
	}

	public function getFileHttpUrl( array $params ) {
		$params['src'] = $this->getBackendPath( $params['src'] );
		return $this->backend->getFileHttpUrl( $params );
	}

	public function directoryExists( array $params ) {
		return $this->backend->directoryExists( $params );
	}

	public function getDirectoryList( array $params ) {
		return $this->backend->getDirectoryList( $params );
	}

	public function getFileList( array $params ) {
		return $this->backend->getFileList( $params );
	}

	public function getFeatures() {
		return $this->backend->getFeatures();
	}

	public function clearCache( array $paths = null ) {
		$this->backend->clearCache( null ); // clear all
	}

	public function preloadCache( array $paths ) {
		$paths = $this->getBackendPaths( $paths );
		$this->backend->preloadCache( $paths );
	}

	public function preloadFileStat( array $params ) {
		$params['srcs'] = $this->getBackendPaths( $params['srcs'] );
		$this->backend->preloadFileStat( $params );
	}

	public function getScopedLocksForOps( array $ops, Status $status ) {
		return $this->backend->getScopedFileLocks( $ops, $status );
	}
}
