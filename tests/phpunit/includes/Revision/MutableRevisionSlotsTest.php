<?php

namespace MediaWiki\Tests\Revision;

use Content;
use InvalidArgumentException;
use MediaWiki\Revision\MutableRevisionSlots;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionSlots;
use MediaWiki\Revision\SlotRecord;
use WikitextContent;

/**
 * @covers \MediaWiki\Revision\MutableRevisionSlots
 */
class MutableRevisionSlotsTest extends RevisionSlotsTest {

	/**
	 * @param SlotRecord[] $slots
	 * @return RevisionSlots
	 */
	protected function newRevisionSlots( $slots = [] ) {
		return new MutableRevisionSlots( $slots );
	}

	public function provideConstructorFailue() {
		yield 'array or the wrong thing' => [
			[ 1, 2, 3 ]
		];
	}

	/**
	 * @dataProvider provideConstructorFailue
	 * @param $slots
	 *
	 * @covers \MediaWiki\Revision\RevisionSlots::__construct
	 * @covers \MediaWiki\Revision\RevisionSlots::setSlotsInternal
	 */
	public function testConstructorFailue( $slots ) {
		$this->setExpectedException( InvalidArgumentException::class );

		new MutableRevisionSlots( $slots );
	}

	public function testSetMultipleSlots() {
		$slots = new MutableRevisionSlots();

		$this->assertSame( [], $slots->getSlots() );

		$slotA = SlotRecord::newUnsaved( 'some', new WikitextContent( 'A' ) );
		$slots->setSlot( $slotA );
		$this->assertTrue( $slots->hasSlot( 'some' ) );
		$this->assertSame( $slotA, $slots->getSlot( 'some' ) );
		$this->assertSame( [ 'some' => $slotA ], $slots->getSlots() );

		$slotB = SlotRecord::newUnsaved( 'other', new WikitextContent( 'B' ) );
		$slots->setSlot( $slotB );
		$this->assertTrue( $slots->hasSlot( 'other' ) );
		$this->assertSame( $slotB, $slots->getSlot( 'other' ) );
		$this->assertSame( [ 'some' => $slotA, 'other' => $slotB ], $slots->getSlots() );
	}

	public function testSetExistingSlotOverwritesSlot() {
		$slots = new MutableRevisionSlots();

		$this->assertSame( [], $slots->getSlots() );

		$slotA = SlotRecord::newUnsaved( SlotRecord::MAIN, new WikitextContent( 'A' ) );
		$slots->setSlot( $slotA );
		$this->assertSame( $slotA, $slots->getSlot( SlotRecord::MAIN ) );
		$this->assertSame( [ 'main' => $slotA ], $slots->getSlots() );

		$slotB = SlotRecord::newUnsaved( SlotRecord::MAIN, new WikitextContent( 'B' ) );
		$slots->setSlot( $slotB );
		$this->assertSame( $slotB, $slots->getSlot( SlotRecord::MAIN ) );
		$this->assertSame( [ 'main' => $slotB ], $slots->getSlots() );
	}

	/**
	 * @param string $role
	 * @param Content $content
	 * @return SlotRecord
	 */
	private function newSavedSlot( $role, Content $content ) {
		return SlotRecord::newSaved( 7, 7, 'xyz', SlotRecord::newUnsaved( $role, $content ) );
	}

	public function testInheritSlotOverwritesSlot() {
		$slots = new MutableRevisionSlots();
		$slotA = SlotRecord::newUnsaved( SlotRecord::MAIN, new WikitextContent( 'A' ) );
		$slots->setSlot( $slotA );
		$slotB = $this->newSavedSlot( SlotRecord::MAIN, new WikitextContent( 'B' ) );
		$slotC = $this->newSavedSlot( 'foo', new WikitextContent( 'C' ) );
		$slots->inheritSlot( $slotB );
		$slots->inheritSlot( $slotC );
		$this->assertSame( [ 'main', 'foo' ], $slots->getSlotRoles() );
		$this->assertNotSame( $slotB, $slots->getSlot( SlotRecord::MAIN ) );
		$this->assertNotSame( $slotC, $slots->getSlot( 'foo' ) );
		$this->assertTrue( $slots->getSlot( SlotRecord::MAIN )->isInherited() );
		$this->assertTrue( $slots->getSlot( 'foo' )->isInherited() );
		$this->assertSame( $slotB->getContent(), $slots->getSlot( SlotRecord::MAIN )->getContent() );
		$this->assertSame( $slotC->getContent(), $slots->getSlot( 'foo' )->getContent() );
	}

	public function testSetContentOfExistingSlotOverwritesContent() {
		$slots = new MutableRevisionSlots();

		$this->assertSame( [], $slots->getSlots() );

		$slotA = SlotRecord::newUnsaved( SlotRecord::MAIN, new WikitextContent( 'A' ) );
		$slots->setSlot( $slotA );
		$this->assertSame( $slotA, $slots->getSlot( SlotRecord::MAIN ) );
		$this->assertSame( [ 'main' => $slotA ], $slots->getSlots() );

		$newContent = new WikitextContent( 'B' );
		$slots->setContent( SlotRecord::MAIN, $newContent );
		$this->assertSame( $newContent, $slots->getContent( SlotRecord::MAIN ) );
	}

	public function testRemoveExistingSlot() {
		$slotA = SlotRecord::newUnsaved( SlotRecord::MAIN, new WikitextContent( 'A' ) );
		$slots = new MutableRevisionSlots( [ $slotA ] );

		$this->assertSame( [ 'main' => $slotA ], $slots->getSlots() );

		$slots->removeSlot( SlotRecord::MAIN );
		$this->assertSame( [], $slots->getSlots() );
		$this->setExpectedException( RevisionAccessException::class );
		$slots->getSlot( SlotRecord::MAIN );
	}

	public function testNewFromParentRevisionSlots() {
		/** @var SlotRecord[] $parentSlots */
		$parentSlots = [
			'some' => $this->newSavedSlot( 'some', new WikitextContent( 'X' ) ),
			'other' => $this->newSavedSlot( 'other', new WikitextContent( 'Y' ) ),
		];
		$slots = MutableRevisionSlots::newFromParentRevisionSlots( $parentSlots );
		$this->assertSame( [ 'some', 'other' ], $slots->getSlotRoles() );
		$this->assertNotSame( $parentSlots['some'], $slots->getSlot( 'some' ) );
		$this->assertNotSame( $parentSlots['other'], $slots->getSlot( 'other' ) );
		$this->assertTrue( $slots->getSlot( 'some' )->isInherited() );
		$this->assertTrue( $slots->getSlot( 'other' )->isInherited() );
		$this->assertSame( $parentSlots['some']->getContent(), $slots->getContent( 'some' ) );
		$this->assertSame( $parentSlots['other']->getContent(), $slots->getContent( 'other' ) );
	}

}
