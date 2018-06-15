<?php

use Psr\Log\LoggerInterface;

/**
 * Interface to key-value storage behind an HTTP server.
 *
 * Uses URL of the form "baseURL/{KEY}" to store, fetch, and delete values.
 *
 * E.g., when base URL is `/v1/sessions/`, then the store would do:
 *
 * `PUT /v1/sessions/12345758`
 *
 * and fetch would do:
 *
 * `GET /v1/sessions/12345758`
 *
 * delete would do:
 *
 * `DELETE /v1/sessions/12345758`
 *
 * Configure with:
 *
 * @code
 * $wgObjectCaches['sessions'] = array(
 *	'class' => 'RESTBagOStuff',
 *	'url' => 'http://localhost:7231/wikimedia.org/v1/sessions/'
 * );
 * @endcode
 */
class RESTBagOStuff extends BagOStuff {
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

	public function __construct( $params ) {
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

	/**
	 * @param string $key
	 * @param int $flags Bitfield of BagOStuff::READ_* constants [optional]
	 * @return mixed Returns false on failure and if the item does not exist
	 */
	protected function doGet( $key, $flags = 0 ) {
		$req = [
			'method' => 'GET',
			'url' => $this->url . rawurlencode( $key ),
		];

		list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $this->client->run( $req );
		if ( $rcode === 200 ) {
			if ( is_string( $rbody ) ) {
				return unserialize( $rbody );
			}
			return false;
		}
		if ( $rcode === 0 || ( $rcode >= 400 && $rcode != 404 ) ) {
			return $this->handleError( "Failed to fetch $key", $rcode, $rerr );
		}
		return false;
	}

	/**
	 * Handle storage error
	 * @param string $msg Error message
	 * @param int $rcode Error code from client
	 * @param string $rerr Error message from client
	 * @return false
	 */
	protected function handleError( $msg, $rcode, $rerr ) {
		$this->logger->error( "$msg : ({code}) {error}", [
			'code' => $rcode,
			'error' => $rerr
		] );
		$this->setLastError( $rcode === 0 ? self::ERR_UNREACHABLE : self::ERR_UNEXPECTED );
		return false;
	}

	/**
	 * Set an item
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants
	 * @return bool Success
	 */
	public function set( $key, $value, $exptime = 0, $flags = 0 ) {
		$req = [
			'method' => 'PUT',
			'url' => $this->url . rawurlencode( $key ),
			'body' => serialize( $value )
		];
		list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $this->client->run( $req );
		if ( $rcode === 200 || $rcode === 201 || $rcode === 204 ) {
			return true;
		}
		return $this->handleError( "Failed to store $key", $rcode, $rerr );
	}

	/**
	 * Delete an item.
	 *
	 * @param string $key
	 * @return bool True if the item was deleted or not found, false on failure
	 */
	public function delete( $key ) {
		$req = [
			'method' => 'DELETE',
			'url' => $this->url . rawurlencode( $key ),
		];
		list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $this->client->run( $req );
		if ( in_array( $rcode, [ 200, 204, 205, 404, 410 ] ) ) {
			return true;
		}
		return $this->handleError( "Failed to delete $key", $rcode, $rerr );
	}
}
