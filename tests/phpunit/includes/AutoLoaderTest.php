<?php

/**
 * @covers AutoLoader
 */
class AutoLoaderTest extends MediaWikiTestCase {

	private $oldPsr4;

	protected function setUp() {
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

		$this->oldPsr4 = AutoLoader::$psr4Namespaces;
		AutoLoader::$psr4Namespaces['Test\\MediaWiki\\AutoLoader\\'] =
			__DIR__ . '/../data/autoloader/psr4';
	}

	protected function tearDown() {
		AutoLoader::$psr4Namespaces = $this->oldPsr4;
		parent::tearDown();
	}

	public function testCoreClass() {
		$this->assertTrue( class_exists( 'TestAutoloadedLocalClass' ) );
	}

	public function testExtensionClass() {
		$this->assertTrue( class_exists( 'TestAutoloadedClass' ) );
	}

	public function testWrongCaseClass() {
		$this->setMwGlobals( 'wgAutoloadAttemptLowercase', true );

		$this->assertTrue( class_exists( 'testautoLoadedcamlCLASS' ) );
	}

	public function testWrongCaseSerializedClass() {
		$this->setMwGlobals( 'wgAutoloadAttemptLowercase', true );

		$dummyCereal = 'O:29:"testautoloadedserializedclass":0:{}';
		$uncerealized = unserialize( $dummyCereal );
		$this->assertFalse( $uncerealized instanceof __PHP_Incomplete_Class,
			"unserialize() can load classes case-insensitively." );
	}

	public function testPsr4() {
		$this->assertTrue( class_exists( 'Test\\MediaWiki\\AutoLoader\\TestFooBar' ) );
	}
}
