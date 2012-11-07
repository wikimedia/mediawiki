<?php
/**
 * Check the autoloader
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Maintenance
 */

require_once( __DIR__ . '/Maintenance.php' );

/**
 * Maintenance script to check classes definitions in the autoloader.
 *
 * @ingroup Maintenance
 */
class CheckAutoLoader extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Look for missing or incorrect entries in wgAutoloadLocalClasses.';
		$this->addOption( 'with-ext', 'Also look through classes from extensions and test suites (wgAutoloadClasses)', false, false, 'e' );
	}
	public function execute() {
		$result = self::assertAutoloadConf( $this->hasOption( 'with-ext' ) );
		foreach ( $result['missing'] as $missing ) {
			// printf( "%-50s Unlisted, in %s\n", $class, $file );
			$this->output( "\t'{$missing['class']}' => '{$missing['file']}',\n" );
		}
		foreach ( $result['wrong'] as $wrong ) {
			$this->output( "{$wrong['class']}: Wrong file: found in {$wrong['file-real']}, listed in {$wrong['file-config']}\n" );
		}
	}

	/**
	 * Used here in self::execute() and in AutoLoaderTest
	 */
	public static function assertAutoloadConf( $includeExt = false ) {
		global $wgAutoloadLocalClasses, $IP;

		$config = $wgAutoloadLocalClasses;

		if ( $includeExt ) {
			global $wgAutoloadClasses;
			require_once( $IP . '/tests/TestsAutoLoader.php' );
			// wgAutoloadLocalClasses has presecence, just like in includes/AutoLoader.php
			$config = $config + $wgAutoloadClasses;
		}

		$files = array_unique( $config );

		$ret = array(
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
					$ret['missing'][] = array(
						'class' => $class,
						'file' => str_replace( "$IP/", '', $file ),
					);
				} elseif ( $config[$class] !== $file ) {
					$ret['wrong'][] = array(
						'class' => $class,
						'file-config' => $config[$class],
						'file-real' => $file,
					);
				}
			}
		}

		return $ret;
	}
}

$maintClass = "CheckAutoLoader";
require_once( RUN_MAINTENANCE_IF_MAIN );
