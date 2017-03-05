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
		$this->assertInstanceOf( 'Interwiki', $interwiki );
		$this->assertSame( $interwiki, $interwikiLookup->fetch( 'de' ), 'in-process caching' );

		$this->assertSame( 'http://de.wikipedia.org/wiki/', $interwiki->getURL(), 'getURL' );
		$this->assertSame( 'http://de.wikipedia.org/w/api.php', $interwiki->getAPI(), 'getAPI' );
		$this->assertSame( 'dewiki', $interwiki->getWikiID(), 'getWikiID' );
		$this->assertSame( true, $interwiki->isLocal(), 'isLocal' );
		$this->assertSame( false, $interwiki->isTranscludable(), 'isTranscludable' );

		Interwiki::invalidateCache( 'de' );
		$this->assertNotSame( $interwiki, $interwikiLookup->fetch( 'de' ), 'invalidate cache' );
	}

	/**
	 * @param string $thisSite
	 * @param string[] $local
	 * @param string[] $global
	 *
	 * @return string[]
	 */
	private function populateHash( $thisSite, $local, $global ) {
		$hash = [];
		$hash[ '__sites:' . wfWikiID() ] = $thisSite;

		$globals = [];
		$locals = [];

		foreach ( $local as $row ) {
			$prefix = $row['iw_prefix'];
			$data = $row['iw_local'] . ' ' . $row['iw_url'];
			$locals[] = $prefix;
			$hash[ "_{$thisSite}:{$prefix}" ] = $data;
		}

		foreach ( $global as $row ) {
			$prefix = $row['iw_prefix'];
			$data = $row['iw_local'] . ' ' . $row['iw_url'];
			$globals[] = $prefix;
			$hash[ "__global:{$prefix}" ] = $data;
		}

		$hash[ '__list:__global' ] = implode( ' ', $globals );
		$hash[ '__list:_' . $thisSite ] = implode( ' ', $locals );

		return $hash;
	}

	private function populateCDB( $thisSite, $local, $global ) {
		$cdbFile = tempnam( wfTempDir(), 'MW-ClassicInterwikiLookupTest-' ) . '.cdb';
		$cdb = CdbWriter::open( $cdbFile );

		$hash = $this->populateHash( $thisSite, $local, $global );

		foreach ( $hash as $key => $value ) {
			$cdb->set( $key, $value );
		}

		$cdb->close();
		return $cdbFile;
	}

	public function testCDBStorage() {
		// NOTE: CDB setup is expensive, so we only do
		//  it once and run all the tests in one go.

		$dewiki = [
			'iw_prefix' => 'de',
			'iw_url' => 'http://de.wikipedia.org/wiki/',
			'iw_local' => 1
		];

		$zzwiki = [
			'iw_prefix' => 'zz',
			'iw_url' => 'http://zzwiki.org/wiki/',
			'iw_local' => 0
		];

		$cdbFile = $this->populateCDB(
			'en',
			[ $dewiki ],
			[ $zzwiki ]
		);

		$this->setWgInterwikiCache( $cdbFile );

		$interwikiLookup = MediaWikiServices::getInstance()->getInterwikiLookup();
		$this->assertEquals(
			[ $dewiki, $zzwiki ],
			$interwikiLookup->getAllPrefixes(),
			'getAllPrefixes()'
		);

		$this->assertTrue( $interwikiLookup->isValidInterwiki( 'de' ), 'known prefix is valid' );
		$this->assertTrue( $interwikiLookup->isValidInterwiki( 'zz' ), 'known prefix is valid' );

		$interwiki = $interwikiLookup->fetch( 'de' );
		$this->assertInstanceOf( 'Interwiki', $interwiki );

		$this->assertSame( 'http://de.wikipedia.org/wiki/', $interwiki->getURL(), 'getURL' );
		$this->assertSame( true, $interwiki->isLocal(), 'isLocal' );

		$interwiki = $interwikiLookup->fetch( 'zz' );
		$this->assertInstanceOf( 'Interwiki', $interwiki );

		$this->assertSame( 'http://zzwiki.org/wiki/', $interwiki->getURL(), 'getURL' );
		$this->assertSame( false, $interwiki->isLocal(), 'isLocal' );

		// cleanup temp file
		unlink( $cdbFile );
	}

	public function testArrayStorage() {
		$dewiki = [
			'iw_prefix' => 'de',
			'iw_url' => 'http://de.wikipedia.org/wiki/',
			'iw_local' => 1
		];

		$zzwiki = [
			'iw_prefix' => 'zz',
			'iw_url' => 'http://zzwiki.org/wiki/',
			'iw_local' => 0
		];

		$cdbData = $this->populateHash(
			'en',
			[ $dewiki ],
			[ $zzwiki ]
		);

		$this->setWgInterwikiCache( $cdbData );

		$interwikiLookup = MediaWikiServices::getInstance()->getInterwikiLookup();
		$this->assertEquals(
			[ $dewiki, $zzwiki ],
			$interwikiLookup->getAllPrefixes(),
			'getAllPrefixes()'
		);

		$this->assertTrue( $interwikiLookup->isValidInterwiki( 'de' ), 'known prefix is valid' );
		$this->assertTrue( $interwikiLookup->isValidInterwiki( 'zz' ), 'known prefix is valid' );

		$interwiki = $interwikiLookup->fetch( 'de' );
		$this->assertInstanceOf( 'Interwiki', $interwiki );

		$this->assertSame( 'http://de.wikipedia.org/wiki/', $interwiki->getURL(), 'getURL' );
		$this->assertSame( true, $interwiki->isLocal(), 'isLocal' );

		$interwiki = $interwikiLookup->fetch( 'zz' );
		$this->assertInstanceOf( 'Interwiki', $interwiki );

		$this->assertSame( 'http://zzwiki.org/wiki/', $interwiki->getURL(), 'getURL' );
		$this->assertSame( false, $interwiki->isLocal(), 'isLocal' );
	}

}
