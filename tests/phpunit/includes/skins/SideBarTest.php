<?php

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Skin\SkinTemplate;
use MediaWiki\Title\Title;

/**
 * @covers \MediaWiki\Skin\Skin
 * @covers \MediaWiki\Skin\SkinTemplate
 * @group Skin
 * @group Database
 */
class SideBarTest extends MediaWikiLangTestCase {
	/** @var SkinTemplate */
	private $skin;
	/** @var string[][] Local cache for sidebar messages */
	private $messages;

	private function initMessagesHref() {
		# List of default messages for the sidebar. The sidebar doesn't care at
		# all whether they are full URLs, interwiki links or local titles.
		$URL_messages = [
			'mainpage',
			'portal-url',
			'currentevents-url',
			'recentchanges-url',
			'randompage-url',
			'helppage',
			'specialpages-url'
		];

		$messageCache = MediaWikiServices::getInstance()->getMessageCache();
		# We're assuming that isValidURI works as advertised: it's also
		# tested separately, in tests/phpunit/includes/HttpTest.php.
		foreach ( $URL_messages as $m ) {
			$titleName = $messageCache->get( $m );
			if ( MWHttpRequest::isValidURI( $titleName ) ) {
				$this->messages[$m]['href'] = $titleName;
			} else {
				$title = Title::newFromText( $titleName );
				$this->messages[$m]['href'] = $title->getLocalURL();
			}
		}
	}

	protected function setUp(): void {
		parent::setUp();
		$this->skin = new SkinTemplate();
		$this->skin->getContext()->setLanguage( 'en' );
	}

	/** @return array */
	public function provideSidebars() {
		$this->initMessagesHref();
		return [
			// sidebar with only two titles
			[
				[
					'Title1' => [],
					'Title2' => [],
				],
				'* Title1
* Title2
'
			],
			// expand messages
			[
				[ 'Title' => [
					[
						'text' => 'Help',
						'href' => $this->messages['helppage']['href'],
						'id' => 'n-help',
						'icon' => 'help',
						'active' => null
					]
				] ],
				'* Title
** helppage|help
'
			],
			// test tricky pipe - T35321 - Make sure there's a | after transforming.
			[
				[ 'Title' => [
					# The first 2 are skipped
					# Doesn't really test the url properly
					# because it will vary with $wgArticlePath et al.
					# ** Baz|Fred
					[
						'text' => 'Fred',
						'href' => Title::makeTitle( NS_MAIN, 'Baz' )->getLocalURL(),
						'id' => 'n-Fred',
						'active' => null,
						'icon' => null,
					],
					[
						'text' => 'title-to-display',
						'href' => Title::makeTitle( NS_MAIN, 'Page-to-go-to' )->getLocalURL(),
						'id' => 'n-title-to-display',
						'active' => null,
						'icon' => null,
					],
				] ],
				'* Title
** {{PAGENAME|Foo}}
** Bar
** Baz|Fred
** {{PLURAL:1|page-to-go-to{{int:pipe-separator/en}}title-to-display|branch not taken}}
'
			],

		];
	}

	/**
	 * @dataProvider provideSidebars
	 */
	public function testAddToSidebarPlain( $expected, $text, $message = '' ) {
		$bar = [];
		$this->skin->addToSidebarPlain( $bar, $text );
		$this->assertEquals( $expected, $bar, $message );
	}

	public function testExternalUrlsRequireADescription() {
		$this->overrideConfigValues( [
			MainConfigNames::NoFollowLinks => true,
			MainConfigNames::NoFollowDomainExceptions => [],
			MainConfigNames::NoFollowNsExceptions => [],
		] );

		$bar = [];
		$text = '* Title
** https://www.mediawiki.org/| Home
** http://valid.no.desc.org/
';
		$this->skin->addToSidebarPlain( $bar, $text );
		$this->assertEquals(
			[ 'Title' => [
				# ** https://www.mediawiki.org/| Home
				[
					'text' => 'Home',
					'href' => 'https://www.mediawiki.org/',
					'id' => 'n-Home',
					'active' => null,
					'icon' => null,
					'rel' => 'nofollow',
				],
				# ** http://valid.no.desc.org/
				# ... skipped since it is missing a pipe with a description
			] ],
			$bar
		);
	}

	public function testProtocolRelativeExternalUrl() {
		$this->overrideConfigValues( [
			MainConfigNames::NoFollowLinks => true,
			MainConfigNames::NoFollowDomainExceptions => [],
			MainConfigNames::NoFollowNsExceptions => [],
		] );

		$bar = [];
		$text = '* Title
** //www.mediawiki.org/| Home
';
		$this->skin->addToSidebarPlain( $bar, $text );
		$this->assertEquals(
			[ 'Title' => [
				# ** //www.mediawiki.org/| Home
				[
					'text' => 'Home',
					'href' => '//www.mediawiki.org/', // not /wiki///www.mediawiki.org/ (T364539)
					'id' => 'n-Home',
					'active' => null,
					'icon' => null,
					'rel' => 'nofollow',
				],
			] ],
			$bar
		);
	}

	private function getAttribs() {
		# Sidebar text we will use everytime
		$text = '* Title
** https://www.mediawiki.org/| Home';

		$bar = [];
		$this->skin->addToSidebarPlain( $bar, $text );

		return $bar['Title'][0];
	}

	/**
	 * Test our assertAttribs() helper function
	 * @coversNothing
	 */
	public function testTestAttributesAssertionHelper() {
		$this->overrideConfigValues( [
			MainConfigNames::NoFollowLinks => true,
			MainConfigNames::NoFollowDomainExceptions => [],
			MainConfigNames::NoFollowNsExceptions => [],
			MainConfigNames::ExternalLinkTarget => false,
		] );
		$attribs = $this->getAttribs();

		$this->assertArrayHasKey( 'rel', $attribs );
		$this->assertEquals( 'nofollow', $attribs['rel'] );

		$this->assertArrayNotHasKey( 'target', $attribs );
	}

	/**
	 * Test $wgNoFollowLinks in sidebar
	 */
	public function testRespectWgnofollowlinks() {
		$this->overrideConfigValue( MainConfigNames::NoFollowLinks, false );

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
		$this->overrideConfigValue( MainConfigNames::ExternalLinkTarget, $externalLinkTarget );

		$attribs = $this->getAttribs();
		$this->assertArrayHasKey( 'target', $attribs );
		$this->assertEquals( $attribs['target'], $externalLinkTarget );
	}

	public static function dataRespectExternallinktarget() {
		return [
			[ '_blank' ],
			[ '_self' ],
		];
	}
}
