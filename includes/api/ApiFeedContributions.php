<?php
/**
 *
 *
 * Created on June 06, 2011
 *
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

/**
 * @ingroup API
 */
class ApiFeedContributions extends ApiBase {

	/**
	 * This module uses a custom feed wrapper printer.
	 *
	 * @return ApiFormatFeedWrapper
	 */
	public function getCustomPrinter() {
		return new ApiFormatFeedWrapper( $this->getMain() );
	}

	public function execute() {
		$params = $this->extractRequestParams();

		$config = $this->getConfig();
		if ( !$config->get( 'Feed' ) ) {
			$this->dieUsage( 'Syndication feeds are not available', 'feed-unavailable' );
		}

		$feedClasses = $config->get( 'FeedClasses' );
		if ( !isset( $feedClasses[$params['feedformat']] ) ) {
			$this->dieUsage( 'Invalid subscription feed type', 'feed-invalid' );
		}

		if ( $params['showsizediff'] && $this->getConfig()->get( 'MiserMode' ) ) {
			$this->dieUsage( 'Size difference is disabled in Miser Mode', 'sizediffdisabled' );
		}

		$msg = wfMessage( 'Contributions' )->inContentLanguage()->text();
		$feedTitle = $config->get( 'Sitename' ) . ' - ' . $msg .
			' [' . $config->get( 'LanguageCode' ) . ']';
		$feedUrl = SpecialPage::getTitleFor( 'Contributions', $params['user'] )->getFullURL();

		$target = $params['user'] == 'newbies'
			? 'newbies'
			: Title::makeTitleSafe( NS_USER, $params['user'] )->getText();

		$feed = new $feedClasses[$params['feedformat']] (
			$feedTitle,
			htmlspecialchars( $msg ),
			$feedUrl
		);

		$pager = new ContribsPager( $this->getContext(), [
			'target' => $target,
			'namespace' => $params['namespace'],
			'year' => $params['year'],
			'month' => $params['month'],
			'tagFilter' => $params['tagfilter'],
			'deletedOnly' => $params['deletedonly'],
			'topOnly' => $params['toponly'],
			'newOnly' => $params['newonly'],
			'showSizeDiff' => $params['showsizediff'],
		] );

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
		$hookResult = Hooks::run(
			'ApiFeedContributions::feedItem',
			[ $row, $this->getContext(), &$feedItem ]
		);
		// Hook returned a valid feed item
		if ( $feedItem instanceof FeedItem ) {
			return $feedItem;
		// Hook was canceled and did not return a valid feed item
		} elseif ( !$hookResult ) {
			return null;
		}

		// Hook completed and did not return a valid feed item
		$title = Title::makeTitle( intval( $row->page_namespace ), $row->page_title );
		if ( $title && $title->userCan( 'read', $this->getUser() ) ) {
			$date = $row->rev_timestamp;
			$comments = $title->getTalkPage()->getFullURL();
			$revision = Revision::newFromRow( $row );

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
	 * @param Revision $revision
	 * @return string
	 */
	protected function feedItemAuthor( $revision ) {
		return $revision->getUserText();
	}

	/**
	 * @param Revision $revision
	 * @return string
	 */
	protected function feedItemDesc( $revision ) {
		if ( $revision ) {
			$msg = wfMessage( 'colon-separator' )->inContentLanguage()->text();
			$content = $revision->getContent();

			if ( $content instanceof TextContent ) {
				// only textual content has a "source view".
				$html = nl2br( htmlspecialchars( $content->getNativeData() ) );
			} else {
				// XXX: we could get an HTML representation of the content via getParserOutput, but that may
				//     contain JS magic and generally may not be suitable for inclusion in a feed.
				//     Perhaps Content should have a getDescriptiveHtml method and/or a getSourceText method.
				// Compare also FeedUtils::formatDiffRow.
				$html = '';
			}

			return '<p>' . htmlspecialchars( $revision->getUserText() ) . $msg .
				htmlspecialchars( FeedItem::stripComment( $revision->getComment() ) ) .
				"</p>\n<hr />\n<div>" . $html . '</div>';
		}

		return '';
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
