<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Mail;

use StatusValue;

/**
 * Send arbitrary emails.
 *
 * Obtain instance via ServiceWiring. This service is intended to eventually
 * replace the stable (but static) functions of the UserMailer class.
 *
 * Use of this class is discouraged in favour of higher-level abstractions
 * whenever possible:
 *
 * - NotificationService with RecentChangeNotification: Format and send emails to watchers about a recent change.
 * - EmailUserFactory: Format and send emails between two wiki users.
 *
 * @since 1.35
 * @ingroup Mail
 */
class Emailer implements IEmailer {

	/**
	 * @since 1.35
	 *
	 * This function will perform a direct (authenticated) login to
	 * a SMTP Server to use for mail relaying if 'wgSMTP' specifies an
	 * array of parameters. It requires PEAR:Mail to do that.
	 * Otherwise it just uses the standard PHP 'mail' function.
	 *
	 * @inheritDoc
	 */
	public function send(
		$to,
		MailAddress $from,
		string $subject,
		string $bodyText,
		?string $bodyHtml = null,
		array $options = []
	): StatusValue {
		$body = $bodyHtml ? [ 'text' => $bodyText, 'html' => $bodyHtml ] : $bodyText;
		return UserMailer::send( $to, $from, $subject, $body, $options );
	}
}
