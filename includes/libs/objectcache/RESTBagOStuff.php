<?php

use Psr\Log\LoggerInterface;

/**
 * Interface to key-value storage behind an HTTP server.
 *
 * Uses URL of the form "baseURL/{KEY}" to store, fetch, and delete values.
 *
 * E.g., when base URL is `/sessions/v1`, then the store would do:
 *
 * `PUT /sessions/v1/12345758`
 *
 * and fetch would do:
 *
 * `GET /sessions/v1/12345758`
 *
 * delete would do:
 *
 * `DELETE /sessions/v1/12345758`
 *
 * Minimal generic configuration:
 *
 * @code
 * $wgObjectCaches['sessions'] = array(
 *	'class' => 'RESTBagOStuff',
 *	'url' => 'http://localhost:7231/wikimedia.org/somepath/'
 * );
 * @endcode
 *
 * Configuration for Kask (session storage):
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
	const DEFAULT_CONN_TIMEOUT = 1.2;

	/**
	 * Default request timeout
	 */
	const DEFAULT_REQ_TIMEOUT = 3.0;

	/**
	 * @var MultiHttpClient
	 */
	private $client;

	/**
	 * REST URL to use for storage.
	 * @var string
	 */
	private $url;

	/**
	 * @var array http parameters: readHeaders, writeHeaders, deleteHeaders, writeMethod
	 */
	private $httpParams;

	/**
	 * Optional serialization type to use. Allowed values: "PHP", "JSON", or "legacy".
	 * "legacy" is PHP serialization with no serialization type tagging or hmac protection.
	 * @var string
	 * @deprecated since 1.34, the "legacy" value will be removed in 1.35.
	 *   Use either "PHP" or "JSON".
	 */
	private $serializationType;

	/**
	 * Optional HMAC Key for protecting the serialized blob. If omitted, or if serializationType
	 * is "legacy", then no protection is done
	 * @var string
	 */
	private $hmacKey;

	/**
	 * @var array additional body fields to log on error, if possible
	 */
	private $extendedErrorBodyFields;

	public function __construct( $params ) {
		$params['segmentationSize'] = $params['segmentationSize'] ?? INF;
		if ( empty( $params['url'] ) ) {
			throw new InvalidArgumentException( 'URL parameter is required' );
		}

		if ( empty( $params['client'] ) ) {
			// Pass through some params to the HTTP client.
			$clientParams = [
				'connTimeout' => $params['connTimeout'] ?? self::DEFAULT_CONN_TIMEOUT,
				'reqTimeout' => $params['reqTimeout'] ?? self::DEFAULT_REQ_TIMEOUT,
			];
			foreach ( [ 'caBundlePath', 'proxy' ] as $key ) {
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
		$this->serializationType = $params['serialization_type'] ?? 'legacy';
		$this->hmacKey = $params['hmac_key'] ?? '';

		// The parent constructor calls setLogger() which sets the logger in $this->client
		parent::__construct( $params );

		// Make sure URL ends with /
		$this->url = rtrim( $params['url'], '/' ) . '/';

		// Default config, R+W > N; no locks on reads though; writes go straight to state-machine
		$this->attrMap[self::ATTR_SYNCWRITES] = self::QOS_SYNCWRITES_QC;
	}

	public function setLogger( LoggerInterface $logger ) {
		parent::setLogger( $logger );
		$this->client->setLogger( $logger );
	}

	protected function doGet( $key, $flags = 0, &$casToken = null ) {
		$casToken = null;

		$req = [
			'method' => 'GET',
			'url' => $this->url . rawurlencode( $key ),
			'headers' => $this->httpParams['readHeaders'],
		];

		list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $this->client->run( $req );
		if ( $rcode === 200 ) {
			if ( is_string( $rbody ) ) {
				$value = $this->decodeBody( $rbody );
				/// @FIXME: use some kind of hash or UUID header as CAS token
				$casToken = ( $value !== false ) ? $rbody : null;

				return $value;
			}
			return false;
		}
		if ( $rcode === 0 || ( $rcode >= 400 && $rcode != 404 ) ) {
			return $this->handleError( "Failed to fetch $key", $rcode, $rerr, $rhdrs, $rbody );
		}
		return false;
	}

	protected function doSet( $key, $value, $exptime = 0, $flags = 0 ) {
		// @TODO: respect WRITE_SYNC (e.g. EACH_QUORUM)
		// @TODO: respect $exptime
		$req = [
			'method' => $this->httpParams['writeMethod'],
			'url' => $this->url . rawurlencode( $key ),
			'body' => $this->encodeBody( $value ),
			'headers' => $this->httpParams['writeHeaders'],
		];

		list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $this->client->run( $req );
		if ( $rcode === 200 || $rcode === 201 || $rcode === 204 ) {
			return true;
		}
		return $this->handleError( "Failed to store $key", $rcode, $rerr, $rhdrs, $rbody );
	}

	protected function doAdd( $key, $value, $exptime = 0, $flags = 0 ) {
		// @TODO: make this atomic
		if ( $this->get( $key ) === false ) {
			return $this->set( $key, $value, $exptime, $flags );
		}

		return false; // key already set
	}

	protected function doDelete( $key, $flags = 0 ) {
		// @TODO: respect WRITE_SYNC (e.g. EACH_QUORUM)
		$req = [
			'method' => 'DELETE',
			'url' => $this->url . rawurlencode( $key ),
			'headers' => $this->httpParams['deleteHeaders'],
		];

		list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $this->client->run( $req );
		if ( in_array( $rcode, [ 200, 204, 205, 404, 410 ] ) ) {
			return true;
		}
		return $this->handleError( "Failed to delete $key", $rcode, $rerr, $rhdrs, $rbody );
	}

	public function incr( $key, $value = 1, $flags = 0 ) {
		// @TODO: make this atomic
		$n = $this->get( $key, self::READ_LATEST );
		if ( $this->isInteger( $n ) ) { // key exists?
			$n = max( $n + (int)$value, 0 );
			// @TODO: respect $exptime
			return $this->set( $key, $n ) ? $n : false;
		}

		return false;
	}

	public function decr( $key, $value = 1, $flags = 0 ) {
		return $this->incr( $key, -$value, $flags );
	}

	/**
	 * Processes the response body.
	 *
	 * @param string $body request body to process
	 * @return mixed|bool the processed body, or false on error
	 */
	private function decodeBody( $body ) {
		if ( $this->serializationType === 'legacy' ) {
			$serialized = $body;
		} else {
			$pieces = explode( '.', $body, 3 );
			if ( count( $pieces ) !== 3 || $pieces[0] !== $this->serializationType ) {
				return false;
			}
			list( , $hmac, $serialized ) = $pieces;
			if ( $this->hmacKey !== '' ) {
				$checkHmac = hash_hmac( 'sha256', $serialized, $this->hmacKey, true );
				if ( !hash_equals( $checkHmac, base64_decode( $hmac ) ) ) {
					return false;
				}
			}
		}

		switch ( $this->serializationType ) {
			case 'JSON':
				$value = json_decode( $serialized, true );
				return ( json_last_error() === JSON_ERROR_NONE ) ? $value : false;

			case 'PHP':
			case 'legacy':
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
	 * @return string the prepared body
	 * @throws LogicException
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
			case "legacy":
				$value = serialize( $body );
				break;

			default:
				throw new \DomainException(
					"Unknown serialization type: $this->serializationType"
				);
		}

		if ( $this->serializationType !== 'legacy' ) {
			if ( $this->hmacKey !== '' ) {
				$hmac = base64_encode(
					hash_hmac( 'sha256', $value, $this->hmacKey, true )
				);
			} else {
				$hmac = '';
			}
			$value = $this->serializationType . '.' . $hmac . '.' . $value;
		}

		return $value;
	}

	/**
	 * Handle storage error
	 * @param string $msg Error message
	 * @param int $rcode Error code from client
	 * @param string $rerr Error message from client
	 * @param array $rhdrs Response headers
	 * @param string $rbody Error body from client (if any)
	 * @return false
	 */
	protected function handleError( $msg, $rcode, $rerr, $rhdrs, $rbody ) {
		$message = "$msg : ({code}) {error}";
		$context = [
			'code' => $rcode,
			'error' => $rerr
		];

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
		return false;
	}
}
