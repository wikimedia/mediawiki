<?php
/**
 * Based on runJobs.php
 *
 * Report number of jobs currently waiting in master database.
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
 * @author Tim Starling
 * @author Antoine Musso
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class ShowJobs extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Show number of jobs waiting in master database";
		$this->addOption( 'group', 'Show number of jobs per job type' );
	}
	public function execute() {
		$dbw = wfGetDB( DB_MASTER );
		if ( $this->hasOption( 'group' ) ) {
			$res = $dbw->select(
				'job',
				array( 'job_cmd', 'count(*) as count' ),
				array(),
				__METHOD__,
				array( 'GROUP BY' => 'job_cmd' )
			);
			foreach ( $res as $row ) {
				$this->output( $row->job_cmd . ': ' . $row->count . "\n" );
			}
		} else {
			$this->output( $dbw->selectField( 'job', 'count(*)', '', __METHOD__ ) . "\n" );
		}
	}
}

$maintClass = "ShowJobs";
require_once( RUN_MAINTENANCE_IF_MAIN );
