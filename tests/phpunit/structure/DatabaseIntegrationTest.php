<?php

use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IDatabase;

/**
 * @group Database
 */
class DatabaseIntegrationTest extends MediaWikiTestCase {
	/**
	 * @var Database
	 */
	protected $db;

	private $functionTest = false;

	protected function setUp() : void {
		parent::setUp();
		$this->db = wfGetDB( DB_MASTER );
	}

	protected function tearDown() : void {
		parent::tearDown();
		if ( $this->functionTest ) {
			$this->dropFunctions();
			$this->functionTest = false;
		}
		$this->db->restoreFlags( IDatabase::RESTORE_INITIAL );
	}

	public function testStoredFunctions() {
		if ( !in_array( wfGetDB( DB_MASTER )->getType(), [ 'mysql', 'postgres' ] ) ) {
			$this->markTestSkipped( 'MySQL or Postgres required' );
		}
		global $IP;
		$this->dropFunctions();
		$this->functionTest = true;
		$this->assertTrue(
			$this->db->sourceFile( "$IP/tests/phpunit/data/db/{$this->db->getType()}/functions.sql" )
		);
		$res = $this->db->query( 'SELECT mw_test_function() AS test', __METHOD__ );
		$this->assertEquals( 42, $res->fetchObject()->test );
	}

	private function dropFunctions() {
		$this->db->query( 'DROP FUNCTION IF EXISTS mw_test_function'
			. ( $this->db->getType() == 'postgres' ? '()' : '' )
		);
	}

	public function testUnknownTableCorruptsResults() {
		$res = $this->db->select( 'page', '*', [ 'page_id' => 1 ] );
		$this->assertFalse( $this->db->tableExists( 'foobarbaz' ) );
		$this->assertIsInt( $res->numRows() );
	}

	public function automaticSqlGenerationParams() {
		return [
			[ 'mysql', '/maintenance/tables-generated.sql' ],
			[ 'sqlite', '/maintenance/sqlite/tables-generated.sql' ],
			[ 'postgres', '/maintenance/postgres/tables-generated.sql' ],
		];
	}

	/**
	 * @dataProvider automaticSqlGenerationParams
	 */
	public function testAutomaticSqlGeneration( $type, $sqlPath ) {
		global $IP;
		$abstractSchemaPath = "$IP/maintenance/tables.json";
		$mysqlPath = $IP . $sqlPath;
		$oldContent = file_get_contents( $mysqlPath );
		$maintenanceScript = new GenerateSchemaSql();
		$maintenanceScript->loadWithArgv(
			[ '--json=' . $abstractSchemaPath, '--sql=' . $mysqlPath, '--type=' . $type ]
		);
		$maintenanceScript->execute();
		$this->assertEquals(
			$oldContent,
			file_get_contents( $mysqlPath ),
			"The generated schema in '$type' type has to be the same"
		);
	}
}
