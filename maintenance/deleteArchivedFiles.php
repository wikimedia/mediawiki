<?php
/**
 * Delete archived (non-current) files from the database
 *
 * Based on deleteOldRevisions.php by Rob Church.
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
 * @author Aaron Schulz
 */

require_once __DIR__ . '/Maintenance.php';
require_once __DIR__ . '/deleteArchivedFiles.inc';

/**
 * Maintenance script to delete archived (non-current) files from the database.
 *
 * @ingroup Maintenance
 */
class DeleteArchivedFiles extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Deletes all archived images.";
		$this->addOption( 'delete', 'Perform the deletion' );
		$this->addOption( 'force', 'Force deletion of rows from filearchive' );
	}

	public function handleOutput( $str ) {
		return $this->output( $str );
	}

	public function execute() {
		if ( !$this->hasOption( 'delete' ) ) {
			$this->output( "Use --delete to actually confirm this script\n" );
			return;
		}
		$force = $this->hasOption( 'force' );
		DeleteArchivedFilesImplementation::doDelete( $this, $force );
	}
}

$maintClass = "DeleteArchivedFiles";
require_once RUN_MAINTENANCE_IF_MAIN;
