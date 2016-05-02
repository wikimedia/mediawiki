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
		global $wgThisIsJustAGlobalServiceStubTest;

		$wgThisIsJustAGlobalServiceStubTest = new GlobalServiceStub(
			'wgThisIsJustAGlobalServiceStubTest',
			'ContentLanguage'
		);

		StubObject::unstub( $wgThisIsJustAGlobalServiceStubTest );

		$expected = MediaWikiServices::getInstance()->getService( 'ContentLanguage' );
		$this->assertSame( $expected, $wgThisIsJustAGlobalServiceStubTest );
	}

}
