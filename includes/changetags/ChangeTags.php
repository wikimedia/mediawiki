<?php
/**
 * Recent changes tagging.
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

class ChangeTags {

	/**
	 * Creates HTML for the given tags
	 *
	 * @param string $tags Comma-separated list of tags
	 * @param string $page A label for the type of action which is being displayed,
	 *   for example: 'history', 'contributions' or 'newpages'
	 * @return array Array with two items: (html, classes)
	 *   - html: String: HTML for displaying the tags (empty string when param $tags is empty)
	 *   - classes: Array of strings: CSS classes used in the generated html, one class for each tag
	 */
	public static function formatSummaryRow( $tags, $page ) {
		global $wgLang;

		if ( !$tags ) {
			return array( '', array() );
		}

		$classes = array();

		$tags = explode( ',', $tags );
		$displayTags = array();
		foreach ( $tags as $tag ) {
			if ( !$tag ) {
				continue;
			}
			$description = self::tagDescription( $tag );
			if ( $description === false ) {
				continue;
			}
			$displayTags[] = Xml::tags(
				'span',
				array( 'class' => 'mw-tag-marker ' .
								Sanitizer::escapeClass( "mw-tag-marker-$tag" ) ),
				$description
			);
			$classes[] = Sanitizer::escapeClass( "mw-tag-$tag" );
		}

		if ( !$displayTags ) {
			return array( '', array() );
		}

		$markers = wfMessage( 'tag-list-wrapper' )
			->numParams( count( $displayTags ) )
			->rawParams( $wgLang->commaList( $displayTags ) )
			->parse();
		$markers = Xml::tags( 'span', array( 'class' => 'mw-tag-markers' ), $markers );

		return array( $markers, $classes );
	}

	/**
	 * Get a short description for a tag.
	 *
	 * Checks if message key "mediawiki:tag-$tag" exists. If it does not,
	 * returns the HTML-escaped tag name. Uses the message if the message
	 * exists, provided it is not disabled. If the message is disabled,
	 * we consider the tag hidden, and return false.
	 *
	 * @param string $tag Tag
	 * @return string|bool Tag description or false if tag is to be hidden.
	 * @since 1.25 Returns false if tag is to be hidden.
	 */
	public static function tagDescription( $tag ) {
		$msg = wfMessage( "tag-$tag" );
		if ( !$msg->exists() ) {
			// No such message, so return the HTML-escaped tag name.
			return htmlspecialchars( $tag );
		}
		if ( $msg->isDisabled() ) {
			// The message exists but is disabled, hide the tag.
			return false;
		}

		// Message exists and isn't disabled, use it.
		return $msg->parse();
	}

	/**
	 * Add tags to a change given its rc_id, rev_id and/or log_id
	 *
	 * @param string|array $tags Tags to add to the change
	 * @param int|null $rc_id The rc_id of the change to add the tags to
	 * @param int|null $rev_id The rev_id of the change to add the tags to
	 * @param int|null $log_id The log_id of the change to add the tags to
	 * @param string $params Params to put in the ct_params field of table 'change_tag'
	 *
	 * @throws MWException
	 * @return bool False if no changes are made, otherwise true
	 */
	public static function addTags( $tags, $rc_id = null, $rev_id = null,
		$log_id = null, $params = null
	) {
		$result = self::updateTags( $tags, null, $rc_id, $rev_id, $log_id, $params );
		return (bool)$result[0];
	}

	/**
	 * Add and remove tags to/from a change given its rc_id, rev_id and/or log_id,
	 * without verifying that the tags exist or are valid. If a tag is present in
	 * both $tagsToAdd and $tagsToRemove, it will be removed.
	 *
	 * This function should only be used by extensions to manipulate tags they
	 * have registered using the ListDefinedTags hook. When dealing with user
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
	public static function updateTags( $tagsToAdd, $tagsToRemove, &$rc_id = null,
		&$rev_id = null, &$log_id = null, $params = null ) {
		global $wgMemc;

		$tagsToAdd = array_filter( (array)$tagsToAdd ); // Make sure we're submitting all tags...
		$tagsToRemove = array_filter( (array)$tagsToRemove );

		if ( !$rc_id && !$rev_id && !$log_id ) {
			throw new MWException( 'At least one of: RCID, revision ID, and log ID MUST be ' .
				'specified when adding or removing a tag from a change!' );
		}

		$dbw = wfGetDB( DB_MASTER );

		// Might as well look for rcids and so on.
		if ( !$rc_id ) {
			// Info might be out of date, somewhat fractionally, on slave.
			if ( $log_id ) {
				$rc_id = $dbw->selectField(
					'recentchanges',
					'rc_id',
					array( 'rc_logid' => $log_id ),
					__METHOD__
				);
			} elseif ( $rev_id ) {
				$rc_id = $dbw->selectField(
					'recentchanges',
					'rc_id',
					array( 'rc_this_oldid' => $rev_id ),
					__METHOD__
				);
			}
		} elseif ( !$log_id && !$rev_id ) {
			// Info might be out of date, somewhat fractionally, on slave.
			$log_id = $dbw->selectField(
				'recentchanges',
				'rc_logid',
				array( 'rc_id' => $rc_id ),
				__METHOD__
			);
			$rev_id = $dbw->selectField(
				'recentchanges',
				'rc_this_oldid',
				array( 'rc_id' => $rc_id ),
				__METHOD__
			);
		}

		// update the tag_summary row
		$prevTags = array();
		if ( !self::updateTagSummaryRow( $tagsToAdd, $tagsToRemove, $rc_id, $rev_id,
			$log_id, $prevTags ) ) {

			// nothing to do
			return array( array(), array(), $prevTags );
		}

		// insert a row into change_tag for each new tag
		if ( count( $tagsToAdd ) ) {
			$tagsRows = array();
			foreach ( $tagsToAdd as $tag ) {
				// Filter so we don't insert NULLs as zero accidentally.
				// Keep in mind that $rc_id === null means "I don't care/know about the
				// rc_id, just delete $tag on this revision/log entry". It doesn't
				// mean "only delete tags on this revision/log WHERE rc_id IS NULL".
				$tagsRows[] = array_filter(
					array(
						'ct_tag' => $tag,
						'ct_rc_id' => $rc_id,
						'ct_log_id' => $log_id,
						'ct_rev_id' => $rev_id,
						'ct_params' => $params
					)
				);
			}
			$dbw->insert( 'change_tag', $tagsRows, __METHOD__, array( 'IGNORE' ) );

			// Increment cache
			$wgMemc->incr( wfMemcKey( 'ChangeTags', 'tag-hitcount', "$tag" ) );
		}

		// delete from change_tag
		if ( count( $tagsToRemove ) ) {
			foreach ( $tagsToRemove as $tag ) {
				$conds = array_filter(
					array(
						'ct_tag' => $tag,
						'ct_rc_id' => $rc_id,
						'ct_log_id' => $log_id,
						'ct_rev_id' => $rev_id
					)
				);
				$dbw->delete( 'change_tag', $conds, __METHOD__ );

				// Decrement cache
				$wgMemc->decr( wfMemcKey( 'ChangeTags', 'tag-hitcount', "$tag" ) );
			}
		}

		return array( $tagsToAdd, $tagsToRemove, $prevTags );
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
		$rc_id, $rev_id, $log_id, &$prevTags = array() ) {

		$dbw = wfGetDB( DB_MASTER );

		$tsConds = array_filter( array(
			'ts_rc_id' => $rc_id,
			'ts_rev_id' => $rev_id,
			'ts_log_id' => $log_id
		) );

		// Can't both add and remove a tag at the same time...
		$tagsToAdd = array_diff( $tagsToAdd, $tagsToRemove );

		// Update the summary row.
		// $prevTags can be out of date on slaves, especially when addTags is called consecutively,
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
				array( 'ts_rev_id', 'ts_rc_id', 'ts_log_id' ),
				array_filter( array_merge( $tsConds, array( 'ts_tags' => implode( ',', $newTags ) ) ) ),
				__METHOD__
			);
		}

		return true;
	}

	/**
	 * Helper function to generate a fatal status with a 'not-allowed' type error.
	 *
	 * @param string $msgOne Message key to use in the case of one tag
	 * @param string $msgMulti Message key to use in the case of more than one tag
	 * @param array $tags Restricted tags (passed as $1 into the message, count of
	 * $tags passed as $2)
	 * @return Status
	 * @since 1.25
	 */
	protected static function restrictedTagError( $msgOne, $msgMulti, $tags ) {
		$lang = RequestContext::getMain()->getLanguage();
		$count = count( $tags );
		return Status::newFatal( ( $count > 1 ) ? $msgMulti : $msgOne,
			$lang->commaList( $tags ), $count );
	}

	/**
	 * Is it OK to allow the user to apply all the specified tags at the same time
	 * as they edit/make the change?
	 *
	 * @param array $tags Tags that you are interested in applying
	 * @param User|null $user User whose permission you wish to check, or null if
	 * you don't care (e.g. maintenance scripts)
	 * @return Status
	 * @since 1.25
	 */
	public static function canAddTagsAccompanyingChange( array $tags,
		User $user = null ) {

		if ( !is_null( $user ) && !$user->isAllowed( 'applychangetags' ) ) {
			return Status::newFatal( 'tags-apply-no-permission' );
		}

		// to be applied, a tag has to be user defined
		// @todo Allow extensions to define tags that can be applied by users...
		$allowedTags = array_diff_key( self::getStoredTags(), self::getRegisteredTags() );
		$disallowedTags =array();
		foreach ( $tags as $tag ) {
			if ( !isset( $allowedTags[$tag] ) ) {
				$disallowedTags[] = $tag;
			}
		}
		if ( $disallowedTags ) {
			return self::restrictedTagError( 'tags-apply-not-allowed-one',
				'tags-apply-not-allowed-multi', $disallowedTags );
		}

		return Status::newGood();
	}

	/**
	 * Adds tags to a given change, checking whether it is allowed first, but
	 * without adding a log entry. Useful for cases where the tag is being added
	 * along with the action that generated the change (e.g. tagging an edit as
	 * it is being made).
	 *
	 * Extensions should not use this function, unless directly handling a user
	 * request to add a particular tag. Normally, extensions should call
	 * ChangeTags::updateTags() instead.
	 *
	 * @param array $tags Tags to apply
	 * @param int|null $rc_id The rc_id of the change to add the tags to
	 * @param int|null $rev_id The rev_id of the change to add the tags to
	 * @param int|null $log_id The log_id of the change to add the tags to
	 * @param string $params Params to put in the ct_params field of table
	 * 'change_tag' when adding tags
	 * @param User $user Who to give credit for the action
	 * @return Status
	 * @since 1.25
	 */
	public static function addTagsAccompanyingChangeWithChecks( array $tags,
		$rc_id, $rev_id, $log_id, $params, User $user ) {

		// are we allowed to do this?
		$result = self::canAddTagsAccompanyingChange( $tags, $user );
		if ( !$result->isOK() ) {
			$result->value = null;
			return $result;
		}

		// do it!
		self::addTags( $tagsToAdd, $rc_id, $rev_id, $log_id, $params );

		return Status::newGood( true );
	}

	/**
	 * Is it OK to allow the user to adds and remove the given tags tags to/from a
	 * change?
	 *
	 * @param array $tagsToAdd Tags that you are interested in adding
	 * @param array $tagsToRemove Tags that you are interested in removing
	 * @param User|null $user User whose permission you wish to check, or null if
	 * you don't care (e.g. maintenance scripts)
	 * @return Status
	 * @since 1.25
	 */
	public static function canUpdateTags( array $tagsToAdd, array $tagsToRemove,
		User $user = null ) {

		if ( !is_null( $user ) && !$user->isAllowed( 'changetags' ) ) {
			return Status::newFatal( 'tags-update-no-permission' );
		}

		$storedTags = self::getStoredTags();
		$registeredTags = self::getRegisteredTags();
		$userDefinedTags = array_diff_key( $storedTags, $registeredTags );

		// to be added, a tag has to be user defined
		// @todo Allow extensions to define tags that can be applied by users...
		$disallowedTags =array();
		foreach ( $tagsToAdd as $tag ) {
			if ( !isset( $userDefinedTags[$tag] ) ) {
				$disallowedTags[] = $tag;
			}
		}
		if ( $disallowedTags ) {
			return self::restrictedTagError( 'tags-update-add-not-allowed-one',
				'tags-update-add-not-allowed-multi', $disallowedTags );
		}

		// to be removed, a tag has to be either user defined or not defined
		// at all
		$disallowedTags =array();
		foreach( $tagsToRemove as $tag ) {
			if ( !isset( $userDefinedTags[$tag] ) && isset( $registeredTags[$tag] ) ) {
				$disallowedTags[] = $tag;
			}
		}
		if ( $disallowedTags ) {
			return self::restrictedTagError( 'tags-update-remove-not-allowed-one',
				'tags-update-remove-not-allowed-multi', $disallowedTags );
		}

		return Status::newGood();
	}

	/**
	 * Adds and/or removes tags to/from a given change, checking whether it is
	 * allowed first, and adding a log entry afterwards.
	 *
	 * Includes a call to ChangeTag::canUpdateTags(), so your code doesn't need
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
	 * @param User $user Who to give credit for the action
	 * @return Status If successful, the value of this Status object will be an
	 * object (stdClass) with the following fields:
	 *  - logId: the ID of the added log entry, or null if no log entry was added
	 *    (i.e. no operation was performed)
	 *  - addedTags: an array containing the tags that were actually added
	 *  - removedTags: an array containing the tags that were actually removed
	 * @since 1.25
	 */
	public static function updateTagsWithChecks( $tagsToAdd, $tagsToRemove,
		$rc_id, $rev_id, $log_id, $params, $reason, User $user ) {

		if ( is_null( $tagsToAdd ) ) {
			$tagsToAdd = array();
		}
		if ( is_null( $tagsToRemove ) ) {
			$tagsToRemove = array();
		}
		if ( !$tagsToAdd && !$tagsToRemove ) {
			// no-op, don't bother
			return Status::newGood( (object)array(
				'logId' => null,
				'addedTags' => array(),
				'removedTags' => array(),
			) );
		}

		// are we allowed to do this?
		$result = self::canUpdateTags( $tagsToAdd, $tagsToRemove, $user );
		if ( !$result->isOK() ) {
			$result->value = null;
			return $result;
		}

		// basic rate limiting
		if ( $user->pingLimiter( 'changetag' ) ) {
			return Status::newFatal( 'actionthrottledtext' );
		}

		// do it!
		list( $tagsAdded, $tagsRemoved, $initialTags ) = self::updateTags( $tagsToAdd,
			$tagsToRemove, $rc_id, $rev_id, $log_id, $params );
		if ( !$tagsAdded && !$tagsRemoved ) {
			// no-op, don't log it
			return Status::newGood( (object)array(
				'logId' => null,
				'addedTags' => array(),
				'removedTags' => array(),
			) );
		}

		// log it
		$logEntry = new ManualLogEntry( 'tag', 'update' );
		$logEntry->setPerformer( $user );
		$logEntry->setComment( $reason );

		// find the appropriate target page
		if ( $rev_id ) {
			$rev = Revision::newFromId( $rev_id );
			if ( $rev ) {
				$title = $rev->getTitle();
				$logEntry->setTarget( $rev->getTitle() );
			}
		} elseif ( $log_id ) {
			// This function is from revision deletion logic and has nothing to do with
			// change tags, but it appears to be the only other place in core where we
			// perform logged actions on log items.
			$logEntry->setTarget( RevDelLogList::suggestTarget( 0, array( $log_id ) ) );
		}

		if ( !$logEntry->getTarget() ) {
			// target is required, so we have to set something
			$logEntry->setTarget( SpecialPage::getTitleFor( 'Tags' ) );
		}

		$logParams = array(
			'4::revid' => $rev_id,
			'5::logid' => $log_id,
			'6:list:tagsAdded' => $tagsAdded,
			'7:number:tagsAddedCount' => count( $tagsAdded ),
			'8:list:tagsRemoved' => $tagsRemoved,
			'9:number:tagsRemovedCount' => count( $tagsRemoved ),
			'initialTags' => $initialTags,
		);
		$logEntry->setParameters( $logParams );
		$logEntry->setRelations( array( 'Tag' => array_merge( $tagsAdded, $tagsRemoved ) ) );

		$dbw = wfGetDB( DB_MASTER );
		$logId = $logEntry->insert( $dbw );
		// Only send this to UDP, not RC, similar to patrol events
		$logEntry->publish( $logId, 'udp' );

		return Status::newGood( (object)array(
			'logId' => $logId,
			'addedTags' => $tagsAdded,
			'removedTags' => $tagsRemoved,
		) );
	}

	/**
	 * Applies all tags-related changes to a query.
	 * Handles selecting tags, and filtering.
	 * Needs $tables to be set up properly, so we can figure out which join conditions to use.
	 *
	 * @param string|array $tables Table names, see DatabaseBase::select
	 * @param string|array $fields Fields used in query, see DatabaseBase::select
	 * @param string|array $conds Conditions used in query, see DatabaseBase::select
	 * @param array $join_conds Join conditions, see DatabaseBase::select
	 * @param array $options Options, see Database::select
	 * @param bool|string $filter_tag Tag to select on
	 *
	 * @throws MWException When unable to determine appropriate JOIN condition for tagging
	 */
	public static function modifyDisplayQuery( &$tables, &$fields, &$conds,
										&$join_conds, &$options, $filter_tag = false ) {
		global $wgRequest, $wgUseTagFilter;

		if ( $filter_tag === false ) {
			$filter_tag = $wgRequest->getVal( 'tagfilter' );
		}

		// Figure out which conditions can be done.
		if ( in_array( 'recentchanges', $tables ) ) {
			$join_cond = 'ct_rc_id=rc_id';
		} elseif ( in_array( 'logging', $tables ) ) {
			$join_cond = 'ct_log_id=log_id';
		} elseif ( in_array( 'revision', $tables ) ) {
			$join_cond = 'ct_rev_id=rev_id';
		} elseif ( in_array( 'archive', $tables ) ) {
			$join_cond = 'ct_rev_id=ar_rev_id';
		} else {
			throw new MWException( 'Unable to determine appropriate JOIN condition for tagging.' );
		}

		$fields['ts_tags'] = wfGetDB( DB_SLAVE )->buildGroupConcatField(
			',', 'change_tag', 'ct_tag', $join_cond
		);

		if ( $wgUseTagFilter && $filter_tag ) {
			// Somebody wants to filter on a tag.
			// Add an INNER JOIN on change_tag

			$tables[] = 'change_tag';
			$join_conds['change_tag'] = array( 'INNER JOIN', $join_cond );
			$conds['ct_tag'] = $filter_tag;
		}
	}

	/**
	 * Build a text box to select a change tag
	 *
	 * @param string $selected Tag to select by default
	 * @param bool $fullForm
	 *        - if false, then it returns an array of (label, form).
	 *        - if true, it returns an entire form around the selector.
	 * @param Title $title Title object to send the form to.
	 *        Used when, and only when $fullForm is true.
	 * @param array $tagList the list of tags to check
	 * (by default, tags applied at least once on the wiki)
	 * @return string|array
	 *        - if $fullForm is false: Array with
	 *        - if $fullForm is true: String, html fragment
	 */
	public static function buildTagFilterSelector( $selected = '',
		$fullForm = false, Title $title = null
	) {
		global $wgUseTagFilter;

		// by default, we check the list of tags applied at least once
		if ( $tagList == null ) {
			$tagList = self::getAppliedTags();
		}

		// check config and if the list of tags is not empty
		// @todo use the list of tags to build a dropdown menu, an autocomplete form or some hybrid
		if ( !$wgUseTagFilter || !count( $tagList ) ) {
			return $fullForm ? '' : array();
		}

		$data = array(
			Html::rawElement(
				'label',
				array( 'for' => 'tagfilter' ),
				wfMessage( 'tag-filter' )->parse()
			),
			Xml::input(
				'tagfilter',
				20,
				$selected,
				array( 'class' => 'mw-tagfilter-input mw-ui-input mw-ui-input-inline', 'id' => 'tagfilter' )
			)
		);

		if ( !$fullForm ) {
			return $data;
		}

		$html = implode( '&#160;', $data );
		$html .= "\n" .
			Xml::element(
				'input',
				array( 'type' => 'submit', 'value' => wfMessage( 'tag-filter-submit' )->text() )
			);
		$html .= "\n" . Html::hidden( 'title', $title->getPrefixedText() );
		$html = Xml::tags(
			'form',
			array( 'action' => $title->getLocalURL(), 'class' => 'mw-tagfilter-form', 'method' => 'get' ),
			$html
		);

		return $html;
	}

	/**
	 * Writes a tag action into the tag management log.
	 *
	 * @param string $action
	 * @param string $tag
	 * @param string $reason
	 * @param User $user Who to attribute the action to
	 * @param int $tagCount For deletion only, how many usages the tag had before
	 * it was deleted.
	 * @since 1.25
	 */
	protected static function logTagManagementAction( $action, $tag, $reason,
		User $user, $tagCount = null ) {

		$dbw = wfGetDB( DB_MASTER );

		$logEntry = new ManualLogEntry( 'managetags', $action );
		$logEntry->setPerformer( $user );
		// target page is not relevant, but it has to be set, so we just put in
		// the title of Special:Tags
		$logEntry->setTarget( Title::newFromText( 'Special:Tags' ) );
		$logEntry->setComment( $reason );

		$params = array( '4::tag' => $tag );
		if ( !is_null( $tagCount ) ) {
			$params['5:number:count'] = $tagCount;
		}
		$logEntry->setParameters( $params );
		$logEntry->setRelations( array( 'Tag' => $tag ) );

		$logId = $logEntry->insert( $dbw );
		$logEntry->publish( $logId );
		return $logId;
	}

	/**
	 * Activates a tag, checking whether it is allowed first, and adding a log
	 * entry afterwards.
	 *
	 * Includes a call to ChangeTag::canActivate(), so your code doesn't need
	 * to do that.
	 *
	 * @param string $tag
	 * @param string $reason
	 * @param User $user Who to give credit for the action
	 * @param bool $ignoreWarnings Can be used for API interaction, default false
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 * @since 1.25
	 */
	public static function activateTagWithChecks( $tag, $reason, User $user,
		$ignoreWarnings = false ) {

		// some of the caches might be outdated due to extensions not purging them
		self::purgeTagCacheAll( $tag );

		// get change tag object
		$changeTag = ChangeTag::getChangeTagObject( $tag );

		// are we allowed to do this?
		$result = $changeTag->canActivate( $user );
		if ( $ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
			$result->value = null;
			return $result;
		}

		// do it!
		ChangeTag::defineTag( $tag );

		// log it
		$logId = self::logTagManagementAction( 'activate', $tag, $reason, $user );
		return Status::newGood( $logId );
	}

	/**
	 * Deactivates a tag, checking whether it is allowed first, and adding a log
	 * entry afterwards.
	 *
	 * Includes a call to ChangeTag::canDeactivate(), so your code doesn't need
	 * to do that.
	 *
	 * @param string $tag
	 * @param string $reason
	 * @param User $user Who to give credit for the action
	 * @param bool $ignoreWarnings Can be used for API interaction, default false
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 * @since 1.25
	 */
	public static function deactivateTagWithChecks( $tag, $reason, User $user,
		$ignoreWarnings = false ) {

		// some of the caches might be outdated due to extensions not purging them
		self::purgeTagCacheAll( $tag );

		// get change tag object
		$changeTag = ChangeTag::getChangeTagObject( $tag );

		// are we allowed to do this?
		$result = $changeTag->canDeactivate( $user );
		if ( $ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
			$result->value = null;
			return $result;
		}

		// do it!
		ChangeTag::undefineTag( $tag );

		// log it
		$logId = self::logTagManagementAction( 'deactivate', $tag, $reason, $user );
		return Status::newGood( $logId );
	}

	/**
	 * Creates a tag by adding a row to the `valid_tag` table.
	 *
	 * Includes a call to ChangeTag::canCreate(), so your code doesn't need to
	 * do that.
	 *
	 * @param string $tag
	 * @param string $reason
	 * @param User $user Who to give credit for the action
	 * @param bool $ignoreWarnings Can be used for API interaction, default false
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 * @since 1.25
	 */
	public static function createTagWithChecks( $tag, $reason, User $user,
		$ignoreWarnings = false ) {

		// some of the caches might be outdated due to extensions not purging them
		self::purgeTagCacheAll( $tag );

		// get change tag object
		$changeTag = ChangeTag::getChangeTagObject( $tag );

		// are we allowed to do this?
		$result = $changeTag->canCreate( $user );
		if ( $ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
			$result->value = null;
			return $result;
		}

		// do it!
		ChangeTag::defineTag( $tag );
		self::purgeTagUsageCache( $tag );

		// log it
		$logId = self::logTagManagementAction( 'create', $tag, $reason, $user );
		return Status::newGood( $logId );
	}

	/**
	 * Deletes a tag, checking whether it is allowed first, and adding a log entry
	 * afterwards.
	 *
	 * Includes a call to ChangeTag::canDelete(), so your code doesn't need to
	 * do that.
	 *
	 * @param string $tag
	 * @param string $reason
	 * @param User $user Who to give credit for the action
	 * @param bool $ignoreWarnings Can be used for API interaction, default false
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 * @since 1.25
	 */
	public static function deleteTagWithChecks( $tag, $reason, User $user,
		$ignoreWarnings = false ) {

		// some of the caches might be outdated due to extensions not purging them
		self::purgeTagCacheAll( $tag );

		// get change tag object
		$changeTag = ChangeTag::getChangeTagObject( $tag );

		// are we allowed to do this?
		$result = $changeTag->canDelete( $user );
		if ( $ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
			$result->value = null;
			return $result;
		}

		// do it!
		$deleteResult = ChangeTag::deleteTagEverywhere( $tag );
		if ( !$deleteResult->isOK() ) {
			return $deleteResult;
		}

		// log it
		$logId = self::logTagManagementAction( 'delete', $tag, $reason, $user, $changeTag->hitcount );
		$deleteResult->value = $logId;
		return $deleteResult;
	}

	/**
	 * Gets tags stored in the `valid_tag` table of the database.
	 * Tags in table 'change_tag' which are not in table 'valid_tag' are not
	 * included.
	 * The keys are the tag names and the values are arrays of params (none for now).
	 *
	 * Tries memcached first.
	 *
	 * @return Array of strings: tags => array of params
	 * @since 1.26
	 */
	public static function getStoredTags() {
		global $wgMemc;
		// Attempting to retrieve from cache...
		$key = wfMemcKey( 'ChangeTags', 'valid-tags-db' );
		$storedTags = $wgMemc->get( $key );

		// If not in cache, db query
		if ( $storedTags == false ) {
			$dbr = wfGetDB( DB_SLAVE );
			$res = $dbr->select( 'valid_tag', array( 'vt_tag'),
				array(), __METHOD__ );

			// Filling array mapping tags to their params
			$storedTags = array();
			foreach ( $res as $row ) {
				$storedTags[$row->vt_tag] = array(
					// tags stored in the valid_tag table are assumed to be active
					// @todo: store this in a new field of valid_tag so that tags
					// previously used by extensions cannot be activated if not
					// deleted beforehand, and modify actions accordingly
					'active' => true,
				);
			}

			// removing nulls inserted as keys
			unset( $storedTags[''] );

			// Caching for a long time (a week), since
			// operations on valid_tag clear this cache
			$wgMemc->set( $key, $storedTags, 60*60*24*7 );
		}

		return $storedTags;
	}

	/**
	 * Gets tags registered by extensions using the ChangeTagsRegister hook.
	 * Extensions need only define those tags they deem to be in active use,
	 * or that have been actively used in the past and are likely to be
	 * actively used again in the future.
	 *
	 * Tries memcached first.
	 *
	 * @return Array of strings: tags => arrays of params
	 * @since 1.26
	 */
	public static function getRegisteredTags() {
		global $wgMemc;
		// Attempting to retrieve from cache...
		$key = wfMemcKey( 'ChangeTags', 'valid-tags-hook' );
		$registeredTags = $wgMemc->get( $key );

		// If not in cache, ask extensions
		if ( $registeredTags == false ) {
			// hack for ListDefinedTags hook until deprecated
			$extensionDefined = array();
			Hooks::run( 'ListDefinedTags', array( &$extensionDefined ) );
			// Filling with param 'active' set to false
			$extensionDefined = array_fill_keys( $extensionDefined,
				array() );

			// hack for ChangeTagsListActive hook until deprecated
			$extensionActive = array();
			Hooks::run( 'ChangeTagsListActive', array( &$extensionActive ) );
			// Filling with arrays with param 'active' set to true
			$extensionActive = array_fill_keys( $extensionActive,
				array( 'active' => true ) );

			// Merging, with ChangeTagsListActive overriding ListDefinedTags
			$registeredTags = array_merge( $extensionDefined, $extensionActive );
			// Applying the new hook, tags as keys and array of params as values
			Hooks::run( 'ChangeTagsRegister', array( &$registeredTags ) );

			// removing nulls inserted as keys
			unset( $registeredTags[''] );

			// Caching for a very short time (5 minutes), since extensions may
			// register or unregister tags without clearing the cache (which
			// should be done with the purgeRegisteredTagsCache function).
			// @todo AbuseFilter should do it (the current 5 minutes wait is
			// clearly noticeable), maybe also a few other extensions that need to.
			// When done, we'll be able to increase the cache duration if needed.
			$wgMemc->set( $key, $registeredTags, 60*5 );
		}
		return $registeredTags;
	}

	/**
	 * Gets an array mapping tags applied at least once to their hitcount
	 *
	 * Tags defined somewhere but not applied are not included.
	 *
	 * @param bool $upToDateHitcounts whether to fetch the most up to date hitcounts
	 * or only hitcounts of an older cache (delay of up to 24 hours)
	 * @return array Array of tags mapped to their hitcount
	 * @since 1.26
	 */
	public static function buildTagUsageStatistics( $upToDateHitcounts = true ) {
		global $wgMemc, $wgTagUsageCacheDuration;

		// Try to retrieve cached array mapping tags to their hitcount
		$commonKey = wfMemcKey( 'ChangeTags', 'tag-usage-stats' );
		$changeTags = $wgMemc->get( $commonKey );

		// If the common cache exists and we want up to date hitcounts, get them
		if ( $changeTags != false && $upToDateHitcounts ) {
			foreach ( $changeTags as $tag => $hitcount ) {
				$hitcountKey = wfMemcKey( 'ChangeTags', 'tag-hitcount', "$tag" );
				// getting most up to date hitcount from specific hitcount cache
				$hitcount = $wgMemc->get( $hitcountKey );
				if ( $hitcount != false ) {
					// Mapping tags to their hitcount
					$changeTags[$tag] = $hitcount;
				} else {
					// Cache attempt failed
					// (normally, this should not happen)
					// So do DB query
					$changeTags = false;
					break;
				}
			}
		}

		// Database query and cache rebuild
		if ( $changeTags == false ) {
			$dbr = wfGetDB( DB_SLAVE );
			$res = $dbr->select(
				'change_tag',
				array( 'ct_tag', 'hitcount' => 'count(*)' ),
				array(),
				__METHOD__,
				array( 'GROUP BY' => 'ct_tag', 'ORDER BY' => 'hitcount DESC' )
			);

			$changeTags = array();
			foreach ( $res as $row ) {
				$tag = $row->ct_tag;
				$hitcount = $row->hitcount;
				// Mapping tags to their hitcount
				$changeTags[$tag] = $hitcount;
				// Caching for a week (gets incremented when applied, decremented when unapplied)
				$hitcountKey = wfMemcKey( 'ChangeTags', 'tag-hitcount', "$tag" );
				$wgMemc->set( $hitcountKey, $hitcount, 60*60*24*7 );
			}

			// removing nulls inserted as keys
			unset( $changeTags[''] );
			// Caching for a moderate duration (24 hours by default)
			$wgMemc->set( $commonKey, $changeTags, $wgTagUsageCacheDuration );
		}

		// returning list of tags, or map of tags to their hitcount
		return $changeTags;
	}

	/**
	 * Returns a map of any tags used on the wiki to number of edits
	 * tagged with them, ordered descending by the hitcount as of the
	 * latest caching. The ordering may on rare occasions be incorrect
	 * since hitcounts are updated when tags are applied.
	 * Does not include tags defined somewhere but not applied
	 *
	 * @return array Array of tags mapped to their up to date hitcounts
	 * @since 1.25
	 */
	public static function tagUsageStatistics() {
		return self::buildTagUsageStatistics( true );
	}

	/**
	 * Lists all defined tags with their params.
	 * This includes tags defined in the valid_tag table or registered
	 * by an extension with the ChangeTagsRegister hook.
	 *
	 * @return array Array of strings: tags mapped to arrays of params
	 * @since 1.26
	 */
	public static function getDefinedTags() {
		return array_merge( self::getStoredTags(),
			self::getRegisteredTags() );
	}

	/**
	 * Returns an array of tags applied at least once,
	 * mapped to their cached hitcount.
	 * A delay of up to 24 hours (by default) may occur
	 * before a newly applied tag gets listed.
	 *
	 * Faster than self::tagUsageStatistics() (which returns up to date hitcounts)
	 *
	 * @param string $tag: tag
	 * @param bool $activeOnly: whether to return only active tags
	 * @param array $definedTags: tags defined on the wiki, mapped to their params
	 * @return array list of tags, mapped to their cached hitcounts
	 * @since 1.26
	 */
	public static function getAppliedTags( $activeOnly = false,
		$definedTags = null ) {
		$appliedTags = self::buildTagUsageStatistics( false );

		// shortcut
		if ( !$activeOnly ) {
			return $appliedTags;
		}

		if ( $definedTags == null ) {
			// fetching the list of defined tags
			$definedTags = self::getDefinedTags();
		}
		// filtering out tags when requested
		foreach ( $definedTags as $tag => &$tagParams ) {
			if ( $activeOnly && !$tagParams['active'] ) {
				unset( $appliedTags[$tag] );
			}
		}
		return $appliedTags;
	}

	/**
	 * Invalidates the cache of tags stored in the valid_tag table.
	 * Use case 1) alone : updating tag params
	 * Use case 2) in combination with purgeTagUsageCache :
	 * defining (incl. creating) or undefining (incl. deleting) tags
	 *
	 * @since 1.26
	 */
	public static function purgeStoredTagsCache() {
		global $wgMemc;
		$wgMemc->delete( wfMemcKey( 'ChangeTags', 'valid-tags-db' ) );
	}

	/**
	 * Invalidates the cache of tags registered by extensions.
	 * Use case 1) alone : updating tag params
	 * Use case 2) in combination with purgeTagUsageCache :
	 * defining (incl. creating) or undefining (incl. deleting) tags
	 *
	 * @since 1.26
	 */
	public static function purgeRegisteredTagsCache() {
		global $wgMemc;
		$wgMemc->delete( wfMemcKey( 'ChangeTags', 'valid-tags-hook' ) );
	}

	/**
	 * Invalidates caches related to tag usage stats
	 * Use case : defining (incl. creating) or undefining (incl. deleting) a tag
	 * This should not be used when applying the tag, the hitcount cache should
	 * be incremented instead. (And when the tag gets removed, decremented.)
	 *
	 * @param string|false $tag: (optional) tag to clear the hitcount cache of
	 * @since 1.25
	 */
	public static function purgeTagUsageCache( $tag = false ) {
		global $wgMemc;
		// delete tag usage cache
		$wgMemc->delete( wfMemcKey( 'ChangeTags', 'tag-usage-stats' ) );
		// delete cached list of never applied tags
		$wgMemc->delete( wfMemcKey( 'ChangeTags', 'never-applied-tags' ) );
		if ( $tag ) {
			// delete hitcount cache
			$wgMemc->delete( wfMemcKey( 'ChangeTags', 'tag-hitcount', "$tag" ) );
		}
	}

	/**
	 * Invalidates all tags-related caches.
	 *
	 * @param string|false $tag: (optional) tag to clear the hitcount cache of
	 * @since 1.25
	 */
	public static function purgeTagCacheAll( $tag = false ) {
		self::purgeStoredTagsCache();
		self::purgeRegisteredTagsCache();
		self::purgeTagUsageCache( $tag );
	}

	/**
	 *
	 *
	 * Deprecated functions follow
	 *
	 *
	 */

	/**
	 * Lists tags from the valid_tag table as values.
	 * Provided for backward compatibility.
	 * Should be deprecated, used anywhere ?
	 *
	 * @return string[] Array of strings: tags
	 * @since 1.25
	 */
	public static function listExplicitlyDefinedTags() {
		return array_keys( self::getStoredTags() );
	}

	/**
	 * Lists tags defined by extensions as values.
	 * Provided for backward compatibility.
	 * Should be deprecated, used anywhere ?
	 *
	 * @return string[] Array of strings: tags
	 * @since 1.25
	 */
	public static function listExtensionDefinedTags() {
		return array_keys( self::getRegisteredTags() );
	}

	/**
	 * Lists all defined tags as values.
	 * Provided for backward compatibility.
	 * Should be deprecated, used anywhere ?
	 *
	 * @return string[] Array of strings: tags
	 * @since 1.25
	 */
	public static function listDefinedTags() {
		return array_keys( self::getDefinedTags() );
	}

	/**
	 * Lists all active tags as values.
	 * Provided for backward compatibility.
	 * Should be deprecated, used anywhere ?
	 *
	 * @return string[] Array of strings: tags
	 * @since 1.25
	 */
	public static function listExtensionActivatedTags() {
		$tags = self::getRegisteredTags();
		// sorting out inactive tags
		foreach ( $tags as $tag => &$tagParams ) {
			if ( !$tagParams['active'] ) {
				unset( $tags[$tag] );
			}
		}
		return array_keys( $tags );
	}
}
