<?php

namespace MediaWiki\Shell;

use MediaWiki\Http\HttpRequestFactory;
use Psr\Http\Message\RequestInterface;
use Shellbox\GuzzleHttpClient;

/**
 * The MediaWiki-specific implementation of a Shellbox HTTP client
 */
class ShellboxHttpClient extends GuzzleHttpClient {
	/** @var HttpRequestFactory */
	private $requestFactory;
	/** @var int|float Timeout in seconds */
	private $timeout;

	/**
	 * @param HttpRequestFactory $requestFactory
	 * @param int|float $timeout
	 */
	public function __construct( HttpRequestFactory $requestFactory, $timeout ) {
		$this->requestFactory = $requestFactory;
		$this->timeout = $timeout;
	}

	protected function modifyRequest( RequestInterface $request ): RequestInterface {
		return $request
			->withHeader( 'X-Request-Id', \WebRequest::getRequestId() );
	}

	protected function createClient( RequestInterface $request ) {
		return $this->requestFactory->createGuzzleClient( [
			'timeout' => $this->timeout
		] );
	}
}
