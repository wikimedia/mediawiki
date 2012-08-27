<?php
/**
 * Check that pages marked as being redirects really are.
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

require_once( __DIR__ . '/Maintenance.php' );

/**
 * Maintenance script to check that pages marked as being redirects really are.
 *
 * @ingroup Maintenance
 */
class CheckBadRedirects extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Check for bad redirects";
	}

	public function execute() {
		$this->output( "Fetching redirects...\n" );
		$dbr = wfGetDB( DB_SLAVE );
		$result = $dbr->select(
			array( 'page' ),
			array( 'page_namespace', 'page_title', 'page_latest' ),
			array( 'page_is_redirect' => 1 ) );

		$count = $result->numRows();
		$this->output( "Found $count redirects.\n" .
						"Checking for bad redirects:\n\n" );

		foreach ( $result as $row ) {
			$title = Title::makeTitle( $row->page_namespace, $row->page_title );
			$rev = Revision::newFromId( $row->page_latest );
			if ( $rev ) {
				$target = Title::newFromRedirect( $rev->getText() );
				if ( !$target ) {
					$this->output( $title->getPrefixedText() . "\n" );
				}
			}
		}
		$this->output( "\nDone.\n" );
	}
}

$maintClass = "CheckBadRedirects";
require_once( RUN_MAINTENANCE_IF_MAIN );
