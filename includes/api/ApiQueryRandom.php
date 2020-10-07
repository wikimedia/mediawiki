<?php

/**
 * Copyright © 2008 Brent Garber
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
 * Query module to get list of random pages
 *
 * @ingroup API
 */
class ApiQueryRandom extends ApiQueryGeneratorBase {
	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'rn' );
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * Actually perform the query and add pages to the result.
	 * @param ApiPageSet|null $resultPageSet
	 * @param int $limit Number of pages to fetch
	 * @param string|null $start Starting page_random
	 * @param int $startId Starting page_id
	 * @param string|null $end Ending page_random
	 * @return array (int, string|null) Number of pages left to query and continuation string
	 */
	protected function runQuery( $resultPageSet, $limit, $start, $startId, $end ) {
		$params = $this->extractRequestParams();

		$this->resetQueryParams();
		$this->addTables( 'page' );
		$this->addFields( [ 'page_id', 'page_random' ] );
		if ( $resultPageSet === null ) {
			$this->addFields( [ 'page_title', 'page_namespace' ] );
		} else {
			$this->addFields( $resultPageSet->getPageTableFields() );
		}
		$this->addWhereFld( 'page_namespace', $params['namespace'] );
		if ( $params['redirect'] || $params['filterredir'] === 'redirects' ) {
			$this->addWhereFld( 'page_is_redirect', 1 );
		} elseif ( $params['filterredir'] === 'nonredirects' ) {
			$this->addWhereFld( 'page_is_redirect', 0 );
		} elseif ( $resultPageSet === null ) {
			$this->addFields( [ 'page_is_redirect' ] );
		}
		$this->addOption( 'LIMIT', $limit + 1 );

		if ( $start !== null ) {
			$start = $this->getDB()->addQuotes( $start );
			if ( $startId > 0 ) {
				$startId = (int)$startId; // safety
				$this->addWhere( "page_random = $start AND page_id >= $startId OR page_random > $start" );
			} else {
				$this->addWhere( "page_random >= $start" );
			}
		}
		if ( $end !== null ) {
			$this->addWhere( 'page_random < ' . $this->getDB()->addQuotes( $end ) );
		}
		$this->addOption( 'ORDER BY', [ 'page_random', 'page_id' ] );

		$result = $this->getResult();
		$path = [ 'query', $this->getModuleName() ];

		$res = $this->select( __METHOD__ );

		if ( $resultPageSet === null ) {
			$this->executeGenderCacheFromResultWrapper( $res, __METHOD__ );
		}

		$count = 0;
		foreach ( $res as $row ) {
			if ( $count++ >= $limit ) {
				return [ 0, "{$row->page_random}|{$row->page_id}" ];
			}
			if ( $resultPageSet === null ) {
				$title = Title::makeTitle( $row->page_namespace, $row->page_title );
				$page = [
					'id' => (int)$row->page_id,
				];
				ApiQueryBase::addTitleInfo( $page, $title );
				if ( isset( $row->page_is_redirect ) ) {
					$page['redirect'] = (bool)$row->page_is_redirect;
				}
				$fit = $result->addValue( $path, null, $page );
				if ( !$fit ) {
					return [ 0, "{$row->page_random}|{$row->page_id}" ];
				}
			} else {
				$resultPageSet->processDbRow( $row );
			}
		}

		return [ $limit - $count, null ];
	}

	/**
	 * @param ApiPageSet|null $resultPageSet
	 */
	public function run( $resultPageSet = null ) {
		$params = $this->extractRequestParams();

		// Since 'filterredir" will always be set in $params, we have to dig
		// into the WebRequest to see if it was actually passed.
		$request = $this->getMain()->getRequest();
		if ( $request->getCheck( $this->encodeParamName( 'filterredir' ) ) ) {
			$this->requireMaxOneParameter( $params, 'filterredir', 'redirect' );
		}

		if ( isset( $params['continue'] ) ) {
			$cont = explode( '|', $params['continue'] );
			$this->dieContinueUsageIf( count( $cont ) != 4 );
			$rand = $cont[0];
			$start = $cont[1];
			$startId = (int)$cont[2];
			$end = $cont[3] ? $rand : null;
			$this->dieContinueUsageIf( !preg_match( '/^0\.\d+$/', $rand ) );
			$this->dieContinueUsageIf( !preg_match( '/^0\.\d+$/', $start ) );
			$this->dieContinueUsageIf( $cont[2] !== (string)$startId );
			$this->dieContinueUsageIf( $cont[3] !== '0' && $cont[3] !== '1' );
		} else {
			$rand = wfRandom();
			$start = $rand;
			$startId = 0;
			$end = null;
		}

		// Set the non-continue if this is being used as a generator
		// (as a list it doesn't matter because lists never non-continue)
		if ( $resultPageSet !== null ) {
			$endFlag = $end === null ? 0 : 1;
			$this->getContinuationManager()->addGeneratorNonContinueParam(
				$this, 'continue', "$rand|$start|$startId|$endFlag"
			);
		}

		list( $left, $continue ) =
			$this->runQuery( $resultPageSet, $params['limit'], $start, $startId, $end );
		if ( $end === null && $continue === null ) {
			// Wrap around. We do this even if $left === 0 for continuation
			// (saving a DB query in this rare case probably isn't worth the
			// added code complexity it would require).
			$end = $rand;
			list( $left, $continue ) = $this->runQuery( $resultPageSet, $left, null, null, $end );
		}

		if ( $continue !== null ) {
			$endFlag = $end === null ? 0 : 1;
			$this->setContinueEnumParameter( 'continue', "$rand|$continue|$endFlag" );
		}

		if ( $resultPageSet === null ) {
			$this->getResult()->addIndexedTagName( [ 'query', $this->getModuleName() ], 'page' );
		}
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function getAllowedParams() {
		return [
			'namespace' => [
				ApiBase::PARAM_TYPE => 'namespace',
				ApiBase::PARAM_ISMULTI => true
			],
			'filterredir' => [
				ApiBase::PARAM_TYPE => [ 'all', 'redirects', 'nonredirects' ],
				ApiBase::PARAM_DFLT => 'nonredirects', // for BC
			],
			'redirect' => [
				ApiBase::PARAM_DEPRECATED => true,
				ApiBase::PARAM_DFLT => false,
			],
			'limit' => [
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_DFLT => 1,
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue'
			],
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=query&list=random&rnnamespace=0&rnlimit=2'
				=> 'apihelp-query+random-example-simple',
			'action=query&generator=random&grnnamespace=0&grnlimit=2&prop=info'
				=> 'apihelp-query+random-example-generator',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Random';
	}
}
