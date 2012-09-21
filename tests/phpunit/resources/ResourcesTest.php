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
		$this->assertFileExists( $file, $comment );
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

			foreach ( array( 'styles', 'scripts', 'languageScripts', 'skinStyles' ) as $type ) {
				if ( !isset( $module[$type] ) ) {
					continue;
				}

				foreach ( (array) $module[$type] as $key => $value ) {
					if ( is_int( $key ) || $type === 'languageScripts' || $type === 'skinStyles' ) {
						$fileList = $value;
					} else {
						$fileList = $key;
					}

					foreach ( (array) $fileList as $file ) {
						$files[] = array( "$prefix/$file", "$type referenced by ResourceLoader module $id" );
					}
				}
			}
		}

		$tempList = array();
		if ( isset( $module['skinStyles'] ) ) {
			$tempList = $module['skinStyles'];
		}
		foreach ( $tempList as $skin => $file ) {
			$files[] = array( "$prefix/$file", "$type referenced by ResourceLoader module $id" );
		}

		return $files;
	}

}
