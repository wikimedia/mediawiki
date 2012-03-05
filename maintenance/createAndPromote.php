<?php
/**
 * Maintenance script to create an account and grant it administrator rights
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
 * @author Rob Church <robchur@gmail.com>
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class CreateAndPromote extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Create a new user account with administrator rights";
		$this->addOption( "bureaucrat", "Grant the account bureaucrat rights" );
		$this->addArg( "username", "Username of new user" );
		$this->addArg( "password", "Password to set" );
	}

	public function execute() {
		$username = $this->getArg( 0 );
		$password = $this->getArg( 1 );
		
		$this->output( wfWikiID() . ": Creating and promoting User:{$username}..." );
		
		$user = User::newFromName( $username );
		if ( !is_object( $user ) ) {
			$this->error( "invalid username.", true );
		} elseif ( 0 != $user->idForName() ) {
			$this->error( "account exists.", true );
		}

		# Try to set the password
		try {
			$user->setPassword( $password );
		} catch ( PasswordError $pwe ) {
			$this->error( $pwe->getText(), true );
		}

		# Insert the account into the database
		$user->addToDatabase();
		$user->saveSettings();
	
		# Promote user
		$user->addGroup( 'sysop' );
		if ( $this->hasOption( 'bureaucrat' ) )
			$user->addGroup( 'bureaucrat' );
	
		# Increment site_stats.ss_users
		$ssu = new SiteStatsUpdate( 0, 0, 0, 0, 1 );
		$ssu->doUpdate();
	
		$this->output( "done.\n" );
	}
}

$maintClass = "CreateAndPromote";
require_once( DO_MAINTENANCE );