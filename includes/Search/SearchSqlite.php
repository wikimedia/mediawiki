<?php
/**
 * SQLite search backend, based upon SearchMysql
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Search
 */

use MediaWiki\MediaWikiServices;
use Wikimedia\AtEase\AtEase;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * Search engine hook for SQLite
 * @ingroup Search
 */
class SearchSqlite extends SearchDatabase {
	/**
	 * Whether fulltext search is supported by the current schema
	 */
	private function fulltextSearchSupported(): bool {
		$dbr = $this->dbProvider->getReplicaDatabase();
		$sql = (string)$dbr->newSelectQueryBuilder()
			->select( 'sql' )
			->from( 'sqlite_master' )
			->where( [ 'tbl_name' => $dbr->tableName( 'searchindex', 'raw' ) ] )
			->caller( __METHOD__ )->fetchField();

		return ( stristr( $sql, 'fts' ) !== false );
	}

	/**
	 * Parse the user's query and transform it into an SQL fragment which will
	 * become part of a WHERE clause
	 */
	private function parseQuery( string $filteredText, bool $fulltext ): string {
		$lc = $this->legalSearchChars( self::CHARS_NO_SYNTAX ); // Minus syntax chars (" and *)
		$searchon = '';
		$this->searchTerms = [];

		$m = [];
		if ( preg_match_all( '/([-+<>~]?)(([' . $lc . ']+)(\*?)|"[^"]*")/',
				$filteredText, $m, PREG_SET_ORDER ) ) {
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

				// Some languages such as Serbian store the input form in the search index,
				// so we may need to search for matches in multiple writing system variants.

				$converter = MediaWikiServices::getInstance()->getLanguageConverterFactory()
					->getLanguageConverter();
				$convertedVariants = $converter->autoConvertToAllVariants( $term );
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
					[ MediaWikiServices::getInstance()->getContentLanguage(),
						'normalizeForSearch' ],
					$variants );

				// Some languages such as Chinese force all variants to a canonical
				// form when stripping to the low-level search index, so to be sure
				// let's check our variants list for unique items after stripping.
				$strippedVariants = array_unique( $strippedVariants );

				$searchon .= $modifier;
				if ( count( $strippedVariants ) > 1 ) {
					$searchon .= '(';
				}
				$count = 0;
				foreach ( $strippedVariants as $stripped ) {
					if ( $nonQuoted && str_contains( $stripped, ' ' ) ) {
						// Hack for Chinese: we need to toss in quotes for
						// multiple-character phrases since normalizeForSearch()
						// added spaces between them to make word breaks.
						$stripped = '"' . trim( $stripped ) . '"';
					}
					if ( $count > 0 ) {
						$searchon .= " OR ";
					}
					$searchon .= "$quote$stripped$quote$wildcard ";
					++$count;
				}
				if ( count( $strippedVariants ) > 1 ) {
					$searchon .= ')';
				}

				// Match individual terms or quoted phrase in result highlighting...
				// Note that variants will be introduced at a later stage for highlighting!
				$regexp = $this->regexTerm( $term, $wildcard );
				$this->searchTerms[] = $regexp;
			}

		} else {
			wfDebug( __METHOD__ . ": Can't understand search query '$filteredText'" );
		}

		$dbr = $this->dbProvider->getReplicaDatabase();
		$searchon = $dbr->addQuotes( $searchon );
		$field = $this->getIndexField( $fulltext );

		return " $field MATCH $searchon ";
	}

	private function regexTerm( string $string, string $wildcard ): string {
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
		if ( $type === self::CHARS_ALL ) {
			// " for phrase, * for wildcard
			$searchChars = "\"*" . $searchChars;
		}
		return $searchChars;
	}

	/** @inheritDoc */
	protected function doSearchTextInDB( $term ) {
		return $this->searchInternal( $term, true );
	}

	/** @inheritDoc */
	protected function doSearchTitleInDB( $term ) {
		return $this->searchInternal( $term, false );
	}

	protected function searchInternal( string $term, bool $fulltext ): ?SqlSearchResultSet {
		if ( !$this->fulltextSearchSupported() ) {
			return null;
		}

		$filteredTerm =
			$this->filter( MediaWikiServices::getInstance()->getContentLanguage()->lc( $term ) );

		$queryBuilder = $this->getQueryBuilder( $filteredTerm, $fulltext );
		$resultSet = $queryBuilder->caller( __METHOD__ )->fetchResultSet();

		$countBuilder = $this->getCountQueryBuilder( $filteredTerm, $fulltext );
		$total = (int)$countBuilder->caller( __METHOD__ )->fetchField();

		return new SqlSearchResultSet( $resultSet, $this->searchTerms, $total );
	}

	/**
	 * Add namespace conditions
	 */
	private function queryNamespaces( SelectQueryBuilder $queryBuilder ): void {
		if ( is_array( $this->namespaces ) ) {
			if ( count( $this->namespaces ) === 0 ) {
				$this->namespaces[] = NS_MAIN;
			}
			$queryBuilder->andWhere( [ 'page_namespace' => $this->namespaces ] );
		}
	}

	/**
	 * Construct the SQL query builder to do the search.
	 */
	private function getQueryBuilder( string $filteredTerm, bool $fulltext ): SelectQueryBuilder {
		$queryBuilder = $this->dbProvider->getReplicaDatabase()->newSelectQueryBuilder();

		$this->queryMain( $queryBuilder, $filteredTerm, $fulltext );
		$this->queryNamespaces( $queryBuilder );
		$queryBuilder->limit( $this->limit )
			->offset( $this->offset );

		return $queryBuilder;
	}

	/**
	 * Picks which field to index on, depending on what type of query.
	 */
	private function getIndexField( bool $fulltext ): string {
		return $fulltext ? 'si_text' : 'si_title';
	}

	/**
	 * Get the base part of the search query using a builder.
	 */
	private function queryMain( SelectQueryBuilder $queryBuilder, string $filteredTerm, bool $fulltext ): void {
		$match = $this->parseQuery( $filteredTerm, $fulltext );
		$queryBuilder->select( [ 'page_id', 'page_namespace', 'page_title' ] )
			->from( 'page' )
			->join( 'searchindex', null, 'page_id=searchindex.rowid' )
			->where( $match );
	}

	/**
	 * Build a count query for total matches
	 */
	private function getCountQueryBuilder( string $filteredTerm, bool $fulltext ): SelectQueryBuilder {
		$match = $this->parseQuery( $filteredTerm, $fulltext );
		$queryBuilder = $this->dbProvider->getReplicaDatabase()->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'page' )
			->join( 'searchindex', null, 'page_id=searchindex.rowid' )
			->where( $match );

		$this->queryNamespaces( $queryBuilder );
		return $queryBuilder;
	}

	/** @inheritDoc */
	public function update( $id, $title, $text ): void {
		if ( !$this->fulltextSearchSupported() ) {
			return;
		}
		// @todo find a method to do it in a single request,
		// couldn't do it so far due to typelessness of FTS3 tables.
		$dbw = $this->dbProvider->getPrimaryDatabase();
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'searchindex' )
			->where( [ 'rowid' => $id ] )
			->caller( __METHOD__ )->execute();
		$dbw->newInsertQueryBuilder()
			->insertInto( 'searchindex' )
			->row( [ 'rowid' => $id, 'si_title' => $title, 'si_text' => $text ] )
			->caller( __METHOD__ )->execute();
	}

	/** @inheritDoc */
	public function updateTitle( $id, $title ): void {
		if ( !$this->fulltextSearchSupported() ) {
			return;
		}

		$dbw = $this->dbProvider->getPrimaryDatabase();
		$dbw->newUpdateQueryBuilder()
			->update( 'searchindex' )
			->set( [ 'si_title' => $title ] )
			->where( [ 'rowid' => $id ] )
			->caller( __METHOD__ )->execute();
	}
}
