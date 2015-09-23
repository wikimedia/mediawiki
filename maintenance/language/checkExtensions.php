<?php
/**
 * Check the extensions language files.
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
 * @ingroup MaintenanceLanguage
 */

require_once __DIR__ . '/../commandLine.inc';
require_once 'languages.inc';
require_once 'checkLanguage.inc';

if ( !class_exists( 'MessageGroups' ) || !class_exists( 'PremadeMediawikiExtensionGroups' ) ) {
	echo <<<TEXT
Please add the Translate extension to LocalSettings.php, and enable the extension groups:
	require_once 'extensions/Translate/Translate.php';
	\$wgTranslateEC = array_keys( \$wgTranslateAC );
If you still get this message, update Translate to its latest version.

TEXT;
	exit( -1 );
}

$cli = new CheckExtensionsCLI( $options, $argv[0] );
$cli->execute();
