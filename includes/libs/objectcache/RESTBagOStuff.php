<?php

namespace Wikimedia\ObjectCache;

use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Wikimedia\Http\MultiHttpClient;

/**
 * Store key-value data via an HTTP service.
 *
 * ### Important caveats
 *
 * This interface is currently an incomplete BagOStuff implementation,
 * supported only for use with MediaWiki features that accept a dedicated
 * cache type to use for a narrow set of cache keys that share the same
 * key expiry and replication requirements, and where the key-value server
 * in question is statically configured with domain knowledge of said
 * key expiry and replication requirements.
 *
 * Specifically, RESTBagOStuff has the following limitations:
 *
 * - The expiry parameter is ignored in methods like `set()`.
 *
 *   There is not currently an agreed protocol for sending this to a
 *   server. This class is written for use with MediaWiki\Session\SessionManager
 *   and Kask/Cassandra at WMF, which does not expose a customizable expiry.
 *
 *   As such, it is not recommended to use RESTBagOStuff to back a general
 *   purpose cache type (such as MediaWiki's main cache, or main stash).
 *   Instead, it is only supported for MediaWiki features where a cache type
 *   can be pointed to a narrow set of keys that naturally share the same TTL
 *   anyway, or where the feature behaves correctly even if the logical expiry
 *   is longer than specified (e.g. immutable keys, or value verification)
 *
 * - Most methods are non-atomic.
 *
 *   The class should only be used for get, set, and delete operations.
 *   Advanced methods like `incr()`, `add()` and `lock()` do exist but
 *   inherit a native and best-effort implementation based on get+set.
 *   These should not be relied upon.
 *
 * ### Backend requirements
 *
 * The HTTP server will receive requests for URLs like `{baseURL}/{KEY}`. It
 * must implement the GET, PUT and DELETE methods.
 *
 * E.g., when the base URL is `/sessions/v1`, then `set()` will:
 *
 * `PUT /sessions/v1/mykeyhere`
 *
 * and `get()` would do:
 *
 * `GET /sessions/v1/mykeyhere`
 *
 * and `delete()` would do:
 *
 * `DELETE /sessions/v1/mykeyhere`
 *
 * ### Example configuration
 *
 * Minimal generic configuration:
 *
 * @code
 * $wgObjectCaches['sessions'] = array(
 *	'class' => 'RESTBagOStuff',
 *	'url' => 'http://localhost:7231/example/'
 * );
 * @endcode
 *
 *
 * Configuration for [Kask](https://www.mediawiki.org/wiki/Kask) session store:
 * @code
 * $wgObjectCaches['sessions'] = array(
 *	'class' => 'RESTBagOStuff',
 *	'url' => 'https://kaskhost:1234/sessions/v1/',
 *	'httpParams' => [
 *		'readHeaders' => [],
 *		'writeHeaders' => [ 'content-type' => 'application/octet-stream' ],
 *		'deleteHeaders' => [],
 *		'writeMethod' => 'POST',
 *	],
 *	'serialization_type' => 'JSON',
 * 	'extendedErrorBodyFields' => [ 'type', 'title', 'detail', 'instance' ]
 * );
 * $wgSessionCacheType = 'sessions';
 * @endcode
 */
class RESTBagOStuff extends MediumSpecificBagOStuff {
	/**
	 * Default connection timeout in seconds. The kernel retransmits the SYN
	 * packet after 1 second, so 1.2 seconds allows for 1 retransmit without
	 * permanent failure.
	 */
	private const DEFAULT_CONN_TIMEOUT = 1.2;

	/**
	 * Default request timeout
	 */
	private const DEFAULT_REQ_TIMEOUT = 3.0;

	/**
	 * @var MultiHttpClient
	 */
	private $client;

	/**
	 * REST URL to use for storage.
	 *
	 * @var string
	 */
	private $url;

	/**
	 * HTTP parameters: readHeaders, writeHeaders, deleteHeaders, writeMethod.
	 *
	 * @var array
	 */
	private $httpParams;

	/**
	 * Optional serialization type to use. Allowed values: "PHP", "JSON".
	 *
	 * @var string
	 */
	private $serializationType;

	/**
	 * Optional HMAC Key for protecting the serialized blob. If omitted no protection is done
	 *
	 * @var string
	 */
	private $hmacKey;

	/**
	 * @var array additional body fields to log on error, if possible
	 */
	private $extendedErrorBodyFields;

	public function __construct( array $params ) {
		$params['segmentationSize'] ??= INF;
		if ( empty( $params['url'] ) ) {
			throw new InvalidArgumentException( 'URL parameter is required' );
		}

		if ( empty( $params['client'] ) ) {
			// Pass through some params to the HTTP client.
			$clientParams = [
				'connTimeout' => $params['connTimeout'] ?? self::DEFAULT_CONN_TIMEOUT,
				'reqTimeout' => $params['reqTimeout'] ?? self::DEFAULT_REQ_TIMEOUT,
			];
			foreach ( [ 'caBundlePath', 'proxy', 'telemetry' ] as $key ) {
				if ( isset( $params[$key] ) ) {
					$clientParams[$key] = $params[$key];
				}
			}
			$this->client = new MultiHttpClient( $clientParams );
		} else {
			$this->client = $params['client'];
		}

		$this->httpParams['writeMethod'] = $params['httpParams']['writeMethod'] ?? 'PUT';
		$this->httpParams['readHeaders'] = $params['httpParams']['readHeaders'] ?? [];
		$this->httpParams['writeHeaders'] = $params['httpParams']['writeHeaders'] ?? [];
		$this->httpParams['deleteHeaders'] = $params['httpParams']['deleteHeaders'] ?? [];
		$this->extendedErrorBodyFields = $params['extendedErrorBodyFields'] ?? [];
		$this->serializationType = $params['serialization_type'] ?? 'PHP';
		$this->hmacKey = $params['hmac_key'] ?? '';

		// The parent constructor calls setLogger() which sets the logger in $this->client
		parent::__construct( $params );

		// Make sure URL ends with /
		$this->url = rtrim( $params['url'], '/' ) . '/';

		$this->attrMap[self::ATTR_DURABILITY] = self::QOS_DURABILITY_DISK;
	}

	public function setLogger( LoggerInterface $logger ): void {
		parent::setLogger( $logger );
		$this->client->setLogger( $logger );
	}

	protected function doGet( $key, $flags = 0, &$casToken = null ) {
		$getToken = ( $casToken === self::PASS_BY_REF );
		$casToken = null;

		$req = [
			'method' => 'GET',
			'url' => $this->url . rawurlencode( $key ),
			'headers' => $this->httpParams['readHeaders'],
		];

		$value = false;
		$valueSize = false;
		[ $rcode, , , $rbody, $rerr ] = $this->client->run( $req );
		if ( $rcode === 200 && is_string( $rbody ) ) {
			$value = $this->decodeBody( $rbody );
			$valueSize = strlen( $rbody );
			// @FIXME: use some kind of hash or UUID header as CAS token
			if ( $getToken && $value !== false ) {
				$casToken = $rbody;
			}
		} elseif ( $rcode === 0 || ( $rcode >= 400 && $rcode != 404 ) ) {
			$this->handleError( 'Failed to fetch {cacheKey}', $rcode, $rerr, $rbody,
				[ 'cacheKey' => $key ] );
		}

		$this->updateOpStats( self::METRIC_OP_GET, [ $key => [ 0, $valueSize ] ] );

		return $value;
	}

	protected function doSet( $key, $value, $exptime = 0, $flags = 0 ) {
		$req = [
			'method' => $this->httpParams['writeMethod'],
			'url' => $this->url . rawurlencode( $key ),
			'body' => $this->encodeBody( $value ),
			'headers' => $this->httpParams['writeHeaders'],
		];

		[ $rcode, , , $rbody, $rerr ] = $this->client->run( $req );
		$res = ( $rcode === 200 || $rcode === 201 || $rcode === 204 );
		if ( !$res ) {
			$this->handleError( 'Failed to store {cacheKey}', $rcode, $rerr, $rbody,
				[ 'cacheKey' => $key ] );
		}

		$this->updateOpStats( self::METRIC_OP_SET, [ $key => [ strlen( $req['body'] ), 0 ] ] );

		return $res;
	}

	protected function doAdd( $key, $value, $exptime = 0, $flags = 0 ) {
		// NOTE: This is non-atomic
		if ( $this->get( $key ) === false ) {
			return $this->set( $key, $value, $exptime, $flags );
		}

		// key already set
		return false;
	}

	protected function doDelete( $key, $flags = 0 ) {
		$req = [
			'method' => 'DELETE',
			'url' => $this->url . rawurlencode( $key ),
			'headers' => $this->httpParams['deleteHeaders'],
		];

		[ $rcode, , , $rbody, $rerr ] = $this->client->run( $req );
		$res = in_array( $rcode, [ 200, 204, 205, 404, 410 ] );
		if ( !$res ) {
			$this->handleError( 'Failed to delete {cacheKey}', $rcode, $rerr, $rbody,
				[ 'cacheKey' => $key ] );
		}

		$this->updateOpStats( self::METRIC_OP_DELETE, [ $key ] );

		return $res;
	}

	protected function doIncrWithInit( $key, $exptime, $step, $init, $flags ) {
		// NOTE: This is non-atomic
		$curValue = $this->doGet( $key );
		if ( $curValue === false ) {
			$newValue = $this->doSet( $key, $init, $exptime ) ? $init : false;
		} elseif ( $this->isInteger( $curValue ) ) {
			$sum = max( $curValue + $step, 0 );
			$newValue = $this->doSet( $key, $sum, $exptime ) ? $sum : false;
		} else {
			$newValue = false;
		}

		return $newValue;
	}

	/**
	 * Processes the response body.
	 *
	 * @param string $body request body to process
	 *
	 * @return mixed|bool the processed body, or false on error
	 */
	private function decodeBody( $body ) {
		$pieces = explode( '.', $body, 3 );
		if ( count( $pieces ) !== 3 || $pieces[0] !== $this->serializationType ) {
			return false;
		}
		[ , $hmac, $serialized ] = $pieces;
		if ( $this->hmacKey !== '' ) {
			$checkHmac = hash_hmac( 'sha256', $serialized, $this->hmacKey, true );
			if ( !hash_equals( $checkHmac, base64_decode( $hmac ) ) ) {
				return false;
			}
		}

		switch ( $this->serializationType ) {
			case 'JSON':
				$value = json_decode( $serialized, true );
				return ( json_last_error() === JSON_ERROR_NONE ) ? $value : false;

			case 'PHP':
				return unserialize( $serialized );

			default:
				throw new \DomainException(
					"Unknown serialization type: $this->serializationType"
				);
		}
	}

	/**
	 * Prepares the request body (the "value" portion of our key/value store) for transmission.
	 *
	 * @param string $body request body to prepare
	 *
	 * @return string the prepared body
	 */
	private function encodeBody( $body ) {
		switch ( $this->serializationType ) {
			case 'JSON':
				$value = json_encode( $body );
				if ( $value === false ) {
					throw new InvalidArgumentException( __METHOD__ . ": body could not be encoded." );
				}
				break;

			case 'PHP':
				$value = serialize( $body );
				break;

			default:
				throw new \DomainException(
					"Unknown serialization type: $this->serializationType"
				);
		}

		if ( $this->hmacKey !== '' ) {
			$hmac = base64_encode(
				hash_hmac( 'sha256', $value, $this->hmacKey, true )
			);
		} else {
			$hmac = '';
		}
		return $this->serializationType . '.' . $hmac . '.' . $value;
	}

	/**
	 * Handle storage error
	 *
	 * @param string $msg Error message
	 * @param int $rcode Error code from client
	 * @param string $rerr Error message from client
	 * @param string $rbody Error body from client (if any)
	 * @param array $context Error context for PSR-3 logging
	 */
	private function handleError( $msg, $rcode, $rerr, $rbody, $context = [] ): void {
		$message = "$msg : ({code}) {error}";
		$context = [
			'code' => $rcode,
			'error' => $rerr
		] + $context;

		if ( $this->extendedErrorBodyFields !== [] ) {
			$body = $this->decodeBody( $rbody );
			if ( $body ) {
				$extraFields = '';
				foreach ( $this->extendedErrorBodyFields as $field ) {
					if ( isset( $body[$field] ) ) {
						$extraFields .= " : ({$field}) {$body[$field]}";
					}
				}
				if ( $extraFields !== '' ) {
					$message .= " {extra_fields}";
					$context['extra_fields'] = $extraFields;
				}
			}
		}

		$this->logger->error( $message, $context );
		$this->setLastError( $rcode === 0 ? self::ERR_UNREACHABLE : self::ERR_UNEXPECTED );
	}
}

/** @deprecated class alias since 1.43 */
class_alias( RESTBagOStuff::class, 'RESTBagOStuff' );
