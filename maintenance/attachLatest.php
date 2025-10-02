<?php
/**
 * Corrects wrong values in the `page_latest` field in the database.
 *
 * Copyright © 2005 Brooke Vibber <bvibber@wikimedia.org>
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

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\IDBAccessObject;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

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
		$dbw = $this->getPrimaryDB();
		$conds = [ 'page_latest' => 0 ];
		if ( $this->hasOption( 'regenerate-all' ) ) {
			$conds = [];
		}
		$result = $dbw->newSelectQueryBuilder()
			->select( [ 'page_id', 'page_namespace', 'page_title' ] )
			->from( 'page' )
			->where( $conds )
			->caller( __METHOD__ )
			->fetchResultSet();

		$services = $this->getServiceContainer();
		$dbDomain = $services->getDBLoadBalancerFactory()->getLocalDomainID();
		$wikiPageFactory = $services->getWikiPageFactory();
		$revisionLookup = $services->getRevisionLookup();

		$n = 0;
		foreach ( $result as $row ) {
			$pageId = intval( $row->page_id );
			$title = Title::makeTitle( $row->page_namespace, $row->page_title );
			$name = $title->getPrefixedText();
			$latestTime = $dbw->newSelectQueryBuilder()
				->select( 'MAX(rev_timestamp)' )
				->from( 'revision' )
				->where( [ 'rev_page' => $pageId ] )
				->caller( __METHOD__ )
				->fetchField();
			if ( !$latestTime ) {
				$this->output( "$dbDomain $pageId [[$name]] can't find latest rev time?!\n" );
				continue;
			}

			$revRecord = $revisionLookup->getRevisionByTimestamp( $title, $latestTime, IDBAccessObject::READ_LATEST );
			if ( $revRecord === null ) {
				$this->output(
					"$dbDomain $pageId [[$name]] latest time $latestTime, can't find revision id\n"
				);
				continue;
			}

			$id = $revRecord->getId();
			$this->output( "$dbDomain $pageId [[$name]] latest time $latestTime, rev id $id\n" );
			if ( $this->hasOption( 'fix' ) ) {
				$page = $wikiPageFactory->newFromTitle( $title );
				$page->updateRevisionOn( $dbw, $revRecord );
				$this->waitForReplication();
			}
			$n++;
		}
		$this->output( "Done! Processed $n pages.\n" );
		if ( !$this->hasOption( 'fix' ) ) {
			$this->output( "This was a dry run; rerun with --fix to update page_latest.\n" );
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = AttachLatest::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
