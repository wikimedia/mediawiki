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

use MediaWiki\Logger\LoggerFactory;

// This endpoint is supposed to be independent of request cookies and other
// details of the session. Log warnings for violations of the no-session
// constraint.
define( 'MW_NO_SESSION', 'warn' );

require __DIR__ . '/includes/WebStart.php';

// URL safety checks
if ( !$wgRequest->checkUrlExtension() ) {
	return;
}

// Set up ResourceLoader
$resourceLoader = new ResourceLoader(
	ConfigFactory::getDefaultInstance()->makeConfig( 'main' ),
	LoggerFactory::getInstance( 'resourceloader' )
);
$context = new ResourceLoaderContext( $resourceLoader, $wgRequest );

// Respond to ResourceLoader request
$resourceLoader->respond( $context );

Profiler::instance()->setTemplated( true );

$mediawiki = new MediaWiki();
$mediawiki->doPostOutputShutdown( 'fast' );
