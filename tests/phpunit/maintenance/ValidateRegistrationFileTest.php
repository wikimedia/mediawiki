<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\Json\FormatJson;
use MediaWiki\Registration\ExtensionRegistry;
use ValidateRegistrationFile;

/**
 * @covers ValidateRegistrationFile
 * @author Dreamy Jazz
 */
class ValidateRegistrationFileTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return ValidateRegistrationFile::class;
	}

	private function getFileWithContent( string $content ): string {
		$testFilename = $this->getNewTempFile();
		$testFile = fopen( $testFilename, 'w' );
		fwrite( $testFile, $content );
		fclose( $testFile );
		return $testFilename;
	}

	public function testExecuteForInvalidRegistrationFile() {
		// Get a fake extension.json with an invalid manifest version.
		$this->maintenance->setArg( 'path', $this->getFileWithContent(
			FormatJson::encode( [ 'manifest_version' => ExtensionRegistry::MANIFEST_VERSION + 1 ] )
		) );
		// Expect a fatal error with an error about the invalid manifest version
		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/non-supported schema version/' );
		$this->maintenance->execute();
	}

	public function testExecuteForValidRegistrationFile() {
		// Get a fake extension.json which is valid
		$filename = $this->getFileWithContent( FormatJson::encode( [
			'manifest_version' => ExtensionRegistry::MANIFEST_VERSION,
			'name' => 'FakeExtension',
		] ) );
		$this->maintenance->setArg( 'path', $filename );
		// Expect a fatal error with an error about the invalid manifest version
		$this->expectOutputRegex(
			'/' . preg_quote( $filename, '/' ) . ' validates against the schema/'
		);
		$this->maintenance->execute();
	}
}
