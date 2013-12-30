<?php

require_once __DIR__ . '/Maintenance.php';

/**
 * Script to take unsalted passwords and salt them
 *
 * If $wgPasswordSalt was set to false, this script
 * should be run to update all passwords, and then
 * it can be set to true.
 */
class SaltPasswords extends Maintenance {

	public function execute() {
		$dbw = wfGetDB( DB_MASTER );
		do {
			$rows = $dbw->select(
				array( 'user' ),
				array( 'user_id', 'user_password' ),
				array( 'user_password' => $dbw->buildLike( ':A:', $dbw->anyString() ) ),
				__METHOD__,
				array( 'LIMIT' => 500 )
			);

			$count = $rows->numRows();

			foreach( $rows as $row ) {
				$oldPass = substr( $row->user_password, 3 );
				$newPass = $this->getNewPassword( $oldPass );
				$dbw->update(
					array( 'user' ),
					array( 'user_password' => $newPass ),
					array( 'user_id' => $row->user_id ),
					__METHOD__
				);
			}
		} while ( $count !== 0 );
	}

	/**
	 * @param string $oldPass md5 hashed password
	 * @return string new salted password
	 */
	protected function getNewPassword( $oldPass ) {
		$salt = MWCryptRand::generateHex( 8 );
		return ':B:' . $salt . ':' . md5( $salt . '-' . $oldPass );
	}

	public function getDbType() {
		return Maintenance::DB_ADMIN;
	}
}

$maintClass = "SaltPasswords";
require_once RUN_MAINTENANCE_IF_MAIN;
