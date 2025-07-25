<?php

namespace MediaWiki\Api;

use MediaWiki\ResourceLoader\CodexModule;
use Wikimedia\ParamValidator\ParamValidator;

class ApiQueryCodexIcons extends ApiQueryBase {

	public function execute() {
		$params = $this->extractRequestParams();
		$iconNames = $params['names'];

		$icons = CodexModule::getIcons( null, $this->getConfig(), $iconNames );

		$result = $this->getResult();
		$result->addValue( 'query', $this->getModuleName(), $icons );
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	/**
	 * No database interaction, so maxlag check is irrelevant
	 * @return bool
	 */
	public function shouldCheckMaxlag() {
		return false;
	}

	public function getAllowedParams() {
		return [
			'names' => [
				ParamValidator::PARAM_TYPE => array_keys( CodexModule::getIcons( null, $this->getConfig() ) ),
				ParamValidator::PARAM_REQUIRED => true,
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_ALL => true,
			]
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=query&list=codexicons&names=cdxIconInfo|cdxIconTrash' =>
				'apihelp-query+codexicons-example',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:CodexIcons';
	}
}
