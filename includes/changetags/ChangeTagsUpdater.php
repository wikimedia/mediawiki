<?php
/**
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Change tagging
 */

/**
 * Allows to update change tags, checking permissions for doing so
 * @since 1.28
 */
class ChangeTagsUpdater {

	/**
	 * @var array ChangeTagsContext
	 */
	protected $context;

	/**
	 * @var Language
	 */
	protected $lang;

	/**
	 * @var User
	 */
	protected $performer = null;

	/**
	 * @param ChangeTagsContext $context
	 * @param Language $lang
	 * @since 1.28
	 */
	public function __construct( ChangeTagsContext $context, Language $lang ) {
		$this->context = $context;
		$this->lang = $lang;
	}

	/**
	 * @param User $performer
	 * @since 1.28
	 */
	public function setPerformer( User $performer ) {
		$this->performer = $performer;
	}

	/**
	 * Is it OK to allow the user to apply all the specified tags at the same time
	 * as they edit/make the change?
	 *
	 * @param array $tags Tags that you are interested in applying
	 * @return Status
	 * @since 1.28
	 */
	public function canAddTagsAccompanyingChange( array $tags ) {

		if ( !is_null( $this->performer ) ) {
			if ( !$this->performer->isAllowed( 'applychangetags' ) ) {
				return Status::newFatal( 'tags-apply-no-permission' );
			} elseif ( $this->performer->isBlocked() ) {
				return Status::newFatal( 'tags-apply-blocked' );
			}
		}

		// to be applied, a tag has to be stored in valid_tag
		// @todo Allow extensions to define tags that can be applied by users...
		$allowedTags = $this->context->getUserTags();
		$disallowedTags = [];
		foreach ( $tags as $tag ) {
			if ( !isset( $allowedTags[$tag] ) ) {
				$disallowedTags[] = $tag;
			}
		}
		if ( $disallowedTags ) {
			return $this->restrictedTagError( 'tags-apply-not-allowed-one',
				'tags-apply-not-allowed-multi', $disallowedTags );
		}

		return Status::newGood();
	}

	/**
	 * Is it OK to allow the user to adds and remove the given tags tags to/from a
	 * change?
	 *
	 * @param array $tagsToAdd Tags that you are interested in adding
	 * @param array $tagsToRemove Tags that you are interested in removing
	 * @return Status
	 * @since 1.28
	 */
	public function canUpdateTags( array $tagsToAdd, array $tagsToRemove ) {

		if ( !is_null( $this->performer ) ) {
			if ( !$this->performer->isAllowed( 'changetags' ) ) {
				return Status::newFatal( 'tags-update-no-permission' );
			} elseif ( $this->performer->isBlocked() ) {
				return Status::newFatal( 'tags-update-blocked' );
			}
		}

		if ( $tagsToAdd ) {
			// to be added, a tag has to be stored in valid_tag
			// @todo Allow extensions to define tags that can be applied by users...
			$storedTags = $this->context->getUserTags();
			$disallowedTags = [];
			foreach ( $tagsToAdd as $tag ) {
				if ( !isset( $storedTags[$tag] ) ) {
					$disallowedTags[] = $tag;
				}
			}
			if ( $disallowedTags ) {
				return $this->restrictedTagError( 'tags-update-add-not-allowed-one',
					'tags-update-add-not-allowed-multi', $disallowedTags );
			}
		}

		if ( $tagsToRemove ) {
			// to be removed, a tag must not be registered by extensions
			$registeredTags = $this->context->getSoftwareTags();
			$disallowedTags = [];
			foreach ( $tagsToRemove as $tag ) {
				if ( isset( $registeredTags[$tag] ) ) {
					$disallowedTags[] = $tag;
				}
			}
			if ( $disallowedTags ) {
				return $this->restrictedTagError( 'tags-update-remove-not-allowed-one',
					'tags-update-remove-not-allowed-multi', $disallowedTags );
			}
		}

		return Status::newGood();
	}

	/**
	 * Helper function to generate a fatal status with a 'not-allowed' type error.
	 *
	 * @param string $msgOne Message key to use in the case of one tag
	 * @param string $msgMulti Message key to use in the case of more than one tag
	 * @param array $tags Restricted tags (passed as $1 into the message, count of
	 * $tags passed as $2)
	 * @return Status
	 * @since 1.28
	 */
	protected function restrictedTagError( $msgOne, $msgMulti, $tags ) {
		$count = count( $tags );
		return Status::newFatal( ( $count > 1 ) ? $msgMulti : $msgOne,
			$this->lang->commaList( $tags ), $count );
	}

	/**
	 * Adds and/or removes tags to/from a given change, checking whether it is
	 * allowed first, and adding a log entry afterwards.
	 *
	 * Includes a call to ChangeTagsContext::canUpdateTags(), so your code doesn't need
	 * to do that. However, it doesn't check whether the *_id parameters are a
	 * valid combination. That is up to you to enforce. See ApiTag::execute() for
	 * an example.
	 *
	 * @param array|null $tagsToAdd If none, pass array() or null
	 * @param array|null $tagsToRemove If none, pass array() or null
	 * @param int|null $rc_id The rc_id of the change to add the tags to
	 * @param int|null $rev_id The rev_id of the change to add the tags to
	 * @param int|null $log_id The log_id of the change to add the tags to
	 * @param string $params Params to put in the ct_params field of table
	 * 'change_tag' when adding tags
	 * @param string $reason Comment for the log
	 * @return Status If successful, the value of this Status object will be an
	 * object (stdClass) with the following fields:
	 *  - logId: the ID of the added log entry, or null if no log entry was added
	 *    (i.e. no operation was performed)
	 *  - addedTags: an array containing the tags that were actually added
	 *  - removedTags: an array containing the tags that were actually removed
	 * @since 1.28
	 */
	public function updateTagsWithChecks( $tagsToAdd, $tagsToRemove,
		$rc_id, $rev_id, $log_id, $params, $reason ) {

		if ( is_null( $tagsToAdd ) ) {
			$tagsToAdd = [];
		}
		if ( is_null( $tagsToRemove ) ) {
			$tagsToRemove = [];
		}
		if ( !$tagsToAdd && !$tagsToRemove ) {
			// no-op, don't bother
			return Status::newGood( (object)[
				'logId' => null,
				'addedTags' => [],
				'removedTags' => [],
			] );
		}

		// are we allowed to do this?
		$result = $this->canUpdateTags( $tagsToAdd, $tagsToRemove );
		if ( !$result->isOK() ) {
			$result->value = null;
			return $result;
		}

		// basic rate limiting
		if ( $this->performer->pingLimiter( 'changetag' ) ) {
			return Status::newFatal( 'actionthrottledtext' );
		}

		// do it!
		list( $tagsAdded, $tagsRemoved, $initialTags ) = self::updateTags( $tagsToAdd,
			$tagsToRemove, $rc_id, $rev_id, $log_id, $params );
		if ( !$tagsAdded && !$tagsRemoved ) {
			// no-op, don't log it
			return Status::newGood( (object)[
				'logId' => null,
				'addedTags' => [],
				'removedTags' => [],
			] );
		}
		// log it
		$logId = $this->logTagUpdateAction( $tagsAdded, $tagsRemoved, $initialTags,
			$rev_id, $log_id, $reason );

		return Status::newGood( (object)[
			'logId' => $logId,
			'addedTags' => $tagsAdded,
			'removedTags' => $tagsRemoved,
		] );
	}

	/**
	 * Writes an update action into the tag log.
	 *
	 * @param array $tagsAdded Added tags
	 * @param array $tagsRemoved Removed tags
	 * @param array $initialTags Initial tags
	 * @param int|null $rev_id The relevant rev_id
	 * @param int|null $log_id The relevant log_id
	 * @param string $reason Log comment
	 * @return int ID of the inserted log entry
	 * @since 1.28
	 */
	protected function logTagUpdateAction( $tagsAdded, $tagsRemoved, $initialTags,
		$rev_id, $log_id, $reason ) {
		$logEntry = new ManualLogEntry( 'tag', 'update' );
		$logEntry->setPerformer( $this->performer );
		$logEntry->setComment( $reason );

		// find the appropriate target page
		if ( $rev_id ) {
			$rev = Revision::newFromId( $rev_id );
			if ( $rev ) {
				$logEntry->setTarget( $rev->getTitle() );
			}
		} elseif ( $log_id ) {
			// This function is from revision deletion logic and has nothing to do with
			// change tags, but it appears to be the only other place in core where we
			// perform logged actions on log items.
			$logEntry->setTarget( RevDelLogList::suggestTarget( null, [ $log_id ] ) );
		}

		if ( !$logEntry->getTarget() ) {
			// target is required, so we have to set something
			$logEntry->setTarget( SpecialPage::getTitleFor( 'Tags' ) );
		}

		$logParams = [
			'4::revid' => $rev_id,
			'5::logid' => $log_id,
			'6:list:tagsAdded' => $tagsAdded,
			'7:number:tagsAddedCount' => count( $tagsAdded ),
			'8:list:tagsRemoved' => $tagsRemoved,
			'9:number:tagsRemovedCount' => count( $tagsRemoved ),
			'initialTags' => $initialTags,
		];
		$logEntry->setParameters( $logParams );
		$logEntry->setRelations( [ 'Tag' => array_merge( $tagsAdded, $tagsRemoved ) ] );

		$dbw = wfGetDB( DB_MASTER );
		$logId = $logEntry->insert( $dbw );
		// Only send this to UDP, not RC, similar to patrol events
		$logEntry->publish( $logId, 'udp' );
		return $logId;
	}

	/**
	 * Add and remove tags to/from a change given its rc_id, rev_id and/or log_id,
	 * without verifying that the tags exist or are valid. If a tag is present in
	 * both $tagsToAdd and $tagsToRemove, it will be removed.
	 *
	 * This function should only be used by extensions to manipulate tags they
	 * have registered using the ChangeTagsRegister hook. When dealing with user
	 * input, call updateTagsWithChecks() instead.
	 *
	 * @param string|array|null $tagsToAdd Tags to add to the change
	 * @param string|array|null $tagsToRemove Tags to remove from the change
	 * @param int|null &$rc_id The rc_id of the change to add the tags to.
	 * Pass a variable whose value is null if the rc_id is not relevant or unknown.
	 * @param int|null &$rev_id The rev_id of the change to add the tags to.
	 * Pass a variable whose value is null if the rev_id is not relevant or unknown.
	 * @param int|null &$log_id The log_id of the change to add the tags to.
	 * Pass a variable whose value is null if the log_id is not relevant or unknown.
	 * @param string $params Params to put in the ct_params field of table
	 * 'change_tag' when adding tags
	 *
	 * @throws MWException When $rc_id, $rev_id and $log_id are all null
	 * @return array Index 0 is an array of tags actually added, index 1 is an
	 * array of tags actually removed, index 2 is an array of tags present on the
	 * revision or log entry before any changes were made
	 *
	 * @since 1.25
	 */
	public static function updateTags(
		$tagsToAdd, $tagsToRemove,
		&$rc_id = null, &$rev_id = null, &$log_id = null, $params = null
	) {

		$tagsToAdd = array_filter( (array)$tagsToAdd ); // Make sure we're submitting all tags...
		$tagsToRemove = array_filter( (array)$tagsToRemove );

		if ( !$rc_id && !$rev_id && !$log_id ) {
			throw new MWException( 'At least one of: RCID, revision ID, and log ID MUST be ' .
				'specified when adding or removing a tag from a change!' );
		}

		$dbw = wfGetDB( DB_MASTER );

		// Might as well look for rcids and so on.
		if ( !$rc_id ) {
			// Info might be out of date, somewhat fractionally, on replica DB.
			// LogEntry/LogPage and WikiPage match rev/log/rc timestamps,
			// so use that relation to avoid full table scans.
			if ( $log_id ) {
				$rc_id = $dbw->selectField(
					[ 'logging', 'recentchanges' ],
					'rc_id',
					[
						'log_id' => $log_id,
						'rc_timestamp = log_timestamp',
						'rc_logid = log_id'
					],
					__METHOD__
				);
			} elseif ( $rev_id ) {
				$rc_id = $dbw->selectField(
					[ 'revision', 'recentchanges' ],
					'rc_id',
					[
						'rev_id' => $rev_id,
						'rc_timestamp = rev_timestamp',
						'rc_this_oldid = rev_id'
					],
					__METHOD__
				);
			}
		} elseif ( !$log_id && !$rev_id ) {
			// Info might be out of date, somewhat fractionally, on replica DB.
			$log_id = $dbw->selectField(
				'recentchanges',
				'rc_logid',
				[ 'rc_id' => $rc_id ],
				__METHOD__
			);
			$rev_id = $dbw->selectField(
				'recentchanges',
				'rc_this_oldid',
				[ 'rc_id' => $rc_id ],
				__METHOD__
			);
		}

		if ( $log_id && !$rev_id ) {
			$rev_id = $dbw->selectField(
				'log_search',
				'ls_value',
				[ 'ls_field' => 'associated_rev_id', 'ls_log_id' => $log_id ],
				__METHOD__
			);
		} elseif ( !$log_id && $rev_id ) {
			$log_id = $dbw->selectField(
				'log_search',
				'ls_log_id',
				[ 'ls_field' => 'associated_rev_id', 'ls_value' => $rev_id ],
				__METHOD__
			);
		}

		// update the tag_summary row
		$prevTags = [];
		if ( !self::updateTagSummaryRow( $tagsToAdd, $tagsToRemove, $rc_id, $rev_id,
			$log_id, $prevTags ) ) {

			// nothing to do
			return [ [], [], $prevTags ];
		}

		// insert a row into change_tag for each new tag
		if ( count( $tagsToAdd ) ) {
			$tagsRows = [];
			foreach ( $tagsToAdd as $tag ) {
				// Filter so we don't insert NULLs as zero accidentally.
				// Keep in mind that $rc_id === null means "I don't care/know about the
				// rc_id, just delete $tag on this revision/log entry". It doesn't
				// mean "only delete tags on this revision/log WHERE rc_id IS NULL".
				$tagsRows[] = array_filter(
					[
						'ct_tag' => $tag,
						'ct_rc_id' => $rc_id,
						'ct_log_id' => $log_id,
						'ct_rev_id' => $rev_id,
						'ct_params' => $params
					]
				);
			}

			$dbw->insert( 'change_tag', $tagsRows, __METHOD__, [ 'IGNORE' ] );
		}

		// delete from change_tag
		if ( count( $tagsToRemove ) ) {
			foreach ( $tagsToRemove as $tag ) {
				$conds = array_filter(
					[
						'ct_tag' => $tag,
						'ct_rc_id' => $rc_id,
						'ct_log_id' => $log_id,
						'ct_rev_id' => $rev_id
					]
				);
				$dbw->delete( 'change_tag', $conds, __METHOD__ );
			}
		}
		ChangeTagsContext::clearCachesAfterUpdate( $tagsToAdd, $tagsToRemove );

		return [ $tagsToAdd, $tagsToRemove, $prevTags ];
	}

	/**
	 * Adds or removes a given set of tags to/from the relevant row of the
	 * tag_summary table. Modifies the tagsToAdd and tagsToRemove arrays to
	 * reflect the tags that were actually added and/or removed.
	 *
	 * @param array &$tagsToAdd
	 * @param array &$tagsToRemove If a tag is present in both $tagsToAdd and
	 * $tagsToRemove, it will be removed
	 * @param int|null $rc_id Null if not known or not applicable
	 * @param int|null $rev_id Null if not known or not applicable
	 * @param int|null $log_id Null if not known or not applicable
	 * @param array &$prevTags Optionally outputs a list of the tags that were
	 * in the tag_summary row to begin with
	 * @return bool True if any modifications were made, otherwise false
	 * @since 1.25
	 */
	protected static function updateTagSummaryRow( &$tagsToAdd, &$tagsToRemove,
		$rc_id, $rev_id, $log_id, &$prevTags = [] ) {

		$dbw = wfGetDB( DB_MASTER );

		$tsConds = array_filter( [
			'ts_rc_id' => $rc_id,
			'ts_rev_id' => $rev_id,
			'ts_log_id' => $log_id
		] );

		// Can't both add and remove a tag at the same time...
		$tagsToAdd = array_diff( $tagsToAdd, $tagsToRemove );

		// Update the summary row.
		// $prevTags can be out of date on replica DBs, especially when addTags is called consecutively,
		// causing loss of tags added recently in tag_summary table.
		$prevTags = $dbw->selectField( 'tag_summary', 'ts_tags', $tsConds, __METHOD__ );
		$prevTags = $prevTags ? $prevTags : '';
		$prevTags = array_filter( explode( ',', $prevTags ) );

		// add tags
		$tagsToAdd = array_values( array_diff( $tagsToAdd, $prevTags ) );
		$newTags = array_unique( array_merge( $prevTags, $tagsToAdd ) );

		// remove tags
		$tagsToRemove = array_values( array_intersect( $tagsToRemove, $newTags ) );
		$newTags = array_values( array_diff( $newTags, $tagsToRemove ) );

		sort( $prevTags );
		sort( $newTags );
		if ( $prevTags == $newTags ) {
			// No change.
			return false;
		}

		if ( !$newTags ) {
			// no tags left, so delete the row altogether
			$dbw->delete( 'tag_summary', $tsConds, __METHOD__ );
		} else {
			$dbw->replace( 'tag_summary',
				[ 'ts_rev_id', 'ts_rc_id', 'ts_log_id' ],
				array_filter( array_merge( $tsConds, [ 'ts_tags' => implode( ',', $newTags ) ] ) ),
				__METHOD__
			);
		}

		return true;
	}

	/**
	 * Permanently removes all traces of a tag from the DB. Good for removing
	 * misspelt or temporary tags.
	 *
	 * This function should be directly called by maintenance scripts only, never
	 * by user-facing code. See deleteTagWithChecks() for functionality that can
	 * safely be exposed to users.
	 *
	 * @param string $tag Tag to remove
	 * @return Status The returned status will be good unless a hook changed it
	 * @since 1.25
	 */
	public static function deleteTagEverywhere( $tag ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->startAtomic( __METHOD__ );

		// delete from valid_tag
		$dbw->delete( 'valid_tag',
			[ 'vt_tag' => $tag ],
			__METHOD__ );

		// find out which revisions use this tag, so we can delete from tag_summary
		$result = $dbw->select( 'change_tag',
			[ 'ct_rc_id', 'ct_log_id', 'ct_rev_id', 'ct_tag' ],
			[ 'ct_tag' => $tag ],
			__METHOD__ );
		foreach ( $result as $row ) {
			// remove the tag from the relevant row of tag_summary
			$tagsToAdd = [];
			$tagsToRemove = [ $tag ];
			self::updateTagSummaryRow( $tagsToAdd, $tagsToRemove, $row->ct_rc_id,
				$row->ct_rev_id, $row->ct_log_id );
		}

		// delete from change_tag
		$dbw->delete( 'change_tag', [ 'ct_tag' => $tag ], __METHOD__ );

		$dbw->endAtomic( __METHOD__ );

		// give extensions a chance
		$status = Status::newGood();
		Hooks::run( 'ChangeTagAfterDelete', [ $tag, &$status ] );
		// let's not allow error results, as the actual tag deletion succeeded
		if ( !$status->isOK() ) {
			wfDebug( 'ChangeTagAfterDelete error condition downgraded to warning' );
			$status->setOK( true );
		}

		// purge all caches
		ChangeTagsContext::purgeTagCacheAll();

		return $status;
	}
}
