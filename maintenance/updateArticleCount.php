<?php
/**
 * Maintenance script to provide a better count of the number of articles
 * and update the site statistics table, if desired
 *
 * @ingroup Maintenance
 * @author Rob Church <robchur@gmail.com>
 */

require_once( "Maintenance.php" );

class UpdateArticleCount extends Maintenance {

	// Content namespaces
	private $namespaces;

	public function __construct() {
		global $wgContentNamespaces;
		parent::__construct();
		$this->mDescription = "Count of the number of articles and update the site statistics table";
		$this->addParam( 'update', 'Update the site_stats table with the new count' );
		$this->namespaces = $wgContentNamespaces;
	}

	public function execute() {
		$this->output( "Counting articles..." );
		$result = $this->count();
	
		if( $result !== false ) {
			$this->output( "found {$result}.\n" );
			if( isset( $options['update'] ) && $options['update'] ) {
				$this->output( "Updating site statistics table... " );
				$dbw = wfGetDB( DB_MASTER );
				$dbw->update( 'site_stats', array( 'ss_good_articles' => $result ), array( 'ss_row_id' => 1 ), __METHOD__ );
				$this->output( "done.\n" );
			} else {
				$this->output( "To update the site statistics table, run the script with the --update option.\n" );
			}
		} else {
			$this->output( "failed.\n" );
		}
	}

	/**
	 * Produce a comma-delimited set of namespaces
	 * Includes paranoia
	 *
	 * @return string
	 */
	private function makeNsSet() {
		foreach( $this->namespaces as $namespace )
			$namespaces[] = intval( $namespace );
		return implode( ', ', $namespaces );
	}

	/**
	 * Produce SQL for the query
	 *
	 * @param $dbr Database handle
	 * @return string
	 */
	private function makeSql( $dbr ) {
		list( $page, $pagelinks ) = $dbr->tableNamesN( 'page', 'pagelinks' );
		$nsset = $this->makeNsSet();
		return "SELECT COUNT(DISTINCT page_namespace, page_title) AS pagecount " .
			"FROM $page, $pagelinks " .
			"WHERE pl_from=page_id and page_namespace IN ( $nsset ) " .
			"AND page_is_redirect = 0 AND page_len > 0";
	}

	/**
	 * Count the number of valid content pages in the wiki
	 *
	 * @return mixed Integer, or false if there's a problem
	 */
	private function count() {
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->query( $this->makeSql( $dbr ), __METHOD__ );
		$row = $dbr->fetchObject( $res );
		$dbr->freeResult( $res );
		return $row->pagecount;
	}
}

$maintClass = "UpdateArticleCount";
require_once( DO_MAINTENANCE );
