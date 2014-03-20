<?php
/**
 * Quickie page name dump script for SisterSites usage.
 * http://www.eekim.com/cgi-bin/wiki.pl?SisterSites
 *
 * Copyright © 2006 Brion Vibber <brion@pobox.com>
 * https://www.mediawiki.org/
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
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that generates a page name dump for SisterSites usage.
 *
 * @ingroup Maintenance
 */
class DumpSisterSites extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Quickie page name dump script for SisterSites usage";
	}

	public function execute() {
		$dbr = wfGetDB( DB_SLAVE );
		$dbr->bufferResults( false );
		$result = $dbr->select( 'page',
			array( 'page_namespace', 'page_title' ),
			array(
				'page_namespace' => NS_MAIN,
				'page_is_redirect' => 0,
			),
			__METHOD__ );

		foreach ( $result as $row ) {
			$title = Title::makeTitle( $row->page_namespace, $row->page_title );
			$url = $title->getFullURL();
			$text = $title->getPrefixedText();
			$this->output( "$url $text\n" );
		}
	}
}

$maintClass = "DumpSisterSites";
require_once RUN_MAINTENANCE_IF_MAIN;
