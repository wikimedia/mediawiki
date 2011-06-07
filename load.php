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
 *
 */

require ( dirname( __FILE__ ) . '/includes/WebStart.php' );
wfProfileIn( 'load.php' );

// URL safety checks
if ( !$wgRequest->checkUrlExtension() ) {
	return;
}

// Respond to resource loading request
$resourceLoader = new ResourceLoader();
$resourceLoader->respond( new ResourceLoaderContext( $resourceLoader, $wgRequest ) );

wfProfileOut( 'load.php' );
wfLogProfilingData();

// Shut down the database
wfGetLBFactory()->shutdown();
