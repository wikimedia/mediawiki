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

/**
 * @ingroup ResourceLoader
 * @internal
 */
class ResourceLoaderMwUrlModule {
	/**
	 * @param string $content JavaScript RegExp content with additional whitespace
	 *  and named capturing group allowed, which will be stripped.
	 * @return string JavaScript code
	 */
	public static function makeJsFromExtendedRegExp( string $content ) : string {
		// Remove whitespace.
		$content = preg_replace( '/\s+/', '', $content );
		// Remove named capturing groups.
		// This allows long regexes to be self-documenting, which we allow for
		// developer convenience, but this syntax is invalid JavaScript ES5.
		$content = preg_replace( '/\?<\w+?>/', '', $content );
		// Format as a valid JavaScript import.
		return 'module.exports = /' . strtr( $content, [ '/' => '\/' ] ) . '/;';
	}
}
