<?php
/**
 * Copyright © 2011 Sam Reed
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

use MediaWiki\MediaWikiServices;
use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;

/**
 * @ingroup API
 */
class ApiFeedContributions extends ApiBase {

	/** @var RevisionStore */
	private $revisionStore;

	/** @var TitleParser */
	private $titleParser;

	/**
	 * This module uses a custom feed wrapper printer.
	 *
	 * @return ApiFormatFeedWrapper
	 */
	public function getCustomPrinter() {
		return new ApiFormatFeedWrapper( $this->getMain() );
	}

	public function execute() {
		$this->revisionStore = MediaWikiServices::getInstance()->getRevisionStore();
		$this->titleParser = MediaWikiServices::getInstance()->getTitleParser();

		$params = $this->extractRequestParams();

		$config = $this->getConfig();
		if ( !$config->get( 'Feed' ) ) {
			$this->dieWithError( 'feed-unavailable' );
		}

		$feedClasses = $config->get( 'FeedClasses' );
		if ( !isset( $feedClasses[$params['feedformat']] ) ) {
			$this->dieWithError( 'feed-invalid' );
		}

		if ( $params['showsizediff'] && $this->getConfig()->get( 'MiserMode' ) ) {
			$this->dieWithError( 'apierror-sizediffdisabled' );
		}

		$msg = wfMessage( 'Contributions' )->inContentLanguage()->text();
		$feedTitle = $config->get( 'Sitename' ) . ' - ' . $msg .
			' [' . $config->get( 'LanguageCode' ) . ']';

		$target = $params['user'];
		if ( ExternalUserNames::isExternal( $target ) ) {
			// Interwiki names make invalid titles, so put the target in the query instead.
			$feedUrl = SpecialPage::getTitleFor( 'Contributions' )->getFullURL( [ 'target' => $target ] );
		} else {
			$feedUrl = SpecialPage::getTitleFor( 'Contributions', $target )->getFullURL();
		}

		$feed = new $feedClasses[$params['feedformat']] (
			$feedTitle,
			htmlspecialchars( $msg ),
			$feedUrl
		);

		// Convert year/month parameters to end parameter
		$params['start'] = '';
		$params['end'] = '';
		$params = ContribsPager::processDateFilter( $params );

		$pager = new ContribsPager(
			$this->getContext(), [
				'target' => $target,
				'namespace' => $params['namespace'],
				'start' => $params['start'],
				'end' => $params['end'],
				'tagFilter' => $params['tagfilter'],
				'deletedOnly' => $params['deletedonly'],
				'topOnly' => $params['toponly'],
				'newOnly' => $params['newonly'],
				'hideMinor' => $params['hideminor'],
				'showSizeDiff' => $params['showsizediff'],
			],
			MediaWikiServices::getInstance()->getLinkRenderer(),
			MediaWikiServices::getInstance()->getLinkBatchFactory()
		);

		$feedLimit = $this->getConfig()->get( 'FeedLimit' );
		if ( $pager->getLimit() > $feedLimit ) {
			$pager->setLimit( $feedLimit );
		}

		$feedItems = [];
		if ( $pager->getNumRows() > 0 ) {
			$count = 0;
			$limit = $pager->getLimit();
			foreach ( $pager->mResult as $row ) {
				// ContribsPager selects one more row for navigation, skip that row
				if ( ++$count > $limit ) {
					break;
				}
				$item = $this->feedItem( $row );
				if ( $item !== null ) {
					$feedItems[] = $item;
				}
			}
		}

		ApiFormatFeedWrapper::setResult( $this->getResult(), $feed, $feedItems );
	}

	protected function feedItem( $row ) {
		// This hook is the api contributions equivalent to the
		// ContributionsLineEnding hook. Hook implementers may cancel
		// the hook to signal the user is not allowed to read this item.
		$feedItem = null;
		$hookResult = $this->getHookRunner()->onApiFeedContributions__feedItem(
			$row, $this->getContext(), $feedItem );
		// Hook returned a valid feed item
		if ( $feedItem instanceof FeedItem ) {
			return $feedItem;
		// Hook was canceled and did not return a valid feed item
		} elseif ( !$hookResult ) {
			return null;
		}

		// Hook completed and did not return a valid feed item
		$title = Title::makeTitle( (int)$row->page_namespace, $row->page_title );
		$user = $this->getUser();

		if ( $title && $this->getPermissionManager()->userCan( 'read', $user, $title ) ) {
			$date = $row->rev_timestamp;
			$comments = $title->getTalkPage()->getFullURL();
			$revision = $this->revisionStore->newRevisionFromRow( $row, 0, $title );

			return new FeedItem(
				$title->getPrefixedText(),
				$this->feedItemDesc( $revision ),
				$title->getFullURL( [ 'diff' => $revision->getId() ] ),
				$date,
				$this->feedItemAuthor( $revision ),
				$comments
			);
		}

		return null;
	}

	/**
	 * @since 1.32, takes a RevisionRecord instead of a Revision
	 * @param RevisionRecord $revision
	 * @return string
	 */
	protected function feedItemAuthor( RevisionRecord $revision ) {
		$user = $revision->getUser();
		return $user ? $user->getName() : '';
	}

	/**
	 * @since 1.32, takes a RevisionRecord instead of a Revision
	 * @param RevisionRecord $revision
	 * @return string
	 */
	protected function feedItemDesc( RevisionRecord $revision ) {
		$msg = wfMessage( 'colon-separator' )->inContentLanguage()->text();
		try {
			$content = $revision->getContent( SlotRecord::MAIN );
		} catch ( RevisionAccessException $e ) {
			$content = null;
		}

		if ( $content instanceof TextContent ) {
			// only textual content has a "source view".
			$html = nl2br( htmlspecialchars( $content->getText() ) );
		} else {
			// XXX: we could get an HTML representation of the content via getParserOutput, but that may
			//     contain JS magic and generally may not be suitable for inclusion in a feed.
			//     Perhaps Content should have a getDescriptiveHtml method and/or a getSourceText method.
			// Compare also FeedUtils::formatDiffRow.
			$html = '';
		}

		$comment = $revision->getComment();

		return '<p>' . htmlspecialchars( $this->feedItemAuthor( $revision ) ) . $msg .
			htmlspecialchars( FeedItem::stripComment( $comment ? $comment->text : '' ) ) .
			"</p>\n<hr />\n<div>" . $html . '</div>';
	}

	public function getAllowedParams() {
		$feedFormatNames = array_keys( $this->getConfig()->get( 'FeedClasses' ) );

		$ret = [
			'feedformat' => [
				ApiBase::PARAM_DFLT => 'rss',
				ApiBase::PARAM_TYPE => $feedFormatNames
			],
			'user' => [
				ApiBase::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'cidr', 'id', 'interwiki' ],
				ApiBase::PARAM_REQUIRED => true,
			],
			'namespace' => [
				ApiBase::PARAM_TYPE => 'namespace'
			],
			'year' => [
				ApiBase::PARAM_TYPE => 'integer'
			],
			'month' => [
				ApiBase::PARAM_TYPE => 'integer'
			],
			'tagfilter' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => array_values( ChangeTags::listDefinedTags() ),
				ApiBase::PARAM_DFLT => '',
			],
			'deletedonly' => false,
			'toponly' => false,
			'newonly' => false,
			'hideminor' => false,
			'showsizediff' => [
				ApiBase::PARAM_DFLT => false,
			],
		];

		if ( $this->getConfig()->get( 'MiserMode' ) ) {
			$ret['showsizediff'][ApiBase::PARAM_HELP_MSG] = 'api-help-param-disabled-in-miser-mode';
		}

		return $ret;
	}

	protected function getExamplesMessages() {
		return [
			'action=feedcontributions&user=Example'
				=> 'apihelp-feedcontributions-example-simple',
		];
	}
}
