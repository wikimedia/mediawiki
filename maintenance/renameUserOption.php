<?php

/**
 * Maintenance script for renaming a user option (up_property column of user_properties),
 * as stored in the database.
 *
 * When changing the name of an option as stored in the database, it should
 * normally be done with backwards compatibility support for the old option name.
 * This script can be used to migrate the option to a new name, and support
 * for the old option name can be deprecated and removed at a later time,
 * presumably after this script being run or otherwise the database updated.
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
 * @since 1.23
 *
 * @licence GNU GPL v2+
 * @author Katie Filbert < aude.wiki@gmail.com >
 */

require_once __DIR__ . '/Maintenance.php';

class UserOptionRenamer extends Maintenance {

	/**
	 * @var boolean
	 */
	private $verbose;

	/**
	 * @var DatabaseBase
	 */
	private $dbw;

	public function __construct() {
		parent::__construct();

		$this->mDescription = 'This script allows renaming a user option'
			. ' (up_property column of user_properties), as stored in the database.'
			. ' This script modifies contents of the user_properties table,'
			. ' directly based on input provided to the script.';

		$this->addOption( 'batch-size', 'Update batch size', false, true, 'b' );
		$this->addOption( 'verbose', 'Verbose output', false, false, 'v' );

		$this->addArg( 'old', 'Old option name.', true );
		$this->addArg( 'new', 'New option name.', true );
	}

	public function execute() {
		$this->setBatchSize( $this->getOption( 'batch-size', 1000 ) );
		$this->verbose = $this->hasOption( 'verbose' );

		$oldOption = $this->getArg( 0 );
		$newOption = $this->getArg( 1 );

		$this->dbw = wfGetDB( DB_MASTER );
		$this->dbw->begin( __METHOD__ );

		$this->renameOption( $oldOption, $newOption );
		$this->purgeOldOption( $oldOption );

		try {
			$this->dbw->commit( __METHOD__ );
		} catch( DBUnexpectedError $ex ) {
			$this->error( $ex );
		}
	}

	/**
	 * @param string $oldOption
	 * @param string $newOption
	 */
	private function renameOption( $oldOption, $newOption ) {
		$totalAffectedUsers = 0;
		$maxId = $this->mBatchSize;
		$startId = 0;
		$maxUser = $this->getMaxUserId( $oldOption );

		while( $startId < $maxUser ) {
			$totalAffectedUsers += $this->updateOptionsBatch( $oldOption, $newOption, $maxId );

			$startId += $this->mBatchSize;
			$maxId += $this->mBatchSize;
		}

		$this->output( "$oldOption option renamed to $newOption for "
			. $totalAffectedUsers . " user(s)\n" );
	}

	/**
	 * @param string $oldOption
	 * @param string $newOption
	 *
	 * @return int
	 */
	private function updateOptionsBatch( $oldOption, $newOption, $maxId ) {
		$this->dbw->update(
			'user_properties',
			array( 'up_property' => $newOption ),
			array(
				'up_property' => $oldOption,
				'up_user <= ' . $maxId
			),
			__METHOD__,
			array( 'IGNORE' )
		);

		$affectedUsers = $this->dbw->affectedRows();

		if ( $this->verbose ) {
			$this->output( "Updated $affectedUsers users up to id " . $maxId . "\n" );
		}

		return $affectedUsers;
	}

	private function purgeOldOption( $oldOption ) {
		$totalAffectedRows = 0;
		$maxId = $this->mBatchSize;

		while( $this->getMaxUserId( $oldOption ) ) {
			$this->dbw->delete(
				'user_properties',
				array(
					'up_property' => $oldOption,
					"up_user <= $maxId"
				),
				__METHOD__
			);

			$affectedRows = $this->dbw->affectedRows();
			$totalAffectedRows += $affectedRows;
			$maxId += $this->mBatchSize;
		}

		$this->output( "$oldOption option purged for $totalAffectedRows total user(s)\n" );
	}

	/**
	 * @param string $oldOption
	 *
	 * @return int
	 */
	private function getMaxUserId( $oldOption ) {
		$row = $this->dbw->selectRow(
			'user_properties',
			'MAX(up_user) as max_id',
			array( 'up_property' => $oldOption ),
			__METHOD__
		);

		return $row->max_id;
	}

}

$maintClass = 'UserOptionRenamer';
require_once RUN_MAINTENANCE_IF_MAIN;
