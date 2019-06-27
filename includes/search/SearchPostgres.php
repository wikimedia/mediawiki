<?php
/**
 * PostgreSQL search engine
 *
 * Copyright © 2006-2007 Greg Sabino Mullane <greg@turnstep.com>
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
use MediaWiki\Revision\SlotRecord;

/**
 * Search engine hook base class for Postgres
 * @ingroup Search
 */
class SearchPostgres extends SearchDatabase {
	/**
	 * Perform a full text search query via tsearch2 and return a result set.
	 * Currently searches a page's current title (page.page_title) and
	 * latest revision article text (pagecontent.old_text)
	 *
	 * @param string $term Raw search term
	 * @return SqlSearchResultSet
	 */
	protected function doSearchTitleInDB( $term ) {
		$q = $this->searchQuery( $term, 'titlevector', 'page_title' );
		$olderror = error_reporting( E_ERROR );
		$dbr = $this->lb->getConnectionRef( DB_REPLICA );
		$resultSet = $dbr->query( $q, 'SearchPostgres', true );
		error_reporting( $olderror );
		return new SqlSearchResultSet( $resultSet, $this->searchTerms );
	}

	protected function doSearchTextInDB( $term ) {
		$q = $this->searchQuery( $term, 'textvector', 'old_text' );
		$olderror = error_reporting( E_ERROR );
		$dbr = $this->lb->getConnectionRef( DB_REPLICA );
		$resultSet = $dbr->query( $q, 'SearchPostgres', true );
		error_reporting( $olderror );
		return new SqlSearchResultSet( $resultSet, $this->searchTerms );
	}

	/**
	 * Transform the user's search string into a better form for tsearch2
	 * Returns an SQL fragment consisting of quoted text to search for.
	 *
	 * @param string $term
	 *
	 * @return string
	 */
	private function parseQuery( $term ) {
		wfDebug( "parseQuery received: $term \n" );

		# # No backslashes allowed
		$term = preg_replace( '/\\\/', '', $term );

		# # Collapse parens into nearby words:
		$term = preg_replace( '/\s*\(\s*/', ' (', $term );
		$term = preg_replace( '/\s*\)\s*/', ') ', $term );

		# # Treat colons as word separators:
		$term = preg_replace( '/:/', ' ', $term );

		$searchstring = '';
		$m = [];
		if ( preg_match_all( '/([-!]?)(\S+)\s*/', $term, $m, PREG_SET_ORDER ) ) {
			foreach ( $m as $terms ) {
				if ( strlen( $terms[1] ) ) {
					$searchstring .= ' & !';
				}
				if ( strtolower( $terms[2] ) === 'and' ) {
					$searchstring .= ' & ';
				} elseif ( strtolower( $terms[2] ) === 'or' || $terms[2] === '|' ) {
					$searchstring .= ' | ';
				} elseif ( strtolower( $terms[2] ) === 'not' ) {
					$searchstring .= ' & !';
				} else {
					$searchstring .= " & $terms[2]";
				}
			}
		}

		# # Strip out leading junk
		$searchstring = preg_replace( '/^[\s\&\|]+/', '', $searchstring );

		# # Remove any doubled-up operators
		$searchstring = preg_replace( '/([\!\&\|]) +(?:[\&\|] +)+/', "$1 ", $searchstring );

		# # Remove any non-spaced operators (e.g. "Zounds!")
		$searchstring = preg_replace( '/([^ ])[\!\&\|]/', "$1", $searchstring );

		# # Remove any trailing whitespace or operators
		$searchstring = preg_replace( '/[\s\!\&\|]+$/', '', $searchstring );

		# # Remove unnecessary quotes around everything
		$searchstring = preg_replace( '/^[\'"](.*)[\'"]$/', "$1", $searchstring );

		# # Quote the whole thing
		$dbr = $this->lb->getConnectionRef( DB_REPLICA );
		$searchstring = $dbr->addQuotes( $searchstring );

		wfDebug( "parseQuery returned: $searchstring \n" );

		return $searchstring;
	}

	/**
	 * Construct the full SQL query to do the search.
	 * @param string $term
	 * @param string $fulltext
	 * @param string $colname
	 * @return string
	 */
	private function searchQuery( $term, $fulltext, $colname ) {
		# Get the SQL fragment for the given term
		$searchstring = $this->parseQuery( $term );

		# # We need a separate query here so gin does not complain about empty searches
		$sql = "SELECT to_tsquery($searchstring)";
		$dbr = $this->lb->getConnectionRef( DB_REPLICA );
		$res = $dbr->query( $sql );
		if ( !$res ) {
			# # TODO: Better output (example to catch: one 'two)
			die( "Sorry, that was not a valid search string. Please go back and try again" );
		}
		$top = $res->fetchRow()[0];

		$this->searchTerms = [];
		$slotRoleStore = MediaWikiServices::getInstance()->getSlotRoleStore();
		if ( $top === "" ) { # # e.g. if only stopwords are used XXX return something better
			$query = "SELECT page_id, page_namespace, page_title, 0 AS score " .
				"FROM page p, revision r, slots s, content c, pagecontent pc " .
				"WHERE p.page_latest = r.rev_id " .
				"AND s.slot_revision_id = r.rev_id " .
				"AND s.slot_role_id = " . $slotRoleStore->getId( SlotRecord::MAIN ) . " " .
				"AND c.content_id = s.slot_content_id " .
				"AND pc.old_id = substring( c.content_address from '^tt:([0-9]+)$' )::int " .
				"AND 1=0";
		} else {
			$m = [];
			if ( preg_match_all( "/'([^']+)'/", $top, $m, PREG_SET_ORDER ) ) {
				foreach ( $m as $terms ) {
					$this->searchTerms[$terms[1]] = $terms[1];
				}
			}

			$query = "SELECT page_id, page_namespace, page_title, " .
				"ts_rank($fulltext, to_tsquery($searchstring), 5) AS score " .
				"FROM page p, revision r, slots s, content c, pagecontent pc " .
				"WHERE p.page_latest = r.rev_id " .
				"AND s.slot_revision_id = r.rev_id " .
				"AND s.slot_role_id = " . $slotRoleStore->getId( SlotRecord::MAIN ) . " " .
				"AND c.content_id = s.slot_content_id " .
				"AND pc.old_id = substring( c.content_address from '^tt:([0-9]+)$' )::int " .
				"AND $fulltext @@ to_tsquery($searchstring)";
		}
		# # Namespaces - defaults to 0
		if ( !is_null( $this->namespaces ) ) { // null -> search all
			if ( count( $this->namespaces ) < 1 ) {
				$query .= ' AND page_namespace = 0';
			} else {
				$namespaces = $dbr->makeList( $this->namespaces );
				$query .= " AND page_namespace IN ($namespaces)";
			}
		}

		$query .= " ORDER BY score DESC, page_id DESC";

		$query .= $dbr->limitResult( '', $this->limit, $this->offset );

		wfDebug( "searchQuery returned: $query \n" );

		return $query;
	}

	# # Most of the work of these two functions are done automatically via triggers

	function update( $pageid, $title, $text ) {
		# # We don't want to index older revisions
		$slotRoleStore = MediaWikiServices::getInstance()->getSlotRoleStore();
		$sql = "UPDATE pagecontent SET textvector = NULL " .
			"WHERE textvector IS NOT NULL " .
			"AND old_id IN " .
			"(SELECT DISTINCT substring( c.content_address from '^tt:([0-9]+)$' )::int AS old_rev_text_id " .
			" FROM content c, slots s, revision r " .
			" WHERE r.rev_page = $pageid " .
			" AND s.slot_revision_id = r.rev_id " .
			" AND s.slot_role_id = " . $slotRoleStore->getId( SlotRecord::MAIN ) . " " .
			" AND c.content_id = s.slot_content_id " .
			" ORDER BY old_rev_text_id DESC OFFSET 1)";

		$dbw = $this->lb->getConnectionRef( DB_MASTER );
		$dbw->query( $sql );

		return true;
	}

	function updateTitle( $id, $title ) {
		return true;
	}
}
