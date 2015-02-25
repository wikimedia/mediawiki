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
		$log_id = null, $params = null
	) {
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
		}

		$dbw->insert( 'change_tag', $tagsRows, __METHOD__, array( 'IGNORE' ) );

		self::purgeTagUsageCache();
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
	 * @return string|array
	 *        - if $fullForm is false: Array with
	 *        - if $fullForm is true: String, html fragment
	 */
	public static function buildTagFilterSelector( $selected = '',
		$fullForm = false, Title $title = null
	) {
		global $wgUseTagFilter;

		if ( !$wgUseTagFilter || !count( self::listDefinedTags() ) ) {
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
	 * Extensions should NOT use this function; they can use the ListDefinedTags
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
		$dbw->delete( 'valid_tag', array( 'vt_tag' => $tag ), __METHOD__ );

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
	 * @param string $tag Tag that you are interested in activating
	 * @param User|null $user User whose permission you wish to check, or null if
	 * you don't care (e.g. maintenance scripts)
	 * @return Status
	 * @since 1.25
	 */
	public static function canActivateTag( $tag, User $user = null ) {
		if ( !is_null( $user ) && !$user->isAllowed( 'managechangetags' ) ) {
			return Status::newFatal( 'tags-manage-no-permission' );
		}

		// non-existing tags cannot be activated
		$tagUsage = self::tagUsageStatistics();
		if ( !isset( $tagUsage[$tag] ) ) {
			return Status::newFatal( 'tags-activate-not-found', $tag );
		}

		// defined tags cannot be activated (a defined tag is either extension-
		// defined, in which case the extension chooses whether or not to active it;
		// or user-defined, in which case it is considered active)
		$validTags = self::getValidTags();
		$extensionDefinedTags = self::listExtensionDefinedTags();
		if ( isset( $validTags[$tag] ) || in_array( $tag, $extensionDefinedTags ) ) {
			// this in_array should go once we have a hook using tags as keys
			return Status::newFatal( 'tags-activate-not-allowed', $tag );
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
	 * @param string $tag Tag that you are interested in deactivating
	 * @param User|null $user User whose permission you wish to check, or null if
	 * you don't care (e.g. maintenance scripts)
	 * @return Status
	 * @since 1.25
	 */
	public static function canDeactivateTag( $tag, User $user = null ) {
		if ( !is_null( $user ) && !$user->isAllowed( 'managechangetags' ) ) {
			return Status::newFatal( 'tags-manage-no-permission' );
		}

		// only tags stored in the valid_tag table can be deactivated
		$validTags = self::getValidTags();
		if ( !isset( $validTags[$tag] ) ) {
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
		if ( strpos( $tag, ',' ) !== false || strpos( $tag, '/' ) !== false ||
			// adding a reserved core- namespace for use by tags defined in core
			strpos( $tag, 'core-' ) !== false  ) {
			return Status::newFatal( 'tags-create-invalid-chars' );
		}

		// could the MediaWiki namespace description messages be created?
		$title = Title::makeTitleSafe( NS_MEDIAWIKI, "Tag-$tag-description" );
		if ( is_null( $title ) ) {
			return Status::newFatal( 'tags-create-invalid-title-chars' );
		}

		// does the tag already exist?
		$tagUsage = self::tagUsageStatistics();
		if ( isset( $tagUsage[$tag] ) ) {
			return Status::newFatal( 'tags-create-already-exists', $tag );
		}

		// check with hooks
		$canCreateResult = Status::newGood();
		Hooks::run( 'ChangeTagCanCreate', array( $tag, $user, &$canCreateResult ) );
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
		self::undefineTag( $tag );

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

		if ( !is_null( $user ) && !$user->isAllowed( 'managechangetags' ) ) {
			return Status::newFatal( 'tags-manage-no-permission' );
		}

		if ( !isset( $tagUsage[$tag] ) ) {
			return Status::newFatal( 'tags-delete-not-found', $tag );
		}

		if ( $tagUsage[$tag] > self::MAX_DELETE_USES ) {
			return Status::newFatal( 'tags-delete-too-many-uses', $tag, self::MAX_DELETE_USES );
		}

		$extensionDefined = self::listExtensionDefinedTags();
		if ( in_array( $tag, $extensionDefined ) ) { // this in_array should go
			// extension-defined tags can't be deleted unless the extension
			// specifically allows it
			$status = Status::newFatal( 'tags-delete-not-allowed' );
		} else {
			// user-defined tags are deletable unless otherwise specified
			$status = Status::newGood();
		}

		Hooks::run( 'ChangeTagCanDelete', array( $tag, $user, &$status ) );
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
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 * @since 1.25
	 */
	public static function deleteTagWithChecks( $tag, $reason, User $user,
		$ignoreWarnings = false ) {

		// are we allowed to do this?
		$result = self::canDeleteTag( $tag, $user );
		if ( $ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
			$result->value = null;
			return $result;
		}

		// store the tag usage statistics
		$tagUsage = self::tagUsageStatistics();

		// do it!
		$deleteResult = self::deleteTagEverywhere( $tag );
		if ( !$deleteResult->isOK() ) {
			return $deleteResult;
		}

		// log it
		$logId = self::logTagAction( 'delete', $tag, $reason, $user, $tagUsage[$tag] );
		$deleteResult->value = $logId;
		return $deleteResult;
	}

	/**
	 * Lists those tags which extensions report as being "active".
	 *
	 * @return array
	 * @since 1.25
	 */
	public static function listExtensionActivatedTags() {
		// Caching...
		global $wgMemc;
		$key = wfMemcKey( 'extension-activated-tags' );
		$tags = $wgMemc->get( $key );
		if ( $tags ) {
			return $tags;
		}

		// ask extensions which tags they consider active
		$extensionActive = array();
		Hooks::run( 'ChangeTagsListActive', array( &$extensionActive ) );

		// Short-term caching.
		$wgMemc->set( $key, $extensionActive, 300 );
		return $extensionActive;
	}

	/**
	 * Basically lists defined tags which count even if they aren't applied to anything.
	 * It returns a union of the keys of getValidTags() and getCoreTags() and the values
	 * of listExtensionDefinedTags().
	 *
	 * @return string[] Array of strings: tags
	 */
	public static function listDefinedTags() {
		$tags1 = array_keys( self::getValidTags() );
		$tags2 = array_keys( self::getCoreTags() );
		$tags3 = self::listExtensionDefinedTags();
		// hook with keys needed, to get rid of array_unique overhead, array_values could go too
		return array_values( array_unique( array_merge( $tags1, $tags2, $tags3 ) ) );
	}

	/**
	 * Gets tags explicitly defined in the `valid_tag` table of the database.
	 * Tags in table 'change_tag' which are not in table 'valid_tag' are not
	 * included.
	 * The keys are the tag names and the values are empty arrays for now. In
	 * the future, if we add params to the valid_tag table, the values will also
	 * contain those params. We may add others for compatibility with the single
	 * hook defining extensions of T91535, such as 'active'. This way, we can
	 * merge the arrays and the sub-key check for 'active' will still be available.
	 *
	 * Tries memcached first.
	 *
	 * @return string[] Array of strings: tags
	 * @since 1.25
	 */
	public static function getValidTags() {
		// Caching...
		global $wgMemc;
		$key = wfMemcKey( 'valid-tags' );
		$tags = $wgMemc->get( $key );
		if ( $tags ) {
			return $tags;
		}

		$validTags = array();

		// Some DB stuff
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'valid_tag', 'vt_tag', array(), __METHOD__ );
		foreach ( $res as $row ) {
			$validTags[$row->vt_tag] = array();
		}

		// Short-term caching.
		$wgMemc->set( $key, $validTags, 300 );
		// returning as is, using keys means we don't even have to worry
		// about duplicates or null entries
		return $validTags;
	}

	/**
	 * Lists tags from the valid_tag table as values.
	 * Provided for backward compatibility.
	 * This shouldn't be used for checking if a tag is valid, use directly
	 * isset applied to getValidTags.
	 *
	 * @return string[] Array of strings: tags
	 * @since 1.25
	 * @should be deprecated
	 */
	public static function listExplicitlyDefinedTags() {
		return array_keys( self::getValidTags() );
	}

	/**
	 * Gets all core tags as keys.
	 * Values are null.
	 * Enables efficient isset check.
	 *
	 * @return array
	 * @since 1.25
	 */
	public static function getCoreTags() {
		// Defining core tags keys
		$coreTagsKeys = array(
			'core-move-crossnamespace',
			'core-move-rename',
			'core-redirect-new',
			'core-redirect-changed',
			'core-redirect-removed',
			'core-redirect-self',
			'core-redirect-nonexistent',
			'core-edit-blank'
		);

		// Returning
		return array_fill_keys( $coreTagsKeys, null );
	}

	/**
	 * Lists tags defined by extensions using the ListDefinedTags hook.
	 * Extensions need only define those tags they deem to be in active use.
	 *
	 * Tries memcached first.
	 *
	 * @return string[] Array of strings: tags
	 * @since 1.25
	 */
	public static function listExtensionDefinedTags() {
		// Caching...
		global $wgMemc;
		$key = wfMemcKey( 'extension-defined-tags' );
		$tags = $wgMemc->get( $key );
		if ( $tags ) {
			return $tags;
		}

		$extensionTags = array();
		Hooks::run( 'ListDefinedTags', array( &$extensionTags ) );
		// we badly need a single hook for tags that uses tag names as keys and params as values
		// both for performance reasons and passing params (active, etc) in one go - T91535
		$extensionTags = array_filter( array_unique( $extensionTags ) );
		// and so we can get rid of array_filter and array_unique

		// Short-term caching.
		$wgMemc->set( $key, $extensionTags, 300 );
		return $extensionTags;
	}

	/**
	 * Invalidates the short-term cache of defined tags used by the
	 * list*DefinedTags functions, as well as the tag statistics cache.
	 * @since 1.25
	 */
	public static function purgeTagCacheAll() {
		global $wgMemc;
		$wgMemc->delete( wfMemcKey( 'valid-tags' ) );
		$wgMemc->delete( wfMemcKey( 'extension-defined-tags' ) );
		$wgMemc->delete( wfMemcKey( 'extension-activated-tags' ) );
		self::purgeTagUsageCache();
	}

	/**
	 * Invalidates the tag statistics cache only.
	 * @since 1.25
	 */
	public static function purgeTagUsageCache() {
		global $wgMemc;
		$wgMemc->delete( wfMemcKey( 'change-tag-statistics' ) );
	}

	/**
	 * Returns a map of any tags used on the wiki to number of edits
	 * tagged with them, ordered descending by the hitcount.
	 *
	 * Keeps a short-term cache in memory, so calling this multiple times in the
	 * same request should be fine.
	 *
	 * @return array Array of string => int
	 */
	public static function tagUsageStatistics() {
		// Caching...
		global $wgMemc;
		$key = wfMemcKey( 'change-tag-statistics' );
		$stats = $wgMemc->get( $key );
		if ( $stats ) {
			return $stats;
		}

		$out = array();

		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select(
			'change_tag',
			array( 'ct_tag', 'hitcount' => 'count(*)' ),
			array(),
			__METHOD__,
			array( 'GROUP BY' => 'ct_tag', 'ORDER BY' => 'hitcount DESC' )
		);

		foreach ( $res as $row ) {
			$out[$row->ct_tag] = $row->hitcount;
		}
		foreach ( self::listDefinedTags() as $tag ) {
			if ( !isset( $out[$tag] ) ) {
				$out[$tag] = 0;
			}
		}

		// Cache for a very short time
		$wgMemc->set( $key, $out, 300 );
		return $out;
	}
}
