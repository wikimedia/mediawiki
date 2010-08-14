<?php
/**
 * Implements Special:Brokenredirects
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
 * @ingroup SpecialPage
 */

/**
 * A special page listing redirects tonon existent page. Those should be
 * fixed to point to an existing page.
 *
 * @ingroup SpecialPage
 */
class BrokenRedirectsPage extends PageQueryPage {
	var $targets = array();

	function getName() {
		return 'BrokenRedirects';
	}

	function isExpensive( ) { return true; }
	function isSyndicated() { return false; }

	function getPageHeader( ) {
		return wfMsgExt( 'brokenredirectstext', array( 'parse' ) );
	}

	function getSQL() {
		$dbr = wfGetDB( DB_SLAVE );
		list( $page, $redirect ) = $dbr->tableNamesN( 'page', 'redirect' );

		$sql = "SELECT 'BrokenRedirects'  AS type,
		                p1.page_namespace AS namespace,
		                p1.page_title     AS title,
		                rd_namespace,
		                rd_title
		           FROM $redirect AS rd
		      JOIN $page p1 ON (rd.rd_from=p1.page_id)
		      LEFT JOIN $page AS p2 ON (rd_namespace=p2.page_namespace AND rd_title=p2.page_title )
				  WHERE rd_namespace >= 0
				    AND p2.page_namespace IS NULL";
		return $sql;
	}

	function getOrder() {
		return '';
	}

	function formatResult( $skin, $result ) {
		global $wgUser, $wgContLang, $wgLang;

		$fromObj = Title::makeTitle( $result->namespace, $result->title );
		if ( isset( $result->rd_title ) ) {
			$toObj = Title::makeTitle( $result->rd_namespace, $result->rd_title );
		} else {
			$blinks = $fromObj->getBrokenLinksFrom(); # TODO: check for redirect, not for links
			if ( $blinks ) {
				$toObj = $blinks[0];
			} else {
				$toObj = false;
			}
		}

		// $toObj may very easily be false if the $result list is cached
		if ( !is_object( $toObj ) ) {
			return '<del>' . $skin->link( $fromObj ) . '</del>';
		}

		$from = $skin->linkKnown(
			$fromObj,
			null,
			array(),
			array( 'redirect' => 'no' )
		);
		$links = array();
		$links[] = $skin->linkKnown(
			$fromObj,
			wfMsgHtml( 'brokenredirects-edit' ),
			array(),
			array( 'action' => 'edit' )
		);
		$to   = $skin->link(
			$toObj,
			null,
			array(),
			array(),
			array( 'broken' )
		);
		$arr = $wgContLang->getArrow();

		$out = $from . wfMsg( 'word-separator' );

		if( $wgUser->isAllowed( 'delete' ) ) {
			$links[] = $skin->linkKnown(
				$fromObj,
				wfMsgHtml( 'brokenredirects-delete' ),
				array(),
				array( 'action' => 'delete' )
			);
		}

		$out .= wfMsg( 'parentheses', $wgLang->pipeList( $links ) );
		$out .= " {$arr} {$to}";
		return $out;
	}
}

/**
 * constructor
 */
function wfSpecialBrokenRedirects() {
	list( $limit, $offset ) = wfCheckLimits();

	$sbr = new BrokenRedirectsPage();

	return $sbr->doQuery( $offset, $limit );
}
