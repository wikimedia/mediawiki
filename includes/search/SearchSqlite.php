<?php
/**
 * SQLite search backend, based upon SearchMysql
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

/**
 * Search engine hook for SQLite
 * @ingroup Search
 */
class SearchSqlite extends SearchEngine {

	/**
	 * @var DatabaseSqlite
	 */
	protected $db;

	/**
	 * Creates an instance of this class
	 * @param $db DatabaseSqlite: database object
	 */
	function __construct( $db ) {
		parent::__construct( $db );
	}

	/**
	 * Whether fulltext search is supported by current schema
	 * @return Boolean
	 */
	function fulltextSearchSupported() {
		return $this->db->checkForEnabledSearch();
	}

	/**
	 * Parse the user's query and transform it into an SQL fragment which will
	 * become part of a WHERE clause
	 *
	 * @return string
	 */
	function parseQuery( $filteredText, $fulltext ) {
		global $wgContLang;
		$lc = SearchEngine::legalSearchChars(); // Minus format chars
		$searchon = '';
		$this->searchTerms = array();

		$m = array();
		if( preg_match_all( '/([-+<>~]?)(([' . $lc . ']+)(\*?)|"[^"]*")/',
			  $filteredText, $m, PREG_SET_ORDER ) ) {
			foreach( $m as $bits ) {
				@list( /* all */, $modifier, $term, $nonQuoted, $wildcard ) = $bits;

				if( $nonQuoted != '' ) {
					$term = $nonQuoted;
					$quote = '';
				} else {
					$term = str_replace( '"', '', $term );
					$quote = '"';
				}

				if( $searchon !== '' ) {
					$searchon .= ' ';
				}

				// Some languages such as Serbian store the input form in the search index,
				// so we may need to search for matches in multiple writing system variants.
				$convertedVariants = $wgContLang->autoConvertToAllVariants( $term );
				if( is_array( $convertedVariants ) ) {
					$variants = array_unique( array_values( $convertedVariants ) );
				} else {
					$variants = array( $term );
				}

				// The low-level search index does some processing on input to work
				// around problems with minimum lengths and encoding in MySQL's
				// fulltext engine.
				// For Chinese this also inserts spaces between adjacent Han characters.
				$strippedVariants = array_map(
					array( $wgContLang, 'normalizeForSearch' ),
					$variants );

				// Some languages such as Chinese force all variants to a canonical
				// form when stripping to the low-level search index, so to be sure
				// let's check our variants list for unique items after stripping.
				$strippedVariants = array_unique( $strippedVariants );

				$searchon .= $modifier;
				if( count( $strippedVariants) > 1 )
					$searchon .= '(';
				foreach( $strippedVariants as $stripped ) {
					if( $nonQuoted && strpos( $stripped, ' ' ) !== false ) {
						// Hack for Chinese: we need to toss in quotes for
						// multiple-character phrases since normalizeForSearch()
						// added spaces between them to make word breaks.
						$stripped = '"' . trim( $stripped ) . '"';
					}
					$searchon .= "$quote$stripped$quote$wildcard ";
				}
				if( count( $strippedVariants) > 1 )
					$searchon .= ')';

				// Match individual terms or quoted phrase in result highlighting...
				// Note that variants will be introduced in a later stage for highlighting!
				$regexp = $this->regexTerm( $term, $wildcard );
				$this->searchTerms[] = $regexp;
			}

		} else {
			wfDebug( __METHOD__ . ": Can't understand search query '{$filteredText}'\n" );
		}

		$searchon = $this->db->strencode( $searchon );
		$field = $this->getIndexField( $fulltext );
		return " $field MATCH '$searchon' ";
	}

	function regexTerm( $string, $wildcard ) {
		global $wgContLang;

		$regex = preg_quote( $string, '/' );
		if( $wgContLang->hasWordBreaks() ) {
			if( $wildcard ) {
				// Don't cut off the final bit!
				$regex = "\b$regex";
			} else {
				$regex = "\b$regex\b";
			}
		} else {
			// For Chinese, words may legitimately abut other words in the text literal.
			// Don't add \b boundary checks... note this could cause false positives
			// for latin chars.
		}
		return $regex;
	}

	public static function legalSearchChars() {
		return "\"*" . parent::legalSearchChars();
	}

	/**
	 * Perform a full text search query and return a result set.
	 *
	 * @param $term String: raw search term
	 * @return SqliteSearchResultSet
	 */
	function searchText( $term ) {
		return $this->searchInternal( $term, true );
	}

	/**
	 * Perform a title-only search query and return a result set.
	 *
	 * @param $term String: raw search term
	 * @return SqliteSearchResultSet
	 */
	function searchTitle( $term ) {
		return $this->searchInternal( $term, false );
	}

	protected function searchInternal( $term, $fulltext ) {
		global $wgCountTotalSearchHits, $wgContLang;

		if ( !$this->fulltextSearchSupported() ) {
			return null;
		}

		$filteredTerm = $this->filter( $wgContLang->lc( $term ) );
		$resultSet = $this->db->query( $this->getQuery( $filteredTerm, $fulltext ) );

		$total = null;
		if( $wgCountTotalSearchHits ) {
			$totalResult = $this->db->query( $this->getCountQuery( $filteredTerm, $fulltext ) );
			$row = $totalResult->fetchObject();
			if( $row ) {
				$total = intval( $row->c );
			}
			$totalResult->free();
		}

		return new SqliteSearchResultSet( $resultSet, $this->searchTerms, $total );
	}


	/**
	 * Return a partial WHERE clause to exclude redirects, if so set
	 * @return String
	 */
	function queryRedirect() {
		if( $this->showRedirects ) {
			return '';
		} else {
			return 'AND page_is_redirect=0';
		}
	}

	/**
	 * Return a partial WHERE clause to limit the search to the given namespaces
	 * @return String
	 */
	function queryNamespaces() {
		if( is_null($this->namespaces) )
			return '';  # search all
		if ( !count( $this->namespaces ) ) {
			$namespaces = '0';
		} else {
			$namespaces = $this->db->makeList( $this->namespaces );
		}
		return 'AND page_namespace IN (' . $namespaces . ')';
	}

	/**
	 * Returns a query with limit for number of results set.
	 * @param $sql String:
	 * @return String
	 */
	function limitResult( $sql ) {
		return $this->db->limitResult( $sql, $this->limit, $this->offset );
	}

	/**
	 * Construct the full SQL query to do the search.
	 * The guts shoulds be constructed in queryMain()
	 * @param $filteredTerm String
	 * @param $fulltext Boolean
	 */
	function getQuery( $filteredTerm, $fulltext ) {
		return $this->limitResult(
			$this->queryMain( $filteredTerm, $fulltext ) . ' ' .
			$this->queryRedirect() . ' ' .
			$this->queryNamespaces()
		);
	}

	/**
	 * Picks which field to index on, depending on what type of query.
	 * @param $fulltext Boolean
	 * @return String
	 */
	function getIndexField( $fulltext ) {
		return $fulltext ? 'si_text' : 'si_title';
	}

	/**
	 * Get the base part of the search query.
	 *
	 * @param $filteredTerm String
	 * @param $fulltext Boolean
	 * @return String
	 */
	function queryMain( $filteredTerm, $fulltext ) {
		$match = $this->parseQuery( $filteredTerm, $fulltext );
		$page        = $this->db->tableName( 'page' );
		$searchindex = $this->db->tableName( 'searchindex' );
		return "SELECT $searchindex.rowid, page_namespace, page_title " .
			"FROM $page,$searchindex " .
			"WHERE page_id=$searchindex.rowid AND $match";
	}

	function getCountQuery( $filteredTerm, $fulltext ) {
		$match = $this->parseQuery( $filteredTerm, $fulltext );
		$page        = $this->db->tableName( 'page' );
		$searchindex = $this->db->tableName( 'searchindex' );
		return "SELECT COUNT(*) AS c " .
			"FROM $page,$searchindex " .
			"WHERE page_id=$searchindex.rowid AND $match" .
			$this->queryRedirect() . ' ' .
			$this->queryNamespaces();
	}

	/**
	 * Create or update the search index record for the given page.
	 * Title and text should be pre-processed.
	 *
	 * @param $id Integer
	 * @param $title String
	 * @param $text String
	 */
	function update( $id, $title, $text ) {
		if ( !$this->fulltextSearchSupported() ) {
			return;
		}
		// @todo: find a method to do it in a single request,
		// couldn't do it so far due to typelessness of FTS3 tables.
		$dbw = wfGetDB( DB_MASTER );

		$dbw->delete( 'searchindex', array( 'rowid' => $id ), __METHOD__ );

		$dbw->insert( 'searchindex',
			array(
				'rowid' => $id,
				'si_title' => $title,
				'si_text' => $text
			), __METHOD__ );
	}

	/**
	 * Update a search index record's title only.
	 * Title should be pre-processed.
	 *
	 * @param $id Integer
	 * @param $title String
	 */
    function updateTitle( $id, $title ) {
		if ( !$this->fulltextSearchSupported() ) {
			return;
		}
		$dbw = wfGetDB( DB_MASTER );

		$dbw->update( 'searchindex',
			array( 'si_title' => $title ),
			array( 'rowid'  => $id ),
			__METHOD__ );
	}
}

/**
 * @ingroup Search
 */
class SqliteSearchResultSet extends SqlSearchResultSet {
	function __construct( $resultSet, $terms, $totalHits=null ) {
		parent::__construct( $resultSet, $terms );
		$this->mTotalHits = $totalHits;
	}

	function getTotalHits() {
		return $this->mTotalHits;
	}
}
