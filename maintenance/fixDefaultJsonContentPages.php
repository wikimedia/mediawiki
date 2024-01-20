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

use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeValue;

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
		$dbr = $this->getReplicaDB();
		$namespaces = [
			NS_MEDIAWIKI => $dbr->expr(
				'page_title',
				IExpression::LIKE,
				new LikeValue( $dbr->anyString(), '.json' )
			),
			NS_USER => $dbr->expr(
				'page_title',
				IExpression::LIKE,
				new LikeValue( $dbr->anyString(), '/', $dbr->anyString(), '.json' )
			),
		];
		foreach ( $namespaces as $ns => $likeExpr ) {
			$lastPage = 0;
			do {
				$rows = $dbr->newSelectQueryBuilder()
					->select( [ 'page_id', 'page_title', 'page_namespace', 'page_content_model' ] )
					->from( 'page' )
					->where( [
						'page_namespace' => $ns,
						$likeExpr,
						$dbr->expr( 'page_id', '>', $lastPage ),
					] )
					->orderBy( 'page_id' )
					->limit( $this->getBatchSize() )
					->caller( __METHOD__ )->fetchResultSet();
				foreach ( $rows as $row ) {
					$this->handleRow( $row );
					$lastPage = $row->page_id;
				}
			} while ( $rows->numRows() >= $this->getBatchSize() );
		}

		return true;
	}

	protected function handleRow( stdClass $row ) {
		$title = Title::makeTitle( $row->page_namespace, $row->page_title );
		$this->output( "Processing {$title} ({$row->page_id})...\n" );
		$rev = $this->getServiceContainer()
			->getRevisionLookup()
			->getRevisionByTitle( $title );
		$content = $rev->getContent( SlotRecord::MAIN, RevisionRecord::RAW );
		$dbw = $this->getPrimaryDB();
		if ( $content instanceof JsonContent ) {
			if ( $content->isValid() ) {
				// Yay, actually JSON. We need to just change the
				// page_content_model because revision will automatically
				// use the default, which is *now* JSON.
				$this->output( "Setting page_content_model to json..." );
				$dbw->newUpdateQueryBuilder()
					->update( 'page' )
					->set( [ 'page_content_model' => CONTENT_MODEL_JSON ] )
					->where( [ 'page_id' => $row->page_id ] )
					->caller( __METHOD__ )->execute();

				$this->output( "done.\n" );
				$this->waitForReplication();
			} else {
				// Not JSON...force it to wikitext. We need to update the
				// revision table so that these revisions are always processed
				// as wikitext in the future. page_content_model is already
				// set to "wikitext".
				$this->output( "Setting rev_content_model to wikitext..." );
				// Grab all the ids for batching
				$ids = $dbw->newSelectQueryBuilder()
					->select( 'rev_id' )
					->from( 'revision' )
					->where( [ 'rev_page' => $row->page_id ] )
					->caller( __METHOD__ )->fetchFieldValues();
				foreach ( array_chunk( $ids, 50 ) as $chunk ) {
					$dbw->newUpdateQueryBuilder()
						->update( 'revision' )
						->set( [ 'rev_content_model' => CONTENT_MODEL_WIKITEXT ] )
						->where( [ 'rev_page' => $row->page_id, 'rev_id' => $chunk ] )
						->caller( __METHOD__ )->execute();

					$this->waitForReplication();
				}
				$this->output( "done.\n" );
			}
		} else {
			$this->output( "not a JSON page? Skipping\n" );
		}
	}
}

$maintClass = FixDefaultJsonContentPages::class;
require_once RUN_MAINTENANCE_IF_MAIN;
