<?php

namespace MediaWiki\Tests\Storage;

use MediaWiki\Storage\SlotRecord;
use MediaWikiTestCase;
use RuntimeException;
use Wikimedia\Assert\ParameterTypeException;
use WikitextContent;

/**
 * @covers \MediaWiki\Storage\SlotRecord
 */
class SlotRecordTest extends MediaWikiTestCase {

	public function provideAContent() {
		yield [ new WikitextContent( 'A' ) ];
		yield [
			function ( SlotRecord $slotRecord ) {
				if ( $slotRecord->getAddress() === 'tt:456' ) {
					return new WikitextContent( 'A' );
				}
				throw new RuntimeException( 'Got Wrong SlotRecord for callback' );
			},
		];
	}

	/**
	 * @dataProvider provideAContent
	 */
	public function testValidConstruction( $content ) {
		$row = (object)[
			'cont_size' => '1',
			'cont_sha1' => 'someHash',
			'cont_address' => 'tt:456',
			'model_name' => 'aModelname',
			'slot_revision' => '2',
			'format_name' => 'someFormatName',
			'role_name' => 'myRole',
			'slot_inherited' => '99'
		];

		$record = new SlotRecord( $row, $content );

		$this->assertSame( 'A', $record->getContent()->getNativeData() );
		$this->assertSame( 1, $record->getSize() );
		$this->assertSame( 'someHash', $record->getSha1() );
		$this->assertSame( 'aModelname', $record->getModel() );
		$this->assertSame( 2, $record->getRevision() );
		$this->assertSame( 'tt:456', $record->getAddress() );
		$this->assertSame( 'someFormatName', $record->getFormat() );
		$this->assertSame( 'myRole', $record->getRole() );
		$this->assertTrue( $record->hasAddress() );
		$this->assertTrue( $record->hasRevision() );
		$this->assertTrue( $record->isInherited() );
	}

	public function provideInvalidConstruction() {
		yield 'both null' => [ null, null ];
		yield 'null row' => [ null, new WikitextContent( 'A' ) ];
		yield 'array row' => [ null, new WikitextContent( 'A' ) ];
		yield 'null content' => [ (object)[], null ];
	}

	/**
	 * @dataProvider provideInvalidConstruction
	 */
	public function testInvalidConstruction( $row, $content ) {
		$this->setExpectedException( ParameterTypeException::class );
		new SlotRecord( $row, $content );
	}

	public function testHasAddress_false() {
		$record = new SlotRecord( (object)[], new WikitextContent( 'A' ) );
		$this->assertFalse( $record->hasAddress() );
	}

	public function testHasRevision_false() {
		$record = new SlotRecord( (object)[], new WikitextContent( 'A' ) );
		$this->assertFalse( $record->hasRevision() );
	}

	public function testInInherited_false() {
		// TODO unskip me once fixed.
		$this->markTestSkipped( 'Should probably return false, needs fixing?' );
		$record = new SlotRecord( (object)[], new WikitextContent( 'A' ) );
		$this->assertFalse( $record->isInherited() );
	}

}
