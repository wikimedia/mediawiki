<?php

namespace MediaWiki\Tests\Maintenance;

use JSParseHelper;

/**
 * @covers \JSParseHelper
 * @author Dreamy Jazz
 */
class JSParseHelperTest extends MaintenanceBaseTestCase {
	public function getMaintenanceClass() {
		return JSParseHelper::class;
	}

	public function testExecuteForMissingFilename() {
		$this->expectCallToFatalError();
		$nonExistingFile = $this->getNewTempDirectory() . 'non-existing-test-file.js';
		$this->expectOutputRegex( '/' . preg_quote( $nonExistingFile, '/' ) . " ERROR: could not read file\n/" );
		$this->maintenance->setArg( 'files(s)', $nonExistingFile );
		$this->maintenance->execute();
	}

	private function getTestJSFile( string $content ): string {
		$testFilename = $this->getNewTempFile();
		$testFile = fopen( $testFilename, 'w' );
		fwrite( $testFile, $content );
		fclose( $testFile );
		return $testFilename;
	}

	public function testExecuteForInvalidJS() {
		// Get a file which has invalid JS
		$testFilename = $this->getTestJSFile( "const a =;" );
		// Run the maintenance script on this file, and expect it to be parsed as invalid.
		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/' . preg_quote( $testFilename, '/' ) . ' ERROR/' );
		$this->maintenance->setArg( 'file(s)', $testFilename );
		$this->maintenance->execute();
	}

	public function testExecuteForValidJS() {
		// Get a file which has valid JS
		$testFilename = $this->getTestJSFile( "const a = 1;" );
		// Run the maintenance script on this file, and expect it to parse as okay.
		$this->expectOutputRegex( '/' . preg_quote( $testFilename, '/' ) . ' OK/' );
		$this->maintenance->setArg( 'file(s)', $testFilename );
		$this->maintenance->execute();
	}
}
