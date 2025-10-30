<?php

namespace MediaWiki\Mail\Hook;

use MediaWiki\Mail\MailAddress;
use MediaWiki\Mail\UserEmailContact;
use MediaWiki\Permissions\Authority;
use StatusValue;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "EmailUserSendEmail" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 * @since 1.41
 */
interface EmailUserSendEmailHook {
	/**
	 * This hook is called before sending an email, when all other checks have succeeded.
	 *
	 * @param Authority $from
	 * @param MailAddress $fromAddress MailAddress of the sender
	 * @param UserEmailContact $to
	 * @param MailAddress $toAddress MailAddress of the target
	 * @param string $subject
	 * @param string $text
	 * @param StatusValue $status Add any error here
	 * @return bool|void True or no return value to continue. To abort, return false and add a fatal error to $status.
	 */
	public function onEmailUserSendEmail(
		Authority $from,
		MailAddress $fromAddress,
		UserEmailContact $to,
		MailAddress $toAddress,
		string $subject,
		string $text,
		StatusValue $status
	);
}
