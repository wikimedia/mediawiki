<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Integration\Mail;

use MediaWiki\Mail\MailAddress;
use MediaWiki\Mail\UserMailer;
use MediaWiki\MainConfigNames;
use MediaWikiIntegrationTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Mail
 * @covers \MediaWiki\Mail\UserMailer
 */
class UserMailerTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();
		// Pin to the default so leftover state from another test (e.g. a raw
		// global write) cannot flip From/Reply-To to the real name. See T6979.
		$this->overrideConfigValue( MainConfigNames::EnotifUseRealName, false );
	}

	private function makeFrom(): MailAddress {
		return new MailAddress( 'from@example.com', 'Sender', 'Real Sender' );
	}

	private function makeTo(): MailAddress {
		return new MailAddress( 'to@example.com', 'Recipient', 'Real Recipient' );
	}

	/**
	 * Capture the fully assembled headers and body by hooking
	 * UserMailerTransformMessage, which runs after all header/MIME assembly but
	 * before the AlternateUserMailer hook that MediaWikiIntegrationTestCase sets
	 * to short-circuit real delivery.
	 *
	 * @param array &$captured Populated with 'headers' and 'body' on send.
	 */
	private function captureAssembledMessage( array &$captured ): void {
		$this->setTemporaryHook(
			'UserMailerTransformMessage',
			static function ( $to, $from, $subject, &$headers, &$body, &$error ) use ( &$captured ) {
				$captured['headers'] = $headers;
				$captured['body'] = $body;
				return true;
			}
		);
	}

	public function testSendTextOnlyAssemblesHeaders() {
		$captured = [];
		$this->captureAssembledMessage( $captured );

		$status = UserMailer::send(
			$this->makeTo(),
			$this->makeFrom(),
			'Test subject',
			'Hello, this is the body.'
		);

		$this->assertStatusGood( $status );
		$headers = $captured['headers'];
		$this->assertSame( '"Sender" <from@example.com>', $headers['From'] );
		$this->assertSame( 'from@example.com', $headers['Return-Path'] );
		$this->assertSame( '1.0', $headers['MIME-Version'] );
		$this->assertSame( 'text/plain; charset=UTF-8', $headers['Content-type'] );
		$this->assertSame( '8bit', $headers['Content-transfer-encoding'] );
		$this->assertArrayHasKey( 'Date', $headers );
		$this->assertArrayHasKey( 'X-Mailer', $headers );
		$this->assertArrayHasKey( 'List-Unsubscribe', $headers );
		$this->assertMatchesRegularExpression( '/^<.+@.+>$/', $headers['Message-ID'] );
		$this->assertSame( 'Hello, this is the body.', $captured['body'] );
	}

	public function testSendMultipartAssemblesMimeBody() {
		$this->overrideConfigValue( MainConfigNames::AllowHTMLEmail, true );
		$captured = [];
		$this->captureAssembledMessage( $captured );

		$status = UserMailer::send(
			$this->makeTo(),
			$this->makeFrom(),
			'Test subject',
			[ 'text' => 'Plain text body.', 'html' => '<p>HTML body.</p>' ]
		);

		$this->assertStatusGood( $status );
		$headers = $captured['headers'];
		$this->assertSame( '1.0', $headers['MIME-Version'] );
		$this->assertStringStartsWith( 'multipart/alternative;', $headers['Content-Type'] );
		$this->assertStringContainsString( 'boundary=', $headers['Content-Type'] );
		// The assembled body must contain both alternatives.
		$this->assertStringContainsString( 'Plain text body.', $captured['body'] );
		$this->assertStringContainsString( '<p>HTML body.</p>', $captured['body'] );
	}

	public function testSendReplyToHeader() {
		$captured = [];
		$this->captureAssembledMessage( $captured );

		$status = UserMailer::send(
			$this->makeTo(),
			$this->makeFrom(),
			'Test subject',
			'Hello, this is the body.',
			[ 'replyTo' => new MailAddress( 'reply@example.com', 'Reply', 'Reply Name' ) ]
		);

		$this->assertStatusGood( $status );
		$this->assertSame( '"Reply" <reply@example.com>', $captured['headers']['Reply-To'] );
	}

	/**
	 * With HTML email disabled, an array body is collapsed to its text part and
	 * sent as a plain-text (non-multipart) message.
	 */
	public function testHtmlBodyStrippedWhenNotAllowed() {
		$this->overrideConfigValue( MainConfigNames::AllowHTMLEmail, false );
		$captured = [];
		$this->captureAssembledMessage( $captured );

		$status = UserMailer::send(
			$this->makeTo(),
			$this->makeFrom(),
			'Test subject',
			[ 'text' => 'Plain text body.', 'html' => '<p>HTML body.</p>' ]
		);

		$this->assertStatusGood( $status );
		$this->assertSame( 'text/plain; charset=UTF-8', $captured['headers']['Content-type'] );
		$this->assertSame( 'Plain text body.', $captured['body'] );
	}

	public function testSendUsesContentTypeOption() {
		$captured = [];
		$this->captureAssembledMessage( $captured );

		$status = UserMailer::send(
			$this->makeTo(),
			$this->makeFrom(),
			'Test subject',
			'Hello, this is the body.',
			[ 'contentType' => 'text/html; charset=UTF-8' ]
		);

		$this->assertStatusGood( $status );
		$this->assertSame( 'text/html; charset=UTF-8', $captured['headers']['Content-type'] );
	}

	public function testSendNoBody() {
		$status = UserMailer::send(
			$this->makeTo(),
			$this->makeFrom(),
			'Test subject',
			'short'
		);
		$this->assertStatusError( 'user-mail-no-body', $status );
	}

	public function testSendNoAddress() {
		$status = UserMailer::send(
			new MailAddress( '', 'Recipient', 'Real Recipient' ),
			$this->makeFrom(),
			'Test subject',
			'Hello, this is the body.'
		);
		$this->assertStatusError( 'user-mail-no-addy', $status );
	}

	public function testTransformContentAbortWithError() {
		$this->setTemporaryHook(
			'UserMailerTransformContent',
			static function ( $to, $from, $body, &$error ) {
				$error = 'content blocked';
				return false;
			}
		);
		$status = UserMailer::send(
			$this->makeTo(),
			$this->makeFrom(),
			'Test subject',
			'Hello, this is the body.'
		);
		$this->assertStatusError( 'php-mail-error', $status );
	}

	public function testTransformContentAbortWithoutError() {
		$this->setTemporaryHook(
			'UserMailerTransformContent',
			static fn ( $to, $from, $body, &$error ) => false
		);
		$status = UserMailer::send(
			$this->makeTo(),
			$this->makeFrom(),
			'Test subject',
			'Hello, this is the body.'
		);
		$this->assertStatusError( 'php-mail-error-unknown', $status );
	}

	public function testTransformMessageAbortWithError() {
		$this->setTemporaryHook(
			'UserMailerTransformMessage',
			static function ( $to, $from, $subject, &$headers, &$body, &$error ) {
				$error = 'message blocked';
				return false;
			}
		);
		$status = UserMailer::send(
			$this->makeTo(),
			$this->makeFrom(),
			'Test subject',
			'Hello, this is the body.'
		);
		$this->assertStatusError( 'php-mail-error', $status );
	}

	/**
	 * When the UserMailerSplitTo hook removes a recipient from a multi-recipient
	 * list, the remaining and split addresses are each delivered separately.
	 */
	public function testSendSplitTo() {
		$splitOut = new MailAddress( 'split@example.com', 'Split', 'Split Name' );
		$this->setTemporaryHook(
			'UserMailerSplitTo',
			static function ( array &$to ) use ( $splitOut ) {
				$to = array_values( array_filter(
					$to,
					static fn ( MailAddress $addr ) => $addr->address !== $splitOut->address
				) );
				return true;
			}
		);

		$invocations = 0;
		$this->setTemporaryHook(
			'UserMailerTransformMessage',
			static function ( $to, $from, $subject, &$headers, &$body, &$error ) use ( &$invocations ) {
				$invocations++;
				return true;
			}
		);

		$status = UserMailer::send(
			[ $this->makeTo(), $splitOut ],
			$this->makeFrom(),
			'Test subject',
			'Hello, this is the body.'
		);

		$this->assertStatusGood( $status );
		// Once for the remaining recipient, once for the split-out recipient.
		$this->assertSame( 2, $invocations );
	}

	/**
	 * @dataProvider provideMakeMsgId
	 */
	public function testMakeMsgId( $smtp, string $server, string $expectedHostSuffix ) {
		$this->overrideConfigValues( [
			MainConfigNames::SMTP => $smtp,
			MainConfigNames::Server => $server,
		] );

		$msgId = TestingAccessWrapper::newFromClass( UserMailer::class )->makeMsgId();

		$this->assertMatchesRegularExpression( '/^<.+@.+>$/', $msgId );
		$this->assertStringEndsWith( $expectedHostSuffix . '>', $msgId );
	}

	public static function provideMakeMsgId(): array {
		return [
			'IDHost from $wgSMTP' => [
				[ 'host' => 'localhost', 'IDHost' => 'id.example.org' ],
				'https://wiki.example.com',
				'@id.example.org',
			],
			'host derived from $wgServer' => [
				false,
				'https://wiki.example.com',
				'@wiki.example.com',
			],
		];
	}

	/**
	 * Exercises the SMTP delivery branch end-to-end against an unreachable local
	 * port: the transport is built, the RawMessage and Envelope assembled, the
	 * connection refused, and the TransportException mapped to a fatal Status.
	 */
	public function testSmtpDeliveryConnectionError() {
		// Port chosen to be closed on the loopback interface so connect() is
		// refused immediately rather than hanging.
		$this->overrideConfigValue( MainConfigNames::SMTP, [
			'host' => '127.0.0.1',
			'port' => 47111,
			'IDHost' => 'id.example.org',
			'auth' => false,
		] );
		// Remove the test harness's default AlternateUserMailer short-circuit so
		// delivery actually reaches the SMTP transport.
		$this->clearHook( 'AlternateUserMailer' );

		$status = UserMailer::send(
			$this->makeTo(),
			$this->makeFrom(),
			'Test subject',
			'Hello, this is the body.'
		);

		$this->assertStatusError( 'smtp-mail-error', $status );
	}
}
