<?php
/**
 * Mssql search engine
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
 * Search engine hook base class for Mssql (ConText).
 * @ingroup Search
 */
class SearchMssql extends SearchEngine {

	/**
	 * Creates an instance of this class
	 * @param $db DatabaseMssql: database object
	 */
	function __construct( $db ) {
		parent::__construct( $db );
	}

	/**
	 * Perform a full text search query and return a result set.
	 *
	 * @param $term String: raw search term
	 * @return MssqlSearchResultSet
	 * @access public
	 */
	function searchText( $term ) {
		$resultSet = $this->db->resultObject( $this->db->query( $this->getQuery( $this->filter( $term ), true ) ) );
		return new MssqlSearchResultSet( $resultSet, $this->searchTerms );
	}

	/**
	 * Perform a title-only search query and return a result set.
	 *
	 * @param $term String: raw search term
	 * @return MssqlSearchResultSet
	 * @access public
	 */
	function searchTitle( $term ) {
		$resultSet = $this->db->resultObject( $this->db->query( $this->getQuery( $this->filter( $term ), false ) ) );
		return new MssqlSearchResultSet( $resultSet, $this->searchTerms );
	}


	/**
	 * Return a partial WHERE clause to exclude redirects, if so set
	 *
	 * @return String
	 * @private
	 */
	function queryRedirect() {
		if ( $this->showRedirects ) {
			return '';
		} else {
			return 'AND page_is_redirect=0';
		}
	}

	/**
	 * Return a partial WHERE clause to limit the search to the given namespaces
	 *
	 * @return String
	 * @private                           
	 */
	function queryNamespaces() {
		$namespaces = implode( ',', $this->namespaces );
		if ( $namespaces == '' ) {
			$namespaces = '0';
		}
		return 'AND page_namespace IN (' . $namespaces . ')';
	}

	/**
	 * Return a LIMIT clause to limit results on the query.
	 *
	 * @param $sql string
	 *
	 * @return String
	 */
	function queryLimit( $sql ) {
		return $this->db->limitResult( $sql, $this->limit, $this->offset );
	}

	/**
	 * Does not do anything for generic search engine
	 * subclasses may define this though
	 *
	 * @return String
	 */
	function queryRanking( $filteredTerm, $fulltext ) {
		return ' ORDER BY ftindex.[RANK] DESC'; // return ' ORDER BY score(1)';
	}

	/**
	 * Construct the full SQL query to do the search.
	 * The guts shoulds be constructed in queryMain()
	 *
	 * @param $filteredTerm String
	 * @param $fulltext Boolean
	 * @return String
	 */
	function getQuery( $filteredTerm, $fulltext ) {
		return $this->queryLimit( $this->queryMain( $filteredTerm, $fulltext ) . ' ' .
			$this->queryRedirect() . ' ' .
			$this->queryNamespaces() . ' ' .
			$this->queryRanking( $filteredTerm, $fulltext ) . ' ' );
	}

	/**
	 * Picks which field to index on, depending on what type of query.
	 *
	 * @param $fulltext Boolean
	 * @return string
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
	 * @private
	 */
	function queryMain( $filteredTerm, $fulltext ) {
		$match = $this->parseQuery( $filteredTerm, $fulltext );
		$page        = $this->db->tableName( 'page' );
		$searchindex = $this->db->tableName( 'searchindex' );
		
		return 'SELECT page_id, page_namespace, page_title, ftindex.[RANK]' .
			"FROM $page,FREETEXTTABLE($searchindex , $match, LANGUAGE 'English') as ftindex " .
			'WHERE page_id=ftindex.[KEY] ';
	}

	/** @todo document
	 * @return string
	 */
	function parseQuery( $filteredText, $fulltext ) {
		global $wgContLang;
		$lc = SearchEngine::legalSearchChars();
		$this->searchTerms = array();

		# @todo FIXME: This doesn't handle parenthetical expressions.
		$m = array();
		$q = array();

		if ( preg_match_all( '/([-+<>~]?)(([' . $lc . ']+)(\*?)|"[^"]*")/',
			$filteredText, $m, PREG_SET_ORDER ) ) {
			foreach ( $m as $terms ) {
				$q[] = $terms[1] . $wgContLang->normalizeForSearch( $terms[2] );

				if ( !empty( $terms[3] ) ) {
					$regexp = preg_quote( $terms[3], '/' );
					if ( $terms[4] )
						$regexp .= "[0-9A-Za-z_]+";
				} else {
					$regexp = preg_quote( str_replace( '"', '', $terms[2] ), '/' );
				}
				$this->searchTerms[] = $regexp;
			}
		}

		$searchon = $this->db->strencode( join( ',', $q ) );
		$field = $this->getIndexField( $fulltext );
		return "$field, '$searchon'";
	}

	/**
	 * Create or update the search index record for the given page.
	 * Title and text should be pre-processed.
	 *
	 * @param $id Integer
	 * @param $title String
	 * @param $text String
	 * @return bool|\ResultWrapper
	 */
	function update( $id, $title, $text ) {
		// We store the column data as UTF-8 byte order marked binary stream
		// because we are invoking the plain text IFilter on it so that, and we want it 
		// to properly decode the stream as UTF-8.  SQL doesn't support UTF8 as a data type
		// but the indexer will correctly handle it by this method.  Since all we are doing
		// is passing this data to the indexer and never retrieving it via PHP, this will save space
		$table = $this->db->tableName( 'searchindex' );
		$utf8bom = '0xEFBBBF';
		$si_title = $utf8bom . bin2hex( $title );
		$si_text = $utf8bom . bin2hex( $text );
		$sql = "DELETE FROM $table WHERE si_page = $id;";
		$sql .= "INSERT INTO $table (si_page, si_title, si_text) VALUES ($id, $si_title, $si_text)";
		return $this->db->query( $sql, 'SearchMssql::update' );
	}

	/**
	 * Update a search index record's title only.
	 * Title should be pre-processed.
	 *
	 * @param $id Integer
	 * @param $title String
	 * @return bool|\ResultWrapper
	 */
	function updateTitle( $id, $title ) {
		$table = $this->db->tableName( 'searchindex' );

		// see update for why we are using the utf8bom
		$utf8bom = '0xEFBBBF';
		$si_title = $utf8bom . bin2hex( $title );
		$sql = "DELETE FROM $table WHERE si_page = $id;";
		$sql .= "INSERT INTO $table (si_page, si_title, si_text) VALUES ($id, $si_title, 0x00)";
		return $this->db->query( $sql, 'SearchMssql::updateTitle' );
	}
}

/**
 * @ingroup Search
 */
class MssqlSearchResultSet extends SearchResultSet {
	function __construct( $resultSet, $terms ) {
		$this->mResultSet = $resultSet;
		$this->mTerms = $terms;
	}

	function termMatches() {
		return $this->mTerms;
	}

	function numRows() {
		return $this->mResultSet->numRows();
	}

	function next() {
		$row = $this->mResultSet->fetchObject();
		if ( $row === false )
			return false;
		return new SearchResult( $row );
	}
}


