<?php
/**
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
 * @author Aaron Schulz
 */

/**
 * This is a simple class to handle action=runjobs and is only used internally
 *
 * @note: this does not requre "write mode" nor tokens due to the signature check
 *
 * @ingroup API
 */
class ApiRunJobs extends ApiBase {
	public function execute() {
		if ( wfReadOnly() ) {
			$this->dieUsage( 'Wiki is in read-only mode', 'read_only', 400 );
		}

		$params = $this->extractRequestParams();
		$squery = $this->getRequest()->getValues();
		unset( $squery['signature'] );
		$cSig = self::getQuerySignature( $squery );
		$rSig = $params['signature'];

		// Time-insensitive signature verification
		if ( strlen( $rSig ) !== strlen( $cSig ) ) {
			$verified = false;
		} else {
			$result = 0;
			for ( $i = 0; $i < strlen( $cSig ); $i++ ) {
				$result |= ord( $cSig{$i} ) ^ ord( $rSig{$i} );
			}
			$verified = ( $result == 0 );
		}

		if ( !$verified || $params['sigexpiry'] < time() ) {
			$this->dieUsage( 'Invalid or stale signature provided', 'bad_signature', 401 );
		}

		// Client will usually disconnect before checking the response,
		// but it needs to know when it is safe to disconnect. Until this
		// reaches ignore_user_abort(), it is not safe as the jobs won't run.
		ignore_user_abort( true ); // jobs may take a bit of time
		header( "HTTP/1.0 204 No Content" );
		ob_flush();
        flush();
		// Once the client receives this response, it can disconnect

		// Do all of the specified tasks...
		if ( in_array( 'jobs', $params['tasks'] ) ) {
			$this->executeJobs( $params );
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
	 * @param array $params Request parameters
	 * @return void
	 */
	protected function executeJobs( array $params ) {
		$n = $params['maxjobs']; // number of jobs to run
		if ( $n < 1 ) {
			return;
		}
		try {
			// Fallback to running the jobs here while the user waits
			$group = JobQueueGroup::singleton();
			do {
				$job = $group->pop( JobQueueGroup::USE_CACHE ); // job from any queue
				if ( $job ) {
					$output = $job->toString() . "\n";
					$t = - microtime( true );
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
			// We don't want exceptions thrown during job execution to
			// be reported to the user since the output is already sent.
			// Instead we just log them.
			MWExceptionHandler::logException( $e );
		}
	}

	public function mustBePosted() {
		return true;
	}

	public function getAllowedParams() {
		return array(
			'tasks' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => array( 'jobs' )
			),
			'maxjobs' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_DFLT => 0
			),
			'signature' =>  array(
				ApiBase::PROP_TYPE => 'string',
			),
			'sigexpiry' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_DFLT => 0 // ~epoch
			),
		);
	}

	public function getParamDescription() {
		return array(
			'tasks' => 'List of task types to perform',
			'maxjobs' => 'Maximum number of jobs to run',
			'signature' => 'HMAC Signature that signs the request',
			'sigexpiry' => 'HMAC signature expiry as a UNIX timestamp'
		);
	}

	public function getDescription() {
		return 'Perform periodic tasks or run jobs from the queue';
	}

	public function getExamples() {
		return array(
			'api.php?action=runjobs&tasks=jobs&maxjobs=1' => 'Run up to 3 jobs from the queue',
		);
	}
}
