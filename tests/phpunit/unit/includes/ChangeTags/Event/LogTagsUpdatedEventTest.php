<?php

namespace MediaWiki\Tests\Unit\ChangeTags\Event;

use MediaWiki\ChangeTags\Event\EntityTagsUpdatedEvent;
use MediaWiki\ChangeTags\Event\LogTagsUpdatedEvent;
use MediaWiki\Logging\LogRecord;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\ChangeTags\Event\LogTagsUpdatedEvent
 */
class LogTagsUpdatedEventTest extends MediaWikiUnitTestCase {

	/**
	 * Returns the tag data for a tag ID and tag params.
	 *
	 * @param int $id
	 * @param string|null $params
	 * @return array{ id: int, params: ?string }
	 */
	private static function tagData( int $id, ?string $params = null ): array {
		return [ 'id' => $id, 'params' => $params ];
	}

	private function newLogRecord( int $id ): LogRecord {
		return new LogRecord(
			$id,
			'delete',
			'restore',
			new UserIdentityValue( 9, 'Deleter' ),
			PageReferenceValue::localReference( NS_MAIN, 'Testing' ),
			'20260828000000'
		);
	}

	public function testConstructionAndAccessors(): void {
		$logRecord = $this->newLogRecord( 789 );
		$performer = new UserIdentityValue( 7, 'Tagger' );

		$event = new LogTagsUpdatedEvent(
			$logRecord,
			[ 'removed' => self::tagData( 2 ), 'kept' => self::tagData( 3 ) ],
			[ 'kept' => self::tagData( 3 ), 'added' => self::tagData( 1, '{"foo":"bar"}' ) ],
			'{"foo":"bar"}',
			$performer,
			'20260828000000'
		);

		$this->assertSame( $logRecord, $event->getLogRecord() );
		$this->assertSame( 789, $event->getLogId() );

		// Inherited state
		$this->assertSame(
			[ 'added' => self::tagData( 1, '{"foo":"bar"}' ) ],
			$event->getAddedPublicAndPrivateTags()
		);
		$this->assertSame(
			[ 'removed' => self::tagData( 2 ) ],
			$event->getRemovedPublicAndPrivateTags()
		);
		$this->assertSame(
			[ 'removed' => self::tagData( 2 ), 'kept' => self::tagData( 3 ) ],
			$event->getPublicAndPrivateTagsBefore()
		);
		$this->assertSame(
			[ 'kept' => self::tagData( 3 ), 'added' => self::tagData( 1, '{"foo":"bar"}' ) ],
			$event->getPublicAndPrivateTagsAfter()
		);
		$this->assertSame( '{"foo":"bar"}', $event->getParams() );
		$this->assertSame( $performer, $event->getPerformer() );
	}

	public function testIsCompatibleWithBaseType(): void {
		$event = new LogTagsUpdatedEvent( $this->newLogRecord( 789 ), [], [] );

		$this->assertSame( LogTagsUpdatedEvent::TYPE, $event->getEventType() );
		$this->assertSame(
			[
				LogTagsUpdatedEvent::ANY,
				EntityTagsUpdatedEvent::TYPE,
				LogTagsUpdatedEvent::TYPE,
			],
			$event->getEventTypeChain()
		);
	}

}
