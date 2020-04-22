<?php

namespace MediaWiki\Hook;

use MailAddress;
use Status;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EmailUserHook {
	/**
	 * This hook is called before sending email from one user to another.
	 *
	 * @since 1.35
	 *
	 * @param MailAddress &$to MailAddress object of receiving user
	 * @param MailAddress &$from MailAddress object of sending user
	 * @param MailAddress &$subject subject of the mail
	 * @param MailAddress &$text text of the mail
	 * @param bool|Status &$error Out-param for an error. Should be set to a Status object or boolean
	 *   false.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEmailUser( &$to, &$from, &$subject, &$text, &$error );
}
