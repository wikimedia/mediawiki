<?php
/**
 * Generic table cleanup class. Already subclasses maintenance
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
 * @defgroup Wikimedia
 * @ingroup Maintenance
 */

require_once( dirname(__FILE__) . '/Maintenance.php' );

abstract class TableCleanup extends Maintenance {
	protected $targetTable = 'page';
	protected $dryrun = false;
	protected $maxLag = 10; # if slaves are lagged more than 10 secs, wait

	public function __construct() {
		parent::__construct();
		$this->addOption( 'dry-run', 'Perform a dry run' );
	}

	public function execute() {
		global $wgUser;
		$wgUser->setName( 'Conversion script' );
		$this->dryrun = $this->hasOption( 'dry-run' );
		if( $this->dryrun ) {
			$this->output( "Checking for bad titles...\n" );
		} else {
			$this->output( "Checking and fixing bad titles...\n" );
		}
		$this->runTable( $this->targetTable,
			'', //'WHERE page_namespace=0',
			array( $this, 'processPage' ) );
	}

	protected function init( $count, $table ) {
		$this->processed = 0;
		$this->updated = 0;
		$this->count = $count;
		$this->startTime = wfTime();
		$this->table = $table;
	}

	protected function progress( $updated ) {
		$this->updated += $updated;
		$this->processed++;
		if( $this->processed % 100 != 0 ) {
			return;
		}
		$portion = $this->processed / $this->count;
		$updateRate = $this->updated / $this->processed;

		$now = wfTime();
		$delta = $now - $this->startTime;
		$estimatedTotalTime = $delta / $portion;
		$eta = $this->startTime + $estimatedTotalTime;

		$this->output( 
			sprintf( "%s %s: %6.2f%% done on %s; ETA %s [%d/%d] %.2f/sec <%.2f%% updated>\n",
				wfWikiID(),
				wfTimestamp( TS_DB, intval( $now ) ),
				$portion * 100.0,
				$this->table,
				wfTimestamp( TS_DB, intval( $eta ) ),
				$this->processed,
				$this->count,
				$this->processed / $delta,
				$updateRate * 100.0 
			)
		);
		flush();
	}

	protected function runTable( $table, $where, $callback ) {
		$dbr = wfGetDB( DB_SLAVE );
		
		$count = $dbr->selectField( $table, 'count(*)', '', __METHOD__ );
		$this->init( $count, $table );
		$this->output( "Processing $table..." );

		// Unbuffered queries, avoids OOM
		$dbr->bufferResults( false );
		
		$tableName = $dbr->tableName( $table );
		$sql = "SELECT * FROM $tableName $where";
		$result = $dbr->query( $sql, __METHOD__ );

		foreach( $result as $row ) {
			call_user_func( $callback, $row );
		}
		
		$this->output( "Finished $table... $this->updated of $this->processed rows updated\n" );
		
		$result->free();
		
		$dbr->bufferResults( true );
	}

	protected function hexChar( $matches ) {
		return sprintf( "\\x%02x", ord( $matches[1] ) );
	}
	
	abstract protected function processPage( $row );
}
