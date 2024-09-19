<?php

use MediaWiki\User\ActorMigration;
use MediaWiki\User\ActorMigrationBase;
use MediaWiki\User\ActorStore;
use MediaWiki\User\ActorStoreFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\Rdbms\IMaintainableDatabase;

/**
 * @group Database
 * @covers \MediaWiki\User\ActorMigration
 * @covers \MediaWiki\User\ActorMigrationBase
 */
class ActorMigrationTest extends MediaWikiLangTestCase {

	/** @var int */
	protected static $amId = 0;

	private const STAGES_BY_NAME = [
		'old' => SCHEMA_COMPAT_WRITE_OLD | SCHEMA_COMPAT_READ_OLD,
		'read-old' => SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD,
		'read-new' => SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW,
		'new' => SCHEMA_COMPAT_WRITE_NEW | SCHEMA_COMPAT_READ_NEW
	];

	protected function getSchemaOverrides( IMaintainableDatabase $db ) {
		return [
			'scripts' => [
				__DIR__ . '/ActorMigrationTest.sql',
			],
			'drop' => [],
			'create' => [ 'actormigration1', 'actormigration2' ],
			'alter' => [],
		];
	}

	private function getMigration( $stage, $actorStoreFactory = null ) {
		$mwServices = $this->getServiceContainer();
		return new ActorMigrationBase(
			[
				'am2_xxx' => [
					'textField' => 'am2_xxx_text',
					'actorField' => 'am2_xxx_actor'
				],
			],
			$stage,
			$actorStoreFactory ?? $mwServices->getActorStoreFactory()
		);
	}

	private static function makeActorCases( $inputs, $expected ) {
		foreach ( $expected as $inputName => $expectedCases ) {
			foreach ( $expectedCases as [ $stages, $expected ] ) {
				foreach ( $stages as $stage ) {
					$cases[$inputName . ', ' . $stage] = array_merge(
						[ $stage ],
						$inputs[$inputName],
						[ $expected ]
					);
				}
			}
		}
		return $cases;
	}

	/**
	 * @dataProvider provideConstructor
	 * @param int $stage
	 * @param string|null $exceptionMsg
	 */
	public function testConstructor( $stage, $exceptionMsg ) {
		try {
			$m = $this->getMigration( $stage );
			if ( $exceptionMsg !== null ) {
				$this->fail( 'Expected exception not thrown' );
			}
			$this->assertInstanceOf( ActorMigrationBase::class, $m );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame( $exceptionMsg, $ex->getMessage() );
		}
	}

	public static function provideConstructor() {
		return [
			[ 0, '$stage must include a write mode' ],
			[ SCHEMA_COMPAT_READ_OLD, '$stage must include a write mode' ],
			[ SCHEMA_COMPAT_READ_NEW, '$stage must include a write mode' ],

			[ SCHEMA_COMPAT_WRITE_OLD, '$stage must include a read mode' ],
			[ SCHEMA_COMPAT_OLD, null ],
			[ SCHEMA_COMPAT_WRITE_OLD | SCHEMA_COMPAT_READ_BOTH, 'Cannot read multiple schemas' ],

			[ SCHEMA_COMPAT_WRITE_NEW, '$stage must include a read mode' ],
			[
				SCHEMA_COMPAT_WRITE_NEW | SCHEMA_COMPAT_READ_OLD,
				'Cannot read the old schema without also writing it'
			],
		];
	}

	/**
	 * @dataProvider provideGetJoin
	 * @param string $stageName
	 * @param string $key
	 * @param array $expect
	 */
	public function testGetJoin( $stageName, $key, $expect ) {
		$stage = self::STAGES_BY_NAME[$stageName];
		$m = $this->getMigration( $stage );
		$result = $m->getJoin( $key );
		$this->assertEquals( $expect, $result );
	}

	public static function provideGetJoin() {
		$inputs = [
			'Simple table' => [ 'am1_user' ],
			'Special name' => [ 'am2_xxx' ],
		];
		$expected = [
			'Simple table' => [
				[
					[ 'old', 'read-old' ],
					[
						'tables' => [],
						'fields' => [
							'am1_user' => 'am1_user',
							'am1_user_text' => 'am1_user_text',
							'am1_actor' => 'NULL',
						],
						'joins' => [],
					]
				],
				[
					[ 'read-new', 'new' ],
					[
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
			],

			'Special name' => [
				[
					[ 'old', 'read-old' ],
					[
						'tables' => [],
						'fields' => [
							'am2_xxx' => 'am2_xxx',
							'am2_xxx_text' => 'am2_xxx_text',
							'am2_xxx_actor' => 'NULL',
						],
						'joins' => [],
					],
				],
				[
					[ 'read-new', 'new' ],
					[
						'tables' => [ 'actor_am2_xxx' => 'actor' ],
						'fields' => [
							'am2_xxx' => 'actor_am2_xxx.actor_user',
							'am2_xxx_text' => 'actor_am2_xxx.actor_name',
							'am2_xxx_actor' => 'am2_xxx_actor',
						],
						'joins' => [
							'actor_am2_xxx' => [ 'JOIN', 'actor_am2_xxx.actor_id = am2_xxx_actor' ],
						],
					],
				],
			],
		];

		return self::makeActorCases( $inputs, $expected );
	}

	private const ACTORS = [
		[ 1, 'User1', 11 ],
		[ 2, 'User2', 12 ],
		[ 0, '192.168.12.34', 34 ],
	];

	private static function findRow( $table, $index, $value ) {
		foreach ( $table as $row ) {
			if ( $row[$index] === $value ) {
				return $row;
			}
		}

		return null;
	}

	/**
	 * @return ActorStore
	 */
	private function getMockActorStore() {
		/** @var MockObject|ActorStore $mock */
		$mock = $this->createNoOpMock( ActorStore::class, [ 'findActorId' ] );

		$mock->method( 'findActorId' )
			->willReturnCallback( static function ( UserIdentity $user ) {
				$row = self::findRow( self::ACTORS, 1, $user->getName() );
				return $row ? $row[2] : null;
			} );

		return $mock;
	}

	/**
	 * @return ActorStoreFactory
	 */
	private function getMockActorStoreFactory() {
		$store = $this->getMockActorStore();

		/** @var MockObject|ActorStoreFactory $mock */
		$mock = $this->createNoOpMock( ActorStoreFactory::class, [ 'getActorNormalization' ] );

		$mock->method( 'getActorNormalization' )
			->willReturn( $store );

		return $mock;
	}

	/**
	 * @dataProvider provideGetWhere
	 * @param string $stageName
	 * @param string $key
	 * @param UserIdentity|UserIdentity[]|null|false $users
	 * @param bool $useId
	 * @param array $expect
	 */
	public function testGetWhere( $stageName, $key, $users, $useId, $expect ) {
		$stage = self::STAGES_BY_NAME[$stageName];
		if ( !isset( $expect['conds'] ) ) {
			$expect['conds'] = '(' . implode( ') OR (', $expect['orconds'] ) . ')';
		}

		$m = $this->getMigration( $stage, $this->getMockActorStoreFactory() );
		$result = $m->getWhere( $this->getDb(), $key, $users, $useId );
		$this->assertEquals( $expect, $result );
	}

	public static function provideGetWhere() {
		$genericUser = new UserIdentityValue( 1, 'User1' );
		$complicatedUsers = [
			new UserIdentityValue( 1, 'User1' ),
			new UserIdentityValue( 2, 'User2' ),
			new UserIdentityValue( 3, 'User3' ),
			new UserIdentityValue( 0, '192.168.12.34' ),
			new UserIdentityValue( 0, '192.168.12.35' ),
			// test handling of non-normalized IPv6 IP
			new UserIdentityValue( 0, '2600:1004:b14a:5ddd:3ebe:bba4:bfba:f37e' ),
		];

		$inputs = [
			'Simple table' => [ 'am1_user', $genericUser, true ],
			'Special name' => [ 'am2_xxx', $genericUser, true ],
			'Multiple users' => [ 'am1_user', $complicatedUsers, true ],
			'Multiple users, no use ID' => [ 'am1_user', $complicatedUsers, false ],
			'Empty $users' => [ 'am1_user', [], true ],
			'Null $users' => [ 'am1_user', null, true ],
			'False $users' => [ 'am1_user', false, true ],
		];

		$expected = [
			'Simple table' => [
				[
					[ 'old', 'read-old' ],
					[
						'tables' => [],
						'orconds' => [ 'userid' => "am1_user = 1" ],
						'joins' => [],
					]
				], [
					[ 'read-new', 'new' ],
					[
						'tables' => [],
						'orconds' => [ 'newactor' => "am1_actor = 11" ],
						'joins' => [],
					],
				],
			],

			'Special name' => [
				[
					[ 'old', 'read-old' ],
					[
						'tables' => [],
						'orconds' => [ 'userid' => "am2_xxx = 1" ],
						'joins' => [],
					],
				], [
					[ 'read-new', 'new' ],
					[
						'tables' => [],
						'orconds' => [ 'newactor' => "am2_xxx_actor = 11" ],
						'joins' => [],
					],
				],
			],

			'Multiple users' => [
				[
					[ 'old', 'read-old' ],
					[
						'tables' => [],
						'orconds' => [
							'userid' => "am1_user IN (1,2,3) ",
							'username' => "am1_user_text IN ('192.168.12.34','192.168.12.35',"
								. "'2600:1004:B14A:5DDD:3EBE:BBA4:BFBA:F37E') "
						],
						'joins' => [],
					]
				], [
					[ 'read-new', 'new' ],
					[
						'tables' => [],
						'orconds' => [ 'newactor' => "am1_actor IN (11,12,34) " ],
						'joins' => [],
					]
				]
			],

			'Multiple users, no use ID' => [
				[
					[ 'old', 'read-old' ],
					[
						'tables' => [],
						'orconds' => [
							'username' => "am1_user_text IN ('User1','User2','User3','192.168.12.34',"
								. "'192.168.12.35','2600:1004:B14A:5DDD:3EBE:BBA4:BFBA:F37E') "
						],
						'joins' => [],
					],
				], [
					[ 'read-new', 'new' ],
					[
						'tables' => [],
						'orconds' => [ 'newactor' => "am1_actor IN (11,12,34) " ],
						'joins' => [],
					]
				]
			],

			'Empty $users' => [ [
				[ 'old', 'read-old', 'read-new', 'new' ],
				[
					'tables' => [],
					'conds' => '1=0',
					'orconds' => [],
					'joins' => [],
				],
			] ],

			'Null $users' => [ [
				[ 'old', 'read-old', 'read-new', 'new' ],
				[
					'tables' => [],
					'conds' => '1=0',
					'orconds' => [],
					'joins' => [],
				],
			] ],

			'False $users' => [ [
				[ 'old', 'read-old', 'read-new', 'new' ],
				[
					'tables' => [],
					'conds' => '1=0',
					'orconds' => [],
					'joins' => [],
				],
			] ],
		];

		return self::makeActorCases( $inputs, $expected );
	}

	/**
	 * @dataProvider provideStages
	 */
	public function testGetWhere_exception( $stage ) {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage(
			'ActorMigrationBase::getWhere: Value for $users must be a UserIdentity or array, got string'
		);

		$m = $this->getMigration( $stage );
		$m->getWhere( $this->getDb(), 'am1_user', 'Foo' );
	}

	/**
	 * @dataProvider provideInsertRoundTrip
	 * @param string $table
	 * @param string $key
	 * @param string $pk
	 */
	public function testInsertRoundTrip( $table, $key, $pk ) {
		$u = $this->getTestUser()->getUser();
		$user = new UserIdentityValue( $u->getId(), $u->getName() );

		$stageNames = array_flip( self::STAGES_BY_NAME );

		$stages = [
			'old' => [
				'old',
				'read-old',
			],
			'read-old' => [
				'old',
				'read-old',
			],
			'read-new' => [
				'read-new',
				'new'
			],
			'new' => [
				'read-new',
				'new'
			],
		];

		$nameKey = $key . '_text';
		$actorKey = ( $key === 'am2_xxx' ? $key : substr( $key, 0, -5 ) ) . '_actor';

		foreach ( $stages as $writeStageName => $possibleReadStages ) {
			$writeStage = self::STAGES_BY_NAME[$writeStageName];
			$w = $this->getMigration( $writeStage );

			$fields = $w->getInsertValues( $this->getDb(), $key, $user );

			if ( $writeStage & SCHEMA_COMPAT_WRITE_OLD ) {
				$this->assertSame( $user->getId(), $fields[$key],
					"old field, stage={$stageNames[$writeStage]}" );
				$this->assertSame( $user->getName(), $fields[$nameKey],
					"old field, stage={$stageNames[$writeStage]}" );
			} else {
				$this->assertArrayNotHasKey( $key, $fields, "old field, stage={$stageNames[$writeStage]}" );
				$this->assertArrayNotHasKey( $nameKey, $fields, "old field, stage={$stageNames[$writeStage]}" );
			}
			if ( $writeStage & SCHEMA_COMPAT_WRITE_NEW ) {
				$this->assertArrayHasKey( $actorKey, $fields,
					"new field, stage={$stageNames[$writeStage]}" );
			} else {
				$this->assertArrayNotHasKey( $actorKey, $fields,
					"new field, stage={$stageNames[$writeStage]}" );
			}

			$id = ++self::$amId;
			$this->getDb()->newInsertQueryBuilder()
				->insertInto( $table )
				->row( [ $pk => $id ] + $fields )
				->caller( __METHOD__ )
				->execute();

			foreach ( $possibleReadStages as $readStageName ) {
				$readStage = self::STAGES_BY_NAME[$readStageName];
				$r = $this->getMigration( $readStage );

				$queryInfo = $r->getJoin( $key );
				$row = $this->getDb()->newSelectQueryBuilder()
					->queryInfo( $queryInfo )
					->from( $table )
					->where( [ $pk => $id ] )
					->caller( __METHOD__ )
					->fetchRow();

				$this->assertSame( $user->getId(), (int)$row->$key,
					"w={$stageNames[$writeStage]}, r={$stageNames[$readStage]}, id" );
				$this->assertSame( $user->getName(), $row->$nameKey,
					"w={$stageNames[$writeStage]}, r={$stageNames[$readStage]}, name" );
			}
		}
	}

	public static function provideInsertRoundTrip() {
		return [
			'normal' => [ 'actormigration1', 'am1_user', 'am1_id' ],
			'special name' => [ 'actormigration2', 'am2_xxx', 'am2_id' ],
		];
	}

	public static function provideStages() {
		$cases = [];
		foreach ( self::STAGES_BY_NAME as $name => $stage ) {
			$cases[$name] = [ $stage ];
		}
		return $cases;
	}

	/**
	 * @dataProvider provideStages
	 * @param int $stage
	 */
	public function testInsertUserIdentity( $stage ) {
		$user = $this->getMutableTestUser()->getUser();
		$userIdentity = new UserIdentityValue( $user->getId(), $user->getName() );

		$m = $this->getMigration( $stage );
		$fields = $m->getInsertValues( $this->getDb(), 'am1_user', $userIdentity );
		$id = ++self::$amId;
		$this->getDb()->newInsertQueryBuilder()
			->insertInto( 'actormigration1' )
			->row( [ 'am1_id' => $id ] + $fields )
			->caller( __METHOD__ )
			->execute();

		$qi = $m->getJoin( 'am1_user' );
		$row = $this->getDb()->newSelectQueryBuilder()
			->queryInfo( $qi )
			->from( 'actormigration1' )
			->where( [ 'am1_id' => $id ] )
			->caller( __METHOD__ )
			->fetchRow();
		$this->assertSame( $user->getId(), (int)$row->am1_user );
		$this->assertSame( $user->getName(), $row->am1_user_text );
		$this->assertSame(
			( $stage & SCHEMA_COMPAT_READ_NEW ) ? $user->getActorId() : 0,
			(int)$row->am1_actor
		);

		$m = $this->getMigration( $stage );
		$fields = $m->getInsertValues( $this->getDb(), 'dummy_user', $userIdentity );
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
	 * @param string $stage
	 * @param string $isAnon
	 * @param string $isNotAnon
	 */
	public function testIsAnon( $stage, $isAnon, $isNotAnon ) {
		$numericStage = self::STAGES_BY_NAME[$stage];
		$m = $this->getMigration( $numericStage );
		$this->assertSame( $isAnon, $m->isAnon( 'foo' ) );
		$this->assertSame( $isNotAnon, $m->isNotAnon( 'foo' ) );
	}

	public static function provideIsAnon() {
		return [
			'old' => [ 'old', 'foo = 0', 'foo != 0' ],
			'read-old' => [ 'read-old', 'foo = 0', 'foo != 0' ],
			'read-new' => [ 'read-new', 'foo IS NULL', 'foo IS NOT NULL' ],
			'new' => [ 'new', 'foo IS NULL', 'foo IS NOT NULL' ],
		];
	}

	public function testCheckDeprecation() {
		$m = new class(
			[
				'soft' => [
					'deprecatedVersion' => null,
				],
				'hard' => [
					'deprecatedVersion' => '1.34',
				],
				'gone' => [
					'removedVersion' => '1.34',
				],
			],
			SCHEMA_COMPAT_NEW,
			$this->getServiceContainer()->getActorStoreFactory()
		) extends ActorMigrationBase {
			public function checkDeprecationForTest( $key ) {
				$this->checkDeprecation( $key );
			}
		};

		$this->hideDeprecated( 'MediaWiki\User\ActorMigrationBase for \'hard\'' );

		$m->checkDeprecationForTest( 'valid' );
		$m->checkDeprecationForTest( 'soft' );
		$m->checkDeprecationForTest( 'hard' );
		try {
			$m->checkDeprecationForTest( 'gone' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'Use of MediaWiki\User\ActorMigrationBase for \'gone\' was removed in MediaWiki 1.34',
				$ex->getMessage()
			);
		}
	}

}
