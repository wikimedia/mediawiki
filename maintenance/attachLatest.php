<?php
/**
 * Corrects wrong values in the `page_latest` field in the database.
 *
 * Copyright © 2005 Brion Vibber <brion@pobox.com>
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

use MediaWiki\MediaWikiServices;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script to correct wrong values in the `page_latest` field
 * in the database.
 *
 * @ingroup Maintenance
 */
class AttachLatest extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addOption( "fix", "Actually fix the entries, will dry run otherwise" );
		$this->addOption( "regenerate-all",
			"Regenerate the page_latest field for all records in table page" );
		$this->addDescription( 'Fix page_latest entries in the page table' );
	}

	public function execute() {
		$this->output( "Looking for pages with page_latest set to 0...\n" );
		$dbw = $this->getDB( DB_MASTER );
		$conds = [ 'page_latest' => 0 ];
		if ( $this->hasOption( 'regenerate-all' ) ) {
			$conds = '';
		}
		$result = $dbw->select( 'page',
			[ 'page_id', 'page_namespace', 'page_title' ],
			$conds,
			__METHOD__ );

		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$n = 0;
		foreach ( $result as $row ) {
			$pageId = intval( $row->page_id );
			$title = Title::makeTitle( $row->page_namespace, $row->page_title );
			$name = $title->getPrefixedText();
			$latestTime = $dbw->selectField( 'revision',
				'MAX(rev_timestamp)',
				[ 'rev_page' => $pageId ],
				__METHOD__ );
			if ( !$latestTime ) {
				$this->output( wfWikiID() . " $pageId [[$name]] can't find latest rev time?!\n" );
				continue;
			}

			$revision = Revision::loadFromTimestamp( $dbw, $title, $latestTime );
			if ( is_null( $revision ) ) {
				$this->output( wfWikiID()
					. " $pageId [[$name]] latest time $latestTime, can't find revision id\n" );
				continue;
			}
			$id = $revision->getId();
			$this->output( wfWikiID() . " $pageId [[$name]] latest time $latestTime, rev id $id\n" );
			if ( $this->hasOption( 'fix' ) ) {
				$page = WikiPage::factory( $title );
				$page->updateRevisionOn( $dbw, $revision );
				$lbFactory->waitForReplication();
			}
			$n++;
		}
		$this->output( "Done! Processed $n pages.\n" );
		if ( !$this->hasOption( 'fix' ) ) {
			$this->output( "This was a dry run; rerun with --fix to update page_latest.\n" );
		}
	}
}

$maintClass = AttachLatest::class;
require_once RUN_MAINTENANCE_IF_MAIN;
