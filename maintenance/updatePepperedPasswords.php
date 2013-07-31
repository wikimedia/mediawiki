<?php
/**
 * Maintenance script to update password encryption keys
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
 * Maintenance script to iterate through all passwords that inherit the
 * type PepperedPassword and update the encryption keys
 *
 * @ingroup Maintenance
 */
class UpdatePepperedPasswords extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Update all peppered passwords to the latest secret key";
		$this->addOption( 'verbose', 'Enables verbose output', false, false, 'v' );
		$this->setBatchSize( 100 );
	}

	public function execute() {
		global $wgAuth;

		if ( !$wgAuth->allowSetLocalPassword() ) {
			$this->error( '$wgAuth does not allow local passwords. Aborting.', true );
		}

		$types = Password::getTypes();
		$dbw = $this->getDB( DB_MASTER );

		// Get a list of password types that are applicable
		$conds = array();
		foreach ( $types as $type => $info ) {
			$obj = Password::newFromType( $type );
			if ( $obj instanceof PepperedPassword ) {
				$conds[] = 'user_password' . $dbw->buildLike( ":$type:", $dbw->anyString() );
			}
		}

		$minUserId = 0;
		do {
			$dbw->begin();

			$res = $dbw->select( 'user',
				array( 'user_id', 'user_name', 'user_password' ),
				array(
					'user_id > ' . $dbw->addQuotes( $minUserId ),
					$dbw->makeList( $conds, LIST_OR ),
				),
				__METHOD__,
				array(
					'ORDER BY' => 'user_id',
					'LIMIT' => $this->mBatchSize,
					'LOCK IN SHARE MODE',
				)
			);

			$updateUsers = array();
			foreach ( $res as $row ) {
				$user = User::newFromId( $row->user_id );
				$password = Password::newFromCiphertext( $row->user_password );

				// Only update if the password is peppered and if the updating actually
				// changes something
				if ( $password instanceof PepperedPassword && $password->update() ) {
					if ( $this->hasOption( 'verbose' ) ) {
						$this->output( "Updating password for user {$row->user_name} ({$row->user_id}).\n" );
					}

					$updateUsers[] = $user;
					$dbw->update( 'user',
						array( 'user_password' => $password->toString() ),
						array( 'user_id' => $row->user_id ),
						__METHOD__
					);
				}

				$minUserId = $row->user_id;
			}

			$dbw->commit();

			// Clear memcached so old passwords are wiped out
			foreach ( $updateUsers as $user ) {
				$user->clearSharedCache();
			}
		} while ( $res->numRows() );
	}
}

$maintClass = "UpdatePepperedPasswords";
require_once RUN_MAINTENANCE_IF_MAIN;
