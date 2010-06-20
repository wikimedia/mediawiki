<?php

/**
 * Created on May 13, 2007
 *
 * API for MediaWiki 1.8+
 *
 * Copyright © 2006 Yuri Astrakhan <Firstname><Lastname>@gmail.com
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
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once( "ApiQueryBase.php" );
}

/**
 * A query module to enumerate categories the set of pages belong to.
 *
 * @ingroup API
 */
class ApiQueryCategories extends ApiQueryGeneratorBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'cl' );
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	private function run( $resultPageSet = null ) {
		if ( $this->getPageSet()->getGoodTitleCount() == 0 ) {
			return;	// nothing to do
		}

		$params = $this->extractRequestParams();
		$prop = array_flip( (array)$params['prop'] );
		$show = array_flip( (array)$params['show'] );

		$this->addFields( array(
			'cl_from',
			'cl_to'
		) );

		$this->addFieldsIf( 'cl_sortkey', isset( $prop['sortkey'] ) );
		$this->addFieldsIf( 'cl_timestamp', isset( $prop['timestamp'] ) );

		$this->addTables( 'categorylinks' );
		$this->addWhereFld( 'cl_from', array_keys( $this->getPageSet()->getGoodTitles() ) );
		if ( !is_null( $params['categories'] ) ) {
			$cats = array();
			foreach ( $params['categories'] as $cat ) {
				$title = Title::newFromText( $cat );
				if ( !$title || $title->getNamespace() != NS_CATEGORY ) {
					$this->setWarning( "``$cat'' is not a category" );
				} else {
					$cats[] = $title->getDBkey();
				}
			}
			$this->addWhereFld( 'cl_to', $cats );
		}

		if ( !is_null( $params['continue'] ) ) {
			$cont = explode( '|', $params['continue'] );
			if ( count( $cont ) != 2 ) {
				$this->dieUsage( "Invalid continue param. You should pass the " .
					"original value returned by the previous query", "_badcontinue" );
			}
			$clfrom = intval( $cont[0] );
			$clto = $this->getDB()->strencode( $this->titleToKey( $cont[1] ) );
			$this->addWhere(
				"cl_from > $clfrom OR " .
				"(cl_from = $clfrom AND " .
				"cl_to >= '$clto')"
			);
		}

		if ( isset( $show['hidden'] ) && isset( $show['!hidden'] ) ) {
			$this->dieUsageMsg( array( 'show' ) );
		}
		if ( isset( $show['hidden'] ) || isset( $show['!hidden'] ) || isset( $prop['hidden'] ) )
		{
			$this->addOption( 'STRAIGHT_JOIN' );
			$this->addTables( array( 'page', 'page_props' ) );
			$this->addFieldsIf( 'pp_propname', isset( $prop['hidden'] ) );
			$this->addJoinConds( array(
				'page' => array( 'LEFT JOIN', array(
					'page_namespace' => NS_CATEGORY,
					'page_title = cl_to' ) ),
				'page_props' => array( 'LEFT JOIN', array(
					'pp_page=page_id',
					'pp_propname' => 'hiddencat' ) )
			) );
			if ( isset( $show['hidden'] ) ) {
				$this->addWhere( array( 'pp_propname IS NOT NULL' ) );
			} elseif ( isset( $show['!hidden'] ) ) {
				$this->addWhere( array( 'pp_propname IS NULL' ) );
			}
		}

		$this->addOption( 'USE INDEX', array( 'categorylinks' => 'cl_from' ) );
		// Don't order by cl_from if it's constant in the WHERE clause
		if ( count( $this->getPageSet()->getGoodTitles() ) == 1 ) {
			$this->addOption( 'ORDER BY', 'cl_to' );
		} else {
			$this->addOption( 'ORDER BY', "cl_from, cl_to" );
		}

		$db = $this->getDB();
		$res = $this->select( __METHOD__ );

		if ( is_null( $resultPageSet ) ) {
			$count = 0;
			while ( $row = $db->fetchObject( $res ) ) {
				if ( ++$count > $params['limit'] ) {
					// We've reached the one extra which shows that
					// there are additional pages to be had. Stop here...
					$this->setContinueEnumParameter( 'continue', $row->cl_from .
							'|' . $this->keyToTitle( $row->cl_to ) );
					break;
				}

				$title = Title::makeTitle( NS_CATEGORY, $row->cl_to );
				$vals = array();
				ApiQueryBase::addTitleInfo( $vals, $title );
				if ( isset( $prop['sortkey'] ) ) {
					$vals['sortkey'] = $row->cl_sortkey;
				}
				if ( isset( $prop['timestamp'] ) ) {
					$vals['timestamp'] = wfTimestamp( TS_ISO_8601, $row->cl_timestamp );
				}
				if ( isset( $prop['hidden'] ) && !is_null( $row->pp_propname ) ) {
					$vals['hidden'] = '';
				}

				$fit = $this->addPageSubItem( $row->cl_from, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'continue', $row->cl_from .
							'|' . $this->keyToTitle( $row->cl_to ) );
					break;
				}
			}
		} else {
			$titles = array();
			while ( $row = $db->fetchObject( $res ) ) {
				if ( ++$count > $params['limit'] ) {
					// We've reached the one extra which shows that
					// there are additional pages to be had. Stop here...
					$this->setContinueEnumParameter( 'continue', $row->cl_from .
							'|' . $this->keyToTitle( $row->cl_to ) );
					break;
				}

				$titles[] = Title :: makeTitle( NS_CATEGORY, $row->cl_to );
			}
			$resultPageSet->populateFromTitles( $titles );
		}
	}

	public function getAllowedParams() {
		return array(
			'prop' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => array (
					'sortkey',
					'timestamp',
					'hidden',
				)
			),
			'show' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => array(
					'hidden',
					'!hidden',
				)
			),
			'limit' => array(
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			),
			'continue' => null,
			'categories' => array(
				ApiBase::PARAM_ISMULTI => true,
			),
		);
	}

	public function getParamDescription() {
		return array(
			'prop' => 'Which additional properties to get for each category',
			'limit' => 'How many categories to return',
			'show' => 'Which kind of categories to show',
			'continue' => 'When more results are available, use this to continue',
			'categories' => 'Only list these categories. Useful for checking whether a certain page is in a certain category',
		);
	}

	public function getDescription() {
		return 'List all categories the page(s) belong to';
	}
	
	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'show' ),
		) );
	}

	protected function getExamples() {
		return array(
			'Get a list of categories [[Albert Einstein]] belongs to:',
			'  api.php?action=query&prop=categories&titles=Albert%20Einstein',
			'Get information about all categories used in the [[Albert Einstein]]:',
			'  api.php?action=query&generator=categories&titles=Albert%20Einstein&prop=info'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
