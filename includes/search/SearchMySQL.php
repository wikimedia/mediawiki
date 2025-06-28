<?php
/**
 * MySQL search engine
 *
 * Copyright (C) 2004 Brooke Vibber <bvibber@wikimedia.org>
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

use MediaWiki\MediaWikiServices;
use Wikimedia\AtEase\AtEase;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeValue;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * Search engine hook for MySQL
 * @ingroup Search
 */
class SearchMySQL extends SearchDatabase {
	/** @var bool */
	protected $strictMatching = true;

	/** @var int|null */
	private static $mMinSearchLength;

	/**
	 * Parse the user's query and transform it into two SQL fragments:
	 * a WHERE condition and an ORDER BY expression
	 *
	 * @param string $filteredText
	 * @param bool $fulltext
	 *
	 * @return array
	 */
	private function parseQuery( $filteredText, $fulltext ) {
		$lc = $this->legalSearchChars( self::CHARS_NO_SYNTAX ); // Minus syntax chars (" and *)
		$searchon = '';
		$this->searchTerms = [];

		# @todo FIXME: This doesn't handle parenthetical expressions.
		$m = [];
		if ( preg_match_all( '/([-+<>~]?)(([' . $lc . ']+)(\*?)|"[^"]*")/',
				$filteredText, $m, PREG_SET_ORDER )
		) {
			$services = MediaWikiServices::getInstance();
			$contLang = $services->getContentLanguage();
			$langConverter = $services->getLanguageConverterFactory()->getLanguageConverter( $contLang );
			foreach ( $m as $bits ) {
				AtEase::suppressWarnings();
				[ /* all */, $modifier, $term, $nonQuoted, $wildcard ] = $bits;
				AtEase::restoreWarnings();

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
				$convertedVariants = $langConverter->autoConvertToAllVariants( $term );
				if ( is_array( $convertedVariants ) ) {
					$variants = array_unique( array_values( $convertedVariants ) );
				} else {
					$variants = [ $term ];
				}

				// The low-level search index does some processing on input to work
				// around problems with minimum lengths and encoding in MySQL's
				// fulltext engine.
				// For Chinese this also inserts spaces between adjacent Han characters.
				$strippedVariants = array_map( [ $contLang, 'normalizeForSearch' ], $variants );

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
					if ( $nonQuoted && str_contains( $stripped, ' ' ) ) {
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
			wfDebug( __METHOD__ . ": Would search with '$searchon'" );
			wfDebug( __METHOD__ . ': Match with /' . implode( '|', $this->searchTerms ) . "/" );
		} else {
			wfDebug( __METHOD__ . ": Can't understand search query '{$filteredText}'" );
		}

		$dbr = $this->dbProvider->getReplicaDatabase();
		$searchon = $dbr->addQuotes( $searchon );
		$field = $this->getIndexField( $fulltext );
		return [
			" MATCH($field) AGAINST($searchon IN BOOLEAN MODE) ",
			" MATCH($field) AGAINST($searchon IN NATURAL LANGUAGE MODE) DESC "
		];
	}

	private function regexTerm( string $string, ?string $wildcard ): string {
		$regex = preg_quote( $string, '/' );
		if ( MediaWikiServices::getInstance()->getContentLanguage()->hasWordBreaks() ) {
			if ( $wildcard ) {
				// Don't cut off the final bit!
				$regex = "\b$regex";
			} else {
				$regex = "\b$regex\b";
			}
		} else {
			// For Chinese, words may legitimately abut other words in the text literal.
			// Don't add \b boundary checks... note this could cause false positives
			// for Latin chars.
		}
		return $regex;
	}

	/** @inheritDoc */
	public function legalSearchChars( $type = self::CHARS_ALL ) {
		$searchChars = parent::legalSearchChars( $type );

		// In the MediaWiki UI, search strings containing (just) a hyphen are translated into
		//     MATCH(si_title) AGAINST('+- ' IN BOOLEAN MODE)
		// which is not valid.

		// From <https://dev.mysql.com/doc/refman/8.0/en/fulltext-boolean.html>:
		// "InnoDB full-text search does not support... a plus and minus sign combination ('+-')"

		// See also https://phabricator.wikimedia.org/T221560
		$searchChars = preg_replace( '/\\\\-/', '', $searchChars );

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
	 * @return SqlSearchResultSet|null
	 */
	protected function doSearchTextInDB( $term ) {
		return $this->searchInternal( $term, true );
	}

	/**
	 * Perform a title-only search query and return a result set.
	 *
	 * @param string $term Raw search term
	 * @return SqlSearchResultSet|null
	 */
	protected function doSearchTitleInDB( $term ) {
		return $this->searchInternal( $term, false );
	}

	protected function searchInternal( string $term, bool $fulltext ): ?SqlSearchResultSet {
		// This seems out of place, why is this called with empty term?
		if ( trim( $term ) === '' ) {
			return null;
		}

		$filteredTerm = $this->filter( $term );
		$queryBuilder = $this->getQueryBuilder( $filteredTerm, $fulltext );
		$resultSet = $queryBuilder->caller( __METHOD__ )->fetchResultSet();

		$total = null;
		$queryBuilder = $this->getCountQueryBuilder( $filteredTerm, $fulltext );
		$totalResult = $queryBuilder->caller( __METHOD__ )->fetchResultSet();

		$row = $totalResult->fetchObject();
		if ( $row ) {
			$total = intval( $row->c );
		}
		$totalResult->free();

		return new SqlSearchResultSet( $resultSet, $this->searchTerms, $total );
	}

	/** @inheritDoc */
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
	 * @param SelectQueryBuilder $queryBuilder
	 * @since 1.18
	 */
	protected function queryFeatures( SelectQueryBuilder $queryBuilder ) {
		foreach ( $this->features as $feature => $value ) {
			if ( $feature === 'title-suffix-filter' && $value ) {
				$dbr = $this->dbProvider->getReplicaDatabase();
				$queryBuilder->andWhere(
					$dbr->expr( 'page_title', IExpression::LIKE, new LikeValue( $dbr->anyString(), $value ) )
				);
			}
		}
	}

	/**
	 * Add namespace conditions
	 * @param SelectQueryBuilder $queryBuilder
	 * @since 1.18 (changed)
	 */
	private function queryNamespaces( $queryBuilder ) {
		if ( is_array( $this->namespaces ) ) {
			if ( count( $this->namespaces ) === 0 ) {
				$this->namespaces[] = NS_MAIN;
			}
			$queryBuilder->andWhere( [ 'page_namespace' => $this->namespaces ] );
		}
	}

	/**
	 * Construct the SQL query builder to do the search.
	 * @param string $filteredTerm
	 * @param bool $fulltext
	 * @return SelectQueryBuilder
	 * @since 1.41
	 */
	private function getQueryBuilder( $filteredTerm, $fulltext ): SelectQueryBuilder {
		$queryBuilder = $this->dbProvider->getReplicaDatabase()->newSelectQueryBuilder();

		$this->queryMain( $queryBuilder, $filteredTerm, $fulltext );
		$this->queryFeatures( $queryBuilder );
		$this->queryNamespaces( $queryBuilder );
		$queryBuilder->limit( $this->limit )
			->offset( $this->offset );

		return $queryBuilder;
	}

	/**
	 * Picks which field to index on, depending on what type of query.
	 * @param bool $fulltext
	 * @return string
	 */
	private function getIndexField( $fulltext ) {
		return $fulltext ? 'si_text' : 'si_title';
	}

	/**
	 * Get the base part of the search query.
	 *
	 * @param SelectQueryBuilder $queryBuilder Search query builder
	 * @param string $filteredTerm
	 * @param bool $fulltext
	 * @since 1.18 (changed)
	 */
	private function queryMain( SelectQueryBuilder $queryBuilder, $filteredTerm, $fulltext ) {
		$match = $this->parseQuery( $filteredTerm, $fulltext );
		$queryBuilder->select( [ 'page_id', 'page_namespace', 'page_title' ] )
			->from( 'page' )
			->join( 'searchindex', null, 'page_id=si_page' )
			->where( $match[0] )
			->orderBy( $match[1] );
	}

	/**
	 * @since 1.41 (changed)
	 * @param string $filteredTerm
	 * @param bool $fulltext
	 * @return SelectQueryBuilder
	 */
	private function getCountQueryBuilder( $filteredTerm, $fulltext ): SelectQueryBuilder {
		$match = $this->parseQuery( $filteredTerm, $fulltext );
		$queryBuilder = $this->dbProvider->getReplicaDatabase()->newSelectQueryBuilder()
			->select( [ 'c' => 'COUNT(*)' ] )
			->from( 'page' )
			->join( 'searchindex', null, 'page_id=si_page' )
			->where( $match[0] );

		$this->queryFeatures( $queryBuilder );
		$this->queryNamespaces( $queryBuilder );

		return $queryBuilder;
	}

	/**
	 * Create or update the search index record for the given page.
	 * Title and text should be pre-processed.
	 *
	 * @param int $id
	 * @param string $title
	 * @param string $text
	 */
	public function update( $id, $title, $text ) {
		$this->dbProvider->getPrimaryDatabase()->newReplaceQueryBuilder()
			->replaceInto( 'searchindex' )
			->uniqueIndexFields( [ 'si_page' ] )
			->row( [
				'si_page' => $id,
				'si_title' => $this->normalizeText( $title ),
				'si_text' => $this->normalizeText( $text )
			] )
			->caller( __METHOD__ )->execute();
	}

	/**
	 * Update a search index record's title only.
	 * Title should be pre-processed.
	 *
	 * @param int $id
	 * @param string $title
	 */
	public function updateTitle( $id, $title ) {
		$this->dbProvider->getPrimaryDatabase()->newUpdateQueryBuilder()
			->update( 'searchindex' )
			->set( [ 'si_title' => $this->normalizeText( $title ) ] )
			->where( [ 'si_page' => $id ] )
			->caller( __METHOD__ )->execute();
	}

	/**
	 * Delete an indexed page
	 * Title should be pre-processed.
	 *
	 * @param int $id Page id that was deleted
	 * @param string $title Title of page that was deleted
	 */
	public function delete( $id, $title ) {
		$this->dbProvider->getPrimaryDatabase()->newDeleteQueryBuilder()
			->deleteFrom( 'searchindex' )
			->where( [ 'si_page' => $id ] )
			->caller( __METHOD__ )->execute();
	}

	/**
	 * Converts some characters for MySQL's indexing to grok it correctly,
	 * and pads short words to overcome limitations.
	 * @param string $string
	 * @return string
	 */
	public function normalizeText( $string ) {
		$out = parent::normalizeText( $string );

		// MySQL fulltext index doesn't grok utf-8, so we
		// need to fold cases and convert to hex
		$out = preg_replace_callback(
			"/([\\xc0-\\xff][\\x80-\\xbf]*)/",
			$this->stripForSearchCallback( ... ),
			MediaWikiServices::getInstance()->getContentLanguage()->lc( $out ) );

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
		// or "192.168.1.1" to work sensibly.
		// MySQL's search seems to ignore them, so you'd match on
		// "example.wikipedia.com" and "192.168.83.1" as well.
		return preg_replace(
			"/(\w)\.(\w|\*)/u",
			"$1u82e$2",
			$out
		);
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
		if ( self::$mMinSearchLength === null ) {
			$sql = "SHOW GLOBAL VARIABLES LIKE 'ft\\_min\\_word\\_len'";

			$dbr = $this->dbProvider->getReplicaDatabase();
			// The real type is still IDatabase, but IReplicaDatabase is used for safety.
			'@phan-var IDatabase $dbr';
			// phpcs:ignore MediaWiki.Usage.DbrQueryUsage.DbrQueryFound
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
