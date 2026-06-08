<?php

namespace MediaWiki\Tests\Unit\Mail;

use MediaWiki\Mail\UserMailer;
use MediaWikiUnitTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Mail
 * @covers \MediaWiki\Mail\UserMailer
 */
class UserMailerTest extends MediaWikiUnitTestCase {

	public function testQuotedPrintable() {
		$this->assertSame(
			"=?UTF-8?Q?=C4=88u=20legebla=3F?=",
			UserMailer::quotedPrintable( "\xc4\x88u legebla?", "UTF-8" )
		);

		$this->assertSame(
			"=?UTF-8?Q?F=C3=B6o=2EBar?=",
			UserMailer::quotedPrintable( "Föo.Bar", "UTF-8" )
		);

		$this->assertSame(
			"Foo.Bar",
			UserMailer::quotedPrintable( "Foo.Bar", "UTF-8" )
		);
	}

	public function testQuotedPrintablePlainAscii() {
		$this->assertSame(
			"Hello World",
			UserMailer::quotedPrintable( "Hello World" ),
			'A plain ASCII string without any characters that need escaping should be returned verbatim, without the encoded-word wrapper'
		);
	}

	public function testQuotedPrintableCharsetHandling() {
		$this->assertSame(
			"=?UTF-8?Q?=C4=88?=",
			UserMailer::quotedPrintable( "\xc4\x88" ),
			'The charset should default to UTF-8 and be upper-cased'
		);
		$this->assertSame(
			"=?ISO8859-1?Q?=C4?=",
			UserMailer::quotedPrintable( "\xc4", "iso-8859-1" ),
			'The ISO-8859 family should be rewritten to the ISO8859 spelling expected inside an encoded-word'
		);
	}

	public function testQuotedPrintableEscapesPeriodAndComma() {
		// The leading non-ASCII byte forces encoded-word mode; the period and
		// comma must then be escaped as =2E and =2C respectively.
		$this->assertSame(
			"=?UTF-8?Q?=C4=2E=2C?=",
			UserMailer::quotedPrintable( "\xc4.," ),
			'T344912 (period) and T385403 (comma) added these characters to the set escaped inside an encoded-word'
		);
	}

	public function testGetSmtpTransportDefaults() {
		$transport = TestingAccessWrapper::newFromClass( UserMailer::class )
			->getSmtpTransport( [] );

		$stream = $transport->getStream();
		$this->assertSame( 'localhost', $stream->getHost() );
		$this->assertSame( 25, $stream->getPort() );
		$this->assertFalse( $stream->isTLS() );
		$this->assertSame( '', $transport->getUsername() );
		$this->assertSame( '', $transport->getPassword() );
	}

	public function testGetSmtpTransportHostAndPort() {
		$transport = TestingAccessWrapper::newFromClass( UserMailer::class )
			->getSmtpTransport( [ 'host' => 'mail.example.com', 'port' => 587 ] );

		$stream = $transport->getStream();
		$this->assertSame( 'mail.example.com', $stream->getHost() );
		$this->assertSame( 587, $stream->getPort() );
		$this->assertFalse( $stream->isTLS() );
	}

	/**
	 * @dataProvider provideEncryptedHostPrefixes
	 */
	public function testGetSmtpTransportEncryptedHostPrefix( string $prefix ) {
		$transport = TestingAccessWrapper::newFromClass( UserMailer::class )
			->getSmtpTransport( [ 'host' => $prefix . 'mail.example.com', 'port' => 465 ] );

		$stream = $transport->getStream();
		$this->assertSame(
			'mail.example.com',
			$stream->getHost(),
			'PEAR\'s Net_SMTP "ssl://" and "tls://" host prefixes should be stripped from the host'
		);
		$this->assertSame( 465, $stream->getPort() );
		$this->assertTrue(
			$stream->isTLS(),
			'An encrypted host prefix should be mapped onto the TLS flag'
		);
	}

	public static function provideEncryptedHostPrefixes(): array {
		return [
			'ssl prefix' => [ 'ssl://' ],
			'tls prefix' => [ 'tls://' ],
		];
	}

	public function testGetSmtpTransportAuth() {
		$transport = TestingAccessWrapper::newFromClass( UserMailer::class )
			->getSmtpTransport( [
				'host' => 'mail.example.com',
				'port' => 587,
				'auth' => true,
				'username' => 'wiki',
				'password' => 'secret',
			] );

		$this->assertSame( 'wiki', $transport->getUsername() );
		$this->assertSame( 'secret', $transport->getPassword() );
	}

	public function testGetSmtpTransportAuthWithoutCredentials() {
		$transport = TestingAccessWrapper::newFromClass( UserMailer::class )
			->getSmtpTransport( [ 'host' => 'mail.example.com', 'auth' => true ] );

		$this->assertSame(
			'',
			$transport->getUsername(),
			'When auth is requested but credentials are omitted, an empty username should be used rather than triggering an undefined-index error'
		);
		$this->assertSame(
			'',
			$transport->getPassword(),
			'When auth is requested but credentials are omitted, an empty password should be used rather than triggering an undefined-index error'
		);
	}
}
