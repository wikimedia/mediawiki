<?php

use Aws\Common\Aws;
use Aws\S3\Enum\CannedAcl;
use Aws\S3\Exception\NoSuchBucketException;
use Aws\S3\Exception\NoSuchKeyException;
use Aws\S3\Exception\S3Exception;

class S3FileBackend extends FileBackendStore {
	private $_aws;

	private $_s3;

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

		// (a) Check the destination object
		if ( empty( $params['overwrite'] ) &&
			$this->fileExists( array( 'src' => $params['dst'], 'latest' => 1 ) ) )
		{
			$status->fatal( 'backend-fail-alreadyexists', $params['dst'] );
			return $status;
		}

		// (b) Actually create the object
		try {
			$this->_s3->putObject( array(
				'Bucket' => $dstCont,
				'Key'    => $dstRel,
				'Body'   => $params['content'],
				'ACL'    => CannedAcl::PUBLIC_READ
			) );
		} catch ( S3Exception $e ) {
			$status->fatal( 'backend-fail-create', $params['dst'] );
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

		// (a) Check the destination object
		if ( empty( $params['overwrite'] ) &&
			$this->fileExists( array( 'src' => $params['dst'], 'latest' => 1 ) ) )
		{
			$status->fatal( 'backend-fail-alreadyexists', $params['dst'] );
			return $status;
		}

		// (b) Actually store the object
		try {
			$this->_s3->putObject( array(
				'Bucket' => $dstCont,
				'Key'    => $dstRel,
				'Body'   => file_get_contents( $params['src'] ),
				'ACL'    => CannedAcl::PUBLIC_READ
			) );
		} catch ( S3Exception $e ) {
			$status->fatal( 'backend-fail-store', $params['src'], $params['dst'] );
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

		// (a) Check the destination object
		if ( empty( $params['overwrite'] ) &&
				$this->fileExists( array( 'src' => $params['dst'], 'latest' => 1 ) ) )
		{
			$status->fatal( 'backend-fail-alreadyexists', $params['dst'] );
			return $status;
		}

		// (b) Actually copy the file to the destination
		try {
			$this->_s3->copyObject( array(
				'Bucket'     => $dstCont,
				'Key'        => $dstRel,
				'CopySource' => $srcCont . '/' . $srcRel
			) );
		} catch ( S3Exception $e ) {
			$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
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
		} catch ( S3Exception $e ) {
			if ( empty( $params['ignoreMissingSource'] ) ) {
				$status->fatal( 'backend-fail-delete', $params['src'] );
			}
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

			$stat = array(
				'mtime' => wfTimestamp( TS_MW, $timestamp ),
				'size'  => $size
			);
		} catch ( NoSuchBucketException $e ) {
		} catch ( NoSuchKeyException $e ) {
		} catch ( S3Exception $e ) {
			$stat = null;
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
		} catch ( S3Exception $e ) {
			return null;
		}
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
					if ( $objectDir !== false ) { // file has a parent dir
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
		} catch ( S3Exception $e ) {

		}

		wfProfileOut( __METHOD__ . '-' . $this->name );
		return $dirs;
	}

	protected function getParentDir( $path ) {
		return ( strpos( $path, '/' ) !== false ) ? dirname( $path ) : false;
	}

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

		} catch ( S3Exception $e ) {

		}

		wfProfileOut( __METHOD__ . '-' . $this->name );
		return $files;
	}

	/**
	 * @see FileBackendStore::doGetLocalCopyMulti()
	 * @return null|TempFSFile
	 */
	protected function doGetLocalCopyMulti( array $params ) {
		$tmpFiles = array();

		$ep = array_diff_key($params, array('src' => 1)); // for error logging
		// Blindly create tmp files and stream to them, catching any exception if the file does
		// not exist. Doing a stat here is useless causes infinite loops in addMissingMetadata().
		foreach ( array_chunk( $params['srcs'], $params['concurrency'] ) as $pathBatch ) {
			foreach ($pathBatch as $path) { // each path in this concurrent batch
				list( $srcCont, $srcRel ) = $this->resolveStoragePathReal( $path );
				if ( $srcRel === null ) {
					$tmpFiles[$path] = null;
					continue;
				}

				$tmpFile = null;
				try {
					// Get source file extension
					$ext = FileBackend::extensionFromPath($path);

					// Create a new temporary file...
					$tmpFile = TempFSFile::factory('localcopy_', $ext);

					if ($tmpFile) {
						$tmpPath = $tmpFile->getPath();

						$this->_s3->getObject( array(
							'Bucket' => $srcCont,
							'Key' => $srcRel,
							'SaveAs' => $tmpPath
						) );
					}
				} catch (S3Exception $e) {
					$tmpFile = null;
				}
				$tmpFiles[$path] = $tmpFile;
			}
		}

		return $tmpFiles;
	}

	/**
	 * @see FileBackendStore::directoriesAreVirtual()
	 * @return bool
	 */
	protected function directoriesAreVirtual() {
		return true;
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
