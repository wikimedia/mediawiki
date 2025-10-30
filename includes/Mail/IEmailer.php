<?php
/**
 * @license GPL-2.0-or-later
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
