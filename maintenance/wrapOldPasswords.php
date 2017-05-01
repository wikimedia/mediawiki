<?php

use MediaWiki\MediaWikiServices;

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
		$this->addDescription( 'Wrap all passwords of a certain type in a new layered type' );
		$this->addOption( 'type',
			'Password type to wrap passwords in (must inherit LayeredParameterizedPassword)', true, true );
		$this->addOption( 'verbose', 'Enables verbose output', false, false, 'v' );
		$this->setBatchSize( 100 );
	}

	public function execute() {
		global $wgAuth;

		if ( !$wgAuth->allowSetLocalPassword() ) {
			$this->error( '$wgAuth does not allow local passwords. Aborting.', true );
		}

		$passwordFactory = new PasswordFactory();
		$passwordFactory->init( RequestContext::getMain()->getConfig() );

		$typeInfo = $passwordFactory->getTypes();
		$layeredType = $this->getOption( 'type' );

		// Check that type exists and is a layered type
		if ( !isset( $typeInfo[$layeredType] ) ) {
			$this->error( 'Undefined password type', true );
		}

		$passObj = $passwordFactory->newFromType( $layeredType );
		if ( !$passObj instanceof LayeredParameterizedPassword ) {
			$this->error( 'Layered parameterized password type must be used.', true );
		}

		// Extract the first layer type
		$typeConfig = $typeInfo[$layeredType];
		$firstType = $typeConfig['types'][0];

		// Get a list of password types that are applicable
		$dbw = $this->getDB( DB_MASTER );
		$typeCond = 'user_password' . $dbw->buildLike( ":$firstType:", $dbw->anyString() );

		$minUserId = 0;
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		do {
			$this->beginTransaction( $dbw, __METHOD__ );

			$res = $dbw->select( 'user',
				[ 'user_id', 'user_name', 'user_password' ],
				[
					'user_id > ' . $dbw->addQuotes( $minUserId ),
					$typeCond
				],
				__METHOD__,
				[
					'ORDER BY' => 'user_id',
					'LIMIT' => $this->mBatchSize,
					'LOCK IN SHARE MODE',
				]
			);

			/** @var User[] $updateUsers */
			$updateUsers = [];
			foreach ( $res as $row ) {
				if ( $this->hasOption( 'verbose' ) ) {
					$this->output( "Updating password for user {$row->user_name} ({$row->user_id}).\n" );
				}

				$user = User::newFromId( $row->user_id );
				/** @var ParameterizedPassword $password */
				$password = $passwordFactory->newFromCiphertext( $row->user_password );
				/** @var LayeredParameterizedPassword $layeredPassword */
				$layeredPassword = $passwordFactory->newFromType( $layeredType );
				$layeredPassword->partialCrypt( $password );

				$updateUsers[] = $user;
				$dbw->update( 'user',
					[ 'user_password' => $layeredPassword->toString() ],
					[ 'user_id' => $row->user_id ],
					__METHOD__
				);

				$minUserId = $row->user_id;
			}

			$this->commitTransaction( $dbw, __METHOD__ );
			$lbFactory->waitForReplication();

			// Clear memcached so old passwords are wiped out
			foreach ( $updateUsers as $user ) {
				$user->clearSharedCache();
			}
		} while ( $res->numRows() );
	}
}

$maintClass = "WrapOldPasswords";
require_once RUN_MAINTENANCE_IF_MAIN;
