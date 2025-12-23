<?php

namespace MediaWiki\Tests\Integration\Mail\ConfirmEmail;

use MediaWiki\Mail\IEmailer;
use MediaWiki\Mail\MailAddress;
use MediaWiki\MainConfigNames;
use MediaWikiIntegrationTestCase;
use StatusValue;

/**
 * @covers \MediaWiki\Mail\ConfirmEmail\ConfirmEmailSender
 * @covers \MediaWiki\Mail\ConfirmEmail\ConfirmEmailBuilderFactory
 * @covers \MediaWiki\Mail\ConfirmEmail\ConfirmEmailData
 * @covers \MediaWiki\Mail\ConfirmEmail\ConfirmEmailContent
 * @covers \MediaWiki\User\User::sendConfirmationMail
 * @group Database
 */
class ConfirmEmailSenderTest extends MediaWikiIntegrationTestCase {

	public function testFailure() {
		$emailer = $this->createNoOpMock( IEmailer::class, [ 'send' ] );
		$emailer->expects( $this->once() )
			->method( 'send' )
			->willReturn( StatusValue::newFatal( 'june' ) );
		$this->setService( 'Emailer', $emailer );

		$user = $this->getTestUser()->getUser();
		$this->assertStatusError( 'june', $user->sendConfirmationMail() );
	}

	public static function provideSuccessfulEmail() {
		return [
			'plaintext' => [ [ MainConfigNames::UserEmailConfirmationUseHTML => false ] ],
			'HTML' => [ [ MainConfigNames::UserEmailConfirmationUseHTML => true ] ],
		];
	}

	/**
	 * @dataProvider provideSuccessfulEmail
	 */
	public function testSuccessfulEmail( array $configOverrides ) {
		$this->overrideConfigValues( $configOverrides );

		$emailer = $this->createNoOpMock( IEmailer::class, [ 'send' ] );
		$emailer->expects( $this->once() )
			->method( 'send' )
			->with(
				$this->callback( function ( array $mailAddresses ) {
					$this->assertCount( 1, $mailAddresses );

					$mailAddress = $mailAddresses[0];
					$this->assertInstanceOf( MailAddress::class, $mailAddress );
					$this->assertEquals( 'test@wikimedia.cz', $mailAddress->address );

					return true;
				} ),
				$this->isInstanceOf( MailAddress::class ),
				$this->isType( 'string' ),
				$this->isType( 'string' ),
				$this->logicalOr(
					$this->isNull(),
					$this->isType( 'string' )
				)
			)
			->willReturn( StatusValue::newGood() );
		$this->setService( 'Emailer', $emailer );

		$user = $this->getMutableTestUser()->getUser();
		$user->setEmail( 'test@wikimedia.cz' );
		$user->saveSettings();

		$this->assertStatusOK( $user->sendConfirmationMail() );

		// Reload the user from the database, as sendConfirmationMail might've modified the state
		$this->assertTrue( $this->getServiceContainer()
			->getUserFactory()
			->newFromId( $user->getId() )
			->isEmailConfirmationPending() );
	}
}
