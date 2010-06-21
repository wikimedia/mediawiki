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
 * A special page to show pages ordered by the number of pages linking to them.
 * Implements Special:Mostlinked
 *
 * @ingroup SpecialPage
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @author Rob Church <robchur@gmail.com>
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason
 * @copyright © 2006 Rob Church
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */
class MostlinkedPage extends QueryPage {

	function getName() { return 'Mostlinked'; }
	function isExpensive() { return true; }
	function isSyndicated() { return false; }

	function getSQL() {
		global $wgMiserMode;

		$dbr = wfGetDB( DB_SLAVE );

		# In miser mode, reduce the query cost by adding a threshold for large wikis
		if ( $wgMiserMode ) {
			$numPages = SiteStats::pages();
			if ( $numPages > 10000 ) {
				$cutoff = 100;
			} elseif ( $numPages > 100 ) {
				$cutoff = intval( sqrt( $numPages ) );
			} else {
				$cutoff = 1;
			}
		} else {
			$cutoff = 1;
		}

		list( $pagelinks, $page ) = $dbr->tableNamesN( 'pagelinks', 'page' );
		return
			"SELECT 'Mostlinked' AS type,
				pl_namespace AS namespace,
				pl_title AS title,
				COUNT(*) AS value
			FROM $pagelinks
			LEFT JOIN $page ON pl_namespace=page_namespace AND pl_title=page_title
			GROUP BY pl_namespace, pl_title
			HAVING COUNT(*) > $cutoff";
	}

	/**
	 * Pre-fill the link cache
	 */
	function preprocessResults( $db, $res ) {
		if( $db->numRows( $res ) > 0 ) {
			$linkBatch = new LinkBatch();
			while( $row = $db->fetchObject( $res ) )
				$linkBatch->add( $row->namespace, $row->title );
			$db->dataSeek( $res, 0 );
			$linkBatch->execute();
		}
	}

	/**
	 * Make a link to "what links here" for the specified title
	 *
	 * @param $title Title being queried
	 * @param $caption String: text to display on the link
	 * @param $skin Skin to use
	 * @return String
	 */
	function makeWlhLink( &$title, $caption, &$skin ) {
		$wlh = SpecialPage::getTitleFor( 'Whatlinkshere', $title->getPrefixedDBkey() );
		return $skin->linkKnown( $wlh, $caption );
	}

	/**
	 * Make links to the page corresponding to the item, and the "what links here" page for it
	 *
	 * @param $skin Skin to be used
	 * @param $result Result row
	 * @return string
	 */
	function formatResult( $skin, $result ) {
		global $wgLang;
		$title = Title::makeTitleSafe( $result->namespace, $result->title );
		if ( !$title ) {
			return '<!-- ' . htmlspecialchars( "Invalid title: [[$title]]" ) . ' -->';
		}
		$link = $skin->link( $title );
		$wlh = $this->makeWlhLink( $title,
			wfMsgExt( 'nlinks', array( 'parsemag', 'escape'),
				$wgLang->formatNum( $result->value ) ), $skin );
		return wfSpecialList( $link, $wlh );
	}
}

/**
 * constructor
 */
function wfSpecialMostlinked() {
	list( $limit, $offset ) = wfCheckLimits();

	$wpp = new MostlinkedPage();

	$wpp->doQuery( $offset, $limit );
}
