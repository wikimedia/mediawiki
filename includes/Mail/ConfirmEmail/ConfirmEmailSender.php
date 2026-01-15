<?php

namespace MediaWiki\Mail\ConfirmEmail;

use LogicException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Context\IContextSource;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Language\MessageLocalizer;
use MediaWiki\Mail\IEmailer;
use MediaWiki\Mail\MailAddress;
use MediaWiki\MainConfigNames;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use StatusValue;

/**
 * Wrapper for IEmailer for sending confirm email sender
 */
class ConfirmEmailSender {

	public const EMAIL_TYPE_CREATED = 'created';
	public const EMAIL_TYPE_CHANGED = 'changed';
	public const EMAIL_TYPE_SET = 'set';

	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::PasswordSender,
	];

	public function __construct(
		private readonly ServiceOptions $options,
		private readonly HookRunner $hookRunner,
		private readonly UserFactory $userFactory,
		private readonly IEmailer $emailer,
		private readonly ConfirmEmailBuilderFactory $confirmEmailBuilderFactory
	) {
		$this->options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
	}

	private function buildEmailByType(
		IContextSource $ctx,
		string $type, ConfirmEmailData $data
	): ConfirmEmailContent {
		$builder = $this->confirmEmailBuilderFactory->newFromContext( $ctx );
		return match ( $type ) {
			self::EMAIL_TYPE_CREATED => $builder->buildEmailCreated( $data ),
			self::EMAIL_TYPE_CHANGED => $builder->buildEmailChanged( $data ),
			self::EMAIL_TYPE_SET => $builder->buildEmailSet( $data ),
			default => throw new LogicException(
				'$type "' . $type . '" is not any of the supported types'
			)
		};
	}

	/**
	 * Send the built email to the recipient
	 *
	 * Uses PasswordSender as the sender (with `emailsender` as the human-readable name).
	 *
	 * @see MainConfigNames::PasswordSender
	 */
	private function sendEmailToRecipient(
		MessageLocalizer $localizer,
		User $recipientUser,
		ConfirmEmailContent $emailContent
	): StatusValue {
		$sender = new MailAddress(
			$this->options->get( MainConfigNames::PasswordSender ),
			$localizer->msg( 'emailsender' )->inContentLanguage()->text()
		);

		return $this->emailer->send(
			[ MailAddress::newFromUser( $recipientUser ) ],
			$sender,
			$emailContent->getSubject(),
			$emailContent->getPlaintext(),
			$emailContent->getHtml()
		);
	}

	/**
	 * Send the email address confirmation message
	 *
	 * This method:
	 * (1) Calls the builder to build the email message
	 * (2) Runs onUserSendConfirmationMail to allow extensions to modify the email
	 * (3) Calls IEmailer to actually send the message out
	 */
	public function sendConfirmationMail(
		IContextSource $ctx,
		string $type, ConfirmEmailData $data
	): StatusValue {
		$emailContent = $this->buildEmailByType( $ctx, $type, $data );
		$recipientUser = $this->userFactory->newFromUserIdentity( $data->getRecipientUser() );

		$emailAsArray = $emailContent->toArray();
		$this->hookRunner->onUserSendConfirmationMail(
			$recipientUser,
			$emailAsArray,
			[
				'type' => $type,
				'ip' => $ctx->getRequest()->getIP(),
				'confirmURL' => $data->getConfirmationUrl(),
				'invalidateURL' => $data->getInvalidationUrl(),
				'expiration' => $data->getUrlExpiration(),
			]
		);
		$emailContent = ConfirmEmailContent::newFromArray( $emailAsArray );

		return $this->sendEmailToRecipient( $ctx, $recipientUser, $emailContent );
	}
}
