<?php
/**
 * This file contains ancient db-related functions that have been deprecated. Do
 * not use them. Please find the appropriate replacements.
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
 */

/**
 * @deprecated Use DatabaseBase::sourceFile(). Will probably be removed in 1.19
 */
function dbsource( $fname, $db = false ) {
	wfDeprecated( __METHOD__ );
	if ( !$db ) {
		$db = wfGetDB( DB_MASTER );
	}
	$error = $db->sourceFile( $fname );
	if ( $error !== true ) {
		print $error;
		exit( 1 );
	}
}
