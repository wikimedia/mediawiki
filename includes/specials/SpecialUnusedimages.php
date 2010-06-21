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

/**
 * implements Special:Unusedimages
 * @ingroup SpecialPage
 */
class UnusedimagesPage extends ImageQueryPage {

	function isExpensive() { return true; }

	function getName() {
		return 'Unusedimages';
	}

	function sortDescending() {
		return false;
	}
	function isSyndicated() { return false; }

	function getSQL() {
		global $wgCountCategorizedImagesAsUsed, $wgDBtype;
		$dbr = wfGetDB( DB_SLAVE );

		switch ($wgDBtype) {
			case 'mysql': 
				$epoch = 'UNIX_TIMESTAMP(img_timestamp)'; 
				break;
			case 'oracle': 
				$epoch = '((trunc(img_timestamp) - to_date(\'19700101\',\'YYYYMMDD\')) * 86400)'; 
				break;
			case 'sqlite':
				$epoch = 'img_timestamp';
				break;
			default:
				$epoch = 'EXTRACT(epoch FROM img_timestamp)';
		}

		if ( $wgCountCategorizedImagesAsUsed ) {
			list( $page, $image, $imagelinks, $categorylinks ) = $dbr->tableNamesN( 'page', 'image', 'imagelinks', 'categorylinks' );

			return "SELECT 'Unusedimages' as type, 6 as namespace, img_name as title, $epoch as value,
						img_user, img_user_text,  img_description
					FROM ((($page AS I LEFT JOIN $categorylinks AS L ON I.page_id = L.cl_from)
						LEFT JOIN $imagelinks AS P ON I.page_title = P.il_to)
						INNER JOIN $image AS G ON I.page_title = G.img_name)
					WHERE I.page_namespace = ".NS_FILE." AND L.cl_from IS NULL AND P.il_to IS NULL";
		} else {
			list( $image, $imagelinks ) = $dbr->tableNamesN( 'image','imagelinks' );

			return "SELECT 'Unusedimages' as type, 6 as namespace, img_name as title, $epoch as value,
				img_user, img_user_text,  img_description
				FROM $image LEFT JOIN $imagelinks ON img_name=il_to WHERE il_to IS NULL ";
		}
	}

	function getPageHeader() {
		return wfMsgExt( 'unusedimagestext', array( 'parse' ) );
	}

}

/**
 * Entry point
 */
function wfSpecialUnusedimages() {
	list( $limit, $offset ) = wfCheckLimits();
	$uip = new UnusedimagesPage();

	return $uip->doQuery( $offset, $limit );
}
