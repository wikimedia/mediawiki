<?php
/**
 * Session provider for bot passwords
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
 * @ingroup Session
 */

namespace MediaWiki\Session;

use BotPassword;
use User;
use WebRequest;

/**
 * Session provider for bot passwords
 * @since 1.27
 */
class BotPasswordSessionProvider extends ImmutableSessionProviderWithCookie {

	/**
	 * @param array $params Keys include:
	 *  - priority: (required) Set the priority
	 *  - sessionCookieName: Session cookie name. Default is '_BPsession'.
	 *  - sessionCookieOptions: Options to pass to WebResponse::setCookie().
	 */
	public function __construct( array $params = [] ) {
		if ( !isset( $params['sessionCookieName'] ) ) {
			$params['sessionCookieName'] = '_BPsession';
		}
		parent::__construct( $params );

		if ( !isset( $params['priority'] ) ) {
			throw new \InvalidArgumentException( __METHOD__ . ': priority must be specified' );
		}
		if ( $params['priority'] < SessionInfo::MIN_PRIORITY ||
			$params['priority'] > SessionInfo::MAX_PRIORITY
		) {
			throw new \InvalidArgumentException( __METHOD__ . ': Invalid priority' );
		}

		$this->priority = $params['priority'];
	}

	public function provideSessionInfo( WebRequest $request ) {
		// Only relevant for the API
		if ( !defined( 'MW_API' ) ) {
			return null;
		}

		// Enabled?
		if ( !$this->config->get( 'EnableBotPasswords' ) ) {
			return null;
		}

		// Have a session ID?
		$id = $this->getSessionIdFromCookie( $request );
		if ( $id === null ) {
			return null;
		}

		return new SessionInfo( $this->priority, [
			'provider' => $this,
			'id' => $id,
			'persisted' => true
		] );
	}

	public function newSessionInfo( $id = null ) {
		// We don't activate by default
		return null;
	}

	/**
	 * Create a new session for a request
	 * @param User $user
	 * @param BotPassword $bp
	 * @param WebRequest $request
	 * @return Session
	 */
	public function newSessionForRequest( User $user, BotPassword $bp, WebRequest $request ) {
		$id = $this->getSessionIdFromCookie( $request );
		$info = new SessionInfo( SessionInfo::MAX_PRIORITY, [
			'provider' => $this,
			'id' => $id,
			'userInfo' => UserInfo::newFromUser( $user, true ),
			'persisted' => $id !== null,
			'metadata' => [
				'centralId' => $bp->getUserCentralId(),
				'appId' => $bp->getAppId(),
				'token' => $bp->getToken(),
				'rights' => \MWGrants::getGrantRights( $bp->getGrants() ),
			],
		] );
		$session = $this->getManager()->getSessionFromInfo( $info, $request );
		$session->persist();
		return $session;
	}

	/**
	 * @inheritDoc
	 * @phan-param array &$metadata
	 */
	public function refreshSessionInfo( SessionInfo $info, WebRequest $request, &$metadata ) {
		$missingKeys = array_diff(
			[ 'centralId', 'appId', 'token' ],
			array_keys( $metadata )
		);
		if ( $missingKeys ) {
			$this->logger->info( 'Session "{session}": Missing metadata: {missing}', [
				'session' => $info->__toString(),
				'missing' => implode( ', ', $missingKeys ),
			] );
			return false;
		}

		$bp = BotPassword::newFromCentralId( $metadata['centralId'], $metadata['appId'] );
		if ( !$bp ) {
			$this->logger->info(
				'Session "{session}": No BotPassword for {centralId} {appId}',
				[
					'session' => $info->__toString(),
					'centralId' => $metadata['centralId'],
					'appId' => $metadata['appId'],
			] );
			return false;
		}

		if ( !hash_equals( $metadata['token'], $bp->getToken() ) ) {
			$this->logger->info( 'Session "{session}": BotPassword token check failed', [
				'session' => $info->__toString(),
				'centralId' => $metadata['centralId'],
				'appId' => $metadata['appId'],
			] );
			return false;
		}

		$status = $bp->getRestrictions()->check( $request );
		if ( !$status->isOK() ) {
			$this->logger->info(
				'Session "{session}": Restrictions check failed',
				[
					'session' => $info->__toString(),
					'restrictions' => $status->getValue(),
					'centralId' => $metadata['centralId'],
					'appId' => $metadata['appId'],
			] );
			return false;
		}

		// Update saved rights
		$metadata['rights'] = \MWGrants::getGrantRights( $bp->getGrants() );

		return true;
	}

	/**
	 * @codeCoverageIgnore
	 * @inheritDoc
	 */
	public function preventSessionsForUser( $username ) {
		BotPassword::removeAllPasswordsForUser( $username );
	}

	public function getAllowedUserRights( SessionBackend $backend ) {
		if ( $backend->getProvider() !== $this ) {
			throw new \InvalidArgumentException( 'Backend\'s provider isn\'t $this' );
		}
		$data = $backend->getProviderMetadata();
		if ( $data && isset( $data['rights'] ) && is_array( $data['rights'] ) ) {
			return $data['rights'];
		}

		// Should never happen
		$this->logger->debug( __METHOD__ . ': No provider metadata, returning no rights allowed' );
		return [];
	}
}
