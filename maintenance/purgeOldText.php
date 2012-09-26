<?php
/**
 * Purge old text records from the database
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

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that purges old text records from the database.
 *
 * @ingroup Maintenance
 */
class PurgeOldText extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Purge old text records from the database";
		$this->addOption( 'purge', 'Performs the deletion' );
	}

	public function execute() {
		$this->purgeRedundantText( $this->hasOption( 'purge' ) );
	}
}

$maintClass = "PurgeOldText";
require_once RUN_MAINTENANCE_IF_MAIN;
