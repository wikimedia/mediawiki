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

namespace MediaWiki\Feed;

use MediaWiki\Content\TextContent;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\RequestContext;
use MediaWiki\Html\Html;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Output\OutputPage;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Title\Title;
use UtfNormal;

/**
 * Helper functions for feeds
 *
 * @ingroup Feed
 */
class FeedUtils {

	/**
	 * Check whether feeds can be used and that $type is a valid feed type
	 *
	 * @param string $type Feed type, as requested by the user
	 * @param OutputPage|null $output Null falls back to $wgOut
	 * @return bool
	 * @since 1.36 $output parameter added
	 *
	 */
	public static function checkFeedOutput( $type, $output = null ) {
		$feed = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::Feed );
		$feedClasses = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::FeedClasses );
		if ( $output === null ) {
			// Todo update GoogleNewsSitemap and deprecate
			global $wgOut;
			$output = $wgOut;
		}

		if ( !$feed ) {
			$output->addWikiMsg( 'feed-unavailable' );
			return false;
		}

		if ( !isset( $feedClasses[$type] ) ) {
			$output->addWikiMsg( 'feed-invalid' );
			return false;
		}

		return true;
	}

	/**
	 * Format a diff for the newsfeed
	 *
	 * @param \stdClass $row Row from the recentchanges table, including fields as
	 *  appropriate for CommentStore
	 * @param string|null $formattedComment rc_comment in HTML format, or null
	 *   to format it on demand.
	 * @return string
	 */
	public static function formatDiff( $row, $formattedComment = null ) {
		$titleObj = Title::makeTitle( $row->rc_namespace, $row->rc_title );
		$timestamp = wfTimestamp( TS_MW, $row->rc_timestamp );
		$actiontext = '';
		if ( $row->rc_type == RC_LOG ) {
			$rcRow = (array)$row; // newFromRow() only accepts arrays for RC rows
			$actiontext = MediaWikiServices::getInstance()->getLogFormatterFactory()
				->newFromRow( $rcRow )->getActionText();
		}
		if ( $row->rc_deleted & RevisionRecord::DELETED_COMMENT ) {
			$formattedComment = wfMessage( 'rev-deleted-comment' )->escaped();
		} elseif ( $formattedComment === null ) {
			$services = MediaWikiServices::getInstance();
			$formattedComment = $services->getCommentFormatter()->format(
				$services->getCommentStore()->getComment( 'rc_comment', $row )->text );
		}
		return self::formatDiffRow2( $titleObj,
			$row->rc_last_oldid, $row->rc_this_oldid,
			$timestamp,
			$formattedComment,
			$actiontext
		);
	}

	/**
	 * Really format a diff for the newsfeed
	 *
	 * @param Title $title
	 * @param int $oldid Old revision's id
	 * @param int $newid New revision's id
	 * @param string $timestamp New revision's timestamp
	 * @param string $comment New revision's comment
	 * @param string $actiontext Text of the action; in case of log event
	 * @return string
	 * @deprecated since 1.38 use formatDiffRow2
	 *
	 */
	public static function formatDiffRow( $title, $oldid, $newid, $timestamp,
		$comment, $actiontext = ''
	) {
		$formattedComment = MediaWikiServices::getInstance()->getCommentFormatter()
			->format( $comment );
		return self::formatDiffRow2( $title, $oldid, $newid, $timestamp,
			$formattedComment, $actiontext );
	}

	/**
	 * Really really format a diff for the newsfeed. Same as formatDiffRow()
	 * except with preformatted comments.
	 *
	 * @param Title $title
	 * @param int $oldid Old revision's id
	 * @param int $newid New revision's id
	 * @param string $timestamp New revision's timestamp
	 * @param string $formattedComment New revision's comment in HTML format
	 * @param string $actiontext Text of the action; in case of log event
	 * @return string
	 */
	public static function formatDiffRow2(
		$title, $oldid, $newid, $timestamp, $formattedComment, $actiontext = ''
	) {
		$feedDiffCutoff = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::FeedDiffCutoff );

		// log entries
		$unwrappedText = implode(
			' ',
			array_filter( [ $actiontext, $formattedComment ] )
		);
		$completeText = Html::rawElement( 'p', [], $unwrappedText ) . "\n";

		// NOTE: Check permissions for anonymous users, not current user.
		//       No "privileged" version should end up in the cache.
		//       Most feed readers will not log in anyway.
		$services = MediaWikiServices::getInstance();
		$anon = $services->getUserFactory()->newAnonymous();
		$permManager = $services->getPermissionManager();
		$userCan = $permManager->userCan(
			'read',
			$anon,
			$title
		);

		// Can't diff special pages, unreadable pages or pages with no new revision
		// to compare against: just return the text.
		if ( $title->getNamespace() < 0 || !$userCan || !$newid ) {
			return $completeText;
		}

		$revLookup = $services->getRevisionLookup();
		$contentHandlerFactory = $services->getContentHandlerFactory();
		if ( $oldid ) {
			$diffText = '';
			// Don't bother generating the diff if we won't be able to show it
			if ( $feedDiffCutoff > 0 ) {
				$revRecord = $revLookup->getRevisionById( $oldid );

				if ( !$revRecord ) {
					$diffText = false;
				} else {
					$context = new DerivativeContext( RequestContext::getMain() );
					$context->setTitle( $title );

					$model = $revRecord->getSlot(
						SlotRecord::MAIN,
						RevisionRecord::RAW
					)->getModel();
					$contentHandler = $contentHandlerFactory->getContentHandler( $model );
					$de = $contentHandler->createDifferenceEngine( $context, $oldid, $newid );
					$lang = $context->getLanguage();
					$user = $context->getUser();
					$diffText = $de->getDiff(
						$context->msg( 'previousrevision' )->text(), // hack
						$context->msg( 'revisionasof',
							$lang->userTimeAndDate( $timestamp, $user ),
							$lang->userDate( $timestamp, $user ),
							$lang->userTime( $timestamp, $user ) )->text() );
				}
			}

			if ( $feedDiffCutoff <= 0 || ( strlen( $diffText ) > $feedDiffCutoff ) ) {
				// Omit large diffs
				$diffText = self::getDiffLink( $title, $newid, $oldid );
			} elseif ( $diffText === false ) {
				// Error in diff engine, probably a missing revision
				$diffText = Html::element(
					'p',
					[],
					"Can't load revision $newid"
				);
			} else {
				// Diff output fine, clean up any illegal UTF-8
				$diffText = UtfNormal\Validator::cleanUp( $diffText );
				$diffText = self::applyDiffStyle( $diffText );
			}
		} else {
			$revRecord = $revLookup->getRevisionById( $newid );
			if ( $feedDiffCutoff <= 0 || $revRecord === null ) {
				$newContent = $contentHandlerFactory
					->getContentHandler( $title->getContentModel() )
					->makeEmptyContent();
			} else {
				$newContent = $revRecord->getContent( SlotRecord::MAIN );
			}

			if ( $newContent instanceof TextContent ) {
				// only textual content has a "source view".
				$text = $newContent->getText();

				if ( $feedDiffCutoff <= 0 || strlen( $text ) > $feedDiffCutoff ) {
					$html = null;
				} else {
					$html = nl2br( htmlspecialchars( $text ) );
				}
			} else {
				// XXX: we could get an HTML representation of the content via getParserOutput, but that may
				//     contain JS magic and generally may not be suitable for inclusion in a feed.
				//     Perhaps Content should have a getDescriptiveHtml method and/or a getSourceText method.
				// Compare also ApiFeedContributions::feedItemDesc
				$html = null;
			}

			if ( $html === null ) {
				// Omit large new page diffs, T31110
				// Also use diff link for non-textual content
				$diffText = self::getDiffLink( $title, $newid );
			} else {
				$diffText = Html::rawElement(
					'p',
					[],
					Html::element( 'b', [], wfMessage( 'newpage' )->text() )
				);
				$diffText .= Html::rawElement( 'div', [], $html );
			}
		}
		$completeText .= $diffText;

		return $completeText;
	}

	/**
	 * Generates a diff link. Used when the full diff is not wanted for example
	 * when $wgFeedDiffCutoff is 0.
	 *
	 * @param Title $title Title object: used to generate the diff URL
	 * @param int $newid Newid for this diff
	 * @param int|null $oldid Oldid for the diff. Null means it is a new article
	 * @return string
	 */
	protected static function getDiffLink( Title $title, $newid, $oldid = null ) {
		$queryParameters = [ 'diff' => $newid ];
		if ( $oldid != null ) {
			$queryParameters['oldid'] = $oldid;
		}
		$diffUrl = $title->getFullURL( $queryParameters );

		$diffLink = Html::element( 'a', [ 'href' => $diffUrl ],
			wfMessage( 'showdiff' )->inContentLanguage()->text() );

		return $diffLink;
	}

	/**
	 * Hacky application of diff styles for the feeds.
	 * Might be 'cleaner' to use DOM or XSLT or something,
	 * but *gack* it's a pain in the ass.
	 *
	 * @param string $text Diff's HTML output
	 * @return string Modified HTML
	 */
	public static function applyDiffStyle( $text ) {
		$styles = [
			'diff' => 'background-color: #fff; color: #202122;',
			'diff-otitle' => 'background-color: #fff; color: #202122; text-align: center;',
			'diff-ntitle' => 'background-color: #fff; color: #202122; text-align: center;',
			'diff-addedline' => 'color: #202122; font-size: 88%; border-style: solid; '
				. 'border-width: 1px 1px 1px 4px; border-radius: 0.33em; border-color: #a3d3ff; '
				. 'vertical-align: top; white-space: pre-wrap;',
			'diff-deletedline' => 'color: #202122; font-size: 88%; border-style: solid; '
				. 'border-width: 1px 1px 1px 4px; border-radius: 0.33em; border-color: #ffe49c; '
				. 'vertical-align: top; white-space: pre-wrap;',
			'diff-context' => 'background-color: #f8f9fa; color: #202122; font-size: 88%; '
				. 'border-style: solid; border-width: 1px 1px 1px 4px; border-radius: 0.33em; '
				. 'border-color: #eaecf0; vertical-align: top; white-space: pre-wrap;',
			'diffchange' => 'font-weight: bold; text-decoration: none;',
		];

		foreach ( $styles as $class => $style ) {
			$text = preg_replace( '/(<\w+\b[^<>]*)\bclass=([\'"])(?:[^\'"]*\s)?' .
				preg_quote( $class ) . '(?:\s[^\'"]*)?\2(?=[^<>]*>)/',
				'$1style="' . $style . '"', $text );
		}

		return $text;
	}

}
