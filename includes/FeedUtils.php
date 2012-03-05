<?php

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
	 * @param $timekey String: cache key of the timestamp of the last item
	 * @param $key String: cache key of feed's content
	 */
	public static function checkPurge( $timekey, $key ) {
		global $wgRequest, $wgUser, $messageMemc;
		$purge = $wgRequest->getVal( 'action' ) === 'purge';
		if ( $purge && $wgUser->isAllowed('purge') ) {
			$messageMemc->delete( $timekey );
			$messageMemc->delete( $key );
		}
	}

	/**
	 * Check whether feeds can be used and that $type is a valid feed type
	 *
	 * @param $type String: feed type, as requested by the user
	 * @return Boolean
	 */
	public static function checkFeedOutput( $type ) {
		global $wgFeed, $wgFeedClasses;

		if ( !$wgFeed ) {
			global $wgOut;
			$wgOut->addWikiMsg( 'feed-unavailable' );
			return false;
		}

		if( !isset( $wgFeedClasses[$type] ) ) {
			wfHttpError( 500, "Internal Server Error", "Unsupported feed type." );
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
		global $wgUser;

		$titleObj = Title::makeTitle( $row->rc_namespace, $row->rc_title );
		$timestamp = wfTimestamp( TS_MW, $row->rc_timestamp );
		$actiontext = '';
		if( $row->rc_type == RC_LOG ) {
			if( $row->rc_deleted & LogPage::DELETED_ACTION ) {
				$actiontext = wfMsgHtml('rev-deleted-event');
			} else {
				$actiontext = LogPage::actionText( $row->rc_log_type, $row->rc_log_action,
					$titleObj, $wgUser->getSkin(), LogPage::extractParams($row->rc_params,true,true) );
			}
		}
		return self::formatDiffRow( $titleObj,
			$row->rc_last_oldid, $row->rc_this_oldid,
			$timestamp,
			($row->rc_deleted & Revision::DELETED_COMMENT) ? wfMsgHtml('rev-deleted-comment') : $row->rc_comment,
			$actiontext );
	}

	/**
	 * Really format a diff for the newsfeed
	 *
	 * @param $title Title object
	 * @param $oldid Integer: old revision's id
	 * @param $newid Integer: new revision's id
	 * @param $timestamp Integer: new revision's timestamp
	 * @param $comment String: new revision's comment
	 * @param $actiontext String: text of the action; in case of log event
	 * @return String
	 */
	public static function formatDiffRow( $title, $oldid, $newid, $timestamp, $comment, $actiontext='' ) {
		global $wgFeedDiffCutoff, $wgLang, $wgUser;
		wfProfileIn( __METHOD__ );

		$skin = $wgUser->getSkin();
		# log enties
		$completeText = '<p>' . implode( ' ',
			array_filter(
				array(
					$actiontext,
					$skin->formatComment( $comment ) ) ) ) . "</p>\n";

		//NOTE: Check permissions for anonymous users, not current user.
		//      No "privileged" version should end up in the cache.
		//      Most feed readers will not log in anway.
		$anon = new User();
		$accErrors = $title->getUserPermissionsErrors( 'read', $anon, true );

		if( $title->getNamespace() >= 0 && !$accErrors && $newid ) {
			if( $oldid ) {
				wfProfileIn( __METHOD__."-dodiff" );

				#$diffText = $de->getDiff( wfMsg( 'revisionasof',
				#	$wgLang->timeanddate( $timestamp ),
				#	$wgLang->date( $timestamp ),
				#	$wgLang->time( $timestamp ) ),
				#	wfMsg( 'currentrev' ) );
				
				// Don't bother generating the diff if we won't be able to show it
				if ( $wgFeedDiffCutoff > 0 ) {
					$de = new DifferenceEngine( $title, $oldid, $newid );
					$diffText = $de->getDiff(
						wfMsg( 'previousrevision' ), // hack
						wfMsg( 'revisionasof',
							$wgLang->timeanddate( $timestamp ),
							$wgLang->date( $timestamp ),
							$wgLang->time( $timestamp ) ) );
				}

				if ( $wgFeedDiffCutoff <= 0 || ( strlen( $diffText ) > $wgFeedDiffCutoff ) ) {
					// Omit large diffs
					$diffLink = $title->escapeFullUrl(
						'diff=' . $newid .
						'&oldid=' . $oldid );
					$diffText = '<a href="' .
						$diffLink .
						'">' .
						htmlspecialchars( wfMsgForContent( 'showdiff' ) ) .
						'</a>';
				} elseif ( $diffText === false ) {
					// Error in diff engine, probably a missing revision
					$diffText = "<p>Can't load revision $newid</p>";
				} else {
					// Diff output fine, clean up any illegal UTF-8
					$diffText = UtfNormal::cleanUp( $diffText );
					$diffText = self::applyDiffStyle( $diffText );
				}
				wfProfileOut( __METHOD__."-dodiff" );
			} else {
				$rev = Revision::newFromId( $newid );
				if( is_null( $rev ) ) {
					$newtext = '';
				} else {
					$newtext = $rev->getText();
				}
				$diffText = '<p><b>' . wfMsg( 'newpage' ) . '</b></p>' .
					'<div>' . nl2br( htmlspecialchars( $newtext ) ) . '</div>';
			}
			$completeText .= $diffText;
		}

		wfProfileOut( __METHOD__ );
		return $completeText;
	}

	/**
	 * Hacky application of diff styles for the feeds.
	 * Might be 'cleaner' to use DOM or XSLT or something,
	 * but *gack* it's a pain in the ass.
	 *
	 * @param $text String: diff's HTML output
	 * @return String: modified HTML
	 * @private
	 */
	public static function applyDiffStyle( $text ) {
		$styles = array(
			'diff'             => 'background-color: white; color:black;',
			'diff-otitle'      => 'background-color: white; color:black;',
			'diff-ntitle'      => 'background-color: white; color:black;',
			'diff-addedline'   => 'background: #cfc; color:black; font-size: smaller;',
			'diff-deletedline' => 'background: #ffa; color:black; font-size: smaller;',
			'diff-context'     => 'background: #eee; color:black; font-size: smaller;',
			'diffchange'       => 'color: red; font-weight: bold; text-decoration: none;',
		);

		foreach( $styles as $class => $style ) {
			$text = preg_replace( "/(<[^>]+)class=(['\"])$class\\2([^>]*>)/",
				"\\1style=\"$style\"\\3", $text );
		}

		return $text;
	}

}
