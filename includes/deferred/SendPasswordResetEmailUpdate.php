<?php
/**
 * Send an email to reset the password
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
 */

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
