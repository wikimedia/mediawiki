<?php
/**
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
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

/**
 * Special page for listing the articles with the fewest revisions.
 *
 * @file
 * @ingroup SpecialPage
 * @author Martin Drashkov
 */
class FewestrevisionsPage extends QueryPage {

	function getName() {
		return 'Fewestrevisions';
	}

	function isExpensive() {
		return true;
	}

	function isSyndicated() {
		return false;
	}

	function getSql() {
		$dbr = wfGetDB( DB_SLAVE );
		list( $revision, $page ) = $dbr->tableNamesN( 'revision', 'page' );

		return "SELECT 'Fewestrevisions' as type,
				page_namespace as namespace,
				page_title as title,
				page_is_redirect as redirect,
				COUNT(*) as value
			FROM $revision
			JOIN $page ON page_id = rev_page
			WHERE page_namespace = " . NS_MAIN . "
			GROUP BY page_namespace, page_title, page_is_redirect
			HAVING COUNT(*) > 1";
			// ^^^ This was probably here to weed out redirects.
			// Since we mark them as such now, it might be
			// useful to remove this. People _do_ create pages
			// and never revise them, they aren't necessarily
			// redirects.
	}

	function sortDescending() {
		return false;
	}

	function formatResult( $skin, $result ) {
		global $wgLang, $wgContLang;

		$nt = Title::makeTitleSafe( $result->namespace, $result->title );
		if( !$nt ) {
			return '<!-- bad title -->';
		}

		$text = $wgContLang->convert( $nt->getPrefixedText() );

		$plink = $skin->linkKnown(
			$nt,
			$text
		);

		$nl = wfMsgExt( 'nrevisions', array( 'parsemag', 'escape' ),
			$wgLang->formatNum( $result->value ) );
		$redirect = $result->redirect ? ' - ' . wfMsgHtml( 'isredirect' ) : '';
		$nlink = $skin->linkKnown(
			$nt,
			$nl,
			array(),
			array( 'action' => 'history' )
		) . $redirect;

		return wfSpecialList( $plink, $nlink );
	}
}

function wfSpecialFewestrevisions() {
	list( $limit, $offset ) = wfCheckLimits();
	$frp = new FewestrevisionsPage();
	$frp->doQuery( $offset, $limit );
}
