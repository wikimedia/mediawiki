<?php
/**
 *
 * @addtogroup SpecialPage
 */

/**
 * A special page listing redirects to redirecting page.
 * The software will automatically not follow double redirects, to prevent loops.
 * @addtogroup SpecialPage
 */
class DoubleRedirectsPage extends PageQueryPage {

	function getName() {
		return 'DoubleRedirects';
	}

	function isExpensive( ) { return true; }
	function isSyndicated() { return false; }

	function getPageHeader( ) {
		return wfMsgExt( 'doubleredirectstext', array( 'parse' ) );
	}

	function getSql() {
		$dbr = wfGetDB( DB_SLAVE );
		list( $page, $redirect ) = $dbr->tableNamesN( 'page', 'redirect' );
		return "
			SELECT
				'DoubleRedirects' as type,
				pa.page_namespace as namespace, pa.page_title as title,
				pb.page_namespace as nsb, pb.page_title as tb,
				pc.page_namespace as nsc, pc.page_title as tc
			FROM
				$redirect AS ra,
				$redirect AS rb,
				$page AS pa,
				$page AS pb,
				$page AS pc
			WHERE
				ra.rd_from = pa.page_id
				AND ra.rd_namespace = pb.page_namespace
				AND ra.rd_title = pb.page_title
				AND rb.rd_from = pb.page_id
				AND rb.rd_namespace = pc.page_namespace
				AND rb.rd_title = pc.page_title";
	}

	function getOrder() {
		return '';
	}

	function formatResult( $skin, $result ) {
		global $wgContLang;
		$parts = array();
	
		$titleA = Title::makeTitle( $result->namespace, $result->title );
		$parts[] = $skin->makeKnownLinkObj( $titleA, '', 'redirect=no' );
		$parts[] = '(' . $skin->makeKnownLinkObj(
			$titleA,
			wfMsgHtml( 'qbedit' ),
			'action=edit&redirect=no'
		) . ')';
	
		// If the report isn't cached, generate some useful additional
		// links to the target page, and *that* page's redirect target
		if( isset( $result->nsb ) ) {
			$parts[] = $wgContLang->getArrow() . $wgContLang->getDirMark();
			$parts[] = $skin->makeKnownLinkObj(
				Title::makeTitle( $result->nsb, $result->tb ),
				'',
				'redirect=no'
			);
			$parts[] = $wgContLang->getArrow() . $wgContLang->getDirMark();
			$parts[] = $skin->makeKnownLinkObj( Title::makeTitle( $result->nsc, $result->tc ) );
		}

		return implode( ' ', $parts );
	}
}

/**
 * constructor
 */
function wfSpecialDoubleRedirects() {
	list( $limit, $offset ) = wfCheckLimits();
	$sdr = new DoubleRedirectsPage();
	return $sdr->doQuery( $offset, $limit );
}