<?php
/**
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Deferred\LinksUpdate\CategoryLinksTable;
use MediaWiki\Title\Title;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;
use Wikimedia\Timestamp\TimestampFormat as TS;

/**
 * A query module to enumerate categories the set of pages belong to.
 *
 * @ingroup API
 */
class ApiQueryCategories extends ApiQueryGeneratorBase {

	public function __construct( ApiQuery $query, string $moduleName ) {
		parent::__construct( $query, $moduleName, 'cl' );
	}

	public function execute() {
		$this->run();
	}

	/** @inheritDoc */
	public function getCacheMode( $params ) {
		return 'public';
	}

	/** @inheritDoc */
	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * @param ApiPageSet|null $resultPageSet
	 */
	private function run( $resultPageSet = null ) {
		$pages = $this->getPageSet()->getGoodPages();
		if ( $pages === [] ) {
			return; // nothing to do
		}

		$params = $this->extractRequestParams();
		$prop = array_fill_keys( (array)$params['prop'], true );
		$show = array_fill_keys( (array)$params['show'], true );

		$cats = [];
		if ( $params['categories'] ) {
			foreach ( $params['categories'] as $cat ) {
				$title = Title::newFromText( $cat );
				if ( !$title || $title->getNamespace() !== NS_CATEGORY ) {
					$this->addWarning( [ 'apiwarn-invalidcategory', wfEscapeWikiText( $cat ) ] );
				} else {
					$cats[] = $title->getDBkey();
				}
			}

			if ( !$cats ) {
				// No titles so no results
				return;
			}
		}

		$filteredRows = [];
		$hiddenCategories = [];
		$needHidden = isset( $prop['hidden'] ) || isset( $show['!hidden'] ) || isset( $show['hidden'] );
		$needFiltering = isset( $show['hidden'] ) || isset( $show['!hidden'] );

		$continueFrom = null;
		$isFirstBatch = true;
		if ( $params['continue'] !== null ) {
			$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'int', 'string' ] );
			$continueFrom = [ $cont[0], $cont[1] ];
			$isFirstBatch = false;
		}

		$db = $this->getDB();

		$this->setVirtualDomain( CategoryLinksTable::VIRTUAL_DOMAIN );
		$categoryLinksDb = $this->getDB();

		$fields = [ 'cl_from', 'lt_title' ];
		if ( isset( $prop['sortkey'] ) ) {
			$fields[] = 'cl_sortkey';
			$fields[] = 'cl_sortkey_prefix';
		}
		if ( isset( $prop['timestamp'] ) ) {
			$fields[] = 'cl_timestamp';
		}

		$loopCount = 0;
		$maxLoops = 20;
		while ( count( $filteredRows ) < $params['limit'] + 1 ) {
			if ( $loopCount > $maxLoops ) {
				// Safety limit to prevent excessive iterations
				break;
			}

			$queryBuilder = $categoryLinksDb->newSelectQueryBuilder()
				->select( $fields )
				->from( 'categorylinks' )
				->join( 'linktarget', null, 'cl_target_id = lt_id' )
				->where( [ 'cl_from' => array_keys( $pages ), 'lt_namespace' => NS_CATEGORY ] )
				->caller( __METHOD__ );

			if ( $cats !== [] ) {
				$queryBuilder->andWhere( [ 'lt_title' => $cats ] );
			}

			if ( $continueFrom !== null ) {
				// Use strict comparison for subsequent batches to skip the continue row
				if ( $isFirstBatch ) {
					$op = $params['dir'] == 'descending' ? '<=' : '>=';
				} else {
					$op = $params['dir'] == 'descending' ? '<' : '>';
				}
				$queryBuilder->andWhere( $categoryLinksDb->buildComparison( $op, [
					'cl_from' => $continueFrom[0],
					'lt_title' => $continueFrom[1],
				] ) );
			}

			$sort = ( $params['dir'] == 'descending' ? ' DESC' : '' );
			if ( count( $pages ) === 1 ) {
				$queryBuilder->orderBy( 'lt_title' . $sort );
			} else {
				$queryBuilder->orderBy( [ 'cl_from' . $sort, 'lt_title' . $sort ] );
			}
			$queryBuilder->limit( $params['limit'] + 1 );

			$res = $queryBuilder->fetchResultSet();

			$isFirstBatch = false;

			$batchRows = [];
			$categories = [];

			foreach ( $res as $row ) {
				$batchRows[] = $row;
				$categories[] = $row->lt_title;
			}

			if ( $categories === [] ) {
				// No more rows available
				break;
			}

			if ( $needHidden ) {
				$hiddenQueryBuilder = $db->newSelectQueryBuilder()
					->select( [ 'pp_page', 'pp_propname', 'page_title' ] )
					->from( 'page_props' )
					->join( 'page', null, 'page_id = pp_page' )
					->where( [
						'pp_propname' => 'hiddencat',
						'page_namespace' => NS_CATEGORY,
						'page_title' => $categories
					] )
					->caller( __METHOD__ );

				$hiddenRes = $hiddenQueryBuilder->fetchResultSet();

				foreach ( $hiddenRes as $hiddenRow ) {
					$hiddenCategories[$hiddenRow->page_title] = true;
				}
			}

			if ( $needFiltering ) {
				foreach ( $batchRows as $row ) {
					if ( isset( $show['hidden'] ) === isset( $hiddenCategories[$row->lt_title] ) ) {
						$filteredRows[] = $row;
					}
				}
			} else {
				$filteredRows = array_merge( $filteredRows, $batchRows );
			}

			if ( count( $batchRows ) < $params['limit'] + 1 ) {
				break;
			}

			$loopCount++;
			$lastRow = end( $batchRows );
			$continueFrom = [ $lastRow->cl_from, $lastRow->lt_title ];
		}

		$this->resetVirtualDomain();

		$count = 0;
		if ( $resultPageSet === null ) {
			foreach ( $filteredRows as $row ) {
				if ( ++$count > $params['limit'] ) {
					// We've reached the one extra which shows that
					// there are additional pages to be had. Stop here...
					$this->setContinueEnumParameter( 'continue', $row->cl_from . '|' . $row->lt_title );
					break;
				}

				$title = Title::makeTitle( NS_CATEGORY, $row->lt_title );
				$vals = [];
				ApiQueryBase::addTitleInfo( $vals, $title );
				if ( isset( $prop['sortkey'] ) ) {
					$vals['sortkey'] = bin2hex( $row->cl_sortkey );
					$vals['sortkeyprefix'] = $row->cl_sortkey_prefix;
				}
				if ( isset( $prop['timestamp'] ) ) {
					$vals['timestamp'] = wfTimestamp( TS::ISO_8601, $row->cl_timestamp );
				}
				if ( isset( $prop['hidden'] ) ) {
					$vals['hidden'] = isset( $hiddenCategories[$row->lt_title] );
				}

				$fit = $this->addPageSubItem( $row->cl_from, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'continue', $row->cl_from . '|' . $row->lt_title );
					break;
				}
			}
		} else {
			$titles = [];
			foreach ( $filteredRows as $row ) {
				if ( ++$count > $params['limit'] ) {
					// We've reached the one extra which shows that
					// there are additional pages to be had. Stop here...
					$this->setContinueEnumParameter( 'continue', $row->cl_from . '|' . $row->lt_title );
					break;
				}

				$titles[] = Title::makeTitle( NS_CATEGORY, $row->lt_title );
			}
			$resultPageSet->populateFromTitles( $titles );
		}
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'prop' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => [
					'sortkey',
					'timestamp',
					'hidden',
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'show' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => [
					'hidden',
					'!hidden',
				]
			],
			'limit' => [
				ParamValidator::PARAM_DEFAULT => 10,
				ParamValidator::PARAM_TYPE => 'limit',
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => ApiBase::LIMIT_BIG1,
				IntegerDef::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'categories' => [
				ParamValidator::PARAM_ISMULTI => true,
			],
			'dir' => [
				ParamValidator::PARAM_DEFAULT => 'ascending',
				ParamValidator::PARAM_TYPE => [
					'ascending',
					'descending'
				]
			],
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=query&prop=categories&titles=Albert%20Einstein'
				=> 'apihelp-query+categories-example-simple',
			'action=query&generator=categories&titles=Albert%20Einstein&prop=info'
				=> 'apihelp-query+categories-example-generator',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Categories';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryCategories::class, 'ApiQueryCategories' );
