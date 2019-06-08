<?php
/**
 * Mock load.php with pre-defined test modules.
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

// This file doesn't run as part of MediaWiki
// phpcs:disable MediaWiki.Usage.SuperGlobalsUsage.SuperGlobals

header( 'Content-Type: text/javascript; charset=utf-8' );

$moduleImplementations = [
	'testUsesMissing' => "
mw.loader.implement( 'testUsesMissing', function () {
	mw.loader.testFail( 'Module usesMissing script should not run.' );
}, {}, {});
",

	'testUsesNestedMissing' => "
mw.loader.implement( 'testUsesNestedMissing', function () {
	mw.loader.testFail('Module testUsesNestedMissing script should not run.' );
}, {}, {});
",

	'testSkipped' => "
mw.loader.implement( 'testSkipped', function () {
	mw.loader.testFail( false, 'Module testSkipped was supposed to be skipped.' );
}, {}, {});
",

	'testNotSkipped' => "
mw.loader.implement( 'testNotSkipped', function () {}, {}, {});
",

	'testUsesSkippable' => "
mw.loader.implement( 'testUsesSkippable', function () {}, {}, {});
",

	'testUrlInc' => "
mw.loader.implement( 'testUrlInc', function () {} );
",
	'testUrlInc.a' => "
mw.loader.implement( 'testUrlInc.a', function () {} );
",
	'testUrlInc.b' => "
mw.loader.implement( 'testUrlInc.b', function () {} );
",
	'testUrlOrder' => "
mw.loader.implement( 'testUrlOrder', function () {} );
",
	'testUrlOrder.a' => "
mw.loader.implement( 'testUrlOrder.a', function () {} );
",
	'testUrlOrder.b' => "
mw.loader.implement( 'testUrlOrder.b', function () {} );
",
];

$response = '';

// Does not support the full behaviour of the real load.php.
// This only supports dotless module names joined by comma,
// with the exception of the hardcoded cases for testUrl*.
if ( isset( $_GET['modules'] ) ) {
	if ( $_GET['modules'] === 'testUrlInc,testUrlIncDump|testUrlInc.a,b' ) {
		$modules = [ 'testUrlInc', 'testUrlIncDump', 'testUrlInc.a', 'testUrlInc.b' ];
	} elseif ( $_GET['modules'] === 'testUrlOrder,testUrlOrderDump|testUrlOrder.a,b' ) {
		$modules = [ 'testUrlOrder', 'testUrlOrderDump', 'testUrlOrder.a', 'testUrlOrder.b' ];
	} else {
		$modules = explode( ',', $_GET['modules'] );
	}
	foreach ( $modules as $module ) {
		if ( isset( $moduleImplementations[$module] ) ) {
			$response .= $moduleImplementations[$module];
		} elseif ( preg_match( '/^test.*Dump$/', $module ) === 1 ) {
			$queryModules = $_GET['modules'];
			$queryVersion = isset( $_GET['version'] ) ? strval( $_GET['version'] ) : null;
			$response .= 'mw.loader.implement( ' . json_encode( $module )
				. ', function ( $, jQuery, require, module ) {'
				. 'module.exports.query = { '
				. 'modules: ' . json_encode( $queryModules ) . ','
				. 'version: ' . json_encode( $queryVersion )
				. ' };'
				. '} );';
		} else {
			// Default
			$response .= 'mw.loader.state(' . json_encode( [ $module => 'missing' ] ) . ');' . "\n";
		}
	}
}

echo $response;
