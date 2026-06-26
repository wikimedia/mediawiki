<?php

namespace MediaWiki\Mail\ConfirmEmail;

use MediaWiki\User\UserIdentity;

/**
 * Value class wrapping variables present in the confirmation email
 *
 * @see ConfirmEmailContent
 * @see ConfirmEmailSender
 */
class ConfirmEmailData {

	public function __construct(
		private readonly UserIdentity $recipientUser,
		private readonly string $confirmationUrl,
		private readonly string $invalidationUrl,
		private readonly string $urlExpiration,
	) {
	}

	public function getRecipientUser(): UserIdentity {
		return $this->recipientUser;
	}

	public function getConfirmationUrl(): string {
		return $this->confirmationUrl;
	}

	public function getInvalidationUrl(): string {
		return $this->invalidationUrl;
	}

	public function getUrlExpiration(): string {
		return $this->urlExpiration;
	}
}
