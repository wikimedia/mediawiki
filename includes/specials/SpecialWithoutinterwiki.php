<?php
/**
 * Implements Special:Withoutinterwiki
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
 * Special page lists pages without language links
 *
 * @ingroup SpecialPage
 */
class WithoutInterwikiPage extends PageQueryPage {
	private $prefix = '';

	function getName() {
		return 'Withoutinterwiki';
	}

	function getPageHeader() {
		global $wgScript, $wgMiserMode;

		# Do not show useless input form if wiki is running in misermode
		if( $wgMiserMode ) {
			return '';
		}

		$prefix = $this->prefix;
		$t = SpecialPage::getTitleFor( $this->getName() );

		return 	Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript ) ) .
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, wfMsg( 'withoutinterwiki-legend' ) ) .
			Xml::hidden( 'title', $t->getPrefixedText() ) .
			Xml::inputLabel( wfMsg( 'allpagesprefix' ), 'prefix', 'wiprefix', 20, $prefix ) . ' ' .
			Xml::submitButton( wfMsg( 'withoutinterwiki-submit' ) ) .
			Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' );
	}

	function sortDescending() {
		return false;
	}

	function isExpensive() {
		return true;
	}

	function isSyndicated() {
		return false;
	}

	function getSQL() {
		$dbr = wfGetDB( DB_SLAVE );
		list( $page, $langlinks ) = $dbr->tableNamesN( 'page', 'langlinks' );
		$prefix = $this->prefix ? 'AND page_title' . $dbr->buildLike( $this->prefix , $dbr->anyString() ) : '';
		return
		  "SELECT 'Withoutinterwiki'  AS type,
		          page_namespace AS namespace,
		          page_title     AS title,
		          page_title     AS value
		     FROM $page
		LEFT JOIN $langlinks
		       ON ll_from = page_id
		    WHERE ll_title IS NULL
		      AND page_namespace=" . NS_MAIN . "
		      AND page_is_redirect = 0
			  {$prefix}";
	}

	function setPrefix( $prefix = '' ) {
		$this->prefix = $prefix;
	}

}

function wfSpecialWithoutinterwiki() {
	global $wgRequest;
	list( $limit, $offset ) = wfCheckLimits();
	// Only searching the mainspace anyway
	$prefix = Title::capitalize( $wgRequest->getVal( 'prefix' ), NS_MAIN );
	$wip = new WithoutInterwikiPage();
	$wip->setPrefix( $prefix );
	$wip->doQuery( $offset, $limit );
}
