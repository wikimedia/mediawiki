<?php
/**
 * Send an email to reset the password
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Deferred;

use MediaWiki\Auth\AuthManager;
use MediaWiki\Logger\LoggerFactory;

/**
 * Sends emails to all accounts associated with that email to reset the password
 * @since 1.35
 */
class SendPasswordResetEmailUpdate implements DeferrableUpdate {
	/** @var AuthManager */
	private $authManager;

	/** @var array */
	private $reqs;

	/** @var array */
	private $logContext;

	/**
	 * @param AuthManager $authManager
	 * @param array $reqs
	 * @param array $logContext
	 */
	public function __construct( AuthManager $authManager, array $reqs, array $logContext ) {
		$this->authManager = $authManager;
		$this->reqs = $reqs;
		$this->logContext = $logContext;
	}

	public function doUpdate() {
		$logger = LoggerFactory::getInstance( 'authentication' );
		foreach ( $this->reqs as $req ) {
			// This is adding a new temporary password, not intentionally changing anything
			// (even though it might technically invalidate an old temporary password).
			$this->authManager->changeAuthenticationData( $req, /* $isAddition */ true );
			$logger->info(
				"{requestingUser} did password reset of {targetUser} and an email was sent",
				$this->logContext + [ 'targetUser' => $req->username ]
			);
		}
	}

}

/** @deprecated class alias since 1.42 */
class_alias( SendPasswordResetEmailUpdate::class, 'SendPasswordResetEmailUpdate' );
