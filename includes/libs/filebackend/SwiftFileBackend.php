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
 */

/**
 * @brief Class for an OpenStack Swift (or Ceph RGW) based file backend.
 *
 * StatusValue messages should avoid mentioning the Swift account name.
 * Likewise, error suppression should be used to avoid path disclosure.
 *
 * @ingroup FileBackend
 * @since 1.19
 */
class SwiftFileBackend extends FileBackendStore {
	/** @var MultiHttpClient */
	protected $http;
	/** @var int TTL in seconds */
	protected $authTTL;
	/** @var string Authentication base URL (without version) */
	protected $swiftAuthUrl;
	/** @var string Override of storage base URL */
	protected $swiftStorageUrl;
	/** @var string Swift user (account:user) to authenticate as */
	protected $swiftUser;
	/** @var string Secret key for user */
	protected $swiftKey;
	/** @var string Shared secret value for making temp URLs */
	protected $swiftTempUrlKey;
	/** @var string S3 access key (RADOS Gateway) */
	protected $rgwS3AccessKey;
	/** @var string S3 authentication key (RADOS Gateway) */
	protected $rgwS3SecretKey;
	/** @var array Additional users (account:user) with read permissions on public containers */
	protected $readUsers;
	/** @var array Additional users (account:user) with write permissions on public containers */
	protected $writeUsers;
	/** @var array Additional users (account:user) with read permissions on private containers */
	protected $secureReadUsers;
	/** @var array Additional users (account:user) with write permissions on private containers */
	protected $secureWriteUsers;

	/** @var BagOStuff */
	protected $srvCache;

	/** @var ProcessCacheLRU Container stat cache */
	protected $containerStatCache;

	/** @var array */
	protected $authCreds;
	/** @var int UNIX timestamp */
	protected $authSessionTimestamp = 0;
	/** @var int UNIX timestamp */
	protected $authErrorTimestamp = null;

	/** @var bool Whether the server is an Ceph RGW */
	protected $isRGW = false;

	/**
	 * @see FileBackendStore::__construct()
	 * @param array $config Params include:
	 *   - swiftAuthUrl       : Swift authentication server URL
	 *   - swiftUser          : Swift user used by MediaWiki (account:username)
	 *   - swiftKey           : Swift authentication key for the above user
	 *   - swiftAuthTTL       : Swift authentication TTL (seconds)
	 *   - swiftTempUrlKey    : Swift "X-Account-Meta-Temp-URL-Key" value on the account.
	 *                          Do not set this until it has been set in the backend.
	 *   - swiftStorageUrl    : Swift storage URL (overrides that of the authentication response).
	 *                          This is useful to set if a TLS proxy is in use.
	 *   - shardViaHashLevels : Map of container names to sharding config with:
	 *                             - base   : base of hash characters, 16 or 36
	 *                             - levels : the number of hash levels (and digits)
	 *                             - repeat : hash subdirectories are prefixed with all the
	 *                                        parent hash directory names (e.g. "a/ab/abc")
	 *   - cacheAuthInfo      : Whether to cache authentication tokens in APC, etc.
	 *                          If those are not available, then the main cache will be used.
	 *                          This is probably insecure in shared hosting environments.
	 *   - rgwS3AccessKey     : Rados Gateway S3 "access key" value on the account.
	 *                          Do not set this until it has been set in the backend.
	 *                          This is used for generating expiring pre-authenticated URLs.
	 *                          Only use this when using rgw and to work around
	 *                          http://tracker.newdream.net/issues/3454.
	 *   - rgwS3SecretKey     : Rados Gateway S3 "secret key" value on the account.
	 *                          Do not set this until it has been set in the backend.
	 *                          This is used for generating expiring pre-authenticated URLs.
	 *                          Only use this when using rgw and to work around
	 *                          http://tracker.newdream.net/issues/3454.
	 *   - readUsers           : Swift users with read access to public containers (account:username)
	 *   - writeUsers          : Swift users with write access to public containers (account:username)
	 *   - secureReadUsers     : Swift users with read access to private containers (account:username)
	 *   - secureWriteUsers    : Swift users with write access to private containers (account:username)
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );
		// Required settings
		$this->swiftAuthUrl = $config['swiftAuthUrl'];
		$this->swiftUser = $config['swiftUser'];
		$this->swiftKey = $config['swiftKey'];
		// Optional settings
		$this->authTTL = $config['swiftAuthTTL'] ?? 15 * 60; // some sane number
		$this->swiftTempUrlKey = $config['swiftTempUrlKey'] ?? '';
		$this->swiftStorageUrl = $config['swiftStorageUrl'] ?? null;
		$this->shardViaHashLevels = $config['shardViaHashLevels'] ?? '';
		$this->rgwS3AccessKey = $config['rgwS3AccessKey'] ?? '';
		$this->rgwS3SecretKey = $config['rgwS3SecretKey'] ?? '';
		// HTTP helper client
		$this->http = new MultiHttpClient( [] );
		// Cache container information to mask latency
		if ( isset( $config['wanCache'] ) && $config['wanCache'] instanceof WANObjectCache ) {
			$this->memCache = $config['wanCache'];
		}
		// Process cache for container info
		$this->containerStatCache = new MapCacheLRU( 300 );
		// Cache auth token information to avoid RTTs
		if ( !empty( $config['cacheAuthInfo'] ) && isset( $config['srvCache'] ) ) {
			$this->srvCache = $config['srvCache'];
		} else {
			$this->srvCache = new EmptyBagOStuff();
		}
		$this->readUsers = $config['readUsers'] ?? [];
		$this->writeUsers = $config['writeUsers'] ?? [];
		$this->secureReadUsers = $config['secureReadUsers'] ?? [];
		$this->secureWriteUsers = $config['secureWriteUsers'] ?? [];
	}

	public function getFeatures() {
		return ( FileBackend::ATTR_UNICODE_PATHS |
			FileBackend::ATTR_HEADERS | FileBackend::ATTR_METADATA );
	}

	protected function resolveContainerPath( $container, $relStoragePath ) {
		if ( !mb_check_encoding( $relStoragePath, 'UTF-8' ) ) {
			return null; // not UTF-8, makes it hard to use CF and the swift HTTP API
		} elseif ( strlen( rawurlencode( $relStoragePath ) ) > 1024 ) {
			return null; // too long for Swift
		}

		return $relStoragePath;
	}

	public function isPathUsableInternal( $storagePath ) {
		list( $container, $rel ) = $this->resolveStoragePathReal( $storagePath );
		if ( $rel === null ) {
			return false; // invalid
		}

		return is_array( $this->getContainerStat( $container ) );
	}

	/**
	 * Sanitize and filter the custom headers from a $params array.
	 * Only allows certain "standard" Content- and X-Content- headers.
	 *
	 * @param array $params
	 * @return array Sanitized value of 'headers' field in $params
	 */
	protected function sanitizeHdrsStrict( array $params ) {
		if ( !isset( $params['headers'] ) ) {
			return [];
		}

		$headers = $this->getCustomHeaders( $params['headers'] );
		unset( $headers[ 'content-type' ] );

		return $headers;
	}

	/**
	 * Sanitize and filter the custom headers from a $params array.
	 * Only allows certain "standard" Content- and X-Content- headers.
	 *
	 * When POSTing data, libcurl adds Content-Type: application/x-www-form-urlencoded
	 * if Content-Type is not set, which overwrites the stored Content-Type header
	 * in Swift - therefore for POSTing data do not strip the Content-Type header (the
	 * previously-stored header that has been already read back from swift is sent)
	 *
	 * @param array $params
	 * @return array Sanitized value of 'headers' field in $params
	 */
	protected function sanitizeHdrs( array $params ) {
		return isset( $params['headers'] )
			? $this->getCustomHeaders( $params['headers'] )
			: [];
	}

	/**
	 * @param array $rawHeaders
	 * @return array Custom non-metadata HTTP headers
	 */
	protected function getCustomHeaders( array $rawHeaders ) {
		$headers = [];

		// Normalize casing, and strip out illegal headers
		foreach ( $rawHeaders as $name => $value ) {
			$name = strtolower( $name );
			if ( preg_match( '/^content-length$/', $name ) ) {
				continue; // blacklisted
			} elseif ( preg_match( '/^(x-)?content-/', $name ) ) {
				$headers[$name] = $value; // allowed
			} elseif ( preg_match( '/^content-(disposition)/', $name ) ) {
				$headers[$name] = $value; // allowed
			}
		}
		// By default, Swift has annoyingly low maximum header value limits
		if ( isset( $headers['content-disposition'] ) ) {
			$disposition = '';
			// @note: assume FileBackend::makeContentDisposition() already used
			foreach ( explode( ';', $headers['content-disposition'] ) as $part ) {
				$part = trim( $part );
				$new = ( $disposition === '' ) ? $part : "{$disposition};{$part}";
				if ( strlen( $new ) <= 255 ) {
					$disposition = $new;
				} else {
					break; // too long; sigh
				}
			}
			$headers['content-disposition'] = $disposition;
		}

		return $headers;
	}

	/**
	 * @param array $rawHeaders
	 * @return array Custom metadata headers
	 */
	protected function getMetadataHeaders( array $rawHeaders ) {
		$headers = [];
		foreach ( $rawHeaders as $name => $value ) {
			$name = strtolower( $name );
			if ( strpos( $name, 'x-object-meta-' ) === 0 ) {
				$headers[$name] = $value;
			}
		}

		return $headers;
	}

	/**
	 * @param array $rawHeaders
	 * @return array Custom metadata headers with prefix removed
	 */
	protected function getMetadata( array $rawHeaders ) {
		$metadata = [];
		foreach ( $this->getMetadataHeaders( $rawHeaders ) as $name => $value ) {
			$metadata[substr( $name, strlen( 'x-object-meta-' ) )] = $value;
		}

		return $metadata;
	}

	protected function doCreateInternal( array $params ) {
		$status = $this->newStatus();

		list( $dstCont, $dstRel ) = $this->resolveStoragePathReal( $params['dst'] );
		if ( $dstRel === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );

			return $status;
		}

		$sha1Hash = Wikimedia\base_convert( sha1( $params['content'] ), 16, 36, 31 );
		$contentType = $params['headers']['content-type']
			?? $this->getContentType( $params['dst'], $params['content'], null );

		$reqs = [ [
			'method' => 'PUT',
			'url' => [ $dstCont, $dstRel ],
			'headers' => [
				'content-length' => strlen( $params['content'] ),
				'etag' => md5( $params['content'] ),
				'content-type' => $contentType,
				'x-object-meta-sha1base36' => $sha1Hash
			] + $this->sanitizeHdrsStrict( $params ),
			'body' => $params['content']
		] ];

		$method = __METHOD__;
		$handler = function ( array $request, StatusValue $status ) use ( $method, $params ) {
			list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $request['response'];
			if ( $rcode === 201 ) {
				// good
			} elseif ( $rcode === 412 ) {
				$status->fatal( 'backend-fail-contenttype', $params['dst'] );
			} else {
				$this->onError( $status, $method, $params, $rerr, $rcode, $rdesc );
			}
		};

		$opHandle = new SwiftFileOpHandle( $this, $handler, $reqs );
		if ( !empty( $params['async'] ) ) { // deferred
			$status->value = $opHandle;
		} else { // actually write the object in Swift
			$status->merge( current( $this->executeOpHandlesInternal( [ $opHandle ] ) ) );
		}

		return $status;
	}

	protected function doStoreInternal( array $params ) {
		$status = $this->newStatus();

		list( $dstCont, $dstRel ) = $this->resolveStoragePathReal( $params['dst'] );
		if ( $dstRel === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );

			return $status;
		}

		Wikimedia\suppressWarnings();
		$sha1Hash = sha1_file( $params['src'] );
		Wikimedia\restoreWarnings();
		if ( $sha1Hash === false ) { // source doesn't exist?
			$status->fatal( 'backend-fail-store', $params['src'], $params['dst'] );

			return $status;
		}
		$sha1Hash = Wikimedia\base_convert( $sha1Hash, 16, 36, 31 );
		$contentType = $params['headers']['content-type']
			?? $this->getContentType( $params['dst'], null, $params['src'] );

		$handle = fopen( $params['src'], 'rb' );
		if ( $handle === false ) { // source doesn't exist?
			$status->fatal( 'backend-fail-store', $params['src'], $params['dst'] );

			return $status;
		}

		$reqs = [ [
			'method' => 'PUT',
			'url' => [ $dstCont, $dstRel ],
			'headers' => [
				'content-length' => filesize( $params['src'] ),
				'etag' => md5_file( $params['src'] ),
				'content-type' => $contentType,
				'x-object-meta-sha1base36' => $sha1Hash
			] + $this->sanitizeHdrsStrict( $params ),
			'body' => $handle // resource
		] ];

		$method = __METHOD__;
		$handler = function ( array $request, StatusValue $status ) use ( $method, $params ) {
			list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $request['response'];
			if ( $rcode === 201 ) {
				// good
			} elseif ( $rcode === 412 ) {
				$status->fatal( 'backend-fail-contenttype', $params['dst'] );
			} else {
				$this->onError( $status, $method, $params, $rerr, $rcode, $rdesc );
			}
		};

		$opHandle = new SwiftFileOpHandle( $this, $handler, $reqs );
		$opHandle->resourcesToClose[] = $handle;

		if ( !empty( $params['async'] ) ) { // deferred
			$status->value = $opHandle;
		} else { // actually write the object in Swift
			$status->merge( current( $this->executeOpHandlesInternal( [ $opHandle ] ) ) );
		}

		return $status;
	}

	protected function doCopyInternal( array $params ) {
		$status = $this->newStatus();

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

		$reqs = [ [
			'method' => 'PUT',
			'url' => [ $dstCont, $dstRel ],
			'headers' => [
				'x-copy-from' => '/' . rawurlencode( $srcCont ) .
					'/' . str_replace( "%2F", "/", rawurlencode( $srcRel ) )
			] + $this->sanitizeHdrsStrict( $params ), // extra headers merged into object
		] ];

		$method = __METHOD__;
		$handler = function ( array $request, StatusValue $status ) use ( $method, $params ) {
			list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $request['response'];
			if ( $rcode === 201 ) {
				// good
			} elseif ( $rcode === 404 ) {
				$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
			} else {
				$this->onError( $status, $method, $params, $rerr, $rcode, $rdesc );
			}
		};

		$opHandle = new SwiftFileOpHandle( $this, $handler, $reqs );
		if ( !empty( $params['async'] ) ) { // deferred
			$status->value = $opHandle;
		} else { // actually write the object in Swift
			$status->merge( current( $this->executeOpHandlesInternal( [ $opHandle ] ) ) );
		}

		return $status;
	}

	protected function doMoveInternal( array $params ) {
		$status = $this->newStatus();

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

		$reqs = [
			[
				'method' => 'PUT',
				'url' => [ $dstCont, $dstRel ],
				'headers' => [
					'x-copy-from' => '/' . rawurlencode( $srcCont ) .
						'/' . str_replace( "%2F", "/", rawurlencode( $srcRel ) )
				] + $this->sanitizeHdrsStrict( $params ) // extra headers merged into object
			]
		];
		if ( "{$srcCont}/{$srcRel}" !== "{$dstCont}/{$dstRel}" ) {
			$reqs[] = [
				'method' => 'DELETE',
				'url' => [ $srcCont, $srcRel ],
				'headers' => []
			];
		}

		$method = __METHOD__;
		$handler = function ( array $request, StatusValue $status ) use ( $method, $params ) {
			list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $request['response'];
			if ( $request['method'] === 'PUT' && $rcode === 201 ) {
				// good
			} elseif ( $request['method'] === 'DELETE' && $rcode === 204 ) {
				// good
			} elseif ( $rcode === 404 ) {
				$status->fatal( 'backend-fail-move', $params['src'], $params['dst'] );
			} else {
				$this->onError( $status, $method, $params, $rerr, $rcode, $rdesc );
			}
		};

		$opHandle = new SwiftFileOpHandle( $this, $handler, $reqs );
		if ( !empty( $params['async'] ) ) { // deferred
			$status->value = $opHandle;
		} else { // actually move the object in Swift
			$status->merge( current( $this->executeOpHandlesInternal( [ $opHandle ] ) ) );
		}

		return $status;
	}

	protected function doDeleteInternal( array $params ) {
		$status = $this->newStatus();

		list( $srcCont, $srcRel ) = $this->resolveStoragePathReal( $params['src'] );
		if ( $srcRel === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['src'] );

			return $status;
		}

		$reqs = [ [
			'method' => 'DELETE',
			'url' => [ $srcCont, $srcRel ],
			'headers' => []
		] ];

		$method = __METHOD__;
		$handler = function ( array $request, StatusValue $status ) use ( $method, $params ) {
			list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $request['response'];
			if ( $rcode === 204 ) {
				// good
			} elseif ( $rcode === 404 ) {
				if ( empty( $params['ignoreMissingSource'] ) ) {
					$status->fatal( 'backend-fail-delete', $params['src'] );
				}
			} else {
				$this->onError( $status, $method, $params, $rerr, $rcode, $rdesc );
			}
		};

		$opHandle = new SwiftFileOpHandle( $this, $handler, $reqs );
		if ( !empty( $params['async'] ) ) { // deferred
			$status->value = $opHandle;
		} else { // actually delete the object in Swift
			$status->merge( current( $this->executeOpHandlesInternal( [ $opHandle ] ) ) );
		}

		return $status;
	}

	protected function doDescribeInternal( array $params ) {
		$status = $this->newStatus();

		list( $srcCont, $srcRel ) = $this->resolveStoragePathReal( $params['src'] );
		if ( $srcRel === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['src'] );

			return $status;
		}

		// Fetch the old object headers/metadata...this should be in stat cache by now
		$stat = $this->getFileStat( [ 'src' => $params['src'], 'latest' => 1 ] );
		if ( $stat && !isset( $stat['xattr'] ) ) { // older cache entry
			$stat = $this->doGetFileStat( [ 'src' => $params['src'], 'latest' => 1 ] );
		}
		if ( !$stat ) {
			$status->fatal( 'backend-fail-describe', $params['src'] );

			return $status;
		}

		// POST clears prior headers, so we need to merge the changes in to the old ones
		$metaHdrs = [];
		foreach ( $stat['xattr']['metadata'] as $name => $value ) {
			$metaHdrs["x-object-meta-$name"] = $value;
		}
		$customHdrs = $this->sanitizeHdrs( $params ) + $stat['xattr']['headers'];

		$reqs = [ [
			'method' => 'POST',
			'url' => [ $srcCont, $srcRel ],
			'headers' => $metaHdrs + $customHdrs
		] ];

		$method = __METHOD__;
		$handler = function ( array $request, StatusValue $status ) use ( $method, $params ) {
			list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $request['response'];
			if ( $rcode === 202 ) {
				// good
			} elseif ( $rcode === 404 ) {
				$status->fatal( 'backend-fail-describe', $params['src'] );
			} else {
				$this->onError( $status, $method, $params, $rerr, $rcode, $rdesc );
			}
		};

		$opHandle = new SwiftFileOpHandle( $this, $handler, $reqs );
		if ( !empty( $params['async'] ) ) { // deferred
			$status->value = $opHandle;
		} else { // actually change the object in Swift
			$status->merge( current( $this->executeOpHandlesInternal( [ $opHandle ] ) ) );
		}

		return $status;
	}

	protected function doPrepareInternal( $fullCont, $dir, array $params ) {
		$status = $this->newStatus();

		// (a) Check if container already exists
		$stat = $this->getContainerStat( $fullCont );
		if ( is_array( $stat ) ) {
			return $status; // already there
		} elseif ( $stat === null ) {
			$status->fatal( 'backend-fail-internal', $this->name );
			$this->logger->error( __METHOD__ . ': cannot get container stat' );

			return $status;
		}

		// (b) Create container as needed with proper ACLs
		if ( $stat === false ) {
			$params['op'] = 'prepare';
			$status->merge( $this->createContainer( $fullCont, $params ) );
		}

		return $status;
	}

	protected function doSecureInternal( $fullCont, $dir, array $params ) {
		$status = $this->newStatus();
		if ( empty( $params['noAccess'] ) ) {
			return $status; // nothing to do
		}

		$stat = $this->getContainerStat( $fullCont );
		if ( is_array( $stat ) ) {
			$readUsers = array_merge( $this->secureReadUsers, [ $this->swiftUser ] );
			$writeUsers = array_merge( $this->secureWriteUsers, [ $this->swiftUser ] );
			// Make container private to end-users...
			$status->merge( $this->setContainerAccess(
				$fullCont,
				$readUsers,
				$writeUsers
			) );
		} elseif ( $stat === false ) {
			$status->fatal( 'backend-fail-usable', $params['dir'] );
		} else {
			$status->fatal( 'backend-fail-internal', $this->name );
			$this->logger->error( __METHOD__ . ': cannot get container stat' );
		}

		return $status;
	}

	protected function doPublishInternal( $fullCont, $dir, array $params ) {
		$status = $this->newStatus();

		$stat = $this->getContainerStat( $fullCont );
		if ( is_array( $stat ) ) {
			$readUsers = array_merge( $this->readUsers, [ $this->swiftUser, '.r:*' ] );
			$writeUsers = array_merge( $this->writeUsers, [ $this->swiftUser ] );

			// Make container public to end-users...
			$status->merge( $this->setContainerAccess(
				$fullCont,
				$readUsers,
				$writeUsers
			) );
		} elseif ( $stat === false ) {
			$status->fatal( 'backend-fail-usable', $params['dir'] );
		} else {
			$status->fatal( 'backend-fail-internal', $this->name );
			$this->logger->error( __METHOD__ . ': cannot get container stat' );
		}

		return $status;
	}

	protected function doCleanInternal( $fullCont, $dir, array $params ) {
		$status = $this->newStatus();

		// Only containers themselves can be removed, all else is virtual
		if ( $dir != '' ) {
			return $status; // nothing to do
		}

		// (a) Check the container
		$stat = $this->getContainerStat( $fullCont, true );
		if ( $stat === false ) {
			return $status; // ok, nothing to do
		} elseif ( !is_array( $stat ) ) {
			$status->fatal( 'backend-fail-internal', $this->name );
			$this->logger->error( __METHOD__ . ': cannot get container stat' );

			return $status;
		}

		// (b) Delete the container if empty
		if ( $stat['count'] == 0 ) {
			$params['op'] = 'clean';
			$status->merge( $this->deleteContainer( $fullCont, $params ) );
		}

		return $status;
	}

	protected function doGetFileStat( array $params ) {
		$params = [ 'srcs' => [ $params['src'] ], 'concurrency' => 1 ] + $params;
		unset( $params['src'] );
		$stats = $this->doGetFileStatMulti( $params );

		return reset( $stats );
	}

	/**
	 * Convert dates like "Tue, 03 Jan 2012 22:01:04 GMT"/"2013-05-11T07:37:27.678360Z".
	 * Dates might also come in like "2013-05-11T07:37:27.678360" from Swift listings,
	 * missing the timezone suffix (though Ceph RGW does not appear to have this bug).
	 *
	 * @param string $ts
	 * @param int $format Output format (TS_* constant)
	 * @return string
	 * @throws FileBackendError
	 */
	protected function convertSwiftDate( $ts, $format = TS_MW ) {
		try {
			$timestamp = new MWTimestamp( $ts );

			return $timestamp->getTimestamp( $format );
		} catch ( Exception $e ) {
			throw new FileBackendError( $e->getMessage() );
		}
	}

	/**
	 * Fill in any missing object metadata and save it to Swift
	 *
	 * @param array $objHdrs Object response headers
	 * @param string $path Storage path to object
	 * @return array New headers
	 */
	protected function addMissingMetadata( array $objHdrs, $path ) {
		if ( isset( $objHdrs['x-object-meta-sha1base36'] ) ) {
			return $objHdrs; // nothing to do
		}

		/** @noinspection PhpUnusedLocalVariableInspection */
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		$this->logger->error( __METHOD__ . ": {path} was not stored with SHA-1 metadata.",
			[ 'path' => $path ] );

		$objHdrs['x-object-meta-sha1base36'] = false;

		$auth = $this->getAuthentication();
		if ( !$auth ) {
			return $objHdrs; // failed
		}

		// Find prior custom HTTP headers
		$postHeaders = $this->getCustomHeaders( $objHdrs );
		// Find prior metadata headers
		$postHeaders += $this->getMetadataHeaders( $objHdrs );

		$status = $this->newStatus();
		/** @noinspection PhpUnusedLocalVariableInspection */
		$scopeLockS = $this->getScopedFileLocks( [ $path ], LockManager::LOCK_UW, $status );
		if ( $status->isOK() ) {
			$tmpFile = $this->getLocalCopy( [ 'src' => $path, 'latest' => 1 ] );
			if ( $tmpFile ) {
				$hash = $tmpFile->getSha1Base36();
				if ( $hash !== false ) {
					$objHdrs['x-object-meta-sha1base36'] = $hash;
					// Merge new SHA1 header into the old ones
					$postHeaders['x-object-meta-sha1base36'] = $hash;
					list( $srcCont, $srcRel ) = $this->resolveStoragePathReal( $path );
					list( $rcode ) = $this->http->run( [
						'method' => 'POST',
						'url' => $this->storageUrl( $auth, $srcCont, $srcRel ),
						'headers' => $this->authTokenHeaders( $auth ) + $postHeaders
					] );
					if ( $rcode >= 200 && $rcode <= 299 ) {
						$this->deleteFileCache( $path );

						return $objHdrs; // success
					}
				}
			}
		}

		$this->logger->error( __METHOD__ . ': unable to set SHA-1 metadata for {path}',
			[ 'path' => $path ] );

		return $objHdrs; // failed
	}

	protected function doGetFileContentsMulti( array $params ) {
		$contents = [];

		$auth = $this->getAuthentication();

		$ep = array_diff_key( $params, [ 'srcs' => 1 ] ); // for error logging
		// Blindly create tmp files and stream to them, catching any exception if the file does
		// not exist. Doing stats here is useless and will loop infinitely in addMissingMetadata().
		$reqs = []; // (path => op)

		foreach ( $params['srcs'] as $path ) { // each path in this concurrent batch
			list( $srcCont, $srcRel ) = $this->resolveStoragePathReal( $path );
			if ( $srcRel === null || !$auth ) {
				$contents[$path] = false;
				continue;
			}
			// Create a new temporary memory file...
			$handle = fopen( 'php://temp', 'wb' );
			if ( $handle ) {
				$reqs[$path] = [
					'method'  => 'GET',
					'url'     => $this->storageUrl( $auth, $srcCont, $srcRel ),
					'headers' => $this->authTokenHeaders( $auth )
						+ $this->headersFromParams( $params ),
					'stream'  => $handle,
				];
			}
			$contents[$path] = false;
		}

		$opts = [ 'maxConnsPerHost' => $params['concurrency'] ];
		$reqs = $this->http->runMulti( $reqs, $opts );
		foreach ( $reqs as $path => $op ) {
			list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $op['response'];
			if ( $rcode >= 200 && $rcode <= 299 ) {
				rewind( $op['stream'] ); // start from the beginning
				$contents[$path] = stream_get_contents( $op['stream'] );
			} elseif ( $rcode === 404 ) {
				$contents[$path] = false;
			} else {
				$this->onError( null, __METHOD__,
					[ 'src' => $path ] + $ep, $rerr, $rcode, $rdesc );
			}
			fclose( $op['stream'] ); // close open handle
		}

		return $contents;
	}

	protected function doDirectoryExists( $fullCont, $dir, array $params ) {
		$prefix = ( $dir == '' ) ? null : "{$dir}/";
		$status = $this->objectListing( $fullCont, 'names', 1, null, $prefix );
		if ( $status->isOK() ) {
			return ( count( $status->value ) ) > 0;
		}

		return null; // error
	}

	/**
	 * @see FileBackendStore::getDirectoryListInternal()
	 * @param string $fullCont
	 * @param string $dir
	 * @param array $params
	 * @return SwiftFileBackendDirList
	 */
	public function getDirectoryListInternal( $fullCont, $dir, array $params ) {
		return new SwiftFileBackendDirList( $this, $fullCont, $dir, $params );
	}

	/**
	 * @see FileBackendStore::getFileListInternal()
	 * @param string $fullCont
	 * @param string $dir
	 * @param array $params
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
	 * @param string|null &$after Resolved container relative path to list items after
	 * @param int $limit Max number of items to list
	 * @param array $params Parameters for getDirectoryList()
	 * @return array List of container relative resolved paths of directories directly under $dir
	 * @throws FileBackendError
	 */
	public function getDirListPageInternal( $fullCont, $dir, &$after, $limit, array $params ) {
		$dirs = [];
		if ( $after === INF ) {
			return $dirs; // nothing more
		}

		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		$prefix = ( $dir == '' ) ? null : "{$dir}/";
		// Non-recursive: only list dirs right under $dir
		if ( !empty( $params['topOnly'] ) ) {
			$status = $this->objectListing( $fullCont, 'names', $limit, $after, $prefix, '/' );
			if ( !$status->isOK() ) {
				throw new FileBackendError( "Iterator page I/O error." );
			}
			$objects = $status->value;
			foreach ( $objects as $object ) { // files and directories
				if ( substr( $object, -1 ) === '/' ) {
					$dirs[] = $object; // directories end in '/'
				}
			}
		} else {
			// Recursive: list all dirs under $dir and its subdirs
			$getParentDir = function ( $path ) {
				return ( strpos( $path, '/' ) !== false ) ? dirname( $path ) : false;
			};

			// Get directory from last item of prior page
			$lastDir = $getParentDir( $after ); // must be first page
			$status = $this->objectListing( $fullCont, 'names', $limit, $after, $prefix );

			if ( !$status->isOK() ) {
				throw new FileBackendError( "Iterator page I/O error." );
			}

			$objects = $status->value;

			foreach ( $objects as $object ) { // files
				$objectDir = $getParentDir( $object ); // directory of object

				if ( $objectDir !== false && $objectDir !== $dir ) {
					// Swift stores paths in UTF-8, using binary sorting.
					// See function "create_container_table" in common/db.py.
					// If a directory is not "greater" than the last one,
					// then it was already listed by the calling iterator.
					if ( strcmp( $objectDir, $lastDir ) > 0 ) {
						$pDir = $objectDir;
						do { // add dir and all its parent dirs
							$dirs[] = "{$pDir}/";
							$pDir = $getParentDir( $pDir );
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

		return $dirs;
	}

	/**
	 * Do not call this function outside of SwiftFileBackendFileList
	 *
	 * @param string $fullCont Resolved container name
	 * @param string $dir Resolved storage directory with no trailing slash
	 * @param string|null &$after Resolved container relative path of file to list items after
	 * @param int $limit Max number of items to list
	 * @param array $params Parameters for getDirectoryList()
	 * @return array List of resolved container relative paths of files under $dir
	 * @throws FileBackendError
	 */
	public function getFileListPageInternal( $fullCont, $dir, &$after, $limit, array $params ) {
		$files = []; // list of (path, stat array or null) entries
		if ( $after === INF ) {
			return $files; // nothing more
		}

		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		$prefix = ( $dir == '' ) ? null : "{$dir}/";
		// $objects will contain a list of unfiltered names or CF_Object items
		// Non-recursive: only list files right under $dir
		if ( !empty( $params['topOnly'] ) ) {
			if ( !empty( $params['adviseStat'] ) ) {
				$status = $this->objectListing( $fullCont, 'info', $limit, $after, $prefix, '/' );
			} else {
				$status = $this->objectListing( $fullCont, 'names', $limit, $after, $prefix, '/' );
			}
		} else {
			// Recursive: list all files under $dir and its subdirs
			if ( !empty( $params['adviseStat'] ) ) {
				$status = $this->objectListing( $fullCont, 'info', $limit, $after, $prefix );
			} else {
				$status = $this->objectListing( $fullCont, 'names', $limit, $after, $prefix );
			}
		}

		// Reformat this list into a list of (name, stat array or null) entries
		if ( !$status->isOK() ) {
			throw new FileBackendError( "Iterator page I/O error." );
		}

		$objects = $status->value;
		$files = $this->buildFileObjectListing( $params, $dir, $objects );

		// Page on the unfiltered object listing (what is returned may be filtered)
		if ( count( $objects ) < $limit ) {
			$after = INF; // avoid a second RTT
		} else {
			$after = end( $objects ); // update last item
			$after = is_object( $after ) ? $after->name : $after;
		}

		return $files;
	}

	/**
	 * Build a list of file objects, filtering out any directories
	 * and extracting any stat info if provided in $objects (for CF_Objects)
	 *
	 * @param array $params Parameters for getDirectoryList()
	 * @param string $dir Resolved container directory path
	 * @param array $objects List of CF_Object items or object names
	 * @return array List of (names,stat array or null) entries
	 */
	private function buildFileObjectListing( array $params, $dir, array $objects ) {
		$names = [];
		foreach ( $objects as $object ) {
			if ( is_object( $object ) ) {
				if ( isset( $object->subdir ) || !isset( $object->name ) ) {
					continue; // virtual directory entry; ignore
				}
				$stat = [
					// Convert various random Swift dates to TS_MW
					'mtime'  => $this->convertSwiftDate( $object->last_modified, TS_MW ),
					'size'   => (int)$object->bytes,
					'sha1'   => null,
					// Note: manifiest ETags are not an MD5 of the file
					'md5'    => ctype_xdigit( $object->hash ) ? $object->hash : null,
					'latest' => false // eventually consistent
				];
				$names[] = [ $object->name, $stat ];
			} elseif ( substr( $object, -1 ) !== '/' ) {
				// Omit directories, which end in '/' in listings
				$names[] = [ $object, null ];
			}
		}

		return $names;
	}

	/**
	 * Do not call this function outside of SwiftFileBackendFileList
	 *
	 * @param string $path Storage path
	 * @param array $val Stat value
	 */
	public function loadListingStatInternal( $path, array $val ) {
		$this->cheapCache->setField( $path, 'stat', $val );
	}

	protected function doGetFileXAttributes( array $params ) {
		$stat = $this->getFileStat( $params );
		if ( $stat ) {
			if ( !isset( $stat['xattr'] ) ) {
				// Stat entries filled by file listings don't include metadata/headers
				$this->clearCache( [ $params['src'] ] );
				$stat = $this->getFileStat( $params );
			}

			return $stat['xattr'];
		} else {
			return false;
		}
	}

	protected function doGetFileSha1base36( array $params ) {
		$stat = $this->getFileStat( $params );
		if ( $stat ) {
			if ( !isset( $stat['sha1'] ) ) {
				// Stat entries filled by file listings don't include SHA1
				$this->clearCache( [ $params['src'] ] );
				$stat = $this->getFileStat( $params );
			}

			return $stat['sha1'];
		} else {
			return false;
		}
	}

	protected function doStreamFile( array $params ) {
		$status = $this->newStatus();

		$flags = !empty( $params['headless'] ) ? StreamFile::STREAM_HEADLESS : 0;

		list( $srcCont, $srcRel ) = $this->resolveStoragePathReal( $params['src'] );
		if ( $srcRel === null ) {
			StreamFile::send404Message( $params['src'], $flags );
			$status->fatal( 'backend-fail-invalidpath', $params['src'] );

			return $status;
		}

		$auth = $this->getAuthentication();
		if ( !$auth || !is_array( $this->getContainerStat( $srcCont ) ) ) {
			StreamFile::send404Message( $params['src'], $flags );
			$status->fatal( 'backend-fail-stream', $params['src'] );

			return $status;
		}

		// If "headers" is set, we only want to send them if the file is there.
		// Do not bother checking if the file exists if headers are not set though.
		if ( $params['headers'] && !$this->fileExists( $params ) ) {
			StreamFile::send404Message( $params['src'], $flags );
			$status->fatal( 'backend-fail-stream', $params['src'] );

			return $status;
		}

		// Send the requested additional headers
		foreach ( $params['headers'] as $header ) {
			header( $header ); // aways send
		}

		if ( empty( $params['allowOB'] ) ) {
			// Cancel output buffering and gzipping if set
			( $this->obResetFunc )();
		}

		$handle = fopen( 'php://output', 'wb' );
		list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $this->http->run( [
			'method' => 'GET',
			'url' => $this->storageUrl( $auth, $srcCont, $srcRel ),
			'headers' => $this->authTokenHeaders( $auth )
				+ $this->headersFromParams( $params ) + $params['options'],
			'stream' => $handle,
			'flags'  => [ 'relayResponseHeaders' => empty( $params['headless'] ) ]
		] );

		if ( $rcode >= 200 && $rcode <= 299 ) {
			// good
		} elseif ( $rcode === 404 ) {
			$status->fatal( 'backend-fail-stream', $params['src'] );
			// Per T43113, nasty things can happen if bad cache entries get
			// stuck in cache. It's also possible that this error can come up
			// with simple race conditions. Clear out the stat cache to be safe.
			$this->clearCache( [ $params['src'] ] );
			$this->deleteFileCache( $params['src'] );
		} else {
			$this->onError( $status, __METHOD__, $params, $rerr, $rcode, $rdesc );
		}

		return $status;
	}

	protected function doGetLocalCopyMulti( array $params ) {
		/** @var TempFSFile[] $tmpFiles */
		$tmpFiles = [];

		$auth = $this->getAuthentication();

		$ep = array_diff_key( $params, [ 'srcs' => 1 ] ); // for error logging
		// Blindly create tmp files and stream to them, catching any exception if the file does
		// not exist. Doing a stat here is useless causes infinite loops in addMissingMetadata().
		$reqs = []; // (path => op)

		foreach ( $params['srcs'] as $path ) { // each path in this concurrent batch
			list( $srcCont, $srcRel ) = $this->resolveStoragePathReal( $path );
			if ( $srcRel === null || !$auth ) {
				$tmpFiles[$path] = null;
				continue;
			}
			// Get source file extension
			$ext = FileBackend::extensionFromPath( $path );
			// Create a new temporary file...
			$tmpFile = TempFSFile::factory( 'localcopy_', $ext, $this->tmpDirectory );
			if ( $tmpFile ) {
				$handle = fopen( $tmpFile->getPath(), 'wb' );
				if ( $handle ) {
					$reqs[$path] = [
						'method'  => 'GET',
						'url'     => $this->storageUrl( $auth, $srcCont, $srcRel ),
						'headers' => $this->authTokenHeaders( $auth )
							+ $this->headersFromParams( $params ),
						'stream'  => $handle,
					];
				} else {
					$tmpFile = null;
				}
			}
			$tmpFiles[$path] = $tmpFile;
		}

		$isLatest = ( $this->isRGW || !empty( $params['latest'] ) );
		$opts = [ 'maxConnsPerHost' => $params['concurrency'] ];
		$reqs = $this->http->runMulti( $reqs, $opts );
		foreach ( $reqs as $path => $op ) {
			list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $op['response'];
			fclose( $op['stream'] ); // close open handle
			if ( $rcode >= 200 && $rcode <= 299 ) {
				$size = $tmpFiles[$path] ? $tmpFiles[$path]->getSize() : 0;
				// Double check that the disk is not full/broken
				if ( $size != $rhdrs['content-length'] ) {
					$tmpFiles[$path] = null;
					$rerr = "Got {$size}/{$rhdrs['content-length']} bytes";
					$this->onError( null, __METHOD__,
						[ 'src' => $path ] + $ep, $rerr, $rcode, $rdesc );
				}
				// Set the file stat process cache in passing
				$stat = $this->getStatFromHeaders( $rhdrs );
				$stat['latest'] = $isLatest;
				$this->cheapCache->setField( $path, 'stat', $stat );
			} elseif ( $rcode === 404 ) {
				$tmpFiles[$path] = false;
			} else {
				$tmpFiles[$path] = null;
				$this->onError( null, __METHOD__,
					[ 'src' => $path ] + $ep, $rerr, $rcode, $rdesc );
			}
		}

		return $tmpFiles;
	}

	public function getFileHttpUrl( array $params ) {
		if ( $this->swiftTempUrlKey != '' ||
			( $this->rgwS3AccessKey != '' && $this->rgwS3SecretKey != '' )
		) {
			list( $srcCont, $srcRel ) = $this->resolveStoragePathReal( $params['src'] );
			if ( $srcRel === null ) {
				return null; // invalid path
			}

			$auth = $this->getAuthentication();
			if ( !$auth ) {
				return null;
			}

			$ttl = $params['ttl'] ?? 86400;
			$expires = time() + $ttl;

			if ( $this->swiftTempUrlKey != '' ) {
				$url = $this->storageUrl( $auth, $srcCont, $srcRel );
				// Swift wants the signature based on the unencoded object name
				$contPath = parse_url( $this->storageUrl( $auth, $srcCont ), PHP_URL_PATH );
				$signature = hash_hmac( 'sha1',
					"GET\n{$expires}\n{$contPath}/{$srcRel}",
					$this->swiftTempUrlKey
				);

				return "{$url}?temp_url_sig={$signature}&temp_url_expires={$expires}";
			} else { // give S3 API URL for rgw
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
				// See https://s3.amazonaws.com/doc/s3-developer-guide/RESTAuthentication.html.
				// Note: adding a newline for empty CanonicalizedAmzHeaders does not work.
				// Note: S3 API is the rgw default; remove the /swift/ URL bit.
				return str_replace( '/swift/v1', '', $this->storageUrl( $auth ) . $spath ) .
					'?' .
					http_build_query( [
						'Signature' => $signature,
						'Expires' => $expires,
						'AWSAccessKeyId' => $this->rgwS3AccessKey
					] );
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
	 * @return array
	 */
	protected function headersFromParams( array $params ) {
		$hdrs = [];
		if ( !empty( $params['latest'] ) ) {
			$hdrs['x-newest'] = 'true';
		}

		return $hdrs;
	}

	/**
	 * @param FileBackendStoreOpHandle[] $fileOpHandles
	 *
	 * @return StatusValue[]
	 */
	protected function doExecuteOpHandlesInternal( array $fileOpHandles ) {
		/** @var StatusValue[] $statuses */
		$statuses = [];

		$auth = $this->getAuthentication();
		if ( !$auth ) {
			foreach ( $fileOpHandles as $index => $fileOpHandle ) {
				$statuses[$index] = $this->newStatus( 'backend-fail-connect', $this->name );
			}

			return $statuses;
		}

		// Split the HTTP requests into stages that can be done concurrently
		$httpReqsByStage = []; // map of (stage => index => HTTP request)
		foreach ( $fileOpHandles as $index => $fileOpHandle ) {
			/** @var SwiftFileOpHandle $fileOpHandle */
			$reqs = $fileOpHandle->httpOp;
			// Convert the 'url' parameter to an actual URL using $auth
			foreach ( $reqs as $stage => &$req ) {
				list( $container, $relPath ) = $req['url'];
				$req['url'] = $this->storageUrl( $auth, $container, $relPath );
				$req['headers'] = $req['headers'] ?? [];
				$req['headers'] = $this->authTokenHeaders( $auth ) + $req['headers'];
				$httpReqsByStage[$stage][$index] = $req;
			}
			$statuses[$index] = $this->newStatus();
		}

		// Run all requests for the first stage, then the next, and so on
		$reqCount = count( $httpReqsByStage );
		for ( $stage = 0; $stage < $reqCount; ++$stage ) {
			$httpReqs = $this->http->runMulti( $httpReqsByStage[$stage] );
			foreach ( $httpReqs as $index => $httpReq ) {
				// Run the callback for each request of this operation
				$callback = $fileOpHandles[$index]->callback;
				$callback( $httpReq, $statuses[$index] );
				// On failure, abort all remaining requests for this operation
				// (e.g. abort the DELETE request if the COPY request fails for a move)
				if ( !$statuses[$index]->isOK() ) {
					$stages = count( $fileOpHandles[$index]->httpOp );
					for ( $s = ( $stage + 1 ); $s < $stages; ++$s ) {
						unset( $httpReqsByStage[$s][$index] );
					}
				}
			}
		}

		return $statuses;
	}

	/**
	 * Set read/write permissions for a Swift container.
	 *
	 * @see http://docs.openstack.org/developer/swift/misc.html#acls
	 *
	 * In general, we don't allow listings to end-users. It's not useful, isn't well-defined
	 * (lists are truncated to 10000 item with no way to page), and is just a performance risk.
	 *
	 * @param string $container Resolved Swift container
	 * @param array $readUsers List of the possible criteria for a request to have
	 * access to read a container. Each item is one of the following formats:
	 *   - account:user        : Grants access if the request is by the given user
	 *   - ".r:<regex>"        : Grants access if the request is from a referrer host that
	 *                           matches the expression and the request is not for a listing.
	 *                           Setting this to '*' effectively makes a container public.
	 *   -".rlistings:<regex>" : Grants access if the request is from a referrer host that
	 *                           matches the expression and the request is for a listing.
	 * @param array $writeUsers A list of the possible criteria for a request to have
	 * access to write to a container. Each item is of the following format:
	 *   - account:user       : Grants access if the request is by the given user
	 * @return StatusValue
	 */
	protected function setContainerAccess( $container, array $readUsers, array $writeUsers ) {
		$status = $this->newStatus();
		$auth = $this->getAuthentication();

		if ( !$auth ) {
			$status->fatal( 'backend-fail-connect', $this->name );

			return $status;
		}

		list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $this->http->run( [
			'method' => 'POST',
			'url' => $this->storageUrl( $auth, $container ),
			'headers' => $this->authTokenHeaders( $auth ) + [
				'x-container-read' => implode( ',', $readUsers ),
				'x-container-write' => implode( ',', $writeUsers )
			]
		] );

		if ( $rcode != 204 && $rcode !== 202 ) {
			$status->fatal( 'backend-fail-internal', $this->name );
			$this->logger->error( __METHOD__ . ': unexpected rcode value ({rcode})',
				[ 'rcode' => $rcode ] );
		}

		return $status;
	}

	/**
	 * Get a Swift container stat array, possibly from process cache.
	 * Use $reCache if the file count or byte count is needed.
	 *
	 * @param string $container Container name
	 * @param bool $bypassCache Bypass all caches and load from Swift
	 * @return array|bool|null False on 404, null on failure
	 */
	protected function getContainerStat( $container, $bypassCache = false ) {
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		if ( $bypassCache ) { // purge cache
			$this->containerStatCache->clear( $container );
		} elseif ( !$this->containerStatCache->hasField( $container, 'stat' ) ) {
			$this->primeContainerCache( [ $container ] ); // check persistent cache
		}
		if ( !$this->containerStatCache->hasField( $container, 'stat' ) ) {
			$auth = $this->getAuthentication();
			if ( !$auth ) {
				return null;
			}

			list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $this->http->run( [
				'method' => 'HEAD',
				'url' => $this->storageUrl( $auth, $container ),
				'headers' => $this->authTokenHeaders( $auth )
			] );

			if ( $rcode === 204 ) {
				$stat = [
					'count' => $rhdrs['x-container-object-count'],
					'bytes' => $rhdrs['x-container-bytes-used']
				];
				if ( $bypassCache ) {
					return $stat;
				} else {
					$this->containerStatCache->setField( $container, 'stat', $stat ); // cache it
					$this->setContainerCache( $container, $stat ); // update persistent cache
				}
			} elseif ( $rcode === 404 ) {
				return false;
			} else {
				$this->onError( null, __METHOD__,
					[ 'cont' => $container ], $rerr, $rcode, $rdesc );

				return null;
			}
		}

		return $this->containerStatCache->getField( $container, 'stat' );
	}

	/**
	 * Create a Swift container
	 *
	 * @param string $container Container name
	 * @param array $params
	 * @return StatusValue
	 */
	protected function createContainer( $container, array $params ) {
		$status = $this->newStatus();

		$auth = $this->getAuthentication();
		if ( !$auth ) {
			$status->fatal( 'backend-fail-connect', $this->name );

			return $status;
		}

		// @see SwiftFileBackend::setContainerAccess()
		if ( empty( $params['noAccess'] ) ) {
			// public
			$readUsers = array_merge( $this->readUsers, [ '.r:*', $this->swiftUser ] );
			$writeUsers = array_merge( $this->writeUsers, [ $this->swiftUser ] );
		} else {
			// private
			$readUsers = array_merge( $this->secureReadUsers, [ $this->swiftUser ] );
			$writeUsers = array_merge( $this->secureWriteUsers, [ $this->swiftUser ] );
		}

		list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $this->http->run( [
			'method' => 'PUT',
			'url' => $this->storageUrl( $auth, $container ),
			'headers' => $this->authTokenHeaders( $auth ) + [
				'x-container-read' => implode( ',', $readUsers ),
				'x-container-write' => implode( ',', $writeUsers )
			]
		] );

		if ( $rcode === 201 ) { // new
			// good
		} elseif ( $rcode === 202 ) { // already there
			// this shouldn't really happen, but is OK
		} else {
			$this->onError( $status, __METHOD__, $params, $rerr, $rcode, $rdesc );
		}

		return $status;
	}

	/**
	 * Delete a Swift container
	 *
	 * @param string $container Container name
	 * @param array $params
	 * @return StatusValue
	 */
	protected function deleteContainer( $container, array $params ) {
		$status = $this->newStatus();

		$auth = $this->getAuthentication();
		if ( !$auth ) {
			$status->fatal( 'backend-fail-connect', $this->name );

			return $status;
		}

		list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $this->http->run( [
			'method' => 'DELETE',
			'url' => $this->storageUrl( $auth, $container ),
			'headers' => $this->authTokenHeaders( $auth )
		] );

		if ( $rcode >= 200 && $rcode <= 299 ) { // deleted
			$this->containerStatCache->clear( $container ); // purge
		} elseif ( $rcode === 404 ) { // not there
			// this shouldn't really happen, but is OK
		} elseif ( $rcode === 409 ) { // not empty
			$this->onError( $status, __METHOD__, $params, $rerr, $rcode, $rdesc ); // race?
		} else {
			$this->onError( $status, __METHOD__, $params, $rerr, $rcode, $rdesc );
		}

		return $status;
	}

	/**
	 * Get a list of objects under a container.
	 * Either just the names or a list of stdClass objects with details can be returned.
	 *
	 * @param string $fullCont
	 * @param string $type ('info' for a list of object detail maps, 'names' for names only)
	 * @param int $limit
	 * @param string|null $after
	 * @param string|null $prefix
	 * @param string|null $delim
	 * @return StatusValue With the list as value
	 */
	private function objectListing(
		$fullCont, $type, $limit, $after = null, $prefix = null, $delim = null
	) {
		$status = $this->newStatus();

		$auth = $this->getAuthentication();
		if ( !$auth ) {
			$status->fatal( 'backend-fail-connect', $this->name );

			return $status;
		}

		$query = [ 'limit' => $limit ];
		if ( $type === 'info' ) {
			$query['format'] = 'json';
		}
		if ( $after !== null ) {
			$query['marker'] = $after;
		}
		if ( $prefix !== null ) {
			$query['prefix'] = $prefix;
		}
		if ( $delim !== null ) {
			$query['delimiter'] = $delim;
		}

		list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $this->http->run( [
			'method' => 'GET',
			'url' => $this->storageUrl( $auth, $fullCont ),
			'query' => $query,
			'headers' => $this->authTokenHeaders( $auth )
		] );

		$params = [ 'cont' => $fullCont, 'prefix' => $prefix, 'delim' => $delim ];
		if ( $rcode === 200 ) { // good
			if ( $type === 'info' ) {
				$status->value = FormatJson::decode( trim( $rbody ) );
			} else {
				$status->value = explode( "\n", trim( $rbody ) );
			}
		} elseif ( $rcode === 204 ) {
			$status->value = []; // empty container
		} elseif ( $rcode === 404 ) {
			$status->value = []; // no container
		} else {
			$this->onError( $status, __METHOD__, $params, $rerr, $rcode, $rdesc );
		}

		return $status;
	}

	protected function doPrimeContainerCache( array $containerInfo ) {
		foreach ( $containerInfo as $container => $info ) {
			$this->containerStatCache->setField( $container, 'stat', $info );
		}
	}

	protected function doGetFileStatMulti( array $params ) {
		$stats = [];

		$auth = $this->getAuthentication();

		$reqs = [];
		foreach ( $params['srcs'] as $path ) {
			list( $srcCont, $srcRel ) = $this->resolveStoragePathReal( $path );
			if ( $srcRel === null ) {
				$stats[$path] = false;
				continue; // invalid storage path
			} elseif ( !$auth ) {
				$stats[$path] = null;
				continue;
			}

			// (a) Check the container
			$cstat = $this->getContainerStat( $srcCont );
			if ( $cstat === false ) {
				$stats[$path] = false;
				continue; // ok, nothing to do
			} elseif ( !is_array( $cstat ) ) {
				$stats[$path] = null;
				continue;
			}

			$reqs[$path] = [
				'method'  => 'HEAD',
				'url'     => $this->storageUrl( $auth, $srcCont, $srcRel ),
				'headers' => $this->authTokenHeaders( $auth ) + $this->headersFromParams( $params )
			];
		}

		$opts = [ 'maxConnsPerHost' => $params['concurrency'] ];
		$reqs = $this->http->runMulti( $reqs, $opts );

		foreach ( $params['srcs'] as $path ) {
			if ( array_key_exists( $path, $stats ) ) {
				continue; // some sort of failure above
			}
			// (b) Check the file
			list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $reqs[$path]['response'];
			if ( $rcode === 200 || $rcode === 204 ) {
				// Update the object if it is missing some headers
				$rhdrs = $this->addMissingMetadata( $rhdrs, $path );
				// Load the stat array from the headers
				$stat = $this->getStatFromHeaders( $rhdrs );
				if ( $this->isRGW ) {
					$stat['latest'] = true; // strong consistency
				}
			} elseif ( $rcode === 404 ) {
				$stat = false;
			} else {
				$stat = null;
				$this->onError( null, __METHOD__, $params, $rerr, $rcode, $rdesc );
			}
			$stats[$path] = $stat;
		}

		return $stats;
	}

	/**
	 * @param array $rhdrs
	 * @return array
	 */
	protected function getStatFromHeaders( array $rhdrs ) {
		// Fetch all of the custom metadata headers
		$metadata = $this->getMetadata( $rhdrs );
		// Fetch all of the custom raw HTTP headers
		$headers = $this->sanitizeHdrs( [ 'headers' => $rhdrs ] );

		return [
			// Convert various random Swift dates to TS_MW
			'mtime' => $this->convertSwiftDate( $rhdrs['last-modified'], TS_MW ),
			// Empty objects actually return no content-length header in Ceph
			'size'  => isset( $rhdrs['content-length'] ) ? (int)$rhdrs['content-length'] : 0,
			'sha1'  => $metadata['sha1base36'] ?? null,
			// Note: manifiest ETags are not an MD5 of the file
			'md5'   => ctype_xdigit( $rhdrs['etag'] ) ? $rhdrs['etag'] : null,
			'xattr' => [ 'metadata' => $metadata, 'headers' => $headers ]
		];
	}

	/**
	 * @return array|null Credential map
	 */
	protected function getAuthentication() {
		if ( $this->authErrorTimestamp !== null ) {
			if ( ( time() - $this->authErrorTimestamp ) < 60 ) {
				return null; // failed last attempt; don't bother
			} else { // actually retry this time
				$this->authErrorTimestamp = null;
			}
		}
		// Session keys expire after a while, so we renew them periodically
		$reAuth = ( ( time() - $this->authSessionTimestamp ) > $this->authTTL );
		// Authenticate with proxy and get a session key...
		if ( !$this->authCreds || $reAuth ) {
			$this->authSessionTimestamp = 0;
			$cacheKey = $this->getCredsCacheKey( $this->swiftUser );
			$creds = $this->srvCache->get( $cacheKey ); // credentials
			// Try to use the credential cache
			if ( isset( $creds['auth_token'] ) && isset( $creds['storage_url'] ) ) {
				$this->authCreds = $creds;
				// Skew the timestamp for worst case to avoid using stale credentials
				$this->authSessionTimestamp = time() - ceil( $this->authTTL / 2 );
			} else { // cache miss
				list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $this->http->run( [
					'method' => 'GET',
					'url' => "{$this->swiftAuthUrl}/v1.0",
					'headers' => [
						'x-auth-user' => $this->swiftUser,
						'x-auth-key' => $this->swiftKey
					]
				] );

				if ( $rcode >= 200 && $rcode <= 299 ) { // OK
					$this->authCreds = [
						'auth_token' => $rhdrs['x-auth-token'],
						'storage_url' => ( $this->swiftStorageUrl !== null )
							? $this->swiftStorageUrl
							: $rhdrs['x-storage-url']
					];

					$this->srvCache->set( $cacheKey, $this->authCreds, ceil( $this->authTTL / 2 ) );
					$this->authSessionTimestamp = time();
				} elseif ( $rcode === 401 ) {
					$this->onError( null, __METHOD__, [], "Authentication failed.", $rcode );
					$this->authErrorTimestamp = time();

					return null;
				} else {
					$this->onError( null, __METHOD__, [], "HTTP return code: $rcode", $rcode );
					$this->authErrorTimestamp = time();

					return null;
				}
			}
			// Ceph RGW does not use <account> in URLs (OpenStack Swift uses "/v1/<account>")
			if ( substr( $this->authCreds['storage_url'], -3 ) === '/v1' ) {
				$this->isRGW = true; // take advantage of strong consistency in Ceph
			}
		}

		return $this->authCreds;
	}

	/**
	 * @param array $creds From getAuthentication()
	 * @param string|null $container
	 * @param string|null $object
	 * @return string
	 */
	protected function storageUrl( array $creds, $container = null, $object = null ) {
		$parts = [ $creds['storage_url'] ];
		if ( strlen( $container ) ) {
			$parts[] = rawurlencode( $container );
		}
		if ( strlen( $object ) ) {
			$parts[] = str_replace( "%2F", "/", rawurlencode( $object ) );
		}

		return implode( '/', $parts );
	}

	/**
	 * @param array $creds From getAuthentication()
	 * @return array
	 */
	protected function authTokenHeaders( array $creds ) {
		return [ 'x-auth-token' => $creds['auth_token'] ];
	}

	/**
	 * Get the cache key for a container
	 *
	 * @param string $username
	 * @return string
	 */
	private function getCredsCacheKey( $username ) {
		return 'swiftcredentials:' . md5( $username . ':' . $this->swiftAuthUrl );
	}

	/**
	 * Log an unexpected exception for this backend.
	 * This also sets the StatusValue object to have a fatal error.
	 *
	 * @param StatusValue|null $status
	 * @param string $func
	 * @param array $params
	 * @param string $err Error string
	 * @param int $code HTTP status
	 * @param string $desc HTTP StatusValue description
	 */
	public function onError( $status, $func, array $params, $err = '', $code = 0, $desc = '' ) {
		if ( $status instanceof StatusValue ) {
			$status->fatal( 'backend-fail-internal', $this->name );
		}
		if ( $code == 401 ) { // possibly a stale token
			$this->srvCache->delete( $this->getCredsCacheKey( $this->swiftUser ) );
		}
		$msg = "HTTP {code} ({desc}) in '{func}' (given '{req_params}')";
		$msgParams = [
			'code'   => $code,
			'desc'   => $desc,
			'func'   => $func,
			'req_params' => FormatJson::encode( $params ),
		];
		if ( $err ) {
			$msg .= ': {err}';
			$msgParams['err'] = $err;
		}
		$this->logger->error( $msg, $msgParams );
	}
}

/**
 * @see FileBackendStoreOpHandle
 */
class SwiftFileOpHandle extends FileBackendStoreOpHandle {
	/** @var array List of Requests for MultiHttpClient */
	public $httpOp;
	/** @var Closure */
	public $callback;

	/**
	 * @param SwiftFileBackend $backend
	 * @param Closure $callback Function that takes (HTTP request array, status)
	 * @param array $httpOp MultiHttpClient op
	 */
	public function __construct( SwiftFileBackend $backend, Closure $callback, array $httpOp ) {
		$this->backend = $backend;
		$this->callback = $callback;
		$this->httpOp = $httpOp;
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
	/** @var array List of path or (path,stat array) entries */
	protected $bufferIter = [];

	/** @var string List items *after* this path */
	protected $bufferAfter = null;

	/** @var int */
	protected $pos = 0;

	/** @var array */
	protected $params = [];

	/** @var SwiftFileBackend */
	protected $backend;

	/** @var string Container name */
	protected $container;

	/** @var string Storage directory */
	protected $dir;

	/** @var int */
	protected $suffixStart;

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
	 * @return int
	 */
	public function key() {
		return $this->pos;
	}

	/**
	 * @see Iterator::next()
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
	 * @param string &$after
	 * @param int $limit
	 * @param array $params
	 * @return Traversable|array
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
		list( $path, $stat ) = current( $this->bufferIter );
		$relPath = substr( $path, $this->suffixStart );
		if ( is_array( $stat ) ) {
			$storageDir = rtrim( $this->params['dir'], '/' );
			$this->backend->loadListingStatInternal( "$storageDir/$relPath", $stat );
		}

		return $relPath;
	}

	protected function pageFromList( $container, $dir, &$after, $limit, array $params ) {
		return $this->backend->getFileListPageInternal( $container, $dir, $after, $limit, $params );
	}
}
