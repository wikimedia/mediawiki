<?php

namespace MediaWiki\Tests\Unit\ChangeTags\Event;

use MediaWiki\ChangeTags\Event\EntityTagsUpdatedEvent;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;
use Wikimedia\Assert\ParameterAssertionException;
use Wikimedia\Assert\ParameterElementTypeException;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\ChangeTags\Event\EntityTagsUpdatedEvent
 */
class EntityTagsUpdatedEventTest extends MediaWikiUnitTestCase {

	private const PUBLIC_TAG = 'mw-manual-revert';
	private const PRIVATE_TAG = 'mw-private-foo';

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

	/**
	 * @param array<string,array{id:int,params:?string}> $tagsBefore
	 * @param array<string,array{id:int,params:?string}> $tagsAfter
	 * @param string|null $params
	 * @param UserIdentity|null $performer
	 * @param string|ConvertibleTimestamp|false $timestamp
	 * @return EntityTagsUpdatedEvent
	 */
	private function newEvent(
		array $tagsBefore = [],
		array $tagsAfter = [],
		?string $params = null,
		?UserIdentity $performer = null,
		$timestamp = false
	): EntityTagsUpdatedEvent {
		return new class (
			$tagsBefore, $tagsAfter, $params, $performer, $timestamp
		) extends EntityTagsUpdatedEvent {
		};
	}

	public function testConstructionAndAccessors(): void {
		$performer = new UserIdentityValue( 7, 'Tagger' );
		$event = $this->newEvent(
			[ 'removed' => self::tagData( 2 ), 'kept' => self::tagData( 3, '{"old":"params"}' ) ],
			[ 'kept' => self::tagData( 3, '{"old":"params"}' ), 'added' => self::tagData( 1, '{"foo":"bar"}' ) ],
			'{"foo":"bar"}',
			$performer,
			'20260828000000'
		);

		$this->assertSame( EntityTagsUpdatedEvent::TYPE, $event->getEventType() );
		$this->assertSame(
			[ EntityTagsUpdatedEvent::ANY, EntityTagsUpdatedEvent::TYPE ],
			$event->getEventTypeChain()
		);
		$this->assertSame(
			[ 'added' => self::tagData( 1, '{"foo":"bar"}' ) ],
			$event->getAddedPublicAndPrivateTags()
		);
		$this->assertSame(
			[ 'removed' => self::tagData( 2 ) ],
			$event->getRemovedPublicAndPrivateTags()
		);
		$this->assertSame(
			[ 'removed' => self::tagData( 2 ), 'kept' => self::tagData( 3, '{"old":"params"}' ) ],
			$event->getPublicAndPrivateTagsBefore()
		);
		$this->assertSame(
			[ 'kept' => self::tagData( 3, '{"old":"params"}' ), 'added' => self::tagData( 1, '{"foo":"bar"}' ) ],
			$event->getPublicAndPrivateTagsAfter()
		);
		$this->assertSame( '{"foo":"bar"}', $event->getParams() );
		$this->assertSame( $performer, $event->getPerformer() );
		$this->assertSame(
			'20260828000000',
			$event->getEventTimestamp()->getTimestamp( TS_MW )
		);
	}

	public function testDefaults(): void {
		$event = $this->newEvent();

		$this->assertSame( [], $event->getAddedPublicAndPrivateTags() );
		$this->assertSame( [], $event->getRemovedPublicAndPrivateTags() );
		$this->assertSame( [], $event->getPublicAndPrivateTagsBefore() );
		$this->assertSame( [], $event->getPublicAndPrivateTagsAfter() );
		$this->assertNull( $event->getParams() );
		$this->assertNull( $event->getPerformer() );
		$this->assertFalse( $event->isReconciliationRequest() );
	}

	/**
	 * A tag that is present before and after the update is not added and not
	 * removed. The event compares only the tag names, not the tag data.
	 */
	public function testTagPresentBeforeAndAfterIsNotAddedOrRemoved(): void {
		$event = $this->newEvent(
			[ 'tag' => self::tagData( 1, '{"old":"params"}' ) ],
			[ 'tag' => self::tagData( 1, '{"new":"params"}' ) ]
		);

		$this->assertSame( [], $event->getAddedPublicAndPrivateTags() );
		$this->assertSame( [], $event->getRemovedPublicAndPrivateTags() );
	}

	public function testPublicAndPrivateTagsAreSeparated(): void {
		$event = $this->newEvent(
			[
				self::PUBLIC_TAG . '-removed' => self::tagData( 3 ),
				self::PRIVATE_TAG . '-removed' => self::tagData( 4 ),
				self::PUBLIC_TAG . '-kept' => self::tagData( 5 ),
				self::PRIVATE_TAG . '-kept' => self::tagData( 6 ),
			],
			[
				self::PUBLIC_TAG . '-kept' => self::tagData( 5 ),
				self::PRIVATE_TAG . '-kept' => self::tagData( 6 ),
				self::PUBLIC_TAG => self::tagData( 1 ),
				self::PRIVATE_TAG => self::tagData( 2 ),
			]
		);

		$this->assertSame(
			[ self::PUBLIC_TAG => self::tagData( 1 ) ],
			$event->getAddedPublicTags()
		);
		$this->assertSame(
			[ self::PRIVATE_TAG => self::tagData( 2 ) ],
			$event->getAddedPrivateTags()
		);

		$this->assertSame(
			[ self::PUBLIC_TAG . '-removed' => self::tagData( 3 ) ],
			$event->getRemovedPublicTags()
		);
		$this->assertSame(
			[ self::PRIVATE_TAG . '-removed' => self::tagData( 4 ) ],
			$event->getRemovedPrivateTags()
		);

		$this->assertSame(
			[
				self::PUBLIC_TAG . '-removed' => self::tagData( 3 ),
				self::PUBLIC_TAG . '-kept' => self::tagData( 5 ),
			],
			$event->getPublicTagsBefore()
		);
		$this->assertSame(
			[
				self::PRIVATE_TAG . '-removed' => self::tagData( 4 ),
				self::PRIVATE_TAG . '-kept' => self::tagData( 6 ),
			],
			$event->getPrivateTagsBefore()
		);

		$this->assertSame(
			[
				self::PUBLIC_TAG . '-kept' => self::tagData( 5 ),
				self::PUBLIC_TAG => self::tagData( 1 ),
			],
			$event->getPublicTagsAfter()
		);
		$this->assertSame(
			[
				self::PRIVATE_TAG . '-kept' => self::tagData( 6 ),
				self::PRIVATE_TAG => self::tagData( 2 ),
			],
			$event->getPrivateTagsAfter()
		);
	}

	/**
	 * Only the reserved prefix makes a tag private; a tag that merely contains
	 * the prefix elsewhere in its name is public.
	 */
	public function testOnlyThePrefixMakesATagPrivate(): void {
		$event = $this->newEvent( [], [
			'not-mw-private-foo' => self::tagData( 1 ),
			'mw-private-foo' => self::tagData( 2 ),
		] );

		$this->assertSame(
			[ 'not-mw-private-foo' => self::tagData( 1 ) ],
			$event->getAddedPublicTags()
		);
		$this->assertSame(
			[ 'mw-private-foo' => self::tagData( 2 ) ],
			$event->getAddedPrivateTags()
		);
	}

	/**
	 * PHP casts decimal integer strings used as array keys to integers, so a tag
	 * named e.g. "0" arrives under an integer key. It is still a public tag.
	 */
	public function testNumericTagNames(): void {
		$event = $this->newEvent( [], [ '0' => self::tagData( 1 ) ] );

		$this->assertSame( [ 0 => self::tagData( 1 ) ], $event->getAddedPublicAndPrivateTags() );
		$this->assertSame( [ 0 => self::tagData( 1 ) ], $event->getAddedPublicTags() );
		$this->assertSame( [], $event->getAddedPrivateTags() );
	}

	public function testRejectsTagNamesAsValues(): void {
		$this->expectException( ParameterElementTypeException::class );
		$this->newEvent( [ 1 => 'tag' ] );
	}

	public function testRejectsTagIdsAsValues(): void {
		$this->expectException( ParameterElementTypeException::class );
		$this->newEvent( [ 'tag' => 1 ] );
	}

	public static function provideInvalidTagData(): iterable {
		yield 'id is missing' => [ [ 'tag' => [ 'params' => null ] ], [] ];
		yield 'id is a string' => [ [ 'tag' => [ 'id' => '1', 'params' => null ] ], [] ];
		yield 'id is null' => [ [ 'tag' => [ 'id' => null, 'params' => null ] ], [] ];
		yield 'params is missing' => [ [ 'tag' => [ 'id' => 1 ] ], [] ];
		yield 'params is an integer' => [ [ 'tag' => [ 'id' => 1, 'params' => 5 ] ], [] ];
		yield 'invalid $tagsAfter' => [ [], [ 'tag' => [ 'id' => 1 ] ] ];
	}

	/**
	 * @dataProvider provideInvalidTagData
	 */
	public function testRejectsInvalidTagData(
		array $tagsBefore,
		array $tagsAfter
	): void {
		$this->expectException( ParameterAssertionException::class );
		$this->newEvent( $tagsBefore, $tagsAfter );
	}

}
