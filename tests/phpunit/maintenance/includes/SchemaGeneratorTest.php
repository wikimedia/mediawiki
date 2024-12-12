<?php

namespace MediaWiki\Tests\Maintenance\Includes;

use MediaWiki\DB\AbstractSchemaValidationError;
use MediaWiki\Maintenance\SchemaGenerator;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Maintenance\SchemaGenerator
 */
class SchemaGeneratorTest extends MediaWikiIntegrationTestCase {

	private const DATA_DIR = __DIR__ . '/../../data/schema-maintenance';

	private function getFileWithContent( string $content ): string {
		$testFilename = $this->getNewTempFile();
		$testFile = fopen( $testFilename, 'w' );
		fwrite( $testFile, $content );
		fclose( $testFile );
		return $testFilename;
	}

	/**
	 * @dataProvider provideValidateAndGetSchema
	 */
	public function testValidateAndGetSchema( string $fileContent, string $expectedExceptionSubstring ) {
		$this->expectException( AbstractSchemaValidationError::class );
		$this->expectExceptionMessage( $expectedExceptionSubstring );
		( new SchemaGenerator() )->validateAndGetSchema( $this->getFileWithContent( $fileContent ) );
	}

	public static function provideValidateAndGetSchema() {
		return [
			'Empty JSON file' => [ '', 'does not exist' ],
			'Not valid JSON' => [ '{{{{', 'Invalid JSON' ],
			'Not a valid schema' => [ '{"abc": "test"}', 'did not pass validation' ],
		];
	}

	public function testValidateAndGetSchema__valid() {
		$generator = new SchemaGenerator();
		$schema = $generator->validateAndGetSchema( self::DATA_DIR . '/tables.json' );
		$this->assertIsArray( $schema );
	}

	public function testGenerateSchemaChange__NoSchemaChangesMade() {
		$generator = new SchemaGenerator();
		$this->expectException( AbstractSchemaValidationError::class );
		$this->expectExceptionMessage( 'No schema changes detected' );
		$generator->generateSchemaChange( 'mysql', self::DATA_DIR . '/patch-no_change.json' );
	}
}
