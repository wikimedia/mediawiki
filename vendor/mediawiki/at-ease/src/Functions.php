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
 */

namespace MediaWiki;

/**
 * Reference-counted warning suppression
 *
 * @param bool $end
 */
function suppressWarnings( $end = false ) {
	static $suppressCount = 0;
	static $originalLevel = false;

	if ( $end ) {
		if ( $suppressCount ) {
			--$suppressCount;
			if ( !$suppressCount ) {
				error_reporting( $originalLevel );
			}
		}
	} else {
		if ( !$suppressCount ) {
			$originalLevel = error_reporting( E_ALL & ~(
					E_WARNING |
					E_NOTICE |
					E_USER_WARNING |
					E_USER_NOTICE |
					E_DEPRECATED |
					E_USER_DEPRECATED |
					E_STRICT
				) );
		}
		++$suppressCount;
	}
}

/**
 * Restore error level to previous value
 */
function restoreWarnings() {
	suppressWarnings( true );
}


/**
 * Call the callback given by the first parameter, suppressing any warnings.
 *
 * @param callable $callback
 * @return mixed
 */
function quietCall( $callback /*, parameters... */ ) {
	$args = array_slice( func_get_args(), 1 );
	suppressWarnings();
	$rv = call_user_func_array( $callback, $args );
	restoreWarnings();
	return $rv;
}
