<?php

/**
 * Interface to key-value storage on RESTBase.
 * Uses URL of the form URL/{KEY} to store/fetch/delete.
 * E.g., when base URL is /v1/sessions/ then the store would do:
 * POST /v1/sessions/12345758
 * and fetch would do:
 * GET /v1/sessions/12345758
 * delete would do:
 * DELETE /v1/sessions/12345758
 */
class RESTBaseBagOStuff extends BagOStuff {

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
		parent::__construct( $params );
		if ( empty( $params['client'] ) ) {
			$this->client = new MultiHttpClient( [] );
		} else {
			$this->client = $params['client'];
		}
		if ( empty( $params['url'] ) ) {
			throw new InvalidArgumentException( 'URL parameter is required' );
		}
		$this->url = $params['url'];
	}

	/**
	 * @param string  $key
	 * @param integer $flags Bitfield of BagOStuff::READ_* constants [optional]
	 * @return mixed Returns false on failure and if the item does not exist
	 */
	protected function doGet( $key, $flags = 0 ) {
		$req = [
			'method' => 'GET',
		    'url' => $this->url . rawurlencode( $key ),

		];
		list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $this->client->run( $req );
		if ( $rcode === 200 ) {
			return $rbody;
		}
		if ( $rcode === 0 || ( $rcode >= 400 && $rcode != 404 ) ) {
			return $this->handleError( "Failed to fetch $key", $rcode, $rerr );
		}
		return false;
	}

	/**
	 * Handle storage error
	 * @param string $msg Error message
	 * @param int    $rcode Error code from client
	 * @param string $rerr Error message from client
	 * @return false
	 */
	protected function handleError( $msg, $rcode, $rerr ) {
		$this->logger->error( "$msg : ($rcode) $rerr" );
		$this->setLastError( $rcode === 0 ? self::ERR_UNREACHABLE : self::ERR_UNEXPECTED );
		return false;
	}

	/**
	 * Set an item
	 *
	 * @param string $key
	 * @param mixed  $value
	 * @param int    $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @param int    $flags Bitfield of BagOStuff::WRITE_* constants
	 * @return bool Success
	 */
	public function set( $key, $value, $exptime = 0, $flags = 0 ) {
		$req = [
			'method' => 'POST',
			'url' => $this->url . rawurlencode( $key ),
			'body' => $value
		];
		list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $this->client->run( $req );
		if ( $rcode === 200 || $rcode === 201 ) {
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
		if ( $rcode === 200 || $rcode === 204 || $rcode === 205 ) {
			return true;
		}
		return $this->handleError( "Failed to delete $key", $rcode, $rerr );
	}
}
