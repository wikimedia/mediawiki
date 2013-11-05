<?php
/**
 * Convenience maintenance script wrapper, useful for scripts
 * or extensions located outside of standard locations.
 *
 * To use, give the maintenance script as a relative path to
 * MediaWiki base install path.
 *
 * Example usage:
 *
 *  If your pwd is mediawiki base folder:
 *   php maintenance/runScript.php extensions/Wikibase/lib/maintenance/dispatchChanges.php
 *
 * If your pwd is maintenance folder:
 *  php runScript.php extensions/Wikibase/lib/maintenance/dispatchChanges.php
 *
 * Or full path:
 *  php /var/www/mediawiki/maintenance/runScript.php maintenance/runJobs.php
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
 * @author Katie Filbert < aude.wiki@gmail.com >
 * @file
 * @ingroup Maintenance
 */
if ( ! getenv( 'MW_INSTALL_PATH' ) ) {
	require_once __DIR__ . '/Maintenance.php';

	$script = $argv[1];
	array_shift( $argv );

	require_once __DIR__ . "/$script";
}
