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
	const MAX_POST_SIZE = 4096;
	/**
	 * Changes preferences of the current user.
	 */
	public function execute() {
		$req = $this->getRequest();
		$this->log = LoggerFactory::getInstance( 'csp' );

		if ( $req->getHeader( 'CONTENT-TYPE' ) !== 'application/json' ) {
			$this->error( 'wrongformat', __METHOD__ );
		}
		if ( $req->getHeader( 'CONTENT-LENGTH' ) > self::MAX_POST_SIZE ) {
			$this->error( 'toobig', __METHOD__ );
		}
		$postBody = $this->getRequest()->getRawInput();
		$status = FormatJson::parse( $postBody, FormatJson::FORCE_ASSOC );
		if ( !$status->isGood() ) {
			// fixme error
			$this->log->info( 'Invalid JSON sent to CSP report' );
			$this->getMain()->getRequest()->response()->statusHeader( 500 );
			$this->dieStatus( $status );
		}

		$report = $status->getValue();

		if ( !isset( $report['csp-report'] ) ) {
			$this->error( 'missingkey' );
		}
		$report = $report['csp-report'];

		$blockedFile = isset( $report['blocked-uri'] ) ? $report['blocked-uri'] : 'n/a';
		$page = isset( $report['document-uri'] ) ? $report['document-uri'] : 'n/a';
		$line = isset( $report['line-number'] ) ? ':' . $report['line-number'] : '';
		$warningText = 'Received CSP report: <' . $blockedFile . '> blocked from being loaded on <' . $page . '>' . $line;
		$this->log->warning( $warningText , array(
			// XXX Is it ok to put untrusted data into log??
			'csp-report' => $report['csp-report'],
			'method' => __METHOD__,
			'user' => $this->getUser()->getName(),
			'user-agent' => $this->getRequest()->getHeader( 'USER-AGENT' )
		) );
		$this->getResult()->addValue( null, $this->getModuleName(), 'success' );

	}

	private function error( $code, $method ) {
		$this->log->info( 'Error reading CSP report: ' . $code, array(
			'method' => $method,
			'user-agent' => $this->getRequest()->getHeader( 'USER-AGENT' ) )
		);
		// 500 so it shows up in browser's developer console.
		$this->dieUsage( "Error processing CSP report: $code", 'cspreport-' . $code, 500 );

	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return false;
	}

	public function getAllowedParams() {
		return array(
		);
	}

	/**
	 * Mark as internal. We don't want randoms submitting CSP reports.
	 */
	public function isInternal() {
		return true;
	}

	/**
	 * Even if you don't have read rights, we still want your report.
	 */
	public function isReadMode() {
		return true;
	}

	// TODO, should this disable shouldCheckMaxLag()? it doesn't touch db.

}
