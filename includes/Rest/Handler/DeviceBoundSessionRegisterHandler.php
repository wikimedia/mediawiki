<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Rest\Response;
use ReportUri\Dbsc\Exception\DbscException;
use ReportUri\Dbsc\RequestContext;

/**
 * POST /dbsc/v0/register — DBSC registration.
 *
 * On success the body is the JSON session instructions and the bound
 * credential cookie is set. Any failure is a non-403 4xx, telling the UA to
 * terminate the registration attempt (spec §8.8).
 *
 * @since 1.48
 */
class DeviceBoundSessionRegisterHandler extends DeviceBoundSessionHandlerBase {

	/** @inheritDoc */
	protected function handle( RequestContext $ctx ): Response {
		$jwt = $this->extractJwt();
		if ( $jwt === null ) {
			return $this->getResponseFactory()->createHttpError(
				400, [ 'reason' => 'missing registration proof' ] );
		}
		if ( $this->config->get( MainConfigNames::DeviceBoundSessionsReportOnly ) ) {
			// Measure only: no binding is stored, so the user agent drops the
			// session attempt on this 4xx.
			MediaWikiServices::getInstance()->getDeviceBoundSessionReporter()
				->recordAttempt( $this->getSession(), $this->getSession()->getRequest(), $jwt );
			return $this->getResponseFactory()->createHttpError(
				400, [ 'reason' => 'device bound sessions are in report-only mode' ] );
		}
		try {
			return $this->applyDbscResponse( $this->manager->server()->register( $jwt, $ctx ) );
		} catch ( DbscException $e ) {
			return $this->getResponseFactory()->createHttpError( 400, [ 'reason' => $e->getMessage() ] );
		}
	}
}
