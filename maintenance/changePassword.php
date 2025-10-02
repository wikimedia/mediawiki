<?php
/**
 * Change the password of a given user
 *
 * Copyright © 2005, Ævar Arnfjörð Bjarmason
 *
 * @license GPL-2.0-or-later
 * @file
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @ingroup Maintenance
 */

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\Maintenance\Maintenance;

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
		$this->addOption( "password", "The password to use", false, true );
		// phpcs:ignore Generic.Files.LineLength.TooLong
		$this->addOption( "passwordstdin", "Makes the script read the password from stdin instead. Cannot be used alongside --password", false, false );
		$this->addDescription( "Change a user's password" );
	}

	public function execute() {
		$user = $this->validateUserOption( "A \"user\" or \"userid\" must be set to change the password for" );
		// phpcs:ignore Generic.Files.LineLength.TooLong
		$password = $this->validatePasswordOption( 'Set either --password or --passwordstdin', 'Either --password or --passwordstdin must be set, not both at once' );
		$status = $user->changeAuthenticationData( [
			'username' => $user->getName(),
			'password' => $password,
			'retype' => $password,
		] );
		if ( $status->isGood() ) {
			$this->output( "Password set for " . $user->getName() . "\n" );
		} else {
			$this->fatalError( $status );
		}
	}

	/**
	 * @param string $errorMsgNoPassword Error message to be displayed if neither --password or --stdin are set
	 * @param string $errorMsgBothPasswords Error message to be displayed if both --password and --stdin are set
	 *
	 * @since 1.44
	 *
	 * @return string The new password
	 */
	private function validatePasswordOption( $errorMsgNoPassword, $errorMsgBothPasswords ) {
		if ( $this->hasOption( 'password' ) && $this->hasOption( 'passwordstdin' ) ) {
			$this->fatalError( $errorMsgBothPasswords );
		}
		if ( $this->hasOption( 'password' ) ) {
			return $this->getOption( 'password' );
		} elseif ( $this->hasOption( 'passwordstdin' ) ) {
			return $this->getStdin( Maintenance::STDIN_ALL );
		} else {
			$this->fatalError( $errorMsgNoPassword );
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = ChangePassword::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
