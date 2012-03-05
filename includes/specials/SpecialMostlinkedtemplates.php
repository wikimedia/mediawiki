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
class SpecialMostlinkedtemplates extends QueryPage {

	/**
	 * Name of the report
	 *
	 * @return String
	 */
	public function getName() {
		return 'Mostlinkedtemplates';
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

	/**
	 * Generate SQL for the report
	 *
	 * @return String
	 */
	public function getSql() {
		$dbr = wfGetDB( DB_SLAVE );
		$templatelinks = $dbr->tableName( 'templatelinks' );
		$name = $dbr->addQuotes( $this->getName() );
		return "SELECT {$name} AS type,
			" . NS_TEMPLATE . " AS namespace,
			tl_title AS title,
			COUNT(*) AS value
			FROM {$templatelinks}
			WHERE tl_namespace = " . NS_TEMPLATE . "
			GROUP BY tl_title";
	}

	/**
	 * Pre-cache page existence to speed up link generation
	 *
	 * @param $db Database connection
	 * @param $res ResultWrapper
	 */
	public function preprocessResults( $db, $res ) {
		$batch = new LinkBatch();
		while( $row = $db->fetchObject( $res ) ) {
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
		$title = Title::makeTitleSafe( $result->namespace, $result->title );

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
		$label = wfMsgExt( 'nlinks', array( 'parsemag', 'escape' ),
		$wgLang->formatNum( $result->value ) );
		return $skin->link( $wlh, $label, array(), array( 'target' => $title->getPrefixedText() ) );
	}
}

/**
 * Execution function
 *
 * @param $par Mixed: parameters passed to the page
 */
function wfSpecialMostlinkedtemplates( $par = false ) {
	list( $limit, $offset ) = wfCheckLimits();
	$mlt = new SpecialMostlinkedtemplates();
	$mlt->doQuery( $offset, $limit );
}
