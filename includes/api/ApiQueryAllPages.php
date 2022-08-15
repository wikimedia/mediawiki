<?php
/**
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Cache\GenderCache;
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

	private NamespaceInfo $namespaceInfo;
	private GenderCache $genderCache;
	private RestrictionStore $restrictionStore;

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		NamespaceInfo $namespaceInfo,
		GenderCache $genderCache,
		RestrictionStore $restrictionStore
	) {
		parent::__construct( $query, $moduleName, 'ap' );
		$this->namespaceInfo = $namespaceInfo;
		$this->genderCache = $genderCache;
		$this->restrictionStore = $restrictionStore;
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

		$params = $this->extractRequestParams();

		// Page filters
		$this->addTables( 'page' );

		if ( $params['continue'] !== null ) {
			$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'string' ] );
			$op = $params['dir'] == 'descending' ? '<=' : '>=';
			$this->addWhere( $db->expr( 'page_title', $op, $cont[0] ) );
		}

		$miserMode = $this->getConfig()->get( MainConfigNames::MiserMode );
		if ( !$miserMode ) {
			if ( $params['filterredir'] == 'redirects' ) {
				$this->addWhereFld( 'page_is_redirect', 1 );
			} elseif ( $params['filterredir'] == 'nonredirects' ) {
				$this->addWhereFld( 'page_is_redirect', 0 );
			}
		}

		$this->addWhereFld( 'page_namespace', $params['namespace'] );
		$dir = ( $params['dir'] == 'descending' ? 'older' : 'newer' );
		$from = ( $params['from'] === null
			? null
			: $this->titlePartToKey( $params['from'], $params['namespace'] ) );
		$to = ( $params['to'] === null
			? null
			: $this->titlePartToKey( $params['to'], $params['namespace'] ) );
		$this->addWhereRange( 'page_title', $dir, $from, $to );

		if ( isset( $params['prefix'] ) ) {
			$this->addWhere(
				$db->expr(
					'page_title',
					IExpression::LIKE,
					new LikeValue( $this->titlePartToKey( $params['prefix'], $params['namespace'] ), $db->anyString() )
				)
			);
		}

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

		$this->addFields( $selectFields );
		$forceNameTitleIndex = true;
		if ( isset( $params['minsize'] ) ) {
			$this->addWhere( 'page_len>=' . (int)$params['minsize'] );
			$forceNameTitleIndex = false;
		}

		if ( !$miserMode && isset( $params['maxsize'] ) ) {
			$this->addWhere( 'page_len<=' . (int)$params['maxsize'] );
			$forceNameTitleIndex = false;
		}

		// Page protection filtering
		if ( $params['prtype'] || $params['prexpiry'] != 'all' ) {
			$this->addTables( 'page_restrictions' );
			$this->addWhere( 'page_id=pr_page' );
			$this->addWhere(
				$db->expr( 'pr_expiry', '>', $db->timestamp() )->or( 'pr_expiry', '=', null )
			);

			if ( $params['prtype'] ) {
				$this->addWhereFld( 'pr_type', $params['prtype'] );

				if ( isset( $params['prlevel'] ) ) {
					// Remove the empty string and '*' from the prlevel array
					$prlevel = array_diff( $params['prlevel'], [ '', '*' ] );

					if ( count( $prlevel ) ) {
						$this->addWhereFld( 'pr_level', $prlevel );
					}
				}
				if ( $params['prfiltercascade'] == 'cascading' ) {
					$this->addWhereFld( 'pr_cascade', 1 );
				} elseif ( $params['prfiltercascade'] == 'noncascading' ) {
					$this->addWhereFld( 'pr_cascade', 0 );
				}
			}
			$forceNameTitleIndex = false;

			if ( $params['prexpiry'] == 'indefinite' ) {
				$this->addWhereFld( 'pr_expiry', [ $db->getInfinity(), null ] );
			} elseif ( $params['prexpiry'] == 'definite' ) {
				$this->addWhere( $db->expr( 'pr_expiry', '!=', $db->getInfinity() ) );
			}

			$this->addOption( 'DISTINCT' );
		} elseif ( isset( $params['prlevel'] ) ) {
			$this->dieWithError(
				[ 'apierror-invalidparammix-mustusewith', 'prlevel', 'prtype' ], 'invalidparammix'
			);
		}

		if ( $params['filterlanglinks'] == 'withoutlanglinks' ) {
			$this->addTables( 'langlinks' );
			$this->addJoinConds( [ 'langlinks' => [ 'LEFT JOIN', 'page_id=ll_from' ] ] );
			$this->addWhere( [ 'll_from' => null ] );
			$forceNameTitleIndex = false;
		} elseif ( $params['filterlanglinks'] == 'withlanglinks' ) {
			$this->addTables( 'langlinks' );
			$this->addWhere( 'page_id=ll_from' );
			$this->addOption( 'STRAIGHT_JOIN' );

			$dbType = $db->getType();
			if ( $dbType === 'mysql' || $dbType === 'sqlite' ) {
				// Avoid MySQL filesort reported in 2015 (T78276)
				$this->addOption( 'GROUP BY', [ 'page_title' ] );
			} else {
				// SQL:1999 rules only counting primary keys
				$this->addOption( 'GROUP BY', [ 'page_title', 'page_id' ] );
			}

			$forceNameTitleIndex = false;
		}

		if ( $forceNameTitleIndex ) {
			$this->addOption( 'USE INDEX', 'page_name_title' );
		}

		$limit = $params['limit'];
		$this->addOption( 'LIMIT', $limit + 1 );
		$res = $this->select( __METHOD__ );

		// Get gender information
		if ( $this->namespaceInfo->hasGenderDistinction( $params['namespace'] ) ) {
			$users = [];
			foreach ( $res as $row ) {
				$users[] = $row->page_title;
			}
			$this->genderCache->doQuery( $users, __METHOD__ );
			$res->rewind(); // reset
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
