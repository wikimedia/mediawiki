<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\ParamValidator\TypeDef\UserDef;

/**
 * @ingroup API
 */
class ApiValidatePassword extends ApiBase {

	public function execute() {
		$params = $this->extractRequestParams();

		// For sanity
		$this->requirePostedParameters( [ 'password' ] );

		if ( $params['user'] !== null ) {
			$user = User::newFromName( $params['user'], 'creatable' );
			if ( !$user ) {
				$encParamName = $this->encodeParamName( 'user' );
				$this->dieWithError(
					[ 'apierror-baduser', $encParamName, wfEscapeWikiText( $params['user'] ) ],
					"baduser_{$encParamName}"
				);
			}

			if ( !$user->isAnon() ||
				MediaWikiServices::getInstance()->getAuthManager()->userExists( $user->getName() )
			) {
				$this->dieWithError( 'userexists' );
			}

			$user->setEmail( (string)$params['email'] );
			$user->setRealName( (string)$params['realname'] );
		} else {
			$user = $this->getUser();
		}

		$r = [];
		$validity = $user->checkPasswordValidity( $params['password'] );
		$r['validity'] = $validity->isGood() ? 'Good' : ( $validity->isOK() ? 'Change' : 'Invalid' );
		$messages = array_merge(
			$this->getErrorFormatter()->arrayFromStatus( $validity, 'error' ),
			$this->getErrorFormatter()->arrayFromStatus( $validity, 'warning' )
		);
		if ( $messages ) {
			$r['validitymessages'] = $messages;
		}

		$this->getHookRunner()->onApiValidatePassword( $this, $r );

		$this->getResult()->addValue( null, $this->getModuleName(), $r );
	}

	public function mustBePosted() {
		return true;
	}

	public function getAllowedParams() {
		return [
			'password' => [
				ApiBase::PARAM_TYPE => 'password',
				ApiBase::PARAM_REQUIRED => true
			],
			'user' => [
				ApiBase::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'id' ],
			],
			'email' => null,
			'realname' => null,
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=validatepassword&password=foobar'
				=> 'apihelp-validatepassword-example-1',
			'action=validatepassword&password=querty&user=Example'
				=> 'apihelp-validatepassword-example-2',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Validatepassword';
	}
}
