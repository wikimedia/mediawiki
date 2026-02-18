<?php

namespace MediaWiki\Mail\NotificationEmail;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Context\IContextSource;
use MediaWiki\Mail\IEmailer;
use MediaWiki\Mail\MailAddress;
use MediaWiki\MainConfigNames;
use MediaWiki\User\User;
use MediaWiki\Utils\UrlUtils;
use StatusValue;
use Wikimedia\ObjectCache\BagOStuff;

/**
 * Sends notification emails to the old address when a user changes or removes their email.
 *
 * Used when the user previously had a confirmed email and is now changing it.
 * The notification is sent to the old address to alert the user.
 */
class NotificationEmailSender {

	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::PasswordSender,
		MainConfigNames::AllowHTMLEmail,
	];

	public function __construct(
		private readonly ServiceOptions $options,
		private readonly IEmailer $emailer,
		private readonly BagOStuff $cache,
		private readonly UrlUtils $urlUtils
	) {
		$this->options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
	}

	/**
	 * Send an email to the old address notifying about the email change.
	 *
	 * @param IContextSource $ctx Request context (for IP, messages, etc.)
	 * @param User $recipientUser The user whose email is being changed (recipient = their old address)
	 * @param string $change Either 'changed' or 'removed'
	 * @param string $newEmail The new email address (only used when $change === 'changed')
	 * @return StatusValue
	 */
	public function sendNotificationMail(
		IContextSource $ctx,
		User $recipientUser,
		string $change,
		string $newEmail = ''
	): StatusValue {
		$allowHtml = $this->options->get( MainConfigNames::AllowHTMLEmail );

		if ( $allowHtml ) {
			$builder = new HTMLNotificationEmailBuilder( $ctx, $this->cache, $this->urlUtils );
			if ( $change === 'changed' ) {
				$emailContent = $builder->buildNotificationEmailChanged( $recipientUser->getName(), $newEmail );
			} else {
				$emailContent = $builder->buildNotificationEmailRemoved( $recipientUser->getName() );
			}

			return $this->sendEmail(
				$recipientUser,
				$ctx,
				$emailContent['subject'],
				$emailContent['body']['text'],
				$emailContent['body']['html']
			);
		}

		$subject = $ctx->msg( 'notificationemail_subject_' . $change )->text();
		$bodyText = $ctx->msg(
			'notificationemail_body_' . $change,
			$ctx->getRequest()->getIP(),
			$recipientUser->getName(),
			$newEmail
		)->text();

		return $this->sendEmail( $recipientUser, $ctx, $subject, $bodyText );
	}

	private function sendEmail(
		User $recipientUser,
		IContextSource $ctx,
		string $subject,
		string $bodyText,
		?string $bodyHtml = null
	): StatusValue {
		$sender = new MailAddress(
			$this->options->get( MainConfigNames::PasswordSender ),
			$ctx->msg( 'emailsender' )->inContentLanguage()->text()
		);

		return $this->emailer->send(
			[ MailAddress::newFromUser( $recipientUser ) ],
			$sender,
			$subject,
			$bodyText,
			$bodyHtml
		);
	}
}
