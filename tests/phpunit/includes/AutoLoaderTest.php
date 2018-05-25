<?php

/**
 * @covers AutoLoader
 */
class AutoLoaderTest extends MediaWikiTestCase {
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
}
