<?php

namespace MediaWiki\Mail\ConfirmEmail;

use MediaWiki\Mail\IEmailer;
use MediaWiki\Mail\UserMailer;
use MediaWiki\User\Hook\UserSendConfirmationMailHook;
use Wikimedia\Assert\Assert;

/**
 * Value class defining an email sent to the user
 *
 * Currently only used for sending cofirmation emails.
 *
 * @todo Despite the name, this class probably can be generalised and passed to IEmailer and/or
 * UserMailer.
 * @see UserMailer
 * @see IEmailer
 */
class ConfirmEmailContent {

	public function __construct(
		private string $subject,
		private string $plaintext,
		private ?string $html = null
	) {
	}

	/**
	 * Email subject
	 */
	public function getSubject(): string {
		return $this->subject;
	}

	/**
	 * Plaintext version of the email
	 */
	public function getPlaintext(): string {
		return $this->plaintext;
	}

	/**
	 * HTML version of the email
	 *
	 * @return string|null Null if HTML version is not available
	 */
	public function getHtml(): ?string {
		return $this->html;
	}

	/**
	 * @param array $data Supported keys are `subject` and `body`; body can be a string (then it
	 * is interpreted as a plaintext message) or an array (then it must have both the `text` and
	 * `html` keys, with plaintext and HTML versions of the message respectively)
	 * @return self
	 */
	public static function newFromArray( array $data ): self {
		Assert::precondition( array_key_exists( 'subject', $data ), 'Array must have \'subject\' as a key' );
		Assert::precondition( array_key_exists( 'body', $data ), 'Array must have \'subject\' as a key' );

		if ( is_string( $data['body'] ) ) {
			$data['body'] = [
				'text' => $data['body'],
				'html' => null,
			];
		}

		Assert::precondition(
			is_array( $data['body'] ) &&
			array_key_exists( 'text', $data['body'] ) &&
			array_key_exists( 'html', $data['body'] ) &&
			is_string( $data['body']['text'] ) &&
			( $data['body']['html'] === null || is_string( $data['body']['html'] ) ),
			'$data[\'body\'] must be a string with plaintext or an array with \'text\' and \'html\' as keys'
		);

		return new self(
			$data['subject'],
			$data['body']['text'],
			$data['body']['html']
		);
	}

	/**
	 * Format the email in a format acceptable by the hook
	 *
	 * @see UserSendConfirmationMailHook
	 */
	public function toArray(): array {
		$mail = [
			'subject' => $this->getSubject(),
			'body' => $this->getPlaintext(),
			'from' => null,
			'replyTo' => null,
		];
		if ( $this->getHtml() ) {
			$mail['body'] = [
				'text' => $this->getPlaintext(),
				'html' => $this->getHtml(),
			];
		}
		return $mail;
	}
}
