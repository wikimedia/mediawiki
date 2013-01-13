<?php
/**
 * Fix remote user rights logs for the local wiki. E.g. User:Krenair@currentwiki -> User:Krenair
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

require_once( __DIR__ . '/Maintenance.php' );

/**
 * Maintenance script to fix remote user rights logs for the local wiki. E.g. User:Krenair@currentwiki -> User:Krenair
 *
 * @ingroup Maintenance
 * @since 1.21
 */
class ConsolidateRemoteLocalUserRightLogs extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Fix remote user rights logs for the local wiki. E.g. User:Krenair@currentwiki -> User:Krenair";
	}

	public function execute() {
		global $wgUserrightsInterwikiDelimiter;
		$dbw = $this->getDB( DB_MASTER );
		$dbw->begin();
		$result = $dbw->select(
			'logging',
			'log_title',
			array( 'log_title' . $dbw->buildLike( $dbw->anyString(), $wgUserrightsInterwikiDelimiter, wfWikiID() ) ),
			__METHOD__,
			array( 'DISTINCT', 'FOR UPDATE' )
		);

		foreach ( $result as $row ) {
			list( $name, $database ) = explode( $wgUserrightsInterwikiDelimiter, $row->log_title );

			$dbw->update(
				'logging',
				array( 'log_title' => $name ),
				array( 'log_title' => $row->log_title ),
				__METHOD__
			);
			$this->output( "Changed '{$row->log_title}' to '{$name}'.\n" );
		}

		$dbw->commit();
	}
}

$maintClass = "ConsolidateRemoteLocalUserRightLogs";
require_once( RUN_MAINTENANCE_IF_MAIN );