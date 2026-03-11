<?php

namespace MediaWiki\Tests\Unit\Mail\ConfirmEmail;

use MediaWiki\Config\HashConfig;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Mail\ConfirmEmail\EmailConfirmationBannerHandler;
use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Mail\ConfirmEmail\EmailConfirmationBannerHandler
 */
class EmailConfirmationBannerHandlerTest extends MediaWikiUnitTestCase {

	private function makeHandler( bool $bannerEnabled = true, bool $emailAuth = true ): EmailConfirmationBannerHandler {
		return new EmailConfirmationBannerHandler(
			new ServiceOptions(
				EmailConfirmationBannerHandler::CONSTRUCTOR_OPTIONS,
				new HashConfig( [
					MainConfigNames::EmailConfirmationBanner => $bannerEnabled,
					MainConfigNames::EmailAuthentication => $emailAuth,
				] )
			)
		);
	}

	private function makeUser(
		bool $named = true,
		string $email = 'user@example.com',
		bool $emailConfirmed = false
	): User {
		$user = $this->createMock( User::class );
		$user->method( 'isNamed' )->willReturn( $named );
		$user->method( 'getEmail' )->willReturn( $email );
		$user->method( 'isEmailConfirmed' )->willReturn( $emailConfirmed );
		return $user;
	}

	private function makeTitle( bool $isConfirmEmailPage = false ): Title {
		$title = $this->createMock( Title::class );
		$title->method( 'isSpecial' )->with( 'Confirmemail' )->willReturn( $isConfirmEmailPage );
		return $title;
	}

	public static function provideShouldShowBanner(): iterable {
		yield 'banner config disabled' => [ false, true, true, 'user@example.com', false, false, false ];
		yield 'email auth disabled' => [ true, false, true, 'user@example.com', false, false, false ];
		yield 'non-named user' => [ true, true, false, 'user@example.com', false, false, false ];
		yield 'no email set' => [ true, true, true, '', false, false, false ];
		yield 'email already confirmed' => [ true, true, true, 'user@example.com', true, false, false ];
		yield 'on ConfirmEmail page' => [ true, true, true, 'user@example.com', false, true, false ];
		yield 'all conditions met' => [ true, true, true, 'user@example.com', false, false, true ];
	}

	/**
	 * @dataProvider provideShouldShowBanner
	 */
	public function testShouldShowBanner(
		bool $bannerEnabled,
		bool $emailAuth,
		bool $named,
		string $email,
		bool $emailConfirmed,
		bool $isConfirmEmailPage,
		bool $expected
	): void {
		$handler = $this->makeHandler( $bannerEnabled, $emailAuth );
		$user = $this->makeUser( $named, $email, $emailConfirmed );
		$title = $this->makeTitle( $isConfirmEmailPage );

		$this->assertSame( $expected, $handler->shouldShowBanner( $user, $title ) );
	}
}
