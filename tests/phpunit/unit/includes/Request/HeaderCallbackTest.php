<?php

use MediaWiki\Request\HeaderCallback;

/**
 * @covers \MediaWiki\Request\HeaderCallback
 */
class HeaderCallbackTest extends MediaWikiUnitTestCase {

	/**
	 * @dataProvider provideSanitizeSetCookie
	 */
	public function testSanitizeSetCookie( $raw, $expectedSanitized ) {
		$this->assertSame( $expectedSanitized, HeaderCallback::sanitizeSetCookie( $raw ) );
	}

	public static function provideSanitizeSetCookie() {
		return [
			[
				[
					'sessionId=38afes7a'
				],
				'sessionId=38afes7a',
			],
			[
				[
					'id=a3fWa; Expires=Wed, 21 Oct 2015 07:28:00 GMT'
				],
				'id=a3fWa; Expires=Wed, 21 Oct 2015 07:28:00 GMT',
			],
			[
				[
					'qwerty=219ffwef9w0f; Domain=somecompany.co.uk'
				],
				'qwerty=219ffwef...; Domain=somecompany.co.uk',
			],
			[
				[
					'sessionId=aaa',
					'sessionId=bbbbbbbbbb',
					'sessionId=ccc',
				],
				"sessionId=aaa\nsessionId=bbbbbbbb...\nsessionId=ccc",
			],
		];
	}

}
