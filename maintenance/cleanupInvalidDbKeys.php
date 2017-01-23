<?php
/**
 * Cleans up invalid titles in various tables.
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
 * Maintenance script that cleans up invalid titles in various tables.
 *
 * @ingroup Maintenance
 */
class CleanupInvalidDbKeys extends Maintenance {
	/** @var resource */
	protected $reportFile;

	/** @var array List of tables to clean up, and the field prefix for that table */
	protected static $tables = [
		// Data tables
		[ 'page', 'page' ],
		[ 'redirect', 'rd', 'idField' => 'rd_from' ],
		[ 'archive', 'ar' ],
		[ 'logging', 'log' ],
		[ 'protected_titles', 'pt', 'idField' => 0 ],
		[ 'category', 'cat', 'nsField' => 14 ],
		[ 'recentchanges', 'rc' ],
		[ 'watchlist', 'wl' ],
		// The querycache tables' qc(c)_title and qcc_titletwo may contain titles,
		// but also usernames or other things like that, so we leave them alone

		// Links tables
		[ 'pagelinks', 'pl', 'idField' => 'pl_from' ],
		[ 'templatelinks', 'tl', 'idField' => 'tl_from' ],
		[ 'categorylinks', 'cl', 'idField' => 'cl_from', 'nsField' => 14, 'titleField' => 'cl_to' ],
	];

	public function __construct() {
		parent::__construct();
		$this->addDescription( <<<'TEXT'
This script cleans up the title fields in various tables to remove entries that
will be rejected by the constructor of TitleValue.  This constructor throws an
exception when invalid data is encountered, which will not normally occur on
regular page views, but can happen on query special pages.

The script targets titles matching the regular expression /^_|[ \r\n\t]|_$/.
Because any foreign key relationships involving these titles will already be
broken, the titles are corrected to a valid version or the rows are deleted
entirely, depending on the table.
TEXT
		);
		$this->addOption( 'fix', 'Actually clean up invalid titles. If this parameter is ' .
			'not specified, the script will report invalid titles but not clean them up.',
			false, false );
		// We require a log file to write to. The script outputs a list of deleted records,
		// and it's lost forever if it scrolls off the top of your console or if you
		// forget to specify the parameter.
		$this->addOption( 'report-file', '(REQUIRED) File to which a log of all actions ' .
			'will be written', true, true );
		$this->addOption( 'table', 'The table(s) to process. This option can be specified ' .
			'more than once (e.g. -t category -t watchlist). If not specified, all available ' .
			'tables will be processed. Available tables are: ' .
			implode( ', ', array_column( static::$tables, 0 ) ), false, true, 't', true );

		$this->setBatchSize( 500 );
		$this->reportFile = null;
	}

	public function execute() {
		// open the report file
		$reportFileName = $this->getOption( 'report-file' );
		MediaWiki\suppressWarnings();
		$this->reportFile = fopen( $reportFileName, 'wb' );
		MediaWiki\restoreWarnings();
		if ( !$this->reportFile ) {
			$this->error( "Failed to open file $reportFileName", 1 );
		}

		$tablesToProcess = $this->getOption( 'table' );
		foreach ( static::$tables as $tableParams ) {
			if ( !$tablesToProcess || in_array( $tableParams[0], $tablesToProcess ) ) {
				$this->cleanupTable( $tableParams );
			}
		}

		$this->output( "Done, report written to $reportFileName\n" );
	}

	/**
	 * Prints text to stdout AND writes it to the report file.
	 *
	 * @param string $str Text to write to both places
	 * @param string|null $channel
	 */
	protected function output( $str, $channel = null ) {
		parent::output( $str, $channel );
		if ( $this->reportFile ) {
			// Also print the message to the report file
			fwrite( $this->reportFile, trim( $str ) ? ( '*** ' . $str ) : $str );
		}
	}

	/**
	 * Writes text to the report file.
	 *
	 * @param string $str Text to write
	 */
	protected function writeToReport( $str ) {
		fwrite( $this->reportFile, $str );
	}

	/**
	 * Identifies, and optionally cleans up, invalid titles.
	 *
	 * @param array $tableParams A child array of self::$tables
	 */
	protected function cleanupTable( $tableParams ) {
		$table = $tableParams[0];
		$prefix = $tableParams[1];
		$idField = isset( $tableParams['idField'] ) ?
			$tableParams['idField'] :
			"{$prefix}_id";
		$nsField = isset( $tableParams['nsField'] ) ?
			$tableParams['nsField'] :
			"{$prefix}_namespace";
		$titleField = isset( $tableParams['titleField'] ) ?
			$tableParams['titleField'] :
			"{$prefix}_title";

		$this->output( "Looking for invalid $titleField entries in $table...\n" );

		// Do all the select queries on the replicas, as they are slow (they use
		// unanchored LIKEs). Naturally this could cause problems if rows are
		// modified after selecting and before deleting/updating, but working on
		// the hypothesis that invalid rows will be old and in all likelihood
		// unreferenced, we should be fine to do it like this.
		$dbr = $this->getDB( DB_REPLICA, 'vslow' );

		// Find all TitleValue-invalid titles.
		$res = $dbr->select(
			$table,
			[
				'id' => $idField,
				'ns' => $nsField,
				'title' => $titleField,
			],
			// The REGEXP operator is not cross-DBMS, so we have to use lots of LIKEs
			[ $dbr->makeList( [
				"$titleField LIKE '% %'",
				"$titleField LIKE '%\\r%'",
				"$titleField LIKE '%\\n%'",
				"$titleField LIKE '%\\t%'",
				"$titleField LIKE '\\_%'",
				"$titleField LIKE '%\\_'",
			], LIST_OR ) ],
			__METHOD__,
			[ 'LIMIT' => $this->mBatchSize ]
		);

		$this->output( "Number of invalid rows: " . $res->numRows() . "\n" );
		if ( !$res->numRows() ) {
			$this->output( "\n" );
			return;
		}

		// Write a table of titles to the report file. Also keep a list of the found
		// IDs, as we might need it later for DB updates
		$this->writeToReport( sprintf( "%10s |  ns | dbkey\n", $idField ) );
		$ids = [];
		foreach ( $res as $row ) {
			$this->writeToReport( sprintf( "%10d | %3d | %s\n", $row->id, $row->ns, $row->title ) );
			$ids[] = $row->id;
		}

		// If we're doing a dry run, output the new titles we would use for the UPDATE
		// queries (if relevant), and finish
		if ( !$this->hasOption( 'fix' ) ) {
			if ( $table === 'logging' || $table === 'archive' ) {
				$this->writeToReport( "The following updates would be run with the --fix flag:\n" );
				foreach ( $res as $row ) {
					$newTitle = self::makeValidTitle( $row->title );
					$this->writeToReport(
						"$idField={$row->id}: update '{$row->title}' to '$newTitle'\n" );
				}
			}

			if ( $table !== 'page' && $table !== 'redirect' ) {
				$this->output( "Run with --fix to clean up these rows\n" );
			}
			$this->output( "\n" );
			return;
		}

		// Fix the bad data, using different logic for the various tables
		$dbw = $this->getDB( DB_MASTER );
		switch ( $table ) {
			case 'page':
			case 'redirect':
				// This shouldn't happen on production wikis, and we already have a script
				// to handle 'page' rows anyway, so just notify the user and let them decide
				// what to do next.
				$this->output( <<<TEXT
IMPORTANT: This script does not fix invalid entries in the $table table.
Consider repairing these rows, and rows in related tables, by hand.
You may like to run, or borrow logic from, the cleanupTitles.php script.

TEXT
				);
				break;

			case 'archive':
			case 'logging':
				// Rename the title to a corrected equivalent. Any foreign key relationships
				// to the page_title field are already broken, so this will just make sure
				// users can still access the log entries/deleted revisions from the interface
				// using a valid page title.
				$this->output(
					"Updating these rows, setting $titleField to the closest valid DB key...\n" );
				$affectedRowCount = 0;
				foreach ( $res as $row ) {
					$newTitle = self::makeValidTitle( $row->title );
					$this->writeToReport(
						"$idField={$row->id}: updating '{$row->title}' to '$newTitle'\n" );

					$dbw->update( $table,
						[ $titleField => $newTitle ],
						[ $idField => $row->id ],
						__METHOD__ );
					$affectedRowCount += $dbw->affectedRows();
				}
				wfWaitForSlaves();
				$this->output( "Updated $affectedRowCount rows on $table.\n" );

				break;

			case 'recentchanges':
			case 'watchlist':
			case 'category':
				// Since these broken titles can't exist, there's really nothing to watch,
				// nothing can be categorised in them, and they can't have been changed
				// recently, so we can just remove these rows.
				$this->output( "Deleting invalid $table rows...\n" );
				$dbw->delete( $table, [ $idField => $ids ], __METHOD__ );
				wfWaitForSlaves();
				$this->output( 'Deleted ' . $dbw->affectedRows() . " rows from $table.\n" );
				break;

			case 'protected_titles':
				// Since these broken titles can't exist, there's really nothing to protect,
				// so we can just remove these rows. Made more complicated by this table
				// not having an ID field
				$this->output( "Deleting invalid $table rows...\n" );
				$affectedRowCount = 0;
				foreach ( $res as $row ) {
					$dbw->delete( $table,
						[ $nsField => $row->ns, $titleField => $row->title ],
						__METHOD__ );
					$affectedRowCount += $dbw->affectedRows();
				}
				wfWaitForSlaves();
				$this->output( "Deleted $affectedRowCount rows from $table.\n" );
				break;

			case 'pagelinks':
			case 'templatelinks':
			case 'categorylinks':
				// Update links tables for each page where these bogus links are supposedly
				// located. If the invalid rows don't go away after these jobs go through,
				// they're probably being added by a buggy hook.
				$this->output( "Queueing link update jobs for the pages in $idField...\n" );
				foreach ( $res as $row ) {
					$wp = WikiPage::newFromID( $row->id );
					if ( $wp ) {
						RefreshLinks::fixLinksFromArticle( $row->id );
					} else {
						// This link entry points to a nonexistent page, so just get rid of it
						$dbw->delete( $table,
							[ $idField => $row->id, $nsField => $row->ns, $titleField => $row->title ],
							__METHOD__ );
					}
				}
				wfWaitForSlaves();
				$this->output( "Link update jobs have been added to the job queue.\n" );
				break;
		}

		$this->output( "\n" );
		return;
	}

	/**
	 * Fix possible validation issues in the given title (DB key).
	 *
	 * @param string $invalidTitle
	 * @return string
	 */
	protected static function makeValidTitle( $invalidTitle ) {
		return strtr( trim( $invalidTitle, '_' ),
			[ ' ' => '_', "\r" => '', "\n" => '', "\t" => '_' ] );
	}
}

$maintClass = 'CleanupInvalidDbKeys';
require_once RUN_MAINTENANCE_IF_MAIN;
