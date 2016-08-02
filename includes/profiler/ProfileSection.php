<?php
/**
 * Function scope profiling assistant
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
 * Class for handling function-scope profiling
 *
 * @since 1.22
 * @deprecated since 1.25 No-op now
 */
class ProfileSection {
	/**
	 * Begin profiling of a function and return an object that ends profiling
	 * of the function when that object leaves scope. As long as the object is
	 * not specifically linked to other objects, it will fall out of scope at
	 * the same moment that the function to be profiled terminates.
	 *
	 * This is typically called like:
	 * @code$section = new ProfileSection( __METHOD__ );@endcode
	 *
	 * @param string $name Name of the function to profile
	 */
	public function __construct( $name ) {
	}
}
