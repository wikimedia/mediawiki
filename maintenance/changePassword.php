<?php
/**
 * Change the password of a given user
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
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 * @ingroup Maintenance
 */

require_once( "Maintenance.php" );

class ChangePassword extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addOption( "user", "The username to operate on", true, true );
		$this->addOption( "password", "The password to use", true, true );
		$this->mDescription = "Change a user's password";
	}
	
	public function execute() {
		$user = User::newFromName( $this->getOption('user') );
		if( !$user->getId() ) {
			$this->error( "No such user: " . $this->getOption('user') . "\n", true );
		}
		try {
			$user->setPassword( $this->getOption('password') );
			$user->saveSettings();
		} catch( PasswordError $pwe ) {
			$this->error( $pwe->getText(), true );
		}
	}
}

$maintClass = "ChangePassword";
require_once( DO_MAINTENANCE );
