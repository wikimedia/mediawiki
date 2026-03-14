<?php

namespace MediaWiki\Exception;

use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MediaWikiServices;

/**
 * Helper functions for the login errors
 * @since 1.46
 */
class LoginErrorHelper {

	/**
	 * @var string[]
	 */
	private static $validErrorMessages = [
		'exception-nologin-text',
		'exception-nologin-text-for-temp-user',
		'watchlistanontext',
		'watchlistanontext-for-temp-user',
		'watchlistlabels-not-logged-in',
		'watchlistlabels-not-logged-in-for-temp-user',
		'changeemail-no-info',
		'confirmemail_needlogin',
		'prefsnologintext2',
		'prefsnologintext2-for-temp-user',
		'specialmute-login-required',
		'specialmute-login-required-for-temp-user',
		'mailnologintext',
	];

	/** @var array|null Cache for {@link LoginErrorHelper::getValidErrorMessages} */
	private static ?array $validErrorMessagesCache = null;

	/**
	 * Returns an array of all error and warning messages that can be displayed on Special:UserLogin or
	 * Special:CreateAccount through the ?error or ?warning GET parameters.
	 *
	 * Special:UserLogin and Special:CreateAccount can show an error or warning message on the form when
	 * coming from another page using the aforementioned GET parameters.
	 *
	 * Further keys can be added by the LoginFormValidErrorMessages hook. If a message key is not in this
	 * list, then no redirect will be performed to Special:UserLogin or Special:CreateAccount.
	 *
	 * @return string[]
	 * @see LoginErrorHelper::$validErrorMessages
	 */
	public static function getValidErrorMessages(): array {
		if ( !static::$validErrorMessagesCache ) {
			static::$validErrorMessagesCache = self::$validErrorMessages;
			( new HookRunner( MediaWikiServices::getInstance()->getHookContainer() ) )
				->onLoginFormValidErrorMessages( static::$validErrorMessagesCache );
		}

		return static::$validErrorMessagesCache;
	}
}
