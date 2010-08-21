<?php
/**
 * Convert from the old links schema (string->ID) to the new schema (ID->ID)
 * The wiki should be put into read-only mode while this script executes
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
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class ConvertLinks extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Convert from the old links schema (string->ID) to the new schema (ID->ID)
The wiki should be put into read-only mode while this script executes";
	}

	public function getDbType() {
		return self::DB_ADMIN;
	}

	public function execute() {
		$dbw = wfGetDB( DB_MASTER );

		$type = $dbw->getType();
		if ( $type != 'mysql' ) {
			$this->output( "Link table conversion not necessary for $type\n" );
			return;
		}

		global $wgLang, $noKeys, $logPerformance, $fh;
	
		$tuplesAdded = $numBadLinks = $curRowsRead = 0; # counters etc
		$totalTuplesInserted = 0; # total tuples INSERTed into links_temp

		$reportCurReadProgress = true; # whether or not to give progress reports while reading IDs from cur table
		$curReadReportInterval = 1000; # number of rows between progress reports

		$reportLinksConvProgress = true; # whether or not to give progress reports during conversion
		$linksConvInsertInterval = 1000; # number of rows per INSERT

		$initialRowOffset = 0;
		# $finalRowOffset = 0; # not used yet; highest row number from links table to process

		# Overwrite the old links table with the new one.  If this is set to false,
		# the new table will be left at links_temp.
		$overwriteLinksTable = true;
	
		# Don't create keys, and so allow duplicates in the new links table.
		# This gives a huge speed improvement for very large links tables which are MyISAM. (What about InnoDB?)
		$noKeys = false;
	
	
		$logPerformance = false; # output performance data to a file
		$perfLogFilename = "convLinksPerf.txt";
		# --------------------------------------------------------------------

		list ( $cur, $links, $links_temp, $links_backup ) = $dbw->tableNamesN( 'cur', 'links', 'links_temp', 'links_backup' );

		if( $dbw->tableExists( 'pagelinks' ) ) {
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
			if ( $logPerformance ) { $fh = fopen ( $perfLogFilename, "w" ); }
			$baseTime = $startTime = $this->getMicroTime();
			# Create a title -> cur_id map
			$this->output( "Loading IDs from $cur table...\n" );
			$this->performanceLog ( "Reading $numRows rows from cur table...\n" );
			$this->performanceLog ( "rows read vs seconds elapsed:\n" );

			$dbw->bufferResults( false );
			$res = $dbw->query( "SELECT cur_namespace,cur_title,cur_id FROM $cur" );
			$ids = array();

			while ( $row = $dbw->fetchObject( $res ) ) {
				$title = $row->cur_title;
				if ( $row->cur_namespace ) {
					$title = $wgLang->getNsText( $row->cur_namespace ) . ":$title";
				}
				$ids[$title] = $row->cur_id;
				$curRowsRead++;
				if ( $reportCurReadProgress ) {
					if ( ( $curRowsRead % $curReadReportInterval ) == 0 ) {
						$this->performanceLog( $curRowsRead . " " . ( $this->getMicroTime() - $baseTime ) . "\n" );
						$this->output( "\t$curRowsRead rows of $cur table read.\n" );
					}
				}
			}
			$dbw->freeResult( $res );
			$dbw->bufferResults( true );
			$this->output( "Finished loading IDs.\n\n" );
			$this->performanceLog( "Took " . ( $this->getMicroTime() - $baseTime ) . " seconds to load IDs.\n\n" );

			# --------------------------------------------------------------------

			# Now, step through the links table (in chunks of $linksConvInsertInterval rows),
			# convert, and write to the new table.
			$this->createTempTable();
			$this->performanceLog( "Resetting timer.\n\n" );
			$baseTime = $this->getMicroTime();
			$this->output( "Processing $numRows rows from $links table...\n" );
			$this->performanceLog( "Processing $numRows rows from $links table...\n" );
			$this->performanceLog( "rows inserted vs seconds elapsed:\n" );
	
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
				while ( $row = $dbw->fetchObject( $res ) ) {
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
				if ( $tuplesAdded != 0  ) {
					if ( $reportLinksConvProgress ) {
						$this->output( "Inserting $tuplesAdded tuples into $links_temp..." );
					}
					$dbw->query( implode( "", $sqlWrite ) );
					$totalTuplesInserted += $tuplesAdded;
					if ( $reportLinksConvProgress )
						$this->output( " done. Total $totalTuplesInserted tuples inserted.\n" );
						$this->performanceLog( $totalTuplesInserted . " " . ( $this->getMicroTime() - $baseTime ) . "\n"  );
				}
			}
			$this->output( "$totalTuplesInserted valid titles and $numBadLinks invalid titles were processed.\n\n" );
			$this->performanceLog( "$totalTuplesInserted valid titles and $numBadLinks invalid titles were processed.\n" );
			$this->performanceLog( "Total execution time: " . ( $this->getMicroTime() - $startTime ) . " seconds.\n" );
			if ( $logPerformance ) { fclose ( $fh ); }
		}
		# --------------------------------------------------------------------

		if ( $overwriteLinksTable ) {
			# Check for existing links_backup, and delete it if it exists.
			$this->output( "Dropping backup links table if it exists..." );
			$dbw->query( "DROP TABLE IF EXISTS $links_backup", DB_MASTER );
			$this->output( " done.\n" );
	
			# Swap in the new table, and move old links table to links_backup
			$this->output( "Swapping tables '$links' to '$links_backup'; '$links_temp' to '$links'..." );
			$dbw->query( "RENAME TABLE links TO $links_backup, $links_temp TO $links", DB_MASTER );
			$this->output( " done.\n\n" );
	
			$dbw->close();
			$this->output( "Conversion complete. The old table remains at $links_backup;\n" );
			$this->output( "delete at your leisure.\n" );
		} else {
			$this->output( "Conversion complete.  The converted table is at $links_temp;\n" );
			$this->output( "the original links table is unchanged.\n" );
		}
	}

	private function createTempTable() {
		global $noKeys;
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
		if ( $noKeys ) {
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

	private function performanceLog( $text ) {
		global $logPerformance, $fh;
		if ( $logPerformance ) {
			fwrite( $fh, $text );
		}
	}

	private function getMicroTime() { # return time in seconds, with microsecond accuracy
		list( $usec, $sec ) = explode( " ", microtime() );
		return ( (float)$usec + (float)$sec );
	}
}

$maintClass = "ConvertLinks";
require_once( DO_MAINTENANCE );
