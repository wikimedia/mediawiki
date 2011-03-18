<?php
/**
 * Implements Special:Mostlinked
 *
 * Copyright © 2005 Ævar Arnfjörð Bjarmason, 2006 Rob Church
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
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @author Rob Church <robchur@gmail.com>
 */

/**
 * A special page to show pages ordered by the number of pages linking to them.
 *
 * @ingroup SpecialPage
 */
class MostlinkedPage extends QueryPage {

	function __construct( $name = 'Mostlinked' ) {
		parent::__construct( $name );
	}

	function isExpensive() { return true; }
	function isSyndicated() { return false; }

	function getQueryInfo() {
		return array (
			'tables' => array ( 'pagelinks', 'page' ),
			'fields' => array ( 'pl_namespace AS namespace',
					'pl_title AS title',
					'COUNT(*) AS value',
					'page_namespace' ),
			'options' => array ( 'HAVING' => 'COUNT(*) > 1',
				'GROUP BY' => 'pl_namespace, pl_title, '.
						'page_namespace' ),
			'join_conds' => array ( 'page' => array ( 'LEFT JOIN',
					array ( 'page_namespace = pl_namespace',
						'page_title = pl_title' ) ) )
		);
	}

	/**
	 * Pre-fill the link cache
	 */
	function preprocessResults( $db, $res ) {
		if( $db->numRows( $res ) > 0 ) {
			$linkBatch = new LinkBatch();
			foreach ( $res as $row ) {
				$linkBatch->add( $row->namespace, $row->title );
			}
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
