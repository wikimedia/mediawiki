<?php
class AutoLoaderTest extends MediaWikiTestCase {

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
}
