<?php
/**
 *
 *
 * Created on June 14, 2007
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
 *
 * @file
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once( "ApiQueryBase.php" );
}

/**
 * A query module to enumerate pages that belong to a category.
 *
 * @ingroup API
 */
class ApiQueryCategoryMembers extends ApiQueryGeneratorBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'cm' );
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
	 * @param $resultPageSet ApiPageSet
	 * @return void
	 */
	private function run( $resultPageSet = null ) {
		$params = $this->extractRequestParams();

		$this->requireOnlyOneParameter( $params, 'title', 'pageid' );

		if ( isset( $params['title'] ) ) {
			$categoryTitle = Title::newFromText( $params['title'] );

			if ( is_null( $categoryTitle ) || $categoryTitle->getNamespace() != NS_CATEGORY ) {
				$this->dieUsage( 'The category name you entered is not valid', 'invalidcategory' );
			}
		} elseif( isset( $params['pageid'] ) ) {
			$categoryTitle = Title::newFromID( $params['pageid'] );

			if ( !$categoryTitle ) {
				$this->dieUsageMsg( array( 'nosuchpageid', $params['pageid'] ) );
			} elseif ( $categoryTitle->getNamespace() != NS_CATEGORY ) {
				$this->dieUsage( 'The category name you entered is not valid', 'invalidcategory' );
			}
		}

		$prop = array_flip( $params['prop'] );
		$fld_ids = isset( $prop['ids'] );
		$fld_title = isset( $prop['title'] );
		$fld_sortkey = isset( $prop['sortkey'] );
		$fld_sortkeyprefix = isset( $prop['sortkeyprefix'] );
		$fld_timestamp = isset( $prop['timestamp'] );
		$fld_type = isset( $prop['type'] );

		if ( is_null( $resultPageSet ) ) {
			$this->addFields( array( 'cl_from', 'page_namespace', 'page_title' ) );
			$this->addFieldsIf( 'page_id', $fld_ids );
			$this->addFieldsIf( 'cl_sortkey_prefix', $fld_sortkeyprefix );
			$this->addFieldsIf( 'cl_sortkey', $fld_sortkey );
		} else {
			$this->addFields( $resultPageSet->getPageTableFields() ); // will include page_ id, ns, title
			$this->addFields( array( 'cl_from', 'cl_sortkey' ) );
		}

		$this->addFieldsIf( 'cl_timestamp', $fld_timestamp || $params['sort'] == 'timestamp' );
		$this->addFieldsIf( 'cl_type', $fld_type );

		$this->addTables( array( 'page', 'categorylinks' ) );	// must be in this order for 'USE INDEX'

		$this->addWhereFld( 'cl_to', $categoryTitle->getDBkey() );
		$this->addWhereFld( 'cl_type', $params['type'] );

		// Scanning large datasets for rare categories sucks, and I already told
		// how to have efficient subcategory access :-) ~~~~ (oh well, domas)
		global $wgMiserMode;
		$miser_ns = array();
		if ( $wgMiserMode ) {
			$miser_ns = $params['namespace'];
		} else {
			$this->addWhereFld( 'page_namespace', $params['namespace'] );
		}

		$dir = $params['dir'] == 'asc' ? 'newer' : 'older';

		if ( $params['sort'] == 'timestamp' ) {
			$this->addWhereRange( 'cl_timestamp',
				$dir,
				$params['start'],
				$params['end'] );

			$this->addOption( 'USE INDEX', 'cl_timestamp' );
		} else {
			// The below produces ORDER BY cl_type, cl_sortkey, cl_from, possibly with DESC added to each of them
			$this->addWhereRange( 'cl_type', $dir, null, null );
			$this->addWhereRange( 'cl_sortkey',
				$dir,
				$params['startsortkey'],
				$params['endsortkey'] );
			$this->addWhereRange( 'cl_from', $dir, null, null );
			$this->addOption( 'USE INDEX', 'cl_sortkey' );
		}

		$this->setContinuation( $params['continue'], $params['dir'] );

		$this->addWhere( 'cl_from=page_id' );

		$limit = $params['limit'];
		$this->addOption( 'LIMIT', $limit + 1 );

		$count = 0;
		$res = $this->select( __METHOD__ );
		foreach ( $res as $row ) {
			if ( ++ $count > $limit ) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				// TODO: Security issue - if the user has no right to view next title, it will still be shown
				if ( $params['sort'] == 'timestamp' ) {
					$this->setContinueEnumParameter( 'start', wfTimestamp( TS_ISO_8601, $row->cl_timestamp ) );
				} else {
					$this->setContinueEnumParameter( 'continue', $row->cl_from );
				}
				break;
			}

			// Since domas won't tell anyone what he told long ago, apply
			// cmnamespace here. This means the query may return 0 actual
			// results, but on the other hand it could save returning 5000
			// useless results to the client. ~~~~
			if ( count( $miser_ns ) && !in_array( $row->page_namespace, $miser_ns ) ) {
				continue;
			}

			if ( is_null( $resultPageSet ) ) {
				$vals = array();
				if ( $fld_ids ) {
					$vals['pageid'] = intval( $row->page_id );
				}
				if ( $fld_title ) {
					$title = Title::makeTitle( $row->page_namespace, $row->page_title );
					ApiQueryBase::addTitleInfo( $vals, $title );
				}
				if ( $fld_sortkey ) {
					$vals['sortkey'] = $row->cl_sortkey;
				}
				if ( $fld_sortkeyprefix ) {
					$vals['sortkeyprefix'] = $row->cl_sortkey_prefix;
				}
				if ( $fld_type  ) {
					$vals['type'] = $row->cl_type;
				}
				if ( $fld_timestamp ) {
					$vals['timestamp'] = wfTimestamp( TS_ISO_8601, $row->cl_timestamp );
				}
				$fit = $this->getResult()->addValue( array( 'query', $this->getModuleName() ),
						null, $vals );
				if ( !$fit ) {
					if ( $params['sort'] == 'timestamp' ) {
						$this->setContinueEnumParameter( 'start', wfTimestamp( TS_ISO_8601, $row->cl_timestamp ) );
					} else {
						$this->setContinueEnumParameter( 'continue', $row->cl_from );
					}
					break;
				}
			} else {
				$resultPageSet->processDbRow( $row );
			}
		}

		if ( is_null( $resultPageSet ) ) {
			$this->getResult()->setIndexedTagName_internal(
					 array( 'query', $this->getModuleName() ), 'cm' );
		}
	}

	/**
	 * Add DB WHERE clause to continue previous query based on 'continue' parameter
	 */
	private function setContinuation( $continue, $dir ) {
		if ( is_null( $continue ) ) {
			return;	// This is not a continuation request
		}

		$encFrom = $this->getDB()->addQuotes( intval( $continue ) );

		$op = ( $dir == 'desc' ? '<=' : '>=' );

		$this->addWhere( "cl_from $op $encFrom" );
	}

	public function getAllowedParams() {
		return array(
			'title' => array(
				ApiBase::PARAM_TYPE => 'string',
			),
			'pageid' => array(
				ApiBase::PARAM_TYPE => 'integer'
			),
			'prop' => array(
				ApiBase::PARAM_DFLT => 'ids|title',
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => array (
					'ids',
					'title',
					'sortkey',
					'sortkeyprefix',
					'type',
					'timestamp',
				)
			),
			'namespace' => array (
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => 'namespace',
			),
			'type' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => 'page|subcat|file',
				ApiBase::PARAM_TYPE => array(
					'page',
					'subcat',
					'file'
				)
			),
			'continue' => null,
			'limit' => array(
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			),
			'sort' => array(
				ApiBase::PARAM_DFLT => 'sortkey',
				ApiBase::PARAM_TYPE => array(
					'sortkey',
					'timestamp'
				)
			),
			'dir' => array(
				ApiBase::PARAM_DFLT => 'asc',
				ApiBase::PARAM_TYPE => array(
					'asc',
					'desc'
				)
			),
			'start' => array(
				ApiBase::PARAM_TYPE => 'timestamp'
			),
			'end' => array(
				ApiBase::PARAM_TYPE => 'timestamp'
			),
			'startsortkey' => null,
			'endsortkey' => null,
		);
	}

	public function getParamDescription() {
		global $wgMiserMode;
		$p = $this->getModulePrefix();
		$desc = array(
			'title' => 'Which category to enumerate (required). Must include Category: prefix. Cannot be used together with cmpageid',
			'pageid' => 'Page ID of the category to enumerate. Cannot be used together with cmtitle',
			'prop' => array(
				'What pieces of information to include',
				' ids           - Adds the page ID',
				' title         - Adds the title and namespace ID of the page',
				' sortkey       - Adds the sortkey used for sorting in the category (may not be human-readble)',
				' sortkeyprefix - Adds the sortkey prefix used for sorting in the category (human-readable part of the sortkey)',
				' type          - Adds the type that the page has been categorised as (page, subcat or file)',
				' timestamp     - Adds the timestamp of when the page was included',
			),
			'namespace' => 'Only include pages in these namespaces',
			'type' => 'What type of category members to include',
			'sort' => 'Property to sort by',
			'dir' => 'In which direction to sort',
			'start' => "Timestamp to start listing from. Can only be used with {$p}sort=timestamp",
			'end' => "Timestamp to end listing at. Can only be used with {$p}sort=timestamp",
			'startsortkey' => "Sortkey to start listing from. Can only be used with {$p}sort=sortkey",
			'endsortkey' => "Sortkey to end listing at. Can only be used with {$p}sort=sortkey",
			'continue' => 'For large categories, give the value retured from previous query',
			'limit' => 'The maximum number of pages to return.',
		);
		if ( $wgMiserMode ) {
			$desc['namespace'] = array(
				$desc['namespace'],
				'NOTE: Due to $wgMiserMode, using this may result in fewer than "limit" results',
				'returned before continuing; in extreme cases, zero results may be returned',
			);
		}
		return $desc;
	}

	public function getDescription() {
		return 'List all pages in a given category';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'cmmissingparam', 'info' => 'One of the parameters title, pageid is required' ),
			array( 'code' => 'cminvalidparammix', 'info' => 'The parameters title, pageid can not be used together' ),
			array( 'code' => 'invalidcategory', 'info' => 'The category name you entered is not valid' ),
			array( 'code' => 'badcontinue', 'info' => 'Invalid continue param. You should pass the original value returned by the previous query' ),
			array( 'nosuchpageid', 'pageid' ),
		) );
	}

	protected function getExamples() {
		return array(
			'Get first 10 pages in [[Category:Physics]]:',
			'  api.php?action=query&list=categorymembers&cmtitle=Category:Physics',
			'Get page info about first 10 pages in [[Category:Physics]]:',
			'  api.php?action=query&generator=categorymembers&gcmtitle=Category:Physics&prop=info',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
