<?php
/**
 * Amazon S3 based file backend.
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
 * @author Thai Phan
 */

use Aws\Common\Aws;
use Aws\S3\Enum\CannedAcl;
use Aws\S3\Exception\NoSuchBucketException;
use Aws\S3\Exception\NoSuchKeyException;
use Aws\S3\Exception\S3Exception;

/**
 * @brief Class for an Amazon S3 based file backend.
 *
 * @ingroup FileBackend
 * @since 1.21
 */
class S3FileBackend extends FileBackendStore {
	private $_aws;

	private $_s3;

	/**
	 * @see FileBackendStore::__construct()
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );

		$this->_aws = Aws::factory( array(
			'key'    => $config['s3Key'],
			'secret' => $config['s3Secret'],
			'region' => $config['s3Region']
		) );

		$this->_s3 = $this->_aws->get( 's3' );
	}

	/**
	 * @see FileBackendStore::resolveContainerPath()
	 * @return null
	 */
	protected function resolveContainerPath( $container, $relStoragePath ) {
		if ( !mb_check_encoding( $relStoragePath, 'UTF-8' ) ) {
			return null;
		} elseif ( strlen( urlencode( $relStoragePath ) ) > 1024 ) {
			return null;
		}

		return $relStoragePath;
	}

	/**
	 * @see FileBackendStore::isPathUsableInternal()
	 * @return bool
	 */
	public function isPathUsableInternal( $storagePath ) {
		list( $container, $rel ) = $this->resolveStoragePathReal( $storagePath );
		if ( $rel === null ) {
			return false; // invalid
		}

		return $this->_s3->doesBucketExist( $container );
	}

	/**
	 * @see FileBackendStore::doCreateInternal()
	 * @return Status
	 */
	protected function doCreateInternal( array $params ) {
		$status = Status::newGood();

		list( $dstCont, $dstRel ) = $this->resolveStoragePathReal( $params['dst'] );
		if ( $dstRel === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );
			return $status;
		}

		// (a) Get a SHA-1 hash of the object
		$sha1Hash = wfBaseConvert( sha1( $params['content'] ), 16, 36, 31 );

		// (b) Actually create the object
		try {
			$this->_s3->putObject( array(
				'Bucket'   => $dstCont,
				'Key'      => $dstRel,
				'Body'     => $params['content'],
				'Metadata' => array( 'sha1base36' => $sha1Hash )
			) );
		} catch ( NoSuchBucketException $e ) {
			$status->fatal( 'backend-fail-create', $params['dst'] );
		} catch ( S3Exception $e ) {
			$this->handleException( $e, $status, __METHOD__, $params );
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::doStoreInternal()
	 * @return Status
	 */
	protected function doStoreInternal( array $params ) {
		$status = Status::newGood();

		list( $dstCont, $dstRel ) = $this->resolveStoragePathReal( $params['dst'] );
		if ( $dstRel === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );
			return $status;
		}

		// (a) Get a SHA-1 hash of the object
		$sha1Hash = sha1_file( $params['src'] );
		if ( $sha1Hash === false ) { // source doesn't exist?
			$status->fatal( 'backend-fail-store', $params['src'], $params['dst'] );
			return $status;
		}
		$sha1Hash = wfBaseConvert( $sha1Hash, 16, 36, 31 );

		// (b) Actually store the object
		try {
			$this->_s3->putObject( array(
				'Bucket'   => $dstCont,
				'Key'      => $dstRel,
				'Body'     => file_get_contents( $params['src'] ),
				'Metadata' => array( 'sha1base36' => $sha1Hash )
			) );
		} catch ( NoSuchBucketException $e ) {
			$status->fatal( 'backend-fail-store', $params['src'], $params['dst'] );
		} catch ( S3Exception $e ) { // some other exception?
			$this->handleException( $e, $status, __METHOD__, $params );
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::doCopyInternal()
	 * @return Status
	 */
	protected function doCopyInternal( array $params ) {
		$status = Status::newGood();

		list( $srcCont, $srcRel ) = $this->resolveStoragePathReal( $params['src'] );
		if ( $srcRel === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['src'] );
			return $status;
		}

		list( $dstCont, $dstRel ) = $this->resolveStoragePathReal( $params['dst'] );
		if ( $dstRel === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );
			return $status;
		}

		try {
			$this->_s3->copyObject( array(
				'Bucket'     => $dstCont,
				'Key'        => $dstRel,
				'CopySource' => $srcCont . '/' . $srcRel
			) );
		} catch ( NoSuchBucketException $e ) {
			$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
		} catch ( NoSuchKeyException $e ) { // source object does not exist
			if ( empty( $params['ignoreMissingSource'] ) ) {
				$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
			}
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, $status, __METHOD__, $params );
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::doDeleteInternal()
	 * @return Status
	 */
	protected function doDeleteInternal( array $params ) {
		$status = Status::newGood();

		list( $srcCont, $srcRel ) = $this->resolveStoragePathReal( $params['src'] );
		if ( $srcRel === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['src'] );
			return $status;
		}

		try {
			$this->_s3->deleteObject( array(
				'Bucket' => $srcCont,
				'Key'    => $srcRel
			) );
		} catch ( NoSuchBucketException $e ) {
			$status->fatal( 'backend-fail-delete', $params['src'] );
		} catch ( NoSuchKeyException $e ) {
			if ( empty( $params['ignoreMissingSource'] ) ) {
				$status->fatal( 'backend-fail-delete', $params['src'] );
			}
		} catch ( S3Exception $e ) { // some other exception?
			$this->handleException( $e, $status, __METHOD__, $params );
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::doPrepareInternal()
	 * @return Status
	 */
	protected function doPrepareInternal( $fullCont, $dir, array $params ) {
		$status = Status::newGood();

		try {
			$this->_s3->createBucket( array(
				'Bucket' => $fullCont
			) );
			if ( !empty( $params['noAccess'] ) ) {
				// Make container private to end-users...
				$status->merge( $this->doSecureInternal( $fullCont, $dir, $params ) );
			} else {
				// Make container public to end-users...
				$status->merge( $this->doPublishInternal( $fullCont, $dir, $params ) );
			}
		} catch ( S3Exception $e ) { // some other exception?
			$this->handleException( $e, $status, __METHOD__, $params );
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::doSecureInternal()
	 * @return Status
	 */
	protected function doSecureInternal( $fullCont, $dir, array $params ) {
		$status = Status::newGood();
		if ( empty( $params['noAccess'] ) ) {
			return $status; // nothing to do
		}

		// Restrict container from end-users...
		try {
			$this->_s3->putBucketAcl( array(
				'Bucket' => $fullCont,
				'ACL'    => CannedAcl::PRIVATE_ACCESS
			) );
		} catch ( S3Exception $e ) { // some other exception?
			$this->handleException( $e, $status, __METHOD__, $params );
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::doPublishInternal()
	 * @return Status
	 */
	protected function doPublishInternal( $fullCont, $dir, array $params ) {
		$status = Status::newGood();
		if ( empty( $params['noAccess'] ) ) {
			return $status; // nothing to do
		}

		// Make container public to end-users...
		try {
			$this->_s3->putBucketAcl( array(
				'Bucket' => $fullCont,
				'ACL'    => CannedAcl::PUBLIC_READ_WRITE
			) );
		} catch ( S3Exception $e ) { // some other exception?
			$this->handleException( $e, $status, __METHOD__, $params );
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::doCleanInternal()
	 * @return Status
	 */
	protected function doCleanInternal( $fullCont, $dir, array $params ) {
		$status = Status::newGood();

		// Only buckets themselves can be removed, all else is virtual
		if ( $dir != '' ) {
			return $status; // nothing to do
		}

		try {
			$this->_s3->deleteBucket( array(
				'Bucket' => $fullCont
			) );
		} catch ( NoSuchBucketException $e ) {
			return $status;
		} catch ( BucketNotEmptyException $e ) {
			return $status;
		} catch ( S3Exception $e ) { // some other exception?
			$this->handleException( $e, $status, __METHOD__, $params );
			return $status;
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::doFileExists()
	 * @return array|bool|null
	 */
	protected function doGetFileStat( array $params ) {
		list( $srcCont, $srcRel ) = $this->resolveStoragePathReal( $params['src'] );
		if ( $srcRel === null ) {
			return false; // invalid storage path
		}

		$stat = false;
		try {
			$blob = $this->_s3->getObject( array(
				'Bucket' => $srcCont,
				'Key' => $srcRel
			) );
			$timestamp = $blob['LastModified'];
			$size = (int)$blob['ContentLength'];
			$sha1 = $blob['Metadata']['sha1base36'];
			$stat = array(
				'mtime' => wfTimestamp( TS_MW, $timestamp ),
				'size'  => $size,
				'sha1'  => $sha1
			);
		} catch ( NoSuchBucketException $e ) {
		} catch ( NoSuchKeyException $e ) {
		} catch ( S3Exception $e ) { // some other exception?
			$stat = null;
			$this->handleException( $e, null, __METHOD__, $params );
		}

		return $stat;
	}

	/**
	 * @see FileBackendStore::doDirectoryExists()
	 * @return bool|null
	 */
	protected function doDirectoryExists( $fullCont, $dir, array $params ) {
		try {
			$prefix = ( $dir == '' ) ? null : "{$dir}/";
			$objects = $this->_s3->listObjects( array(
				'Bucket' => $fullCont,
				'MaxKeys' => 1,
				'Prefix' => $prefix
			) );
			return ( count( $objects['Contents'] ) > 0 );
		} catch ( NoSuchBucketException $e ) {
			return false;
		} catch ( S3Exception $e ) { // some other exception?
			$this->handleException( $e, null, __METHOD__,
				array( 'cont' => $fullCont, 'dir' => $dir ) );
		}

		return null; // error
	}

	/**
	 * @see FileBackendStore::getDirectoryListInternal()
	 * @return S3FileBackendDirList
	 */
	public function getDirectoryListInternal( $fullCont, $dir, array $params ) {
		return new S3FileBackendDirList( $this, $fullCont, $dir, $params );
	}

	/**
	 * @see FileBackendStore::getFileListInternal()
	 * @return S3FileBackendFileList
	 */
	public function getFileListInternal( $fullCont, $dir, array $params ) {
		return new S3FileBackendFileList( $this, $fullCont, $dir, $params );
	}

	/**
	 * Do not call this function outside of S3FileBackendFileList
	 *
	 * @param $fullCont string Resolved bucket name
	 * @param $dir string Resolved storage directory with no trailing slash
	 * @param $after string|null Storage path of file to list items after
	 * @param $limit integer Max number of items to list
	 * @param $params Array Includes flag for 'topOnly'
	 * @return Array List of relative paths of dirs directly under $dir
	 */
	public function getDirListPageInternal( $fullCont, $dir, &$after, $limit, array $params ) {
		$dir = array();
		if ( $after === INF ) {
			return $dirs; // nothing more
		}
		wfProfileIn( __METHOD__ . '-' . $this->name );

		$objects = array();
		try {
			$prefix = ( $dir == '' ) ? null : "{$dir}/";
			// Non-recursive: only list dirs right under $dir
			if ( !empty( $params['topOnly'] ) ) {
				$blobs = $this->_s3->listObjects( array(
					'Bucket'    => $fullCont,
					'MaxKeys'   => $limit,
					'Marker'    => $after,
					'Prefix'    => $prefix,
					'Delimiter' => '/'
				) );
				foreach ( $blobs['Contents'] as $blob ) {
					array_push( $objects, $blob['Key'] );
				}
				foreach ( $objects as $object ) { // files and dirs
					if ( substr( $object, -1 ) === '/' ) {
						$dirs[] = $object; // directories end in '/'
					}
				}
			// Recursive: list all dirs under $dir and its subdirs
			} else {
				// Get directory from last item of prior page
				$lastDir = $this->getParentDir( $after ); // must be first page

				$blobs = $this->_s3->listObjects( array(
					'Bucket'  => $fullCont,
					'MaxKeys' => $limit,
					'Marker'  => $after,
					'Prefix'  => $prefix
				) );
				foreach ( $blobs['Contents'] as $blob ) {
					array_push( $objects, $blob['Key'] );
				}
				foreach ( $objects as $object ) { // files
					$objectDir = $this->getParentDir( $object ); // directory of object
					if ( $objectDir !== false && $objectDir !== $dir ) {
						if ( strcmp( $objectDir, $lastDir ) > 0 ) {
							$pDir = $objectDir;
							do { // add dir and all its parent dirs
								$dirs[] = "{$pDir}/";
								$pDir = $this->getParentDir( $pDir );
							} while ( $pDir !== false // sanity
								&& strcmp( $pDir, $lastDir ) > 0 // not done already
								&& strlen( $pDir ) > strlen( $dir ) // within $dir
							);
						}
						$lastDir = $objectDir;
					}
				}
			}
			if ( count( $objects ) < $limit ) {
				$after = INF; // avoid a second RTT
			} else {
				$after = end( $objects ); // update last item
			}
		} catch ( NoSuchBucketException $e ) {
		} catch ( S3Exception $e ) { // some other exception?
			$this->handleException( $e, null, __METHOD__,
				array( 'cont' => $fullCont, 'dir' => $dir ) );
		}

		wfProfileOut( __METHOD__ . '-' . $this->name );
		return $dirs;
	}

	protected function getParentDir( $path ) {
		return ( strpos( $path, '/' ) !== false ) ? dirname( $path ) : false;
	}

	/**
	 * Do not call this function outside of S3FileBackendFileList
	 *
	 * @param $fullCont string Resolved bucket name
	 * @param $dir string Resolved storage directory with no trailing slash
	 * @param $after string|null Storage path of file to list items after
	 * @param $limit integer Max number of items to list
	 * @param $params Array Includes flag for 'topOnly'
	 * @return Array List of relative paths of files under $dir
	 */
	public function getFileListPageInternal( $fullCont, $dir, &$after, $limit, array $params ) {
		$files = array();
		if ( $after === INF ) {
			return $files; // nothing more
		}
		wfProfileIn( __METHOD__ . '-' . $this->name );

		$objects = array();
		try {
			$prefix = ( $dir == '' ) ? null : "{$dir}/";
			// Non-recursive: only list files right under $dir
			if ( !empty( $params['topOnly'] ) ) { // files and dirs
				$blobs = $this->_s3->listObjects( array(
					'Bucket'   => $fullCont,
					'MaxKeys'  => $limit,
					'Marker'   => $after,
					'Prefix'   => $prefix,
					'Delimiter' => '/'
				) );
				foreach ( $blobs['Contents'] as $blob ) {
					array_push( $objects, $blob['Key'] );
				}
				foreach ( $objects as $object ) {
					if ( substr( $object, -1 ) !== '/' ) {
						$files[] = $object; // directories end in '/'
					}
				}
			// Recursive: list all files under $dir and its subdirs
			} else { // files
				$blobs = $this->_s3->listObjects( array(
					'Bucket'   => $fullCont,
					'MaxKeys'  => $limit,
					'Marker'   => $after,
					'Prefix'   => $prefix
				) );
				foreach ( $blobs['Contents'] as $blob ) {
					array_push( $objects, $blob['Key'] );
				}
				$files = $objects;
			}
			if ( count( $objects ) < $limit ) {
				$after = INF; // avoid a second RTT
			} else {
				$after = end( $objects ); // update last item
			}
		} catch ( NoSuchBucketException $e ) {
		} catch ( S3Exception $e ) { // some other exception?
			$this->handleException( $e, null, __METHOD__,
				array( 'cont' => $fullCont, 'dir' => $dir ) );
		}

		wfProfileOut( __METHOD__ . '-' . $this->name );
		return $files;
	}

	/**
	 * @see FileBackendStore::doGetFileSha1base36()
	 * @return bool
	 */
	protected function doGetFileSha1base36( array $params ) {
		$stat = $this->doFileStat( $params );
		if ( $stat ) {
			return $stat['sha1'];
		} else {
			return false;
		}
	}

	/**
	 * @see FileBackendStore::doStreamFile()
	 * @return Status
	 */
	protected function doStreamFile( array $params ) {
		$status = Status::newGood();

		list( $srcCont, $srcRel ) = $this->resolveStoragePathReal( $params['src'] );
		if ( $srcRel === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['src'] );
		}

		try {
			$object = $this->_s3->getObject( array(
				'Bucket' => $srcCont,
				'Key'    => $srcRel
			) );
			file_put_contents( 'php://output', $object['Body'] );
		} catch ( NoSuchBucketException $e ) {
			$status->fatal( 'backend-fail-stream', $params['src'] );
		} catch ( NoSuchKeyException $e ) {
			$status->fatal( 'backend-fail-stream', $params['src'] );
		} catch ( S3Exception $e ) { // some other exception?
			$this->handleException( $e, $status, __METHOD__, $params );
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::doGetLocalCopyMulti()
	 * @return null|TempFSFile
	 */
	protected function doGetLocalCopyMulti( array $params ) {
		$tmpFiles = array();

		$ep = array_diff_key( $params, array( 'src' => 1 ) ); // for error logging
		// Blindly create tmp files and stream to them, catching any exception if the file does
		// not exist. Doing a stat here is useless causes infinite loops in addMissingMetadata().
		foreach ( array_chunk( $params['srcs'], $params['concurrency'] ) as $pathBatch ) {
			foreach ( $pathBatch as $path ) { // each path in this concurrent batch
				list( $srcCont, $srcRel ) = $this->resolveStoragePathReal( $path );
				if ( $srcRel === null ) {
					$tmpFiles[$path] = null;
					continue;
				}
				$tmpFile = null;
				try {
					// Get source file extension
					$ext = FileBackend::extensionFromPath( $path );
					// Create a new temporary file...
					$tmpFile = TempFSFile::factory( 'localcopy_', $ext );
					if ( $tmpFile ) {
						$tmpPath = $tmpFile->getPath();
						$this->_s3->getObject( array(
							'Bucket' => $srcCont,
							'Key'    => $srcRel,
							'SaveAs' => $tmpPath
						) );
					}
				} catch ( NoSuchBucketException $e ) {
					$tmpFile = null;
				} catch ( NoSuchKeyException $e ) {
					$tmpFile = null;
				} catch ( S3Exception $e ) {
					$tmpFile = null;
					$this->handleException( $e, null, __METHOD__, array( 'src' => $path ) + $ep );
				}
				$tmpFiles[$path] = $tmpFile;
			}
		}

		return $tmpFiles;
	}

	/**
	 * @see FileBackendStore::getFileHttpUrl()
	 * @return string|null
	 */
	public function getFileHttpUrl( array $params ) {
		list( $srcCont, $srcRel ) = $this->resolveStoragePathReal( $params['src'] );
		if ( $srcRel === null ) {
			return null; // invalid path
		}

		try {
			$expires = time() + 86400;
			$path = $params['src'];
			$request = $srcCont . '/' . $srcRel;
			$url = $this->_s3->createPresignedUrl( $request, $expires );
			return $url;
		} catch ( NoSuchBucketException $e ) {
		} catch ( S3Exception $e ) { // some other exception?
			$this->handleException( $e, null, __METHOD__, $params );
		}
		return null;
	}

	/**
	 * @see FileBackendStore::directoriesAreVirtual()
	 * @return bool
	 */
	protected function directoriesAreVirtual() {
		return true;
	}

	/**
	 * Log an unexpected exception for this backend.
	 * This also sets the Status object to have a fatal error.
	 *
	 * @param $e Exception
	 * @param $status Status|null
	 * @param $func string
	 * @param $params Array
	 * @return void
	 */
	protected function handleException( Exception $e, $status, $func, array $params ) {
		if ( $status instanceof Status ) {
			$status->fatal( 'backend-fail-internal', $this->name );
		}
		if ( $e->getMessage() ) {
			trigger_error( "$func:" . $e->getMessage(), E_USER_WARNING );
		}
		wfDebugLog( 'S3Backend',
			get_class( $e ) . " in '{$func}' (given '" . FormatJson::encode( $params ) . "')" .
			( $e->getMessage() ? ": {$e->getMessage()}" : "" )
		);
	}
}

abstract class S3FileBackendList implements Iterator {
	/** @var Array */
	protected $bufferIter = array();
	protected $bufferAfter = null; // string; list items *after* this path
	protected $pos = 0; // integer
	/** @var Array */
	protected $params = array();

	/** @var S3FileBackend */
	protected $backend;
	protected $container;
	protected $dir;
	protected $suffixStart;

	const PAGE_SIZE = 9000; // file listing buffer size

	public function __construct( S3FileBackend $backend, $fullCont, $dir, array $params ) {
		$this->backend = $backend;
		$this->container = $fullCont;
		$this->dir = $dir;
		if ( substr( $this->dir, -1 ) === '/' ) {
			$this->dir = substr( $this->dir, 0, -1 ); // remove trailing slash
		}
		if ( $this->dir == '' ) { // whole container
			$this->suffixStart = 0;
		} else { // dir within container
			$this->suffixStart = strlen( $this->dir ) + 1; // size of "path/to/dir/"
		}
		$this->params = $params;
	}

	public function key() {
		return $this->pos;
	}

	/**
	 * @see Iterator::next()
	 * @return void
	 */
	public function next() {
		// Advance to the next file in the page
		next( $this->bufferIter );
		++$this->pos;
		// Check if there are no files left in this page and
		// advance to the next page if this page was not empty.
		if ( !$this->valid() && count( $this->bufferIter ) ) {
			$this->bufferIter = $this->pageFromList(
				$this->container, $this->dir, $this->bufferAfter, self::PAGE_SIZE, $this->params
			); // updates $this->bufferAfter
		}
	}

	/**
	 * @see Iterator::rewind()
	 * @return void
	 */
	public function rewind() {
		$this->pos = 0;
		$this->bufferAfter = null;
		$this->bufferIter = $this->pageFromList(
			$this->container, $this->dir, $this->bufferAfter, self::PAGE_SIZE, $this->params
		); // updates $this->bufferAfter
	}

	/**
	 * @see Iterator::valid()
	 * @return bool
	 */
	public function valid() {
		if ( $this->bufferIter === null ) {
			return false; // some failure?
		} else {
			return ( current( $this->bufferIter ) !== false ); // no paths can have this value
		}
	}

	/**
	 * Get the given list portion (page)
	 *
	 * @param $container string Resolved container name
	 * @param $dir string Resolved path relative to container
	 * @param $after string|null
	 * @param $limit integer
	 * @param $params Array
	 * @return Traversable|Array|null Returns null on failure
	 */
	abstract protected function pageFromList( $container, $dir, &$after, $limit, array $params );
}

class S3FileBackendDirList extends S3FileBackendList {
	/**
	 * @see Iterator::current()
	 * @return string|bool String (relative path) or false
	 */
	public function current() {
		return substr( current( $this->bufferIter ), $this->suffixStart, -1 );
	}

	/**
	 * @see S3FileBackendList::pageFromList()
	 * @return Array|null
	 */
	protected function pageFromList( $container, $dir, &$after, $limit, array $params ) {
		return $this->backend->getDirListPageInternal( $container, $dir, $after, $limit, $params );
	}
}

class S3FileBackendFileList extends S3FileBackendList {
	/**
	 * @see Iterator::current()
	 * @return string|bool String (relative path) or false
	 */
	public function current() {
		return substr( current( $this->bufferIter ), $this->suffixStart );
	}

	/**
	 * @see S3FileBackendList::pageFromList()
	 * @return Array|null
	 */
	protected function pageFromList( $container, $dir, &$after, $limit, array $params ) {
		return $this->backend->getFileListPageInternal( $container, $dir, $after, $limit, $params );
	}
}
