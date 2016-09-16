<?php
/**
 * @defgroup Database Database
 *
 * This file deals with database interface functions
 * and query specifics/optimisations.
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
 * @ingroup Database
 */

/**
 * Database abstraction object
 * @ingroup Database
 */
abstract class DatabaseBase extends Database {
	/**
	 * Boolean, controls output of large amounts of debug information.
	 * @param bool|null $debug
	 *   - true to enable debugging
	 *   - false to disable debugging
	 *   - omitted or null to do nothing
	 *
	 * @return bool Previous value of the flag
	 * @deprecated since 1.28; use setFlag()
	 */
	public function debug( $debug = null ) {
		$res = $this->getFlag( DBO_DEBUG );
		if ( $debug !== null ) {
			$debug ? $this->setFlag( DBO_DEBUG ) : $this->clearFlag( DBO_DEBUG );
		}

		return $res;
	}

	/**
	 * Returns true if this database supports (and uses) cascading deletes
	 *
	 * @return bool
	 */
	public function cascadingDeletes() {
		return false;
	}
	/**
	 * Returns true if this database supports (and uses) triggers (e.g. on the page table)
	 *
	 * @return bool
	 */
	public function cleanupTriggers() {
		return false;
	}
	/**
	 * Returns true if this database is strict about what can be put into an IP field.
	 * Specifically, it uses a NULL value instead of an empty string.
	 *
	 * @return bool
	 */
	public function strictIPs() {
		return false;
	}

	/**
	 * @return string
	 * @deprecated since 1.27; use SearchEngineFactory::getSearchEngineClass()
	 */
	public function getSearchEngine() {
		return 'SearchEngineDummy';
	}
}
