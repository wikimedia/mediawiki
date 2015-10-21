<?php
/**
 * Fix instances of pre-existing JSON pages
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
 * Usage:
 *  fixDefaultJsonContentPages.php
 *
 * It is automatically run by update.php
 */
class FixDefaultJsonContentPages extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription =
				'Fix instances of JSON pages prior to them being the ContentHandler default';
		$this->setBatchSize( 100 );
	}

	protected function getUpdateKey() {
		return __CLASS__;
	}

	protected function doDBUpdates() {
		$dbr = wfGetDB( DB_SLAVE );
		$namespaces = array(
			NS_MEDIAWIKI => $dbr->buildLike( $dbr->anyString(), '.json' ),
			NS_USER => $dbr->buildLike( $dbr->anyString(), '/', $dbr->anyString(), '.json' ),
		);
		foreach ( $namespaces as $ns => $like ) {
			$lastPage = 0;
			do {
				$rows = $dbr->select(
						'page',
						array( 'page_id', 'page_title', 'page_namespace', 'page_content_model' ),
						array(
								'page_namespace' => $ns,
								'page_title ' . $like,
								'page_id > ' . $dbr->addQuotes( $lastPage )
						),
						__METHOD__,
						array( 'ORDER BY' => 'page_id', 'LIMIT' => $this->mBatchSize )
				);
				foreach ( $rows as $row ) {
					$this->handleRow( $row );
				}
			} while ( $rows->numRows() >= $this->mBatchSize );
		}

		return true;
	}

	protected function handleRow( stdClass $row ) {
		$title = Title::makeTitle( $row->page_namespace, $row->page_title );
		$this->output( "Processing {$title} ({$row->page_id})...\n" );
		$rev = Revision::newFromTitle( $title );
		$content = $rev->getContent( Revision::RAW );
		if ( $content instanceof JsonContent ) {
			if ( $content->isValid() ) {
				// Yay, actually JSON.
				$this->output( "Setting page_content_model to json..." );
				wfGetDB( DB_MASTER )->update(
					'page',
					array( 'page_content_model' => 'json' ),
					array( 'page_id' => $row->page_id ),
					__METHOD__
				);
				$this->output( "done.\n" );
				wfWaitForSlaves();
			} else {
				// Not JSON...force it to wikitext.
				$this->output( "Setting rev_content_model to wikitext..." );
				wfGetDB( DB_MASTER )->update(
					'revision',
					array( 'rev_content_model' => 'wikitext' ),
					array( 'rev_page' => $row->page_id ),
					__METHOD__
				);
				$this->output( "done.\n" );
				wfWaitForSlaves();
			}
		} else {
			$this->output( "not a JSON page? Skipping\n" );
		}
	}
}

$maintClass = 'FixDefaultJsonContentPages';
require_once RUN_MAINTENANCE_IF_MAIN;
