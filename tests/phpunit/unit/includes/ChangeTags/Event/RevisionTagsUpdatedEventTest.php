<?php

namespace MediaWiki\Tests\Unit\ChangeTags\Event;

use MediaWiki\ChangeTags\Event\EntityTagsUpdatedEvent;
use MediaWiki\ChangeTags\Event\RevisionTagsUpdatedEvent;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;
use Wikimedia\Assert\ParameterAssertionException;

/**
 * @covers \MediaWiki\ChangeTags\Event\RevisionTagsUpdatedEvent
 */
class RevisionTagsUpdatedEventTest extends MediaWikiUnitTestCase {

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

	private function newRevisionRecord( ?int $id ): RevisionRecord {
		$revisionRecord = $this->createMock( RevisionRecord::class );
		$revisionRecord->method( 'getId' )->willReturn( $id );
		return $revisionRecord;
	}

	public function testConstructionAndAccessors(): void {
		$revisionRecord = $this->newRevisionRecord( 456 );
		$performer = new UserIdentityValue( 7, 'Tagger' );

		$event = new RevisionTagsUpdatedEvent(
			$revisionRecord,
			[ 'removed' => self::tagData( 2 ), 'kept' => self::tagData( 3 ) ],
			[ 'kept' => self::tagData( 3 ), 'added' => self::tagData( 1, '{"foo":"bar"}' ) ],
			'{"foo":"bar"}',
			$performer,
			'20260828000000'
		);

		$this->assertSame( $revisionRecord, $event->getRevisionRecord() );
		$this->assertSame( 456, $event->getRevisionId() );

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
		$this->assertSame(
			'20260828000000',
			$event->getEventTimestamp()->getTimestamp( TS_MW )
		);
	}

	public function testIsCompatibleWithBaseType(): void {
		$event = new RevisionTagsUpdatedEvent( $this->newRevisionRecord( 456 ), [], [] );

		$this->assertSame( RevisionTagsUpdatedEvent::TYPE, $event->getEventType() );
		$this->assertSame(
			[
				RevisionTagsUpdatedEvent::ANY,
				EntityTagsUpdatedEvent::TYPE,
				RevisionTagsUpdatedEvent::TYPE,
			],
			$event->getEventTypeChain()
		);
	}

	public function testRejectsUnsavedRevision(): void {
		$this->expectException( ParameterAssertionException::class );
		new RevisionTagsUpdatedEvent( $this->newRevisionRecord( null ), [], [] );
	}

}
