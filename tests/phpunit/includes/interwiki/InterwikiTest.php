<?php
/**
 * @covers Interwiki
 *
 * @group MediaWiki
 * @group Database
 */
class InterwikiTest extends MediaWikiTestCase {

	public function testConstructor() {
		$interwiki = new Interwiki(
			'xyz',
			'http://xyz.acme.test/wiki/$1',
			'http://xyz.acme.test/w/api.php',
			'xyzwiki',
			1,
			0
		);

		$this->setContentLang( 'qqx' );

		$this->assertSame( '(interwiki-name-xyz)', $interwiki->getName() );
		$this->assertSame( '(interwiki-desc-xyz)', $interwiki->getDescription() );
		$this->assertSame( 'http://xyz.acme.test/w/api.php', $interwiki->getAPI() );
		$this->assertSame( 'http://xyz.acme.test/wiki/$1', $interwiki->getURL() );
		$this->assertSame( 'xyzwiki', $interwiki->getWikiID() );
		$this->assertTrue( $interwiki->isLocal() );
		$this->assertFalse( $interwiki->isTranscludable() );
	}

	public function testGetUrl() {
		$interwiki = new Interwiki(
			'xyz',
			'http://xyz.acme.test/wiki/$1'
		);

		$this->assertSame( 'http://xyz.acme.test/wiki/$1', $interwiki->getURL() );
		$this->assertSame( 'http://xyz.acme.test/wiki/Foo%26Bar', $interwiki->getURL( 'Foo&Bar' ) );
	}

}
