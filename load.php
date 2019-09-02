<?php
/**
 * This file is the entry point for ResourceLoader.
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
 * @author Roan Kattouw
 * @author Trevor Parscal
 */

use MediaWiki\MediaWikiServices;

// This endpoint is supposed to be independent of request cookies and other
// details of the session. Enforce this constraint with respect to session use.
define( 'MW_NO_SESSION', 1 );

define( 'MW_ENTRY_POINT', 'load' );

require __DIR__ . '/includes/WebStart.php';

// URL safety checks
if ( !$wgRequest->checkUrlExtension() ) {
	return;
}

// Disable ChronologyProtector so that we don't wait for unrelated MediaWiki
// writes when getting database connections for ResourceLoader. (T192611)
MediaWikiServices::getInstance()->getDBLoadBalancerFactory()->disableChronologyProtection();

$resourceLoader = MediaWikiServices::getInstance()->getResourceLoader();
$context = new ResourceLoaderContext( $resourceLoader, $wgRequest );

// Respond to ResourceLoader request
$resourceLoader->respond( $context );

Profiler::instance()->setAllowOutput();

$mediawiki = new MediaWiki();
$mediawiki->doPostOutputShutdown( 'fast' );
