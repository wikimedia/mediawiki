<?php

namespace MediaWiki\Tests\Storage;

use Content;
use MediaWiki\Storage\MutableRevisionSlots;
use MediaWiki\Storage\RevisionAccessException;
use MediaWiki\Storage\SlotRecord;
use WikitextContent;

/**
 * @covers \MediaWiki\Storage\MutableRevisionSlots
 */
class MutableRevisionSlotsTest extends RevisionSlotsTest {

	protected function newRevisionSlots( $slots = [] ) {
		return new MutableRevisionSlots( $slots );
	}

	public function testSetMultipleSlots() {
		$slots = new MutableRevisionSlots();

		$this->assertSame( [], $slots->getSlotRoles() );
		$this->assertSame( [], $slots->getModifiedRoles() );
		$this->assertSame( [], $slots->getRemovedRoles() );

		$slotA = SlotRecord::newUnsaved( 'some', new WikitextContent( 'A' ) );
		$slots->setSlot( $slotA );
		$this->assertTrue( $slots->hasSlot( 'some' ) );
		$this->assertSame( $slotA, $slots->getSlot( 'some' ) );
		$this->assertSame( [ 'some' => $slotA ], $slots->getSlots() );
		$this->assertSame( [ 'some' ], $slots->getSlotRoles() );
		$this->assertSame( [ 'some' ], $slots->getModifiedRoles() );
		$this->assertSame( [], $slots->getRemovedRoles() );

		$slotB = SlotRecord::newUnsaved( 'other', new WikitextContent( 'B' ) );
		$slots->setSlot( $slotB );
		$this->assertTrue( $slots->hasSlot( 'other' ) );
		$this->assertSame( $slotB, $slots->getSlot( 'other' ) );
		$this->assertSame( [ 'some' => $slotA, 'other' => $slotB ], $slots->getSlots() );
		$this->assertSame( [ 'some', 'other' ], $slots->getSlotRoles() );
		$this->assertSame( [ 'some', 'other' ], $slots->getModifiedRoles() );
		$this->assertSame( [], $slots->getRemovedRoles() );
	}

	public function testSetExistingSlotOverwritesSlot() {
		$slots = new MutableRevisionSlots();

		$slotA = SlotRecord::newUnsaved( 'main', new WikitextContent( 'A' ) );
		$slots->setSlot( $slotA );
		$this->assertSame( $slotA, $slots->getSlot( 'main' ) );
		$this->assertSame( [ 'main' => $slotA ], $slots->getSlots() );

		$slotB = SlotRecord::newUnsaved( 'main', new WikitextContent( 'B' ) );
		$slots->setSlot( $slotB );
		$this->assertSame( $slotB, $slots->getSlot( 'main' ) );
		$this->assertSame( [ 'main' => $slotB ], $slots->getSlots() );

		$this->assertSame( [ 'main' ], $slots->getSlotRoles() );
		$this->assertSame( [ 'main' ], $slots->getModifiedRoles() );
		$this->assertSame( [], $slots->getRemovedRoles() );

		// setting the same slot again should not make a difference
		$slots->setSlot( $slotB );
		$this->assertSame( [ 'main' => $slotB ], $slots->getSlots() );
	}

	public function testInheritSlotOverwritesSlot() {
		$slots = new MutableRevisionSlots();

		$slotA = SlotRecord::newUnsaved( 'main', new WikitextContent( 'A' ) );
		$slots->setSlot( $slotA );

		$slotB = $this->newSavedSlot( 'main', new WikitextContent( 'B' ) );
		$slotC = $this->newSavedSlot( 'foo', new WikitextContent( 'C' ) );

		$slots->inheritSlot( $slotB );
		$slots->inheritSlot( $slotC );

		$this->assertSame( [ 'main', 'foo' ], $slots->getSlotRoles() );
		$this->assertSame( [], $slots->getModifiedRoles(), 'Inherited slots are not modified' );
		$this->assertSame( [], $slots->getRemovedRoles(), 'Inherited slots are not removed' );

		$this->assertNotSame( $slotB, $slots->getSlot( 'main' ) );
		$this->assertNotSame( $slotC, $slots->getSlot( 'foo' ) );

		$this->assertTrue( $slots->getSlot( 'main' )->isInherited() );
		$this->assertTrue( $slots->getSlot( 'foo' )->isInherited() );

		$this->assertSame( $slotB->getContent(), $slots->getSlot( 'main' )->getContent() );
		$this->assertSame( $slotC->getContent(), $slots->getSlot( 'foo' )->getContent() );
	}

	public function testSetContentOfExistingSlotOverwritesContent() {
		$slotA = SlotRecord::newUnsaved( 'main', new WikitextContent( 'A' ) );
		$slots = new MutableRevisionSlots( [ $slotA ] );

		$this->assertSame( [ 'main' ], $slots->getSlotRoles() );
		$this->assertSame( [], $slots->getModifiedRoles() );
		$this->assertSame( [], $slots->getRemovedRoles() );

		$newContent = new WikitextContent( 'B' );
		$slots->setContent( 'main', $newContent );
		$this->assertSame( $newContent, $slots->getContent( 'main' ) );
		$this->assertSame( $newContent, $slots->getSlot( 'main' )->getContent() );

		$this->assertSame( [ 'main' ], $slots->getSlotRoles() );
		$this->assertSame( [ 'main' ], $slots->getModifiedRoles() );
		$this->assertSame( [], $slots->getRemovedRoles() );
	}

	public function testRemoveSlot() {
		$slots = new MutableRevisionSlots();

		$slotA = SlotRecord::newUnsaved( 'main', new WikitextContent( 'A' ) );
		$slots->setSlot( $slotA );

		$this->assertSame( [ 'main' => $slotA ], $slots->getSlots() );

		$slots->removeSlot( 'main' );
		$slots->removeSlot( 'other' );
		$this->assertSame( [], $slots->getSlots() );
		$this->assertSame( [], $slots->getSlotRoles() );
		$this->assertSame( [], $slots->getModifiedRoles() );
		$this->assertSame( [ 'main', 'other' ], $slots->getRemovedRoles() );

		// removing the same slot again should not trigger an error
		$slots->removeSlot( 'main' );

		// getting a slot after removing it should fail
		$this->setExpectedException( RevisionAccessException::class );
		$slots->getSlot( 'main' );
	}

	public function testGetModifiedRoles() {
		$slotA = SlotRecord::newUnsaved( 'main', new WikitextContent( 'A' ) );
		$slots = new MutableRevisionSlots( [ $slotA ] );

		$this->assertSame( [], $slots->getModifiedRoles() );

		$slots->setContent( 'main', new WikitextContent( 'A' ) );
		$slots->setContent( 'foo', new WikitextContent( 'Foo' ) );

		$bar = $this->newSavedSlot( 'bar', new WikitextContent( 'Bar' ) );
		$slots->inheritSlot( $bar );

		$this->assertSame( [ 'main', 'foo' ], $slots->getModifiedRoles() );

		$foo = $this->newSavedSlot( 'foo', new WikitextContent( 'Foo' ) );
		$slots->inheritSlot( $foo );

		$this->assertSame( [ 'main' ], $slots->getModifiedRoles() );

		$slots->removeSlot( 'main' );
		$this->assertSame( [], $slots->getModifiedRoles() );
	}

	public function testGetremovedRoles() {
		$slotA = SlotRecord::newUnsaved( 'main', new WikitextContent( 'A' ) );
		$slots = new MutableRevisionSlots( [ $slotA ] );

		$this->assertSame( [], $slots->getRemovedRoles() );

		$slots->removeSlot( 'main', new WikitextContent( 'A' ) );
		$slots->removeSlot( 'foo', new WikitextContent( 'Foo' ) );

		$this->assertSame( [ 'main', 'foo' ], $slots->getRemovedRoles() );

		$foo = $this->newSavedSlot( 'foo', new WikitextContent( 'Foo' ) );
		$slots->inheritSlot( $foo );

		$this->assertSame( [ 'main' ], $slots->getRemovedRoles() );

		$slots->setContent( 'main', new WikitextContent( 'A' ) );
		$this->assertSame( [], $slots->getRemovedRoles() );
	}

	/**
	 * @param string $role
	 * @param Content $content
	 * @return SlotRecord
	 */
	private function newSavedSlot( $role, Content $content ) {
		return SlotRecord::newSaved( 7, 7, 'xyz', SlotRecord::newUnsaved( $role, $content ) );
	}

	public function testNewFromParentRevisionSlots() {
		/** @var SlotRecord[] $parentSlots */
		$parentSlots = [
			'some' => $this->newSavedSlot( 'some', new WikitextContent( 'X' ) ),
			'other' => $this->newSavedSlot( 'other', new WikitextContent( 'Y' ) ),
		];

		$slots = MutableRevisionSlots::newFromParentRevisionSlots( $parentSlots );

		$this->assertSame( [ 'some', 'other' ], $slots->getSlotRoles() );
		$this->assertSame( [], $slots->getModifiedRoles() );

		$this->assertNotSame( $parentSlots['some'], $slots->getSlot( 'some' ) );
		$this->assertNotSame( $parentSlots['other'], $slots->getSlot( 'other' ) );

		$this->assertTrue( $slots->getSlot( 'some' )->isInherited() );
		$this->assertTrue( $slots->getSlot( 'other' )->isInherited() );

		$this->assertSame( $parentSlots['some']->getContent(), $slots->getContent( 'some' ) );
		$this->assertSame( $parentSlots['other']->getContent(), $slots->getContent( 'other' ) );
	}

	public function testNewAsUpdate() {
		$newContent = [
			'some' => new WikitextContent( 'X' ),
			'other' => new WikitextContent( 'Y' ),
		];

		$slots = MutableRevisionSlots::newAsUpdate( $newContent, [ 'xyzzy' ] );

		$this->assertSame( [ 'some', 'other' ], $slots->getSlotRoles() );
		$this->assertSame( [ 'some', 'other' ], $slots->getModifiedRoles() );
		$this->assertSame( [ 'xyzzy' ], $slots->getRemovedRoles() );

		$this->assertFalse( $slots->getSlot( 'some' )->isInherited() );
		$this->assertFalse( $slots->getSlot( 'other' )->isInherited() );

		$this->assertSame( $newContent['some'], $slots->getContent( 'some' ) );
		$this->assertSame( $newContent['other'], $slots->getContent( 'other' ) );
	}

	public function testApplyUpdate() {
		/** @var SlotRecord[] $parentSlots */
		$parentSlots = [
			'X' => $this->newSavedSlot( 'X', new WikitextContent( 'X' ) ),
			'Y' => $this->newSavedSlot( 'Y', new WikitextContent( 'Y' ) ),
			'Z' => $this->newSavedSlot( 'Z', new WikitextContent( 'Z' ) ),
		];

		$slots = MutableRevisionSlots::newFromParentRevisionSlots( $parentSlots );

		$update = MutableRevisionSlots::newAsUpdate(
			[
				'A' => new WikitextContent( 'A' ),
				'Y' => new WikitextContent( 'yyy' ),
			],
			[ 'Z' ]
		);

		$slots->applyUpdate( $update );

		$this->assertSame( [ 'X', 'Y', 'A' ], $slots->getSlotRoles() );
		$this->assertSame( [ 'A', 'Y' ], $slots->getModifiedRoles() );
		$this->assertSame( [ 'Z' ], $slots->getRemovedRoles() );

		$this->assertSame( $update->getSlot( 'A' ), $slots->getSlot( 'A' ) );
		$this->assertSame( $update->getSlot( 'Y' ), $slots->getSlot( 'Y' ) );
	}

	public function provideHasSameUpdates() {
		$fooX = SlotRecord::newUnsaved( 'x', new WikitextContent( 'Foo' ) );
		$barZ = SlotRecord::newUnsaved( 'z', new WikitextContent( 'Bar' ) );
		$fooY = SlotRecord::newUnsaved( 'y', new WikitextContent( 'Foo' ) );

		$a = new MutableRevisionSlots();
		$a->setSlot( $fooX );
		$a->setSlot( $barZ );
		$a->removeSlot( 'Q' );

		$a2 = new MutableRevisionSlots();
		$a2->setSlot( $fooX );
		$a2->setSlot( $barZ );
		$a2->removeSlot( 'Q' );

		$a3 = new MutableRevisionSlots( [ 'y' => $fooY ] );
		$a3->setSlot( $fooX );
		$a3->setSlot( $barZ );
		$a3->removeSlot( 'Q' );

		$b = new MutableRevisionSlots( [ 'x' => $fooX ] );
		$b->setSlot( $barZ );
		$b->removeSlot( 'Q' );

		$c = new MutableRevisionSlots();
		$c->setSlot( $fooX );
		$c->setSlot( $barZ );

		yield 'same instance' => [ $a, $a, true ];
		yield 'same slots' => [ $a, $a2, true ];
		yield 'same updates, different slots' => [ $a, $a3, true ];

		yield 'same slots, different modified' => [ $a, $b, false ];
		yield 'same slots, different removed' => [ $a, $c, false ];
	}

	/**
	 * @dataProvider provideHasSameUpdates
	 */
	public function testHasSameUpdates( MutableRevisionSlots $a, MutableRevisionSlots $b, $same ) {
		$this->assertSame( $same, $a->hasSameUpdates( $b ) );
		$this->assertSame( $same, $b->hasSameUpdates( $a ) );
	}

}
