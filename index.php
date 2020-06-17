<?php // For broken web servers: ><pre>

// If you are reading this in your web browser, your server is probably
// not configured correctly to run PHP applications!
//
// See the README, INSTALL, and UPGRADE files for basic setup instructions
// and pointers to the online documentation.
//
// https://www.mediawiki.org/wiki/Special:MyLanguage/MediaWiki
//
// -------------------------------------------------

/**
 * The main web entry point for web browser navigations, usually via an
 * Action or SpecialPage subclass.
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
 * @ingroup entrypoint
 */

define( 'MW_ENTRY_POINT', 'index' );

// Bail on old versions of PHP, or if composer has not been run yet to install
// dependencies. Using dirname( __FILE__ ) here because __DIR__ is PHP5.3+.
// phpcs:ignore MediaWiki.Usage.DirUsage.FunctionFound
require_once dirname( __FILE__ ) . '/includes/PHPVersionCheck.php';
wfEntryPointCheck( 'html', dirname( $_SERVER['SCRIPT_NAME'] ) );

require __DIR__ . '/includes/WebStart.php';

wfIndexMain();

function wfIndexMain() {
	// @todo Deprecate global $mediaWiki
	// phpcs:ignore MediaWiki.NamingConventions.ValidGlobalName.allowedPrefix
	global $mediaWiki;
	$mediaWiki = new MediaWiki();
	$mediaWiki->run();
}
