<?php
/**
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
 */

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Search\SearchUpdate;
use MediaWiki\Title\Title;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\Rdbms\IDBAccessObject;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Periodic off-peak updating of the search index.
 *
 * Usage: php updateSearchIndex.php [-s START] [-e END] [-p POSFILE] [-l LOCKTIME] [-q]
 * Where START is the starting timestamp
 * END is the ending timestamp
 * POSFILE is a file to load timestamps from and save them to, searchUpdate.WIKI_ID.pos by default
 * LOCKTIME is how long the searchindex and revision tables will be locked for
 * -q means quiet
 *
 * @ingroup Search
 * @ingroup Maintenance
 */
class UpdateSearchIndex extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Script for periodic off-peak updating of the search index' );
		$this->addOption( 's', 'Starting timestamp', false, true );
		$this->addOption( 'e', 'Ending timestamp', false, true );
		$this->addOption(
			'p',
			'File for saving/loading timestamps, searchUpdate.WIKI_ID.pos by default',
			false,
			true
		);
		$this->addOption(
			'l',
			'Deprecated, has no effect (formerly lock time)',
			false,
			true
		);
	}

	/** @inheritDoc */
	public function getDbType() {
		return Maintenance::DB_ADMIN;
	}

	public function execute() {
		$dbDomain = WikiMap::getCurrentWikiDbDomain()->getId();
		$posFile = $this->getOption( 'p', 'searchUpdate.' . rawurlencode( $dbDomain ) . '.pos' );
		$end = $this->getOption( 'e', wfTimestampNow() );
		if ( $this->hasOption( 's' ) ) {
			$start = $this->getOption( 's' );
		} elseif ( is_readable( $posFile ) ) {
			$start = file_get_contents( $posFile );
		} else {
			$start = wfTimestamp( TS_MW, time() - 86400 );
		}

		$this->doUpdateSearchIndex( $start, $end );
		$file = fopen( $posFile, 'w' );
		if ( $file !== false ) {
			fwrite( $file, $end );
			fclose( $file );
		} else {
			$this->error( "*** Couldn't write to the $posFile!\n" );
		}
	}

	private function doUpdateSearchIndex( string $start, string $end ) {
		global $wgDisableSearchUpdate;

		$wgDisableSearchUpdate = false;

		$dbw = $this->getPrimaryDB();

		$this->output( "Updating searchindex between $start and $end\n" );

		# Select entries from recentchanges which are on top and between the specified times
		$start = $dbw->timestamp( $start );
		$end = $dbw->timestamp( $end );

		$res = $dbw->newSelectQueryBuilder()
			->select( 'rc_cur_id' )
			->from( 'recentchanges' )
			->join( 'page', null, 'rc_cur_id=page_id AND rc_this_oldid=page_latest' )
			->where( [
				$dbw->expr( 'rc_type', '!=', RC_LOG ),
				$dbw->expr( 'rc_timestamp', '>=', $start ),
				$dbw->expr( 'rc_timestamp', '<=', $end ),
			] )
			->caller( __METHOD__ )->fetchResultSet();

		foreach ( $res as $row ) {
			$this->updateSearchIndexForPage( (int)$row->rc_cur_id );
		}
		$this->output( "Done\n" );
	}

	/**
	 * Update the searchindex table for a given pageid
	 * @param int $pageId The page ID to update.
	 * @return null|string
	 */
	private function updateSearchIndexForPage( int $pageId ) {
		// Get current revision
		$rev = $this->getServiceContainer()
			->getRevisionLookup()
			->getRevisionByPageId( $pageId, 0, IDBAccessObject::READ_LATEST );
		$title = null;
		if ( $rev ) {
			$titleObj = Title::newFromPageIdentity( $rev->getPage() );
			$title = $titleObj->getPrefixedDBkey();
			$this->output( "$title..." );
			# Update searchindex
			$u = new SearchUpdate( $pageId, $titleObj, $rev->getContent( SlotRecord::MAIN ) );
			$u->doUpdate();
			$this->output( "\n" );
		}

		return $title;
	}
}

// @codeCoverageIgnoreStart
$maintClass = UpdateSearchIndex::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
