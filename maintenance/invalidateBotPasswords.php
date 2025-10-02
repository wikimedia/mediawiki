<?php
/**
 * Invalidates the bot passwords of a given user
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\User\BotPassword;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script to invalidate the bot passwords of a given user.
 *
 * @ingroup Maintenance
 */
class InvalidateBotPasswords extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addOption( "user", "The username to operate on", false, true );
		$this->addOption( "userid", "The user id to operate on", false, true );
		$this->addDescription( "Invalidate a user's bot passwords" );
	}

	public function execute() {
		$user = $this->validateUserOption( "A \"user\" or \"userid\" must be set to invalidate the bot passwords for" );

		$res = BotPassword::invalidateAllPasswordsForUser( $user->getName() );

		if ( $res ) {
			$this->output( "Bot passwords invalidated for " . $user->getName() . "\n" );
		} else {
			$this->output( "No bot passwords invalidated for " . $user->getName() . "\n" );
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = InvalidateBotPasswords::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
