<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup RevisionDelete
 */

use MediaWiki\Context\IContextSource;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\RevisionList\RevisionListBase;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\LBFactory;

/**
 * Abstract base class for a list of deletable items. The list class
 * needs to be able to make a query from a set of identifiers to pull
 * relevant rows, to return RevDelItem subclasses wrapping them, and
 * to wrap bulk update operations.
 *
 * @property RevDelItem $current
 * @method RevDelItem next()
 * @method RevDelItem reset()
 * @method RevDelItem current()
 */
abstract class RevDelList extends RevisionListBase {

	/** Flag used for suppression, depending on the type of log */
	protected const SUPPRESS_BIT = RevisionRecord::DELETED_RESTRICTED;

	/** @var LBFactory */
	private $lbFactory;

	/**
	 * @param IContextSource $context
	 * @param PageIdentity $page
	 * @param array $ids
	 * @param LBFactory $lbFactory
	 */
	public function __construct(
		IContextSource $context,
		PageIdentity $page,
		array $ids,
		LBFactory $lbFactory
	) {
		parent::__construct( $context, $page );

		// ids is a protected variable in RevisionListBase
		$this->ids = $ids;
		$this->lbFactory = $lbFactory;
	}

	/**
	 * Get the DB field name associated with the ID list.
	 * This used to populate the log_search table for finding log entries.
	 * Override this function.
	 * @return string|null
	 */
	public static function getRelationType() {
		return null;
	}

	/**
	 * Get the user right required for this list type
	 * Override this function.
	 * @since 1.22
	 * @return string|null
	 */
	public static function getRestriction() {
		return null;
	}

	/**
	 * Get the revision deletion constant for this list type
	 * Override this function.
	 * @since 1.22
	 * @return int|null
	 */
	public static function getRevdelConstant() {
		return null;
	}

	/**
	 * Suggest a target for the revision deletion
	 * Optionally override this function.
	 * @since 1.22
	 * @param Title|null $target User-supplied target
	 * @param array $ids
	 * @return Title|null
	 */
	public static function suggestTarget( $target, array $ids ) {
		return $target;
	}

	/**
	 * Indicate whether any item in this list is suppressed
	 * @since 1.25
	 * @return bool
	 */
	public function areAnySuppressed() {
		/** @var RevDelItem $item */
		foreach ( $this as $item ) {
			if ( $item->getBits() & self::SUPPRESS_BIT ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Set the visibility for the revisions in this list. Logging and
	 * transactions are done here.
	 *
	 * @param array $params Associative array of parameters. Members are:
	 *     value:         ExtractBitParams() bitfield array
	 *     comment:       The log comment
	 *     perItemStatus: Set if you want per-item status reports
	 *     tags:          The array of change tags to apply to the log entry
	 * @return Status
	 * @since 1.23 Added 'perItemStatus' param
	 */
	public function setVisibility( array $params ) {
		$status = Status::newGood();

		$bitPars = $params['value'];
		$comment = $params['comment'];
		$perItemStatus = $params['perItemStatus'] ?? false;

		// T387638 - Always ensure ->value['itemStatuses'] is set if requested
		if ( $perItemStatus ) {
			$status->value['itemStatuses'] = [];
		}

		// CAS-style checks are done on the _deleted fields so the select
		// does not need to use FOR UPDATE nor be in the atomic section
		$dbw = $this->lbFactory->getPrimaryDatabase();
		$this->res = $this->doQuery( $dbw );

		$status->merge( $this->acquireItemLocks() );
		if ( !$status->isGood() ) {
			return $status;
		}

		$dbw->startAtomic( __METHOD__, $dbw::ATOMIC_CANCELABLE );
		$dbw->onTransactionResolution(
			function () {
				// Release locks on commit or error
				$this->releaseItemLocks();
			},
			__METHOD__
		);

		$missing = array_fill_keys( $this->ids, true );
		$this->clearFileOps();
		$idsForLog = [];
		$authorActors = [];

		// For multi-item deletions, set the old/new bitfields in log_params such that "hid X"
		// shows in logs if field X was hidden from ANY item and likewise for "unhid Y". Note the
		// form does not let the same field get hidden and unhidden in different items at once.
		$virtualOldBits = 0;
		$virtualNewBits = 0;
		$logType = 'delete';
		$useSuppressLog = false;

		// Will be filled with id => [old, new bits] information and
		// passed to doPostCommitUpdates().
		$visibilityChangeMap = [];

		/** @var RevDelItem $item */
		foreach ( $this as $item ) {
			unset( $missing[$item->getId()] );

			if ( $perItemStatus ) {
				$itemStatus = Status::newGood();
				$status->value['itemStatuses'][$item->getId()] = $itemStatus;
			} else {
				$itemStatus = $status;
			}

			$oldBits = $item->getBits();
			// Build the actual new rev_deleted bitfield
			$newBits = RevisionDeleter::extractBitfield( $bitPars, $oldBits );

			if ( $oldBits == $newBits ) {
				$itemStatus->warning(
					'revdelete-no-change', $item->formatDate(), $item->formatTime() );
				$status->failCount++;
				continue;
			} elseif ( $oldBits == 0 && $newBits != 0 ) {
				$opType = 'hide';
			} elseif ( $oldBits != 0 && $newBits == 0 ) {
				$opType = 'show';
			} else {
				$opType = 'modify';
			}

			if ( $item->isHideCurrentOp( $newBits ) ) {
				// Cannot hide current version text
				$itemStatus->error(
					'revdelete-hide-current', $item->formatDate(), $item->formatTime() );
				$status->failCount++;
				continue;
			} elseif ( !$item->canView() ) {
				// Cannot access this revision
				$msg = ( $opType == 'show' ) ?
					'revdelete-show-no-access' : 'revdelete-modify-no-access';
				$itemStatus->error( $msg, $item->formatDate(), $item->formatTime() );
				$status->failCount++;
				continue;
			// Cannot just "hide from Sysops" without hiding any fields
			} elseif ( $newBits == self::SUPPRESS_BIT ) {
				$itemStatus->warning(
					'revdelete-only-restricted', $item->formatDate(), $item->formatTime() );
				$status->failCount++;
				continue;
			}

			// Update the revision
			$ok = $item->setBits( $newBits );

			if ( $ok ) {
				$idsForLog[] = $item->getId();
				// If any item field was suppressed or unsuppressed
				if ( ( $oldBits | $newBits ) & self::SUPPRESS_BIT ) {
					$logType = 'suppress';
					$useSuppressLog = true;
				}
				// Track which fields where (un)hidden for each item
				$addedBits = ( $oldBits ^ $newBits ) & $newBits;
				$removedBits = ( $oldBits ^ $newBits ) & $oldBits;
				$virtualNewBits |= $addedBits;
				$virtualOldBits |= $removedBits;

				$status->successCount++;
				$authorActors[] = $item->getAuthorActor();

				// Save the old and new bits in $visibilityChangeMap for
				// later use.
				$visibilityChangeMap[$item->getId()] = [
					'oldBits' => $oldBits,
					'newBits' => $newBits,
				];
			} else {
				$itemStatus->error(
					'revdelete-concurrent-change', $item->formatDate(), $item->formatTime() );
				$status->failCount++;
			}
		}

		// Handle missing revisions
		foreach ( $missing as $id => $unused ) {
			if ( $perItemStatus ) {
				$status->value['itemStatuses'][$id] = Status::newFatal( 'revdelete-modify-missing', $id );
			} else {
				$status->error( 'revdelete-modify-missing', $id );
			}
			$status->failCount++;
		}

		if ( $status->successCount == 0 ) {
			$dbw->endAtomic( __METHOD__ );
			return $status;
		}

		// Save success count
		$successCount = $status->successCount;

		// Move files, if there are any
		$status->merge( $this->doPreCommitUpdates() );
		if ( !$status->isOK() ) {
			// Fatal error, such as no configured archive directory or I/O failures
			$dbw->cancelAtomic( __METHOD__ );
			return $status;
		}

		// Log it
		$authorFields = [];
		$authorFields['authorActors'] = $authorActors;

		$tags = $params['tags'] ?? [];

		$logEntry = $this->updateLog(
			$logType,
			[
				'page' => $this->page,
				'count' => $successCount,
				'newBits' => $virtualNewBits,
				'oldBits' => $virtualOldBits,
				'comment' => $comment,
				'ids' => $idsForLog,
				'tags' => $tags,
			] + $authorFields
		);

		$this->emitEvents( $bitPars, $visibilityChangeMap, $tags, $logEntry, $useSuppressLog );

		// Clear caches after commit
		DeferredUpdates::addCallableUpdate(
			function () use ( $visibilityChangeMap ) {
				$this->doPostCommitUpdates( $visibilityChangeMap );
			},
			DeferredUpdates::PRESEND,
			$dbw
		);

		$dbw->endAtomic( __METHOD__ );

		return $status;
	}

	/**
	 * @param array $bitPars See RevisionDeleter::extractBitfield
	 * @param array $visibilityChangeMap [id => ['oldBits' => $oldBits, 'newBits' => $newBits], ... ]
	 * @param array $tags
	 * @param LogEntry $logEntry
	 * @param bool $suppressed
	 */
	protected function emitEvents(
		array $bitPars,
		array $visibilityChangeMap,
		array $tags,
		LogEntry $logEntry,
		bool $suppressed
	) {
		// stub
	}

	final protected function acquireItemLocks(): Status {
		$status = Status::newGood();
		/** @var RevDelItem $item */
		foreach ( $this as $item ) {
			$status->merge( $item->lock() );
		}

		return $status;
	}

	final protected function releaseItemLocks(): Status {
		$status = Status::newGood();
		/** @var RevDelItem $item */
		foreach ( $this as $item ) {
			$status->merge( $item->unlock() );
		}

		return $status;
	}

	/**
	 * Reload the list data from the primary DB. This can be done after setVisibility()
	 * to allow $item->getHTML() to show the new data.
	 * @since 1.37
	 */
	public function reloadFromPrimary() {
		$dbw = $this->lbFactory->getPrimaryDatabase();
		$this->res = $this->doQuery( $dbw );
	}

	/**
	 * Record a log entry on the action
	 * @param string $logType One of (delete,suppress)
	 * @param array $params Associative array of parameters:
	 *     newBits:         The new value of the *_deleted bitfield
	 *     oldBits:         The old value of the *_deleted bitfield.
	 *     page:            The target page reference
	 *     ids:             The ID list
	 *     comment:         The log comment
	 *     authorActors:    The array of the actor IDs of the offenders
	 *     tags:            The array of change tags to apply to the log entry
	 */
	private function updateLog( $logType, $params ): LogEntry {
		// Get the URL param's corresponding DB field
		$field = RevisionDeleter::getRelationType( $this->getType() );
		if ( !$field ) {
			throw new UnexpectedValueException( "Bad log URL param type!" );
		}
		// Add params for affected page and ids
		$logParams = $this->getLogParams( $params );
		// Actually add the deletion log entry
		$logEntry = new ManualLogEntry( $logType, $this->getLogAction() );
		$logEntry->setTarget( $params['page'] );
		$logEntry->setComment( $params['comment'] );
		$logEntry->setParameters( $logParams );
		$logEntry->setPerformer( $this->getUser() );
		// Allow for easy searching of deletion log items for revision/log items
		$relations = [
			$field => $params['ids'],
		];
		if ( isset( $params['authorActors'] ) ) {
			$relations += [
				'target_author_actor' => $params['authorActors'],
			];
		}
		$logEntry->setRelations( $relations );
		// Apply change tags to the log entry
		$logEntry->addTags( $params['tags'] );
		$logId = $logEntry->insert();
		$logEntry->publish( $logId );

		return $logEntry;
	}

	/**
	 * Get the log action for this list type
	 * @return string
	 */
	public function getLogAction() {
		return 'revision';
	}

	/**
	 * Get log parameter array.
	 * @param array $params Associative array of log parameters, same as updateLog()
	 * @return array
	 */
	public function getLogParams( $params ) {
		return [
			'4::type' => $this->getType(),
			'5::ids' => $params['ids'],
			'6::ofield' => $params['oldBits'],
			'7::nfield' => $params['newBits'],
		];
	}

	/**
	 * Clear any data structures needed for doPreCommitUpdates() and doPostCommitUpdates()
	 * STUB
	 */
	public function clearFileOps() {
	}

	/**
	 * A hook for setVisibility(): do batch updates pre-commit.
	 * STUB
	 * @return Status
	 */
	public function doPreCommitUpdates() {
		return Status::newGood();
	}

	/**
	 * A hook for setVisibility(): do any necessary updates post-commit.
	 * STUB
	 * @param array $visibilityChangeMap [id => ['oldBits' => $oldBits, 'newBits' => $newBits], ... ]
	 * @return Status
	 */
	public function doPostCommitUpdates( array $visibilityChangeMap ) {
		return Status::newGood();
	}

}
