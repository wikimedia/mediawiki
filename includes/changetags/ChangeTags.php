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
	 * @param string $tag Tag
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
		$result = ChangeTagsUpdater::updateTags( $tags, null, $rc_id, $rev_id, $log_id, $params, $rc );
		return (bool)$result[0];
	}

	/**
	 * Applies all tags-related changes to a query.
	 * Handles selecting tags, and filtering.
	 * Needs $tables to be set up properly, so we can figure out which join conditions to use.
	 *
	 * @param string|array $tables Table names, see Database::select
	 * @param string|array $fields Fields used in query, see Database::select
	 * @param string|array $conds Conditions used in query, see Database::select
	 * @param array $join_conds Join conditions, see Database::select
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

		$fields['ts_tags'] = wfGetDB( DB_REPLICA )->buildGroupConcatField(
			',', 'change_tag', 'ct_tag', $join_cond
		);

		if ( $wgUseTagFilter && $filter_tag ) {
			// Somebody wants to filter on a tag.
			// Add an INNER JOIN on change_tag

			$tables[] = 'change_tag';
			$join_conds['change_tag'] = [ 'INNER JOIN', $join_cond ];
			$conds['ct_tag'] = $filter_tag;
		}
	}

	/**
	 * Build a text box to select a change tag
	 *
	 * @param string $selected Tag to select by default
	 * @param bool $ooui Use an OOUI TextInputWidget as selector instead of a non-OOUI input field
	 *        You need to call OutputPage::enableOOUI() yourself.
	 * @return array an array of (label, selector)
	 */
	public static function buildTagFilterSelector( $selected = '', $ooui = false ) {
		$config = RequestContext::getMain()->getConfig();
		// check config
		if ( !$config->get( 'UseTagFilter' ) ) {
			return [];
		} else {
			// check if some tags are defined
			$changeTagsContext = new ChangeTagsContext( $config );
			$tagList = $changeTagsContext->getDefinedTags();
			if ( !count( $tagList ) ) {
				return [];
			}
		}

		$data = [
			Html::rawElement(
				'label',
				[ 'for' => 'tagfilter' ],
				wfMessage( 'tag-filter' )->parse()
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
	 * @see ChangeTagsContext::canAddTagsAccompanyingChange
	 * @since 1.25
	 * @todo deprecate
	 */
	public static function canAddTagsAccompanyingChange( array $tags,
		User $user = null ) {
		$changeTagsContext = new ChangeTagsContext( RequestContext::getMain()->getConfig() );
		$lang = RequestContext::getMain()->getLanguage();
		$manager = new ChangeTagsUpdater( $changeTagsContext, $user, $lang );
		return $manager->canAddTagsAccompanyingChange( $tags );
	}

	/**
	 * @deprecated since 1.28, use ChangeTagsContext class instead
	 * @since 1.28
	 */
	public static function listSoftwareActivatedTags() {
		wfDeprecated( __METHOD__, '1.28' );
		$changeTagsContext = new ChangeTagsContext( RequestContext::getMain()->getConfig() );
		$tags = [];
		$registered = $changeTagsContext->getSoftwareTags();
		foreach ( $registered as $tag => &$val ) {
			$changeTag = new ChangeTag( $tag, $changeTagsContext );
			if ( $changeTag->isActive() ) {
				$tags[] = $tag;
			}
		}
		return $tags;
	}

	/**
	 * @deprecated since 1.28, use ChangeTagsContext class instead
	 * @since 1.25
	 */
	public static function listExtensionActivatedTags() {
		return self::listSoftwareActivatedTags();
	}

	/**
	 * @deprecated since 1.28, use ChangeTagsContext class instead
	 */
	public static function listDefinedTags() {
		wfDeprecated( __METHOD__, '1.28' );
		$changeTagsContext = new ChangeTagsContext( RequestContext::getMain()->getConfig() );
		return array_keys( $changeTagsContext->getDefinedTags() );
	}

	/**
	 * @deprecated since 1.28, use ChangeTagsContext class instead
	 * @since 1.28
	 */
	public static function listSoftwareDefinedTags() {
		wfDeprecated( __METHOD__, '1.28' );
		return array_merge( self::$coreTags, array_keys( $changeTagsContext->getSoftwareTags() ) );
	}

	/**
	 * @deprecated since 1.28, use ChangeTagsContext class instead
	 * @since 1.25
	 */
	public static function listExplicitlyDefinedTags() {
		wfDeprecated( __METHOD__, '1.28' );
		$changeTagsContext = new ChangeTagsContext( RequestContext::getMain()->getConfig() );
		return array_keys( $changeTagsContext->getUserTags() );
	}

	/**
	 * @deprecated since 1.28, use ChangeTagsContext class instead
	 * @since 1.25
	 */
	public static function listExtensionDefinedTags() {
		wfDeprecated( __METHOD__, '1.28' );
		$changeTagsContext = new ChangeTagsContext( RequestContext::getMain()->getConfig() );
		return array_keys( $changeTagsContext->getSoftwareTags() );
	}

	/**
	 * @deprecated since 1.28, use ChangeTagsContext::purgeTagCacheAll() instead
	 * @since 1.25
	 */
	public static function purgeRegisteredTagsCache() {
		wfDeprecated( __METHOD__, '1.28' );
		ChangeTagsContext::purgeTagCacheAll();
	}

	/**
	 * @deprecated since 1.28, use ChangeTagsContext::purgeTagUsageCache() instead
	 * @since 1.25
	 */
	public static function purgeTagUsageCache() {
		wfDeprecated( __METHOD__, '1.28' );
		ChangeTagsContext::purgeTagUsageCache();
	}

	/**
	 * @deprecated since 1.28, use ChangeTagsContext::getStats() instead
	 */
	public static function tagUsageStatistics() {
		wfDeprecated( __METHOD__, '1.28' );
		$changeTagsContext = new ChangeTagsContext( RequestContext::getMain()->getConfig() );
		return $changeTagsContext->getStats();
	}
}
