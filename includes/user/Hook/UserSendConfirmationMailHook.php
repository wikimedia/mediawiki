<?php

namespace MediaWiki\User\Hook;

use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UserSendConfirmationMailHook {
	/**
	 * This hook is called just before a confirmation email is sent to a user.
	 *
	 * Hook handlers can modify the email that will be sent.
	 *
	 * @since 1.35
	 *
	 * @param User $user The User for which the confirmation email is going to be sent
	 * @param array &$mail Associative array describing the email, with the following keys:
	 *   - subject: Subject line of the email
	 *   - body: Email body. Can be a string, or an array with keys 'text' and 'html'
	 *   - from: User object, or null meaning $wgPasswordSender will be used
	 *   - replyTo: MailAddress object or null
	 * @param array $info Associative array with additional information:
	 *   - type: 'created' if the user's account was just created; 'set' if the user
	 *     set an email address when they previously didn't have one; 'changed' if
	 *     the user had an email address and changed it
	 *   - ip: The IP address from which the user set/changed their email address
	 *   - confirmURL: URL the user should visit to confirm their email
	 *   - invalidateURL: URL the user should visit to invalidate confirmURL
	 *   - expiration: time and date when confirmURL expires
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserSendConfirmationMail( $user, &$mail, $info );
}
