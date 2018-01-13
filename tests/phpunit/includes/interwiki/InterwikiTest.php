<?php
use MediaWiki\MediaWikiServices;

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

	//// tests for static data access methods below ///////////////////////////////////////////////

	private function populateDB( $iwrows ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'interwiki', '*', __METHOD__ );
		$dbw->insert( 'interwiki', array_values( $iwrows ), __METHOD__ );
		$this->tablesUsed[] = 'interwiki';
	}

	private function setWgInterwikiCache( $interwikiCache ) {
		$this->overrideMwServices();
		MediaWikiServices::getInstance()->resetServiceForTesting( 'InterwikiLookup' );
		$this->setMwGlobals( 'wgInterwikiCache', $interwikiCache );
	}

	public function testDatabaseStorage() {
		$this->markTestSkipped( 'Needs I37b8e8018b3 <https://gerrit.wikimedia.org/r/#/c/270555/>' );

		// NOTE: database setup is expensive, so we only do
		//  it once and run all the tests in one go.
		$dewiki = [
			'iw_prefix' => 'de',
			'iw_url' => 'http://de.wikipedia.org/wiki/',
			'iw_api' => 'http://de.wikipedia.org/w/api.php',
			'iw_wikiid' => 'dewiki',
			'iw_local' => 1,
			'iw_trans' => 0
		];

		$zzwiki = [
			'iw_prefix' => 'zz',
			'iw_url' => 'http://zzwiki.org/wiki/',
			'iw_api' => 'http://zzwiki.org/w/api.php',
			'iw_wikiid' => 'zzwiki',
			'iw_local' => 0,
			'iw_trans' => 0
		];

		$this->populateDB( [ $dewiki, $zzwiki ] );

		$this->setWgInterwikiCache( false );

		$interwikiLookup = MediaWikiServices::getInstance()->getInterwikiLookup();
		$this->assertEquals(
			[ $dewiki, $zzwiki ],
			$interwikiLookup->getAllPrefixes(),
			'getAllPrefixes()'
		);
		$this->assertEquals(
			[ $dewiki ],
			$interwikiLookup->getAllPrefixes( true ),
			'getAllPrefixes()'
		);
		$this->assertEquals(
			[ $zzwiki ],
			$interwikiLookup->getAllPrefixes( false ),
			'getAllPrefixes()'
		);

		$this->assertTrue( $interwikiLookup->isValidInterwiki( 'de' ), 'known prefix is valid' );
		$this->assertFalse( $interwikiLookup->isValidInterwiki( 'xyz' ), 'unknown prefix is valid' );

		$this->assertNull( $interwikiLookup->fetch( null ), 'no prefix' );
		$this->assertFalse( $interwikiLookup->fetch( 'xyz' ), 'unknown prefix' );

		$interwiki = $interwikiLookup->fetch( 'de' );
		$this->assertInstanceOf( Interwiki::class, $interwiki );
		$this->assertSame( $interwiki, $interwikiLookup->fetch( 'de' ), 'in-process caching' );

		$this->assertSame( 'http://de.wikipedia.org/wiki/', $interwiki->getURL(), 'getURL' );
		$this->assertSame( 'http://de.wikipedia.org/w/api.php', $interwiki->getAPI(), 'getAPI' );
		$this->assertSame( 'dewiki', $interwiki->getWikiID(), 'getWikiID' );
		$this->assertSame( true, $interwiki->isLocal(), 'isLocal' );
		$this->assertSame( false, $interwiki->isTranscludable(), 'isTranscludable' );

		Interwiki::invalidateCache( 'de' );
		$this->assertNotSame( $interwiki, $interwikiLookup->fetch( 'de' ), 'invalidate cache' );
	}

}
