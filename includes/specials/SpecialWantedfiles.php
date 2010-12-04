<?php
/**
 * Implements Special:Wantedfiles
 *
 * Copyright © 2008 Soxred93
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
 * @author Soxred93 <soxred93@gmail.com>
 */

/**
 * Querypage that lists the most wanted files
 *
 * @ingroup SpecialPage
 */
class WantedFilesPage extends WantedQueryPage {

	function getName() {
		return 'Wantedfiles';
	}

	/**
	 * KLUGE: The results may contain false positives for files
	 * that exist e.g. in a shared repo.  Setting this at least
	 * keeps them from showing up as redlinks in the output, even
	 * if it doesn't fix the real problem (bug 6220).
	 */
	function forceExistenceCheck() {
		return true;
	}

	function getSQL() {
		$dbr = wfGetDB( DB_SLAVE );
		list( $imagelinks, $image ) = $dbr->tableNamesN( 'imagelinks', 'image' );
		$name = $dbr->addQuotes( $this->getName() );
		return
			"
			SELECT
				$name as type,
				" . NS_FILE . " as namespace,
				il_to as title,
				COUNT(*) as value
			FROM $imagelinks
			LEFT JOIN $image ON il_to = img_name
			WHERE img_name IS NULL
			GROUP BY il_to
			";
	}
}

/**
 * constructor
 */
function wfSpecialWantedFiles() {
	list( $limit, $offset ) = wfCheckLimits();

	$wpp = new WantedFilesPage();

	$wpp->doQuery( $offset, $limit );
}
