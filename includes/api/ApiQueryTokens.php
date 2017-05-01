<?php
/**
 * Module to fetch tokens via action=query&meta=tokens
 *
 * Created on August 8, 2014
 *
 * Copyright © 2014 Brad Jorsch bjorsch@wikimedia.org
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
 * @since 1.24
 */

/**
 * Module to fetch tokens via action=query&meta=tokens
 *
 * @ingroup API
 * @since 1.24
 */
class ApiQueryTokens extends ApiQueryBase {

	public function execute() {
		$params = $this->extractRequestParams();
		$res = [
			ApiResult::META_TYPE => 'assoc',
		];

		if ( $this->lacksSameOriginSecurity() ) {
			$this->setWarning( 'Tokens may not be obtained when the same-origin policy is not applied' );
			return;
		}

		$user = $this->getUser();
		$session = $this->getRequest()->getSession();
		$salts = self::getTokenTypeSalts();
		foreach ( $params['type'] as $type ) {
			$res[$type . 'token'] = self::getToken( $user, $session, $salts[$type] )->toString();
		}

		$this->getResult()->addValue( 'query', $this->getModuleName(), $res );
	}

	/**
	 * Get the salts for known token types
	 * @return (string|array)[] Returning a string will use that as the salt
	 *  for User::getEditTokenObject() to fetch the token, which will give a
	 *  LoggedOutEditToken (always "+\\") for anonymous users. Returning an
	 *  array will use it as parameters to MediaWiki\Session\Session::getToken(),
	 *  which will always return a full token even for anonymous users.
	 */
	public static function getTokenTypeSalts() {
		static $salts = null;
		if ( !$salts ) {
			$salts = [
				'csrf' => '',
				'watch' => 'watch',
				'patrol' => 'patrol',
				'rollback' => 'rollback',
				'userrights' => 'userrights',
				'login' => [ '', 'login' ],
				'createaccount' => [ '', 'createaccount' ],
			];
			Hooks::run( 'ApiQueryTokensRegisterTypes', [ &$salts ] );
			ksort( $salts );
		}

		return $salts;
	}

	/**
	 * Get a token from a salt
	 * @param User $user
	 * @param MediaWiki\Session\Session $session
	 * @param string|array $salt A string will be used as the salt for
	 *  User::getEditTokenObject() to fetch the token, which will give a
	 *  LoggedOutEditToken (always "+\\") for anonymous users. An array will
	 *  be used as parameters to MediaWiki\Session\Session::getToken(), which
	 *  will always return a full token even for anonymous users. An array will
	 *  also persist the session.
	 * @return MediaWiki\Session\Token
	 */
	public static function getToken( User $user, MediaWiki\Session\Session $session, $salt ) {
		if ( is_array( $salt ) ) {
			$session->persist();
			return call_user_func_array( [ $session, 'getToken' ], $salt );
		} else {
			return $user->getEditTokenObject( $salt, $session->getRequest() );
		}
	}

	public function getAllowedParams() {
		return [
			'type' => [
				ApiBase::PARAM_DFLT => 'csrf',
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => array_keys( self::getTokenTypeSalts() ),
			],
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=query&meta=tokens'
				=> 'apihelp-query+tokens-example-simple',
			'action=query&meta=tokens&type=watch|patrol'
				=> 'apihelp-query+tokens-example-types',
		];
	}

	public function isReadMode() {
		// So login tokens can be fetched on private wikis
		return false;
	}

	public function getCacheMode( $params ) {
		return 'private';
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Tokens';
	}
}
