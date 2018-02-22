<?php
/**
 * Copyright © 2015 Brian Wolff
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

use MediaWiki\Logger\LoggerFactory;

/**
 * Api module to receive and log CSP violation reports
 *
 * @ingroup API
 */
class ApiCSPReport extends ApiBase {

	private $log;

	/**
	 * These reports should be small. Ignore super big reports out of paranoia
	 */
	const MAX_POST_SIZE = 8192;

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
		$flags = $this->getFlags( $report );

		$warningText = $this->generateLogLine( $flags, $report );
		$this->logReport( $flags, $warningText, [
			// XXX Is it ok to put untrusted data into log??
			'csp-report' => $report,
			'method' => __METHOD__,
			'user' => $this->getUser()->getName(),
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
	 * @return array
	 */
	private function getFlags( $report ) {
		$reportOnly = $this->getParameter( 'reportonly' );
		$source = $this->getParameter( 'source' );
		$falsePositives = $this->getConfig()->get( 'CSPFalsePositiveUrls' );

		$flags = [];
		if ( $source !== 'internal' ) {
			$flags[] = 'source=' . $source;
		}
		if ( $reportOnly ) {
			$flags[] = 'report-only';
		}

		if (
			( isset( $report['blocked-uri'] ) &&
			isset( $falsePositives[$report['blocked-uri']] ) )
			|| ( isset( $report['source-file'] ) &&
			isset( $falsePositives[$report['source-file']] ) )
		) {
			// Report caused by Ad-Ware
			$flags[] = 'false-positive';
		}
		return $flags;
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
	 * @return Array
	 */
	private function getReport() {
		$postBody = $this->getRequest()->getRawInput();
		if ( strlen( $postBody ) > self::MAX_POST_SIZE ) {
			// paranoia, already checked content-length earlier.
			$this->error( 'toobig', __METHOD__ );
		}
		$status = FormatJson::parse( $postBody, FormatJson::FORCE_ASSOC );
		if ( !$status->isGood() ) {
			$msg = $status->getErrors()[0]['message'];
			if ( $msg instanceof Message ) {
				$msg = $msg->getKey();
			}
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

		$blockedFile = isset( $report['blocked-uri'] ) ? $report['blocked-uri'] : 'n/a';
		$page = isset( $report['document-uri'] ) ? $report['document-uri'] : 'n/a';
		$line = isset( $report['line-number'] ) ? ':' . $report['line-number'] : '';
		$warningText = $flagText .
			' Received CSP report: <' . $blockedFile .
			'> blocked from being loaded on <' . $page . '>' . $line;
		return $warningText;
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

	public function getAllowedParams() {
		return [
			'reportonly' => [
				ApiBase::PARAM_TYPE => 'boolean',
				ApiBase::PARAM_DFLT => false
			],
			'source' => [
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_DFLT => 'internal',
				ApiBase::PARAM_REQUIRED => false
			]
		];
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return false;
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
	 * Doesn't touch db, so max lag should be rather irrelavent.
	 *
	 * Also, this makes sure that reports aren't lost during lag events.
	 * @return bool
	 */
	public function shouldCheckMaxLag() {
		return false;
	}
}
