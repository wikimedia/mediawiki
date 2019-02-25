<?php
/**
 * Copyright © 2008 Roan Kattouw "<Firstname>.<Lastname>@gmail.com"
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

/**
 * This query action allows clients to retrieve a list of pages
 * on the logged-in user's watchlist.
 *
 * @ingroup API
 */
class ApiQueryWatchlistRaw extends ApiQueryGeneratorBase {

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'wr' );
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * @param ApiPageSet $resultPageSet
	 * @return void
	 */
	private function run( $resultPageSet = null ) {
		$params = $this->extractRequestParams();

		$user = $this->getWatchlistUser( $params );

		$prop = array_flip( (array)$params['prop'] );
		$show = array_flip( (array)$params['show'] );
		if ( isset( $show[WatchedItemQueryService::FILTER_CHANGED] )
			&& isset( $show[WatchedItemQueryService::FILTER_NOT_CHANGED] )
		) {
			$this->dieWithError( 'apierror-show' );
		}

		$options = [];
		if ( $params['namespace'] ) {
			$options['namespaceIds'] = $params['namespace'];
		}
		if ( isset( $show[WatchedItemQueryService::FILTER_CHANGED] ) ) {
			$options['filter'] = WatchedItemQueryService::FILTER_CHANGED;
		}
		if ( isset( $show[WatchedItemQueryService::FILTER_NOT_CHANGED] ) ) {
			$options['filter'] = WatchedItemQueryService::FILTER_NOT_CHANGED;
		}

		if ( isset( $params['continue'] ) ) {
			$cont = explode( '|', $params['continue'] );
			$this->dieContinueUsageIf( count( $cont ) != 2 );
			$ns = (int)$cont[0];
			$this->dieContinueUsageIf( strval( $ns ) !== $cont[0] );
			$title = $cont[1];
			$options['startFrom'] = new TitleValue( $ns, $title );
		}

		if ( isset( $params['fromtitle'] ) ) {
			list( $ns, $title ) = $this->prefixedTitlePartToKey( $params['fromtitle'] );
			$options['from'] = new TitleValue( $ns, $title );
		}

		if ( isset( $params['totitle'] ) ) {
			list( $ns, $title ) = $this->prefixedTitlePartToKey( $params['totitle'] );
			$options['until'] = new TitleValue( $ns, $title );
		}

		$options['sort'] = WatchedItemStore::SORT_ASC;
		if ( $params['dir'] === 'descending' ) {
			$options['sort'] = WatchedItemStore::SORT_DESC;
		}
		$options['limit'] = $params['limit'] + 1;

		$titles = [];
		$count = 0;
		$items = MediaWikiServices::getInstance()->getWatchedItemQueryService()
			->getWatchedItemsForUser( $user, $options );
		foreach ( $items as $item ) {
			$ns = $item->getLinkTarget()->getNamespace();
			$dbKey = $item->getLinkTarget()->getDBkey();
			if ( ++$count > $params['limit'] ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'continue', $ns . '|' . $dbKey );
				break;
			}
			$t = Title::makeTitle( $ns, $dbKey );

			if ( is_null( $resultPageSet ) ) {
				$vals = [];
				ApiQueryBase::addTitleInfo( $vals, $t );
				if ( isset( $prop['changed'] ) && !is_null( $item->getNotificationTimestamp() ) ) {
					$vals['changed'] = wfTimestamp( TS_ISO_8601, $item->getNotificationTimestamp() );
				}
				$fit = $this->getResult()->addValue( $this->getModuleName(), null, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'continue', $ns . '|' . $dbKey );
					break;
				}
			} else {
				$titles[] = $t;
			}
		}
		if ( is_null( $resultPageSet ) ) {
			$this->getResult()->addIndexedTagName( $this->getModuleName(), 'wr' );
		} else {
			$resultPageSet->populateFromTitles( $titles );
		}
	}

	public function getAllowedParams() {
		return [
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'namespace' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => 'namespace'
			],
			'limit' => [
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'prop' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => [
					'changed',
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'show' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => [
					WatchedItemQueryService::FILTER_CHANGED,
					WatchedItemQueryService::FILTER_NOT_CHANGED
				]
			],
			'owner' => [
				ApiBase::PARAM_TYPE => 'user'
			],
			'token' => [
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_SENSITIVE => true,
			],
			'dir' => [
				ApiBase::PARAM_DFLT => 'ascending',
				ApiBase::PARAM_TYPE => [
					'ascending',
					'descending'
				],
			],
			'fromtitle' => [
				ApiBase::PARAM_TYPE => 'string'
			],
			'totitle' => [
				ApiBase::PARAM_TYPE => 'string'
			],
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=query&list=watchlistraw'
				=> 'apihelp-query+watchlistraw-example-simple',
			'action=query&generator=watchlistraw&gwrshow=changed&prop=info'
				=> 'apihelp-query+watchlistraw-example-generator',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Watchlistraw';
	}
}
