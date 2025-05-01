<?php

namespace MediaWiki\Tests\Unit\Revision;

use InvalidArgumentException;
use MediaWiki\Content\TextContent;
use MediaWiki\Content\TextContentHandler;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionSlots;
use MediaWiki\Revision\SlotRecord;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Revision\RevisionSlots
 */
class RevisionSlotsTest extends MediaWikiUnitTestCase {

	/**
	 * Creates a subclass that overrides AbstractContent::getContentHandler() and returns a
	 * ContentHandler without the need to go through MediaWikiServices.
	 *
	 * @param string $text
	 * @return TextContent
	 */
	protected static function getTextContent( $text ) {
		return new class( $text ) extends TextContent {
			public function getContentHandler() {
				return new TextContentHandler();
			}
		};
	}

	/**
	 * @param SlotRecord[] $slots
	 * @return RevisionSlots
	 */
	protected static function newRevisionSlots( $slots = [] ) {
		return new RevisionSlots( $slots );
	}

	public static function provideConstructorFailue() {
		yield 'not an array or callable' => [
			'foo'
		];
		yield 'array of the wrong thing' => [
			[ 1, 2, 3 ]
		];
	}

	/**
	 * @dataProvider provideConstructorFailue
	 * @param array $slots
	 */
	public function testConstructorFailue( $slots ) {
		$this->expectException( InvalidArgumentException::class );

		new RevisionSlots( $slots );
	}

	public function testGetSlot() {
		$mainSlot = SlotRecord::newUnsaved( SlotRecord::MAIN, new WikitextContent( 'A' ) );
		$auxSlot = SlotRecord::newUnsaved( 'aux', new WikitextContent( 'B' ) );
		$slots = self::newRevisionSlots( [ $mainSlot, $auxSlot ] );

		$this->assertSame( $mainSlot, $slots->getSlot( SlotRecord::MAIN ) );
		$this->assertSame( $auxSlot, $slots->getSlot( 'aux' ) );
		$this->expectException( RevisionAccessException::class );
		$slots->getSlot( 'nothere' );
	}

	public function testHasSlot() {
		$mainSlot = SlotRecord::newUnsaved( SlotRecord::MAIN, new WikitextContent( 'A' ) );
		$auxSlot = SlotRecord::newUnsaved( 'aux', new WikitextContent( 'B' ) );
		$slots = self::newRevisionSlots( [ $mainSlot, $auxSlot ] );

		$this->assertTrue( $slots->hasSlot( SlotRecord::MAIN ) );
		$this->assertTrue( $slots->hasSlot( 'aux' ) );
		$this->assertFalse( $slots->hasSlot( 'AUX' ) );
		$this->assertFalse( $slots->hasSlot( 'xyz' ) );
	}

	public function testGetContent() {
		$mainContent = new WikitextContent( 'A' );
		$auxContent = new WikitextContent( 'B' );
		$mainSlot = SlotRecord::newUnsaved( SlotRecord::MAIN, $mainContent );
		$auxSlot = SlotRecord::newUnsaved( 'aux', $auxContent );
		$slots = self::newRevisionSlots( [ $mainSlot, $auxSlot ] );

		$this->assertSame( $mainContent, $slots->getContent( SlotRecord::MAIN ) );
		$this->assertSame( $auxContent, $slots->getContent( 'aux' ) );
		$this->expectException( RevisionAccessException::class );
		$slots->getContent( 'nothere' );
	}

	public function testGetSlotRoles_someSlots() {
		$mainSlot = SlotRecord::newUnsaved( SlotRecord::MAIN, new WikitextContent( 'A' ) );
		$auxSlot = SlotRecord::newUnsaved( 'aux', new WikitextContent( 'B' ) );
		$slots = self::newRevisionSlots( [ $mainSlot, $auxSlot ] );

		$this->assertSame( [ SlotRecord::MAIN, 'aux' ], $slots->getSlotRoles() );
	}

	public function testGetSlotRoles_noSlots() {
		$slots = self::newRevisionSlots( [] );

		$this->assertSame( [], $slots->getSlotRoles() );
	}

	public function testGetSlots() {
		$mainSlot = SlotRecord::newUnsaved( SlotRecord::MAIN, new WikitextContent( 'A' ) );
		$auxSlot = SlotRecord::newUnsaved( 'aux', new WikitextContent( 'B' ) );
		$slotsArray = [ $mainSlot, $auxSlot ];
		$slots = self::newRevisionSlots( $slotsArray );

		$this->assertEquals( [ SlotRecord::MAIN => $mainSlot, 'aux' => $auxSlot ], $slots->getSlots() );
	}

	public function testGetNonDerivedSlots() {
		$mainSlot = SlotRecord::newUnsaved( SlotRecord::MAIN, new WikitextContent( 'A' ) );
		$auxSlot = SlotRecord::newDerived( 'aux', new WikitextContent( 'B' ) );
		$slotsArray = [ $mainSlot, $auxSlot ];
		$slots = self::newRevisionSlots( $slotsArray );

		$this->assertEquals( [ SlotRecord::MAIN => $mainSlot ], $slots->getPrimarySlots() );
	}

	public function testGetInheritedSlots() {
		$mainSlot = SlotRecord::newUnsaved( SlotRecord::MAIN, new WikitextContent( 'A' ) );
		$auxSlot = SlotRecord::newInherited(
			SlotRecord::newSaved(
				7, 7, 'foo',
				SlotRecord::newUnsaved( 'aux', new WikitextContent( 'B' ) )
			)
		);
		$slotsArray = [ $mainSlot, $auxSlot ];
		$slots = self::newRevisionSlots( $slotsArray );

		$this->assertEquals( [ 'aux' => $auxSlot ], $slots->getInheritedSlots() );
	}

	public function testGetOriginalSlots() {
		$mainSlot = SlotRecord::newUnsaved( SlotRecord::MAIN, new WikitextContent( 'A' ) );
		$auxSlot = SlotRecord::newInherited(
			SlotRecord::newSaved(
				7, 7, 'foo',
				SlotRecord::newUnsaved( 'aux', new WikitextContent( 'B' ) )
			)
		);
		$slotsArray = [ $mainSlot, $auxSlot ];
		$slots = self::newRevisionSlots( $slotsArray );

		$this->assertEquals( [ SlotRecord::MAIN => $mainSlot ], $slots->getOriginalSlots() );
	}

	public static function provideComputeSize() {
		yield [ 1, [ 'A' ] ];
		yield [ 2, [ 'AA' ] ];
		yield [ 4, [ 'AA', 'X', 'H' ] ];
	}

	/**
	 * @dataProvider provideComputeSize
	 */
	public function testComputeSize( $expected, $contentStrings ) {
		$slotsArray = [];
		foreach ( $contentStrings as $key => $contentString ) {
			$slotsArray[] = SlotRecord::newUnsaved( strval( $key ), new WikitextContent( $contentString ) );
		}
		$slots = self::newRevisionSlots( $slotsArray );

		$this->assertSame( $expected, $slots->computeSize() );
	}

	public static function provideComputeSha1() {
		yield [ 'ctqm7794fr2dp1taki8a88ovwnvmnmj', [ 'A' ] ];
		yield [ 'eyq8wiwlcofnaiy4eid97gyfy60uw51', [ 'AA' ] ];
		yield [ 'lavctqfpxartyjr31f853drgfl4kj1g', [ 'AA', 'X', 'H' ] ];
	}

	/**
	 * @dataProvider provideComputeSha1
	 * @note this test is a bit brittle as the hashes are hardcoded, perhaps just check that strings
	 *       are returned and different Slots objects return different strings?
	 */
	public function testComputeSha1( $expected, $contentStrings ) {
		$slotsArray = [];
		foreach ( $contentStrings as $key => $contentString ) {
			$slotsArray[] = SlotRecord::newUnsaved(
				strval( $key ),
				self::getTextContent( $contentString )
			);
		}
		$slots = self::newRevisionSlots( $slotsArray );

		$this->assertSame( $expected, $slots->computeSha1() );
	}

	public static function provideHasSameContent() {
		$fooX = SlotRecord::newUnsaved( 'x', self::getTextContent( 'Foo' ) );
		$barZ = SlotRecord::newUnsaved( 'z', self::getTextContent( 'Bar' ) );
		$fooY = SlotRecord::newUnsaved( 'y', self::getTextContent( 'Foo' ) );
		$barZS = SlotRecord::newSaved( 7, 7, 'xyz', $barZ );
		$barZ2 = SlotRecord::newUnsaved( 'z', self::getTextContent( 'Baz' ) );

		$a = self::newRevisionSlots( [ 'x' => $fooX, 'z' => $barZ ] );
		$a2 = self::newRevisionSlots( [ 'x' => $fooX, 'z' => $barZ ] );
		$a3 = self::newRevisionSlots( [ 'x' => $fooX, 'z' => $barZS ] );
		$b = self::newRevisionSlots( [ 'y' => $fooY, 'z' => $barZ ] );
		$c = self::newRevisionSlots( [ 'x' => $fooX, 'z' => $barZ2 ] );

		yield 'same instance' => [ $a, $a, true ];
		yield 'same slots' => [ $a, $a2, true ];
		yield 'same content' => [ $a, $a3, true ];

		yield 'different roles' => [ $a, $b, false ];
		yield 'different content' => [ $a, $c, false ];
	}

	/**
	 * @dataProvider provideHasSameContent
	 */
	public function testHasSameContent( RevisionSlots $a, RevisionSlots $b, $same ) {
		$this->assertSame( $same, $a->hasSameContent( $b ) );
		$this->assertSame( $same, $b->hasSameContent( $a ) );
	}

	public static function provideGetRolesWithDifferentContent() {
		$fooX = SlotRecord::newUnsaved( 'x', self::getTextContent( 'Foo' ) );
		$barZ = SlotRecord::newUnsaved( 'z', self::getTextContent( 'Bar' ) );
		$fooY = SlotRecord::newUnsaved( 'y', self::getTextContent( 'Foo' ) );
		$barZS = SlotRecord::newSaved( 7, 7, 'xyz', $barZ );
		$barZ2 = SlotRecord::newUnsaved( 'z', self::getTextContent( 'Baz' ) );

		$a = self::newRevisionSlots( [ 'x' => $fooX, 'z' => $barZ ] );
		$a2 = self::newRevisionSlots( [ 'x' => $fooX, 'z' => $barZ ] );
		$a3 = self::newRevisionSlots( [ 'x' => $fooX, 'z' => $barZS ] );
		$b = self::newRevisionSlots( [ 'y' => $fooY, 'z' => $barZ ] );
		$c = self::newRevisionSlots( [ 'x' => $fooX, 'z' => $barZ2 ] );

		yield 'same instance' => [ $a, $a, [] ];
		yield 'same slots' => [ $a, $a2, [] ];
		yield 'same content' => [ $a, $a3, [] ];

		yield 'different roles' => [ $a, $b, [ 'x', 'y' ] ];
		yield 'different content' => [ $a, $c, [ 'z' ] ];
	}

	/**
	 * @dataProvider provideGetRolesWithDifferentContent
	 */
	public function testGetRolesWithDifferentContent( RevisionSlots $a, RevisionSlots $b, $roles ) {
		$this->assertArrayEquals( $roles, $a->getRolesWithDifferentContent( $b ) );
		$this->assertArrayEquals( $roles, $b->getRolesWithDifferentContent( $a ) );
	}

}
