<?php

declare( strict_types = 1 );

namespace MediaWiki\Tests\Unit\Composer\PhpUnitSplitter;

use MediaWiki\Composer\ComposerLaunchParallel;
use MediaWiki\Composer\ComposerSystemInterface;
use MediaWiki\Composer\PhpUnitSplitter\PhpUnitErrorTestCaseFoundException;
use MediaWiki\Composer\PhpUnitSplitter\PhpUnitXmlManager;
use MediaWiki\Composer\PhpUnitSplitter\SplitGroupExecutor;
use MediaWiki\Composer\PhpUnitSplitter\TestListMissingException;
use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @license GPL-2.0-or-later
 * @covers \MediaWiki\Composer\PhpUnitSplitter\PhpUnitXmlManager
 */
class PhpUnitXmlManagerTest extends TestCase {
	use MediaWikiCoversValidator;

	private string $testDir;
	private PhpUnitXmlManager $manager;

	public function setUp(): void {
		parent::setUp();
		$this->testDir = implode( DIRECTORY_SEPARATOR, [ sys_get_temp_dir(), uniqid( 'PhpUnitTest' ) ] );
		mkdir( $this->testDir );
		$this->manager = new PhpUnitXmlManager(
			$this->testDir,
			'tests-list.xml',
			'phpunit-test.xml',
			'test'
		);
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
		foreach ( [ 'phpunit.xml', 'phpunit-test.xml', 'phpunit.xml.dist', 'tests-list.xml' ] as $file ) {
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

	private function copyTestListIntoPlace( string $srcFilename = 'tests-list.xml' ) {
		copy(
			__DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . $srcFilename,
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
		copy( self::getSourcePhpUnitDistXml(), implode( DIRECTORY_SEPARATOR, [ $this->testDir, "phpunit-test.xml" ] ) );
		$this->copyTestListIntoPlace();
		$this->manager->createPhpUnitXml( 4 );
		copy( self::getSourcePhpUnitDistXml(), implode( DIRECTORY_SEPARATOR, [ $this->testDir, "phpunit-test.xml" ] ) );
		$this->assertFalse( $this->manager->isPhpUnitXmlPrepared(), "Expected phpunit.dist.xml to be treated as unprepared" );
	}

	public function testFailWithInformativeErrorIfErrorTestCaseFound() {
		$this->assertFalse( $this->manager->isPhpUnitXmlPrepared(), "Expected no PHPUnit Xml to be present" );
		copy( self::getSourcePhpUnitDistXml(), implode( DIRECTORY_SEPARATOR, [ $this->testDir, "phpunit-test.xml" ] ) );
		$this->copyTestListIntoPlace( 'tests-list-with-error.xml' );
		$this->expectException( PhpUnitErrorTestCaseFoundException::class );
		$this->manager->createPhpUnitXml( 4 );
	}

	public function testMatchJobPartRegexp() {
		$urlBase = "https://results-server.example.com/results";
		$logPath = "47/1113147/8/test/mediawiki-quibble-vendor-mysql-php74/96878ad";
		$url = TestingAccessWrapper::newFromClass( PhpUnitXmlManager::class )->generateResultsCacheUrl( $urlBase, $logPath );
		$this->assertEquals( $urlBase . '/mediawiki-quibble-vendor-mysql-php74', $url );
	}

	public function testMatchJobPartRegexpQuibbleAtStart() {
		$urlBase = "https://results-server.example.com/results";
		$logPath = "47/1113147/8/test/quibble-vendor-mysql-php74-noselenium/96878ad";
		$url = TestingAccessWrapper::newFromClass( PhpUnitXmlManager::class )->generateResultsCacheUrl( $urlBase, $logPath );
		$this->assertEquals( $urlBase . '/quibble-vendor-mysql-php74-noselenium', $url );
	}

	public function testSplitTestsList() {
		$systemMock = $this->createMock( ComposerSystemInterface::class );
		$systemMock->method( 'getcwd' )->willReturn( $this->testDir );
		$systemMock->expects( $this->once() )
			->method( 'exit' )
			->with( ComposerLaunchParallel::EXIT_STATUS_PHPUNIT_LIST_TESTS_ERROR );
		$executorMock = $this->createMock( SplitGroupExecutor::class );
		$executorMock->expects( $this->once() )
			->method( 'runLinearFallback' )
			->with( 'database' );
		$this->copyTestListIntoPlace( 'tests-list-with-error.xml' );
		PhpUnitXmlManager::splitTestsList(
			'custom',
			'tests-list.xml',
			'database',
			'phpunit-custom.xml',
			$systemMock,
			$executorMock,
			null
		);
		$this->assertFileExists( $this->testDir . DIRECTORY_SEPARATOR . 'phpunit.xml' );
	}
}
