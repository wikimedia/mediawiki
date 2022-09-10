<?php

use MediaWiki\MainConfigNames;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers AutoLoader
 */
class AutoLoaderTest extends MediaWikiIntegrationTestCase {

	private $oldPsr4;
	private $oldClassFiles;

	protected function setUp(): void {
		parent::setUp();

		// Fancy dance to trigger a rebuild of AutoLoader::$autoloadLocalClassesLower
		$this->mergeMwGlobalArrayValue( 'wgAutoloadLocalClasses', [
			'TestAutoloadedLocalClass' =>
				__DIR__ . '/../data/autoloader/TestAutoloadedLocalClass.php',
			'TestAutoloadedCamlClass' =>
				__DIR__ . '/../data/autoloader/TestAutoloadedCamlClass.php',
			'TestAutoloadedSerializedClass' =>
				__DIR__ . '/../data/autoloader/TestAutoloadedSerializedClass.php',
		] );
		AutoLoader::resetAutoloadLocalClassesLower();
		$this->mergeMwGlobalArrayValue( 'wgAutoloadClasses', [
			'TestAutoloadedClass' => __DIR__ . '/../data/autoloader/TestAutoloadedClass.php',
		] );

		$access = TestingAccessWrapper::newFromClass( AutoLoader::class );
		$this->oldPsr4 = $access->psr4Namespaces;
		$this->oldClassFiles = $access->classFiles;
		AutoLoader::registerNamespaces( [
			'Test\\MediaWiki\\AutoLoader\\' => __DIR__ . '/../data/autoloader/psr4'
		] );
		AutoLoader::registerClasses( [
			'TestAnotherAutoloadedClass' => __DIR__ . '/../data/autoloader/TestAnotherAutoloadedClass.php',
		] );
	}

	protected function tearDown(): void {
		$access = TestingAccessWrapper::newFromClass( AutoLoader::class );
		$access->psr4Namespaces = $this->oldPsr4;
		$access->classFiles = $this->oldClassFiles;
		parent::tearDown();
	}

	public function testFind() {
		$path = __DIR__ . '/../data/autoloader/TestAutoloadedLocalClass.php';
		$this->assertSame( $path, AutoLoader::find( TestAutoloadedLocalClass::class ) );
	}

	public function testCoreClass() {
		$this->assertTrue( class_exists( 'TestAutoloadedLocalClass' ) );
	}

	public function testExtensionClass() {
		$this->assertTrue( class_exists( 'TestAnotherAutoloadedClass' ) );
	}

	public function testLegacyExtensionClass() {
		$this->assertTrue( class_exists( 'TestAutoloadedClass' ) );
	}

	public function testWrongCaseClass() {
		$this->overrideConfigValue( MainConfigNames::AutoloadAttemptLowercase, true );

		$this->assertTrue( class_exists( 'testautoLoadedcamlCLASS' ) );
	}

	public function testWrongCaseSerializedClass() {
		$this->overrideConfigValue( MainConfigNames::AutoloadAttemptLowercase, true );

		$dummySer = 'O:29:"testautoloadedserializedclass":0:{}';
		$dummy = unserialize( $dummySer );
		$this->assertSame(
			get_class( $dummy ),
			TestAutoloadedSerializedClass::class,
			'load class case-insensitively'
		);
	}

	public function testPsr4() {
		$this->assertTrue( class_exists( 'Test\\MediaWiki\\AutoLoader\\TestFooBar' ) );
	}
}
