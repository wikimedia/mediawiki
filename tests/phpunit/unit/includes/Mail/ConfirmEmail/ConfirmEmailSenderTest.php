<?php

namespace MediaWiki\Tests\Unit\Mail\ConfirmEmail;

use LogicException;
use MediaWiki\Config\HashConfig;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Context\IContextSource;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Mail\ConfirmEmail\ConfirmEmailBuilderFactory;
use MediaWiki\Mail\ConfirmEmail\ConfirmEmailContent;
use MediaWiki\Mail\ConfirmEmail\ConfirmEmailData;
use MediaWiki\Mail\ConfirmEmail\ConfirmEmailSender;
use MediaWiki\Mail\ConfirmEmail\IConfirmEmailBuilder;
use MediaWiki\Mail\IEmailer;
use MediaWiki\Mail\MailAddress;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\WebRequest;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;
use StatusValue;

/**
 * @covers \MediaWiki\Mail\ConfirmEmail\ConfirmEmailSender
 * @covers \MediaWiki\Mail\ConfirmEmail\ConfirmEmailContent
 * @covers \MediaWiki\Mail\ConfirmEmail\ConfirmEmailData
 */
class ConfirmEmailSenderTest extends MediaWikiUnitTestCase {

	public static function provideSuccessful() {
		return [
			'created' => [ 'buildEmailCreated', 'created' ],
			'changed' => [ 'buildEmailChanged', 'changed' ],
			'set' => [ 'buildEmailSet', 'set' ],
		];
	}

	/**
	 * @dataProvider provideSuccessful
	 */
	public function testSuccessful( string $expectedMethod, string $emailType ) {
		$user = $this->createMock( User::class );

		$hookRunner = $this->createNoOpMock( HookRunner::class, [ 'onUserSendConfirmationMail' ] );
		$hookRunner->expects( $this->once() )
			->method( 'onUserSendConfirmationMail' )
			->with(
				$user,
				$this->isType( 'array' ),
				[
					'type' => $emailType,
					'ip' => '1.2.3.4',
					'confirmURL' => 'https://wiki.cz/confirm',
					'invalidateURL' => 'https://wiki.cz/invalidate',
					'expiration' => '20251124091522',
				]
			);

		$userIdentity = new UserIdentityValue( 1, 'Admin' );
		$userFactory = $this->createNoOpMock( UserFactory::class, [ 'newFromUserIdentity' ] );
		$userFactory->expects( $this->once() )
			->method( 'newFromUserIdentity' )
			->with( $userIdentity )
			->willReturn( $user );

		$emailer = $this->createNoOpMock( IEmailer::class, [ 'send' ] );
		$emailer->expects( $this->once() )
			->method( 'send' )
			->with(
				$this->isType( 'array' ),
				$this->isInstanceOf( MailAddress::class ),
				'Subject',
				'Plaintext',
				'HTML'
			)
			->willReturn( StatusValue::newGood() );

		$request = $this->createNoOpMock( WebRequest::class, [ 'getIP' ] );
		$request->expects( $this->once() )
			->method( 'getIP' )
			->willReturn( '1.2.3.4' );
		$ctx = $this->createNoOpMock( IContextSource::class, [ 'getRequest', 'msg' ] );
		$ctx->expects( $this->once() )
			->method( 'getRequest' )
			->willReturn( $request );
		$ctx->expects( $this->once() )
			->method( 'msg' )
			->with( 'emailsender' )
			->willReturn( $this->getMockMessage( 'emailsender' ) );
		$data = new ConfirmEmailData(
			$userIdentity,
			'https://wiki.cz/confirm',
			'https://wiki.cz/invalidate',
			'20251124091522'
		);
		$email = new ConfirmEmailContent(
			'Subject',
			'Plaintext',
			'HTML'
		);
		$builder = $this->createNoOpMock( IConfirmEmailBuilder::class, [ $expectedMethod ] );
		$builder->expects( $this->once() )
			->method( $expectedMethod )
			->with( $data )
			->willReturn( $email );
		$builderFactory = $this->createNoOpMock( ConfirmEmailBuilderFactory::class, [ 'newFromContext' ] );
		$builderFactory->expects( $this->once() )
			->method( 'newFromContext' )
			->with( $ctx )
			->willReturn( $builder );

		$sender = new ConfirmEmailSender(
			new ServiceOptions( ConfirmEmailSender::CONSTRUCTOR_OPTIONS, new HashConfig( [
				MainConfigNames::PasswordSender => 'password@sender.com',
			] ) ),
			$hookRunner,
			$userFactory,
			$emailer,
			$builderFactory
		);
		$status = $sender->sendConfirmationMail( $ctx, $emailType, $data );
		$this->assertStatusOK( $status );
	}

	public function testWrongType() {
		$ctx = $this->createNoOpMock( IContextSource::class );
		$builder = $this->createNoOpMock( IConfirmEmailBuilder::class );
		$builderFactory = $this->createNoOpMock( ConfirmEmailBuilderFactory::class, [ 'newFromContext' ] );
		$builderFactory->expects( $this->once() )
			->method( 'newFromContext' )
			->with( $ctx )
			->willReturn( $builder );
		$sender = new ConfirmEmailSender(
			$this->createNoOpMock( ServiceOptions::class, [ 'assertRequiredOptions' ] ),
			$this->createNoOpMock( HookRunner::class ),
			$this->createNoOpMock( UserFactory::class ),
			$this->createNoOpMock( IEmailer::class ),
			$builderFactory
		);

		$this->expectException( LogicException::class );
		$sender->sendConfirmationMail(
			$ctx,
			'nonexisting',
			$this->createNoOpMock( ConfirmEmailData::class )
		);
	}
}
