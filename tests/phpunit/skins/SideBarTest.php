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
	/** Local cache for sidebar messages */
	private $messages;

	/** Build $this->messages array */
	private function initMessagesHref() {
		# List of default messages for the sidebar. The sidebar doesn't care at
		# all whether they are full URLs, interwiki links or local titles.
		$URL_messages = array(
			'mainpage',
			'portal-url',
			'currentevents-url',
			'recentchanges-url',
			'randompage-url',
			'helppage',
		);

		# We're assuming that isValidURI works as advertised: it's also
		# tested separately, in tests/phpunit/includes/HttpTest.php.
		foreach ( $URL_messages as $m ) {
			$titleName = MessageCache::singleton()->get( $m );
			if ( Http::isValidURI( $titleName ) ) {
				$this->messages[$m]['href'] = $titleName;
			} else {
				$title = Title::newFromText( $titleName );
				$this->messages[$m]['href'] = $title->getLocalURL();
			}
		}
	}

	protected function setUp() {
		parent::setUp();
		$this->initMessagesHref();
		$this->skin = new SkinTemplate();
		$this->skin->getContext()->setLanguage( Language::factory( 'en' ) );
	}

	/**
	 * Internal helper to test the sidebar
	 * @param array $expected
	 * @param string $text
	 * @param string $message (Default: '')
	 * @todo this assert method to should be converted to a test using a dataprovider..
	 */
	private function assertSideBar( $expected, $text, $message = '' ) {
		$bar = array();
		$this->skin->addToSidebarPlain( $bar, $text );
		$this->assertEquals( $expected, $bar, $message );
	}

	/**
	 * @covers SkinTemplate::addToSidebarPlain
	 */
	public function testSidebarWithOnlyTwoTitles() {
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

	/**
	 * @covers SkinTemplate::addToSidebarPlain
	 */
	public function testExpandMessages() {
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

	/**
	 * @covers SkinTemplate::addToSidebarPlain
	 */
	public function testExternalUrlsRequireADescription() {
		$this->setMwGlobals( array(
			'wgNoFollowLinks' => true,
			'wgNoFollowDomainExceptions' => array(),
			'wgNoFollowNsExceptions' => array(),
		) );
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
	 * @covers SkinTemplate::addToSidebarPlain
	 */
	public function testTrickyPipe() {
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
	public function testTestAttributesAssertionHelper() {
		$this->setMwGlobals( array(
			'wgNoFollowLinks' => true,
			'wgNoFollowDomainExceptions' => array(),
			'wgNoFollowNsExceptions' => array(),
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
	 * @dataProvider dataRespectExternallinktarget
	 */
	public function testRespectExternallinktarget( $externalLinkTarget ) {
		$this->setMwGlobals( 'wgExternalLinkTarget', $externalLinkTarget );

		$attribs = $this->getAttribs();
		$this->assertArrayHasKey( 'target', $attribs );
		$this->assertEquals( $attribs['target'], $externalLinkTarget );
	}

	public static function dataRespectExternallinktarget() {
		return array(
			array( '_blank' ),
			array( '_self' ),
		);
	}
}
