<?php

namespace MediaWiki\Tests\Unit\Utils;

use MediaWikiUnitTestCase;
use Wikimedia\Http\HttpHeader;
use Wikimedia\ScopedCallback;

/**
 * Note, tests in these class use status headers and check the status code, because headers_list has no headers in CLI
 *
 * @covers \Wikimedia\Http\HttpHeader
 */
class HttpHeaderTest extends MediaWikiUnitTestCase {
	public function testSendHeader__doesNothingByDefaultInTests() {
		$statusCode = 666;
		HttpHeader::send( "HTTP/1.0 $statusCode Server caught fire" );
		$this->assertNotSame( $statusCode, http_response_code() );
	}

	public function testSendHeader__scopedEnable() {
		$firstCode = 666;

		$scopedEnable = HttpHeader::enableForTest();
		HttpHeader::send( "HTTP/1.0 $firstCode Server caught fire" );
		$this->assertSame( $firstCode, http_response_code() );

		ScopedCallback::consume( $scopedEnable );

		$secondCode = 418;
		HttpHeader::send( "HTTP/1.0 $secondCode I'm a teapot" );
		$this->assertNotSame( $secondCode, http_response_code() );
	}
}
