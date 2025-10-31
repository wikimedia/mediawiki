<?php
/**
 * Copyright © 2015 Wikimedia Foundation and contributors
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Session\Token;
use MediaWiki\Utils\MWTimestamp;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * @since 1.25
 * @ingroup API
 */
class ApiCheckToken extends ApiBase {

	public function execute() {
		$params = $this->extractRequestParams();
		$token = $params['token'];
		$maxage = $params['maxtokenage'];
		$salts = ApiQueryTokens::getTokenTypeSalts();

		$res = [];

		$tokenObj = ApiQueryTokens::getToken(
			$this->getUser(), $this->getRequest()->getSession(), $salts[$params['type']]
		);

		if ( str_ends_with( $token, urldecode( Token::SUFFIX ) ) ) {
			$this->addWarning( 'apiwarn-checktoken-percentencoding' );
		}

		if ( $tokenObj->match( $token, $maxage ) ) {
			$res['result'] = 'valid';
		} elseif ( $maxage !== null && $tokenObj->match( $token ) ) {
			$res['result'] = 'expired';
		} else {
			$res['result'] = 'invalid';
		}

		$ts = Token::getTimestamp( $token );
		if ( $ts !== null ) {
			$mwts = new MWTimestamp();
			$mwts->timestamp->setTimestamp( $ts );
			$res['generated'] = $mwts->getTimestamp( TS_ISO_8601 );
		}

		$this->getResult()->addValue( null, $this->getModuleName(), $res );
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'type' => [
				ParamValidator::PARAM_TYPE => array_keys( ApiQueryTokens::getTokenTypeSalts() ),
				ParamValidator::PARAM_REQUIRED => true,
			],
			'token' => [
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
				ParamValidator::PARAM_SENSITIVE => true,
			],
			'maxtokenage' => [
				ParamValidator::PARAM_TYPE => 'integer',
			],
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=checktoken&type=csrf&token=123ABC'
				=> 'apihelp-checktoken-example-simple',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Checktoken';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiCheckToken::class, 'ApiCheckToken' );
