<?php

declare( strict_types = 1 );

namespace MediaWiki\Tests\Unit\composer\PhpUnitSplitter;

use MediaWiki\Composer\PhpUnitSplitter\PhpUnitXmlManager;
use MediaWiki\Composer\PhpUnitSplitter\TestListMissingException;
use PHPUnit\Framework\TestCase;

/**
 * @license GPL-2.0-or-later
 * @covers \MediaWiki\Composer\PhpUnitSplitter\PhpUnitXmlManager
 */
class PhpUnitXmlManagerTest extends TestCase {

	private string $testDir;
	private PhpUnitXmlManager $manager;

	public function setUp(): void {
		parent::setUp();
		$this->testDir = implode( DIRECTORY_SEPARATOR, [ sys_get_temp_dir(), uniqid( 'PhpUnitTest' ) ] );
		mkdir( $this->testDir );
		$this->manager = new PhpUnitXmlManager( $this->testDir, 'tests-list.xml' );
		$this->setupTestFolder();
	}

	private static function getSourcePhpUnitDistXml(): string {
		return __DIR__ . DIRECTORY_SEPARATOR . implode(
		DIRECTORY_SEPARATOR, [ '..', '..', '..', '..', '..', '..', 'phpunit.xml.dist' ]
		);
	}

	private function setupTestFolder() {
		copy(
			self::getSourcePhpUnitDistXml(),
			$this->testDir . DIRECTORY_SEPARATOR . 'phpunit.xml.dist'
		);
		mkdir( $this->testDir . DIRECTORY_SEPARATOR . "tests" );
		foreach ( glob( __DIR__ . DIRECTORY_SEPARATOR . "*Test.php" ) as $file ) {
			copy(
				$file,
				implode( DIRECTORY_SEPARATOR, [ $this->testDir, "tests", basename( $file ) ] )
			);
		}
	}

	private function tearDownTestFolder() {
		foreach ( [ 'phpunit.xml', 'phpunit.xml.dist', 'tests-list.xml' ] as $file ) {
			$path = $this->testDir . DIRECTORY_SEPARATOR . $file;
			if ( file_exists( $path ) ) {
				unlink( $path );
			}
		}
		$testsFolder = $this->testDir . DIRECTORY_SEPARATOR . "tests";
		foreach ( glob( $testsFolder . DIRECTORY_SEPARATOR . "*.php" ) as $file ) {
			unlink( $file );
		}
		rmdir( $testsFolder );
		rmdir( $this->testDir );
	}

	private function copyTestListIntoPlace() {
		copy(
			__DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'tests-list.xml',
			$this->testDir . DIRECTORY_SEPARATOR . 'tests-list.xml'
		);
	}

	public function tearDown(): void {
		parent::tearDown();
		$this->tearDownTestFolder();
	}

	public function testIsPrepared() {
		$this->copyTestListIntoPlace();
		$this->assertFalse( $this->manager->isPhpUnitXmlPrepared(), "Expected no PHPUnit Xml to be present" );
		$this->manager->createPhpUnitXml( 4 );
		$this->assertTrue( $this->manager->isPhpUnitXmlPrepared(), "Expected PHPUnit Xml to have been prepared" );
	}

	public function testFailsIfNoListIsPresent() {
		$this->assertFalse( $this->manager->isPhpUnitXmlPrepared(), "Expected no PHPUnit Xml to be present" );
		$this->expectException( TestListMissingException::class );
		$this->manager->createPhpUnitXml( 4 );
	}

	public function testPhpUnitXmlDistNotPrepared() {
		$this->assertFalse( $this->manager->isPhpUnitXmlPrepared(), "Expected no PHPUnit Xml to be present" );
		copy( self::getSourcePhpUnitDistXml(), implode( DIRECTORY_SEPARATOR, [ $this->testDir, "phpunit.xml" ] ) );
		$this->copyTestListIntoPlace();
		$this->manager->createPhpUnitXml( 4 );
		copy( self::getSourcePhpUnitDistXml(), implode( DIRECTORY_SEPARATOR, [ $this->testDir, "phpunit.xml" ] ) );
		$this->assertFalse( $this->manager->isPhpUnitXmlPrepared(), "Expected phpunit.dist.xml to be treated as unprepared" );
	}
}
