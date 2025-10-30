<?php

namespace MediaWiki\Hook;

use MediaWiki\Mail\MailAddress;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UserMailerChangeReturnPath" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UserMailerChangeReturnPathHook {
	/**
	 * This hook is called to generate a VERP return address
	 * when UserMailer sends an email, with a bounce handling extension.
	 *
	 * @since 1.35
	 *
	 * @param MailAddress[] $to Array of MailAddress objects for the recipients
	 * @param string &$returnPath Return address
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserMailerChangeReturnPath( $to, &$returnPath );
}
