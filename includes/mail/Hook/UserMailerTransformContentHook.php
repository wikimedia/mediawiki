<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserMailerTransformContentHook {
	/**
	 * Called in UserMailer::send() to change email
	 * contents. Extensions can block sending the email by returning false and setting
	 * $error.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $to array of MailAdresses of the targets
	 * @param ?mixed $from MailAddress of the sender
	 * @param ?mixed &$body email body, either a string (for plaintext emails) or an array with
	 *   'text' and 'html' keys
	 * @param ?mixed &$error should be set to an error message string
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserMailerTransformContent( $to, $from, &$body, &$error );
}
