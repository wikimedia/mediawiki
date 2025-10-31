<?php
/**
 * Copyright © 2012 Wikimedia Foundation and contributors
 *
 * @license GPL-2.0-or-later
 * @file
 * @since 1.21
 */

namespace MediaWiki\Api;

use MediaWiki\Title\Title;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * A query module to enumerate pages that use a particular prop
 *
 * @ingroup API
 * @since 1.21
 */
class ApiQueryPagesWithProp extends ApiQueryGeneratorBase {

	public function __construct( ApiQuery $query, string $moduleName ) {
		parent::__construct( $query, $moduleName, 'pwp' );
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
	 * @return void
	 */
	private function run( $resultPageSet = null ) {
		$params = $this->extractRequestParams();

		$prop = array_fill_keys( $params['prop'], true );
		$fld_ids = isset( $prop['ids'] );
		$fld_title = isset( $prop['title'] );
		$fld_value = isset( $prop['value'] );

		if ( $resultPageSet === null ) {
			$this->addFields( [ 'page_id' ] );
			$this->addFieldsIf( [ 'page_title', 'page_namespace' ], $fld_title );
			$this->addFieldsIf( 'pp_value', $fld_value );
		} else {
			$this->addFields( $resultPageSet->getPageTableFields() );
		}
		$this->addTables( [ 'page_props', 'page' ] );
		$this->addWhere( 'pp_page=page_id' );
		$this->addWhereFld( 'pp_propname', $params['propname'] );

		$dir = ( $params['dir'] == 'ascending' ) ? 'newer' : 'older';

		if ( $params['continue'] ) {
			$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'int' ] );
			// Add a WHERE clause
			$this->addWhereRange( 'pp_page', $dir, $cont[0], null );
		}

		$sort = ( $params['dir'] === 'descending' ? ' DESC' : '' );
		$this->addOption( 'ORDER BY', 'pp_page' . $sort );

		$limit = $params['limit'];
		$this->addOption( 'LIMIT', $limit + 1 );

		$result = $this->getResult();
		$count = 0;
		$res = $this->select( __METHOD__ );

		if ( $fld_title && $resultPageSet === null ) {
			$this->executeGenderCacheFromResultWrapper( $res, __METHOD__ );
		}

		foreach ( $res as $row ) {
			if ( ++$count > $limit ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'continue', $row->page_id );
				break;
			}

			if ( $resultPageSet === null ) {
				$vals = [
					ApiResult::META_TYPE => 'assoc',
				];
				if ( $fld_ids ) {
					$vals['pageid'] = (int)$row->page_id;
				}
				if ( $fld_title ) {
					$title = Title::makeTitle( $row->page_namespace, $row->page_title );
					ApiQueryBase::addTitleInfo( $vals, $title );
				}
				if ( $fld_value ) {
					$vals['value'] = $row->pp_value;
				}
				$fit = $result->addValue( [ 'query', $this->getModuleName() ], null, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'continue', $row->page_id );
					break;
				}
			} else {
				$resultPageSet->processDbRow( $row );
			}
		}

		if ( $resultPageSet === null ) {
			$result->addIndexedTagName( [ 'query', $this->getModuleName() ], 'page' );
		}
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'propname' => [
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
			],
			'prop' => [
				ParamValidator::PARAM_DEFAULT => 'ids|title',
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => [
					'ids',
					'title',
					'value',
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
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
			'dir' => [
				ParamValidator::PARAM_DEFAULT => 'ascending',
				ParamValidator::PARAM_TYPE => [
					'ascending',
					'descending',
				]
			],
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=query&list=pageswithprop&pwppropname=displaytitle&pwpprop=ids|title|value'
				=> 'apihelp-query+pageswithprop-example-simple',
			'action=query&generator=pageswithprop&gpwppropname=notoc&prop=info'
				=> 'apihelp-query+pageswithprop-example-generator',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Pageswithprop';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryPagesWithProp::class, 'ApiQueryPagesWithProp' );
