<?php
/**
 *
 *
 * Created on Oct 4, 2008
 *
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
		$this->selectNamedDB( 'watchlist', DB_SLAVE, 'watchlist' );

		$params = $this->extractRequestParams();

		$user = $this->getWatchlistUser( $params );

		$prop = array_flip( (array)$params['prop'] );
		$show = array_flip( (array)$params['show'] );
		if ( isset( $show['changed'] ) && isset( $show['!changed'] ) ) {
			$this->dieUsageMsg( 'show' );
		}

		$this->addTables( 'watchlist' );
		$this->addFields( [ 'wl_namespace', 'wl_title' ] );
		$this->addFieldsIf( 'wl_notificationtimestamp', isset( $prop['changed'] ) );
		$this->addWhereFld( 'wl_user', $user->getId() );
		$this->addWhereFld( 'wl_namespace', $params['namespace'] );
		$this->addWhereIf( 'wl_notificationtimestamp IS NOT NULL', isset( $show['changed'] ) );
		$this->addWhereIf( 'wl_notificationtimestamp IS NULL', isset( $show['!changed'] ) );

		if ( isset( $params['continue'] ) ) {
			$cont = explode( '|', $params['continue'] );
			$this->dieContinueUsageIf( count( $cont ) != 2 );
			$ns = intval( $cont[0] );
			$this->dieContinueUsageIf( strval( $ns ) !== $cont[0] );
			$title = $this->getDB()->addQuotes( $cont[1] );
			$op = $params['dir'] == 'ascending' ? '>' : '<';
			$this->addWhere(
				"wl_namespace $op $ns OR " .
				"(wl_namespace = $ns AND " .
				"wl_title $op= $title)"
			);
		}

		if ( isset( $params['fromtitle'] ) ) {
			list( $ns, $title ) = $this->prefixedTitlePartToKey( $params['fromtitle'] );
			$title = $this->getDB()->addQuotes( $title );
			$op = $params['dir'] == 'ascending' ? '>' : '<';
			$this->addWhere(
				"wl_namespace $op $ns OR " .
				"(wl_namespace = $ns AND " .
				"wl_title $op= $title)"
			);
		}

		if ( isset( $params['totitle'] ) ) {
			list( $ns, $title ) = $this->prefixedTitlePartToKey( $params['totitle'] );
			$title = $this->getDB()->addQuotes( $title );
			$op = $params['dir'] == 'ascending' ? '<' : '>'; // Reversed from above!
			$this->addWhere(
				"wl_namespace $op $ns OR " .
				"(wl_namespace = $ns AND " .
				"wl_title $op= $title)"
			);
		}

		$sort = ( $params['dir'] == 'descending' ? ' DESC' : '' );
		// Don't ORDER BY wl_namespace if it's constant in the WHERE clause
		if ( count( $params['namespace'] ) == 1 ) {
			$this->addOption( 'ORDER BY', 'wl_title' . $sort );
		} else {
			$this->addOption( 'ORDER BY', [
				'wl_namespace' . $sort,
				'wl_title' . $sort
			] );
		}
		$this->addOption( 'LIMIT', $params['limit'] + 1 );
		$res = $this->select( __METHOD__ );

		$titles = [];
		$count = 0;
		foreach ( $res as $row ) {
			if ( ++$count > $params['limit'] ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'continue', $row->wl_namespace . '|' . $row->wl_title );
				break;
			}
			$t = Title::makeTitle( $row->wl_namespace, $row->wl_title );

			if ( is_null( $resultPageSet ) ) {
				$vals = [];
				ApiQueryBase::addTitleInfo( $vals, $t );
				if ( isset( $prop['changed'] ) && !is_null( $row->wl_notificationtimestamp ) ) {
					$vals['changed'] = wfTimestamp( TS_ISO_8601, $row->wl_notificationtimestamp );
				}
				$fit = $this->getResult()->addValue( $this->getModuleName(), null, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'continue', $row->wl_namespace . '|' . $row->wl_title );
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
					'changed',
					'!changed',
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
				ApiBase::PARAM_HELP_MSG => 'api-help-param-direction',
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
		return 'https://www.mediawiki.org/wiki/API:Watchlistraw';
	}
}
