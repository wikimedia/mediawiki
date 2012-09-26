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

require_once __DIR__ . '/../../../includes/json/FormatJson.php';
require_once __DIR__ . '/../../../includes/Xml.php';

$moduleImplementations = array(
	'testUsesMissing' => "
mw.loader.implement( 'testUsesMissing', function () {
	QUnit.ok( false, 'Module test.usesMissing script should not run.');
	QUnit.start();
}, {}, {});
",

	'testUsesNestedMissing' => "
mw.loader.implement( 'testUsesNestedMissing', function () {
	QUnit.ok( false, 'Module testUsesNestedMissing script should not run.');
}, {}, {});
",
);

$response = '';

// Only support for non-encoded module names, full module names expected
if ( isset( $_GET['modules'] ) ) {
	$modules = explode( ',', $_GET['modules'] );
	foreach ( $modules as $module ) {
		if ( isset( $moduleImplementations[$module] ) ) {
			$response .= $moduleImplementations[$module];
		} else {
			$response .= Xml::encodeJsCall( 'mw.loader.state', array( $module, 'missing' ), true );
		}
	}
}

echo $response;
