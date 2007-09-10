<?php
# Copyright (C) 2005 Brion Vibber <brion@pobox.com>
# http://www.mediawiki.org/
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
# http://www.gnu.org/copyleft/gpl.html

$options = array( 'fix' );

/** */
require_once( 'commandLine.inc' );
require_once( 'maintenance/userDupes.inc' );

$wgTitle = Title::newFromText( 'Dupe user entry cleanup script' );

$fix = isset( $options['fix'] );
$dbw =& wfGetDB( DB_MASTER );
$duper = new UserDupes( $dbw );
$retval = $duper->checkDupes( $fix );

if( $retval ) {
	echo "\nLooks good!\n";
	exit( 0 );
} else {
	echo "\nOh noeees\n";
	exit( -1 );
}

?>