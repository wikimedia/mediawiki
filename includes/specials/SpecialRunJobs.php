<?php
/**
 * Implements Special:RunJobs
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
 * @ingroup SpecialPage
 */

use MediaWiki\Logger\LoggerFactory;

/**
 * Special page designed for running background tasks (internal use only)
 *
 * @ingroup SpecialPage
 */
class SpecialRunJobs extends UnlistedSpecialPage {
	public function __construct() {
		parent::__construct( 'RunJobs' );
	}

	public function doesWrites() {
		return true;
	}

	public function execute( $par = '' ) {
		$this->getOutput()->disable();

		if ( wfReadOnly() ) {
			wfHttpError( 423, 'Locked', 'Wiki is in read-only mode.' );
			return;
		}

		// Validate request method
		if ( !$this->getRequest()->wasPosted() ) {
			wfHttpError( 400, 'Bad Request', 'Request must be POSTed.' );
			return;
		}

		// Validate request parameters
		$optional = [ 'maxjobs' => 0, 'maxtime' => 30, 'type' => false,
			'async' => true, 'stats' => false ];
		$required = array_flip( [ 'title', 'tasks', 'signature', 'sigexpiry' ] );
		$params = array_intersect_key( $this->getRequest()->getValues(), $required + $optional );
		$missing = array_diff_key( $required, $params );
		if ( count( $missing ) ) {
			wfHttpError( 400, 'Bad Request',
				'Missing parameters: ' . implode( ', ', array_keys( $missing ) )
			);
			return;
		}

		// Validate request signature
		$squery = $params;
		unset( $squery['signature'] );
		$correctSignature = self::getQuerySignature( $squery, $this->getConfig()->get( 'SecretKey' ) );
		$providedSignature = $params['signature'];
		$verified = is_string( $providedSignature )
			&& hash_equals( $correctSignature, $providedSignature );
		if ( !$verified || $params['sigexpiry'] < time() ) {
			wfHttpError( 400, 'Bad Request', 'Invalid or stale signature provided.' );
			return;
		}

		// Apply any default parameter values
		$params += $optional;

		if ( $params['async'] ) {
			// HTTP 202 Accepted
			HttpStatus::header( 202 );
			// Clients are meant to disconnect without waiting for the full response.
			// Let the page output happen before the jobs start, so that clients know it's
			// safe to disconnect. MediaWiki::preOutputCommit() calls ignore_user_abort()
			// or similar to make sure we stay alive to run the deferred update.
			DeferredUpdates::addUpdate(
				new TransactionRoundDefiningUpdate(
					function () use ( $params ) {
						$this->doRun( $params );
					},
					__METHOD__
				),
				DeferredUpdates::POSTSEND
			);
		} else {
			$stats = $this->doRun( $params );

			if ( $params['stats'] ) {
				$this->getRequest()->response()->header( 'Content-Type: application/json' );
				print FormatJson::encode( $stats );
			} else {
				print "Done\n";
			}
		}
	}

	protected function doRun( array $params ) {
		$runner = new JobRunner( LoggerFactory::getInstance( 'runJobs' ) );
		return $runner->run( [
			'type'     => $params['type'],
			'maxJobs'  => $params['maxjobs'] ?: 1,
			'maxTime'  => $params['maxtime'] ?: 30
		] );
	}

	/**
	 * @param array $query
	 * @param string $secretKey
	 * @return string
	 */
	public static function getQuerySignature( array $query, $secretKey ) {
		ksort( $query ); // stable order
		return hash_hmac( 'sha1', wfArrayToCgi( $query ), $secretKey );
	}
}
