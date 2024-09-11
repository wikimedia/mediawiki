<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\Json\FormatJson;
use MediaWiki\Registration\ExtensionRegistry;
use UpdateExtensionJsonSchema;

/**
 * @covers ValidateRegistrationFile
 * @author Dreamy Jazz
 */
class UpdateExtensionJsonSchemaTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return UpdateExtensionJsonSchema::class;
	}

	public function testExecuteForNonExistingFilename() {
		$filename = __DIR__ . '/NonExistentPathForTest/abcdef.json';
		$this->maintenance->setArg( 'path', $filename );
		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/Error: Unable to read ' . preg_quote( $filename, '/' ) . '/' );
		$this->maintenance->execute();
	}

	private function getFileWithContent( string $content ): string {
		$testFilename = $this->getNewTempFile();
		$testFile = fopen( $testFilename, 'w' );
		fwrite( $testFile, $content );
		fclose( $testFile );
		return $testFilename;
	}

	public function testExecuteForInvalidJson() {
		$this->maintenance->setArg( 'path', $this->getFileWithContent( '{' ) );
		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/Error: Invalid JSON/' );
		$this->maintenance->execute();
	}

	public function testExecuteForLatestManifestVersion() {
		$filename = $this->getFileWithContent( FormatJson::encode( [
			'manifest_version' => ExtensionRegistry::MANIFEST_VERSION,
			'name' => 'FakeExtension',
		] ) );
		$this->maintenance->setArg( 'path', $filename );
		$this->expectOutputString( 'Already at the latest version: ' . ExtensionRegistry::MANIFEST_VERSION . "\n" );
		$this->maintenance->execute();
	}
}
