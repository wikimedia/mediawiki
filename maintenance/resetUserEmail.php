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

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Password\PasswordFactory;
use MediaWiki\User\User;

/**
 * Maintenance script that resets user email.
 *
 * Supports resetting a single user's email via positional arguments,
 * or batch-resetting multiple users by reading from a file or stdin.
 *
 * @since 1.27
 * @ingroup Maintenance
 */
class ResetUserEmail extends Maintenance {
	public function __construct() {
		parent::__construct();

		$this->addDescription(
			"Resets a user's email, or batch resets from a file.\n\n"
			. "To reset a single user:\n"
			. "  resetUserEmail.php <user> <email>\n\n"
			. "To batch reset from a file (one \"<username>\t<email>\" pair per line, tab-separated):\n"
			. "  resetUserEmail.php --file /path/to/resets.tsv\n\n"
			. "To batch reset from stdin:\n"
			. "  resetUserEmail.php --file -"
		);

		$this->addArg( 'user', 'Username or user ID, if starts with #', false );
		$this->addArg( 'email', 'Email to assign', false );

		$this->addOption( 'no-reset-password', 'Don\'t reset the user\'s password' );
		$this->addOption( 'email-password',
			'Send a temporary password to the user\'s new email address'
		);
		$this->addOption( 'reason', 'Reason for the email change (ticket number etc)', false, true );
		$this->addOption(
			'file',
			'File with one "<username>\t<email>" pair per line, tab-separated '
			. '(# lines are comments). Use - for stdin.',
			false,
			true,
			'f'
		);
		$this->setBatchSize( 100 );
	}

	public function execute() {
		$file = $this->getOption( 'file' );
		$userName = $this->getArg( 0 );

		if ( $file !== null && $userName !== null ) {
			$this->fatalError( 'Cannot use both positional arguments and --file' );
		}

		if ( $file !== null ) {
			$this->resetFromFile( $file );
		} elseif ( $userName !== null ) {
			$email = $this->getArg( 1, '' );
			$this->resetSingleUser( $userName, $email );
		} else {
			$this->fatalError( 'Either provide <user> <email> arguments or use --file' );
		}
	}

	/**
	 * Reset email for a single user, preserving original error behavior.
	 */
	private function resetSingleUser( string $userName, string $email ): void {
		$user = $this->resolveUser( $userName );
		if ( !$user ) {
			$this->fatalError( "Error: user '$userName' does not exist\n" );
		}

		if ( $email !== '' && !Sanitizer::validateEmail( $email ) ) {
			$this->fatalError( "Error: email '$email' is not valid\n" );
		}

		$this->performReset( $user, $userName, $email );
		$this->output( "Done!\n" );
	}

	/**
	 * Reset emails for users listed in a file or stdin.
	 * Each line should contain a username and email address separated by
	 * a tab character. Lines starting with "# " are treated as comments.
	 *
	 * @param string $file Path to the input file, or '-' for stdin
	 */
	private function resetFromFile( string $file ): void {
		$shouldClose = false;
		if ( $file === '-' ) {
			$handle = $this->getStdin();
		} else {
			if ( !is_readable( $file ) ) {
				$this->fatalError( "Could not open file: $file" );
			}
			$handle = fopen( $file, 'r' );
			if ( $handle === false ) {
				$this->fatalError( "Could not open file: $file" );
			}
			$shouldClose = true;
		}

		// Pre-create system user once for email-password mode
		$sysUser = null;
		if ( $this->hasOption( 'email-password' ) ) {
			$sysUser = User::newSystemUser( 'Maintenance script', [ 'steal' => true ] );
			if ( $sysUser === null ) {
				$this->fatalError( 'Could not create system user for email-password mode' );
			}
		}

		$processed = 0;
		$good = 0;
		$bad = 0;
		for ( $lineNum = 1; ; $lineNum++ ) {
			$rawLine = fgets( $handle );
			if ( $rawLine === false ) {
				break;
			}
			$line = trim( $rawLine );
			if ( $line === '' || preg_match( '/^#(\s|$)/', $line ) ) {
				continue;
			}

			$parts = preg_split( '/\t/', $line, 2 );
			if ( count( $parts ) !== 2 ) {
				$this->error( "Line $lineNum: expected '<username>\\t<email>' (tab-separated), got: $line" );
				$bad++;
				continue;
			}

			[ $userName, $email ] = array_map( 'trim', $parts );
			$processed++;

			$user = $this->resolveUser( $userName );
			if ( !$user ) {
				$this->error( "Line $lineNum: user '$userName' does not exist" );
				$bad++;
				continue;
			}

			if ( $email !== '' && !Sanitizer::validateEmail( $email ) ) {
				$this->error( "Line $lineNum: email '$email' is not valid" );
				$bad++;
				continue;
			}

			$this->performReset( $user, $userName, $email, $sysUser );
			$this->output( "Done: $userName\n" );
			$good++;

			if ( $processed % $this->getBatchSize() === 0 ) {
				$this->waitForReplication();
			}
		}

		if ( $shouldClose ) {
			fclose( $handle );
		}

		$this->output(
			"\nBatch complete: $good succeeded, $bad failed out of $processed processed.\n"
		);
	}

	/**
	 * Resolve a username string to a User object.
	 *
	 * @param string $userName Username or '#<id>'
	 * @return User|null The user, or null if not found
	 */
	private function resolveUser( string $userName ): ?User {
		$userFactory = $this->getServiceContainer()->getUserFactory();
		if ( preg_match( '/^#\d+$/', $userName ) ) {
			$user = $userFactory->newFromId( (int)substr( $userName, 1 ) );
		} else {
			$user = $userFactory->newFromName( $userName );
		}
		if ( !$user || !$user->isRegistered() || !$user->loadFromId() ) {
			return null;
		}
		return $user;
	}

	/**
	 * Perform the actual email reset and optional password change
	 * for a given user.
	 */
	private function performReset(
		User $user, string $userName, string $email, ?User $sysUser = null
	): void {
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
				$this->error( "Password couldn't be reset for '$userName' because:" );
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
				// Clear inherited --file option to avoid conflict with
				// InvalidateUserSessions' own --user/--file mutual exclusion
				$invalidator->deleteOption( 'file' );
				$invalidator->execute();
			}
		}

		if ( $this->hasOption( 'email-password' ) ) {
			if ( $sysUser === null ) {
				$sysUser = User::newSystemUser( 'Maintenance script', [ 'steal' => true ] );
				if ( $sysUser === null ) {
					$this->error( "Could not create system user for email-password for '$userName'" );
					return;
				}
			}
			$passReset = $this->getServiceContainer()->getPasswordReset();
			$status = $passReset->execute( $sysUser, $user->getName(), $email );
			if ( !$status->isGood() ) {
				$this->error( "Email couldn't be sent for '$userName' because:" );
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
	}
}

// @codeCoverageIgnoreStart
$maintClass = ResetUserEmail::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
