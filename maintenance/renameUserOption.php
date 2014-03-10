<?php

/**
 * Maintenance script for renaming a user option (up_property column of user_properties),
 * as stored in the database.
 *
 * @licence GNU GPL v2+
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
require_once __DIR__ . '/Maintenance.php';

class UserOptionRenamer extends Maintenance {

	public function __construct() {
		parent::__construct();

		$this->mDescription = 'This script allows renaming a user option'
			. ' (up_property column of user_properties), as stored in the database.';

		$this->addArg( 'old', 'Old option name.', true );
		$this->addArg( 'new', 'New option name.', true );
	}

	public function execute() {
		$oldOption = $this->getArg( 0 );
		$newOption = $this->getArg( 1 );

		$userCount = $this->renameOption( $oldOption, $newOption );

		$this->output( "$oldOption option renamed to $newOption for $userCount user(s)\n" );
	}

	/**
	 * @param string $oldOption
	 * @param string $newOption
	 *
	 * @return int
	 */
	private function renameOption( $oldOption, $newOption ) {
		$dbw = wfGetDB( DB_MASTER );

		$dbw->update(
			'user_properties',
			array( 'up_property' => $newOption ),
			array( 'up_property' => $oldOption ),
			__METHOD__
		);

		return $dbw->affectedRows();
	}
}

$maintClass = 'UserOptionRenamer';
require_once RUN_MAINTENANCE_IF_MAIN;
