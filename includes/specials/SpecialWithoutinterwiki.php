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
	/**
	 * Contains three values:
	 *  - NS index
	 *  - Title for DB (underscored)
	 *  - Title for View (spaced)
	 *
	 * @var array $prefix
	 */
	private $prefix = array();

	function __construct( $name = 'Withoutinterwiki' ) {
		parent::__construct( $name );
	}

	function execute( $par ) {
		$this->prefix = $this->getNamespaceKeyAndText(
			$this->getRequest()->getInt( 'namespace', NS_MAIN ),
			$this->getRequest()->getVal( 'prefix', $par ) );

		parent::execute( $par );
	}

	function getPageHeader() {
		# Do not show useless input form if special page is cached
		if( $this->isCached() ) {
			return '';
		}

		return Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript ) ) .
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, $this->msg( 'withoutinterwiki-legend' )->text() ) .
			Html::hidden( 'title', $this->getTitle()->getPrefixedText() ) .
			Xml::buildForm( array(
				'allpagesprefix' => Xml::input( 'prefix', 20, $this->prefix[2], array( 'id' => 'wiprefix' ) ),
				'namespace' => Html::namespaceSelector(
					array( 'selected' => $this->prefix[0] ),
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
		$ns = MWNamespace::getContentNamespaces();
		$title = '';
		if ( $this->prefix && $this->prefix[1] ) {
			$title = $this->prefix[1];
		} elseif( $this->prefix && !MWNamespace::equals( $this->prefix[0], NS_MAIN )) {
			$ns = $this->prefix[0];
		}

		$query = array (
			'tables' => array ( 'page', 'langlinks' ),
			'fields' => array ( 'namespace' => 'page_namespace',
					'title' => 'page_title',
					'value' => 'page_title' ),
			'conds' => array ( 'll_title IS NULL',
					'page_namespace' => $ns,
					'page_is_redirect' => 0 ),
			'join_conds' => array ( 'langlinks' => array (
					'LEFT JOIN', 'll_from = page_id' ) )
		);
		if ( $title ) {
			$dbr = wfGetDB( DB_SLAVE );
			$query['conds'][] = 'page_title ' . $dbr->buildLike( $title, $dbr->anyString() );
		}
		return $query;
	}

	/**
	 * @param $ns Integer: the namespace of the article
	 * @param $text String: the name of the article
	 * @todo The method is the same as SpecialAllpages::getNamespaceKeyAndText
	 *       Perhaps, it should be moved to a common ancestor to share the code
	 * @return array( int namespace, string dbkey, string pagename ) or NULL on error
	 */
	private function getNamespaceKeyAndText( $ns, $text ) {
		if ( $text == '' )
			return array( $ns, '', '' );

		$t = Title::makeTitleSafe( $ns, $text );
		if ( $t && $t->isLocal() ) {
			return array( $t->getNamespace(), $t->getDBkey(), $t->getText() );
		} elseif ( $t ) {
			return null;
		}

		// try again, in case the problem was an empty pagename
		$text = preg_replace('/(#|$)/', 'X$1', $text);
		$t = Title::makeTitleSafe($ns, $text);
		if ( $t && $t->isLocal() ) {
			return array( $t->getNamespace(), '', '' );
		} else {
			return null;
		}
	}
}
