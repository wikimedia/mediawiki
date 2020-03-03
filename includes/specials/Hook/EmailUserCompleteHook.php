<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EmailUserCompleteHook {
	/**
	 * After sending email from one user to another.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $to MailAddress object of receiving user
	 * @param ?mixed $from MailAddress object of sending user
	 * @param ?mixed $subject subject of the mail
	 * @param ?mixed $text text of the mail
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEmailUserComplete( $to, $from, $subject, $text );
}
