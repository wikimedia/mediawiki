<?php
/**
 * Copyright © 2010 Roan Kattouw "<Firstname>.<Lastname>@gmail.com"
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
 * Query module to get the results of a QueryPage-based special page
 *
 * @ingroup API
 */
class ApiQueryQueryPage extends ApiQueryGeneratorBase {
	private $qpMap;

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'qp' );
		// Build mapping from special page names to QueryPage classes
		$uselessQueryPages = $this->getConfig()->get( 'APIUselessQueryPages' );
		$this->qpMap = [];
		foreach ( QueryPage::getPages() as $page ) {
			if ( !in_array( $page[1], $uselessQueryPages ) ) {
				$this->qpMap[$page[1]] = $page[0];
			}
		}
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * @param ApiPageSet $resultPageSet
	 */
	public function run( $resultPageSet = null ) {
		$params = $this->extractRequestParams();
		$result = $this->getResult();

		/** @var QueryPage $qp */
		$qp = new $this->qpMap[$params['page']]();
		if ( !$qp->userCanExecute( $this->getUser() ) ) {
			$this->dieWithError( 'apierror-specialpage-cantexecute' );
		}

		$r = [ 'name' => $params['page'] ];
		if ( $qp->isCached() ) {
			if ( !$qp->isCacheable() ) {
				$r['disabled'] = true;
			} else {
				$r['cached'] = true;
				$ts = $qp->getCachedTimestamp();
				if ( $ts ) {
					$r['cachedtimestamp'] = wfTimestamp( TS_ISO_8601, $ts );
				}
				$r['maxresults'] = $this->getConfig()->get( 'QueryCacheLimit' );
			}
		}
		$result->addValue( [ 'query' ], $this->getModuleName(), $r );

		if ( $qp->isCached() && !$qp->isCacheable() ) {
			// Disabled query page, don't run the query
			return;
		}

		$res = $qp->doQuery( $params['offset'], $params['limit'] + 1 );
		$count = 0;
		$titles = [];
		foreach ( $res as $row ) {
			if ( ++$count > $params['limit'] ) {
				// We've had enough
				$this->setContinueEnumParameter( 'offset', $params['offset'] + $params['limit'] );
				break;
			}

			$title = Title::makeTitle( $row->namespace, $row->title );
			if ( is_null( $resultPageSet ) ) {
				$data = [ 'value' => $row->value ];
				if ( $qp->usesTimestamps() ) {
					$data['timestamp'] = wfTimestamp( TS_ISO_8601, $row->value );
				}
				self::addTitleInfo( $data, $title );

				foreach ( $row as $field => $value ) {
					if ( !in_array( $field, [ 'namespace', 'title', 'value', 'qc_type' ] ) ) {
						$data['databaseResult'][$field] = $value;
					}
				}

				$fit = $result->addValue( [ 'query', $this->getModuleName(), 'results' ], null, $data );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'offset', $params['offset'] + $count - 1 );
					break;
				}
			} else {
				$titles[] = $title;
			}
		}
		if ( is_null( $resultPageSet ) ) {
			$result->addIndexedTagName(
				[ 'query', $this->getModuleName(), 'results' ],
				'page'
			);
		} else {
			$resultPageSet->populateFromTitles( $titles );
		}
	}

	public function getCacheMode( $params ) {
		/** @var QueryPage $qp */
		$qp = new $this->qpMap[$params['page']]();
		if ( $qp->getRestriction() != '' ) {
			return 'private';
		}

		return 'public';
	}

	public function getAllowedParams() {
		return [
			'page' => [
				ApiBase::PARAM_TYPE => array_keys( $this->qpMap ),
				ApiBase::PARAM_REQUIRED => true
			],
			'offset' => [
				ApiBase::PARAM_DFLT => 0,
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'limit' => [
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=query&list=querypage&qppage=Ancientpages'
				=> 'apihelp-query+querypage-example-ancientpages',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Querypage';
	}
}
