<?php
/**
 * Invalidate the sessions of certain users on the wiki.
 * If you want to invalidate all sessions, use $wgAuthenticationTokenVersion instead.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Session\SessionManager;
use MediaWiki\User\User;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Invalidate the sessions of certain users on the wiki.
 * If you want to invalidate all sessions, use $wgAuthenticationTokenVersion instead.
 *
 * @ingroup Maintenance
 */
class InvalidateUserSessions extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'Invalidate the sessions of certain users on the wiki.'
		);
		$this->addOption( 'user', 'Username', false, true, 'u' );
		$this->addOption( 'file', 'File with one username per line', false, true, 'f' );
		$this->setBatchSize( 1000 );
	}

	public function execute() {
		$username = $this->getOption( 'user' );
		$file = $this->getOption( 'file' );

		if ( $username === null && $file === null ) {
			$this->fatalError( 'Either --user or --file is required' );
		} elseif ( $username !== null && $file !== null ) {
			$this->fatalError( 'Cannot use both --user and --file' );
		}

		if ( $username !== null ) {
			$usernames = [ $username ];
		} else {
			$usernames = is_readable( $file ) ?
				file( $file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES ) : false;
			if ( $usernames === false ) {
				$this->fatalError( "Could not open $file", 2 );
			}
		}

		$i = 0;
		$sessionManager = SessionManager::singleton();
		foreach ( $usernames as $username ) {
			$i++;
			$user = User::newFromName( $username );
			try {
				$sessionManager->invalidateSessionsForUser( $user );
				if ( $user->isRegistered() ) {
					$this->output( "Invalidated sessions for user $username\n" );
				} else {
					# session invalidation might still work if there is a central identity provider
					$this->output( "Could not find user $username, tried to invalidate anyway\n" );
				}
			} catch ( Exception $e ) {
				$this->output( "Failed to invalidate sessions for user $username | "
					. str_replace( [ "\r", "\n" ], ' ', $e->getMessage() ) . "\n" );
			}

			if ( $i % $this->getBatchSize() ) {
				$this->waitForReplication();
			}
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = InvalidateUserSessions::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
