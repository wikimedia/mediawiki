<?php

namespace MediaWiki\Tests\Integration\Specials;

use MediaWiki\Tests\Specials\SpecialPageTestBase;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\Specials\SpecialEmailInvalidate
 * @group Database
 */
class SpecialEmailInvalidateTest extends SpecialPageTestBase {

	/** @dataProvider provideInvalidTokens */
	public function testExecuteForInvalidToken( string $token ): void {
		[ $html ] = $this->executeSpecialPage( $token );

		$this->assertStringContainsString( '(confirmemail_invalid)', $html );
	}

	public static function provideInvalidTokens(): array {
		return [
			'No token provided' => [ 'token' => '' ],
			'Token does not match any user' => [ 'token' => 'testing-nonexistent-token' ],
		];
	}

	/** @dataProvider provideUserLoginStates */
	public function testExecuteForValidToken( bool $userLoggedIn ): void {
		ConvertibleTimestamp::setFakeTime( '20260724155239' );

		$user = $this->getMutableTestUser()->getUser();

		$user->setEmail( 'test@example.com' );
		$user->setEmailAuthenticationTimestamp( ConvertibleTimestamp::now() );
		$user->saveSettings();

		$this->assertSame( 'test@example.com', $user->getEmail() );
		$this->assertSame( ConvertibleTimestamp::now(), $user->getEmailAuthenticationTimestamp() );

		$expiration = '';
		$generatedToken = $user->getConfirmationToken( $expiration );
		$user->saveSettings();

		$performer = $userLoggedIn ?
			$this->getTestSysop()->getUser() :
			$this->getServiceContainer()->getUserFactory()->newAnonymous( '1.2.3.4' );
		[ $html ] = $this->executeSpecialPage( $generatedToken, null, null, $performer );

		$this->assertStringContainsString( '(confirmemail_invalidated)', $html );

		$user->clearInstanceCache( 'name' );
		$this->assertSame( '', $user->getEmail() );
		$this->assertNull( $user->getEmailAuthenticationTimestamp() );

		if ( $userLoggedIn ) {
			$this->assertStringNotContainsString( 'returnto', $html );
		} else {
			$this->assertStringContainsString( 'returnto', $html );
		}
	}

	public static function provideUserLoginStates(): array {
		return [
			'User is not logged in' => [ 'userLoggedIn' => false ],
			'User is logged in' => [ 'userLoggedIn' => true ],
		];
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'Invalidateemail' );
	}
}
