<?php
/**
 * Implements Special:Disambiguations
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
 * A special page that lists pages containing links to disambiguations pages
 *
 * @ingroup SpecialPage
 */
class DisambiguationsPage extends PageQueryPage {

	function __construct( $name = 'Disambiguations' ) {
		parent::__construct( $name );
	}

	function isExpensive() { return true; }
	function isSyndicated() { return false; }

	function getPageHeader() {
		return wfMsgExt( 'disambiguations-text', array( 'parse' ) );
	}

	function getQueryInfo() {
		$dbr = wfGetDB( DB_SLAVE );
		$dMsgText = wfMsgForContent( 'disambiguationspage' );
		$linkBatch = new LinkBatch;

		# If the text can be treated as a title, use it verbatim.
		# Otherwise, pull the titles from the links table
		$dp = Title::newFromText($dMsgText);
		if( $dp ) {
			if( $dp->getNamespace() != NS_TEMPLATE ) {
				# FIXME we assume the disambiguation message is a template but
				# the page can potentially be from another namespace :/
				wfDebug("Mediawiki:disambiguationspage message does not refer to a template!\n");
			}
			$linkBatch->addObj( $dp );
		} else {
				# Get all the templates linked from the Mediawiki:Disambiguationspage
				$disPageObj = Title::makeTitleSafe( NS_MEDIAWIKI, 'disambiguationspage' );
				$res = $dbr->select(
					array('pagelinks', 'page'),
					'pl_title',
					array('page_id = pl_from',
						'pl_namespace' => NS_TEMPLATE,
						'page_namespace' => $disPageObj->getNamespace(),
						'page_title' => $disPageObj->getDBkey()),
					__METHOD__ );

				foreach ( $res as $row ) {
					$linkBatch->addObj( Title::makeTitle( NS_TEMPLATE, $row->pl_title ));
				}
		}
		$set = $linkBatch->constructSet( 'tl', $dbr );
		if( $set === false ) {
			# We must always return a valid SQL query, but this way
			# the DB will always quickly return an empty result
			$set = 'FALSE';
			wfDebug("Mediawiki:disambiguationspage message does not link to any templates!\n");
		}
		
		// FIXME: What are pagelinks and p2 doing here?
		return array (
			'tables' => array( 'templatelinks', 'p1' => 'page', 'pagelinks', 'p2' => 'page' ),
			'fields' => array( 'p1.page_namespace AS namespace',
					'p1.page_title AS title',
					'pl_from AS value' ),
			'conds' => array( $set,
					'p1.page_id = tl_from',
					'pl_namespace = p1.page_namespace',
					'pl_title = p1.page_title',
					'p2.page_id = pl_from',
					'p2.page_namespace' => MWNamespace::getContentNamespaces() )
		);
	}

	function getOrderFields() {
		return array( 'tl_namespace', 'tl_title', 'value' );
	}
	
	function sortDescending() {
		return false;
	}
	
	/**
	 * Fetch  links and cache their existence
	 */
	function preprocessResults( $db, $res ) {
		$batch = new LinkBatch;
		foreach ( $res as $row ) {
			$batch->add( $row->namespace, $row->title );
		}
		$batch->execute();

		// Back to start for display
		if ( $db->numRows( $res ) > 0 ) {
			// If there are no rows we get an error seeking.
			$db->dataSeek( $res, 0 );
		}
	}


	function formatResult( $skin, $result ) {
		global $wgContLang;
		$title = Title::newFromID( $result->value );
		$dp = Title::makeTitle( $result->namespace, $result->title );

		$from = $skin->link( $title );
		$edit = $skin->link( $title, wfMsgExt( 'parentheses', array( 'escape' ), wfMsg( 'editlink' ) ) ,
			array(), array( 'redirect' => 'no', 'action' => 'edit' ) );
		$arr  = $wgContLang->getArrow();
		$to   = $skin->link( $dp );

		return "$from $edit $arr $to";
	}
}
