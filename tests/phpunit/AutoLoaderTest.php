<?php
class AutoLoaderTest extends MediaWikiTestCase {

	public function testConfig() {
		$results = self::checkAutoloadConf();

		$this->assertEquals(
			$results['missing'],
			array(),
			'No classes missing from configuration.'
		);

		$this->assertEquals(
			$results['wrong'],
			array(),
			'No incorrect entries in the configuration.'
		);
	}

	protected static function checkAutoloadConf() {
		global $wgAutoloadLocalClasses, $wgAutoloadClasses, $IP;

		// wgAutoloadLocalClasses has precedence, just like in includes/AutoLoader.php
		$config = $wgAutoloadLocalClasses + $wgAutoloadClasses;

		$files = array_unique( $config );

		$results = array(
			'missing' => array(),
			'wrong' => array(),
		);

		foreach ( $files as $file ) {
			// Only prefix $IP if it doesn't have it already.
			// Generally local classes don't have it, and those from extensions and test suites do.
			if ( substr( $file, 0, 1 ) != '/' && substr( $file, 1, 1 ) != ':' ) {
				$filePath = "$IP/$file";
			} else {
				$filePath = $file;
			}
			if ( function_exists( 'parsekit_compile_file' ) ) {
				$parseInfo = parsekit_compile_file( "$filePath" );
				$classes = array_keys( $parseInfo['class_table'] );
			} else {
				$contents = file_get_contents( "$filePath" );
				$m = array();
				preg_match_all( '/\n\s*class\s+([a-zA-Z0-9_]+)/', $contents, $m, PREG_PATTERN_ORDER );
				$classes = $m[1];
			}
			foreach ( $classes as $class ) {
				if ( !isset( $config[$class] ) ) {
					$results['missing'][] = array(
						'class' => $class,
						'file' => str_replace( "$IP/", '', $file ),
					);
				} elseif ( $config[$class] !== $file ) {
					$results['wrong'][] = array(
						'class' => $class,
						'file-config' => $config[$class],
						'file-real' => $file,
					);
				}
			}
		}

		return $results;
	}
}
