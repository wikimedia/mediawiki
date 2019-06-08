<?php

use Wikimedia\TestingAccessWrapper;

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

		$this->setContentLang( 'en' );
		$this->setMwGlobals( [
			'wgScript' => '/index.php',
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

		$this->setExpectedException( UserNotLoggedIn::class, $expected );

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

	public function provideBuildPrevNextNavigation() {
		yield [ 0, 20, false, false ];
		yield [ 17, 20, false, false ];
		yield [ 0, 17, false, false ];
		yield [ 0, 20, true, 'Foo' ];
		yield [ 17, 20, true, 'Föö_Bär' ];
	}

	/**
	 * @dataProvider provideBuildPrevNextNavigation
	 */
	public function testBuildPrevNextNavigation( $offset, $limit, $atEnd, $subPage ) {
		$this->setUserLang( Language::factory( 'qqx' ) ); // disable i18n

		$specialPage = new SpecialPage( 'Watchlist' );
		$specialPage = TestingAccessWrapper::newFromObject( $specialPage );

		$html = $specialPage->buildPrevNextNavigation(
			$offset,
			$limit,
			[ 'x' => 25 ],
			$atEnd,
			$subPage
		);

		$this->assertStringStartsWith( '(viewprevnext:', $html );

		preg_match_all( '!<a.*?</a>!', $html, $m, PREG_PATTERN_ORDER );
		$links = $m[0];

		foreach ( $links as $a ) {
			if ( $subPage ) {
				$this->assertContains( 'Special:Watchlist/' . wfUrlencode( $subPage ), $a );
			} else {
				$this->assertContains( 'Special:Watchlist', $a );
				$this->assertNotContains( 'Special:Watchlist/', $a );
			}
			$this->assertContains( 'x=25', $a );
		}

		$i = 0;

		if ( $offset > 0 ) {
			$this->assertContains(
				'limit=' . $limit . '&amp;offset=' . max( 0, $offset - $limit ) . '&amp;',
				$links[ $i ]
			);
			$this->assertContains( 'title="(prevn-title: ' . $limit . ')"', $links[$i] );
			$this->assertContains( 'class="mw-prevlink"', $links[$i] );
			$this->assertContains( '>(prevn: ' . $limit . ')<', $links[$i] );
			$i += 1;
		}

		if ( !$atEnd ) {
			$this->assertContains(
				'limit=' . $limit . '&amp;offset=' . ( $offset + $limit ) . '&amp;',
				$links[ $i ]
			);
			$this->assertContains( 'title="(nextn-title: ' . $limit . ')"', $links[$i] );
			$this->assertContains( 'class="mw-nextlink"', $links[$i] );
			$this->assertContains( '>(nextn: ' . $limit . ')<', $links[$i] );
			$i += 1;
		}

		$this->assertCount( 5 + $i, $links );

		$this->assertContains( 'limit=20&amp;offset=' . $offset, $links[$i] );
		$this->assertContains( 'title="(shown-title: 20)"', $links[$i] );
		$this->assertContains( 'class="mw-numlink"', $links[$i] );
		$this->assertContains( '>20<', $links[$i] );
		$i += 4;

		$this->assertContains( 'limit=500&amp;offset=' . $offset, $links[$i] );
		$this->assertContains( 'title="(shown-title: 500)"', $links[$i] );
		$this->assertContains( 'class="mw-numlink"', $links[$i] );
		$this->assertContains( '>500<', $links[$i] );
	}

}
