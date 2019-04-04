<?php

use MediaWiki\User\UserIdentity;
use MediaWiki\MediaWikiServices;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Database
 * @covers ActorMigration
 */
class ActorMigrationTest extends MediaWikiLangTestCase {

	protected $tablesUsed = [
		'revision',
		'revision_actor_temp',
		'ipblocks',
		'recentchanges',
		'actor',
	];

	/**
	 * Create an ActorMigration for a particular stage
	 * @param int $stage
	 * @return ActorMigration
	 */
	protected function makeMigration( $stage ) {
		return new ActorMigration( $stage );
	}

	/**
	 * @dataProvider provideConstructor
	 * @param int $stage
	 * @param string|null $exceptionMsg
	 */
	public function testConstructor( $stage, $exceptionMsg ) {
		try {
			$m = new ActorMigration( $stage );
			if ( $exceptionMsg !== null ) {
				$this->fail( 'Expected exception not thrown' );
			}
			$this->assertInstanceOf( ActorMigration::class, $m );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame( $exceptionMsg, $ex->getMessage() );
		}
	}

	public static function provideConstructor() {
		return [
			[ 0, '$stage must include a write mode' ],
			[ SCHEMA_COMPAT_READ_OLD, '$stage must include a write mode' ],
			[ SCHEMA_COMPAT_READ_NEW, '$stage must include a write mode' ],
			[ SCHEMA_COMPAT_READ_BOTH, '$stage must include a write mode' ],

			[ SCHEMA_COMPAT_WRITE_OLD, '$stage must include a read mode' ],
			[ SCHEMA_COMPAT_WRITE_OLD | SCHEMA_COMPAT_READ_OLD, null ],
			[
				SCHEMA_COMPAT_WRITE_OLD | SCHEMA_COMPAT_READ_NEW,
				'Cannot read the new schema without also writing it'
			],
			[ SCHEMA_COMPAT_WRITE_OLD | SCHEMA_COMPAT_READ_BOTH, 'Cannot read both schemas' ],

			[ SCHEMA_COMPAT_WRITE_NEW, '$stage must include a read mode' ],
			[
				SCHEMA_COMPAT_WRITE_NEW | SCHEMA_COMPAT_READ_OLD,
				'Cannot read the old schema without also writing it'
			],
			[ SCHEMA_COMPAT_WRITE_NEW | SCHEMA_COMPAT_READ_NEW, null ],
			[ SCHEMA_COMPAT_WRITE_NEW | SCHEMA_COMPAT_READ_BOTH, 'Cannot read both schemas' ],

			[ SCHEMA_COMPAT_WRITE_BOTH, '$stage must include a read mode' ],
			[ SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD, null ],
			[ SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW, null ],
			[ SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_BOTH, 'Cannot read both schemas' ],
		];
	}

	/**
	 * @dataProvider provideGetJoin
	 * @param int $stage
	 * @param string $key
	 * @param array $expect
	 */
	public function testGetJoin( $stage, $key, $expect ) {
		$m = $this->makeMigration( $stage );
		$result = $m->getJoin( $key );
		$this->assertEquals( $expect, $result );
	}

	public static function provideGetJoin() {
		return [
			'Simple table, old' => [
				SCHEMA_COMPAT_OLD, 'rc_user', [
					'tables' => [],
					'fields' => [
						'rc_user' => 'rc_user',
						'rc_user_text' => 'rc_user_text',
						'rc_actor' => 'NULL',
					],
					'joins' => [],
				],
			],
			'Simple table, read-old' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD, 'rc_user', [
					'tables' => [],
					'fields' => [
						'rc_user' => 'rc_user',
						'rc_user_text' => 'rc_user_text',
						'rc_actor' => 'NULL',
					],
					'joins' => [],
				],
			],
			'Simple table, read-new' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW, 'rc_user', [
					'tables' => [ 'actor_rc_user' => 'actor' ],
					'fields' => [
						'rc_user' => 'actor_rc_user.actor_user',
						'rc_user_text' => 'actor_rc_user.actor_name',
						'rc_actor' => 'rc_actor',
					],
					'joins' => [
						'actor_rc_user' => [ 'JOIN', 'actor_rc_user.actor_id = rc_actor' ],
					],
				],
			],
			'Simple table, new' => [
				SCHEMA_COMPAT_NEW, 'rc_user', [
					'tables' => [ 'actor_rc_user' => 'actor' ],
					'fields' => [
						'rc_user' => 'actor_rc_user.actor_user',
						'rc_user_text' => 'actor_rc_user.actor_name',
						'rc_actor' => 'rc_actor',
					],
					'joins' => [
						'actor_rc_user' => [ 'JOIN', 'actor_rc_user.actor_id = rc_actor' ],
					],
				],
			],

			'ipblocks, old' => [
				SCHEMA_COMPAT_OLD, 'ipb_by', [
					'tables' => [],
					'fields' => [
						'ipb_by' => 'ipb_by',
						'ipb_by_text' => 'ipb_by_text',
						'ipb_by_actor' => 'NULL',
					],
					'joins' => [],
				],
			],
			'ipblocks, read-old' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD, 'ipb_by', [
					'tables' => [],
					'fields' => [
						'ipb_by' => 'ipb_by',
						'ipb_by_text' => 'ipb_by_text',
						'ipb_by_actor' => 'NULL',
					],
					'joins' => [],
				],
			],
			'ipblocks, read-new' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW, 'ipb_by', [
					'tables' => [ 'actor_ipb_by' => 'actor' ],
					'fields' => [
						'ipb_by' => 'actor_ipb_by.actor_user',
						'ipb_by_text' => 'actor_ipb_by.actor_name',
						'ipb_by_actor' => 'ipb_by_actor',
					],
					'joins' => [
						'actor_ipb_by' => [ 'JOIN', 'actor_ipb_by.actor_id = ipb_by_actor' ],
					],
				],
			],
			'ipblocks, new' => [
				SCHEMA_COMPAT_NEW, 'ipb_by', [
					'tables' => [ 'actor_ipb_by' => 'actor' ],
					'fields' => [
						'ipb_by' => 'actor_ipb_by.actor_user',
						'ipb_by_text' => 'actor_ipb_by.actor_name',
						'ipb_by_actor' => 'ipb_by_actor',
					],
					'joins' => [
						'actor_ipb_by' => [ 'JOIN', 'actor_ipb_by.actor_id = ipb_by_actor' ],
					],
				],
			],

			'Revision, old' => [
				SCHEMA_COMPAT_OLD, 'rev_user', [
					'tables' => [],
					'fields' => [
						'rev_user' => 'rev_user',
						'rev_user_text' => 'rev_user_text',
						'rev_actor' => 'NULL',
					],
					'joins' => [],
				],
			],
			'Revision, read-old' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD, 'rev_user', [
					'tables' => [],
					'fields' => [
						'rev_user' => 'rev_user',
						'rev_user_text' => 'rev_user_text',
						'rev_actor' => 'NULL',
					],
					'joins' => [],
				],
			],
			'Revision, read-new' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW, 'rev_user', [
					'tables' => [
						'temp_rev_user' => 'revision_actor_temp',
						'actor_rev_user' => 'actor',
					],
					'fields' => [
						'rev_user' => 'actor_rev_user.actor_user',
						'rev_user_text' => 'actor_rev_user.actor_name',
						'rev_actor' => 'temp_rev_user.revactor_actor',
					],
					'joins' => [
						'temp_rev_user' => [ 'JOIN', 'temp_rev_user.revactor_rev = rev_id' ],
						'actor_rev_user' => [ 'JOIN', 'actor_rev_user.actor_id = temp_rev_user.revactor_actor' ],
					],
				],
			],
			'Revision, new' => [
				SCHEMA_COMPAT_NEW, 'rev_user', [
					'tables' => [
						'temp_rev_user' => 'revision_actor_temp',
						'actor_rev_user' => 'actor',
					],
					'fields' => [
						'rev_user' => 'actor_rev_user.actor_user',
						'rev_user_text' => 'actor_rev_user.actor_name',
						'rev_actor' => 'temp_rev_user.revactor_actor',
					],
					'joins' => [
						'temp_rev_user' => [ 'JOIN', 'temp_rev_user.revactor_rev = rev_id' ],
						'actor_rev_user' => [ 'JOIN', 'actor_rev_user.actor_id = temp_rev_user.revactor_actor' ],
					],
				],
			],
		];
	}

	/**
	 * @dataProvider provideGetWhere
	 * @param int $stage
	 * @param string $key
	 * @param UserIdentity[] $users
	 * @param bool $useId
	 * @param array $expect
	 */
	public function testGetWhere( $stage, $key, $users, $useId, $expect ) {
		$expect['conds'] = '(' . implode( ') OR (', $expect['orconds'] ) . ')';

		if ( count( $users ) === 1 ) {
			$users = reset( $users );
		}

		$m = $this->makeMigration( $stage );
		$result = $m->getWhere( $this->db, $key, $users, $useId );
		$this->assertEquals( $expect, $result );
	}

	public function provideGetWhere() {
		$makeUserIdentity = function ( $id, $name, $actor ) {
			$u = $this->getMock( UserIdentity::class );
			$u->method( 'getId' )->willReturn( $id );
			$u->method( 'getName' )->willReturn( $name );
			$u->method( 'getActorId' )->willReturn( $actor );
			return $u;
		};

		$genericUser = [ $makeUserIdentity( 1, 'User1', 11 ) ];
		$complicatedUsers = [
			$makeUserIdentity( 1, 'User1', 11 ),
			$makeUserIdentity( 2, 'User2', 12 ),
			$makeUserIdentity( 3, 'User3', 0 ),
			$makeUserIdentity( 0, '192.168.12.34', 34 ),
			$makeUserIdentity( 0, '192.168.12.35', 0 ),
		];

		return [
			'Simple table, old' => [
				SCHEMA_COMPAT_OLD, 'rc_user', $genericUser, true, [
					'tables' => [],
					'orconds' => [ 'userid' => "rc_user = '1'" ],
					'joins' => [],
				],
			],
			'Simple table, read-old' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD, 'rc_user', $genericUser, true, [
					'tables' => [],
					'orconds' => [ 'userid' => "rc_user = '1'" ],
					'joins' => [],
				],
			],
			'Simple table, read-new' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW, 'rc_user', $genericUser, true, [
					'tables' => [],
					'orconds' => [ 'actor' => "rc_actor = '11'" ],
					'joins' => [],
				],
			],
			'Simple table, new' => [
				SCHEMA_COMPAT_NEW, 'rc_user', $genericUser, true, [
					'tables' => [],
					'orconds' => [ 'actor' => "rc_actor = '11'" ],
					'joins' => [],
				],
			],

			'ipblocks, old' => [
				SCHEMA_COMPAT_OLD, 'ipb_by', $genericUser, true, [
					'tables' => [],
					'orconds' => [ 'userid' => "ipb_by = '1'" ],
					'joins' => [],
				],
			],
			'ipblocks, read-old' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD, 'ipb_by', $genericUser, true, [
					'tables' => [],
					'orconds' => [ 'userid' => "ipb_by = '1'" ],
					'joins' => [],
				],
			],
			'ipblocks, read-new' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW, 'ipb_by', $genericUser, true, [
					'tables' => [],
					'orconds' => [ 'actor' => "ipb_by_actor = '11'" ],
					'joins' => [],
				],
			],
			'ipblocks, new' => [
				SCHEMA_COMPAT_NEW, 'ipb_by', $genericUser, true, [
					'tables' => [],
					'orconds' => [ 'actor' => "ipb_by_actor = '11'" ],
					'joins' => [],
				],
			],

			'Revision, old' => [
				SCHEMA_COMPAT_OLD, 'rev_user', $genericUser, true, [
					'tables' => [],
					'orconds' => [ 'userid' => "rev_user = '1'" ],
					'joins' => [],
				],
			],
			'Revision, read-old' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD, 'rev_user', $genericUser, true, [
					'tables' => [],
					'orconds' => [ 'userid' => "rev_user = '1'" ],
					'joins' => [],
				],
			],
			'Revision, read-new' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW, 'rev_user', $genericUser, true, [
					'tables' => [
						'temp_rev_user' => 'revision_actor_temp',
					],
					'orconds' => [ 'actor' => "temp_rev_user.revactor_actor = '11'" ],
					'joins' => [
						'temp_rev_user' => [ 'JOIN', 'temp_rev_user.revactor_rev = rev_id' ],
					],
				],
			],
			'Revision, new' => [
				SCHEMA_COMPAT_NEW, 'rev_user', $genericUser, true, [
					'tables' => [
						'temp_rev_user' => 'revision_actor_temp',
					],
					'orconds' => [ 'actor' => "temp_rev_user.revactor_actor = '11'" ],
					'joins' => [
						'temp_rev_user' => [ 'JOIN', 'temp_rev_user.revactor_rev = rev_id' ],
					],
				],
			],

			'Multiple users, old' => [
				SCHEMA_COMPAT_OLD, 'rc_user', $complicatedUsers, true, [
					'tables' => [],
					'orconds' => [
						'userid' => "rc_user IN ('1','2','3') ",
						'username' => "rc_user_text IN ('192.168.12.34','192.168.12.35') "
					],
					'joins' => [],
				],
			],
			'Multiple users, read-old' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD, 'rc_user', $complicatedUsers, true, [
					'tables' => [],
					'orconds' => [
						'userid' => "rc_user IN ('1','2','3') ",
						'username' => "rc_user_text IN ('192.168.12.34','192.168.12.35') "
					],
					'joins' => [],
				],
			],
			'Multiple users, read-new' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW, 'rc_user', $complicatedUsers, true, [
					'tables' => [],
					'orconds' => [ 'actor' => "rc_actor IN ('11','12','34') " ],
					'joins' => [],
				],
			],
			'Multiple users, new' => [
				SCHEMA_COMPAT_NEW, 'rc_user', $complicatedUsers, true, [
					'tables' => [],
					'orconds' => [ 'actor' => "rc_actor IN ('11','12','34') " ],
					'joins' => [],
				],
			],

			'Multiple users, no use ID, old' => [
				SCHEMA_COMPAT_OLD, 'rc_user', $complicatedUsers, false, [
					'tables' => [],
					'orconds' => [
						'username' => "rc_user_text IN ('User1','User2','User3','192.168.12.34','192.168.12.35') "
					],
					'joins' => [],
				],
			],
			'Multiple users, read-old' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD, 'rc_user', $complicatedUsers, false, [
					'tables' => [],
					'orconds' => [
						'username' => "rc_user_text IN ('User1','User2','User3','192.168.12.34','192.168.12.35') "
					],
					'joins' => [],
				],
			],
			'Multiple users, read-new' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW, 'rc_user', $complicatedUsers, false, [
					'tables' => [],
					'orconds' => [ 'actor' => "rc_actor IN ('11','12','34') " ],
					'joins' => [],
				],
			],
			'Multiple users, new' => [
				SCHEMA_COMPAT_NEW, 'rc_user', $complicatedUsers, false, [
					'tables' => [],
					'orconds' => [ 'actor' => "rc_actor IN ('11','12','34') " ],
					'joins' => [],
				],
			],
		];
	}

	/**
	 * @dataProvider provideInsertRoundTrip
	 * @param string $table
	 * @param string $key
	 * @param string $pk
	 * @param array $extraFields
	 */
	public function testInsertRoundTrip( $table, $key, $pk, $extraFields ) {
		$u = $this->getTestUser()->getUser();
		$user = $this->getMock( UserIdentity::class );
		$user->method( 'getId' )->willReturn( $u->getId() );
		$user->method( 'getName' )->willReturn( $u->getName() );
		if ( $u->getActorId( $this->db ) ) {
			$user->method( 'getActorId' )->willReturn( $u->getActorId() );
		} else {
			$this->db->insert(
				'actor',
				[ 'actor_user' => $u->getId(), 'actor_name' => $u->getName() ],
				__METHOD__
			);
			$user->method( 'getActorId' )->willReturn( $this->db->insertId() );
		}

		$stageNames = [
			SCHEMA_COMPAT_OLD => 'old',
			SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD => 'write-both-read-old',
			SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW => 'write-both-read-new',
			SCHEMA_COMPAT_NEW => 'new',
		];

		$stages = [
			SCHEMA_COMPAT_OLD => [
				SCHEMA_COMPAT_OLD,
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD,
			],
			SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD => [
				SCHEMA_COMPAT_OLD,
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD,
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW,
				SCHEMA_COMPAT_NEW
			],
			SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW => [
				SCHEMA_COMPAT_OLD,
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD,
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW,
				SCHEMA_COMPAT_NEW
			],
			SCHEMA_COMPAT_NEW => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW,
				SCHEMA_COMPAT_NEW
			],
		];

		$nameKey = $key . '_text';
		$actorKey = $key === 'ipb_by' ? 'ipb_by_actor' : substr( $key, 0, -5 ) . '_actor';

		foreach ( $stages as $writeStage => $possibleReadStages ) {
			if ( $key === 'ipb_by' ) {
				$extraFields['ipb_address'] = __CLASS__ . "#{$stageNames[$writeStage]}";
			}

			$w = $this->makeMigration( $writeStage );
			$usesTemp = $key === 'rev_user';

			if ( $usesTemp ) {
				list( $fields, $callback ) = $w->getInsertValuesWithTempTable( $this->db, $key, $user );
			} else {
				$fields = $w->getInsertValues( $this->db, $key, $user );
			}

			if ( $writeStage & SCHEMA_COMPAT_WRITE_OLD ) {
				$this->assertSame( $user->getId(), $fields[$key],
					"old field, stage={$stageNames[$writeStage]}" );
				$this->assertSame( $user->getName(), $fields[$nameKey],
					"old field, stage={$stageNames[$writeStage]}" );
			} else {
				$this->assertArrayNotHasKey( $key, $fields, "old field, stage={$stageNames[$writeStage]}" );
				$this->assertArrayNotHasKey( $nameKey, $fields, "old field, stage={$stageNames[$writeStage]}" );
			}
			if ( ( $writeStage & SCHEMA_COMPAT_WRITE_NEW ) && !$usesTemp ) {
				$this->assertSame( $user->getActorId(), $fields[$actorKey],
					"new field, stage={$stageNames[$writeStage]}" );
			} else {
				$this->assertArrayNotHasKey( $actorKey, $fields,
					"new field, stage={$stageNames[$writeStage]}" );
			}

			$this->db->insert( $table, $extraFields + $fields, __METHOD__ );
			$id = $this->db->insertId();
			if ( $usesTemp ) {
				$callback( $id, $extraFields );
			}

			foreach ( $possibleReadStages as $readStage ) {
				$r = $this->makeMigration( $readStage );

				$queryInfo = $r->getJoin( $key );
				$row = $this->db->selectRow(
					[ $table ] + $queryInfo['tables'],
					$queryInfo['fields'],
					[ $pk => $id ],
					__METHOD__,
					[],
					$queryInfo['joins']
				);

				$this->assertSame( $user->getId(), (int)$row->$key,
					"w={$stageNames[$writeStage]}, r={$stageNames[$readStage]}, id" );
				$this->assertSame( $user->getName(), $row->$nameKey,
					"w={$stageNames[$writeStage]}, r={$stageNames[$readStage]}, name" );
				$this->assertSame(
					( $readStage & SCHEMA_COMPAT_READ_OLD ) ? 0 : $user->getActorId(),
					(int)$row->$actorKey,
					"w={$stageNames[$writeStage]}, r={$stageNames[$readStage]}, actor"
				);
			}
		}
	}

	public static function provideInsertRoundTrip() {
		$db = wfGetDB( DB_REPLICA ); // for timestamps

		$comment = MediaWikiServices::getInstance()->getCommentStore()
			->createComment( wfGetDB( DB_MASTER ), '' );

		return [
			'recentchanges' => [ 'recentchanges', 'rc_user', 'rc_id', [
				'rc_timestamp' => $db->timestamp(),
				'rc_namespace' => 0,
				'rc_title' => 'Test',
				'rc_this_oldid' => 42,
				'rc_last_oldid' => 41,
				'rc_source' => 'test',
				'rc_comment_id' => $comment->id,
			] ],
			'ipblocks' => [ 'ipblocks', 'ipb_by', 'ipb_id', [
				'ipb_range_start' => '',
				'ipb_range_end' => '',
				'ipb_timestamp' => $db->timestamp(),
				'ipb_expiry' => $db->getInfinity(),
				'ipb_reason_id' => $comment->id,
			] ],
			'revision' => [ 'revision', 'rev_user', 'rev_id', [
				'rev_page' => 42,
				'rev_len' => 0,
				'rev_timestamp' => $db->timestamp(),
			] ],
		];
	}

	public static function provideStages() {
		return [
			'old' => [ SCHEMA_COMPAT_OLD ],
			'read-old' => [ SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD ],
			'read-new' => [ SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW ],
			'new' => [ SCHEMA_COMPAT_NEW ],
		];
	}

	/**
	 * @dataProvider provideStages
	 * @param int $stage
	 * @expectedException InvalidArgumentException
	 * @expectedExceptionMessage Must use getInsertValuesWithTempTable() for rev_user
	 */
	public function testInsertWrong( $stage ) {
		$m = $this->makeMigration( $stage );
		$m->getInsertValues( $this->db, 'rev_user', $this->getTestUser()->getUser() );
	}

	/**
	 * @dataProvider provideStages
	 * @param int $stage
	 * @expectedException InvalidArgumentException
	 * @expectedExceptionMessage Must use getInsertValues() for rc_user
	 */
	public function testInsertWithTempTableWrong( $stage ) {
		$m = $this->makeMigration( $stage );
		$m->getInsertValuesWithTempTable( $this->db, 'rc_user', $this->getTestUser()->getUser() );
	}

	/**
	 * @dataProvider provideStages
	 * @param int $stage
	 */
	public function testInsertWithTempTableDeprecated( $stage ) {
		$wrap = TestingAccessWrapper::newFromClass( ActorMigration::class );
		$wrap->formerTempTables += [ 'rc_user' => '1.30' ];

		$this->hideDeprecated( 'ActorMigration::getInsertValuesWithTempTable for rc_user' );
		$m = $this->makeMigration( $stage );
		list( $fields, $callback )
			= $m->getInsertValuesWithTempTable( $this->db, 'rc_user', $this->getTestUser()->getUser() );
		$this->assertTrue( is_callable( $callback ) );
	}

	/**
	 * @dataProvider provideStages
	 * @param int $stage
	 * @expectedException InvalidArgumentException
	 * @expectedExceptionMessage $extra[rev_timestamp] is not provided
	 */
	public function testInsertWithTempTableCallbackMissingFields( $stage ) {
		$m = $this->makeMigration( $stage );
		list( $fields, $callback )
			= $m->getInsertValuesWithTempTable( $this->db, 'rev_user', $this->getTestUser()->getUser() );
		$callback( 1, [] );
	}

	/**
	 * @dataProvider provideStages
	 * @param int $stage
	 */
	public function testInsertUserIdentity( $stage ) {
		$this->setMwGlobals( [
			// for User::getActorId()
			'wgActorTableSchemaMigrationStage' => $stage
		] );
		$this->overrideMwServices();

		$user = $this->getMutableTestUser()->getUser();
		$userIdentity = $this->getMock( UserIdentity::class );
		$userIdentity->method( 'getId' )->willReturn( $user->getId() );
		$userIdentity->method( 'getName' )->willReturn( $user->getName() );
		$userIdentity->method( 'getActorId' )->willReturn( 0 );

		list( $cFields, $cCallback ) = MediaWikiServices::getInstance()->getCommentStore()
			->insertWithTempTable( $this->db, 'rev_comment', '' );
		$m = $this->makeMigration( $stage );
		list( $fields, $callback ) =
			$m->getInsertValuesWithTempTable( $this->db, 'rev_user', $userIdentity );
		$extraFields = [
			'rev_page' => 42,
			'rev_len' => 0,
			'rev_timestamp' => $this->db->timestamp(),
		] + $cFields;
		$this->db->insert( 'revision', $extraFields + $fields, __METHOD__ );
		$id = $this->db->insertId();
		$callback( $id, $extraFields );
		$cCallback( $id );

		$qi = $m->getJoin( 'rev_user' );
		$row = $this->db->selectRow(
			[ 'revision' ] + $qi['tables'], $qi['fields'], [ 'rev_id' => $id ], __METHOD__, [], $qi['joins']
		);
		$this->assertSame( $user->getId(), (int)$row->rev_user );
		$this->assertSame( $user->getName(), $row->rev_user_text );
		$this->assertSame(
			( $stage & SCHEMA_COMPAT_READ_NEW ) ? $user->getActorId() : 0,
			(int)$row->rev_actor
		);

		$m = $this->makeMigration( $stage );
		$fields = $m->getInsertValues( $this->db, 'dummy_user', $userIdentity );
		if ( $stage & SCHEMA_COMPAT_WRITE_OLD ) {
			$this->assertSame( $user->getId(), $fields['dummy_user'] );
			$this->assertSame( $user->getName(), $fields['dummy_user_text'] );
		} else {
			$this->assertArrayNotHasKey( 'dummy_user', $fields );
			$this->assertArrayNotHasKey( 'dummy_user_text', $fields );
		}
		if ( $stage & SCHEMA_COMPAT_WRITE_NEW ) {
			$this->assertSame( $user->getActorId(), $fields['dummy_actor'] );
		} else {
			$this->assertArrayNotHasKey( 'dummy_actor', $fields );
		}
	}

	public function testNewMigration() {
		$m = ActorMigration::newMigration();
		$this->assertInstanceOf( ActorMigration::class, $m );
		$this->assertSame( $m, ActorMigration::newMigration() );
	}

	/**
	 * @dataProvider provideIsAnon
	 * @param int $stage
	 * @param string $isAnon
	 * @param string $isNotAnon
	 */
	public function testIsAnon( $stage, $isAnon, $isNotAnon ) {
		$m = $this->makeMigration( $stage );
		$this->assertSame( $isAnon, $m->isAnon( 'foo' ) );
		$this->assertSame( $isNotAnon, $m->isNotAnon( 'foo' ) );
	}

	public static function provideIsAnon() {
		return [
			'old' => [ SCHEMA_COMPAT_OLD, 'foo = 0', 'foo != 0' ],
			'read-old' => [ SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD, 'foo = 0', 'foo != 0' ],
			'read-new' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW, 'foo IS NULL', 'foo IS NOT NULL'
			],
			'new' => [ SCHEMA_COMPAT_NEW, 'foo IS NULL', 'foo IS NOT NULL' ],
		];
	}

}
