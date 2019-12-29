<?php

use MediaWiki\User\UserIdentity;
use Wikimedia\ScopedCallback;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Database
 * @covers ActorMigration
 */
class ActorMigrationTest extends MediaWikiLangTestCase {

	protected $resetActorMigration = null;
	protected static $amId = 0;

	protected $tablesUsed = [
		'actor',
	];

	protected function setUp() : void {
		parent::setUp();

		$w = TestingAccessWrapper::newFromClass( ActorMigration::class );
		$data = [
			'tempTables' => $w->tempTables,
			'formerTempTables' => $w->formerTempTables,
			'deprecated' => $w->deprecated,
			'removed' => $w->removed,
			'specialFields' => $w->specialFields,
		];
		$this->resetActorMigration = new ScopedCallback( function ( $w, $data ) {
			foreach ( $data as $k => $v ) {
				$w->$k = $v;
			}
		}, [ $w, $data ] );

		$w->tempTables = [
			'am2_user' => [
				'table' => 'actormigration2_temp',
				'pk' => 'am2t_id',
				'field' => 'am2t_actor',
				'joinPK' => 'am2_id',
				'extra' => [],
			]
		];
		$w->specialFields = [
			'am3_xxx' => [ 'am3_xxx_text', 'am3_xxx_actor' ],
		];
	}

	protected function tearDown() : void {
		parent::tearDown();
		ScopedCallback::consume( $this->resetActorMigration );
	}

	protected function getSchemaOverrides( IMaintainableDatabase $db ) {
		return [
			'scripts' => [
				__DIR__ . '/ActorMigrationTest.sql',
			],
			'drop' => [],
			'create' => [ 'actormigration1', 'actormigration2', 'actormigration2_temp', 'actormigration3' ],
			'alter' => [],
		];
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
		$m = new ActorMigration( $stage );
		$result = $m->getJoin( $key );
		$this->assertEquals( $expect, $result );
	}

	public static function provideGetJoin() {
		return [
			'Simple table, old' => [
				SCHEMA_COMPAT_OLD, 'am1_user', [
					'tables' => [],
					'fields' => [
						'am1_user' => 'am1_user',
						'am1_user_text' => 'am1_user_text',
						'am1_actor' => 'NULL',
					],
					'joins' => [],
				],
			],
			'Simple table, read-old' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD, 'am1_user', [
					'tables' => [],
					'fields' => [
						'am1_user' => 'am1_user',
						'am1_user_text' => 'am1_user_text',
						'am1_actor' => 'NULL',
					],
					'joins' => [],
				],
			],
			'Simple table, read-new' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW, 'am1_user', [
					'tables' => [ 'actor_am1_user' => 'actor' ],
					'fields' => [
						'am1_user' => 'actor_am1_user.actor_user',
						'am1_user_text' => 'actor_am1_user.actor_name',
						'am1_actor' => 'am1_actor',
					],
					'joins' => [
						'actor_am1_user' => [ 'JOIN', 'actor_am1_user.actor_id = am1_actor' ],
					],
				],
			],
			'Simple table, new' => [
				SCHEMA_COMPAT_NEW, 'am1_user', [
					'tables' => [ 'actor_am1_user' => 'actor' ],
					'fields' => [
						'am1_user' => 'actor_am1_user.actor_user',
						'am1_user_text' => 'actor_am1_user.actor_name',
						'am1_actor' => 'am1_actor',
					],
					'joins' => [
						'actor_am1_user' => [ 'JOIN', 'actor_am1_user.actor_id = am1_actor' ],
					],
				],
			],

			'Special name, old' => [
				SCHEMA_COMPAT_OLD, 'am3_xxx', [
					'tables' => [],
					'fields' => [
						'am3_xxx' => 'am3_xxx',
						'am3_xxx_text' => 'am3_xxx_text',
						'am3_xxx_actor' => 'NULL',
					],
					'joins' => [],
				],
			],
			'Special name, read-old' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD, 'am3_xxx', [
					'tables' => [],
					'fields' => [
						'am3_xxx' => 'am3_xxx',
						'am3_xxx_text' => 'am3_xxx_text',
						'am3_xxx_actor' => 'NULL',
					],
					'joins' => [],
				],
			],
			'Special name, read-new' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW, 'am3_xxx', [
					'tables' => [ 'actor_am3_xxx' => 'actor' ],
					'fields' => [
						'am3_xxx' => 'actor_am3_xxx.actor_user',
						'am3_xxx_text' => 'actor_am3_xxx.actor_name',
						'am3_xxx_actor' => 'am3_xxx_actor',
					],
					'joins' => [
						'actor_am3_xxx' => [ 'JOIN', 'actor_am3_xxx.actor_id = am3_xxx_actor' ],
					],
				],
			],
			'Special name, new' => [
				SCHEMA_COMPAT_NEW, 'am3_xxx', [
					'tables' => [ 'actor_am3_xxx' => 'actor' ],
					'fields' => [
						'am3_xxx' => 'actor_am3_xxx.actor_user',
						'am3_xxx_text' => 'actor_am3_xxx.actor_name',
						'am3_xxx_actor' => 'am3_xxx_actor',
					],
					'joins' => [
						'actor_am3_xxx' => [ 'JOIN', 'actor_am3_xxx.actor_id = am3_xxx_actor' ],
					],
				],
			],

			'Temp table, old' => [
				SCHEMA_COMPAT_OLD, 'am2_user', [
					'tables' => [],
					'fields' => [
						'am2_user' => 'am2_user',
						'am2_user_text' => 'am2_user_text',
						'am2_actor' => 'NULL',
					],
					'joins' => [],
				],
			],
			'Temp table, read-old' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD, 'am2_user', [
					'tables' => [],
					'fields' => [
						'am2_user' => 'am2_user',
						'am2_user_text' => 'am2_user_text',
						'am2_actor' => 'NULL',
					],
					'joins' => [],
				],
			],
			'Temp table, read-new' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW, 'am2_user', [
					'tables' => [
						'temp_am2_user' => 'actormigration2_temp',
						'actor_am2_user' => 'actor',
					],
					'fields' => [
						'am2_user' => 'actor_am2_user.actor_user',
						'am2_user_text' => 'actor_am2_user.actor_name',
						'am2_actor' => 'temp_am2_user.am2t_actor',
					],
					'joins' => [
						'temp_am2_user' => [ 'JOIN', 'temp_am2_user.am2t_id = am2_id' ],
						'actor_am2_user' => [ 'JOIN', 'actor_am2_user.actor_id = temp_am2_user.am2t_actor' ],
					],
				],
			],
			'Temp table, new' => [
				SCHEMA_COMPAT_NEW, 'am2_user', [
					'tables' => [
						'temp_am2_user' => 'actormigration2_temp',
						'actor_am2_user' => 'actor',
					],
					'fields' => [
						'am2_user' => 'actor_am2_user.actor_user',
						'am2_user_text' => 'actor_am2_user.actor_name',
						'am2_actor' => 'temp_am2_user.am2t_actor',
					],
					'joins' => [
						'temp_am2_user' => [ 'JOIN', 'temp_am2_user.am2t_id = am2_id' ],
						'actor_am2_user' => [ 'JOIN', 'actor_am2_user.actor_id = temp_am2_user.am2t_actor' ],
					],
				],
			],
		];
	}

	/**
	 * @dataProvider provideGetWhere
	 * @param int $stage
	 * @param string $key
	 * @param UserIdentity|UserIdentity[]|null|false $users
	 * @param bool $useId
	 * @param array $expect
	 */
	public function testGetWhere( $stage, $key, $users, $useId, $expect ) {
		if ( !isset( $expect['conds'] ) ) {
			$expect['conds'] = '(' . implode( ') OR (', $expect['orconds'] ) . ')';
		}

		$m = new ActorMigration( $stage );
		$result = $m->getWhere( $this->db, $key, $users, $useId );
		$this->assertEquals( $expect, $result );
	}

	public function provideGetWhere() {
		$makeUserIdentity = function ( $id, $name, $actor ) {
			$u = $this->createMock( UserIdentity::class );
			$u->method( 'getId' )->willReturn( $id );
			$u->method( 'getName' )->willReturn( $name );
			$u->method( 'getActorId' )->willReturn( $actor );
			return $u;
		};

		$genericUser = $makeUserIdentity( 1, 'User1', 11 );
		$complicatedUsers = [
			$makeUserIdentity( 1, 'User1', 11 ),
			$makeUserIdentity( 2, 'User2', 12 ),
			$makeUserIdentity( 3, 'User3', 0 ),
			$makeUserIdentity( 0, '192.168.12.34', 34 ),
			$makeUserIdentity( 0, '192.168.12.35', 0 ),
		];

		return [
			'Simple table, old' => [
				SCHEMA_COMPAT_OLD, 'am1_user', $genericUser, true, [
					'tables' => [],
					'orconds' => [ 'userid' => "am1_user = 1" ],
					'joins' => [],
				],
			],
			'Simple table, read-old' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD, 'am1_user', $genericUser, true, [
					'tables' => [],
					'orconds' => [ 'userid' => "am1_user = 1" ],
					'joins' => [],
				],
			],
			'Simple table, read-new' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW, 'am1_user', $genericUser, true, [
					'tables' => [],
					'orconds' => [ 'actor' => "am1_actor = 11" ],
					'joins' => [],
				],
			],
			'Simple table, new' => [
				SCHEMA_COMPAT_NEW, 'am1_user', $genericUser, true, [
					'tables' => [],
					'orconds' => [ 'actor' => "am1_actor = 11" ],
					'joins' => [],
				],
			],

			'Special name, old' => [
				SCHEMA_COMPAT_OLD, 'am3_xxx', $genericUser, true, [
					'tables' => [],
					'orconds' => [ 'userid' => "am3_xxx = 1" ],
					'joins' => [],
				],
			],
			'Special name, read-old' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD, 'am3_xxx', $genericUser, true, [
					'tables' => [],
					'orconds' => [ 'userid' => "am3_xxx = 1" ],
					'joins' => [],
				],
			],
			'Special name, read-new' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW, 'am3_xxx', $genericUser, true, [
					'tables' => [],
					'orconds' => [ 'actor' => "am3_xxx_actor = 11" ],
					'joins' => [],
				],
			],
			'Special name, new' => [
				SCHEMA_COMPAT_NEW, 'am3_xxx', $genericUser, true, [
					'tables' => [],
					'orconds' => [ 'actor' => "am3_xxx_actor = 11" ],
					'joins' => [],
				],
			],

			'Temp table, old' => [
				SCHEMA_COMPAT_OLD, 'am2_user', $genericUser, true, [
					'tables' => [],
					'orconds' => [ 'userid' => "am2_user = 1" ],
					'joins' => [],
				],
			],
			'Temp table, read-old' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD, 'am2_user', $genericUser, true, [
					'tables' => [],
					'orconds' => [ 'userid' => "am2_user = 1" ],
					'joins' => [],
				],
			],
			'Temp table, read-new' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW, 'am2_user', $genericUser, true, [
					'tables' => [
						'temp_am2_user' => 'actormigration2_temp',
					],
					'orconds' => [ 'actor' => "temp_am2_user.am2t_actor = 11" ],
					'joins' => [
						'temp_am2_user' => [ 'JOIN', 'temp_am2_user.am2t_id = am2_id' ],
					],
				],
			],
			'Temp table, new' => [
				SCHEMA_COMPAT_NEW, 'am2_user', $genericUser, true, [
					'tables' => [
						'temp_am2_user' => 'actormigration2_temp',
					],
					'orconds' => [ 'actor' => "temp_am2_user.am2t_actor = 11" ],
					'joins' => [
						'temp_am2_user' => [ 'JOIN', 'temp_am2_user.am2t_id = am2_id' ],
					],
				],
			],

			'Multiple users, old' => [
				SCHEMA_COMPAT_OLD, 'am1_user', $complicatedUsers, true, [
					'tables' => [],
					'orconds' => [
						'userid' => "am1_user IN (1,2,3) ",
						'username' => "am1_user_text IN ('192.168.12.34','192.168.12.35') "
					],
					'joins' => [],
				],
			],
			'Multiple users, read-old' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD, 'am1_user', $complicatedUsers, true, [
					'tables' => [],
					'orconds' => [
						'userid' => "am1_user IN (1,2,3) ",
						'username' => "am1_user_text IN ('192.168.12.34','192.168.12.35') "
					],
					'joins' => [],
				],
			],
			'Multiple users, read-new' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW, 'am1_user', $complicatedUsers, true, [
					'tables' => [],
					'orconds' => [ 'actor' => "am1_actor IN (11,12,34) " ],
					'joins' => [],
				],
			],
			'Multiple users, new' => [
				SCHEMA_COMPAT_NEW, 'am1_user', $complicatedUsers, true, [
					'tables' => [],
					'orconds' => [ 'actor' => "am1_actor IN (11,12,34) " ],
					'joins' => [],
				],
			],

			'Multiple users, no use ID, old' => [
				SCHEMA_COMPAT_OLD, 'am1_user', $complicatedUsers, false, [
					'tables' => [],
					'orconds' => [
						'username' => "am1_user_text IN ('User1','User2','User3','192.168.12.34','192.168.12.35') "
					],
					'joins' => [],
				],
			],
			'Multiple users, read-old' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD, 'am1_user', $complicatedUsers, false, [
					'tables' => [],
					'orconds' => [
						'username' => "am1_user_text IN ('User1','User2','User3','192.168.12.34','192.168.12.35') "
					],
					'joins' => [],
				],
			],
			'Multiple users, read-new' => [
				SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW, 'am1_user', $complicatedUsers, false, [
					'tables' => [],
					'orconds' => [ 'actor' => "am1_actor IN (11,12,34) " ],
					'joins' => [],
				],
			],
			'Multiple users, new' => [
				SCHEMA_COMPAT_NEW, 'am1_user', $complicatedUsers, false, [
					'tables' => [],
					'orconds' => [ 'actor' => "am1_actor IN (11,12,34) " ],
					'joins' => [],
				],
			],

			'Empty $users' => [
				SCHEMA_COMPAT_NEW, 'am1_user', [], true, [
					'tables' => [],
					'conds' => '1=0',
					'orconds' => [],
					'joins' => [],
				],
			],
			'Null $users' => [
				SCHEMA_COMPAT_NEW, 'am1_user', null, true, [
					'tables' => [],
					'conds' => '1=0',
					'orconds' => [],
					'joins' => [],
				],
			],
			'False $users' => [
				SCHEMA_COMPAT_NEW, 'am1_user', false, true, [
					'tables' => [],
					'conds' => '1=0',
					'orconds' => [],
					'joins' => [],
				],
			],
		];
	}

	/**
	 * @dataProvider provideStages
	 */
	public function testGetWhere_exception( $stage ) {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage(
			'ActorMigration::getWhere: Value for $users must be a UserIdentity or array, got string'
		);

		$m = new ActorMigration( $stage );
		$result = $m->getWhere( $this->db, 'am1_user', 'Foo' );
	}

	/**
	 * @dataProvider provideInsertRoundTrip
	 * @param string $table
	 * @param string $key
	 * @param string $pk
	 * @param bool $usesTemp
	 */
	public function testInsertRoundTrip( $table, $key, $pk, $usesTemp ) {
		$u = $this->getTestUser()->getUser();
		$user = $this->createMock( UserIdentity::class );
		$user->method( 'getId' )->willReturn( $u->getId() );
		$user->method( 'getName' )->willReturn( $u->getName() );
		$user->method( 'getActorId' )->willReturn( $u->getActorId( $this->db ) );

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
		$actorKey = ( $key === 'am3_xxx' ? $key : substr( $key, 0, -5 ) ) . '_actor';

		foreach ( $stages as $writeStage => $possibleReadStages ) {
			$w = new ActorMigration( $writeStage );

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

			$id = ++self::$amId;
			$this->db->insert( $table, [ $pk => $id ] + $fields, __METHOD__ );
			if ( $usesTemp ) {
				$callback( $id, [] );
			}

			foreach ( $possibleReadStages as $readStage ) {
				$r = new ActorMigration( $readStage );

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
		return [
			'normal' => [ 'actormigration1', 'am1_user', 'am1_id', false ],
			'temp table' => [ 'actormigration2', 'am2_user', 'am2_id', true ],
			'special name' => [ 'actormigration3', 'am3_xxx', 'am3_id', false ],
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
	 */
	public function testInsertWrong( $stage ) {
		$m = new ActorMigration( $stage );
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "Must use getInsertValuesWithTempTable() for am2_user" );
		$m->getInsertValues( $this->db, 'am2_user', $this->getTestUser()->getUser() );
	}

	/**
	 * @dataProvider provideStages
	 * @param int $stage
	 */
	public function testInsertWithTempTableWrong( $stage ) {
		$m = new ActorMigration( $stage );
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "Must use getInsertValues() for am1_user" );
		$m->getInsertValuesWithTempTable( $this->db, 'am1_user', $this->getTestUser()->getUser() );
	}

	/**
	 * @dataProvider provideStages
	 * @param int $stage
	 */
	public function testInsertWithTempTableDeprecated( $stage ) {
		$wrap = TestingAccessWrapper::newFromClass( ActorMigration::class );
		$wrap->formerTempTables += [ 'am1_user' => '1.30' ];

		$this->hideDeprecated( 'ActorMigration::getInsertValuesWithTempTable for am1_user' );
		$m = new ActorMigration( $stage );
		list( $fields, $callback )
			= $m->getInsertValuesWithTempTable( $this->db, 'am1_user', $this->getTestUser()->getUser() );
		$this->assertIsCallable( $callback );
	}

	/**
	 * @dataProvider provideStages
	 * @param int $stage
	 */
	public function testInsertWithTempTableCallbackMissingFields( $stage ) {
		$w = TestingAccessWrapper::newFromClass( ActorMigration::class );
		$w->tempTables = [
			'foo_user' => [
				'table' => 'foo_temp',
				'pk' => 'footmp_id',
				'field' => 'footmp_actor',
				'joinPK' => 'foo_id',
				'extra' => [ 'footmp_timestamp' => 'foo_timestamp' ],
			],
		];

		$m = new ActorMigration( $stage );
		list( $fields, $callback )
			= $m->getInsertValuesWithTempTable( $this->db, 'foo_user', $this->getTestUser()->getUser() );
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( '$extra[foo_timestamp] is not provided' );
		$callback( 1, [] );
	}

	/**
	 * @dataProvider provideStages
	 * @param int $stage
	 */
	public function testInsertUserIdentity( $stage ) {
		$user = $this->getMutableTestUser()->getUser();
		$userIdentity = $this->createMock( UserIdentity::class );
		$userIdentity->method( 'getId' )->willReturn( $user->getId() );
		$userIdentity->method( 'getName' )->willReturn( $user->getName() );
		$userIdentity->method( 'getActorId' )->willReturn( 0 );

		$m = new ActorMigration( $stage );
		list( $fields, $callback ) =
			$m->getInsertValuesWithTempTable( $this->db, 'am2_user', $userIdentity );
		$id = ++self::$amId;
		$this->db->insert( 'actormigration2', [ 'am2_id' => $id ] + $fields, __METHOD__ );
		$callback( $id, [] );

		$qi = $m->getJoin( 'am2_user' );
		$row = $this->db->selectRow(
			[ 'actormigration2' ] + $qi['tables'],
			$qi['fields'],
			[ 'am2_id' => $id ],
			__METHOD__,
			[],
			$qi['joins']
		);
		$this->assertSame( $user->getId(), (int)$row->am2_user );
		$this->assertSame( $user->getName(), $row->am2_user_text );
		$this->assertSame(
			( $stage & SCHEMA_COMPAT_READ_NEW ) ? $user->getActorId() : 0,
			(int)$row->am2_actor
		);

		$m = new ActorMigration( $stage );
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
		$m = new ActorMigration( $stage );
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

	public function testCheckDeprecation() {
		$wrap = TestingAccessWrapper::newFromClass( ActorMigration::class );
		$wrap->deprecated += [ 'soft' => null, 'hard' => '1.34' ];
		$wrap->removed += [ 'gone' => '1.34' ];

		$this->hideDeprecated( 'ActorMigration for \'hard\'' );

		$wrap->checkDeprecation( 'valid' );
		$wrap->checkDeprecation( 'soft' );
		$wrap->checkDeprecation( 'hard' );
		try {
			$wrap->checkDeprecation( 'gone' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'Use of ActorMigration for \'gone\' was removed in MediaWiki 1.34',
				$ex->getMessage()
			);
		}
	}

}
