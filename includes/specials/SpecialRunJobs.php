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
 * @author Aaron Schulz
 */

/**
 * Special page designed for running background tasks (internal use only)
 *
 * @ingroup SpecialPage
 */
class SpecialRunJobs extends UnlistedSpecialPage {
	public function __construct() {
		parent::__construct( 'RunJobs' );
	}

	public function execute( $par = '' ) {
		$this->getOutput()->disable();

		if ( wfReadOnly() ) {
			header( "HTTP/1.0 423 Locked" );
			print 'Wiki is in read-only mode';

			return;
		} elseif ( !$this->getRequest()->wasPosted() ) {
			header( "HTTP/1.0 400 Bad Request" );
			print 'Request must be POSTed';

			return;
		}

		$optional = array( 'maxjobs' => 0 );
		$required = array_flip( array( 'title', 'tasks', 'signature', 'sigexpiry' ) );

		$params = array_intersect_key( $this->getRequest()->getValues(), $required + $optional );
		$missing = array_diff_key( $required, $params );
		if ( count( $missing ) ) {
			header( "HTTP/1.0 400 Bad Request" );
			print 'Missing parameters: ' . implode( ', ', array_keys( $missing ) );

			return;
		}

		$squery = $params;
		unset( $squery['signature'] );
		$cSig = self::getQuerySignature( $squery ); // correct signature
		$rSig = $params['signature']; // provided signature

		// Constant-time signature verification
		// http://www.emerose.com/timing-attacks-explained
		// @todo: make a common method for this
		if ( !is_string( $rSig ) || strlen( $rSig ) !== strlen( $cSig ) ) {
			$verified = false;
		} else {
			$result = 0;
			for ( $i = 0; $i < strlen( $cSig ); $i++ ) {
				$result |= ord( $cSig[$i] ) ^ ord( $rSig[$i] );
			}
			$verified = ( $result == 0 );
		}
		if ( !$verified || $params['sigexpiry'] < time() ) {
			header( "HTTP/1.0 400 Bad Request" );
			print 'Invalid or stale signature provided';

			return;
		}

		// Apply any default parameter values
		$params += $optional;

		// Client will usually disconnect before checking the response,
		// but it needs to know when it is safe to disconnect. Until this
		// reaches ignore_user_abort(), it is not safe as the jobs won't run.
		ignore_user_abort( true ); // jobs may take a bit of time
		header( "HTTP/1.0 202 Accepted" );
		ob_flush();
		flush();
		// Once the client receives this response, it can disconnect

		// Do all of the specified tasks...
		if ( in_array( 'jobs', explode( '|', $params['tasks'] ) ) ) {
			self::executeJobs( (int)$params['maxjobs'] );
		}
	}

	/**
	 * @param array $query
	 * @return string
	 */
	public static function getQuerySignature( array $query ) {
		global $wgSecretKey;

		ksort( $query ); // stable order
		return hash_hmac( 'sha1', wfArrayToCgi( $query ), $wgSecretKey );
	}

	/**
	 * Run jobs from the job queue
	 *
	 * @note: also called from Wiki.php
	 *
	 * @param integer $maxJobs Maximum number of jobs to run
	 * @return void
	 */
	public static function executeJobs( $maxJobs ) {
		$n = $maxJobs; // number of jobs to run
		if ( $n < 1 ) {
			return;
		}
		try {
			$group = JobQueueGroup::singleton();
			$count = $group->executeReadyPeriodicTasks();
			if ( $count > 0 ) {
				wfDebugLog( 'jobqueue', "Executed $count periodic queue task(s)." );
			}

			do {
				$job = $group->pop( JobQueueGroup::TYPE_DEFAULT, JobQueueGroup::USE_CACHE );
				if ( $job ) {
					$output = $job->toString() . "\n";
					$t = -microtime( true );
					wfProfileIn( __METHOD__ . '-' . get_class( $job ) );
					$success = $job->run();
					wfProfileOut( __METHOD__ . '-' . get_class( $job ) );
					$group->ack( $job ); // done
					$t += microtime( true );
					$t = round( $t * 1000 );
					if ( $success === false ) {
						$output .= "Error: " . $job->getLastError() . ", Time: $t ms\n";
					} else {
						$output .= "Success, Time: $t ms\n";
					}
					wfDebugLog( 'jobqueue', $output );
				}
			} while ( --$n && $job );
		} catch ( MWException $e ) {
			MWExceptionHandler::rollbackMasterChangesAndLog( $e );
			// We don't want exceptions thrown during job execution to
			// be reported to the user since the output is already sent.
			// Instead we just log them.
			MWExceptionHandler::logException( $e );
		}
	}
}
