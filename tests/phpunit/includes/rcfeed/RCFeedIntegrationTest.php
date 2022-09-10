<?php

use MediaWiki\MainConfigNames;

/**
 * @group medium
 * @group Database
 * @covers FormattedRCFeed
 * @covers RecentChange
 * @covers JSONRCFeedFormatter
 * @covers MachineReadableRCFeedFormatter
 * @covers RCFeed
 */
class RCFeedIntegrationTest extends MediaWikiIntegrationTestCase {
	protected function setUp(): void {
		parent::setUp();
		$this->overrideConfigValues( [
			MainConfigNames::CanonicalServer => 'https://example.org',
			MainConfigNames::ServerName => 'example.org',
			MainConfigNames::ScriptPath => '/w',
			MainConfigNames::DBname => 'example',
			MainConfigNames::DBprefix => $this->dbPrefix(),
			MainConfigNames::RCFeeds => [],
			MainConfigNames::RCEngines => [],
		] );
	}

	public function testNotify() {
		$feed = $this->getMockBuilder( FormattedRCFeed::class )
			->setConstructorArgs( [ [ 'formatter' => JSONRCFeedFormatter::class ] ] )
			->onlyMethods( [ 'send' ] )
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
						'wiki' => 'example-' . $this->dbPrefix(),
					] ),
					$line
				);
				return true;
			} ) );

		$this->overrideConfigValue(
			MainConfigNames::RCFeeds,
			[
				'myfeed' => [
					'class' => $feed,
					'uri' => 'test://localhost:1234',
					'formatter' => JSONRCFeedFormatter::class,
				],
			]
		);
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
