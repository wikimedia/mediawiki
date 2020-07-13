<?php

namespace MediaWiki\Hook;

use MailAddress;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UserMailerTransformMessageHook {
	/**
	 * This hook is called in UserMailer::send() to change email after
	 * it has gone through the MIME transform.
	 *
	 * @since 1.35
	 *
	 * @param MailAddress[] $to Array of addresses of the targets
	 * @param MailAddress $from Address of the sender
	 * @param string &$subject Email subject (not MIME encoded)
	 * @param array &$headers Email headers (except To: and Subject:) as an array of header
	 *   name => value pairs
	 * @param string &$body email body (in MIME format)
	 * @param string &$error Error message
	 * @return bool|void True or no return value to continue, or false and set $error to
	 *   block sending the email
	 */
	public function onUserMailerTransformMessage( $to, $from, &$subject, &$headers,
		&$body, &$error
	);
}
