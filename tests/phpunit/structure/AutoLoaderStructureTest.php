<?php

/**
 * @coversNothing
 */
class AutoLoaderStructureTest extends MediaWikiTestCase {
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

	public function providePSR4Completeness() {
		foreach ( AutoLoader::$psr4Namespaces as $prefix => $dir ) {
			foreach ( $this->recurseFiles( $dir ) as $file ) {
				yield [ $prefix, $file ];
			}
		}
	}

	private function recurseFiles( $dir ) {
		return ( new File_Iterator_Facade() )->getFilesAsArray( $dir, [ '.php' ] );
	}

	/**
	 * @dataProvider providePSR4Completeness
	 */
	public function testPSR4Completeness( $prefix, $file ) {
		global $wgAutoloadLocalClasses, $wgAutoloadClasses;
		$contents = file_get_contents( $file );
		list( $classesInFile, $aliasesInFile ) = self::parseFile( $contents );
		$classes = array_keys( $classesInFile );
		$this->assertCount( 1, $classes,
			"Only one class per file in PSR-4 autoloaded classes ($file)" );

		$this->assertStringStartsWith( $prefix, $classes[0] );
		$this->assertTrue(
			class_exists( $classes[0] ) || interface_exists( $classes[0] ) || trait_exists( $classes[0] ),
			"Class {$classes[0]} not autoloaded properly"
		);

		$otherClasses = $wgAutoloadLocalClasses + $wgAutoloadClasses;
		foreach ( $aliasesInFile as $alias => $class ) {
			$this->assertArrayHasKey( $alias, $otherClasses,
				'Alias must be in the classmap autoloader'
			);
		}
	}

	private static function parseFile( $contents ) {
		// We could use token_get_all() here, but this is faster
		// Note: Keep in sync with ClassCollector
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
				|
					class_alias \s* \( \s*
						(?P<originalStatic> [a-zA-Z0-9_]+)::class \s* , \s*
						([\'"]) (?P<aliasString> [^\'"]+ ) \g{-2} \s*
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
				// 'class Foo {}'
				$class = $fileNamespace . $match['class'];
				$classesInFile[$class] = true;
			} else {
				if ( !empty( $match['original'] ) ) {
					// 'class_alias( "Foo", "Bar" );'
					$aliasesInFile[$match['alias']] = $match['original'];
				} else {
					// 'class_alias( Foo::class, "Bar" );'
					$aliasesInFile[$match['aliasString']] = $fileNamespace . $match['originalStatic'];
				}
			}
		}

		return [ $classesInFile, $aliasesInFile ];
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

			Wikimedia\suppressWarnings();
			$contents = file_get_contents( $filePath );
			Wikimedia\restoreWarnings();

			if ( $contents === false ) {
				$actual[$class] = "[couldn't read file '$filePath']";
				continue;
			}

			list( $classesInFile, $aliasesInFile ) = self::parseFile( $contents );

			foreach ( $classesInFile as $className => $ignore ) {
				$actual[$className] = $file;
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

	public function testAutoloadOrder() {
		$path = realpath( __DIR__ . '/../../..' );
		$oldAutoload = file_get_contents( $path . '/autoload.php' );
		$generator = new AutoloadGenerator( $path, 'local' );
		$generator->setExcludePaths( array_values( AutoLoader::getAutoloadNamespaces() ) );
		$generator->initMediaWikiDefault();
		$newAutoload = $generator->getAutoload( 'maintenance/generateLocalAutoload.php' );

		$this->assertEquals( $oldAutoload, $newAutoload, 'autoload.php does not match' .
			' output of generateLocalAutoload.php script.' );
	}
}
