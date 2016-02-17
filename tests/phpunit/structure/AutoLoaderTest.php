<?php

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

		// wgAutoloadLocalClasses has precedence, just like in includes/AutoLoader.php
		$expected = $wgAutoloadLocalClasses + $wgAutoloadClasses;
		$actual = [];

		$files = array_unique( $expected );

		foreach ( $files as $class => $file ) {
			// Only prefix $IP if it doesn't have it already.
			// Generally local classes don't have it, and those from extensions and test suites do.
			if ( substr( $file, 0, 1 ) != '/' && substr( $file, 1, 1 ) != ':' ) {
				$filePath = "$IP/$file";
			} else {
				$filePath = $file;
			}

			if ( !file_exists( $filePath ) ) {
				$actual[$class] = "[file '$filePath' does not exist]";
				continue;
			}

			MediaWiki\suppressWarnings();
			$contents = file_get_contents( $filePath );
			MediaWiki\restoreWarnings();

			if ( $contents === false ) {
				$actual[$class] = "[couldn't read file '$filePath']";
				continue;
			}

			// We could use token_get_all() here, but this is faster
			$matches = [];
			preg_match_all( '/
				^ [\t ]* (?:
					(?:final\s+)? (?:abstract\s+)? (?:class|interface|trait) \s+
					(?P<class> [a-zA-Z0-9_]+)
				|
					class_alias \s* \( \s*
						([\'"]) (?P<original> [^\'"]+) \g{-2} \s* , \s*
						([\'"]) (?P<alias> [^\'"]+ ) \g{-2} \s*
					\) \s* ;
				)
			/imx', $contents, $matches, PREG_SET_ORDER );

			$namespaceMatch = [];
			preg_match( '/
				^ [\t ]*
					namespace \s+
						([a-zA-Z0-9_]+(\\\\[a-zA-Z0-9_]+)*)
					\s* ;
			/imx', $contents, $namespaceMatch );
			$fileNamespace = $namespaceMatch ? $namespaceMatch[1] . '\\' : '';

			$classesInFile = [];
			$aliasesInFile = [];

			foreach ( $matches as $match ) {
				if ( !empty( $match['class'] ) ) {
					$class = $fileNamespace . $match['class'];
					$actual[$class] = $file;
					$classesInFile[$class] = true;
				} else {
					$aliasesInFile[$match['alias']] = $match['original'];
				}
			}

			// Only accept aliases for classes in the same file, because for correct
			// behavior, all aliases for a class must be set up when the class is loaded
			// (see <https://bugs.php.net/bug.php?id=61422>).
			foreach ( $aliasesInFile as $alias => $class ) {
				if ( isset( $classesInFile[$class] ) ) {
					$actual[$alias] = $file;
				} else {
					$actual[$alias] = "[original class not in $file]";
				}
			}
		}

		return [
			'expected' => $expected,
			'actual' => $actual,
		];
	}

	function testCoreClass() {
		$this->assertTrue( class_exists( 'TestAutoloadedLocalClass' ) );
	}

	function testExtensionClass() {
		$this->assertTrue( class_exists( 'TestAutoloadedClass' ) );
	}

	function testWrongCaseClass() {
		$this->setMwGlobals( 'wgAutoloadAttemptLowercase', true );

		$this->assertTrue( class_exists( 'testautoLoadedcamlCLASS' ) );
	}

	function testWrongCaseSerializedClass() {
		$this->setMwGlobals( 'wgAutoloadAttemptLowercase', true );

		$dummyCereal = 'O:29:"testautoloadedserializedclass":0:{}';
		$uncerealized = unserialize( $dummyCereal );
		$this->assertFalse( $uncerealized instanceof __PHP_Incomplete_Class,
			"unserialize() can load classes case-insensitively." );
	}
}
