<?php

namespace MediaWiki\Tests\Unit\Permissions;

use DatabaseTestHelper;
use MediaWiki\Cache\LinkCache;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Page\PageStore;
use MediaWiki\Permissions\RestrictionStore;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\Title\Title;
use MediaWikiUnitTestCase;
use MockTitleTrait;
use ReflectionClass;
use ReflectionMethod;
use RuntimeException;
use UnexpectedValueException;
use Wikimedia\Assert\PreconditionException;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\DeleteQueryBuilder;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\SelectQueryBuilder;
use Wikimedia\Rdbms\UnionQueryBuilder;

/**
 * @covers \MediaWiki\Permissions\RestrictionStore
 */
class RestrictionStoreTest extends MediaWikiUnitTestCase {
	use DummyServicesTrait;
	use MockTitleTrait;

	private const DEFAULT_RESTRICTION_TYPES = [ 'create', 'edit', 'move', 'upload' ];

	/**
	 * @param array $expectedCalls E.g.:
	 *   [
	 *     DB_REPLICA => [
	 *       'update' => callback, # to be called exactly once
	 *       'delete' => [ callback, 2 ], # to be called exactly twice
	 *       'select' => [ callback, -1 ], # may be called 0 or more times
	 *     ],
	 *   ]
	 * @return LBFactory
	 */
	private function newMockLBFactory( array $expectedCalls = [] ): LBFactory {
		if ( !isset( $expectedCalls[DB_REPLICA] ) ) {
			$expectedCalls[DB_REPLICA] = [];
		}
		$expectedCalls[DB_REPLICA]['decodeExpiry'] = [
			static function ( string $expiry ): string {
				return $expiry;
			},
			-1
		];

		$dbs = [];
		foreach ( $expectedCalls as $index => $calls ) {
			$dbs[$index] = $this->createNoOpMock(
				IDatabase::class,
				array_merge( array_keys( $calls ), [ 'newSelectQueryBuilder', 'newDeleteQueryBuilder', 'newUnionQueryBuilder' ] )
			);
			foreach ( $calls as $method => $callback ) {
				$count = 1;
				if ( is_array( $callback ) ) {
					[ $callback, $count ] = $callback;
				}
				$dbs[$index]->expects( $count < 0 ? $this->any() : $this->exactly( $count ) )
					->method( $method )->willReturnCallback( $callback );
			}
			$dbs[$index]
				->method( 'newSelectQueryBuilder' )
				->willReturnCallback( static function () use ( $dbs, $index ) {
					return new SelectQueryBuilder( $dbs[$index] );
				} );
			$dbs[$index]
				->method( 'newUnionQueryBuilder' )
				->willReturnCallback( static function () use ( $dbs, $index ) {
					return new UnionQueryBuilder( $dbs[$index] );
				} );
			$dbs[$index]
				->method( 'newDeleteQueryBuilder' )
				->willReturnCallback( static function () use ( $dbs, $index ) {
					return new DeleteQueryBuilder( $dbs[$index] );
				} );
		}

		$lb = $this->createMock( ILoadBalancer::class );
		$lb->method( 'getConnection' )->willReturnCallback(
			function ( int $index ) use ( $dbs ): IDatabase {
				$this->assertArrayHasKey( $index, $dbs );
				return $dbs[$index];
			}
		);

		$lbFactory = $this->createMock( LBFactory::class );
		$lbFactory->method( 'getReplicaDatabase' )->willReturnCallback(
			static function ( $domain = null ) use ( $lb ) {
				return $lb->getConnection( DB_REPLICA, [], $domain );
			}
		);
		$lbFactory->method( 'getPrimaryDatabase' )->willReturnCallback(
			static function ( $domain = null ) use ( $lb ) {
				return $lb->getConnection( DB_PRIMARY, [], $domain );
			}
		);
		$lbFactory->method( 'getMainLB' )->willReturn( $lb );

		return $lbFactory;
	}

	private function newRestrictionStore( array $options = [] ): RestrictionStore {
		return new RestrictionStore(
			new ServiceOptions( RestrictionStore::CONSTRUCTOR_OPTIONS, $options + [
				MainConfigNames::NamespaceProtection => [],
				MainConfigNames::RestrictionLevels => [ '', 'autoconfirmed', 'sysop' ],
				MainConfigNames::RestrictionTypes => self::DEFAULT_RESTRICTION_TYPES,
				MainConfigNames::SemiprotectedRestrictionLevels => [ 'autoconfirmed' ],
			] ),
			$this->createNoOpMock( WANObjectCache::class ),
			$this->newMockLBFactory( $options['db'] ?? [] ),
			// @todo test that these calls work correctly
			$this->createNoOpMock( LinkCache::class, [ 'addLinkObj', 'getGoodLinkFieldObj' ] ),
			$this->createNoOpMock( LinksMigration::class, [ 'getLinksConditions' ] ),
			$this->getDummyCommentStore(),
			$this->createHookContainer( isset( $options['hookFn'] )
				? [ 'TitleGetRestrictionTypes' => $options['hookFn'] ]
				: [] ),
			$this->createNoOpMock( PageStore::class )
		);
	}

	private static function newImproperPageIdentity(
		int $ns, string $dbKey, $wikiId = PageIdentity::LOCAL
	): PageIdentity {
		// PageIdentityValue doesn't allow negative namespaces, and Title::exists accesses services
		// for hooks, so we need another solution to unit-test PageIdentity objects with negative
		// namespaces (until they cease to exist).
		return new class( $ns, $dbKey, $wikiId ) extends PageReferenceValue implements PageIdentity {
			public function getId( $wikiId = PageIdentity::LOCAL ): int {
				throw new RuntimeException;
			}

			public function canExist(): bool {
				return false;
			}

			public function exists(): bool {
				return false;
			}
		};
	}

	/**
	 * @dataProvider provideNonLocalPage
	 */
	public function testNonLocalPage( string $method, ...$extraArgs ) {
		$this->expectException( PreconditionException::class );
		$this->expectExceptionMessage( 'otherwiki' );

		$obj = $this->newRestrictionStore();
		$page = new PageIdentityValue( 1, NS_MAIN, 'X', 'otherwiki' );
		$obj->$method( $page, ...$extraArgs );
	}

	public static function provideNonLocalPage() {
		// We programmatically get all public methods whose first parameter is a PageIdentity. This
		// way we'll make sure to include any new methods that are added in the future.
		$ret = [];
		$methods = ( new ReflectionClass( RestrictionStore::class ) )
			->getMethods( ReflectionMethod::IS_PUBLIC );
		foreach ( $methods as $method ) {
			$params = $method->getParameters();
			if ( !$params[0]->hasType() || $params[0]->getType()->getName() !== PageIdentity::class ) {
				continue;
			}
			$ret[$method->getName()] = [ $method->getName() ];

			foreach ( array_slice( $params, 1 ) as $param ) {
				// Extra required arguments
				if ( $param->isOptional() ) {
					break;
				}
				switch ( $param->getType()->getName() ) {
					case 'string':
						$ret[$method->getName()][] = 'x';
						break;
					case 'array':
						$ret[$method->getName()][] = [];
						break;
					default:
						throw new UnexpectedValueException(
							"{$param->getType()->getName} type not supported" );
				}
			}
		}

		return $ret;
	}

	/**
	 * @dataProvider provideGetRestrictions
	 */
	public function testGetRestrictions(
		array $expected,
		PageIdentity $page,
		string $action,
		?array $rowsToLoad,
		array $options = []
	): void {
		$obj = $this->newRestrictionStore( $options );
		if ( is_array( $rowsToLoad ) ) {
			$obj->loadRestrictionsFromRows( $page, $rowsToLoad );
		}
		$this->assertSame( $expected, $obj->getRestrictions( $page, $action ) );
	}

	public static function provideGetRestrictions(): array {
		$all = self::provideGetAllRestrictions();
		$ret = [];

		foreach ( $all as $name => $arr ) {
			[ $expected, $page ] = $arr;

			$actions = array_merge( self::DEFAULT_RESTRICTION_TYPES, [ 'vaporize' ],
				array_keys( $expected ) );
			foreach ( $actions as $action ) {
				$ret["$name ($action)"] =
					array_merge(
						[ $expected[$action] ?? [], $page, $action ],
						array_slice( $arr, 2 )
					);
			}
		}

		return $ret;
	}

	/**
	 * @dataProvider provideGetAllRestrictions
	 */
	public function testGetAllRestrictions(
		array $expected, PageIdentity $page, ?array $rowsToLoad, array $options = []
	): void {
		$obj = $this->newRestrictionStore( $options );
		if ( is_array( $rowsToLoad ) ) {
			$obj->loadRestrictionsFromRows( $page, $rowsToLoad );
		}
		$this->assertSame( $expected, $obj->getAllRestrictions( $page ) );
	}

	public static function provideGetAllRestrictions(): array {
		return [
			'Special page' => [
				[],
				self::newImproperPageIdentity( NS_SPECIAL, 'X' ),
				null,
			],
			'Media page' => [
				[],
				self::newImproperPageIdentity( NS_MEDIA, 'X' ),
				null,
			],
			'Simple existing unprotected page' => [
				[ 'edit' => [], 'move' => [] ],
				PageIdentityValue::localIdentity( 1, NS_MAIN, 'X' ),
				[],
			],
			'Simple existing protected page' => [
				[ 'edit' => [ 'sysop', 'bureaucrat' ], 'move' => [ 'sysop' ] ],
				PageIdentityValue::localIdentity( 1, NS_MAIN, 'X' ),
				[
					(object)[ 'pr_type' => 'edit', 'pr_level' => 'sysop,bureaucrat',
						'pr_expiry' => 'infinity', 'pr_cascade' => 0 ],
					(object)[ 'pr_type' => 'move', 'pr_level' => 'sysop',
						'pr_expiry' => 'infinity', 'pr_cascade' => 0 ],
				],
			],
			'Protection type not allowed' => [
				[ 'edit' => [ 'sysop' ] ],
				PageIdentityValue::localIdentity( 1, NS_MAIN, 'X' ),
				[
					(object)[ 'pr_type' => 'edit', 'pr_level' => 'sysop',
						'pr_expiry' => 'infinity', 'pr_cascade' => 0 ],
					(object)[ 'pr_type' => 'move', 'pr_level' => 'sysop',
						'pr_expiry' => 'infinity', 'pr_cascade' => 0 ],
				],
				[ MainConfigNames::RestrictionTypes => [ 'create', 'edit', 'upload' ] ],
			],
			'Expired protection' => [
				[ 'edit' => [], 'move' => [ 'sysop' ] ],
				PageIdentityValue::localIdentity( 1, NS_MAIN, 'X' ),
				[
					(object)[ 'pr_type' => 'edit', 'pr_level' => 'sysop',
						'pr_expiry' => '20200101000000', 'pr_cascade' => 0 ],
					(object)[ 'pr_type' => 'move', 'pr_level' => 'sysop',
						'pr_expiry' => 'infinity', 'pr_cascade' => 0 ],
				],
			],
		];
	}

	/**
	 * @dataProvider provideGetRestrictionExpiry
	 */
	public function testGetRestrictionExpiry(
		?string $expected,
		PageIdentity $page,
		string $action,
		?array $rowsToLoad,
		array $options = []
	): void {
		$obj = $this->newRestrictionStore( $options );
		if ( is_array( $rowsToLoad ) ) {
			$obj->loadRestrictionsFromRows( $page, $rowsToLoad );
		}
		$this->assertSame( $expected, $obj->getRestrictionExpiry( $page, $action ) );
	}

	public static function provideGetRestrictionExpiry(): array {
		return [
			'Special page' => [
				null,
				self::newImproperPageIdentity( NS_SPECIAL, 'X' ),
				'edit',
				null,
			],
			'Media page' => [
				null,
				self::newImproperPageIdentity( NS_MEDIA, 'X' ),
				'edit',
				null,
			],
			'Simple existing unprotected page' => [
				'infinity',
				PageIdentityValue::localIdentity( 1, NS_MAIN, 'X' ),
				'edit',
				[],
			],
			'Simple existing protected page (edit)' => [
				'20760101000000',
				PageIdentityValue::localIdentity( 1, NS_MAIN, 'X' ),
				'edit',
				[
					(object)[ 'pr_type' => 'edit', 'pr_level' => 'sysop',
						'pr_expiry' => '20760101000000', 'pr_cascade' => 0 ],
					(object)[ 'pr_type' => 'move', 'pr_level' => 'sysop',
						'pr_expiry' => 'infinity', 'pr_cascade' => 0 ],
				],
			],
			'Simple existing protected page (move)' => [
				'20670101000000',
				PageIdentityValue::localIdentity( 1, NS_MAIN, 'X' ),
				'move',
				[
					(object)[ 'pr_type' => 'edit', 'pr_level' => 'sysop',
						'pr_expiry' => 'infinity', 'pr_cascade' => 0 ],
					(object)[ 'pr_type' => 'move', 'pr_level' => 'sysop',
						'pr_expiry' => '20670101000000', 'pr_cascade' => 0 ],
				],
			],
			'Simple existing protected page (unrecognized)' => [
				null,
				PageIdentityValue::localIdentity( 1, NS_MAIN, 'X' ),
				'unrecognized',
				[
					(object)[ 'pr_type' => 'edit', 'pr_level' => 'sysop',
						'pr_expiry' => 'infinity', 'pr_cascade' => 0 ],
					(object)[ 'pr_type' => 'move', 'pr_level' => 'sysop',
						'pr_expiry' => 'infinity', 'pr_cascade' => 0 ],
				],
			],
			'Simple existing expired protected page (edit)' => [
				'infinity',
				PageIdentityValue::localIdentity( 1, NS_MAIN, 'X' ),
				'edit',
				[
					(object)[ 'pr_type' => 'edit', 'pr_level' => 'sysop',
						'pr_expiry' => '20160101000000', 'pr_cascade' => 0 ],
					(object)[ 'pr_type' => 'move', 'pr_level' => 'sysop',
						'pr_expiry' => 'infinity', 'pr_cascade' => 0 ],
				],
			],
			'Simple existing expired protected page (move)' => [
				'infinity',
				PageIdentityValue::localIdentity( 1, NS_MAIN, 'X' ),
				'move',
				[
					(object)[ 'pr_type' => 'edit', 'pr_level' => 'sysop',
						'pr_expiry' => 'infinity', 'pr_cascade' => 0 ],
					(object)[ 'pr_type' => 'move', 'pr_level' => 'sysop',
						'pr_expiry' => '20170101000000', 'pr_cascade' => 0 ],
				],
			],
			'Simple existing expired protected page (unrecognized)' => [
				null,
				PageIdentityValue::localIdentity( 1, NS_MAIN, 'X' ),
				'unrecognized',
				[
					(object)[ 'pr_type' => 'edit', 'pr_level' => 'sysop',
						'pr_expiry' => '20170101000000', 'pr_cascade' => 0 ],
					(object)[ 'pr_type' => 'unrecognized', 'pr_level' => 'sysop',
						'pr_expiry' => '20170101000000', 'pr_cascade' => 0 ],
				],
			],
		];
	}

	/**
	 * @dataProvider provideGetCreateProtection
	 */
	public function testGetCreateProtection(
		?array $expected, PageIdentity $page, $return, array $options = []
	): void {
		if ( $page->canExist() && !$page->exists() ) {
			$options['db'] = [ DB_REPLICA => [ 'selectRow' =>
				function (
					$table, $vars, $conds, string $fname, $options = [], $join_conds = []
				) use ( $page, $return ) {
					$options = (array)$options;
					$options['LIMIT'] = 1;
					$db = new DatabaseTestHelper( __CLASS__ );
					$sql = trim( preg_replace( '/\s+/', ' ', $db->selectSQLText(
						$table, $vars, $conds, $fname, $options, $join_conds ) ) );
					$this->assertSame(
						'SELECT pt_user,pt_expiry,pt_create_perm,comment_pt_reason.comment_text ' .
						'AS pt_reason_text,comment_pt_reason.comment_data AS pt_reason_data,' .
						'comment_pt_reason.comment_id AS pt_reason_cid ' .
						'FROM protected_titles JOIN comment comment_pt_reason ON ' .
						'((comment_pt_reason.comment_id = pt_reason_id)) ' .
						"WHERE pt_namespace = {$page->getNamespace()} AND " .
						"pt_title = '{$page->getDBkey()}' LIMIT 1",
						$sql );

					return is_array( $return ) ? (object)$return : $return;
				}
			] ];
		}
		$obj = $this->newRestrictionStore( $options );
		$this->assertSame( $expected, $obj->getCreateProtection( $page ) );
	}

	public static function provideGetCreateProtection(): array {
		$ret = [
			'Special page' => [ null, self::newImproperPageIdentity( NS_SPECIAL, 'X' ), null ],
			'Media page' => [ null, self::newImproperPageIdentity( NS_MEDIA, 'X' ), null ],
			'Existing page' => [ null, PageIdentityValue::localIdentity( 1, NS_MAIN, 'X' ), null ],
			'Unprotected' => [ null, PageIdentityValue::localIdentity( 0, NS_MAIN, 'X' ), false ],
		];
		$protectedTests = [
			'sysop' => 'editprotected',
			'autoconfirmed' => 'editsemiprotected',
			'editprotected' => 'editprotected',
			'editsemiprotected' => 'editsemiprotected',
			'custom' => 'custom',
		];
		foreach ( $protectedTests as $db => $returned ) {
			$ret["Protected ($db)"] = [
				[
					'user' => 123,
					'expiry' => 'infinity',
					'permission' => $returned,
					'reason' => 'reason',
				],
				PageIdentityValue::localIdentity( 0, NS_MAIN, 'X' ),
				[
					'pt_user' => 123,
					'pt_expiry' => 'infinity',
					'pt_create_perm' => $db,
					'pt_reason_id' => 456,
					'pt_reason_data' => '{}',
					'pt_reason_text' => 'reason',
				],
			];
		}

		return $ret;
	}

	/**
	 * @dataProvider provideDeleteCreateProtection
	 */
	public function testDeleteCreateProtection( PageIdentity $page ): void {
		$obj = $this->newRestrictionStore( [ 'db' => [ DB_PRIMARY => [ 'delete' =>
			function ( string $table, array $where, string $method ) use ( $page ): bool {
				$this->assertSame( 'protected_titles', $table );
				$this->assertSame(
					[ 'pt_namespace' => $page->getNamespace(), 'pt_title' => $page->getDBkey() ],
					$where
				);
				return true;
			}
		] ] ] );

		$obj->deleteCreateProtection( $page );
	}

	public static function provideDeleteCreateProtection(): array {
		return [
			// Most of these don't actually make sense, but test current behavior regardless.
			'Special page' => [ self::newImproperPageIdentity( NS_SPECIAL, 'X' ) ],
			'Media page' => [ self::newImproperPageIdentity( NS_MEDIA, 'X' ) ],
			'Nonexistent page' => [ PageIdentityValue::localIdentity( 0, NS_MAIN, 'X' ) ],
			'Existing page' => [ PageIdentityValue::localIdentity( 1, NS_MAIN, 'X' ) ],
		];
	}

	/**
	 * @dataProvider provideIsSemiProtected
	 */
	public function testIsSemiProtected(
		bool $expected, ?array $rowsToLoad, array $options = []
	): void {
		$page = $options['page'] ?? PageIdentityValue::localIdentity( 1, NS_MAIN, 'X' );
		$obj = $this->newRestrictionStore( $options );
		if ( is_array( $rowsToLoad ) ) {
			$obj->loadRestrictionsFromRows( $page, $rowsToLoad );
		}
		$this->assertSame( $expected, $obj->isSemiProtected( $page, 'edit' ) );
	}

	public static function provideIsSemiProtected(): array {
		return [
			'Special page' => [ false, null,
				[ 'page' => self::newImproperPageIdentity( NS_SPECIAL, 'X' ) ] ],
			'Media page' => [ false, null,
				[ 'page' => self::newImproperPageIdentity( NS_MEDIA, 'X' ) ] ],
			'Unprotected page' => [
				false,
				[ (object)[ 'pr_type' => 'move', 'pr_level' => 'autoconfirmed',
					'pr_expiry' => 'infinity', 'pr_cascade' => '0' ] ],
			],
			'Semiprotected page' => [
				true,
				[
					(object)[ 'pr_type' => 'edit', 'pr_level' => 'autoconfirmed',
						'pr_expiry' => 'infinity', 'pr_cascade' => '0' ],
					(object)[ 'pr_type' => 'move', 'pr_level' => 'sysop',
						'pr_expiry' => 'infinity', 'pr_cascade' => '0' ],
				],
			],
			'Fully protected page' => [
				false,
				[
					(object)[ 'pr_type' => 'edit', 'pr_level' => 'sysop',
						'pr_expiry' => 'infinity', 'pr_cascade' => '0' ],
					(object)[ 'pr_type' => 'move', 'pr_level' => 'autoconfirmed',
						'pr_expiry' => 'infinity', 'pr_cascade' => '0' ],
				],
			],
			'No semiprotection configured' => [
				false,
				[ (object)[ 'pr_type' => 'edit', 'pr_level' => 'autoconfirmed',
						'pr_expiry' => 'infinity', 'pr_cascade' => '0' ] ],
				[ MainConfigNames::SemiprotectedRestrictionLevels => [] ],
			],
			'Config with editsemiprotected' => [
				true,
				[ (object)[ 'pr_type' => 'edit', 'pr_level' => 'autoconfirmed',
						'pr_expiry' => 'infinity', 'pr_cascade' => '0' ] ],
				[ MainConfigNames::SemiprotectedRestrictionLevels => [ 'editsemiprotected' ] ],
			],
			'Data with editsemiprotected' => [
				true,
				[ (object)[ 'pr_type' => 'edit', 'pr_level' => 'editsemiprotected',
						'pr_expiry' => 'infinity', 'pr_cascade' => '0' ] ],
			],
			'Config and data with editsemiprotected' => [
				true,
				[ (object)[ 'pr_type' => 'edit', 'pr_level' => 'editsemiprotected',
						'pr_expiry' => 'infinity', 'pr_cascade' => '0' ] ],
				[ MainConfigNames::SemiprotectedRestrictionLevels => [ 'editsemiprotected' ] ],
			],
			'Semiprotection plus other protection level' => [
				false,
				[ (object)[ 'pr_type' => 'edit', 'pr_level' => 'autoconfirmed,superman',
						'pr_expiry' => 'infinity', 'pr_cascade' => '0' ] ],
				[ MainConfigNames::RestrictionLevels => [ '', 'autoconfirmed', 'sysop', 'superman' ] ],
			],
			'Two semiprotections' => [
				true,
				[ (object)[ 'pr_type' => 'edit', 'pr_level' => 'autoconfirmed,superman',
						'pr_expiry' => 'infinity', 'pr_cascade' => '0' ] ],
				[ MainConfigNames::RestrictionLevels => [ '', 'autoconfirmed', 'sysop', 'superman' ],
					MainConfigNames::SemiprotectedRestrictionLevels => [ 'autoconfirmed', 'superman' ] ],
			],
		];
	}

	/**
	 * @dataProvider provideIsProtected
	 */
	public function testIsProtected(
		bool $expected, ?array $rowsToLoad, array $options = []
	): void {
		$page = $options['page'] ?? PageIdentityValue::localIdentity( 1, NS_MAIN, 'X' );
		$action = $options['action'] ?? '';
		$obj = $this->newRestrictionStore( $options );
		if ( is_array( $rowsToLoad ) ) {
			$obj->loadRestrictionsFromRows( $page, $rowsToLoad );
		}
		$this->assertSame( $expected, $obj->isProtected( $page, $action ) );
	}

	public static function provideIsProtected(): array {
		return [
			'Special page' => [ true, null,
				[ 'page' => self::newImproperPageIdentity( NS_SPECIAL, 'X' ) ] ],
			'Media page' => [ false, null,
				[ 'page' => self::newImproperPageIdentity( NS_MEDIA, 'X' ) ] ],
			'Unprotected page' => [ false, [] ],
			'Semiprotected page' => [
				true,
				[ (object)[ 'pr_type' => 'edit', 'pr_level' => 'autoconfirmed',
						'pr_expiry' => 'infinity', 'pr_cascade' => '0' ] ],
			],
			'Fully protected page' => [
				true,
				[ (object)[ 'pr_type' => 'edit', 'pr_level' => 'sysop',
						'pr_expiry' => 'infinity', 'pr_cascade' => '0' ] ],
			],
			'Protected against empty string' => [
				false,
				[ (object)[ 'pr_type' => 'edit', 'pr_level' => '',
						'pr_expiry' => 'infinity', 'pr_cascade' => '0' ] ],
			],
			'Unrecognized protection' => [
				false,
				[ (object)[ 'pr_type' => 'edit', 'pr_level' => 'unrecognized',
						'pr_expiry' => 'infinity', 'pr_cascade' => '0' ] ],
			],
			'Unrecognized plus recognized protection' => [
				true,
				[ (object)[ 'pr_type' => 'edit', 'pr_level' => 'sysop,unrecognized',
						'pr_expiry' => 'infinity', 'pr_cascade' => '0' ] ],
			],

			'Check unrecognized protection type' => [
				false,
				[
					(object)[ 'pr_type' => 'edit', 'pr_level' => 'sysop',
						'pr_expiry' => 'infinity', 'pr_cascade' => '0' ],
					(object)[ 'pr_type' => 'move', 'pr_level' => 'sysop',
						'pr_expiry' => 'infinity', 'pr_cascade' => '0' ],
					(object)[ 'pr_type' => 'unrecognized', 'pr_level' => 'sysop',
						'pr_expiry' => 'infinity', 'pr_cascade' => '0' ],
				],
				[ 'action' => 'unrecognized' ],
			],
			'Check custom protection type' => [
				true,
				[ (object)[ 'pr_type' => 'custom', 'pr_level' => 'sysop',
					'pr_expiry' => 'infinity', 'pr_cascade' => '0' ], ],
				[ 'action' => 'custom', MainConfigNames::RestrictionTypes =>
					array_merge( self::DEFAULT_RESTRICTION_TYPES, [ 'custom' ] ) ],
			],
			'Check custom protection level' => [
				true,
				[ (object)[ 'pr_type' => 'edit', 'pr_level' => 'custom',
					'pr_expiry' => 'infinity', 'pr_cascade' => '0' ], ],
				[ 'action' => 'edit', 'RestrictionLevels' =>
					[ '', 'autoconfirmed', 'sysop', 'custom' ] ],
			],

			'Check edit protection of edit-protected page' => [
				true,
				[ (object)[ 'pr_type' => 'edit', 'pr_level' => 'sysop',
						'pr_expiry' => 'infinity', 'pr_cascade' => '0' ] ],
				[ 'action' => 'edit' ],
			],
			'Check move protection of edit-protected page' => [
				false,
				[ (object)[ 'pr_type' => 'edit', 'pr_level' => 'sysop',
						'pr_expiry' => 'infinity', 'pr_cascade' => '0' ] ],
				[ 'action' => 'move' ],
			],
			'Check move protection of move-protected page' => [
				true,
				[ (object)[ 'pr_type' => 'move', 'pr_level' => 'sysop',
						'pr_expiry' => 'infinity', 'pr_cascade' => '0' ] ],
				[ 'action' => 'move' ],
			],
			'Check edit protection of move-protected page' => [
				false,
				[ (object)[ 'pr_type' => 'move', 'pr_level' => 'sysop',
						'pr_expiry' => 'infinity', 'pr_cascade' => '0' ] ],
				[ 'action' => 'edit' ],
			],
			'Check edit protection of edit- and move-protected page' => [
				true,
				[
					(object)[ 'pr_type' => 'edit', 'pr_level' => 'sysop',
						'pr_expiry' => 'infinity', 'pr_cascade' => '0' ],
					(object)[ 'pr_type' => 'move', 'pr_level' => 'sysop',
						'pr_expiry' => 'infinity', 'pr_cascade' => '0' ],
				],
				[ 'action' => 'edit' ],
			],
			'Check move protection of edit- and move-protected page' => [
				true,
				[
					(object)[ 'pr_type' => 'edit', 'pr_level' => 'sysop',
						'pr_expiry' => 'infinity', 'pr_cascade' => '0' ],
					(object)[ 'pr_type' => 'move', 'pr_level' => 'sysop',
						'pr_expiry' => 'infinity', 'pr_cascade' => '0' ],
				],
				[ 'action' => 'move' ],
			],
		];
	}

	/**
	 * @dataProvider provideListApplicableRestrictionTypes
	 */
	public function testListApplicableRestrictionTypes(
		array $expected, PageIdentity $page, array $options = []
	): void {
		$obj = $this->newRestrictionStore( $options );

		$this->assertSame( $expected, $obj->listApplicableRestrictionTypes( $page ) );
	}

	public static function provideListApplicableRestrictionTypes(): array {
		$expandedRestrictions = array_merge( self::DEFAULT_RESTRICTION_TYPES, [ 'liquify' ] );
		return [
			'Special page' => [
				[],
				self::newImproperPageIdentity( NS_SPECIAL, 'X' ),
			],
			'Media page' => [
				[],
				self::newImproperPageIdentity( NS_MEDIA, 'X' ),
			],
			'Nonexistent page' => [
				[ 'create' ],
				PageIdentityValue::localIdentity( 0, NS_MAIN, 'X' ),
			],
			'Existing page' => [
				[ 'edit', 'move' ],
				PageIdentityValue::localIdentity( 1, NS_MAIN, 'X' ),
			],
			'Nonexistent file' => [
				[ 'create' ],
				PageIdentityValue::localIdentity( 0, NS_FILE, 'X' ),
			],
			'Existing file' => [
				[ 'edit', 'move', 'upload' ],
				PageIdentityValue::localIdentity( 1, NS_FILE, 'X' ),
			],

			'Nonexistent page with no create' => [
				[],
				PageIdentityValue::localIdentity( 0, NS_MAIN, 'X' ),
				[ MainConfigNames::RestrictionTypes => [ 'edit', 'move', 'upload' ] ],
			],
			'Existing page with no move' => [
				[ 'edit' ],
				PageIdentityValue::localIdentity( 1, NS_MAIN, 'X' ),
				[ MainConfigNames::RestrictionTypes => [ 'create', 'edit', 'upload' ] ],
			],
			'Nonexistent file with no upload' => [
				[ 'create' ],
				PageIdentityValue::localIdentity( 0, NS_FILE, 'X' ),
				[ MainConfigNames::RestrictionTypes => [ 'create', 'edit', 'move' ] ],
			],

			'Special page with extra type' => [
				[],
				self::newImproperPageIdentity( NS_SPECIAL, 'X' ),
				[ MainConfigNames::RestrictionTypes => $expandedRestrictions ],
			],
			'Media page with extra type' => [
				[],
				self::newImproperPageIdentity( NS_MEDIA, 'X' ),
				[ MainConfigNames::RestrictionTypes => $expandedRestrictions ],
			],
			'Nonexistent page with extra type' => [
				[ 'create' ],
				PageIdentityValue::localIdentity( 0, NS_MAIN, 'X' ),
				[ MainConfigNames::RestrictionTypes => $expandedRestrictions ],
			],
			'Existing page with extra type' => [
				[ 'edit', 'move', 'liquify' ],
				PageIdentityValue::localIdentity( 1, NS_MAIN, 'X' ),
				[ MainConfigNames::RestrictionTypes => $expandedRestrictions ],
			],
			'Nonexistent file with extra type' => [
				[ 'create' ],
				PageIdentityValue::localIdentity( 0, NS_FILE, 'X' ),
				[ MainConfigNames::RestrictionTypes => $expandedRestrictions ],
			],
			'Existing file with extra type' => [
				[ 'edit', 'move', 'upload', 'liquify' ],
				PageIdentityValue::localIdentity( 1, NS_FILE, 'X' ),
				[ MainConfigNames::RestrictionTypes => $expandedRestrictions ],
			],

			'Hook' => [
				[ 'move', 'liquify' ],
				PageIdentityValue::localIdentity( 1, NS_MAIN, 'X' ),
				[ 'hookFn' => static function ( Title $title, array &$types ): bool {
					self::assertEquals( Title::castFromPageIdentity(
						PageIdentityValue::localIdentity( 1, NS_MAIN, 'X' ) ), $title );
					self::assertSame( [ 'edit', 'move' ], $types );

					$types = [ 'move', 'liquify' ];

					return false;
				} ],
			],
			'Hook not run for special page' => [
				[],
				self::newImproperPageIdentity( NS_SPECIAL, 'X' ),
				[ 'hookFn' => static function () {
					Assert::fail( 'Should be unreached' );
				} ],
			],
			'Hook not run for media page' => [
				[],
				self::newImproperPageIdentity( NS_MEDIA, 'X' ),
				[ 'hookFn' => static function () {
					Assert::fail( 'Should be unreached' );
				} ],
			],
		];
	}

	/**
	 * @dataProvider provideListAllRestrictionTypes
	 */
	public function testListAllRestrictionTypes(
		array $expected, array $args, array $options = []
	) {
		$obj = $this->newRestrictionStore( $options );
		$this->assertSame( $expected, $obj->listAllRestrictionTypes( ...$args ) );
	}

	public static function provideListAllRestrictionTypes() {
		$expandedRestrictions = array_merge( self::DEFAULT_RESTRICTION_TYPES, [ 'solidify' ] );
		return [
			'Exists' => [ [ 'edit', 'move', 'upload' ], [ true ] ],
			'Default is exists' => [ [ 'edit', 'move', 'upload' ], [] ],
			'Nonexistent' => [ [ 'create' ], [ false ] ],

			'Exists with extra restriction type' => [
				[ 'edit', 'move', 'upload', 'solidify' ],
				[ true ],
				[ MainConfigNames::RestrictionTypes => $expandedRestrictions ],
			],
			'Default is exists with extra restriction type' => [
				[ 'edit', 'move', 'upload', 'solidify' ],
				[],
				[ MainConfigNames::RestrictionTypes => $expandedRestrictions ],
			],
			'Nonexistent with extra restriction type' => [
				[ 'create' ],
				[ false ],
				[ MainConfigNames::RestrictionTypes => $expandedRestrictions ],
			],

			'Exists with no edit' => [
				[ 'move', 'upload' ],
				[ true ],
				[ MainConfigNames::RestrictionTypes => [ 'create', 'move', 'upload' ] ],
			],
			'Exists with only create' => [
				[],
				[ true ],
				[ MainConfigNames::RestrictionTypes => [ 'create' ] ],
			],
			'Nonexistent with no create' => [
				[],
				[ false ],
				[ MainConfigNames::RestrictionTypes => [ 'edit', 'move', 'upload', 'solidify' ] ],
			],
			'Nonexistent with no upload' => [
				[ 'create' ],
				[ false ],
				[ MainConfigNames::RestrictionTypes => [ 'create', 'edit', 'move', 'solidify' ] ],
			],
			'Nonexistent with no create or upload' => [
				[],
				[ false ],
				[ MainConfigNames::RestrictionTypes => [ 'edit', 'move', 'solidify' ] ],
			],
		];
	}

	/**
	 * @dataProvider provideAreRestrictionsLoaded
	 */
	public function testAreRestrictionsLoaded(
		bool $expected, PageIdentity $page, ?array $rowsToLoad = null, array $options = []
	): void {
		$obj = $this->newRestrictionStore( $options );
		if ( is_array( $rowsToLoad ) ) {
			$obj->loadRestrictionsFromRows( $page, $rowsToLoad );
		}
		$this->assertSame( $expected, $obj->areRestrictionsLoaded( $page ) );
	}

	public static function provideAreRestrictionsLoaded(): array {
		return [
			'Special page' => [ false, self::newImproperPageIdentity( NS_SPECIAL, 'X' ) ],
			'Media page' => [ false, self::newImproperPageIdentity( NS_MEDIA, 'X' ) ],
			'Regular page' => [ false, PageIdentityValue::localIdentity( 1, NS_MAIN, 'X' ) ],
			'Regular page with no restrictions' =>
				[ true, PageIdentityValue::localIdentity( 1, NS_MAIN, 'X' ), [] ],
			'Regular page with restrictions' => [
				true,
				PageIdentityValue::localIdentity( 1, NS_MAIN, 'X' ),
				[ (object)[ 'pr_type' => 'edit', 'pr_level' => 'sysop',
					'pr_expiry' => 'infinity', 'pr_cascade' => 0 ] ],
			],
		];
	}

	/**
	 * @dataProvider provideAreRestrictionsCascading
	 */
	public function testAreRestrictionsCascading(
		bool $expected, PageIdentity $page, ?array $rowsToLoad, array $options = []
	): void {
		$obj = $this->newRestrictionStore( $options );
		if ( is_array( $rowsToLoad ) ) {
			$obj->loadRestrictionsFromRows( $page, $rowsToLoad );
		}
		$this->assertSame( $expected, $obj->areRestrictionsCascading( $page ) );
	}

	public static function provideAreRestrictionsCascading(): array {
		return [
			'Special page' => [ false, self::newImproperPageIdentity( NS_SPECIAL, 'X' ), null ],
			'Media page' => [ false, self::newImproperPageIdentity( NS_MEDIA, 'X' ), null ],
			'Regular page with no restrictions' =>
				[ false, PageIdentityValue::localIdentity( 1, NS_MAIN, 'X' ), [] ],
			'Regular page with restrictions' => [
				false,
				PageIdentityValue::localIdentity( 1, NS_MAIN, 'X' ),
				[ (object)[ 'pr_type' => 'edit', 'pr_level' => 'sysop',
					'pr_expiry' => 'infinity', 'pr_cascade' => 0 ] ],
			],
			'Regular page with cascading restrictions' => [
				true,
				PageIdentityValue::localIdentity( 1, NS_MAIN, 'X' ),
				[ (object)[ 'pr_type' => 'edit', 'pr_level' => 'sysop',
					'pr_expiry' => 'infinity', 'pr_cascade' => 1 ] ],
			],
			'Regular page with some cascading restrictions and some not' => [
				true,
				PageIdentityValue::localIdentity( 1, NS_MAIN, 'X' ),
				[
					(object)[ 'pr_type' => 'edit', 'pr_level' => 'sysop',
						'pr_expiry' => 'infinity', 'pr_cascade' => 0 ],
					(object)[ 'pr_type' => 'move', 'pr_level' => 'sysop',
						'pr_expiry' => 'infinity', 'pr_cascade' => 1 ],
				],
			],
		];
	}

	public function testFlushRestrictions(): void {
		$obj = $this->newRestrictionStore();
		$page = PageIdentityValue::localIdentity( 1, NS_MAIN, 'X' );
		$this->assertFalse( $obj->areRestrictionsLoaded( $page ) );
		$obj->loadRestrictionsFromRows( $page, [] );
		$this->assertTrue( $obj->areRestrictionsLoaded( $page ) );
		$obj->flushRestrictions( $page );
		$this->assertFalse( $obj->areRestrictionsLoaded( $page ) );
	}

	public function testGetCascadeProtectionSources() {
		$obj = $this->newRestrictionStore( [ 'db' => [ DB_REPLICA => [
			'select' => [
				static function () {
					return new FakeResultWrapper( [
						(object)[ 'pr_page' => 1, 'page_namespace' => NS_MAIN, 'page_title' => 'test',
							'pr_expiry' => 'infinity', 'pr_type' => 'edit', 'pr_level' => 'Sysop',
							'tl_from' => 1 ]
					] );
				},
				2
			]
		] ] ] );

		$page = PageIdentityValue::localIdentity( 1, NS_MAIN, 'X' );
		[ $sources, $restrictions, $tlSources, $ilSources ] = $obj->getCascadeProtectionSources( $page );
		$this->assertCount( 1, $sources );
		$this->assertArrayHasKey( 'edit', $restrictions );
		$this->assertCount( 0, $ilSources );
		$this->assertCount( 1, $tlSources );
	}

	public function testGetCascadeProtectionSourcesFile() {
		$obj = $this->newRestrictionStore( [ 'db' => [ DB_REPLICA => [
			'select' => [
				static function () {
					return new FakeResultWrapper( [
						(object)[ 'pr_page' => 1, 'page_namespace' => NS_MAIN, 'page_title' => 'test1',
							'pr_expiry' => 'infinity', 'pr_type' => 'edit', 'pr_level' => 'Sysop',
							'tl_from' => 1, 'il_from' => 2 ],
						(object)[ 'pr_page' => 2, 'page_namespace' => NS_MAIN, 'page_title' => 'test2',
							'pr_expiry' => 'infinity', 'pr_type' => 'edit', 'pr_level' => 'Sysop',
							'tl_from' => 1, 'il_from' => 2 ]
					] );
				},
				3
			]
		] ] ] );

		$page = PageIdentityValue::localIdentity( 1, NS_FILE, 'Image.jpg' );
		[ $sources, $restrictions, $tlSources, $ilSources ] = $obj->getCascadeProtectionSources( $page );
		$this->assertCount( 2, $sources );
		$this->assertCount( 1, $ilSources );
		$this->assertCount( 1, $tlSources );
		$this->assertArrayHasKey( 'edit', $restrictions );
	}

	public function testGetCascadeProtectionSourcesSpecialPage() {
		$obj = $this->newRestrictionStore( [ 'db' => [ DB_REPLICA => [ 'select' => [
			static function () {
				return [];
			},
			0
		] ] ] ] );

		$page = $this->makeMockTitle( 'Whatlinkshere', [ 'namespace' => NS_SPECIAL ] );
		[ $sources, $restrictions, $ilSources ] = $obj->getCascadeProtectionSources( $page );
		$this->assertCount( 0, $sources );
		$this->assertCount( 0, $restrictions );
		$this->assertCount( 0, $ilSources );
	}

	public function testShouldNotFetchProtectionSettingsIfActionCannotBeRestricted(): void {
		$store = new RestrictionStore(
			new ServiceOptions( RestrictionStore::CONSTRUCTOR_OPTIONS, [
					MainConfigNames::NamespaceProtection => [],
					MainConfigNames::RestrictionLevels => [ '', 'autoconfirmed', 'sysop' ],
					MainConfigNames::RestrictionTypes => self::DEFAULT_RESTRICTION_TYPES,
					MainConfigNames::SemiprotectedRestrictionLevels => [ 'autoconfirmed' ],
				] ),
			WANObjectCache::newEmpty(),
			$this->createNoopMock( LBFactory::class ),
			$this->createMock( LinkCache::class ),
			$this->createMock( LinksMigration::class ),
			$this->createMock( CommentStore::class ),
			$this->createMock( HookContainer::class ),
			$this->createMock( PageStore::class )
		);

		$page = PageIdentityValue::localIdentity( 1, NS_MAIN, 'Test' );

		$this->assertSame( [], $store->getRestrictions( $page, 'non-restrictable-action' ) );
	}

}
