<?php

/**
 * @covers RCCacheEntryBuilder
 *
 * @group Database
 *
 * @licence GNU GPL v2+
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class RCCacheEntryBuilderTest extends MediaWikiLangTestCase {

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( array(
			'wgArticlePath' => '/wiki/$1'
		) );
	}

	/**
	 * @dataProvider buildProvider
	 */
	public function testBuild( $expected, $context, $messages, $recentChange, $watched, $type ) {
		$cacheEntryBuilder = new RCCacheEntryBuilder( $context, $messages );
		$cacheEntry = $cacheEntryBuilder->build( $recentChange, $watched );

		$expected['watched'] = $watched;

		$this->assertInstanceOf( 'RCCacheEntry', $cacheEntry );
		$this->assertChangeValues( $expected, $cacheEntry, $type );
	}

	public function buildProvider() {
		$context = $this->getContext();
		$messages = $this->getMessages();

		return array(
			array(
				array(
					'title' => 'Xyz',
					'user' => 'Mary',
					'diff' => array( 'curid' => 5, 'diff' => 191, 'oldid' => 190 ),
					'cur' => array( 'curid' => 5, 'diff' => 0, 'oldid' => 191 ),
					'timestamp' => '21:21',
					'numberofWatchingusers' => 0,
					'unpatrolled' => false
				),
				$context,
				$messages,
				$this->makeEditRecentChange(
					'Xyz',
					$this->getTestUser(),
					5, // curid
					191, // thisid
					190, // lastid
					'20131103212153',
					0, // counter
					0 // number of watching users
				),
				false,
				'edit'
			),
			array(
				array(
					'title' => 'Abc',
					'user' => 'Mary',
					'timestamp' => '21:21',
					'numberofWatchingusers' => 0,
					'unpatrolled' => false
				),
				$context,
				$messages,
				$this->makeLogRecentChange(
					'Abc',
					$this->getTestUser(),
					'20131103212153',
					0, // counter
					0 // number of watching users
				),
				false,
				'delete'
			),
			array(
				array(
					'title' => 'Zzz',
					'user' => 'Mary',
					'diff' => '',
					'cur' => '',
					'timestamp' => '21:21',
					'numberofWatchingusers' => 0,
					'unpatrolled' => false
				),
				$context,
				$messages,
				$this->makeDeletedEditRecentChange(
					'Zzz',
					$this->getTestUser(),
					'20131103212153',
					191, // thisid
					190, // lastid
					'20131103212153',
					0, // counter
					0 // number of watching users
				),
				false,
				'deletedrevuser'
			)
		);
	}

	protected function assertChangeValues( $expected, $cacheEntry, $type ) {
		$this->assertEquals( $expected['watched'], $cacheEntry->watched );
		$this->assertEquals( $expected['timestamp'], $cacheEntry->timestamp );
		$this->assertEquals( $expected['numberofWatchingusers'], $cacheEntry->numberofWatchingusers );
		$this->assertEquals( $expected['unpatrolled'], $cacheEntry->unpatrolled );

		if ( $type === 'deletedrevuser' ) {
			$this->assertTag(
				array(
					'tag' => 'span',
					'attributes' => array(
						'class' => 'history-deleted'
					),
					'content' => '(username removed)'
				), $cacheEntry->userlink
			);
		} else {
			$this->assertTag(
				array(
					'tag' => 'a',
					'attributes' => array(
						'class' => 'new mw-userlink'
					),
					'content' => $expected['user']
				), $cacheEntry->userlink
			);

			$this->assertTag(
				array(
					'tag' => 'span',
					'attributes' => array(
						'class' => 'mw-usertoollinks'
					),
					'child' => array(
						'tag' => 'a',
						'content' => 'Talk',
					)
				), $cacheEntry->usertalklink
			);

			$this->assertTag(
				array(
					'tag' => 'span',
					'attributes' => array(
						'class' => 'mw-usertoollinks'
					),
					'child' => array(
						'tag' => 'a',
						'content' => 'contribs',
					)
				), $cacheEntry->usertalklink
			);
		}

		if ( $type === 'delete' ) {
			$this->assertTag(
				array(
					'tag' => 'a',
					'attributes' => array(
						'href' => '/wiki/Special:Log/delete',
						'title' => 'Special:Log/delete'
					),
					'content' => 'Deletion log'
				), $cacheEntry->link
			);
		} else {
			$this->assertTag(
				array(
					'tag' => 'a',
					'attributes' => array(
						'href' => '/wiki/' . $expected['title'],
						'title' => $expected['title']
					),
					'content' => $expected['title']
				), $cacheEntry->link
			);
		}

		if ( $type === 'delete' || $type === 'deletedrevuser' ) {
			$this->assertEquals( 'cur', $cacheEntry->curlink );
			$this->assertEquals( 'diff', $cacheEntry->difflink );
			$this->assertEquals( 'prev', $cacheEntry->lastlink );
		} else {
			$this->assertQueryLink( 'cur', $expected['cur'], $cacheEntry->curlink );
			$this->assertQueryLink( 'prev', $expected['diff'], $cacheEntry->lastlink );
			$this->assertQueryLink( 'diff', $expected['diff'], $cacheEntry->difflink );
		}
	}

	protected function assertQueryLink( $content, $params, $link ) {
		$this->assertTag(
			array(
				'tag' => 'a',
				'content' => $content
			), $link, $link
		);

		foreach( $params as $key => $value ) {
			$this->assertRegExp( '/' . $key . '=' . $value . '/', $link );
		}
	}

	protected function makeRecentChange( $attribs, $counter, $watchingUsers ) {
		$change = new RecentChange();
		$change->setAttribs( $attribs );
		$change->counter = $counter;
		$change->numberofWatchingusers = $watchingUsers;

		return $change;
	}

	protected function makeEditRecentChange( $title, $user, $curid, $thisid, $lastid,
		$timestamp, $counter, $watchingUsers
	) {

		$attribs = array_merge(
			$this->getDefaultAttributes( $title, $timestamp ),
			array(
				'rc_user' => $user->getId(),
				'rc_user_text' => $user->getName(),
				'rc_this_oldid' => $thisid,
				'rc_last_oldid' => $lastid,
				'rc_cur_id' => $curid
			)
		);

		return $this->makeRecentChange( $attribs, $counter, $watchingUsers );
	}

	protected function makeLogRecentChange( $title, $user, $timestamp, $counter, $watchingUsers ) {
		$attribs = array_merge(
			$this->getDefaultAttributes( $title, $timestamp ),
			array(
				'rc_cur_id' => 0,
				'rc_user' => $user->getId(),
				'rc_user_text' => $user->getName(),
				'rc_this_oldid' => 0,
				'rc_last_oldid' => 0,
				'rc_old_len' => null,
				'rc_new_len' => null,
				'rc_type' => 3,
				'rc_logid' => 25,
				'rc_log_type' => 'delete',
				'rc_log_action' => 'delete'
			)
		);

		return $this->makeRecentChange( $attribs, $counter, $watchingUsers );
	}

	protected function makeDeletedEditRecentChange( $title, $user, $timestamp, $curid, $thisid,
		$lastid, $counter, $watchingUsers
	) {
		$attribs = array_merge(
			$this->getDefaultAttributes( $title, $timestamp ),
			array(
				'rc_user' => $user->getId(),
				'rc_user_text' => $user->getName(),
				'rc_deleted' => 5,
				'rc_cur_id' => $curid,
				'rc_this_oldid' => $thisid,
				'rc_last_oldid' => $lastid
			)
		);

		return $this->makeRecentChange( $attribs, $counter, $watchingUsers );
	}

	protected function getDefaultAttributes( $title, $timestamp ) {
		return array(
			'rc_id' => 545,
			'rc_user' => 0,
			'rc_user_text' => '127.0.0.1',
			'rc_ip' => '127.0.0.1',
			'rc_title' => $title,
			'rc_namespace' => 0,
			'rc_timestamp' => $timestamp,
			'rc_cur_time' => $timestamp,
			'rc_old_len' => 212,
			'rc_new_len' => 188,
			'rc_comment' => '',
			'rc_minor' => 0,
			'rc_bot' => 0,
			'rc_type' => 0,
			'rc_patrolled' => 1,
			'rc_deleted' => 0,
			'rc_logid' => 0,
			'rc_log_type' => null,
			'rc_log_action' => '',
			'rc_params' => '',
			'rc_source' => 'mw.edit'
		);
	}

	private function getTestUser() {
		$user = User::newFromName( 'Mary' );

		if ( ! $user->getId() ) {
			$user->addToDatabase();
		}

		return $user;
	}

	private function getMessages() {
		return array(
			'cur' => 'cur',
			'diff' => 'diff',
			'hist' => 'hist',
			'enhancedrc-history' => 'history',
			'last' => 'prev',
			'blocklink' => 'block',
			'history' => 'Page history',
			'semicolon-separator' => '; ',
			'pipe-separator' => ' | '
		);
	}

	private function getContext() {
		$title = Title::newFromText( 'RecentChanges', NS_SPECIAL );

		$context = new RequestContext();
		$context->setTitle( $title );
		$context->setLanguage( Language::factory( 'en' ) );

		$user = $this->getTestUser();
		$context->setUser( $user );

		return $context;
	}

}
