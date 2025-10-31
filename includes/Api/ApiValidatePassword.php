<?php

namespace MediaWiki\Api;

use MediaWiki\Auth\AuthManager;
use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserRigorOptions;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * @ingroup API
 */
class ApiValidatePassword extends ApiBase {

	private AuthManager $authManager;
	private UserFactory $userFactory;

	public function __construct(
		ApiMain $mainModule,
		string $moduleName,
		AuthManager $authManager,
		UserFactory $userFactory
	) {
		parent::__construct( $mainModule, $moduleName );
		$this->authManager = $authManager;
		$this->userFactory = $userFactory;
	}

	public function execute() {
		$params = $this->extractRequestParams();

		$this->logFeatureUsage( 'action=validatepassword' );

		$this->requirePostedParameters( [ 'password' ] );

		if ( $params['user'] !== null ) {
			$user = $this->userFactory->newFromName(
				$params['user'],
				UserRigorOptions::RIGOR_CREATABLE
			);
			if ( !$user ) {
				$encParamName = $this->encodeParamName( 'user' );
				$this->dieWithError(
					[ 'apierror-baduser', $encParamName, wfEscapeWikiText( $params['user'] ) ],
					"baduser_{$encParamName}"
				);
			}

			if ( $user->isRegistered() || $this->authManager->userExists( $user->getName() ) ) {
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

	/** @inheritDoc */
	public function mustBePosted() {
		return true;
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'password' => [
				ParamValidator::PARAM_TYPE => 'password',
				ParamValidator::PARAM_REQUIRED => true
			],
			'user' => [
				ParamValidator::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'id' ],
			],
			'email' => null,
			'realname' => null,
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=validatepassword&password=foobar'
				=> 'apihelp-validatepassword-example-1',
			'action=validatepassword&password=querty&user=Example'
				=> 'apihelp-validatepassword-example-2',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Validatepassword';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiValidatePassword::class, 'ApiValidatePassword' );
