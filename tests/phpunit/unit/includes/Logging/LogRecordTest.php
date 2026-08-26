<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Unit\Logging;

use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\Logging\LogPage;
use MediaWiki\Logging\LogRecord;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;
use Wikimedia\Assert\ParameterAssertionException;
use Wikimedia\Assert\PreconditionException;

/**
 * @covers \MediaWiki\Logging\LogRecord
 */
class LogRecordTest extends MediaWikiUnitTestCase {

	private function newLogRecord(
		string $comment = '',
		array $params = [],
		int $deleted = 0
	): LogRecord {
		return new LogRecord(
			123,
			'delete',
			'restore',
			new UserIdentityValue( 7, 'Performer' ),
			new PageReferenceValue( NS_MAIN, 'Test', WikiAwareEntity::LOCAL ),
			'20200909001122',
			$comment,
			$params,
			$deleted
		);
	}

	public function testGetters() {
		$performer = new UserIdentityValue( 7, 'Performer' );
		$target = new PageReferenceValue( NS_MAIN, 'Test', WikiAwareEntity::LOCAL );
		$params = [ '4::color' => 'blue' ];

		$record = new LogRecord(
			123,
			'delete',
			'restore',
			$performer,
			$target,
			'20200909001122',
			'Some reason',
			$params,
			LogPage::DELETED_COMMENT,
			1717,
			true
		);

		$this->assertSame( 123, $record->getId() );
		$this->assertSame( 'delete', $record->getType() );
		$this->assertSame( 'restore', $record->getSubtype() );
		$this->assertSame( 'delete/restore', $record->getFullType() );
		$this->assertSame( $performer, $record->getPerformer() );
		$this->assertSame( $target, $record->getTarget() );
		$this->assertSame( '20200909001122', $record->getTimestamp() );
		$this->assertSame( 'Some reason', $record->getComment() );
		$this->assertSame( $params, $record->getParameters() );
		$this->assertSame( LogPage::DELETED_COMMENT, $record->getDeleted() );
		$this->assertSame( 1717, $record->getAssociatedRevId() );
		$this->assertTrue( $record->isLegacy() );
		$this->assertSame( WikiAwareEntity::LOCAL, $record->getWikiId() );
	}

	public function testDefaults() {
		$record = $this->newLogRecord();

		$this->assertSame( '', $record->getComment() );
		$this->assertSame( [], $record->getParameters() );
		$this->assertSame( 0, $record->getDeleted() );
		$this->assertNull( $record->getAssociatedRevId() );
		$this->assertFalse( $record->isLegacy() );
		$this->assertSame( WikiAwareEntity::LOCAL, $record->getWikiId() );
	}

	/**
	 * @dataProvider provideNoAssociatedRevId
	 */
	public function testNoAssociatedRevIdIsNormalizedToNull( ?int $associatedRevId ) {
		$record = new LogRecord(
			123,
			'delete',
			'restore',
			new UserIdentityValue( 7, 'Performer' ),
			new PageReferenceValue( NS_MAIN, 'Test', WikiAwareEntity::LOCAL ),
			'20200909001122',
			'',
			[],
			0,
			$associatedRevId
		);

		$this->assertNull( $record->getAssociatedRevId() );
	}

	public static function provideNoAssociatedRevId() {
		yield 'null' => [ null ];
		yield 'zero' => [ 0 ];
	}

	public function testTimestampIsNormalized() {
		$record = new LogRecord(
			123,
			'delete',
			'restore',
			new UserIdentityValue( 7, 'Performer' ),
			new PageReferenceValue( NS_MAIN, 'Test', WikiAwareEntity::LOCAL ),
			'2020-09-09T00:11:22Z'
		);

		$this->assertSame( '20200909001122', $record->getTimestamp() );
	}

	public function testIsDeleted() {
		$record = $this->newLogRecord(
			'Some reason',
			[],
			LogPage::DELETED_COMMENT | LogPage::DELETED_RESTRICTED
		);

		$this->assertTrue( $record->isDeleted( LogPage::DELETED_COMMENT ) );
		$this->assertTrue( $record->isDeleted( LogPage::DELETED_RESTRICTED ) );
		$this->assertFalse( $record->isDeleted( LogPage::DELETED_ACTION ) );
		$this->assertFalse( $record->isDeleted( LogPage::DELETED_USER ) );
	}

	public function testForeignWiki() {
		$record = new LogRecord(
			123,
			'delete',
			'restore',
			new UserIdentityValue( 7, 'Performer', 'acmewiki' ),
			new PageReferenceValue( NS_MAIN, 'Test', 'acmewiki' ),
			'20200909001122',
			'Some reason',
			[],
			0,
			1717,
			false,
			'acmewiki'
		);

		$this->assertSame( 'acmewiki', $record->getWikiId() );
		$this->assertSame( 123, $record->getId( 'acmewiki' ) );
		$this->assertSame( 1717, $record->getAssociatedRevId( 'acmewiki' ) );

		$this->expectException( PreconditionException::class );
		$record->getId();
	}

	public function testConstructorRejectsNegativeId() {
		$this->expectException( ParameterAssertionException::class );
		new LogRecord(
			-1,
			'delete',
			'restore',
			new UserIdentityValue( 7, 'Performer' ),
			new PageReferenceValue( NS_MAIN, 'Test', WikiAwareEntity::LOCAL ),
			'20200909001122'
		);
	}

	public function testConstructorRejectsForeignPerformer() {
		$this->expectException( ParameterAssertionException::class );
		new LogRecord(
			123,
			'delete',
			'restore',
			new UserIdentityValue( 7, 'Performer', 'acmewiki' ),
			new PageReferenceValue( NS_MAIN, 'Test', WikiAwareEntity::LOCAL ),
			'20200909001122'
		);
	}

	public function testConstructorRejectsForeignTarget() {
		$this->expectException( ParameterAssertionException::class );
		new LogRecord(
			123,
			'delete',
			'restore',
			new UserIdentityValue( 7, 'Performer' ),
			new PageReferenceValue( NS_MAIN, 'Test', 'acmewiki' ),
			'20200909001122'
		);
	}
}
