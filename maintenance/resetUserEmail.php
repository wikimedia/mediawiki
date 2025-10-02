<?php
/**
 * Reset user email.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Password\PasswordFactory;
use MediaWiki\User\User;

/**
 * Maintenance script that resets user email.
 *
 * @since 1.27
 * @ingroup Maintenance
 */
class ResetUserEmail extends Maintenance {
	public function __construct() {
		parent::__construct();

		$this->addDescription( "Resets a user's email" );

		$this->addArg( 'user', 'Username or user ID, if starts with #' );
		$this->addArg( 'email', 'Email to assign' );

		$this->addOption( 'no-reset-password', 'Don\'t reset the user\'s password' );
		$this->addOption( 'email-password', 'Send a temporary password to the user\'s new email address' );
	}

	public function execute() {
		$userName = $this->getArg( 0 );
		if ( preg_match( '/^#\d+$/', $userName ) ) {
			$user = User::newFromId( (int)substr( $userName, 1 ) );
		} else {
			$user = User::newFromName( $userName );
		}
		if ( !$user || !$user->isRegistered() || !$user->loadFromId() ) {
			$this->fatalError( "Error: user '$userName' does not exist\n" );
		}

		$email = $this->getArg( 1, '' );
		if ( $email !== '' && !Sanitizer::validateEmail( $email ) ) {
			$this->fatalError( "Error: email '$email' is not valid\n" );
		}

		// Code from https://wikitech.wikimedia.org/wiki/Password_reset
		$user->setEmail( $email );
		$user->setEmailAuthenticationTimestamp( wfTimestampNow() );
		$user->saveSettings();

		if ( !$this->hasOption( 'no-reset-password' ) ) {
			// Kick whomever is currently controlling the account off if possible
			$password = PasswordFactory::generateRandomPasswordString( 128 );
			$status = $user->changeAuthenticationData( [
				'username' => $user->getName(),
				'password' => $password,
				'retype' => $password,
			] );
			if ( !$status->isGood() ) {
				$this->error( "Password couldn't be reset because:" );
				$this->error( $status );
			}
		}

		if ( $this->hasOption( 'email-password' ) ) {
			$passReset = $this->getServiceContainer()->getPasswordReset();
			$sysUser = User::newSystemUser( 'Maintenance script', [ 'steal' => true ] );
			$status = $passReset->execute( $sysUser, $user->getName(), $email );
			if ( !$status->isGood() ) {
				$this->error( "Email couldn't be sent because:" );
				$this->error( $status );
			}
		}
		$this->output( "Done!\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = ResetUserEmail::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
