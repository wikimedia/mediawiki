<?php

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

	public function __construct( $name = null, array $data = [], $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );

		$this->testRecentChangesHelper = new TestRecentChangesHelper();
	}

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( [
			'wgArticlePath' => '/wiki/$1'
		] );
	}

	/**
	 * @dataProvider editChangeProvider
	 */
	public function testNewFromRecentChange( $expected, $context, $messages,
		$recentChange, $watched
	) {
		$cacheEntryFactory = new RCCacheEntryFactory( $context, $messages );
		$cacheEntry = $cacheEntryFactory->newFromRecentChange( $recentChange, $watched );

		$this->assertInstanceOf( 'RCCacheEntry', $cacheEntry );

		$this->assertEquals( $watched, $cacheEntry->watched, 'watched' );
		$this->assertEquals( $expected['timestamp'], $cacheEntry->timestamp, 'timestamp' );
		$this->assertEquals(
			$expected['numberofWatchingusers'], $cacheEntry->numberofWatchingusers,
			'watching users'
		);
		$this->assertEquals( $expected['unpatrolled'], $cacheEntry->unpatrolled, 'unpatrolled' );

		$this->assertUserLinks( 'TestRecentChangesUser', $cacheEntry );
		$this->assertTitleLink( 'Xyz', $cacheEntry );

		$this->assertQueryLink( 'cur', $expected['cur'], $cacheEntry->curlink, 'cur link' );
		$this->assertQueryLink( 'prev', $expected['diff'], $cacheEntry->lastlink, 'prev link' );
		$this->assertQueryLink( 'diff', $expected['diff'], $cacheEntry->difflink, 'diff link' );
	}

	public function editChangeProvider() {
		return [
			[
				[
					'title' => 'Xyz',
					'user' => 'TestRecentChangesUser',
					'diff' => [ 'curid' => 5, 'diff' => 191, 'oldid' => 190 ],
					'cur' => [ 'curid' => 5, 'diff' => 0, 'oldid' => 191 ],
					'timestamp' => '21:21',
					'numberofWatchingusers' => 0,
					'unpatrolled' => false
				],
				$this->getContext(),
				$this->getMessages(),
				$this->testRecentChangesHelper->makeEditRecentChange(
					$this->getTestUser(),
					'Xyz',
					5, // curid
					191, // thisid
					190, // lastid
					'20131103212153',
					0, // counter
					0 // number of watching users
				),
				false
			]
		];
	}

	/**
	 * @dataProvider deleteChangeProvider
	 */
	public function testNewForDeleteChange( $expected, $context, $messages, $recentChange, $watched ) {
		$cacheEntryFactory = new RCCacheEntryFactory( $context, $messages );
		$cacheEntry = $cacheEntryFactory->newFromRecentChange( $recentChange, $watched );

		$this->assertInstanceOf( 'RCCacheEntry', $cacheEntry );

		$this->assertEquals( $watched, $cacheEntry->watched, 'watched' );
		$this->assertEquals( $expected['timestamp'], $cacheEntry->timestamp, 'timestamp' );
		$this->assertEquals(
			$expected['numberofWatchingusers'],
			$cacheEntry->numberofWatchingusers, 'watching users'
		);
		$this->assertEquals( $expected['unpatrolled'], $cacheEntry->unpatrolled, 'unpatrolled' );

		$this->assertDeleteLogLink( $cacheEntry );
		$this->assertUserLinks( 'TestRecentChangesUser', $cacheEntry );

		$this->assertEquals( 'cur', $cacheEntry->curlink, 'cur link for delete log or rev' );
		$this->assertEquals( 'diff', $cacheEntry->difflink, 'diff link for delete log or rev' );
		$this->assertEquals( 'prev', $cacheEntry->lastlink, 'pref link for delete log or rev' );
	}

	public function deleteChangeProvider() {
		return [
			[
				[
					'title' => 'Abc',
					'user' => 'TestRecentChangesUser',
					'timestamp' => '21:21',
					'numberofWatchingusers' => 0,
					'unpatrolled' => false
				],
				$this->getContext(),
				$this->getMessages(),
				$this->testRecentChangesHelper->makeLogRecentChange(
					'delete',
					'delete',
					$this->getTestUser(),
					'Abc',
					'20131103212153',
					0, // counter
					0 // number of watching users
				),
				false
			]
		];
	}

	/**
	 * @dataProvider revUserDeleteProvider
	 */
	public function testNewForRevUserDeleteChange( $expected, $context, $messages,
		$recentChange, $watched
	) {
		$cacheEntryFactory = new RCCacheEntryFactory( $context, $messages );
		$cacheEntry = $cacheEntryFactory->newFromRecentChange( $recentChange, $watched );

		$this->assertInstanceOf( 'RCCacheEntry', $cacheEntry );

		$this->assertEquals( $watched, $cacheEntry->watched, 'watched' );
		$this->assertEquals( $expected['timestamp'], $cacheEntry->timestamp, 'timestamp' );
		$this->assertEquals(
			$expected['numberofWatchingusers'],
			$cacheEntry->numberofWatchingusers, 'watching users'
		);
		$this->assertEquals( $expected['unpatrolled'], $cacheEntry->unpatrolled, 'unpatrolled' );

		$this->assertRevDel( $cacheEntry );
		$this->assertTitleLink( 'Zzz', $cacheEntry );

		$this->assertEquals( 'cur', $cacheEntry->curlink, 'cur link for delete log or rev' );
		$this->assertEquals( 'diff', $cacheEntry->difflink, 'diff link for delete log or rev' );
		$this->assertEquals( 'prev', $cacheEntry->lastlink, 'pref link for delete log or rev' );
	}

	public function revUserDeleteProvider() {
		return [
			[
				[
					'title' => 'Zzz',
					'user' => 'TestRecentChangesUser',
					'diff' => '',
					'cur' => '',
					'timestamp' => '21:21',
					'numberofWatchingusers' => 0,
					'unpatrolled' => false
				],
				$this->getContext(),
				$this->getMessages(),
				$this->testRecentChangesHelper->makeDeletedEditRecentChange(
					$this->getTestUser(),
					'Zzz',
					'20131103212153',
					191, // thisid
					190, // lastid
					'20131103212153',
					0, // counter
					0 // number of watching users
				),
				false
			]
		];
	}

	private function assertUserLinks( $user, $cacheEntry ) {
		$this->assertTag(
			[
				'tag' => 'a',
				'attributes' => [
					'class' => 'new mw-userlink'
				],
				'content' => $user
			],
			$cacheEntry->userlink,
			'verify user link'
		);

		$this->assertTag(
			[
				'tag' => 'span',
				'attributes' => [
					'class' => 'mw-usertoollinks'
				],
				'child' => [
					'tag' => 'a',
					'content' => 'talk',
				]
			],
			$cacheEntry->usertalklink,
			'verify user talk link'
		);

		$this->assertTag(
			[
				'tag' => 'span',
				'attributes' => [
					'class' => 'mw-usertoollinks'
				],
				'child' => [
					'tag' => 'a',
					'content' => 'contribs',
				]
			],
			$cacheEntry->usertalklink,
			'verify user tool links'
		);
	}

	private function assertDeleteLogLink( $cacheEntry ) {
		$this->assertTag(
			[
				'tag' => 'a',
				'attributes' => [
					'href' => '/wiki/Special:Log/delete',
					'title' => 'Special:Log/delete'
				],
				'content' => 'Deletion log'
			],
			$cacheEntry->link,
			'verify deletion log link'
		);
	}

	private function assertRevDel( $cacheEntry ) {
		$this->assertTag(
			[
				'tag' => 'span',
				'attributes' => [
					'class' => 'history-deleted'
				],
				'content' => '(username removed)'
			],
			$cacheEntry->userlink,
			'verify user link for change with deleted revision and user'
		);
	}

	private function assertTitleLink( $title, $cacheEntry ) {
		$this->assertTag(
			[
				'tag' => 'a',
				'attributes' => [
					'href' => '/wiki/' . $title,
					'title' => $title
				],
				'content' => $title
			],
			$cacheEntry->link,
			'verify title link'
		);
	}

	private function assertQueryLink( $content, $params, $link ) {
		$this->assertTag(
			[
				'tag' => 'a',
				'content' => $content
			],
			$link,
			'assert query link element'
		);

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

	private function getTestUser() {
		$user = User::newFromName( 'TestRecentChangesUser' );

		if ( !$user->getId() ) {
			$user->addToDatabase();
		}

		return $user;
	}

	private function getContext() {
		$user = $this->getTestUser();
		$context = $this->testRecentChangesHelper->getTestContext( $user );

		$title = Title::newFromText( 'RecentChanges', NS_SPECIAL );
		$context->setTitle( $title );

		return $context;
	}
}
