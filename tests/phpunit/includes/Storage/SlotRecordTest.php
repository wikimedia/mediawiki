<?php

namespace MediaWiki\Tests\Storage;

use InvalidArgumentException;
use LogicException;
use MediaWiki\Storage\IncompleteRevisionException;
use MediaWiki\Storage\SlotRecord;
use MediaWiki\Storage\SuppressedDataException;
use MediaWikiTestCase;
use WikitextContent;

/**
 * @covers \MediaWiki\Storage\SlotRecord
 */
class SlotRecordTest extends MediaWikiTestCase {

	private function makeRow( $data = [] ) {
		$data = $data + [
			'slot_id' => 1234,
			'slot_content_id' => 33,
			'content_size' => '5',
			'content_sha1' => 'someHash',
			'content_address' => 'tt:456',
			'model_name' => CONTENT_MODEL_WIKITEXT,
			'format_name' => CONTENT_FORMAT_WIKITEXT,
			'slot_revision_id' => '2',
			'slot_origin' => '1',
			'role_name' => 'myRole',
		];
		return (object)$data;
	}

	public function testCompleteConstruction() {
		$row = $this->makeRow();
		$record = new SlotRecord( $row, new WikitextContent( 'A' ) );

		$this->assertTrue( $record->hasAddress() );
		$this->assertTrue( $record->hasRevision() );
		$this->assertTrue( $record->isInherited() );
		$this->assertSame( 'A', $record->getContent()->getNativeData() );
		$this->assertSame( 5, $record->getSize() );
		$this->assertSame( 'someHash', $record->getSha1() );
		$this->assertSame( CONTENT_MODEL_WIKITEXT, $record->getModel() );
		$this->assertSame( 2, $record->getRevision() );
		$this->assertSame( 1, $record->getOrigin() );
		$this->assertSame( 'tt:456', $record->getAddress() );
		$this->assertSame( 33, $record->getContentId() );
		$this->assertSame( CONTENT_FORMAT_WIKITEXT, $record->getFormat() );
		$this->assertSame( 'myRole', $record->getRole() );
	}

	public function testConstructionDeferred() {
		$row = $this->makeRow( [
			'content_size' => null, // to be computed
			'content_sha1' => null, // to be computed
			'format_name' => function () {
				return CONTENT_FORMAT_WIKITEXT;
			},
			'slot_revision_id' => '2',
			'slot_origin' => '2',
		] );

		$content = function () {
			return new WikitextContent( 'A' );
		};

		$record = new SlotRecord( $row, $content );

		$this->assertTrue( $record->hasAddress() );
		$this->assertTrue( $record->hasRevision() );
		$this->assertFalse( $record->isInherited() );
		$this->assertSame( 'A', $record->getContent()->getNativeData() );
		$this->assertSame( 1, $record->getSize() );
		$this->assertNotNull( $record->getSha1() );
		$this->assertSame( CONTENT_MODEL_WIKITEXT, $record->getModel() );
		$this->assertSame( 2, $record->getRevision() );
		$this->assertSame( 2, $record->getRevision() );
		$this->assertSame( 'tt:456', $record->getAddress() );
		$this->assertSame( 33, $record->getContentId() );
		$this->assertSame( CONTENT_FORMAT_WIKITEXT, $record->getFormat() );
		$this->assertSame( 'myRole', $record->getRole() );
	}

	public function testNewUnsaved() {
		$record = SlotRecord::newUnsaved( 'myRole', new WikitextContent( 'A' ) );

		$this->assertFalse( $record->hasAddress() );
		$this->assertFalse( $record->hasRevision() );
		$this->assertFalse( $record->isInherited() );
		$this->assertSame( 'A', $record->getContent()->getNativeData() );
		$this->assertSame( 1, $record->getSize() );
		$this->assertNotNull( $record->getSha1() );
		$this->assertSame( CONTENT_MODEL_WIKITEXT, $record->getModel() );
		$this->assertSame( 'myRole', $record->getRole() );
	}

	public function provideInvalidConstruction() {
		yield 'both null' => [ null, null ];
		yield 'null row' => [ null, new WikitextContent( 'A' ) ];
		yield 'array row' => [ [], new WikitextContent( 'A' ) ];
		yield 'empty row' => [ (object)[], new WikitextContent( 'A' ) ];
		yield 'null content' => [ (object)[], null ];
	}

	/**
	 * @dataProvider provideInvalidConstruction
	 */
	public function testInvalidConstruction( $row, $content ) {
		$this->setExpectedException( InvalidArgumentException::class );
		new SlotRecord( $row, $content );
	}

	public function testGetContentId_fails() {
		$record = SlotRecord::newUnsaved( 'main', new WikitextContent( 'A' ) );
		$this->setExpectedException( IncompleteRevisionException::class );

		$record->getContentId();
	}

	public function testGetAddress_fails() {
		$record = SlotRecord::newUnsaved( 'main', new WikitextContent( 'A' ) );
		$this->setExpectedException( IncompleteRevisionException::class );

		$record->getAddress();
	}

	public function provideIncomplete() {
		$unsaved = SlotRecord::newUnsaved( 'main', new WikitextContent( 'A' ) );
		yield 'unsaved' => [ $unsaved ];

		$parent = new SlotRecord( $this->makeRow(), new WikitextContent( 'A' ) );
		$inherited = SlotRecord::newInherited( $parent );
		yield 'inherited' => [ $inherited ];
	}

	/**
	 * @dataProvider provideIncomplete
	 */
	public function testGetRevision_fails( SlotRecord $record ) {
		$record = SlotRecord::newUnsaved( 'main', new WikitextContent( 'A' ) );
		$this->setExpectedException( IncompleteRevisionException::class );

		$record->getRevision();
	}

	/**
	 * @dataProvider provideIncomplete
	 */
	public function testGetOrigin_fails( SlotRecord $record ) {
		$record = SlotRecord::newUnsaved( 'main', new WikitextContent( 'A' ) );
		$this->setExpectedException( IncompleteRevisionException::class );

		$record->getOrigin();
	}

	public function provideHashStability() {
		yield [ '', 'phoiac9h4m842xq45sp7s6u21eteeq1' ];
		yield [ 'Lorem ipsum', 'hcr5u40uxr81d3nx89nvwzclfz6r9c5' ];
	}

	/**
	 * @dataProvider provideHashStability
	 */
	public function testHashStability( $text, $hash ) {
		// Changing the output of the hash function will break things horribly!

		$this->assertSame( $hash, SlotRecord::base36Sha1( $text ) );

		$record = SlotRecord::newUnsaved( 'main', new WikitextContent( $text ) );
		$this->assertSame( $hash, $record->getSha1() );
	}

	public function testNewWithSuppressedContent() {
		$input = new SlotRecord( $this->makeRow(), new WikitextContent( 'A' ) );
		$output = SlotRecord::newWithSuppressedContent( $input );

		$this->setExpectedException( SuppressedDataException::class );
		$output->getContent();
	}

	public function testNewInherited() {
		$row = $this->makeRow( [ 'slot_revision_id' => 7, 'slot_origin' => 7 ] );
		$parent = new SlotRecord( $row, new WikitextContent( 'A' ) );

		// This would happen while doing an edit, before saving revision meta-data.
		$inherited = SlotRecord::newInherited( $parent );

		$this->assertSame( $parent->getContentId(), $inherited->getContentId() );
		$this->assertSame( $parent->getAddress(), $inherited->getAddress() );
		$this->assertSame( $parent->getContent(), $inherited->getContent() );
		$this->assertTrue( $inherited->isInherited() );
		$this->assertFalse( $inherited->hasRevision() );

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

		// make sure we didn't mess with the internal state of $parent or $inherited
		$this->assertSame( 7, $parent->getRevision() );
		$this->assertFalse( $inherited->hasRevision() );
	}

	public function testNewSaved() {
		// This would happen while doing an edit, before saving revision meta-data.
		$unsaved = SlotRecord::newUnsaved( 'main', new WikitextContent( 'A' ) );

		// This would happen while doing an edit, after saving the revision meta-data
		// and content meta-data.
		$saved = SlotRecord::newSaved( 10, 20, 'theNewAddress', $unsaved );
		$this->assertFalse( $saved->isInherited() );
		$this->assertTrue( $saved->hasRevision() );
		$this->assertTrue( $saved->hasAddress() );
		$this->assertSame( 'theNewAddress', $saved->getAddress() );
		$this->assertSame( 20, $saved->getContentId() );
		$this->assertSame( 'A', $saved->getContent()->getNativeData() );
		$this->assertSame( 10, $saved->getRevision() );
		$this->assertSame( 10, $saved->getOrigin() );

		// make sure we didn't mess with the internal state of $unsaved
		$this->assertFalse( $unsaved->hasAddress() );
		$this->assertFalse( $unsaved->hasRevision() );
	}

	public function provideNewSaved_LogicException() {
		$freshRow = $this->makeRow( [
			'content_id' => 10,
			'content_address' => 'address:1',
			'slot_origin' => 1,
			'slot_revision_id' => 1,
		] );

		$freshSlot = new SlotRecord( $freshRow, new WikitextContent( 'A' ) );
		yield 'mismatching address' => [ 1, 10, 'address:BAD', $freshSlot ];
		yield 'mismatching revision' => [ 5, 10, 'address:1', $freshSlot ];
		yield 'mismatching content ID' => [ 1, 17, 'address:1', $freshSlot ];

		$inheritedRow = $this->makeRow( [
			'content_id' => null,
			'content_address' => null,
			'slot_origin' => 0,
			'slot_revision_id' => 1,
		] );

		$inheritedSlot = new SlotRecord( $inheritedRow, new WikitextContent( 'A' ) );
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
		$this->setExpectedException( LogicException::class );
		SlotRecord::newSaved( $revisionId, $contentId, $contentAddress, $protoSlot );
	}

	public function provideNewSaved_InvalidArgumentException() {
		$unsaved = SlotRecord::newUnsaved( 'main', new WikitextContent( 'A' ) );

		yield 'bad revision id' => [ 'xyzzy', 5, 'address', $unsaved ];
		yield 'bad content id' => [ 7, 'xyzzy', 'address', $unsaved ];
		yield 'bad content address' => [ 7, 5, 77, $unsaved ];
	}

	/**
	 * @dataProvider provideNewSaved_InvalidArgumentException
	 */
	public function testNewSaved_InvalidArgumentException(
		$revisionId,
		$contentId,
		$contentAddress,
		SlotRecord $protoSlot
	) {
		$this->setExpectedException( InvalidArgumentException::class );
		SlotRecord::newSaved( $revisionId, $contentId, $contentAddress, $protoSlot );
	}

}
