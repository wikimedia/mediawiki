<?php
/**
 * Send SQL queries from the specified file to the database, performing
 * variable replacement along the way.
 *
 * @file
 * @ingroup Database Maintenance
 */

require_once( "Maintenance.php" );

class MwSql extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Send SQL queries to a MediaWiki database";
	}

	public function execute() {
		if ( $this->hasArg() ) {
			$fileName = $this->getArg();
			$file = fopen( $fileName, 'r' );
			$promptCallback = false;
		} else {
			$file = $this->getStdin();
			$promptObject = new SqlPromptPrinter( "> " );
			$promptCallback = $promptObject->cb();
		}
	
		if ( !$file )
			$this->error( "Unable to open input file\n", true );

		$dbw = wfGetDB( DB_MASTER );
		$error = $dbw->sourceStream( $file, $promptCallback, array( $this, 'sqlPrintResult' ) );
		if ( $error !== true ) {
			$this->error( $error, true );
		} else {
			exit( 0 );
		}
	}

	/**
	 * Print the results, callback for $db->sourceStream()
	 * @param $res The results object
	 * @param $db Database object
	 */
	public function sqlPrintResult( $res, $db ) {
		if ( !$res ) {
			// Do nothing
		} elseif ( is_object( $res ) && $res->numRows() ) {
			while ( $row = $res->fetchObject() ) {
				$this->output( print_r( $row, true ) );
			}
		} else {
			$affected = $db->affectedRows();
			$this->output( "Query OK, $affected row(s) affected\n" );
		}
	}
}

class SqlPromptPrinter {
	function __construct( $prompt ) {
		$this->prompt = $prompt;
	}

	function cb() {
		return array( $this, 'printPrompt' );
	}

	function printPrompt() {
		echo $this->prompt;
	}
}

$maintClass = "MwSql";
require_once( DO_MAINTENANCE );
