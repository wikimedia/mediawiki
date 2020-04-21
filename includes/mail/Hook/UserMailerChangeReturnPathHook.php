<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserMailerChangeReturnPathHook {
	/**
	 * Called to generate a VERP return address
	 * when UserMailer sends an email, with a bounce handling extension.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $to Array of MailAddress objects for the recipients
	 * @param ?mixed &$returnPath The return address string
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserMailerChangeReturnPath( $to, &$returnPath );
}
