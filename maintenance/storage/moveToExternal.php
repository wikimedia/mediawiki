<?php
/**
 * Move revision's text to external storage
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
 * @ingroup Maintenance ExternalStorage
 */

require_once __DIR__ . '/../Maintenance.php';

/**
 * Move revisions' text to external storage
 * @ingroup Maintenance ExternalStorage
 */
class MoveToExternal extends Maintenance {
	const REPORTING_INTERVAL = 1;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Move revisions\' text to external storage' );
		$this->addOption( 's', 'Starting revision ID', false, true );
		$this->addOption( 'e', 'Starting revision ID', false, true );
		$this->addOption( 'cluster', 'ExternalStore cluster', true, true );
		$this->setBatchSize( 1000 );
	}

	public function execute() {
		$dbw = wfGetDB( DB_MASTER );
		$dbr = wfGetDB( DB_REPLICA );

		$minID = $this->getOption( 's', 1 );
		if ( $this->hasOption( 'e' ) ) {
			$maxID = $this->getOption( 'e' );
		} else {
			$maxID = $dbw->selectField( 'text', 'MAX(old_id)', '', __METHOD__ );
		}
		$cluster = $this->getOption( 'cluster' );

		$exists = $dbr->selectField(
			'text',
			'1',
			[
				"old_id BETWEEN $minID AND $maxID",
				'old_flags' . $dbr->buildLike( $dbr->anyString(), 'object', $dbr->anyString() ),
			],
			__METHOD__
		);
		if ( $exists ) {
			$this->fatalError( "Integrity check failed: found objects in your text table." .
				" Run migrateHistoryBlobs.php to fix this." );
		}

		$count = $maxID - $minID + 1;
		$blockSize = $this->getBatchSize();
		$numBlocks = ceil( $count / $blockSize );
		$this->output( "Moving text rows from $minID to $maxID to external storage\n" );
		$ext = new ExternalStoreDB;
		$numMoved = 0;

		for ( $block = 0; $block < $numBlocks; $block++ ) {
			$blockStart = $block * $blockSize + $minID;
			$blockEnd = $blockStart + $blockSize - 1;

			if ( !( $block % self::REPORTING_INTERVAL ) ) {
				$this->output( "oldid=$blockStart, moved=$numMoved\n" );
				wfWaitForSlaves();
			}

			$res = $dbr->select( 'text', [ 'old_id', 'old_flags', 'old_text' ],
				[
					"old_id BETWEEN $blockStart AND $blockEnd",
					'old_flags NOT ' . $dbr->buildLike( $dbr->anyString(), 'external', $dbr->anyString() ),
				], __METHOD__ );
			foreach ( $res as $row ) {
				# Resolve stubs
				$text = $row->old_text;
				$id = $row->old_id;
				if ( $row->old_flags === '' ) {
					$flags = 'external';
				} else {
					$flags = "{$row->old_flags},external";
				}

				if ( strlen( $text ) < 100 ) {
					// Don't move tiny revisions
					continue;
				}

				# $this->output( "Storing "  . strlen( $text ) . " bytes to $url\n" );
				# $this->output( "old_id=$id\n" );

				$url = $ext->store( $cluster, $text );
				if ( !$url ) {
					$this->fatalError( "Error writing to external storage\n" );
				}
				$dbw->update( 'text',
					[ 'old_flags' => $flags, 'old_text' => $url ],
					[ 'old_id' => $id ], __METHOD__ );
				$numMoved++;
			}
		}
	}

}

$maintClass = 'MoveToExternal';
require_once RUN_MAINTENANCE_IF_MAIN;
