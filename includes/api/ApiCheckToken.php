<?php
/**
 * Copyright © 2015 Wikimedia Foundation and contributors
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
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
