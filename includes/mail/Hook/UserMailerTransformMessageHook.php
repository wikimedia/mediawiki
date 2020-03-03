<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserMailerTransformMessageHook {
	/**
	 * Called in UserMailer::send() to change email after
	 * it has gone through the MIME transform. Extensions can block sending the email
	 * by returning false and setting $error.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $to array of MailAdresses of the targets
	 * @param ?mixed $from MailAddress of the sender
	 * @param ?mixed &$subject email subject (not MIME encoded)
	 * @param ?mixed &$headers email headers (except To: and Subject:) as an array of header
	 *   name => value pairs
	 * @param ?mixed &$body email body (in MIME format) as a string
	 * @param ?mixed &$error should be set to an error message string
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserMailerTransformMessage( $to, $from, &$subject, &$headers,
		&$body, &$error
	);
}
