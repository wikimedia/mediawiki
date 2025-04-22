<?php
/**
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
 */

use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\Language\Language;
use MediaWiki\Language\RawMessage;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use MediaWiki\Xml\XmlSelect;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * @defgroup ChangeTags Change tagging
 * Tagging for revisions, log entries, or recent changes.
 *
 * These can be built-in tags from MediaWiki core, or applied by extensions
 * via edit filters (e.g. AbuseFilter), or applied by extensions via hooks
 * (e.g. onRecentChange_save), or manually by authorized users via the
 * SpecialEditTags interface.
 *
 * @see RecentChanges
 */

/**
 * Recent changes tagging.
 *
 * @ingroup ChangeTags
 */
class ChangeTags {
	/**
	 * The tagged edit changes the content model of the page.
	 */
	public const TAG_CONTENT_MODEL_CHANGE = 'mw-contentmodelchange';
	/**
	 * The tagged edit creates a new redirect (either by creating a new page or turning an
	 * existing page into a redirect).
	 */
	public const TAG_NEW_REDIRECT = 'mw-new-redirect';
	/**
	 * The tagged edit turns a redirect page into a non-redirect.
	 */
	public const TAG_REMOVED_REDIRECT = 'mw-removed-redirect';
	/**
	 * The tagged edit changes the target of a redirect page.
	 */
	public const TAG_CHANGED_REDIRECT_TARGET = 'mw-changed-redirect-target';
	/**
	 * The tagged edit blanks the page (replaces it with the empty string).
	 */
	public const TAG_BLANK = 'mw-blank';
	/**
	 * The tagged edit removes more than 90% of the content of the page.
	 */
	public const TAG_REPLACE = 'mw-replace';
	/**
	 * The tagged edit is a rollback (undoes the previous edit and all immediately preceding edits
	 * by the same user, and was performed via the "rollback" link available to advanced users
	 * or via the rollback API).
	 *
	 * The associated tag data is a JSON containing the edit result (see EditResult::jsonSerialize()).
	 */
	public const TAG_ROLLBACK = 'mw-rollback';
	/**
	 * The tagged edit is was performed via the "undo" link. (Usually this means that it undoes
	 * some previous edit, but the undo workflow includes an edit step so it could be anything.)
	 *
	 * The associated tag data is a JSON containing the edit result (see EditResult::jsonSerialize()).
	 */
	public const TAG_UNDO = 'mw-undo';
	/**
	 * The tagged edit restores the page to an earlier revision.
	 *
	 * The associated tag data is a JSON containing the edit result (see EditResult::jsonSerialize()).
	 */
	public const TAG_MANUAL_REVERT = 'mw-manual-revert';
	/**
	 * The tagged edit is reverted by a subsequent edit (which is tagged by one of TAG_ROLLBACK,
	 * TAG_UNDO, TAG_MANUAL_REVERT). Multiple edits might be reverted by the same edit.
	 *
	 * The associated tag data is a JSON containing the edit result (see EditResult::jsonSerialize())
	 * with an extra 'revertId' field containing the revision ID of the reverting edit.
	 */
	public const TAG_REVERTED = 'mw-reverted';
	/**
	 * This tagged edit was performed while importing media files using the importImages.php maintenance script.
	 */
	public const TAG_SERVER_SIDE_UPLOAD = 'mw-server-side-upload';

	/**
	 * List of tags which denote a revert of some sort. (See also TAG_REVERTED.)
	 */
	public const REVERT_TAGS = [ self::TAG_ROLLBACK, self::TAG_UNDO, self::TAG_MANUAL_REVERT ];

	/**
	 * Flag for canDeleteTag().
	 */
	public const BYPASS_MAX_USAGE_CHECK = 1;

	/**
	 * Can't delete tags with more than this many uses. Similar in intent to
	 * the bigdelete user right
	 * @todo Use the job queue for tag deletion to avoid this restriction
	 */
	private const MAX_DELETE_USES = 5000;

	/**
	 * Name of change_tag table
	 */
	private const CHANGE_TAG = 'change_tag';

	public const DISPLAY_TABLE_ALIAS = 'changetagdisplay';

	/**
	 * Constants that can be used to set the `activeOnly` parameter for calling
	 * self::buildCustomTagFilterSelect in order to improve function/parameter legibility
	 *
	 * If TAG_SET_ACTIVE_ONLY is used then the hit count for each tag will be checked against
	 * and only tags with hits will be returned
	 * Otherwise if TAG_SET_ALL is used then all tags will be returned regardlesss of if they've
	 * ever been used or not
	 */
	public const TAG_SET_ACTIVE_ONLY = true;
	public const TAG_SET_ALL = false;

	/**
	 * Constants that can be used to set the `useAllTags` parameter for calling
	 * self::buildCustomTagFilterSelect in order to improve function/parameter legibility
	 *
	 * If USE_ALL_TAGS is used then all on-wiki tags will be returned
	 * Otherwise if USE_SOFTWARE_TAGS_ONLY is used then only mediawiki core-defined tags
	 * will be returned
	 */
	public const USE_ALL_TAGS = true;
	public const USE_SOFTWARE_TAGS_ONLY = false;

	/**
	 * Loads defined core tags, checks for invalid types (if not array),
	 * and filters for supported and enabled (if $all is false) tags only.
	 *
	 * @param bool $all If true, return all valid defined tags. Otherwise, return only enabled ones.
	 * @return array Array of all defined/enabled tags.
	 * @deprecated since 1.41 use ChangeTagsStore::getSoftwareTags() instead.
	 */
	public static function getSoftwareTags( $all = false ) {
		return MediaWikiServices::getInstance()->getChangeTagsStore()->getSoftwareTags( $all );
	}

	/**
	 * Creates HTML for the given tags
	 *
	 * @param string $tags Comma-separated list of tags
	 * @param null|string $unused Unused (formerly: $page)
	 * @param MessageLocalizer|null $localizer
	 * @note Even though it takes null as a valid argument, a MessageLocalizer is preferred
	 *       in a new code, as the null value is subject to change in the future
	 * @return array Array with two items: (html, classes)
	 *   - html: String: HTML for displaying the tags (empty string when param $tags is empty)
	 *   - classes: Array of strings: CSS classes used in the generated html, one class for each tag
	 * @return-taint onlysafefor_htmlnoent
	 */
	public static function formatSummaryRow( $tags, $unused, ?MessageLocalizer $localizer = null ) {
		if ( $tags === '' || $tags === null ) {
			return [ '', [] ];
		}
		if ( !$localizer ) {
			$localizer = RequestContext::getMain();
		}

		$classes = [];

		$tags = explode( ',', $tags );
		$order = array_flip( MediaWikiServices::getInstance()->getChangeTagsStore()->listDefinedTags() );
		usort( $tags, static function ( $a, $b ) use ( $order ) {
			return ( $order[ $a ] ?? INF ) <=> ( $order[ $b ] ?? INF );
		} );

		$displayTags = [];
		foreach ( $tags as $tag ) {
			if ( $tag === '' ) {
				continue;
			}
			$classes[] = Sanitizer::escapeClass( "mw-tag-$tag" );
			$description = self::tagDescription( $tag, $localizer );
			if ( $description === false ) {
				continue;
			}
			$displayTags[] = Html::rawElement(
				'span',
				[ 'class' => 'mw-tag-marker ' .
					Sanitizer::escapeClass( "mw-tag-marker-$tag" ) ],
				$description
			);
		}

		if ( !$displayTags ) {
			return [ '', $classes ];
		}

		$markers = $localizer->msg( 'tag-list-wrapper' )
			->numParams( count( $displayTags ) )
			->rawParams( implode( ' ', $displayTags ) )
			->parse();
		$markers = Html::rawElement( 'span', [ 'class' => 'mw-tag-markers' ], $markers );

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
	 * @return Message|false Tag description, or false if tag is to be hidden.
	 */
	public static function tagShortDescriptionMessage( $tag, MessageLocalizer $context ) {
		$msg = $context->msg( "tag-$tag" );
		if ( !$msg->exists() ) {
			// No such message
			// Pass through ->msg(), even though it seems redundant, to avoid requesting
			// the user's language from session-less entry points (T227233)
			return $context->msg( new RawMessage( '$1', [ Message::plaintextParam( $tag ) ] ) );
		}
		if ( $msg->isDisabled() ) {
			// The message exists but is disabled, hide the tag.
			return false;
		}

		// Message exists and isn't disabled, use it.
		return $msg;
	}

	/**
	 * Get the tag's help link.
	 *
	 * Checks if message key "mediawiki:tag-$tag-helppage" exists in content language. If it does,
	 * and contains a URL or a page title, return a (possibly relative) link URL that points there.
	 * Otherwise return null.
	 *
	 * @since 1.43
	 * @param string $tag
	 * @param MessageLocalizer $context
	 * @return string|null Tag link, or null if not provided or invalid
	 */
	public static function tagHelpLink( $tag, MessageLocalizer $context ) {
		$msg = $context->msg( "tag-$tag-helppage" )->inContentLanguage();
		if ( $msg->exists() && !$msg->isDisabled() ) {
			$url = Skin::makeInternalOrExternalUrl( $msg->text() );
			if ( $url ) {
				return $url;
			}
		}
		return null;
	}

	/**
	 * Get a short description for a tag.
	 *
	 * The description combines the label from tagShortDescriptionMessage() with the link from
	 * tagHelpLink() (unless the label already contains some links).
	 *
	 * @param string $tag
	 * @param MessageLocalizer $context
	 * @return string|false Tag description or false if tag is to be hidden.
	 * @since 1.25 Returns false if tag is to be hidden.
	 */
	public static function tagDescription( $tag, MessageLocalizer $context ) {
		$msg = self::tagShortDescriptionMessage( $tag, $context );
		$link = self::tagHelpLink( $tag, $context );
		if ( $msg && $link ) {
			$label = $msg->parse();
			// Avoid invalid HTML caused by link wrapping if the label already contains a link
			if ( !str_contains( $label, '<a ' ) ) {
				return Html::rawElement( 'a', [ 'href' => $link ], $label );
			}
		}
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
	 * @return Message|false Message object of the tag long description or false if
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
	 * Add tags to a change given its rc_id, rev_id and/or log_id
	 *
	 * @deprecated since 1.41 use ChangeTagsStore instead.
	 * @param string|string[] $tags Tags to add to the change
	 * @param int|null $rc_id The rc_id of the change to add the tags to
	 * @param int|null $rev_id The rev_id of the change to add the tags to
	 * @param int|null $log_id The log_id of the change to add the tags to
	 * @param string|null $params Params to put in the ct_params field of table 'change_tag'
	 * @param RecentChange|null $rc Recent change, in case the tagging accompanies the action
	 * (this should normally be the case)
	 *
	 * @return bool False if no changes are made, otherwise true
	 */
	public static function addTags( $tags, $rc_id = null, $rev_id = null,
		$log_id = null, $params = null, ?RecentChange $rc = null
	) {
		return MediaWikiServices::getInstance()->getChangeTagsStore()->addTags(
			$tags, $rc_id, $rev_id, $log_id, $params, $rc
		);
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
	 * @deprecated since 1.41 use ChangeTagStore::updateTags()
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
	 * @param UserIdentity|null $user Tagging user, in case the tagging is subsequent to the tagged action
	 *
	 * @return array Index 0 is an array of tags actually added, index 1 is an
	 * array of tags actually removed, index 2 is an array of tags present on the
	 * revision or log entry before any changes were made
	 *
	 * @since 1.25
	 */
	public static function updateTags( $tagsToAdd, $tagsToRemove, &$rc_id = null,
		&$rev_id = null, &$log_id = null, $params = null, ?RecentChange $rc = null,
		?UserIdentity $user = null
	) {
		return MediaWikiServices::getInstance()->getChangeTagsStore()->updateTags(
			$tagsToAdd, $tagsToRemove, $rc_id, $rev_id, $log_id, $params, $rc, $user
		);
	}

	/**
	 * Return all the tags associated with the given recent change ID,
	 * revision ID, and/or log entry ID, along with any data stored with the tag.
	 *
	 * @deprecated since 1.41 use ChangeTagStore::getTagsWithData()
	 * @param IReadableDatabase $db the database to query
	 * @param int|null $rc_id
	 * @param int|null $rev_id
	 * @param int|null $log_id
	 * @return string[] Tag name => data. Data format is tag-specific.
	 * @since 1.36
	 */
	public static function getTagsWithData(
		IReadableDatabase $db, $rc_id = null, $rev_id = null, $log_id = null
	) {
		return MediaWikiServices::getInstance()->getChangeTagsStore()->getTagsWithData( $db, $rc_id, $rev_id, $log_id );
	}

	/**
	 * Return all the tags associated with the given recent change ID,
	 * revision ID, and/or log entry ID.
	 *
	 * @deprecated since 1.41 use ChangeTagStore::getTags()
	 * @param IReadableDatabase $db the database to query
	 * @param int|null $rc_id
	 * @param int|null $rev_id
	 * @param int|null $log_id
	 * @return string[]
	 */
	public static function getTags( IReadableDatabase $db, $rc_id = null, $rev_id = null, $log_id = null ) {
		return MediaWikiServices::getInstance()->getChangeTagsStore()->getTags( $db, $rc_id, $rev_id, $log_id );
	}

	/**
	 * Helper function to generate a fatal status with a 'not-allowed' type error.
	 *
	 * @param string $msgOne Message key to use in the case of one tag
	 * @param string $msgMulti Message key to use in the case of more than one tag
	 * @param string[] $tags Restricted tags (passed as $1 into the message, count of
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
	 * @param string[] $tags Tags that you are interested in applying
	 * @param Authority|null $performer whose permission you wish to check, or null to
	 * check for a generic non-blocked user with the relevant rights
	 * @param bool $checkBlock Whether to check the blocked status of $performer
	 * @return Status
	 * @since 1.25
	 */
	public static function canAddTagsAccompanyingChange(
		array $tags,
		?Authority $performer = null,
		$checkBlock = true
	) {
		$user = null;
		$services = MediaWikiServices::getInstance();
		if ( $performer !== null ) {
			if ( !$performer->isAllowed( 'applychangetags' ) ) {
				return Status::newFatal( 'tags-apply-no-permission' );
			}

			if ( $checkBlock && $performer->getBlock() && $performer->getBlock()->isSitewide() ) {
				return Status::newFatal(
					'tags-apply-blocked',
					$performer->getUser()->getName()
				);
			}

			// ChangeTagsAllowedAdd hook still needs a full User object
			$user = $services->getUserFactory()->newFromAuthority( $performer );
		}

		// to be applied, a tag has to be explicitly defined
		$allowedTags = $services->getChangeTagsStore()->listExplicitlyDefinedTags();
		( new HookRunner( $services->getHookContainer() ) )->onChangeTagsAllowedAdd( $allowedTags, $tags, $user );
		$disallowedTags = array_diff( $tags, $allowedTags );
		if ( $disallowedTags ) {
			return self::restrictedTagError( 'tags-apply-not-allowed-one',
				'tags-apply-not-allowed-multi', $disallowedTags );
		}

		return Status::newGood();
	}

	/**
	 * Is it OK to allow the user to adds and remove the given tags to/from a
	 * change?
	 *
	 * Extensions should not use this function, unless directly handling a user
	 * request to add or remove tags from an existing revision or log entry.
	 *
	 * @param string[] $tagsToAdd Tags that you are interested in adding
	 * @param string[] $tagsToRemove Tags that you are interested in removing
	 * @param Authority|null $performer whose permission you wish to check, or null to
	 * check for a generic non-blocked user with the relevant rights
	 * @return Status
	 * @since 1.25
	 */
	public static function canUpdateTags(
		array $tagsToAdd,
		array $tagsToRemove,
		?Authority $performer = null
	) {
		if ( $performer !== null ) {
			if ( !$performer->isDefinitelyAllowed( 'changetags' ) ) {
				return Status::newFatal( 'tags-update-no-permission' );
			}

			if ( $performer->getBlock() && $performer->getBlock()->isSitewide() ) {
				return Status::newFatal(
					'tags-update-blocked',
					$performer->getUser()->getName()
				);
			}
		}

		$changeTagStore = MediaWikiServices::getInstance()->getChangeTagsStore();
		if ( $tagsToAdd ) {
			// to be added, a tag has to be explicitly defined
			// @todo Allow extensions to define tags that can be applied by users...
			$explicitlyDefinedTags = $changeTagStore->listExplicitlyDefinedTags();
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
			$softwareDefinedTags = $changeTagStore->listSoftwareDefinedTags();
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
	 * @param Authority $performer who to check permissions and give credit for the action
	 * @return Status If successful, the value of this Status object will be an
	 * object (stdClass) with the following fields:
	 *  - logId: the ID of the added log entry, or null if no log entry was added
	 *    (i.e. no operation was performed)
	 *  - addedTags: an array containing the tags that were actually added
	 *  - removedTags: an array containing the tags that were actually removed
	 * @since 1.25
	 */
	public static function updateTagsWithChecks( $tagsToAdd, $tagsToRemove,
		$rc_id, $rev_id, $log_id, $params, string $reason, Authority $performer
	) {
		if ( !$tagsToAdd && !$tagsToRemove ) {
			// no-op, don't bother
			return Status::newGood( (object)[
				'logId' => null,
				'addedTags' => [],
				'removedTags' => [],
			] );
		}

		$tagsToAdd ??= [];
		$tagsToRemove ??= [];

		// are we allowed to do this?
		$result = self::canUpdateTags( $tagsToAdd, $tagsToRemove, $performer );
		if ( !$result->isOK() ) {
			$result->value = null;
			return $result;
		}

		// basic rate limiting
		$status = PermissionStatus::newEmpty();
		if ( !$performer->authorizeAction( 'changetags', $status ) ) {
			return Status::wrap( $status );
		}

		// do it!
		$changeTagStore = MediaWikiServices::getInstance()->getChangeTagsStore();
		[ $tagsAdded, $tagsRemoved, $initialTags ] = $changeTagStore->updateTags( $tagsToAdd,
			$tagsToRemove, $rc_id, $rev_id, $log_id, $params, null, $performer->getUser() );
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
		$logEntry->setPerformer( $performer->getUser() );
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

		$dbw = MediaWikiServices::getInstance()->getConnectionProvider()->getPrimaryDatabase();
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
	 * WARNING: If $filter_tag contains more than one tag and $exclude is false, this function
	 * will add DISTINCT, which may cause performance problems for your query unless you put
	 * the ID field of your table at the end of the ORDER BY, and set a GROUP BY equal to the
	 * ORDER BY. For example, if you had ORDER BY foo_timestamp DESC, you will now need
	 * GROUP BY foo_timestamp, foo_id ORDER BY foo_timestamp DESC, foo_id DESC.
	 *
	 * @deprecated since 1.41 use ChangeTagsStore::modifyDisplayQueryBuilder instead
	 * @param string|array &$tables Table names, see Database::select
	 * @param string|array &$fields Fields used in query, see Database::select
	 * @param string|array &$conds Conditions used in query, see Database::select
	 * @param array &$join_conds Join conditions, see Database::select
	 * @param string|array &$options Options, see Database::select
	 * @param string|array|false|null $filter_tag Tag(s) to select on (OR)
	 * @param bool $exclude If true, exclude tag(s) from $filter_tag (NOR)
	 *
	 */
	public static function modifyDisplayQuery( &$tables, &$fields, &$conds,
		&$join_conds, &$options, $filter_tag = '', bool $exclude = false
	) {
		MediaWikiServices::getInstance()->getChangeTagsStore()->modifyDisplayQuery(
			$tables,
			$fields,
			$conds,
			$join_conds,
			$options,
			$filter_tag,
			$exclude
		);
	}

	/**
	 * Get the name of the change_tag table to use for modifyDisplayQuery().
	 * This also does first-call initialisation of the table in testing mode.
	 *
	 * @deprecated since 1.41 use ChangeTags::CHANGE_TAG or 'change_tag' instead.
	 *   Note that directly querying this table is discouraged, try using one of
	 *   the existing functions instead.
	 * @return string
	 */
	public static function getDisplayTableName() {
		return self::CHANGE_TAG;
	}

	/**
	 * Make the tag summary subquery based on the given tables and return it.
	 *
	 * @deprecated since 1.41 use ChangeTagStore instead
	 * @param string|array $tables Table names, see Database::select
	 *
	 * @return string tag summary subqeury
	 */
	public static function makeTagSummarySubquery( $tables ) {
		return MediaWikiServices::getInstance()->getChangeTagsStore()->makeTagSummarySubquery( $tables );
	}

	/**
	 * Build a text box to select a change tag. The tag set can be customized via the $activeOnly
	 * and $useAllTags parameters and defaults to all active tags.
	 *
	 * @param string $selected Tag to select by default
	 * @param bool $ooui Use an OOUI TextInputWidget as selector instead of a non-OOUI input field
	 *        You need to call OutputPage::enableOOUI() yourself.
	 * @param IContextSource|null $context
	 * @note Even though it takes null as a valid argument, an IContextSource is preferred
	 *       in a new code, as the null value can change in the future
	 * @param bool $activeOnly Whether to filter for tags that have been used or not
	 * @param bool $useAllTags Whether to use all known tags or to only use software defined tags
	 *        These map to ChangeTagsStore->listDefinedTags and ChangeTagsStore->getSoftwareTags respectively
	 * @return array an array of (label, selector)
	 */
	public static function buildTagFilterSelector(
		$selected = '', $ooui = false, ?IContextSource $context = null,
		bool $activeOnly = self::TAG_SET_ACTIVE_ONLY,
		bool $useAllTags = self::USE_ALL_TAGS
	) {
		if ( !$context ) {
			$context = RequestContext::getMain();
		}

		$config = $context->getConfig();
		$changeTagStore = MediaWikiServices::getInstance()->getChangeTagsStore();
		if ( !$config->get( MainConfigNames::UseTagFilter ) ||
		!count( $changeTagStore->listDefinedTags() ) ) {
			return [];
		}

		$tags = self::getChangeTagList(
			$context,
			$context->getLanguage(),
			$activeOnly,
			$useAllTags,
			true
		);

		$autocomplete = [];
		foreach ( $tags as $tagInfo ) {
			$autocomplete[ $tagInfo['label'] ] = $tagInfo['name'];
		}

		$data = [
			Html::rawElement(
				'label',
				[ 'for' => 'tagfilter' ],
				$context->msg( 'tag-filter' )->parse()
			)
		];

		if ( $ooui ) {
			$options = Html::listDropdownOptionsOoui( $autocomplete );

			$data[] = new OOUI\ComboBoxInputWidget( [
				'id' => 'tagfilter',
				'name' => 'tagfilter',
				'value' => $selected,
				'classes' => 'mw-tagfilter-input',
				'options' => $options,
			] );
		} else {
			$datalist = new XmlSelect( false, 'tagfilter-datalist' );
			$datalist->setTagName( 'datalist' );
			$datalist->addOptions( $autocomplete );

			$data[] = Html::input(
				'tagfilter',
				$selected,
				'text',
				[
					'class' => [ 'mw-tagfilter-input', 'mw-ui-input', 'mw-ui-input-inline' ],
					'size' => 20,
					'id' => 'tagfilter',
					'list' => 'tagfilter-datalist',
				]
			) . $datalist->getHTML();
		}

		return $data;
	}

	/**
	 * Set ctd_user_defined = 1 in change_tag_def without checking that the tag name is valid.
	 * Extensions should NOT use this function; they can use the ListDefinedTags
	 * hook instead.
	 *
	 * @deprecated since 1.41 use ChangeTagsStore
	 * @param string $tag Tag to create
	 * @since 1.25
	 */
	public static function defineTag( $tag ) {
		MediaWikiServices::getInstance()->getChangeTagsStore()->defineTag( $tag );
	}

	/**
	 * Is it OK to allow the user to activate this tag?
	 *
	 * @param string $tag Tag that you are interested in activating
	 * @param Authority|null $performer whose permission you wish to check, or null if
	 * you don't care (e.g. maintenance scripts)
	 * @return Status
	 * @since 1.25
	 */
	public static function canActivateTag( $tag, ?Authority $performer = null ) {
		if ( $performer !== null ) {
			if ( !$performer->isAllowed( 'managechangetags' ) ) {
				return Status::newFatal( 'tags-manage-no-permission' );
			}
			if ( $performer->getBlock() && $performer->getBlock()->isSitewide() ) {
				return Status::newFatal(
					'tags-manage-blocked',
					$performer->getUser()->getName()
				);
			}
		}

		// defined tags cannot be activated (a defined tag is either extension-
		// defined, in which case the extension chooses whether or not to active it;
		// or user-defined, in which case it is considered active)
		$changeTagStore = MediaWikiServices::getInstance()->getChangeTagsStore();
		$definedTags = $changeTagStore->listDefinedTags();
		if ( in_array( $tag, $definedTags ) ) {
			return Status::newFatal( 'tags-activate-not-allowed', $tag );
		}

		// non-existing tags cannot be activated
		if ( !isset( $changeTagStore->tagUsageStatistics()[$tag] ) ) { // we already know the tag is undefined
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
	 * @param Authority $performer who to check permissions and give credit for the action
	 * @param bool $ignoreWarnings Can be used for API interaction, default false
	 * @param array $logEntryTags Change tags to apply to the entry
	 * that will be created in the tag management log
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 * @since 1.25
	 */
	public static function activateTagWithChecks( string $tag, string $reason, Authority $performer,
		bool $ignoreWarnings = false, array $logEntryTags = []
	) {
		// are we allowed to do this?
		$result = self::canActivateTag( $tag, $performer );
		if ( $ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
			$result->value = null;
			return $result;
		}
		$changeTagStore = MediaWikiServices::getInstance()->getChangeTagsStore();

		$changeTagStore->defineTag( $tag );

		$logId = $changeTagStore->logTagManagementAction( 'activate', $tag, $reason, $performer->getUser(),
			null, $logEntryTags );

		return Status::newGood( $logId );
	}

	/**
	 * Is it OK to allow the user to deactivate this tag?
	 *
	 * @param string $tag Tag that you are interested in deactivating
	 * @param Authority|null $performer whose permission you wish to check, or null if
	 * you don't care (e.g. maintenance scripts)
	 * @return Status
	 * @since 1.25
	 */
	public static function canDeactivateTag( $tag, ?Authority $performer = null ) {
		if ( $performer !== null ) {
			if ( !$performer->isAllowed( 'managechangetags' ) ) {
				return Status::newFatal( 'tags-manage-no-permission' );
			}
			if ( $performer->getBlock() && $performer->getBlock()->isSitewide() ) {
				return Status::newFatal(
					'tags-manage-blocked',
					$performer->getUser()->getName()
				);
			}
		}

		// only explicitly-defined tags can be deactivated
		$explicitlyDefinedTags = MediaWikiServices::getInstance()->getChangeTagsStore()->listExplicitlyDefinedTags();
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
	 * @param Authority $performer who to check permissions and give credit for the action
	 * @param bool $ignoreWarnings Can be used for API interaction, default false
	 * @param array $logEntryTags Change tags to apply to the entry
	 * that will be created in the tag management log
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 * @since 1.25
	 */
	public static function deactivateTagWithChecks( string $tag, string $reason, Authority $performer,
		bool $ignoreWarnings = false, array $logEntryTags = []
	) {
		// are we allowed to do this?
		$result = self::canDeactivateTag( $tag, $performer );
		if ( $ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
			$result->value = null;
			return $result;
		}
		$changeTagStore = MediaWikiServices::getInstance()->getChangeTagsStore();

		$changeTagStore->undefineTag( $tag );

		$logId = $changeTagStore->logTagManagementAction( 'deactivate', $tag, $reason,
			$performer->getUser(), null, $logEntryTags );

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
	 * @param Authority|null $performer whose permission you wish to check, or null if
	 * you don't care (e.g. maintenance scripts)
	 * @return Status
	 * @since 1.25
	 */
	public static function canCreateTag( $tag, ?Authority $performer = null ) {
		$user = null;
		$services = MediaWikiServices::getInstance();
		if ( $performer !== null ) {
			if ( !$performer->isAllowed( 'managechangetags' ) ) {
				return Status::newFatal( 'tags-manage-no-permission' );
			}
			if ( $performer->getBlock() && $performer->getBlock()->isSitewide() ) {
				return Status::newFatal(
					'tags-manage-blocked',
					$performer->getUser()->getName()
				);
			}
			// ChangeTagCanCreate hook still needs a full User object
			$user = $services->getUserFactory()->newFromAuthority( $performer );
		}

		$status = self::isTagNameValid( $tag );
		if ( !$status->isGood() ) {
			return $status;
		}

		// does the tag already exist?
		$changeTagStore = $services->getChangeTagsStore();
		if (
			isset( $changeTagStore->tagUsageStatistics()[$tag] ) ||
			in_array( $tag, $changeTagStore->listDefinedTags() )
		) {
			return Status::newFatal( 'tags-create-already-exists', $tag );
		}

		// check with hooks
		$canCreateResult = Status::newGood();
		( new HookRunner( $services->getHookContainer() ) )->onChangeTagCanCreate( $tag, $user, $canCreateResult );
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
	 * @param Authority $performer who to check permissions and give credit for the action
	 * @param bool $ignoreWarnings Can be used for API interaction, default false
	 * @param array $logEntryTags Change tags to apply to the entry
	 * that will be created in the tag management log
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 * @since 1.25
	 */
	public static function createTagWithChecks( string $tag, string $reason, Authority $performer,
		bool $ignoreWarnings = false, array $logEntryTags = []
	) {
		// are we allowed to do this?
		$result = self::canCreateTag( $tag, $performer );
		if ( $ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
			$result->value = null;
			return $result;
		}

		$changeTagStore = MediaWikiServices::getInstance()->getChangeTagsStore();
		$changeTagStore->defineTag( $tag );
		$logId = $changeTagStore->logTagManagementAction( 'create', $tag, $reason,
			$performer->getUser(), null, $logEntryTags );

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
	 * @deprecated since 1.41 use ChangeTagsStore instead
	 * @param string $tag Tag to remove
	 * @return Status The returned status will be good unless a hook changed it
	 * @since 1.25
	 */
	public static function deleteTagEverywhere( $tag ) {
		return MediaWikiServices::getInstance()->getChangeTagsStore()->deleteTagEverywhere( $tag );
	}

	/**
	 * Is it OK to allow the user to delete this tag?
	 *
	 * @param string $tag Tag that you are interested in deleting
	 * @param Authority|null $performer whose permission you wish to check, or null if
	 * you don't care (e.g. maintenance scripts)
	 * @param int $flags Use ChangeTags::BYPASS_MAX_USAGE_CHECK to ignore whether
	 *  there are more uses than we would normally allow to be deleted through the
	 *  user interface.
	 * @return Status
	 * @since 1.25
	 */
	public static function canDeleteTag( $tag, ?Authority $performer = null, int $flags = 0 ) {
		$user = null;
		$services = MediaWikiServices::getInstance();
		if ( $performer !== null ) {
			if ( !$performer->isAllowed( 'deletechangetags' ) ) {
				return Status::newFatal( 'tags-delete-no-permission' );
			}
			if ( $performer->getBlock() && $performer->getBlock()->isSitewide() ) {
				return Status::newFatal(
					'tags-manage-blocked',
					$performer->getUser()->getName()
				);
			}
			// ChangeTagCanDelete hook still needs a full User object
			$user = $services->getUserFactory()->newFromAuthority( $performer );
		}

		$changeTagStore = $services->getChangeTagsStore();
		$tagUsage = $changeTagStore->tagUsageStatistics();
		if (
			!isset( $tagUsage[$tag] ) &&
			!in_array( $tag, $changeTagStore->listDefinedTags() )
		) {
			return Status::newFatal( 'tags-delete-not-found', $tag );
		}

		if ( $flags !== self::BYPASS_MAX_USAGE_CHECK &&
			isset( $tagUsage[$tag] ) &&
			$tagUsage[$tag] > self::MAX_DELETE_USES
		) {
			return Status::newFatal( 'tags-delete-too-many-uses', $tag, self::MAX_DELETE_USES );
		}

		$softwareDefined = $changeTagStore->listSoftwareDefinedTags();
		if ( in_array( $tag, $softwareDefined ) ) {
			// extension-defined tags can't be deleted unless the extension
			// specifically allows it
			$status = Status::newFatal( 'tags-delete-not-allowed' );
		} else {
			// user-defined tags are deletable unless otherwise specified
			$status = Status::newGood();
		}

		( new HookRunner( $services->getHookContainer() ) )->onChangeTagCanDelete( $tag, $user, $status );
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
	 * @param Authority $performer who to check permissions and give credit for the action
	 * @param bool $ignoreWarnings Can be used for API interaction, default false
	 * @param array $logEntryTags Change tags to apply to the entry
	 * that will be created in the tag management log
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 * @since 1.25
	 */
	public static function deleteTagWithChecks( string $tag, string $reason, Authority $performer,
		bool $ignoreWarnings = false, array $logEntryTags = []
	) {
		$changeTagStore = MediaWikiServices::getInstance()->getChangeTagsStore();
		// are we allowed to do this?
		$result = self::canDeleteTag( $tag, $performer );
		if ( $ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
			$result->value = null;
			return $result;
		}

		// store the tag usage statistics
		$hitcount = $changeTagStore->tagUsageStatistics()[$tag] ?? 0;

		// do it!
		$deleteResult = $changeTagStore->deleteTagEverywhere( $tag );
		if ( !$deleteResult->isOK() ) {
			return $deleteResult;
		}

		// log it
		$changeTagStore = MediaWikiServices::getInstance()->getChangeTagsStore();
		$logId = $changeTagStore->logTagManagementAction( 'delete', $tag, $reason, $performer->getUser(),
			$hitcount, $logEntryTags );

		$deleteResult->value = $logId;
		return $deleteResult;
	}

	/**
	 * Lists those tags which core or extensions report as being "active".
	 *
	 * @deprecated since 1.41 use ChangeTagsStore instead
	 * @return array
	 * @since 1.25
	 */
	public static function listSoftwareActivatedTags() {
		return MediaWikiServices::getInstance()->getChangeTagsStore()->listSoftwareActivatedTags();
	}

	/**
	 * Basically lists defined tags which count even if they aren't applied to anything.
	 * It returns a union of the results of listExplicitlyDefinedTags() and
	 * listSoftwareDefinedTags()
	 *
	 * @deprecated since 1.41 use ChangeTagsStore instead
	 * @return string[] Array of strings: tags
	 */
	public static function listDefinedTags() {
		return MediaWikiServices::getInstance()->getChangeTagsStore()->listDefinedTags();
	}

	/**
	 * Lists tags explicitly defined in the `change_tag_def` table of the database.
	 *
	 * Tries memcached first.
	 *
	 * @deprecated since 1.41 use ChangeTagsStore instead
	 * @return string[] Array of strings: tags
	 * @since 1.25
	 */
	public static function listExplicitlyDefinedTags() {
		return MediaWikiServices::getInstance()->getChangeTagsStore()->listExplicitlyDefinedTags();
	}

	/**
	 * Lists tags defined by core or extensions using the ListDefinedTags hook.
	 * Extensions need only define those tags they deem to be in active use.
	 *
	 * Tries memcached first.
	 *
	 * @deprecated since 1.41 use ChangeTagsStore instead
	 * @return string[] Array of strings: tags
	 * @since 1.25
	 */
	public static function listSoftwareDefinedTags() {
		return MediaWikiServices::getInstance()->getChangeTagsStore()->listSoftwareDefinedTags();
	}

	/**
	 * Invalidates the short-term cache of defined tags used by the
	 * list*DefinedTags functions, as well as the tag statistics cache.
	 * @deprecated since 1.41 use ChangeTagsStore instead
	 * @since 1.25
	 */
	public static function purgeTagCacheAll() {
		MediaWikiServices::getInstance()->getChangeTagsStore()->purgeTagCacheAll();
	}

	/**
	 * Returns a map of any tags used on the wiki to number of edits
	 * tagged with them, ordered descending by the hitcount.
	 * This does not include tags defined somewhere that have never been applied.
	 *
	 * @deprecated since 1.41 use ChangeTagsStore
	 * @return array Array of string => int
	 */
	public static function tagUsageStatistics() {
		return MediaWikiServices::getInstance()->getChangeTagsStore()->tagUsageStatistics();
	}

	/**
	 * Maximum length of a tag description in UTF-8 characters.
	 * Longer descriptions will be truncated.
	 */
	private const TAG_DESC_CHARACTER_LIMIT = 120;

	/**
	 * Get information about change tags, without parsing messages, for tag filter dropdown menus.
	 * By default, this will return explicitly-defined and software-defined tags that are currently active (have hits)
	 *
	 * Message contents are the raw values (->plain()), because parsing messages is expensive.
	 * Even though we're not parsing messages, building a data structure with the contents of
	 * hundreds of i18n messages is still not cheap (see T223260#5370610), so this function
	 * caches its output in WANCache for up to 24 hours.
	 *
	 * Returns an array of associative arrays with information about each tag:
	 * - name: Tag name (string)
	 * - labelMsg: Short description message (Message object, or false for hidden tags)
	 * - label: Short description message (raw message contents)
	 * - descriptionMsg: Long description message (Message object)
	 * - description: Long description message (raw message contents)
	 * - cssClass: CSS class to use for RC entries with this tag
	 * - helpLink: Link to a help page describing this tag (string or null)
	 * - hits: Number of RC entries that have this tag
	 *
	 * This data is consumed by the `mediawiki.rcfilters.filters.ui` module,
	 * specifically `mw.rcfilters.dm.FilterGroup` and `mw.rcfilters.dm.FilterItem`.
	 *
	 * @param MessageLocalizer $localizer
	 * @param Language $lang
	 * @param bool $activeOnly
	 * @param bool $useAllTags
	 * @return array[] Information about each tag
	 */
	public static function getChangeTagListSummary(
		MessageLocalizer $localizer,
		Language $lang,
		bool $activeOnly = self::TAG_SET_ACTIVE_ONLY,
		bool $useAllTags = self::USE_ALL_TAGS
	) {
		$changeTagStore = MediaWikiServices::getInstance()->getChangeTagsStore();

		if ( $useAllTags ) {
			$tagKeys = $changeTagStore->listDefinedTags();
			$cacheKey = 'tags-list-summary';
		} else {
			$tagKeys = $changeTagStore->getSoftwareTags( true );
			$cacheKey = 'core-software-tags-summary';
		}

		// if $tagHitCounts exists, check against it later to determine whether or not to omit tags
		$tagHitCounts = null;
		if ( $activeOnly ) {
			$tagHitCounts = $changeTagStore->tagUsageStatistics();
		} else {
			// The full set of tags should use a different cache key than the subset
			$cacheKey .= '-all';
		}

		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		return $cache->getWithSetCallback(
			$cache->makeKey( $cacheKey, $lang->getCode() ),
			WANObjectCache::TTL_DAY,
			static function ( $oldValue, &$ttl, array &$setOpts ) use ( $localizer, $tagKeys, $tagHitCounts ) {
				$result = [];
				foreach ( $tagKeys as $tagName ) {
					// Only list tags that are still actively defined
					if ( $tagHitCounts !== null ) {
						// Only list tags with more than 0 hits
						$hits = $tagHitCounts[$tagName] ?? 0;
						if ( $hits <= 0 ) {
							continue;
						}
					}

					$labelMsg = self::tagShortDescriptionMessage( $tagName, $localizer );
					$helpLink = self::tagHelpLink( $tagName, $localizer );
					$descriptionMsg = self::tagLongDescriptionMessage( $tagName, $localizer );
					// Don't cache the message object, use the correct MessageLocalizer to parse later.
					$result[] = [
						'name' => $tagName,
						'labelMsg' => (bool)$labelMsg,
						'label' => $labelMsg ? $labelMsg->plain() : $tagName,
						'descriptionMsg' => (bool)$descriptionMsg,
						'description' => $descriptionMsg ? $descriptionMsg->plain() : '',
						'helpLink' => $helpLink,
						'cssClass' => Sanitizer::escapeClass( 'mw-tag-' . $tagName ),
					];
				}
				return $result;
			}
		);
	}

	/**
	 * Get information about change tags for tag filter dropdown menus.
	 *
	 * This manipulates the label and description of each tag, which are parsed, stripped
	 * and (in the case of description) truncated versions of these messages. Message
	 * parsing is expensive, so to detect whether the tag list has changed, use
	 * getChangeTagListSummary() instead.
	 *
	 * @param MessageLocalizer $localizer
	 * @param Language $lang
	 * @param bool $activeOnly
	 * @param bool $useAllTags
	 * @param bool $labelsOnly Do not parse descriptions and omit 'description' in the result
	 * @return array[] Same as getChangeTagListSummary(), with messages parsed, stripped and truncated
	 */
	public static function getChangeTagList(
		MessageLocalizer $localizer, Language $lang,
		bool $activeOnly = self::TAG_SET_ACTIVE_ONLY, bool $useAllTags = self::USE_ALL_TAGS,
		$labelsOnly = false
	) {
		$tags = self::getChangeTagListSummary( $localizer, $lang, $activeOnly, $useAllTags );

		foreach ( $tags as &$tagInfo ) {
			if ( $tagInfo['labelMsg'] ) {
				// Optimization: Skip the parsing if the label contains only plain text (T344352)
				if ( wfEscapeWikiText( $tagInfo['label'] ) !== $tagInfo['label'] ) {
					// Use localizer with the correct page title to parse plain message from the cache.
					$labelMsg = new RawMessage( $tagInfo['label'] );
					$tagInfo['label'] = Sanitizer::stripAllTags( $localizer->msg( $labelMsg )->parse() );
				}
			} else {
				$tagInfo['label'] = $localizer->msg( 'tag-hidden', $tagInfo['name'] )->text();
			}
			// Optimization: Skip parsing the descriptions if not needed by the caller (T344352)
			if ( $labelsOnly ) {
				unset( $tagInfo['description'] );
			} elseif ( $tagInfo['descriptionMsg'] ) {
				// Optimization: Skip the parsing if the description contains only plain text (T344352)
				if ( wfEscapeWikiText( $tagInfo['description'] ) !== $tagInfo['description'] ) {
					$descriptionMsg = new RawMessage( $tagInfo['description'] );
					$tagInfo['description'] = Sanitizer::stripAllTags( $localizer->msg( $descriptionMsg )->parse() );
				}
				$tagInfo['description'] = $lang->truncateForVisual( $tagInfo['description'],
					self::TAG_DESC_CHARACTER_LIMIT );
			}
			unset( $tagInfo['labelMsg'] );
			unset( $tagInfo['descriptionMsg'] );
		}

		// Instead of sorting by hit count (disabled for now), sort by display name
		usort( $tags, static function ( $a, $b ) {
			return strcasecmp( $a['label'], $b['label'] );
		} );
		return $tags;
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
	 * @param Authority $performer
	 * @return bool
	 */
	public static function showTagEditingUI( Authority $performer ) {
		$changeTagStore = MediaWikiServices::getInstance()->getChangeTagsStore();
		return $performer->isAllowed( 'changetags' ) && (bool)$changeTagStore->listExplicitlyDefinedTags();
	}
}
