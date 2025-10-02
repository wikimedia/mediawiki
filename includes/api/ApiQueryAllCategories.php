<?php
/**
 * Copyright © 2007 Roan Kattouw <roan.kattouw@gmail.com>
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * Query module to enumerate all categories, even the ones that don't have
 * category pages.
 *
 * @ingroup API
 */
class ApiQueryAllCategories extends ApiQueryCategoryList {

	public function __construct( ApiQuery $query, string $moduleName ) {
		parent::__construct( $query, $moduleName, 'ac' );
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
		$db = $this->getDB();
		$params = $this->extractRequestParams();

		$this->createQuery( $db, $params );
		$this->executeQuery( $params, $resultPageSet );
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'from' => null,
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'to' => null,
			'prefix' => null,
			'dir' => [
				ParamValidator::PARAM_DEFAULT => 'ascending',
				ParamValidator::PARAM_TYPE => [
					'ascending',
					'descending'
				],
			],
			'min' => [
				ParamValidator::PARAM_TYPE => 'integer'
			],
			'max' => [
				ParamValidator::PARAM_TYPE => 'integer'
			],
			'limit' => [
				ParamValidator::PARAM_DEFAULT => 10,
				ParamValidator::PARAM_TYPE => 'limit',
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => ApiBase::LIMIT_BIG1,
				IntegerDef::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'prop' => [
				ParamValidator::PARAM_TYPE => [ 'size', 'hidden' ],
				ParamValidator::PARAM_DEFAULT => '',
				ParamValidator::PARAM_ISMULTI => true,
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=query&list=allcategories&acprop=size'
				=> 'apihelp-query+allcategories-example-size',
			'action=query&generator=allcategories&gacprefix=List&prop=info'
				=> 'apihelp-query+allcategories-example-generator',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Allcategories';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryAllCategories::class, 'ApiQueryAllCategories' );
