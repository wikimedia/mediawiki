<?php
/**
 * Convert from the old links schema (string->ID) to the new schema (ID->ID).
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
 * Maintenance script to convert from the old links schema (string->ID)
 * to the new schema (ID->ID).
 *
 * The wiki should be put into read-only mode while this script executes.
 *
 * @ingroup Maintenance
 */
class ConvertLinks extends Maintenance {
	private $logPerformance;

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Convert from the old links schema (string->ID) to the new schema (ID->ID).
The wiki should be put into read-only mode while this script executes";

		$this->addArg( 'logperformance', "Log performance to perfLogFilename.", false );
		$this->addArg( 'perfLogFilename', "Filename where performance is logged if --logperformance was set (defaults to 'convLinksPerf.txt').", false );
		$this->addArg( 'keep-links-table', "Don't overwrite the old links table with the new one, leave the new table at links_temp.", false );
		$this->addArg( 'nokeys', "Don't create keys, and so allow duplicates in the new links table.\n
This gives a huge speed improvement for very large links tables which are MyISAM." /* (What about InnoDB?) */, false );
	}

	public function getDbType() {
		return Maintenance::DB_ADMIN;
	}

	public function execute() {
		$dbw = wfGetDB( DB_MASTER );

		$type = $dbw->getType();
		if ( $type != 'mysql' ) {
			$this->output( "Link table conversion not necessary for $type\n" );
			return;
		}

		global $wgContLang;

		$numBadLinks = $curRowsRead = 0; # counters etc
		$totalTuplesInserted = 0; # total tuples INSERTed into links_temp

		$reportCurReadProgress = true; # whether or not to give progress reports while reading IDs from cur table
		$curReadReportInterval = 1000; # number of rows between progress reports

		$reportLinksConvProgress = true; # whether or not to give progress reports during conversion
		$linksConvInsertInterval = 1000; # number of rows per INSERT

		$initialRowOffset = 0;
		# $finalRowOffset = 0; # not used yet; highest row number from links table to process

		$overwriteLinksTable = !$this->hasOption( 'keep-links-table' );
		$noKeys = $this->hasOption( 'noKeys' );
		$this->logPerformance = $this->hasOption( 'logperformance' );
		$perfLogFilename = $this->getArg( 'perfLogFilename', "convLinksPerf.txt" );

		# --------------------------------------------------------------------

		list( $cur, $links, $links_temp, $links_backup ) = $dbw->tableNamesN( 'cur', 'links', 'links_temp', 'links_backup' );

		if ( $dbw->tableExists( 'pagelinks' ) ) {
			$this->output( "...have pagelinks; skipping old links table updates\n" );
			return;
		}

		$res = $dbw->query( "SELECT l_from FROM $links LIMIT 1" );
		if ( $dbw->fieldType( $res, 0 ) == "int" ) {
			$this->output( "Schema already converted\n" );
			return;
		}

		$res = $dbw->query( "SELECT COUNT(*) AS count FROM $links" );
		$row = $dbw->fetchObject( $res );
		$numRows = $row->count;
		$dbw->freeResult( $res );

		if ( $numRows == 0 ) {
			$this->output( "Updating schema (no rows to convert)...\n" );
			$this->createTempTable();
		} else {
			$fh = false;
			if ( $this->logPerformance ) {
				$fh = fopen ( $perfLogFilename, "w" );
				if ( !$fh ) {
					$this->error( "Couldn't open $perfLogFilename" );
					$this->logPerformance = false;
				}
			}
			$baseTime = $startTime = $this->getMicroTime();
			# Create a title -> cur_id map
			$this->output( "Loading IDs from $cur table...\n" );
			$this->performanceLog ( $fh, "Reading $numRows rows from cur table...\n" );
			$this->performanceLog ( $fh, "rows read vs seconds elapsed:\n" );

			$dbw->bufferResults( false );
			$res = $dbw->query( "SELECT cur_namespace,cur_title,cur_id FROM $cur" );
			$ids = array();

			foreach ( $res as $row ) {
				$title = $row->cur_title;
				if ( $row->cur_namespace ) {
					$title = $wgContLang->getNsText( $row->cur_namespace ) . ":$title";
				}
				$ids[$title] = $row->cur_id;
				$curRowsRead++;
				if ( $reportCurReadProgress ) {
					if ( ( $curRowsRead % $curReadReportInterval ) == 0 ) {
						$this->performanceLog( $fh, $curRowsRead . " " . ( $this->getMicroTime() - $baseTime ) . "\n" );
						$this->output( "\t$curRowsRead rows of $cur table read.\n" );
					}
				}
			}
			$dbw->freeResult( $res );
			$dbw->bufferResults( true );
			$this->output( "Finished loading IDs.\n\n" );
			$this->performanceLog( $fh, "Took " . ( $this->getMicroTime() - $baseTime ) . " seconds to load IDs.\n\n" );

			# --------------------------------------------------------------------

			# Now, step through the links table (in chunks of $linksConvInsertInterval rows),
			# convert, and write to the new table.
			$this->createTempTable();
			$this->performanceLog( $fh, "Resetting timer.\n\n" );
			$baseTime = $this->getMicroTime();
			$this->output( "Processing $numRows rows from $links table...\n" );
			$this->performanceLog( $fh, "Processing $numRows rows from $links table...\n" );
			$this->performanceLog( $fh, "rows inserted vs seconds elapsed:\n" );

			for ( $rowOffset = $initialRowOffset; $rowOffset < $numRows; $rowOffset += $linksConvInsertInterval ) {
				$sqlRead = "SELECT * FROM $links ";
				$sqlRead = $dbw->limitResult( $sqlRead, $linksConvInsertInterval, $rowOffset );
				$res = $dbw->query( $sqlRead );
				if ( $noKeys ) {
					$sqlWrite = array( "INSERT INTO $links_temp (l_from,l_to) VALUES " );
				} else {
					$sqlWrite = array( "INSERT IGNORE INTO $links_temp (l_from,l_to) VALUES " );
				}

				$tuplesAdded = 0; # no tuples added to INSERT yet
				foreach ( $res as $row ) {
					$fromTitle = $row->l_from;
					if ( array_key_exists( $fromTitle, $ids ) ) { # valid title
						$from = $ids[$fromTitle];
						$to = $row->l_to;
						if ( $tuplesAdded != 0 ) {
							$sqlWrite[] = ",";
						}
						$sqlWrite[] = "($from,$to)";
						$tuplesAdded++;
					} else { # invalid title
						$numBadLinks++;
					}
				}
				$dbw->freeResult( $res );
				# $this->output( "rowOffset: $rowOffset\ttuplesAdded: $tuplesAdded\tnumBadLinks: $numBadLinks\n" );
				if ( $tuplesAdded != 0 ) {
					if ( $reportLinksConvProgress ) {
						$this->output( "Inserting $tuplesAdded tuples into $links_temp..." );
					}
					$dbw->query( implode( "", $sqlWrite ) );
					$totalTuplesInserted += $tuplesAdded;
					if ( $reportLinksConvProgress ) {
						$this->output( " done. Total $totalTuplesInserted tuples inserted.\n" );
						$this->performanceLog( $fh, $totalTuplesInserted . " " . ( $this->getMicroTime() - $baseTime ) . "\n" );
					}
				}
			}
			$this->output( "$totalTuplesInserted valid titles and $numBadLinks invalid titles were processed.\n\n" );
			$this->performanceLog( $fh, "$totalTuplesInserted valid titles and $numBadLinks invalid titles were processed.\n" );
			$this->performanceLog( $fh, "Total execution time: " . ( $this->getMicroTime() - $startTime ) . " seconds.\n" );
			if ( $this->logPerformance ) {
				fclose ( $fh );
			}
		}
		# --------------------------------------------------------------------

		if ( $overwriteLinksTable ) {
			# Check for existing links_backup, and delete it if it exists.
			$this->output( "Dropping backup links table if it exists..." );
			$dbw->query( "DROP TABLE IF EXISTS $links_backup", __METHOD__ );
			$this->output( " done.\n" );

			# Swap in the new table, and move old links table to links_backup
			$this->output( "Swapping tables '$links' to '$links_backup'; '$links_temp' to '$links'..." );
			$dbw->query( "RENAME TABLE links TO $links_backup, $links_temp TO $links", __METHOD__ );
			$this->output( " done.\n\n" );

			$this->output( "Conversion complete. The old table remains at $links_backup;\n" );
			$this->output( "delete at your leisure.\n" );
		} else {
			$this->output( "Conversion complete.  The converted table is at $links_temp;\n" );
			$this->output( "the original links table is unchanged.\n" );
		}
	}

	private function createTempTable() {
		$dbConn = wfGetDB( DB_MASTER );

		if ( !( $dbConn->isOpen() ) ) {
			$this->output( "Opening connection to database failed.\n" );
			return;
		}
		$links_temp = $dbConn->tableName( 'links_temp' );

		$this->output( "Dropping temporary links table if it exists..." );
		$dbConn->query( "DROP TABLE IF EXISTS $links_temp" );
		$this->output( " done.\n" );

		$this->output( "Creating temporary links table..." );
		if ( $this->hasOption( 'noKeys' ) ) {
			$dbConn->query( "CREATE TABLE $links_temp ( " .
			"l_from int(8) unsigned NOT NULL default '0', " .
			"l_to int(8) unsigned NOT NULL default '0')" );
		} else {
			$dbConn->query( "CREATE TABLE $links_temp ( " .
			"l_from int(8) unsigned NOT NULL default '0', " .
			"l_to int(8) unsigned NOT NULL default '0', " .
			"UNIQUE KEY l_from(l_from,l_to), " .
			"KEY (l_to))" );
		}
		$this->output( " done.\n\n" );
	}

	private function performanceLog( $fh, $text ) {
		if ( $this->logPerformance ) {
			fwrite( $fh, $text );
		}
	}

	private function getMicroTime() { # return time in seconds, with microsecond accuracy
		list( $usec, $sec ) = explode( " ", microtime() );
		return ( (float)$usec + (float)$sec );
	}
}

$maintClass = "ConvertLinks";
require_once RUN_MAINTENANCE_IF_MAIN;
