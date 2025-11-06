<?php

namespace MediaWiki\Hook;

use MediaWiki\Mail\MailAddress;
use MediaWiki\Status\Status;
use Wikimedia\Message\MessageSpecifier;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "EmailUser" to register handlers implementing this interface.
 *
 * @ingroup Hooks
 * @deprecated since 1.41 Handle the EmailUserSendEmail hook instead.
 */
interface EmailUserHook {
	/**
	 * This hook is called before sending email from one user to another.
	 *
	 * @since 1.35
	 *
	 * @param MailAddress &$to MailAddress object of receiving user
	 * @param MailAddress &$from MailAddress object of sending user
	 * @param string &$subject subject of the mail
	 * @param string &$text text of the mail
	 * @param bool|Status|MessageSpecifier|array &$error Out-param for an error.
	 *   Should be set to a Status object or boolean false.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEmailUser( &$to, &$from, &$subject, &$text, &$error );
}
