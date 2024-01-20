<?php
/**
 * Test revision text compression and decompression.
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

use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Title\Title;

require_once __DIR__ . '/../Maintenance.php';

class TestCompression extends Maintenance {
	public function __construct() {
		parent::__construct();

		$this->addArg( 'title', 'The page to test' );

		$this->addOption( 'type', 'The HistoryBlob subclass to use', false, true );
		$this->addOption( 'start', 'The start date', false, true );
		$this->addOption( 'limit', 'Maximum number of revisions to process', false, true );
	}

	public function execute() {
		$lang = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'en' );
		$title = Title::newFromText( $this->getArg( 0 ) );

		if ( $this->hasOption( 'start' ) ) {
			$start = wfTimestamp( TS_MW, strtotime( $this->getOption( 'start' ) ) );
			echo "Starting from " . $lang->timeanddate( $start ) . "\n";
		} else {
			$start = '19700101000000';
		}
		if ( $this->hasOption( 'limit' ) ) {
			$limit = $this->getOption( 'limit' );
			$untilHappy = false;
		} else {
			$limit = 1000;
			$untilHappy = true;
		}
		$type = $this->getOption( 'type', ConcatenatedGzipHistoryBlob::class );

		$dbr = $this->getReplicaDB();

		$revStore = $this->getServiceContainer()->getRevisionStore();
		$res = $revStore->newSelectQueryBuilder( $dbr )
			->joinComment()
			->joinPage()
			->where( [
				'page_namespace' => $title->getNamespace(),
				'page_title' => $title->getDBkey(),
				$dbr->expr( 'rev_timestamp', '>', $dbr->timestamp( $start ) ),
			] )
			->limit( $limit )
			->caller( __FILE__ )->fetchResultSet();

		$blob = new $type;
		$hashes = [];
		$keys = [];
		$uncompressedSize = 0;
		$t = -microtime( true );
		foreach ( $res as $row ) {
			$revRecord = $revStore->newRevisionFromRow( $row );
			$text = $revRecord->getSlot( SlotRecord::MAIN, RevisionRecord::RAW )
				->getContent()
				->serialize();
			$uncompressedSize += strlen( $text );
			$hashes[$row->rev_id] = md5( $text );
			$keys[$row->rev_id] = $blob->addItem( $text );
			if ( $untilHappy && !$blob->isHappy() ) {
				break;
			}
		}

		$serialized = serialize( $blob );
		$t += microtime( true );
		# print_r( $blob->mDiffMap );

		printf( "%s\nCompression ratio for %d revisions: %5.2f, %s -> %d\n",
			$type,
			count( $hashes ),
			$uncompressedSize / strlen( $serialized ),
			$lang->formatSize( $uncompressedSize ),
			strlen( $serialized )
		);
		printf( "Compression time: %5.2f ms\n", $t * 1000 );

		$t = -microtime( true );
		$blob = unserialize( $serialized );
		foreach ( $keys as $id => $key ) {
			$text = $blob->getItem( $key );
			if ( md5( $text ) != $hashes[$id] ) {
				echo "Content hash mismatch for rev_id $id\n";
				# var_dump( $text );
			}
		}
		$t += microtime( true );
		printf( "Decompression time: %5.2f ms\n", $t * 1000 );
	}
}

$maintClass = TestCompression::class;
require_once RUN_MAINTENANCE_IF_MAIN;
