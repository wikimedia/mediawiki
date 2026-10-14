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

	/**
	 * @dataProvider provideJsonSchemasPaths
	 */
	public function testNormalizePath(
		string $expected,
		string $jsonSchema,
		string $installPath,
		string $extensionDirectory
	) {
		$this->assertEquals(
			$expected,
			SchemaGenerator::normalizePath( $jsonSchema, $installPath, $extensionDirectory )
		);
	}

	public static function provideJsonSchemasPaths() {
		// ($expected, $jsonSchema, $installPath, $extensionDirectory)
		return [
			'Core patch under core root' => [
				'tests/phpunit/data/schema-maintenance/patch-no_change.json',
				self::DATA_DIR . '/patch-no_change.json',
				MW_INSTALL_PATH,
				MW_INSTALL_PATH . '/extensions'
			],
			'Extension patch with default wgExtensionDirectory under core root' => [
				'db_patches/patch.json',
				self::DATA_DIR . '/extensions/FooExt/db_patches/patch.json',
				self::DATA_DIR, // used as MW_INSTALL_PATH
				self::DATA_DIR . '/extensions'
			],
			'Extension patch with custom wgExtensionDirectory outside core root' => [
				'db_patches/patch.json',
				self::DATA_DIR . '/extensions/FooExt/db_patches/patch.json',
				MW_INSTALL_PATH . '/includes', // not a parent of self::DATA_DIR
				self::DATA_DIR . '/extensions'
			],
		];
	}

	public static function provideNormalizePathOutsideExtensionDirectory() {
		// ($expected, $jsonSchema relative to the temporary directory)
		return [
			'Extension symlinked into wgExtensionDirectory, symlink path' => [
				'db_patches/patch.json',
				'core/extensions/FooExt/db_patches/patch.json',
			],
			'Extension symlinked into wgExtensionDirectory, resolved path' => [
				'db_patches/patch.json',
				'clones/FooExt/db_patches/patch.json',
			],
			'Skin under core root' => [
				'sql/patch.json',
				'core/skins/FooSkin/sql/patch.json',
			],
			'Core patch under core root' => [
				'sql/patch.json',
				'core/sql/patch.json',
			],
		];
	}

	/**
	 * @dataProvider provideNormalizePathOutsideExtensionDirectory
	 */
	public function testNormalizePathOutsideExtensionDirectory( string $expected, string $jsonSchema ) {
		$dir = $this->getNewTempDirectory();
		mkdir( "$dir/core/extensions", 0777, true );
		foreach ( [ 'clones/FooExt/db_patches', 'core/skins/FooSkin/sql', 'core/sql' ] as $subdir ) {
			mkdir( "$dir/$subdir", 0777, true );
			touch( "$dir/$subdir/patch.json" );
		}
		touch( "$dir/clones/FooExt/extension.json" );
		touch( "$dir/core/skins/FooSkin/skin.json" );
		if ( !@symlink( "$dir/clones/FooExt", "$dir/core/extensions/FooExt" ) ) {
			$this->markTestSkipped( 'Cannot create a symlink' );
		}

		$this->assertSame(
			$expected,
			SchemaGenerator::normalizePath( "$dir/$jsonSchema", "$dir/core", "$dir/core/extensions" )
		);
	}

}
