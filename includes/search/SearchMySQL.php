<?php
# Copyright (C) 2004 Brion Vibber <brion@pobox.com>
# http://www.mediawiki.org/
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
# http://www.gnu.org/copyleft/gpl.html

/**
 * @file
 * @ingroup Search
 */

/**
 * Search engine hook for MySQL 4+
 * @ingroup Search
 */
class SearchMySQL extends SearchEngine {
	var $strictMatching = true;

	/** @todo document */
	function __construct( $db ) {
		$this->db = $db;
	}

	/** 
	 * Parse the user's query and transform it into an SQL fragment which will 
	 * become part of a WHERE clause
	 */
	function parseQuery( $filteredText, $fulltext ) {
		global $wgContLang;
		$lc = SearchEngine::legalSearchChars(); // Minus format chars
		$searchon = '';
		$this->searchTerms = array();

		# FIXME: This doesn't handle parenthetical expressions.
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
			
				if( $searchon !== '' ) $searchon .= ' ';
				if( $this->strictMatching && ($modifier == '') ) {
					// If we leave this out, boolean op defaults to OR which is rarely helpful.
					$modifier = '+';
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
					array( $wgContLang, 'stripForSearch' ),
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
						// multiple-character phrases since stripForSearch()
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
			wfDebug( __METHOD__ . ": Would search with '$searchon'\n" );
			wfDebug( __METHOD__ . ': Match with /' . implode( '|', $this->searchTerms ) . "/\n" );
		} else {
			wfDebug( __METHOD__ . ": Can't understand search query '{$filteredText}'\n" );
		}

		$searchon = $this->db->strencode( $searchon );
		$field = $this->getIndexField( $fulltext );
		return " MATCH($field) AGAINST('$searchon' IN BOOLEAN MODE) ";
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
	 * @return MySQLSearchResultSet
	 */
	function searchText( $term ) {
		return $this->searchInternal( $term, true );
	}

	/**
	 * Perform a title-only search query and return a result set.
	 *
	 * @param $term String: raw search term
	 * @return MySQLSearchResultSet
	 */
	function searchTitle( $term ) {
		return $this->searchInternal( $term, false );
	}
	
	protected function searchInternal( $term, $fulltext ) {
		global $wgSearchMySQLTotalHits;
		
		$filteredTerm = $this->filter( $term );
		$resultSet = $this->db->query( $this->getQuery( $filteredTerm, $fulltext ) );
		
		$total = null;
		if( $wgSearchMySQLTotalHits ) {
			$totalResult = $this->db->query( $this->getCountQuery( $filteredTerm, $fulltext ) );
			$row = $totalResult->fetchObject();
			if( $row ) {
				$total = intval( $row->c );
			}
			$totalResult->free();
		}
		
		return new MySQLSearchResultSet( $resultSet, $this->searchTerms, $total );
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
	 * Return a LIMIT clause to limit results on the query.
	 * @return String
	 */
	function queryLimit() {
		return $this->db->limitResult( '', $this->limit, $this->offset );
	}

	/**
	 * Does not do anything for generic search engine
	 * subclasses may define this though
	 * @return String
	 */
	function queryRanking( $filteredTerm, $fulltext ) {
		return '';
	}

	/**
	 * Construct the full SQL query to do the search.
	 * The guts shoulds be constructed in queryMain()
	 * @param $filteredTerm String
	 * @param $fulltext Boolean
	 */
	function getQuery( $filteredTerm, $fulltext ) {
		return $this->queryMain( $filteredTerm, $fulltext ) . ' ' .
			$this->queryRedirect() . ' ' .
			$this->queryNamespaces() . ' ' .
			$this->queryRanking( $filteredTerm, $fulltext ) . ' ' .
			$this->queryLimit();
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
	 * The actual match syntax will depend on the server
	 * version; MySQL 3 and MySQL 4 have different capabilities
	 * in their fulltext search indexes.
	 *
	 * @param $filteredTerm String
	 * @param $fulltext Boolean
	 * @return String
	 */
	function queryMain( $filteredTerm, $fulltext ) {
		$match = $this->parseQuery( $filteredTerm, $fulltext );
		$page        = $this->db->tableName( 'page' );
		$searchindex = $this->db->tableName( 'searchindex' );
		return 'SELECT page_id, page_namespace, page_title ' .
			"FROM $page,$searchindex " .
			'WHERE page_id=si_page AND ' . $match;
	}

	function getCountQuery( $filteredTerm, $fulltext ) {
		$match = $this->parseQuery( $filteredTerm, $fulltext );
		$page        = $this->db->tableName( 'page' );
		$searchindex = $this->db->tableName( 'searchindex' );
		return "SELECT COUNT(*) AS c " .
			"FROM $page,$searchindex " .
			'WHERE page_id=si_page AND ' . $match .
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
		$dbw = wfGetDB( DB_MASTER );
		$dbw->replace( 'searchindex',
			array( 'si_page' ),
			array(
				'si_page' => $id,
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
		$dbw = wfGetDB( DB_MASTER );

		$dbw->update( 'searchindex',
			array( 'si_title' => $title ),
			array( 'si_page'  => $id ),
			__METHOD__,
			array( $dbw->lowPriorityOption() ) );
	}
}

/**
 * @ingroup Search
 */
class MySQLSearchResultSet extends SqlSearchResultSet {
	function MySQLSearchResultSet( $resultSet, $terms, $totalHits=null ) {
		parent::__construct( $resultSet, $terms );
		$this->mTotalHits = $totalHits;
	}

	function getTotalHits() {
		return $this->mTotalHits;
	}
}