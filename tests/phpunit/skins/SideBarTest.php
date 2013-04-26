<?php

/**
 * @group Skin
 */
class SideBarTest extends MediaWikiLangTestCase {

	/** A skin template, reinitialized before each test */
	private $skin;
	/** Local cache for sidebar messages */
	private $messages;

	/** Build $this->messages array */
	private function initMessagesHref() {
		# List of default messages for the sidebar:
		$URL_messages = array(
			'mainpage',
			'portal-url',
			'currentevents-url',
			'recentchanges-url',
			'randompage-url',
			'helppage',
		);

		foreach ( $URL_messages as $m ) {
			$titleName = MessageCache::singleton()->get( $m );
			$title = Title::newFromText( $titleName );
			$this->messages[$m]['href'] = $title->getLocalURL();
		}
	}

	protected function setUp() {
		parent::setUp();
		$this->initMessagesHref();
		$this->skin = new SkinTemplate();
		$this->skin->getContext()->setLanguage( Language::factory( 'en' ) );
	}

	protected function tearDown() {
		parent::tearDown();
		$this->skin = null;
	}

	/**
	 * Internal helper to test the sidebar
	 * @param $expected
	 * @param $text
	 * @param $message (Default: '')
	 */
	private function assertSideBar( $expected, $text, $message = '' ) {
		$bar = array();
		$this->skin->addToSidebarPlain( $bar, $text );
		$this->assertEquals( $expected, $bar, $message );
	}

	function testSidebarWithOnlyTwoTitles() {
		$this->assertSideBar(
			array(
				'Title1' => array(),
				'Title2' => array(),
			),
			'* Title1
* Title2
'
		);
	}

	function testExpandMessages() {
		$this->assertSidebar(
			array( 'Title' => array(
				array(
					'text' => 'Help',
					'href' => $this->messages['helppage']['href'],
					'id' => 'n-help',
					'active' => null
				)
			) ),
			'* Title
** helppage|help
'
		);
	}

	function testExternalUrlsRequireADescription() {
		$this->assertSidebar(
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
	}

	/**
	 * bug 33321 - Make sure there's a | after transforming.
	 * @group Database
	 */
	function testTrickyPipe() {
		$this->assertSidebar(
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
	function testTestAttributesAssertionHelper() {
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
	function testRespectWgnofollowlinks() {
		$this->setMwGlobals( 'wgNoFollowLinks', false );

		$attribs = $this->getAttribs();
		$this->assertArrayNotHasKey( 'rel', $attribs,
			'External URL in sidebar do not have rel=nofollow when $wgNoFollowLinks = false'
		);
	}

	/**
	 * Test $wgExternaLinkTarget in sidebar
	 * @dataProvider dataRespectExternallinktarget
	 */
	function testRespectExternallinktarget( $externalLinkTarget ) {
		$this->setMwGlobals( 'wgExternalLinkTarget', $externalLinkTarget );

		$attribs = $this->getAttribs();
		$this->assertArrayHasKey( 'target', $attribs );
		$this->assertEquals( $attribs['target'], $externalLinkTarget );
	}

	function dataRespectExternallinktarget() {
		return array(
			array( '_blank' ),
			array( '_self' ),
		);
	}
}
