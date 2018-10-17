<?php

/**
 * Test class for SpecialRedirectExternal class.
 *
 * @license GPL-2.0-or-later
 */
class SpecialRedirectExternalTest extends MediaWikiTestCase {

	/**
	 * @dataProvider provideDispatch
	 * @covers SpecialRedirectExternal::dispatch
	 * @covers SpecialRedirectExternal
	 * @param $url
	 * @param $expectedStatus
	 */
	public function testDispatch( $url, $expectedStatus ) {
		$page = new SpecialRedirectExternal();
		$this->assertEquals( $expectedStatus, $page->dispatch( $url )->isGood() );
	}

	/**
	 * @throws HttpError
	 * @expectedException HttpError
	 * @expectedExceptionMessage asdf is not a valid URL
	 * @covers SpecialRedirectExternal::execute
	 */
	public function testExecuteInvalidUrl() {
		$page = new SpecialRedirectExternal();
		$page->execute( 'asdf' );
	}

	/**
	 * @throws HttpError
	 * @covers SpecialRedirectExternal::execute
	 */
	public function testValidUrl() {
		$page = new SpecialRedirectExternal();
		$this->assertTrue( $page->execute( 'https://www.mediawiki.org' ) );
	}

	public static function provideDispatch() {
		return [
			[ 'asdf', false ],
			[ null, false ],
			[ 'https://www.mediawiki.org?test=1', true ],
		];
	}
}
