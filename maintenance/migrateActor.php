<?php
/**
 * Migrate actor refs from pre-1.30 columns to the 'actor' table
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

use Wikimedia\Rdbms\IDatabase;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that migrates actor refs from pre-1.30 columns to the
 * 'actor' table
 *
 * @ingroup Maintenance
 */
class MigrateActor extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Migrates actor refs from pre-1.30 columns to the \'actor\' table' );
		$this->setBatchSize( 100 );
	}

	protected function getUpdateKey() {
		return __CLASS__;
	}

	protected function updateSkippedMessage() {
		return 'actor already migrated.';
	}

	protected function doDBUpdates() {
		// TODO: do stuff
		return true;
	}
}

$maintClass = "MigrateComments";
require_once RUN_MAINTENANCE_IF_MAIN;
