<?php

namespace MediaWiki\Tests\SpecialPage;

use MediaWiki\Exception\UserNotLoggedIn;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\SpecialPage\SpecialPage
 *
 * @group Database
 *
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class SpecialPageTest extends MediaWikiIntegrationTestCase {

	use TempUserTestTrait;

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::Script => '/index.php',
			MainConfigNames::LanguageCode => 'en',
		] );
	}

	/**
	 * @dataProvider getTitleForProvider
	 */
	public function testGetTitleFor( $expectedName, $name ) {
		$title = SpecialPage::getTitleFor( $name );
		$expected = Title::makeTitle( NS_SPECIAL, $expectedName );
		$this->assertEquals( $expected, $title );
	}

	public static function getTitleForProvider() {
		return [
			[ 'UserLogin', 'Userlogin' ]
		];
	}

	public function testInvalidGetTitleFor() {
		$this->expectPHPError(
			E_USER_NOTICE,
			function () {
				$title = SpecialPage::getTitleFor( 'cat' );
				$expected = Title::makeTitle( NS_SPECIAL, 'Cat' );
				$this->assertEquals( $expected, $title );
			}
		);
	}

	/**
	 * @dataProvider getTitleForWithWarningProvider
	 */
	public function testGetTitleForWithWarning( $expected, $name ) {
		$this->expectPHPError(
			E_USER_NOTICE,
			function () use ( $name, $expected ) {
				$title = SpecialPage::getTitleFor( $name );
				$this->assertEquals( $expected, $title );
			}
		);
	}

	public static function getTitleForWithWarningProvider() {
		return [
			[ Title::makeTitle( NS_SPECIAL, 'UserLogin' ), 'UserLogin' ]
		];
	}

	/**
	 * @dataProvider requireLoginAnonProvider
	 */
	public function testRequireLoginAnon( $expected, ...$params ) {
		$specialPage = new SpecialPage( 'Watchlist', 'viewmywatchlist' );

		$user = User::newFromId( 0 );
		$specialPage->getContext()->setUser( $user );
		$specialPage->getContext()->setLanguage(
			$this->getServiceContainer()->getLanguageFactory()->getLanguage( 'en' ) );

		$this->expectException( UserNotLoggedIn::class );
		$this->expectExceptionMessage( $expected );

		$specialPage->requireLogin( ...$params );
	}

	public static function requireLoginAnonProvider() {
		$lang = 'en';

		$expected1 = wfMessage( 'exception-nologin-text' )->inLanguage( $lang )->text();
		$expected2 = wfMessage( 'about' )->inLanguage( $lang )->text();

		return [
			[ $expected1 ],
			[ $expected2, 'about' ],
			[ $expected2, 'about', 'about' ],
		];
	}

	public function testRequireLoginNotAnon() {
		$specialPage = new SpecialPage( 'Watchlist', 'viewmywatchlist' );

		$user = $this->getTestSysop()->getUser();
		$specialPage->getContext()->setUser( $user );

		$specialPage->requireLogin();

		// no exception thrown, logged in use can access special page
		$this->assertTrue( true );
	}

	/**
	 * @dataProvider provideRequireNamedUserAnon
	 */
	public function testRequireNamedUserAnon( $expected, ...$params ) {
		$specialPage = new SpecialPage( 'Watchlist', 'viewmywatchlist' );

		$user = User::newFromId( 0 );
		$specialPage->getContext()->setUser( $user );
		$specialPage->getContext()->setLanguage(
			$this->getServiceContainer()->getLanguageFactory()->getLanguage( 'en' ) );

		$this->expectException( UserNotLoggedIn::class );
		$this->expectExceptionMessage( $expected );

		$specialPage->requireLogin( ...$params );
	}

	public static function provideRequireNamedUserAnon() {
		$lang = 'en';

		$expected1 = wfMessage( 'exception-nologin-text' )->inLanguage( $lang )->text();
		$expected2 = wfMessage( 'about' )->inLanguage( $lang )->text();

		return [
			[ $expected1 ],
			[ $expected2, 'about' ],
			[ $expected2, 'about', 'about' ],
		];
	}

	public function testRequireNamedUserForTempUser() {
		$this->enableAutoCreateTempUser();
		$specialPage = new SpecialPage( 'Watchlist', 'viewmywatchlist' );

		$user = $this->getServiceContainer()->getTempUserCreator()->create( null, new FauxRequest() )->getUser();
		$specialPage->getContext()->setUser( $user );

		$this->expectException( UserNotLoggedIn::class );
		$specialPage->requireNamedUser();
	}

	public function testRequireNamedUserForNamedUser() {
		$specialPage = new SpecialPage( 'Watchlist', 'viewmywatchlist' );

		$user = $this->getTestSysop()->getUser();
		$specialPage->getContext()->setUser( $user );

		$specialPage->requireNamedUser();

		// no exception thrown, so the test passes.
		$this->assertTrue( true );
	}
}
