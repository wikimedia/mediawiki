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
	private $namespace = NS_MAIN;

	function __construct( $name = 'Withoutinterwiki' ) {
		parent::__construct( $name );
	}

	function execute( $par ) {
		print_r($par);
		$this->namespace = $this->getRequest()->getVal( 'namespace', NS_MAIN );
		if ( !MWNamespace::exists( $this->namespace ) )
			$this->namespace = NS_MAIN;

		$this->prefix = Title::capitalize(
			$this->getRequest()->getVal( 'prefix', $par ), $this->namespace );
		parent::execute( $par );
	}

	function getPageHeader() {
		global $wgScript;

		# Do not show useless input form if special page is cached
		if( $this->isCached() ) {
			return '';
		}

		return Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript ) ) .
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, $this->msg( 'withoutinterwiki-legend' )->text() ) .
			Html::hidden( 'title', $this->getTitle()->getPrefixedText() ) .
			Xml::buildForm( array(
				'allpagesprefix' => Xml::input( 'prefix', 20, $this->prefix, array( 'id' => 'wiprefix' ) ),
				'namespace' => Html::namespaceSelector(
					array( 'selected' => $this->namespace ),
					array( 'name' => 'namespace', 'id' => 'wiprefix' ) )
				) , 'withoutinterwiki-submit' ).
			Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' );
	}

	function sortDescending() {
		return false;
	}

	function getOrderFields() {
		return array( 'page_namespace', 'page_title' );
	}

	function isExpensive() {
		return true;
	}

	function isSyndicated() {
		return false;
	}

	function getQueryInfo() {
		$query = array (
			'tables' => array ( 'page', 'langlinks' ),
			'fields' => array ( 'namespace' => 'page_namespace',
					'title' => 'page_title',
					'value' => 'page_title' ),
			'conds' => array ( 'll_title IS NULL',
					'page_namespace' => $this->namespace,
					'page_is_redirect' => 0 ),
			'join_conds' => array ( 'langlinks' => array (
					'LEFT JOIN', 'll_from = page_id' ) )
		);
		if ( $this->prefix ) {
			$dbr = wfGetDB( DB_SLAVE );
			$query['conds'][] = 'page_title ' . $dbr->buildLike( $this->prefix, $dbr->anyString() );
		}
		return $query;
	}
}
