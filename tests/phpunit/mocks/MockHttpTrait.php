<?php
/**
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
 */

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Status\Status;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\NullLogger;
use Wikimedia\Http\MultiHttpClient;

/**
 * Trait for test cases that need to mock HTTP requests.
 *
 * @stable to use in extensions
 * @since 1.36
 */
trait MockHttpTrait {
	/**
	 * @see MediaWikiIntegrationTestCase::setService()
	 *
	 * @param string $name
	 * @phpcs:ignore MediaWiki.Commenting.FunctionComment.ObjectTypeHintParam
	 * @param object|callable $service
	 */
	abstract protected function setService( string $name, $service );

	/**
	 * Install a mock HttpRequestFactory in MediaWikiServices, for the duration
	 * of the current test case.
	 *
	 * @param null|string|array|callable|MWHttpRequest|MultiHttpClient|GuzzleHttp\Client $request
	 *        A list of MWHttpRequest to return on consecutive calls to HttpRequestFactory::create().
	 *        These MWHttpRequest also represent the desired response.
	 *        For convenience, a single MWHttpRequest can be given,
	 *        or a callable producing such an MWHttpRequest,
	 *        or a string that will be used as the response body of a successful request.
	 *        If a MultiHttpClient is given, createMultiClient() is supported.
	 *        If a GuzzleHttp\Client is given, createGuzzleClient() is supported.
	 *        Array of MultiHttpClient or GuzzleHttp\Client mocks is supported, but not an array
	 *        that contains the mix of the two.
	 *        If null is given, any call to create(), createMultiClient() or createGuzzleClient()
	 *        will cause the test to fail.
	 */
	private function installMockHttp( $request = null ) {
		$this->setService( 'HttpRequestFactory', function () use ( $request ) {
			return $this->makeMockHttpRequestFactory( $request );
		} );
	}

	/**
	 * Return a mock HttpRequestFactory in MediaWikiServices.
	 *
	 * @param null|string|array|callable|MWHttpRequest|MultiHttpClient $request A list of
	 *        MWHttpRequest to return on consecutive calls to HttpRequestFactory::create().
	 *        These MWHttpRequest also represent the desired response.
	 *        For convenience, a single MWHttpRequest can be given,
	 *        or a callable producing such an MWHttpRequest,
	 *        or a string that will be used as the response body of a successful request.
	 *        If a MultiHttpClient is given, createMultiClient() is supported.
	 *        If a GuzzleHttp\Client is given, createGuzzleClient() is supported.
	 *        Array of MultiHttpClient or GuzzleHttp\Client mocks is supported, but not an array
	 *        that contains the mix of the two.
	 *        If null or a MultiHttpClient is given instead of a MWHttpRequest,
	 *        a call to create() will cause the test to fail.
	 *
	 * @return HttpRequestFactory
	 */
	private function makeMockHttpRequestFactory( $request = null ) {
		$options = new ServiceOptions( HttpRequestFactory::CONSTRUCTOR_OPTIONS, [
			MainConfigNames::HTTPTimeout => 1,
			MainConfigNames::HTTPConnectTimeout => 1,
			MainConfigNames::HTTPMaxTimeout => 1,
			MainConfigNames::HTTPMaxConnectTimeout => 1,
			MainConfigNames::LocalVirtualHosts => [],
			MainConfigNames::LocalHTTPProxy => false,
		] );

		$failCallback = static function ( /* discard any arguments */ ) {
			TestCase::fail( 'method should not be called' );
		};

		/** @var HttpRequestFactory|MockObject $mockHttpRequestFactory */
		$mockHttpRequestFactory = $this->getMockBuilder( HttpRequestFactory::class )
			->setConstructorArgs( [ $options, new NullLogger() ] )
			->onlyMethods( [ 'create', 'createMultiClient', 'createGuzzleClient' ] )
			->getMock();

		foreach ( [
			MultiHttpClient::class => 'createMultiClient',
			GuzzleHttp\Client::class => 'createGuzzleClient'
		] as $class => $method ) {
			if ( $request instanceof $class ) {
				$mockHttpRequestFactory->method( $method )
					->willReturn( $request );
			} elseif ( $this->isArrayOfClass( $class, $request ) ) {
				$mockHttpRequestFactory->method( $method )
					->willReturnOnConsecutiveCalls( ...$request );
			} else {
				$mockHttpRequestFactory->method( $method )
					->willReturn( $this->createNoOpMock( $class ) );
			}
		}

		if ( $request === null ) {
			$mockHttpRequestFactory->method( 'create' )
				->willReturnCallback( $failCallback );
		} elseif ( $request instanceof MultiHttpClient ) {
			$mockHttpRequestFactory->method( 'create' )
				->willReturnCallback( $failCallback );
		} elseif ( $request instanceof GuzzleHttp\Client ) {
			$mockHttpRequestFactory->method( 'create' )
				->willReturnCallback( $failCallback );
		} elseif ( $request instanceof MWHttpRequest ) {
			$mockHttpRequestFactory->method( 'create' )
				->willReturn( $request );
		} elseif ( is_callable( $request ) ) {
			$mockHttpRequestFactory->method( 'create' )
				->willReturnCallback( $request );
		} elseif ( is_array( $request ) ) {
			$mockHttpRequestFactory->method( 'create' )
				->willReturnOnConsecutiveCalls( ...$request );
		} elseif ( is_string( $request ) ) {
			$mockHttpRequestFactory->method( 'create' )
				->willReturn( $this->makeFakeHttpRequest( $request ) );
		}

		return $mockHttpRequestFactory;
	}

	/**
	 * Check whether $array is an array where all elements are instances of $class.
	 *
	 * @internal to the trait
	 * @param string $class
	 * @param mixed $array
	 * @return bool
	 */
	private function isArrayOfClass( string $class, $array ): bool {
		if ( !is_array( $array ) || !count( $array ) ) {
			return false;
		}
		foreach ( $array as $item ) {
			if ( !$item instanceof $class ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Constructs a fake MWHTTPRequest. The request also represents the desired response.
	 *
	 * @note Not all methods on MWHTTPRequest are mocked, calling other methods will
	 *       cause the test to fail.
	 *
	 * @param string $body The response body.
	 * @param int|StatusValue $responseStatus The response status code. Use 0 to indicate an internal error.
	 *        Alternatively, you can provide a configured StatusValue with status code as a value and
	 *        whatever warnings or errors you want.
	 * @param string[] $headers Any response headers.
	 *
	 * @return MWHttpRequest
	 */
	private function makeFakeHttpRequest(
		$body = 'Lorem Ipsum',
		$responseStatus = 200,
		$headers = []
	) {
		$mockHttpRequest = $this->createNoOpMock(
			MWHttpRequest::class,
			[ 'execute', 'setCallback', 'isRedirect', 'getFinalUrl',
				'getResponseHeaders', 'getResponseHeader', 'setHeader',
				'getStatus', 'getContent', 'setOriginalRequest',
			]
		);

		$statusCode = $responseStatus instanceof StatusValue ? $responseStatus->getValue() : $responseStatus;
		$mockHttpRequest->method( 'isRedirect' )->willReturn(
			$statusCode >= 300 && $statusCode < 400
		);

		$mockHttpRequest->method( 'getFinalUrl' )->willReturn( $headers[ 'Location' ] ?? '' );

		$mockHttpRequest->method( 'getResponseHeaders' )->willReturn( $headers );
		$mockHttpRequest->method( 'getResponseHeader' )->willReturnCallback(
			static function ( $name ) use ( $headers ) {
				return $headers[$name] ?? null;
			}
		);

		$dataCallback = null;
		$mockHttpRequest->method( 'setCallback' )->willReturnCallback(
			static function ( $callback ) use ( &$dataCallback ) {
				$dataCallback = $callback;
			}
		);

		if ( is_int( $responseStatus ) ) {
			$statusObject = Status::newGood( $statusCode );

			if ( $statusCode === 0 ) {
				$statusObject->fatal( 'http-internal-error' );
			} elseif ( $statusCode >= 400 ) {
				$statusObject->fatal( "http-bad-status", $statusCode, $body );
			}
		} else {
			$statusObject = Status::wrap( $responseStatus );
		}

		$mockHttpRequest->method( 'getContent' )->willReturn( $body );
		$mockHttpRequest->method( 'getStatus' )->willReturn( $statusCode );

		$mockHttpRequest->method( 'execute' )->willReturnCallback(
			function () use ( &$dataCallback, $body, $statusObject ) {
				if ( $dataCallback ) {
					$dataCallback( $this, $body );
				}
				return $statusObject;
			}
		);

		return $mockHttpRequest;
	}

	/**
	 * Construct a fake HTTP request that will result in an HTTP timeout.
	 *
	 * @see self::makeFakeHttpRequest
	 * @param string $body
	 * @param string $requestUrl
	 * @return MWHttpRequest
	 */
	private function makeFakeTimeoutRequest(
		string $body = 'HTTP Timeout',
		string $requestUrl = 'https://dummy.org'
	) {
		$responseStatus = StatusValue::newGood( 504 );
		$responseStatus->fatal( 'http-timed-out', $requestUrl );
		return $this->makeFakeHttpRequest( $body, $responseStatus, [] );
	}

	/**
	 * Constructs a fake MultiHttpClient which will return the given response.
	 *
	 * @note Not all methods on MultiHttpClient are mocked, calling other methods will
	 *       cause the test to fail.
	 *
	 * @param array $responses An array mapping request keys to responses.
	 *        Each response may be a string (the response body), or an array with the
	 *        following keys (all optional): 'code', 'reason', 'headers', 'body', 'error'.
	 *        If the 'response' key is set, the associated value is expected to be the
	 *        response array and contain the 'code',  'body', etc fields. This allows
	 *        $responses to have the same structure as the return value of runMulti().
	 *
	 * @return MultiHttpClient
	 */
	private function makeFakeHttpMultiClient( $responses = [] ) {
		$mockHttpRequestMulti = $this->createNoOpMock(
			MultiHttpClient::class,
			[ 'run', 'runMulti' ]
		);

		$mockHttpRequestMulti->method( 'run' )->willReturnCallback(
			static function ( array $req, array $opts = [] ) use ( $mockHttpRequestMulti ) {
				return $mockHttpRequestMulti->runMulti( [ $req ], $opts )[0]['response'];
			}
		);

		$mockHttpRequestMulti->method( 'runMulti' )->willReturnCallback(
			static function ( array $reqs, array $opts = [] ) use ( $responses ) {
				foreach ( $reqs as $key => &$req ) {
					$resp = $responses[$key] ?? [ 'code' => 0, 'error' => 'unknown' ];

					if ( is_string( $resp ) ) {
						$resp = [ 'body' => $resp ];
					}

					if ( isset( $resp['response'] ) ) {
						// $responses is not just an array of responses,
						// but a request/response structure.
						$resp = $resp['response'];
					}

					$req['response'] = $resp + [
						'code' => 200,
						'reason' => '',
						'headers' => [],
						'body' => '',
						'error' => '',
					];

					$req['response'][0] = $req['response']['code'];
					$req['response'][1] = $req['response']['reason'];
					$req['response'][2] = $req['response']['headers'];
					$req['response'][3] = $req['response']['body'];
					$req['response'][4] = $req['response']['error'];

					unset( $req );
				}

				return $reqs;
			}
		);

		return $mockHttpRequestMulti;
	}

	/**
	 * Constructs a fake GuzzleHttp\Client which will return the given response.
	 *
	 * @note Not all methods on GuzzleHttp\Client are mocked, calling other methods will
	 *       cause the test to fail.
	 *
	 * @param ResponseInterface|string $response The response to return.
	 *
	 * @return GuzzleHttp\Client
	 */
	private function makeFakeGuzzleClient( $response ) {
		if ( is_string( $response ) ) {
			$response = new GuzzleHttp\Psr7\Response( 200, [], $response );
		}

		$mockHttpClient = $this->createNoOpMock(
			GuzzleHttp\Client::class,
			[ 'request', 'get', 'put', 'post' ]
		);

		$mockHttpClient->method( 'request' )->willReturn( $response );
		$mockHttpClient->method( 'get' )->willReturn( $response );
		$mockHttpClient->method( 'put' )->willReturn( $response );
		$mockHttpClient->method( 'post' )->willReturn( $response );

		return $mockHttpClient;
	}

}
