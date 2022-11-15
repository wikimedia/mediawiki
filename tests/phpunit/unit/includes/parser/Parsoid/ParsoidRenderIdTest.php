<?php

namespace MediaWiki\Tests\Unit\Parser\Parsoid;

use MediaWiki\Parser\Parsoid\ParsoidRenderID;
use MediaWikiUnitTestCase;

class ParsoidRenderIdTest extends MediaWikiUnitTestCase {

	/**
	 * @covers \MediaWiki\Parser\Parsoid\ParsoidRenderID
	 */
	public function testConstruction() {
		$renderID = new ParsoidRenderID( 1, '123-abc' );
		$this->assertSame( 1, $renderID->getRevisionID() );
		$this->assertEquals( '123-abc', $renderID->getUniqueID() );
		$this->assertEquals( '1/123-abc', $renderID->__toString() );
	}

	/**
	 * @covers \MediaWiki\Parser\Parsoid\ParsoidRenderID::newFromKey
	 */
	public function testRoundTrip() {
		$renderID = new ParsoidRenderID( 1, '123-abc' );
		$stringRenderID = $renderID->__toString();
		$backToRenderID = $renderID::newFromKey( $stringRenderID );
		$this->assertSame( $stringRenderID, $backToRenderID->__toString() );
	}

	/**
	 * @dataProvider provideETags
	 *
	 * @param string $eTag
	 * @param ParsoidRenderID $renderId
	 *
	 * @covers \MediaWiki\Parser\Parsoid\ParsoidRenderID::newFromETag
	 */
	public function testNewFromETag( $eTag, $renderId ) {
		$actual = ParsoidRenderID::newFromETag( $eTag );
		$this->assertSame( $renderId->getKey(), $actual->getKey() );
	}

	public function provideETags() {
		yield [ '"1/abc/stash"', new ParsoidRenderID( 1, 'abc' ) ];
		yield [ '"1/abc"', new ParsoidRenderID( 1, 'abc' ) ];
		yield [ '"1/abc/stash/stash"', new ParsoidRenderID( 1, 'abc' ) ];
		yield [ 'W/"1/abc"', new ParsoidRenderID( 1, 'abc' ) ];
	}

	/**
	 * @dataProvider provideBadETags
	 *
	 * @param string $eTag
	 *
	 * @covers \MediaWiki\Parser\Parsoid\ParsoidRenderID::newFromETag
	 */
	public function testNewFromBadETag( $eTag ) {
		// make sure it doesn't explode
		$this->assertNull( ParsoidRenderID::newFromETag( $eTag ) );
	}

	public function provideBadETags() {
		yield [ '' ];
		yield [ '0' ];
		yield [ '""' ];
		yield [ '"/"' ];
		yield [ '"x/y"' ];
		yield [ '"1/foo"XXX' ];
		yield [ 'XXX"1/foo"' ];
	}
}
