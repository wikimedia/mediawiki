<?php
/**
 * Base class for profiling.
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
 * @ingroup Profiler
 * @defgroup Profiler Profiler
 */

/**
 * Profiler base class that defines the interface and some trivial
 * functionality
 *
 * @ingroup Profiler
 */
abstract class Profiler {
	/** @var string|bool Profiler ID for bucketing data */
	protected $mProfileID = false;
	/** @var bool Whether MediaWiki is in a SkinTemplate output context */
	protected $mTemplated = false;

	/** @var TransactionProfiler */
	protected $trxProfiler;

	// @codingStandardsIgnoreStart PSR2.Classes.PropertyDeclaration.Underscore
	/** @var Profiler Do not call this outside Profiler and ProfileSection */
	public static $__instance = null;
	// @codingStandardsIgnoreEnd

	/**
	 * @param array $params
	 */
	public function __construct( array $params ) {
		if ( isset( $params['profileID'] ) ) {
			$this->mProfileID = $params['profileID'];
		}
		$this->trxProfiler = new TransactionProfiler();
	}

	/**
	 * Singleton
	 * @return Profiler
	 */
	final public static function instance() {
		if ( self::$__instance === null ) {
			global $wgProfiler;
			if ( is_array( $wgProfiler ) ) {
				if ( !isset( $wgProfiler['class'] ) ) {
					$class = 'ProfilerStub';
				} else {
					$class = $wgProfiler['class'];
				}
				self::$__instance = new $class( $wgProfiler );
			} else {
				self::$__instance = new ProfilerStub( array() );
			}
		}
		return self::$__instance;
	}

	/**
	 * Set the profiler to a specific profiler instance. Mostly for dumpHTML
	 * @param Profiler $p
	 */
	final public static function setInstance( Profiler $p ) {
		self::$__instance = $p;
	}

	/**
	 * Return whether this a stub profiler
	 *
	 * @return bool
	 */
	abstract public function isStub();

	/**
	 * Return whether this profiler stores data
	 *
	 * Called by Parser::braceSubstitution. If true, the parser will not
	 * generate per-title profiling sections, to avoid overloading the
	 * profiling data collector.
	 *
	 * @see Profiler::logData()
	 * @return bool
	 */
	abstract public function isPersistent();

	/**
	 * @param string $id
	 */
	public function setProfileID( $id ) {
		$this->mProfileID = $id;
	}

	/**
	 * @return string
	 */
	public function getProfileID() {
		if ( $this->mProfileID === false ) {
			return wfWikiID();
		} else {
			return $this->mProfileID;
		}
	}

	/**
	 * Called by wfProfieIn()
	 *
	 * @param string $functionname
	 */
	abstract public function profileIn( $functionname );

	/**
	 * Called by wfProfieOut()
	 *
	 * @param string $functionname
	 */
	abstract public function profileOut( $functionname );

	/**
	 * Mark a DB as in a transaction with one or more writes pending
	 *
	 * Note that there can be multiple connections to a single DB.
	 *
	 * @param string $server DB server
	 * @param string $db DB name
	 * @param string $id Resource ID string of connection
	 */
	public function transactionWritingIn( $server, $db, $id = '' ) {
		$this->trxProfiler->transactionWritingIn( $server, $db, $id );
	}

	/**
	 * Mark a DB as no longer in a transaction
	 *
	 * This will check if locks are possibly held for longer than
	 * needed and log any affected transactions to a special DB log.
	 * Note that there can be multiple connections to a single DB.
	 *
	 * @param string $server DB server
	 * @param string $db DB name
	 * @param string $id Resource ID string of connection
	 */
	public function transactionWritingOut( $server, $db, $id = '' ) {
		$this->trxProfiler->transactionWritingOut( $server, $db, $id );
	}

	/**
	 * Close opened profiling sections
	 */
	abstract public function close();

	/**
	 * Log the data to some store or even the page output
	 */
	abstract public function logData();

	/**
	 * Mark this call as templated or not
	 *
	 * @param bool $t
	 */
	public function setTemplated( $t ) {
		$this->mTemplated = $t;
	}

	/**
	 * Returns a profiling output to be stored in debug file
	 *
	 * @return string
	 */
	abstract public function getOutput();

	/**
	 * @return array
	 */
	abstract public function getRawData();

	/**
	 * Get the initial time of the request, based either on $wgRequestTime or
	 * $wgRUstart. Will return null if not able to find data.
	 *
	 * @param string|bool $metric Metric to use, with the following possibilities:
	 *   - user: User CPU time (without system calls)
	 *   - cpu: Total CPU time (user and system calls)
	 *   - wall (or any other string): elapsed time
	 *   - false (default): will fall back to default metric
	 * @return float|null
	 */
	protected function getTime( $metric = 'wall' ) {
		if ( $metric === 'cpu' || $metric === 'user' ) {
			$ru = wfGetRusage();
			if ( !$ru ) {
				return 0;
			}
			$time = $ru['ru_utime.tv_sec'] + $ru['ru_utime.tv_usec'] / 1e6;
			if ( $metric === 'cpu' ) {
				# This is the time of system calls, added to the user time
				# it gives the total CPU time
				$time += $ru['ru_stime.tv_sec'] + $ru['ru_stime.tv_usec'] / 1e6;
			}
			return $time;
		} else {
			return microtime( true );
		}
	}

	/**
	 * Get the initial time of the request, based either on $wgRequestTime or
	 * $wgRUstart. Will return null if not able to find data.
	 *
	 * @param string|bool $metric Metric to use, with the following possibilities:
	 *   - user: User CPU time (without system calls)
	 *   - cpu: Total CPU time (user and system calls)
	 *   - wall (or any other string): elapsed time
	 *   - false (default): will fall back to default metric
	 * @return float|null
	 */
	protected function getInitialTime( $metric = 'wall' ) {
		global $wgRequestTime, $wgRUstart;

		if ( $metric === 'cpu' || $metric === 'user' ) {
			if ( !count( $wgRUstart ) ) {
				return null;
			}

			$time = $wgRUstart['ru_utime.tv_sec'] + $wgRUstart['ru_utime.tv_usec'] / 1e6;
			if ( $metric === 'cpu' ) {
				# This is the time of system calls, added to the user time
				# it gives the total CPU time
				$time += $wgRUstart['ru_stime.tv_sec'] + $wgRUstart['ru_stime.tv_usec'] / 1e6;
			}
			return $time;
		} else {
			if ( empty( $wgRequestTime ) ) {
				return null;
			} else {
				return $wgRequestTime;
			}
		}
	}

	/**
	 * Add an entry in the debug log file
	 *
	 * @param string $s String to output
	 */
	protected function debug( $s ) {
		if ( function_exists( 'wfDebug' ) ) {
			wfDebug( $s );
		}
	}

	/**
	 * Add an entry in the debug log group
	 *
	 * @param string $group Group to send the message to
	 * @param string $s String to output
	 */
	protected function debugGroup( $group, $s ) {
		if ( function_exists( 'wfDebugLog' ) ) {
			wfDebugLog( $group, $s );
		}
	}
}
