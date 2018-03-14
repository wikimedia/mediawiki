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
];

$response = '';

// Only support for non-encoded module names, full module names expected
if ( isset( $_GET['modules'] ) ) {
	$modules = explode( ',', $_GET['modules'] );
	foreach ( $modules as $module ) {
		if ( isset( $moduleImplementations[$module] ) ) {
			$response .= $moduleImplementations[$module];
		} else {
			$response .= 'mw.loader.state(' . json_encode( $module ) . ', "missing" );' . "\n";
		}
	}
}

echo $response;
