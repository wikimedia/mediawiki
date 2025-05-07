<?php

use MediaWiki\Installer\DatabaseUpdater;
use MediaWiki\Maintenance\FakeMaintenance;
use Psr\Log\NullLogger;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DatabaseSqlite;
use Wikimedia\Rdbms\TransactionProfiler;

/**
 * @group sqlite
 * @group Database
 * @group medium
 */
class DatabaseSqliteUpgradeTest extends \MediaWikiIntegrationTestCase {
	/** @var DatabaseSqlite */
	protected $db;

	/** @var array|null */
	protected $currentSchema;

	protected function setUp(): void {
		parent::setUp();

		if ( !Sqlite::isPresent() ) {
			$this->markTestSkipped( 'No SQLite support detected' );
		}
		$this->db = $this->newMockDb();
		if ( version_compare( $this->getDb()->getServerVersion(), '3.6.0', '<' ) ) {
			$this->markTestSkipped( "SQLite at least 3.6 required, {$this->getDb()->getServerVersion()} found" );
		}
	}

	/**
	 * @param string|null $version
	 * @return \PHPUnit\Framework\MockObject\MockObject|DatabaseSqlite
	 */
	private function newMockDb( $version = null ) {
		$mock = $this->getMockBuilder( DatabaseSqlite::class )
			->setConstructorArgs( [ [
				'dbFilePath' => ':memory:',
				'dbname' => 'Foo',
				'schema' => null,
				'host' => false,
				'user' => false,
				'password' => false,
				'tablePrefix' => '',
				'cliMode' => true,
				'agent' => 'unit-tests',
				'serverName' => null,
				'flags' => DBO_DEFAULT,
				'variables' => [ 'synchronous' => 'NORMAL', 'temp_store' => 'MEMORY' ],
				'profiler' => null,
				'topologyRole' => Database::ROLE_STREAMING_MASTER,
				'trxProfiler' => new TransactionProfiler(),
				'errorLogger' => null,
				'deprecationLogger' => new NullLogger(),
				'srvCache' => new HashBagOStuff(),
			] ] )->onlyMethods( array_merge(
				[ 'query' ],
				$version ? [ 'getServerVersion' ] : []
			) )->getMock();

		$mock->initConnection();

		$mock->method( 'query' )->willReturn( true );

		if ( $version ) {
			$mock->method( 'getServerVersion' )->willReturn( $version );
		}

		return $mock;
	}

	/**
	 * @coversNothing
	 */
	public function testEntireSchema() {
		$result = Sqlite::checkSqlSyntax( dirname( __FILE__, 6 ) . "/sql/sqlite/tables-generated.sql" );
		if ( $result !== true ) {
			$this->fail( $result );
		}
		$this->assertTrue( true ); // avoid test being marked as incomplete due to lack of assertions
	}

	/**
	 * Runs upgrades of older databases and compares results with current schema
	 * @coversNothing
	 * @param string $version
	 * @dataProvider provideSupportedVersions
	 */
	public function testUpgradeFromVersion( string $version ) {
		$currentSchema =& $this->currentSchema;
		if ( $currentSchema === null ) {
			$currentDB = $this->initAndUpgradeTestDB( null );
			$currentSchema = [];
			foreach ( $this->getTables( $currentDB ) as $table ) {
				$currentSchema[$table] = [
					'columns' => $this->getColumns( $currentDB, $table ),
					'indexes' => $this->getIndexes( $currentDB, $table )
				];
			}
			$currentDB->close();
		}

		// Mismatches for these columns we can safely ignore
		$ignoredColumns = [];

		$versions = "upgrading from $version to " . MW_VERSION;
		$db = $this->initAndUpgradeTestDB( $version );
		$tables = $this->getTables( $db );

		$this->assertEquals( array_keys( $currentSchema ), $tables, "Different tables $versions" );

		foreach ( $tables as $table ) {
			$cols = $this->getColumns( $db, $table );
			$this->assertEquals(
				array_keys( $currentSchema[$table]['columns'] ),
				array_keys( $cols ),
				"Mismatching columns for table \"$table\" $versions"
			);

			foreach ( $currentSchema[$table]['columns'] as $name => $column ) {
				$fullName = "$table.$name";
				$this->assertEquals(
					(bool)$column->pk,
					(bool)$cols[$name]->pk,
					"PRIMARY KEY status does not match for column $fullName $versions"
				);
				if ( !in_array( $fullName, $ignoredColumns ) ) {
					$this->assertEquals(
						(bool)$column->notnull,
						(bool)$cols[$name]->notnull,
						"NOT NULL status does not match for column $fullName $versions"
					);
					if ( $cols[$name]->dflt_value === 'NULL' ) {
						$cols[$name]->dflt_value = null;
					}
					if ( $column->dflt_value === 'NULL' ) {
						$column->dflt_value = null;
					}
					$this->assertEquals(
						$column->dflt_value,
						$cols[$name]->dflt_value,
						"Default values does not match for column $fullName $versions"
					);
				}
			}

			$indexes = $this->getIndexes( $db, $table );
			$this->assertEquals(
				array_keys( $currentSchema[$table]['indexes'] ),
				array_keys( $indexes ),
				"mismatching indexes for table \"$table\" $versions"
			);
		}
	}

	public static function provideSupportedVersions() {
		return [
			[ '1.36' ],
			[ '1.37' ],
			[ '1.38' ],
			[ '1.39' ],
			[ '1.40' ],
			[ '1.41' ],
			[ '1.42' ],
			[ '1.43' ],
		];
	}

	private function initAndUpgradeTestDB( $version = null ) {
		static $maint = null;

		if ( $maint === null ) {
			$maint = new FakeMaintenance();
			$maint->loadParamsAndArgs( null, [ 'quiet' => 1 ] );
		}

		$p = [ 'variables' => [ 'synchronous' => 'NORMAL', 'temp_store' => 'MEMORY' ] ];
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:', $p );
		if ( $version !== null ) {
			$db->sourceFile( dirname( __FILE__, 6 ) . "/tests/phpunit/data/db/sqlite/tables-$version.sql" );
			$updater = DatabaseUpdater::newForDB( $db, false, $maint );
			$updater->doUpdates( [ 'core' ] );
		} else {
			$db->sourceFile( dirname( __FILE__, 6 ) . "/sql/sqlite/tables-generated.sql" );
		}

		return $db;
	}

	private function getTables( $db ) {
		$list = array_diff(
			$db->listTables(),
			[
				'external_user', // removed from core in 1.22
				'math', // moved out of core in 1.18
				'trackbacks', // removed from core in 1.19
				'searchindex',
				'searchindex_content',
				'searchindex_segments',
				'searchindex_segdir',
			]
		);
		sort( $list );

		return $list;
	}

	private function getColumns( $db, $table ) {
		$cols = [];
		$res = $db->query( "PRAGMA table_info($table)" );
		$this->assertNotNull( $res );
		foreach ( $res as $col ) {
			$cols[$col->name] = $col;
		}
		ksort( $cols );

		return $cols;
	}

	private function getIndexes( $db, $table ) {
		$indexes = [];
		$res = $db->query( "PRAGMA index_list($table)" );
		$this->assertNotNull( $res );
		foreach ( $res as $index ) {
			$res2 = $db->query( "PRAGMA index_info({$index->name})" );
			$this->assertNotNull( $res2 );
			$index->columns = [];
			foreach ( $res2 as $col ) {
				$index->columns[] = $col;
			}
			$indexes[$index->name] = $index;
		}
		ksort( $indexes );

		return $indexes;
	}
}
