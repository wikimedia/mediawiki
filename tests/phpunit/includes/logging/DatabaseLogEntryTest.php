<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\IDatabase;

class DatabaseLogEntryTest extends MediaWikiTestCase {
	/**
	 * @covers       DatabaseLogEntry::newFromId
	 * @covers       DatabaseLogEntry::getSelectQueryData
	 *
	 * @dataProvider provideNewFromId
	 *
	 * @param int $id
	 * @param array $selectFields
	 * @param string[]|null $row
	 * @param string[]|null $expectedFields
	 * @param string $migration
	 */
	public function testNewFromId( $id,
		array $selectFields,
		array $row = null,
		array $expectedFields = null,
		$migration
	) {
		$this->setMwGlobals( [
			'wgCommentTableSchemaMigrationStage' => $migration,
			'wgActorTableSchemaMigrationStage' => $migration,
		] );

		// Thses services cache their joins
		MediaWikiServices::getInstance()->resetServiceForTesting( 'CommentStore' );
		MediaWikiServices::getInstance()->resetServiceForTesting( 'ActorMigration' );

		$row = $row ? (object)$row : null;
		$db = $this->getMock( IDatabase::class );
		$db->expects( self::once() )
			->method( 'selectRow' )
			->with( $selectFields['tables'],
				$selectFields['fields'],
				$selectFields['conds'],
				'DatabaseLogEntry::newFromId',
				$selectFields['options'],
				$selectFields['join_conds']
			)
			->will( self::returnValue( $row ) );

		/** @var IDatabase $db */
		$logEntry = DatabaseLogEntry::newFromId( $id, $db );

		if ( !$expectedFields ) {
			self::assertNull( $logEntry, "Expected no log entry returned for id=$id" );
		} else {
			self::assertEquals( $id, $logEntry->getId() );
			self::assertEquals( $expectedFields['type'], $logEntry->getType() );
			self::assertEquals( $expectedFields['comment'], $logEntry->getComment() );
		}
	}

	public function provideNewFromId() {
		$oldTables = [
			'tables' => [ 'logging', 'user' ],
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
				'user_editcount',
				'log_comment_text' => 'log_comment',
				'log_comment_data' => 'NULL',
				'log_comment_cid' => 'NULL',
				'log_user' => 'log_user',
				'log_user_text' => 'log_user_text',
				'log_actor' => 'NULL',
			],
			'options' => [],
			'join_conds' => [ 'user' => [ 'LEFT JOIN', 'user_id=log_user' ] ],
		];
		$newTables = [
			'tables' => [
				'logging',
				'user',
				'comment_log_comment' => 'comment',
				'actor_log_user' => 'actor'
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
				'user_editcount',
				'log_comment_text' => 'comment_log_comment.comment_text',
				'log_comment_data' => 'comment_log_comment.comment_data',
				'log_comment_cid' => 'comment_log_comment.comment_id',
				'log_user' => 'actor_log_user.actor_user',
				'log_user_text' => 'actor_log_user.actor_name',
				'log_actor' => 'log_actor',
			],
			'options' => [],
			'join_conds' => [
				'user' => [ 'LEFT JOIN', 'user_id=actor_log_user.actor_user' ],
				'comment_log_comment' => [ 'JOIN', 'comment_log_comment.comment_id = log_comment_id' ],
				'actor_log_user' => [ 'JOIN', 'actor_log_user.actor_id = log_actor' ],
			],
		];
		return [
			[
				0,
				$oldTables + [ 'conds' => [ 'log_id' => 0 ] ],
				null,
				null,
				MIGRATION_OLD,
			],
			[
				123,
				$oldTables + [ 'conds' => [ 'log_id' => 123 ] ],
				[
					'log_id' => 123,
					'log_type' => 'foobarize',
					'log_comment_text' => 'test!',
					'log_comment_data' => null,
				],
				[ 'type' => 'foobarize', 'comment' => 'test!' ],
				MIGRATION_OLD,
			],
			[
				567,
				$newTables + [ 'conds' => [ 'log_id' => 567 ] ],
				[
					'log_id' => 567,
					'log_type' => 'foobarize',
					'log_comment_text' => 'test!',
					'log_comment_data' => null,
				],
				[ 'type' => 'foobarize', 'comment' => 'test!' ],
				MIGRATION_NEW,
			],
		];
	}
}
