<?php
/**
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Cache\GenderCache;
use MediaWiki\Deferred\LinksUpdate\LangLinksTable;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\RestrictionStore;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeValue;

/**
 * Query module to enumerate all available pages.
 *
 * @ingroup API
 */
class ApiQueryAllPages extends ApiQueryGeneratorBase {

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		private readonly NamespaceInfo $namespaceInfo,
		private readonly GenderCache $genderCache,
		private readonly RestrictionStore $restrictionStore,
	) {
		parent::__construct( $query, $moduleName, 'ap' );
	}

	public function execute() {
		$this->run();
	}

	/** @inheritDoc */
	public function getCacheMode( $params ) {
		return 'public';
	}

	/**
	 * @param ApiPageSet $resultPageSet
	 * @return void
	 */
	public function executeGenerator( $resultPageSet ) {
		if ( $resultPageSet->isResolvingRedirects() ) {
			$this->dieWithError( 'apierror-allpages-generator-redirects', 'params' );
		}

		$this->run( $resultPageSet );
	}

	/**
	 * @param ApiPageSet|null $resultPageSet
	 * @return void
	 */
	private function run( $resultPageSet = null ) {
		$db = $this->getDB();
		$this->setVirtualDomain( LangLinksTable::VIRTUAL_DOMAIN );
		$langLinksDb = $this->getDB();

		$params = $this->extractRequestParams();

		$miserMode = $this->getConfig()->get( MainConfigNames::MiserMode );

		if ( $resultPageSet === null ) {
			$selectFields = [
				'page_namespace',
				'page_title',
				'page_id'
			];
		} else {
			$selectFields = $resultPageSet->getPageTableFields();
		}

		$miserModeFilterRedirValue = null;
		$miserModeFilterRedir = $miserMode && $params['filterredir'] !== 'all';
		if ( $miserModeFilterRedir ) {
			$selectFields[] = 'page_is_redirect';

			if ( $params['filterredir'] == 'redirects' ) {
				$miserModeFilterRedirValue = 1;
			} elseif ( $params['filterredir'] == 'nonredirects' ) {
				$miserModeFilterRedirValue = 0;
			}
		}

		$forceNameTitleIndex = !(
			isset( $params['minsize'] ) ||
			( !$miserMode && isset( $params['maxsize'] ) ) ||
			( $params['prtype'] || $params['prexpiry'] != 'all' )
		);

		$limit = $params['limit'];

		// Use loop-based query to handle langlinks filtering separately
		// (langlinks table is in a different cluster)
		$filteredRows = [];
		$pagesWithLangLinks = [];
		$filterWithLangLinks = $params['filterlanglinks'] == 'withlanglinks';
		$needLangLinksFilter = $params['filterlanglinks'] !== 'all';

		$continueFrom = null;
		if ( $params['continue'] !== null ) {
			$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'string' ] );
			$continueFrom = $cont[0];
		}

		$isFirstBatch = true;
		$loopCount = 0;
		$maxLoops = 20;
		while ( count( $filteredRows ) < $limit + 1 ) {
			if ( $loopCount > $maxLoops ) {
				// Safety limit to prevent excessive iterations
				break;
			}

			$queryBuilder = $db->newSelectQueryBuilder()
				->select( $selectFields )
				->from( 'page' )
				->where( [ 'page_namespace' => $params['namespace'] ] )
				->caller( __METHOD__ );

			if ( $continueFrom !== null ) {
				// Use strict comparison for subsequent batches to skip the continue row
				if ( $isFirstBatch ) {
					$op = $params['dir'] == 'descending' ? '<=' : '>=';
				} else {
					$op = $params['dir'] == 'descending' ? '<' : '>';
				}
				$queryBuilder->andWhere( $db->expr( 'page_title', $op, $continueFrom ) );
			}

			$dir = ( $params['dir'] == 'descending' ? 'older' : 'newer' );
			$from = ( $params['from'] === null
				? null
				: $this->titlePartToKey( $params['from'], $params['namespace'] ) );
			$to = ( $params['to'] === null
				? null
				: $this->titlePartToKey( $params['to'], $params['namespace'] ) );

			if ( $dir === 'newer' ) {
				if ( $from !== null ) {
					$queryBuilder->andWhere( $db->expr( 'page_title', '>=', $from ) );
				}
				if ( $to !== null ) {
					$queryBuilder->andWhere( $db->expr( 'page_title', '<=', $to ) );
				}
			} else {
				if ( $from !== null ) {
					$queryBuilder->andWhere( $db->expr( 'page_title', '<=', $from ) );
				}
				if ( $to !== null ) {
					$queryBuilder->andWhere( $db->expr( 'page_title', '>=', $to ) );
				}
			}

			if ( isset( $params['prefix'] ) ) {
				$queryBuilder->andWhere(
					$db->expr(
						'page_title',
						IExpression::LIKE,
						new LikeValue(
							$this->titlePartToKey( $params['prefix'], $params['namespace'] ),
							$db->anyString()
						)
					)
				);
			}

			if ( !$miserMode ) {
				if ( $params['filterredir'] == 'redirects' ) {
					$queryBuilder->andWhere( [ 'page_is_redirect' => 1 ] );
				} elseif ( $params['filterredir'] == 'nonredirects' ) {
					$queryBuilder->andWhere( [ 'page_is_redirect' => 0 ] );
				}
			}

			if ( isset( $params['minsize'] ) ) {
				$queryBuilder->andWhere( $db->expr( 'page_len', '>=', (int)$params['minsize'] ) );
			}

			if ( !$miserMode && isset( $params['maxsize'] ) ) {
				$queryBuilder->andWhere( $db->expr( 'page_len', '<=', (int)$params['maxsize'] ) );
			}

			// Page protection filtering
			if ( $params['prtype'] || $params['prexpiry'] != 'all' ) {
				$queryBuilder->join( 'page_restrictions', null, 'page_id=pr_page' );
				$queryBuilder->andWhere(
					$db->expr( 'pr_expiry', '>', $db->timestamp() )->or( 'pr_expiry', '=', null )
				);

				if ( $params['prtype'] ) {
					$queryBuilder->andWhere( [ 'pr_type' => $params['prtype'] ] );

					if ( isset( $params['prlevel'] ) ) {
						$prlevel = array_diff( $params['prlevel'], [ '', '*' ] );
						if ( count( $prlevel ) ) {
							$queryBuilder->andWhere( [ 'pr_level' => $prlevel ] );
						}
					}
					if ( $params['prfiltercascade'] == 'cascading' ) {
						$queryBuilder->andWhere( [ 'pr_cascade' => 1 ] );
					} elseif ( $params['prfiltercascade'] == 'noncascading' ) {
						$queryBuilder->andWhere( [ 'pr_cascade' => 0 ] );
					}
				}

				if ( $params['prexpiry'] == 'indefinite' ) {
					$queryBuilder->andWhere( [ 'pr_expiry' => [ $db->getInfinity(), null ] ] );
				} elseif ( $params['prexpiry'] == 'definite' ) {
					$queryBuilder->andWhere( $db->expr( 'pr_expiry', '!=', $db->getInfinity() ) );
				}

				$queryBuilder->distinct();
			} elseif ( isset( $params['prlevel'] ) ) {
				$this->dieWithError(
					[ 'apierror-invalidparammix-mustusewith', 'prlevel', 'prtype' ], 'invalidparammix'
				);
			}

			if ( $forceNameTitleIndex ) {
				$queryBuilder->useIndex( 'page_name_title' );
			}

			$sort = ( $params['dir'] == 'descending' ? ' DESC' : '' );
			$queryBuilder->orderBy( 'page_title' . $sort );
			$queryBuilder->limit( $limit + 1 );

			$res = $queryBuilder->fetchResultSet();

			$isFirstBatch = false;

			$batchRows = [];
			$pageIds = [];

			foreach ( $res as $row ) {
				$batchRows[] = $row;
				$pageIds[] = $row->page_id;
			}

			if ( $pageIds === [] ) {
				// No more rows available
				break;
			}

			if ( $needLangLinksFilter ) {
				$langLinksQueryBuilder = $langLinksDb->newSelectQueryBuilder()
					->select( 'll_from' )
					->from( 'langlinks' )
					->where( [ 'll_from' => $pageIds ] )
					->caller( __METHOD__ );

				$langLinksRes = $langLinksQueryBuilder->fetchResultSet();

				foreach ( $langLinksRes as $langLinksRow ) {
					$pagesWithLangLinks[$langLinksRow->ll_from] = true;
				}
			}

			foreach ( $batchRows as $row ) {
				if ( $needLangLinksFilter ) {
					$hasLangLinks = isset( $pagesWithLangLinks[$row->page_id] );
					if ( $filterWithLangLinks === $hasLangLinks ) {
						$filteredRows[] = $row;
					}
				} else {
					$filteredRows[] = $row;
				}
			}

			if ( count( $batchRows ) < $limit + 1 ) {
				break;
			}

			$lastRow = end( $batchRows );
			$continueFrom = $lastRow->page_title;

			$loopCount++;
		}

		$this->resetVirtualDomain();

		$res = $filteredRows;

		// Get gender information
		if ( $this->namespaceInfo->hasGenderDistinction( $params['namespace'] ) ) {
			$users = [];
			foreach ( $res as $row ) {
				$users[] = $row->page_title;
			}
			$this->genderCache->doQuery( $users, __METHOD__ );
		}

		$count = 0;
		$result = $this->getResult();
		foreach ( $res as $row ) {
			if ( ++$count > $limit ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'continue', $row->page_title );
				break;
			}

			if ( $miserModeFilterRedir && (int)$row->page_is_redirect !== $miserModeFilterRedirValue ) {
				// Filter implemented in PHP due to being in Miser Mode
				continue;
			}

			if ( $resultPageSet === null ) {
				$title = Title::makeTitle( $row->page_namespace, $row->page_title );
				$vals = [
					'pageid' => (int)$row->page_id,
					'ns' => $title->getNamespace(),
					'title' => $title->getPrefixedText()
				];
				$fit = $result->addValue( [ 'query', $this->getModuleName() ], null, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'continue', $row->page_title );
					break;
				}
			} else {
				$resultPageSet->processDbRow( $row );
			}
		}

		if ( $resultPageSet === null ) {
			$result->addIndexedTagName( [ 'query', $this->getModuleName() ], 'p' );
		}
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		$ret = [
			'from' => null,
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'to' => null,
			'prefix' => null,
			'namespace' => [
				ParamValidator::PARAM_DEFAULT => NS_MAIN,
				ParamValidator::PARAM_TYPE => 'namespace',
			],
			'filterredir' => [
				ParamValidator::PARAM_DEFAULT => 'all',
				ParamValidator::PARAM_TYPE => [
					'all',
					'redirects',
					'nonredirects'
				]
			],
			'filterlanglinks' => [
				ParamValidator::PARAM_TYPE => [
					'withlanglinks',
					'withoutlanglinks',
					'all'
				],
				ParamValidator::PARAM_DEFAULT => 'all'
			],
			'minsize' => [
				ParamValidator::PARAM_TYPE => 'integer',
			],
			'maxsize' => [
				ParamValidator::PARAM_TYPE => 'integer',
			],
			'prtype' => [
				ParamValidator::PARAM_TYPE => $this->restrictionStore->listAllRestrictionTypes( true ),
				ParamValidator::PARAM_ISMULTI => true
			],
			'prlevel' => [
				ParamValidator::PARAM_TYPE =>
					$this->getConfig()->get( MainConfigNames::RestrictionLevels ),
				ParamValidator::PARAM_ISMULTI => true
			],
			'prfiltercascade' => [
				ParamValidator::PARAM_DEFAULT => 'all',
				ParamValidator::PARAM_TYPE => [
					'cascading',
					'noncascading',
					'all'
				],
			],
			'prexpiry' => [
				ParamValidator::PARAM_TYPE => [
					'indefinite',
					'definite',
					'all'
				],
				ParamValidator::PARAM_DEFAULT => 'all',
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'limit' => [
				ParamValidator::PARAM_DEFAULT => 10,
				ParamValidator::PARAM_TYPE => 'limit',
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => ApiBase::LIMIT_BIG1,
				IntegerDef::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'dir' => [
				ParamValidator::PARAM_DEFAULT => 'ascending',
				ParamValidator::PARAM_TYPE => [
					'ascending',
					'descending'
				]
			],
		];

		if ( $this->getConfig()->get( MainConfigNames::MiserMode ) ) {
			$ret['filterredir'][ApiBase::PARAM_HELP_MSG_APPEND] = [ 'api-help-param-limited-in-miser-mode' ];
			$ret['maxsize'][ApiBase::PARAM_HELP_MSG_APPEND] = [ 'api-help-param-disabled-in-miser-mode' ];
		}

		return $ret;
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=query&list=allpages&apfrom=B'
				=> 'apihelp-query+allpages-example-b',
			'action=query&generator=allpages&gaplimit=4&gapfrom=T&prop=info'
				=> 'apihelp-query+allpages-example-generator',
			'action=query&generator=allpages&gaplimit=2&' .
				'gapfilterredir=nonredirects&gapfrom=Re&prop=revisions&rvprop=content'
				=> 'apihelp-query+allpages-example-generator-revisions',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Allpages';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryAllPages::class, 'ApiQueryAllPages' );
