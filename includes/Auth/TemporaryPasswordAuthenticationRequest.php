<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Auth;

use MediaWiki\Language\RawMessage;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Password\PasswordFactory;

/**
 * This represents the intention to set a temporary password for the user.
 *
 * @stable to extend
 * @ingroup Auth
 * @since 1.27
 */
class TemporaryPasswordAuthenticationRequest extends AuthenticationRequest {
	/** @var string|null Temporary password */
	public $password;

	/** @var bool Email password to the user. */
	public $mailpassword = false;

	/** @var string Username or IP address of the caller */
	public $caller;

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getFieldInfo() {
		return [
			'mailpassword' => [
				'type' => 'checkbox',
				'label' => wfMessage( 'createaccountmail' ),
				'help' => wfMessage( 'createaccountmail-help' ),
			],
		];
	}

	/**
	 * @stable to call
	 * @param string|null $password
	 */
	public function __construct( $password = null ) {
		$this->password = $password;
		if ( $password ) {
			$this->mailpassword = true;
		}
	}

	/**
	 * Return an instance with a new, random password
	 * @return TemporaryPasswordAuthenticationRequest
	 */
	public static function newRandom() {
		$config = MediaWikiServices::getInstance()->getMainConfig();

		// get the min password length
		$minLength = 0;
		$policy = $config->get( MainConfigNames::PasswordPolicy );
		foreach ( $policy['policies'] as $p ) {
			foreach ( [ 'MinimalPasswordLength', 'MinimumPasswordLengthToLogin' ] as $check ) {
				$minLength = max( $minLength, $p[$check]['value'] ?? $p[$check] ?? 0 );
			}
		}

		$password = PasswordFactory::generateRandomPasswordString( $minLength );

		return new self( $password );
	}

	/**
	 * Return an instance with an invalid password
	 * @return TemporaryPasswordAuthenticationRequest
	 */
	public static function newInvalid() {
		return new self( null );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function describeCredentials() {
		return [
			'provider' => wfMessage( 'authmanager-provider-temporarypassword' ),
			'account' => new RawMessage( '$1', [ $this->username ] ),
		] + parent::describeCredentials();
	}

}
