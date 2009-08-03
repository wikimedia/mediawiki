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
 * @ingroup Maintenance
 */

require_once( dirname(__FILE__) . '/Maintenance.php' );

class CheckAutoLoader extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "AutoLoader sanity checks";
	}
	public function execute() {
		global $wgAutoloadLocalClasses, $IP;
		$files = array_unique( $wgAutoloadLocalClasses );

		foreach( $files as $file ) {
			if( function_exists( 'parsekit_compile_file' ) ){
				$parseInfo = parsekit_compile_file( "$IP/$file" );
				$classes = array_keys( $parseInfo['class_table'] );
			} else {
				$contents = file_get_contents( "$IP/$file" );
				$m = array();
				preg_match_all( '/\n\s*class\s+([a-zA-Z0-9_]+)/', $contents, $m, PREG_PATTERN_ORDER );
				$classes = $m[1];
			}
			foreach ( $classes as $class ) {
				if ( !isset( $wgAutoloadLocalClasses[$class] ) ) {
					//printf( "%-50s Unlisted, in %s\n", $class, $file );
					$this->output( "\t'$class' => '$file',\n" );
				} elseif ( $wgAutoloadLocalClasses[$class] !== $file ) {
					$this->output( "$class: Wrong file: found in $file, listed in " . $wgAutoloadLocalClasses[$class] . "\n" );
				}
			}
		}
	}
}

$maintClass = "CheckAutoLoader";
require_once( DO_MAINTENANCE );
