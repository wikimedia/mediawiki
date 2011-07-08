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
 * @file
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class TableCleanup extends Maintenance {
	protected $defaultParams = array(
		'table' => 'page',
		'conds' => array(),
		'index' => 'page_id',
		'callback' => 'processRow',
	);

	protected $dryrun = false;
	protected $maxLag = 10; # if slaves are lagged more than 10 secs, wait
	public $batchSize = 100;
	public $reportInterval = 100;

	public function __construct() {
		parent::__construct();
		$this->addOption( 'dry-run', 'Perform a dry run' );
	}

	public function execute() {
		global $wgUser;
		$wgUser->setName( 'Conversion script' );
		$this->dryrun = $this->hasOption( 'dry-run' );
		if ( $this->dryrun ) {
			$this->output( "Checking for bad titles...\n" );
		} else {
			$this->output( "Checking and fixing bad titles...\n" );
		}
		$this->runTable( $this->defaultParams );
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
		if ( $this->processed % $this->reportInterval != 0 ) {
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

	public function runTable( $params ) {
		$dbr = wfGetDB( DB_SLAVE );

		if ( array_diff( array_keys( $params ),
			array( 'table', 'conds', 'index', 'callback' ) ) )
		{
			throw new MWException( __METHOD__ . ': Missing parameter ' . implode( ', ', $params ) );
		}

		$table = $params['table'];
		// count(*) would melt the DB for huge tables, we can estimate here
		$count = $dbr->estimateRowCount( $table, '*', '', __METHOD__ );
		$this->init( $count, $table );
		$this->output( "Processing $table...\n" );


		$index = (array)$params['index'];
		$indexConds = array();
		$options = array(
			'ORDER BY' => implode( ',', $index ),
			'LIMIT' => $this->batchSize
		);
		$callback = array( $this, $params['callback'] );

		while ( true ) {
			$conds = array_merge( $params['conds'], $indexConds );
			$res = $dbr->select( $table, '*', $conds, __METHOD__, $options );
			if ( !$res->numRows() ) {
				// Done
				break;
			}

			foreach ( $res as $row ) {
				call_user_func( $callback, $row );
			}

			if ( $res->numRows() < $this->batchSize ) {
				// Done
				break;
			}

			// Update the conditions to select the next batch.
			// Construct a condition string by starting with the least significant part
			// of the index, and adding more significant parts progressively to the left
			// of the string.
			$nextCond = '';
			foreach ( array_reverse( $index ) as $field ) {
				$encValue = $dbr->addQuotes( $row->$field );
				if ( $nextCond === '' ) {
					$nextCond = "$field > $encValue";
				} else {
					$nextCond = "$field > $encValue OR ($field = $encValue AND ($nextCond))";
				}
			}
			$indexConds = array( $nextCond );
		}

		$this->output( "Finished $table... $this->updated of $this->processed rows updated\n" );
	}

	protected function hexChar( $matches ) {
		return sprintf( "\\x%02x", ord( $matches[1] ) );
	}
}

class TableCleanupTest extends TableCleanup {
	function processRow( $row ) {
		$this->progress( mt_rand( 0, 1 ) );
	}
}

