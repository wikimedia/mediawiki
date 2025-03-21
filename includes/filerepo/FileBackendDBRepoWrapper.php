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
 */

namespace MediaWiki\FileRepo;

use Closure;
use InvalidArgumentException;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Output\StreamFile;
use Shellbox\Command\BoxedCommand;
use StatusValue;
use Wikimedia\FileBackend\FileBackend;
use Wikimedia\MapCacheLRU\MapCacheLRU;
use Wikimedia\Rdbms\IDatabase;

/**
 * Proxy backend that manages file layout rewriting for FileRepo.
 *
 * LocalRepo may be configured to store files under their title names or by SHA-1.
 * This acts as a shim in the latter case, providing backwards compatability for
 * most callers. All "public"/"deleted" zone files actually go in an "original"
 * container and are never changed.
 *
 * This requires something like thumb_handler.php and img_auth.php for client viewing of files.
 *
 * @ingroup FileRepo
 * @ingroup FileBackend
 * @since 1.25
 */
class FileBackendDBRepoWrapper extends FileBackend {
	/** @var FileBackend */
	protected $backend;
	/** @var string */
	protected $repoName;
	/** @var Closure */
	protected $dbHandleFunc;
	/** @var MapCacheLRU */
	protected $resolvedPathCache;
	/** @var IDatabase[] */
	protected $dbs;
	private int $migrationStage;

	public function __construct( array $config ) {
		/** @var FileBackend $backend */
		$backend = $config['backend'];
		$config['name'] = $backend->getName();
		$config['domainId'] = $backend->getDomainId();
		parent::__construct( $config );
		$this->backend = $config['backend'];
		$this->repoName = $config['repoName'];
		$this->dbHandleFunc = $config['dbHandleFactory'];
		$this->resolvedPathCache = new MapCacheLRU( 100 );
		$this->migrationStage = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::FileSchemaMigrationStage
		);
	}

	/**
	 * Get the underlying FileBackend that is being wrapped
	 *
	 * @return FileBackend
	 */
	public function getInternalBackend() {
		return $this->backend;
	}

	/**
	 * Translate a legacy "title" path to its "sha1" counterpart
	 *
	 * E.g. mwstore://local-backend/local-public/a/ab/<name>.jpg
	 * => mwstore://local-backend/local-original/x/y/z/<sha1>.jpg
	 *
	 * @param string $path
	 * @param bool $latest
	 * @return string
	 */
	public function getBackendPath( $path, $latest = true ) {
		$paths = $this->getBackendPaths( [ $path ], $latest );
		return current( $paths );
	}

	/**
	 * Translate legacy "title" paths to their "sha1" counterparts
	 *
	 * E.g. mwstore://local-backend/local-public/a/ab/<name>.jpg
	 * => mwstore://local-backend/local-original/x/y/z/<sha1>.jpg
	 *
	 * @param string[] $paths
	 * @param bool $latest
	 * @return string[] Translated paths in same order
	 */
	public function getBackendPaths( array $paths, $latest = true ) {
		$db = $this->getDB( $latest ? DB_PRIMARY : DB_REPLICA );

		// @TODO: batching
		$resolved = [];
		foreach ( $paths as $i => $path ) {
			if ( !$latest && $this->resolvedPathCache->hasField( $path, 'target', 10 ) ) {
				$resolved[$i] = $this->resolvedPathCache->getField( $path, 'target' );
				continue;
			}

			[ , $container ] = FileBackend::splitStoragePath( $path );

			if ( $container === "{$this->repoName}-public" ) {
				$name = basename( $path );
				if ( $this->migrationStage & SCHEMA_COMPAT_READ_OLD ) {
					if ( str_contains( $path, '!' ) ) {
						$sha1 = $db->newSelectQueryBuilder()
							->select( 'oi_sha1' )
							->from( 'oldimage' )
							->where( [ 'oi_archive_name' => $name ] )
							->caller( __METHOD__ )->fetchField();
					} else {
						$sha1 = $db->newSelectQueryBuilder()
							->select( 'img_sha1' )
							->from( 'image' )
							->where( [ 'img_name' => $name ] )
							->caller( __METHOD__ )->fetchField();
					}
				} else {
					if ( str_contains( $path, '!' ) ) {
						$sha1 = $db->newSelectQueryBuilder()
							->select( 'fr_sha1' )
							->from( 'filerevision' )
							->where( [ 'fr_archive_name' => $name ] )
							->caller( __METHOD__ )->fetchField();
					} else {
						$sha1 = $db->newSelectQueryBuilder()
							->select( 'fr_sha1' )
							->from( 'file' )
							->join( 'filerevision', null, 'file_latest = fr_id' )
							->where( [ 'file_name' => $name ] )
							->caller( __METHOD__ )->fetchField();
					}
				}

				if ( !is_string( $sha1 ) || $sha1 === '' ) {
					$resolved[$i] = $path; // give up
					continue;
				}
				$resolved[$i] = $this->getPathForSHA1( $sha1 );
				$this->resolvedPathCache->setField( $path, 'target', $resolved[$i] );
			} elseif ( $container === "{$this->repoName}-deleted" ) {
				$name = basename( $path ); // <hash>.<ext>
				$sha1 = substr( $name, 0, strpos( $name, '.' ) ); // ignore extension
				$resolved[$i] = $this->getPathForSHA1( $sha1 );
				$this->resolvedPathCache->setField( $path, 'target', $resolved[$i] );
			} else {
				$resolved[$i] = $path;
			}
		}

		$res = [];
		foreach ( $paths as $i => $path ) {
			$res[$i] = $resolved[$i];
		}

		return $res;
	}

	protected function doOperationsInternal( array $ops, array $opts ) {
		return $this->backend->doOperationsInternal( $this->mungeOpPaths( $ops ), $opts );
	}

	protected function doQuickOperationsInternal( array $ops, array $opts ) {
		return $this->backend->doQuickOperationsInternal( $this->mungeOpPaths( $ops ), $opts );
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
		return $this->translateSrcParams( __FUNCTION__, $params );
	}

	public function fileExists( array $params ) {
		return $this->translateSrcParams( __FUNCTION__, $params );
	}

	public function getFileTimestamp( array $params ) {
		return $this->translateSrcParams( __FUNCTION__, $params );
	}

	public function getFileSize( array $params ) {
		return $this->translateSrcParams( __FUNCTION__, $params );
	}

	public function getFileStat( array $params ) {
		return $this->translateSrcParams( __FUNCTION__, $params );
	}

	public function getFileXAttributes( array $params ) {
		return $this->translateSrcParams( __FUNCTION__, $params );
	}

	public function getFileSha1Base36( array $params ) {
		return $this->translateSrcParams( __FUNCTION__, $params );
	}

	public function getFileProps( array $params ) {
		return $this->translateSrcParams( __FUNCTION__, $params );
	}

	public function streamFile( array $params ) {
		// The stream methods use the file extension to determine the
		// Content-Type (as MediaWiki should already validate it on upload).
		// The translated SHA1 path has no extension, so this needs to use
		// the untranslated path extension.
		$type = StreamFile::contentTypeFromPath( $params['src'] );
		if ( $type && $type != 'unknown/unknown' ) {
			$params['headers'][] = "Content-type: $type";
		}
		return $this->translateSrcParams( __FUNCTION__, $params );
	}

	public function getFileContentsMulti( array $params ) {
		return $this->translateArrayResults( __FUNCTION__, $params );
	}

	public function getLocalReferenceMulti( array $params ) {
		return $this->translateArrayResults( __FUNCTION__, $params );
	}

	public function getLocalCopyMulti( array $params ) {
		return $this->translateArrayResults( __FUNCTION__, $params );
	}

	public function getFileHttpUrl( array $params ) {
		return $this->translateSrcParams( __FUNCTION__, $params );
	}

	public function addShellboxInputFile( BoxedCommand $command, string $boxedName,
		array $params
	) {
		$params['src'] = $this->getBackendPath( $params['src'], !empty( $params['latest'] ) );
		return $this->backend->addShellboxInputFile( $command, $boxedName, $params );
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

	public function clearCache( ?array $paths = null ) {
		$this->backend->clearCache( null ); // clear all
	}

	public function preloadCache( array $paths ) {
		$paths = $this->getBackendPaths( $paths );
		$this->backend->preloadCache( $paths );
	}

	public function preloadFileStat( array $params ) {
		return $this->translateSrcParams( __FUNCTION__, $params );
	}

	public function getScopedLocksForOps( array $ops, StatusValue $status ) {
		return $this->backend->getScopedLocksForOps( $ops, $status );
	}

	/**
	 * Get the ultimate original storage path for a file
	 *
	 * Use this when putting a new file into the system
	 *
	 * @param string $sha1 File SHA-1 base36
	 * @return string
	 */
	public function getPathForSHA1( $sha1 ) {
		if ( strlen( $sha1 ) < 3 ) {
			throw new InvalidArgumentException( "Invalid file SHA-1." );
		}
		return $this->backend->getContainerStoragePath( "{$this->repoName}-original" ) .
			"/{$sha1[0]}/{$sha1[1]}/{$sha1[2]}/{$sha1}";
	}

	/**
	 * Get a connection to the repo file registry DB
	 *
	 * @param int $index
	 * @return IDatabase
	 */
	protected function getDB( $index ) {
		if ( !isset( $this->dbs[$index] ) ) {
			$func = $this->dbHandleFunc;
			$this->dbs[$index] = $func( $index );
		}
		return $this->dbs[$index];
	}

	/**
	 * Translates paths found in the "src" or "srcs" keys of a params array
	 *
	 * @param string $function
	 * @param array $params
	 * @return mixed
	 */
	protected function translateSrcParams( $function, array $params ) {
		$latest = !empty( $params['latest'] );

		if ( isset( $params['src'] ) ) {
			$params['src'] = $this->getBackendPath( $params['src'], $latest );
		}

		if ( isset( $params['srcs'] ) ) {
			$params['srcs'] = $this->getBackendPaths( $params['srcs'], $latest );
		}

		return $this->backend->$function( $params );
	}

	/**
	 * Translates paths when the backend function returns results keyed by paths
	 *
	 * @param string $function
	 * @param array $params
	 * @return array
	 */
	protected function translateArrayResults( $function, array $params ) {
		$origPaths = $params['srcs'];
		$params['srcs'] = $this->getBackendPaths( $params['srcs'], !empty( $params['latest'] ) );
		$pathMap = array_combine( $params['srcs'], $origPaths );

		$results = $this->backend->$function( $params );

		$contents = [];
		foreach ( $results as $path => $result ) {
			$contents[$pathMap[$path]] = $result;
		}

		return $contents;
	}

	/**
	 * Translate legacy "title" source paths to their "sha1" counterparts
	 *
	 * This leaves destination paths alone since we don't want those to mutate
	 *
	 * @param array[] $ops
	 * @return array[]
	 */
	protected function mungeOpPaths( array $ops ) {
		// Ops that use 'src' and do not mutate core file data there
		static $srcRefOps = [ 'store', 'copy', 'describe' ];
		foreach ( $ops as &$op ) {
			if ( isset( $op['src'] ) && in_array( $op['op'], $srcRefOps ) ) {
				$op['src'] = $this->getBackendPath( $op['src'], true );
			}
			if ( isset( $op['srcs'] ) ) {
				$op['srcs'] = $this->getBackendPaths( $op['srcs'], true );
			}
		}
		return $ops;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( FileBackendDBRepoWrapper::class, 'FileBackendDBRepoWrapper' );
