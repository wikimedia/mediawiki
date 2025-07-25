<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
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
