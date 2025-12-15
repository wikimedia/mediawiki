<?php
declare( strict_types = 1 );

namespace MediaWiki\Api;

use MediaWiki\Language\LanguageNameSearch;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Language name search API
 *
 * Copyright (C) 2012 Alolita Sharma, Amir Aharoni, Arun Ganesh, Brandon Harris,
 * Niklas Laxström, Pau Giner, Santhosh Thottingal, Siebrand Mazeland and other
 * contributors.
 *
 * @license GPL-2.0-or-later
 * @ingroup API
 */
class ApiLanguageSearch extends ApiBase {
	private LanguageNameSearch $languageNameSearch;

	public function __construct(
		ApiMain $main,
		string $action,
		LanguageNameSearch $languageNameSearch
	) {
		parent::__construct( $main, $action );
		$this->languageNameSearch = $languageNameSearch;
	}

	/**
	 * @inheritDoc
	 */
	public function execute() {
		$params = $this->extractRequestParams();
		$search = $params['search'];
		$typos = $params['typos'];
		$searches = $this->languageNameSearch->doSearch( $search, $typos, $this->getLanguage()->getCode() );
		$result = $this->getResult();
		$result->addValue( null, $this->getModuleName(), $searches );
	}

	/**
	 * @inheritDoc
	 */
	public function getAllowedParams(): array {
		return [
			'search' => [
				ParamValidator::PARAM_REQUIRED => true
			],
			'typos' => [
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_DEFAULT => 1
			],
		];
	}

	/**
	 * @inheritDoc
	 */
	protected function getExamplesMessages(): array {
		return [
			'action=languagesearch&search=Te'
				=> 'apihelp-languagesearch-example-1',
			'action=languagesearch&search=ഫി'
				=> 'apihelp-languagesearch-example-2',
			'action=languagesearch&search=ഫി&typos=1'
				=> 'apihelp-languagesearch-example-3',
		];
	}

	/**
	 * @inheritDoc
	 */
	public function getHelpUrls(): string {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Languagesearch';
	}
}
