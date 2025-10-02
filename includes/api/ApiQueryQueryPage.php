<?php
/**
 * Copyright © 2010 Roan Kattouw <roan.kattouw@gmail.com>
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\MainConfigNames;
use MediaWiki\SpecialPage\QueryPage;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Title\Title;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * Query module to get the results of a QueryPage-based special page
 *
 * @ingroup API
 */
class ApiQueryQueryPage extends ApiQueryGeneratorBase {

	/**
	 * @var string[] list of special page names
	 */
	private $queryPages;

	private SpecialPageFactory $specialPageFactory;

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		SpecialPageFactory $specialPageFactory
	) {
		parent::__construct( $query, $moduleName, 'qp' );
		$this->queryPages = array_values( array_diff(
			array_column( QueryPage::getPages(), 1 ), // [ class, name ]
			$this->getConfig()->get( MainConfigNames::APIUselessQueryPages )
		) );
		$this->specialPageFactory = $specialPageFactory;
	}

	public function execute() {
		$this->run();
	}

	/** @inheritDoc */
	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * @param string $name
	 * @return QueryPage
	 */
	private function getSpecialPage( $name ): QueryPage {
		$qp = $this->specialPageFactory->getPage( $name );
		if ( !$qp ) {
			self::dieDebug(
				__METHOD__,
				'SpecialPageFactory failed to create special page ' . $name
			);
		}
		if ( !( $qp instanceof QueryPage ) ) {
			self::dieDebug(
				__METHOD__,
				'Special page ' . $name . ' is not a QueryPage'
			);
		}
		// @phan-suppress-next-line PhanTypeMismatchReturnNullable T240141
		return $qp;
	}

	/**
	 * @param ApiPageSet|null $resultPageSet Set when used as a generator, null otherwise
	 */
	public function run( $resultPageSet = null ) {
		$params = $this->extractRequestParams();
		$result = $this->getResult();

		$qp = $this->getSpecialPage( $params['page'] );
		if ( !$qp->userCanExecute( $this->getUser() ) ) {
			$this->dieWithError( 'apierror-specialpage-cantexecute' );
		}

		if ( $resultPageSet === null ) {
			$r = [ 'name' => $params['page'] ];
			if ( $qp->isCached() ) {
				if ( !$qp->isCacheable() ) {
					$r['disabled'] = true;
				} else {
					$r['cached'] = true;
					$ts = $qp->getCachedTimestamp();
					if ( $ts ) {
						$r['cachedtimestamp'] = wfTimestamp( TS_ISO_8601, $ts );
					}
					$r['maxresults'] = $this->getConfig()->get( MainConfigNames::QueryCacheLimit );
				}
			}
			$result->addValue( [ 'query' ], $this->getModuleName(), $r );
		}

		if ( $qp->isCached() && !$qp->isCacheable() ) {
			// Disabled query page, don't run the query
			return;
		}

		$res = $qp->doQuery( $params['offset'], $params['limit'] + 1 );
		$count = 0;
		$titles = [];
		foreach ( $res as $row ) {
			if ( ++$count > $params['limit'] ) {
				// We've had enough
				$this->setContinueEnumParameter( 'offset', $params['offset'] + $params['limit'] );
				break;
			}

			$title = Title::makeTitle( $row->namespace, $row->title );
			if ( $resultPageSet === null ) {
				$data = [];
				if ( isset( $row->value ) ) {
					$data['value'] = $row->value;
					if ( $qp->usesTimestamps() ) {
						$data['timestamp'] = wfTimestamp( TS_ISO_8601, $row->value );
					}
				}
				self::addTitleInfo( $data, $title );

				foreach ( $row as $field => $value ) {
					if ( !in_array( $field, [ 'namespace', 'title', 'value', 'qc_type' ] ) ) {
						$data['databaseResult'][$field] = $value;
					}
				}

				$fit = $result->addValue( [ 'query', $this->getModuleName(), 'results' ], null, $data );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'offset', $params['offset'] + $count - 1 );
					break;
				}
			} else {
				$titles[] = $title;
			}
		}
		if ( $resultPageSet === null ) {
			$result->addIndexedTagName(
				[ 'query', $this->getModuleName(), 'results' ],
				'page'
			);
		} else {
			$resultPageSet->populateFromTitles( $titles );
		}
	}

	/** @inheritDoc */
	public function getCacheMode( $params ) {
		$qp = $this->getSpecialPage( $params['page'] );
		if ( $qp->getRestriction() != '' ) {
			return 'private';
		}

		return 'public';
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'page' => [
				ParamValidator::PARAM_TYPE => $this->queryPages,
				ParamValidator::PARAM_REQUIRED => true
			],
			'offset' => [
				ParamValidator::PARAM_DEFAULT => 0,
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'limit' => [
				ParamValidator::PARAM_DEFAULT => 10,
				ParamValidator::PARAM_TYPE => 'limit',
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => ApiBase::LIMIT_BIG1,
				IntegerDef::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=query&list=querypage&qppage=Ancientpages'
				=> 'apihelp-query+querypage-example-ancientpages',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Querypage';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryQueryPage::class, 'ApiQueryQueryPage' );
