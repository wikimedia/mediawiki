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
		$trace = NullHttpUtil::getFormattedTrace();
		Assert::fail( "HTTP request blocked: $url. Use MockHttpTrait.\n$trace" );
	}

	public function sendAsync( RequestInterface $request, array $options = [] ): PromiseInterface {
		$url = $request->getUri();
		$trace = NullHttpUtil::getFormattedTrace();
		Assert::fail( "HTTP request blocked: $url. Use MockHttpTrait.\n$trace" );
	}

	public function sendRequest( RequestInterface $request ): ResponseInterface {
		$url = $request->getUri();
		$trace = NullHttpUtil::getFormattedTrace();
		Assert::fail( "HTTP request blocked: $url. Use MockHttpTrait.\n$trace" );
	}

	/** @inheritDoc */
	public function request( string $method, $uri = '', array $options = [] ): ResponseInterface {
		$trace = NullHttpUtil::getFormattedTrace();
		Assert::fail( "HTTP request blocked: $uri. Use MockHttpTrait.\n$trace" );
	}

	/** @inheritDoc */
	public function requestAsync( string $method, $uri = '', array $options = [] ): PromiseInterface {
		$trace = NullHttpUtil::getFormattedTrace();
		Assert::fail( "HTTP request blocked: $uri. Use MockHttpTrait.\n$trace" );
	}
}
