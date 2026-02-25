<?php

use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\Logging\DatabaseLogEntry;
use MediaWiki\User\ActorStore;
use MediaWiki\User\ActorStoreFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * @group Database
 */
class DatabaseLogEntryTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers \MediaWiki\Logging\DatabaseLogEntry::newFromId
	 * @covers \MediaWiki\Logging\DatabaseLogEntry::getSelectQueryData
	 *
	 * @dataProvider provideNewFromId
	 *
	 * @param int $id
	 * @param array $selectFields
	 * @param string[]|null $row
	 * @param string[]|null $expectedFields
	 */
	public function testNewFromId( $id,
		array $selectFields,
		?array $row = null,
		?array $expectedFields = null
	) {
		$row = $row ? (object)$row : null;
		$db = $this->createMock( IReadableDatabase::class );
		$db->expects( self::once() )
			->method( 'selectRow' )
			->with( $selectFields['tables'],
				$selectFields['fields'],
				$selectFields['conds'],
				$this->isType( 'string' ),
				$selectFields['options'],
				$selectFields['join_conds']
			)
			->willReturn( $row );
		$db->expects( self::once() )
			->method( 'getDomainID' )
			->willReturn( WikiMap::getCurrentWikiDbDomain() );

		/** @var IReadableDatabase $db */
		$logEntry = DatabaseLogEntry::newFromId( $id, $db );

		if ( !$expectedFields ) {
			self::assertNull( $logEntry, "Expected no log entry returned for id=$id" );
		} else {
			self::assertEquals( $id, $logEntry->getId() );
			self::assertEquals( $expectedFields['type'], $logEntry->getType() );
			self::assertEquals( $expectedFields['comment'], $logEntry->getComment() );
			self::assertEquals(
				WikiAwareEntity::LOCAL,
				$logEntry->getTargetPage()->getWikiId()
			);
			self::assertEquals(
				WikiAwareEntity::LOCAL,
				$logEntry->getPerformerIdentity()->getWikiId()
			);
		}
	}

	public static function provideNewFromId() {
		$newTables = [
			'tables' => [
				'logging',
				'comment_log_comment' => 'comment',
				'logging_actor' => 'actor',
				'user' => 'user',
			],
			'fields' => [
				'log_id',
				'log_type',
				'log_action',
				'log_timestamp',
				'log_namespace',
				'log_title',
				'log_params',
				'log_deleted',
				'user_id',
				'user_name',
				'log_comment_text' => 'comment_log_comment.comment_text',
				'log_comment_data' => 'comment_log_comment.comment_data',
				'log_comment_cid' => 'comment_log_comment.comment_id',
				'log_user' => 'logging_actor.actor_user',
				'log_user_text' => 'logging_actor.actor_name',
				'log_actor',
			],
			'options' => [],
			'join_conds' => [
				'user' => [ 'LEFT JOIN', 'user_id=logging_actor.actor_user' ],
				'comment_log_comment' => [ 'JOIN', 'comment_log_comment.comment_id = log_comment_id' ],
				'logging_actor' => [ 'JOIN', 'actor_id=log_actor' ],
			],
		];

		$defaults = [
			'log_namespace' => NS_MAIN,
			'log_title' => 'TestPage',
		];

		return [
			[
				0,
				$newTables + [ 'conds' => [ 'log_id' => 0 ] ],
				null,
				null
			],
			[
				123,
				$newTables + [ 'conds' => [ 'log_id' => 123 ] ],
				[
					'log_id' => 123,
					'log_type' => 'foobarize',
					'log_comment_text' => 'test!',
					'log_comment_data' => null,
				] + $defaults,
				[ 'type' => 'foobarize', 'comment' => 'test!' ]
			],
			[
				567,
				$newTables + [ 'conds' => [ 'log_id' => 567 ] ],
				[
					'log_id' => 567,
					'log_type' => 'foobarize',
					'log_comment_text' => 'test!',
					'log_comment_data' => null,
				] + $defaults,
				[ 'type' => 'foobarize', 'comment' => 'test!' ]
			],
		];
	}

	public static function provideGetPerformerIdentity() {
		yield 'registered actor' => [
			'actorRowFields' => [
				'user_id' => 42,
				'log_user_text' => 'Testing',
				'log_actor' => 24,
			],
			UserIdentityValue::newRegistered( 42, 'Testing' ),
		];
		yield 'anon actor' => [
			'actorRowFields' => [
				'log_user_text' => '127.0.0.1',
				'log_actor' => 24,
			],
			UserIdentityValue::newAnonymous( '127.0.0.1' ),
		];
		yield 'unknown actor' => [
			'actorRowFields' => [],
			new UserIdentityValue( 0, ActorStore::UNKNOWN_USER_NAME ),
		];
	}

	/**
	 * @dataProvider provideGetPerformerIdentity
	 * @covers \MediaWiki\Logging\DatabaseLogEntry::getPerformerIdentity
	 */
	public function testGetPerformer( array $actorRowFields, UserIdentity $expected ) {
		$logEntry = DatabaseLogEntry::newFromRow( [
			'log_id' => 1,
		] + $actorRowFields );
		$performer = $logEntry->getPerformerIdentity();
		$this->assertTrue( $expected->equals( $performer ) );
	}

	/**
	 * @covers \MediaWiki\Logging\DatabaseLogEntry::newFromRow
	 */
	public function testNewFromRowAnotherWiki() {
		$realDb = $this->getServiceContainer()->getConnectionProvider()->getPrimaryDatabase();
		$realActorStore = $this->getServiceContainer()->getActorStore();

		$this->overrideMwServices( null, [
			'ActorStoreFactory' => function () {
				$mockAS = $this->createMock( ActorStore::class );
				$mockAS->expects( $this->once() )->method( 'newActorFromRowFields' )->willReturn(
					UserIdentityValue::newRegistered( 42, 'Testing', 'anotherwiki' ) );
				$mockASF = $this->createMock( ActorStoreFactory::class );
				$mockASF->expects( $this->once() )->method( 'getActorStore' )->with( 'anotherwiki' )->willReturn( $mockAS );
				return $mockASF;
			},
		] );

		$row = (object)[
			'log_id' => 9999,
			'log_type' => 'phpunit',
			'log_action' => 'test',
			'log_timestamp' => time(),
			'log_page' => 767676,
			'log_namespace' => 0,
			'log_title' => 'AnotherWikiTest',
			'log_params' => null,
			'log_deleted' => 0,
		];
		$logEntry = DatabaseLogEntry::newFromRow( $row, 'anotherwiki' );
		$user = $logEntry->getPerformerIdentity();
		$this->assertEquals( 'anotherwiki', $user->getWikiId() );
		// Check that we didn't create a local actor (T398177)
		$found = $realActorStore->findActorIdByName( 'AnotherWikiTest', $realDb );
		$this->assertNull( $found );
	}

	/**
	 * @covers \MediaWiki\Logging\DatabaseLogEntry::newFromId
	 */
	public function testNewFromIdAnotherWiki() {
		$realDb = $this->getServiceContainer()->getConnectionProvider()->getPrimaryDatabase();
		$realActorStore = $this->getServiceContainer()->getActorStore();

		$row = (object)[
			'log_id' => 9999,
			'log_type' => 'phpunit',
			'log_action' => 'test',
			'log_timestamp' => time(),
			'log_page' => 767676,
			'log_namespace' => 0,
			'log_title' => 'AnotherWikiTest',
			'log_params' => null,
			'log_deleted' => 0,
		];

		$mockDb = $this->createMock( IDatabase::class );
		$mockDb->method( 'insertId' )->willReturn( 9999 );
		$mockDb->method( 'getDomainID' )->willReturn( 'anotherwiki' );
		$mockDb->method( 'selectRow' )->willReturn( $row );

		$this->overrideMwServices( null, [
			'ActorStoreFactory' => function () {
				$mockAS = $this->createMock( ActorStore::class );
				$mockAS->expects( $this->once() )->method( 'newActorFromRowFields' )->willReturn(
					UserIdentityValue::newRegistered( 42, 'Testing', 'anotherwiki' ) );
				$mockASF = $this->createMock( ActorStoreFactory::class );
				$mockASF->expects( $this->once() )->method( 'getActorStore' )->with( 'anotherwiki' )->willReturn( $mockAS );
				return $mockASF;
			},
		] );

		$logEntry = DatabaseLogEntry::newFromId( 9999, $mockDb );
		$this->assertEquals( 'anotherwiki', $logEntry->getPerformerIdentity()->getWikiId() );
		$this->assertEquals( 'anotherwiki', $logEntry->getTargetPage()->getWikiId() );
	}
}
