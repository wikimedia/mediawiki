<?php
/**
 * Implements Special:Unusedtemplates
 *
 * Copyright © 2006 Rob Church
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
 * A special page that lists unused templates
 *
 * @ingroup SpecialPage
 */
class UnusedtemplatesPage extends QueryPage {

	function __construct( $name = 'Unusedtemplates' ) {
		parent::__construct( $name );
	}

	function isExpensive() { return true; }
	function isSyndicated() { return false; }
	function sortDescending() { return false; }

	function getQueryInfo() {
		return array (
			'tables' => array ( 'page', 'templatelinks' ),
			'fields' => array ( 'page_namespace AS namespace',
					'page_title AS title',
					'page_title AS value' ),
			'conds' => array ( 'page_namespace' => NS_TEMPLATE,
					'tl_from IS NULL',
					'page_is_redirect' => 0 ),
			'join_conds' => array ( 'templatelinks' => array (
				'LEFT JOIN', array ( 'tl_title = page_title',
					'tl_namespace = page_namespace' ) ) )
		);
	}

	function formatResult( $skin, $result ) {
		$title = Title::makeTitle( NS_TEMPLATE, $result->title );
		$pageLink = $skin->linkKnown(
			$title,
			null,
			array(),
			array( 'redirect' => 'no' )
		);
		$wlhLink = $skin->linkKnown(
			SpecialPage::getTitleFor( 'Whatlinkshere' ),
			wfMsgHtml( 'unusedtemplateswlh' ),
			array(),
			array( 'target' => $title->getPrefixedText() )
		);
		return wfSpecialList( $pageLink, $wlhLink );
	}

	function getPageHeader() {
		return wfMsgExt( 'unusedtemplatestext', array( 'parse' ) );
	}

}

