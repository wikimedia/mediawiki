<?php

class AutoLoaderTest extends MediaWikiTestCase {
	protected function setUp() {
		global $wgAutoloadLocalClasses, $wgAutoloadClasses;

		parent::setUp();

		// Fancy dance to trigger a rebuild of AutoLoader::$autoloadLocalClassesLower
		$this->testLocalClasses = array(
			'TestAutoloadedLocalClass' => __DIR__ . '/../data/autoloader/TestAutoloadedLocalClass.php',
			'TestAutoloadedCamlClass' => __DIR__ . '/../data/autoloader/TestAutoloadedCamlClass.php',
		);
		$this->setMwGlobals( 'wgAutoloadLocalClasses', $this->testLocalClasses + $wgAutoloadLocalClasses );
		InstrumentedAutoLoader::resetAutoloadLocalClassesLower();

		$this->testExtensionClasses = array(
			'TestAutoloadedClass' => __DIR__ . '/../data/autoloader/TestAutoloadedClass.php',
		);
		$this->setMwGlobals( 'wgAutoloadClasses', $this->testExtensionClasses + $wgAutoloadClasses );
	}

	function testCoreClass() {
		$this->assertTrue( class_exists( 'TestAutoloadedLocalClass' ) );
	}

	function testExtensionClass() {
		$this->assertTrue( class_exists( 'TestAutoloadedClass' ) );
	}

	function testWrongCaseClass() {
		$this->assertTrue( class_exists( 'testautoLoadedcamlCLASS' ) );
	}
}

class InstrumentedAutoLoader extends AutoLoader {
	static function resetAutoloadLocalClassesLower() {
		self::$autoloadLocalClassesLower = null;
	}
}
