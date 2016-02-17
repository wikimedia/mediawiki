<?php

/**
 * @covers SpecialPage
 *
 * @group Database
 *
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class SpecialPageTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( [
			'wgScript' => '/index.php',
			'wgContLang' => Language::factory( 'en' )
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

	public function getTitleForProvider() {
		return [
			[ 'UserLogin', 'Userlogin' ]
		];
	}

	/**
	 * @expectedException PHPUnit_Framework_Error_Notice
	 */
	public function testInvalidGetTitleFor() {
		$title = SpecialPage::getTitleFor( 'cat' );
		$expected = Title::makeTitle( NS_SPECIAL, 'Cat' );
		$this->assertEquals( $expected, $title );
	}

	/**
	 * @expectedException PHPUnit_Framework_Error_Notice
	 * @dataProvider getTitleForWithWarningProvider
	 */
	public function testGetTitleForWithWarning( $expected, $name ) {
		$title = SpecialPage::getTitleFor( $name );
		$this->assertEquals( $expected, $title );
	}

	public function getTitleForWithWarningProvider() {
		return [
			[ Title::makeTitle( NS_SPECIAL, 'UserLogin' ), 'UserLogin' ]
		];
	}

	/**
	 * @dataProvider requireLoginAnonProvider
	 */
	public function testRequireLoginAnon( $expected, $reason, $title ) {
		$specialPage = new SpecialPage( 'Watchlist', 'viewmywatchlist' );

		$user = User::newFromId( 0 );
		$specialPage->getContext()->setUser( $user );
		$specialPage->getContext()->setLanguage( Language::factory( 'en' ) );

		$this->setExpectedException( 'UserNotLoggedIn', $expected );

		// $specialPage->requireLogin( [ $reason [, $title ] ] )
		call_user_func_array(
			[ $specialPage, 'requireLogin' ],
			array_filter( [ $reason, $title ] )
		);
	}

	public function requireLoginAnonProvider() {
		$lang = 'en';

		$expected1 = wfMessage( 'exception-nologin-text' )->inLanguage( $lang )->text();
		$expected2 = wfMessage( 'about' )->inLanguage( $lang )->text();

		return [
			[ $expected1, null, null ],
			[ $expected2, 'about', null ],
			[ $expected2, 'about', 'about' ],
		];
	}

	public function testRequireLoginNotAnon() {
		$specialPage = new SpecialPage( 'Watchlist', 'viewmywatchlist' );

		$user = User::newFromName( "UTSysop" );
		$specialPage->getContext()->setUser( $user );

		$specialPage->requireLogin();

		// no exception thrown, logged in use can access special page
		$this->assertTrue( true );
	}

}
