<?php

namespace MediaWiki\Tests\Storage;

use MediaWiki\Storage\RevisionAccessException;
use MediaWiki\Storage\RevisionSlots;
use MediaWiki\Storage\SlotRecord;
use MediaWikiTestCase;
use WikitextContent;

class RevisionSlotsTest extends MediaWikiTestCase {

	/**
	 * @param SlotRecord[] $slots
	 * @return RevisionSlots
	 */
	protected function newRevisionSlots( $slots = [] ) {
		return new RevisionSlots( $slots );
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

}
