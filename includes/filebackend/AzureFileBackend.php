<?php
/**
 * @author Aaron Schulz
 * @author Markus Glaser
 * @author Thai Phan
 */

use WindowsAzure\Blob\Models\ContainerACL;
use WindowsAzure\Blob\Models\CreateBlobOptions;
use WindowsAzure\Blob\Models\ListBlobsOptions;
use WindowsAzure\Blob\Models\PublicAccessType;
use WindowsAzure\Common\ServiceException;
use WindowsAzure\Common\ServicesBuilder;

class AzureFileBackend extends FileBackendStore {
	/** @var IBlob */
	private $_blobProxy;

	/** @var string */
	private $_connectionString;

	/**
	 * @see FileBackendStore::__construct()
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );

		// Generate connection string to Windows Azure storage account
		$this->_connectionString = 'DefaultEndpointsProtocol=http;'
					 . 'AccountName=' . $config['azureAccount'] . ';'
					 . 'AccountKey=' . $config['azureKey'];

		$this->_blobProxy = ServicesBuilder::getInstance()->createBlobService( $this->_connectionString );
	}

	/**
	 * Resolve a container name, checking if it's allowed by the backend.
	 *
	 * @see FileBackendStore::resolveContainerName()
	 * @link http://msdn.microsoft.com/en-us/library/windowsazure/dd135715.aspx
	 *
	 * @param $container string Container name
	 * @return string|null
	 */
	protected function resolveContainerName( $container ) {
		// All letters in a container name must be lowercase.
		$container = strtolower( $container );

		// Remove all characters from the container name besides letters,
		// numbers and dashes.
		$container = preg_replace( '#[^a-z0-9\-]#', '', $container );

		// Remove dashes from the start and end of a container name (only
		// letters and numbers are allowed).
		$container = preg_replace( '#^-#', '', $container );
		$container = preg_replace( '#-$#', '', $container );

		// Consecutive dashes are not permitted. There can be only one.
		$container = preg_replace( '#-{2,}#', '-', $container );

		return $container;
	}

	/**
	 * Resolve a relative storage path, checking if it's allowed by the backend.
	 *
	 * @see FileBackendStore::resolveContainerPath()
	 *
	 * @param $container string Container name
	 * @param $relStoragePath string Storage path relative to the container
	 * @return string|null Path or null if not valid
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

		try {
			$this->_blobProxy->getContainerProperties( $container );
			return true; // container exists
		} catch ( ServiceException $e ) {
			return false;
		}
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
			$options = new CreateBlobOptions();
			$options->setMetadata( array( 'sha1base36' => $sha1Hash ) );
			$this->_blobProxy->createBlockBlob( $dstCont, $dstRel, $params['content'], $options );
		} catch ( ServiceException $e ) {
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

		// (a) Get a SHA-1 hash of the object
		$sha1Hash = sha1_file( $params['src'] );
		if ( $sha1Hash === false ) { // source doesn't exist?
			$status->fatal( 'backend-fail-store', $params['src'], $params['dst'] );
			return $status;
		}
		$sha1Hash = wfBaseConvert( $sha1Hash, 16, 36, 31 );

		// (b) Actually store the object
		try {
			$options = new CreateBlobOptions();
			$options->setMetadata( array( 'sha1base36' => $sha1Hash ) );
			$this->_blobProxy->createBlockBlob( $dstCont, $dstRel, file_get_contents( $params['src'] ), $options );
		} catch ( ServiceException $e ) {
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

		try {
			$this->_blobProxy->copyBlob( $dstCont, $dstRel, $srcCont, $srcRel );
		} catch ( ServiceException $e ) {
			switch ( $e->getCode() ) {
				case 404:
					if ( empty( $params['ignoreMissingSource'] ) ) {
						$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
					}
			}
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
			$this->_blobProxy->deleteBlob( $srcCont, $srcRel );
		} catch ( ServiceException $e ) {
			switch ( $e->getCode() ) {
				case 404:
					if ( empty( $params['ignoreMissingSource'] ) ) {
						$status->fatal( 'backend-fail-delete', $params['src'] );
					}
			}
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
			$this->_blobProxy->createContainer( $fullCont );
			if ( !empty( $params['noAccess'] ) ) {
				// Make container private to end-users...
				$status->merge( $this->doSecureInternal( $fullCont, $dir, $params ) );
			} else {
				// Make container public to end-users...
				$status->merge( $this->doPublishInternal( $fullCont, $dir, $params ) );
			}
		} catch ( ServiceException $e ) {

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
			$acl = new ContainerAcl();
			$acl->setPublicAccess( PublicAccessType::NONE );
			$this->_blobProxy->setContainerAcl( $fullCont, $acl );
		} catch ( ServiceException $e ) {

		}

		return $status;
	}

	/**
	 * @see FileBackendStore::doPublishInternal()
	 * @return Status
	 */
	protected function doPublishInternal( $fullCont, $dir, array $params ) {
		$status = Status::newGood();

		// Unrestrict container from end-users...
		try {
			$acl = new ContainerAcl();
			$acl->setPublicAccess( PublicAccessType::CONTAINER_AND_BLOBS );
			$this->_blobProxy->setContainerAcl( $fullCont, $acl );
		} catch ( ServiceException $e ) {

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

		try {
			$this->addMissingMetadata( $srcCont, $srcRel, $params['src'] );

			$properties = $this->_blobProxy->getBlobProperties( $srcCont, $srcRel );

			$timestamp = $properties->getProperties()->getLastModified()->getTimestamp();

			$size = $properties->getProperties()->getContentLength();

			$metadata = $properties->getMetadata();

			$sha1 = $metadata['sha1base36'];

			$stat = array(
				'mtime' => wfTimestamp( TS_MW, $timestamp ),
				'size'  => $size,
				'sha1'  => $sha1
			);
		} catch ( ServiceException $e ) {
			switch ( $e->getCode() ) {
				case 404:
					$stat = false;
					break;

				default:
					$stat = null;
			}
		}

		return $stat;
	}

	/**
	 * Fill in any missing blob metadata and save it to Azure
	 *
	 * @param $srcCont string Container name
	 * @param $srcRel string Blob name
	 * @param $path string Storage path to object
	 * @return bool Success
	 * @throws ServiceException Azure Storage service exception
	 */
	protected function addMissingMetadata( $srcCont, $srcRel, $path ) {
		$metadata = $this->_blobProxy->getBlobMetadata( $srcCont, $srcRel )->getMetadata();
		if ( isset( $metadata['sha1base36'] ) ) {
			return true; // nothing to do
		}
		wfProfileIn( __METHOD__ );
		trigger_error( "$path was not stored with SHA-1 metadata.", E_USER_WARNING );
		$status = Status::newGood();
		$scopeLockS = $this->getScopedFileLocks( array( $path ), LockManager::LOCK_UW, $status );
		if ( $status->isOK() ) {
			$tmpFile = $this->getLocalCopy( array( 'src' => $path, 'latest' => 1 ) );
			if ( $tmpFile ) {
				$hash = $tmpFile->getSha1Base36();
				if ( $hash !== false ) {
					$this->_blobProxy->setBlobMetadata( $srcCont, $srcRel, array( 'sha1base36' => $hash ) );
					wfProfileOut( __METHOD__ );
					return true; // success
				}
			}
		}
		trigger_error( "Unable to set SHA-1 metadata for $path", E_USER_WARNING );
		// Set the SHA-1 metadata to 0 (setting to false doesn't seem to work)
		$this->_blobProxy->setBlobMetadata( $srcCont, $srcRel, array( 'sha1base36' => 0 ) );
		wfProfileOut( __METHOD__ );
		return false; // failed
	}

	/**
	 * @see FileBackendStore::doDirectoryExists()
	 * @return bool|null
	 */
	protected function doDirectoryExists( $fullCont, $dir, array $params ) {
		try {
			$prefix = ( $dir == '' ) ? null : "{$dir}/";

			$options = new ListBlobsOptions();
			$options->setMaxResults( 1 );
			$options->setPrefix( $prefix );

			$blobs = $this->_blobProxy->listBlobs( $fullCont, $options )->getBlobs();

			return ( count( $blobs ) > 0 );
		} catch ( ServiceException $e ) {
			switch ( $e->getCode() ) {
				case 404:
					return false;

				default:
					return null;
			}
		}
	}

	/**
	 * @see FileBackendStore::getDirectoryListInternal()
	 * @return AzureFileBackendDirList
	 */
	public function getDirectoryListInternal( $fullCont, $dir, array $params ) {
		return new AzureFileBackendDirList( $this, $fullCont, $dir, $params );
	}

	/**
	 * @see FileBackendStore::getFileListInternal()
	 * @return AzureFileBackendFileList
	 */
	public function getFileListInternal( $fullCont, $dir, array $params ) {
		return new AzureFileBackendFileList( $this, $fullCont, $dir, $params );
	}

	public function getDirListPageInternal( $fullCont, $dir, &$after, $limit, array $params ) {
		$dirs = array();
		if ( $after === INF ) {
			return $dirs;
		}
		wfProfileIn( __METHOD__ . '-' . $this->name );

		try {
			$prefix = ( $dir == '' ) ? null : "{$dir}/";

			$options = new ListBlobsOptions();
			$options->setMaxResults( $limit );
			$options->setMarker( $after );
			$options->setPrefix( $prefix );

			$objects = array();

			if ( !empty( $params['topOnly'] ) ) {
				$options->setDelimiter( '/' );

				// Blobs are listed in alphabetical order in the response body, with
				// upper-case letters listed first.
				$blobs = $this->_blobProxy->listBlobs( $fullCont, $options )->getBlobs();

				foreach ( $blobs as $blob ) {
					array_push( $objects, $blob->getName() );
				}

				foreach ( $objects as $object ) {
					if ( substr( $object, -1 ) !== '/' ) {
						$dirs[] = $object;
					}
				}
			} else {
				// Get directory from last item of prior page
				$lastDir = $this->getParentDir( $after ); // must be first page
				$blobs = $this->_blobProxy->listBlobs( $fullCont, $options )->getBlobs();

				// Generate an array of blob names
				foreach ( $blobs as $blob ) {
					array_push( $objects, $blob->getName() );
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
		} catch ( ServiceException $e ) {

		}

		wfProfileOut( __METHOD__ . '-' . $this->name );
		return $dirs;
	}

	protected function getParentDir( $path ) {
		return ( strpos( $path, '/' ) !== false ) ? dirname( $path ) : false;
	}

	/**
	 * Do not call this function outside of AzureFileBackendFileList
	 *
	 * @return array List of relative paths of files under $dir
	 */
	public function getFileListPageInternal( $fullCont, $dir, &$after, $limit, array $params ) {
		$files = array();
		if ( $after === INF ) {
			return $files;
		}
		wfProfileIn( __METHOD__ . '-' . $this->name );

		try {
			$prefix = ( $dir == '' ) ? null : "{$dir}/";

			$options = new ListBlobsOptions();
			$options->setMaxResults( $limit );
			$options->setMarker( $after );
			$options->setPrefix( $prefix );

			$objects = array();

			if ( !empty( $params['topOnly'] ) ) {
				$options->setDelimiter( '/' );

				$blobs = $this->_blobProxy->listBlobs( $fullCont, $options )->getBlobs();

				foreach ( $blobs as $blob ) {
					array_push( $objects, $blob->getName() );
				}

				foreach ( $objects as $object ) {
					if ( substr( $object, -1 ) !== '/' ) {
						$files[] = $object;
					}
				}
			} else {
				$blobs = $this->_blobProxy->listBlobs( $fullCont, $options )->getBlobs();

				foreach ( $blobs as $blob ) {
					array_push( $objects, $blob->getName() );
				}

				$files = $objects;
			}

			if ( count( $objects ) < $limit ) {
				$after = INF;
			} else {
				$after = end( $objects );
			}
		} catch ( ServiceException $e ) {

		}

		wfProfileOut( __METHOD__ . '-' . $this->name );
		return $files;
	}

	/**
	 * @see FileBackendStore::doGetFileSha1base36()
	 * @return bool
	 */
	protected function doGetFileSha1base36( array $params ) {
		$stat = $this->getFileStat( $params );
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
			$contents = $this->_blobProxy->getBlob( $srcCont, $srcRel )->getContentStream();
			file_put_contents( 'php://output', $contents );
		} catch ( ServiceException $e ) {
			$status->fatal( 'backend-fail-stream', $params['src'] );
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::doGetLocalCopyMulti()
	 * @return null|TempFSFile
	 */
	protected function doGetLocalCopyMulti( array $params ) {
		$tmpFiles = array();

		$ep = array_diff_key( $params, array( 'srcs' => 1 ) ); // for error logging
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
						$contents = $this->_blobProxy->getBlob( $srcCont, $srcRel )->getContentStream();
						file_put_contents( $tmpPath, $contents );
					}
				} catch ( ServiceException $e ) {
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

/*
 * AzureFileBackend helper class to page through listsings.
 * Do not use this class from places outside AzureFileBackend.
 *
 * @ingroup FileBackend
 */
abstract class AzureFileBackendList implements Iterator {
	/** @var Array */
	protected $bufferIter = array();
	protected $bufferAfter = null; // string; list items *after* this path
	protected $pos = 0; // integer
	/** @var Array */
	protected $params = array();

	/** @var AzureFileBackend */
	protected $backend;
	protected $container; // string; container name
	protected $dir; // string; storage directory
	protected $suffixStart; // integer

	const PAGE_SIZE = 9000; // file listing buffer size

	/**
	 * @param $backend AzureFileBackend
	 * @param $fullCont string Resolved container name
	 * @param $dir string Resolved directory relative to container
	 * @param $params Array
	 */
	public function __construct( AzureFileBackend $backend, $fullCont, $dir, array $params ) {
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

	/**
	 * @see Iterator::key()
	 * @return integer
	 */
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
	 * @return Traversable|array|null Returns null on failure
	 */
	abstract protected function pageFromList( $container, $dir, &$after, $limit, array $params );
}

/**
 * Iterator for listing directories
 */
class AzureFileBackendDirList extends AzureFileBackendList {
	/**
	 * @see Iterator::current()
	 * @return string|bool String (relative path) or false
	 */
	public function current() {
		return substr( current( $this->bufferIter ), $this->suffixStart, -1 );
	}

	/**
	 * @see AzureFileBackendList::pageFromList()
	 * @return Array|null
	 */
	public function pageFromList( $container, $dir, &$after, $limit, array $params ) {
		return $this->backend->getDirListPageInternal( $container, $dir, $after, $limit, $params );
	}
}

/**
 * Iterator for listing regular files
 */
class AzureFileBackendFileList extends AzureFileBackendList {
	/**
	 * @see Iterator::current()
	 * @return string|bool String (relative path) or false
	 */
	public function current() {
		return substr( current( $this->bufferIter ), $this->suffixStart );
	}

	/**
	 * @see AzureFileBackendList::pageFromList()
	 * @return Array|null
	 */
	public function pageFromList( $container, $dir, &$after, $limit, array $params ) {
		return $this->backend->getFileListPageInternal( $container, $dir, $after, $limit, $params );
	}
}
