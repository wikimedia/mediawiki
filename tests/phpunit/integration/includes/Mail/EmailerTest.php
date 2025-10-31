<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Integration\Mail;

use MediaWiki\Mail\Emailer;
use MediaWiki\Mail\MailAddress;
use MediaWikiIntegrationTestCase;

/**
 * @group Mail
 * @covers \MediaWiki\Mail\Emailer
 * @covers \MediaWiki\Mail\UserMailer
 */
class EmailerTest extends MediaWikiIntegrationTestCase {

	/**
	 * Tests the send method with a good address and good parameters.
	 * @dataProvider provideSend
	 */
	public function testSend( $to, MailAddress $from, string $subject, string $bodyText, ?string $bodyHtml = null,
		array $options = [] ) {
		$emailer = new Emailer();
		$status = $emailer->send( $to, $from, $subject, $bodyText, $bodyHtml, $options );
		// The test is successful if the status is good.
		$this->assertTrue( $status->isGood() );
	}

	public function testSendWithBadAddress() {
		// Create a new Emailer object.
		$emailer = new Emailer();
		// Send an email.
		$status = $emailer->send(
			new MailAddress( ' ', 'Sender', 'Real name' ),
			new MailAddress( ' ', 'Recipient', 'Real name' ),
			'',
			'',
		);
		// The test is successful if the status is not good.
		$this->assertFalse( $status->isGood() );
	}

	public static function provideSend(): array {
		$from = new MailAddress( 'foo@example.com', 'UserName', 'Real name' );
		$to = new MailAddress( 'bar@example.com', 'UserName', 'Real name' );
		$bodyHtml = '<p>Hello, World!</p>';
		return [
			[ $to, $from, 'Test subject', 'Hello, World!' ],
			[ $to, $from, 'Test subject', 'Hello, World!', $bodyHtml ],
			[ $to, $from, 'Test subject', 'Hello, World!', $bodyHtml, [ 'cc' => [ $from ] ] ],
		];
	}
}
