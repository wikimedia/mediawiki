<?php
/**
 * Core profiling functions. Have to exist before basically anything.
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
 * @ingroup Profiler
 */

/**
 * Get system resource usage of current request context.
 * Invokes the getrusage(2) system call, requesting RUSAGE_SELF if on PHP5
 * or RUSAGE_THREAD if on HHVM. Returns false if getrusage is not available.
 *
 * @since 1.24
 * @return array|bool Resource usage data or false if no data available.
 */
function wfGetRusage() {
	if ( !function_exists( 'getrusage' ) ) {
		return false;
	} elseif ( defined( 'HHVM_VERSION' ) && PHP_OS === 'Linux' ) {
		return getrusage( 2 /* RUSAGE_THREAD */ );
	} else {
		return getrusage( 0 /* RUSAGE_SELF */ );
	}
}

/**
 * Begin profiling of a function
 * @param string $functionname Name of the function we will profile
 * @deprecated since 1.25
 */
function wfProfileIn( $functionname ) {
}

/**
 * Stop profiling of a function
 * @param string $functionname Name of the function we have profiled
 * @deprecated since 1.25
 */
function wfProfileOut( $functionname = 'missing' ) {
}
