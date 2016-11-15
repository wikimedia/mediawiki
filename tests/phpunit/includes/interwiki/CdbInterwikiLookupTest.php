<?php
/**
 * @covers MediaWiki\Interwiki\CdbInterwikiLookup
 *
 * @group MediaWiki
 */
class CdbInterwikiLookupTest extends MediaWikiTestCase {
	private function populateCDB( $thisSite, $local, $global ) {
		$cdbFile = tempnam( wfTempDir(), 'MW-CdbInterwikiLookupTest-' ) . '.cdb';
		$cdb = \Cdb\Writer::open( $cdbFile );

		$cdb->set( '__sites:' . wfWikiID(), $thisSite );

		$globals = [];
		$locals = [];

		foreach ( $local as $row ) {
			$prefix = $row['iw_prefix'];
			$data = $row['iw_local'] . ' ' . $row['iw_url'];
			$locals[] = $prefix;
			$cdb->set( "_{$thisSite}:{$prefix}", $data );
		}

		foreach ( $global as $row ) {
			$prefix = $row['iw_prefix'];
			$data = $row['iw_local'] . ' ' . $row['iw_url'];
			$globals[] = $prefix;
			$cdb->set( "__global:{$prefix}", $data );
		}

		$cdb->set( '__list:__global', implode( ' ', $globals ) );
		$cdb->set( '__list:_' . $thisSite, implode( ' ', $locals ) );

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
		$lookup = new \MediaWiki\Interwiki\CdbInterwikiLookup(
			Language::factory( 'en' ),
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
		$this->assertTrue( $lookup->isValidInterwiki( 'DE' ), 'known prefix is valid' );
		$this->assertTrue( $lookup->isValidInterwiki( 'zz' ), 'known prefix is valid' );

		$interwiki = $lookup->fetch( 'de' );
		$this->assertInstanceOf( 'Interwiki', $interwiki );

		$this->assertSame( 'http://de.wikipedia.org/wiki/', $interwiki->getURL(), 'getURL' );
		$this->assertSame( true, $interwiki->isLocal(), 'isLocal' );

		$interwikiLc = $lookup->fetch( 'DE' );
		$this->assertSame( $interwiki, $interwikiLc, 'lowercase is applied' );

		$interwiki = $lookup->fetch( 'zz' );
		$this->assertInstanceOf( 'Interwiki', $interwiki );

		$this->assertSame( 'http://zzwiki.org/wiki/', $interwiki->getURL(), 'getURL' );
		$this->assertSame( false, $interwiki->isLocal(), 'isLocal' );

		// cleanup temp file
		unlink( $cdbFile );
	}
}
