<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Session;

use InvalidArgumentException;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\GrantsInfo;
use MediaWiki\Request\WebRequest;
use MediaWiki\User\BotPassword;
use MediaWiki\User\User;
use MWRestrictions;

/**
 * Session provider for bot passwords
 *
 * @since 1.27
 * @ingroup Session
 */
class BotPasswordSessionProvider extends ImmutableSessionProviderWithCookie {
	private GrantsInfo $grantsInfo;

	/** @var bool Whether the current request is an API request. */
	private $isApiRequest;

	/**
	 * @param GrantsInfo $grantsInfo
	 * @param array $params Keys include:
	 *  - priority: (required) Set the priority
	 *  - sessionCookieName: Session cookie name. Default is '_BPsession'.
	 *  - sessionCookieOptions: Options to pass to WebResponse::setCookie().
	 *  - isApiRequest: Whether the current request is an API request. Should be only set in tests.
	 */
	public function __construct( GrantsInfo $grantsInfo, array $params = [] ) {
		if ( !isset( $params['sessionCookieName'] ) ) {
			$params['sessionCookieName'] = '_BPsession';
		}
		parent::__construct( $params );

		if ( !isset( $params['priority'] ) ) {
			throw new InvalidArgumentException( __METHOD__ . ': priority must be specified' );
		}
		if ( $params['priority'] < SessionInfo::MIN_PRIORITY ||
			$params['priority'] > SessionInfo::MAX_PRIORITY
		) {
			throw new InvalidArgumentException( __METHOD__ . ': Invalid priority' );
		}

		$this->priority = $params['priority'];

		$this->grantsInfo = $grantsInfo;

		$this->isApiRequest = $params['isApiRequest']
			?? ( defined( 'MW_API' ) || defined( 'MW_REST_API' ) );
	}

	/** @inheritDoc */
	public function provideSessionInfo( WebRequest $request ) {
		// Only relevant for the (Action or REST) API
		if ( !$this->isApiRequest ) {
			return null;
		}

		// Enabled?
		if ( !$this->getConfig()->get( MainConfigNames::EnableBotPasswords ) ) {
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

	/** @inheritDoc */
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
				'rights' => $this->grantsInfo->getGrantRights( $bp->getGrants() ),
				'restrictions' => $bp->getRestrictions()->toJson(),
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
		$metadata['rights'] = $this->grantsInfo->getGrantRights( $bp->getGrants() );

		return true;
	}

	/**
	 * @codeCoverageIgnore
	 * @inheritDoc
	 */
	public function preventSessionsForUser( $username ) {
		BotPassword::removeAllPasswordsForUser( $username );
	}

	/** @inheritDoc */
	public function getAllowedUserRights( SessionBackend $backend ) {
		if ( $backend->getProvider() !== $this ) {
			throw new InvalidArgumentException( 'Backend\'s provider isn\'t $this' );
		}
		$data = $backend->getProviderMetadata();
		if ( $data && isset( $data['rights'] ) && is_array( $data['rights'] ) ) {
			return $data['rights'];
		}

		// Should never happen
		$this->logger->debug( __METHOD__ . ': No provider metadata, returning no rights allowed' );
		return [];
	}

	public function getRestrictions( ?array $data ): ?MWRestrictions {
		if ( $data && isset( $data['restrictions'] ) && is_string( $data['restrictions'] ) ) {
			try {
				return MWRestrictions::newFromJson( $data['restrictions'] );
			} catch ( InvalidArgumentException ) {
				$this->logger->warning( __METHOD__ . ': Failed to parse restrictions: {restrictions}', [
					'restrictions' => $data['restrictions']
				] );
				return null;
			}
		}
		return null;
	}
}
