<?php

namespace MediaWiki\Session;

use Closure;
use LogicException;
use MediaWiki\User\UserIdentity;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use ReportUri\Dbsc\AuditLoggerInterface;
use ReportUri\Dbsc\DbscServer;
use ReportUri\Dbsc\RequestContext;

/**
 * MediaWiki glue for report-uri/dbsc-php, which owns the DBSC wire protocol.
 * This service owns what is MediaWiki-specific: the binding subject (central
 * id under SUL, keyed local hash otherwise), RequestContext construction,
 * farm-wide sign-out, and audit-to-PSR logging.
 *
 * @since 1.48
 * @ingroup Session
 */
class DeviceBoundSessionManager implements LoggerAwareInterface, AuditLoggerInterface {

	private LoggerInterface $logger;
	private ?DbscServer $server = null;

	/**
	 * @param Closure $serverFactory Signature fn( AuditLoggerInterface ): DbscServer
	 * @param DeviceBoundSessionStore $store
	 * @param SessionManagerInterface $sessionManager
	 * @param string $canonicalServer $wgCanonicalServer
	 */
	public function __construct(
		private Closure $serverFactory,
		private DeviceBoundSessionStore $store,
		private SessionManagerInterface $sessionManager,
		private string $canonicalServer,
	) {
		$this->logger = new NullLogger();
	}

	public function setLogger( LoggerInterface $logger ): void {
		$this->logger = $logger;
	}

	public function server(): DbscServer {
		if ( $this->server === null ) {
			$server = ( $this->serverFactory )( $this );
			if ( !$server instanceof DbscServer ) {
				throw new LogicException( 'DBSC server factory must return a DbscServer' );
			}
			$this->server = $server;
		}
		return $this->server;
	}

	/**
	 * Scoped central id identifying the user a binding belongs to. Farm-wide
	 * sign-out keys on this, so it must not vary per session.
	 *
	 * @param UserIdentity $user
	 * @return string|null Null if the user is anonymous or has no central id
	 */
	public function getSubject( UserIdentity $user ): ?string {
		if ( !$user->isRegistered() ) {
			return null;
		}
		$subject = $this->sessionManager->getSubjectIdentifier( $user );
		// The anonymous identifier is shared by every unresolvable user, so a
		// binding keyed to it would be sign-out collateral for all of them.
		return $subject === 'mw:' . SessionManager::JWT_SUB_ANON ? null : $subject;
	}

	/**
	 * Build the library's RequestContext for the current MediaWiki session.
	 *
	 * @param Session $session
	 * @param array<string,string> $headers Flattened request headers
	 * @param array<string,string> $cookies Request cookies, unprefixed names
	 * @return RequestContext|null Null when the session cannot be bound
	 */
	public function buildContext( Session $session, array $headers, array $cookies ): ?RequestContext {
		$subject = $this->getSubject( $session->getUser() );
		if ( $subject === null ) {
			return null;
		}
		return new RequestContext(
			(string)$session->getId(), $subject, $this->canonicalServer, $headers, $cookies
		);
	}

	/**
	 * Farm-wide sign-out for a subject.
	 */
	public function terminateAllForSubject( string $subject ): void {
		$this->store->terminateAllForSubject( $subject );
	}

	/**
	 * Parse an RFC 9651 sf-string item; proof and session-id headers arrive quoted.
	 */
	public static function parseSfString( string $raw ): string {
		$raw = trim( $raw );
		if ( strlen( $raw ) >= 2 && str_starts_with( $raw, '"' ) && str_ends_with( $raw, '"' ) ) {
			return stripcslashes( substr( $raw, 1, -1 ) );
		}
		return $raw;
	}

	/** @inheritDoc */
	public function log( string $event, string $message, ?string $userId ): void {
		$this->logger->info( 'DBSC {dbscEvent}: {dbscMessage}', [
			'dbscEvent' => $event,
			'dbscMessage' => $message,
			'dbscSubject' => $userId,
		] );
	}
}
