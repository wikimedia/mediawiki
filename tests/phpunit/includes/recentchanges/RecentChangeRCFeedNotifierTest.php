<?php

use MediaWiki\MainConfigNames;
use MediaWiki\RecentChanges\RecentChange;

/**
 * @group Database
 */
class RecentChangeRCFeedNotifierTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::CanonicalServer => 'https://example.org',
			MainConfigNames::ServerName => 'example.org',
			MainConfigNames::ScriptPath => '/w',
			MainConfigNames::Script => '/w/index.php',
			MainConfigNames::UseRCPatrol => false,
			MainConfigNames::UseNPPatrol => false,
			MainConfigNames::RCFeeds => [],
			MainConfigNames::RCEngines => [],
		] );
	}

	/**
	 * @covers \MediaWiki\RecentChanges\RecentChangeRCFeedNotifier::getNotifyUrl
	 */
	public function testGetNotifyUrlForEdit() {
		$rc = new RecentChange;
		$rc->setAttribs( [
			'rc_id' => 60,
			'rc_timestamp' => '20110401090000',
			'rc_namespace' => NS_MAIN,
			'rc_title' => 'Example',
			'rc_type' => RC_EDIT,
			'rc_source' => RecentChange::SRC_EDIT,
			'rc_cur_id' => 42,
			'rc_this_oldid' => 50,
			'rc_last_oldid' => 30,
			'rc_patrolled' => 0,
		] );
		$this->assertSame(
			'https://example.org/w/index.php?diff=50&oldid=30',
			$this->getServiceContainer()->getRecentChangeRCFeedNotifier()->getNotifyUrl( $rc ),
			'Notify url'
		);

		$this->overrideConfigValue( MainConfigNames::UseRCPatrol, true );
		$this->assertSame(
			'https://example.org/w/index.php?diff=50&oldid=30&rcid=60',
			$this->getServiceContainer()->getRecentChangeRCFeedNotifier()->getNotifyUrl( $rc ),
			'Notify url (RC Patrol)'
		);
	}

	/**
	 * @covers \MediaWiki\RecentChanges\RecentChangeRCFeedNotifier::getNotifyUrl
	 */
	public function testGetNotifyUrlForCreate() {
		$rc = new RecentChange;
		$rc->setAttribs( [
			'rc_id' => 60,
			'rc_timestamp' => '20110401090000',
			'rc_namespace' => NS_MAIN,
			'rc_title' => 'Example',
			'rc_type' => RC_NEW,
			'rc_source' => RecentChange::SRC_NEW,
			'rc_cur_id' => 42,
			'rc_this_oldid' => 50,
			'rc_last_oldid' => 0,
			'rc_patrolled' => 0,
		] );
		$this->assertSame(
			'https://example.org/w/index.php?oldid=50',
			$this->getServiceContainer()->getRecentChangeRCFeedNotifier()->getNotifyUrl( $rc ),
			'Notify url'
		);

		$this->overrideConfigValue( MainConfigNames::UseNPPatrol, true );
		$this->assertSame(
			'https://example.org/w/index.php?oldid=50&rcid=60',
			$this->getServiceContainer()->getRecentChangeRCFeedNotifier()->getNotifyUrl( $rc ),
			'Notify url (NP Patrol)'
		);
	}

	/**
	 * @covers \MediaWiki\RecentChanges\RecentChangeRCFeedNotifier::getNotifyUrl
	 */
	public function testGetNotifyUrlForLog() {
		$rc = new RecentChange;
		$rc->setAttribs( [
			'rc_id' => 60,
			'rc_timestamp' => '20110401090000',
			'rc_namespace' => NS_MAIN,
			'rc_title' => 'Example',
			'rc_type' => RC_LOG,
			'rc_source' => RecentChange::SRC_LOG,
			'rc_cur_id' => 42,
			'rc_this_oldid' => 50,
			'rc_last_oldid' => 0,
			'rc_patrolled' => 2,
			'rc_logid' => 160,
			'rc_log_type' => 'delete',
			'rc_log_action' => 'delete',
		] );
		$this->assertSame(
			null,
			$this->getServiceContainer()->getRecentChangeRCFeedNotifier()->getNotifyUrl( $rc ),
			'Notify url'
		);
	}
}
