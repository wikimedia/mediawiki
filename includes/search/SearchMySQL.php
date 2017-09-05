<?php
/**
 * MySQL search engine
 *
 * Copyright (C) 2004 Brion Vibber <brion@pobox.com>
 * https://www.mediawiki.org/
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
 * Search engine hook for MySQL 4+
 * @ingroup Search
 */
class SearchMySQL extends SearchDatabase {
	protected $strictMatching = true;

	private static $mMinSearchLength;

	/**
	 * Parse the user's query and transform it into an SQL fragment which will
	 * become part of a WHERE clause
	 *
	 * @param string $filteredText
	 * @param string $fulltext
	 *
	 * @return string
	 */
	function parseQuery( $filteredText, $fulltext ) {
		global $wgContLang;

		$lc = $this->legalSearchChars( self::CHARS_NO_SYNTAX ); // Minus syntax chars (" and *)
		$searchon = '';
		$this->searchTerms = [];

		# @todo FIXME: This doesn't handle parenthetical expressions.
		$m = [];
		if ( preg_match_all( '/([-+<>~]?)(([' . $lc . ']+)(\*?)|"[^"]*")/',
				$filteredText, $m, PREG_SET_ORDER ) ) {
			foreach ( $m as $bits ) {
				MediaWiki\suppressWarnings();
				list( /* all */, $modifier, $term, $nonQuoted, $wildcard ) = $bits;
				MediaWiki\restoreWarnings();

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
				if ( $this->strictMatching && ( $modifier == '' ) ) {
					// If we leave this out, boolean op defaults to OR which is rarely helpful.
					$modifier = '+';
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
					$stripped = $this->normalizeText( $stripped );
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
			wfDebug( __METHOD__ . ": Would search with '$searchon'\n" );
			wfDebug( __METHOD__ . ': Match with /' . implode( '|', $this->searchTerms ) . "/\n" );
		} else {
			wfDebug( __METHOD__ . ": Can't understand search query '{$filteredText}'\n" );
		}

		$searchon = $this->db->addQuotes( $searchon );
		$field = $this->getIndexField( $fulltext );
		return " MATCH($field) AGAINST($searchon IN BOOLEAN MODE) ";
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
		// This seems out of place, why is this called with empty term?
		if ( trim( $term ) === '' ) {
			return null;
		}

		$filteredTerm = $this->filter( $term );
		$query = $this->getQuery( $filteredTerm, $fulltext );
		$resultSet = $this->db->select(
			$query['tables'], $query['fields'], $query['conds'],
			__METHOD__, $query['options'], $query['joins']
		);

		$total = null;
		$query = $this->getCountQuery( $filteredTerm, $fulltext );
		$totalResult = $this->db->select(
			$query['tables'], $query['fields'], $query['conds'],
			__METHOD__, $query['options'], $query['joins']
		);

		$row = $totalResult->fetchObject();
		if ( $row ) {
			$total = intval( $row->c );
		}
		$totalResult->free();

		return new SqlSearchResultSet( $resultSet, $this->searchTerms, $total );
	}

	public function supports( $feature ) {
		switch ( $feature ) {
		case 'title-suffix-filter':
			return true;
		default:
			return parent::supports( $feature );
		}
	}

	/**
	 * Add special conditions
	 * @param array $query
	 * @since 1.18
	 */
	protected function queryFeatures( &$query ) {
		foreach ( $this->features as $feature => $value ) {
			if ( $feature === 'title-suffix-filter' && $value ) {
				$query['conds'][] = 'page_title' . $this->db->buildLike( $this->db->anyString(), $value );
			}
		}
	}

	/**
	 * Add namespace conditions
	 * @param array $query
	 * @since 1.18 (changed)
	 */
	function queryNamespaces( &$query ) {
		if ( is_array( $this->namespaces ) ) {
			if ( count( $this->namespaces ) === 0 ) {
				$this->namespaces[] = '0';
			}
			$query['conds']['page_namespace'] = $this->namespaces;
		}
	}

	/**
	 * Add limit options
	 * @param array $query
	 * @since 1.18
	 */
	protected function limitResult( &$query ) {
		$query['options']['LIMIT'] = $this->limit;
		$query['options']['OFFSET'] = $this->offset;
	}

	/**
	 * Construct the SQL query to do the search.
	 * The guts shoulds be constructed in queryMain()
	 * @param string $filteredTerm
	 * @param bool $fulltext
	 * @return array
	 * @since 1.18 (changed)
	 */
	function getQuery( $filteredTerm, $fulltext ) {
		$query = [
			'tables' => [],
			'fields' => [],
			'conds' => [],
			'options' => [],
			'joins' => [],
		];

		$this->queryMain( $query, $filteredTerm, $fulltext );
		$this->queryFeatures( $query );
		$this->queryNamespaces( $query );
		$this->limitResult( $query );

		return $query;
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
	 * @param array &$query Search query array
	 * @param string $filteredTerm
	 * @param bool $fulltext
	 * @since 1.18 (changed)
	 */
	function queryMain( &$query, $filteredTerm, $fulltext ) {
		$match = $this->parseQuery( $filteredTerm, $fulltext );
		$query['tables'][] = 'page';
		$query['tables'][] = 'searchindex';
		$query['fields'][] = 'page_id';
		$query['fields'][] = 'page_namespace';
		$query['fields'][] = 'page_title';
		$query['conds'][] = 'page_id=si_page';
		$query['conds'][] = $match;
	}

	/**
	 * @since 1.18 (changed)
	 * @param string $filteredTerm
	 * @param bool $fulltext
	 * @return array
	 */
	function getCountQuery( $filteredTerm, $fulltext ) {
		$match = $this->parseQuery( $filteredTerm, $fulltext );

		$query = [
			'tables' => [ 'page', 'searchindex' ],
			'fields' => [ 'COUNT(*) as c' ],
			'conds' => [ 'page_id=si_page', $match ],
			'options' => [],
			'joins' => [],
		];

		$this->queryFeatures( $query );
		$this->queryNamespaces( $query );

		return $query;
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
		$dbw = wfGetDB( DB_MASTER );
		$dbw->replace( 'searchindex',
			[ 'si_page' ],
			[
				'si_page' => $id,
				'si_title' => $this->normalizeText( $title ),
				'si_text' => $this->normalizeText( $text )
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
		$dbw = wfGetDB( DB_MASTER );

		$dbw->update( 'searchindex',
			[ 'si_title' => $this->normalizeText( $title ) ],
			[ 'si_page' => $id ],
			__METHOD__,
			[ $dbw->lowPriorityOption() ] );
	}

	/**
	 * Delete an indexed page
	 * Title should be pre-processed.
	 *
	 * @param int $id Page id that was deleted
	 * @param string $title Title of page that was deleted
	 */
	function delete( $id, $title ) {
		$dbw = wfGetDB( DB_MASTER );

		$dbw->delete( 'searchindex', [ 'si_page' => $id ], __METHOD__ );
	}

	/**
	 * Converts some characters for MySQL's indexing to grok it correctly,
	 * and pads short words to overcome limitations.
	 * @param string $string
	 * @return mixed|string
	 */
	function normalizeText( $string ) {
		global $wgContLang;

		$out = parent::normalizeText( $string );

		// MySQL fulltext index doesn't grok utf-8, so we
		// need to fold cases and convert to hex
		$out = preg_replace_callback(
			"/([\\xc0-\\xff][\\x80-\\xbf]*)/",
			[ $this, 'stripForSearchCallback' ],
			$wgContLang->lc( $out ) );

		// And to add insult to injury, the default indexing
		// ignores short words... Pad them so we can pass them
		// through without reconfiguring the server...
		$minLength = $this->minSearchLength();
		if ( $minLength > 1 ) {
			$n = $minLength - 1;
			$out = preg_replace(
				"/\b(\w{1,$n})\b/",
				"$1u800",
				$out );
		}

		// Periods within things like hostnames and IP addresses
		// are also important -- we want a search for "example.com"
		// or "192.168.1.1" to work sanely.
		// MySQL's search seems to ignore them, so you'd match on
		// "example.wikipedia.com" and "192.168.83.1" as well.
		$out = preg_replace(
			"/(\w)\.(\w|\*)/u",
			"$1u82e$2",
			$out );

		return $out;
	}

	/**
	 * Armor a case-folded UTF-8 string to get through MySQL's
	 * fulltext search without being mucked up by funny charset
	 * settings or anything else of the sort.
	 * @param array $matches
	 * @return string
	 */
	protected function stripForSearchCallback( $matches ) {
		return 'u8' . bin2hex( $matches[1] );
	}

	/**
	 * Check MySQL server's ft_min_word_len setting so we know
	 * if we need to pad short words...
	 *
	 * @return int
	 */
	protected function minSearchLength() {
		if ( is_null( self::$mMinSearchLength ) ) {
			$sql = "SHOW GLOBAL VARIABLES LIKE 'ft\\_min\\_word\\_len'";

			$dbr = wfGetDB( DB_SLAVE );
			$result = $dbr->query( $sql, __METHOD__ );
			$row = $result->fetchObject();
			$result->free();

			if ( $row && $row->Variable_name == 'ft_min_word_len' ) {
				self::$mMinSearchLength = intval( $row->Value );
			} else {
				self::$mMinSearchLength = 0;
			}
		}
		return self::$mMinSearchLength;
	}
}
