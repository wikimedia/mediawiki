<?php

namespace MediaWiki\Tests\Revision;

use DummyContentForTesting;
use InvalidArgumentException;
use LogicException;
use MediaWiki\Revision\IncompleteRevisionException;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SuppressedDataException;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Revision\SlotRecord
 */
class SlotRecordTest extends MediaWikiUnitTestCase {

	private static function makeRow( $data = [] ) {
		$data = $data + [
			'slot_id' => 1234,
			'slot_content_id' => 33,
			'content_size' => '5',
			'content_sha1' => 'someHash',
			'content_address' => 'tt:456',
			'model_name' => DummyContentForTesting::MODEL_ID,
			'format_name' => CONTENT_FORMAT_WIKITEXT,
			'slot_revision_id' => '2',
			'slot_origin' => '1',
			'role_name' => 'myRole',
		];
		return (object)$data;
	}

	public function testCompleteConstruction() {
		$row = self::makeRow();
		$record = new SlotRecord( $row, new DummyContentForTesting( 'A' ) );

		$this->assertTrue( $record->hasAddress() );
		$this->assertTrue( $record->hasContentId() );
		$this->assertTrue( $record->hasRevision() );
		$this->assertTrue( $record->isInherited() );
		$this->assertSame( 'A', $record->getContent()->getNativeData() );
		$this->assertSame( 5, $record->getSize() );
		$this->assertSame( 'someHash', $record->getSha1() );
		$this->assertSame( DummyContentForTesting::MODEL_ID, $record->getModel() );
		$this->assertSame( 2, $record->getRevision() );
		$this->assertSame( 1, $record->getOrigin() );
		$this->assertSame( 'tt:456', $record->getAddress() );
		$this->assertSame( 33, $record->getContentId() );
		$this->assertSame( CONTENT_FORMAT_WIKITEXT, $record->getFormat() );
		$this->assertSame( 'myRole', $record->getRole() );
		$this->assertFalse( $record->isDerived() );
	}

	public function testConstructionDeferred() {
		$row = self::makeRow( [
			'content_size' => null, // to be computed
			'content_sha1' => null, // to be computed
			'format_name' => static function () {
				return CONTENT_FORMAT_WIKITEXT;
			},
			'slot_revision_id' => '2',
			'slot_origin' => '2',
			'slot_content_id' => static function () {
				return null;
			},
		] );

		$content = static function () {
			return new DummyContentForTesting( 'A' );
		};

		$record = new SlotRecord( $row, $content );

		$this->assertTrue( $record->hasAddress() );
		$this->assertTrue( $record->hasRevision() );
		$this->assertFalse( $record->hasContentId() );
		$this->assertFalse( $record->isInherited() );
		$this->assertSame( 'A', $record->getContent()->getNativeData() );
		$this->assertSame( 1, $record->getSize() );
		$this->assertNotEmpty( $record->getSha1() );
		$this->assertSame( DummyContentForTesting::MODEL_ID, $record->getModel() );
		$this->assertSame( 2, $record->getRevision() );
		$this->assertSame( 2, $record->getRevision() );
		$this->assertSame( 'tt:456', $record->getAddress() );
		$this->assertSame( CONTENT_FORMAT_WIKITEXT, $record->getFormat() );
		$this->assertSame( 'myRole', $record->getRole() );
		$this->assertFalse( $record->isDerived() );
	}

	public function testNewUnsaved() {
		$record = SlotRecord::newUnsaved( 'myRole', new DummyContentForTesting( 'A' ) );

		$this->assertFalse( $record->hasAddress() );
		$this->assertFalse( $record->hasContentId() );
		$this->assertFalse( $record->hasRevision() );
		$this->assertFalse( $record->isInherited() );
		$this->assertFalse( $record->hasOrigin() );
		$this->assertSame( 'A', $record->getContent()->getNativeData() );
		$this->assertSame( 1, $record->getSize() );
		$this->assertNotEmpty( $record->getSha1() );
		$this->assertSame( DummyContentForTesting::MODEL_ID, $record->getModel() );
		$this->assertSame( 'myRole', $record->getRole() );
		$this->assertFalse( $record->isDerived() );
	}

	public static function provideInvalidConstruction() {
		yield 'empty row' => [ (object)[], new DummyContentForTesting( 'A' ) ];
		yield 'null content' => [ (object)[], null ];
	}

	/**
	 * @dataProvider provideInvalidConstruction
	 */
	public function testInvalidConstruction( $row, $content ) {
		$this->expectException( InvalidArgumentException::class );
		new SlotRecord( $row, $content );
	}

	public function testGetContentId_fails() {
		$record = SlotRecord::newUnsaved( SlotRecord::MAIN, new DummyContentForTesting( 'A' ) );
		$this->expectException( IncompleteRevisionException::class );

		$record->getContentId();
	}

	public function testGetAddress_fails() {
		$record = SlotRecord::newUnsaved( SlotRecord::MAIN, new DummyContentForTesting( 'A' ) );
		$this->expectException( IncompleteRevisionException::class );

		$record->getAddress();
	}

	public static function provideIncomplete() {
		$unsaved = SlotRecord::newUnsaved( SlotRecord::MAIN, new DummyContentForTesting( 'A' ) );
		yield 'unsaved' => [ $unsaved ];

		$parent = new SlotRecord( self::makeRow(), new DummyContentForTesting( 'A' ) );
		$inherited = SlotRecord::newInherited( $parent );
		yield 'inherited' => [ $inherited ];
	}

	/**
	 * @dataProvider provideIncomplete
	 */
	public function testGetRevision_fails( SlotRecord $record ) {
		$record = SlotRecord::newUnsaved( SlotRecord::MAIN, new DummyContentForTesting( 'A' ) );
		$this->expectException( IncompleteRevisionException::class );

		$record->getRevision();
	}

	/**
	 * @dataProvider provideIncomplete
	 */
	public function testGetOrigin_fails( SlotRecord $record ) {
		$record = SlotRecord::newUnsaved( SlotRecord::MAIN, new DummyContentForTesting( 'A' ) );
		$this->expectException( IncompleteRevisionException::class );

		$record->getOrigin();
	}

	public static function provideHashStability() {
		yield [ '', 'phoiac9h4m842xq45sp7s6u21eteeq1' ];
		yield [ 'Lorem ipsum', 'hcr5u40uxr81d3nx89nvwzclfz6r9c5' ];
	}

	/**
	 * @dataProvider provideHashStability
	 */
	public function testHashStability( $text, $hash ) {
		// Changing the output of the hash function will break things horribly!

		$this->assertSame( $hash, SlotRecord::base36Sha1( $text ) );

		$record = SlotRecord::newUnsaved( SlotRecord::MAIN, new DummyContentForTesting( $text ) );
		$this->assertSame( $hash, $record->getSha1() );
	}

	public function testHashComputed() {
		$row = self::makeRow();
		$row->content_sha1 = '';

		$rec = new SlotRecord( $row, new DummyContentForTesting( 'A' ) );
		$this->assertNotEmpty( $rec->getSha1() );
	}

	public function testNewWithSuppressedContent() {
		$input = new SlotRecord( self::makeRow(), new DummyContentForTesting( 'A' ) );
		$output = SlotRecord::newWithSuppressedContent( $input );

		$this->expectException( SuppressedDataException::class );
		$output->getContent();
	}

	public function testNewInherited() {
		$row = self::makeRow( [ 'slot_revision_id' => 7, 'slot_origin' => 7 ] );
		$parent = new SlotRecord( $row, new DummyContentForTesting( 'A' ) );

		// This would happen while doing an edit, before saving revision meta-data.
		$inherited = SlotRecord::newInherited( $parent );

		$this->assertSame( $parent->getContentId(), $inherited->getContentId() );
		$this->assertSame( $parent->getAddress(), $inherited->getAddress() );
		$this->assertSame( $parent->getContent(), $inherited->getContent() );
		$this->assertTrue( $inherited->isInherited() );
		$this->assertTrue( $inherited->hasOrigin() );
		$this->assertFalse( $inherited->hasRevision() );
		$this->assertFalse( $inherited->isDerived() );

		// make sure we didn't mess with the internal state of $parent
		$this->assertFalse( $parent->isInherited() );
		$this->assertSame( 7, $parent->getRevision() );

		// This would happen while doing an edit, after saving the revision meta-data
		// and content meta-data.
		$saved = SlotRecord::newSaved(
			10,
			$inherited->getContentId(),
			$inherited->getAddress(),
			$inherited
		);
		$this->assertSame( $parent->getContentId(), $saved->getContentId() );
		$this->assertSame( $parent->getAddress(), $saved->getAddress() );
		$this->assertSame( $parent->getContent(), $saved->getContent() );
		$this->assertTrue( $saved->isInherited() );
		$this->assertTrue( $saved->hasRevision() );
		$this->assertSame( 10, $saved->getRevision() );
		$this->assertFalse( $saved->isDerived() );

		// make sure we didn't mess with the internal state of $parent or $inherited
		$this->assertSame( 7, $parent->getRevision() );
		$this->assertFalse( $inherited->hasRevision() );
	}

	public function testNewSaved() {
		// This would happen while doing an edit, before saving revision meta-data.
		$unsaved = SlotRecord::newUnsaved( SlotRecord::MAIN, new DummyContentForTesting( 'A' ) );

		// This would happen while doing an edit, after saving the revision meta-data
		// and content meta-data.
		$saved = SlotRecord::newSaved( 10, 20, 'theNewAddress', $unsaved );
		$this->assertFalse( $saved->isInherited() );
		$this->assertTrue( $saved->hasOrigin() );
		$this->assertTrue( $saved->hasRevision() );
		$this->assertTrue( $saved->hasAddress() );
		$this->assertTrue( $saved->hasContentId() );
		$this->assertSame( 'theNewAddress', $saved->getAddress() );
		$this->assertSame( 20, $saved->getContentId() );
		$this->assertSame( 'A', $saved->getContent()->getNativeData() );
		$this->assertSame( 10, $saved->getRevision() );
		$this->assertSame( 10, $saved->getOrigin() );
		$this->assertFalse( $saved->isDerived() );

		// make sure we didn't mess with the internal state of $unsaved
		$this->assertFalse( $unsaved->hasAddress() );
		$this->assertFalse( $unsaved->hasContentId() );
		$this->assertFalse( $unsaved->hasRevision() );
		$this->assertFalse( $unsaved->isDerived() );
	}

	public static function provideNewSaved_LogicException() {
		$freshRow = self::makeRow( [
			'content_id' => 10,
			'content_address' => 'address:1',
			'slot_origin' => 1,
			'slot_revision_id' => 1,
		] );

		$freshSlot = new SlotRecord( $freshRow, new DummyContentForTesting( 'A' ) );
		yield 'mismatching address' => [ 1, 10, 'address:BAD', $freshSlot ];
		yield 'mismatching revision' => [ 5, 10, 'address:1', $freshSlot ];
		yield 'mismatching content ID' => [ 1, 17, 'address:1', $freshSlot ];

		$inheritedRow = self::makeRow( [
			'content_id' => null,
			'content_address' => null,
			'slot_origin' => 0,
			'slot_revision_id' => 1,
		] );

		$inheritedSlot = new SlotRecord( $inheritedRow, new DummyContentForTesting( 'A' ) );
		yield 'inherited, but no address' => [ 1, 10, 'address:2', $inheritedSlot ];
	}

	/**
	 * @dataProvider provideNewSaved_LogicException
	 */
	public function testNewSaved_LogicException(
		$revisionId,
		$contentId,
		$contentAddress,
		SlotRecord $protoSlot
	) {
		$this->expectException( LogicException::class );
		SlotRecord::newSaved( $revisionId, $contentId, $contentAddress, $protoSlot );
	}

	public static function provideHasSameContent() {
		$fail = static function () {
			self::fail( 'There should be no need to actually load the content.' );
		};

		$a100a1 = new SlotRecord(
			self::makeRow(
				[
					'model_name' => 'A',
					'content_size' => 100,
					'content_sha1' => 'hash-a',
					'content_address' => 'xxx:a1',
				]
			),
			$fail
		);
		$a100a1b = new SlotRecord(
			self::makeRow(
				[
					'model_name' => 'A',
					'content_size' => 100,
					'content_sha1' => 'hash-a',
					'content_address' => 'xxx:a1',
				]
			),
			$fail
		);
		$a100null = new SlotRecord(
			self::makeRow(
				[
					'model_name' => 'A',
					'content_size' => 100,
					'content_sha1' => 'hash-a',
					'content_address' => null,
				]
			),
			$fail
		);
		$a100a2 = new SlotRecord(
			self::makeRow(
				[
					'model_name' => 'A',
					'content_size' => 100,
					'content_sha1' => 'hash-a',
					'content_address' => 'xxx:a2',
				]
			),
			$fail
		);
		$b100a1 = new SlotRecord(
			self::makeRow(
				[
					'model_name' => 'B',
					'content_size' => 100,
					'content_sha1' => 'hash-a',
					'content_address' => 'xxx:a1',
				]
			),
			$fail
		);
		$a200a1 = new SlotRecord(
			self::makeRow(
				[
					'model_name' => 'A',
					'content_size' => 200,
					'content_sha1' => 'hash-a',
					'content_address' => 'xxx:a2',
				]
			),
			$fail
		);
		$a100x1 = new SlotRecord(
			self::makeRow(
				[
					'model_name' => 'A',
					'content_size' => 100,
					'content_sha1' => 'hash-x',
					'content_address' => 'xxx:x1',
				]
			),
			$fail
		);

		yield 'same instance' => [ $a100a1, $a100a1, true ];
		yield 'no address' => [ $a100a1, $a100null, true ];
		yield 'same address' => [ $a100a1, $a100a1b, true ];
		yield 'different address' => [ $a100a1, $a100a2, true ];
		yield 'different model' => [ $a100a1, $b100a1, false ];
		yield 'different size' => [ $a100a1, $a200a1, false ];
		yield 'different hash' => [ $a100a1, $a100x1, false ];
	}

	/**
	 * @dataProvider provideHasSameContent
	 */
	public function testHasSameContent( SlotRecord $a, SlotRecord $b, $sameContent ) {
		$this->assertSame( $sameContent, $a->hasSameContent( $b ) );
		$this->assertSame( $sameContent, $b->hasSameContent( $a ) );
	}

	public function testNewDerived() {
		$record = SlotRecord::newDerived( 'derivedslot', new DummyContentForTesting( 'A' ) );

		$this->assertFalse( $record->hasAddress() );
		$this->assertFalse( $record->hasContentId() );
		$this->assertFalse( $record->hasRevision() );
		$this->assertFalse( $record->isInherited() );
		$this->assertFalse( $record->hasOrigin() );
		$this->assertSame( 'A', $record->getContent()->getNativeData() );
		$this->assertSame( 1, $record->getSize() );
		$this->assertNotEmpty( $record->getSha1() );
		$this->assertSame( DummyContentForTesting::MODEL_ID, $record->getModel() );
		$this->assertSame( 'derivedslot', $record->getRole() );
		$this->assertTrue( $record->isDerived() );
	}

	public function testCopyDerived() {
		$unsaved = SlotRecord::newDerived( 'derivedslot', new DummyContentForTesting( 'A' ) );
		$saved = SlotRecord::newSaved( 10, 20, 'theNewAddress', $unsaved );

		$this->assertFalse( $saved->isInherited() );
		$this->assertTrue( $saved->hasOrigin() );
		$this->assertTrue( $saved->hasRevision() );
		$this->assertTrue( $saved->hasAddress() );
		$this->assertTrue( $saved->hasContentId() );
		$this->assertSame( 'theNewAddress', $saved->getAddress() );
		$this->assertSame( 20, $saved->getContentId() );
		$this->assertSame( 'A', $saved->getContent()->getNativeData() );
		$this->assertSame( 10, $saved->getRevision() );
		$this->assertSame( 10, $saved->getOrigin() );
		$this->assertTrue( $saved->isDerived() );

		// make sure we didn't mess with the internal state of $unsaved
		$this->assertFalse( $unsaved->hasAddress() );
		$this->assertFalse( $unsaved->hasContentId() );
		$this->assertFalse( $unsaved->hasRevision() );
		$this->assertTrue( $unsaved->isDerived() );
	}
}
