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
		$this->addDescription(
			'Fix instances of JSON pages prior to them being the ContentHandler default' );
		$this->setBatchSize( 100 );
	}

	protected function getUpdateKey() {
		return __CLASS__;
	}

	protected function doDBUpdates() {
		if ( !$this->getConfig()->get( 'ContentHandlerUseDB' ) ) {
			$this->output( "\$wgContentHandlerUseDB is not enabled, nothing to do.\n" );
			return true;
		}

		$dbr = $this->getDB( DB_REPLICA );
		$namespaces = [
			NS_MEDIAWIKI => $dbr->buildLike( $dbr->anyString(), '.json' ),
			NS_USER => $dbr->buildLike( $dbr->anyString(), '/', $dbr->anyString(), '.json' ),
		];
		foreach ( $namespaces as $ns => $like ) {
			$lastPage = 0;
			do {
				$rows = $dbr->select(
						'page',
						[ 'page_id', 'page_title', 'page_namespace', 'page_content_model' ],
						[
								'page_namespace' => $ns,
								'page_title ' . $like,
								'page_id > ' . $dbr->addQuotes( $lastPage )
						],
						__METHOD__,
						[ 'ORDER BY' => 'page_id', 'LIMIT' => $this->mBatchSize ]
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
		$dbw = $this->getDB( DB_MASTER );
		if ( $content instanceof JsonContent ) {
			if ( $content->isValid() ) {
				// Yay, actually JSON. We need to just change the
				// page_content_model because revision will automatically
				// use the default, which is *now* JSON.
				$this->output( "Setting page_content_model to json..." );
				$dbw->update(
					'page',
					[ 'page_content_model' => CONTENT_MODEL_JSON ],
					[ 'page_id' => $row->page_id ],
					__METHOD__
				);
				$this->output( "done.\n" );
				wfWaitForSlaves();
			} else {
				// Not JSON...force it to wikitext. We need to update the
				// revision table so that these revisions are always processed
				// as wikitext in the future. page_content_model is already
				// set to "wikitext".
				$this->output( "Setting rev_content_model to wikitext..." );
				// Grab all the ids for batching
				$ids = $dbw->selectFieldValues(
					'revision',
					'rev_id',
					[ 'rev_page' => $row->page_id ],
					__METHOD__
				);
				foreach ( array_chunk( $ids, 50 ) as $chunk ) {
					$dbw->update(
						'revision',
						[ 'rev_content_model' => CONTENT_MODEL_WIKITEXT ],
						[ 'rev_page' => $row->page_id, 'rev_id' => $chunk ]
					);
					wfWaitForSlaves();
				}
				$this->output( "done.\n" );
			}
		} else {
			$this->output( "not a JSON page? Skipping\n" );
		}
	}
}

$maintClass = 'FixDefaultJsonContentPages';
require_once RUN_MAINTENANCE_IF_MAIN;
