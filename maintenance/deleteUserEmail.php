<?php
/**
 * Deletes a given user's associated email address
 * Usage: php deleteUserEmail.php <user>
 * where <user> can be either the username or user ID
 *
 * @license GPL-2.0-or-later
 * @file
 * @author Samuel Guebo <sguebo@wikimedia.org>
 * @see https://phabricator.wikimedia.org/T290099
 * @ingroup Maintenance
 */

use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * @since 1.38
 * @author Samuel Guebo
 */
class DeleteUserEmail extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( "Delete a user's email" );
		$this->addArg( 'user', 'Username or user ID, if starts with #', true );
	}

	public function execute() {
		$userFactory = $this->getServiceContainer()->getUserFactory();
		$userName = $this->getArg( 0 );
		if ( preg_match( '/^#\d+$/', $userName ) ) {
			$user = $userFactory->newFromId( (int)substr( $userName, 1 ) );
		} else {
			$user = $userFactory->newFromName( $userName );
		}

		// Checking whether User object is valid and has an actual id
		if ( !$user || !$user->isRegistered() || !$user->loadFromId() ) {
			$this->fatalError( "Error: user '$userName' could not be loaded" );
		}

		// Blank the email address
		$user->invalidateEmail();
		$user->saveSettings();
		$this->output( "Done!\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = DeleteUserEmail::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
