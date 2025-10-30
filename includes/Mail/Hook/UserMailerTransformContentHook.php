<?php

namespace MediaWiki\Hook;

use MediaWiki\Mail\MailAddress;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UserMailerTransformContent" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UserMailerTransformContentHook {
	/**
	 * This hook is called in UserMailer::send() to change email contents.
	 *
	 * @since 1.35
	 *
	 * @param MailAddress[] $to Array of addresses of the targets
	 * @param MailAddress $from Address of the sender
	 * @param string|array &$body Email body, either a string (for plaintext emails) or an array with
	 *   'text' and 'html' keys
	 * @param string &$error Error message
	 * @return bool|void True or no return value to continue, or false and set $error to
	 *   block sending the email
	 */
	public function onUserMailerTransformContent( $to, $from, &$body, &$error );
}
