<?php
if ( ! defined( 'MEDIAWIKI' ) )
	die( -1 );
/**
 * A basic extension that's used by the parser tests to test date magic words
 *
 * Handy so that we don't have to upgrade the parsertests every second to
 * compensate with the passage of time and certainly less expensive than a
 * time-freezing device, get yours now!
 *
 * Copyright © 2005, 2006 Ævar Arnfjörð Bjarmason
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
 * @ingroup Maintenance
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 */

$wgHooks['ParserGetVariableValueTs'][] = 'wfParserTimeSetup';

function wfParserTimeSetup( &$parser, &$ts ) {
	$ts = 123; // $ perl -le 'print scalar localtime 123' ==> Thu Jan  1 00:02:03 1970

	return true;
}

