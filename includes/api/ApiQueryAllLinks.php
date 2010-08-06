<?php

/**
 * Created on July 7, 2007
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
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once( 'ApiQueryBase.php' );
}

/**
 * Query module to enumerate links from all pages together.
 *
 * @ingroup API
 */
class ApiQueryAllLinks extends ApiQueryGeneratorBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'al' );
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

	private function run( $resultPageSet = null ) {
		$db = $this->getDB();
		$params = $this->extractRequestParams();

		$prop = array_flip( $params['prop'] );
		$fld_ids = isset( $prop['ids'] );
		$fld_title = isset( $prop['title'] );

		if ( $params['unique'] ) {
			if ( !is_null( $resultPageSet ) ) {
				$this->dieUsage( $this->getModuleName() . ' cannot be used as a generator in unique links mode', 'params' );
			}
			if ( $fld_ids ) {
				$this->dieUsage( $this->getModuleName() . ' cannot return corresponding page ids in unique links mode', 'params' );
			}
			$this->addOption( 'DISTINCT' );
		}

		$this->addTables( 'pagelinks' );
		$this->addWhereFld( 'pl_namespace', $params['namespace'] );

		if ( !is_null( $params['from'] ) && !is_null( $params['continue'] ) ) {
			$this->dieUsage( 'alcontinue and alfrom cannot be used together', 'params' );
		}
		if ( !is_null( $params['continue'] ) ) {
			$arr = explode( '|', $params['continue'] );
			if ( count( $arr ) != 2 ) {
				$this->dieUsage( 'Invalid continue parameter', 'badcontinue' );
			}
			$from = $this->getDB()->strencode( $this->titleToKey( $arr[0] ) );
			$id = intval( $arr[1] );
			$this->addWhere(
				"pl_title > '$from' OR " .
				"(pl_title = '$from' AND " .
				"pl_from > $id)"
			);
		}

		if ( !is_null( $params['from'] ) ) {
			$this->addWhere( 'pl_title>=' . $db->addQuotes( $this->titlePartToKey( $params['from'] ) ) );
		}
		if ( !is_null( $params['to'] ) ) {
			$this->addWhere( 'pl_title<=' . $db->addQuotes( $this->titlePartToKey( $params['to'] ) ) );
		}
		if ( isset( $params['prefix'] ) ) {
			$this->addWhere( 'pl_title' . $db->buildLike( $this->titlePartToKey( $params['prefix'] ), $db->anyString() ) );
		}

		$this->addFields( array(
			'pl_title',
		) );
		$this->addFieldsIf( 'pl_from', !$params['unique'] );

		$this->addOption( 'USE INDEX', 'pl_namespace' );
		$limit = $params['limit'];
		$this->addOption( 'LIMIT', $limit + 1 );
		if ( $params['unique'] ) {
			$this->addOption( 'ORDER BY', 'pl_title' );
		} else {
			$this->addOption( 'ORDER BY', 'pl_title, pl_from' );
		}

		$res = $this->select( __METHOD__ );

		$pageids = array();
		$count = 0;
		$result = $this->getResult();
		foreach ( $res as $row ) {
			if ( ++ $count > $limit ) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				// TODO: Security issue - if the user has no right to view next title, it will still be shown
				if ( $params['unique'] ) {
					$this->setContinueEnumParameter( 'from', $this->keyToTitle( $row->pl_title ) );
				} else {
					$this->setContinueEnumParameter( 'continue', $this->keyToTitle( $row->pl_title ) . "|" . $row->pl_from );
				}
				break;
			}

			if ( is_null( $resultPageSet ) ) {
				$vals = array();
				if ( $fld_ids ) {
					$vals['fromid'] = intval( $row->pl_from );
				}
				if ( $fld_title ) {
					$title = Title::makeTitle( $params['namespace'], $row->pl_title );
					ApiQueryBase::addTitleInfo( $vals, $title );
				}
				$fit = $result->addValue( array( 'query', $this->getModuleName() ), null, $vals );
				if ( !$fit ) {
					if ( $params['unique'] ) {
						$this->setContinueEnumParameter( 'from', $this->keyToTitle( $row->pl_title ) );
					} else {
						$this->setContinueEnumParameter( 'continue', $this->keyToTitle( $row->pl_title ) . "|" . $row->pl_from );
					}
					break;
				}
			} else {
				$pageids[] = $row->pl_from;
			}
		}

		if ( is_null( $resultPageSet ) ) {
			$result->setIndexedTagName_internal( array( 'query', $this->getModuleName() ), 'l' );
		} else {
			$resultPageSet->populateFromPageIDs( $pageids );
		}
	}

	public function getAllowedParams() {
		return array(
			'continue' => null,
			'from' => null,
			'to' => null,
			'prefix' => null,
			'unique' => false,
			'prop' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => 'title',
				ApiBase::PARAM_TYPE => array(
					'ids',
					'title'
				)
			),
			'namespace' => array(
				ApiBase::PARAM_DFLT => 0,
				ApiBase::PARAM_TYPE => 'namespace'
			),
			'limit' => array(
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			)
		);
	}

	public function getParamDescription() {
		$p = $this->getModulePrefix();
		return array(
			'from' => 'The page title to start enumerating from',
			'to' => 'The page title to stop enumerating at',
			'prefix' => 'Search for all page titles that begin with this value',
			'unique' => "Only show unique links. Cannot be used with generator or {$p}prop=ids",
			'prop' => array(
				'What pieces of information to include',
				" ids    - Adds pageid of where the link is from (Cannot be used with {$p}unique)",
				' title  - Adds the title of the link',
			),
			'namespace' => 'The namespace to enumerate',
			'limit' => 'How many total links to return',
			'continue' => 'When more results are available, use this to continue',
		);
	}

	public function getDescription() {
		return 'Enumerate all links that point to a given namespace';
	}

	public function getPossibleErrors() {
		$m = $this->getModuleName();
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'params', 'info' => "{$m} cannot be used as a generator in unique links mode" ),
			array( 'code' => 'params', 'info' => "{$m} cannot return corresponding page ids in unique links mode" ),
			array( 'code' => 'params', 'info' => 'alcontinue and alfrom cannot be used together' ),
			array( 'code' => 'badcontinue', 'info' => 'Invalid continue parameter' ),
		) );
	}

	protected function getExamples() {
		return array(
			'api.php?action=query&list=alllinks&alunique&alfrom=B',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
