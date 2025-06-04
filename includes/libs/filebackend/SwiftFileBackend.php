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

namespace Wikimedia\FileBackend;

use Exception;
use LockManager;
use Psr\Log\LoggerInterface;
use Shellbox\Command\BoxedCommand;
use StatusValue;
use stdClass;
use Wikimedia\AtEase\AtEase;
use Wikimedia\FileBackend\FileIteration\SwiftFileBackendDirList;
use Wikimedia\FileBackend\FileIteration\SwiftFileBackendFileList;
use Wikimedia\FileBackend\FileOpHandle\SwiftFileOpHandle;
use Wikimedia\Http\MultiHttpClient;
use Wikimedia\MapCacheLRU\MapCacheLRU;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\EmptyBagOStuff;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\RequestTimeout\TimeoutException;
use Wikimedia\Timestamp\ConvertibleTimestamp;

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
	private const DEFAULT_HTTP_OPTIONS = [ 'httpVersion' => 'v1.1' ];
	private const AUTH_FAILURE_ERROR = 'Could not connect due to prior authentication failure';

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
	/** @var bool */
	protected $canShellboxGetTempUrl;
	/** @var string|null */
	protected $shellboxIpRange;
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

	/** @var MapCacheLRU Container stat cache */
	protected $containerStatCache;

	/** @var array|null */
	protected $authCreds;
	/** @var int|null UNIX timestamp */
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
	 *   - canShellboxGetTempUrl : Set this to true to generate a TempURL allowing Shellbox to
	 *                          directly fetch files from Swift. swiftTempUrlKey should be set.
	 *   - shellboxIpRange    : An IP range string to use when generating TempURLs for Shellbox.
	 *                          Specifying this will improve security by preventing exfiltrated
	 *                          TempURLs from being usable outside the server.
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
	 *   - connTimeout         : The HTTP connect timeout to use when connecting to Swift, in
	 *                           seconds.
	 *   - reqTimeout          : The HTTP request timeout to use when communicating with Swift, in
	 *                           seconds.
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );
		// Required settings
		$this->swiftAuthUrl = $config['swiftAuthUrl'];
		$this->swiftUser = $config['swiftUser'];
		$this->swiftKey = $config['swiftKey'];
		// Optional settings
		$this->authTTL = $config['swiftAuthTTL'] ?? 15 * 60; // some sensible number
		$this->swiftTempUrlKey = $config['swiftTempUrlKey'] ?? '';
		$this->canShellboxGetTempUrl = $config['canShellboxGetTempUrl'] ?? false;
		$this->shellboxIpRange = $config['shellboxIpRange'] ?? null;
		$this->swiftStorageUrl = $config['swiftStorageUrl'] ?? null;
		$this->shardViaHashLevels = $config['shardViaHashLevels'] ?? '';
		$this->rgwS3AccessKey = $config['rgwS3AccessKey'] ?? '';
		$this->rgwS3SecretKey = $config['rgwS3SecretKey'] ?? '';

		// HTTP helper client
		$httpOptions = [];
		foreach ( [ 'connTimeout', 'reqTimeout' ] as $optionName ) {
			if ( isset( $config[$optionName] ) ) {
				$httpOptions[$optionName] = $config[$optionName];
			}
		}
		$this->http = new MultiHttpClient( $httpOptions );
		$this->http->setLogger( $this->logger );

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
		// Per https://docs.openstack.org/swift/latest/overview_large_objects.html
		// we need to split objects if they are larger than 5 GB. Support for
		// splitting objects has not yet been implemented by this class
		// so limit max file size to 5GiB.
		$this->maxFileSize = 5 * 1024 * 1024 * 1024;
	}

	public function setLogger( LoggerInterface $logger ) {
		parent::setLogger( $logger );
		$this->http->setLogger( $logger );
	}

	public function getFeatures() {
		return (
			self::ATTR_UNICODE_PATHS |
			self::ATTR_HEADERS |
			self::ATTR_METADATA
		);
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
		[ $container, $rel ] = $this->resolveStoragePathReal( $storagePath );
		if ( $rel === null ) {
			return false; // invalid
		}

		return is_array( $this->getContainerStat( $container ) );
	}

	/**
	 * Filter/normalize a header map to only include mutable "content-"/"x-content-" headers
	 *
	 * Mutable headers can be changed via HTTP POST even if the file content is the same
	 *
	 * @see https://docs.openstack.org/api-ref/object-store
	 * @param string[] $headers Map of (header => value) for a swift object
	 * @return string[] Map of (header => value) for Content-* headers mutable via POST
	 */
	protected function extractMutableContentHeaders( array $headers ) {
		$contentHeaders = [];
		// Normalize casing, and strip out illegal headers
		foreach ( $headers as $name => $value ) {
			$name = strtolower( $name );
			if ( $name === 'x-delete-at' && is_numeric( $value ) ) {
				// Expects a Unix Epoch date
				$contentHeaders[$name] = $value;
			} elseif ( $name === 'x-delete-after' && is_numeric( $value ) ) {
				// Expects number of minutes time to live.
				$contentHeaders[$name] = $value;
			} elseif ( preg_match( '/^(x-)?content-(?!length$)/', $name ) ) {
				// Only allow content-* and x-content-* headers (but not content-length)
				$contentHeaders[$name] = $value;
			} elseif ( $name === 'content-type' && $value !== '' ) {
				// This header can be set to a value but not unset
				$contentHeaders[$name] = $value;
			}
		}
		// By default, Swift has annoyingly low maximum header value limits
		if ( isset( $contentHeaders['content-disposition'] ) ) {
			$maxLength = 255;
			// @note: assume FileBackend::makeContentDisposition() already used
			$offset = $maxLength - strlen( $contentHeaders['content-disposition'] );
			if ( $offset < 0 ) {
				$pos = strrpos( $contentHeaders['content-disposition'], ';', $offset );
				$contentHeaders['content-disposition'] = $pos === false
					? ''
					: trim( substr( $contentHeaders['content-disposition'], 0, $pos ) );
			}
		}

		return $contentHeaders;
	}

	/**
	 * @see https://docs.openstack.org/api-ref/object-store
	 * @param string[] $headers Map of (header => value) for a swift object
	 * @return string[] Map of (metadata header name => metadata value)
	 */
	protected function extractMetadataHeaders( array $headers ) {
		$metadataHeaders = [];
		foreach ( $headers as $name => $value ) {
			$name = strtolower( $name );
			if ( strpos( $name, 'x-object-meta-' ) === 0 ) {
				$metadataHeaders[$name] = $value;
			}
		}

		return $metadataHeaders;
	}

	/**
	 * @see https://docs.openstack.org/api-ref/object-store
	 * @param string[] $headers Map of (header => value) for a swift object
	 * @return string[] Map of (metadata key name => metadata value)
	 */
	protected function getMetadataFromHeaders( array $headers ) {
		$prefixLen = strlen( 'x-object-meta-' );

		$metadata = [];
		foreach ( $this->extractMetadataHeaders( $headers ) as $name => $value ) {
			$metadata[substr( $name, $prefixLen )] = $value;
		}

		return $metadata;
	}

	protected function doCreateInternal( array $params ) {
		$status = $this->newStatus();

		[ $dstCont, $dstRel ] = $this->resolveStoragePathReal( $params['dst'] );
		if ( $dstRel === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );

			return $status;
		}

		// Headers that are not strictly a function of the file content
		$mutableHeaders = $this->extractMutableContentHeaders( $params['headers'] ?? [] );
		// Make sure that the "content-type" header is set to something sensible
		$mutableHeaders['content-type']
			??= $this->getContentType( $params['dst'], $params['content'], null );

		$reqs = [ [
			'method' => 'PUT',
			'container' => $dstCont,
			'relPath' => $dstRel,
			'headers' => array_merge(
				$mutableHeaders,
				[
					'etag' => md5( $params['content'] ),
					'content-length' => strlen( $params['content'] ),
					'x-object-meta-sha1base36' =>
						\Wikimedia\base_convert( sha1( $params['content'] ), 16, 36, 31 )
				]
			),
			'body' => $params['content']
		] ];

		$method = __METHOD__;
		$handler = function ( array $request, StatusValue $status ) use ( $method, $params ) {
			[ $rcode, $rdesc, , $rbody, $rerr ] = $request['response'];
			if ( $rcode === 201 || $rcode === 202 ) {
				// good
			} elseif ( $rcode === 412 ) {
				$status->fatal( 'backend-fail-contenttype', $params['dst'] );
			} else {
				$this->onError( $status, $method, $params, $rerr, $rcode, $rdesc, $rbody );
			}

			return SwiftFileOpHandle::CONTINUE_IF_OK;
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

		[ $dstCont, $dstRel ] = $this->resolveStoragePathReal( $params['dst'] );
		if ( $dstRel === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );

			return $status;
		}

		// Open a handle to the source file so that it can be streamed. The size and hash
		// will be computed using the handle. In the off chance that the source file changes
		// during this operation, the PUT will fail due to an ETag mismatch and be aborted.
		AtEase::suppressWarnings();
		$srcHandle = fopen( $params['src'], 'rb' );
		AtEase::restoreWarnings();
		if ( $srcHandle === false ) { // source doesn't exist?
			$status->fatal( 'backend-fail-notexists', $params['src'] );

			return $status;
		}

		// Compute the MD5 and SHA-1 hashes in one pass
		$srcSize = fstat( $srcHandle )['size'];
		$md5Context = hash_init( 'md5' );
		$sha1Context = hash_init( 'sha1' );
		$hashDigestSize = 0;
		while ( !feof( $srcHandle ) ) {
			$buffer = (string)fread( $srcHandle, 131_072 ); // 128 KiB
			hash_update( $md5Context, $buffer );
			hash_update( $sha1Context, $buffer );
			$hashDigestSize += strlen( $buffer );
		}
		// Reset the handle back to the beginning so that it can be streamed
		rewind( $srcHandle );

		if ( $hashDigestSize !== $srcSize ) {
			$status->fatal( 'backend-fail-hash', $params['src'] );

			return $status;
		}

		// Headers that are not strictly a function of the file content
		$mutableHeaders = $this->extractMutableContentHeaders( $params['headers'] ?? [] );
		// Make sure that the "content-type" header is set to something sensible
		$mutableHeaders['content-type']
			??= $this->getContentType( $params['dst'], null, $params['src'] );

		$reqs = [ [
			'method' => 'PUT',
			'container' => $dstCont,
			'relPath' => $dstRel,
			'headers' => array_merge(
				$mutableHeaders,
				[
					'content-length' => $srcSize,
					'etag' => hash_final( $md5Context ),
					'x-object-meta-sha1base36' =>
						\Wikimedia\base_convert( hash_final( $sha1Context ), 16, 36, 31 )
				]
			),
			'body' => $srcHandle // resource
		] ];

		$method = __METHOD__;
		$handler = function ( array $request, StatusValue $status ) use ( $method, $params ) {
			[ $rcode, $rdesc, , $rbody, $rerr ] = $request['response'];
			if ( $rcode === 201 || $rcode === 202 ) {
				// good
			} elseif ( $rcode === 412 ) {
				$status->fatal( 'backend-fail-contenttype', $params['dst'] );
			} else {
				$this->onError( $status, $method, $params, $rerr, $rcode, $rdesc, $rbody );
			}

			return SwiftFileOpHandle::CONTINUE_IF_OK;
		};

		$opHandle = new SwiftFileOpHandle( $this, $handler, $reqs );
		$opHandle->resourcesToClose[] = $srcHandle;

		if ( !empty( $params['async'] ) ) { // deferred
			$status->value = $opHandle;
		} else { // actually write the object in Swift
			$status->merge( current( $this->executeOpHandlesInternal( [ $opHandle ] ) ) );
		}

		return $status;
	}

	protected function doCopyInternal( array $params ) {
		$status = $this->newStatus();

		[ $srcCont, $srcRel ] = $this->resolveStoragePathReal( $params['src'] );
		if ( $srcRel === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['src'] );

			return $status;
		}

		[ $dstCont, $dstRel ] = $this->resolveStoragePathReal( $params['dst'] );
		if ( $dstRel === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );

			return $status;
		}

		$reqs = [ [
			'method' => 'PUT',
			'container' => $dstCont,
			'relPath' => $dstRel,
			'headers' => array_merge(
				$this->extractMutableContentHeaders( $params['headers'] ?? [] ),
				[
					'x-copy-from' => '/' . rawurlencode( $srcCont ) . '/' .
						str_replace( "%2F", "/", rawurlencode( $srcRel ) )
				]
			)
		] ];

		$method = __METHOD__;
		$handler = function ( array $request, StatusValue $status ) use ( $method, $params ) {
			[ $rcode, $rdesc, , $rbody, $rerr ] = $request['response'];
			if ( $rcode === 201 ) {
				// good
			} elseif ( $rcode === 404 ) {
				if ( empty( $params['ignoreMissingSource'] ) ) {
					$status->fatal( 'backend-fail-copy', $params['src'], $params['dst'] );
				}
			} else {
				$this->onError( $status, $method, $params, $rerr, $rcode, $rdesc, $rbody );
			}

			return SwiftFileOpHandle::CONTINUE_IF_OK;
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

		[ $srcCont, $srcRel ] = $this->resolveStoragePathReal( $params['src'] );
		if ( $srcRel === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['src'] );

			return $status;
		}

		[ $dstCont, $dstRel ] = $this->resolveStoragePathReal( $params['dst'] );
		if ( $dstRel === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['dst'] );

			return $status;
		}

		$reqs = [ [
			'method' => 'PUT',
			'container' => $dstCont,
			'relPath' => $dstRel,
			'headers' => array_merge(
				$this->extractMutableContentHeaders( $params['headers'] ?? [] ),
				[
					'x-copy-from' => '/' . rawurlencode( $srcCont ) . '/' .
						str_replace( "%2F", "/", rawurlencode( $srcRel ) )
				]
			)
		] ];
		if ( "{$srcCont}/{$srcRel}" !== "{$dstCont}/{$dstRel}" ) {
			$reqs[] = [
				'method' => 'DELETE',
				'container' => $srcCont,
				'relPath' => $srcRel,
				'headers' => []
			];
		}

		$method = __METHOD__;
		$handler = function ( array $request, StatusValue $status ) use ( $method, $params ) {
			[ $rcode, $rdesc, , $rbody, $rerr ] = $request['response'];
			if ( $request['method'] === 'PUT' && $rcode === 201 ) {
				// good
			} elseif ( $request['method'] === 'DELETE' && $rcode === 204 ) {
				// good
			} elseif ( $rcode === 404 ) {
				if ( empty( $params['ignoreMissingSource'] ) ) {
					$status->fatal( 'backend-fail-move', $params['src'], $params['dst'] );
				} else {
					// Leave Status as OK but skip the DELETE request
					return SwiftFileOpHandle::CONTINUE_NO;
				}
			} else {
				$this->onError( $status, $method, $params, $rerr, $rcode, $rdesc, $rbody );
			}

			return SwiftFileOpHandle::CONTINUE_IF_OK;
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

		[ $srcCont, $srcRel ] = $this->resolveStoragePathReal( $params['src'] );
		if ( $srcRel === null ) {
			$status->fatal( 'backend-fail-invalidpath', $params['src'] );

			return $status;
		}

		$reqs = [ [
			'method' => 'DELETE',
			'container' => $srcCont,
			'relPath' => $srcRel,
			'headers' => []
		] ];

		$method = __METHOD__;
		$handler = function ( array $request, StatusValue $status ) use ( $method, $params ) {
			[ $rcode, $rdesc, , $rbody, $rerr ] = $request['response'];
			if ( $rcode === 204 ) {
				// good
			} elseif ( $rcode === 404 ) {
				if ( empty( $params['ignoreMissingSource'] ) ) {
					$status->fatal( 'backend-fail-delete', $params['src'] );
				}
			} else {
				$this->onError( $status, $method, $params, $rerr, $rcode, $rdesc, $rbody );
			}

			return SwiftFileOpHandle::CONTINUE_IF_OK;
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

		[ $srcCont, $srcRel ] = $this->resolveStoragePathReal( $params['src'] );
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

		// Swift object POST clears any prior headers, so merge the new and old headers here.
		// Also, during, POST, libcurl adds "Content-Type: application/x-www-form-urlencoded"
		// if "Content-Type" is not set, which would clobber the header value for the object.
		$oldMetadataHeaders = [];
		foreach ( $stat['xattr']['metadata'] as $name => $value ) {
			$oldMetadataHeaders["x-object-meta-$name"] = $value;
		}
		$newContentHeaders = $this->extractMutableContentHeaders( $params['headers'] ?? [] );
		$oldContentHeaders = $stat['xattr']['headers'];

		$reqs = [ [
			'method' => 'POST',
			'container' => $srcCont,
			'relPath' => $srcRel,
			'headers' => $oldMetadataHeaders + $newContentHeaders + $oldContentHeaders
		] ];

		$method = __METHOD__;
		$handler = function ( array $request, StatusValue $status ) use ( $method, $params ) {
			[ $rcode, $rdesc, , $rbody, $rerr ] = $request['response'];
			if ( $rcode === 202 ) {
				// good
			} elseif ( $rcode === 404 ) {
				$status->fatal( 'backend-fail-describe', $params['src'] );
			} else {
				$this->onError( $status, $method, $params, $rerr, $rcode, $rdesc, $rbody );
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

	/**
	 * @inheritDoc
	 */
	protected function doPrepareInternal( $fullCont, $dir, array $params ) {
		$status = $this->newStatus();

		// (a) Check if container already exists
		$stat = $this->getContainerStat( $fullCont );
		if ( is_array( $stat ) ) {
			return $status; // already there
		} elseif ( $stat === self::RES_ERROR ) {
			$status->fatal( 'backend-fail-internal', $this->name );
			$this->logger->error( __METHOD__ . ': cannot get container stat' );
		} else {
			// (b) Create container as needed with proper ACLs
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
		} elseif ( $stat === self::RES_ABSENT ) {
			$status->fatal( 'backend-fail-usable', $params['dir'] );
		} else {
			$status->fatal( 'backend-fail-internal', $this->name );
			$this->logger->error( __METHOD__ . ': cannot get container stat' );
		}

		return $status;
	}

	protected function doPublishInternal( $fullCont, $dir, array $params ) {
		$status = $this->newStatus();
		if ( empty( $params['access'] ) ) {
			return $status; // nothing to do
		}

		$stat = $this->getContainerStat( $fullCont );
		if ( is_array( $stat ) ) {
			$readUsers = array_merge( $this->readUsers, [ $this->swiftUser, '.r:*' ] );
			if ( !empty( $params['listing'] ) ) {
				$readUsers[] = '.rlistings';
			}
			$writeUsers = array_merge( $this->writeUsers, [ $this->swiftUser ] );

			// Make container public to end-users...
			$status->merge( $this->setContainerAccess(
				$fullCont,
				$readUsers,
				$writeUsers
			) );
		} elseif ( $stat === self::RES_ABSENT ) {
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
		if ( $stat === self::RES_ABSENT ) {
			return $status; // ok, nothing to do
		} elseif ( $stat === self::RES_ERROR ) {
			$status->fatal( 'backend-fail-internal', $this->name );
			$this->logger->error( __METHOD__ . ': cannot get container stat' );
		} elseif ( is_array( $stat ) && $stat['count'] == 0 ) {
			// (b) Delete the container if empty
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
			$timestamp = new ConvertibleTimestamp( $ts );

			return $timestamp->getTimestamp( $format );
		} catch ( TimeoutException $e ) {
			throw $e;
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
	protected function addMissingHashMetadata( array $objHdrs, $path ) {
		if ( isset( $objHdrs['x-object-meta-sha1base36'] ) ) {
			return $objHdrs; // nothing to do
		}

		/** @noinspection PhpUnusedLocalVariableInspection */
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );
		$this->logger->error( __METHOD__ . ": {path} was not stored with SHA-1 metadata.",
			[ 'path' => $path ] );

		$objHdrs['x-object-meta-sha1base36'] = false;

		// Find prior custom HTTP headers
		$postHeaders = $this->extractMutableContentHeaders( $objHdrs );
		// Find prior metadata headers
		$postHeaders += $this->extractMetadataHeaders( $objHdrs );

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
					[ $srcCont, $srcRel ] = $this->resolveStoragePathReal( $path );
					[ $rcode ] = $this->requestWithAuth( [
						'method' => 'POST',
						'container' => $srcCont,
						'relPath' => $srcRel,
						'headers' => $postHeaders
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
		$ep = array_diff_key( $params, [ 'srcs' => 1 ] ); // for error logging
		// Blindly create tmp files and stream to them, catching any exception
		// if the file does not exist. Do not waste time doing file stats here.
		$reqs = []; // (path => op)

		// Initial dummy values to preserve path order
		$contents = array_fill_keys( $params['srcs'], self::RES_ERROR );
		foreach ( $params['srcs'] as $path ) { // each path in this concurrent batch
			[ $srcCont, $srcRel ] = $this->resolveStoragePathReal( $path );
			if ( $srcRel === null ) {
				continue; // invalid storage path
			}
			// Create a new temporary memory file...
			$handle = fopen( 'php://temp', 'wb' );
			if ( $handle ) {
				$reqs[$path] = [
					'method'  => 'GET',
					'container' => $srcCont,
					'relPath' => $srcRel,
					'headers' => $this->headersFromParams( $params ),
					'stream'  => $handle,
				];
			}
		}

		$reqs = $this->requestMultiWithAuth(
			$reqs,
			[ 'maxConnsPerHost' => $params['concurrency'] ]
		);
		foreach ( $reqs as $path => $op ) {
			[ $rcode, $rdesc, $rhdrs, $rbody, $rerr ] = $op['response'];
			if ( $rcode >= 200 && $rcode <= 299 ) {
				rewind( $op['stream'] ); // start from the beginning
				$content = (string)stream_get_contents( $op['stream'] );
				$size = strlen( $content );
				// Make sure that stream finished
				if ( $size === (int)$rhdrs['content-length'] ) {
					$contents[$path] = $content;
				} else {
					$contents[$path] = self::RES_ERROR;
					$rerr = "Got {$size}/{$rhdrs['content-length']} bytes";
					$this->onError( null, __METHOD__,
						[ 'src' => $path ] + $ep, $rerr, $rcode, $rdesc );
				}
			} elseif ( $rcode === 404 ) {
				$contents[$path] = self::RES_ABSENT;
			} else {
				$contents[$path] = self::RES_ERROR;
				$this->onError( null, __METHOD__,
					[ 'src' => $path ] + $ep, $rerr, $rcode, $rdesc, $rbody );
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

		return self::RES_ERROR;
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
	 * @param string|null &$after Resolved container relative path used for continuation paging
	 * @param int $limit Max number of items to list
	 * @param array $params Parameters for {@link getDirectoryList()}
	 * @return string[] List of resolved container relative directories directly under $dir
	 * @throws FileBackendError
	 */
	public function getDirListPageInternal( $fullCont, $dir, &$after, $limit, array $params ) {
		$dirs = [];
		if ( $after === INF ) {
			return $dirs; // nothing more
		}

		/** @noinspection PhpUnusedLocalVariableInspection */
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		$prefix = ( $dir == '' ) ? null : "{$dir}/";
		// Non-recursive: only list dirs right under $dir
		if ( !empty( $params['topOnly'] ) ) {
			$status = $this->objectListing( $fullCont, 'names', $limit, $after, $prefix, '/' );
			if ( !$status->isOK() ) {
				throw new FileBackendError( "Iterator page I/O error." );
			}
			$objects = $status->value;
			// @phan-suppress-next-line PhanTypeSuspiciousNonTraversableForeach
			foreach ( $objects as $object ) { // files and directories
				if ( substr( $object, -1 ) === '/' ) {
					$dirs[] = $object; // directories end in '/'
				}
			}
		} else {
			// Recursive: list all dirs under $dir and its subdirs
			$getParentDir = static function ( $path ) {
				return ( $path !== null && strpos( $path, '/' ) !== false ) ? dirname( $path ) : false;
			};

			// Get directory from last item of prior page
			$lastDir = $getParentDir( $after ); // must be first page
			$status = $this->objectListing( $fullCont, 'names', $limit, $after, $prefix );

			if ( !$status->isOK() ) {
				throw new FileBackendError( "Iterator page I/O error." );
			}

			$objects = $status->value;

			// @phan-suppress-next-line PhanTypeSuspiciousNonTraversableForeach
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
						} while ( $pDir !== false
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
	 * @param array $params Parameters for {@link getFileList()}
	 * @return array[] List of (name, stat map or null) tuples under $dir
	 * @throws FileBackendError
	 */
	public function getFileListPageInternal( $fullCont, $dir, &$after, $limit, array $params ) {
		$files = []; // list of (path, stat map or null) entries
		if ( $after === INF ) {
			return $files; // nothing more
		}

		/** @noinspection PhpUnusedLocalVariableInspection */
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		$prefix = ( $dir == '' ) ? null : "{$dir}/";
		// $objects will contain a list of unfiltered names or stdClass items
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

		// Reformat this list into a list of (name, stat map or null) entries
		if ( !$status->isOK() ) {
			throw new FileBackendError( "Iterator page I/O error." );
		}

		$objects = $status->value;
		$files = $this->buildFileObjectListing( $objects );

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
	 * and extracting any stat info if provided in $objects
	 *
	 * @param stdClass[]|string[] $objects List of stdClass items or object names
	 * @return array[] List of (name, stat map or null) entries
	 */
	private function buildFileObjectListing( array $objects ) {
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
					// Note: manifest ETags are not an MD5 of the file
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
		// Stat entries filled by file listings don't include metadata/headers
		if ( is_array( $stat ) && !isset( $stat['xattr'] ) ) {
			$this->clearCache( [ $params['src'] ] );
			$stat = $this->getFileStat( $params );
		}

		if ( is_array( $stat ) ) {
			return $stat['xattr'];
		}

		return $stat === self::RES_ERROR ? self::RES_ERROR : self::RES_ABSENT;
	}

	protected function doGetFileSha1base36( array $params ) {
		// Avoid using stat entries from file listings, which never include the SHA-1 hash.
		// Also, recompute the hash if it's not part of the metadata headers for some reason.
		$params['requireSHA1'] = true;

		$stat = $this->getFileStat( $params );
		if ( is_array( $stat ) ) {
			return $stat['sha1'];
		}

		return $stat === self::RES_ERROR ? self::RES_ERROR : self::RES_ABSENT;
	}

	protected function doStreamFile( array $params ) {
		$status = $this->newStatus();

		$flags = !empty( $params['headless'] ) ? HTTPFileStreamer::STREAM_HEADLESS : 0;

		[ $srcCont, $srcRel ] = $this->resolveStoragePathReal( $params['src'] );
		if ( $srcRel === null ) {
			HTTPFileStreamer::send404Message( $params['src'], $flags );
			$status->fatal( 'backend-fail-invalidpath', $params['src'] );

			return $status;
		}

		if ( !is_array( $this->getContainerStat( $srcCont ) ) ) {
			HTTPFileStreamer::send404Message( $params['src'], $flags );
			$status->fatal( 'backend-fail-stream', $params['src'] );

			return $status;
		}

		// If "headers" is set, we only want to send them if the file is there.
		// Do not bother checking if the file exists if headers are not set though.
		if ( $params['headers'] && !$this->fileExists( $params ) ) {
			HTTPFileStreamer::send404Message( $params['src'], $flags );
			$status->fatal( 'backend-fail-stream', $params['src'] );

			return $status;
		}

		// Send the requested additional headers
		if ( empty( $params['headless'] ) ) {
			foreach ( $params['headers'] as $header ) {
				$this->header( $header );
			}
		}

		if ( empty( $params['allowOB'] ) ) {
			// Cancel output buffering and gzipping if set
			$this->resetOutputBuffer();
		}

		$handle = fopen( 'php://output', 'wb' );
		[ $rcode, $rdesc, , $rbody, $rerr ] = $this->requestWithAuth( [
			'method' => 'GET',
			'container' => $srcCont,
			'relPath' => $srcRel,
			'headers' => $this->headersFromParams( $params ) + $params['options'],
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
			$this->onError( $status, __METHOD__, $params, $rerr, $rcode, $rdesc, $rbody );
		}

		return $status;
	}

	protected function doGetLocalCopyMulti( array $params ) {
		$ep = array_diff_key( $params, [ 'srcs' => 1 ] ); // for error logging
		// Blindly create tmp files and stream to them, catching any exception
		// if the file does not exist. Do not waste time doing file stats here.
		$reqs = []; // (path => op)

		// Initial dummy values to preserve path order
		$tmpFiles = array_fill_keys( $params['srcs'], self::RES_ERROR );
		foreach ( $params['srcs'] as $path ) { // each path in this concurrent batch
			[ $srcCont, $srcRel ] = $this->resolveStoragePathReal( $path );
			if ( $srcRel === null ) {
				continue; // invalid storage path
			}
			// Get source file extension
			$ext = FileBackend::extensionFromPath( $path );
			// Create a new temporary file...
			$tmpFile = $this->tmpFileFactory->newTempFSFile( 'localcopy_', $ext );
			$handle = $tmpFile ? fopen( $tmpFile->getPath(), 'wb' ) : false;
			if ( $handle ) {
				$reqs[$path] = [
					'method'  => 'GET',
					'container' => $srcCont,
					'relPath' => $srcRel,
					'headers' => $this->headersFromParams( $params ),
					'stream'  => $handle,
				];
				$tmpFiles[$path] = $tmpFile;
			}
		}

		// Ceph RADOS Gateway is in use (strong consistency) or X-Newest will be used
		$latest = ( $this->isRGW || !empty( $params['latest'] ) );

		$reqs = $this->requestMultiWithAuth(
			$reqs,
			[ 'maxConnsPerHost' => $params['concurrency'] ]
		);
		foreach ( $reqs as $path => $op ) {
			[ $rcode, $rdesc, $rhdrs, $rbody, $rerr ] = $op['response'];
			fclose( $op['stream'] ); // close open handle
			if ( $rcode >= 200 && $rcode <= 299 ) {
				/** @var TempFSFile $tmpFile */
				$tmpFile = $tmpFiles[$path];
				// Make sure that the stream finished and fully wrote to disk
				$size = $tmpFile->getSize();
				if ( $size !== (int)$rhdrs['content-length'] ) {
					$tmpFiles[$path] = self::RES_ERROR;
					$rerr = "Got {$size}/{$rhdrs['content-length']} bytes";
					$this->onError( null, __METHOD__,
						[ 'src' => $path ] + $ep, $rerr, $rcode, $rdesc );
				}
				// Set the file stat process cache in passing
				$stat = $this->getStatFromHeaders( $rhdrs );
				$stat['latest'] = $latest;
				$this->cheapCache->setField( $path, 'stat', $stat );
			} elseif ( $rcode === 404 ) {
				$tmpFiles[$path] = self::RES_ABSENT;
				$this->cheapCache->setField(
					$path,
					'stat',
					$latest ? self::ABSENT_LATEST : self::ABSENT_NORMAL
				);
			} else {
				$tmpFiles[$path] = self::RES_ERROR;
				$this->onError( null, __METHOD__,
					[ 'src' => $path ] + $ep, $rerr, $rcode, $rdesc, $rbody );
			}
		}

		return $tmpFiles;
	}

	public function addShellboxInputFile( BoxedCommand $command, string $boxedName,
		array $params
	) {
		if ( $this->canShellboxGetTempUrl ) {
			$urlParams = [ 'src' => $params['src'] ];
			if ( $this->shellboxIpRange !== null ) {
				$urlParams['ipRange'] = $this->shellboxIpRange;
			}
			$url = $this->getFileHttpUrl( $urlParams );
			if ( $url ) {
				$command->inputFileFromUrl( $boxedName, $url );
				return $this->newStatus();
			}
		}
		return parent::addShellboxInputFile( $command, $boxedName, $params );
	}

	public function getFileHttpUrl( array $params ) {
		if ( $this->swiftTempUrlKey == '' &&
			( $this->rgwS3AccessKey == '' || $this->rgwS3SecretKey != '' )
		) {
			$this->logger->debug( "Can't get Swift file URL: no key available" );
			return self::TEMPURL_ERROR;
		}

		[ $srcCont, $srcRel ] = $this->resolveStoragePathReal( $params['src'] );
		if ( $srcRel === null ) {
			$this->logger->debug( "Can't get Swift file URL: can't resolve path" );
			return self::TEMPURL_ERROR; // invalid path
		}

		$auth = $this->getAuthentication();
		if ( !$auth ) {
			$this->logger->debug( "Can't get Swift file URL: authentication failed" );
			return self::TEMPURL_ERROR;
		}

		$method = $params['method'] ?? 'GET';
		$ttl = $params['ttl'] ?? 86400;
		$expires = time() + $ttl;

		if ( $this->swiftTempUrlKey != '' ) {
			$url = $this->storageUrl( $auth, $srcCont, $srcRel );
			// Swift wants the signature based on the unencoded object name
			$contPath = parse_url( $this->storageUrl( $auth, $srcCont ), PHP_URL_PATH );
			$messageParts = [
				$method,
				$expires,
				"{$contPath}/{$srcRel}"
			];
			$query = [
				'temp_url_expires' => $expires,
			];
			if ( isset( $params['ipRange'] ) ) {
				array_unshift( $messageParts, "ip={$params['ipRange']}" );
				$query['temp_url_ip_range'] = $params['ipRange'];
			}

			$signature = hash_hmac( 'sha1',
				implode( "\n", $messageParts ),
				$this->swiftTempUrlKey
			);
			$query = [ 'temp_url_sig' => $signature ] + $query;

			return $url . '?' . http_build_query( $query );
		} else { // give S3 API URL for rgw
			// Path for signature starts with the bucket
			$spath = '/' . rawurlencode( $srcCont ) . '/' .
				str_replace( '%2F', '/', rawurlencode( $srcRel ) );
			// Calculate the hash
			$signature = base64_encode( hash_hmac(
				'sha1',
				"{$method}\n\n\n{$expires}\n{$spath}",
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

	protected function doExecuteOpHandlesInternal( array $fileOpHandles ) {
		/** @var SwiftFileOpHandle[] $fileOpHandles */
		'@phan-var SwiftFileOpHandle[] $fileOpHandles';

		/** @var StatusValue[] $statuses */
		$statuses = [];

		// Split the HTTP requests into stages that can be done concurrently
		$httpReqsByStage = []; // map of (stage => index => HTTP request)
		foreach ( $fileOpHandles as $index => $fileOpHandle ) {
			$reqs = $fileOpHandle->httpOp;
			foreach ( $reqs as $stage => $req ) {
				$httpReqsByStage[$stage][$index] = $req;
			}
			$statuses[$index] = $this->newStatus();
		}

		// Run all requests for the first stage, then the next, and so on
		$reqCount = count( $httpReqsByStage );
		for ( $stage = 0; $stage < $reqCount; ++$stage ) {
			$httpReqs = $this->requestMultiWithAuth( $httpReqsByStage[$stage] );
			foreach ( $httpReqs as $index => $httpReq ) {
				/** @var SwiftFileOpHandle $fileOpHandle */
				$fileOpHandle = $fileOpHandles[$index];
				// Run the callback for each request of this operation
				$status = $statuses[$index];
				( $fileOpHandle->callback )( $httpReq, $status );
				// On failure, abort all remaining requests for this operation. This is used
				// in "move" operations to abort the DELETE request if the PUT request fails.
				if (
					!$status->isOK() ||
					$fileOpHandle->state === $fileOpHandle::CONTINUE_NO
				) {
					$stages = count( $fileOpHandle->httpOp );
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
	 * @return StatusValue Good status without value for success, fatal otherwise.
	 */
	protected function setContainerAccess( $container, array $readUsers, array $writeUsers ) {
		$status = $this->newStatus();

		[ $rcode, , , , ] = $this->requestWithAuth( [
			'method' => 'POST',
			'container' => $container,
			'headers' => [
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
	 * Get a Swift container stat map, possibly from process cache.
	 * Use $reCache if the file count or byte count is needed.
	 *
	 * @param string $container Container name
	 * @param bool $bypassCache Bypass all caches and load from Swift
	 * @return array|false|null False on 404, null on failure
	 */
	protected function getContainerStat( $container, $bypassCache = false ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$ps = $this->scopedProfileSection( __METHOD__ . "-{$this->name}" );

		if ( $bypassCache ) { // purge cache
			$this->containerStatCache->clear( $container );
		} elseif ( !$this->containerStatCache->hasField( $container, 'stat' ) ) {
			$this->primeContainerCache( [ $container ] ); // check persistent cache
		}
		if ( !$this->containerStatCache->hasField( $container, 'stat' ) ) {
			[ $rcode, $rdesc, $rhdrs, $rbody, $rerr ] = $this->requestWithAuth( [
				'method' => 'HEAD',
				'container' => $container
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
				return self::RES_ABSENT;
			} else {
				$this->onError( null, __METHOD__,
					[ 'cont' => $container ], $rerr, $rcode, $rdesc, $rbody );

				return self::RES_ERROR;
			}
		}

		return $this->containerStatCache->getField( $container, 'stat' );
	}

	/**
	 * Create a Swift container
	 *
	 * @param string $container Container name
	 * @param array $params
	 * @return StatusValue Good status without value for success, fatal otherwise.
	 */
	protected function createContainer( $container, array $params ) {
		$status = $this->newStatus();

		// @see SwiftFileBackend::setContainerAccess()
		if ( empty( $params['noAccess'] ) ) {
			// public
			$readUsers = array_merge( $this->readUsers, [ '.r:*', $this->swiftUser ] );
			if ( empty( $params['noListing'] ) ) {
				$readUsers[] = '.rlistings';
			}
			$writeUsers = array_merge( $this->writeUsers, [ $this->swiftUser ] );
		} else {
			// private
			$readUsers = array_merge( $this->secureReadUsers, [ $this->swiftUser ] );
			$writeUsers = array_merge( $this->secureWriteUsers, [ $this->swiftUser ] );
		}

		[ $rcode, $rdesc, , $rbody, $rerr ] = $this->requestWithAuth( [
			'method' => 'PUT',
			'container' => $container,
			'headers' => [
				'x-container-read' => implode( ',', $readUsers ),
				'x-container-write' => implode( ',', $writeUsers )
			]
		] );

		if ( $rcode === 201 ) { // new
			// good
		} elseif ( $rcode === 202 ) { // already there
			// this shouldn't really happen, but is OK
		} else {
			$this->onError( $status, __METHOD__, $params, $rerr, $rcode, $rdesc, $rbody );
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

		[ $rcode, $rdesc, , $rbody, $rerr ] = $this->requestWithAuth( [
			'method' => 'DELETE',
			'container' => $container
		] );

		if ( $rcode >= 200 && $rcode <= 299 ) { // deleted
			$this->containerStatCache->clear( $container ); // purge
		} elseif ( $rcode === 404 ) { // not there
			// this shouldn't really happen, but is OK
		} elseif ( $rcode === 409 ) { // not empty
			$this->onError( $status, __METHOD__, $params, $rerr, $rcode, $rdesc ); // race?
		} else {
			$this->onError( $status, __METHOD__, $params, $rerr, $rcode, $rdesc, $rbody );
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

		[ $rcode, $rdesc, , $rbody, $rerr ] = $this->requestWithAuth( [
			'method' => 'GET',
			'container' => $fullCont,
			'query' => $query,
		] );

		$params = [ 'cont' => $fullCont, 'prefix' => $prefix, 'delim' => $delim ];
		if ( $rcode === 200 ) { // good
			if ( $type === 'info' ) {
				$status->value = json_decode( trim( $rbody ) );
			} else {
				$status->value = explode( "\n", trim( $rbody ) );
			}
		} elseif ( $rcode === 204 ) {
			$status->value = []; // empty container
		} elseif ( $rcode === 404 ) {
			$status->value = []; // no container
		} else {
			$this->onError( $status, __METHOD__, $params, $rerr, $rcode, $rdesc, $rbody );
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

		$reqs = []; // (path => op)
		// (a) Check the containers of the paths...
		foreach ( $params['srcs'] as $path ) {
			[ $srcCont, $srcRel ] = $this->resolveStoragePathReal( $path );
			if ( $srcRel === null ) {
				// invalid storage path
				$stats[$path] = self::RES_ERROR;
				continue;
			}

			$cstat = $this->getContainerStat( $srcCont );
			if ( $cstat === self::RES_ABSENT ) {
				$stats[$path] = self::RES_ABSENT;
				continue; // ok, nothing to do
			} elseif ( $cstat === self::RES_ERROR ) {
				$stats[$path] = self::RES_ERROR;
				continue;
			}

			$reqs[$path] = [
				'method'  => 'HEAD',
				'container' => $srcCont,
				'relPath' => $srcRel,
				'headers' => $this->headersFromParams( $params )
			];
		}

		// (b) Check the files themselves...
		$reqs = $this->requestMultiWithAuth(
			$reqs,
			[ 'maxConnsPerHost' => $params['concurrency'] ]
		);
		foreach ( $reqs as $path => $op ) {
			[ $rcode, $rdesc, $rhdrs, $rbody, $rerr ] = $op['response'];
			if ( $rcode === 200 || $rcode === 204 ) {
				// Update the object if it is missing some headers
				if ( !empty( $params['requireSHA1'] ) ) {
					$rhdrs = $this->addMissingHashMetadata( $rhdrs, $path );
				}
				// Load the stat map from the headers
				$stat = $this->getStatFromHeaders( $rhdrs );
				if ( $this->isRGW ) {
					$stat['latest'] = true; // strong consistency
				}
			} elseif ( $rcode === 404 ) {
				$stat = self::RES_ABSENT;
			} else {
				$stat = self::RES_ERROR;
				$this->onError( null, __METHOD__, $params, $rerr, $rcode, $rdesc, $rbody );
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
		$metadata = $this->getMetadataFromHeaders( $rhdrs );
		// Fetch all of the custom raw HTTP headers
		$headers = $this->extractMutableContentHeaders( $rhdrs );

		return [
			// Convert various random Swift dates to TS_MW
			'mtime' => $this->convertSwiftDate( $rhdrs['last-modified'], TS_MW ),
			// Empty objects actually return no content-length header in Ceph
			'size'  => isset( $rhdrs['content-length'] ) ? (int)$rhdrs['content-length'] : 0,
			'sha1'  => $metadata['sha1base36'] ?? null,
			// Note: manifest ETags are not an MD5 of the file
			'md5'   => ctype_xdigit( $rhdrs['etag'] ) ? $rhdrs['etag'] : null,
			'xattr' => [ 'metadata' => $metadata, 'headers' => $headers ]
		];
	}

	/**
	 * Get the cached auth token.
	 *
	 * @return array|null Credential map
	 */
	protected function getAuthentication() {
		if ( $this->authErrorTimestamp !== null ) {
			$interval = time() - $this->authErrorTimestamp;
			if ( $interval < 60 ) {
				$this->logger->debug(
					'rejecting request since auth failure occurred {interval} seconds ago',
					[ 'interval' => $interval ]
				);
				return null;
			} else { // actually retry this time
				$this->authErrorTimestamp = null;
			}
		}
		// Authenticate with proxy and get a session key...
		if ( !$this->authCreds ) {
			$cacheKey = $this->getCredsCacheKey( $this->swiftUser );
			$creds = $this->srvCache->get( $cacheKey ); // credentials
			// Try to use the credential cache
			if ( isset( $creds['auth_token'] )
				&& isset( $creds['storage_url'] )
				&& isset( $creds['expiry_time'] )
				&& $creds['expiry_time'] > time()
			) {
				$this->setAuthCreds( $creds );
			} else { // cache miss
				$this->refreshAuthentication();
			}
		}

		return $this->authCreds;
	}

	/**
	 * Update the auth credentials
	 *
	 * @param array|null $creds
	 */
	private function setAuthCreds( ?array $creds ) {
		$this->logger->debug( 'Using auth token with expiry_time={expiry_time}',
			[
				'expiry_time' => isset( $creds['expiry_time'] )
					? gmdate( 'c', $creds['expiry_time'] ) : 'null'
			]
		);
		$this->authCreds = $creds;
		// Ceph RGW does not use <account> in URLs (OpenStack Swift uses "/v1/<account>")
		if ( $creds && str_ends_with( $creds['storage_url'], '/v1' ) ) {
			$this->isRGW = true; // take advantage of strong consistency in Ceph
		}
	}

	/**
	 * Fetch the auth token from the server, without caching.
	 *
	 * @return array|null Credential map
	 */
	private function refreshAuthentication() {
		[ $rcode, , $rhdrs, $rbody, ] = $this->http->run( [
			'method' => 'GET',
			'url' => "{$this->swiftAuthUrl}/v1.0",
			'headers' => [
				'x-auth-user' => $this->swiftUser,
				'x-auth-key' => $this->swiftKey
			]
		], self::DEFAULT_HTTP_OPTIONS );

		if ( $rcode >= 200 && $rcode <= 299 ) { // OK
			if ( isset( $rhdrs['x-auth-token-expires'] ) ) {
				$ttl = intval( $rhdrs['x-auth-token-expires'] );
			} else {
				$ttl = $this->authTTL;
			}
			$expiryTime = time() + $ttl;
			$creds = [
				'auth_token' => $rhdrs['x-auth-token'],
				'storage_url' => $this->swiftStorageUrl ?? $rhdrs['x-storage-url'],
				'expiry_time' => $expiryTime,
			];
			$this->srvCache->set( $this->getCredsCacheKey( $this->swiftUser ), $creds, $expiryTime );
		} elseif ( $rcode === 401 ) {
			$this->onError( null, __METHOD__, [], "Authentication failed.", $rcode );
			$this->authErrorTimestamp = time();
			$creds = null;
		} else {
			$this->onError( null, __METHOD__, [], "HTTP return code: $rcode", $rcode, $rbody );
			$this->authErrorTimestamp = time();
			$creds = null;
		}
		$this->setAuthCreds( $creds );
		return $creds;
	}

	/**
	 * @param array $creds From getAuthentication()
	 * @param string|null $container
	 * @param string|null $object
	 * @return string
	 */
	protected function storageUrl( array $creds, $container = null, $object = null ) {
		$parts = [ $creds['storage_url'] ];
		if ( ( $container ?? '' ) !== '' ) {
			$parts[] = rawurlencode( $container );
		}
		if ( ( $object ?? '' ) !== '' ) {
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
	 * Perform an authenticated HTTP request
	 *
	 * @param array $req The request data, including:
	 *   - container: The name of the container (required)
	 *   - relPath: The relative path under the container. If this is omitted,
	 *     the request will refer to the container itself.
	 *   - headers: An array of request headers to send, in addition to the
	 *     auth headers.
	 *   - Other keys to be passed through to MultiHttpClient::run()
	 * @return array The response array from MultiHttpClient::run()
	 */
	private function requestWithAuth( array $req ) {
		return $this->requestMultiWithAuth( [ $req ] )[0]['response'];
	}

	/**
	 * Perform a batch of authenticated HTTP requests
	 *
	 * @param array $reqs An array of request data arrays. See self::requestWithAuth()
	 * @param array $options Options to pass through to MultiHttpClient, in
	 *    addition to the default options DEFAULT_HTTP_OPTIONS
	 * @return array The request array with responses populated, as returned by
	 *   MultiHttpClient::runMulti()
	 */
	private function requestMultiWithAuth( array $reqs, $options = [] ) {
		$remainingTries = 2;
		$auth = $this->getAuthentication();
		while ( true ) {
			if ( !$auth ) {
				foreach ( $reqs as &$req ) {
					if ( !isset( $req['response'] ) ) {
						$req['response'] = $this->getAuthFailureResponse();
					}
				}
				break;
			}
			foreach ( $reqs as &$req ) {
				'@phan-var array $req'; // Not array[]
				if ( isset( $req['response'] ) ) {
					// Request was attempted before
					// Retry only if it gave a 401 response code
					if ( $req['response']['code'] !== 401 ) {
						continue;
					}
				}
				$req['headers'] = $this->authTokenHeaders( $auth ) + ( $req['headers'] ?? [] );
				$req['url'] = $this->storageUrl( $auth, $req['container'], $req['relPath'] ?? null );
			}
			unset( $req );
			$reqs = $this->http->runMulti( $reqs, $options + self::DEFAULT_HTTP_OPTIONS );
			if ( --$remainingTries > 0 ) {
				// Retry if any request failed with 401 "not authorized"
				foreach ( $reqs as $req ) {
					if ( $req['response']['code'] === 401 ) {
						$auth = $this->refreshAuthentication();
						continue 2;
					}
				}
			}
			break;
		}
		return $reqs;
	}

	/**
	 * Get a synthetic response to return from requestWithAuth() or requestMultiWithAuth()
	 * if the request could not be issued due to failure of a prior authentication request.
	 * This failure should not be logged as an HTTP error since the original failure would
	 * have been logged.
	 *
	 * @return array
	 */
	private function getAuthFailureResponse() {
		return [
			'code' => 0,
			0 => 0,
			'reason' => '',
			1 => '',
			'headers' => [],
			2 => [],
			'body' => '',
			3 => '',
			'error' => self::AUTH_FAILURE_ERROR,
			4 => self::AUTH_FAILURE_ERROR
		];
	}

	/**
	 * Log an unexpected exception for this backend.
	 * This also sets the StatusValue object to have a fatal error.
	 *
	 * @param StatusValue|null $status To add fatal errors to
	 * @param string $func
	 * @param array $params
	 * @param string $err Error string
	 * @param int $code HTTP status
	 * @param string $desc HTTP StatusValue description
	 * @param string $body HTTP body
	 */
	public function onError( $status, $func, array $params, $err = '', $code = 0, $desc = '', $body = '' ) {
		if ( $code === 0 && $err === self::AUTH_FAILURE_ERROR ) {
			if ( $status instanceof StatusValue ) {
				$status->fatal( 'backend-fail-connect', $this->name );
			}
			// Already logged
			return;
		}
		if ( $status instanceof StatusValue ) {
			$status->fatal( 'backend-fail-internal', $this->name );
		}
		$msg = "HTTP {code} ({desc}) in '{func}'";
		$msgParams = [
			'code'   => $code,
			'desc'   => $desc,
			'func'   => $func,
			'req_params' => $params,
		];
		if ( $err ) {
			$msg .= ': {err}';
			$msgParams['err'] = $err;
		}
		if ( $code == 502 ) {
			$msg .= ' ({truncatedBody})';
			$msgParams['truncatedBody'] = substr( strip_tags( $body ), 0, 100 );
		}
		$this->logger->error( $msg, $msgParams );
	}
}

/** @deprecated class alias since 1.43 */
class_alias( SwiftFileBackend::class, 'SwiftFileBackend' );
