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
		global $test_GlobalServiceStubTest;

		$test_GlobalServiceStubTest = new GlobalServiceStub( 'test_GlobalServiceStubTest', 'ContentLanguage' );
		StubObject::unstub( $test_GlobalServiceStubTest );

		$expected = MediaWikiServices::getInstance()->getService( 'ContentLanguage' );
		$this->assertSame( $expected, $test_GlobalServiceStubTest );
	}

}
