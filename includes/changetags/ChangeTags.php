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

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\Database;

class ChangeTags {
	/**
	 * Can't delete tags with more than this many uses. Similar in intent to
	 * the bigdelete user right
	 * @todo Use the job queue for tag deletion to avoid this restriction
	 */
	const MAX_DELETE_USES = 5000;

	private static $definedSoftwareTags = [
		'mw-contentmodelchange',
		'mw-new-redirect',
		'mw-removed-redirect',
		'mw-changed-redirect-target',
		'mw-blank',
		'mw-replace',
		'mw-rollback',
		'mw-undo',
	];

	/**
	 * Loads defined core tags, checks for invalid types (if not array),
	 * and filters for supported and enabled (if $all is false) tags only.
	 *
	 * @param bool $all If true, return all valid defined tags. Otherwise, return only enabled ones.
	 * @return array Array of all defined/enabled tags.
	 */
	public static function getSoftwareTags( $all = false ) {
		global $wgSoftwareTags;
		$softwareTags = [];

		if ( !is_array( $wgSoftwareTags ) ) {
			wfWarn( 'wgSoftwareTags should be associative array of enabled tags.
			Please refer to documentation for the list of tags you can enable' );
			return $softwareTags;
		}

		$availableSoftwareTags = !$all ?
			array_keys( array_filter( $wgSoftwareTags ) ) :
			array_keys( $wgSoftwareTags );

		$softwareTags = array_intersect(
			$availableSoftwareTags,
			self::$definedSoftwareTags
		);

		return $softwareTags;
	}

	/**
	 * Creates HTML for the given tags
	 *
	 * @param string $tags Comma-separated list of tags
	 * @param string $page A label for the type of action which is being displayed,
	 *   for example: 'history', 'contributions' or 'newpages'
	 * @param IContextSource|null $context
	 * @note Even though it takes null as a valid argument, an IContextSource is preferred
	 *       in a new code, as the null value is subject to change in the future
	 * @return array Array with two items: (html, classes)
	 *   - html: String: HTML for displaying the tags (empty string when param $tags is empty)
	 *   - classes: Array of strings: CSS classes used in the generated html, one class for each tag
	 */
	public static function formatSummaryRow( $tags, $page, IContextSource $context = null ) {
		if ( !$tags ) {
			return [ '', [] ];
		}
		if ( !$context ) {
			$context = RequestContext::getMain();
		}

		$classes = [];

		$tags = explode( ',', $tags );
		$displayTags = [];
		foreach ( $tags as $tag ) {
			if ( !$tag ) {
				continue;
			}
			$description = self::tagDescription( $tag, $context );
			if ( $description === false ) {
				continue;
			}
			$displayTags[] = Xml::tags(
				'span',
				[ 'class' => 'mw-tag-marker ' .
								Sanitizer::escapeClass( "mw-tag-marker-$tag" ) ],
				$description
			);
			$classes[] = Sanitizer::escapeClass( "mw-tag-$tag" );
		}

		if ( !$displayTags ) {
			return [ '', [] ];
		}

		$markers = $context->msg( 'tag-list-wrapper' )
			->numParams( count( $displayTags ) )
			->rawParams( $context->getLanguage()->commaList( $displayTags ) )
			->parse();
		$markers = Xml::tags( 'span', [ 'class' => 'mw-tag-markers' ], $markers );

		return [ $markers, $classes ];
	}

	/**
	 * Get a short description for a tag.
	 *
	 * Checks if message key "mediawiki:tag-$tag" exists. If it does not,
	 * returns the HTML-escaped tag name. Uses the message if the message
	 * exists, provided it is not disabled. If the message is disabled,
	 * we consider the tag hidden, and return false.
	 *
	 * @param string $tag
	 * @param IContextSource $context
	 * @return string|bool Tag description or false if tag is to be hidden.
	 * @since 1.25 Returns false if tag is to be hidden.
	 */
	public static function tagDescription( $tag, IContextSource $context ) {
		$msg = $context->msg( "tag-$tag" );
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
	 * Get the message object for the tag's long description.
	 *
	 * Checks if message key "mediawiki:tag-$tag-description" exists. If it does not,
	 * or if message is disabled, returns false. Otherwise, returns the message object
	 * for the long description.
	 *
	 * @param string $tag
	 * @param IContextSource $context
	 * @return Message|bool Message object of the tag long description or false if
	 *  there is no description.
	 */
	public static function tagLongDescriptionMessage( $tag, IContextSource $context ) {
		$msg = $context->msg( "tag-$tag-description" );
		if ( !$msg->exists() ) {
			return false;
		}
		if ( $msg->isDisabled() ) {
			// The message exists but is disabled, hide the description.
			return false;
		}

		// Message exists and isn't disabled, use it.
		return $msg;
	}

	/**
	 * Get truncated message for the tag's long description.
	 *
	 * @param string $tag Tag name.
	 * @param int $length Maximum length of truncated message, including ellipsis.
	 * @param IContextSource $context
	 *
	 * @return string Truncated long tag description.
	 */
	public static function truncateTagDescription( $tag, $length, IContextSource $context ) {
		$originalDesc = self::tagLongDescriptionMessage( $tag, $context );
		// If there is no tag description, return empty string
		if ( !$originalDesc ) {
			return '';
		}

		$taglessDesc = Sanitizer::stripAllTags( $originalDesc->parse() );
		$escapedDesc = Sanitizer::escapeHtmlAllowEntities( $taglessDesc );

		return $context->getLanguage()->truncateForVisual( $escapedDesc, $length );
	}

	/**
	 * Add tags to a change given its rc_id, rev_id and/or log_id
	 *
	 * @param string|string[] $tags Tags to add to the change
	 * @param int|null $rc_id The rc_id of the change to add the tags to
	 * @param int|null $rev_id The rev_id of the change to add the tags to
	 * @param int|null $log_id The log_id of the change to add the tags to
	 * @param string $params Params to put in the ct_params field of table 'change_tag'
	 * @param RecentChange|null $rc Recent change, in case the tagging accompanies the action
	 * (this should normally be the case)
	 *
	 * @throws MWException
	 * @return bool False if no changes are made, otherwise true
	 */
	public static function addTags( $tags, $rc_id = null, $rev_id = null,
		$log_id = null, $params = null, RecentChange $rc = null
	) {
		$result = self::updateTags( $tags, null, $rc_id, $rev_id, $log_id, $params, $rc );
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
	 * @param RecentChange|null $rc Recent change being tagged, in case the tagging accompanies
	 * the action
	 * @param User|null $user Tagging user, in case the tagging is subsequent to the tagged action
	 *
	 * @throws MWException When $rc_id, $rev_id and $log_id are all null
	 * @return array Index 0 is an array of tags actually added, index 1 is an
	 * array of tags actually removed, index 2 is an array of tags present on the
	 * revision or log entry before any changes were made
	 *
	 * @since 1.25
	 */
	public static function updateTags( $tagsToAdd, $tagsToRemove, &$rc_id = null,
		&$rev_id = null, &$log_id = null, $params = null, RecentChange $rc = null,
		User $user = null
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
			$log_id, $prevTags )
		) {
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

		self::purgeTagUsageCache();

		Hooks::run( 'ChangeTagsAfterUpdateTags', [ $tagsToAdd, $tagsToRemove, $prevTags,
			$rc_id, $rev_id, $log_id, $params, $rc, $user ] );

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
		$rc_id, $rev_id, $log_id, &$prevTags = []
	) {
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
			return false;
		}

		if ( !$newTags ) {
			// No tags left, so delete the row altogether
			$dbw->delete( 'tag_summary', $tsConds, __METHOD__ );
		} else {
			// Specify the non-DEFAULT value columns in the INSERT/REPLACE clause
			$row = array_filter( [ 'ts_tags' => implode( ',', $newTags ) ] + $tsConds );
			// Check the unique keys for conflicts, ignoring any NULL *_id values
			$uniqueKeys = [];
			foreach ( [ 'ts_rev_id', 'ts_rc_id', 'ts_log_id' ] as $uniqueColumn ) {
				if ( isset( $row[$uniqueColumn] ) ) {
					$uniqueKeys[] = [ $uniqueColumn ];
				}
			}

			$dbw->replace( 'tag_summary', $uniqueKeys, $row, __METHOD__ );
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
	public static function canAddTagsAccompanyingChange( array $tags, User $user = null ) {
		if ( !is_null( $user ) ) {
			if ( !$user->isAllowed( 'applychangetags' ) ) {
				return Status::newFatal( 'tags-apply-no-permission' );
			} elseif ( $user->isBlocked() ) {
				return Status::newFatal( 'tags-apply-blocked', $user->getName() );
			}
		}

		// to be applied, a tag has to be explicitly defined
		$allowedTags = self::listExplicitlyDefinedTags();
		Hooks::run( 'ChangeTagsAllowedAdd', [ &$allowedTags, $tags, $user ] );
		$disallowedTags = array_diff( $tags, $allowedTags );
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
	public static function addTagsAccompanyingChangeWithChecks(
		array $tags, $rc_id, $rev_id, $log_id, $params, User $user
	) {
		// are we allowed to do this?
		$result = self::canAddTagsAccompanyingChange( $tags, $user );
		if ( !$result->isOK() ) {
			$result->value = null;
			return $result;
		}

		// do it!
		self::addTags( $tags, $rc_id, $rev_id, $log_id, $params );

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
		User $user = null
	) {
		if ( !is_null( $user ) ) {
			if ( !$user->isAllowed( 'changetags' ) ) {
				return Status::newFatal( 'tags-update-no-permission' );
			} elseif ( $user->isBlocked() ) {
				return Status::newFatal( 'tags-update-blocked', $user->getName() );
			}
		}

		if ( $tagsToAdd ) {
			// to be added, a tag has to be explicitly defined
			// @todo Allow extensions to define tags that can be applied by users...
			$explicitlyDefinedTags = self::listExplicitlyDefinedTags();
			$diff = array_diff( $tagsToAdd, $explicitlyDefinedTags );
			if ( $diff ) {
				return self::restrictedTagError( 'tags-update-add-not-allowed-one',
					'tags-update-add-not-allowed-multi', $diff );
			}
		}

		if ( $tagsToRemove ) {
			// to be removed, a tag must not be defined by an extension, or equivalently it
			// has to be either explicitly defined or not defined at all
			// (assuming no edge case of a tag both explicitly-defined and extension-defined)
			$softwareDefinedTags = self::listSoftwareDefinedTags();
			$intersect = array_intersect( $tagsToRemove, $softwareDefinedTags );
			if ( $intersect ) {
				return self::restrictedTagError( 'tags-update-remove-not-allowed-one',
					'tags-update-remove-not-allowed-multi', $intersect );
			}
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
		$rc_id, $rev_id, $log_id, $params, $reason, User $user
	) {
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
			$tagsToRemove, $rc_id, $rev_id, $log_id, $params, null, $user );
		if ( !$tagsAdded && !$tagsRemoved ) {
			// no-op, don't log it
			return Status::newGood( (object)[
				'logId' => null,
				'addedTags' => [],
				'removedTags' => [],
			] );
		}

		// log it
		$logEntry = new ManualLogEntry( 'tag', 'update' );
		$logEntry->setPerformer( $user );
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

		return Status::newGood( (object)[
			'logId' => $logId,
			'addedTags' => $tagsAdded,
			'removedTags' => $tagsRemoved,
		] );
	}

	/**
	 * Applies all tags-related changes to a query.
	 * Handles selecting tags, and filtering.
	 * Needs $tables to be set up properly, so we can figure out which join conditions to use.
	 *
	 * WARNING: If $filter_tag contains more than one tag, this function will add DISTINCT,
	 * which may cause performance problems for your query unless you put the ID field of your
	 * table at the end of the ORDER BY, and set a GROUP BY equal to the ORDER BY. For example,
	 * if you had ORDER BY foo_timestamp DESC, you will now need GROUP BY foo_timestamp, foo_id
	 * ORDER BY foo_timestamp DESC, foo_id DESC.
	 *
	 * @param string|array &$tables Table names, see Database::select
	 * @param string|array &$fields Fields used in query, see Database::select
	 * @param string|array &$conds Conditions used in query, see Database::select
	 * @param array &$join_conds Join conditions, see Database::select
	 * @param string|array &$options Options, see Database::select
	 * @param string|array $filter_tag Tag(s) to select on
	 *
	 * @throws MWException When unable to determine appropriate JOIN condition for tagging
	 */
	public static function modifyDisplayQuery( &$tables, &$fields, &$conds,
										&$join_conds, &$options, $filter_tag = '' ) {
		global $wgUseTagFilter;

		// Normalize to arrays
		$tables = (array)$tables;
		$fields = (array)$fields;
		$conds = (array)$conds;
		$options = (array)$options;

		// Figure out which ID field to use
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

		$fields['ts_tags'] = wfGetDB( DB_REPLICA )->buildGroupConcatField(
			',', 'change_tag', 'ct_tag', $join_cond
		);

		if ( $wgUseTagFilter && $filter_tag ) {
			// Somebody wants to filter on a tag.
			// Add an INNER JOIN on change_tag

			$tables[] = 'change_tag';
			$join_conds['change_tag'] = [ 'INNER JOIN', $join_cond ];
			$conds['ct_tag'] = $filter_tag;
			if (
				is_array( $filter_tag ) && count( $filter_tag ) > 1 &&
				!in_array( 'DISTINCT', $options )
			) {
				$options[] = 'DISTINCT';
			}
		}
	}

	/**
	 * Build a text box to select a change tag
	 *
	 * @param string $selected Tag to select by default
	 * @param bool $ooui Use an OOUI TextInputWidget as selector instead of a non-OOUI input field
	 *        You need to call OutputPage::enableOOUI() yourself.
	 * @param IContextSource|null $context
	 * @note Even though it takes null as a valid argument, an IContextSource is preferred
	 *       in a new code, as the null value can change in the future
	 * @return array an array of (label, selector)
	 */
	public static function buildTagFilterSelector(
		$selected = '', $ooui = false, IContextSource $context = null
	) {
		if ( !$context ) {
			$context = RequestContext::getMain();
		}

		$config = $context->getConfig();
		if ( !$config->get( 'UseTagFilter' ) || !count( self::listDefinedTags() ) ) {
			return [];
		}

		$data = [
			Html::rawElement(
				'label',
				[ 'for' => 'tagfilter' ],
				$context->msg( 'tag-filter' )->parse()
			)
		];

		if ( $ooui ) {
			$data[] = new OOUI\TextInputWidget( [
				'id' => 'tagfilter',
				'name' => 'tagfilter',
				'value' => $selected,
				'classes' => 'mw-tagfilter-input',
			] );
		} else {
			$data[] = Xml::input(
				'tagfilter',
				20,
				$selected,
				[ 'class' => 'mw-tagfilter-input mw-ui-input mw-ui-input-inline', 'id' => 'tagfilter' ]
			);
		}

		return $data;
	}

	/**
	 * Defines a tag in the valid_tag table, without checking that the tag name
	 * is valid.
	 * Extensions should NOT use this function; they can use the ListDefinedTags
	 * hook instead.
	 *
	 * @param string $tag Tag to create
	 * @since 1.25
	 */
	public static function defineTag( $tag ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->replace( 'valid_tag',
			[ 'vt_tag' ],
			[ 'vt_tag' => $tag ],
			__METHOD__ );

		// clear the memcache of defined tags
		self::purgeTagCacheAll();
	}

	/**
	 * Removes a tag from the valid_tag table. The tag may remain in use by
	 * extensions, and may still show up as 'defined' if an extension is setting
	 * it from the ListDefinedTags hook.
	 *
	 * @param string $tag Tag to remove
	 * @since 1.25
	 */
	public static function undefineTag( $tag ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'valid_tag', [ 'vt_tag' => $tag ], __METHOD__ );

		// clear the memcache of defined tags
		self::purgeTagCacheAll();
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
	 * @param array $logEntryTags Change tags to apply to the entry
	 * that will be created in the tag management log
	 * @return int ID of the inserted log entry
	 * @since 1.25
	 */
	protected static function logTagManagementAction( $action, $tag, $reason,
		User $user, $tagCount = null, array $logEntryTags = []
	) {
		$dbw = wfGetDB( DB_MASTER );

		$logEntry = new ManualLogEntry( 'managetags', $action );
		$logEntry->setPerformer( $user );
		// target page is not relevant, but it has to be set, so we just put in
		// the title of Special:Tags
		$logEntry->setTarget( Title::newFromText( 'Special:Tags' ) );
		$logEntry->setComment( $reason );

		$params = [ '4::tag' => $tag ];
		if ( !is_null( $tagCount ) ) {
			$params['5:number:count'] = $tagCount;
		}
		$logEntry->setParameters( $params );
		$logEntry->setRelations( [ 'Tag' => $tag ] );
		$logEntry->setTags( $logEntryTags );

		$logId = $logEntry->insert( $dbw );
		$logEntry->publish( $logId );
		return $logId;
	}

	/**
	 * Is it OK to allow the user to activate this tag?
	 *
	 * @param string $tag Tag that you are interested in activating
	 * @param User|null $user User whose permission you wish to check, or null if
	 * you don't care (e.g. maintenance scripts)
	 * @return Status
	 * @since 1.25
	 */
	public static function canActivateTag( $tag, User $user = null ) {
		if ( !is_null( $user ) ) {
			if ( !$user->isAllowed( 'managechangetags' ) ) {
				return Status::newFatal( 'tags-manage-no-permission' );
			} elseif ( $user->isBlocked() ) {
				return Status::newFatal( 'tags-manage-blocked', $user->getName() );
			}
		}

		// defined tags cannot be activated (a defined tag is either extension-
		// defined, in which case the extension chooses whether or not to active it;
		// or user-defined, in which case it is considered active)
		$definedTags = self::listDefinedTags();
		if ( in_array( $tag, $definedTags ) ) {
			return Status::newFatal( 'tags-activate-not-allowed', $tag );
		}

		// non-existing tags cannot be activated
		$tagUsage = self::tagUsageStatistics();
		if ( !isset( $tagUsage[$tag] ) ) { // we already know the tag is undefined
			return Status::newFatal( 'tags-activate-not-found', $tag );
		}

		return Status::newGood();
	}

	/**
	 * Activates a tag, checking whether it is allowed first, and adding a log
	 * entry afterwards.
	 *
	 * Includes a call to ChangeTag::canActivateTag(), so your code doesn't need
	 * to do that.
	 *
	 * @param string $tag
	 * @param string $reason
	 * @param User $user Who to give credit for the action
	 * @param bool $ignoreWarnings Can be used for API interaction, default false
	 * @param array $logEntryTags Change tags to apply to the entry
	 * that will be created in the tag management log
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 * @since 1.25
	 */
	public static function activateTagWithChecks( $tag, $reason, User $user,
		$ignoreWarnings = false, array $logEntryTags = []
	) {
		// are we allowed to do this?
		$result = self::canActivateTag( $tag, $user );
		if ( $ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
			$result->value = null;
			return $result;
		}

		// do it!
		self::defineTag( $tag );

		// log it
		$logId = self::logTagManagementAction( 'activate', $tag, $reason, $user,
			null, $logEntryTags );

		return Status::newGood( $logId );
	}

	/**
	 * Is it OK to allow the user to deactivate this tag?
	 *
	 * @param string $tag Tag that you are interested in deactivating
	 * @param User|null $user User whose permission you wish to check, or null if
	 * you don't care (e.g. maintenance scripts)
	 * @return Status
	 * @since 1.25
	 */
	public static function canDeactivateTag( $tag, User $user = null ) {
		if ( !is_null( $user ) ) {
			if ( !$user->isAllowed( 'managechangetags' ) ) {
				return Status::newFatal( 'tags-manage-no-permission' );
			} elseif ( $user->isBlocked() ) {
				return Status::newFatal( 'tags-manage-blocked', $user->getName() );
			}
		}

		// only explicitly-defined tags can be deactivated
		$explicitlyDefinedTags = self::listExplicitlyDefinedTags();
		if ( !in_array( $tag, $explicitlyDefinedTags ) ) {
			return Status::newFatal( 'tags-deactivate-not-allowed', $tag );
		}
		return Status::newGood();
	}

	/**
	 * Deactivates a tag, checking whether it is allowed first, and adding a log
	 * entry afterwards.
	 *
	 * Includes a call to ChangeTag::canDeactivateTag(), so your code doesn't need
	 * to do that.
	 *
	 * @param string $tag
	 * @param string $reason
	 * @param User $user Who to give credit for the action
	 * @param bool $ignoreWarnings Can be used for API interaction, default false
	 * @param array $logEntryTags Change tags to apply to the entry
	 * that will be created in the tag management log
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 * @since 1.25
	 */
	public static function deactivateTagWithChecks( $tag, $reason, User $user,
		$ignoreWarnings = false, array $logEntryTags = []
	) {
		// are we allowed to do this?
		$result = self::canDeactivateTag( $tag, $user );
		if ( $ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
			$result->value = null;
			return $result;
		}

		// do it!
		self::undefineTag( $tag );

		// log it
		$logId = self::logTagManagementAction( 'deactivate', $tag, $reason, $user,
			null, $logEntryTags );

		return Status::newGood( $logId );
	}

	/**
	 * Is the tag name valid?
	 *
	 * @param string $tag Tag that you are interested in creating
	 * @return Status
	 * @since 1.30
	 */
	public static function isTagNameValid( $tag ) {
		// no empty tags
		if ( $tag === '' ) {
			return Status::newFatal( 'tags-create-no-name' );
		}

		// tags cannot contain commas (used as a delimiter in tag_summary table),
		// pipe (used as a delimiter between multiple tags in
		// SpecialRecentchanges and friends), or slashes (would break tag description messages in
		// MediaWiki namespace)
		if ( strpos( $tag, ',' ) !== false || strpos( $tag, '|' ) !== false
			|| strpos( $tag, '/' ) !== false ) {
			return Status::newFatal( 'tags-create-invalid-chars' );
		}

		// could the MediaWiki namespace description messages be created?
		$title = Title::makeTitleSafe( NS_MEDIAWIKI, "Tag-$tag-description" );
		if ( is_null( $title ) ) {
			return Status::newFatal( 'tags-create-invalid-title-chars' );
		}

		return Status::newGood();
	}

	/**
	 * Is it OK to allow the user to create this tag?
	 *
	 * @param string $tag Tag that you are interested in creating
	 * @param User|null $user User whose permission you wish to check, or null if
	 * you don't care (e.g. maintenance scripts)
	 * @return Status
	 * @since 1.25
	 */
	public static function canCreateTag( $tag, User $user = null ) {
		if ( !is_null( $user ) ) {
			if ( !$user->isAllowed( 'managechangetags' ) ) {
				return Status::newFatal( 'tags-manage-no-permission' );
			} elseif ( $user->isBlocked() ) {
				return Status::newFatal( 'tags-manage-blocked', $user->getName() );
			}
		}

		$status = self::isTagNameValid( $tag );
		if ( !$status->isGood() ) {
			return $status;
		}

		// does the tag already exist?
		$tagUsage = self::tagUsageStatistics();
		if ( isset( $tagUsage[$tag] ) || in_array( $tag, self::listDefinedTags() ) ) {
			return Status::newFatal( 'tags-create-already-exists', $tag );
		}

		// check with hooks
		$canCreateResult = Status::newGood();
		Hooks::run( 'ChangeTagCanCreate', [ $tag, $user, &$canCreateResult ] );
		return $canCreateResult;
	}

	/**
	 * Creates a tag by adding a row to the `valid_tag` table.
	 *
	 * Includes a call to ChangeTag::canDeleteTag(), so your code doesn't need to
	 * do that.
	 *
	 * @param string $tag
	 * @param string $reason
	 * @param User $user Who to give credit for the action
	 * @param bool $ignoreWarnings Can be used for API interaction, default false
	 * @param array $logEntryTags Change tags to apply to the entry
	 * that will be created in the tag management log
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 * @since 1.25
	 */
	public static function createTagWithChecks( $tag, $reason, User $user,
		$ignoreWarnings = false, array $logEntryTags = []
	) {
		// are we allowed to do this?
		$result = self::canCreateTag( $tag, $user );
		if ( $ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
			$result->value = null;
			return $result;
		}

		// do it!
		self::defineTag( $tag );

		// log it
		$logId = self::logTagManagementAction( 'create', $tag, $reason, $user,
			null, $logEntryTags );

		return Status::newGood( $logId );
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
		self::undefineTag( $tag );

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

		// clear the memcache of defined tags
		self::purgeTagCacheAll();

		return $status;
	}

	/**
	 * Is it OK to allow the user to delete this tag?
	 *
	 * @param string $tag Tag that you are interested in deleting
	 * @param User|null $user User whose permission you wish to check, or null if
	 * you don't care (e.g. maintenance scripts)
	 * @return Status
	 * @since 1.25
	 */
	public static function canDeleteTag( $tag, User $user = null ) {
		$tagUsage = self::tagUsageStatistics();

		if ( !is_null( $user ) ) {
			if ( !$user->isAllowed( 'deletechangetags' ) ) {
				return Status::newFatal( 'tags-delete-no-permission' );
			} elseif ( $user->isBlocked() ) {
				return Status::newFatal( 'tags-manage-blocked', $user->getName() );
			}
		}

		if ( !isset( $tagUsage[$tag] ) && !in_array( $tag, self::listDefinedTags() ) ) {
			return Status::newFatal( 'tags-delete-not-found', $tag );
		}

		if ( isset( $tagUsage[$tag] ) && $tagUsage[$tag] > self::MAX_DELETE_USES ) {
			return Status::newFatal( 'tags-delete-too-many-uses', $tag, self::MAX_DELETE_USES );
		}

		$softwareDefined = self::listSoftwareDefinedTags();
		if ( in_array( $tag, $softwareDefined ) ) {
			// extension-defined tags can't be deleted unless the extension
			// specifically allows it
			$status = Status::newFatal( 'tags-delete-not-allowed' );
		} else {
			// user-defined tags are deletable unless otherwise specified
			$status = Status::newGood();
		}

		Hooks::run( 'ChangeTagCanDelete', [ $tag, $user, &$status ] );
		return $status;
	}

	/**
	 * Deletes a tag, checking whether it is allowed first, and adding a log entry
	 * afterwards.
	 *
	 * Includes a call to ChangeTag::canDeleteTag(), so your code doesn't need to
	 * do that.
	 *
	 * @param string $tag
	 * @param string $reason
	 * @param User $user Who to give credit for the action
	 * @param bool $ignoreWarnings Can be used for API interaction, default false
	 * @param array $logEntryTags Change tags to apply to the entry
	 * that will be created in the tag management log
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 * @since 1.25
	 */
	public static function deleteTagWithChecks( $tag, $reason, User $user,
		$ignoreWarnings = false, array $logEntryTags = []
	) {
		// are we allowed to do this?
		$result = self::canDeleteTag( $tag, $user );
		if ( $ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
			$result->value = null;
			return $result;
		}

		// store the tag usage statistics
		$tagUsage = self::tagUsageStatistics();
		$hitcount = isset( $tagUsage[$tag] ) ? $tagUsage[$tag] : 0;

		// do it!
		$deleteResult = self::deleteTagEverywhere( $tag );
		if ( !$deleteResult->isOK() ) {
			return $deleteResult;
		}

		// log it
		$logId = self::logTagManagementAction( 'delete', $tag, $reason, $user,
			$hitcount, $logEntryTags );

		$deleteResult->value = $logId;
		return $deleteResult;
	}

	/**
	 * Lists those tags which core or extensions report as being "active".
	 *
	 * @return array
	 * @since 1.25
	 */
	public static function listSoftwareActivatedTags() {
		// core active tags
		$tags = self::getSoftwareTags();
		if ( !Hooks::isRegistered( 'ChangeTagsListActive' ) ) {
			return $tags;
		}
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		return $cache->getWithSetCallback(
			$cache->makeKey( 'active-tags' ),
			WANObjectCache::TTL_MINUTE * 5,
			function ( $oldValue, &$ttl, array &$setOpts ) use ( $tags ) {
				$setOpts += Database::getCacheSetOptions( wfGetDB( DB_REPLICA ) );

				// Ask extensions which tags they consider active
				Hooks::run( 'ChangeTagsListActive', [ &$tags ] );
				return $tags;
			},
			[
				'checkKeys' => [ $cache->makeKey( 'active-tags' ) ],
				'lockTSE' => WANObjectCache::TTL_MINUTE * 5,
				'pcTTL' => WANObjectCache::TTL_PROC_LONG
			]
		);
	}

	/**
	 * Basically lists defined tags which count even if they aren't applied to anything.
	 * It returns a union of the results of listExplicitlyDefinedTags()
	 *
	 * @return string[] Array of strings: tags
	 */
	public static function listDefinedTags() {
		$tags1 = self::listExplicitlyDefinedTags();
		$tags2 = self::listSoftwareDefinedTags();
		return array_values( array_unique( array_merge( $tags1, $tags2 ) ) );
	}

	/**
	 * Lists tags explicitly defined in the `valid_tag` table of the database.
	 * Tags in table 'change_tag' which are not in table 'valid_tag' are not
	 * included.
	 *
	 * Tries memcached first.
	 *
	 * @return string[] Array of strings: tags
	 * @since 1.25
	 */
	public static function listExplicitlyDefinedTags() {
		$fname = __METHOD__;

		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		return $cache->getWithSetCallback(
			$cache->makeKey( 'valid-tags-db' ),
			WANObjectCache::TTL_MINUTE * 5,
			function ( $oldValue, &$ttl, array &$setOpts ) use ( $fname ) {
				$dbr = wfGetDB( DB_REPLICA );

				$setOpts += Database::getCacheSetOptions( $dbr );

				$tags = $dbr->selectFieldValues( 'valid_tag', 'vt_tag', [], $fname );

				return array_filter( array_unique( $tags ) );
			},
			[
				'checkKeys' => [ $cache->makeKey( 'valid-tags-db' ) ],
				'lockTSE' => WANObjectCache::TTL_MINUTE * 5,
				'pcTTL' => WANObjectCache::TTL_PROC_LONG
			]
		);
	}

	/**
	 * Lists tags defined by core or extensions using the ListDefinedTags hook.
	 * Extensions need only define those tags they deem to be in active use.
	 *
	 * Tries memcached first.
	 *
	 * @return string[] Array of strings: tags
	 * @since 1.25
	 */
	public static function listSoftwareDefinedTags() {
		// core defined tags
		$tags = self::getSoftwareTags( true );
		if ( !Hooks::isRegistered( 'ListDefinedTags' ) ) {
			return $tags;
		}
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		return $cache->getWithSetCallback(
			$cache->makeKey( 'valid-tags-hook' ),
			WANObjectCache::TTL_MINUTE * 5,
			function ( $oldValue, &$ttl, array &$setOpts ) use ( $tags ) {
				$setOpts += Database::getCacheSetOptions( wfGetDB( DB_REPLICA ) );

				Hooks::run( 'ListDefinedTags', [ &$tags ] );
				return array_filter( array_unique( $tags ) );
			},
			[
				'checkKeys' => [ $cache->makeKey( 'valid-tags-hook' ) ],
				'lockTSE' => WANObjectCache::TTL_MINUTE * 5,
				'pcTTL' => WANObjectCache::TTL_PROC_LONG
			]
		);
	}

	/**
	 * Invalidates the short-term cache of defined tags used by the
	 * list*DefinedTags functions, as well as the tag statistics cache.
	 * @since 1.25
	 */
	public static function purgeTagCacheAll() {
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();

		$cache->touchCheckKey( $cache->makeKey( 'active-tags' ) );
		$cache->touchCheckKey( $cache->makeKey( 'valid-tags-db' ) );
		$cache->touchCheckKey( $cache->makeKey( 'valid-tags-hook' ) );

		self::purgeTagUsageCache();
	}

	/**
	 * Invalidates the tag statistics cache only.
	 * @since 1.25
	 */
	public static function purgeTagUsageCache() {
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();

		$cache->touchCheckKey( $cache->makeKey( 'change-tag-statistics' ) );
	}

	/**
	 * Returns a map of any tags used on the wiki to number of edits
	 * tagged with them, ordered descending by the hitcount.
	 * This does not include tags defined somewhere that have never been applied.
	 *
	 * Keeps a short-term cache in memory, so calling this multiple times in the
	 * same request should be fine.
	 *
	 * @return array Array of string => int
	 */
	public static function tagUsageStatistics() {
		$fname = __METHOD__;
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		return $cache->getWithSetCallback(
			$cache->makeKey( 'change-tag-statistics' ),
			WANObjectCache::TTL_MINUTE * 5,
			function ( $oldValue, &$ttl, array &$setOpts ) use ( $fname ) {
				$dbr = wfGetDB( DB_REPLICA, 'vslow' );

				$setOpts += Database::getCacheSetOptions( $dbr );

				$res = $dbr->select(
					'change_tag',
					[ 'ct_tag', 'hitcount' => 'count(*)' ],
					[],
					$fname,
					[ 'GROUP BY' => 'ct_tag', 'ORDER BY' => 'hitcount DESC' ]
				);

				$out = [];
				foreach ( $res as $row ) {
					$out[$row->ct_tag] = $row->hitcount;
				}

				return $out;
			},
			[
				'checkKeys' => [ $cache->makeKey( 'change-tag-statistics' ) ],
				'lockTSE' => WANObjectCache::TTL_MINUTE * 5,
				'pcTTL' => WANObjectCache::TTL_PROC_LONG
			]
		);
	}

	/**
	 * Indicate whether change tag editing UI is relevant
	 *
	 * Returns true if the user has the necessary right and there are any
	 * editable tags defined.
	 *
	 * This intentionally doesn't check "any addable || any deletable", because
	 * it seems like it would be more confusing than useful if the checkboxes
	 * suddenly showed up because some abuse filter stopped defining a tag and
	 * then suddenly disappeared when someone deleted all uses of that tag.
	 *
	 * @param User $user
	 * @return bool
	 */
	public static function showTagEditingUI( User $user ) {
		return $user->isAllowed( 'changetags' ) && (bool)self::listExplicitlyDefinedTags();
	}
}
