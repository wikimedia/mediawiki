<?php
/**
 * Control or view paused jobs.
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
 * Control or view paused jobs.
 *
 * @ingroup Maintenance
 */
class PauseJobs extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Control or view paused jobs.";
		$this->addArg( 'names', 'Comma separated list of jobs to pause', false );
		$this->addOption( 'list', 'List paused jobs.  Names not required.' );
		$this->addOption( 'unpause', 'Unpause the named jobs.  Names required.' );
	}

	public function execute() {
		if ( $this->getOption( 'list' ) ) {
			$pausedJobs = JobQueueGroup::singleton()->loadPausedJobs();
			foreach ( $pausedJobs as $pausedJob ) {
				$this->output( "$pausedJob\n" );
			}
			return;
		}
		$names = $this->getArg( 0 );
		if ( !$names ) {
			$this->error( 'Names required unless in --list mode.', 1 );
		}
		$names = explode( ',', $names );
		foreach ( $names as $name ) {
			if ( $this->getOption( 'unpause' ) ) {
				JobQueueGroup::singleton()->unpauseJob( $name );
			} else {
				JobQueueGroup::singleton()->pauseJob( $name );
			}
		}
	}
}

$maintClass = 'PauseJobs';
require_once RUN_MAINTENANCE_IF_MAIN;
