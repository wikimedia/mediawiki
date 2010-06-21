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
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */
 
/**
 * @file
 * @ingroup SpecialPage
 */

/**
 * A special page that displays a list of pages that are not on anyones watchlist.
 * Implements Special:Unwatchedpages
 *
 * @ingroup SpecialPage
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */
class UnwatchedpagesPage extends QueryPage {

	function getName() { return 'Unwatchedpages'; }
	function isExpensive() { return true; }
	function isSyndicated() { return false; }

	function getSQL() {
		$dbr = wfGetDB( DB_SLAVE );
		list( $page, $watchlist ) = $dbr->tableNamesN( 'page', 'watchlist' );
		$mwns = NS_MEDIAWIKI;
		return
			"
			SELECT
				'Unwatchedpages' as type,
				page_namespace as namespace,
				page_title as title,
				page_namespace as value
			FROM $page
			LEFT JOIN $watchlist ON wl_namespace = page_namespace AND page_title = wl_title
			WHERE wl_title IS NULL AND page_is_redirect = 0 AND page_namespace<>$mwns
			";
	}

	function sortDescending() { return false; }

	function formatResult( $skin, $result ) {
		global $wgContLang;

		$nt = Title::makeTitle( $result->namespace, $result->title );
		$text = $wgContLang->convert( $nt->getPrefixedText() );

		$plink = $skin->linkKnown(
			$nt,
			htmlspecialchars( $text )
		);
		$wlink = $skin->linkKnown(
			$nt,
			wfMsgHtml( 'watch' ),
			array(),
			array( 'action' => 'watch' )
		);

		return wfSpecialList( $plink, $wlink );
	}
}

/**
 * constructor
 */
function wfSpecialUnwatchedpages() {
	global $wgUser, $wgOut;

	if ( ! $wgUser->isAllowed( 'unwatchedpages' ) )
		return $wgOut->permissionRequired( 'unwatchedpages' );

	list( $limit, $offset ) = wfCheckLimits();

	$wpp = new UnwatchedpagesPage();

	$wpp->doQuery( $offset, $limit );
}
