<?php
/**
 * Implements Special:Mostlinkedtemplates
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
 * @author Rob Church <robchur@gmail.com>
 */

/**
 * Special page lists templates with a large number of
 * transclusion links, i.e. "most used" templates
 *
 * @ingroup SpecialPage
 */
class MostlinkedTemplatesPage extends QueryPage {

	function __construct( $name = 'Mostlinkedtemplates' ) {
		parent::__construct( $name );
	}

	/**
	 * Is this report expensive, i.e should it be cached?
	 *
	 * @return Boolean
	 */
	public function isExpensive() {
		return true;
	}

	/**
	 * Is there a feed available?
	 *
	 * @return Boolean
	 */
	public function isSyndicated() {
		return false;
	}

	/**
	 * Sort the results in descending order?
	 *
	 * @return Boolean
	 */
	public function sortDescending() {
		return true;
	}

	public function getQueryInfo() {
		return array (
			'tables' => array ( 'templatelinks' ),
			'fields' => array ( 'tl_namespace AS namespace',
					'tl_title AS title',
					'COUNT(*) AS value' ),
			'conds' => array ( 'tl_namespace' => NS_TEMPLATE ),
			'options' => array( 'GROUP BY' => 'tl_namespace, tl_title' )
		);
	}

	/**
	 * Pre-cache page existence to speed up link generation
	 *
	 * @param $db Database connection
	 * @param $res ResultWrapper
	 */
	public function preprocessResults( $db, $res ) {
		$batch = new LinkBatch();
		foreach ( $res as $row ) {
			$batch->add( $row->namespace, $row->title );
		}
		$batch->execute();
		if( $db->numRows( $res ) > 0 )
			$db->dataSeek( $res, 0 );
	}

	/**
	 * Format a result row
	 *
	 * @param $skin Skin to use for UI elements
	 * @param $result Result row
	 * @return String
	 */
	public function formatResult( $skin, $result ) {
		$title = Title::makeTitle( $result->namespace, $result->title );

		return wfSpecialList(
			$skin->link( $title ),
			$this->makeWlhLink( $title, $skin, $result )
		);
	}

	/**
	 * Make a "what links here" link for a given title
	 *
	 * @param $title Title to make the link for
	 * @param $skin Skin to use
	 * @param $result Result row
	 * @return String
	 */
	private function makeWlhLink( $title, $skin, $result ) {
		global $wgLang;
		$wlh = SpecialPage::getTitleFor( 'Whatlinkshere' );
		$label = wfMsgExt( 'ntransclusions', array( 'parsemag', 'escape' ),
			$wgLang->formatNum( $result->value ) );
		return $skin->link( $wlh, $label, array(), array( 'target' => $title->getPrefixedText() ) );
	}
}

