<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface AlternateUserMailerHook {
	/**
	 * Called before mail is sent so that mail could be logged
	 * (or something else) instead of using PEAR or PHP's mail(). Return false to skip
	 * the regular method of sending mail.  Return a string to return a php-mail-error
	 * message containing the error. Returning true will continue with sending email
	 * in the regular way.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $headers Associative array of headers for the email
	 * @param ?mixed $to MailAddress object or array
	 * @param ?mixed $from From address
	 * @param ?mixed $subject Subject of the email
	 * @param ?mixed $body Body of the message
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAlternateUserMailer( $headers, $to, $from, $subject, $body );
}
