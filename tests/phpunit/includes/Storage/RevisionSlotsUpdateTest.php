<?php

namespace MediaWiki\Tests\Storage;

use Content;
use MediaWiki\Revision\MutableRevisionSlots;
use MediaWiki\Revision\RevisionSlots;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\RevisionSlotsUpdate;
use MediaWikiTestCase;
use WikitextContent;

/**
 * @covers \MediaWiki\Storage\RevisionSlotsUpdate
 */
class RevisionSlotsUpdateTest extends MediaWikiTestCase {

	public function provideNewFromRevisionSlots() {
		$slotA = SlotRecord::newUnsaved( 'A', new WikitextContent( 'A' ) );
		$slotB = SlotRecord::newUnsaved( 'B', new WikitextContent( 'B' ) );
		$slotC = SlotRecord::newUnsaved( 'C', new WikitextContent( 'C' ) );

		$slotB2 = SlotRecord::newUnsaved( 'B', new WikitextContent( 'B2' ) );

		$parentSlots = new RevisionSlots( [
			'A' => $slotA,
			'B' => $slotB,
			'C' => $slotC,
		] );

		$newSlots = new RevisionSlots( [
			'A' => $slotA,
			'B' => $slotB2,
		] );

		yield [ $newSlots, null, [ 'A', 'B' ], [] ];
		yield [ $newSlots, $parentSlots, [ 'B' ], [ 'C' ] ];
	}

	/**
	 * @dataProvider provideNewFromRevisionSlots
	 *
	 * @param RevisionSlots $newSlots
	 * @param RevisionSlots $parentSlots
	 * @param string[] $modified
	 * @param string[] $removed
	 */
	public function testNewFromRevisionSlots(
		RevisionSlots $newSlots,
		RevisionSlots $parentSlots = null,
		array $modified = [],
		array $removed = []
	) {
		$update = RevisionSlotsUpdate::newFromRevisionSlots( $newSlots, $parentSlots );

		$this->assertEquals( $modified, $update->getModifiedRoles() );
		$this->assertEquals( $removed, $update->getRemovedRoles() );

		foreach ( $modified as $role ) {
			$this->assertSame( $newSlots->getSlot( $role ), $update->getModifiedSlot( $role ) );
		}
	}

	public function provideNewFromContent() {
		$slotA = SlotRecord::newUnsaved( 'A', new WikitextContent( 'A' ) );
		$slotB = SlotRecord::newUnsaved( 'B', new WikitextContent( 'B' ) );
		$slotC = SlotRecord::newUnsaved( 'C', new WikitextContent( 'C' ) );

		$parentSlots = new RevisionSlots( [
			'A' => $slotA,
			'B' => $slotB,
			'C' => $slotC,
		] );

		$newContent = [
			'A' => new WikitextContent( 'A' ),
			'B' => new WikitextContent( 'B2' ),
		];

		yield [ $newContent, null, [ 'A', 'B' ] ];
		yield [ $newContent, $parentSlots, [ 'B' ] ];
	}

	/**
	 * @dataProvider provideNewFromContent
	 *
	 * @param Content[] $newContent
	 * @param RevisionSlots $parentSlots
	 * @param string[] $modified
	 */
	public function testNewFromContent(
		array $newContent,
		RevisionSlots $parentSlots = null,
		array $modified = []
	) {
		$update = RevisionSlotsUpdate::newFromContent( $newContent, $parentSlots );

		$this->assertEquals( $modified, $update->getModifiedRoles() );
		$this->assertEmpty( $update->getRemovedRoles() );
	}

	public function testConstructor() {
		$update = new RevisionSlotsUpdate();

		$this->assertEmpty( $update->getModifiedRoles() );
		$this->assertEmpty( $update->getRemovedRoles() );

		$slotA = SlotRecord::newUnsaved( 'A', new WikitextContent( 'A' ) );
		$update = new RevisionSlotsUpdate( [ 'A' => $slotA ] );

		$this->assertEquals( [ 'A' ], $update->getModifiedRoles() );
		$this->assertEmpty( $update->getRemovedRoles() );

		$update = new RevisionSlotsUpdate( [ 'A' => $slotA ], [ 'X' ] );

		$this->assertEquals( [ 'A' ], $update->getModifiedRoles() );
		$this->assertEquals( [ 'X' ], $update->getRemovedRoles() );
	}

	public function testModifySlot() {
		$slots = new RevisionSlotsUpdate();

		$this->assertSame( [], $slots->getModifiedRoles() );
		$this->assertSame( [], $slots->getRemovedRoles() );

		$slotA = SlotRecord::newUnsaved( 'some', new WikitextContent( 'A' ) );
		$slots->modifySlot( $slotA );
		$this->assertTrue( $slots->isModifiedSlot( 'some' ) );
		$this->assertFalse( $slots->isRemovedSlot( 'some' ) );
		$this->assertSame( $slotA, $slots->getModifiedSlot( 'some' ) );
		$this->assertSame( [ 'some' ], $slots->getModifiedRoles() );
		$this->assertSame( [], $slots->getRemovedRoles() );

		$slotB = SlotRecord::newUnsaved( 'other', new WikitextContent( 'B' ) );
		$slots->modifySlot( $slotB );
		$this->assertTrue( $slots->isModifiedSlot( 'other' ) );
		$this->assertFalse( $slots->isRemovedSlot( 'other' ) );
		$this->assertSame( $slotB, $slots->getModifiedSlot( 'other' ) );
		$this->assertSame( [ 'some', 'other' ], $slots->getModifiedRoles() );
		$this->assertSame( [], $slots->getRemovedRoles() );

		// modify slot A again
		$slots->modifySlot( $slotA );
		$this->assertArrayEquals( [ 'some', 'other' ], $slots->getModifiedRoles() );

		// remove modified slot
		$slots->removeSlot( 'some' );
		$this->assertSame( [ 'other' ], $slots->getModifiedRoles() );
		$this->assertSame( [ 'some' ], $slots->getRemovedRoles() );

		// modify removed slot
		$slots->modifySlot( $slotA );
		$this->assertArrayEquals( [ 'some', 'other' ], $slots->getModifiedRoles() );
		$this->assertSame( [], $slots->getRemovedRoles() );
	}

	public function testRemoveSlot() {
		$slots = new RevisionSlotsUpdate();

		$slotA = SlotRecord::newUnsaved( SlotRecord::MAIN, new WikitextContent( 'A' ) );
		$slots->modifySlot( $slotA );

		$this->assertSame( [ 'main' ], $slots->getModifiedRoles() );

		$slots->removeSlot( SlotRecord::MAIN );
		$slots->removeSlot( 'other' );
		$this->assertSame( [], $slots->getModifiedRoles() );
		$this->assertSame( [ 'main', 'other' ], $slots->getRemovedRoles() );
		$this->assertTrue( $slots->isRemovedSlot( SlotRecord::MAIN ) );
		$this->assertTrue( $slots->isRemovedSlot( 'other' ) );
		$this->assertFalse( $slots->isModifiedSlot( SlotRecord::MAIN ) );

		// removing the same slot again should not trigger an error
		$slots->removeSlot( SlotRecord::MAIN );

		// getting a slot after removing it should fail
		$this->setExpectedException( RevisionAccessException::class );
		$slots->getModifiedSlot( SlotRecord::MAIN );
	}

	public function testGetModifiedRoles() {
		$slots = new RevisionSlotsUpdate( [], [ 'xyz' ] );

		$this->assertSame( [], $slots->getModifiedRoles() );

		$slots->modifyContent( SlotRecord::MAIN, new WikitextContent( 'A' ) );
		$slots->modifyContent( 'foo', new WikitextContent( 'Foo' ) );
		$this->assertSame( [ 'main', 'foo' ], $slots->getModifiedRoles() );

		$slots->removeSlot( SlotRecord::MAIN );
		$this->assertSame( [ 'foo' ], $slots->getModifiedRoles() );
	}

	public function testGetRemovedRoles() {
		$slotA = SlotRecord::newUnsaved( SlotRecord::MAIN, new WikitextContent( 'A' ) );
		$slots = new RevisionSlotsUpdate( [ $slotA ] );

		$this->assertSame( [], $slots->getRemovedRoles() );

		$slots->removeSlot( SlotRecord::MAIN, new WikitextContent( 'A' ) );
		$slots->removeSlot( 'foo', new WikitextContent( 'Foo' ) );

		$this->assertSame( [ 'main', 'foo' ], $slots->getRemovedRoles() );

		$slots->modifyContent( SlotRecord::MAIN, new WikitextContent( 'A' ) );
		$this->assertSame( [ 'foo' ], $slots->getRemovedRoles() );
	}

	public function provideHasSameUpdates() {
		$fooX = SlotRecord::newUnsaved( 'x', new WikitextContent( 'Foo' ) );
		$barZ = SlotRecord::newUnsaved( 'z', new WikitextContent( 'Bar' ) );

		$a = new RevisionSlotsUpdate();
		$a->modifySlot( $fooX );
		$a->modifySlot( $barZ );
		$a->removeSlot( 'Q' );

		$a2 = new RevisionSlotsUpdate();
		$a2->modifySlot( $fooX );
		$a2->modifySlot( $barZ );
		$a2->removeSlot( 'Q' );

		$b = new RevisionSlotsUpdate();
		$b->modifySlot( $barZ );
		$b->removeSlot( 'Q' );

		$c = new RevisionSlotsUpdate();
		$c->modifySlot( $fooX );
		$c->modifySlot( $barZ );

		yield 'same instance' => [ $a, $a, true ];
		yield 'same udpates' => [ $a, $a2, true ];

		yield 'different modified' => [ $a, $b, false ];
		yield 'different removed' => [ $a, $c, false ];
	}

	/**
	 * @dataProvider provideHasSameUpdates
	 */
	public function testHasSameUpdates( RevisionSlotsUpdate $a, RevisionSlotsUpdate $b, $same ) {
		$this->assertSame( $same, $a->hasSameUpdates( $b ) );
		$this->assertSame( $same, $b->hasSameUpdates( $a ) );
	}

	/**
	 * @param string $role
	 * @param Content $content
	 * @return SlotRecord
	 */
	private function newSavedSlot( $role, Content $content ) {
		return SlotRecord::newSaved( 7, 7, 'xyz', SlotRecord::newUnsaved( $role, $content ) );
	}

	public function testApplyUpdate() {
		/** @var SlotRecord[] $parentSlots */
		$parentSlots = [
			'X' => $this->newSavedSlot( 'X', new WikitextContent( 'X' ) ),
			'Y' => $this->newSavedSlot( 'Y', new WikitextContent( 'Y' ) ),
			'Z' => $this->newSavedSlot( 'Z', new WikitextContent( 'Z' ) ),
		];
		$slots = MutableRevisionSlots::newFromParentRevisionSlots( $parentSlots );
		$update = RevisionSlotsUpdate::newFromContent( [
			'A' => new WikitextContent( 'A' ),
			'Y' => new WikitextContent( 'yyy' ),
		] );

		$update->removeSlot( 'Z' );

		$update->apply( $slots );
		$this->assertSame( [ 'X', 'Y', 'A' ], $slots->getSlotRoles() );
		$this->assertSame( $update->getModifiedSlot( 'A' ), $slots->getSlot( 'A' ) );
		$this->assertSame( $update->getModifiedSlot( 'Y' ), $slots->getSlot( 'Y' ) );
	}

}
