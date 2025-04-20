<?php

namespace MediaWiki\Tests\Rest;

use MediaWiki\Rest\ConditionalHeaderUtil;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\Response;
use MediaWikiUnitTestCase;
use PHPUnit\Framework\Assert;

/**
 * @covers \MediaWiki\Rest\ConditionalHeaderUtil
 */
class ConditionalHeaderUtilTest extends MediaWikiUnitTestCase {
	private static function makeFail( string $fn ) {
		return static function () use ( $fn ) {
			Assert::fail( "$fn was not expected to be called" );
		};
	}

	public static function provider() {
		return [
			'nothing' => [
				'GET',
				null,
				null,
				null,
				[],
				null,
				null,
				[]
			],
			'inm true' => [
				'GET',
				'"a"',
				null,
				null,
				[ 'If-None-Match' => '"b"' ],
				null,
				null,
				[ 'ETag' => [ '"a"' ] ]
			],
			'inm false' => [
				'GET',
				'"a"',
				null,
				null,
				[ 'If-None-Match' => '"a"' ],
				null,
				304,
				[ 'ETag' => [ '"a"' ] ]
			],
			'ims true' => [
				'GET',
				null,
				'Mon, 14 Oct 2019 00:00:01 GMT',
				null,
				[ 'If-Modified-Since' => 'Mon, 14 Oct 2019 00:00:00 GMT' ],
				null,
				null,
				[ 'Last-Modified' => [ 'Mon, 14 Oct 2019 00:00:01 GMT' ] ]
			],
			'ims false' => [
				'GET',
				null,
				'Mon, 14 Oct 2019 00:00:00 GMT',
				null,
				[ 'If-Modified-Since' => 'Mon, 14 Oct 2019 00:00:00 GMT' ],
				null,
				304,
				[ 'Last-Modified' => [ 'Mon, 14 Oct 2019 00:00:00 GMT' ] ]
			],
			'im true' => [
				'GET',
				'"a"',
				null,
				null,
				[ 'If-Match' => '"a"' ],
				null,
				null,
				[ 'ETag' => [ '"a"' ] ]
			],
			'im false' => [
				'GET',
				'"a"',
				null,
				null,
				[ 'If-Match' => '"b"' ],
				null,
				412,
				[]
			],
			'ius true' => [
				'GET',
				null,
				'Mon, 14 Oct 2019 00:00:00 GMT',
				null,
				[ 'If-Unmodified-Since' => 'Mon, 14 Oct 2019 00:00:00 GMT' ],
				null,
				null,
				[ 'Last-Modified' => [ 'Mon, 14 Oct 2019 00:00:00 GMT' ] ]
			],
			'ius false' => [
				'GET',
				null,
				'Mon, 14 Oct 2019 00:00:01 GMT',
				null,
				[ 'If-Unmodified-Since' => 'Mon, 14 Oct 2019 00:00:00 GMT' ],
				null,
				412,
				[]
			],
			'im true, ius false' => [
				'GET',
				'"a"',
				'Mon, 14 Oct 2019 00:00:01 GMT',
				null,
				[
					'If-Match' => '"a"',
					'If-Unmodified-Since' => 'Mon, 14 Oct 2019 00:00:00 GMT'
				],
				null,
				null,
				[
					'Last-Modified' => [ 'Mon, 14 Oct 2019 00:00:01 GMT' ],
					'ETag' => [ '"a"' ]
				]
			],
			'inm true, ims false' => [
				'GET',
				'"a"',
				'Mon, 14 Oct 2019 00:00:00 GMT',
				null,
				[
					'If-None-Match' => '"b"',
					'If-Modified-Since' => 'Mon, 14 Oct 2019 00:00:00 GMT'
				],
				null,
				null,
				[
					'Last-Modified' => [ 'Mon, 14 Oct 2019 00:00:00 GMT' ],
					'ETag' => [ '"a"' ]
				]
			],
			'im true, inm false' => [
				'GET',
				'"a"',
				null,
				null,
				[
					'If-Match' => '"a"',
					'If-None-Match' => '"a"'
				],
				null,
				304,
				[ 'ETag' => [ '"a"' ] ]
			],
			'ius true, inm false' => [
				'GET',
				'"a"',
				'Mon, 14 Oct 2019 00:00:00 GMT',
				null,
				[
					'If-Unmodified-Since' => 'Mon, 14 Oct 2019 00:00:00 GMT',
					'If-None-Match' => '"a"'
				],
				null,
				304,
				[
					'Last-Modified' => [ 'Mon, 14 Oct 2019 00:00:00 GMT' ],
					'ETag' => [ '"a"' ]
				]
			],
			'im star true' => [
				'GET',
				'"a"',
				null,
				true,
				[ 'If-Match' => '*' ],
				null,
				null,
				[ 'ETag' => [ '"a"' ] ]
			],
			'im star true, no etag' => [
				'GET',
				null,
				null,
				true,
				[ 'If-Match' => '*' ],
				null,
				null,
				[]
			],
			'im star false' => [
				'GET',
				null,
				null,
				false,
				[ 'If-Match' => '*' ],
				null,
				412,
				[]
			],
			'inm false post' => [
				'POST',
				'"a"',
				null,
				null,
				[ 'If-None-Match' => '"a"' ],
				null,
				412,
				[]
			],
			'im multiple true' => [
				'GET',
				'"a"',
				null,
				null,
				[ 'If-Match' => '"b", "a"' ],
				null,
				null,
				[ 'ETag' => [ '"a"' ] ]
			],
			'im multiple false' => [
				'GET',
				'"a"',
				null,
				null,
				[ 'If-Match' => '"b", "c"' ],
				null,
				412,
				[]
			],
			// A strong ETag returned by the server may have been "weakened" by
			// a proxy or middleware, e.g. when applying compression and setting
			// content-encoding (rather than transfer-encoding). We want to still accept
			// such ETags, even though that's against the HTTP spec (T238849).
			// See ConditionalHeaderUtil::setVarnishETagHack.
			'If-Match weak vs strong' => [
				'GET',
				'"a"',
				null,
				null,
				[ 'If-Match' => 'W/"a"' ],
				null,
				null,
				[ 'ETag' => [ '"a"' ] ]
			],
			'If-Match strong vs weak' => [
				'GET',
				'W/"a"',
				null,
				null,
				[ 'If-Match' => '"a"' ],
				null,
				412,
				[]
			],
			'If-Match weak vs weak' => [
				'GET',
				'W/"a"',
				null,
				null,
				[ 'If-Match' => 'W/"a"' ],
				null,
				412,
				[]
			],
			'ims with resource unknown' => [
				'GET',
				null,
				null,
				false,
				[ 'If-Modified-Since' => 'Mon, 14 Oct 2019 00:00:00 GMT' ],
				null,
				null,
				[]
			],
			'ius with resource unknown' => [
				'GET',
				self::makeFail( 'getETag' ),
				null,
				false,
				[ 'If-Unmodified-Since' => 'Mon, 14 Oct 2019 00:00:00 GMT' ],
				null,
				412,
				[]
			],
			'If-None-Match wildcard GET request, resource has representation' => [
				'GET',
				null,
				null,
				true,
				[ 'If-None-Match' => '*' ],
				null,
				304,
				[]
			],
			'If-None-Match wildcard non-GET request, resource has representation' => [
				'POST',
				self::makeFail( 'getETag' ),
				self::makeFail( 'getLastModified' ),
				true,
				[ 'If-None-Match' => '*' ],
				null,
				412,
				[]
			],
			'inm true, 4xx response' => [
				'GET',
				'"a"',
				null,
				null,
				[ 'If-None-Match' => '"b"' ],
				404,
				null,
				[]
			],
			'inm true, 301 response' => [
				'GET',
				'"a"',
				null,
				null,
				[ 'If-None-Match' => '"b"' ],
				301,
				null,
				[]
			],
			'inm true, 307 response' => [
				'GET',
				'"a"',
				null,
				null,
				[ 'If-None-Match' => '"b"' ],
				307,
				null,
				[]
			],
		];
	}

	/** @dataProvider provider */
	public function testConditionalHeaderUtil( $method, $eTag, $lastModified, $hasRepresentation,
		$requestHeaders, $statusOverride, $expectedStatus, $expectedResponseHeaders
	) {
		$util = new ConditionalHeaderUtil;
		$util->setValidators( $eTag, $lastModified, $hasRepresentation );
		$request = new RequestData( [
			'method' => $method,
			'headers' => $requestHeaders
		] );
		$status = $util->checkPreconditions( $request );
		$this->assertSame( $expectedStatus, $status );

		if ( $statusOverride !== null ) {
			$status = $statusOverride;
		}

		$response = new Response;

		if ( $status ) {
			$response->setStatus( $status );

			$util->applyResponseHeaders( $response );
			$this->assertSame( $expectedResponseHeaders, $response->getHeaders() );
		}
	}
}
