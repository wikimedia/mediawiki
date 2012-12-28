<?php

use WindowsAzure\Blob\Models\ListBlobsOptions;
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
			return false;
		}

		try {
			$this->_blobProxy->listBlobs( $container );
			return true;
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

		// (a) Check the destination object
		if ( empty( $params['overwrite'] ) ) {
			if ( $this->fileExists( array( 'src' => $params['dst'], 'latest' => 1 ) ) ) {
				$status->fatal( 'backend-fail-alreadyexists', $params['dst'] );
				return $status;
			}
		}

		// (b) Actually create the object
		try {
			$this->_blobProxy->createBlockBlob( $dstCont, $dstRel, $params['content'] );
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

		// (a) Check if the destination object already exists
		if ( empty( $params['overwrite'] ) ) {
			if ( $this->fileExists( array( 'src' => $params['dst'], 'latest' => 1 ) ) ) {
				$status->fatal( 'backend-fail-alreadyexists', $params['dst'] );
				return $status;
			}
		}

		// (b) Actually store the object
		try {
			$this->_blobProxy->createBlockBlob( $dstCont, $dstRel, file_get_contents( $params['src'] ) );
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

		// (a) Check if the destination object already exists
		if ( empty( $params['overwrite'] ) ) {
			if ( $this->fileExists( array( 'src' => $params['dst'], 'latest' => 1 ) ) ) {
				$status->fatal( 'backend-fail-alreadyexists', $params['dst'] );
				return $status;
			}
		}

		// (b) Actually copy the file to the destination
		try {
			$this->_blobProxy->copyBlob( $dstCont, $dstRel, $srcCont, $srcRel );
		} catch ( ServiceException $e ) {
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
			$this->_blobProxy->deleteBlob( $srcCont, $srcRel );
		} catch ( ServiceException $e ) {
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
			return false;
		}

		try {
			$blob = $this->_blobProxy->getBlob( $srcCont, $srcRel );

			$timestamp = $blob->getProperties()->getLastModified()->getTimestamp();

			$size = $blob->getProperties()->getContentLength();

			$stat = array(
				'mtime' => wfTimestamp( TS_MW, $timestamp ),
				'size'  => $size
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
	 * @see FileBackendStore::doDirectoryExists()
	 * @return bool|null
	 */
	protected function doDirectoryExists( $fullCont, $dir, array $params ) {
		try {
			$prefix = ( $dir == '' ) ? null : "{$dir}/";

			$options = new ListBlobsOptions();
			$options->setMaxResults( 1 );
			$options->setPrefix( $prefix );

			$listBlobsResult = $this->_blobProxy->listBlobs( $fullCont, $options );

			$objects = array();
			foreach ( $blobs as $blob ) {
				array_push( $objects, $blob->getName() );
			}

			return ( count( $objects ) > 0 );
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

				$listBlobsResult = $this->_blobProxy->listBlobs( $fullCont, $options );

				$blobs = $listBlobsResult->getBlobs();

				foreach ( $blobs as $blob ) {
					array_push( $objects, $blob->getName() );
				}

				foreach ( $objects as $object ) {
					if ( substr( $object, -1 ) !== '/' ) {
						$dirs[] = $object;
					}
				}
			} else {
				$lastDir = $this->getParentDir( $after );

				$listBlobsResult = $this->_blobProxy->listBlobs( $fullCont, $options );

				$blobs = $listBlobsResult->getBlobs();

				foreach ( $blobs as $blob ) {
					array_push( $objects, $blob->getName() );
				}

				foreach ( $objects as $object ) {
					$objectDir = $this->getParentDir( $object );
					if ( $objectDir !== false ) {
						if ( strcmp( $objectDir, $lastDir ) > 0 ) {
							$pDir = $objectDir;
							do {
								$dirs[] = "{$pDir}/";
								$pDir = $this->getParentDir( $pDir );
							} while ( $pDir !== false
								&& strcmp( $pDir, $lastDir ) > 0
								&& strlen( $pDir ) > strlen( $dir )
							);
						}
						$lastDir = $objectDir;
					}
				}
			}

			if ( count( $objects ) < $limit ) {
				$after = INF;
			} else {
				$after = end( $objects );
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

				$listBlobsResult = $this->_blobProxy->listBlobs( $fullCont, $options );

				$blobs = $listBlobsResult->getBlobs();

				foreach ( $blobs as $blob ) {
					array_push( $objects, $blob->getName() );
				}

				foreach ( $objects as $object ) {
					if ( substr( $object, -1 ) !== '/' ) {
						$files[] = $object;
					}
				}
			} else {
				$listBlobsResult = $this->_blobProxy->listBlobs( $fullCont, $options );

				$blobs = $listBlobsResult->getBlobs();

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
	 * @see FileBackendStore::getLocalCopy()
	 * @return null|TempFSFile
	 */
	public function getLocalCopy( array $params ) {
		list( $srcCont, $srcRel ) = $this->resolveStoragePathReal( $params['src'] );
		if ( $srcRel === null ) {
			return null;
		}

		$tmpFile = null;
		try {
			// Get source file extension
			$ext = FileBackend::extensionFromPath( $srcRel );

			// Create a new temporary file
			$tmpFile = TempFSFile::factory( 'localcopy_', $ext );

			if ( $tmpFile ) {
				// Path to the file where to write the data.
				$filename = $tmpFile->getPath();

				// The data to write.
				$data = $this->_blobProxy->getBlob( $srcCont, $srcRel )->getContentStream();

				// Write the data to file.
				file_put_contents( $filename, $data );
			}
		} catch ( ServiceException $e ) {
			$tmpFile = null;
		}

		return $tmpFile;
	}

	/**
	 * @see FileBackendStore::directoriesAreVirtual()
	 * @return bool
	 */
	protected function directoriesAreVirtual() {
		return true;
	}
}

abstract class AzureFileBackendList implements Iterator {
	/** @var array */
	protected $bufferIter = array();

	/** @var string */
	protected $bufferAfter = null;

	/** @var int */
	protected $pos = 0;

	/** @var array */
	protected $params = array();

	/** @var AzureFileBackend */
	protected $backend;

	/** @var string Container name */
	protected $container;

	/** @var Storage directory */
	protected $dir;

	/** @var int */
	protected $suffixStart;

	const PAGE_SIZE = 9000;

	public function __construct( AzureFileBackend $backend, $fullCont, $dir, array $params ) {
		$this->backend = $backend;
		$this->container = $fullCont;
		$this->dir = $dir;
		if ( substr( $this->dir, -1 ) === '/' ) {
			$this->dir = substr( $this->dir, 0, -1 );
		}
		if ( $this->dir == '' ) {
			$this->suffixStart = 0;
		} else {
			$this->suffixStart = strlen( $this->dir ) + 1;
		}
		$this->params = $params;
	}

	/**
	 * @see Iterator::key()
	 * @return int
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
			$this->bufferIter = $this->pageFromList( $this->container, $this->dir, $this->bufferAfter, self::PAGE_SIZE, $this->params );
		}
	}

	/**
	 * @see Iterator::rewind()
	 * @return void
	 */
	public function rewind() {
		$this->pos = 0;
		$this->bufferAfter = null;
		$this->bufferIter = $this->pageFromList( $this->container, $this->dir, $this->bufferAfter, self::PAGE_SIZE, $this->params );
	}

	/**
	 * @see Iterator::valid()
	 * @return bool
	 */
	public function valid() {
		if ( $this->bufferIter === null ) {
			return false;
		} else {
			return ( current( $this->bufferIter ) !== false );
		}
	}

	/**
	 * Get the given list portion (page)
	 *
	 * @param $container string Resolved container name
	 * @param $dir string Resolved path relative to container
	 * @param $after string|null
	 * @param $limit int
	 * @param $params array
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
	 * @return array|null
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
	 * @return array|null
	 */
	public function pageFromList( $container, $dir, &$after, $limit, array $params ) {
		return $this->backend->getFileListPageInternal( $container, $dir, $after, $limit, $params );
	}
}