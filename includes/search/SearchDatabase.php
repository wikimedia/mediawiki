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
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Base search engine base class for database-backed searches
 * @stable to extend
 * @ingroup Search
 * @since 1.23
 */
abstract class SearchDatabase extends SearchEngine {
	/** @var ILoadBalancer */
	protected $lb;
	/** @var IDatabase (backwards compatibility) */
	protected $db;

	/**
	 * @var string[] search terms
	 */
	protected $searchTerms = [];

	/**
	 * @param ILoadBalancer $lb The load balancer for the DB cluster to search on
	 */
	public function __construct( ILoadBalancer $lb ) {
		$this->lb = $lb;
		// @TODO: remove this deprecated field in 1.35
		$this->db = $lb->getLazyConnectionRef( DB_REPLICA ); // b/c
	}

	/**
	 * @param string $term
	 * @return ISearchResultSet|Status|null
	 */
	final public function doSearchText( $term ) {
		return $this->doSearchTextInDB( $this->extractNamespacePrefix( $term ) );
	}

	/**
	 * Perform a full text search query and return a result set.
	 *
	 * @param string $term Raw search term
	 * @return SqlSearchResultSet|null
	 */
	abstract protected function doSearchTextInDB( $term );

	/**
	 * @param string $term
	 * @return ISearchResultSet|null
	 */
	final public function doSearchTitle( $term ) {
		return $this->doSearchTitleInDB( $this->extractNamespacePrefix( $term ) );
	}

	/**
	 * Perform a title-only search query and return a result set.
	 *
	 * @param string $term Raw search term
	 * @return SqlSearchResultSet|null
	 */
	abstract protected function doSearchTitleInDB( $term );

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

	/**
	 * Extract the optional namespace prefix and set self::namespaces
	 * accordingly and return the query string
	 * @param string $term
	 * @return string the query string without any namespace prefix
	 */
	final protected function extractNamespacePrefix( $term ) {
		$queryAndNs = self::parseNamespacePrefixes( $term );
		if ( $queryAndNs === false ) {
			return $term;
		}
		$this->namespaces = $queryAndNs[1];
		return $queryAndNs[0];
	}
}
