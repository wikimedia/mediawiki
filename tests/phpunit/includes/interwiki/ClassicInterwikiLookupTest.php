<?php
/**
 * @covers MediaWiki\Interwiki\ClassicInterwikiLookup
 *
 * @group MediaWiki
 * @group Database
 */
class ClassicInterwikiLookupTest extends MediaWikiTestCase {

	private function populateDB( $iwrows ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'interwiki', '*', __METHOD__ );
		$dbw->insert( 'interwiki', array_values( $iwrows ), __METHOD__ );
		$this->tablesUsed[] = 'interwiki';
	}

	public function testDatabaseStorage() {
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
		$lookup = new \MediaWiki\Interwiki\ClassicInterwikiLookup(
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
		$this->assertFalse( $lookup->isValidInterwiki( 'xyz' ), 'unknown prefix is valid' );

		$this->assertNull( $lookup->fetch( null ), 'no prefix' );
		$this->assertFalse( $lookup->fetch( 'xyz' ), 'unknown prefix' );

		$interwiki = $lookup->fetch( 'de' );
		$this->assertInstanceOf( 'Interwiki', $interwiki );
		$this->assertSame( $interwiki, $lookup->fetch( 'de' ), 'in-process caching' );

		$this->assertSame( 'http://de.wikipedia.org/wiki/', $interwiki->getURL(), 'getURL' );
		$this->assertSame( 'http://de.wikipedia.org/w/api.php', $interwiki->getAPI(), 'getAPI' );
		$this->assertSame( 'dewiki', $interwiki->getWikiID(), 'getWikiID' );
		$this->assertSame( true, $interwiki->isLocal(), 'isLocal' );
		$this->assertSame( false, $interwiki->isTranscludable(), 'isTranscludable' );

		$lookup->invalidateCache( 'de' );
		$this->assertNotSame( $interwiki, $lookup->fetch( 'de' ), 'invalidate cache' );
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
		$cdb = \Cdb\Writer::open( $cdbFile );

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
		$lookup = new \MediaWiki\Interwiki\ClassicInterwikiLookup(
			Language::factory( 'en' ),
			WANObjectCache::newEmpty(),
			60*60,
			$cdbFile,
			3,
			'en'
		);

		$this->assertEquals(
			[ $dewiki, $zzwiki ],
			$lookup->getAllPrefixes(),
			'getAllPrefixes()'
		);

		$this->assertTrue( $lookup->isValidInterwiki( 'de' ), 'known prefix is valid' );
		$this->assertTrue( $lookup->isValidInterwiki( 'zz' ), 'known prefix is valid' );

		$interwiki = $lookup->fetch( 'de' );
		$this->assertInstanceOf( 'Interwiki', $interwiki );

		$this->assertSame( 'http://de.wikipedia.org/wiki/', $interwiki->getURL(), 'getURL' );
		$this->assertSame( true, $interwiki->isLocal(), 'isLocal' );

		$interwiki = $lookup->fetch( 'zz' );
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

		$hash = $this->populateHash(
			'en',
			[ $dewiki ],
			[ $zzwiki ]
		);
		$lookup = new \MediaWiki\Interwiki\ClassicInterwikiLookup(
			Language::factory( 'en' ),
			WANObjectCache::newEmpty(),
			60*60,
			$hash,
			3,
			'en'
		);

		$this->assertEquals(
			[ $dewiki, $zzwiki ],
			$lookup->getAllPrefixes(),
			'getAllPrefixes()'
		);

		$this->assertTrue( $lookup->isValidInterwiki( 'de' ), 'known prefix is valid' );
		$this->assertTrue( $lookup->isValidInterwiki( 'zz' ), 'known prefix is valid' );

		$interwiki = $lookup->fetch( 'de' );
		$this->assertInstanceOf( 'Interwiki', $interwiki );

		$this->assertSame( 'http://de.wikipedia.org/wiki/', $interwiki->getURL(), 'getURL' );
		$this->assertSame( true, $interwiki->isLocal(), 'isLocal' );

		$interwiki = $lookup->fetch( 'zz' );
		$this->assertInstanceOf( 'Interwiki', $interwiki );

		$this->assertSame( 'http://zzwiki.org/wiki/', $interwiki->getURL(), 'getURL' );
		$this->assertSame( false, $interwiki->isLocal(), 'isLocal' );
	}

}
