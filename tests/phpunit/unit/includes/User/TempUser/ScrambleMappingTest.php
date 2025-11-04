<?php

namespace MediaWiki\Tests\User\TempUser;

use MediaWiki\User\TempUser\ScrambleMapping;
use MediaWikiUnitTestCase;
use OutOfBoundsException;

/**
 * @covers \MediaWiki\User\TempUser\ScrambleMapping
 */
class ScrambleMappingTest extends MediaWikiUnitTestCase {

	protected function setUp(): void {
		parent::setUp();

		if ( !extension_loaded( 'gmp' ) && !extension_loaded( 'bcmath' ) ) {
			$this->markTestSkipped( 'ScrambleMapping requires the gmp or bcmath PHP extensions' );
		}
	}

	public function testMap() {
		$map = new ScrambleMapping( [] );
		$duplicates = 0;
		// This has been verified up to 1e8 but for CI purposes we will use 200
		$n = 200;
		// Make a bit array for duplicate detection, with enough space for one extra digit
		$bitArray = str_repeat( "\0", $n * 10 / 8 );
		for ( $i = 0; $i < $n; $i++ ) {
			$value = (int)$map->getSerialIdForIndex( $i );
			$minor = $value % 8;
			$major = ( $value - $minor ) / 8;
			$prevBits = ord( $bitArray[$major] );
			$prevStatus = ( $prevBits & ( 1 << $minor ) );
			$duplicates += ( $prevStatus ? 1 : 0 );
			$newBits = $prevBits | ( 1 << $minor );
			$bitArray[$major] = chr( $newBits );
		}
		$this->assertSame( 0, $duplicates, 'duplicate detected' );
	}

	public function testOffsetTooLarge() {
		$map = new ScrambleMapping( [ 'offset' => 10 ] );
		$this->expectException( OutOfBoundsException::class );
		$map->getSerialIdForIndex( 1 );
	}
}
