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
 */

class ChangeTags {
	/**
	 * Can't delete tags with more than this many uses. Similar in intent to
	 * the bigdelete user right
	 * @todo Use the job queue for tag deletion to avoid this restriction
	 */
	const MAX_DELETE_USES = 5000;

	/**
	 * Function making a change tag object from a tag name based on some context
	 * Make sure you provide all the relevant data for the checks you need
	 *
	 * @param string $tag tag's name
	 * @param array $tagStats tag usage statistics mapping each tag to its hitcount
	 * @param array $storedTags tags stored in the change_tag table of the database
	 * @param array $registeredTags tags defined by extensions
	 * @return change tag object
	 */
	public static function getChangeTagObject( $tag, $tagStats, $storedTags,
		$registeredTags ) {

		$ctObj = (object) array( 'name' => $tag );

		$ctObj->isApplied = isset( $tagStats[$tag] );
		$ctObj->isStored = isset( $storedTags[$tag] );
		$ctObj->extensionDefined = isset( $registeredTags[$tag] );

		$ctObj->exists = $ctObj->isApplied || $ctObj->extensionDefined ||
			$ctObj->isStored;

		// shortcut
		if ( !$ctObj->exists ) {
			return $ctObj;
		}

		// a change tag is user defined if it is stored in the valid_tag table and
		// not defined by an extension
		$ctObj->userDefined = $ctObj->isStored && !$ctObj->extensionDefined;

		// some info based on tag stats
		$ctObj->hitcount = $ctObj->isApplied ? $tagStats[$tag] : 0;
		$ctObj->isBig = ( $ctObj->hitcount > self::MAX_DELETE_USES );

		if ( $ctObj->extensionDefined ) {
			// is the tag in active use by the extension ?
			$ctObj->isActive = isset( $registeredTags[$tag]['active'] ) &&
				$registeredTags[$tag]['active'];

			// user-friendly name of the extension
			$ctObj->extensionName = isset( $tagParams['name'] ) ?
				(string) $tagParams['name'] : '';
			// params to pass in the extension-specific description
			$ctObj->extensionDescParams = isset( $tagParams['descParams'] ) ?
				(array) $tagParams['descParams'] : array();
			// should admins be allowed to delete applied instances of the tag ?
			// this does not undefine the tag, unless the extension does it by means of
			// the ChangeTagAfterDelete hook
			$ctObj->extensionCanDelete = isset( $registeredTags[$tag]['canDelete'] ) &&
				$registeredTags[$tag]['canDelete'];

			$ctObj->canActivate = false;
			// in case they were previously user defined
			$ctObj->canDeactivate = $ctObj->isStored;
			$ctObj->canDelete = !$ctObj->isBig && $ctObj->extensionCanDelete;

		} elseif ( $ctObj->userDefined ) {
			// is the tag allowed to be applied by users and bots ?
			// yes, as we assume these tags are active
			$ctObj->isActive = true;

			$ctObj->canActivate = false;
			// no need to deactivate never applied user defined tags,
			// they can just be deleted
			$ctObj->canDeactivate = $ctObj->isApplied;
			$ctObj->canDelete = !$ctObj->isBig;

		} else {
			// for undefined tags
			$ctObj->isActive = false;

			$ctObj->canActivate = true;
			$ctObj->canDeactivate = false;
			$ctObj->canDelete = !$ctObj->isBig;
		}

		return $ctObj;
	}

	/**
	 * Shortcut for a change tag object where context is completely rebuilt
	 * and provided in full
	 *
	 * @param string $tag tag's name
	 * @return change tag object
	 */
	public static function rebuildChangeTagObject ( $tag ) {

		// some of the caches might be outdated due to extensions not purging them
		self::purgeTagUsageCache();
		self::purgeStoredTagsCache();
		self::purgeRegisteredTagsCache();

		return getChangeTagObject(
			$tag,
			self::getTagUsageStatistics(),
			self::getStoredTags(),
			self::getRegisteredTags()
		);
	}

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

		$tags = explode( ',', $tags );

		// XXX(Ori Livneh, 2014-11-08): remove once bug 73181 is resolved.
		$tags = array_diff( $tags, array( 'HHVM', '' ) );

		if ( !$tags ) {
			return array( '', array() );
		}

		$classes = array();

		$displayTags = array();
		foreach ( $tags as $tag ) {
			$displayTags[] = Xml::tags(
				'span',
				array( 'class' => 'mw-tag-marker ' .
								Sanitizer::escapeClass( "mw-tag-marker-$tag" ) ),
				self::tagDescription( $tag )
			);
			$classes[] = Sanitizer::escapeClass( "mw-tag-$tag" );
		}
		$markers = wfMessage( 'tag-list-wrapper' )
			->numParams( count( $displayTags ) )
			->rawParams( $wgLang->commaList( $displayTags ) )
			->parse();
		$markers = Xml::tags( 'span', array( 'class' => 'mw-tag-markers' ), $markers );

		return array( $markers, $classes );
	}

	/**
	 * Get a short description for a tag
	 *
	 * @param string $tag Tag
	 *
	 * @return string Short description of the tag from "mediawiki:tag-$tag" if this message exists,
	 *   html-escaped version of $tag otherwise
	 */
	public static function tagDescription( $tag ) {
		$msg = wfMessage( "tag-$tag" );
		return $msg->exists() ? $msg->parse() : htmlspecialchars( $tag );
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
	 *
	 * @exception MWException When $rc_id, $rev_id and $log_id are all null
	 */
	public static function addTags( $tags, $rc_id = null, $rev_id = null,
		$log_id = null, $params = null ) {
		global $wgMemc;
		if ( !is_array( $tags ) ) {
			$tags = array( $tags );
		}

		$tags = array_filter( $tags ); // Make sure we're submitting all tags...

		if ( !$rc_id && !$rev_id && !$log_id ) {
			throw new MWException( 'At least one of: RCID, revision ID, and log ID MUST be ' .
				'specified when adding a tag to a change!' );
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

		$tsConds = array_filter( array(
			'ts_rc_id' => $rc_id,
			'ts_rev_id' => $rev_id,
			'ts_log_id' => $log_id )
		);

		// Update the summary row.
		// $prevTags can be out of date on slaves, especially when addTags is called consecutively,
		// causing loss of tags added recently in tag_summary table.
		$prevTags = $dbw->selectField( 'tag_summary', 'ts_tags', $tsConds, __METHOD__ );
		$prevTags = $prevTags ? $prevTags : '';
		$prevTags = array_filter( explode( ',', $prevTags ) );
		$newTags = array_unique( array_merge( $prevTags, $tags ) );
		sort( $prevTags );
		sort( $newTags );

		if ( $prevTags == $newTags ) {
			// No change.
			return false;
		}

		$dbw->replace(
			'tag_summary',
			array( 'ts_rev_id', 'ts_rc_id', 'ts_log_id' ),
			array_filter( array_merge( $tsConds, array( 'ts_tags' => implode( ',', $newTags ) ) ) ),
			__METHOD__
		);

		// Insert the tags rows.
		$tagsRows = array();
		foreach ( $tags as $tag ) { // Filter so we don't insert NULLs as zero accidentally.
			$tagsRows[] = array_filter(
				array(
					'ct_tag' => $tag,
					'ct_rc_id' => $rc_id,
					'ct_log_id' => $log_id,
					'ct_rev_id' => $rev_id,
					'ct_params' => $params
				)
			);
			// Increment cache
			$wgMemc->incr( wfMemcKey( 'ChangeTags', 'tag-hitcount', "$tag" ) );
		}

		$dbw->insert( 'change_tag', $tagsRows, __METHOD__, array( 'IGNORE' ) );

		return true;
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
	 * @return string|array
	 *        - if $fullForm is false: Array with
	 *        - if $fullForm is true: String, html fragment
	 */
	public static function buildTagFilterSelector( $selected = '',
		$fullForm = false, Title $title = null, $tagList = null ) {
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
	 * Defines a tag in the valid_tag table, without checking that the tag name
	 * is valid.
	 * Extensions should NOT use this function; they can use the ChangeTagsRegister
	 * hook instead.
	 *
	 * @param string $tag Tag to create
	 * @since 1.25
	 */
	public static function defineTag( $tag ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->replace( 'valid_tag',
			array( 'vt_tag' ),
			array( 'vt_tag' => $tag ),
			__METHOD__ );
		self::purgeStoredTagsCache();
	}

	/**
	 * Removes a tag from the valid_tag table. The tag may remain in use by
	 * extensions, and may still show up as 'defined' if an extension is setting
	 * it from the ChangeTagsRegister hook.
	 *
	 * @param string $tag Tag to remove
	 * @since 1.25
	 */
	public static function undefineTag( $tag ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'valid_tag',
			array( 'vt_tag' => $tag ),
			__METHOD__ );
		self::purgeStoredTagsCache();
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
	protected static function logTagAction( $action, $tag, $reason, User $user,
		$tagCount = null ) {

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
	 * Is it OK to allow the user to activate this tag?
	 *
	 * @param string|changeTag object $tag Tag name or tag object for the tag
	 * that you are interested in activating
	 * @param User|null $user User whose permission you wish to check, or null if
	 * you don't care (e.g. maintenance scripts)
	 * @return Status
	 * @since 1.25
	 */
	public static function canActivateTag( $tag, User $user = null ) {

		// permission check
		if ( !is_null( $user ) && !$user->isAllowed( 'managechangetags' ) ) {
			return Status::newFatal( 'tags-manage-no-permission' );
		}
		// rebuild change tag object if not already provided
		if ( !is_object( $tag ) ) {
			$tag = self::rebuildChangeTagObject( $tag );
		}
		// non-existing tags cannot be managed
		if ( !$tag->exists ) {
			return Status::newFatal( 'tags-manage-not-found', $tag->name );
		}

		// check specific to activate action
		if ( !$tag->canActivate ) {
			return Status::newFatal( 'tags-activate-not-allowed', $tag->name );
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
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 * @since 1.25
	 */
	public static function activateTagWithChecks( $tag, $reason, User $user,
		$ignoreWarnings = false ) {

		// are we allowed to do this?
		$result = self::canActivateTag( $tag, $user );
		if ( $ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
			$result->value = null;
			return $result;
		}

		// do it!
		self::defineTag( $tag );

		// log it
		$logId = self::logTagAction( 'activate', $tag, $reason, $user );
		return Status::newGood( $logId );
	}

	/**
	 * Is it OK to allow the user to deactivate this tag?
	 *
	 * @param string|changeTag object $changeTag Tag name or tag object for the tag
	 * that you are interested in deactivating
	 * @param User|null $user User whose permission you wish to check, or null if
	 * you don't care (e.g. maintenance scripts)
	 * @return Status
	 * @since 1.25
	 */
	public static function canDeactivateTag( $tag, User $user = null ) {

		// permission check
		if ( !is_null( $user ) && !$user->isAllowed( 'managechangetags' ) ) {
			return Status::newFatal( 'tags-manage-no-permission' );
		}
		// rebuild change tag object if not already provided
		if ( !is_object( $tag ) ) {
			$tag = self::rebuildChangeTagObject( $tag );
		}
		// non-existing tags cannot be managed
		if ( !$tag->exists ) {
			return Status::newFatal( 'tags-manage-not-found', $tag->name );
		}

		// check specific to deactivate action
		if ( !$tag->canDeactivate ) {
			return Status::newFatal( 'tags-deactivate-not-allowed', $tag->name );
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
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 * @since 1.25
	 */
	public static function deactivateTagWithChecks( $tag, $reason, User $user,
		$ignoreWarnings = false ) {

		// are we allowed to do this?
		$result = self::canDeactivateTag( $tag, $user );
		if ( $ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
			$result->value = null;
			return $result;
		}

		// do it!
		self::undefineTag( $tag );

		// log it
		$logId = self::logTagAction( 'deactivate', $tag, $reason, $user );
		return Status::newGood( $logId );
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
		if ( !is_null( $user ) && !$user->isAllowed( 'managechangetags' ) ) {
			return Status::newFatal( 'tags-manage-no-permission' );
		}

		// no empty tags
		if ( $tag === '' ) {
			return Status::newFatal( 'tags-create-no-name' );
		}

		// tags cannot contain commas (used as a delimiter in tag_summary table) or
		// slashes (would break tag description messages in MediaWiki namespace)
		if ( strpos( $tag, ',' ) !== false || strpos( $tag, '/' ) !== false ) {
			return Status::newFatal( 'tags-create-invalid-chars' );
		}

		// could the MediaWiki namespace description messages be created?
		$title = Title::makeTitleSafe( NS_MEDIAWIKI, "Tag-$tag-description" );
		if ( is_null( $title ) ) {
			return Status::newFatal( 'tags-create-invalid-title-chars' );
		}

		// rebuild change tag object
		$tag = self::rebuildChangeTagObject( $tag );

		// does the tag already exist?
		if ( $tag->exists ) {
			return Status::newFatal( 'tags-create-already-exists', $tag->name );
		}

		// check with hooks
		$canCreateResult = Status::newGood();
		Hooks::run( 'ChangeTagCanCreate', array( $tag->name, $user, &$canCreateResult ) );
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
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 * @since 1.25
	 */
	public static function createTagWithChecks( $tag, $reason, User $user,
		$ignoreWarnings = false ) {

		// are we allowed to do this?
		$result = self::canCreateTag( $tag, $user );
		if ( $ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
			$result->value = null;
			return $result;
		}

		// do it!
		self::defineTag( $tag );
		self::purgeTagUsageCache( $tag );

		// log it
		$logId = self::logTagAction( 'create', $tag, $reason, $user );
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
		$dbw->begin( __METHOD__ );

		// delete from valid_tag
		// we don't call self::undefineTag since the purging of caches should occur
		// at the same time after all operations have been performed
		$dbw->delete( 'valid_tag',
			array( 'vt_tag' => $tag ),
			__METHOD__ );

		// find out which revisions use this tag, so we can delete from tag_summary
		$result = $dbw->select( 'change_tag',
			array( 'ct_rc_id', 'ct_log_id', 'ct_rev_id', 'ct_tag' ),
			array( 'ct_tag' => $tag ),
			__METHOD__ );
		foreach ( $result as $row ) {
			if ( $row->ct_rev_id ) {
				$field = 'ts_rev_id';
				$fieldValue = $row->ct_rev_id;
			} elseif ( $row->ct_log_id ) {
				$field = 'ts_log_id';
				$fieldValue = $row->ct_log_id;
			} elseif ( $row->ct_rc_id ) {
				$field = 'ts_rc_id';
				$fieldValue = $row->ct_rc_id;
			} else {
				// don't know what's up; just skip it
				continue;
			}

			// remove the tag from the relevant row of tag_summary
			$tsResult = $dbw->selectField( 'tag_summary',
				'ts_tags',
				array( $field => $fieldValue ),
				__METHOD__ );
			$tsValues = explode( ',', $tsResult );
			$tsValues = array_values( array_diff( $tsValues, array( $tag ) ) );
			if ( !$tsValues ) {
				// no tags left, so delete the row altogether
				$dbw->delete( 'tag_summary',
					array( $field => $fieldValue ),
					__METHOD__ );
			} else {
				$dbw->update( 'tag_summary',
					array( 'ts_tags' => implode( ',', $tsValues ) ),
					array( $field => $fieldValue ),
					__METHOD__ );
			}
		}

		// delete from change_tag
		$dbw->delete( 'change_tag', array( 'ct_tag' => $tag ), __METHOD__ );

		$dbw->commit( __METHOD__ );

		// give extensions a chance
		$status = Status::newGood();
		Hooks::run( 'ChangeTagAfterDelete', array( $tag, &$status ) );
		// let's not allow error results, as the actual tag deletion succeeded
		if ( !$status->isOK() ) {
			wfDebug( 'ChangeTagAfterDelete error condition downgraded to warning' );
			$status->ok = true;
		}

		// Clearing tag caches
		self::purgeTagUsageCache( $tag );
		self::purgeStoredTagsCache();
		self::purgeRegisteredTagsCache();

		return $status;
	}

	/**
	 * Is it OK to allow the user to delete this tag?
	 *
	 * @param string|changeTag object $tag Tag that you are interested in deleting
	 * @param User|null $user User whose permission you wish to check, or null if
	 * you don't care (e.g. maintenance scripts)
	 * @return Status
	 * @since 1.25
	 */
	public static function canDeleteTag( $tag, User $user = null ) {

		// permission check
		if ( !is_null( $user ) && !$user->isAllowed( 'managechangetags' ) ) {
			return Status::newFatal( 'tags-manage-no-permission' );
		}
		// rebuild change tag object if not already provided
		if ( !is_object( $tag ) ) {
			$tag = self::rebuildChangeTagObject( $tag );
		}
		// non-existing tags cannot be managed
		if ( !$tag->exists ) {
			return Status::newFatal( 'tags-manage-not-found', $tag->name );
		}

		// tags with too many uses cannot be deleted
		if ( $tag->isBig ) {
			return Status::newFatal( 'tags-delete-too-many-uses', $tag->name, self::MAX_DELETE_USES );
		}

		if ( $tag->extensionDefined && !$tag->extensionDeletable ) {
			// extension-defined tags can't be deleted unless the extension
			// specifically allows it
			return Status::newFatal( 'tags-delete-not-allowed' );
		} else {
			// user-defined tags, extension defined tags when allowed, or undefined tags can be deleted
			return Status::newGood();
		}
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
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 * @since 1.25
	 */
	public static function deleteTagWithChecks( $tag, $reason, User $user,
		$ignoreWarnings = false ) {

		// make a change tag object here since we'll need hitcount
		$changeTag = self::rebuildChangeTagObject( $tag );

		// are we allowed to do this?
		$result = self::canDeleteTag( $changeTag, $user );
		if ( $ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
			$result->value = null;
			return $result;
		}

		// do it!
		$deleteResult = self::deleteTagEverywhere( $tag );
		if ( !$deleteResult->isOK() ) {
			return $deleteResult;
		}

		// log it
		$logId = self::logTagAction( 'delete', $tag, $reason, $user, $changeTag->hitcount );
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
	 * @since 1.25
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
	 * The tags are keys and the values are params, such as active status.
	 *
	 * @return string[] Array of strings: tags
	 * @since 1.25
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
			return array_keys( $appliedTags );
		}

		// fetching the list of defined tags
		if ( $definedTags == null ) {
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
	 * @param string $tag: (optional) tag to clear the hitcount cache of
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

	/**
	 * Invalidates all tags-related caches.
	 * Provided for backward compatibility.
	 * Should be deprecated, used anywhere ?
	 *
	 * @since 1.25
	 */
	public static function purgeTagCacheAll() {
		global $wgMemc;
		self::purgeStoredTagsCache();
		self::purgeRegisteredTagsCache();
		self::purgeTagUsageCache();
	}
}
