<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\ChangeTags\Event;

use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\Logging\LogRecord;
use MediaWiki\User\UserIdentity;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Domain event representing updates to the change tags attached to a log entry,
 * that is, to a row in the change_tag table that has a ct_log_id.
 *
 * A log entry is often recorded alongside a revision, but it is not necessarily
 * about that revision: actions that operate on past revisions only produce a log
 * entry. Tag updates affecting a revision are therefore reported by a separate
 * event, see RevisionTagsUpdatedEvent.
 *
 * @see ChangeTagsStore::updateTags()
 *
 * @since 1.47
 * @unstable
 */
class LogTagsUpdatedEvent extends EntityTagsUpdatedEvent {

	public const TYPE = 'LogTagsUpdated';

	private LogRecord $logRecord;

	/**
	 * @param LogRecord $logRecord The log entry the tags apply to.
	 * @param array<string,array{id:int,params:?string}> $tagsBefore See EntityTagsUpdatedEvent
	 * @param array<string,array{id:int,params:?string}> $tagsAfter See EntityTagsUpdatedEvent
	 * @param string|null $params See EntityTagsUpdatedEvent
	 * @param UserIdentity|null $performer See EntityTagsUpdatedEvent
	 * @param string|ConvertibleTimestamp|false $timestamp
	 */
	public function __construct(
		LogRecord $logRecord,
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

		$this->logRecord = $logRecord;
	}

	/**
	 * Returns the log entry the tags apply to.
	 */
	public function getLogRecord(): LogRecord {
		return $this->logRecord;
	}

	/**
	 * Returns the id of the log entry the tags apply to,
	 * as stored in change_tag.ct_log_id.
	 */
	public function getLogId(): int {
		return $this->logRecord->getId();
	}

}
