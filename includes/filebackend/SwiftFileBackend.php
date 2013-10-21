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
 * @brief Class for an OpenStack Swift (or Ceph RGW) based file backend.
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
	protected $swiftTempUrlKey; // string; shared secret value for making temp urls
	protected $swiftAnonUser; // string; username to handle unauthenticated requests
	protected $swiftUseCDN; // boolean; whether CloudFiles CDN is enabled
	protected $swiftCDNExpiry; // integer; how long to cache things in the CDN
	protected $swiftCDNPurgable; // boolean; whether object CDN purging is enabled

	// Rados Gateway specific options
	protected $rgwS3AccessKey; // string; S3 access key
	protected $rgwS3SecretKey; // string; S3 authentication key

	/** @var CF_Connection */
	protected $conn; // Swift connection handle
	protected $sessionStarted = 0; // integer UNIX timestamp

	/** @var CloudFilesException */
	protected $connException;
	protected $connErrorTime = 0; // UNIX timestamp

	/** @var BagOStuff */
	protected $srvCache;

	/** @var ProcessCacheLRU */
	protected $connContainerCache; // container object cache

	/**
	 * @see FileBackendStore::__construct()
	 * Additional $config params include:
	 *   - swiftAuthUrl       : Swift authentication server URL
	 *   - swiftUser          : Swift user used by MediaWiki (account:username)
	 *   - swiftKey           : Swift authentication key for the above user
	 *   - swiftAuthTTL       : Swift authentication TTL (seconds)
	 *   - swiftTempUrlKey    : Swift "X-Account-Meta-Temp-URL-Key" value on the account.
	 *                          Do not set this until it has been set in the backend.
	 *   - swiftAnonUser      : Swift user used for end-user requests (account:username).
	 *                          If set, then views of public containers are assumed to go
	 *                          through this user. If not set, then public containers are
	 *                          accessible to unauthenticated requests via ".r:*" in the ACL.
	 *   - swiftUseCDN        : Whether a Cloud Files Content Delivery Network is set up
	 *   - swiftCDNExpiry     : How long (in seconds) to store content in the CDN.
	 *                          If files may likely change, this should probably not exceed
	 *                          a few days. For example, deletions may take this long to apply.
	 *                          If object purging is enabled, however, this is not an issue.
	 *   - swiftCDNPurgable   : Whether object purge requests are allowed by the CDN.
	 *   - shardViaHashLevels : Map of container names to sharding config with:
	 *                             - base   : base of hash characters, 16 or 36
	 *                             - levels : the number of hash levels (and digits)
	 *                             - repeat : hash subdirectories are prefixed with all the
	 *                                        parent hash directory names (e.g. "a/ab/abc")
	 *   - cacheAuthInfo      : Whether to cache authentication tokens in APC, XCache, ect.
	 *                          If those are not available, then the main cache will be used.
	 *                          This is probably insecure in shared hosting environments.
	 *   - rgwS3AccessKey     : Ragos Gateway S3 "access key" value on the account.
	 *                          Do not set this until it has been set in the backend.
	 *                          This is used for generating expiring pre-authenticated URLs.
	 *                          Only use this when using rgw and to work around
	 *                          http://tracker.newdream.net/issues/3454.
	 *   - rgwS3SecretKey     : Ragos Gateway S3 "secret key" value on the account.
	 *                          Do not set this until it has been set in the backend.
	 *                          This is used for generating expiring pre-authenticated URLs.
	 *                          Only use this when using rgw and to work around
	 *                          http://tracker.newdream.net/issues/3454.
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );
		if ( !class_exists( 'CF_Constants' ) ) {
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
		$this->swiftTempUrlKey = isset( $config['swiftTempUrlKey'] )
			? $config['swiftTempUrlKey']
			: '';
		$this->shardViaHashLevels = isset( $config['shardViaHashLevels'] )
			? $config['shardViaHashLevels']
			: '';
		$this->swiftUseCDN = isset( $config['swiftUseCDN'] )
			? $config['swiftUseCDN']
			: false;
		$this->swiftCDNExpiry = isset( $config['swiftCDNExpiry'] )
			? $config['swiftCDNExpiry']
			: 12 * 3600; // 12 hours is safe (tokens last 24 hours per http://docs.openstack.org)
		$this->swiftCDNPurgable = isset( $config['swiftCDNPurgable'] )
			? $config['swiftCDNPurgable']
			: true;
		$this->rgwS3AccessKey = isset( $config['rgwS3AccessKey'] )
			? $config['rgwS3AccessKey']
			: '';
		$this->rgwS3SecretKey = isset( $config['rgwS3SecretKey'] )
			? $config['rgwS3SecretKey']
			: '';
		// Cache container information to mask latency
		$this->memCache = wfGetMainCache();
		// Process cache for container info
		$this->connContainerCache = new ProcessCacheLRU( 300 );
		// Cache auth token information to avoid RTTs
		if ( !empty( $config['cacheAuthInfo'] ) ) {
			if ( PHP_SAPI === 'cli' ) {
				$this->srvCache = wfGetMainCache(); // preferrably memcached
			} else {
				try { // look for APC, XCache, WinCache, ect...
					$this->srvCache = ObjectCache::newAccelerator( array() );
				} catch ( Exception $e ) {}
			}
		}
		$this->srvCache = $this->srvCache ? $this->srvCache : new EmptyBagOStuff();
	}

	/**
	 * @see FileBackendStore::resolveContainerPath()
	 * @return null
	 */
	protected function resolveContainerPath( $container, $relStoragePath ) {
		if ( !mb_check_encoding( $relStoragePath, 'UTF-8' ) ) { // mb_string required by CF
			return null; // not UTF-8, makes it hard to use CF and the swift HTTP API
		} elseif ( strlen( urlencode( $relStoragePath ) ) > 1024 ) {
			return null; // too long for Swift
		}
		return $relStoragePath;
	}

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
	 * @param array $headers
	 * @return array
	 */
	protected function sanitizeHdrs( array $headers ) {
		// By default, Swift has annoyingly low maximum header value limits
		if ( isset( $headers['Content-Disposition'] ) ) {
			$headers['Content-Disposition'] = $this->truncDisp( $headers['Content-Disposition'] );
		}
		return $headers;
	}

	/**
	 * @param string $disposition Content-Disposition header value
	 * @return string Truncated Content-Disposition header value to meet Swift limits
	 */
	protected function truncDisp( $disposition ) {
		$res = '';
		foreach ( explode( ';', $disposition ) as $part ) {
			$part = trim( $part );
			$new = ( $res === '' ) ? $part : "{$res};{$part}";
			if ( strlen( $new ) <= 255 ) {
				$res = $new;
			} else {
				break; // too long; sigh
			}
		}
		return $res;
	}

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
			$obj->setMetadataValues( array( 'Sha1base36' => $sha1Hash ) );
			// Manually set the ETag (https://github.com/rackspace/php-cloudfiles/issues/59).
			// The MD5 here will be checked within Swift against its own MD5.
			$obj->set_etag( md5( $params['content'] ) );
			// Use the same content type as StreamFile for security
			$obj->content_type = $this->getContentType( $params['dst'], $params['content'], null );
			// Set any other custom headers if requested
			if ( isset( $params['headers'] ) ) {
				$obj->headers += $this->sanitizeHdrs( $params['headers'] );
			}
			if ( !empty( $params['async'] ) ) { // deferred
				$op = $obj->write_async( $params['content'] );
				$status->value = new SwiftFileOpHandle( $this, $params, 'Create', $op );
				$status->value->affectedObjects[] = $obj;
			} else { // actually write the object in Swift
				$obj->write( $params['content'] );
				$this->purgeCDNCache( array( $obj ) );
			}
		} catch ( CDNNotEnabledException $e ) {
			// CDN not enabled; nothing to see here
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
		} catch ( NoSuchContainerException $e ) {
			$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
			return $status;
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, $status, __METHOD__, $params );
			return $status;
		}

		// (b) Get a SHA-1 hash of the object
		wfSuppressWarnings();
		$sha1Hash = sha1_file( $params['src'] );
		wfRestoreWarnings();
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
			$obj->setMetadataValues( array( 'Sha1base36' => $sha1Hash ) );
			// The MD5 here will be checked within Swift against its own MD5.
			$obj->set_etag( md5_file( $params['src'] ) );
			// Use the same content type as StreamFile for security
			$obj->content_type = $this->getContentType( $params['dst'], null, $params['src'] );
			// Set any other custom headers if requested
			if ( isset( $params['headers'] ) ) {
				$obj->headers += $this->sanitizeHdrs( $params['headers'] );
			}
			if ( !empty( $params['async'] ) ) { // deferred
				wfSuppressWarnings();
				$fp = fopen( $params['src'], 'rb' );
				wfRestoreWarnings();
				if ( !$fp ) {
					$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
				} else {
					$op = $obj->write_async( $fp, filesize( $params['src'] ), true );
					$status->value = new SwiftFileOpHandle( $this, $params, 'Store', $op );
					$status->value->resourcesToClose[] = $fp;
					$status->value->affectedObjects[] = $obj;
				}
			} else { // actually write the object in Swift
				$obj->load_from_filename( $params['src'], true ); // calls $obj->write()
				$this->purgeCDNCache( array( $obj ) );
			}
		} catch ( CDNNotEnabledException $e ) {
			// CDN not enabled; nothing to see here
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
		} catch ( NoSuchContainerException $e ) {
			if ( empty( $params['ignoreMissingSource'] ) || isset( $sContObj ) ) {
				$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
			}
			return $status;
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, $status, __METHOD__, $params );
			return $status;
		}

		// (b) Actually copy the file to the destination
		try {
			$dstObj = new CF_Object( $dContObj, $dstRel, false, false ); // skip HEAD
			$hdrs = array(); // source file headers to override with new values
			// Set any other custom headers if requested
			if ( isset( $params['headers'] ) ) {
				$hdrs += $this->sanitizeHdrs( $params['headers'] );
			}
			if ( !empty( $params['async'] ) ) { // deferred
				$op = $sContObj->copy_object_to_async( $srcRel, $dContObj, $dstRel, null, $hdrs );
				$status->value = new SwiftFileOpHandle( $this, $params, 'Copy', $op );
				$status->value->affectedObjects[] = $dstObj;
			} else { // actually write the object in Swift
				$sContObj->copy_object_to( $srcRel, $dContObj, $dstRel, null, $hdrs );
				$this->purgeCDNCache( array( $dstObj ) );
			}
		} catch ( CDNNotEnabledException $e ) {
			// CDN not enabled; nothing to see here
		} catch ( NoSuchObjectException $e ) { // source object does not exist
			if ( empty( $params['ignoreMissingSource'] ) ) {
				$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
			}
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
		} catch ( NoSuchContainerException $e ) {
			if ( empty( $params['ignoreMissingSource'] ) || isset( $sContObj ) ) {
				$status->fatal( 'backend-fail-move', $params['src'], $params['dst'] );
			}
			return $status;
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, $status, __METHOD__, $params );
			return $status;
		}

		// (b) Actually move the file to the destination
		try {
			$srcObj = new CF_Object( $sContObj, $srcRel, false, false ); // skip HEAD
			$dstObj = new CF_Object( $dContObj, $dstRel, false, false ); // skip HEAD
			$hdrs = array(); // source file headers to override with new values
			// Set any other custom headers if requested
			if ( isset( $params['headers'] ) ) {
				$hdrs += $this->sanitizeHdrs( $params['headers'] );
			}
			if ( !empty( $params['async'] ) ) { // deferred
				$op = $sContObj->move_object_to_async( $srcRel, $dContObj, $dstRel, null, $hdrs );
				$status->value = new SwiftFileOpHandle( $this, $params, 'Move', $op );
				$status->value->affectedObjects[] = $srcObj;
				$status->value->affectedObjects[] = $dstObj;
			} else { // actually write the object in Swift
				$sContObj->move_object_to( $srcRel, $dContObj, $dstRel, null, $hdrs );
				$this->purgeCDNCache( array( $srcObj ) );
				$this->purgeCDNCache( array( $dstObj ) );
			}
		} catch ( CDNNotEnabledException $e ) {
			// CDN not enabled; nothing to see here
		} catch ( NoSuchObjectException $e ) { // source object does not exist
			if ( empty( $params['ignoreMissingSource'] ) ) {
				$status->fatal( 'backend-fail-move', $params['src'], $params['dst'] );
			}
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

	protected function doDeleteInternal( array $params ) {
		$status = Status::newGood();

		list( $srcCont, $srcRel ) = $this->resolveStoragePathReal( $params['src'] );
		if ( $srcRel === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['src'] );
			return $status;
		}

		try {
			$sContObj = $this->getContainer( $srcCont );
			$srcObj = new CF_Object( $sContObj, $srcRel, false, false ); // skip HEAD
			if ( !empty( $params['async'] ) ) { // deferred
				$op = $sContObj->delete_object_async( $srcRel );
				$status->value = new SwiftFileOpHandle( $this, $params, 'Delete', $op );
				$status->value->affectedObjects[] = $srcObj;
			} else { // actually write the object in Swift
				$sContObj->delete_object( $srcRel );
				$this->purgeCDNCache( array( $srcObj ) );
			}
		} catch ( CDNNotEnabledException $e ) {
			// CDN not enabled; nothing to see here
		} catch ( NoSuchContainerException $e ) {
			if ( empty( $params['ignoreMissingSource'] ) ) {
				$status->fatal( 'backend-fail-delete', $params['src'] );
			}
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

	protected function doDescribeInternal( array $params ) {
		$status = Status::newGood();

		list( $srcCont, $srcRel ) = $this->resolveStoragePathReal( $params['src'] );
		if ( $srcRel === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['src'] );
			return $status;
		}

		try {
			$sContObj = $this->getContainer( $srcCont );
			// Get the latest version of the current metadata
			$srcObj = $sContObj->get_object( $srcRel,
				$this->headersFromParams( array( 'latest' => true ) ) );
			// Merge in the metadata updates...
			if ( isset( $params['headers'] ) ) {
				$srcObj->headers = $this->sanitizeHdrs( $params['headers'] ) + $srcObj->headers;
			}
			$srcObj->sync_metadata(); // save to Swift
			$this->purgeCDNCache( array( $srcObj ) );
		} catch ( CDNNotEnabledException $e ) {
			// CDN not enabled; nothing to see here
		} catch ( NoSuchContainerException $e ) {
			$status->fatal( 'backend-fail-describe', $params['src'] );
		} catch ( NoSuchObjectException $e ) {
			$status->fatal( 'backend-fail-describe', $params['src'] );
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, $status, __METHOD__, $params );
		}

		return $status;
	}

	protected function doPrepareInternal( $fullCont, $dir, array $params ) {
		$status = Status::newGood();

		// (a) Check if container already exists
		try {
			$this->getContainer( $fullCont );
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
			if ( !empty( $params['noAccess'] ) ) {
				// Make container private to end-users...
				$status->merge( $this->doSecureInternal( $fullCont, $dir, $params ) );
			} else {
				// Make container public to end-users...
				$status->merge( $this->doPublishInternal( $fullCont, $dir, $params ) );
			}
			if ( $this->swiftUseCDN ) { // Rackspace style CDN
				$contObj->make_public( $this->swiftCDNExpiry );
			}
		} catch ( CDNNotEnabledException $e ) {
			// CDN not enabled; nothing to see here
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
		if ( empty( $params['noAccess'] ) ) {
			return $status; // nothing to do
		}

		// Restrict container from end-users...
		try {
			// doPrepareInternal() should have been called,
			// so the Swift container should already exist...
			$contObj = $this->getContainer( $fullCont ); // normally a cache hit
			// NoSuchContainerException not thrown: container must exist

			// Make container private to end-users...
			$status->merge( $this->setContainerAccess(
				$contObj,
				array( $this->auth->username ), // read
				array( $this->auth->username ) // write
			) );
			if ( $this->swiftUseCDN && $contObj->is_public() ) { // Rackspace style CDN
				$contObj->make_private();
			}
		} catch ( CDNNotEnabledException $e ) {
			// CDN not enabled; nothing to see here
		} catch ( CloudFilesException $e ) { // some other exception?
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

		// Unrestrict container from end-users...
		try {
			// doPrepareInternal() should have been called,
			// so the Swift container should already exist...
			$contObj = $this->getContainer( $fullCont ); // normally a cache hit
			// NoSuchContainerException not thrown: container must exist

			// Make container public to end-users...
			if ( $this->swiftAnonUser != '' ) {
				$status->merge( $this->setContainerAccess(
					$contObj,
					array( $this->auth->username, $this->swiftAnonUser ), // read
					array( $this->auth->username, $this->swiftAnonUser ) // write
				) );
			} else {
				$status->merge( $this->setContainerAccess(
					$contObj,
					array( $this->auth->username, '.r:*' ), // read
					array( $this->auth->username ) // write
				) );
			}
			if ( $this->swiftUseCDN && !$contObj->is_public() ) { // Rackspace style CDN
				$contObj->make_public();
			}
		} catch ( CDNNotEnabledException $e ) {
			// CDN not enabled; nothing to see here
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, $status, __METHOD__, $params );
		}

		return $status;
	}

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
				'size' => (int)$srcObj->content_length,
				'sha1' => $srcObj->getMetadataValue( 'Sha1base36' )
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
	 * @param CF_Object $obj
	 * @param string $path Storage path to object
	 * @return bool Success
	 * @throws Exception cloudfiles exceptions
	 */
	protected function addMissingMetadata( CF_Object $obj, $path ) {
		if ( $obj->getMetadataValue( 'Sha1base36' ) !== null ) {
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
					$obj->setMetadataValues( array( 'Sha1base36' => $hash ) );
					$obj->sync_metadata(); // save to Swift
					wfProfileOut( __METHOD__ );
					return true; // success
				}
			}
		}
		trigger_error( "Unable to set SHA-1 metadata for $path", E_USER_WARNING );
		$obj->setMetadataValues( array( 'Sha1base36' => false ) );
		wfProfileOut( __METHOD__ );
		return false; // failed
	}

	protected function doGetFileContentsMulti( array $params ) {
		$contents = array();

		$ep = array_diff_key( $params, array( 'srcs' => 1 ) ); // for error logging
		// Blindly create tmp files and stream to them, catching any exception if the file does
		// not exist. Doing stats here is useless and will loop infinitely in addMissingMetadata().
		foreach ( array_chunk( $params['srcs'], $params['concurrency'] ) as $pathBatch ) {
			$cfOps = array(); // (path => CF_Async_Op)

			foreach ( $pathBatch as $path ) { // each path in this concurrent batch
				list( $srcCont, $srcRel ) = $this->resolveStoragePathReal( $path );
				if ( $srcRel === null ) {
					$contents[$path] = false;
					continue;
				}
				$data = false;
				try {
					$sContObj = $this->getContainer( $srcCont );
					$obj = new CF_Object( $sContObj, $srcRel, false, false ); // skip HEAD
					// Create a new temporary memory file...
					$handle = fopen( 'php://temp', 'wb' );
					if ( $handle ) {
						$headers = $this->headersFromParams( $params );
						if ( count( $pathBatch ) > 1 ) {
							$cfOps[$path] = $obj->stream_async( $handle, $headers );
							$cfOps[$path]->_file_handle = $handle; // close this later
						} else {
							$obj->stream( $handle, $headers );
							rewind( $handle ); // start from the beginning
							$data = stream_get_contents( $handle );
							fclose( $handle );
						}
					} else {
						$data = false;
					}
				} catch ( NoSuchContainerException $e ) {
					$data = false;
				} catch ( NoSuchObjectException $e ) {
					$data = false;
				} catch ( CloudFilesException $e ) { // some other exception?
					$data = false;
					$this->handleException( $e, null, __METHOD__, array( 'src' => $path ) + $ep );
				}
				$contents[$path] = $data;
			}

			$batch = new CF_Async_Op_Batch( $cfOps );
			$cfOps = $batch->execute();
			foreach ( $cfOps as $path => $cfOp ) {
				try {
					$cfOp->getLastResponse();
					rewind( $cfOp->_file_handle ); // start from the beginning
					$contents[$path] = stream_get_contents( $cfOp->_file_handle );
				} catch ( NoSuchContainerException $e ) {
					$contents[$path] = false;
				} catch ( NoSuchObjectException $e ) {
					$contents[$path] = false;
				} catch ( CloudFilesException $e ) { // some other exception?
					$contents[$path] = false;
					$this->handleException( $e, null, __METHOD__, array( 'src' => $path ) + $ep );
				}
				fclose( $cfOp->_file_handle ); // close open handle
			}
		}

		return $contents;
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
	 * @param string $fullCont Resolved container name
	 * @param string $dir Resolved storage directory with no trailing slash
	 * @param string|null $after Storage path of file to list items after
	 * @param integer $limit Max number of items to list
	 * @param array $params Parameters for getDirectoryList()
	 * @return Array List of resolved paths of directories directly under $dir
	 * @throws FileBackendError
	 */
	public function getDirListPageInternal( $fullCont, $dir, &$after, $limit, array $params ) {
		$dirs = array();
		if ( $after === INF ) {
			return $dirs; // nothing more
		}

		$section = new ProfileSection( __METHOD__ . '-' . $this->name );
		try {
			$container = $this->getContainer( $fullCont );
			$prefix = ( $dir == '' ) ? null : "{$dir}/";
			// Non-recursive: only list dirs right under $dir
			if ( !empty( $params['topOnly'] ) ) {
				$objects = $container->list_objects( $limit, $after, $prefix, null, '/' );
				foreach ( $objects as $object ) { // files and directories
					if ( substr( $object, -1 ) === '/' ) {
						$dirs[] = $object; // directories end in '/'
					}
				}
			// Recursive: list all dirs under $dir and its subdirs
			} else {
				// Get directory from last item of prior page
				$lastDir = $this->getParentDir( $after ); // must be first page
				$objects = $container->list_objects( $limit, $after, $prefix );
				foreach ( $objects as $object ) { // files
					$objectDir = $this->getParentDir( $object ); // directory of object
					if ( $objectDir !== false && $objectDir !== $dir ) {
						// Swift stores paths in UTF-8, using binary sorting.
						// See function "create_container_table" in common/db.py.
						// If a directory is not "greater" than the last one,
						// then it was already listed by the calling iterator.
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
			// Page on the unfiltered directory listing (what is returned may be filtered)
			if ( count( $objects ) < $limit ) {
				$after = INF; // avoid a second RTT
			} else {
				$after = end( $objects ); // update last item
			}
		} catch ( NoSuchContainerException $e ) {
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, null, __METHOD__,
				array( 'cont' => $fullCont, 'dir' => $dir ) );
			throw new FileBackendError( "Got " . get_class( $e ) . " exception." );
		}

		return $dirs;
	}

	protected function getParentDir( $path ) {
		return ( strpos( $path, '/' ) !== false ) ? dirname( $path ) : false;
	}

	/**
	 * Do not call this function outside of SwiftFileBackendFileList
	 *
	 * @param string $fullCont Resolved container name
	 * @param string $dir Resolved storage directory with no trailing slash
	 * @param string|null $after Storage path of file to list items after
	 * @param integer $limit Max number of items to list
	 * @param array $params Parameters for getDirectoryList()
	 * @return Array List of resolved paths of files under $dir
	 * @throws FileBackendError
	 */
	public function getFileListPageInternal( $fullCont, $dir, &$after, $limit, array $params ) {
		$files = array();
		if ( $after === INF ) {
			return $files; // nothing more
		}

		$section = new ProfileSection( __METHOD__ . '-' . $this->name );
		try {
			$container = $this->getContainer( $fullCont );
			$prefix = ( $dir == '' ) ? null : "{$dir}/";
			// Non-recursive: only list files right under $dir
			if ( !empty( $params['topOnly'] ) ) { // files and dirs
				if ( !empty( $params['adviseStat'] ) ) {
					$limit = min( $limit, self::CACHE_CHEAP_SIZE );
					// Note: get_objects() does not include directories
					$objects = $this->loadObjectListing( $params, $dir,
						$container->get_objects( $limit, $after, $prefix, null, '/' ) );
					$files = $objects;
				} else {
					$objects = $container->list_objects( $limit, $after, $prefix, null, '/' );
					foreach ( $objects as $object ) { // files and directories
						if ( substr( $object, -1 ) !== '/' ) {
							$files[] = $object; // directories end in '/'
						}
					}
				}
			// Recursive: list all files under $dir and its subdirs
			} else { // files
				if ( !empty( $params['adviseStat'] ) ) {
					$limit = min( $limit, self::CACHE_CHEAP_SIZE );
					$objects = $this->loadObjectListing( $params, $dir,
						$container->get_objects( $limit, $after, $prefix ) );
				} else {
					$objects = $container->list_objects( $limit, $after, $prefix );
				}
				$files = $objects;
			}
			// Page on the unfiltered object listing (what is returned may be filtered)
			if ( count( $objects ) < $limit ) {
				$after = INF; // avoid a second RTT
			} else {
				$after = end( $objects ); // update last item
			}
		} catch ( NoSuchContainerException $e ) {
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, null, __METHOD__,
				array( 'cont' => $fullCont, 'dir' => $dir ) );
			throw new FileBackendError( "Got " . get_class( $e ) . " exception." );
		}

		return $files;
	}

	/**
	 * Load a list of objects that belong under $dir into stat cache
	 * and return a list of the names of the objects in the same order.
	 *
	 * @param array $params Parameters for getDirectoryList()
	 * @param string $dir Resolved container directory path
	 * @param array $cfObjects List of CF_Object items
	 * @return array List of object names
	 */
	private function loadObjectListing( array $params, $dir, array $cfObjects ) {
		$names = array();
		$storageDir = rtrim( $params['dir'], '/' );
		$suffixStart = ( $dir === '' ) ? 0 : strlen( $dir ) + 1; // size of "path/to/dir/"
		// Iterate over the list *backwards* as this primes the stat cache, which is LRU.
		// If this fills the cache and the caller stats an uncached file before stating
		// the ones on the listing, there would be zero cache hits if this went forwards.
		for ( end( $cfObjects ); key( $cfObjects ) !== null; prev( $cfObjects ) ) {
			$object = current( $cfObjects );
			$path = "{$storageDir}/" . substr( $object->name, $suffixStart );
			$val = array(
				// Convert dates like "Tue, 03 Jan 2012 22:01:04 GMT" to TS_MW
				'mtime'  => wfTimestamp( TS_MW, $object->last_modified ),
				'size'   => (int)$object->content_length,
				'latest' => false // eventually consistent
			);
			$this->cheapCache->set( $path, 'stat', $val );
			$names[] = $object->name;
		}
		return array_reverse( $names ); // keep the paths in original order
	}

	protected function doGetFileSha1base36( array $params ) {
		$stat = $this->getFileStat( $params );
		if ( $stat ) {
			if ( !isset( $stat['sha1'] ) ) {
				// Stat entries filled by file listings don't include SHA1
				$this->clearCache( array( $params['src'] ) );
				$stat = $this->getFileStat( $params );
			}
			return $stat['sha1'];
		} else {
			return false;
		}
	}

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
			$obj = new CF_Object( $cont, $srcRel, false, false ); // skip HEAD
			$obj->stream( $output, $this->headersFromParams( $params ) );
		} catch ( NoSuchObjectException $e ) {
			$status->fatal( 'backend-fail-stream', $params['src'] );
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, $status, __METHOD__, $params );
		}

		return $status;
	}

	protected function doGetLocalCopyMulti( array $params ) {
		$tmpFiles = array();

		$ep = array_diff_key( $params, array( 'srcs' => 1 ) ); // for error logging
		// Blindly create tmp files and stream to them, catching any exception if the file does
		// not exist. Doing a stat here is useless causes infinite loops in addMissingMetadata().
		foreach ( array_chunk( $params['srcs'], $params['concurrency'] ) as $pathBatch ) {
			$cfOps = array(); // (path => CF_Async_Op)

			foreach ( $pathBatch as $path ) { // each path in this concurrent batch
				list( $srcCont, $srcRel ) = $this->resolveStoragePathReal( $path );
				if ( $srcRel === null ) {
					$tmpFiles[$path] = null;
					continue;
				}
				$tmpFile = null;
				try {
					$sContObj = $this->getContainer( $srcCont );
					$obj = new CF_Object( $sContObj, $srcRel, false, false ); // skip HEAD
					// Get source file extension
					$ext = FileBackend::extensionFromPath( $path );
					// Create a new temporary file...
					$tmpFile = TempFSFile::factory( 'localcopy_', $ext );
					if ( $tmpFile ) {
						$handle = fopen( $tmpFile->getPath(), 'wb' );
						if ( $handle ) {
							$headers = $this->headersFromParams( $params );
							if ( count( $pathBatch ) > 1 ) {
								$cfOps[$path] = $obj->stream_async( $handle, $headers );
								$cfOps[$path]->_file_handle = $handle; // close this later
							} else {
								$obj->stream( $handle, $headers );
								fclose( $handle );
							}
						} else {
							$tmpFile = null;
						}
					}
				} catch ( NoSuchContainerException $e ) {
					$tmpFile = null;
				} catch ( NoSuchObjectException $e ) {
					$tmpFile = null;
				} catch ( CloudFilesException $e ) { // some other exception?
					$tmpFile = null;
					$this->handleException( $e, null, __METHOD__, array( 'src' => $path ) + $ep );
				}
				$tmpFiles[$path] = $tmpFile;
			}

			$batch = new CF_Async_Op_Batch( $cfOps );
			$cfOps = $batch->execute();
			foreach ( $cfOps as $path => $cfOp ) {
				try {
					$cfOp->getLastResponse();
				} catch ( NoSuchContainerException $e ) {
					$tmpFiles[$path] = null;
				} catch ( NoSuchObjectException $e ) {
					$tmpFiles[$path] = null;
				} catch ( CloudFilesException $e ) { // some other exception?
					$tmpFiles[$path] = null;
					$this->handleException( $e, null, __METHOD__, array( 'src' => $path ) + $ep );
				}
				fclose( $cfOp->_file_handle ); // close open handle
			}
		}

		return $tmpFiles;
	}

	public function getFileHttpUrl( array $params ) {
		if ( $this->swiftTempUrlKey != '' ||
			( $this->rgwS3AccessKey != '' && $this->rgwS3SecretKey != '' ) )
		{
			list( $srcCont, $srcRel ) = $this->resolveStoragePathReal( $params['src'] );
			if ( $srcRel === null ) {
				return null; // invalid path
			}
			try {
				$ttl = isset( $params['ttl'] ) ? $params['ttl'] : 86400;
				$sContObj = $this->getContainer( $srcCont );
				$obj = new CF_Object( $sContObj, $srcRel, false, false ); // skip HEAD
				if ( $this->swiftTempUrlKey != '' ) {
					return $obj->get_temp_url( $this->swiftTempUrlKey, $ttl, "GET" );
				} else { // give S3 API URL for rgw
					$expires = time() + $ttl;
					// Path for signature starts with the bucket
					$spath = '/' . rawurlencode( $srcCont ) . '/' .
						str_replace( '%2F', '/', rawurlencode( $srcRel ) );
					// Calculate the hash
					$signature = base64_encode( hash_hmac(
						'sha1',
						"GET\n\n\n{$expires}\n{$spath}",
						$this->rgwS3SecretKey,
						true // raw
					) );
					// See http://s3.amazonaws.com/doc/s3-developer-guide/RESTAuthentication.html.
					// Note: adding a newline for empty CanonicalizedAmzHeaders does not work.
					return wfAppendQuery(
						str_replace( '/swift/v1', '', // S3 API is the rgw default
							$sContObj->cfs_http->getStorageUrl() . $spath ),
						array(
							'Signature' => $signature,
							'Expires' => $expires,
							'AWSAccessKeyId' => $this->rgwS3AccessKey )
					);
				}
			} catch ( NoSuchContainerException $e ) {
			} catch ( CloudFilesException $e ) { // some other exception?
				$this->handleException( $e, null, __METHOD__, $params );
			}
		}
		return null;
	}

	protected function directoriesAreVirtual() {
		return true;
	}

	/**
	 * Get headers to send to Swift when reading a file based
	 * on a FileBackend params array, e.g. that of getLocalCopy().
	 * $params is currently only checked for a 'latest' flag.
	 *
	 * @param array $params
	 * @return Array
	 */
	protected function headersFromParams( array $params ) {
		$hdrs = array();
		if ( !empty( $params['latest'] ) ) {
			$hdrs[] = 'X-Newest: true';
		}
		return $hdrs;
	}

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
			$function = '_getResponse' . $fileOpHandles[$index]->call;
			try { // catch exceptions; update status
				$this->$function( $cfOp, $status, $fileOpHandles[$index]->params );
				$this->purgeCDNCache( $fileOpHandles[$index]->affectedObjects );
			} catch ( CloudFilesException $e ) { // some other exception?
				$this->handleException( $e, $status,
					__CLASS__ . ":$function", $fileOpHandles[$index]->params );
			}
			$statuses[$index] = $status;
		}

		return $statuses;
	}

	/**
	 * Set read/write permissions for a Swift container.
	 *
	 * $readGrps is a list of the possible criteria for a request to have
	 * access to read a container. Each item is one of the following formats:
	 *   - account:user        : Grants access if the request is by the given user
	 *   - ".r:<regex>"        : Grants access if the request is from a referrer host that
	 *                           matches the expression and the request is not for a listing.
	 *                           Setting this to '*' effectively makes a container public.
	 *   -".rlistings:<regex>" : Grants access if the request is from a referrer host that
	 *                           matches the expression and the request is for a listing.
	 *
	 * $writeGrps is a list of the possible criteria for a request to have
	 * access to write to a container. Each item is of the following format:
	 *   - account:user       : Grants access if the request is by the given user
	 *
	 * @see http://swift.openstack.org/misc.html#acls
	 *
	 * In general, we don't allow listings to end-users. It's not useful, isn't well-defined
	 * (lists are truncated to 10000 item with no way to page), and is just a performance risk.
	 *
	 * @param CF_Container $contObj Swift container
	 * @param array $readGrps List of read access routes
	 * @param array $writeGrps List of write access routes
	 * @return Status
	 */
	protected function setContainerAccess(
		CF_Container $contObj, array $readGrps, array $writeGrps
	) {
		$creds = $contObj->cfs_auth->export_credentials();

		$url = $creds['storage_url'] . '/' . rawurlencode( $contObj->name );

		// Note: 10 second timeout consistent with php-cloudfiles
		$req = MWHttpRequest::factory( $url, array( 'method' => 'POST', 'timeout' => 10 ) );
		$req->setHeader( 'X-Auth-Token', $creds['auth_token'] );
		$req->setHeader( 'X-Container-Read', implode( ',', $readGrps ) );
		$req->setHeader( 'X-Container-Write', implode( ',', $writeGrps ) );

		return $req->execute(); // should return 204
	}

	/**
	 * Purge the CDN cache of affected objects if CDN caching is enabled.
	 * This is for Rackspace/Akamai CDNs.
	 *
	 * @param array $objects List of CF_Object items
	 * @return void
	 */
	public function purgeCDNCache( array $objects ) {
		if ( $this->swiftUseCDN && $this->swiftCDNPurgable ) {
			foreach ( $objects as $object ) {
				try {
					$object->purge_from_cdn();
				} catch ( CDNNotEnabledException $e ) {
					// CDN not enabled; nothing to see here
				} catch ( CloudFilesException $e ) {
					$this->handleException( $e, null, __METHOD__,
						array( 'cont' => $object->container->name, 'obj' => $object->name ) );
				}
			}
		}
	}

	/**
	 * Get an authenticated connection handle to the Swift proxy
	 *
	 * @throws CloudFilesException
	 * @throws CloudFilesException|Exception
	 * @return CF_Connection|bool False on failure
	 */
	protected function getConnection() {
		if ( $this->connException instanceof CloudFilesException ) {
			if ( ( time() - $this->connErrorTime ) < 60 ) {
				throw $this->connException; // failed last attempt; don't bother
			} else { // actually retry this time
				$this->connException = null;
				$this->connErrorTime = 0;
			}
		}
		// Session keys expire after a while, so we renew them periodically
		$reAuth = ( ( time() - $this->sessionStarted ) > $this->authTTL );
		// Authenticate with proxy and get a session key...
		if ( !$this->conn || $reAuth ) {
			$this->sessionStarted = 0;
			$this->connContainerCache->clear();
			$cacheKey = $this->getCredsCacheKey( $this->auth->username );
			$creds = $this->srvCache->get( $cacheKey ); // credentials
			if ( is_array( $creds ) ) { // cache hit
				$this->auth->load_cached_credentials(
					$creds['auth_token'], $creds['storage_url'], $creds['cdnm_url'] );
				$this->sessionStarted = time() - ceil( $this->authTTL / 2 ); // skew for worst case
			} else { // cache miss
				try {
					$this->auth->authenticate();
					$creds = $this->auth->export_credentials();
					$this->srvCache->add( $cacheKey, $creds, ceil( $this->authTTL / 2 ) ); // cache
					$this->sessionStarted = time();
				} catch ( CloudFilesException $e ) {
					$this->connException = $e; // don't keep re-trying
					$this->connErrorTime = time();
					throw $e; // throw it back
				}
			}
			if ( $this->conn ) { // re-authorizing?
				$this->conn->close(); // close active cURL handles in CF_Http object
			}
			$this->conn = new CF_Connection( $this->auth );
		}
		return $this->conn;
	}

	/**
	 * Close the connection to the Swift proxy
	 *
	 * @return void
	 */
	protected function closeConnection() {
		if ( $this->conn ) {
			$this->conn->close(); // close active cURL handles in CF_Http object
			$this->conn = null;
			$this->sessionStarted = 0;
			$this->connContainerCache->clear();
		}
	}

	/**
	 * Get the cache key for a container
	 *
	 * @param string $username
	 * @return string
	 */
	private function getCredsCacheKey( $username ) {
		return wfMemcKey( 'backend', $this->getName(), 'usercreds', $username );
	}

	/**
	 * Get a Swift container object, possibly from process cache.
	 * Use $reCache if the file count or byte count is needed.
	 *
	 * @param string $container Container name
	 * @param bool $bypassCache Bypass all caches and load from Swift
	 * @return CF_Container
	 * @throws CloudFilesException
	 */
	protected function getContainer( $container, $bypassCache = false ) {
		$conn = $this->getConnection(); // Swift proxy connection
		if ( $bypassCache ) { // purge cache
			$this->connContainerCache->clear( $container );
		} elseif ( !$this->connContainerCache->has( $container, 'obj' ) ) {
			$this->primeContainerCache( array( $container ) ); // check persistent cache
		}
		if ( !$this->connContainerCache->has( $container, 'obj' ) ) {
			$contObj = $conn->get_container( $container );
			// NoSuchContainerException not thrown: container must exist
			$this->connContainerCache->set( $container, 'obj', $contObj ); // cache it
			if ( !$bypassCache ) {
				$this->setContainerCache( $container, // update persistent cache
					array( 'bytes' => $contObj->bytes_used, 'count' => $contObj->object_count )
				);
			}
		}
		return $this->connContainerCache->get( $container, 'obj' );
	}

	/**
	 * Create a Swift container
	 *
	 * @param string $container Container name
	 * @return CF_Container
	 * @throws CloudFilesException
	 */
	protected function createContainer( $container ) {
		$conn = $this->getConnection(); // Swift proxy connection
		$contObj = $conn->create_container( $container );
		$this->connContainerCache->set( $container, 'obj', $contObj ); // cache
		return $contObj;
	}

	/**
	 * Delete a Swift container
	 *
	 * @param string $container Container name
	 * @return void
	 * @throws CloudFilesException
	 */
	protected function deleteContainer( $container ) {
		$conn = $this->getConnection(); // Swift proxy connection
		$this->connContainerCache->clear( $container ); // purge
		$conn->delete_container( $container );
	}

	protected function doPrimeContainerCache( array $containerInfo ) {
		try {
			$conn = $this->getConnection(); // Swift proxy connection
			foreach ( $containerInfo as $container => $info ) {
				$contObj = new CF_Container( $conn->cfs_auth, $conn->cfs_http,
					$container, $info['count'], $info['bytes'] );
				$this->connContainerCache->set( $container, 'obj', $contObj );
			}
		} catch ( CloudFilesException $e ) { // some other exception?
			$this->handleException( $e, null, __METHOD__, array() );
		}
	}

	/**
	 * Log an unexpected exception for this backend.
	 * This also sets the Status object to have a fatal error.
	 *
	 * @param Exception $e
	 * @param Status $status|null
	 * @param string $func
	 * @param array $params
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
		if ( $e instanceof InvalidResponseException ) { // possibly a stale token
			$this->srvCache->delete( $this->getCredsCacheKey( $this->auth->username ) );
			$this->closeConnection(); // force a re-connect and re-auth next time
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
	/** @var Array */
	public $affectedObjects = array();

	/**
	 * @param SwiftFileBackend $backend
	 * @param array $params
	 * @param string $call
	 * @param CF_Async_Op $cfOp
	 */
	public function __construct(
		SwiftFileBackend $backend, array $params, $call, CF_Async_Op $cfOp
	) {
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

	const PAGE_SIZE = 9000; // file listing buffer size

	/**
	 * @param SwiftFileBackend $backend
	 * @param string $fullCont Resolved container name
	 * @param string $dir Resolved directory relative to container
	 * @param array $params
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
	 * @param string $container Resolved container name
	 * @param string $dir Resolved path relative to container
	 * @param string $after|null
	 * @param integer $limit
	 * @param array $params
	 * @return Traversable|Array
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
	 * @return Array
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
	 * @return Array
	 */
	protected function pageFromList( $container, $dir, &$after, $limit, array $params ) {
		return $this->backend->getFileListPageInternal( $container, $dir, $after, $limit, $params );
	}
}
