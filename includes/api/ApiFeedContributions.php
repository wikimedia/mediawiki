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

		global $wgFeed, $wgFeedClasses, $wgSitename, $wgLanguageCode;

		if ( !$wgFeed ) {
			$this->dieUsage( 'Syndication feeds are not available', 'feed-unavailable' );
		}

		if ( !isset( $wgFeedClasses[$params['feedformat']] ) ) {
			$this->dieUsage( 'Invalid subscription feed type', 'feed-invalid' );
		}

		global $wgMiserMode;
		if ( $params['showsizediff'] && $wgMiserMode ) {
			$this->dieUsage( 'Size difference is disabled in Miser Mode', 'sizediffdisabled' );
		}

		$msg = wfMessage( 'Contributions' )->inContentLanguage()->text();
		$feedTitle = $wgSitename . ' - ' . $msg . ' [' . $wgLanguageCode . ']';
		$feedUrl = SpecialPage::getTitleFor( 'Contributions', $params['user'] )->getFullURL();

		$target = $params['user'] == 'newbies'
				? 'newbies'
				: Title::makeTitleSafe( NS_USER, $params['user'] )->getText();

		$feed = new $wgFeedClasses[$params['feedformat']] (
			$feedTitle,
			htmlspecialchars( $msg ),
			$feedUrl
		);

		$pager = new ContribsPager( $this->getContext(), array(
			'target' => $target,
			'namespace' => $params['namespace'],
			'year' => $params['year'],
			'month' => $params['month'],
			'tagFilter' => $params['tagfilter'],
			'deletedOnly' => $params['deletedonly'],
			'topOnly' => $params['toponly'],
			'showSizeDiff' => $params['showsizediff'],
		) );

		$feedItems = array();
		if ( $pager->getNumRows() > 0 ) {
			foreach ( $pager->mResult as $row ) {
				$feedItems[] = $this->feedItem( $row );
			}
		}

		ApiFormatFeedWrapper::setResult( $this->getResult(), $feed, $feedItems );
	}

	protected function feedItem( $row ) {
		$title = Title::makeTitle( intval( $row->page_namespace ), $row->page_title );
		if ( $title && $title->userCan( 'read', $this->getUser() ) ) {
			$date = $row->rev_timestamp;
			$comments = $title->getTalkPage()->getFullURL();
			$revision = Revision::newFromRow( $row );

			return new FeedItem(
				$title->getPrefixedText(),
				$this->feedItemDesc( $revision ),
				$title->getFullURL(),
				$date,
				$this->feedItemAuthor( $revision ),
				$comments
			);
		}
		return null;
	}

	/**
	 * @param $revision Revision
	 * @return string
	 */
	protected function feedItemAuthor( $revision ) {
		return $revision->getUserText();
	}

	/**
	 * @param $revision Revision
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
				//XXX: we could get an HTML representation of the content via getParserOutput, but that may
				//     contain JS magic and generally may not be suitable for inclusion in a feed.
				//     Perhaps Content should have a getDescriptiveHtml method and/or a getSourceText method.
				//Compare also FeedUtils::formatDiffRow.
				$html = '';
			}

			return '<p>' . htmlspecialchars( $revision->getUserText() ) . $msg .
				htmlspecialchars( FeedItem::stripComment( $revision->getComment() ) ) .
				"</p>\n<hr />\n<div>" . $html . "</div>";
		}
		return '';
	}

	public function getAllowedParams() {
		global $wgFeedClasses;
		$feedFormatNames = array_keys( $wgFeedClasses );
		return array(
			'feedformat' => array(
				ApiBase::PARAM_DFLT => 'rss',
				ApiBase::PARAM_TYPE => $feedFormatNames
			),
			'user' => array(
				ApiBase::PARAM_TYPE => 'user',
				ApiBase::PARAM_REQUIRED => true,
			),
			'namespace' => array(
				ApiBase::PARAM_TYPE => 'namespace'
			),
			'year' => array(
				ApiBase::PARAM_TYPE => 'integer'
			),
			'month' => array(
				ApiBase::PARAM_TYPE => 'integer'
			),
			'tagfilter' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => array_values( ChangeTags::listDefinedTags() ),
				ApiBase::PARAM_DFLT => '',
			),
			'deletedonly' => false,
			'toponly' => false,
			'showsizediff' => false,
		);
	}

	public function getParamDescription() {
		return array(
			'feedformat' => 'The format of the feed',
			'user' => 'What users to get the contributions for',
			'namespace' => 'What namespace to filter the contributions by',
			'year' => 'From year (and earlier)',
			'month' => 'From month (and earlier)',
			'tagfilter' => 'Filter contributions that have these tags',
			'deletedonly' => 'Show only deleted contributions',
			'toponly' => 'Only show edits that are latest revisions',
			'showsizediff' => 'Show the size difference between revisions. Disabled in Miser Mode',
		);
	}

	public function getDescription() {
		return 'Returns a user contributions feed';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'feed-unavailable', 'info' => 'Syndication feeds are not available' ),
			array( 'code' => 'feed-invalid', 'info' => 'Invalid subscription feed type' ),
			array( 'code' => 'sizediffdisabled', 'info' => 'Size difference is disabled in Miser Mode' ),
		) );
	}

	public function getExamples() {
		return array(
			'api.php?action=feedcontributions&user=Reedy',
		);
	}
}
