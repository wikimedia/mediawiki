<?php
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
use MediaWiki\Revision\SlotRecord;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script for periodic off-peak updating of the search index.
 *
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

	private function doUpdateSearchIndex( $start, $end ) {
		global $wgDisableSearchUpdate;

		$wgDisableSearchUpdate = false;

		$dbw = $this->getDB( DB_MASTER );

		$this->output( "Updating searchindex between $start and $end\n" );

		# Select entries from recentchanges which are on top and between the specified times
		$start = $dbw->timestamp( $start );
		$end = $dbw->timestamp( $end );

		$res = $dbw->select(
			[ 'recentchanges', 'page' ],
			'rc_cur_id',
			[
				'rc_type != ' . $dbw->addQuotes( RC_LOG ),
				'rc_timestamp BETWEEN ' . $dbw->addQuotes( $start ) . ' AND ' . $dbw->addQuotes( $end )
			],
			__METHOD__,
			[],
			[
				'page' => [ 'JOIN', 'rc_cur_id=page_id AND rc_this_oldid=page_latest' ]
			]
		);

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
	public function updateSearchIndexForPage( int $pageId ) {
		// Get current revision
		$rev = MediaWikiServices::getInstance()
			->getRevisionLookup()
			->getRevisionByPageId( $pageId, 0, IDBAccessObject::READ_LATEST );
		$title = null;
		if ( $rev ) {
			$titleObj = Title::newFromLinkTarget( $rev->getPageAsLinkTarget() );
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

$maintClass = UpdateSearchIndex::class;
require_once RUN_MAINTENANCE_IF_MAIN;
