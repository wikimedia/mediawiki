<?php
/**
 * Change the password of a given user
 *
 * Copyright © 2005, Ævar Arnfjörð Bjarmason
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
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script to change the password of a given user.
 *
 * @ingroup Maintenance
 */
class ChangePassword extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addOption( "user", "The username to operate on", false, true );
		$this->addOption( "userid", "The user id to operate on", false, true );
		$this->addOption( "password", "The password to use", true, true );
		$this->addDescription( "Change a user's password" );
	}

	public function execute() {
		if ( $this->hasOption( "user" ) ) {
			$user = User::newFromName( $this->getOption( 'user' ) );
		} elseif ( $this->hasOption( "userid" ) ) {
			$user = User::newFromId( $this->getOption( 'userid' ) );
		} else {
			$this->fatalError( "A \"user\" or \"userid\" must be set to change the password for" );
		}
		if ( !$user || !$user->getId() ) {
			$this->fatalError( "No such user: " . $this->getOption( 'user' ) );
		}
		$password = $this->getOption( 'password' );
		try {
			$status = $user->changeAuthenticationData( [
				'username' => $user->getName(),
				'password' => $password,
				'retype' => $password,
			] );
			if ( !$status->isGood() ) {
				throw new PasswordError( $status->getWikiText( null, null, 'en' ) );
			}
			$user->saveSettings();
			$this->output( "Password set for " . $user->getName() . "\n" );
		} catch ( PasswordError $pwe ) {
			$this->fatalError( $pwe->getText() );
		}
	}
}

$maintClass = ChangePassword::class;
require_once RUN_MAINTENANCE_IF_MAIN;
