<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\MainConfigNames;
use MediaWiki\Rest\Response;
use ReportUri\Dbsc\Exception\DbscException;
use ReportUri\Dbsc\Exception\RetryableRefreshException;
use ReportUri\Dbsc\Exception\SessionNotFoundException;
use ReportUri\Dbsc\RequestContext;

/**
 * POST /dbsc/v0/refresh — DBSC refresh.
 *
 * Maps the library contract onto spec status semantics: no proof or a
 * retryable failure => 403 + Secure-Session-Challenge (the UA re-signs);
 * unknown session or any other failure => 404 (the UA terminates it; the
 * binding is kept); success => 200 with rotated cookie and next
 * challenge. Usable requests carry Sec-Secure-Session-Id, a Fetch-forbidden
 * header that scripts and cross-site forms cannot send.
 *
 * @since 1.48
 */
class DeviceBoundSessionRefreshHandler extends DeviceBoundSessionHandlerBase {

	/** @inheritDoc */
	protected function handle( RequestContext $ctx ): Response {
		if ( $this->config->get( MainConfigNames::DeviceBoundSessionsReportOnly ) ) {
			// No bindings exist in report-only mode, so there is nothing to refresh.
			return $this->getResponseFactory()->createHttpError(
				404, [ 'reason' => 'device bound sessions are in report-only mode' ] );
		}
		$server = $this->manager->server();
		if ( $server->getBinding( $ctx ) === null ) {
			// Terminal non-403 4xx so the UA ends the device bound session;
			// issueRefreshChallenge() answers an unknown session with a bare
			// 403 and no challenge, which would loop the UA instead (§8.8).
			return $this->unknownSession();
		}
		$jwt = $this->extractJwt();
		try {
			if ( $jwt === null ) {
				return $this->applyDbscResponse( $server->issueRefreshChallenge( $ctx ), 403 );
			}
			return $this->applyDbscResponse( $server->refresh( $jwt, $ctx ) );
		} catch ( RetryableRefreshException ) {
			try {
				return $this->applyDbscResponse( $server->issueRefreshChallenge( $ctx ), 403 );
			} catch ( SessionNotFoundException ) {
				return $this->unknownSession();
			}
		} catch ( SessionNotFoundException ) {
			return $this->unknownSession();
		} catch ( DbscException $e ) {
			// Keep the binding: anyone holding the session cookie can trigger
			// this, so deleting it would let them drop back to cookie-only auth.
			// Logged here because the library skips malformed proofs.
			$this->manager->log( 'dbscRefreshRejected', $e->getMessage(), $ctx->userId );
			return $this->unknownSession();
		}
	}

	private function unknownSession(): Response {
		return $this->getResponseFactory()->createHttpError( 404, [ 'reason' => 'unknown session' ] );
	}
}
