<?php
/**
 * Change the password of a given user
 *
 * @file
 * @ingroup Maintenance
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

require_once( "Maintenance.php" );

class ChangePassword extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addParam( "user", "The username to operate on", true, true );
		$this->addParam( "password", "The password to use", true, true );
		$this->mDescription = "Change a user's password."
	}
	
	public function execute() {
		if( !$this->hasOption('user') || !$this->hasOption('password') ) {
			$this->error( "Username or password not provided, halting.", true );
		}
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
