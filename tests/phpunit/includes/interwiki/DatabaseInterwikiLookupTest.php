<?php
/**
 * @covers MediaWiki\Interwiki\DatabaseInterwikiLookup
 *
 * @group MediaWiki
 * @group Database
 */
class DatabaseInterwikiLookupTest extends MediaWikiTestCase {

	private function populateDB( $iwrows ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'interwiki', '*', __METHOD__ );
		$dbw->insert( 'interwiki', array_values( $iwrows ), __METHOD__ );
		$this->tablesUsed[] = 'interwiki';
	}

	public function test() {
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
		$lookup = new \MediaWiki\Interwiki\DatabaseInterwikiLookup(
			Language::factory( 'en' ),
			WANObjectCache::newEmpty(),
			60*60,
			false,
			3,
			'en'
		);

		$this->assertEquals(
			[ $dewiki, $zzwiki ],
			$lookup->getAllPrefixes(),
			'getAllPrefixes()'
		);
		$this->assertEquals(
			[ $dewiki ],
			$lookup->getAllPrefixes( true ),
			'getAllPrefixes()'
		);
		$this->assertEquals(
			[ $zzwiki ],
			$lookup->getAllPrefixes( false ),
			'getAllPrefixes()'
		);

		$this->assertTrue( $lookup->isValidInterwiki( 'de' ), 'known prefix is valid' );
		$this->assertTrue( $lookup->isValidInterwiki( 'DE' ), 'known prefix is valid' );
		$this->assertFalse( $lookup->isValidInterwiki( 'xyz' ), 'unknown prefix is valid' );

		$this->assertNull( $lookup->fetch( null ), 'no prefix' );
		$this->assertFalse( $lookup->fetch( 'xyz' ), 'unknown prefix' );

		$interwiki = $lookup->fetch( 'de' );
		$this->assertInstanceOf( 'Interwiki', $interwiki );
		$this->assertSame( $interwiki, $lookup->fetch( 'de' ), 'in-process caching' );
		$this->assertSame( $interwiki, $lookup->fetch( 'DE' ), 'lowercase is applied' );

		$this->assertSame( 'http://de.wikipedia.org/wiki/', $interwiki->getURL(), 'getURL' );
		$this->assertSame( 'http://de.wikipedia.org/w/api.php', $interwiki->getAPI(), 'getAPI' );
		$this->assertSame( 'dewiki', $interwiki->getWikiID(), 'getWikiID' );
		$this->assertSame( true, $interwiki->isLocal(), 'isLocal' );
		$this->assertSame( false, $interwiki->isTranscludable(), 'isTranscludable' );

		$lookup->invalidateCache( 'de' );
		$this->assertNotSame( $interwiki, $lookup->fetch( 'de' ), 'invalidate cache' );
	}
}
