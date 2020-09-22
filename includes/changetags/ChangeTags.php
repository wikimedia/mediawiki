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

use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\NameTableAccessException;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IDatabase;

class ChangeTags {
	/**
	 * Can't delete tags with more than this many uses. Similar in intent to
	 * the bigdelete user right
	 * @todo Use the job queue for tag deletion to avoid this restriction
	 */
	private const MAX_DELETE_USES = 5000;

	/**
	 * Flag for canDeleteTag().
	 */
	public const BYPASS_MAX_USAGE_CHECK = 1;

	/**
	 * A list of tags defined and used by MediaWiki itself.
	 */
	private static $definedSoftwareTags = [
		'mw-contentmodelchange',
		'mw-new-redirect',
		'mw-removed-redirect',
		'mw-changed-redirect-target',
		'mw-blank',
		'mw-replace',
		'mw-rollback',
		'mw-undo',
		'mw-manual-revert',
		'mw-reverted',
	];

	/**
	 * If true, this class attempts to avoid reopening database tables within the same query,
	 * to avoid the "Can't reopen table" error when operating on temporary tables while running
	 * tests.
	 *
	 * @see https://phabricator.wikimedia.org/T256006
	 * @see 1.35
	 *
	 * @var bool
	 */
	public static $avoidReopeningTablesForTesting = false;

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
	 * @return-taint onlysafefor_htmlnoent
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
			->rawParams( implode( ' ', $displayTags ) )
			->parse();
		$markers = Xml::tags( 'span', [ 'class' => 'mw-tag-markers' ], $markers );

		return [ $markers, $classes ];
	}

	/**
	 * Get the message object for the tag's short description.
	 *
	 * Checks if message key "mediawiki:tag-$tag" exists. If it does not,
	 * returns the tag name in a RawMessage. If the message exists, it is
	 * used, provided it is not disabled. If the message is disabled, we
	 * consider the tag hidden, and return false.
	 *
	 * @since 1.34
	 * @param string $tag
	 * @param MessageLocalizer $context
	 * @return Message|bool Tag description, or false if tag is to be hidden.
	 */
	public static function tagShortDescriptionMessage( $tag, MessageLocalizer $context ) {
		$msg = $context->msg( "tag-$tag" );
		if ( !$msg->exists() ) {
			// No such message
			return ( new RawMessage( '$1', [ Message::plaintextParam( $tag ) ] ) )
				// HACK MessageLocalizer doesn't have a way to set the right language on a RawMessage,
				// so extract the language from $msg and use that.
				// The language doesn't really matter, but we need to set it to avoid requesting
				// the user's language from session-less entry points (T227233)
				->inLanguage( $msg->getLanguage() );

		}
		if ( $msg->isDisabled() ) {
			// The message exists but is disabled, hide the tag.
			return false;
		}

		// Message exists and isn't disabled, use it.
		return $msg;
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
	 * @param MessageLocalizer $context
	 * @return string|bool Tag description or false if tag is to be hidden.
	 * @since 1.25 Returns false if tag is to be hidden.
	 */
	public static function tagDescription( $tag, MessageLocalizer $context ) {
		$msg = self::tagShortDescriptionMessage( $tag, $context );
		return $msg ? $msg->parse() : false;
	}

	/**
	 * Get the message object for the tag's long description.
	 *
	 * Checks if message key "mediawiki:tag-$tag-description" exists. If it does not,
	 * or if message is disabled, returns false. Otherwise, returns the message object
	 * for the long description.
	 *
	 * @param string $tag
	 * @param MessageLocalizer $context
	 * @return Message|bool Message object of the tag long description or false if
	 *  there is no description.
	 */
	public static function tagLongDescriptionMessage( $tag, MessageLocalizer $context ) {
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
	 * @deprecated since 1.35
	 *
	 * @return string Truncated long tag description.
	 */
	public static function truncateTagDescription( $tag, $length, IContextSource $context ) {
		wfDeprecated( __METHOD__, '1.35' );
		// FIXME: Make this accept MessageLocalizer and Language instead of IContextSource

		$originalDesc = self::tagLongDescriptionMessage( $tag, $context );
		// If there is no tag description, return empty string
		if ( !$originalDesc ) {
			return '';
		}

		$taglessDesc = Sanitizer::stripAllTags( $originalDesc->parse() );

		return $context->getLanguage()->truncateForVisual( $taglessDesc, $length );
	}

	/**
	 * Add tags to a change given its rc_id, rev_id and/or log_id
	 *
	 * @param string|string[] $tags Tags to add to the change
	 * @param int|null $rc_id The rc_id of the change to add the tags to
	 * @param int|null $rev_id The rev_id of the change to add the tags to
	 * @param int|null $log_id The log_id of the change to add the tags to
	 * @param string|null $params Params to put in the ct_params field of table 'change_tag'
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
	 * @param string|null $params Params to put in the ct_params field of table
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
				[ 'ls_field' => 'associated_rev_id', 'ls_value' => (string)$rev_id ],
				__METHOD__
			);
		}

		$prevTags = self::getTags( $dbw, $rc_id, $rev_id, $log_id );

		// add tags
		$tagsToAdd = array_values( array_diff( $tagsToAdd, $prevTags ) );
		$newTags = array_unique( array_merge( $prevTags, $tagsToAdd ) );

		// remove tags
		$tagsToRemove = array_values( array_intersect( $tagsToRemove, $newTags ) );
		$newTags = array_values( array_diff( $newTags, $tagsToRemove ) );

		sort( $prevTags );
		sort( $newTags );
		if ( $prevTags == $newTags ) {
			return [ [], [], $prevTags ];
		}

		// insert a row into change_tag for each new tag
		$changeTagDefStore = MediaWikiServices::getInstance()->getChangeTagDefStore();
		if ( count( $tagsToAdd ) ) {
			$changeTagMapping = [];
			foreach ( $tagsToAdd as $tag ) {
				$changeTagMapping[$tag] = $changeTagDefStore->acquireId( $tag );
			}
			$fname = __METHOD__;
			// T207881: update the counts at the end of the transaction
			$dbw->onTransactionPreCommitOrIdle( function () use ( $dbw, $tagsToAdd, $fname ) {
				$dbw->update(
					'change_tag_def',
					[ 'ctd_count = ctd_count + 1' ],
					[ 'ctd_name' => $tagsToAdd ],
					$fname
				);
			}, $fname );

			$tagsRows = [];
			foreach ( $tagsToAdd as $tag ) {
				// Filter so we don't insert NULLs as zero accidentally.
				// Keep in mind that $rc_id === null means "I don't care/know about the
				// rc_id, just delete $tag on this revision/log entry". It doesn't
				// mean "only delete tags on this revision/log WHERE rc_id IS NULL".
				$tagsRows[] = array_filter(
					[
						'ct_rc_id' => $rc_id,
						'ct_log_id' => $log_id,
						'ct_rev_id' => $rev_id,
						'ct_params' => $params,
						'ct_tag_id' => $changeTagMapping[$tag] ?? null,
					]
				);

			}

			$dbw->insert( 'change_tag', $tagsRows, __METHOD__, [ 'IGNORE' ] );
		}

		// delete from change_tag
		if ( count( $tagsToRemove ) ) {
			$fname = __METHOD__;
			foreach ( $tagsToRemove as $tag ) {
				$conds = array_filter(
					[
						'ct_rc_id' => $rc_id,
						'ct_log_id' => $log_id,
						'ct_rev_id' => $rev_id,
						'ct_tag_id' => $changeTagDefStore->getId( $tag ),
					]
				);
				$dbw->delete( 'change_tag', $conds, __METHOD__ );
				if ( $dbw->affectedRows() ) {
					// T207881: update the counts at the end of the transaction
					$dbw->onTransactionPreCommitOrIdle( function () use ( $dbw, $tag, $fname ) {
						$dbw->update(
							'change_tag_def',
							[ 'ctd_count = ctd_count - 1' ],
							[ 'ctd_name' => $tag ],
							$fname
						);

						$dbw->delete(
							'change_tag_def',
							[ 'ctd_name' => $tag, 'ctd_count' => 0, 'ctd_user_defined' => 0 ],
							$fname
						);
					}, $fname );
				}
			}
		}

		Hooks::runner()->onChangeTagsAfterUpdateTags( $tagsToAdd, $tagsToRemove, $prevTags,
			$rc_id, $rev_id, $log_id, $params, $rc, $user );

		return [ $tagsToAdd, $tagsToRemove, $prevTags ];
	}

	/**
	 * Return all the tags associated with the given recent change ID,
	 * revision ID, and/or log entry ID.
	 *
	 * @param IDatabase $db the database to query
	 * @param int|null $rc_id
	 * @param int|null $rev_id
	 * @param int|null $log_id
	 * @return string[]
	 */
	public static function getTags( IDatabase $db, $rc_id = null, $rev_id = null, $log_id = null ) {
		$conds = array_filter(
			[
				'ct_rc_id' => $rc_id,
				'ct_rev_id' => $rev_id,
				'ct_log_id' => $log_id,
			]
		);

		$tagIds = $db->selectFieldValues(
			'change_tag',
			'ct_tag_id',
			$conds,
			__METHOD__
		);

		$tags = [];
		$changeTagDefStore = MediaWikiServices::getInstance()->getChangeTagDefStore();
		foreach ( $tagIds as $tagId ) {
			$tags[] = $changeTagDefStore->getName( (int)$tagId );
		}

		return $tags;
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
		$tags = array_values( $tags );
		$count = count( $tags );
		$status = Status::newFatal( ( $count > 1 ) ? $msgMulti : $msgOne,
			$lang->commaList( $tags ), $count );
		$status->value = $tags;
		return $status;
	}

	/**
	 * Is it OK to allow the user to apply all the specified tags at the same time
	 * as they edit/make the change?
	 *
	 * Extensions should not use this function, unless directly handling a user
	 * request to add a tag to a revision or log entry that the user is making.
	 *
	 * @param array $tags Tags that you are interested in applying
	 * @param User|null $user User whose permission you wish to check, or null to
	 * check for a generic non-blocked user with the relevant rights
	 * @return Status
	 * @since 1.25
	 */
	public static function canAddTagsAccompanyingChange( array $tags, User $user = null ) {
		if ( $user !== null ) {
			if ( !MediaWikiServices::getInstance()->getPermissionManager()
					->userHasRight( $user, 'applychangetags' )
			) {
				return Status::newFatal( 'tags-apply-no-permission' );
			} elseif ( $user->getBlock() && $user->getBlock()->isSitewide() ) {
				return Status::newFatal( 'tags-apply-blocked', $user->getName() );
			}
		}

		// to be applied, a tag has to be explicitly defined
		$allowedTags = self::listExplicitlyDefinedTags();
		Hooks::runner()->onChangeTagsAllowedAdd( $allowedTags, $tags, $user );
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
	 * Extensions should not use this function, unless directly handling a user
	 * request to add or remove tags from an existing revision or log entry.
	 *
	 * @param array $tagsToAdd Tags that you are interested in adding
	 * @param array $tagsToRemove Tags that you are interested in removing
	 * @param User|null $user User whose permission you wish to check, or null to
	 * check for a generic non-blocked user with the relevant rights
	 * @return Status
	 * @since 1.25
	 */
	public static function canUpdateTags( array $tagsToAdd, array $tagsToRemove,
		User $user = null
	) {
		if ( $user !== null ) {
			if ( !MediaWikiServices::getInstance()->getPermissionManager()
					->userHasRight( $user, 'changetags' )
			) {
				return Status::newFatal( 'tags-update-no-permission' );
			} elseif ( $user->getBlock() && $user->getBlock()->isSitewide() ) {
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
	 * Includes a call to ChangeTags::canUpdateTags(), so your code doesn't need
	 * to do that. However, it doesn't check whether the *_id parameters are a
	 * valid combination. That is up to you to enforce. See ApiTag::execute() for
	 * an example.
	 *
	 * Extensions should generally avoid this function. Call
	 * ChangeTags::updateTags() instead, unless directly handling a user request
	 * to add or remove tags from an existing revision or log entry.
	 *
	 * @param array|null $tagsToAdd If none, pass [] or null
	 * @param array|null $tagsToRemove If none, pass [] or null
	 * @param int|null $rc_id The rc_id of the change to add the tags to
	 * @param int|null $rev_id The rev_id of the change to add the tags to
	 * @param int|null $log_id The log_id of the change to add the tags to
	 * @param string|null $params Params to put in the ct_params field of table
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
		if ( $tagsToAdd === null ) {
			$tagsToAdd = [];
		}
		if ( $tagsToRemove === null ) {
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
			$revisionRecord = MediaWikiServices::getInstance()
				->getRevisionLookup()
				->getRevisionById( $rev_id );
			if ( $revisionRecord ) {
				$logEntry->setTarget( $revisionRecord->getPageAsLinkTarget() );
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
		&$join_conds, &$options, $filter_tag = ''
	) {
		global $wgUseTagFilter;

		// Normalize to arrays
		$tables = (array)$tables;
		$fields = (array)$fields;
		$conds = (array)$conds;
		$options = (array)$options;

		$fields['ts_tags'] = self::makeTagSummarySubquery( $tables );

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

		if ( $wgUseTagFilter && $filter_tag ) {
			// Somebody wants to filter on a tag.
			// Add an INNER JOIN on change_tag

			$tagTable = 'change_tag';
			if ( self::$avoidReopeningTablesForTesting && defined( 'MW_PHPUNIT_TEST' ) ) {
				$db = wfGetDB( DB_REPLICA );

				if ( $db->getType() === 'mysql' ) {
					// When filtering by tag, we are using the change_tag table twice:
					// Once in a join for filtering, and once in a sub-query to list all
					// tags for each revision. This does not work with temporary tables
					// on some versions of MySQL, which causes phpunit tests to fail.
					// As a hacky workaround, we copy the temporary table, and join
					// against the copy. It is acknowledge that this is quite horrific.
					// Discuss at T256006.

					$tagTable = 'change_tag_for_display_query';
					$db->query(
						'CREATE TEMPORARY TABLE IF NOT EXISTS ' . $db->tableName( $tagTable )
						. ' LIKE ' . $db->tableName( 'change_tag' )
					);
					$db->query(
						'INSERT IGNORE INTO ' . $db->tableName( $tagTable )
						. ' SELECT * FROM ' . $db->tableName( 'change_tag' )
					);
				}
			}

			$tables[] = $tagTable;
			$join_conds[$tagTable] = [ 'JOIN', $join_cond ];
			$filterTagIds = [];
			$changeTagDefStore = MediaWikiServices::getInstance()->getChangeTagDefStore();
			foreach ( (array)$filter_tag as $filterTagName ) {
				try {
					$filterTagIds[] = $changeTagDefStore->getId( $filterTagName );
				} catch ( NameTableAccessException $exception ) {
					// Return nothing.
					$conds[] = '0=1';
					break;
				}
			}

			if ( $filterTagIds !== [] ) {
				$conds['ct_tag_id'] = $filterTagIds;
			}

			if (
				is_array( $filter_tag ) && count( $filter_tag ) > 1 &&
				!in_array( 'DISTINCT', $options )
			) {
				$options[] = 'DISTINCT';
			}
		}
	}

	/**
	 * Make the tag summary subquery based on the given tables and return it.
	 *
	 * @param string|array $tables Table names, see Database::select
	 *
	 * @return string tag summary subqeury
	 * @throws MWException When unable to determine appropriate JOIN condition for tagging
	 */
	public static function makeTagSummarySubquery( $tables ) {
		// Normalize to arrays
		$tables = (array)$tables;

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

		$tagTables = [ 'change_tag', 'change_tag_def' ];
		$join_cond_ts_tags = [ 'change_tag_def' => [ 'JOIN', 'ct_tag_id=ctd_id' ] ];
		$field = 'ctd_name';

		return wfGetDB( DB_REPLICA )->buildGroupConcatField(
			',', $tagTables, $field, $join_cond, $join_cond_ts_tags
		);
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
	 * Set ctd_user_defined = 1 in change_tag_def without checking that the tag name is valid.
	 * Extensions should NOT use this function; they can use the ListDefinedTags
	 * hook instead.
	 *
	 * @param string $tag Tag to create
	 * @since 1.25
	 */
	public static function defineTag( $tag ) {
		$dbw = wfGetDB( DB_MASTER );
		$tagDef = [
			'ctd_name' => $tag,
			'ctd_user_defined' => 1,
			'ctd_count' => 0
		];
		$dbw->upsert(
			'change_tag_def',
			$tagDef,
			'ctd_name',
			[ 'ctd_user_defined' => 1 ],
			__METHOD__
		);

		// clear the memcache of defined tags
		self::purgeTagCacheAll();
	}

	/**
	 * Update ctd_user_defined = 0 field in change_tag_def.
	 * The tag may remain in use by extensions, and may still show up as 'defined'
	 * if an extension is setting it from the ListDefinedTags hook.
	 *
	 * @param string $tag Tag to remove
	 * @since 1.25
	 */
	public static function undefineTag( $tag ) {
		$dbw = wfGetDB( DB_MASTER );

		$dbw->update(
			'change_tag_def',
			[ 'ctd_user_defined' => 0 ],
			[ 'ctd_name' => $tag ],
			__METHOD__
		);

		$dbw->delete(
			'change_tag_def',
			[ 'ctd_name' => $tag, 'ctd_count' => 0 ],
			__METHOD__
		);

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
	 * @param int|null $tagCount For deletion only, how many usages the tag had before
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
		if ( $tagCount !== null ) {
			$params['5:number:count'] = $tagCount;
		}
		$logEntry->setParameters( $params );
		$logEntry->setRelations( [ 'Tag' => $tag ] );
		$logEntry->addTags( $logEntryTags );

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
		if ( $user !== null ) {
			if ( !MediaWikiServices::getInstance()->getPermissionManager()
					->userHasRight( $user, 'managechangetags' )
			) {
				return Status::newFatal( 'tags-manage-no-permission' );
			} elseif ( $user->getBlock() && $user->getBlock()->isSitewide() ) {
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
		if ( $user !== null ) {
			if ( !MediaWikiServices::getInstance()->getPermissionManager()
					->userHasRight( $user, 'managechangetags' )
			) {
				return Status::newFatal( 'tags-manage-no-permission' );
			} elseif ( $user->getBlock() && $user->getBlock()->isSitewide() ) {
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

		// tags cannot contain commas (used to be used as a delimiter in tag_summary table),
		// pipe (used as a delimiter between multiple tags in
		// SpecialRecentchanges and friends), or slashes (would break tag description messages in
		// MediaWiki namespace)
		if ( strpos( $tag, ',' ) !== false || strpos( $tag, '|' ) !== false
			|| strpos( $tag, '/' ) !== false ) {
			return Status::newFatal( 'tags-create-invalid-chars' );
		}

		// could the MediaWiki namespace description messages be created?
		$title = Title::makeTitleSafe( NS_MEDIAWIKI, "Tag-$tag-description" );
		if ( $title === null ) {
			return Status::newFatal( 'tags-create-invalid-title-chars' );
		}

		return Status::newGood();
	}

	/**
	 * Is it OK to allow the user to create this tag?
	 *
	 * Extensions should NOT use this function. In most cases, a tag can be
	 * defined using the ListDefinedTags hook without any checking.
	 *
	 * @param string $tag Tag that you are interested in creating
	 * @param User|null $user User whose permission you wish to check, or null if
	 * you don't care (e.g. maintenance scripts)
	 * @return Status
	 * @since 1.25
	 */
	public static function canCreateTag( $tag, User $user = null ) {
		if ( $user !== null ) {
			if ( !MediaWikiServices::getInstance()->getPermissionManager()
					->userHasRight( $user, 'managechangetags' )
			) {
				return Status::newFatal( 'tags-manage-no-permission' );
			} elseif ( $user->getBlock() && $user->getBlock()->isSitewide() ) {
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
		Hooks::runner()->onChangeTagCanCreate( $tag, $user, $canCreateResult );
		return $canCreateResult;
	}

	/**
	 * Creates a tag by adding it to `change_tag_def` table.
	 *
	 * Extensions should NOT use this function; they can use the ListDefinedTags
	 * hook instead.
	 *
	 * Includes a call to ChangeTag::canCreateTag(), so your code doesn't need to
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

		// fetch tag id, this must be done before calling undefineTag(), see T225564
		$tagId = MediaWikiServices::getInstance()->getChangeTagDefStore()->getId( $tag );

		// set ctd_user_defined = 0
		self::undefineTag( $tag );

		// delete from change_tag
		$dbw->delete( 'change_tag', [ 'ct_tag_id' => $tagId ], __METHOD__ );
		$dbw->delete( 'change_tag_def', [ 'ctd_name' => $tag ], __METHOD__ );
		$dbw->endAtomic( __METHOD__ );

		// give extensions a chance
		$status = Status::newGood();
		Hooks::runner()->onChangeTagAfterDelete( $tag, $status );
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
	 * @param int $flags Use ChangeTags::BYPASS_MAX_USAGE_CHECK to ignore whether
	 *  there are more uses than we would normally allow to be deleted through the
	 *  user interface.
	 * @return Status
	 * @since 1.25
	 */
	public static function canDeleteTag( $tag, User $user = null, int $flags = 0 ) {
		$tagUsage = self::tagUsageStatistics();

		if ( $user !== null ) {
			if ( !MediaWikiServices::getInstance()->getPermissionManager()
					->userHasRight( $user, 'deletechangetags' )
			) {
				return Status::newFatal( 'tags-delete-no-permission' );
			} elseif ( $user->getBlock() && $user->getBlock()->isSitewide() ) {
				return Status::newFatal( 'tags-manage-blocked', $user->getName() );
			}
		}

		if ( !isset( $tagUsage[$tag] ) && !in_array( $tag, self::listDefinedTags() ) ) {
			return Status::newFatal( 'tags-delete-not-found', $tag );
		}

		if ( $flags !== self::BYPASS_MAX_USAGE_CHECK &&
			isset( $tagUsage[$tag] ) &&
			$tagUsage[$tag] > self::MAX_DELETE_USES
		) {
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

		Hooks::runner()->onChangeTagCanDelete( $tag, $user, $status );
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
		$hitcount = $tagUsage[$tag] ?? 0;

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
		$hookContainer = MediaWikiServices::getInstance()->getHookContainer();
		if ( !$hookContainer->isRegistered( 'ChangeTagsListActive' ) ) {
			return $tags;
		}
		$hookRunner = new HookRunner( $hookContainer );
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		return $cache->getWithSetCallback(
			$cache->makeKey( 'active-tags' ),
			WANObjectCache::TTL_MINUTE * 5,
			function ( $oldValue, &$ttl, array &$setOpts ) use ( $tags, $hookRunner ) {
				$setOpts += Database::getCacheSetOptions( wfGetDB( DB_REPLICA ) );

				// Ask extensions which tags they consider active
				$hookRunner->onChangeTagsListActive( $tags );
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
	 * Lists tags explicitly defined in the `change_tag_def` table of the database.
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

				$tags = $dbr->selectFieldValues(
					'change_tag_def',
					'ctd_name',
					[ 'ctd_user_defined' => 1 ],
					$fname
				);

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
		$hookContainer = MediaWikiServices::getInstance()->getHookContainer();
		if ( !$hookContainer->isRegistered( 'ListDefinedTags' ) ) {
			return $tags;
		}
		$hookRunner = new HookRunner( $hookContainer );
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		return $cache->getWithSetCallback(
			$cache->makeKey( 'valid-tags-hook' ),
			WANObjectCache::TTL_MINUTE * 5,
			function ( $oldValue, &$ttl, array &$setOpts ) use ( $tags, $hookRunner ) {
				$setOpts += Database::getCacheSetOptions( wfGetDB( DB_REPLICA ) );

				$hookRunner->onListDefinedTags( $tags );
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
		$cache->touchCheckKey( $cache->makeKey( 'tags-usage-statistics' ) );

		MediaWikiServices::getInstance()->getChangeTagDefStore()->reloadMap();
	}

	/**
	 * Returns a map of any tags used on the wiki to number of edits
	 * tagged with them, ordered descending by the hitcount.
	 * This does not include tags defined somewhere that have never been applied.
	 * @return array Array of string => int
	 */
	public static function tagUsageStatistics() {
		$fname = __METHOD__;

		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		return $cache->getWithSetCallback(
			$cache->makeKey( 'tags-usage-statistics' ),
			WANObjectCache::TTL_MINUTE * 5,
			function ( $oldValue, &$ttl, array &$setOpts ) use ( $fname ) {
				$dbr = wfGetDB( DB_REPLICA );
				$res = $dbr->select(
					'change_tag_def',
					[ 'ctd_name', 'ctd_count' ],
					[],
					$fname,
					[ 'ORDER BY' => 'ctd_count DESC' ]
				);

				$out = [];
				foreach ( $res as $row ) {
					$out[$row->ctd_name] = $row->ctd_count;
				}

				return $out;
			},
			[
				'checkKeys' => [ $cache->makeKey( 'tags-usage-statistics' ) ],
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
		return MediaWikiServices::getInstance()->getPermissionManager()
				   ->userHasRight( $user, 'changetags' ) &&
			   (bool)self::listExplicitlyDefinedTags();
	}
}
