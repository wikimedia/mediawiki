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
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * Trait for test cases that need to mock HTTP requests.
 *
 * @stable to use in extensions
 * @since 1.36
 */
trait MockHttpTrait {

	/**
	 * @see TestCase::getMockBuilder()
	 */
	abstract public function getMockBuilder( $className ): MockBuilder;

	/**
	 * @see MediaWikiIntegrationTestCase::setService()
	 */
	abstract protected function setService( $name, $service );

	/**
	 * Install a mock HttpRequestFactory in MediaWikiServices, for the duration
	 * of the current test case.
	 *
	 * @param null|string|array|callable|MWHttpRequest|MultiHttpClient $request A list of
	 *        MWHttpRequest to return on consecutive calls to HttpRequestFactory::create().
	 *        These MWHttpRequest also represent the desired response.
	 *        For convenience, a single MWHttpRequest can be given,
	 *        or a callable producing such an MWHttpRequest,
	 *        or a string that will be used as the response body of a successful request.
	 *        If a MultiHttpClient is given, createMultiClient() is supported.
	 *        If null or a MultiHttpClient is given instead of a MWHttpRequest,
	 *        a call to create() will cause the test to fail.
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
	 *        If null or a MultiHttpClient is given instead of a MWHttpRequest,
	 *        a call to create() will cause the test to fail.
	 *
	 * @return HttpRequestFactory
	 */
	private function makeMockHttpRequestFactory( $request = null ) {
		$options = new ServiceOptions( HttpRequestFactory::CONSTRUCTOR_OPTIONS, [
			'HTTPTimeout' => 1,
			'HTTPConnectTimeout' => 1,
			'HTTPMaxTimeout' => 1,
			'HTTPMaxConnectTimeout' => 1,
		] );

		/** @var HttpRequestFactory|MockObject $mockHttpRequestFactory */
		$mockHttpRequestFactory = $this->getMockBuilder( HttpRequestFactory::class )
			->setConstructorArgs( [ $options, new NullLogger() ] )
			->onlyMethods( [ 'create', 'createMultiClient' ] )
			->getMock();

		if ( $request instanceof MultiHttpClient ) {
			$mockHttpRequestFactory->method( 'createMultiClient' )
				->willReturn( $request );
		} else {
			$mockHttpRequestFactory->method( 'createMultiClient' )
				->willReturnCallback( [ TestCase::class, 'fail' ] );
		}

		if ( $request === null ) {
			$mockHttpRequestFactory->method( 'create' )
				->willReturnCallback( [ TestCase::class, 'fail' ] );
		} elseif ( $request instanceof MultiHttpClient ) {
			$mockHttpRequestFactory->method( 'create' )
				->willReturnCallback( [ TestCase::class, 'fail' ] );
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
	 * Constructs a fake MWHTTPRequest. The request also represents the desired response.
	 *
	 * @note Not all methods on MWHTTPRequest are mocked, calling other methods will
	 *       cause the test to fail.
	 *
	 * @param string $body The response body.
	 * @param int $statusCode The response status code. Use 0 to indicate an internal error.
	 * @param string[] $headers Any response headers.
	 *
	 * @return MWHttpRequest
	 */
	private function makeFakeHttpRequest( $body = 'Lorem Ipsum', $statusCode = 200, $headers = [] ) {
		$mockHttpRequest = $this->createNoOpMock(
			MWHttpRequest::class,
			[ 'execute', 'setCallback', 'isRedirect', 'getFinalUrl',
				'getResponseHeaders', 'getResponseHeader', 'setHeader',
				'getStatus', 'getContent'
			]
		);

		$mockHttpRequest->method( 'isRedirect' )->willReturn(
			$statusCode >= 300 && $statusCode < 400
		);

		$mockHttpRequest->method( 'getFinalUrl' )->willReturn( $headers[ 'Location' ] ?? '' );

		$mockHttpRequest->method( 'getResponseHeaders' )->willReturn( $headers );
		$mockHttpRequest->method( 'getResponseHeader' )->willReturnCallback(
			function ( $name ) use ( $headers ) {
				return $headers[$name] ?? null;
			}
		);

		$dataCallback = null;
		$mockHttpRequest->method( 'setCallback' )->willReturnCallback(
			function ( $callback ) use ( &$dataCallback ) {
				$dataCallback = $callback;
			}
		);

		$status = Status::newGood( $statusCode );

		if ( $statusCode === 0 ) {
			$status->fatal( 'http-internal-error' );
		}

		$mockHttpRequest->method( 'getContent' )->willReturn( $body );
		$mockHttpRequest->method( 'getStatus' )->willReturn( $statusCode );

		$mockHttpRequest->method( 'execute' )->willReturnCallback(
			function () use ( &$dataCallback, $body, $status ) {
				if ( $dataCallback ) {
					$dataCallback( $this, $body );
				}
				return $status;
			}
		);

		return $mockHttpRequest;
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
			function ( array $req, array $opts = [] ) use ( $mockHttpRequestMulti ) {
				return $mockHttpRequestMulti->runMulti( [ $req ], $opts )[0]['response'];
			}
		);

		$mockHttpRequestMulti->method( 'runMulti' )->willReturnCallback(
			function ( array $reqs, array $opts = [] ) use ( $responses ) {
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

}
