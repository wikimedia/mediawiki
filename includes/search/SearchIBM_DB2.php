<?php
/**
 * IBM DB2 search engine
 *
 * Copyright © 2004 Brion Vibber <brion@pobox.com>
 * http://www.mediawiki.org/
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
 * Search engine hook base class for IBM DB2
 * @ingroup Search
 */
class SearchIBM_DB2 extends SearchEngine {

	/**
	 * Creates an instance of this class
	 * @param $db DatabaseIbm_db2: database object
	 */
	function __construct($db) {
		parent::__construct( $db );
	}

	/**
	 * Perform a full text search query and return a result set.
	 *
	 * @param $term String: raw search term
	 * @return SqlSearchResultSet
	 */
	function searchText( $term ) {
		$resultSet = $this->db->resultObject($this->db->query($this->getQuery($this->filter($term), true)));
		return new SqlSearchResultSet($resultSet, $this->searchTerms);
	}

	/**
	 * Perform a title-only search query and return a result set.
	 *
	 * @param $term String: taw search term
	 * @return SqlSearchResultSet
	 */
	function searchTitle($term) {
		$resultSet = $this->db->resultObject($this->db->query($this->getQuery($this->filter($term), false)));
		return new SqlSearchResultSet($resultSet, $this->searchTerms);
	}


	/**
	 * Return a partial WHERE clause to exclude redirects, if so set
	 * @return String
	 */
	function queryRedirect() {
		if ($this->showRedirects) {
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
			return '';
		$namespaces = implode(',', $this->namespaces);
		if ($namespaces == '') {
			$namespaces = '0';
		}
		return 'AND page_namespace IN (' . $namespaces . ')';
	}

	/**
	 * Return a LIMIT clause to limit results on the query.
	 * @return String
	 */
	function queryLimit( $sql ) {
		return $this->db->limitResult($sql, $this->limit, $this->offset);
	}

	/**
	 * Does not do anything for generic search engine
	 * subclasses may define this though
	 * @return String
	 */
	function queryRanking($filteredTerm, $fulltext) {
		// requires Net Search Extender or equivalent
		// return ' ORDER BY score(1)';
		return '';
	}

	/**
	 * Construct the full SQL query to do the search.
	 * The guts shoulds be constructed in queryMain()
	 * @param $filteredTerm String
	 * @param $fulltext Boolean
	 */
	function getQuery( $filteredTerm, $fulltext ) {
		return $this->queryLimit($this->queryMain($filteredTerm, $fulltext) . ' ' .
			$this->queryRedirect() . ' ' .
			$this->queryNamespaces() . ' ' .
			$this->queryRanking( $filteredTerm, $fulltext ) . ' ');
	}


	/**
	 * Picks which field to index on, depending on what type of query.
	 * @param $fulltext Boolean
	 * @return String
	 */
	function getIndexField($fulltext) {
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
		$match = $this->parseQuery($filteredTerm, $fulltext);
		$page        = $this->db->tableName('page');
		$searchindex = $this->db->tableName('searchindex');
		return 'SELECT page_id, page_namespace, page_title ' .
			"FROM $page,$searchindex " .
			'WHERE page_id=si_page AND ' . $match;
	}

	/** @todo document */
	function parseQuery($filteredText, $fulltext) {
		global $wgContLang;
		$lc = SearchEngine::legalSearchChars();
		$this->searchTerms = array();

		# @todo FIXME: This doesn't handle parenthetical expressions.
		$m = array();
		$q = array();

		if (preg_match_all('/([-+<>~]?)(([' . $lc . ']+)(\*?)|"[^"]*")/',
			  $filteredText, $m, PREG_SET_ORDER)) {
			foreach($m as $terms) {

				// Search terms in all variant forms, only
				// apply on wiki with LanguageConverter
				$temp_terms = $wgContLang->autoConvertToAllVariants( $terms[2] );
				if( is_array( $temp_terms )) {
					$temp_terms = array_unique( array_values( $temp_terms ));
					foreach( $temp_terms as $t )
						$q[] = $terms[1] . $wgContLang->normalizeForSearch( $t );
				}
				else
					$q[] = $terms[1] . $wgContLang->normalizeForSearch( $terms[2] );

				if (!empty($terms[3])) {
					$regexp = preg_quote( $terms[3], '/' );
					if ($terms[4])
						$regexp .= "[0-9A-Za-z_]+";
				} else {
					$regexp = preg_quote(str_replace('"', '', $terms[2]), '/');
				}
				$this->searchTerms[] = $regexp;
			}
		}

		$searchon = $this->db->strencode(join(',', $q));
		$field = $this->getIndexField($fulltext);
		
		// requires Net Search Extender or equivalent
		//return " CONTAINS($field, '$searchon') > 0 ";
		
		return " lcase($field) LIKE lcase('%$searchon%')";
	}

	/**
	 * Create or update the search index record for the given page.
	 * Title and text should be pre-processed.
	 *
	 * @param $id Integer
	 * @param $title String
	 * @param $text String
	 */
	function update($id, $title, $text) {
		$dbw = wfGetDB(DB_MASTER);
		$dbw->replace('searchindex',
			array('si_page'),
			array(
				'si_page' => $id,
				'si_title' => $title,
				'si_text' => $text
			), 'SearchIBM_DB2::update' );
		// ?
		//$dbw->query("CALL ctx_ddl.sync_index('si_text_idx')");
		//$dbw->query("CALL ctx_ddl.sync_index('si_title_idx')");
	}

	/**
	 * Update a search index record's title only.
	 * Title should be pre-processed.
	 *
	 * @param $id Integer
	 * @param $title String
	 */
	function updateTitle($id, $title) {
		$dbw = wfGetDB(DB_MASTER);

		$dbw->update('searchindex',
			array('si_title' => $title),
			array('si_page'  => $id),
			'SearchIBM_DB2::updateTitle',
			array());
	}
}
