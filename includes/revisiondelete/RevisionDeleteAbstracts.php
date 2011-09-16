<?php

/**
 * Abstract base class for a list of deletable items. The list class
 * needs to be able to make a query from a set of identifiers to pull
 * relevant rows, to return RevDel_Item subclasses wrapping them, and
 * to wrap bulk update operations.
 */
abstract class RevDel_List extends Rev_List {
	function __construct( IContextSource $context, Title $title, array $ids ) {
		parent::__construct( $context, $title );
		$this->ids = $ids;
	}

	/**
	 * Get the DB field name associated with the ID list.
	 * This used to populate the log_search table for finding log entries.
	 * Override this function.
	 */
	public static function getRelationType() {
		return null;
	}

	/**
	 * Set the visibility for the revisions in this list. Logging and
	 * transactions are done here.
	 *
	 * @param $params Associative array of parameters. Members are:
	 *     value:       The integer value to set the visibility to
	 *     comment:     The log comment.
	 * @return Status
	 */
	public function setVisibility( $params ) {
		$bitPars = $params['value'];
		$comment = $params['comment'];

		$this->res = false;
		$dbw = wfGetDB( DB_MASTER );
		$this->doQuery( $dbw );
		$dbw->begin();
		$status = Status::newGood();
		$missing = array_flip( $this->ids );
		$this->clearFileOps();
		$idsForLog = array();
		$authorIds = $authorIPs = array();

		for ( $this->reset(); $this->current(); $this->next() ) {
			$item = $this->current();
			unset( $missing[ $item->getId() ] );

			$oldBits = $item->getBits();
			// Build the actual new rev_deleted bitfield
			$newBits = SpecialRevisionDelete::extractBitfield( $bitPars, $oldBits );

			if ( $oldBits == $newBits ) {
				$status->warning( 'revdelete-no-change', $item->formatDate(), $item->formatTime() );
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
				$status->error( 'revdelete-hide-current', $item->formatDate(), $item->formatTime() );
				$status->failCount++;
				continue;
			}
			if ( !$item->canView() ) {
				// Cannot access this revision
				$msg = ($opType == 'show') ?
					'revdelete-show-no-access' : 'revdelete-modify-no-access';
				$status->error( $msg, $item->formatDate(), $item->formatTime() );
				$status->failCount++;
				continue;
			}
			// Cannot just "hide from Sysops" without hiding any fields
			if( $newBits == Revision::DELETED_RESTRICTED ) {
				$status->warning( 'revdelete-only-restricted', $item->formatDate(), $item->formatTime() );
				$status->failCount++;
				continue;
			}

			// Update the revision
			$ok = $item->setBits( $newBits );

			if ( $ok ) {
				$idsForLog[] = $item->getId();
				$status->successCount++;
				if( $item->getAuthorId() > 0 ) {
					$authorIds[] = $item->getAuthorId();
				} elseif( IP::isIPAddress( $item->getAuthorName() ) ) {
					$authorIPs[] = $item->getAuthorName();
				}
			} else {
				$status->error( 'revdelete-concurrent-change', $item->formatDate(), $item->formatTime() );
				$status->failCount++;
			}
		}

		// Handle missing revisions
		foreach ( $missing as $id => $unused ) {
			$status->error( 'revdelete-modify-missing', $id );
			$status->failCount++;
		}

		if ( $status->successCount == 0 ) {
			$status->ok = false;
			$dbw->rollback();
			return $status;
		}

		// Save success count
		$successCount = $status->successCount;

		// Move files, if there are any
		$status->merge( $this->doPreCommitUpdates() );
		if ( !$status->isOK() ) {
			// Fatal error, such as no configured archive directory
			$dbw->rollback();
			return $status;
		}

		// Log it
		$this->updateLog( array(
			'title' => $this->title,
			'count' => $successCount,
			'newBits' => $newBits,
			'oldBits' => $oldBits,
			'comment' => $comment,
			'ids' => $idsForLog,
			'authorIds' => $authorIds,
			'authorIPs' => $authorIPs
		) );
		$dbw->commit();

		// Clear caches
		$status->merge( $this->doPostCommitUpdates() );
		return $status;
	}

	/**
	 * Reload the list data from the master DB. This can be done after setVisibility()
	 * to allow $item->getHTML() to show the new data.
	 */
	function reloadFromMaster() {
		$dbw = wfGetDB( DB_MASTER );
		$this->res = $this->doQuery( $dbw );
	}

	/**
	 * Record a log entry on the action
	 * @param $params Associative array of parameters:
	 *     newBits:         The new value of the *_deleted bitfield
	 *     oldBits:         The old value of the *_deleted bitfield.
	 *     title:           The target title
	 *     ids:             The ID list
	 *     comment:         The log comment
	 *     authorsIds:      The array of the user IDs of the offenders
	 *     authorsIPs:      The array of the IP/anon user offenders
	 */
	protected function updateLog( $params ) {
		// Get the URL param's corresponding DB field
		$field = RevisionDeleter::getRelationType( $this->getType() );
		if( !$field ) {
			throw new MWException( "Bad log URL param type!" );
		}
		// Put things hidden from sysops in the oversight log
		if ( ( $params['newBits'] | $params['oldBits'] ) & $this->getSuppressBit() ) {
			$logType = 'suppress';
		} else {
			$logType = 'delete';
		}
		// Add params for effected page and ids
		$logParams = $this->getLogParams( $params );
		// Actually add the deletion log entry
		$log = new LogPage( $logType );
		$logid = $log->addEntry( $this->getLogAction(), $params['title'],
			$params['comment'], $logParams );
		// Allow for easy searching of deletion log items for revision/log items
		$log->addRelations( $field, $params['ids'], $logid );
		$log->addRelations( 'target_author_id', $params['authorIds'], $logid );
		$log->addRelations( 'target_author_ip', $params['authorIPs'], $logid );
	}

	/**
	 * Get the log action for this list type
	 */
	public function getLogAction() {
		return 'revision';
	}

	/**
	 * Get log parameter array.
	 * @param $params Associative array of log parameters, same as updateLog()
	 * @return array
	 */
	public function getLogParams( $params ) {
		return array(
			$this->getType(),
			implode( ',', $params['ids'] ),
			"ofield={$params['oldBits']}",
			"nfield={$params['newBits']}"
		);
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
	 * @return Status
	 */
	public function doPostCommitUpdates() {
		return Status::newGood();
	}

	/**
	 * Get the integer value of the flag used for suppression
	 */
	abstract public function getSuppressBit();
}

/**
 * Abstract base class for deletable items
 */
abstract class RevDel_Item extends Rev_Item {
	/**
	 * Returns true if the item is "current", and the operation to set the given
	 * bits can't be executed for that reason
	 * STUB
	 */
	public function isHideCurrentOp( $newBits ) {
		return false;
	}

	/**
	 * Get the current deletion bitfield value
	 */
	abstract public function getBits();

	/**
	 * Set the visibility of the item. This should do any necessary DB queries.
	 *
	 * The DB update query should have a condition which forces it to only update
	 * if the value in the DB matches the value fetched earlier with the SELECT.
	 * If the update fails because it did not match, the function should return
	 * false. This prevents concurrency problems.
	 *
	 * @return boolean success
	 */
	abstract public function setBits( $newBits );
}
