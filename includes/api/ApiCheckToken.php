<?php
/**
 * Created on Jan 29, 2015
 *
 * Copyright © 2015 Brad Jorsch bjorsch@wikimedia.org
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
		if ( $tokenObj->match( $token, $maxage ) ) {
			$res['result'] = 'valid';
		} elseif ( $maxage !== null && $tokenObj->match( $token ) ) {
			$res['result'] = 'expired';
		} else {
			$res['result'] = 'invalid';
		}

		$ts = MediaWiki\Session\Token::getTimestamp( $token );
		if ( $ts !== null ) {
			$mwts = new MWTimestamp();
			$mwts->timestamp->setTimestamp( $ts );
			$res['generated'] = $mwts->getTimestamp( TS_ISO_8601 );
		}

		$this->getResult()->addValue( null, $this->getModuleName(), $res );
	}

	public function getAllowedParams() {
		return [
			'type' => [
				ApiBase::PARAM_TYPE => array_keys( ApiQueryTokens::getTokenTypeSalts() ),
				ApiBase::PARAM_REQUIRED => true,
			],
			'token' => [
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true,
				ApiBase::PARAM_SENSITIVE => true,
			],
			'maxtokenage' => [
				ApiBase::PARAM_TYPE => 'integer',
			],
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=checktoken&type=csrf&token=123ABC'
				=> 'apihelp-checktoken-example-simple',
		];
	}
}
