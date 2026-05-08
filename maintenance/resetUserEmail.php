<?php
/**
 * Reset user email.
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

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\Logger\LoggerFactory;
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
		$this->addOption( 'reason', 'Reason for the email change (ticket number etc)', false, true );
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

		$oldAddr = $user->getEmail();

		// Code from https://wikitech.wikimedia.org/wiki/Password_reset
		$user->setEmail( $email );
		$user->setEmailAuthenticationTimestamp( wfTimestampNow() );
		$user->saveSettings();

		$logger = LoggerFactory::getInstance( 'authentication' );
		$logger->info(
			'Changing email address for {user} from {oldemail} to {newemail} via resetUserEmail.php', [
				'user' => $user->getName(),
				'oldemail' => $oldAddr,
				'newemail' => $email,
				'reason' => $this->getOption( 'reason', '' ),
			]
		);

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
			} else {
				$logger->info(
					'Scrambling password for {user} via resetUserEmail.php', [
						'user' => $user->getName(),
						'reason' => $this->getOption( 'reason', '' ),
					]
				);

				$invalidator = $this->createChild( InvalidateUserSessions::class );
				$invalidator->setOption( 'user', $user->getName() );
				$invalidator->execute();
			}
		}

		if ( $this->hasOption( 'email-password' ) ) {
			$passReset = $this->getServiceContainer()->getPasswordReset();
			$sysUser = User::newSystemUser( 'Maintenance script', [ 'steal' => true ] );
			$status = $passReset->execute( $sysUser, $user->getName(), $email );
			if ( !$status->isGood() ) {
				$this->error( "Email couldn't be sent because:" );
				$this->error( $status );
			} else {
				$logger->info(
					'Password reset email sent for {user} via resetUserEmail.php', [
						'user' => $user->getName(),
						'reason' => $this->getOption( 'reason', '' ),
					]
				);
			}
		}
		$this->output( "Done!\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = ResetUserEmail::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
