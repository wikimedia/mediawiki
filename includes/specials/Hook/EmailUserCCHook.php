<?php

namespace MediaWiki\Hook;

use MediaWiki\Mail\MailAddress;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "EmailUserCC" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface EmailUserCCHook {
	/**
	 * This hook is called before sending the copy of the email to the author.
	 *
	 * @since 1.35
	 *
	 * @param MailAddress &$to MailAddress object of receiving user
	 * @param MailAddress &$from MailAddress object of sending user
	 * @param string &$subject Subject of the mail
	 * @param string &$text Text of the mail
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEmailUserCC( &$to, &$from, &$subject, &$text );
}
