<?php

namespace MediaWiki\Mail\ConfirmEmail;

use MediaWiki\Context\IContextSource;

class PlaintextConfirmEmailBuilder implements IConfirmEmailBuilder {

	public function __construct( private readonly IContextSource $context ) {
	}

	private function getEmailParams(
		ConfirmEmailData $data
	): array {
		return [
			$this->context->getRequest()->getIP(),
			$data->getRecipientUser()->getName(),
			$data->getConfirmationUrl(),
			$this->context->getLanguage()->userTimeAndDate(
				$data->getUrlExpiration(),
				$data->getRecipientUser()
			),
			$data->getInvalidationUrl(),
			$this->context->getLanguage()->userDate(
				$data->getUrlExpiration(),
				$data->getRecipientUser()
			),
			$this->context->getLanguage()->userTime(
				$data->getUrlExpiration(),
				$data->getRecipientUser()
			)
		];
	}

	public function buildEmailCreated(
		ConfirmEmailData $data
	): ConfirmEmailContent {
		return new ConfirmEmailContent(
			$this->context->msg( 'confirmemail_subject' )->text(),
			$this->context->msg(
				'confirmemail_body',
				...$this->getEmailParams( $data )
			)->text()
		);
	}

	public function buildEmailChanged(
		ConfirmEmailData $data
	): ConfirmEmailContent {
		return new ConfirmEmailContent(
			$this->context->msg( 'confirmemail_subject' )->text(),
			$this->context->msg(
				'confirmemail_body_changed',
				...$this->getEmailParams( $data )
			)->text()
		);
	}

	public function buildEmailSet(
		ConfirmEmailData $data
	): ConfirmEmailContent {
		return new ConfirmEmailContent(
			$this->context->msg( 'confirmemail_subject' )->text(),
			$this->context->msg(
				'confirmemail_body_set',
				...$this->getEmailParams( $data )
			)->text()
		);
	}
}
