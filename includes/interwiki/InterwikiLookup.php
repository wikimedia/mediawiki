<?php
namespace MediaWiki\Interwiki;

/**
 * Service interface for looking up Interwiki records.
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
use Interwiki;

/**
 * Service interface for looking up Interwiki records.
 *
 * @since 1.28
 */
interface InterwikiLookup {

	/**
	 * Check whether an interwiki prefix exists
	 *
	 * @param string $prefix Interwiki prefix to use
	 * @return bool Whether it exists
	 */
	public function isValidInterwiki( $prefix );

	/**
	 * Fetch an Interwiki object
	 *
	 * @param string $prefix Interwiki prefix to use
	 * @return Interwiki|null|bool
	 */
	public function fetch( $prefix );

	/**
	 * Returns all interwiki prefixes
	 *
	 * @param string|null $local If set, limits output to local/non-local interwikis
	 * @return string[] List of prefixes
	 */
	public function getAllPrefixes( $local = null );

	/**
	 * Purge the in-process and persistent object cache for an interwiki prefix
	 * @param string $prefix
	 */
	public function invalidateCache( $prefix );

}
