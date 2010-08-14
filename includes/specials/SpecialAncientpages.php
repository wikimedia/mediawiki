<?php
/**
 * Implements Special:Ancientpages
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
 * Implements Special:Ancientpages
 *
 * @ingroup SpecialPage
 */
class AncientPagesPage extends QueryPage {

	function getName() {
		return "Ancientpages";
	}

	function isExpensive() {
		return true;
	}

	function isSyndicated() { return false; }

	function getSQL() {
		global $wgDBtype;
		$db = wfGetDB( DB_SLAVE );
		$page = $db->tableName( 'page' );
		$revision = $db->tableName( 'revision' );

		switch ($wgDBtype) {
			case 'mysql': 
				$epoch = 'UNIX_TIMESTAMP(rev_timestamp)'; 
				break;
			case 'ibm_db2':
				// TODO implement proper conversion to a Unix epoch
				$epoch = 'rev_timestamp';
				break;
			case 'oracle': 
				$epoch = '((trunc(rev_timestamp) - to_date(\'19700101\',\'YYYYMMDD\')) * 86400)'; 
				break;
			case 'sqlite':
				$epoch = 'rev_timestamp';
				break;
			case 'mssql':
				$epoch = 'DATEDIFF(s,CONVERT(datetime,\'1/1/1970\'),rev_timestamp)';
				break;
			default:
				$epoch = 'EXTRACT(epoch FROM rev_timestamp)';
		}

		return
			"SELECT 'Ancientpages' as type,
					page_namespace as namespace,
			        page_title as title,
			        $epoch as value
			FROM $page, $revision
			WHERE page_namespace=".NS_MAIN." AND page_is_redirect=0
			  AND page_latest=rev_id";
	}

	function sortDescending() {
		return false;
	}

	function formatResult( $skin, $result ) {
		global $wgLang, $wgContLang;

		$d = $wgLang->timeanddate( wfTimestamp( TS_MW, $result->value ), true );
		$title = Title::makeTitle( $result->namespace, $result->title );
		$link = $skin->linkKnown(
			$title,
			htmlspecialchars( $wgContLang->convert( $title->getPrefixedText() ) )
		);
		return wfSpecialList($link, htmlspecialchars($d) );
	}
}

function wfSpecialAncientpages() {
	list( $limit, $offset ) = wfCheckLimits();

	$app = new AncientPagesPage();

	$app->doQuery( $offset, $limit );
}
