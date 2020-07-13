<?php

namespace MediaWiki\Hook;

use MailAddress;

/**
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
