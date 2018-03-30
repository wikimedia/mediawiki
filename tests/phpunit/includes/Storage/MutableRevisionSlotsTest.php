<?php

namespace MediaWiki\Tests\Storage;

use InvalidArgumentException;
use MediaWiki\Storage\MutableRevisionSlots;
use MediaWiki\Storage\RevisionAccessException;
use MediaWiki\Storage\RevisionSlots;
use MediaWiki\Storage\SlotRecord;
use WikitextContent;

/**
 * @covers \MediaWiki\Storage\MutableRevisionSlots
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
	 * @covers \MediaWiki\Storage\RevisionSlots::__construct
	 * @covers \MediaWiki\Storage\RevisionSlots::setSlotsInternal
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

		$slotA = SlotRecord::newUnsaved( 'main', new WikitextContent( 'A' ) );
		$slots->setSlot( $slotA );
		$this->assertSame( $slotA, $slots->getSlot( 'main' ) );
		$this->assertSame( [ 'main' => $slotA ], $slots->getSlots() );

		$slotB = SlotRecord::newUnsaved( 'main', new WikitextContent( 'B' ) );
		$slots->setSlot( $slotB );
		$this->assertSame( $slotB, $slots->getSlot( 'main' ) );
		$this->assertSame( [ 'main' => $slotB ], $slots->getSlots() );
	}

	public function testSetContentOfExistingSlotOverwritesContent() {
		$slots = new MutableRevisionSlots();

		$this->assertSame( [], $slots->getSlots() );

		$slotA = SlotRecord::newUnsaved( 'main', new WikitextContent( 'A' ) );
		$slots->setSlot( $slotA );
		$this->assertSame( $slotA, $slots->getSlot( 'main' ) );
		$this->assertSame( [ 'main' => $slotA ], $slots->getSlots() );

		$newContent = new WikitextContent( 'B' );
		$slots->setContent( 'main', $newContent );
		$this->assertSame( $newContent, $slots->getContent( 'main' ) );
	}

	public function testRemoveExistingSlot() {
		$slotA = SlotRecord::newUnsaved( 'main', new WikitextContent( 'A' ) );
		$slots = new MutableRevisionSlots( [ $slotA ] );

		$this->assertSame( [ 'main' => $slotA ], $slots->getSlots() );

		$slots->removeSlot( 'main' );
		$this->assertSame( [], $slots->getSlots() );
		$this->setExpectedException( RevisionAccessException::class );
		$slots->getSlot( 'main' );
	}

}
