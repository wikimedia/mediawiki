<?php

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\MainConfigNames;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;
use Psr\Log\NullLogger;

/**
 * A simple {@link HttpRequestFactory} implementation that can be used to prevent
 * HTTP requests in tests. All attempts to create requests will fail.
 *
 * Use MockHttpTrait for creating a mock factory and controlling responses.
 *
 * @author Daniel Kinzler
 * @license GPL-2.0-or-later
 */
class NullHttpRequestFactory extends HttpRequestFactory {

	public function __construct() {
		$options = new ServiceOptions(
			self::CONSTRUCTOR_OPTIONS, [
			MainConfigNames::HTTPTimeout => 1,
			MainConfigNames::HTTPConnectTimeout => 1,
			MainConfigNames::HTTPMaxTimeout => 2,
			MainConfigNames::HTTPMaxConnectTimeout => 2,
			MainConfigNames::LocalVirtualHosts => [],
			MainConfigNames::LocalHTTPProxy => false,
			]
		);

		parent::__construct( $options, new NullLogger() );
	}

	/**
	 * Always fails.
	 *
	 * @param string $url
	 * @param array $options
	 * @param string $caller
	 *
	 * @throws AssertionFailedError always
	 */
	public function create( $url, array $options = [], $caller = __METHOD__ ) {
		Assert::fail( "HTTP request blocked: $url by $caller. Use MockHttpTrait." );
	}

	/**
	 * Returns a NullMultiHttpClient that will fail to make any requests.
	 *
	 * @param array $options
	 *
	 * @return NullMultiHttpClient
	 */
	public function createMultiClient( $options = [] ) {
		return new NullMultiHttpClient( $options );
	}

	/**
	 * @param array $config
	 *
	 * @return \GuzzleHttp\Client
	 */
	public function createGuzzleClient( array $config = [] ): \GuzzleHttp\Client {
		return new NullGuzzleClient( $config );
	}

}
