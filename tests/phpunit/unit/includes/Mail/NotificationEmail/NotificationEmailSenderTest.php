<?php

namespace MediaWiki\Tests\Unit\Mail\NotificationEmail;

use MediaWiki\Config\HashConfig;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Context\IContextSource;
use MediaWiki\Language\Language;
use MediaWiki\Mail\IEmailer;
use MediaWiki\Mail\NotificationEmail\NotificationEmailSender;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\WebRequest;
use MediaWiki\User\User;
use MediaWiki\Utils\UrlUtils;
use MediaWikiUnitTestCase;
use StatusValue;
use Wikimedia\ObjectCache\HashBagOStuff;

/**
 * @covers \MediaWiki\Mail\NotificationEmail\NotificationEmailSender
 * @group medium
 */
class NotificationEmailSenderTest extends MediaWikiUnitTestCase {

	private function createMockUser(): User {
		$user = $this->createMock( User::class );
		$user->method( 'getName' )->willReturn( 'TestUser' );
		$user->method( 'getEmail' )->willReturn( 'old@example.com' );
		$user->method( 'getUser' )->willReturn( $user );
		$user->method( 'getRealName' )->willReturn( '' );
		return $user;
	}

	private function getMockContext(): IContextSource {
		$request = $this->createNoOpMock( WebRequest::class, [ 'getIP' ] );
		$request->method( 'getIP' )->willReturn( '192.0.2.1' );

		$config = new HashConfig( [
			MainConfigNames::Logo => null,
			MainConfigNames::Logos => [],
		] );
		$language = $this->createNoOpMock( Language::class, [ 'getCode' ] );
		$language->method( 'getCode' )->willReturn( 'en' );

		$context = $this->createNoOpMock(
			IContextSource::class,
			[ 'getRequest', 'msg', 'getConfig', 'getLanguage' ]
		);
		$context->method( 'getRequest' )->willReturn( $request );
		$context->method( 'msg' )
			->willReturnCallback( function ( $key, ...$params ) {
				return $this->getMockMessage( $key . ':' . implode( ',', $params ) );
			} );
		$context->method( 'getConfig' )->willReturn( $config );
		$context->method( 'getLanguage' )->willReturn( $language );

		return $context;
	}

	public function testSendNotificationMailPlaintext() {
		$emailer = $this->createNoOpMock( IEmailer::class, [ 'send' ] );
		$emailer->expects( $this->once() )
			->method( 'send' )
			->with(
				$this->anything(),
				$this->anything(),
				$this->stringContains( 'notificationemail_subject_' ),
				$this->isType( 'string' ),
				$this->isNull()
			)
			->willReturn( StatusValue::newGood() );

		$sender = new NotificationEmailSender(
			new ServiceOptions( NotificationEmailSender::CONSTRUCTOR_OPTIONS, new HashConfig( [
				MainConfigNames::PasswordSender => 'noreply@example.com',
				MainConfigNames::AllowHTMLEmail => false,
			] ) ),
			$emailer,
			new HashBagOStuff(),
			$this->createNoOpMock( UrlUtils::class )
		);

		$result = $sender->sendNotificationMail(
			$this->getMockContext(),
			$this->createMockUser(),
			'changed',
			'new@example.com'
		);

		$this->assertStatusOK( $result );
	}

	public function testSendNotificationMailHtml() {
		$emailer = $this->createNoOpMock( IEmailer::class, [ 'send' ] );
		$emailer->expects( $this->once() )
			->method( 'send' )
			->with(
				$this->anything(),
				$this->anything(),
				$this->isType( 'string' ),
				$this->isType( 'string' ),
				$this->isType( 'string' )
			)
			->willReturn( StatusValue::newGood() );

		$urlUtils = $this->createNoOpMock( UrlUtils::class, [ 'expand' ] );
		$urlUtils->method( 'expand' )
			->with( $this->isType( 'string' ), PROTO_CANONICAL )
			->willReturnCallback( static function ( $url ) {
				return 'https://example.com' . $url;
			} );

		$sender = new NotificationEmailSender(
			new ServiceOptions( NotificationEmailSender::CONSTRUCTOR_OPTIONS, new HashConfig( [
				MainConfigNames::PasswordSender => 'noreply@example.com',
				MainConfigNames::AllowHTMLEmail => true,
			] ) ),
			$emailer,
			new HashBagOStuff(),
			$urlUtils
		);

		$result = $sender->sendNotificationMail(
			$this->getMockContext(),
			$this->createMockUser(),
			'changed',
			'new@example.com'
		);

		$this->assertStatusOK( $result );
	}

}
