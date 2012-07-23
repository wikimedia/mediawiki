<?php
/**
 *
 *
 * Created on June 14, 2007
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

		$categoryTitle = $this->getTitleOrPageId( $params )->getTitle();
		if ( $categoryTitle->getNamespace() != NS_CATEGORY ) {
			$this->dieUsage( 'The category name you entered is not valid', 'invalidcategory' );
		}

		$prop = array_flip( $params['prop'] );
		$fld_ids = isset( $prop['ids'] );
		$fld_title = isset( $prop['title'] );
		$fld_sortkey = isset( $prop['sortkey'] );
		$fld_sortkeyprefix = isset( $prop['sortkeyprefix'] );
		$fld_timestamp = isset( $prop['timestamp'] );
		$fld_type = isset( $prop['type'] );

		if ( is_null( $resultPageSet ) ) {
			$this->addFields( array( 'cl_from', 'cl_sortkey', 'cl_type', 'page_namespace', 'page_title' ) );
			$this->addFieldsIf( 'page_id', $fld_ids );
			$this->addFieldsIf( 'cl_sortkey_prefix', $fld_sortkeyprefix );
		} else {
			$this->addFields( $resultPageSet->getPageTableFields() ); // will include page_ id, ns, title
			$this->addFields( array( 'cl_from', 'cl_sortkey', 'cl_type' ) );
		}

		$this->addFieldsIf( 'cl_timestamp', $fld_timestamp || $params['sort'] == 'timestamp' );

		$this->addTables( array( 'page', 'categorylinks' ) );	// must be in this order for 'USE INDEX'

		$this->addWhereFld( 'cl_to', $categoryTitle->getDBkey() );
		$queryTypes = $params['type'];
		$contWhere = false;

		// Scanning large datasets for rare categories sucks, and I already told
		// how to have efficient subcategory access :-) ~~~~ (oh well, domas)
		global $wgMiserMode;
		$miser_ns = array();
		if ( $wgMiserMode ) {
			$miser_ns = $params['namespace'];
		} else {
			$this->addWhereFld( 'page_namespace', $params['namespace'] );
		}

		$dir = in_array( $params['dir'], array( 'asc', 'ascending', 'newer' ) ) ? 'newer' : 'older';

		if ( $params['sort'] == 'timestamp' ) {
			$this->addTimestampWhereRange( 'cl_timestamp',
				$dir,
				$params['start'],
				$params['end'] );

			$this->addOption( 'USE INDEX', 'cl_timestamp' );
		} else {
			if ( $params['continue'] ) {
				$cont = explode( '|', $params['continue'], 3 );
				if ( count( $cont ) != 3 ) {
					$this->dieUsage( 'Invalid continue param. You should pass the original value returned '.
						'by the previous query', '_badcontinue'
					);
				}

				// Remove the types to skip from $queryTypes
				$contTypeIndex = array_search( $cont[0], $queryTypes );
				$queryTypes = array_slice( $queryTypes, $contTypeIndex );

				// Add a WHERE clause for sortkey and from
				// pack( "H*", $foo ) is used to convert hex back to binary
				$escSortkey = $this->getDB()->addQuotes( pack( "H*", $cont[1] ) );
				$from = intval( $cont[2] );
				$op = $dir == 'newer' ? '>' : '<';
				// $contWhere is used further down
				$contWhere = "cl_sortkey $op $escSortkey OR " .
					"(cl_sortkey = $escSortkey AND " .
					"cl_from $op= $from)";
				// The below produces ORDER BY cl_sortkey, cl_from, possibly with DESC added to each of them
				$this->addWhereRange( 'cl_sortkey', $dir, null, null );
				$this->addWhereRange( 'cl_from', $dir, null, null );
			} else {
				$startsortkey = $params['startsortkeyprefix'] !== null ?
					Collation::singleton()->getSortkey( $params['startsortkeyprefix'] ) :
					$params['startsortkey'];
				$endsortkey = $params['endsortkeyprefix'] !== null ?
					Collation::singleton()->getSortkey( $params['endsortkeyprefix'] ) :
					$params['endsortkey'];

				// The below produces ORDER BY cl_sortkey, cl_from, possibly with DESC added to each of them
				$this->addWhereRange( 'cl_sortkey',
					$dir,
					$startsortkey,
					$endsortkey );
				$this->addWhereRange( 'cl_from', $dir, null, null );
			}
			$this->addOption( 'USE INDEX', 'cl_sortkey' );
		}

		$this->addWhere( 'cl_from=page_id' );

		$limit = $params['limit'];
		$this->addOption( 'LIMIT', $limit + 1 );

		if ( $params['sort'] == 'sortkey' ) {
			// Run a separate SELECT query for each value of cl_type.
			// This is needed because cl_type is an enum, and MySQL has
			// inconsistencies between ORDER BY cl_type and
			// WHERE cl_type >= 'foo' making proper paging impossible
			// and unindexed.
			$rows = array();
			$first = true;
			foreach ( $queryTypes as $type ) {
				$extraConds = array( 'cl_type' => $type );
				if ( $first && $contWhere ) {
					// Continuation condition. Only added to the
					// first query, otherwise we'll skip things
					$extraConds[] = $contWhere;
				}
				$res = $this->select( __METHOD__, array( 'where' => $extraConds ) );
				$rows = array_merge( $rows, iterator_to_array( $res ) );
				if ( count( $rows ) >= $limit + 1 ) {
					break;
				}
				$first = false;
			}
		} else {
			// Sorting by timestamp
			// No need to worry about per-type queries because we
			// aren't sorting or filtering by type anyway
			$res = $this->select( __METHOD__ );
			$rows = iterator_to_array( $res );
		}

		$result = $this->getResult();
		$count = 0;
		foreach ( $rows as $row ) {
			if ( ++ $count > $limit ) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				// TODO: Security issue - if the user has no right to view next title, it will still be shown
				if ( $params['sort'] == 'timestamp' ) {
					$this->setContinueEnumParameter( 'start', wfTimestamp( TS_ISO_8601, $row->cl_timestamp ) );
				} else {
					$sortkey = bin2hex( $row->cl_sortkey );
					$this->setContinueEnumParameter( 'continue',
						"{$row->cl_type}|$sortkey|{$row->cl_from}"
					);
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
					$vals['sortkey'] = bin2hex( $row->cl_sortkey );
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
				$fit = $result->addValue( array( 'query', $this->getModuleName() ),
						null, $vals );
				if ( !$fit ) {
					if ( $params['sort'] == 'timestamp' ) {
						$this->setContinueEnumParameter( 'start', wfTimestamp( TS_ISO_8601, $row->cl_timestamp ) );
					} else {
						$sortkey = bin2hex( $row->cl_sortkey );
						$this->setContinueEnumParameter( 'continue',
							"{$row->cl_type}|$sortkey|{$row->cl_from}"
						);
					}
					break;
				}
			} else {
				$resultPageSet->processDbRow( $row );
			}
		}

		if ( is_null( $resultPageSet ) ) {
			$result->setIndexedTagName_internal(
					 array( 'query', $this->getModuleName() ), 'cm' );
		}
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
				ApiBase::PARAM_DFLT => 'ascending',
				ApiBase::PARAM_TYPE => array(
					'asc',
					'desc',
					// Normalising with other modules
					'ascending',
					'descending',
					'newer',
					'older',
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
			'startsortkeyprefix' => null,
			'endsortkeyprefix' => null,
		);
	}

	public function getParamDescription() {
		global $wgMiserMode;
		$p = $this->getModulePrefix();
		$desc = array(
			'title' => "Which category to enumerate (required). Must include Category: prefix. Cannot be used together with {$p}pageid",
			'pageid' => "Page ID of the category to enumerate. Cannot be used together with {$p}title",
			'prop' => array(
				'What pieces of information to include',
				' ids           - Adds the page ID',
				' title         - Adds the title and namespace ID of the page',
				' sortkey       - Adds the sortkey used for sorting in the category (hexadecimal string)',
				' sortkeyprefix - Adds the sortkey prefix used for sorting in the category (human-readable part of the sortkey)',
				' type          - Adds the type that the page has been categorised as (page, subcat or file)',
				' timestamp     - Adds the timestamp of when the page was included',
			),
			'namespace' => 'Only include pages in these namespaces',
			'type' => "What type of category members to include. Ignored when {$p}sort=timestamp is set",
			'sort' => 'Property to sort by',
			'dir' => 'In which direction to sort',
			'start' => "Timestamp to start listing from. Can only be used with {$p}sort=timestamp",
			'end' => "Timestamp to end listing at. Can only be used with {$p}sort=timestamp",
			'startsortkey' => "Sortkey to start listing from. Must be given in binary format. Can only be used with {$p}sort=sortkey",
			'endsortkey' => "Sortkey to end listing at. Must be given in binary format. Can only be used with {$p}sort=sortkey",
			'startsortkeyprefix' => "Sortkey prefix to start listing from. Can only be used with {$p}sort=sortkey. Overrides {$p}startsortkey",
			'endsortkeyprefix' => "Sortkey prefix to end listing BEFORE (not at, if this value occurs it will not be included!). Can only be used with {$p}sort=sortkey. Overrides {$p}endsortkey",
			'continue' => 'For large categories, give the value returned from previous query',
			'limit' => 'The maximum number of pages to return.',
		);

		if ( $wgMiserMode ) {
			$desc['namespace'] = array(
				$desc['namespace'],
				"NOTE: Due to \$wgMiserMode, using this may result in fewer than \"{$p}limit\" results",
				'returned before continuing; in extreme cases, zero results may be returned.',
				"Note that you can use {$p}type=subcat or {$p}type=file instead of {$p}namespace=14 or 6.",
			);
		}
		return $desc;
	}

	public function getResultProperties() {
		return array(
			'ids' => array(
				'pageid' => 'integer'
			),
			'title' => array(
				'ns' => 'namespace',
				'title' => 'string'
			),
			'sortkey' => array(
				'sortkey' => 'string'
			),
			'sortkeyprefix' => array(
				'sortkeyprefix' => 'string'
			),
			'type' => array(
				'type' => array(
					ApiBase::PROP_TYPE => array(
						'page',
						'subcat',
						'file'
					)
				)
			),
			'timestamp' => array(
				'timestamp' => 'timestamp'
			)
		);
	}

	public function getDescription() {
		return 'List all pages in a given category';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(),
			$this->getTitleOrPageIdErrorMessage(),
			array(
				array( 'code' => 'invalidcategory', 'info' => 'The category name you entered is not valid' ),
				array( 'code' => 'badcontinue', 'info' => 'Invalid continue param. You should pass the original value returned by the previous query' ),
			)
		);
	}

	public function getExamples() {
		return array(
			'api.php?action=query&list=categorymembers&cmtitle=Category:Physics' => 'Get first 10 pages in [[Category:Physics]]',
			'api.php?action=query&generator=categorymembers&gcmtitle=Category:Physics&prop=info' => 'Get page info about first 10 pages in [[Category:Physics]]',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Categorymembers';
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
