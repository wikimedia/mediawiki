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

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Count of the number of articles and update the site statistics table";
		$this->addOption( 'update', 'Update the site_stats table with the new count' );
	}

	public function execute() {
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
	 * Count the number of valid content pages in the wiki
	 *
	 * @return mixed Integer, or false if there's a problem
	 */
	private function count() {
		return wfGetDB( DB_SLAVE )->selectField(
			array( 'page', 'pagelinks' ),
			'COUNT(DISTINCT page_id)',
			array(
				'pl_from=page_id',
				'page_namespace' => MWNamespace::getContentNamespaces(),
				'page_is_redirect' => 0,
				'page_len > 0',
			),
			__METHOD__
		);
	}
}

$maintClass = "UpdateArticleCount";
require_once( RUN_MAINTENANCE_IF_MAIN );
