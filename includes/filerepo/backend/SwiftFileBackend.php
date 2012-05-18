<?php
/**
 * OpenStack Swift based file backend.
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
 * @author Russ Nelson
 * @author Aaron Schulz
 */

/**
 * @brief Class for an OpenStack Swift based file backend.
 *
 * This requires the SwiftCloudFiles MediaWiki extension, which includes
 * the php-cloudfiles library (https://github.com/rackspace/php-cloudfiles).
 * php-cloudfiles requires the curl, fileinfo, and mb_string PHP extensions.
 *
 * Status messages should avoid mentioning the Swift account name.
 * Likewise, error suppression should be used to avoid path disclosure.
 *
 * @ingroup FileBackend
 * @since 1.19
 */
class SwiftFileBackend extends FileBackendStore {
	/** @var CF_Authentication */
	protected $auth; // Swift authentication handler
	protected $authTTL; // integer seconds
	protected $swiftAnonUser; // string; username to handle unauthenticated requests
	protected $maxContCacheSize = 300; // integer; max containers with entries

	/** @var CF_Connection */
	protected $conn; // Swift connection handle
	protected $connStarted = 0; // integer UNIX timestamp
	protected $connContainers = array(); // container object cache

	/**
	 * @see FileBackendStore::__construct()
	 * Additional $config params include:
	 *    swiftAuthUrl       : Swift authentication server URL
	 *    swiftUser          : Swift user used by MediaWiki (account:username)
	 *    swiftKey           : Swift authentication key for the above user
	 *    swiftAuthTTL       : Swift authentication TTL (seconds)
	 *    swiftAnonUser      : Swift user used for end-user requests (account:username)
	 *    shardViaHashLevels : Map of container names to sharding config with:
	 *                         'base'   : base of hash characters, 16 or 36
	 *                         'levels' : the number of hash levels (and digits)
	 *                         'repeat' : hash subdirectories are prefixed with all the
	 *                                    parent hash directory names (e.g. "a/ab/abc")
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );
		if ( !MWInit::classExists( 'CF_Constants' ) ) {
			throw new MWException( 'SwiftCloudFiles extension not installed.' );
		}
		// Required settings
		$this->auth = new CF_Authentication(
			$config['swiftUser'],
			$config['swiftKey'],
			null, // account; unused
			$config['swiftAuthUrl']
		);
		// Optional settings
		$this->authTTL = isset( $config['swiftAuthTTL'] )
			? $config['swiftAuthTTL']
			: 5 * 60; // some sane number
		$this->swiftAnonUser = isset( $config['swiftAnonUser'] )
			? $config['swiftAnonUser']
			: '';
		$this->shardViaHashLevels = isset( $config['shardViaHashLevels'] )
			? $config['shardViaHashLevels']
			: '';
		// Cache container info to mask latency
		$this->memCache = wfGetMainCache();
	}

	/**
	 * @see FileBackendStore::resolveContainerPath()
	 * @return null
	 */
	protected function resolveContainerPath( $container, $relStoragePath ) {
		if ( strlen( urlencode( $relStoragePath ) ) > 1024 ) {
			return null; // too long for Swift
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
			$this->getContainer( $container );
			return true; // container exists
		} catch ( NoSuchContainerException $e ) {
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, null, __METHOD__, array( 'path' => $storagePath ) );
		}

		return false;
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

		// (a) Check the destination container and object
		try {
			$dContObj = $this->getContainer( $dstCont );
			if ( empty( $params['overwrite'] ) &&
				$this->fileExists( array( 'src' => $params['dst'], 'latest' => 1 ) ) )
			{
				$status->fatal( 'backend-fail-alreadyexists', $params['dst'] );
				return $status;
			}
		} catch ( NoSuchContainerException $e ) {
			$status->fatal( 'backend-fail-create', $params['dst'] );
			return $status;
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, $status, __METHOD__, $params );
			return $status;
		}

		// (b) Get a SHA-1 hash of the object
		$sha1Hash = wfBaseConvert( sha1( $params['content'] ), 16, 36, 31 );

		// (c) Actually create the object
		try {
			// Create a fresh CF_Object with no fields preloaded.
			// We don't want to preserve headers, metadata, and such.
			$obj = new CF_Object( $dContObj, $dstRel, false, false ); // skip HEAD
			// Note: metadata keys stored as [Upper case char][[Lower case char]...]
			$obj->metadata = array( 'Sha1base36' => $sha1Hash );
			// Manually set the ETag (https://github.com/rackspace/php-cloudfiles/issues/59).
			// The MD5 here will be checked within Swift against its own MD5.
			$obj->set_etag( md5( $params['content'] ) );
			// Use the same content type as StreamFile for security
			$obj->content_type = StreamFile::contentTypeFromPath( $params['dst'] );
			if ( !empty( $params['async'] ) ) { // deferred
				$handle = $obj->write_async( $params['content'] );
				$status->value = new SwiftFileOpHandle( $this, $params, 'Create', $handle );
			} else { // actually write the object in Swift
				$obj->write( $params['content'] );
			}
		} catch ( BadContentTypeException $e ) {
			$status->fatal( 'backend-fail-contenttype', $params['dst'] );
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, $status, __METHOD__, $params );
		}

		return $status;
	}

	/**
	 * @see SwiftFileBackend::doExecuteOpHandlesInternal()
	 */
	protected function _getResponseCreate( CF_Async_Op $cfOp, Status $status, array $params ) {
		try {
			$cfOp->getLastResponse();
		} catch ( BadContentTypeException $e ) {
			$status->fatal( 'backend-fail-contenttype', $params['dst'] );
		}
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

		// (a) Check the destination container and object
		try {
			$dContObj = $this->getContainer( $dstCont );
			if ( empty( $params['overwrite'] ) &&
				$this->fileExists( array( 'src' => $params['dst'], 'latest' => 1 ) ) )
			{
				$status->fatal( 'backend-fail-alreadyexists', $params['dst'] );
				return $status;
			}
		} catch ( NoSuchContainerException $e ) {
			$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
			return $status;
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, $status, __METHOD__, $params );
			return $status;
		}

		// (b) Get a SHA-1 hash of the object
		$sha1Hash = sha1_file( $params['src'] );
		if ( $sha1Hash === false ) { // source doesn't exist?
			$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
			return $status;
		}
		$sha1Hash = wfBaseConvert( $sha1Hash, 16, 36, 31 );

		// (c) Actually store the object
		try {
			// Create a fresh CF_Object with no fields preloaded.
			// We don't want to preserve headers, metadata, and such.
			$obj = new CF_Object( $dContObj, $dstRel, false, false ); // skip HEAD
			// Note: metadata keys stored as [Upper case char][[Lower case char]...]
			$obj->metadata = array( 'Sha1base36' => $sha1Hash );
			// The MD5 here will be checked within Swift against its own MD5.
			$obj->set_etag( md5_file( $params['src'] ) );
			// Use the same content type as StreamFile for security
			$obj->content_type = StreamFile::contentTypeFromPath( $params['dst'] );
			if ( !empty( $params['async'] ) ) { // deferred
				wfSuppressWarnings();
				$fp = fopen( $params['src'], 'rb' );
				wfRestoreWarnings();
				if ( !$fp ) {
					$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
				} else {
					$handle = $obj->write_async( $fp, filesize( $params['src'] ), true );
					$status->value = new SwiftFileOpHandle( $this, $params, 'Store', $handle );
					$status->value->resourcesToClose[] = $fp;
				}
			} else { // actually write the object in Swift
				$obj->load_from_filename( $params['src'], true ); // calls $obj->write()
			}
		} catch ( BadContentTypeException $e ) {
			$status->fatal( 'backend-fail-contenttype', $params['dst'] );
		} catch ( IOException $e ) {
			$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, $status, __METHOD__, $params );
		}

		return $status;
	}

	/**
	 * @see SwiftFileBackend::doExecuteOpHandlesInternal()
	 */
	protected function _getResponseStore( CF_Async_Op $cfOp, Status $status, array $params ) {
		try {
			$cfOp->getLastResponse();
		} catch ( BadContentTypeException $e ) {
			$status->fatal( 'backend-fail-contenttype', $params['dst'] );
		} catch ( IOException $e ) {
			$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
		}
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

		// (a) Check the source/destination containers and destination object
		try {
			$sContObj = $this->getContainer( $srcCont );
			$dContObj = $this->getContainer( $dstCont );
			if ( empty( $params['overwrite'] ) &&
				$this->fileExists( array( 'src' => $params['dst'], 'latest' => 1 ) ) )
			{
				$status->fatal( 'backend-fail-alreadyexists', $params['dst'] );
				return $status;
			}
		} catch ( NoSuchContainerException $e ) {
			$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
			return $status;
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, $status, __METHOD__, $params );
			return $status;
		}

		// (b) Actually copy the file to the destination
		try {
			if ( !empty( $params['async'] ) ) { // deferred
				$handle = $sContObj->copy_object_to_async( $srcRel, $dContObj, $dstRel );
				$status->value = new SwiftFileOpHandle( $this, $params, 'Copy', $handle );
			} else { // actually write the object in Swift
				$sContObj->copy_object_to( $srcRel, $dContObj, $dstRel );
			}
		} catch ( NoSuchObjectException $e ) { // source object does not exist
			$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, $status, __METHOD__, $params );
		}

		return $status;
	}

	/**
	 * @see SwiftFileBackend::doExecuteOpHandlesInternal()
	 */
	protected function _getResponseCopy( CF_Async_Op $cfOp, Status $status, array $params ) {
		try {
			$cfOp->getLastResponse();
		} catch ( NoSuchObjectException $e ) { // source object does not exist
			$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
		}
	}

	/**
	 * @see FileBackendStore::doMoveInternal()
	 * @return Status
	 */
	protected function doMoveInternal( array $params ) {
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

		// (a) Check the source/destination containers and destination object
		try {
			$sContObj = $this->getContainer( $srcCont );
			$dContObj = $this->getContainer( $dstCont );
			if ( empty( $params['overwrite'] ) &&
				$this->fileExists( array( 'src' => $params['dst'], 'latest' => 1 ) ) )
			{
				$status->fatal( 'backend-fail-alreadyexists', $params['dst'] );
				return $status;
			}
		} catch ( NoSuchContainerException $e ) {
			$status->fatal( 'backend-fail-move', $params['src'], $params['dst'] );
			return $status;
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, $status, __METHOD__, $params );
			return $status;
		}

		// (b) Actually move the file to the destination
		try {
			if ( !empty( $params['async'] ) ) { // deferred
				$handle = $sContObj->move_object_to_async( $srcRel, $dContObj, $dstRel );
				$status->value = new SwiftFileOpHandle( $this, $params, 'Move', $handle );
			} else { // actually write the object in Swift
				$sContObj->move_object_to( $srcRel, $dContObj, $dstRel );
			}
		} catch ( NoSuchObjectException $e ) { // source object does not exist
			$status->fatal( 'backend-fail-move', $params['src'], $params['dst'] );
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, $status, __METHOD__, $params );
		}

		return $status;
	}

	/**
	 * @see SwiftFileBackend::doExecuteOpHandlesInternal()
	 */
	protected function _getResponseMove( CF_Async_Op $cfOp, Status $status, array $params ) {
		try {
			$cfOp->getLastResponse();
		} catch ( NoSuchObjectException $e ) { // source object does not exist
			$status->fatal( 'backend-fail-move', $params['src'], $params['dst'] );
		}
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
			$sContObj = $this->getContainer( $srcCont );
			if ( !empty( $params['async'] ) ) { // deferred
				$handle = $sContObj->delete_object_async( $srcRel );
				$status->value = new SwiftFileOpHandle( $this, $params, 'Delete', $handle );
			} else { // actually write the object in Swift
				$sContObj->delete_object( $srcRel );
			}
		} catch ( NoSuchContainerException $e ) {
			$status->fatal( 'backend-fail-delete', $params['src'] );
		} catch ( NoSuchObjectException $e ) {
			if ( empty( $params['ignoreMissingSource'] ) ) {
				$status->fatal( 'backend-fail-delete', $params['src'] );
			}
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, $status, __METHOD__, $params );
		}

		return $status;
	}

	/**
	 * @see SwiftFileBackend::doExecuteOpHandlesInternal()
	 */
	protected function _getResponseDelete( CF_Async_Op $cfOp, Status $status, array $params ) {
		try {
			$cfOp->getLastResponse();
		} catch ( NoSuchContainerException $e ) {
			$status->fatal( 'backend-fail-delete', $params['src'] );
		} catch ( NoSuchObjectException $e ) {
			if ( empty( $params['ignoreMissingSource'] ) ) {
				$status->fatal( 'backend-fail-delete', $params['src'] );
			}
		}
	}

	/**
	 * @see FileBackendStore::doPrepareInternal()
	 * @return Status
	 */
	protected function doPrepareInternal( $fullCont, $dir, array $params ) {
		$status = Status::newGood();

		// (a) Check if container already exists
		try {
			$contObj = $this->getContainer( $fullCont );
			// NoSuchContainerException not thrown: container must exist
			return $status; // already exists
		} catch ( NoSuchContainerException $e ) {
			// NoSuchContainerException thrown: container does not exist
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, $status, __METHOD__, $params );
			return $status;
		}

		// (b) Create container as needed
		try {
			$contObj = $this->createContainer( $fullCont );
			if ( $this->swiftAnonUser != '' ) {
				// Make container public to end-users...
				$status->merge( $this->setContainerAccess(
					$contObj,
					array( $this->auth->username, $this->swiftAnonUser ), // read
					array( $this->auth->username ) // write
				) );
			}
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, $status, __METHOD__, $params );
			return $status;
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::doSecureInternal()
	 * @return Status
	 */
	protected function doSecureInternal( $fullCont, $dir, array $params ) {
		$status = Status::newGood();

		if ( $this->swiftAnonUser != '' ) {
			// Restrict container from end-users...
			try {
				// doPrepareInternal() should have been called,
				// so the Swift container should already exist...
				$contObj = $this->getContainer( $fullCont ); // normally a cache hit
				// NoSuchContainerException not thrown: container must exist
				if ( !isset( $contObj->mw_wasSecured ) ) {
					$status->merge( $this->setContainerAccess(
						$contObj,
						array( $this->auth->username ), // read
						array( $this->auth->username ) // write
					) );
					// @TODO: when php-cloudfiles supports container
					// metadata, we can make use of that to avoid RTTs
					$contObj->mw_wasSecured = true; // avoid useless RTTs
				}
			} catch ( CloudFilesException $e ) { // some other exception?
				$this->handleException( $e, $status, __METHOD__, $params );
			}
		}

		return $status;
	}

	/**
	 * @see FileBackendStore::doCleanInternal()
	 * @return Status
	 */
	protected function doCleanInternal( $fullCont, $dir, array $params ) {
		$status = Status::newGood();

		// Only containers themselves can be removed, all else is virtual
		if ( $dir != '' ) {
			return $status; // nothing to do
		}

		// (a) Check the container
		try {
			$contObj = $this->getContainer( $fullCont, true );
		} catch ( NoSuchContainerException $e ) {
			return $status; // ok, nothing to do
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, $status, __METHOD__, $params );
			return $status;
		}

		// (b) Delete the container if empty
		if ( $contObj->object_count == 0 ) {
			try {
				$this->deleteContainer( $fullCont );
			} catch ( NoSuchContainerException $e ) {
				return $status; // race?
			} catch ( NonEmptyContainerException $e ) {
				return $status; // race? consistency delay?
			} catch ( CloudFilesException $e ) { // some other exception?
				$this->handleException( $e, $status, __METHOD__, $params );
				return $status;
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
			$contObj = $this->getContainer( $srcCont );
			$srcObj = $contObj->get_object( $srcRel, $this->headersFromParams( $params ) );
			$this->addMissingMetadata( $srcObj, $params['src'] );
			$stat = array(
				// Convert dates like "Tue, 03 Jan 2012 22:01:04 GMT" to TS_MW
				'mtime' => wfTimestamp( TS_MW, $srcObj->last_modified ),
				'size'  => $srcObj->content_length,
				'sha1'  => $srcObj->metadata['Sha1base36']
			);
		} catch ( NoSuchContainerException $e ) {
		} catch ( NoSuchObjectException $e ) {
		} catch ( CloudFilesException $e ) { // some other exception?
			$stat = null;
			$this->handleException( $e, null, __METHOD__, $params );
		}

		return $stat;
	}

	/**
	 * Fill in any missing object metadata and save it to Swift
	 *
	 * @param $obj CF_Object
	 * @param $path string Storage path to object
	 * @return bool Success
	 * @throws Exception cloudfiles exceptions
	 */
	protected function addMissingMetadata( CF_Object $obj, $path ) {
		if ( isset( $obj->metadata['Sha1base36'] ) ) {
			return true; // nothing to do
		}
		$status = Status::newGood();
		$scopeLockS = $this->getScopedFileLocks( array( $path ), LockManager::LOCK_UW, $status );
		if ( $status->isOK() ) {
			# Do not stat the file in getLocalCopy() to avoid infinite loops
			$tmpFile = $this->getLocalCopy( array( 'src' => $path, 'latest' => 1, 'nostat' => 1 ) );
			if ( $tmpFile ) {
				$hash = $tmpFile->getSha1Base36();
				if ( $hash !== false ) {
					$obj->metadata['Sha1base36'] = $hash;
					$obj->sync_metadata(); // save to Swift
					return true; // success
				}
			}
		}
		$obj->metadata['Sha1base36'] = false;
		return false; // failed
	}

	/**
	 * @see FileBackend::getFileContents()
	 * @return bool|null|string
	 */
	public function getFileContents( array $params ) {
		list( $srcCont, $srcRel ) = $this->resolveStoragePathReal( $params['src'] );
		if ( $srcRel === null ) {
			return false; // invalid storage path
		}

		if ( !$this->fileExists( $params ) ) {
			return null;
		}

		$data = false;
		try {
			$sContObj = $this->getContainer( $srcCont );
			$obj = new CF_Object( $sContObj, $srcRel, false, false ); // skip HEAD request
			$data = $obj->read( $this->headersFromParams( $params ) );
		} catch ( NoSuchContainerException $e ) {
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, null, __METHOD__, $params );
		}

		return $data;
	}

	/**
	 * @see FileBackendStore::doDirectoryExists()
	 * @return bool|null
	 */
	protected function doDirectoryExists( $fullCont, $dir, array $params ) {
		try {
			$container = $this->getContainer( $fullCont );
			$prefix = ( $dir == '' ) ? null : "{$dir}/";
			return ( count( $container->list_objects( 1, null, $prefix ) ) > 0 );
		} catch ( NoSuchContainerException $e ) {
			return false;
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, null, __METHOD__,
				array( 'cont' => $fullCont, 'dir' => $dir ) );
		}

		return null; // error
	}

	/**
	 * @see FileBackendStore::getDirectoryListInternal()
	 * @return SwiftFileBackendDirList
	 */
	public function getDirectoryListInternal( $fullCont, $dir, array $params ) {
		return new SwiftFileBackendDirList( $this, $fullCont, $dir, $params );
	}

	/**
	 * @see FileBackendStore::getFileListInternal()
	 * @return SwiftFileBackendFileList
	 */
	public function getFileListInternal( $fullCont, $dir, array $params ) {
		return new SwiftFileBackendFileList( $this, $fullCont, $dir, $params );
	}

	/**
	 * Do not call this function outside of SwiftFileBackendFileList
	 *
	 * @param $fullCont string Resolved container name
	 * @param $dir string Resolved storage directory with no trailing slash
	 * @param $after string|null Storage path of file to list items after
	 * @param $limit integer Max number of items to list
	 * @param $params Array Includes flag for 'topOnly'
	 * @return Array List of relative paths of dirs directly under $dir
	 */
	public function getDirListPageInternal( $fullCont, $dir, &$after, $limit, array $params ) {
		$dirs = array();

		try {
			$container = $this->getContainer( $fullCont );
			$prefix = ( $dir == '' ) ? null : "{$dir}/";
			// Non-recursive: only list dirs right under $dir
			if ( !empty( $params['topOnly'] ) ) {
				$objects = $container->list_objects( $limit, $after, $prefix, null, '/' );
				foreach ( $objects as $object ) { // files and dirs
					if ( substr( $object, -1 ) === '/' ) {
						$dirs[] = $object; // directories end in '/'
					}
					$after = $object; // update last item
				}
			// Recursive: list all dirs under $dir and its subdirs
			} else {
				// Get directory from last item of prior page
				$lastDir = $this->getParentDir( $after ); // must be first page
				$objects = $container->list_objects( $limit, $after, $prefix );
				foreach ( $objects as $object ) { // files
					$objectDir = $this->getParentDir( $object ); // directory of object
					if ( $objectDir !== false ) { // file has a parent dir
						// Swift stores paths in UTF-8, using binary sorting.
						// See function "create_container_table" in common/db.py.
						// If a directory is not "greater" than the last one,
						// then it was already listed by the calling iterator.
						if ( $objectDir > $lastDir ) {
							$pDir = $objectDir;
							do { // add dir and all its parent dirs
								$dirs[] = "{$pDir}/";
								$pDir = $this->getParentDir( $pDir );
							} while ( $pDir !== false // sanity
								&& $pDir > $lastDir // not done already
								&& strlen( $pDir ) > strlen( $dir ) // within $dir
							);
						}
						$lastDir = $objectDir;
					}
					$after = $object; // update last item
				}
			}
		} catch ( NoSuchContainerException $e ) {
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, null, __METHOD__,
				array( 'cont' => $fullCont, 'dir' => $dir ) );
		}

		return $dirs;
	}

	protected function getParentDir( $path ) {
		return ( strpos( $path, '/' ) !== false ) ? dirname( $path ) : false;
	}

	/**
	 * Do not call this function outside of SwiftFileBackendFileList
	 *
	 * @param $fullCont string Resolved container name
	 * @param $dir string Resolved storage directory with no trailing slash
	 * @param $after string|null Storage path of file to list items after
	 * @param $limit integer Max number of items to list
	 * @param $params Array Includes flag for 'topOnly'
	 * @return Array List of relative paths of files under $dir
	 */
	public function getFileListPageInternal( $fullCont, $dir, &$after, $limit, array $params ) {
		$files = array();

		try {
			$container = $this->getContainer( $fullCont );
			$prefix = ( $dir == '' ) ? null : "{$dir}/";
			// Non-recursive: only list files right under $dir
			if ( !empty( $params['topOnly'] ) ) { // files and dirs
				$objects = $container->list_objects( $limit, $after, $prefix, null, '/' );
				foreach ( $objects as $object ) {
					if ( substr( $object, -1 ) !== '/' ) {
						$files[] = $object; // directories end in '/'
					}
				}
			// Recursive: list all files under $dir and its subdirs
			} else { // files
				$files = $container->list_objects( $limit, $after, $prefix );
			}
			$after = end( $files ); // update last item
			reset( $files ); // reset pointer
		} catch ( NoSuchContainerException $e ) {
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, null, __METHOD__,
				array( 'cont' => $fullCont, 'dir' => $dir ) );
		}

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
			$cont = $this->getContainer( $srcCont );
		} catch ( NoSuchContainerException $e ) {
			$status->fatal( 'backend-fail-stream', $params['src'] );
			return $status;
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, $status, __METHOD__, $params );
			return $status;
		}

		try {
			$output = fopen( 'php://output', 'wb' );
			$obj = new CF_Object( $cont, $srcRel, false, false ); // skip HEAD request
			$obj->stream( $output, $this->headersFromParams( $params ) );
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, $status, __METHOD__, $params );
		}

		return $status;
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

		# Check the recursion guard to avoid loops when filling metadata
		if ( empty( $params['nostat'] ) && !$this->fileExists( $params ) ) {
			return null;
		}

		$tmpFile = null;
		try {
			$sContObj = $this->getContainer( $srcCont );
			$obj = new CF_Object( $sContObj, $srcRel, false, false ); // skip HEAD
			// Get source file extension
			$ext = FileBackend::extensionFromPath( $srcRel );
			// Create a new temporary file...
			$tmpFile = TempFSFile::factory( wfBaseName( $srcRel ) . '_', $ext );
			if ( $tmpFile ) {
				$handle = fopen( $tmpFile->getPath(), 'wb' );
				if ( $handle ) {
					$obj->stream( $handle, $this->headersFromParams( $params ) );
					fclose( $handle );
				} else {
					$tmpFile = null; // couldn't open temp file
				}
			}
		} catch ( NoSuchContainerException $e ) {
			$tmpFile = null;
		} catch ( CloudFilesException $e ) { // some other exception?
			$tmpFile = null;
			$this->handleException( $e, null, __METHOD__, $params );
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

	/**
	 * Get headers to send to Swift when reading a file based
	 * on a FileBackend params array, e.g. that of getLocalCopy().
	 * $params is currently only checked for a 'latest' flag.
	 *
	 * @param $params Array
	 * @return Array
	 */
	protected function headersFromParams( array $params ) {
		$hdrs = array();
		if ( !empty( $params['latest'] ) ) {
			$hdrs[] = 'X-Newest: true';
		}
		return $hdrs;
	}

	/**
	 * @see FileBackendStore::doExecuteOpHandlesInternal()
	 * @return Array List of corresponding Status objects
	 */
	protected function doExecuteOpHandlesInternal( array $fileOpHandles ) {
		$statuses = array();

		$cfOps = array(); // list of CF_Async_Op objects
		foreach ( $fileOpHandles as $index => $fileOpHandle ) {
			$cfOps[$index] = $fileOpHandle->cfOp;
		}
		$batch = new CF_Async_Op_Batch( $cfOps );

		$cfOps = $batch->execute();
		foreach ( $cfOps as $index => $cfOp ) {
			$status = Status::newGood();
			try { // catch exceptions; update status
				$function = '_getResponse' . $fileOpHandles[$index]->call;
				$this->$function( $cfOp, $status, $fileOpHandles[$index]->params );
			} catch ( CloudFilesException $e ) { // some other exception?
				$this->handleException( $e, $status,
					__CLASS__ . ":$function", $fileOpHandles[$index]->params );
			}
			$statuses[$index] = $status;
		}

		foreach ( $fileOpHandles as $fileOpHandle ) {
			$fileOpHandle->closeResources();
		}

		return $statuses;
	}

	/**
	 * Set read/write permissions for a Swift container
	 *
	 * @param $contObj CF_Container Swift container
	 * @param $readGrps Array Swift users who can read (account:user)
	 * @param $writeGrps Array Swift users who can write (account:user)
	 * @return Status
	 */
	protected function setContainerAccess(
		CF_Container $contObj, array $readGrps, array $writeGrps
	) {
		$creds = $contObj->cfs_auth->export_credentials();

		$url = $creds['storage_url'] . '/' . rawurlencode( $contObj->name );

		// Note: 10 second timeout consistent with php-cloudfiles
		$req = new CurlHttpRequest( $url, array( 'method' => 'POST', 'timeout' => 10 ) );
		$req->setHeader( 'X-Auth-Token', $creds['auth_token'] );
		$req->setHeader( 'X-Container-Read', implode( ',', $readGrps ) );
		$req->setHeader( 'X-Container-Write', implode( ',', $writeGrps ) );

		return $req->execute(); // should return 204
	}

	/**
	 * Get a connection to the Swift proxy
	 *
	 * @return CF_Connection|bool False on failure
	 * @throws InvalidResponseException
	 */
	protected function getConnection() {
		if ( $this->conn === false ) {
			throw new InvalidResponseException; // failed last attempt
		}
		// Session keys expire after a while, so we renew them periodically
		if ( $this->conn && ( time() - $this->connStarted ) > $this->authTTL ) {
			$this->conn->close(); // close active cURL connections
			$this->conn = null;
		}
		// Authenticate with proxy and get a session key...
		if ( $this->conn === null ) {
			$this->connContainers = array();
			try {
				$this->auth->authenticate();
				$this->conn = new CF_Connection( $this->auth );
				$this->connStarted = time();
			} catch ( AuthenticationException $e ) {
				$this->conn = false; // don't keep re-trying
			} catch ( InvalidResponseException $e ) {
				$this->conn = false; // don't keep re-trying
			}
		}
		if ( !$this->conn ) {
			throw new InvalidResponseException; // auth/connection problem
		}
		return $this->conn;
	}

	/**
	 * @see FileBackendStore::doClearCache()
	 */
	protected function doClearCache( array $paths = null ) {
		$this->connContainers = array(); // clear container object cache
	}

	/**
	 * Get a Swift container object, possibly from process cache.
	 * Use $reCache if the file count or byte count is needed.
	 *
	 * @param $container string Container name
	 * @param $bypassCache bool Bypass all caches and load from Swift
	 * @return CF_Container
	 * @throws NoSuchContainerException
	 * @throws InvalidResponseException
	 */
	protected function getContainer( $container, $bypassCache = false ) {
		$conn = $this->getConnection(); // Swift proxy connection
		if ( $bypassCache ) { // purge cache
			unset( $this->connContainers[$container] );
		} elseif ( !isset( $this->connContainers[$container] ) ) {
			$this->primeContainerCache( array( $container ) ); // check persistent cache
		}
		if ( !isset( $this->connContainers[$container] ) ) {
			$contObj = $conn->get_container( $container );
			// NoSuchContainerException not thrown: container must exist
			if ( count( $this->connContainers ) >= $this->maxContCacheSize ) { // trim cache?
				reset( $this->connContainers );
				unset( $this->connContainers[key( $this->connContainers )] );
			}
			$this->connContainers[$container] = $contObj; // cache it
			if ( !$bypassCache ) {
				$this->setContainerCache( $container, // update persistent cache
					array( 'bytes' => $contObj->bytes_used, 'count' => $contObj->object_count )
				);
			}
		}
		return $this->connContainers[$container];
	}

	/**
	 * Create a Swift container
	 *
	 * @param $container string Container name
	 * @return CF_Container
	 * @throws InvalidResponseException
	 */
	protected function createContainer( $container ) {
		$conn = $this->getConnection(); // Swift proxy connection
		$contObj = $conn->create_container( $container );
		$this->connContainers[$container] = $contObj; // cache it
		return $contObj;
	}

	/**
	 * Delete a Swift container
	 *
	 * @param $container string Container name
	 * @return void
	 * @throws InvalidResponseException
	 */
	protected function deleteContainer( $container ) {
		$conn = $this->getConnection(); // Swift proxy connection
		$conn->delete_container( $container );
		unset( $this->connContainers[$container] ); // purge cache
	}

	/**
	 * @see FileBackendStore::doPrimeContainerCache()
	 * @return void
	 */
	protected function doPrimeContainerCache( array $containerInfo ) {
		try {
			$conn = $this->getConnection(); // Swift proxy connection
			foreach ( $containerInfo as $container => $info ) {
				$this->connContainers[$container] = new CF_Container(
					$conn->cfs_auth,
					$conn->cfs_http,
					$container,
					$info['count'],
					$info['bytes']
				);
			}
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, null, __METHOD__, array() );
		}
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
			if ( $e instanceof AuthenticationException ) {
				$status->fatal( 'backend-fail-connect', $this->name );
			} else {
				$status->fatal( 'backend-fail-internal', $this->name );
			}
		}
		if ( $e->getMessage() ) {
			trigger_error( "$func: " . $e->getMessage(), E_USER_WARNING );
		}
		wfDebugLog( 'SwiftBackend',
			get_class( $e ) . " in '{$func}' (given '" . FormatJson::encode( $params ) . "')" .
			( $e->getMessage() ? ": {$e->getMessage()}" : "" )
		);
	}
}

/**
 * @see FileBackendStoreOpHandle
 */
class SwiftFileOpHandle extends FileBackendStoreOpHandle {
	/** @var CF_Async_Op */
	public $cfOp;

	public function __construct( $backend, array $params, $call, CF_Async_Op $cfOp ) {
		$this->backend = $backend;
		$this->params = $params;
		$this->call = $call;
		$this->cfOp = $cfOp;
	}
}

/**
 * SwiftFileBackend helper class to page through listings.
 * Swift also has a listing limit of 10,000 objects for sanity.
 * Do not use this class from places outside SwiftFileBackend.
 *
 * @ingroup FileBackend
 */
abstract class SwiftFileBackendList implements Iterator {
	/** @var Array */
	protected $bufferIter = array();
	protected $bufferAfter = null; // string; list items *after* this path
	protected $pos = 0; // integer
	/** @var Array */
	protected $params = array();

	/** @var SwiftFileBackend */
	protected $backend;
	protected $container; // string; container name
	protected $dir; // string; storage directory
	protected $suffixStart; // integer

	const PAGE_SIZE = 5000; // file listing buffer size

	/**
	 * @param $backend SwiftFileBackend
	 * @param $fullCont string Resolved container name
	 * @param $dir string Resolved directory relative to container
	 * @param $params Array
	 */
	public function __construct( SwiftFileBackend $backend, $fullCont, $dir, array $params ) {
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
	 * @return Traversable|Array|null Returns null on failure
	 */
	abstract protected function pageFromList( $container, $dir, &$after, $limit, array $params );
}

/**
 * Iterator for listing directories
 */
class SwiftFileBackendDirList extends SwiftFileBackendList {
	/**
	 * @see Iterator::current()
	 * @return string|bool String (relative path) or false
	 */
	public function current() {
		return substr( current( $this->bufferIter ), $this->suffixStart, -1 );
	}

	/**
	 * @see SwiftFileBackendList::pageFromList()
	 * @return Array|null
	 */
	protected function pageFromList( $container, $dir, &$after, $limit, array $params ) {
		return $this->backend->getDirListPageInternal( $container, $dir, $after, $limit, $params );
	}
}

/**
 * Iterator for listing regular files
 */
class SwiftFileBackendFileList extends SwiftFileBackendList {
	/**
	 * @see Iterator::current()
	 * @return string|bool String (relative path) or false
	 */
	public function current() {
		return substr( current( $this->bufferIter ), $this->suffixStart );
	}

	/**
	 * @see SwiftFileBackendList::pageFromList()
	 * @return Array|null
	 */
	protected function pageFromList( $container, $dir, &$after, $limit, array $params ) {
		return $this->backend->getFileListPageInternal( $container, $dir, $after, $limit, $params );
	}
}
