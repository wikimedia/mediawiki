<?php
/**
 * Helper functions for feeds.
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
 * @ingroup Feed
 */

/**
 * Helper functions for feeds
 *
 * @ingroup Feed
 */
class FeedUtils {

	/**
	 * Check whether feed's cache should be cleared; for changes feeds
	 * If the feed should be purged; $timekey and $key will be removed from
	 * $messageMemc
	 *
	 * @param string $timekey cache key of the timestamp of the last item
	 * @param string $key cache key of feed's content
	 */
	public static function checkPurge( $timekey, $key ) {
		global $wgRequest, $wgUser, $messageMemc;
		$purge = $wgRequest->getVal( 'action' ) === 'purge';
		if ( $purge && $wgUser->isAllowed( 'purge' ) ) {
			$messageMemc->delete( $timekey );
			$messageMemc->delete( $key );
		}
	}

	/**
	 * Check whether feeds can be used and that $type is a valid feed type
	 *
	 * @param string $type feed type, as requested by the user
	 * @return Boolean
	 */
	public static function checkFeedOutput( $type ) {
		global $wgOut, $wgFeed, $wgFeedClasses;

		if ( !$wgFeed ) {
			$wgOut->addWikiMsg( 'feed-unavailable' );
			return false;
		}

		if ( !isset( $wgFeedClasses[$type] ) ) {
			$wgOut->addWikiMsg( 'feed-invalid' );
			return false;
		}

		return true;
	}

	/**
	 * Format a diff for the newsfeed
	 *
	 * @param $row Object: row from the recentchanges table
	 * @return String
	 */
	public static function formatDiff( $row ) {
		$titleObj = Title::makeTitle( $row->rc_namespace, $row->rc_title );
		$timestamp = wfTimestamp( TS_MW, $row->rc_timestamp );
		$actiontext = '';
		if ( $row->rc_type == RC_LOG ) {
			$rcRow = (array)$row; // newFromRow() only accepts arrays for RC rows
			$actiontext = LogFormatter::newFromRow( $rcRow )->getActionText();
		}
		return self::formatDiffRow( $titleObj,
			$row->rc_last_oldid, $row->rc_this_oldid,
			$timestamp,
			$row->rc_deleted & Revision::DELETED_COMMENT
				? wfMessage( 'rev-deleted-comment' )->escaped()
				: $row->rc_comment,
			$actiontext
		);
	}

	/**
	 * Really format a diff for the newsfeed
	 *
	 * @param $title Title object
	 * @param $oldid Integer: old revision's id
	 * @param $newid Integer: new revision's id
	 * @param $timestamp Integer: new revision's timestamp
	 * @param string $comment new revision's comment
	 * @param string $actiontext text of the action; in case of log event
	 * @return String
	 */
	public static function formatDiffRow( $title, $oldid, $newid, $timestamp, $comment, $actiontext = '' ) {
		global $wgFeedDiffCutoff, $wgLang;
		wfProfileIn( __METHOD__ );

		// log entries
		$completeText = '<p>' . implode( ' ',
			array_filter(
				array(
					$actiontext,
					Linker::formatComment( $comment ) ) ) ) . "</p>\n";

		// NOTE: Check permissions for anonymous users, not current user.
		//       No "privileged" version should end up in the cache.
		//       Most feed readers will not log in anyway.
		$anon = new User();
		$accErrors = $title->getUserPermissionsErrors( 'read', $anon, true );

		// Can't diff special pages, unreadable pages or pages with no new revision
		// to compare against: just return the text.
		if ( $title->getNamespace() < 0 || $accErrors || !$newid ) {
			wfProfileOut( __METHOD__ );
			return $completeText;
		}

		if ( $oldid ) {
			wfProfileIn( __METHOD__ . "-dodiff" );

			#$diffText = $de->getDiff( wfMessage( 'revisionasof',
			#	$wgLang->timeanddate( $timestamp ),
			#	$wgLang->date( $timestamp ),
			#	$wgLang->time( $timestamp ) )->text(),
			#	wfMessage( 'currentrev' )->text() );

			$diffText = '';
			// Don't bother generating the diff if we won't be able to show it
			if ( $wgFeedDiffCutoff > 0 ) {
				$rev = Revision::newFromId( $oldid );

				if ( !$rev ) {
					$diffText = false;
				} else {
					$context = clone RequestContext::getMain();
					$context->setTitle( $title );

					$contentHandler = $rev->getContentHandler();
					$de = $contentHandler->createDifferenceEngine( $context, $oldid, $newid );
					$diffText = $de->getDiff(
						wfMessage( 'previousrevision' )->text(), // hack
						wfMessage( 'revisionasof',
							$wgLang->timeanddate( $timestamp ),
							$wgLang->date( $timestamp ),
							$wgLang->time( $timestamp ) )->text() );
				}
			}

			if ( $wgFeedDiffCutoff <= 0 || ( strlen( $diffText ) > $wgFeedDiffCutoff ) ) {
				// Omit large diffs
				$diffText = self::getDiffLink( $title, $newid, $oldid );
			} elseif ( $diffText === false ) {
				// Error in diff engine, probably a missing revision
				$diffText = "<p>Can't load revision $newid</p>";
			} else {
				// Diff output fine, clean up any illegal UTF-8
				$diffText = UtfNormal::cleanUp( $diffText );
				$diffText = self::applyDiffStyle( $diffText );
			}
			wfProfileOut( __METHOD__ . "-dodiff" );
		} else {
			$rev = Revision::newFromId( $newid );
			if ( $wgFeedDiffCutoff <= 0 || is_null( $rev ) ) {
				$newContent = ContentHandler::getForTitle( $title )->makeEmptyContent();
			} else {
				$newContent = $rev->getContent();
			}

			if ( $newContent instanceof TextContent ) {
				// only textual content has a "source view".
				$text = $newContent->getNativeData();

				if ( $wgFeedDiffCutoff <= 0 || strlen( $text ) > $wgFeedDiffCutoff ) {
					$html = null;
				} else {
					$html = nl2br( htmlspecialchars( $text ) );
				}
			} else {
				//XXX: we could get an HTML representation of the content via getParserOutput, but that may
				//     contain JS magic and generally may not be suitable for inclusion in a feed.
				//     Perhaps Content should have a getDescriptiveHtml method and/or a getSourceText method.
				//Compare also ApiFeedContributions::feedItemDesc
				$html = null;
			}

			if ( $html === null ) {

				// Omit large new page diffs, bug 29110
				// Also use diff link for non-textual content
				$diffText = self::getDiffLink( $title, $newid );
			} else {
				$diffText = '<p><b>' . wfMessage( 'newpage' )->text() . '</b></p>' .
					'<div>' . $html . '</div>';
			}
		}
		$completeText .= $diffText;

		wfProfileOut( __METHOD__ );
		return $completeText;
	}

	/**
	 * Generates a diff link. Used when the full diff is not wanted for example
	 * when $wgFeedDiffCutoff is 0.
	 *
	 * @param $title Title object: used to generate the diff URL
	 * @param $newid Integer newid for this diff
	 * @param $oldid Integer|null oldid for the diff. Null means it is a new article
	 * @return string
	 */
	protected static function getDiffLink( Title $title, $newid, $oldid = null ) {
		$queryParameters = array( 'diff' => $newid );
		if ( $oldid != null ) {
			$queryParameters['oldid'] = $oldid;
		}
		$diffUrl = $title->getFullURL( $queryParameters );

		$diffLink = Html::element( 'a', array( 'href' => $diffUrl ),
			wfMessage( 'showdiff' )->inContentLanguage()->text() );

		return $diffLink;
	}

	/**
	 * Hacky application of diff styles for the feeds.
	 * Might be 'cleaner' to use DOM or XSLT or something,
	 * but *gack* it's a pain in the ass.
	 *
	 * @param string $text diff's HTML output
	 * @return String: modified HTML
	 */
	public static function applyDiffStyle( $text ) {
		$styles = array(
			'diff'             => 'background-color: white; color:black;',
			'diff-otitle'      => 'background-color: white; color:black; text-align: center;',
			'diff-ntitle'      => 'background-color: white; color:black; text-align: center;',
			'diff-addedline'   => 'color:black; font-size: 88%; border-style: solid; border-width: 1px 1px 1px 4px; border-radius: 0.33em; border-color: #a3d3ff; vertical-align: top; white-space: pre-wrap;',
			'diff-deletedline' => 'color:black; font-size: 88%; border-style: solid; border-width: 1px 1px 1px 4px; border-radius: 0.33em; border-color: #ffe49c; vertical-align: top; white-space: pre-wrap;',
			'diff-context'     => 'background-color: #f9f9f9; color: #333333; font-size: 88%; border-style: solid; border-width: 1px 1px 1px 4px; border-radius: 0.33em; border-color: #e6e6e6; vertical-align: top; white-space: pre-wrap;',
			'diffchange'       => 'font-weight: bold; text-decoration: none;',
		);

		foreach ( $styles as $class => $style ) {
			$text = preg_replace( "/(<[^>]+)class=(['\"])$class\\2([^>]*>)/",
				"\\1style=\"$style\"\\3", $text );
		}

		return $text;
	}

}
