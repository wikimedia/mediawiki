<?php
/**
 * Maintenance script to wrap all old-style passwords in a layered type
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

use MediaWiki\MediaWikiServices;

/**
 * Maintenance script to wrap all passwords of a certain type in a specified layered
 * type that wraps around the old type.
 *
 * @since 1.24
 * @ingroup Maintenance
 */
class WrapOldPasswords extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Wrap all passwords of a certain type in a new layered type. '
					. 'The script runs in dry-run mode by default (use --update to update rows)' );
		$this->addOption( 'type',
			'Password type to wrap passwords in (must inherit LayeredParameterizedPassword)', true, true );
		$this->addOption( 'verbose', 'Enables verbose output', false, false, 'v' );
		$this->addOption( 'update', 'Actually wrap passwords', false, false, 'u' );
		$this->setBatchSize( 3 );
	}

	public function execute() {
		$passwordFactory = MediaWikiServices::getInstance()->getPasswordFactory();

		$typeInfo = $passwordFactory->getTypes();
		$layeredType = $this->getOption( 'type' );

		// Check that type exists and is a layered type
		if ( !isset( $typeInfo[$layeredType] ) ) {
			$this->fatalError( 'Undefined password type' );
		}

		$passObj = $passwordFactory->newFromType( $layeredType );
		if ( !$passObj instanceof LayeredParameterizedPassword ) {
			$this->fatalError( 'Layered parameterized password type must be used.' );
		}

		// Extract the first layer type
		$typeConfig = $typeInfo[$layeredType];
		$firstType = $typeConfig['types'][0];

		$update = $this->hasOption( 'update' );

		// Get a list of password types that are applicable
		$dbw = $this->getDB( DB_PRIMARY );
		$typeCond = 'user_password' . $dbw->buildLike( ":$firstType:", $dbw->anyString() );

		$count = 0;
		$minUserId = 0;
		while ( true ) {
			if ( $update ) {
				$this->beginTransaction( $dbw, __METHOD__ );
			}

			$start = microtime( true );
			$res = $dbw->select( 'user',
				[ 'user_id', 'user_name', 'user_password' ],
				[
					'user_id > ' . $dbw->addQuotes( $minUserId ),
					$typeCond
				],
				__METHOD__,
				[
					'ORDER BY' => 'user_id',
					'LIMIT' => $this->getBatchSize(),
					'LOCK IN SHARE MODE',
				]
			);

			if ( $res->numRows() === 0 ) {
				if ( $update ) {
					$this->commitTransaction( $dbw, __METHOD__ );
				}
				break;
			}

			/** @var User[] $updateUsers */
			$updateUsers = [];
			foreach ( $res as $row ) {
				$user = User::newFromId( $row->user_id );
				/** @var ParameterizedPassword $password */
				$password = $passwordFactory->newFromCiphertext( $row->user_password );
				'@phan-var ParameterizedPassword $password';
				/** @var LayeredParameterizedPassword $layeredPassword */
				$layeredPassword = $passwordFactory->newFromType( $layeredType );
				'@phan-var LayeredParameterizedPassword $layeredPassword';
				$layeredPassword->partialCrypt( $password );

				if ( $this->hasOption( 'verbose' ) ) {
					$this->output(
						"Updating password for user {$row->user_name} ({$row->user_id}) from " .
						"type {$password->getType()} to {$layeredPassword->getType()}.\n"
					);
				}

				$count++;
				if ( $update ) {
					$updateUsers[] = $user;
					$dbw->update( 'user',
						[ 'user_password' => $layeredPassword->toString() ],
						[ 'user_id' => $row->user_id ],
						__METHOD__
					);
				}

				$minUserId = $row->user_id;
			}

			if ( $update ) {
				$this->commitTransaction( $dbw, __METHOD__ );

				// Clear memcached so old passwords are wiped out
				foreach ( $updateUsers as $user ) {
					$user->clearSharedCache( 'refresh' );
				}
			}

			$this->output( "Last id processed: $minUserId; Actually updated: $count...\n" );
			$delta = microtime( true ) - $start;
			$this->output( sprintf(
				"%4d passwords wrapped in %6.2fms (%6.2fms each)\n",
				$res->numRows(),
				$delta * 1000.0,
				( $delta / $res->numRows() ) * 1000.0
			) );
		}

		if ( $update ) {
			$this->output( "$count users rows updated.\n" );
		} else {
			$this->output( "$count user rows found using old password formats. "
				. "Run script again with --update to update these rows.\n" );
		}
	}
}

$maintClass = WrapOldPasswords::class;
require_once RUN_MAINTENANCE_IF_MAIN;
