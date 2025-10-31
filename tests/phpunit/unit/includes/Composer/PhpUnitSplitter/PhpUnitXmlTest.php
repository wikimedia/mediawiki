<?php

declare( strict_types = 1 );

namespace MediaWiki\Tests\Unit\Composer\PhpUnitSplitter;

use MediaWiki\Composer\PhpUnitSplitter\PhpUnitXml;
use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;

/**
 * @license GPL-2.0-or-later
 * @covers \MediaWiki\Composer\PhpUnitSplitter\PhpUnitXml
 */
class PhpUnitXmlTest extends TestCase {
	use MediaWikiCoversValidator;

	private const BASIC_XML = '<?xml version="1.0" encoding="UTF-8"?>
<phpunit bootstrap="tests/phpunit/bootstrap.php">
<testsuites>
	<testsuite name="core:unit">
		<directory>tests/phpunit/unit</directory>
	</testsuite>
</testsuites>
</phpunit>';

	public function createFixtureFile( string $data ): string {
		$filename = tempnam( sys_get_temp_dir(), "phpunit-test" );
		file_put_contents( $filename, $data );
		return $filename;
	}

	public function testFixtureContainsNoSplitGroups() {
		$phpUnitXmlFile = $this->createFixtureFile( self::BASIC_XML );
		$phpUnitXml = new PhpUnitXml( $phpUnitXmlFile );
		$this->assertFalse( $phpUnitXml->containsSplitGroups(), "No split groups expected in fixture" );
		unlink( $phpUnitXmlFile );
	}

	public function testAddSplitGroups() {
		$phpUnitXmlFile = $this->createFixtureFile( self::BASIC_XML );
		$phpUnitXml = new PhpUnitXml( $phpUnitXmlFile );
		$phpUnitXml->addSplitGroups( [
			[ "file1.php", "file2.php" ],
			[ "file3.php", "file4.php" ],
			[ "file7.php", "file6.php" ],
			[ "file9.php", "file8.php" ],
			[ "file11.php", "file10.php" ],
			[ "file13.php", "file12.php" ],
		] );
		$this->assertTrue( $phpUnitXml->containsSplitGroups(), "Expected groups to be added" );
		unlink( $phpUnitXmlFile );
	}
}
