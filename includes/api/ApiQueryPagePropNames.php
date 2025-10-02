<?php
/**
 * Copyright © 2013 Wikimedia Foundation and contributors
 *
 * @license GPL-2.0-or-later
 * @file
 * @since 1.21
 */

namespace MediaWiki\Api;

use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * A query module to list used page props
 *
 * @ingroup API
 * @since 1.21
 */
class ApiQueryPagePropNames extends ApiQueryBase {

	public function __construct( ApiQuery $query, string $moduleName ) {
		parent::__construct( $query, $moduleName, 'ppn' );
	}

	/** @inheritDoc */
	public function getCacheMode( $params ) {
		return 'public';
	}

	public function execute() {
		$params = $this->extractRequestParams();

		$this->addTables( 'page_props' );
		$this->addFields( 'pp_propname' );
		$this->addOption( 'DISTINCT' );
		$this->addOption( 'ORDER BY', 'pp_propname' );

		if ( $params['continue'] ) {
			$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'string' ] );
			// Add a WHERE clause
			$this->addWhereRange( 'pp_propname', 'newer', $cont[0], null );
		}

		$limit = $params['limit'];

		// mysql has issues with limit in loose index T115825
		if ( $this->getDB()->getType() !== 'mysql' ) {
			$this->addOption( 'LIMIT', $limit + 1 );
		}

		$result = $this->getResult();
		$count = 0;
		foreach ( $this->select( __METHOD__ ) as $row ) {
			if ( ++$count > $limit ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'continue', $row->pp_propname );
				break;
			}

			$vals = [];
			$vals['propname'] = $row->pp_propname;
			$fit = $result->addValue( [ 'query', $this->getModuleName() ], null, $vals );
			if ( !$fit ) {
				$this->setContinueEnumParameter( 'continue', $row->pp_propname );
				break;
			}
		}

		$result->addIndexedTagName( [ 'query', $this->getModuleName() ], 'p' );
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'limit' => [
				ParamValidator::PARAM_TYPE => 'limit',
				ParamValidator::PARAM_DEFAULT => 10,
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => ApiBase::LIMIT_BIG1,
				IntegerDef::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=query&list=pagepropnames'
				=> 'apihelp-query+pagepropnames-example-simple',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Pagepropnames';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryPagePropNames::class, 'ApiQueryPagePropNames' );
