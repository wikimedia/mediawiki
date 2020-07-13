<?php

namespace MediaWiki\Hook;

use MailAddress;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UserMailerSplitToHook {
	/**
	 * This hook is called in UserMailer::send() to give extensions a chance
	 * to split up an email with multiple To: fields into separate emails.
	 *
	 * @since 1.35
	 *
	 * @param MailAddress[] &$to Unset the ones which should be mailed separately
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserMailerSplitTo( &$to );
}
