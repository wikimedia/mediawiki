<?php
/**
 * Sanity checks for making sure registered resources are sane.
 *
 * @file
 * @author Niklas Laxström
 * @copyright Copyright © 2012, Niklas Laxström
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */
class ResourcesTest extends MediaWikiTestCase {

	/**
	 * @dataProvider fileProvider
	 */
	public function testFileExistence( $file, $comment ) {
		$this->assertTrue( file_exists( $file ), $comment );
	}

	public function fileProvider() {
		global $IP, $wgResourceModules;
		$files = array();
		$modules = require( "$IP/resources/Resources.php" );
		$modules = array_merge( $modules, $wgResourceModules );

		foreach ( $modules as $id => $module ) {
			if ( isset( $module['localBasePath'] ) ) {
				$prefix = $module['localBasePath'];
			} else {
				$prefix = $IP;
			}

			foreach( array( 'styles', 'scripts', 'languageScripts' ) as $type ) {
				if ( isset( $module[$type] ) ) {
					foreach ( (array) $module[$type] as $key => $value ) {
						if ( is_int( $key ) || $type === 'languageScripts' ) {
							$file = $value;
						} else {
							$file = $key;
						}
						$files[] = array( "$prefix/$file", "File $prefix/$file of $type referenced by rl module $id exists" );
					}
				}
			}
		}

		$tempList = array();
		if ( isset( $module['skinStyles'] ) ) {
			$tempList = $module['skinStyles'];
		}
		foreach( $tempList as $skin => $file ) {
			$files[] = array( "$prefix/$file", "File $prefix/$file of $type referenced by rl module $id exists" );
		}

		return $files;
	}

}
