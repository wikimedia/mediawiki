<?php

use GuzzleHttp\Promise\PromiseInterface;
use PHPUnit\Framework\Assert;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class NullGuzzleClient extends \GuzzleHttp\Client {
	/** @inheritDoc */
	public function __construct( $config ) {
	}

	public function send( RequestInterface $request, array $options = [] ): ResponseInterface {
		$url = $request->getUri();
		Assert::fail( "HTTP request blocked: $url. Use MockHttpTrait." );
	}

	public function sendAsync( RequestInterface $request, array $options = [] ): PromiseInterface {
		$url = $request->getUri();
		Assert::fail( "HTTP request blocked: $url. Use MockHttpTrait." );
	}

	public function sendRequest( RequestInterface $request ): ResponseInterface {
		$url = $request->getUri();
		Assert::fail( "HTTP request blocked: $url. Use MockHttpTrait." );
	}

	/** @inheritDoc */
	public function request( string $method, $uri = '', array $options = [] ): ResponseInterface {
		Assert::fail( "HTTP request blocked: $uri. Use MockHttpTrait." );
	}

	/** @inheritDoc */
	public function requestAsync( string $method, $uri = '', array $options = [] ): PromiseInterface {
		Assert::fail( "HTTP request blocked: $uri. Use MockHttpTrait." );
	}
}
