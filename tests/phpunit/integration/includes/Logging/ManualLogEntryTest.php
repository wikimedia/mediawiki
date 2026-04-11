<?php

namespace MediaWiki\Tests\Integration\Logging;

use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Logging\LogEntryBase;
use MediaWiki\Logging\LogPage;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\User\ActorStore;
use MediaWiki\User\ActorStoreFactory;
use MediaWiki\User\UserIdentityValue;
use MediaWikiIntegrationTestCase;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\InsertQueryBuilder;
use Wikimedia\Rdbms\SelectQueryBuilder;
use Wikimedia\ScopedCallback;

/**
 * @group Database
 * @covers \MediaWiki\Logging\ManualLogEntry
 */
class ManualLogEntryTest extends MediaWikiIntegrationTestCase {
	/** @dataProvider provideCreationAndPublishingToRecentChanges */
	public function testCreationAndPublishingToRecentChanges( bool $userHasBotRight ) {
		$performer = $this->getTestUser()->getUser();
		$target = $this->getExistingTestPage()->getTitle();
		$logParams = [ '4::test' => 'testabc1234', 'testing' => 'testingabc' ];

		// Give the performer the bot right temporarily to allow testing that ::publish
		// (if specified by this test case), so that we can test that ::publish sets the RecentChanges bot flag
		// outside any DeferredUpdates to prevent issues such as described in T387659.
		if ( $userHasBotRight ) {
			$userRightScope = $this->getServiceContainer()->getPermissionManager()
				->addTemporaryUserRights( $performer, 'bot' );
		}

		// Create a log entry and publish it to just RecentChanges
		$logEntry = new ManualLogEntry( 'phpunit', 'test' );
		$logEntry->setPerformer( $performer );
		$logEntry->setTarget( $target );
		$logEntry->setComment( 'A very good reason' );
		$logEntry->setTimestamp( '20300405060708' );
		$logEntry->setParameters( $logParams );
		$logEntry->setDeleted( LogPage::DELETED_ACTION );
		$logId = $logEntry->insert();
		$logEntry->publish( $logId, 'rc' );
		ScopedCallback::consume( $userRightScope );

		// Actually cause the writes to RecentChanges, as they are queued using DeferredUpdates.
		DeferredUpdates::doUpdates();

		// Assert that the log entry exists and that it matches what we provided above.
		$this->newSelectQueryBuilder()
			->select( [
				'log_type', 'log_action', 'log_timestamp', 'log_namespace', 'log_title', 'log_deleted',
				'log_actor', 'comment_text'
			] )
			->from( 'logging' )
			->join( 'comment', null, 'log_comment_id=comment_id' )
			->where( [ 'log_id' => $logId ] )
			->caller( __METHOD__ )
			->assertRowValue( [
				'phpunit', 'test', $this->getDb()->timestamp( '20300405060708' ), $target->getNamespace(),
				$target->getDBkey(), LogPage::DELETED_ACTION, $performer->getActorId(), 'A very good reason',
			] );
		$this->assertArrayEquals(
			$logParams,
			LogEntryBase::extractParams(
				$this->newSelectQueryBuilder()
				->select( 'log_params' )
				->from( 'logging' )
				->caller( __METHOD__ )
				->where( [ 'log_id' => $logId ] )
				->fetchField(),
				'phpunit/test'
			),
			false,
			true
		);

		// Assert that the entry was sent to RecentChanges
		$actualRecentChangeObject = $this->getServiceContainer()
			->getRecentChangeLookup()
			->getRecentChangeByConds( [ 'rc_logid' => $logId ] );
		$actualRecentChangeTitle = $actualRecentChangeObject->getPage();
		$this->assertNotNull( $actualRecentChangeTitle );
		$this->assertTrue( $target->isSamePageAs( $actualRecentChangeTitle ) );
		$this->assertTrue( $performer->equals( $actualRecentChangeObject->getPerformerIdentity() ) );
		$this->assertArrayContains(
			[
				// DB stores booleans as integers and we get back that integer as a string.
				'rc_bot' => (string)intval( $userHasBotRight ),
				'rc_log_type' => 'phpunit',
				'rc_log_action' => 'test',
				// The RecentChanges object stores the timestamp as TS::MW, even if DB stores it in a different
				// format.
				'rc_timestamp' => '20300405060708',
				'rc_deleted' => (string)LogPage::DELETED_ACTION,
			],
			$actualRecentChangeObject->getAttributes()
		);
	}

	public static function provideCreationAndPublishingToRecentChanges() {
		return [
			'User does not have bot right' => [ false ],
			'User temporarily has the bot right' => [ true ],
		];
	}

	/**
	 * @covers \MediaWiki\Logging\ManualLogEntry::insert
	 */
	public function testInsertAnotherWiki() {
		$realDb = $this->getServiceContainer()->getConnectionProvider()->getPrimaryDatabase();
		$realActorStore = $this->getServiceContainer()->getActorStore();

		$mockDb = $this->createMock( IDatabase::class );
		$mockDb->method( 'insertId' )->willReturn( 9999 );
		$mockDb->method( 'getDomainID' )->willReturn( 'anotherwiki' );
		$mockDb->method( 'newInsertQueryBuilder' )->willReturn( new InsertQueryBuilder( $mockDb ) );
		$mockDb->method( 'newSelectQueryBuilder' )->willReturn( new SelectQueryBuilder( $mockDb ) );

		$this->overrideMwServices( null, [
			'ActorStoreFactory' => function () {
				$mockAS = $this->createMock( ActorStore::class );
				$mockAS->expects( $this->once() )->method( 'acquireActorId' );
				$mockASF = $this->createMock( ActorStoreFactory::class );
				$mockASF->expects( $this->once() )->method( 'getActorStore' )->with( 'anotherwiki' )->willReturn( $mockAS );
				return $mockASF;
			},
		] );

		$logEntry = new ManualLogEntry( 'phpunit', 'test' );
		$logEntry->setPerformer( new UserIdentityValue( 676767, 'AnotherWikiTest', 'anotherwiki' ) );
		$logEntry->setTarget( new PageIdentityValue( 767676, NS_MAIN, 'AnotherWikiTest', 'anotherwiki' ) );
		$logId = $logEntry->insert( $mockDb );
		$this->assertEquals( 9999, $logId );

		// Check that we didn't create a local actor (T398177)
		$found = $realActorStore->findActorIdByName( 'AnotherWikiTest', $realDb );
		$this->assertNull( $found );
		// Check that we didn't create a local log entry
		$found = $realDb->newSelectQueryBuilder()
			->from( 'logging' )->select( 'log_id' )->where( [ 'log_page' => 767676 ] )->fetchField();
		$this->assertFalse( $found );
	}
}
