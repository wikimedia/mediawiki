<?php

namespace MediaWiki\Mail\Hook;

use MediaWiki\Permissions\Authority;
use StatusValue;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "EmailUserAuthorizeSend" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 * @since 1.41
 */
interface EmailUserAuthorizeSendHook {
	/**
	 * This hook is called when checking whether a user is allowed to send emails.
	 *
	 * @param Authority $sender
	 * @param StatusValue $status Add any error here
	 * @return bool|void True or no return value to continue, false to abort, which also requires adding
	 * a fatal error to $status.
	 */
	public function onEmailUserAuthorizeSend( Authority $sender, StatusValue $status );
}
