<?php
/**
 * Copyright © 2015 Brian Wolff
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Json\FormatJson;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\ContentSecurityPolicy;
use MediaWiki\Utils\UrlUtils;
use Psr\Log\LoggerInterface;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Api module to receive and log CSP violation reports
 *
 * @ingroup API
 */
class ApiCSPReport extends ApiBase {

	private LoggerInterface $log;

	/**
	 * These reports should be small. Ignore super big reports out of paranoia
	 */
	private const MAX_POST_SIZE = 8192;

	private UrlUtils $urlUtils;

	public function __construct(
		ApiMain $main,
		string $action,
		UrlUtils $urlUtils
	) {
		parent::__construct( $main, $action );
		$this->urlUtils = $urlUtils;
	}

	/**
	 * Logs a content-security-policy violation report from web browser.
	 */
	public function execute() {
		$reportOnly = $this->getParameter( 'reportonly' );
		$logname = $reportOnly ? 'csp-report-only' : 'csp';
		$this->log = LoggerFactory::getInstance( $logname );
		$userAgent = $this->getRequest()->getHeader( 'user-agent' );

		$this->verifyPostBodyOk();
		$report = $this->getReport();
		$flags = $this->getFlags( $report, $userAgent );

		$warningText = $this->generateLogLine( $flags, $report );
		$this->logReport( $flags, $warningText, [
			// XXX Is it ok to put untrusted data into log??
			'csp-report' => $report,
			'method' => __METHOD__,
			'user_id' => $this->getUser()->getId() ?: 'logged-out',
			'user-agent' => $userAgent,
			'source' => $this->getParameter( 'source' ),
		] );
		$this->getResult()->addValue( null, $this->getModuleName(), 'success' );
	}

	/**
	 * Log CSP report, with a different severity depending on $flags
	 * @param array $flags Flags for this report
	 * @param string $logLine text of log entry
	 * @param array $context logging context
	 */
	private function logReport( $flags, $logLine, $context ) {
		if ( in_array( 'false-positive', $flags ) ) {
			// These reports probably don't matter much
			$this->log->debug( $logLine, $context );
		} else {
			// Normal report.
			$this->log->warning( $logLine, $context );
		}
	}

	/**
	 * Get extra notes about the report.
	 *
	 * @param array $report The CSP report
	 * @param string $userAgent
	 * @return array
	 */
	private function getFlags( $report, $userAgent ) {
		$reportOnly = $this->getParameter( 'reportonly' );
		$source = $this->getParameter( 'source' );
		$falsePositives = $this->getConfig()->get( MainConfigNames::CSPFalsePositiveUrls );

		$flags = [];
		if ( $source !== 'internal' ) {
			$flags[] = 'source=' . $source;
		}
		if ( $reportOnly ) {
			$flags[] = 'report-only';
		}

		if (
			(
				ContentSecurityPolicy::falsePositiveBrowser( $userAgent ) &&
				$report['blocked-uri'] === "self"
			) ||
			(
				isset( $report['blocked-uri'] ) &&
				$this->matchUrlPattern( $report['blocked-uri'], $falsePositives )
			) ||
			(
				isset( $report['source-file'] ) &&
				$this->matchUrlPattern( $report['source-file'], $falsePositives )
			)
		) {
			// False positive due to:
			// https://bugzilla.mozilla.org/show_bug.cgi?id=1026520

			$flags[] = 'false-positive';
		}
		return $flags;
	}

	/**
	 * @param string $url
	 * @param string[] $patterns
	 * @return bool
	 */
	private function matchUrlPattern( $url, array $patterns ) {
		if ( isset( $patterns[ $url ] ) ) {
			return true;
		}

		$bits = $this->urlUtils->parse( $url );
		if ( !$bits ) {
			return false;
		}

		unset( $bits['user'], $bits['pass'], $bits['query'], $bits['fragment'] );
		$bits['path'] = '';
		$serverUrl = UrlUtils::assemble( $bits );
		if ( isset( $patterns[$serverUrl] ) ) {
			// The origin of the url matches a pattern,
			// e.g. "https://example.org" matches "https://example.org/foo/b?a#r"
			return true;
		}
		foreach ( $patterns as $pattern => $val ) {
			// We only use this pattern if it ends in a slash, this prevents
			// "/foos" from matching "/foo", and "https://good.combo.bad" matching
			// "https://good.com".
			if ( str_ends_with( $pattern, '/' ) && str_starts_with( $url, $pattern ) ) {
				// The pattern starts with the same as the url
				// e.g. "https://example.org/foo/" matches "https://example.org/foo/b?a#r"
				return true;
			}
		}

		return false;
	}

	/**
	 * Output an api error if post body is obviously not OK.
	 */
	private function verifyPostBodyOk() {
		$req = $this->getRequest();
		$contentType = $req->getHeader( 'content-type' );
		if ( $contentType !== 'application/json'
			&& $contentType !== 'application/csp-report'
		) {
			$this->error( 'wrongformat', __METHOD__ );
		}
		if ( $req->getHeader( 'content-length' ) > self::MAX_POST_SIZE ) {
			$this->error( 'toobig', __METHOD__ );
		}
	}

	/**
	 * Get the report from post body and turn into associative array.
	 *
	 * @return array
	 */
	private function getReport() {
		$postBody = $this->getRequest()->getRawInput();
		if ( strlen( $postBody ) > self::MAX_POST_SIZE ) {
			// paranoia, already checked content-length earlier.
			$this->error( 'toobig', __METHOD__ );
		}
		$status = FormatJson::parse( $postBody, FormatJson::FORCE_ASSOC );
		if ( !$status->isGood() ) {
			$msg = $status->getMessages()[0]->getKey();
			$this->error( $msg, __METHOD__ );
		}

		$report = $status->getValue();

		if ( !isset( $report['csp-report'] ) ) {
			$this->error( 'missingkey', __METHOD__ );
		}
		return $report['csp-report'];
	}

	/**
	 * Get text of log line.
	 *
	 * @param array $flags of additional markers for this report
	 * @param array $report the csp report
	 * @return string Text to put in log
	 */
	private function generateLogLine( $flags, $report ) {
		$flagText = '';
		if ( $flags ) {
			$flagText = '[' . implode( ', ', $flags ) . ']';
		}

		$blockedOrigin = isset( $report['blocked-uri'] )
			? $this->originFromUrl( $report['blocked-uri'] )
			: 'n/a';
		$page = $report['document-uri'] ?? 'n/a';
		$line = isset( $report['line-number'] )
			? ':' . $report['line-number']
			: '';
		return $flagText .
			' Received CSP report: <' . $blockedOrigin . '>' .
			' blocked from being loaded on <' . $page . '>' . $line;
	}

	/**
	 * @param string $url
	 * @return string
	 */
	private function originFromUrl( $url ) {
		$bits = $this->urlUtils->parse( $url ) ?? [];
		unset( $bits['user'], $bits['pass'], $bits['query'], $bits['fragment'] );
		$bits['path'] = '';
		// e.g. "https://example.org" from "https://example.org/foo/b?a#r"
		return UrlUtils::assemble( $bits );
	}

	/**
	 * Stop processing the request, and output/log an error
	 *
	 * @param string $code error code
	 * @param string $method method that made error
	 * @throws ApiUsageException Always
	 */
	private function error( $code, $method ) {
		$this->log->info( 'Error reading CSP report: ' . $code, [
			'method' => $method,
			'user-agent' => $this->getRequest()->getHeader( 'user-agent' )
		] );
		// Return 400 on error for user agents to display, e.g. to the console.
		$this->dieWithError(
			[ 'apierror-csp-report', wfEscapeWikiText( $code ) ], 'cspreport-' . $code, [], 400
		);
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'reportonly' => [
				ParamValidator::PARAM_TYPE => 'boolean',
				ParamValidator::PARAM_DEFAULT => false
			],
			'source' => [
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_DEFAULT => 'internal',
				ParamValidator::PARAM_REQUIRED => false
			]
		];
	}

	/** @inheritDoc */
	public function mustBePosted() {
		return true;
	}

	/**
	 * Mark as internal. This isn't meant to be used by normal api users
	 * @return bool
	 */
	public function isInternal() {
		return true;
	}

	/**
	 * Even if you don't have read rights, we still want your report.
	 * @return bool
	 */
	public function isReadMode() {
		return false;
	}

	/**
	 * Doesn't touch db, so max lag should be rather irrelevant.
	 *
	 * Also, this makes sure that reports aren't lost during lag events.
	 * @return bool
	 */
	public function shouldCheckMaxLag() {
		return false;
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiCSPReport::class, 'ApiCSPReport' );
