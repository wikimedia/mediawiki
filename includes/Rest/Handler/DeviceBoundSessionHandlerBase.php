<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Config\Config;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\Response;
use MediaWiki\Session\DeviceBoundSessionManager;
use ReportUri\Dbsc\DbscResponse;
use ReportUri\Dbsc\RequestContext;

/**
 * Base for the DBSC REST endpoints: POST-only routes that return 404 unless
 * $wgDeviceBoundSessions and 401 without an authenticated session.
 *
 * requireSafeAgainstCsrf() stays false since cookie sessions must be able to
 * call these; the DBSC protocol handlers CSRF safety. Normal CORS rules apply
 * as a refresh triggered during a cross-origin request is itself cross-origin
 * (spec §8.8). Allowed CORS origins are further restricted by the
 * allowed_refresh_initiators response field during registration, to be
 * implemented in a future patch.
 *
 * @since 1.48
 */
abstract class DeviceBoundSessionHandlerBase extends Handler {

	public function __construct(
		protected DeviceBoundSessionManager $manager,
		protected Config $config,
	) {
	}

	/**
	 * Handle a request that passed the feature and session gates.
	 */
	abstract protected function handle( RequestContext $ctx ): Response;

	public function execute(): Response {
		if ( !$this->config->get( MainConfigNames::DeviceBoundSessions ) ) {
			return $this->getResponseFactory()->createHttpError(
				404, [ 'reason' => 'device-bound sessions disabled' ] );
		}
		$ctx = $this->manager->buildContext(
			$this->getSession(), $this->flattenedHeaders(), $this->getRequest()->getCookieParams()
		);
		if ( $ctx === null ) {
			return $this->getResponseFactory()->createHttpError(
				401, [ 'reason' => 'login required' ] );
		}
		return $this->handle( $ctx );
	}

	/**
	 * Proof JWT from the Secure-Session-Response header.
	 */
	protected function extractJwt(): ?string {
		$header = $this->getRequest()->getHeaderLine( 'Secure-Session-Response' );
		return $header !== '' ? DeviceBoundSessionManager::parseSfString( $header ) : null;
	}

	/**
	 * Apply a library DbscResponse (status, headers, cookies, body) to a REST
	 * Response.
	 *
	 * @param DbscResponse $dbsc
	 * @param int $defaultStatus Used when the library leaves status null
	 * @return Response
	 */
	protected function applyDbscResponse( DbscResponse $dbsc, int $defaultStatus = 200 ): Response {
		$response = $this->getResponseFactory()->create();
		$response->setStatus( $dbsc->status ?? $defaultStatus );
		foreach ( $dbsc->headers as $name => $value ) {
			$response->setHeader( $name, $value );
		}
		foreach ( $dbsc->cookies as $cookie ) {
			$response->setCookie( $cookie->name, $cookie->delete ? '' : $cookie->value,
				$cookie->delete ? 1 : $cookie->expiresAt,
				[
					// __Host- requires the name verbatim and no Domain.
					'prefix' => '',
					'domain' => '',
					'path' => $cookie->path,
					'secure' => $cookie->secure,
					'httpOnly' => $cookie->httpOnly,
					'sameSite' => $cookie->sameSite,
				] );
		}
		if ( $dbsc->body !== null ) {
			$response->setHeader( 'Content-Type', $dbsc->contentType ?? 'application/json' );
			$response->getBody()->write( $dbsc->body );
		}
		return $response;
	}

	/**
	 * @return array<string,string> First value of every request header
	 */
	private function flattenedHeaders(): array {
		$flat = [];
		foreach ( $this->getRequest()->getHeaders() as $name => $values ) {
			$flat[$name] = is_array( $values ) ? (string)( $values[0] ?? '' ) : (string)$values;
		}
		return $flat;
	}

	/** @inheritDoc */
	public function needsWriteAccess() {
		return true;
	}

	/** @inheritDoc */
	public function requireSafeAgainstCsrf() {
		return false;
	}
}
