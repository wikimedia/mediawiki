<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @group Mail
 * @covers \UserMailer
 */
class UserMailerTest extends MediaWikiUnitTestCase {

	public function testQuotedPrintable() {
		$this->assertEquals(
			"=?UTF-8?Q?=C4=88u=20legebla=3F?=",
			UserMailer::quotedPrintable( "\xc4\x88u legebla?", "UTF-8" )
		);

		$this->assertEquals(
			"=?UTF-8?Q?F=C3=B6o=2EBar?=",
			UserMailer::quotedPrintable( "Föo.Bar", "UTF-8" )
		);

		$this->assertEquals(
			"Foo.Bar",
			UserMailer::quotedPrintable( "Foo.Bar", "UTF-8" )
		);
	}

	/**
	 * @dataProvider provideRecipientDomains
	 */
	public function testRecipientDomains( array $addresses, string $expected ) {
		$to = array_map( static fn ( string $address ) => new MailAddress( $address ), $addresses );
		$this->assertSame(
			$expected,
			TestingAccessWrapper::newFromClass( UserMailer::class )->recipientDomains( $to )
		);
	}

	public static function provideRecipientDomains(): array {
		return [
			'single recipient' => [ [ 'alice@example.org' ], 'example.org' ],
			'distinct domains, in order' => [
				[ 'alice@example.org', 'bob@gmail.com' ],
				'example.org, gmail.com',
			],
			'duplicate domains collapse to one' => [
				[ 'alice@example.org', 'carol@example.org' ],
				'example.org',
			],
			'address without @ is skipped' => [
				[ 'invalid', 'bob@gmail.com' ],
				'gmail.com',
			],
			'no extractable domain yields empty string' => [
				[ 'invalid', 'alsoinvalid' ],
				'',
			],
		];
	}

	/**
	 * @dataProvider provideMailLogContext
	 */
	public function testMailLogContext( array $toAddresses, string $fromAddress, array $expected ) {
		$to = array_map( static fn ( string $address ) => new MailAddress( $address ), $toAddresses );
		$from = new MailAddress( $fromAddress );
		$this->assertSame(
			$expected,
			TestingAccessWrapper::newFromClass( UserMailer::class )->mailLogContext( $to, $from )
		);
	}

	public static function provideMailLogContext(): array {
		return [
			'single recipient' => [
				[ 'alice@example.org' ],
				'noreply@wiki.example',
				[
					'to' => 'alice@example.org',
					'recipient_count' => 1,
					'recipient_domains' => 'example.org',
					'from' => 'noreply@wiki.example',
				],
			],
			'multiple recipients log the first as {to}' => [
				[ 'alice@example.org', 'bob@gmail.com' ],
				'noreply@wiki.example',
				[
					'to' => 'alice@example.org',
					'recipient_count' => 2,
					'recipient_domains' => 'example.org, gmail.com',
					'from' => 'noreply@wiki.example',
				],
			],
		];
	}
}
