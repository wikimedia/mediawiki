<?php
/**
 *
 *
 * Created on July 7, 2007
 *
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
 * Query module to enumerate links from all pages together.
 *
 * @ingroup API
 */
class ApiQueryAllLinks extends ApiQueryGeneratorBase {

	private $table, $tablePrefix, $indexTag;
	private $fieldTitle = 'title';
	private $dfltNamespace = NS_MAIN;
	private $hasNamespace = true;
	private $useIndex = null;
	private $props = array();

	public function __construct( ApiQuery $query, $moduleName ) {
		switch ( $moduleName ) {
			case 'alllinks':
				$prefix = 'al';
				$this->table = 'pagelinks';
				$this->tablePrefix = 'pl_';
				$this->useIndex = 'pl_namespace';
				$this->indexTag = 'l';
				break;
			case 'alltransclusions':
				$prefix = 'at';
				$this->table = 'templatelinks';
				$this->tablePrefix = 'tl_';
				$this->dfltNamespace = NS_TEMPLATE;
				$this->useIndex = 'tl_namespace';
				$this->indexTag = 't';
				break;
			case 'allfileusages':
				$prefix = 'af';
				$this->table = 'imagelinks';
				$this->tablePrefix = 'il_';
				$this->fieldTitle = 'to';
				$this->dfltNamespace = NS_FILE;
				$this->hasNamespace = false;
				$this->indexTag = 'f';
				break;
			case 'allredirects':
				$prefix = 'ar';
				$this->table = 'redirect';
				$this->tablePrefix = 'rd_';
				$this->indexTag = 'r';
				$this->props = array(
					'fragment' => 'rd_fragment',
					'interwiki' => 'rd_interwiki',
				);
				break;
			default:
				ApiBase::dieDebug( __METHOD__, 'Unknown module name' );
		}

		parent::__construct( $query, $moduleName, $prefix );
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
	 * @return void
	 */
	private function run( $resultPageSet = null ) {
		$db = $this->getDB();
		$params = $this->extractRequestParams();

		$pfx = $this->tablePrefix;
		$fieldTitle = $this->fieldTitle;
		$prop = array_flip( $params['prop'] );
		$fld_ids = isset( $prop['ids'] );
		$fld_title = isset( $prop['title'] );
		if ( $this->hasNamespace ) {
			$namespace = $params['namespace'];
		} else {
			$namespace = $this->dfltNamespace;
		}

		if ( $params['unique'] ) {
			$matches = array_intersect_key( $prop, $this->props + array( 'ids' => 1 ) );
			if ( $matches ) {
				$p = $this->getModulePrefix();
				$this->dieUsage(
					"Cannot use {$p}prop=" . join( '|', array_keys( $matches ) ) . " with {$p}unique",
					'params'
				);
			}
			$this->addOption( 'DISTINCT' );
		}

		$this->addTables( $this->table );
		if ( $this->hasNamespace ) {
			$this->addWhereFld( $pfx . 'namespace', $namespace );
		}

		$continue = !is_null( $params['continue'] );
		if ( $continue ) {
			$continueArr = explode( '|', $params['continue'] );
			$op = $params['dir'] == 'descending' ? '<' : '>';
			if ( $params['unique'] ) {
				$this->dieContinueUsageIf( count( $continueArr ) != 1 );
				$continueTitle = $db->addQuotes( $continueArr[0] );
				$this->addWhere( "{$pfx}{$fieldTitle} $op= $continueTitle" );
			} else {
				$this->dieContinueUsageIf( count( $continueArr ) != 2 );
				$continueTitle = $db->addQuotes( $continueArr[0] );
				$continueFrom = intval( $continueArr[1] );
				$this->addWhere(
					"{$pfx}{$fieldTitle} $op $continueTitle OR " .
					"({$pfx}{$fieldTitle} = $continueTitle AND " .
					"{$pfx}from $op= $continueFrom)"
				);
			}
		}

		// 'continue' always overrides 'from'
		$from = ( $continue || $params['from'] === null ? null :
			$this->titlePartToKey( $params['from'], $namespace ) );
		$to = ( $params['to'] === null ? null :
			$this->titlePartToKey( $params['to'], $namespace ) );
		$this->addWhereRange( $pfx . $fieldTitle, 'newer', $from, $to );

		if ( isset( $params['prefix'] ) ) {
			$this->addWhere( $pfx . $fieldTitle . $db->buildLike( $this->titlePartToKey(
				$params['prefix'], $namespace ), $db->anyString() ) );
		}

		$this->addFields( array( 'pl_title' => $pfx . $fieldTitle ) );
		$this->addFieldsIf( array( 'pl_from' => $pfx . 'from' ), !$params['unique'] );
		foreach ( $this->props as $name => $field ) {
			$this->addFieldsIf( $field, isset( $prop[$name] ) );
		}

		if ( $this->useIndex ) {
			$this->addOption( 'USE INDEX', $this->useIndex );
		}
		$limit = $params['limit'];
		$this->addOption( 'LIMIT', $limit + 1 );

		$sort = ( $params['dir'] == 'descending' ? ' DESC' : '' );
		$orderBy = array();
		$orderBy[] = $pfx . $fieldTitle . $sort;
		if ( !$params['unique'] ) {
			$orderBy[] = $pfx . 'from' . $sort;
		}
		$this->addOption( 'ORDER BY', $orderBy );

		$res = $this->select( __METHOD__ );

		$pageids = array();
		$titles = array();
		$count = 0;
		$result = $this->getResult();
		foreach ( $res as $row ) {
			if ( ++$count > $limit ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				if ( $params['unique'] ) {
					$this->setContinueEnumParameter( 'continue', $row->pl_title );
				} else {
					$this->setContinueEnumParameter( 'continue', $row->pl_title . '|' . $row->pl_from );
				}
				break;
			}

			if ( is_null( $resultPageSet ) ) {
				$vals = array(
					ApiResult::META_TYPE => 'assoc',
				);
				if ( $fld_ids ) {
					$vals['fromid'] = intval( $row->pl_from );
				}
				if ( $fld_title ) {
					$title = Title::makeTitle( $namespace, $row->pl_title );
					ApiQueryBase::addTitleInfo( $vals, $title );
				}
				foreach ( $this->props as $name => $field ) {
					if ( isset( $prop[$name] ) && $row->$field !== null && $row->$field !== '' ) {
						$vals[$name] = $row->$field;
					}
				}
				$fit = $result->addValue( array( 'query', $this->getModuleName() ), null, $vals );
				if ( !$fit ) {
					if ( $params['unique'] ) {
						$this->setContinueEnumParameter( 'continue', $row->pl_title );
					} else {
						$this->setContinueEnumParameter( 'continue', $row->pl_title . '|' . $row->pl_from );
					}
					break;
				}
			} elseif ( $params['unique'] ) {
				$titles[] = Title::makeTitle( $namespace, $row->pl_title );
			} else {
				$pageids[] = $row->pl_from;
			}
		}

		if ( is_null( $resultPageSet ) ) {
			$result->addIndexedTagName( array( 'query', $this->getModuleName() ), $this->indexTag );
		} elseif ( $params['unique'] ) {
			$resultPageSet->populateFromTitles( $titles );
		} else {
			$resultPageSet->populateFromPageIDs( $pageids );
		}
	}

	public function getAllowedParams() {
		$allowedParams = array(
			'continue' => array(
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			),
			'from' => null,
			'to' => null,
			'prefix' => null,
			'unique' => false,
			'prop' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => 'title',
				ApiBase::PARAM_TYPE => array_merge(
					array( 'ids', 'title' ), array_keys( $this->props )
				),
			),
			'namespace' => array(
				ApiBase::PARAM_DFLT => $this->dfltNamespace,
				ApiBase::PARAM_TYPE => 'namespace'
			),
			'limit' => array(
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			),
			'dir' => array(
				ApiBase::PARAM_DFLT => 'ascending',
				ApiBase::PARAM_TYPE => array(
					'ascending',
					'descending'
				)
			),
		);
		if ( !$this->hasNamespace ) {
			unset( $allowedParams['namespace'] );
		}

		return $allowedParams;
	}

	protected function getExamplesMessages() {
		$p = $this->getModulePrefix();
		$name = $this->getModuleName();
		$path = $this->getModulePath();

		return array(
			"action=query&list={$name}&{$p}from=B&{$p}prop=ids|title"
				=> "apihelp-$path-example-B",
			"action=query&list={$name}&{$p}unique=&{$p}from=B"
				=> "apihelp-$path-example-unique",
			"action=query&generator={$name}&g{$p}unique=&g{$p}from=B"
				=> "apihelp-$path-example-unique-generator",
			"action=query&generator={$name}&g{$p}from=B"
				=> "apihelp-$path-example-generator",
		);
	}

	public function getHelpUrls() {
		$name = ucfirst( $this->getModuleName() );

		return "https://www.mediawiki.org/wiki/API:{$name}";
	}
}
