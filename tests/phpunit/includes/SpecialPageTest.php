<?php

/**
 * @covers SpecialPage
 *
 * @group Database
 *
 * @licence GNU GPL v2+
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class SpecialPageTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( array(
			'wgScript' => '/index.php',
			'wgContLang' => Language::factory( 'en' )
		) );
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
		return array(
			array( 'UserLogin', 'Userlogin' )
		);
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
		return array(
			array( Title::makeTitle( NS_SPECIAL, 'UserLogin' ), 'UserLogin' )
		);
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

		if ( $reason === 'blank' && $title === 'blank' ) {
			$specialPage->requireLogin();
		} else {
			$specialPage->requireLogin( $reason, $title );
		}
	}

	public function requireLoginAnonProvider() {
		$lang = 'en';

		$msg = wfMessage( 'loginreqlink' )->inLanguage( $lang )->escaped();
		$loginLink = '<a href="/index.php?title=Special:UserLogin&amp;returnto=Special%3AWatchlist"'
			. ' title="Special:UserLogin">' . $msg . '</a>';

		$expected1 = wfMessage( 'exception-nologin-text-manual' )
			->params( $loginLink )->inLanguage( $lang )->text();

		$expected2 = wfMessage( 'about' )->inLanguage( $lang )->text();

		return array(
			array( $expected1, null, null ),
			array( $expected2, 'about', null ),
			array( $expected2, wfMessage( 'about' ), null ),
			array( $expected2, 'about', 'about' ),
			array( $expected2, 'about', wfMessage( 'about' ) ),
			array( $expected1, 'blank', 'blank' )
		);
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
