<?php

use Wikimedia\Rdbms\Database;

/**
 * @group Database
 */
class DatabaseIntegrationTest extends MediaWikiIntegrationTestCase {
	/**
	 * @var Database
	 */
	protected $db;

	protected function setUp() : void {
		parent::setUp();
		$this->db = wfGetDB( DB_MASTER );
	}

	public function testUnknownTableCorruptsResults() {
		$res = $this->db->select( 'page', '*', [ 'page_id' => 1 ] );
		$this->assertFalse( $this->db->tableExists( 'foobarbaz' ) );
		$this->assertIsInt( $res->numRows() );
	}

	public function automaticSqlGenerationParams() {
		return [
			[ 'mysql' ],
			[ 'sqlite' ],
			[ 'postgres' ],
		];
	}

	/**
	 * @dataProvider automaticSqlGenerationParams
	 */
	public function testAutomaticSqlGeneration( $type ) {
		global $IP;
		$abstractSchemaPath = "$IP/maintenance/tables.json";
		if ( $type === 'mysql' ) {
			$oldPath = "$IP/maintenance/tables-generated.sql";
		} else {
			$oldPath = "$IP/maintenance/$type/tables-generated.sql";
		}
		$oldContent = file_get_contents( $oldPath );
		$newPath = $this->getNewTempFile();
		$maintenanceScript = new GenerateSchemaSql();
		$maintenanceScript->loadWithArgv(
			[ '--json=' . $abstractSchemaPath, '--sql=' . $newPath, '--type=' . $type ]
		);
		$maintenanceScript->execute();
		$this->assertEquals(
			$oldContent,
			file_get_contents( $newPath ),
			"The generated schema in '$type' type has to be the same"
		);
	}
}
