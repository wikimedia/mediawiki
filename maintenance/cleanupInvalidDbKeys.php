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

use MediaWiki\MediaWikiServices;

/**
 * Maintenance script that cleans up invalid titles in various tables.
 *
 * @since 1.29
 * @ingroup Maintenance
 */
class CleanupInvalidDbKeys extends Maintenance {
	/** @var array[] List of tables to clean up, and the field prefix for that table */
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

The script runs with the expectation that STDOUT is redirected to a file.
TEXT
		);
		$this->addOption( 'fix', 'Actually clean up invalid titles. If this parameter is ' .
			'not specified, the script will report invalid titles but not clean them up.',
			false, false );
		$this->addOption( 'table', 'The table(s) to process. This option can be specified ' .
			'more than once (e.g. -t category -t watchlist). If not specified, all available ' .
			'tables will be processed. Available tables are: ' .
			implode( ', ', array_column( static::$tables, 0 ) ), false, true, 't', true );

		$this->setBatchSize( 500 );
	}

	public function execute() {
		$tablesToProcess = $this->getOption( 'table' );
		foreach ( static::$tables as $tableParams ) {
			if ( !$tablesToProcess || in_array( $tableParams[0], $tablesToProcess ) ) {
				$this->cleanupTable( $tableParams );
			}
		}

		$this->outputStatus( 'Done!' );
		if ( $this->hasOption( 'fix' ) ) {
			$dbDomain = WikiMap::getCurrentWikiDbDomain()->getId();
			$this->outputStatus( " Cleaned up invalid DB keys on $dbDomain!\n" );
		}
	}

	/**
	 * Prints text to STDOUT, and STDERR if STDOUT was redirected to a file.
	 * Used for progress reporting.
	 *
	 * @param string $str Text to write to both places
	 * @param string|null $channel Ignored
	 */
	protected function outputStatus( $str, $channel = null ) {
		// Make it easier to find progress lines in the STDOUT log
		if ( trim( $str ) ) {
			fwrite( STDOUT, '*** ' . trim( $str ) . "\n" );
		}
		fwrite( STDERR, $str );
	}

	/**
	 * Prints text to STDOUT. Used for logging output.
	 *
	 * @param string $str Text to write
	 */
	protected function writeToReport( $str ) {
		fwrite( STDOUT, $str );
	}

	/**
	 * Identifies, and optionally cleans up, invalid titles.
	 *
	 * @param array $tableParams A child array of self::$tables
	 */
	protected function cleanupTable( $tableParams ) {
		list( $table, $prefix ) = $tableParams;
		$idField = $tableParams['idField'] ?? "{$prefix}_id";
		$nsField = $tableParams['nsField'] ?? "{$prefix}_namespace";
		$titleField = $tableParams['titleField'] ?? "{$prefix}_title";

		$this->outputStatus( "Looking for invalid $titleField entries in $table...\n" );

		// Do all the select queries on the replicas, as they are slow (they use
		// unanchored LIKEs). Naturally this could cause problems if rows are
		// modified after selecting and before deleting/updating, but working on
		// the hypothesis that invalid rows will be old and in all likelihood
		// unreferenced, we should be fine to do it like this.
		$dbr = $this->getDB( DB_REPLICA, 'vslow' );
		$linksMigration = MediaWikiServices::getInstance()->getLinksMigration();
		$joinConds = [];
		$tables = [ $table ];
		if ( isset( $linksMigration::$mapping[$table] ) ) {
			list( $nsField,$titleField ) = $linksMigration->getTitleFields( $table );
			$joinConds = $linksMigration->getQueryInfo( $table )['joins'];
			$tables = $linksMigration->getQueryInfo( $table )['tables'];
		}

		// Find all TitleValue-invalid titles.
		$percent = $dbr->anyString();
		$res = $dbr->newSelectQueryBuilder()
			->select( [
				'id' => $idField,
				'ns' => $nsField,
				'title' => $titleField,
			] )
			->tables( $tables )
			// The REGEXP operator is not cross-DBMS, so we have to use lots of LIKEs
			->where( $dbr->makeList( [
				$titleField . $dbr->buildLike( $percent, ' ', $percent ),
				$titleField . $dbr->buildLike( $percent, "\r", $percent ),
				$titleField . $dbr->buildLike( $percent, "\n", $percent ),
				$titleField . $dbr->buildLike( $percent, "\t", $percent ),
				$titleField . $dbr->buildLike( '_', $percent ),
				$titleField . $dbr->buildLike( $percent, '_' ),
			], LIST_OR ) )
			->joinConds( $joinConds )
			->limit( $this->getBatchSize() )
			->caller( __METHOD__ )
			->fetchResultSet();

		$this->outputStatus( "Number of invalid rows: " . $res->numRows() . "\n" );
		if ( !$res->numRows() ) {
			$this->outputStatus( "\n" );
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
				$this->outputStatus( "Run with --fix to clean up these rows\n" );
			}
			$this->outputStatus( "\n" );
			return;
		}

		$services = MediaWikiServices::getInstance();
		$lbFactory = $services->getDBLoadBalancerFactory();

		// Fix the bad data, using different logic for the various tables
		$dbw = $this->getDB( DB_PRIMARY );
		switch ( $table ) {
			case 'page':
			case 'redirect':
				// This shouldn't happen on production wikis, and we already have a script
				// to handle 'page' rows anyway, so just notify the user and let them decide
				// what to do next.
				$this->outputStatus( <<<TEXT
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
				$this->outputStatus(
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
				$lbFactory->waitForReplication();
				$this->outputStatus( "Updated $affectedRowCount rows on $table.\n" );

				break;

			case 'recentchanges':
			case 'watchlist':
			case 'category':
				// Since these broken titles can't exist, there's really nothing to watch,
				// nothing can be categorised in them, and they can't have been changed
				// recently, so we can just remove these rows.
				$this->outputStatus( "Deleting invalid $table rows...\n" );
				$dbw->delete( $table, [ $idField => $ids ], __METHOD__ );
				$lbFactory->waitForReplication();
				$this->outputStatus( 'Deleted ' . $dbw->affectedRows() . " rows from $table.\n" );
				break;

			case 'protected_titles':
				// Since these broken titles can't exist, there's really nothing to protect,
				// so we can just remove these rows. Made more complicated by this table
				// not having an ID field
				$this->outputStatus( "Deleting invalid $table rows...\n" );
				$affectedRowCount = 0;
				foreach ( $res as $row ) {
					$dbw->delete( $table,
						[ $nsField => $row->ns, $titleField => $row->title ],
						__METHOD__ );
					$affectedRowCount += $dbw->affectedRows();
				}
				$lbFactory->waitForReplication();
				$this->outputStatus( "Deleted $affectedRowCount rows from $table.\n" );
				break;

			case 'pagelinks':
			case 'templatelinks':
			case 'categorylinks':
				// Update links tables for each page where these bogus links are supposedly
				// located. If the invalid rows don't go away after these jobs go through,
				// they're probably being added by a buggy hook.
				$this->outputStatus( "Queueing link update jobs for the pages in $idField...\n" );
				$linksMigration = MediaWikiServices::getInstance()->getLinksMigration();
				$wikiPageFactory = $services->getWikiPageFactory();
				foreach ( $res as $row ) {
					$wp = $wikiPageFactory->newFromID( $row->id );
					if ( $wp ) {
						RefreshLinks::fixLinksFromArticle( $row->id );
					} else {
						if ( isset( $linksMigration::$mapping[$table] ) ) {
							$conds = $linksMigration->getLinksConditions(
								$table,
								Title::makeTitle( $row->ns, $row->title )
							);
						} else {
							$conds = [ $nsField => $row->ns, $titleField => $row->title ];
						}
						// This link entry points to a nonexistent page, so just get rid of it
						$dbw->delete( $table,
							array_merge( [ $idField => $row->id ], $conds ),
							__METHOD__ );
					}
				}
				$lbFactory->waitForReplication();
				$this->outputStatus( "Link update jobs have been added to the job queue.\n" );
				break;
		}

		$this->outputStatus( "\n" );
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

$maintClass = CleanupInvalidDbKeys::class;
require_once RUN_MAINTENANCE_IF_MAIN;
