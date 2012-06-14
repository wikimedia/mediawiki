<?php
/**
 * ResourceLoader stub working with pre-defined test modules.
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
 * @package MediaWiki
 * @author Lupo
 * @since 1.20
 */
header( 'Content-Type: text/javascript; charset=utf-8' );

require_once "../../../includes/Xml.php";

$modules = array (
	'test.use_missing' => array (
		'src' => 'mw.loader.implement("test.use_missing", function () {start(); ok(false, "Module test.use_missing should not run.");}, {}, {});',
		'deps' => array ( 'test.missing' )
	),
	'test.use_missing2' => array (
		'src' => 'mw.loader.implement("test.use_missing2", function () {start(); ok(false, "Module test.use_missing2 should not run.");}, {}, {});',
		'deps' => array ( 'test.missing2' )
	)
);

// Copied from ResourceLoaderContext
function expandModuleNames( $modules ) {
	$retval = array();
	// For backwards compatibility with an earlier hack, replace ! with .
	$modules = str_replace( '!', '.', $modules );
	$exploded = explode( '|', $modules );
	foreach ( $exploded as $group ) {
		if ( strpos( $group, ',' ) === false ) {
			// This is not a set of modules in foo.bar,baz notation
			// but a single module
			$retval[] = $group;
		} else {
			// This is a set of modules in foo.bar,baz notation
			$pos = strrpos( $group, '.' );
			if ( $pos === false ) {
				// Prefixless modules, i.e. without dots
				$retval = explode( ',', $group );
			} else {
				// We have a prefix and a bunch of suffixes
				$prefix = substr( $group, 0, $pos ); // 'foo'
				$suffixes = explode( ',', substr( $group, $pos + 1 ) ); // array( 'bar', 'baz' )
				foreach ( $suffixes as $suffix ) {
					$retval[] = "$prefix.$suffix";
				}
			}
		}
	}
	return $retval;
}

function addModule ( $moduleName, &$gotten ) {
	global $modules;

	$result = "";
	if ( isset( $gotten[$moduleName] ) ) {
		return $result;
	}
	$gotten[$moduleName] = true;
	if ( isset( $modules[$moduleName] ) ) {
		$deps = $modules[$moduleName]['deps'];
		foreach ( $deps as $depName ) {
			$result .= addModule( $depName, $gotten ) . "\n";
		}
		$result .= $modules[$moduleName]['src'] . "\n";
	} else {
		$result .= 'mw.loader.state( ' . Xml::encodeJsVar( $moduleName ) . ', "missing" );' . "\n";
	}
	return $result . "\n";
}

$result = "";

if ( isset( $_GET['modules'] ) ) {
	$toGet = expandModuleNames( $_GET['modules'] );
	$gotten = array();
	foreach ( $toGet as $moduleName ) {
		$result .= addModule( $moduleName, $gotten );
	}
}

echo $result;
