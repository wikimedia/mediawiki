<?php
/**
 * Purges a specific page.
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
 * Maintenance script that purges a specific page
 *
 * @ingroup Maintenance
 */
class PurgePage extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Purge page.' );
		$this->addOption( 'title', 'Title', true, true );
		$this->addOption( 'skip-exists-check', 'Skip page existence check', false, false );
	}

	public function execute() {
		$title = Title::newFromText( $this->getOption( 'title' ) );

		if ( is_null( $title ) ) {
			$this->error( 'Invalid page title' );
			return;
		}

		$page = WikiPage::factory( $title );

		if ( is_null( $page ) ) {
			$this->error( "Could not instantiate page object" );
			return;
		}

		if ( !$this->getOption( 'skip-exists-check' ) && !$page->exists() ) {
			$this->error( "Page doesn't exist" );
			return;
		}

		if ( $page->doPurge() ) {
			$this->output( "Purged\n" );
		} else {
			$this->error( "Purge failed" );
		}
	}
}

$maintClass = "PurgePage";
require_once RUN_MAINTENANCE_IF_MAIN;
