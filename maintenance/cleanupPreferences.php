<?php
/**
 * Remove junk preferences from the database.
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
 * @author TyA <tya.wiki@gmail.com>
 * @author Chad <chad@wikimedia.org>
 * @see https://phabricator.wikimedia.org/T32976
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that removes bogus preferences from the database.
 *
 * @ingroup Maintenance
 */
class CleanupPreferences extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Clean up hidden preferences, removed preferences, and normalizes values';
		$this->setBatchSize( 50 );
		$this->addOption( 'dry-run', "Don't perform any actual deletions" );
	}

	/**
	 * We will do this in three passes
	 *   1) The easiest is to drop the hidden preferences from the database. We
	 *      don't actually want them
	 *   2) Drop preference keys that we don't know about. They could've been
	 *      removed from core, provided by a now-disabled extension, or the result
	 *      of a bug. We don't want them.
	 *   3) Normalize accepted skin values. This is the biggest part of the work.
	 *      For each preference we know about, iterate over it and if it's got a
	 *      limited set of accepted values (so it's not text, basically), make sure
	 *      all values are in that range. Drop ones that aren't.
	 */
	public function execute() {
		global $wgHiddenPrefs, $wgDefaultPreferences;

		$dbw = $this->getDB( DB_MASTER );

		$this->deleteByWhere(
			$dbw,
			'Dropping hidden preferences',
			'user_property IN (' . $dbw->makeList( $wgHiddenPrefs ) . ')'
		);

		$this->deleteByWhere(
			$dbw,
			'Dropping unknown preferences',
			'user_property NOT IN (' . $dbw->makeList( array_keys( $wgDefaultPreferences ) ) . ')'
		);

		// Something something phase 3
	}

	/**
	 *
	 */
	private function deleteByWhere( $dbw, $startMessage, $where ) {
		$this->output( $startMessage . "...\n" );

		while ( true ) {
			$res = $dbw->select(
				'user_properties',
				'*', // The table lacks a primary key, so select the whole row
				$where,
				__METHOD__,
				[ 'LIMIT' => $this->mBatchSize ]
			);

			if ( $res->numRows() <= 0 ) {
				// All done!
				$this->output( "DONE!\n" );
				break;
			}

			// Progress or something
			$this->output( "...doing {$this->mBatchSize} entries\n" );

			// Delete our batch, then wait
			foreach( $res as $row ) {
				$dbw->delete(
					'user_properties',
					[
						'up_user'     => $row->up_user,
						'up_property' => $row->up_property,
						'up_value'    => $row->up_value,
					],
					__METHOD__
				);
			}
			wfWaitForSlaves();
		}
	}
}

$maintClass = 'CleanupPreferences'; // Tells it to run the class
require_once RUN_MAINTENANCE_IF_MAIN;
