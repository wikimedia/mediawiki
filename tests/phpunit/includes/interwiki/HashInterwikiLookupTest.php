<?php
/**
 * @covers MediaWiki\Interwiki\HashInterwikiLookup
 *
 * @group MediaWiki
 */
class HashInterwikiLookupTest extends MediaWikiTestCase {
	/**
	 * @param string $thisSite
	 * @param string[] $local
	 * @param string[] $global
	 *
	 * @return string[]
	 */
	public static function populateHash( $thisSite, $local, $global ) {
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

		$hash = self::populateHash(
			'en',
			[ $dewiki ],
			[ $zzwiki ]
		);
		$lookup = new \MediaWiki\Interwiki\HashInterwikiLookup(
			Language::factory( 'en' ),
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
	}

}
