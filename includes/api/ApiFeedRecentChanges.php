<?php
/**
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
 * Recent changes feed.
 *
 * @ingroup API
 */
class ApiFeedRecentChanges extends ApiBase {

	/**
	 * This module uses a custom feed wrapper printer.
	 *
	 * @return ApiFormatFeedWrapper
	 */
	public function getCustomPrinter() {
		return new ApiFormatFeedWrapper( $this->getMain() );
	}

	/**
	 * Make a nested call to the API to request watchlist items in the last $hours.
	 * Wrap the result as an RSS/Atom feed.
	 */
	public function execute() {
		global $wgFeed, $wgFeedClasses, $wgFeedLimit, $wgSitename, $wgLanguageCode;

		$this->params = $this->extractRequestParams();

		if ( !$wgFeed ) {
			$this->dieUsage( 'Syndication feeds are not available', 'feed-unavailable' );
		}

		if ( !isset( $wgFeedClasses[$this->params['feedformat']] ) ) {
			$this->dieUsage( 'Invalid subscription feed type', 'feed-invalid' );
		}

		$feedFormat = $this->params['feedformat'];
		$specialClass = $this->params['target'] ? 'SpecialRecentchangeslinked' : 'SpecialRecentchanges';

		$formatter = $this->getFeedObject( $feedFormat, $specialClass );

		// Everything is passed implicitly via $wgRequest… :(
		$rc = new $specialClass();
		$rows = $rc->getRows();

		$feedItems = $rows ? ChangesFeed::buildItems( $rows ) : array();

		ApiFormatFeedWrapper::setResult( $this->getResult(), $formatter, $feedItems );
	}

	/**
	 * Return a ChannelFeed object.
	 *
	 * @param string $feedFormat Feed's format (either 'rss' or 'atom')
	 * @param string $specialClass Relevant special page name (either 'SpecialRecentchanges' or 'SpecialRecentchangeslinked')
	 * @return ChannelFeed
	 */
	public function getFeedObject( $feedFormat, $specialClass ) {
		if ( $specialClass === 'SpecialRecentchangeslinked' ) {
			$feed = new ChangesFeed( $feedFormat, false );
			$feedObj = $feed->getFeedObject(
				$this->msg( 'recentchangeslinked-title', Title::newFromText( $this->params['target'] )->getPrefixedText() )
					->inContentLanguage()->text(),
				$this->msg( 'recentchangeslinked-feed' )->inContentLanguage()->text(),
				SpecialPage::getTitleFor( 'Recentchangeslinked' )->getFullURL()
			);
		} else {
			$feed = new ChangesFeed( $feedFormat, 'rcfeed' );
			$feedObj = $feed->getFeedObject(
				$this->msg( 'recentchanges' )->inContentLanguage()->text(),
				$this->msg( 'recentchanges-feed-description' )->inContentLanguage()->text(),
				SpecialPage::getTitleFor( 'Recentchanges' )->getFullURL()
			);
		}

		return $feedObj;
	}

	public function getAllowedParams() {
		global $wgFeedClasses, $wgAllowCategorizedRecentChanges;
		$feedFormatNames = array_keys( $wgFeedClasses );

		$ret = array(
			'feedformat' => array(
				ApiBase::PARAM_DFLT => 'rss',
				ApiBase::PARAM_TYPE => $feedFormatNames
			),

			'namespace' => array(
				ApiBase::PARAM_TYPE => 'namespace'
			),
			'invert' => false,
			'associated' => false,

			'days' => array(
				ApiBase::PARAM_DFLT => 7,
				ApiBase::PARAM_TYPE => 'integer',
			),
			'limit' => array(
				ApiBase::PARAM_DFLT => 50,
				ApiBase::PARAM_TYPE => 'integer',
			),
			'from' => '',

			'hideminor' => false,
			'hidebots' => false,
			'hideanons' => false,
			'hideliu' => false,
			'hidepatrolled' => false,
			'hidemyself' => false,

			'tagfilter' => '',

			'target' => '',
			'showlinkedto' => false,
		);

		if ( $wgAllowCategorizedRecentChanges ) {
			$ret = array_merge( $ret, array(
				'categories' => '',
				'categories_any' => '',
			) );
		}

		return $ret;
	}

	public function getParamDescription() {
		return array(
			'feedformat' => 'The format of the feed',
			'namespace' => 'Namespace to limit the results to',
			'invert' => 'All namespaces but the selected one',
			'associated' => 'Include associated (talk or main) namespace',
			'days' => 'Days to limit the results to',
			'limit' => 'Maximum number of results to return',
			'from' => 'Show changes since then',
			'hideminor' => 'Hide minor changes',
			'hidebots' => 'Hide changes made by bots',
			'hideanons' => 'Hide changes made by anonymous users',
			'hideliu' => 'Hide changes made by registered users',
			'hidepatrolled' => 'Hide patrolled changes',
			'hidemyself' => 'Hide changes made by yourself',
			'tagfilter' => 'Filter by tag',
			'target' => 'Show only changes on pages linked from this page',
			'showlinkedto' => 'Show changes on pages linked to the selected page instead',
			'categories' => 'Show only changes on pages in all of these categories (separate with "|")',
			'categories_any' => 'Show only changes on pages in any of the categories instead',
		);
	}

	public function getDescription() {
		return 'Returns a recent changes feed';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'feed-unavailable', 'info' => 'Syndication feeds are not available' ),
			array( 'code' => 'feed-invalid', 'info' => 'Invalid subscription feed type' ),
		) );
	}

	public function getExamples() {
		return array(
			'api.php?action=feedrecentchanges',
			'api.php?action=feedrecentchanges&days=30'
		);
	}
}
