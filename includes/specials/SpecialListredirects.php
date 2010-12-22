<?php
/**
 * Implements Special:Listredirects
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
 * Special:Listredirects - Lists all the redirects on the wiki.
 * @ingroup SpecialPage
 */
class ListredirectsPage extends QueryPage {

	function __construct( $name = 'Listredirects' ) {
		parent::__construct( $name );
	}
	
	function isExpensive() { return true; }
	function isSyndicated() { return false; }
	function sortDescending() { return false; }

	function getQueryInfo() {
		return array(
			'tables' => array( 'p1' => 'page', 'redirect', 'p2' => 'page' ),
			'fields' => array( 'p1.page_namespace AS namespace',
					'p1.page_title AS title',
					'rd_namespace',
					'rd_title',
					'p2.page_id AS redirid' ),
			'conds' => array( 'p1.page_is_redirect' => 1 ),
			'join_conds' => array( 'redirect' => array(
					'LEFT JOIN', 'rd_from=p1.page_id' ),
				'p2' => array( 'LEFT JOIN', array(
					'p2.page_namespace=rd_namespace',
					'p2.page_title=rd_title' ) ) )
		);
	}

	function getOrderFields() {
		return array ( 'p1.page_namespace', 'p1.page_title' );
	}

	function formatResult( $skin, $result ) {
		global $wgContLang;

		# Make a link to the redirect itself
		$rd_title = Title::makeTitle( $result->namespace, $result->title );
		$rd_link = $skin->link(
			$rd_title,
			null,
			array(),
			array( 'redirect' => 'no' )
		);

		# Find out where the redirect leads
		$revision = Revision::newFromTitle( $rd_title );
		if( $revision ) {
			# Make a link to the destination page
			$target = Title::newFromRedirect( $revision->getText() );
			if( $target ) {
				$arr = $wgContLang->getArrow() . $wgContLang->getDirMark();
				$targetLink = $skin->link( $target );
				return "$rd_link $arr $targetLink";
			} else {
				return "<del>$rd_link</del>";
			}
		} else {
			return "<del>$rd_link</del>";
		}
	}
}
