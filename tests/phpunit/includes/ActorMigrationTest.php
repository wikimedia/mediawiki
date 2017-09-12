<?php

use MediaWiki\User\UserIdentity;
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
				MIGRATION_OLD, 'rc_user', [
					'tables' => [],
					'fields' => [
						'rc_user' => 'rc_user',
						'rc_user_text' => 'rc_user_text',
						'rc_actor' => 'NULL',
					],
					'joins' => [],
				],
			],
			'Simple table, write-both' => [
				MIGRATION_WRITE_BOTH, 'rc_user', [
					'tables' => [ 'actor_rc_user' => 'actor' ],
					'fields' => [
						'rc_user' => 'COALESCE( actor_rc_user.actor_user, rc_user )',
						'rc_user_text' => 'COALESCE( actor_rc_user.actor_name, rc_user_text )',
						'rc_actor' => 'rc_actor',
					],
					'joins' => [
						'actor_rc_user' => [ 'LEFT JOIN', 'actor_rc_user.actor_id = rc_actor' ],
					],
				],
			],
			'Simple table, write-new' => [
				MIGRATION_WRITE_NEW, 'rc_user', [
					'tables' => [ 'actor_rc_user' => 'actor' ],
					'fields' => [
						'rc_user' => 'COALESCE( actor_rc_user.actor_user, rc_user )',
						'rc_user_text' => 'COALESCE( actor_rc_user.actor_name, rc_user_text )',
						'rc_actor' => 'rc_actor',
					],
					'joins' => [
						'actor_rc_user' => [ 'LEFT JOIN', 'actor_rc_user.actor_id = rc_actor' ],
					],
				],
			],
			'Simple table, new' => [
				MIGRATION_NEW, 'rc_user', [
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
				MIGRATION_OLD, 'ipb_by', [
					'tables' => [],
					'fields' => [
						'ipb_by' => 'ipb_by',
						'ipb_by_text' => 'ipb_by_text',
						'ipb_by_actor' => 'NULL',
					],
					'joins' => [],
				],
			],
			'ipblocks, write-both' => [
				MIGRATION_WRITE_BOTH, 'ipb_by', [
					'tables' => [ 'actor_ipb_by' => 'actor' ],
					'fields' => [
						'ipb_by' => 'COALESCE( actor_ipb_by.actor_user, ipb_by )',
						'ipb_by_text' => 'COALESCE( actor_ipb_by.actor_name, ipb_by_text )',
						'ipb_by_actor' => 'ipb_by_actor',
					],
					'joins' => [
						'actor_ipb_by' => [ 'LEFT JOIN', 'actor_ipb_by.actor_id = ipb_by_actor' ],
					],
				],
			],
			'ipblocks, write-new' => [
				MIGRATION_WRITE_NEW, 'ipb_by', [
					'tables' => [ 'actor_ipb_by' => 'actor' ],
					'fields' => [
						'ipb_by' => 'COALESCE( actor_ipb_by.actor_user, ipb_by )',
						'ipb_by_text' => 'COALESCE( actor_ipb_by.actor_name, ipb_by_text )',
						'ipb_by_actor' => 'ipb_by_actor',
					],
					'joins' => [
						'actor_ipb_by' => [ 'LEFT JOIN', 'actor_ipb_by.actor_id = ipb_by_actor' ],
					],
				],
			],
			'ipblocks, new' => [
				MIGRATION_NEW, 'ipb_by', [
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
				MIGRATION_OLD, 'rev_user', [
					'tables' => [],
					'fields' => [
						'rev_user' => 'rev_user',
						'rev_user_text' => 'rev_user_text',
						'rev_actor' => 'NULL',
					],
					'joins' => [],
				],
			],
			'Revision, write-both' => [
				MIGRATION_WRITE_BOTH, 'rev_user', [
					'tables' => [
						'temp_rev_user' => 'revision_actor_temp',
						'actor_rev_user' => 'actor',
					],
					'fields' => [
						'rev_user' => 'COALESCE( actor_rev_user.actor_user, rev_user )',
						'rev_user_text' => 'COALESCE( actor_rev_user.actor_name, rev_user_text )',
						'rev_actor' => 'temp_rev_user.revactor_actor',
					],
					'joins' => [
						'temp_rev_user' => [ 'LEFT JOIN', 'temp_rev_user.revactor_rev = rev_id' ],
						'actor_rev_user' => [ 'LEFT JOIN', 'actor_rev_user.actor_id = temp_rev_user.revactor_actor' ],
					],
				],
			],
			'Revision, write-new' => [
				MIGRATION_WRITE_NEW, 'rev_user', [
					'tables' => [
						'temp_rev_user' => 'revision_actor_temp',
						'actor_rev_user' => 'actor',
					],
					'fields' => [
						'rev_user' => 'COALESCE( actor_rev_user.actor_user, rev_user )',
						'rev_user_text' => 'COALESCE( actor_rev_user.actor_name, rev_user_text )',
						'rev_actor' => 'temp_rev_user.revactor_actor',
					],
					'joins' => [
						'temp_rev_user' => [ 'LEFT JOIN', 'temp_rev_user.revactor_rev = rev_id' ],
						'actor_rev_user' => [ 'LEFT JOIN', 'actor_rev_user.actor_id = temp_rev_user.revactor_actor' ],
					],
				],
			],
			'Revision, new' => [
				MIGRATION_NEW, 'rev_user', [
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
				MIGRATION_OLD, 'rc_user', $genericUser, true, [
					'tables' => [],
					'orconds' => [ 'userid' => "rc_user = '1'" ],
					'joins' => [],
				],
			],
			'Simple table, write-both' => [
				MIGRATION_WRITE_BOTH, 'rc_user', $genericUser, true, [
					'tables' => [],
					'orconds' => [
						'actor' => "rc_actor = '11'",
						'userid' => "rc_actor = '0' AND rc_user = '1'"
					],
					'joins' => [],
				],
			],
			'Simple table, write-new' => [
				MIGRATION_WRITE_NEW, 'rc_user', $genericUser, true, [
					'tables' => [],
					'orconds' => [
						'actor' => "rc_actor = '11'",
						'userid' => "rc_actor = '0' AND rc_user = '1'"
					],
					'joins' => [],
				],
			],
			'Simple table, new' => [
				MIGRATION_NEW, 'rc_user', $genericUser, true, [
					'tables' => [],
					'orconds' => [ 'actor' => "rc_actor = '11'" ],
					'joins' => [],
				],
			],

			'ipblocks, old' => [
				MIGRATION_OLD, 'ipb_by', $genericUser, true, [
					'tables' => [],
					'orconds' => [ 'userid' => "ipb_by = '1'" ],
					'joins' => [],
				],
			],
			'ipblocks, write-both' => [
				MIGRATION_WRITE_BOTH, 'ipb_by', $genericUser, true, [
					'tables' => [],
					'orconds' => [
						'actor' => "ipb_by_actor = '11'",
						'userid' => "ipb_by_actor = '0' AND ipb_by = '1'"
					],
					'joins' => [],
				],
			],
			'ipblocks, write-new' => [
				MIGRATION_WRITE_NEW, 'ipb_by', $genericUser, true, [
					'tables' => [],
					'orconds' => [
						'actor' => "ipb_by_actor = '11'",
						'userid' => "ipb_by_actor = '0' AND ipb_by = '1'"
					],
					'joins' => [],
				],
			],
			'ipblocks, new' => [
				MIGRATION_NEW, 'ipb_by', $genericUser, true, [
					'tables' => [],
					'orconds' => [ 'actor' => "ipb_by_actor = '11'" ],
					'joins' => [],
				],
			],

			'Revision, old' => [
				MIGRATION_OLD, 'rev_user', $genericUser, true, [
					'tables' => [],
					'orconds' => [ 'userid' => "rev_user = '1'" ],
					'joins' => [],
				],
			],
			'Revision, write-both' => [
				MIGRATION_WRITE_BOTH, 'rev_user', $genericUser, true, [
					'tables' => [
						'temp_rev_user' => 'revision_actor_temp',
					],
					'orconds' => [
						'actor' =>
							"(temp_rev_user.revactor_actor IS NOT NULL) AND temp_rev_user.revactor_actor = '11'",
						'userid' => "temp_rev_user.revactor_actor IS NULL AND rev_user = '1'"
					],
					'joins' => [
						'temp_rev_user' => [ 'LEFT JOIN', 'temp_rev_user.revactor_rev = rev_id' ],
					],
				],
			],
			'Revision, write-new' => [
				MIGRATION_WRITE_NEW, 'rev_user', $genericUser, true, [
					'tables' => [
						'temp_rev_user' => 'revision_actor_temp',
					],
					'orconds' => [
						'actor' =>
							"(temp_rev_user.revactor_actor IS NOT NULL) AND temp_rev_user.revactor_actor = '11'",
						'userid' => "temp_rev_user.revactor_actor IS NULL AND rev_user = '1'"
					],
					'joins' => [
						'temp_rev_user' => [ 'LEFT JOIN', 'temp_rev_user.revactor_rev = rev_id' ],
					],
				],
			],
			'Revision, new' => [
				MIGRATION_NEW, 'rev_user', $genericUser, true, [
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
				MIGRATION_OLD, 'rc_user', $complicatedUsers, true, [
					'tables' => [],
					'orconds' => [
						'userid' => "rc_user IN ('1','2','3') ",
						'username' => "rc_user_text IN ('192.168.12.34','192.168.12.35') "
					],
					'joins' => [],
				],
			],
			'Multiple users, write-both' => [
				MIGRATION_WRITE_BOTH, 'rc_user', $complicatedUsers, true, [
					'tables' => [],
					'orconds' => [
						'actor' => "rc_actor IN ('11','12','34') ",
						'userid' => "rc_actor = '0' AND rc_user IN ('1','2','3') ",
						'username' => "rc_actor = '0' AND rc_user_text IN ('192.168.12.34','192.168.12.35') "
					],
					'joins' => [],
				],
			],
			'Multiple users, write-new' => [
				MIGRATION_WRITE_NEW, 'rc_user', $complicatedUsers, true, [
					'tables' => [],
					'orconds' => [
						'actor' => "rc_actor IN ('11','12','34') ",
						'userid' => "rc_actor = '0' AND rc_user IN ('1','2','3') ",
						'username' => "rc_actor = '0' AND rc_user_text IN ('192.168.12.34','192.168.12.35') "
					],
					'joins' => [],
				],
			],
			'Multiple users, new' => [
				MIGRATION_NEW, 'rc_user', $complicatedUsers, true, [
					'tables' => [],
					'orconds' => [ 'actor' => "rc_actor IN ('11','12','34') " ],
					'joins' => [],
				],
			],

			'Multiple users, no use ID, old' => [
				MIGRATION_OLD, 'rc_user', $complicatedUsers, false, [
					'tables' => [],
					'orconds' => [
						'username' => "rc_user_text IN ('User1','User2','User3','192.168.12.34','192.168.12.35') "
					],
					'joins' => [],
				],
			],
			'Multiple users, write-both' => [
				MIGRATION_WRITE_BOTH, 'rc_user', $complicatedUsers, false, [
					'tables' => [],
					'orconds' => [
						'actor' => "rc_actor IN ('11','12','34') ",
						'username' => "rc_actor = '0' AND "
						. "rc_user_text IN ('User1','User2','User3','192.168.12.34','192.168.12.35') "
					],
					'joins' => [],
				],
			],
			'Multiple users, write-new' => [
				MIGRATION_WRITE_NEW, 'rc_user', $complicatedUsers, false, [
					'tables' => [],
					'orconds' => [
						'actor' => "rc_actor IN ('11','12','34') ",
						'username' => "rc_actor = '0' AND "
						. "rc_user_text IN ('User1','User2','User3','192.168.12.34','192.168.12.35') "
					],
					'joins' => [],
				],
			],
			'Multiple users, new' => [
				MIGRATION_NEW, 'rc_user', $complicatedUsers, false, [
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

		$stages = [
			MIGRATION_OLD => [ MIGRATION_OLD, MIGRATION_WRITE_NEW ],
			MIGRATION_WRITE_BOTH => [ MIGRATION_OLD, MIGRATION_NEW ],
			MIGRATION_WRITE_NEW => [ MIGRATION_WRITE_BOTH, MIGRATION_NEW ],
			MIGRATION_NEW => [ MIGRATION_WRITE_BOTH, MIGRATION_NEW ],
		];

		$nameKey = $key . '_text';
		$actorKey = $key === 'ipb_by' ? 'ipb_by_actor' : substr( $key, 0, -5 ) . '_actor';

		foreach ( $stages as $writeStage => $readRange ) {
			if ( $key === 'ipb_by' ) {
				$extraFields['ipb_address'] = __CLASS__ . "#$writeStage";
			}

			$w = $this->makeMigration( $writeStage );
			$usesTemp = $key === 'rev_user';

			if ( $usesTemp ) {
				list( $fields, $callback ) = $w->getInsertValuesWithTempTable( $this->db, $key, $user );
			} else {
				$fields = $w->getInsertValues( $this->db, $key, $user );
			}

			if ( $writeStage <= MIGRATION_WRITE_BOTH ) {
				$this->assertSame( $user->getId(), $fields[$key], "old field, stage=$writeStage" );
				$this->assertSame( $user->getName(), $fields[$nameKey], "old field, stage=$writeStage" );
			} else {
				$this->assertArrayNotHasKey( $key, $fields, "old field, stage=$writeStage" );
				$this->assertArrayNotHasKey( $nameKey, $fields, "old field, stage=$writeStage" );
			}
			if ( $writeStage >= MIGRATION_WRITE_BOTH && !$usesTemp ) {
				$this->assertSame( $user->getActorId(), $fields[$actorKey], "new field, stage=$writeStage" );
			} else {
				$this->assertArrayNotHasKey( $actorKey, $fields, "new field, stage=$writeStage" );
			}

			$this->db->insert( $table, $extraFields + $fields, __METHOD__ );
			$id = $this->db->insertId();
			if ( $usesTemp ) {
				$callback( $id, $extraFields );
			}

			for ( $readStage = $readRange[0]; $readStage <= $readRange[1]; $readStage++ ) {
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

				$this->assertSame( $user->getId(), (int)$row->$key, "w=$writeStage, r=$readStage, id" );
				$this->assertSame( $user->getName(), $row->$nameKey, "w=$writeStage, r=$readStage, name" );
				$this->assertSame(
					$readStage === MIGRATION_OLD || $writeStage === MIGRATION_OLD ? 0 : $user->getActorId(),
					(int)$row->$actorKey,
					"w=$writeStage, r=$readStage, actor"
				);
			}
		}
	}

	public static function provideInsertRoundTrip() {
		$db = wfGetDB( DB_REPLICA ); // for timestamps

		$ipbfields = [
		];
		$revfields = [
		];

		return [
			'recentchanges' => [ 'recentchanges', 'rc_user', 'rc_id', [
				'rc_timestamp' => $db->timestamp(),
				'rc_namespace' => 0,
				'rc_title' => 'Test',
				'rc_this_oldid' => 42,
				'rc_last_oldid' => 41,
				'rc_source' => 'test',
			] ],
			'ipblocks' => [ 'ipblocks', 'ipb_by', 'ipb_id', [
				'ipb_range_start' => '',
				'ipb_range_end' => '',
				'ipb_timestamp' => $db->timestamp(),
				'ipb_expiry' => $db->getInfinity(),
			] ],
			'revision' => [ 'revision', 'rev_user', 'rev_id', [
				'rev_page' => 42,
				'rev_text_id' => 42,
				'rev_len' => 0,
				'rev_timestamp' => $db->timestamp(),
			] ],
		];
	}

	public static function provideStages() {
		return [
			'MIGRATION_OLD' => [ MIGRATION_OLD ],
			'MIGRATION_WRITE_BOTH' => [ MIGRATION_WRITE_BOTH ],
			'MIGRATION_WRITE_NEW' => [ MIGRATION_WRITE_NEW ],
			'MIGRATION_NEW' => [ MIGRATION_NEW ],
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

	public function testInsertUserIdentity() {
		$user = $this->getTestUser()->getUser();
		$userIdentity = $this->getMock( UserIdentity::class );
		$userIdentity->method( 'getId' )->willReturn( $user->getId() );
		$userIdentity->method( 'getName' )->willReturn( $user->getName() );
		$userIdentity->method( 'getActorId' )->willReturn( 0 );

		list( $cFields, $cCallback ) = CommentStore::newKey( 'rev_comment' )
			->insertWithTempTable( $this->db, '' );
		$m = $this->makeMigration( MIGRATION_WRITE_BOTH );
		list( $fields, $callback ) =
			$m->getInsertValuesWithTempTable( $this->db, 'rev_user', $userIdentity );
		$extraFields = [
			'rev_page' => 42,
			'rev_text_id' => 42,
			'rev_len' => 0,
			'rev_timestamp' => $this->db->timestamp(),
		] + $cFields;
		$this->db->insert( 'revision', $extraFields + $fields, __METHOD__ );
		$id = $this->db->insertId();
		$callback( $id, $extraFields );
		$cCallback( $id );

		$qi = Revision::getQueryInfo();
		$row = $this->db->selectRow(
			$qi['tables'], $qi['fields'], [ 'rev_id' => $id ], __METHOD__, [], $qi['joins']
		);
		$this->assertSame( $user->getId(), (int)$row->rev_user );
		$this->assertSame( $user->getName(), $row->rev_user_text );
		$this->assertSame( $user->getActorId(), (int)$row->rev_actor );

		$m = $this->makeMigration( MIGRATION_WRITE_BOTH );
		$fields = $m->getInsertValues( $this->db, 'dummy_user', $userIdentity );
		$this->assertSame( $user->getId(), $fields['dummy_user'] );
		$this->assertSame( $user->getName(), $fields['dummy_user_text'] );
		$this->assertSame( $user->getActorId(), $fields['dummy_actor'] );
	}

	public function testConstructor() {
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
			'MIGRATION_OLD' => [ MIGRATION_OLD, 'foo = 0', 'foo != 0' ],
			'MIGRATION_WRITE_BOTH' => [ MIGRATION_WRITE_BOTH, 'foo = 0', 'foo != 0' ],
			'MIGRATION_WRITE_NEW' => [ MIGRATION_WRITE_NEW, 'foo = 0', 'foo != 0' ],
			'MIGRATION_NEW' => [ MIGRATION_NEW, 'foo IS NULL', 'foo IS NOT NULL' ],
		];
	}

}
