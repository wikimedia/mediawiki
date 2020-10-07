<?php
/**
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
 * @defgroup Maintenance Maintenance
 */

if ( !defined( 'MW_ENTRY_POINT' ) ) {
	define( 'MW_ENTRY_POINT', 'cli' );
}

// Bail on old versions of PHP, or if composer has not been run yet to install
// dependencies.
require_once __DIR__ . '/../includes/PHPVersionCheck.php';
wfEntryPointCheck( 'text' );

/**
 * @defgroup MaintenanceArchive Maintenance archives
 * @ingroup Maintenance
 */

// Define this so scripts can easily find doMaintenance.php
define( 'RUN_MAINTENANCE_IF_MAIN', __DIR__ . '/doMaintenance.php' );

// Original name for compat, harmless
// Support: MediaWiki < 1.31
define( 'DO_MAINTENANCE', RUN_MAINTENANCE_IF_MAIN );

/**
 * @var string|false
 * @phan-var class-string|false
 */
$maintClass = false;

// Some extensions rely on MW_INSTALL_PATH to find core files to include. Setting it here helps them
// if they're included by a core script (like DatabaseUpdater) after Maintenance.php has already
// been run.
if ( strval( getenv( 'MW_INSTALL_PATH' ) ) === '' ) {
	putenv( 'MW_INSTALL_PATH=' . realpath( __DIR__ . '/..' ) );
}

require_once __DIR__ . '/includes/Maintenance.php';
require_once __DIR__ . '/includes/LoggedUpdateMaintenance.php';
require_once __DIR__ . '/includes/FakeMaintenance.php';
