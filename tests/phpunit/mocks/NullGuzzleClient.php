<?php

use PHPUnit\Framework\Assert;
use Psr\Http\Message\RequestInterface;

class NullGuzzleClient extends \GuzzleHttp\Client {
	/** @inheritDoc */
	public function __construct( $config ) {
	}

	public function send( RequestInterface $request, array $options = [] ): never {
		$url = $request->getUri();
		$trace = NullHttpUtil::getFormattedTrace();
		Assert::fail( "HTTP request blocked: $url. Use MockHttpTrait.\n$trace" );
	}

	public function sendAsync( RequestInterface $request, array $options = [] ): never {
		$url = $request->getUri();
		$trace = NullHttpUtil::getFormattedTrace();
		Assert::fail( "HTTP request blocked: $url. Use MockHttpTrait.\n$trace" );
	}

	public function sendRequest( RequestInterface $request ): never {
		$url = $request->getUri();
		$trace = NullHttpUtil::getFormattedTrace();
		Assert::fail( "HTTP request blocked: $url. Use MockHttpTrait.\n$trace" );
	}

	/** @inheritDoc */
	public function request( string $method, $uri = '', array $options = [] ): never {
		$trace = NullHttpUtil::getFormattedTrace();
		Assert::fail( "HTTP request blocked: $uri. Use MockHttpTrait.\n$trace" );
	}

	/** @inheritDoc */
	public function requestAsync( string $method, $uri = '', array $options = [] ): never {
		$trace = NullHttpUtil::getFormattedTrace();
		Assert::fail( "HTTP request blocked: $uri. Use MockHttpTrait.\n$trace" );
	}
}
