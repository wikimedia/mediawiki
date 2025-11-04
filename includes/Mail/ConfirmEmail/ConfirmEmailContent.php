<?php

namespace MediaWiki\Mail\ConfirmEmail;

use Wikimedia\Assert\Assert;

class ConfirmEmailContent {

	public function __construct(
		private string $subject,
		private string $plaintext,
		private ?string $html = null
	) {
	}

	public function getSubject(): string {
		return $this->subject;
	}

	public function getPlaintext(): string {
		return $this->plaintext;
	}

	public function getHtml(): ?string {
		return $this->html;
	}

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
