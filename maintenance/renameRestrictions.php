<?php
/**
 * Rename restriction level
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
 * Maintenance script that updates page_restrictions and
 * protected_titles tables to use a new name for a given
 * restriction level.
 *
 * @ingroup Maintenance
 */
class RenameRestrictions extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Rename a restriction level' );
		$this->addArg( 'oldlevel', 'Old name of restriction level', true );
		$this->addArg( 'newlevel', 'New name of restriction level', true );
	}

	public function execute() {
		$oldLevel = $this->getArg( 0 );
		$newLevel = $this->getArg( 1 );

		$dbm = wfGetDB( DB_MASTER );
		$dbm->update(
			'page_restrictions',
			[ 'pr_level' => $newLevel ],
			[ 'pr_level' => $oldLevel ],
			__METHOD__
		);
		$dbm->update(
			'protected_titles',
			[ 'pt_create_perm' => $newLevel ],
			[ 'pt_create_perm' => $oldLevel ],
			__METHOD__
		);
	}

}

$maintClass = RenameRestrictions::class;
require_once RUN_MAINTENANCE_IF_MAIN;
