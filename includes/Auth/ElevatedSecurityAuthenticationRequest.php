<?php
/**
 * Authentication request for reauthenticating with elevated security
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
 * @ingroup Auth
 */

namespace MediaWiki\Auth;

use LogicException;
use MediaWiki\Session\Session;
use StatusValue;

/**
 * Represents an elevated-security reauthentication process (ie. the user proving their
 * identity before getting access to some security-sensitive functionality such as password
 * change or actions restricted to highly privileged users). This request is managed by
 * AuthManager and can be initiated by the securityLevel option of getAuthenticationRequests().
 *
 * This request being present in the set of requests passed to an authentication provider means
 * that the user is guaranteed not to change during the authentication process (but the session id
 * might renew), and after a successful authentication the user will pass a check for
 * AuthManager::securitySensitiveOperationStatus() with the given security level.
 * @ingroup Auth
 * @since 1.47
 * @see AuthManager::getAuthenticationRequests()
 * @see AuthManager::securitySensitiveOperationStatus()
 * @see SpecialPage::checkLoginSecurityLevel()
 */
class ElevatedSecurityAuthenticationRequest extends AuthenticationRequest {

	/** Max allowed time between getAuthenticationRequests() and beginAuthentication(). */
	public const int MAX_AGE = 300;

	/** The user who is being reauthenticated. Should always be an existing local account. */
	public int $userId;

	/** The security level of the reauthentication process. */
	public string $securityLevel;

	private string $token;

	private StatusValue $validationStatus;

	/** The user session which will be elevated. */
	private ?Session $session = null;

	private function __construct() {
	}

	/**
	 * Create a new elevated-security reauthentication request, and store its data in the
	 * current session.
	 * @param Session $session The user session which will be elevated. Will be persisted.
	 * @param string $securityLevel The security level of the reauthentication process.
	 * @return ElevatedSecurityAuthenticationRequest
	 * @internal for use by AuthManager only
	 */
	public static function create( Session $session, $securityLevel ) {
		if ( $session->getUser()->isAnon() ) {
			throw new LogicException( 'Cannot create an ElevatedSecurityAuthenticationRequest for anon' );
		}

		$req = new self();
		$req->userId = $session->getUser()->getId();
		$req->securityLevel = $securityLevel;
		$req->session = $session;
		$req->validationStatus = StatusValue::newFatal( 'authmanager-elevatedsecurity-not-validated' );

		$token = $session->getToken( [ (string)$req->userId, $req->securityLevel ], 'reauth' );
		$session->persist(); // sanity, in case the caller somehow forgets
		$req->token = $token->toString();

		return $req;
	}

	public function __sleep() {
		// Don't try to serialize the session. Without a session validate() will fail,
		// but that's OK, this request can only come from getAuthenticationRequests() so
		// it will only be validated at the beginning of the begin/continue* methods when
		// it has not gone through session storage yet.
		return [ 'userId', 'securityLevel', 'token', 'validationStatus' ];
	}

	/** @inheritDoc */
	public function getFieldInfo() {
		return [
			'elevatedSecurityToken' => [
				'type' => 'hidden',
				'value' => $this->token,
				'label' => wfMessage( 'authmanager-elevatedsecurity-token-label' ),
				'help' => wfMessage( 'authmanager-elevatedsecurity-token-help' ),
				'sensitive' => true,
			],
		];
	}

	/** @inheritDoc */
	public function loadFromSubmission( array $data ) {
		$this->validationStatus = StatusValue::newGood();

		$dataToken = $data['elevatedSecurityToken'] ?? null;
		if ( !$this->session ) {
			// loadFromSubmission() was called after serialize+unserialize. This should not happen.
			throw new LogicException( __METHOD__ . ' called on incomplete object' );
		}
		$sessionToken = $this->session->getToken( [ (string)$this->userId, $this->securityLevel ], 'reauth' );
		if ( !$dataToken ) {
			// Normally we handle missing fields by returning false but ElevatedSecurityAuthRequest
			// has a special meaning; if it was present in the initial set of requests, it should not
			// disappear during loading the data. So here we just record what's present, and will
			// deal with errors in validate().
			$this->validationStatus->fatal( 'authmanager-elevatedsecurity-missing-token' );
		} elseif ( !$sessionToken->match( $dataToken, self::MAX_AGE ) ) {
			$this->validationStatus->fatal( 'authmanager-elevatedsecurity-invalid-token' );
		}

		return true;
	}

	/** @inheritDoc */
	public function validate() {
		return $this->validationStatus;
	}

	public static function __set_state( $data ) {
		$ret = new static();
		foreach ( $data as $k => $v ) {
			$ret->$k = $v;
		}
		return $ret;
	}

}
