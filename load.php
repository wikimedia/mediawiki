<?php
/**
 * This file is the entry point for the resource loader.
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

// Bail on old versions of PHP, or if composer has not been run yet to install
// dependencies. Using dirname( __FILE__ ) here because __DIR__ is PHP5.3+.
require_once dirname( __FILE__ ) . '/includes/PHPVersionCheck.php';
wfEntryPointCheck( 'load.php' );

require __DIR__ . '/includes/WebStart.php';


// URL safety checks
if ( !$wgRequest->checkUrlExtension() ) {
	return;
}

// Respond to resource loading request.
// foo()->bar() syntax is not supported in PHP4, and this file needs to *parse* in PHP4.
$configFactory = ConfigFactory::getDefaultInstance();
$resourceLoader = new ResourceLoader( $configFactory->makeConfig( 'main' ) );
$resourceLoader->respond( new ResourceLoaderContext( $resourceLoader, $wgRequest ) );

Profiler::instance()->setTemplated( true );
wfLogProfilingData();

// Shut down the database.
$lb = wfGetLBFactory();
$lb->shutdown();
