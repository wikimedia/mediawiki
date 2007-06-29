<?php
# Copyright (C) 2004 Brion Vibber <brion@pobox.com>, Domas Mituzas <domas.mituzas@gmail.com>
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
 * Search engine hook for PostgreSQL / Tsearch2
 * @addtogroup Search
 */

/**
 * @todo document
 * @addtogroup Search
 */
class SearchTsearch2 extends SearchEngine {
	var $strictMatching = false;

	function SearchTsearch2( &$db ) {
		$this->db =& $db;
		$this->mRanking = true;
	}

	function getIndexField( $fulltext ) {
		return $fulltext ? 'si_text' : 'si_title';
	}

	function parseQuery( $filteredText, $fulltext ) {
		global $wgContLang;
		$lc = SearchEngine::legalSearchChars();
		$searchon = '';
		$this->searchTerms = array();

		# FIXME: This doesn't handle parenthetical expressions.
		$m = array();
		if( preg_match_all( '/([-+<>~]?)(([' . $lc . ']+)(\*?)|"[^"]*")/',
			  $filteredText, $m, PREG_SET_ORDER ) ) {
			foreach( $m as $terms ) {
				if( $searchon !== '' ) $searchon .= ' ';
				if( $this->strictMatching && ($terms[1] == '') ) {
					$terms[1] = '+';
				}
				$searchon .= $terms[1] . $wgContLang->stripForSearch( $terms[2] );
				if( !empty( $terms[3] ) ) {
					$regexp = preg_quote( $terms[3], '/' );
					if( $terms[4] ) $regexp .= "[0-9A-Za-z_]+";
				} else {
					$regexp = preg_quote( str_replace( '"', '', $terms[2] ), '/' );
				}
				$this->searchTerms[] = $regexp;
			}
			wfDebug( "Would search with '$searchon'\n" );
			wfDebug( 'Match with /\b' . implode( '\b|\b', $this->searchTerms ) . "\b/\n" );
		} else {
			wfDebug( "Can't understand search query '{$this->filteredText}'\n" );
		}

		$searchon = preg_replace('/(\s+)/','&',$searchon);
		$searchon = $this->db->strencode( $searchon );
		return $searchon;
	}

	function queryRanking($filteredTerm, $fulltext) {
		$field = $this->getIndexField( $fulltext );
		$searchon = $this->parseQuery($filteredTerm,$fulltext);
		if ($this->mRanking)
			return " ORDER BY rank($field,to_tsquery('$searchon')) DESC";
		else
			return "";
	}


	function queryMain( $filteredTerm, $fulltext ) {
		$match = $this->parseQuery( $filteredTerm, $fulltext );
		$field = $this->getIndexField( $fulltext );
		$cur = $this->db->tableName( 'cur' );
		$searchindex = $this->db->tableName( 'searchindex' );
		return 'SELECT cur_id, cur_namespace, cur_title, cur_text ' .
			"FROM $cur,$searchindex " .
			'WHERE cur_id=si_page AND ' .
			" $field @@ to_tsquery ('$match') " ;
	}

	function update( $id, $title, $text ) {
		$dbw = wfGetDB(DB_MASTER);
		$searchindex = $dbw->tableName( 'searchindex' );
		$sql = "DELETE FROM $searchindex WHERE si_page={$id}";
		$dbw->query($sql,"SearchTsearch2:update");
		$sql = "INSERT INTO $searchindex (si_page,si_title,si_text) ".
			" VALUES ( $id, to_tsvector('".
				$dbw->strencode($title).
				"'),to_tsvector('".
				$dbw->strencode( $text)."')) ";
		$dbw->query($sql,"SearchTsearch2:update");
	}

	function updateTitle($id,$title) {
		$dbw = wfGetDB(DB_MASTER);
		$searchindex = $dbw->tableName( 'searchindex' );
		$sql = "UPDATE $searchindex SET si_title=to_tsvector('" .
				$dbw->strencode( $title ) .
				"') WHERE si_page={$id}";

		$dbw->query( $sql, "SearchMySQL4::updateTitle" );
	}

}


