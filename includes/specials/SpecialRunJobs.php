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

		$optional = array( 'maxjobs' => 0, 'maxtime' => 30, 'type' => false, 'async' => true );
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
		$correctSignature = self::getQuerySignature( $squery, $this->getConfig()->get( 'SecretKey' ) );
		$providedSignature = $params['signature'];

		$verified = is_string( $providedSignature )
			&& hash_equals( $correctSignature, $providedSignature );
		if ( !$verified || $params['sigexpiry'] < time() ) {
			header( "HTTP/1.0 400 Bad Request" );
			print 'Invalid or stale signature provided';

			return;
		}

		// Apply any default parameter values
		$params += $optional;

		if ( $params['async'] ) {
			// Client will usually disconnect before checking the response,
			// but it needs to know when it is safe to disconnect. Until this
			// reaches ignore_user_abort(), it is not safe as the jobs won't run.
			ignore_user_abort( true ); // jobs may take a bit of time
			header( "HTTP/1.0 202 Accepted" );
			ob_flush();
			flush();
			// Once the client receives this response, it can disconnect
		}

		// Do all of the specified tasks...
		if ( in_array( 'jobs', explode( '|', $params['tasks'] ) ) ) {
			$runner = new JobRunner( LoggerFactory::getInstance( 'runJobs' ) );
			$response = $runner->run( array(
				'type'     => $params['type'],
				'maxJobs'  => $params['maxjobs'] ? $params['maxjobs'] : 1,
				'maxTime'  => $params['maxtime'] ? $params['maxjobs'] : 30
			) );
			if ( !$params['async'] ) {
				print FormatJson::encode( $response, true );
			}
		}
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
