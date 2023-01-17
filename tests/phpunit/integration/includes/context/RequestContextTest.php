<?php

namespace MediaWiki\Tests\Integration\Context;

use MediaWikiIntegrationTestCase;
use RequestContext;

class RequestContextTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers \RequestContext::sanitizeLangCode
	 *
	 * @dataProvider provideSanitizeLangCode
	 */
	public function testSanitizeLangCode(
		?string $input, string $expected
	): void {
		$this->assertSame(
			$expected,
			RequestContext::sanitizeLangCode( $input )
		);
	}

	public function provideSanitizeLangCode() {
		global $wgLanguageCode;

		yield 'Null' => [ null, $wgLanguageCode ];
		yield 'Blank' => [ '', $wgLanguageCode ];

		yield 'Current' => [ $wgLanguageCode, $wgLanguageCode ];

		yield 'Non-English' => [ 'fr', 'fr' ];
		yield 'Documentation falls back to default' => [ 'qqq', $wgLanguageCode ];

		yield 'Sub-codes' => [ 'fr-fr', 'fr-fr' ];

		yield 'Lower-casing' => [ 'en-GB', 'en-gb' ];

		yield 'Valid codes unknown to MW' => [ 'zzz', 'zzz' ];
		yield 'Valid sub-codes unknown to MW' => [ 'en-IN', 'en-in' ];
		yield 'Extended codes' => [ 'en-US-aave', 'en-us-aave' ];

		yield 'Invalid code' => [ 'z!!z', 'z!!z' ];

		yield 'Attempted XSS code' => [ 'a&#0', $wgLanguageCode ];
	}
}
