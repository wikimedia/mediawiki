<?php

/**
 * Copyright © 2008 Brent Garber
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Content\ContentHandlerFactory;
use MediaWiki\Title\Title;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * Query module to get list of random pages
 *
 * @ingroup API
 */
class ApiQueryRandom extends ApiQueryGeneratorBase {

	private ContentHandlerFactory $contentHandlerFactory;

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		ContentHandlerFactory $contentHandlerFactory
	) {
		parent::__construct( $query, $moduleName, 'rn' );
		$this->contentHandlerFactory = $contentHandlerFactory;
	}

	public function execute() {
		$this->run();
	}

	/** @inheritDoc */
	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * Actually perform the query and add pages to the result.
	 * @param ApiPageSet|null $resultPageSet
	 * @param int $limit Number of pages to fetch
	 * @param string|null $start Starting page_random
	 * @param int|null $startId Starting page_id
	 * @param string|null $end Ending page_random
	 * @return array (int, string|null) Number of pages left to query and continuation string
	 */
	protected function runQuery( $resultPageSet, $limit, $start, $startId, $end ) {
		$params = $this->extractRequestParams();

		$this->resetQueryParams();
		$this->addTables( 'page' );
		$this->addFields( [ 'page_id', 'page_random' ] );
		if ( $resultPageSet === null ) {
			$this->addFields( [ 'page_title', 'page_namespace' ] );
		} else {
			$this->addFields( $resultPageSet->getPageTableFields() );
		}
		$this->addWhereFld( 'page_namespace', $params['namespace'] );
		if ( $params['redirect'] || $params['filterredir'] === 'redirects' ) {
			$this->addWhereFld( 'page_is_redirect', 1 );
		} elseif ( $params['filterredir'] === 'nonredirects' ) {
			$this->addWhereFld( 'page_is_redirect', 0 );
		} elseif ( $resultPageSet === null ) {
			$this->addFields( [ 'page_is_redirect' ] );
		}

		$db = $this->getDB();
		if ( isset( $params['minsize'] ) ) {
			$this->addWhere( $db->expr( 'page_len', '>=', (int)$params['minsize'] ) );
		}
		if ( isset( $params['maxsize'] ) ) {
			$this->addWhere( $db->expr( 'page_len', '<=', (int)$params['maxsize'] ) );
		}

		if ( isset( $params['contentmodel'] ) ) {
			$this->addWhereFld( 'page_content_model', $params['contentmodel'] );
		}

		$this->addOption( 'LIMIT', $limit + 1 );

		if ( $start !== null ) {
			if ( $startId > 0 ) {
				$this->addWhere( $db->buildComparison( '>=', [
					'page_random' => $start,
					'page_id' => $startId,
				] ) );
			} else {
				$this->addWhere( $db->buildComparison( '>=', [
					'page_random' => $start,
				] ) );
			}
		}
		if ( $end !== null ) {
			$this->addWhere( $db->expr( 'page_random', '<', $end ) );
		}
		$this->addOption( 'ORDER BY', [ 'page_random', 'page_id' ] );

		$result = $this->getResult();
		$path = [ 'query', $this->getModuleName() ];

		$res = $this->select( __METHOD__ );

		if ( $resultPageSet === null ) {
			$this->executeGenderCacheFromResultWrapper( $res, __METHOD__ );
		}

		$count = 0;
		foreach ( $res as $row ) {
			if ( $count++ >= $limit ) {
				return [ 0, "{$row->page_random}|{$row->page_id}" ];
			}
			if ( $resultPageSet === null ) {
				$title = Title::makeTitle( $row->page_namespace, $row->page_title );
				$page = [
					'id' => (int)$row->page_id,
				];
				ApiQueryBase::addTitleInfo( $page, $title );
				if ( isset( $row->page_is_redirect ) ) {
					$page['redirect'] = (bool)$row->page_is_redirect;
				}
				$fit = $result->addValue( $path, null, $page );
				if ( !$fit ) {
					return [ 0, "{$row->page_random}|{$row->page_id}" ];
				}
			} else {
				$resultPageSet->processDbRow( $row );
			}
		}

		return [ $limit - $count, null ];
	}

	/**
	 * @param ApiPageSet|null $resultPageSet
	 */
	public function run( $resultPageSet = null ) {
		$params = $this->extractRequestParams();

		// Since 'filterredir' will always be set in $params, we have to dig
		// into the WebRequest to see if it was actually passed.
		$request = $this->getMain()->getRequest();
		if ( $request->getCheck( $this->encodeParamName( 'filterredir' ) ) ) {
			$this->requireMaxOneParameter( $params, 'filterredir', 'redirect' );
		}

		if ( isset( $params['continue'] ) ) {
			$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'string', 'string', 'int', 'string' ] );
			$rand = $cont[0];
			$start = $cont[1];
			$startId = $cont[2];
			$end = $cont[3] ? $rand : null;
			$this->dieContinueUsageIf( !preg_match( '/^0\.\d+$/', $rand ) );
			$this->dieContinueUsageIf( !preg_match( '/^0\.\d+$/', $start ) );
			$this->dieContinueUsageIf( $cont[3] !== '0' && $cont[3] !== '1' );
		} else {
			$rand = wfRandom();
			$start = $rand;
			$startId = 0;
			$end = null;
		}

		// Set the non-continue if this is being used as a generator
		// (as a list it doesn't matter because lists never non-continue)
		if ( $resultPageSet !== null ) {
			$endFlag = $end === null ? 0 : 1;
			$this->getContinuationManager()->addGeneratorNonContinueParam(
				$this, 'continue', "$rand|$start|$startId|$endFlag"
			);
		}

		[ $left, $continue ] =
			$this->runQuery( $resultPageSet, $params['limit'], $start, $startId, $end );
		if ( $end === null && $continue === null ) {
			// Wrap around. We do this even if $left === 0 for continuation
			// (saving a DB query in this rare case probably isn't worth the
			// added code complexity it would require).
			$end = $rand;
			[ , $continue ] = $this->runQuery( $resultPageSet, $left, null, null, $end );
		}

		if ( $continue !== null ) {
			$endFlag = $end === null ? 0 : 1;
			$this->setContinueEnumParameter( 'continue', "$rand|$continue|$endFlag" );
		}

		if ( $resultPageSet === null ) {
			$this->getResult()->addIndexedTagName( [ 'query', $this->getModuleName() ], 'page' );
		}
	}

	/** @inheritDoc */
	public function getCacheMode( $params ) {
		return 'public';
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'namespace' => [
				ParamValidator::PARAM_TYPE => 'namespace',
				ParamValidator::PARAM_ISMULTI => true
			],
			'filterredir' => [
				ParamValidator::PARAM_TYPE => [ 'all', 'redirects', 'nonredirects' ],
				ParamValidator::PARAM_DEFAULT => 'nonredirects', // for BC
			],
			'minsize' => [
				ParamValidator::PARAM_TYPE => 'integer',
			],
			'maxsize' => [
				ParamValidator::PARAM_TYPE => 'integer',
			],
			'contentmodel' => [
				ParamValidator::PARAM_TYPE => $this->contentHandlerFactory->getContentModels(),
			],
			'redirect' => [
				ParamValidator::PARAM_DEPRECATED => true,
				ParamValidator::PARAM_DEFAULT => false,
			],
			'limit' => [
				ParamValidator::PARAM_TYPE => 'limit',
				ParamValidator::PARAM_DEFAULT => 1,
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => ApiBase::LIMIT_BIG1,
				IntegerDef::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue'
			],
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=query&list=random&rnnamespace=0&rnlimit=2'
				=> 'apihelp-query+random-example-simple',
			'action=query&generator=random&grnnamespace=0&grnlimit=2&prop=info'
				=> 'apihelp-query+random-example-generator',
			'action=query&list=random&rnnamespace=0&rnlimit=1&minsize=500'
				=> 'apihelp-query+random-example-minsize',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Random';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryRandom::class, 'ApiQueryRandom' );
