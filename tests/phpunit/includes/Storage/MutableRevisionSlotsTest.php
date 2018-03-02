<?php

namespace MediaWiki\Tests\Storage;

use MediaWiki\Storage\MutableRevisionSlots;
use MediaWiki\Storage\RevisionAccessException;
use MediaWiki\Storage\SlotRecord;
use WikitextContent;

/**
 * @covers \MediaWiki\Storage\MutableRevisionSlots
 */
class MutableRevisionSlotsTest extends RevisionSlotsTest {

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
