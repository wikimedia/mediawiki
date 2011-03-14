<?php
/**
 * Implements Special:Fewestrevisions
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
 * Special page for listing the articles with the fewest revisions.
 *
 * @ingroup SpecialPage
 * @author Martin Drashkov
 */
class FewestrevisionsPage extends QueryPage {

	function __construct( $name = 'Fewestrevisions' ) {
		parent::__construct( $name );
	}

	function isExpensive() {
		return true;
	}

	function isSyndicated() {
		return false;
	}

	function getQueryInfo() {
		return array (
			'tables' => array ( 'revision', 'page' ),
			'fields' => array ( 'page_namespace AS namespace',
					'page_title AS title',
					'COUNT(*) AS value',
					'page_is_redirect AS redirect' ),
			'conds' => array ( 'page_namespace' => MWNamespace::getContentNamespaces(),
					'page_id = rev_page' ),
			'options' => array ( 'HAVING' => 'COUNT(*) > 1',
			// ^^^ This was probably here to weed out redirects.
			// Since we mark them as such now, it might be
			// useful to remove this. People _do_ create pages
			// and never revise them, they aren't necessarily
			// redirects.
			'GROUP BY' => 'page_namespace, page_title, page_is_redirect' )
		);
	}


	function sortDescending() {
		return false;
	}

	/**
	 * @param $skin Skin object
	 * @param $result Object: database row
	 */
	function formatResult( $skin, $result ) {
		global $wgLang, $wgContLang;

		$nt = Title::makeTitleSafe( $result->namespace, $result->title );
		if( !$nt ) {
			return '<!-- bad title -->';
		}

		$text = $wgContLang->convert( $nt->getPrefixedText() );

		$plink = $skin->linkKnown(
			$nt,
			$text
		);

		$nl = wfMsgExt( 'nrevisions', array( 'parsemag', 'escape' ),
			$wgLang->formatNum( $result->value ) );
		$redirect = $result->redirect ? ' - ' . wfMsgHtml( 'isredirect' ) : '';
		$nlink = $skin->linkKnown(
			$nt,
			$nl,
			array(),
			array( 'action' => 'history' )
		) . $redirect;

		return wfSpecialList( $plink, $nlink );
	}
}
