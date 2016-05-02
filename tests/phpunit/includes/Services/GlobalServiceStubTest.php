<?php
use MediaWiki\MediaWikiServices;
use MediaWiki\Services\GlobalServiceStub;
use MediaWiki\Services\ServiceContainer;

/**
 * @covers MediaWiki\Services\GlobalServiceStub
 *
 * @group MediaWiki
 * @group MediaWikiServices
 */
class GlobalServiceStubTest extends PHPUnit_Framework_TestCase {

	public function testUnstub() {
		global $wg_test_GlobalServiceStubTest;

		$wg_test_GlobalServiceStubTest = new GlobalServiceStub(
			'wg_test_GlobalServiceStubTest',
			'ContentLanguage'
		);

		StubObject::unstub( $wg_test_GlobalServiceStubTest );

		$expected = MediaWikiServices::getInstance()->getService( 'ContentLanguage' );
		$this->assertSame( $expected, $wg_test_GlobalServiceStubTest );
	}

}
