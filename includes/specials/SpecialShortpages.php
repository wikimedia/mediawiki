<?php
/**
 * Implements Special:Shortpages
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
 * SpecialShortpages extends QueryPage. It is used to return the shortest
 * pages in the database.
 *
 * @ingroup SpecialPage
 */
class ShortPagesPage extends QueryPage {

	function getName() {
		return 'Shortpages';
	}

	/**
	 * This query is indexed as of 1.5
	 */
	function isExpensive() {
		return true;
	}

	function isSyndicated() {
		return false;
	}

	function getSQL() {
		global $wgContentNamespaces;

		$dbr = wfGetDB( DB_SLAVE );
		$page = $dbr->tableName( 'page' );
		$name = $dbr->addQuotes( $this->getName() );

		$forceindex = $dbr->useIndexClause("page_len");

		if ($wgContentNamespaces)
			$nsclause = "page_namespace IN (" . $dbr->makeList($wgContentNamespaces) . ")";
		else
			$nsclause = "page_namespace = " . NS_MAIN;

		return
			"SELECT $name as type,
				page_namespace as namespace,
			        page_title as title,
			        page_len AS value
			FROM $page $forceindex
			WHERE $nsclause AND page_is_redirect=0";
	}

	function preprocessResults( $db, $res ) {
		# There's no point doing a batch check if we aren't caching results;
		# the page must exist for it to have been pulled out of the table
		if( $this->isCached() ) {
			$batch = new LinkBatch();
			while( $row = $db->fetchObject( $res ) )
				$batch->add( $row->namespace, $row->title );
			$batch->execute();
			if( $db->numRows( $res ) > 0 )
				$db->dataSeek( $res, 0 );
		}
	}

	function sortDescending() {
		return false;
	}

	function formatResult( $skin, $result ) {
		global $wgLang, $wgContLang;
		$dm = $wgContLang->getDirMark();

		$title = Title::makeTitleSafe( $result->namespace, $result->title );
		if ( !$title ) {
			return '<!-- Invalid title ' .  htmlspecialchars( "{$result->namespace}:{$result->title}" ). '-->';
		}
		$hlink = $skin->linkKnown(
			$title,
			wfMsgHtml( 'hist' ),
			array(),
			array( 'action' => 'history' )
		);
		$plink = $this->isCached()
					? $skin->link( $title )
					: $skin->linkKnown( $title );
		$size = wfMsgExt( 'nbytes', array( 'parsemag', 'escape' ), $wgLang->formatNum( htmlspecialchars( $result->value ) ) );

		return $title->exists()
				? "({$hlink}) {$dm}{$plink} {$dm}[{$size}]"
				: "<del>({$hlink}) {$dm}{$plink} {$dm}[{$size}]</del>";
	}
}

/**
 * constructor
 */
function wfSpecialShortpages() {
	list( $limit, $offset ) = wfCheckLimits();

	$spp = new ShortPagesPage();

	return $spp->doQuery( $offset, $limit );
}
