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
 * A query module to enumerate pages that belong to a category.
 *
 * @ingroup API
 */
class ApiQueryCategoryMembers extends ApiQueryGeneratorBase {

	public function __construct( ApiQuery $query, $moduleName ) {
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
	 * @param string $hexSortkey
	 * @return bool
	 */
	private function validateHexSortkey( $hexSortkey ) {
		// A hex sortkey has an unbound number of 2 letter pairs
		return (bool)preg_match( '/^(?:[a-fA-F0-9]{2})*$/D', $hexSortkey );
	}

	/**
	 * @param ApiPageSet $resultPageSet
	 * @return void
	 */
	private function run( $resultPageSet = null ) {
		$params = $this->extractRequestParams();

		$categoryTitle = $this->getTitleOrPageId( $params )->getTitle();
		if ( $categoryTitle->getNamespace() != NS_CATEGORY ) {
			$this->dieWithError( 'apierror-invalidcategory' );
		}

		$prop = array_flip( $params['prop'] );
		$fld_ids = isset( $prop['ids'] );
		$fld_title = isset( $prop['title'] );
		$fld_sortkey = isset( $prop['sortkey'] );
		$fld_sortkeyprefix = isset( $prop['sortkeyprefix'] );
		$fld_timestamp = isset( $prop['timestamp'] );
		$fld_type = isset( $prop['type'] );

		if ( is_null( $resultPageSet ) ) {
			$this->addFields( [ 'cl_from', 'cl_sortkey', 'cl_type', 'page_namespace', 'page_title' ] );
			$this->addFieldsIf( 'page_id', $fld_ids );
			$this->addFieldsIf( 'cl_sortkey_prefix', $fld_sortkeyprefix );
		} else {
			$this->addFields( $resultPageSet->getPageTableFields() ); // will include page_ id, ns, title
			$this->addFields( [ 'cl_from', 'cl_sortkey', 'cl_type' ] );
		}

		$this->addFieldsIf( 'cl_timestamp', $fld_timestamp || $params['sort'] == 'timestamp' );

		$this->addTables( [ 'page', 'categorylinks' ] ); // must be in this order for 'USE INDEX'

		$this->addWhereFld( 'cl_to', $categoryTitle->getDBkey() );
		$queryTypes = $params['type'];
		$contWhere = false;

		// Scanning large datasets for rare categories sucks, and I already told
		// how to have efficient subcategory access :-) ~~~~ (oh well, domas)
		$miser_ns = [];
		if ( $this->getConfig()->get( 'MiserMode' ) ) {
			$miser_ns = $params['namespace'] ?: [];
		} else {
			$this->addWhereFld( 'page_namespace', $params['namespace'] );
		}

		$dir = in_array( $params['dir'], [ 'asc', 'ascending', 'newer' ] ) ? 'newer' : 'older';

		if ( $params['sort'] == 'timestamp' ) {
			$this->addTimestampWhereRange( 'cl_timestamp',
				$dir,
				$params['start'],
				$params['end'] );
			// Include in ORDER BY for uniqueness
			$this->addWhereRange( 'cl_from', $dir, null, null );

			if ( !is_null( $params['continue'] ) ) {
				$cont = explode( '|', $params['continue'] );
				$this->dieContinueUsageIf( count( $cont ) != 2 );
				$op = ( $dir === 'newer' ? '>' : '<' );
				$db = $this->getDB();
				$continueTimestamp = $db->addQuotes( $db->timestamp( $cont[0] ) );
				$continueFrom = (int)$cont[1];
				$this->dieContinueUsageIf( $continueFrom != $cont[1] );
				$this->addWhere( "cl_timestamp $op $continueTimestamp OR " .
					"(cl_timestamp = $continueTimestamp AND " .
					"cl_from $op= $continueFrom)"
				);
			}

			$this->addOption( 'USE INDEX', 'cl_timestamp' );
		} else {
			if ( $params['continue'] ) {
				$cont = explode( '|', $params['continue'], 3 );
				$this->dieContinueUsageIf( count( $cont ) != 3 );

				// Remove the types to skip from $queryTypes
				$contTypeIndex = array_search( $cont[0], $queryTypes );
				$queryTypes = array_slice( $queryTypes, $contTypeIndex );

				// Add a WHERE clause for sortkey and from
				$this->dieContinueUsageIf( !$this->validateHexSortkey( $cont[1] ) );
				$escSortkey = $this->getDB()->addQuotes( hex2bin( $cont[1] ) );
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
				if ( $params['startsortkeyprefix'] !== null ) {
					$startsortkey = Collation::singleton()->getSortKey( $params['startsortkeyprefix'] );
				} elseif ( $params['starthexsortkey'] !== null ) {
					if ( !$this->validateHexSortkey( $params['starthexsortkey'] ) ) {
						$encParamName = $this->encodeParamName( 'starthexsortkey' );
						$this->dieWithError( [ 'apierror-badparameter', $encParamName ], "badvalue_$encParamName" );
					}
					$startsortkey = hex2bin( $params['starthexsortkey'] );
				} else {
					$startsortkey = $params['startsortkey'];
				}
				if ( $params['endsortkeyprefix'] !== null ) {
					$endsortkey = Collation::singleton()->getSortKey( $params['endsortkeyprefix'] );
				} elseif ( $params['endhexsortkey'] !== null ) {
					if ( !$this->validateHexSortkey( $params['endhexsortkey'] ) ) {
						$encParamName = $this->encodeParamName( 'endhexsortkey' );
						$this->dieWithError( [ 'apierror-badparameter', $encParamName ], "badvalue_$encParamName" );
					}
					$endsortkey = hex2bin( $params['endhexsortkey'] );
				} else {
					$endsortkey = $params['endsortkey'];
				}

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
			$rows = [];
			$first = true;
			foreach ( $queryTypes as $type ) {
				$extraConds = [ 'cl_type' => $type ];
				if ( $first && $contWhere ) {
					// Continuation condition. Only added to the
					// first query, otherwise we'll skip things
					$extraConds[] = $contWhere;
				}
				$res = $this->select( __METHOD__, [ 'where' => $extraConds ] );
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
			if ( ++$count > $limit ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				// @todo Security issue - if the user has no right to view next
				// title, it will still be shown
				if ( $params['sort'] == 'timestamp' ) {
					$this->setContinueEnumParameter( 'continue', "$row->cl_timestamp|$row->cl_from" );
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
				$vals = [
					ApiResult::META_TYPE => 'assoc',
				];
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
				if ( $fld_type ) {
					$vals['type'] = $row->cl_type;
				}
				if ( $fld_timestamp ) {
					$vals['timestamp'] = wfTimestamp( TS_ISO_8601, $row->cl_timestamp );
				}
				$fit = $result->addValue( [ 'query', $this->getModuleName() ],
					null, $vals );
				if ( !$fit ) {
					if ( $params['sort'] == 'timestamp' ) {
						$this->setContinueEnumParameter( 'continue', "$row->cl_timestamp|$row->cl_from" );
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
			$result->addIndexedTagName(
				[ 'query', $this->getModuleName() ], 'cm' );
		}
	}

	public function getAllowedParams() {
		$ret = [
			'title' => [
				ApiBase::PARAM_TYPE => 'string',
			],
			'pageid' => [
				ApiBase::PARAM_TYPE => 'integer'
			],
			'prop' => [
				ApiBase::PARAM_DFLT => 'ids|title',
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => [
					'ids',
					'title',
					'sortkey',
					'sortkeyprefix',
					'type',
					'timestamp',
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'namespace' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => 'namespace',
			],
			'type' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => 'page|subcat|file',
				ApiBase::PARAM_TYPE => [
					'page',
					'subcat',
					'file'
				]
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'limit' => [
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'sort' => [
				ApiBase::PARAM_DFLT => 'sortkey',
				ApiBase::PARAM_TYPE => [
					'sortkey',
					'timestamp'
				]
			],
			'dir' => [
				ApiBase::PARAM_DFLT => 'ascending',
				ApiBase::PARAM_TYPE => [
					'asc',
					'desc',
					// Normalising with other modules
					'ascending',
					'descending',
					'newer',
					'older',
				]
			],
			'start' => [
				ApiBase::PARAM_TYPE => 'timestamp'
			],
			'end' => [
				ApiBase::PARAM_TYPE => 'timestamp'
			],
			'starthexsortkey' => null,
			'endhexsortkey' => null,
			'startsortkeyprefix' => null,
			'endsortkeyprefix' => null,
			'startsortkey' => [
				ApiBase::PARAM_DEPRECATED => true,
			],
			'endsortkey' => [
				ApiBase::PARAM_DEPRECATED => true,
			],
		];

		if ( $this->getConfig()->get( 'MiserMode' ) ) {
			$ret['namespace'][ApiBase::PARAM_HELP_MSG_APPEND] = [
				'api-help-param-limited-in-miser-mode',
			];
		}

		return $ret;
	}

	protected function getExamplesMessages() {
		return [
			'action=query&list=categorymembers&cmtitle=Category:Physics'
				=> 'apihelp-query+categorymembers-example-simple',
			'action=query&generator=categorymembers&gcmtitle=Category:Physics&prop=info'
				=> 'apihelp-query+categorymembers-example-generator',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Categorymembers';
	}
}
