<?php
/**
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
 */

/**
 * @file
 * @ingroup SpecialPage
 */
class DisambiguationsPage extends PageQueryPage {

	function getName() {
		return 'Disambiguations';
	}

	function isExpensive( ) { return true; }
	function isSyndicated() { return false; }


	function getPageHeader( ) {
		return wfMsgExt( 'disambiguations-text', array( 'parse' ) );
	}

	function getSQL() {
		global $wgContentNamespaces;

		$dbr = wfGetDB( DB_SLAVE );

		$dMsgText = wfMsgForContent('disambiguationspage');

		$linkBatch = new LinkBatch;

		# If the text can be treated as a title, use it verbatim.
		# Otherwise, pull the titles from the links table
		$dp = Title::newFromText($dMsgText);
		if( $dp ) {
			if($dp->getNamespace() != NS_TEMPLATE) {
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
					array('page_id = pl_from', 'pl_namespace' => NS_TEMPLATE,
						'page_namespace' => $disPageObj->getNamespace(), 'page_title' => $disPageObj->getDBkey()),
					__METHOD__ );

				while ( $row = $dbr->fetchObject( $res ) ) {
					$linkBatch->addObj( Title::makeTitle( NS_TEMPLATE, $row->pl_title ));
				}

				$dbr->freeResult( $res );
		}

		$set = $linkBatch->constructSet( 'lb.tl', $dbr );
		if( $set === false ) {
			# We must always return a valid sql query, but this way DB will always quicly return an empty result
			$set = 'FALSE';
			wfDebug("Mediawiki:disambiguationspage message does not link to any templates!\n");
		}

		list( $page, $pagelinks, $templatelinks) = $dbr->tableNamesN( 'page', 'pagelinks', 'templatelinks' );

		if ( $wgContentNamespaces ) {
			$nsclause = 'IN (' . $dbr->makeList( $wgContentNamespaces ) . ')';
		} else {
			$nsclause = '= ' . NS_MAIN;
		}

		$sql = "SELECT 'Disambiguations' AS \"type\", pb.page_namespace AS namespace,"
			." pb.page_title AS title, la.pl_from AS value"
			." FROM {$templatelinks} AS lb, {$page} AS pb, {$pagelinks} AS la, {$page} AS pa"
			." WHERE $set"  # disambiguation template(s)
			.' AND pa.page_id = la.pl_from'
			.' AND pa.page_namespace ' . $nsclause
			.' AND pb.page_id = lb.tl_from'
			.' AND pb.page_namespace = la.pl_namespace'
			.' AND pb.page_title = la.pl_title'
			.' ORDER BY lb.tl_namespace, lb.tl_title';

		return $sql;
	}

	function getOrder() {
		return '';
	}

	function formatResult( $skin, $result ) {
		global $wgContLang;
		$title = Title::newFromID( $result->value );
		$dp = Title::makeTitle( $result->namespace, $result->title );

		$from = $skin->link( $title );
		$edit = $skin->link( $title, wfMsgExt( 'parentheses', array( 'escape' ), wfMsg( 'editlink' ) ) , array(), array( 'redirect' => 'no', 'action' => 'edit' ) );
		$arr  = $wgContLang->getArrow();
		$to   = $skin->link( $dp );

		return "$from $edit $arr $to";
	}
}

/**
 * Constructor
 */
function wfSpecialDisambiguations() {
	list( $limit, $offset ) = wfCheckLimits();

	$sd = new DisambiguationsPage();

	return $sd->doQuery( $offset, $limit );
}
