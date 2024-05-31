<?php

declare( strict_types = 1 );

namespace MediaWiki\Tests\Unit\composer\PhpUnitSplitter;

use MediaWiki\Composer\PhpUnitSplitter\PhpUnitTestFileScanner;
use PHPUnit\Framework\TestCase;

/**
 * @license GPL-2.0-or-later
 * @covers \MediaWiki\Composer\PhpUnitSplitter\PhpUnitTestFileScanner
 */
class PhpUnitTestFileScannerTest extends TestCase {

	public function testScanForTestFiles() {
		$scanner = new PhpUnitTestFileScanner( __DIR__ );
		$files = $scanner->scanForFiles();
		$expected = [];
		foreach ( glob( __DIR__ . DIRECTORY_SEPARATOR . "*Test.php" ) as $testFile ) {
			$expected[ basename( $testFile ) ] = [ $testFile ];
		}
		$this->assertEquals( $expected, $files, "Expected PhpUnitSplitter test files to be found" );
	}

}
