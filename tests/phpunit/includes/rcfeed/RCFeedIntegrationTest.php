<?php

/**
 * @group Database
 */
class RCFeedIntegrationTest extends MediaWikiTestCase {
	protected function setUp() {
		parent::setUp();
		$this->setMwGlobals( [
			'wgCanonicalServer' => 'https://example.org',
			'wgServerName' => 'example.org',
			'wgScriptPath' => '/w',
			'wgDBname' => 'example',
			'wgDBprefix' => '',
			'wgRCFeeds' => [],
			'wgRCEngines' => [],
		] );
	}

	/**
	 * @covers RecentChange::notifyRCFeeds
	 * @covers RecentChange::getEngine
	 * @covers RCFeed::factory
	 * @covers FormattedRCFeed::__construct
	 * @covers FormattedRCFeed::notify
	 * @covers JSONRCFeedFormatter::formatArray
	 * @covers MachineReadableRCFeedFormatter::getLine
	 */
	public function testNotify() {
		$feed = $this->getMockBuilder( 'RCFeedEngine' )
			->setConstructorArgs( [ [ 'formatter' => 'JSONRCFeedFormatter' ] ] )
			->setMethods( [ 'send' ] )
			->getMock();

		$feed->method( 'send' )
			->willReturn( true );

		$feed->expects( $this->once() )
			->method( 'send' )
			->with( $this->anything(), $this->callback( function ( $line ) {
				$this->assertJsonStringEqualsJsonString(
					json_encode( [
						'id' => null,
						'type' => 'log',
						'namespace' => 0,
						'title' => 'Example',
						'comment' => '',
						'timestamp' => 1301644800,
						'user' => 'UTSysop',
						'bot' => false,
						'log_id' => 0,
						'log_type' => 'move',
						'log_action' => 'move',
						'log_params' => [
							'color' => 'green',
							'nr' => 42,
							'pet' => 'cat',
						],
						'log_action_comment' => '',
						'server_url' => 'https://example.org',
						'server_name' => 'example.org',
						'server_script_path' => '/w',
						'wiki' => 'example',
					] ),
					$line
				);
				return true;
			} ) );

		$this->setMwGlobals( [
			'wgRCFeeds' => [
				'myfeed' => [
					'uri' => 'test://localhost:1234',
					'formatter' => 'JSONRCFeedFormatter',
				],
			],
			'wgRCEngines' => [
				'test' => $feed,
			],
		] );
		$logpage = SpecialPage::getTitleFor( 'Log', 'move' );
		$user = $this->getTestSysop()->getUser();
		$rc = RecentChange::newLogEntry(
			'20110401080000',
			$logpage, // &$title
			$user, // &$user
			'', // $actionComment
			'127.0.0.1', // $ip
			'move', // $type
			'move', // $action
			Title::makeTitle( 0, 'Example' ), // $target
			'', // $logComment
			LogEntryBase::makeParamBlob( [
				'4::color' => 'green',
				'5:number:nr' => 42,
				'pet' => 'cat',
			] )
		);
		$rc->notifyRCFeeds();
	}
}
