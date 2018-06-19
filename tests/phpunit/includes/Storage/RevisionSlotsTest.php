<?php

namespace MediaWiki\Tests\Storage;

use InvalidArgumentException;
use MediaWiki\Storage\RevisionAccessException;
use MediaWiki\Storage\RevisionSlots;
use MediaWiki\Storage\SlotRecord;
use MediaWikiTestCase;
use TextContent;
use WikitextContent;

class RevisionSlotsTest extends MediaWikiTestCase {

	/**
	 * @param SlotRecord[] $slots
	 * @return RevisionSlots
	 */
	protected function newRevisionSlots( $slots = [] ) {
		return new RevisionSlots( $slots );
	}

	public function provideConstructorFailue() {
		yield 'not an array or callable' => [
			'foo'
		];
		yield 'array of the wrong thing' => [
			[ 1, 2, 3 ]
		];
	}

	/**
	 * @dataProvider provideConstructorFailue
	 * @param $slots
	 *
	 * @covers \MediaWiki\Storage\RevisionSlots::__construct
	 * @covers \MediaWiki\Storage\RevisionSlots::setSlotsInternal
	 */
	public function testConstructorFailue( $slots ) {
		$this->setExpectedException( InvalidArgumentException::class );

		new RevisionSlots( $slots );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionSlots::getSlot
	 */
	public function testGetSlot() {
		$mainSlot = SlotRecord::newUnsaved( 'main', new WikitextContent( 'A' ) );
		$auxSlot = SlotRecord::newUnsaved( 'aux', new WikitextContent( 'B' ) );
		$slots = $this->newRevisionSlots( [ $mainSlot, $auxSlot ] );

		$this->assertSame( $mainSlot, $slots->getSlot( 'main' ) );
		$this->assertSame( $auxSlot, $slots->getSlot( 'aux' ) );
		$this->setExpectedException( RevisionAccessException::class );
		$slots->getSlot( 'nothere' );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionSlots::hasSlot
	 */
	public function testHasSlot() {
		$mainSlot = SlotRecord::newUnsaved( 'main', new WikitextContent( 'A' ) );
		$auxSlot = SlotRecord::newUnsaved( 'aux', new WikitextContent( 'B' ) );
		$slots = $this->newRevisionSlots( [ $mainSlot, $auxSlot ] );

		$this->assertTrue( $slots->hasSlot( 'main' ) );
		$this->assertTrue( $slots->hasSlot( 'aux' ) );
		$this->assertFalse( $slots->hasSlot( 'AUX' ) );
		$this->assertFalse( $slots->hasSlot( 'xyz' ) );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionSlots::getContent
	 */
	public function testGetContent() {
		$mainContent = new WikitextContent( 'A' );
		$auxContent = new WikitextContent( 'B' );
		$mainSlot = SlotRecord::newUnsaved( 'main', $mainContent );
		$auxSlot = SlotRecord::newUnsaved( 'aux', $auxContent );
		$slots = $this->newRevisionSlots( [ $mainSlot, $auxSlot ] );

		$this->assertSame( $mainContent, $slots->getContent( 'main' ) );
		$this->assertSame( $auxContent, $slots->getContent( 'aux' ) );
		$this->setExpectedException( RevisionAccessException::class );
		$slots->getContent( 'nothere' );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionSlots::getSlotRoles
	 */
	public function testGetSlotRoles_someSlots() {
		$mainSlot = SlotRecord::newUnsaved( 'main', new WikitextContent( 'A' ) );
		$auxSlot = SlotRecord::newUnsaved( 'aux', new WikitextContent( 'B' ) );
		$slots = $this->newRevisionSlots( [ $mainSlot, $auxSlot ] );

		$this->assertSame( [ 'main', 'aux' ], $slots->getSlotRoles() );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionSlots::getSlotRoles
	 */
	public function testGetSlotRoles_noSlots() {
		$slots = $this->newRevisionSlots( [] );

		$this->assertSame( [], $slots->getSlotRoles() );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionSlots::getSlots
	 */
	public function testGetSlots() {
		$mainSlot = SlotRecord::newUnsaved( 'main', new WikitextContent( 'A' ) );
		$auxSlot = SlotRecord::newUnsaved( 'aux', new WikitextContent( 'B' ) );
		$slotsArray = [ $mainSlot, $auxSlot ];
		$slots = $this->newRevisionSlots( $slotsArray );

		$this->assertEquals( [ 'main' => $mainSlot, 'aux' => $auxSlot ], $slots->getSlots() );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionSlots::getInheritedSlots
	 */
	public function testGetInheritedSlots() {
		$mainSlot = SlotRecord::newUnsaved( 'main', new WikitextContent( 'A' ) );
		$auxSlot = SlotRecord::newInherited(
			SlotRecord::newSaved(
				7, 7, 'foo',
				SlotRecord::newUnsaved( 'aux', new WikitextContent( 'B' ) )
			)
		);
		$slotsArray = [ $mainSlot, $auxSlot ];
		$slots = $this->newRevisionSlots( $slotsArray );

		$this->assertEquals( [ 'aux' => $auxSlot ], $slots->getInheritedSlots() );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionSlots::getOriginalSlots
	 */
	public function testGetOriginalSlots() {
		$mainSlot = SlotRecord::newUnsaved( 'main', new WikitextContent( 'A' ) );
		$auxSlot = SlotRecord::newInherited(
			SlotRecord::newSaved(
				7, 7, 'foo',
				SlotRecord::newUnsaved( 'aux', new WikitextContent( 'B' ) )
			)
		);
		$slotsArray = [ $mainSlot, $auxSlot ];
		$slots = $this->newRevisionSlots( $slotsArray );

		$this->assertEquals( [ 'main' => $mainSlot ], $slots->getOriginalSlots() );
	}

	public function provideComputeSize() {
		yield [ 1, [ 'A' ] ];
		yield [ 2, [ 'AA' ] ];
		yield [ 4, [ 'AA', 'X', 'H' ] ];
	}

	/**
	 * @dataProvider provideComputeSize
	 * @covers \MediaWiki\Storage\RevisionSlots::computeSize
	 */
	public function testComputeSize( $expected, $contentStrings ) {
		$slotsArray = [];
		foreach ( $contentStrings as $key => $contentString ) {
			$slotsArray[] = SlotRecord::newUnsaved( strval( $key ), new WikitextContent( $contentString ) );
		}
		$slots = $this->newRevisionSlots( $slotsArray );

		$this->assertSame( $expected, $slots->computeSize() );
	}

	public function provideComputeSha1() {
		yield [ 'ctqm7794fr2dp1taki8a88ovwnvmnmj', [ 'A' ] ];
		yield [ 'eyq8wiwlcofnaiy4eid97gyfy60uw51', [ 'AA' ] ];
		yield [ 'lavctqfpxartyjr31f853drgfl4kj1g', [ 'AA', 'X', 'H' ] ];
	}

	/**
	 * @dataProvider provideComputeSha1
	 * @covers \MediaWiki\Storage\RevisionSlots::computeSha1
	 * @note this test is a bit brittle as the hashes are hardcoded, perhaps just check that strings
	 *       are returned and different Slots objects return different strings?
	 */
	public function testComputeSha1( $expected, $contentStrings ) {
		$slotsArray = [];
		foreach ( $contentStrings as $key => $contentString ) {
			$slotsArray[] = SlotRecord::newUnsaved( strval( $key ), new WikitextContent( $contentString ) );
		}
		$slots = $this->newRevisionSlots( $slotsArray );

		$this->assertSame( $expected, $slots->computeSha1() );
	}

	public function provideHasSameContent() {
		$fooX = SlotRecord::newUnsaved( 'x', new TextContent( 'Foo' ) );
		$barZ = SlotRecord::newUnsaved( 'z', new TextContent( 'Bar' ) );
		$fooY = SlotRecord::newUnsaved( 'y', new TextContent( 'Foo' ) );
		$barZS = SlotRecord::newSaved( 7, 7, 'xyz', $barZ );
		$barZ2 = SlotRecord::newUnsaved( 'z', new TextContent( 'Baz' ) );

		$a = $this->newRevisionSlots( [ 'x' => $fooX, 'z' => $barZ ] );
		$a2 = $this->newRevisionSlots( [ 'x' => $fooX, 'z' => $barZ ] );
		$a3 = $this->newRevisionSlots( [ 'x' => $fooX, 'z' => $barZS ] );
		$b = $this->newRevisionSlots( [ 'y' => $fooY, 'z' => $barZ ] );
		$c = $this->newRevisionSlots( [ 'x' => $fooX, 'z' => $barZ2 ] );

		yield 'same instance' => [ $a, $a, true ];
		yield 'same slots' => [ $a, $a2, true ];
		yield 'same content' => [ $a, $a3, true ];

		yield 'different roles' => [ $a, $b, false ];
		yield 'different content' => [ $a, $c, false ];
	}

	/**
	 * @dataProvider provideHasSameContent
	 * @covers \MediaWiki\Storage\RevisionSlots::hasSameContent
	 */
	public function testHasSameContent( RevisionSlots $a, RevisionSlots $b, $same ) {
		$this->assertSame( $same, $a->hasSameContent( $b ) );
		$this->assertSame( $same, $b->hasSameContent( $a ) );
	}

	public function provideGetRolesWithDifferentContent() {
		$fooX = SlotRecord::newUnsaved( 'x', new TextContent( 'Foo' ) );
		$barZ = SlotRecord::newUnsaved( 'z', new TextContent( 'Bar' ) );
		$fooY = SlotRecord::newUnsaved( 'y', new TextContent( 'Foo' ) );
		$barZS = SlotRecord::newSaved( 7, 7, 'xyz', $barZ );
		$barZ2 = SlotRecord::newUnsaved( 'z', new TextContent( 'Baz' ) );

		$a = $this->newRevisionSlots( [ 'x' => $fooX, 'z' => $barZ ] );
		$a2 = $this->newRevisionSlots( [ 'x' => $fooX, 'z' => $barZ ] );
		$a3 = $this->newRevisionSlots( [ 'x' => $fooX, 'z' => $barZS ] );
		$b = $this->newRevisionSlots( [ 'y' => $fooY, 'z' => $barZ ] );
		$c = $this->newRevisionSlots( [ 'x' => $fooX, 'z' => $barZ2 ] );

		yield 'same instance' => [ $a, $a, [] ];
		yield 'same slots' => [ $a, $a2, [] ];
		yield 'same content' => [ $a, $a3, [] ];

		yield 'different roles' => [ $a, $b, [ 'x', 'y' ] ];
		yield 'different content' => [ $a, $c, [ 'z' ] ];
	}

	/**
	 * @dataProvider provideGetRolesWithDifferentContent
	 * @covers \MediaWiki\Storage\RevisionSlots::getRolesWithDifferentContent
	 */
	public function testGetRolesWithDifferentContent( RevisionSlots $a, RevisionSlots $b, $roles ) {
		$this->assertArrayEquals( $roles, $a->getRolesWithDifferentContent( $b ) );
		$this->assertArrayEquals( $roles, $b->getRolesWithDifferentContent( $a ) );
	}

}
