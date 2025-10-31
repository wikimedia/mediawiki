<?php
/**
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use Wikimedia\ParamValidator\ParamValidator;

/**
 * API Serialized PHP output formatter
 * @ingroup API
 */
class ApiFormatPhp extends ApiFormatBase {

	/** @inheritDoc */
	public function getMimeType() {
		return 'application/vnd.php.serialized';
	}

	public function execute() {
		$params = $this->extractRequestParams();

		switch ( $params['formatversion'] ) {
			case 1:
				$transforms = [
					'BC' => [],
					'Types' => [],
					'Strip' => 'all',
				];
				break;

			case 2:
			case 'latest':
				$transforms = [
					'Types' => [],
					'Strip' => 'all',
				];
				break;

			default:
				// Should have been caught during parameter validation
				self::dieDebug( __METHOD__, 'Unknown value for \'formatversion\'' );
		}
		$this->printText( serialize( $this->getResult()->getResultData( null, $transforms ) ) );
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return parent::getAllowedParams() + [
			'formatversion' => [
				ParamValidator::PARAM_TYPE => [ '1', '2', 'latest' ],
				ParamValidator::PARAM_DEFAULT => '1',
				ApiBase::PARAM_HELP_MSG => 'apihelp-php-param-formatversion',
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [
					'1' => 'apihelp-php-paramvalue-formatversion-1',
					'2' => 'apihelp-php-paramvalue-formatversion-2',
					'latest' => 'apihelp-php-paramvalue-formatversion-latest',
				],
			],
		];
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiFormatPhp::class, 'ApiFormatPhp' );
