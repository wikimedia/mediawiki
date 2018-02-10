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
class SearchSqlite extends SearchDatabase {
	/**
	 * Whether fulltext search is supported by current schema
	 * @return bool
	 */
	function fulltextSearchSupported() {
		return $this->db->checkForEnabledSearch();
	}

	/**
	 * Parse the user's query and transform it into an SQL fragment which will
	 * become part of a WHERE clause
	 *
	 * @param string $filteredText
	 * @param bool $fulltext
	 * @return string
	 */
	function parseQuery( $filteredText, $fulltext ) {
		global $wgContLang;
		$lc = $this->legalSearchChars( self::CHARS_NO_SYNTAX ); // Minus syntax chars (" and *)
		$searchon = '';
		$this->searchTerms = [];

		$m = [];
		if ( preg_match_all( '/([-+<>~]?)(([' . $lc . ']+)(\*?)|"[^"]*")/',
				$filteredText, $m, PREG_SET_ORDER ) ) {
			foreach ( $m as $bits ) {
				Wikimedia\suppressWarnings();
				list( /* all */, $modifier, $term, $nonQuoted, $wildcard ) = $bits;
				Wikimedia\restoreWarnings();

				if ( $nonQuoted != '' ) {
					$term = $nonQuoted;
					$quote = '';
				} else {
					$term = str_replace( '"', '', $term );
					$quote = '"';
				}

				if ( $searchon !== '' ) {
					$searchon .= ' ';
				}

				// Some languages such as Serbian store the input form in the search index,
				// so we may need to search for matches in multiple writing system variants.
				$convertedVariants = $wgContLang->autoConvertToAllVariants( $term );
				if ( is_array( $convertedVariants ) ) {
					$variants = array_unique( array_values( $convertedVariants ) );
				} else {
					$variants = [ $term ];
				}

				// The low-level search index does some processing on input to work
				// around problems with minimum lengths and encoding in MySQL's
				// fulltext engine.
				// For Chinese this also inserts spaces between adjacent Han characters.
				$strippedVariants = array_map(
					[ $wgContLang, 'normalizeForSearch' ],
					$variants );

				// Some languages such as Chinese force all variants to a canonical
				// form when stripping to the low-level search index, so to be sure
				// let's check our variants list for unique items after stripping.
				$strippedVariants = array_unique( $strippedVariants );

				$searchon .= $modifier;
				if ( count( $strippedVariants ) > 1 ) {
					$searchon .= '(';
				}
				foreach ( $strippedVariants as $stripped ) {
					if ( $nonQuoted && strpos( $stripped, ' ' ) !== false ) {
						// Hack for Chinese: we need to toss in quotes for
						// multiple-character phrases since normalizeForSearch()
						// added spaces between them to make word breaks.
						$stripped = '"' . trim( $stripped ) . '"';
					}
					$searchon .= "$quote$stripped$quote$wildcard ";
				}
				if ( count( $strippedVariants ) > 1 ) {
					$searchon .= ')';
				}

				// Match individual terms or quoted phrase in result highlighting...
				// Note that variants will be introduced in a later stage for highlighting!
				$regexp = $this->regexTerm( $term, $wildcard );
				$this->searchTerms[] = $regexp;
			}

		} else {
			wfDebug( __METHOD__ . ": Can't understand search query '{$filteredText}'\n" );
		}

		$searchon = $this->db->addQuotes( $searchon );
		$field = $this->getIndexField( $fulltext );
		return " $field MATCH $searchon ";
	}

	function regexTerm( $string, $wildcard ) {
		global $wgContLang;

		$regex = preg_quote( $string, '/' );
		if ( $wgContLang->hasWordBreaks() ) {
			if ( $wildcard ) {
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

	public static function legalSearchChars( $type = self::CHARS_ALL ) {
		$searchChars = parent::legalSearchChars( $type );
		if ( $type === self::CHARS_ALL ) {
			// " for phrase, * for wildcard
			$searchChars = "\"*" . $searchChars;
		}
		return $searchChars;
	}

	/**
	 * Perform a full text search query and return a result set.
	 *
	 * @param string $term Raw search term
	 * @return SqlSearchResultSet
	 */
	function searchText( $term ) {
		return $this->searchInternal( $term, true );
	}

	/**
	 * Perform a title-only search query and return a result set.
	 *
	 * @param string $term Raw search term
	 * @return SqlSearchResultSet
	 */
	function searchTitle( $term ) {
		return $this->searchInternal( $term, false );
	}

	protected function searchInternal( $term, $fulltext ) {
		global $wgContLang;

		if ( !$this->fulltextSearchSupported() ) {
			return null;
		}

		$filteredTerm = $this->filter( $wgContLang->lc( $term ) );
		$resultSet = $this->db->query( $this->getQuery( $filteredTerm, $fulltext ) );

		$total = null;
		$totalResult = $this->db->query( $this->getCountQuery( $filteredTerm, $fulltext ) );
		$row = $totalResult->fetchObject();
		if ( $row ) {
			$total = intval( $row->c );
		}
		$totalResult->free();

		return new SqlSearchResultSet( $resultSet, $this->searchTerms, $total );
	}

	/**
	 * Return a partial WHERE clause to limit the search to the given namespaces
	 * @return string
	 */
	function queryNamespaces() {
		if ( is_null( $this->namespaces ) ) {
			return '';  # search all
		}
		if ( !count( $this->namespaces ) ) {
			$namespaces = '0';
		} else {
			$namespaces = $this->db->makeList( $this->namespaces );
		}
		return 'AND page_namespace IN (' . $namespaces . ')';
	}

	/**
	 * Returns a query with limit for number of results set.
	 * @param string $sql
	 * @return string
	 */
	function limitResult( $sql ) {
		return $this->db->limitResult( $sql, $this->limit, $this->offset );
	}

	/**
	 * Construct the full SQL query to do the search.
	 * The guts shoulds be constructed in queryMain()
	 * @param string $filteredTerm
	 * @param bool $fulltext
	 * @return string
	 */
	function getQuery( $filteredTerm, $fulltext ) {
		return $this->limitResult(
			$this->queryMain( $filteredTerm, $fulltext ) . ' ' .
			$this->queryNamespaces()
		);
	}

	/**
	 * Picks which field to index on, depending on what type of query.
	 * @param bool $fulltext
	 * @return string
	 */
	function getIndexField( $fulltext ) {
		return $fulltext ? 'si_text' : 'si_title';
	}

	/**
	 * Get the base part of the search query.
	 *
	 * @param string $filteredTerm
	 * @param bool $fulltext
	 * @return string
	 */
	function queryMain( $filteredTerm, $fulltext ) {
		$match = $this->parseQuery( $filteredTerm, $fulltext );
		$page = $this->db->tableName( 'page' );
		$searchindex = $this->db->tableName( 'searchindex' );
		return "SELECT $searchindex.rowid, page_namespace, page_title " .
			"FROM $page,$searchindex " .
			"WHERE page_id=$searchindex.rowid AND $match";
	}

	function getCountQuery( $filteredTerm, $fulltext ) {
		$match = $this->parseQuery( $filteredTerm, $fulltext );
		$page = $this->db->tableName( 'page' );
		$searchindex = $this->db->tableName( 'searchindex' );
		return "SELECT COUNT(*) AS c " .
			"FROM $page,$searchindex " .
			"WHERE page_id=$searchindex.rowid AND $match " .
			$this->queryNamespaces();
	}

	/**
	 * Create or update the search index record for the given page.
	 * Title and text should be pre-processed.
	 *
	 * @param int $id
	 * @param string $title
	 * @param string $text
	 */
	function update( $id, $title, $text ) {
		if ( !$this->fulltextSearchSupported() ) {
			return;
		}
		// @todo find a method to do it in a single request,
		// couldn't do it so far due to typelessness of FTS3 tables.
		$dbw = wfGetDB( DB_MASTER );

		$dbw->delete( 'searchindex', [ 'rowid' => $id ], __METHOD__ );

		$dbw->insert( 'searchindex',
			[
				'rowid' => $id,
				'si_title' => $title,
				'si_text' => $text
			], __METHOD__ );
	}

	/**
	 * Update a search index record's title only.
	 * Title should be pre-processed.
	 *
	 * @param int $id
	 * @param string $title
	 */
	function updateTitle( $id, $title ) {
		if ( !$this->fulltextSearchSupported() ) {
			return;
		}
		$dbw = wfGetDB( DB_MASTER );

		$dbw->update( 'searchindex',
			[ 'si_title' => $title ],
			[ 'rowid' => $id ],
			__METHOD__ );
	}
}
