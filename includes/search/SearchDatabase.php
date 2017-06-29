<?php
/**
 * Database search engine
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
 * @ingroup Search
 */

use Wikimedia\Rdbms\IDatabase;

/**
 * Base search engine base class for database-backed searches
 * @ingroup Search
 * @since 1.23
 */
class SearchDatabase extends SearchEngine {
	/**
	 * @var IDatabase Slave database for reading from for results
	 */
	protected $db;

	/**
	 * Constructor
	 * @param IDatabase $db The database to search from
	 */
	public function __construct( IDatabase $db = null ) {
		if ( $db ) {
			$this->db = $db;
		} else {
			$this->db = wfGetDB( DB_REPLICA );
		}
	}

	/**
	 * Return a 'cleaned up' search string
	 *
	 * @param string $text
	 * @return string
	 */
	protected function filter( $text ) {
		// List of chars allowed in the search query.
		// This must include chars used in the search syntax.
		// Usually " (phrase) or * (wildcards) if supported by the engine
		$lc = $this->legalSearchChars( self::CHARS_ALL );
		return trim( preg_replace( "/[^{$lc}]/", " ", $text ) );
	}
}
