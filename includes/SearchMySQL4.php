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
# 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
# http://www.gnu.org/copyleft/gpl.html

/**
 * Search engine hook for MySQL 4+
 * @package MediaWiki
 * @subpackage Search
 */

require_once( 'SearchEngine.php' );

class SearchMySQL4 extends SearchEngine {
	var $strictMatching = true;
	
	function SearchMySQL4( &$db ) {
		$this->db =& $db;
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
			wfDebug( "Match with /\b" . implode( '\b|\b', $this->searchTerms ) . "\b/\n" );
		} else {
			wfDebug( "Can't understand search query '{$this->filteredText}'\n" );
		}
		
		$searchon = $this->db->strencode( $searchon );
		$field = $this->getIndexField( $fulltext );
		return " MATCH($field) AGAINST('$searchon' IN BOOLEAN MODE) ";
	}

	function queryMain( $filteredTerm, $fulltext ) {
		$match = $this->parseQuery( $filteredTerm, $fulltext );
		$page = $this->db->tableName( 'page' );
		$text = $this->db->tableName( 'text' );
		$searchindex = $this->db->tableName( 'searchindex' );
		return 'SELECT page_id, page_namespace, page_title, old_flags, old_text ' .
			"FROM $page,$text,$searchindex " .
			'WHERE page_id=si_page AND page_latest=old_id AND ' . $match;
	}

        function update( $id, $title, $text ) {
                $dbw=& wfGetDB(DB_MASTER);
                $dbw->replace( 'searchindex', array(array('si_page')),
                        array(
                                'si_page' => $id,
                                'si_title' => $dbw->strencode($title),
                                'si_text' => $dbw->strencode( $text )
                        ), 'SearchMySQL4::update' );
        }

        function updateTitle($id,$title) {
                $dbw=& wfGetDB(DB_MASTER);
                $lowpri=$dbw->lowPriorityOption();
                $searchindex = $dbw->tableName( 'searchindex' );

                $sql = "UPDATE $lowpri $searchindex SET si_title='" .
                          $dbw->strencode( $title ) .
                          "' WHERE si_page={$id}";

                $dbw->query( $sql, "SearchMySQL4::updateTitle" );
        }

}

?>
