<?php

/**
 * Abstract base class for a list of deletable items
 */
abstract class RevDel_List {
	var $special, $title, $ids, $res, $current;
	var $type = null; // override this
	var $idField = null; // override this
	var $dateField = false; // override this
	var $authorIdField = false; // override this
	var $authorNameField = false; // override this

	/**
	 * @param $special The parent SpecialPage
	 * @param $title The target title
	 * @param $ids Array of IDs
	 */
	public function __construct( $special, $title, $ids ) {
		$this->special = $special;
		$this->title = $title;
		$this->ids = $ids;
	}

	/**
	 * Get the internal type name of this list. Equal to the table name.
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Get the DB field name associated with the ID list
	 */
	public function getIdField() {
		return $this->idField;
	}

	/**
	 * Get the DB field name storing timestamps
	 */
	public function getTimestampField() {
		return $this->dateField;
	}

	/**
	 * Get the DB field name storing user ids
	 */
	public function getAuthorIdField() {
		return $this->authorIdField;
	}

	/**
	 * Get the DB field name storing user names
	 */
	public function getAuthorNameField() {
		return $this->authorNameField;
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
				} else if( IP::isIPAddress( $item->getAuthorName() ) ) {
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
	 * Initialise the current iteration pointer
	 */
	protected function initCurrent() {
		$row = $this->res->current();
		if ( $row ) {
			$this->current = $this->newItem( $row );
		} else {
			$this->current = false;
		}
	}

	/**
	 * Start iteration. This must be called before current() or next(). 
	 * @return First list item
	 */
	public function reset() {
		if ( !$this->res ) {
			$this->res = $this->doQuery( wfGetDB( DB_SLAVE ) );
		} else {
			$this->res->rewind();
		}
		$this->initCurrent();
		return $this->current;
	}

	/**
	 * Get the current list item, or false if we are at the end
	 */
	public function current() {
		return $this->current;
	}

	/**
	 * Move the iteration pointer to the next list item, and return it.
	 */
	public function next() {
		$this->res->next();
		$this->initCurrent();
		return $this->current;
	}
	
	/**
	 * Get the number of items in the list.
	 */
	public function length() {
		if( !$this->res ) {
			return 0;
		} else {
			return $this->res->numRows();
		}
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
	 * Create an item object from a DB result row
	 * @param $row stdclass
	 */
	abstract public function newItem( $row );

	/**
	 * Do the DB query to iterate through the objects.
	 * @param $db Database object to use for the query
	 */
	abstract public function doQuery( $db );

	/**
	 * Get the integer value of the flag used for suppression
	 */
	abstract public function getSuppressBit();
}

/**
 * Abstract base class for deletable items
 */
abstract class RevDel_Item {
	/** The parent SpecialPage */
	var $special;

	/** The parent RevDel_List */
	var $list;

	/** The DB result row */
	var $row;

	/** 
	 * @param $list RevDel_List
	 * @param $row DB result row
	 */
	public function __construct( $list, $row ) {
		$this->special = $list->special;
		$this->list = $list;
		$this->row = $row;
	}

	/**
	 * Get the ID, as it would appear in the ids URL parameter
	 */
	public function getId() {
		$field = $this->list->getIdField();
		return $this->row->$field;
	}

	/**
	 * Get the date, formatted with $wgLang
	 */
	public function formatDate() {
		global $wgLang;
		return $wgLang->date( $this->getTimestamp() );
	}

	/**
	 * Get the time, formatted with $wgLang
	 */
	public function formatTime() {
		global $wgLang;
		return $wgLang->time( $this->getTimestamp() );
	}

	/**
	 * Get the timestamp in MW 14-char form
	 */
	public function getTimestamp() {
		$field = $this->list->getTimestampField();
		return wfTimestamp( TS_MW, $this->row->$field );
	}
	
	/**
	 * Get the author user ID
	 */	
	public function getAuthorId() {
		$field = $this->list->getAuthorIdField();
		return intval( $this->row->$field );
	}

	/**
	 * Get the author user name
	 */	
	public function getAuthorName() {
		$field = $this->list->getAuthorNameField();
		return strval( $this->row->$field );
	}

	/** 
	 * Returns true if the item is "current", and the operation to set the given
	 * bits can't be executed for that reason
	 * STUB
	 */
	public function isHideCurrentOp( $newBits ) {
		return false;
	}

	/**
	 * Returns true if the current user can view the item
	 */
	abstract public function canView();
	
	/**
	 * Returns true if the current user can view the item text/file
	 */
	abstract public function canViewContent();

	/**
	 * Get the current deletion bitfield value
	 */
	abstract public function getBits();

	/**
	 * Get the HTML of the list item. Should be include <li></li> tags.
	 * This is used to show the list in HTML form, by the special page.
	 */
	abstract public function getHTML();

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
