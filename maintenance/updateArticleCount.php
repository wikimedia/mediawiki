<?php
/**
 * Maintenance script to provide a better count of the number of articles
 * and update the site statistics table, if desired
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
 * @author Rob Church <robchur@gmail.com>
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class UpdateArticleCount extends Maintenance {

	// Content namespaces
	private $namespaces;

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Count of the number of articles and update the site statistics table";
		$this->addOption( 'update', 'Update the site_stats table with the new count' );
	}

	public function execute() {
		global $wgContentNamespaces;
		$this->namespaces = $wgContentNamespaces;
		$this->output( "Counting articles..." );
		$result = $this->count();

		if ( $result !== false ) {
			$this->output( "found {$result}.\n" );
			if ( $this->hasOption( 'update' ) ) {
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
		foreach ( $this->namespaces as $namespace )
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
		return "SELECT COUNT(DISTINCT page_id) AS pagecount " .
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
		return $row ? $row->pagecount : false;
	}
}

$maintClass = "UpdateArticleCount";
require_once( RUN_MAINTENANCE_IF_MAIN );
