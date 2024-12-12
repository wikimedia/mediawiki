<?php

namespace MediaWiki\Tests\Maintenance\Includes;

use GenerateSchemaChangeSql;
use GenerateSchemaSql;
use MediaWiki\Tests\Maintenance\MaintenanceBaseTestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * @covers \MediaWiki\Maintenance\SchemaMaintenance
 * @covers \GenerateSchemaChangeSql
 * @covers \GenerateSchemaSql
 */
class SchemaMaintenanceTest extends MaintenanceBaseTestCase {

	private const DATA_DIR = __DIR__ . '/../../data/schema-maintenance';

	protected function getMaintenanceClass() {
		return GenerateSchemaSql::class;
	}

	/** @dataProvider provideExecuteForFatalError */
	public function testExecuteForFatalError( $options, $expectedOutputRegex ) {
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->expectCallToFatalError();
		$this->expectOutputRegex( $expectedOutputRegex );
		$this->maintenance->execute();
	}

	public static function provideExecuteForFatalError() {
		return [
			'Unsupported SQL platform' => [
				[ 'type' => 'unknown-platform' ], "/'unknown-platform' is not a supported platform/",
			],
		];
	}

	private function getFileWithContent( string $content ): string {
		$testFilename = $this->getNewTempFile();
		$testFile = fopen( $testFilename, 'w' );
		fwrite( $testFile, $content );
		fclose( $testFile );
		return $testFilename;
	}

	/** @dataProvider provideExecuteForFatalErrorWithJsonFileSpecified */
	public function testExecuteForFatalErrorWithJsonFileSpecified( $options, $fileContent, $expectedOutputRegex ) {
		$this->testExecuteForFatalError(
			array_merge( [ 'json' => $this->getFileWithContent( $fileContent ) ], $options ),
			$expectedOutputRegex
		);
	}

	public static function provideExecuteForFatalErrorWithJsonFileSpecified() {
		return [
			'Validate mode when JSON file empty' => [ [ 'validate' => 1 ], '', '/does not exist/' ],
			'Validate mode when JSON file is not valid JSON' => [ [ 'validate' => 1 ], '{{{{', '/Invalid JSON/' ],
			'Validate mode when JSON file is not a valid schema' => [
				[ 'validate' => 1 ], '{"abc": "test"}', '/did not pass validation/',
			],
			'JSON file empty when not validating' => [ [], '', '/does not exist/' ],
		];
	}

	public function testExecuteForSchemaChangeWhenNoSchemaChangesMade() {
		$maintenance = new GenerateSchemaChangeSql();
		$maintenance->setOption( 'json', self::DATA_DIR . '/patch-no_change.json' );
		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/No schema changes detected/' );
		$maintenance->execute();
	}

	public function testExecuteForSuccessfulValidationOfJsonFile() {
		$this->maintenance->setOption( 'validate', 1 );
		$this->maintenance->setOption( 'json', self::DATA_DIR . '/tables.json' );
		$this->maintenance->execute();
		$this->expectOutputString( "Schema is valid.\n" );
	}

	public function testExecuteForSuccessfulValidationOfJsonSchemaChangeFile() {
		$maintenance = new GenerateSchemaChangeSql();
		$maintenance->setOption( 'validate', 1 );
		$maintenance->setOption( 'json', self::DATA_DIR . '/patch-drop-ct_tag.json' );
		$maintenance->execute();
		$this->expectOutputString( "Schema is valid.\n" );
	}

	private function assertDirectoryContainsExpectedFiles(
		string $directoryPath, array $expectedFilePathsToFileContentPath
	) {
		$directory = new RecursiveDirectoryIterator( $directoryPath );
		$directoryIterator = new RecursiveIteratorIterator( $directory );

		foreach ( $directoryIterator as $path ) {
			if ( $path->isDir() || $path === $directoryPath ) {
				continue;
			}

			// Check that the filename is expected to be present.
			$this->assertTrue( str_starts_with( $path, $directoryPath ) );
			$relativePath = substr( $path, strlen( $directoryPath ) );
			// The expected file may be an array key or an array value. First check for the key, and if not
			// present then check for array values as long as they key for the value is an integer.
			if ( array_key_exists( $relativePath, $expectedFilePathsToFileContentPath ) ) {
				$this->assertArrayHasKey( $relativePath, $expectedFilePathsToFileContentPath );
				$expectedFileContentPath = $expectedFilePathsToFileContentPath[$relativePath];
			} else {
				$keyForPath = array_search( $relativePath, $expectedFilePathsToFileContentPath );
				$this->assertIsInt( $keyForPath, "$relativePath was not expected" );
				$expectedFileContentPath = $relativePath;
			}

			// Fetch the expected content from the phpunit/data/schema-maintenance/ folder and check that the
			// file here equals that content.
			$expectedContent = file_get_contents( self::DATA_DIR . $expectedFileContentPath );
			$actualContent = file_get_contents( $path );
			// Normalise the content such that both the expected and actual content use LF instead of CRLF / VR
			$expectedContent = str_replace( [ "\r\n", "\r" ], "\n", $expectedContent );
			$actualContent = str_replace( [ "\r\n", "\r" ], "\n", $actualContent );

			$this->assertSame(
				$expectedContent,
				$actualContent,
				"The SchemaMaintenance script did not produce the expected SQL."
			);
		}
	}

	/** @dataProvider provideExecuteForSuccessfulGenerationOfSchemaSql */
	public function testExecuteForSuccessfulGenerationOfSchemaSql(
		$options, $shouldMysqlFolderExist, $sqlFilename, $expectedSqlFiles
	) {
		// Get a temporary directory to put the generated SQL files
		$sqlPath = $this->getNewTempDirectory();
		if ( $shouldMysqlFolderExist ) {
			mkdir( $sqlPath . '/mysql' );
		}
		$sqlPathArgument = $sqlPath;
		if ( $sqlFilename ) {
			$sqlPathArgument .= '/' . $sqlFilename;
		}
		// Run the maintenance script
		$this->maintenance->setOption( 'json', realpath( self::DATA_DIR ) );
		$this->maintenance->setOption( 'sql', $sqlPathArgument );
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->maintenance->execute();
		$this->assertDirectoryContainsExpectedFiles( $sqlPath, $expectedSqlFiles );
		// Check that the output of the script is as expected
		$expectedOutputString = '';
		foreach ( $expectedSqlFiles as $key => $value ) {
			// The expected filepath is the value, if the key is an integer. Otherwise it is the key.
			$file = is_int( $key ) ? $value : $key;
			$expectedOutputString .= 'Schema change generated and written to ' . $sqlPath . $file . "\n";
		}
		$this->expectOutputString( $expectedOutputString );
	}

	public static function provideExecuteForSuccessfulGenerationOfSchemaSql() {
		return [
			'Only mysql' => [ [ 'type' => 'mysql' ], true, '', [ '/mysql/tables-generated.sql' ] ],
			'Only mysql when mysql folder does not exist' => [
				[ 'type' => 'mysql' ], false, '', [ '/tables-generated.sql' => '/mysql/tables-generated.sql' ],
			],
			'Only postgres' => [ [ 'type' => 'postgres' ], true, '', [ '/postgres/tables-generated.sql' ] ],
			'Only SQLite' => [ [ 'type' => 'sqlite' ], true, '', [ '/sqlite/tables-generated.sql' ] ],
			'All types' => [
				[ 'type' => 'all' ], true, '',
				[ '/mysql/tables-generated.sql', '/sqlite/tables-generated.sql', '/postgres/tables-generated.sql' ],
			],
			'All types when SQL option is a file' => [
				[ 'type' => 'all' ], true, 'tables-generated-actor.sql',
				[
					'/mysql/tables-generated-actor.sql' => '/mysql/tables-generated.sql',
					'/sqlite/tables-generated-actor.sql' => '/sqlite/tables-generated.sql',
					'/postgres/tables-generated-actor.sql' => '/postgres/tables-generated.sql'
				],
			],
		];
	}

	/** @dataProvider provideExecuteForSuccessfulGenerationOfSchemaChangeSql */
	public function testExecuteForSuccessfulGenerationOfSchemaChangeSql( $options, $expectedSqlFiles ) {
		// Get a temporary directory to put the generated SQL files
		$sqlPath = $this->getNewTempDirectory();
		mkdir( $sqlPath . '/mysql' );
		// Run the maintenance script
		$maintenance = new GenerateSchemaChangeSql();
		$maintenance->setOption( 'json', realpath( self::DATA_DIR . '/patch-drop-ct_tag.json' ) );
		$maintenance->setOption( 'sql', $sqlPath );
		foreach ( $options as $name => $value ) {
			$maintenance->setOption( $name, $value );
		}
		$maintenance->execute();
		$this->assertDirectoryContainsExpectedFiles( $sqlPath, $expectedSqlFiles );
		// Check that the output of the script is as expected
		$expectedOutputString = '';
		foreach ( $expectedSqlFiles as $file ) {
			$expectedOutputString .= 'Schema change generated and written to ' . $sqlPath . $file . "\n";
		}
		$this->expectOutputString( $expectedOutputString );
	}

	public static function provideExecuteForSuccessfulGenerationOfSchemaChangeSql() {
		return [
			'Only mysql' => [ [ 'type' => 'mysql' ], [ '/mysql/patch-drop-ct_tag.sql' ] ],
			'Only postgres' => [ [ 'type' => 'postgres' ], [ '/postgres/patch-drop-ct_tag.sql' ] ],
			'Only SQLite' => [ [ 'type' => 'sqlite' ], [ '/sqlite/patch-drop-ct_tag.sql' ] ],
			'All types' => [
				[ 'type' => 'all' ],
				[ '/mysql/patch-drop-ct_tag.sql', '/sqlite/patch-drop-ct_tag.sql', '/postgres/patch-drop-ct_tag.sql' ],
			],
		];
	}

	public function testExecuteWhenSchemaSqlUnchanged() {
		// Get a temporary directory and add the mysql tables-generated.sql file into it from the data directory.
		$sqlPath = $this->getNewTempDirectory();
		$testFile = fopen( $sqlPath . '/tables-generated.sql', 'w' );
		fwrite( $testFile, file_get_contents( self::DATA_DIR . '/mysql/tables-generated.sql' ) );
		fclose( $testFile );
		// Call the maintenance script to generate just the mysql SQL file and check that it outputs no changes were
		// made.
		$this->maintenance->setOption( 'json', realpath( self::DATA_DIR ) );
		$this->maintenance->setOption( 'sql', $sqlPath . '/tables-generated.sql' );
		$this->maintenance->execute();
		$this->expectOutputString(
			"Schema change is unchanged.\n" .
			'Schema change generated and written to ' . $sqlPath . "/tables-generated.sql\n"
		);
		// Check that the test file has not been changed
		$contentBeforeCall = file_get_contents( self::DATA_DIR . '/mysql/tables-generated.sql' );
		$contentAfterCall = file_get_contents( $sqlPath . '/tables-generated.sql' );
		$this->assertSame( $contentBeforeCall, $contentAfterCall );
	}
}
