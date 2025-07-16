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
 * Interface for sending arbitrary emails.
 *
 * Default implementation is MediaWiki\Mail\Emailer.
 *
 * @internal
 * @since 1.35
 * @ingroup Mail
 */
interface IEmailer {

	/**
	 * Sends emails to recipients
	 *
	 * @since 1.35
	 *
	 * @param MailAddress|MailAddress[] $to Array of Recipients' emails
	 * @param MailAddress $from Sender's email
	 * @param string $subject Email's subject.
	 * @param string $bodyText text part of body
	 * @param string|null $bodyHtml html part of body (optional)
	 * @param array $options Keys:
	 *     'replyTo' MailAddress
	 *     'contentType' string default 'text/plain; charset=UTF-8'
	 *     'headers' array Extra headers to set
	 *
	 * @phan-param array{replyTo?:?MailAddress,contentType?:string,headers?:array<string,string>} $options
	 * @return StatusValue
	 */
	public function send(
		$to,
		MailAddress $from,
		string $subject,
		string $bodyText,
		?string $bodyHtml = null,
		array $options = []
	): StatusValue;
}
