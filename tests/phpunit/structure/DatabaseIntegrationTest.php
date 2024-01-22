<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\Database;

/**
 * @group Database
 * @coversNothing
 */
class DatabaseIntegrationTest extends MediaWikiIntegrationTestCase {
	/**
	 * @var Database
	 */
	protected $db;

	protected function setUp(): void {
		parent::setUp();
		$this->db = MediaWikiServices::getInstance()
			->getConnectionProvider()
			->getPrimaryDatabase();
	}

	public function testUnknownTableCorruptsResults() {
		$res = $this->db->newSelectQueryBuilder()
			->select( '*' )
			->from( 'page' )
			->where( [ 'page_id' => 1 ] )
			->fetchResultSet();
		$this->assertFalse( $this->db->tableExists( 'foobarbaz' ) );
		$this->assertIsInt( $res->numRows() );
	}

	public function testUniformTablePrefix() {
		global $IP;
		$path = "$IP/maintenance/tables.json";
		$tables = json_decode( file_get_contents( $path ), true );

		// @todo Remove exception once these tables are fixed
		$excludeList = [
			'user_newtalk',
			'objectcache',
		];

		$prefixes = [];
		foreach ( $tables as $table ) {
			$tableName = $table['name'];

			if ( in_array( $tableName, $excludeList ) ) {
				continue;
			}

			foreach ( $table['columns'] as $column ) {
				$prefixes[] = strtok( $column['name'], '_' );
			}
			foreach ( $table['indexes'] ?? [] as $index ) {
				$prefixes[] = strtok( $index['name'], '_' );
			}

			if ( count( array_unique( $prefixes ) ) === 1 ) {
				$prefixes = []; // reset
				continue;
			}

			$list = implode( '_, ', $prefixes ) . '_';

			$this->fail(
				"Columns and indexes of '$tableName' table should"
				. " have uniform prefix. Non-uniform found: [ $list ]"
			);
		}

		$this->assertSame( [], $prefixes );
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
			[ '--json=' . $abstractSchemaPath, '--sql=' . $newPath, '--type=' . $type, '--quiet' ]
		);
		$maintenanceScript->execute();
		$this->assertEquals(
			$oldContent,
			file_get_contents( $newPath ),
			"The generated schema in '$type' type has to be the same"
		);
	}

	/**
	 * T352229
	 */
	public function testBooleanValues() {
		$res = $this->db->newSelectQueryBuilder()
			->select( [ 'false' => '1=0', 'true' => '1=1' ] )
			->fetchResultSet();
		$obj = $res->fetchObject();
		$this->assertCount( 2, (array)$obj );
		$this->assertSame( '0', $obj->false );
		$this->assertSame( '1', $obj->true );

		$res->seek( 0 );
		$row = $res->fetchRow();
		$this->assertCount( 4, $row );
		$this->assertSame( '0', $row[0] );
		$this->assertSame( '1', $row[1] );
		$this->assertSame( '0', $row['false'] );
		$this->assertSame( '1', $row['true'] );
	}

	public function testListTables() {
		$prefix = $this->db->tablePrefix() . 'listtables_';
		$table = $prefix . 'table';
		$view = $prefix . 'view';
		$allTables = $this->db->listTables();
		$this->assertIsArray( $allTables );

		$this->assertSame( [], $this->db->listTables( $prefix ) );

		try {
			$this->db->query( "CREATE TABLE $table (i INT)" );
			$this->assertSame( [ $table ], $this->db->listTables( $prefix ) );
			// Confirm that listTables() does not include views (T45571)
			$this->db->query( "CREATE VIEW $view AS SELECT * FROM $table" );
			$this->assertSame( [ $table ], $this->db->listTables( $prefix ) );
		} finally {
			$this->db->query( "DROP VIEW IF EXISTS $view" );
			$this->db->query( "DROP TABLE IF EXISTS $table" );
		}
	}
}
