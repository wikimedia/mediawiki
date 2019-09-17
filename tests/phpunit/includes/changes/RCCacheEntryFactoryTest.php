<?php

use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MediaWikiServices;

/**
 * @covers RCCacheEntryFactory
 *
 * @group Database
 *
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class RCCacheEntryFactoryTest extends MediaWikiLangTestCase {

	/**
	 * @var TestRecentChangesHelper
	 */
	private $testRecentChangesHelper;

	/**
	 * @var LinkRenderer
	 */
	private $linkRenderer;

	public function __construct( $name = null, array $data = [], $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );

		$this->testRecentChangesHelper = new TestRecentChangesHelper();
	}

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( [
			'wgArticlePath' => '/wiki/$1'
		] );

		$this->linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();
	}

	public function testNewFromRecentChange() {
		$user = $this->getMutableTestUser()->getUser();
		$recentChange = $this->testRecentChangesHelper->makeEditRecentChange(
			$user,
			'Xyz',
			5, // curid
			191, // thisid
			190, // lastid
			'20131103212153',
			0, // counter
			0 // number of watching users
		);
		$cacheEntryFactory = new RCCacheEntryFactory(
			$this->getContext(),
			$this->getMessages(),
			$this->linkRenderer
		);
		$cacheEntry = $cacheEntryFactory->newFromRecentChange( $recentChange, false );

		$this->assertInstanceOf( RCCacheEntry::class, $cacheEntry );

		$this->assertEquals( false, $cacheEntry->watched, 'watched' );
		$this->assertEquals( '21:21', $cacheEntry->timestamp, 'timestamp' );
		$this->assertSame( 0, $cacheEntry->numberofWatchingusers, 'watching users' );
		$this->assertEquals( false, $cacheEntry->unpatrolled, 'unpatrolled' );

		$this->assertUserLinks( $user->getName(), $cacheEntry );
		$this->assertTitleLink( 'Xyz', $cacheEntry );

		$diff = [ 'curid' => 5, 'diff' => 191, 'oldid' => 190 ];
		$cur = [ 'curid' => 5, 'diff' => 0, 'oldid' => 191 ];
		$this->assertQueryLink( 'cur', $cur, $cacheEntry->curlink );
		$this->assertQueryLink( 'prev', $diff, $cacheEntry->lastlink );
		$this->assertQueryLink( 'diff', $diff, $cacheEntry->difflink );
	}

	public function testNewForDeleteChange() {
		$user = $this->getMutableTestUser()->getUser();
		$recentChange = $this->testRecentChangesHelper->makeLogRecentChange(
			'delete',
			'delete',
			$user,
			'Abc',
			'20131103212153',
			0, // counter
			0 // number of watching users
		);
		$cacheEntryFactory = new RCCacheEntryFactory(
			$this->getContext(),
			$this->getMessages(),
			$this->linkRenderer
		);
		$cacheEntry = $cacheEntryFactory->newFromRecentChange( $recentChange, false );

		$this->assertInstanceOf( RCCacheEntry::class, $cacheEntry );

		$this->assertEquals( false, $cacheEntry->watched, 'watched' );
		$this->assertEquals( '21:21', $cacheEntry->timestamp, 'timestamp' );
		$this->assertSame( 0, $cacheEntry->numberofWatchingusers, 'watching users' );
		$this->assertEquals( false, $cacheEntry->unpatrolled, 'unpatrolled' );

		$this->assertDeleteLogLink( $cacheEntry );
		$this->assertUserLinks( $user->getName(), $cacheEntry );

		$this->assertEquals( 'cur', $cacheEntry->curlink, 'cur link for delete log or rev' );
		$this->assertEquals( 'diff', $cacheEntry->difflink, 'diff link for delete log or rev' );
		$this->assertEquals( 'prev', $cacheEntry->lastlink, 'pref link for delete log or rev' );
	}

	public function testNewForRevUserDeleteChange() {
		$user = $this->getMutableTestUser()->getUser();
		$recentChange = $this->testRecentChangesHelper->makeDeletedEditRecentChange(
			$user,
			'Zzz',
			'20131103212153',
			191, // thisid
			190, // lastid
			'20131103212153',
			0, // counter
			0 // number of watching users
		);
		$cacheEntryFactory = new RCCacheEntryFactory(
			$this->getContext(),
			$this->getMessages(),
			$this->linkRenderer
		);
		$cacheEntry = $cacheEntryFactory->newFromRecentChange( $recentChange, false );

		$this->assertInstanceOf( RCCacheEntry::class, $cacheEntry );

		$this->assertEquals( false, $cacheEntry->watched, 'watched' );
		$this->assertEquals( '21:21', $cacheEntry->timestamp, 'timestamp' );
		$this->assertSame( 0, $cacheEntry->numberofWatchingusers, 'watching users' );
		$this->assertEquals( false, $cacheEntry->unpatrolled, 'unpatrolled' );

		$this->assertRevDel( $cacheEntry );
		$this->assertTitleLink( 'Zzz', $cacheEntry );

		$this->assertEquals( 'cur', $cacheEntry->curlink, 'cur link for delete log or rev' );
		$this->assertEquals( 'diff', $cacheEntry->difflink, 'diff link for delete log or rev' );
		$this->assertEquals( 'prev', $cacheEntry->lastlink, 'pref link for delete log or rev' );
	}

	private function assertValidHTML( $actual ) {
		// Throws if invalid
		$doc = PHPUnit_Util_XML::load( $actual, /* isHtml */ true );
	}

	private function assertUserLinks( $user, $cacheEntry ) {
		$this->assertValidHTML( $cacheEntry->userlink );
		$this->assertRegExp(
			'#^<a .*class="new mw-userlink".*><bdi>' . $user . '</bdi></a>#',
			$cacheEntry->userlink,
			'verify user link'
		);

		$this->assertValidHTML( $cacheEntry->usertalklink );
		$this->assertRegExp(
			'#^ <span class="mw-usertoollinks mw-changeslist-links">.*<span><a .+>talk</a></span>.*</span>#',
			$cacheEntry->usertalklink,
			'verify user talk link'
		);

		$this->assertValidHTML( $cacheEntry->usertalklink );
		$this->assertRegExp(
			'#^ <span class="mw-usertoollinks mw-changeslist-links">.*<span><a .+>' .
				'contribs</a></span>.*</span>$#',
			$cacheEntry->usertalklink,
			'verify user tool links'
		);
	}

	private function assertDeleteLogLink( $cacheEntry ) {
		$this->assertEquals(
			'(<a href="/wiki/Special:Log/delete" title="Special:Log/delete">Deletion log</a>)',
			$cacheEntry->link,
			'verify deletion log link'
		);

		$this->assertValidHTML( $cacheEntry->link );
	}

	private function assertRevDel( $cacheEntry ) {
		$this->assertEquals(
			' <span class="history-deleted">(username removed)</span>',
			$cacheEntry->userlink,
			'verify user link for change with deleted revision and user'
		);
		$this->assertValidHTML( $cacheEntry->userlink );
	}

	private function assertTitleLink( $title, $cacheEntry ) {
		$this->assertEquals(
			'<a href="/wiki/' . $title . '" title="' . $title . '">' . $title . '</a>',
			$cacheEntry->link,
			'verify title link'
		);
		$this->assertValidHTML( $cacheEntry->link );
	}

	private function assertQueryLink( $content, $params, $link ) {
		$this->assertRegExp(
			"#^<a .+>$content</a>$#",
			$link,
			'verify query link element'
		);
		$this->assertValidHTML( $link );

		foreach ( $params as $key => $value ) {
			$this->assertRegExp( '/' . $key . '=' . $value . '/', $link, "verify $key link params" );
		}
	}

	private function getMessages() {
		return [
			'cur' => 'cur',
			'diff' => 'diff',
			'hist' => 'hist',
			'enhancedrc-history' => 'history',
			'last' => 'prev',
			'blocklink' => 'block',
			'history' => 'Page history',
			'semicolon-separator' => '; ',
			'pipe-separator' => ' | '
		];
	}

	private function getContext() {
		$user = $this->getMutableTestUser()->getUser();
		$context = $this->testRecentChangesHelper->getTestContext( $user );

		$title = Title::newFromText( 'RecentChanges', NS_SPECIAL );
		$context->setTitle( $title );

		return $context;
	}
}
