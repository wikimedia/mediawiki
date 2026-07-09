<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\ChangeTags;

use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Language\Language;
use MediaWiki\Language\MessageLocalizer;
use MediaWiki\Language\RawMessage;
use MediaWiki\Language\SimpleLocalizationContext;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\RevisionDelete\RevDelLogList;
use MediaWiki\Skin\Skin;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;

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
	 * The tagged edit recreates a page that has been previously deleted.
	 */
	public const TAG_RECREATE = 'mw-recreated';
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
	 * This tagged temporary account auto-creation was performed via Special:Mytalk
	 * from an IP address that is blocked from account creation.
	 */
	public const TAG_IPBLOCK_APPEAL = 'mw-ipblock-appeal';
	/**
	 * This tagged edit was performed on a page with the JavaScript content model on a page
	 * not a subpage of the user's own user page.
	 */
	public const TAG_EDITED_OTHER_USERS_JS = 'mw-edited-other-users-js';
	/**
	 * This tagged edit was performed on a page with the CSS content model on a page
	 * not a subpage of the user's own user page.
	 */
	public const TAG_EDITED_OTHER_USERS_CSS = 'mw-edited-other-users-css';

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
	 * Otherwise if TAG_SET_ALL is used then all tags will be returned regardless of if they've
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
	 * Creates HTML for the given tags
	 *
	 * @deprecated Since 1.47. Use {@link ChangeTagsFormatter::formatTagsAsSummaryList} instead.
	 * @param string $tags Comma-separated list of tags
	 * @param null|string $unused Unused (formerly: $page)
	 * @param MessageLocalizer $localizer
	 * @return array Array with two items: (html, classes)
	 *   - html: String: HTML for displaying the tags (empty string when param $tags is empty)
	 *   - classes: Array of strings: CSS classes used in the generated html, one class for each tag
	 * @return-taint onlysafefor_htmlnoent
	 */
	public static function formatSummaryRow( $tags, $unused, MessageLocalizer $localizer ) {
		wfDeprecated( __METHOD__, '1.47' );
		return MediaWikiServices::getInstance()->getChangeTagsFormatter()->formatTagsAsSummaryList(
			$tags,
			$localizer,
			RequestContext::getMain()->getAuthority()
		);
	}

	/**
	 * Get the message object for the tag's short description.
	 *
	 * Checks if message key "mediawiki:tag-$tag" exists. If it does not,
	 * returns the tag name in a RawMessage. If the message exists, it is
	 * used, provided it is not disabled. If the message is disabled, we
	 * consider the tag hidden, and return false.
	 *
	 * @deprecated Since 1.47. Use {@link ChangeTagsFormatter::getTagDescription} instead.
	 * @since 1.34
	 * @param string $tag
	 * @param MessageLocalizer $context
	 * @return Message|false Tag description, or false if tag is to be hidden.
	 */
	public static function tagShortDescriptionMessage( $tag, MessageLocalizer $context ) {
		wfDeprecated( __METHOD__, '1.47' );
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
	 * @deprecated Since 1.47. Use {@link ChangeTagsFormatter::getTagDescription} instead.
	 * @since 1.43
	 * @param string $tag
	 * @param MessageLocalizer $context
	 * @return string|null Tag link, or null if not provided or invalid
	 */
	public static function tagHelpLink( $tag, MessageLocalizer $context ) {
		wfDeprecated( __METHOD__, '1.47' );
		$msg = $context->msg( "tag-$tag-helppage" )->inContentLanguage();
		if ( !$msg->isDisabled() ) {
			return Skin::makeInternalOrExternalUrl( $msg->text() ) ?: null;
		}
		return null;
	}

	/**
	 * Get a short description for a tag.
	 *
	 * The description combines the label from tagShortDescriptionMessage() with the link from
	 * tagHelpLink() (unless the label already contains some links).
	 *
	 * @deprecated Since 1.47. Use {@link ChangeTagsFormatter::getTagDescription} instead.
	 * @param string $tag
	 * @param MessageLocalizer $context
	 * @return string|false Tag description or false if tag is to be hidden.
	 * @since 1.25 Returns false if tag is to be hidden.
	 */
	public static function tagDescription( $tag, MessageLocalizer $context ) {
		wfDeprecated( __METHOD__, '1.47' );
		$description = MediaWikiServices::getInstance()->getChangeTagsFormatter()->getTagDescription( $tag, $context );
		if ( $description === '' ) {
			return false;
		}
		return $description;
	}

	/**
	 * Get the message object for the tag's long description.
	 *
	 * Checks if message key "mediawiki:tag-$tag-description" exists. If it does not,
	 * or if message is disabled, returns false. Otherwise, returns the message object
	 * for the long description.
	 *
	 * @deprecated Since 1.47. Use {@link ChangeTagsFormatter::getTagDescription} instead.
	 * @param string $tag
	 * @param MessageLocalizer $context
	 * @return Message|false Message object of the tag long description or false if
	 *  there is no description.
	 */
	public static function tagLongDescriptionMessage( $tag, MessageLocalizer $context ) {
		wfDeprecated( __METHOD__, '1.47' );
		$msg = $context->msg( "tag-$tag-description" );
		return $msg->isDisabled() ? false : $msg;
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
		$tags = array_values( $tags );
		$count = count( $tags );
		$status = Status::newFatal( ( $count > 1 ) ? $msgMulti : $msgOne,
			Message::listParam( $tags ), $count );
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
	 * @deprecated Since 1.47
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
		wfDeprecated( __METHOD__, '1.47' );
		return self::canUpdateTagsInternal(
			$tagsToAdd,
			$tagsToRemove,
			$performer ?? new UltimateAuthority( RequestContext::getMain()->getUser() )
		);
	}

	/**
	 * Decides whether the given {@link Authority} can add and remove the given tags
	 * to/from a change.
	 *
	 * NOTE: The method is named with "internal" so that the existing public version
	 * of this method can be still exist but be deprecated. Once {@link self::canUpdateTags()}
	 * is dropped, this method should be renamed to remove "internal"
	 *
	 * @param string[] $tagsToAdd Tags that are being added
	 * @param string[] $tagsToRemove Tags that are being removed
	 * @param Authority $performer
	 * @return Status
	 */
	private static function canUpdateTagsInternal(
		array $tagsToAdd,
		array $tagsToRemove,
		Authority $performer
	): Status {
		if ( !$performer->isDefinitelyAllowed( 'changetags' ) ) {
			return Status::newFatal( 'tags-update-no-permission' );
		}

		if ( $performer->getBlock() && $performer->getBlock()->isSitewide() ) {
			return Status::newFatal(
				'tags-update-blocked',
				$performer->getUser()->getName()
			);
		}

		$changeTagsStore = MediaWikiServices::getInstance()->getChangeTagsStore();
		if ( $tagsToAdd ) {
			// to be added, a tag has to be explicitly defined
			// @todo Allow extensions to define tags that can be applied by users...
			$explicitlyDefinedTags = $changeTagsStore->listExplicitlyDefinedTags();
			$diff = array_diff( $tagsToAdd, $explicitlyDefinedTags );
			if ( $diff ) {
				return self::restrictedTagError( 'tags-update-add-not-allowed-one',
					'tags-update-add-not-allowed-multi', $diff );
			}
		}

		if ( $tagsToRemove ) {
			// Restricted tags can only be removed by users who can view the tag. We do this
			// separately so that private tags that are undefined cannot be removed from changes.
			$unviewableRestricted = array_filter(
				$tagsToRemove,
				static fn ( $tag ) => $changeTagsStore->isRestrictedTag( $tag )
					&& !$changeTagsStore->canViewTag( $tag, $performer )
			);
			if ( $unviewableRestricted ) {
				return self::restrictedTagError( 'tags-update-remove-not-allowed-one',
					'tags-update-remove-not-allowed-multi', $unviewableRestricted );
			}

			// to be removed, a tag must not be defined by an extension, or equivalently it
			// has to be either explicitly defined or not defined at all
			// (assuming no edge case of a tag both explicitly-defined and extension-defined)
			$softwareDefinedTags = $changeTagsStore->listSoftwareDefinedTags();
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
	 * This validates that the provided {@link Authority} can make the changes, but
	 * it does not check whether the *_id parameters are a valid combination.
	 * That is up to you to enforce. See ApiTag::execute() for an example.
	 *
	 * Extensions should generally avoid this function. Call
	 * ChangeTagsStore->updateTags() instead, unless directly handling a user request
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
		$result = self::canUpdateTagsInternal( $tagsToAdd, $tagsToRemove, $performer );
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
		$changeTagsStore = MediaWikiServices::getInstance()->getChangeTagsStore();
		[ $tagsAdded, $tagsRemoved, $initialTags ] = $changeTagsStore->updateTags( $tagsToAdd,
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
	 * Build a text box to select a change tag. The tag set can be customized via the $activeOnly
	 * and $useAllTags parameters and defaults to all active tags.
	 *
	 * @deprecated Since 1.47. Use {@link ChangeTagsFormatter::buildTagFilter()} instead.
	 * @param string $selected Tag to select by default
	 * @param bool $ooui Use an OOUI TextInputWidget as selector instead of a non-OOUI input field
	 *        You need to call OutputPage::enableOOUI() yourself.
	 * @param IContextSource $context
	 * @param bool $activeOnly Whether to filter for tags that have been used or not
	 * @param bool $useAllTags Whether to use all known tags or to only use software defined tags
	 *        These map to ChangeTagsStore->listDefinedTags and ChangeTagsStore->getCoreDefinedTags respectively
	 * @return array{0:string,1:string}|null Two chunks of HTML (label, and dropdown menu) or null if disabled
	 */
	public static function buildTagFilterSelector(
		$selected, $ooui, IContextSource $context,
		bool $activeOnly = self::TAG_SET_ACTIVE_ONLY,
		bool $useAllTags = self::USE_ALL_TAGS
	) {
		wfDeprecated( __METHOD__, '1.47' );
		return MediaWikiServices::getInstance()->getChangeTagsFormatter()->buildTagFilter(
			$selected,
			$ooui ? 'ooui' : 'other',
			$context,
			$activeOnly,
			$useAllTags
		);
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
	public static function canActivateTag( string $tag, ?Authority $performer = null ) {
		$changeTagsStore = MediaWikiServices::getInstance()->getChangeTagsStore();
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
			if ( $changeTagsStore->filterViewableTags( [ $tag ], $performer ) === [] ) {
				return Status::newFatal( 'tags-activate-not-found', $tag );
			}
		}

		// defined tags cannot be activated (a defined tag is either extension-
		// defined, in which case the extension chooses whether or not to active it;
		// or user-defined, in which case it is considered active)
		$definedTags = $changeTagsStore->listDefinedTags();
		if ( in_array( $tag, $definedTags ) ) {
			return Status::newFatal( 'tags-activate-not-allowed', $tag );
		}

		// non-existing tags cannot be activated
		if ( !isset( $changeTagsStore->tagUsageStatistics()[$tag] ) ) { // we already know the tag is undefined
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
		$changeTagsStore = MediaWikiServices::getInstance()->getChangeTagsStore();

		$changeTagsStore->defineTag( $tag );

		$logId = $changeTagsStore->logTagManagementAction( 'activate', $tag, $reason, $performer->getUser(),
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
	public static function canDeactivateTag( string $tag, ?Authority $performer = null ) {
		$changeTagsStore = MediaWikiServices::getInstance()->getChangeTagsStore();
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
			if ( $changeTagsStore->filterViewableTags( [ $tag ], $performer ) === [] ) {
				return Status::newFatal( 'tags-deactivate-not-found', $tag );
			}
		}

		// only explicitly-defined tags can be deactivated
		$explicitlyDefinedTags = $changeTagsStore->listExplicitlyDefinedTags();
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
		$changeTagsStore = MediaWikiServices::getInstance()->getChangeTagsStore();

		$changeTagsStore->undefineTag( $tag );

		$logId = $changeTagsStore->logTagManagementAction( 'deactivate', $tag, $reason,
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
		if ( str_contains( $tag, ',' ) || str_contains( $tag, '|' ) || str_contains( $tag, '/' ) ) {
			return Status::newFatal( 'tags-create-invalid-chars' );
		}

		// could the MediaWiki namespace description messages be created?
		$title = Title::makeTitleSafe( NS_MEDIAWIKI, "Tag-$tag-description" );
		if ( $title === null ) {
			return Status::newFatal( 'tags-create-invalid-title-chars' );
		}

		$changeTagsStore = MediaWikiServices::getInstance()->getChangeTagsStore();
		if ( $changeTagsStore->isRestrictedTag( $tag ) ) {
			return Status::newFatal( 'tags-create-reserved-prefix', ChangeTagsStore::PRIVATE_TAG_PREFIX );
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
		$changeTagsStore = $services->getChangeTagsStore();
		if (
			isset( $changeTagsStore->tagUsageStatistics()[$tag] ) ||
			in_array( $tag, $changeTagsStore->listDefinedTags() )
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

		$changeTagsStore = MediaWikiServices::getInstance()->getChangeTagsStore();
		$changeTagsStore->defineTag( $tag );
		$logId = $changeTagsStore->logTagManagementAction( 'create', $tag, $reason,
			$performer->getUser(), null, $logEntryTags );

		return Status::newGood( $logId );
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
	public static function canDeleteTag( string $tag, ?Authority $performer = null, int $flags = 0 ) {
		$user = null;
		$services = MediaWikiServices::getInstance();
		$changeTagsStore = $services->getChangeTagsStore();
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
			if ( $changeTagsStore->filterViewableTags( [ $tag ], $performer ) === [] ) {
				return Status::newFatal( 'tags-delete-not-found', $tag );
			}
			// ChangeTagCanDelete hook still needs a full User object
			$user = $services->getUserFactory()->newFromAuthority( $performer );
		}

		$tagUsage = $changeTagsStore->tagUsageStatistics();
		if (
			!isset( $tagUsage[$tag] ) &&
			!in_array( $tag, $changeTagsStore->listDefinedTags() )
		) {
			return Status::newFatal( 'tags-delete-not-found', $tag );
		}

		if ( $flags !== self::BYPASS_MAX_USAGE_CHECK &&
			isset( $tagUsage[$tag] ) &&
			$tagUsage[$tag] > self::MAX_DELETE_USES
		) {
			return Status::newFatal( 'tags-delete-too-many-uses', $tag, self::MAX_DELETE_USES );
		}

		$softwareDefined = $changeTagsStore->listSoftwareDefinedTags();
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
		$changeTagsStore = MediaWikiServices::getInstance()->getChangeTagsStore();
		// are we allowed to do this?
		$result = self::canDeleteTag( $tag, $performer );
		if ( $ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
			$result->value = null;
			return $result;
		}

		// store the tag usage statistics
		$hitcount = $changeTagsStore->tagUsageStatistics()[$tag] ?? 0;

		// do it!
		$deleteResult = $changeTagsStore->deleteTagEverywhere( $tag );
		if ( !$deleteResult->isOK() ) {
			return $deleteResult;
		}

		// log it
		$changeTagsStore = MediaWikiServices::getInstance()->getChangeTagsStore();
		$logId = $changeTagsStore->logTagManagementAction( 'delete', $tag, $reason, $performer->getUser(),
			$hitcount, $logEntryTags );

		$deleteResult->value = $logId;
		return $deleteResult;
	}

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
	 * @deprecated Since 1.47. Use {@link ChangeTagsFormatter::getChangeTagListSummary()} instead.
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
		wfDeprecated( __METHOD__, '1.47' );
		return MediaWikiServices::getInstance()->getChangeTagsFormatter()->getChangeTagListSummary(
			new SimpleLocalizationContext( $localizer, $lang ),
			( $localizer instanceof IContextSource ? $localizer : RequestContext::getMain() )->getAuthority(),
			$activeOnly,
			$useAllTags
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
	 * @deprecated Since 1.47. Use {@link ChangeTagsFormatter::getChangeTagList()} instead.
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
		wfDeprecated( __METHOD__, '1.47' );
		return MediaWikiServices::getInstance()->getChangeTagsFormatter()->getChangeTagList(
			new SimpleLocalizationContext( $localizer, $lang ),
			( $localizer instanceof IContextSource ? $localizer : RequestContext::getMain() )->getAuthority(),
			$activeOnly,
			$useAllTags,
			$labelsOnly
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
	 * @param Authority $performer
	 * @return bool
	 */
	public static function showTagEditingUI( Authority $performer ) {
		$changeTagsStore = MediaWikiServices::getInstance()->getChangeTagsStore();
		return $performer->isAllowed( 'changetags' ) && (bool)$changeTagsStore->listExplicitlyDefinedTags();
	}
}

/** @deprecated class alias since 1.44 */
class_alias( ChangeTags::class, 'ChangeTags' );
