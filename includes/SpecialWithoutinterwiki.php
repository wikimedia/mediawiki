<?php

/**
 * Special page lists pages without language links
 *
 * @addtogroup SpecialPage
 * @author Rob Church <robchur@gmail.com>
 */
class WithoutInterwikiPage extends PageQueryPage {
	private $prefix = '';

	function getName() {
		return 'Withoutinterwiki';
	}

	function getPageHeader() {
		global $wgScript, $wgContLang;
		$prefix = $this->prefix;
		$t = SpecialPage::getTitleFor( $this->getName() );
		$align = $wgContLang->isRtl() ? 'left' : 'right';

		$s = '<p>' . wfMsgExt( 'withoutinterwiki-header', array( 'parseinline' ) ) . '</p>';
		$s .= Xml::openElement( 'div', array( 'class' => 'namespaceoptions' ) );
		$s .= Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript ) );
		$s .= Xml::hidden( 'title', $t->getPrefixedText() );
		$s .= Xml::openElement( 'table', array( 'id' => 'nsselect', 'class' => 'withoutinterwiki' ) );
		$s .= "<tr>
				<td align='$align'>" .
					Xml::label( wfMsg( 'allpagesprefix' ), 'wiprefix' ) .
				"</td>
				<td>" .
					Xml::input( 'prefix', 20, htmlspecialchars ( $prefix ), array( 'id' => 'wiprefix' ) ) .
				"</td>
			</tr>
			<tr>
				<td align='$align'></td>
				<td>" .
					Xml::submitButton( wfMsgHtml( 'withoutinterwiki-submit' ) ) .
				"</td>
			</tr>";
		$s .= Xml::closeElement( 'table' );
		$s .= Xml::closeElement( 'form' );
		$s .= Xml::closeElement( 'div' );
		return $s;
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
		$prefix = $this->prefix ? "AND page_title LIKE '" . $dbr->escapeLike( $this->prefix ) . "%'" : '';
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
	$prefix = $wgRequest->getVal( 'prefix' );
	$wip = new WithoutInterwikiPage();
	$wip->setPrefix( $prefix );
	$wip->doQuery( $offset, $limit );
}
