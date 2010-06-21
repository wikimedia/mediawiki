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
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

/**
 * implements Special:Mostimages
 * @ingroup SpecialPage
 */
class MostimagesPage extends ImageQueryPage {

	function getName() { return 'Mostimages'; }
	function isExpensive() { return true; }
	function isSyndicated() { return false; }

	function getSQL() {
		$dbr = wfGetDB( DB_SLAVE );
		$imagelinks = $dbr->tableName( 'imagelinks' );
		return
			"
			SELECT
				'Mostimages' as type,
				" . NS_FILE . " as namespace,
				il_to as title,
				COUNT(*) as value
			FROM $imagelinks
			GROUP BY il_to
			HAVING COUNT(*) > 1
			";
	}

	function getCellHtml( $row ) {
		global $wgLang;
		return wfMsgExt( 'nlinks',  array( 'parsemag', 'escape' ),
			$wgLang->formatNum( $row->value ) ) . '<br />';
	}

}

/**
 * Constructor
 */
function wfSpecialMostimages() {
	list( $limit, $offset ) = wfCheckLimits();

	$wpp = new MostimagesPage();

	$wpp->doQuery( $offset, $limit );
}
