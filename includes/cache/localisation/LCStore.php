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
 * Interface for the persistence layer of LocalisationCache.
 *
 * The persistence layer is two-level hierarchical cache. The first level
 * is the language, the second level is the item or subitem.
 *
 * Since the data for a whole language is rebuilt in one operation, it needs
 * to have a fast and atomic method for deleting or replacing all of the
 * current data for a given language. The interface reflects this bulk update
 * operation. Callers writing to the cache must first call startWrite(), then
 * will call set() a couple of thousand times, then will call finishWrite()
 * to commit the operation. When finishWrite() is called, the cache is
 * expected to delete all data previously stored for that language.
 *
 * The values stored are PHP variables suitable for serialize(). Implementations
 * of LCStore are responsible for serializing and unserializing.
 */
interface LCStore {

	/**
	 * Get a value.
	 * @param string $code Language code
	 * @param string $key Cache key
	 */
	function get( $code, $key );

	/**
	 * Start a write transaction.
	 * @param string $code Language code
	 */
	function startWrite( $code );

	/**
	 * Finish a write transaction.
	 */
	function finishWrite();

	/**
	 * Set a key to a given value. startWrite() must be called before this
	 * is called, and finishWrite() must be called afterwards.
	 * @param string $key
	 * @param mixed $value
	 */
	function set( $key, $value );

}
