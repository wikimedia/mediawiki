<?php
/**
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
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
 * A query module to enumerate categories the set of pages belong to.
 *
 * @ingroup API
 */
class ApiQueryCategories extends ApiQueryGeneratorBase {

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'cl' );
	}

	public function execute() {
		$this->run();
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * @param ApiPageSet $resultPageSet
	 */
	private function run( $resultPageSet = null ) {
		if ( $this->getPageSet()->getGoodTitleCount() == 0 ) {
			return; // nothing to do
		}

		$params = $this->extractRequestParams();
		$prop = array_flip( (array)$params['prop'] );
		$show = array_flip( (array)$params['show'] );

		$this->addFields( [
			'cl_from',
			'cl_to'
		] );

		$this->addFieldsIf( [ 'cl_sortkey', 'cl_sortkey_prefix' ], isset( $prop['sortkey'] ) );
		$this->addFieldsIf( 'cl_timestamp', isset( $prop['timestamp'] ) );

		$this->addTables( 'categorylinks' );
		$this->addWhereFld( 'cl_from', array_keys( $this->getPageSet()->getGoodTitles() ) );
		if ( $params['categories'] ) {
			$cats = [];
			foreach ( $params['categories'] as $cat ) {
				$title = Title::newFromText( $cat );
				if ( !$title || $title->getNamespace() != NS_CATEGORY ) {
					$this->addWarning( [ 'apiwarn-invalidcategory', wfEscapeWikiText( $cat ) ] );
				} else {
					$cats[] = $title->getDBkey();
				}
			}
			if ( !$cats ) {
				// No titles so no results
				return;
			}
			$this->addWhereFld( 'cl_to', $cats );
		}

		if ( !is_null( $params['continue'] ) ) {
			$cont = explode( '|', $params['continue'] );
			$this->dieContinueUsageIf( count( $cont ) != 2 );
			$op = $params['dir'] == 'descending' ? '<' : '>';
			$clfrom = (int)$cont[0];
			$clto = $this->getDB()->addQuotes( $cont[1] );
			$this->addWhere(
				"cl_from $op $clfrom OR " .
				"(cl_from = $clfrom AND " .
				"cl_to $op= $clto)"
			);
		}

		if ( isset( $show['hidden'] ) && isset( $show['!hidden'] ) ) {
			$this->dieWithError( 'apierror-show' );
		}
		if ( isset( $show['hidden'] ) || isset( $show['!hidden'] ) || isset( $prop['hidden'] ) ) {
			$this->addOption( 'STRAIGHT_JOIN' );
			$this->addTables( [ 'page', 'page_props' ] );
			$this->addFieldsIf( 'pp_propname', isset( $prop['hidden'] ) );
			$this->addJoinConds( [
				'page' => [ 'LEFT JOIN', [
					'page_namespace' => NS_CATEGORY,
					'page_title = cl_to' ] ],
				'page_props' => [ 'LEFT JOIN', [
					'pp_page=page_id',
					'pp_propname' => 'hiddencat' ] ]
			] );
			if ( isset( $show['hidden'] ) ) {
				$this->addWhere( [ 'pp_propname IS NOT NULL' ] );
			} elseif ( isset( $show['!hidden'] ) ) {
				$this->addWhere( [ 'pp_propname IS NULL' ] );
			}
		}

		$sort = ( $params['dir'] == 'descending' ? ' DESC' : '' );
		// Don't order by cl_from if it's constant in the WHERE clause
		if ( count( $this->getPageSet()->getGoodTitles() ) == 1 ) {
			$this->addOption( 'ORDER BY', 'cl_to' . $sort );
		} else {
			$this->addOption( 'ORDER BY', [
				'cl_from' . $sort,
				'cl_to' . $sort
			] );
		}

		$res = $this->select( __METHOD__ );

		$count = 0;
		if ( is_null( $resultPageSet ) ) {
			foreach ( $res as $row ) {
				if ( ++$count > $params['limit'] ) {
					// We've reached the one extra which shows that
					// there are additional pages to be had. Stop here...
					$this->setContinueEnumParameter( 'continue', $row->cl_from . '|' . $row->cl_to );
					break;
				}

				$title = Title::makeTitle( NS_CATEGORY, $row->cl_to );
				$vals = [];
				ApiQueryBase::addTitleInfo( $vals, $title );
				if ( isset( $prop['sortkey'] ) ) {
					$vals['sortkey'] = bin2hex( $row->cl_sortkey );
					$vals['sortkeyprefix'] = $row->cl_sortkey_prefix;
				}
				if ( isset( $prop['timestamp'] ) ) {
					$vals['timestamp'] = wfTimestamp( TS_ISO_8601, $row->cl_timestamp );
				}
				if ( isset( $prop['hidden'] ) ) {
					$vals['hidden'] = !is_null( $row->pp_propname );
				}

				$fit = $this->addPageSubItem( $row->cl_from, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'continue', $row->cl_from . '|' . $row->cl_to );
					break;
				}
			}
		} else {
			$titles = [];
			foreach ( $res as $row ) {
				if ( ++$count > $params['limit'] ) {
					// We've reached the one extra which shows that
					// there are additional pages to be had. Stop here...
					$this->setContinueEnumParameter( 'continue', $row->cl_from . '|' . $row->cl_to );
					break;
				}

				$titles[] = Title::makeTitle( NS_CATEGORY, $row->cl_to );
			}
			$resultPageSet->populateFromTitles( $titles );
		}
	}

	public function getAllowedParams() {
		return [
			'prop' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => [
					'sortkey',
					'timestamp',
					'hidden',
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'show' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => [
					'hidden',
					'!hidden',
				]
			],
			'limit' => [
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'categories' => [
				ApiBase::PARAM_ISMULTI => true,
			],
			'dir' => [
				ApiBase::PARAM_DFLT => 'ascending',
				ApiBase::PARAM_TYPE => [
					'ascending',
					'descending'
				]
			],
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=query&prop=categories&titles=Albert%20Einstein'
				=> 'apihelp-query+categories-example-simple',
			'action=query&generator=categories&titles=Albert%20Einstein&prop=info'
				=> 'apihelp-query+categories-example-generator',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Categories';
	}
}
