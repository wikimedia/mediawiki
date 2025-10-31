<?php
/**
 * Module to fetch tokens via action=query&meta=tokens
 *
 * Copyright © 2014 Wikimedia Foundation and contributors
 *
 * @license GPL-2.0-or-later
 * @file
 * @since 1.24
 */

namespace MediaWiki\Api;

use MediaWiki\MediaWikiServices;
use MediaWiki\User\User;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Module to fetch tokens via action=query&meta=tokens
 *
 * @ingroup API
 * @since 1.24
 */
class ApiQueryTokens extends ApiQueryBase {

	public function execute() {
		$params = $this->extractRequestParams();

		if ( $this->lacksSameOriginSecurity() ) {
			$this->addWarning( [ 'apiwarn-tokens-origin' ] );
			return;
		}

		$user = $this->getUser();
		$session = $this->getRequest()->getSession();
		$salts = self::getTokenTypeSalts();

		$done = [];
		$path = [ 'query', $this->getModuleName() ];
		$this->getResult()->addArrayType( $path, 'assoc' );

		foreach ( $params['type'] as $type ) {
			$token = self::getToken( $user, $session, $salts[$type] )->toString();
			$fit = $this->getResult()->addValue( $path, $type . 'token', $token );

			if ( !$fit ) {
				// Abuse type as a query-continue parameter and set it to all unprocessed types
				$this->setContinueEnumParameter( 'type',
					array_diff( $params['type'], $done ) );
				break;
			}
			$done[] = $type;
		}
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
			$hookContainer = MediaWikiServices::getInstance()->getHookContainer();
			$hookRunner = new ApiHookRunner( $hookContainer );
			$hookRunner->onApiQueryTokensRegisterTypes( $salts );
			ksort( $salts );
		}

		return $salts;
	}

	/**
	 * Get a token from a salt
	 * @param User $user
	 * @param \MediaWiki\Session\Session $session
	 * @param string|array $salt A string will be used as the salt for
	 *  User::getEditTokenObject() to fetch the token, which will give a
	 *  LoggedOutEditToken (always "+\\") for anonymous users. An array will
	 *  be used as parameters to MediaWiki\Session\Session::getToken(), which
	 *  will always return a full token even for anonymous users. An array will
	 *  also persist the session.
	 * @return \MediaWiki\Session\Token
	 */
	public static function getToken( User $user, \MediaWiki\Session\Session $session, $salt ) {
		if ( is_array( $salt ) ) {
			$token = $session->getToken( ...$salt );
		} else {
			$token = $user->getEditTokenObject( $salt, $session->getRequest() );
		}
		if ( $token->wasNew() ) {
			$session->persist();
		}
		return $token;
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'type' => [
				ParamValidator::PARAM_DEFAULT => 'csrf',
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => array_keys( self::getTokenTypeSalts() ),
				ParamValidator::PARAM_ALL => true,
			],
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=query&meta=tokens'
				=> 'apihelp-query+tokens-example-simple',
			'action=query&meta=tokens&type=watch|patrol'
				=> 'apihelp-query+tokens-example-types',
		];
	}

	/** @inheritDoc */
	public function isReadMode() {
		// So login tokens can be fetched on private wikis
		return false;
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Tokens';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryTokens::class, 'ApiQueryTokens' );
