<?php

namespace MediaWiki\Tests\Rest;

use MediaWiki\Rest\ConditionalHeaderUtil;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\Response;

/**
 * @covers \MediaWiki\Rest\ConditionalHeaderUtil
 */
class ConditionalHeaderUtilTest extends \MediaWikiUnitTestCase {
	public static function provider() {
		return [
			'nothing' => [
				'GET',
				null,
				null,
				null,
				[],
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
				[ 'ETag' => [ '"a"' ] ]
			],
			'inm false' => [
				'GET',
				'"a"',
				null,
				null,
				[ 'If-None-Match' => '"a"' ],
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
				[ 'Last-Modified' => [ 'Mon, 14 Oct 2019 00:00:01 GMT' ] ]
			],
			'ims false' => [
				'GET',
				null,
				'Mon, 14 Oct 2019 00:00:00 GMT',
				null,
				[ 'If-Modified-Since' => 'Mon, 14 Oct 2019 00:00:00 GMT' ],
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
				[ 'ETag' => [ '"a"' ] ]
			],
			'im false' => [
				'GET',
				'"a"',
				null,
				null,
				[ 'If-Match' => '"b"' ],
				412,
				[ 'ETag' => [ '"a"' ] ]
			],
			'ius true' => [
				'GET',
				null,
				'Mon, 14 Oct 2019 00:00:00 GMT',
				null,
				[ 'If-Unmodified-Since' => 'Mon, 14 Oct 2019 00:00:00 GMT' ],
				null,
				[ 'Last-Modified' => [ 'Mon, 14 Oct 2019 00:00:00 GMT' ] ]
			],
			'ius false' => [
				'GET',
				null,
				'Mon, 14 Oct 2019 00:00:01 GMT',
				null,
				[ 'If-Unmodified-Since' => 'Mon, 14 Oct 2019 00:00:00 GMT' ],
				412,
				[ 'Last-Modified' => [ 'Mon, 14 Oct 2019 00:00:01 GMT' ] ]
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
				[ 'ETag' => [ '"a"' ] ]
			],
			'im star false' => [
				'GET',
				null,
				null,
				false,
				[ 'If-Match' => '*' ],
				412,
				[]
			],
			'inm false post' => [
				'POST',
				'"a"',
				null,
				null,
				[ 'If-None-Match' => '"a"' ],
				412,
				[ 'ETag' => [ '"a"' ] ]
			],
			'im multiple true' => [
				'GET',
				'"a"',
				null,
				null,
				[ 'If-Match' => '"b", "a"' ],
				null,
				[ 'ETag' => [ '"a"' ] ]
			],
			'im multiple false' => [
				'GET',
				'"a"',
				null,
				null,
				[ 'If-Match' => '"b", "c"' ],
				412,
				[ 'ETag' => [ '"a"' ] ]
			],
			'im weak' => [
				'GET',
				'"a"',
				null,
				null,
				[ 'If-Match' => 'W/"a"' ],
				412,
				[ 'ETag' => [ '"a"' ] ]
			],
			'ims with resource unknown' => [
				'GET',
				null,
				null,
				null,
				[ 'If-Modified-Since' => 'Mon, 14 Oct 2019 00:00:00 GMT' ],
				null,
				[]
			],
			'ius with resource unknown' => [
				'GET',
				null,
				null,
				null,
				[ 'If-Unmodified-Since' => 'Mon, 14 Oct 2019 00:00:00 GMT' ],
				412,
				[]
			],
		];
	}

	/** @dataProvider provider */
	public function testConditionalHeaderUtil( $method, $eTag, $lastModified, $hasRepresentation,
		$requestHeaders, $expectedStatus, $expectedResponseHeaders
	) {
		$util = new ConditionalHeaderUtil;
		$util->setValidators( $eTag, $lastModified, $hasRepresentation );
		$request = new RequestData( [
			'method' => $method,
			'headers' => $requestHeaders
		] );
		$status = $util->checkPreconditions( $request );
		$this->assertSame( $expectedStatus, $status );

		$response = new Response;
		$util->applyResponseHeaders( $response );
		$this->assertSame( $expectedResponseHeaders, $response->getHeaders() );
	}
}
