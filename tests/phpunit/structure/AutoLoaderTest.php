<?php
class AutoLoaderTest extends MediaWikiTestCase {

	protected function setUp() {
		global $wgAutoloadLocalClasses, $wgAutoloadClasses;

		parent::setUp();

		// Fancy dance to trigger a rebuild of AutoLoader::$autoloadLocalClassesLower
		$this->testLocalClasses = array(
			'TestAutoloadedLocalClass' => __DIR__ . '/../data/autoloader/TestAutoloadedLocalClass.php',
			'TestAutoloadedCamlClass' => __DIR__ . '/../data/autoloader/TestAutoloadedCamlClass.php',
			'TestAutoloadedSerializedClass' => __DIR__ . '/../data/autoloader/TestAutoloadedSerializedClass.php',
		);
		$this->setMwGlobals( 'wgAutoloadLocalClasses', $this->testLocalClasses + $wgAutoloadLocalClasses );
		InstrumentedAutoLoader::resetAutoloadLocalClassesLower();

		$this->testExtensionClasses = array(
			'TestAutoloadedClass' => __DIR__ . '/../data/autoloader/TestAutoloadedClass.php',
		);
		$this->setMwGlobals( 'wgAutoloadClasses', $this->testExtensionClasses + $wgAutoloadClasses );
	}

	/**
	 * Assert that there were no classes loaded that are not registered with the AutoLoader.
	 *
	 * For example foo.php having class Foo and class Bar but only registering Foo.
	 * This is important because we should not be relying on Foo being used before Bar.
	 */
	public function testAutoLoadConfig() {
		$results = self::checkAutoLoadConf();

		$this->assertEquals(
			$results['expected'],
			$results['actual']
		);
	}

	protected static function checkAutoLoadConf() {
		global $wgAutoloadLocalClasses, $wgAutoloadClasses, $IP;
		$supportsParsekit = function_exists( 'parsekit_compile_file' );

		// wgAutoloadLocalClasses has precedence, just like in includes/AutoLoader.php
		$expected = $wgAutoloadLocalClasses + $wgAutoloadClasses;
		$actual = array();

		$files = array_unique( $expected );

		foreach ( $files as $file ) {
			// Only prefix $IP if it doesn't have it already.
			// Generally local classes don't have it, and those from extensions and test suites do.
			if ( substr( $file, 0, 1 ) != '/' && substr( $file, 1, 1 ) != ':' ) {
				$filePath = "$IP/$file";
			} else {
				$filePath = $file;
			}
			if ( $supportsParsekit ) {
				$parseInfo = parsekit_compile_file( "$filePath" );
				$classes = array_keys( $parseInfo['class_table'] );
			} else {
				$contents = file_get_contents( "$filePath" );
				$m = array();
				preg_match_all( '/\n\s*(?:final)?\s*(?:abstract)?\s*(?:class|interface)\s+([a-zA-Z0-9_]+)/', $contents, $m, PREG_PATTERN_ORDER );
				$classes = $m[1];
			}
			foreach ( $classes as $class ) {
				$actual[$class] = $file;
			}
		}

		return array(
			'expected' => $expected,
			'actual' => $actual,
		);
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

	function testWrongCaseSerializedClass() {
		$dummyCereal = 'O:29:"testautoloadedserializedclass":0:{}';
		$uncerealized = unserialize( $dummyCereal );
		$this->assertFalse( $uncerealized instanceof __PHP_Incomplete_Class,
			"unserialize() can load classes case-insensitively.");
	}
}

/**
 * Cheater to poke protected members
 */
class InstrumentedAutoLoader extends AutoLoader {
	static function resetAutoloadLocalClassesLower() {
		self::$autoloadLocalClassesLower = null;
	}
}
