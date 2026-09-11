<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\ChangeTags\Event;

use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\User\UserIdentity;
use Wikimedia\Assert\Assert;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Domain event representing updates to the change tags attached to a revision,
 * that is, to a row in the change_tag table that has a ct_rev_id.
 *
 * If the same update also affects a log entry, a separate event is emitted for
 * it, see LogTagsUpdatedEvent.
 *
 * @see ChangeTagsStore::updateTags()
 *
 * @since 1.47
 * @unstable
 */
class RevisionTagsUpdatedEvent extends EntityTagsUpdatedEvent {

	public const TYPE = 'RevisionTagsUpdated';

	private RevisionRecord $revisionRecord;

	/**
	 * @param RevisionRecord $revisionRecord The revision the tags apply to.
	 *   Must be a saved revision, that is, it must have an ID.
	 * @param array<string,array{id:int,params:?string}> $tagsBefore See EntityTagsUpdatedEvent
	 * @param array<string,array{id:int,params:?string}> $tagsAfter See EntityTagsUpdatedEvent
	 * @param string|null $params See EntityTagsUpdatedEvent
	 * @param UserIdentity|null $performer See EntityTagsUpdatedEvent
	 * @param string|ConvertibleTimestamp|false $timestamp
	 */
	public function __construct(
		RevisionRecord $revisionRecord,
		array $tagsBefore,
		array $tagsAfter,
		?string $params = null,
		?UserIdentity $performer = null,
		$timestamp = false
	) {
		parent::__construct(
			$tagsBefore,
			$tagsAfter,
			$params,
			$performer,
			$timestamp
		);

		$this->declareEventType( self::TYPE );

		Assert::parameter(
			$revisionRecord->getId() !== null,
			'$revisionRecord',
			'must have an ID'
		);

		$this->revisionRecord = $revisionRecord;
	}

	/**
	 * Returns the revision the tags apply to.
	 */
	public function getRevisionRecord(): RevisionRecord {
		return $this->revisionRecord;
	}

	/**
	 * Returns the id of the revision the tags apply to,
	 * as stored in change_tag.ct_rev_id.
	 */
	public function getRevisionId(): int {
		return $this->revisionRecord->getId();
	}

}
