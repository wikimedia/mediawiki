<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserMailerSplitToHook {
	/**
	 * Called in UserMailer::send() to give extensions a chance
	 * to split up an email with multiple the To: field into separate emails.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$to array of MailAddress objects; unset the ones which should be mailed
	 *   separately
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserMailerSplitTo( &$to );
}
