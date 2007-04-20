<?php
# Copyright (C) 2006-2007 Greg Sabino Mullane <greg@turnstep.com>
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
 * Search engine hook base class for Postgres
 * @addtogroup Search
 */
class SearchPostgres extends SearchEngine {

	function SearchPostgres( $db ) {
		$this->db = $db;
	}

	/**
	 * Perform a full text search query via tsearch2 and return a result set.
	 * Currently searches a page's current title (page.page_title) and 
	 * latest revision article text (pagecontent.old_text)
	 *
	 * @param string $term - Raw search term
	 * @return PostgresSearchResultSet
	 * @access public
	 */
	function searchTitle( $term ) {
		$resultSet = $this->db->resultObject( $this->db->query( $this->searchQuery( $term , 'titlevector', 'page_title' )));
		return new PostgresSearchResultSet( $resultSet, $this->searchTerms );
	}
	function searchText( $term ) {
		$resultSet = $this->db->resultObject( $this->db->query( $this->searchQuery( $term, 'textvector', 'old_text' )));
		return new PostgresSearchResultSet( $resultSet, $this->searchTerms );
	}


	/*
	 * Transform the user's search string into a better form for tsearch2
	*/
	function parseQuery( $term ) {

		wfDebug( "parseQuery received: $term" );

		## No backslashes allowed
		$term = preg_replace('/\\\/', '', $term);

		## Collapse parens into nearby words:
		$term = preg_replace('/\s*\(\s*/', ' (', $term);
		$term = preg_replace('/\s*\)\s*/', ') ', $term);

		## Treat colons as word separators:
		$term = preg_replace('/:/', ' ', $term);

		$this->searchTerms = array();
		$m = array();
		$searchstring = '';
		if( preg_match_all('/([-!]?)(\S+)\s*/', $term, $m, PREG_SET_ORDER ) ) {
			foreach( $m as $terms ) {
				if (strlen($terms[1])) {
					$searchstring .= ' & !';
				}
				if (strtolower($terms[2]) === 'and') {
					$searchstring .= ' & ';
				}
				else if (strtolower($terms[2]) === 'or' or $terms[2] === '|') {
					$searchstring .= ' | ';
				}
				else if (strtolower($terms[2]) === 'not') {
					$searchstring .= ' & !';
				}
				else {
					$searchstring .= " & $terms[2]";
					$safeterm = preg_replace('/\W+/', '', $terms[2]);
					$this->searchTerms[$safeterm] = $safeterm;
				}
			}
		}

		## Strip out leading junk
		$searchstring = preg_replace('/^[\s\&\|]+/', '', $searchstring);

		## Remove any doubled-up operators
		$searchstring = preg_replace('/([\!\&\|]) +(?:[\&\|] +)+/', "$1 ", $searchstring);

		## Remove any non-spaced operators (e.g. "Zounds!")
		$searchstring = preg_replace('/([^ ])[\!\&\|]/', "$1", $searchstring);

		## Remove any trailing whitespace or operators
		$searchstring = preg_replace('/[\s\!\&\|]+$/', '', $searchstring);

		## Remove unnecessary quotes around everything
		$searchstring = preg_replace('/^[\'"](.*)[\'"]$/', "$1", $searchstring);

		## Quote the whole thing
		$searchstring = $this->db->addQuotes($searchstring);

		wfDebug( "parseQuery returned: $searchstring" );

		return $searchstring;

	}

	/**
	 * Construct the full SQL query to do the search.
	 * @param string $filteredTerm
	 * @param string $fulltext
	 * @private
	 */
	function searchQuery( $term, $fulltext, $colname ) {

		$searchstring = $this->parseQuery( $term );

		## We need a separate query here so gin does not complain about empty searches
		$SQL = "SELECT to_tsquery('default',$searchstring)";
		$res = $this->db->doQuery($SQL);
		if (!$res) {
			## TODO: Better output (example to catch: one 'two)
			die ("Sorry, that was not a valid search string. Please go back and try again");
		}
		$top = pg_fetch_result($res,0,0);

		if ($top === "") { ## e.g. if only stopwords are used XXX return something better
			$query = "SELECT page_id, page_namespace, page_title, 0 AS score ".
				"FROM page p, revision r, pagecontent c WHERE p.page_latest = r.rev_id " .
				"AND r.rev_text_id = c.old_id AND 1=0";
		}
		else {
			$query = "SELECT page_id, page_namespace, page_title, ".
			"rank($fulltext, to_tsquery('default',$searchstring),5) AS score ".
			"FROM page p, revision r, pagecontent c WHERE p.page_latest = r.rev_id " .
			"AND r.rev_text_id = c.old_id AND $fulltext @@ to_tsquery('default',$searchstring)";
		}

		## Redirects
		if (! $this->showRedirects)
			$query .= ' AND page_is_redirect = 0'; ## IS FALSE

		## Namespaces - defaults to 0
		if ( count($this->namespaces) < 1)
			$query .= ' AND page_namespace = 0';
		else {
			$namespaces = implode( ',', $this->namespaces );
			$query .= " AND page_namespace IN ($namespaces)";
		}

		$query .= " ORDER BY score DESC, page_id DESC";

		$query .= $this->db->limitResult( '', $this->limit, $this->offset );

		wfDebug( "searchQuery returned: $query" );

		return $query;
	}

	## Most of the work of these two functions are done automatically via triggers

	function update( $pageid, $title, $text ) {
		## We don't want to index older revisions
		$SQL = "UPDATE pagecontent SET textvector = NULL WHERE old_id = ".
				"(SELECT rev_text_id FROM revision WHERE rev_page = $pageid ".
				"ORDER BY rev_text_id DESC LIMIT 1 OFFSET 1)";
		$this->db->doQuery($SQL);
		return true;
	}

	function updateTitle( $id, $title ) {
		return true;
	}

} ## end of the SearchPostgres class

/**
 * @addtogroup Search
 */
class PostgresSearchResult extends SearchResult {
	function PostgresSearchResult( $row ) {
		$this->mTitle = Title::makeTitle( $row->page_namespace, $row->page_title );
		$this->score = $row->score;
	}
	function getScore() {
		return $this->score;
	}
}

/**
 * @addtogroup Search
 */
class PostgresSearchResultSet extends SearchResultSet {
	function PostgresSearchResultSet( $resultSet, $terms ) {
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
		if( $row === false ) {
			return false;
		} else {
			return new PostgresSearchResult( $row );
		}
	}
}


?>
