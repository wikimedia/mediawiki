<?php

namespace MediaWiki\Hook;

use MailAddress;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface AlternateUserMailerHook {
	/**
	 * This hook is called before mail is sent so that mail could be logged
	 * (or something else) instead of using PEAR or PHP's mail().
	 *
	 * @since 1.35
	 *
	 * @param array $headers Associative array of headers for the email
	 * @param MailAddress|array $to To address
	 * @param MailAddress $from From address
	 * @param string $subject Subject of the email
	 * @param string $body Body of the message
	 * @return bool|void True or no return value to continue sending email in the
	 *   regular way, or false to skip the regular method of sending mail. Return a string
	 *   to return a php-mail-error message containing the error.
	 */
	public function onAlternateUserMailer( $headers, $to, $from, $subject, $body );
}
