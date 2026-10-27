<?php

namespace MediaWiki\Session;

use Exception;
use MediaWiki\Request\WebRequest;
use MediaWiki\Request\WebResponse;
use Psr\Log\LoggerInterface;
use ReportUri\Dbsc\Exception\JwtInvalidException;
use ReportUri\Dbsc\JwtVerifier;

/**
 * Report-only mode: a session is offered a registration once. A
 * browser that supports DBSC generates a device key and posts a
 * signed proof; the proof is verified, the attempt logged, and
 * registration then refused, so no binding is stored and the refresh
 * endpoint stays closed. Offers against verified attempts give the
 * support rate.
 *
 * @since 1.48
 * @ingroup Session
 */
class DeviceBoundSessionReporter {

	/** Marks a session as already offered, so each browser is probed once. */
	private const SESSION_FLAG = 'DeviceBoundReportOnlyOffered';

	public function __construct(
		private DeviceBoundSessionManager $manager,
		private DeviceBoundSessionStore $store,
		private LoggerInterface $logger,
		private string $secret,
	) {
	}

	/**
	 * Offer a registration to a logged-in session, at most once per session:
	 * repeating it would burn the browser's device key quota
	 *
	 * @param Session $session
	 * @param WebRequest $request
	 * @param WebResponse $response
	 */
	public function offerOnce( Session $session, WebRequest $request, WebResponse $response ): void {
		if ( $session->get( self::SESSION_FLAG ) ) {
			return;
		}
		if ( !$session->getProvider() instanceof CookieSessionProvider ) {
			// Token and header providers cannot hold a bound cookie
			return;
		}
		if ( !$session->getUser()->isRegistered() ) {
			return;
		}
		// set so a failure below is not retried on every page view
		$session->set( self::SESSION_FLAG, true );
		$session->save();
		try {
			$ctx = $this->manager->buildContext( $session, [], [] );
			if ( $ctx === null ) {
				return;
			}
			$offer = $this->manager->server()->buildRegistrationHeaderResponse( $ctx );
			foreach ( $offer->headers as $name => $value ) {
				$response->header( "$name: $value" );
			}
			$this->log( 'offered', $session, $request, [] );
		} catch ( Exception $e ) {
			// log should never break a page render
			$this->logger->warning( 'DBSC report-only offer failed', [ 'exception' => $e ] );
		}
	}

	/**
	 * Record a registration attempt and report whether its proof was genuine.
	 *
	 * @param Session $session
	 * @param WebRequest $request
	 * @param string $jwt Proof from Secure-Session-Response
	 * @return bool Whether the proof verified against a live challenge
	 */
	public function recordAttempt( Session $session, WebRequest $request, string $jwt ): bool {
		$verifier = new JwtVerifier();
		$algorithm = null;
		$failure = null;
		try {
			$result = $verifier->verifyRegistrationJwt( $verifier->parse( $jwt ) );
			$algorithm = $result->algorithm;
			$pending = $this->store->getPendingRegistration( (string)$session->getId() );
			if ( $pending === null ) {
				$failure = 'no-pending-challenge';
			} elseif ( !hash_equals( $pending->regChallenge, $result->challenge ) ) {
				$failure = 'challenge-mismatch';
			}
		} catch ( JwtInvalidException ) {
			$failure = 'invalid-proof';
		}
		// The challenge is spent either way; a retry needs a fresh offer
		$this->store->deletePendingRegistration( (string)$session->getId() );
		$this->log( 'registrationAttempt', $session, $request, [
			'dbscVerified' => $failure === null,
			'dbscFailure' => $failure,
			'dbscAlgorithm' => $algorithm,
		] );
		return $failure === null;
	}

	/**
	 * @param string $stage
	 * @param Session $session
	 * @param WebRequest $request
	 * @param array $extra
	 */
	private function log( string $stage, Session $session, WebRequest $request, array $extra ): void {
		$subject = $this->manager->getSubject( $session->getUser() );
		$extra += $request->getSecurityLogContext( $session->getUser() );
		$this->logger->info( 'DBSC report-only: {dbscStage}', [
			'dbscEvent' => 'dbscReportOnly',
			'dbscStage' => $stage,
			// Hashed so attempts can be counted per user without identifying one
			'dbscSubject' => $subject !== null
				? substr( hash_hmac( 'sha256', $subject, $this->secret ), 0, 16 )
				: null,
		] + $extra );
	}
}
