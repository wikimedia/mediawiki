<?php

/**
 * @group Skin
 */
class SideBarTest extends MediaWikiLangTestCase {

	/**
	 * A skin template, reinitialized before each test
	 * @var SkinTemplate
	 */
	private $skin;

	protected function setUp() {
		parent::setUp();
		$this->skin = new SkinTemplate();
		$this->skin->getContext()->setLanguage( Language::factory( 'en' ) );
	}

	/**
	 * @covers SkinTemplate::addToSidebarPlain
	 * @dataProvider provideSideBarTest
	 * @group Database
	 */
	public function testSideBar( $expected, $text, $message = '' ) {
		$bar = array();
		$this->skin->addToSidebarPlain( $bar, $text );
		$this->assertEquals( $expected, $bar, $message );
	}

	public static function provideSideBarTest() {
		$testCases = array();

		$testCases['testSidebarWithOnlyTwoTitles'] = array(
			array(
				'Title1' => array(),
				'Title2' => array(),
			),
			'* Title1
* Title2
'
		);

		$titleName = MessageCache::singleton()->get( 'helppage' );
		$title = Title::newFromText( $titleName );
		$testCases['testExpandMessages'] = array(
			array( 'Title' => array(
				array(
					'text' => 'Help',
					'href' => $title->getLocalURL(),
					'id' => 'n-help',
					'active' => null
				)
			) ),
			'* Title
** helppage|help
'
		);

		$testCases['testExternalUrlsRequireADescription'] = array(
			array( 'Title' => array(
				# ** http://www.mediawiki.org/| Home
				array(
					'text' => 'Home',
					'href' => 'http://www.mediawiki.org/',
					'id' => 'n-Home',
					'active' => null,
					'rel' => 'nofollow',
				),
				# ** http://valid.no.desc.org/
				# ... skipped since it is missing a pipe with a description
			) ),
			'* Title
** http://www.mediawiki.org/| Home
** http://valid.no.desc.org/
'
		);

		//bug 33321 - Make sure there's a | after transforming.
		$testCases['testTrickyPipe'] = array(
			array( 'Title' => array(
				# The first 2 are skipped
				# Doesn't really test the url properly
				# because it will vary with $wgArticlePath et al.
				# ** Baz|Fred
				array(
					'text' => 'Fred',
					'href' => Title::newFromText( 'Baz' )->getLocalURL(),
					'id' => 'n-Fred',
					'active' => null,
				),
				array(
					'text' => 'title-to-display',
					'href' => Title::newFromText( 'page-to-go-to' )->getLocalURL(),
					'id' => 'n-title-to-display',
					'active' => null,
				),
			) ),
			'* Title
** {{PAGENAME|Foo}}
** Bar
** Baz|Fred
** {{PLURAL:1|page-to-go-to{{int:pipe-separator/en}}title-to-display|branch not taken}}
'
		);

		return $testCases;
	}

	#### Attributes for external links ##########################
	private function getAttribs() {
		# Sidebar text we will use everytime
		$text = '* Title
** http://www.mediawiki.org/| Home';

		$bar = array();
		$this->skin->addToSideBarPlain( $bar, $text );

		return $bar['Title'][0];
	}

	/**
	 * Simple test to verify our helper assertAttribs() is functional
	 */
	public function testTestAttributesAssertionHelper() {
		$this->setMwGlobals( array(
			'wgNoFollowLinks' => true,
			'wgExternalLinkTarget' => false,
		) );
		$attribs = $this->getAttribs();

		$this->assertArrayHasKey( 'rel', $attribs );
		$this->assertEquals( 'nofollow', $attribs['rel'] );

		$this->assertArrayNotHasKey( 'target', $attribs );
	}

	/**
	 * Test $wgNoFollowLinks in sidebar
	 */
	public function testRespectWgnofollowlinks() {
		$this->setMwGlobals( 'wgNoFollowLinks', false );

		$attribs = $this->getAttribs();
		$this->assertArrayNotHasKey( 'rel', $attribs,
			'External URL in sidebar do not have rel=nofollow when $wgNoFollowLinks = false'
		);
	}

	/**
	 * Test $wgExternaLinkTarget in sidebar
	 * @dataProvider provideRespectExternallinktarget
	 */
	public function testRespectExternallinktarget( $externalLinkTarget ) {
		$this->setMwGlobals( 'wgExternalLinkTarget', $externalLinkTarget );

		$attribs = $this->getAttribs();
		$this->assertArrayHasKey( 'target', $attribs );
		$this->assertEquals( $attribs['target'], $externalLinkTarget );
	}

	public static function provideRespectExternallinktarget() {
		return array(
			array( '_blank' ),
			array( '_self' ),
		);
	}
}
