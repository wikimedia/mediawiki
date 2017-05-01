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
use Wikimedia\ScopedCallback;

/**
 * Profiler base class that defines the interface and some trivial
 * functionality
 *
 * @ingroup Profiler
 */
abstract class Profiler {
	/** @var string|bool Profiler ID for bucketing data */
	protected $profileID = false;
	/** @var bool Whether MediaWiki is in a SkinTemplate output context */
	protected $templated = false;
	/** @var array All of the params passed from $wgProfiler */
	protected $params = [];
	/** @var IContextSource Current request context */
	protected $context = null;
	/** @var TransactionProfiler */
	protected $trxProfiler;
	/** @var Profiler */
	private static $instance = null;

	/**
	 * @param array $params
	 */
	public function __construct( array $params ) {
		if ( isset( $params['profileID'] ) ) {
			$this->profileID = $params['profileID'];
		}
		$this->params = $params;
		$this->trxProfiler = new TransactionProfiler();
	}

	/**
	 * Singleton
	 * @return Profiler
	 */
	final public static function instance() {
		if ( self::$instance === null ) {
			global $wgProfiler, $wgProfileLimit;

			$params = [
				'class'     => 'ProfilerStub',
				'sampling'  => 1,
				'threshold' => $wgProfileLimit,
				'output'    => [],
			];
			if ( is_array( $wgProfiler ) ) {
				$params = array_merge( $params, $wgProfiler );
			}

			$inSample = mt_rand( 0, $params['sampling'] - 1 ) === 0;
			if ( PHP_SAPI === 'cli' || !$inSample ) {
				$params['class'] = 'ProfilerStub';
			}

			if ( !is_array( $params['output'] ) ) {
				$params['output'] = [ $params['output'] ];
			}

			self::$instance = new $params['class']( $params );
		}
		return self::$instance;
	}

	/**
	 * Replace the current profiler with $profiler if no non-stub profiler is set
	 *
	 * @param Profiler $profiler
	 * @throws MWException
	 * @since 1.25
	 */
	final public static function replaceStubInstance( Profiler $profiler ) {
		if ( self::$instance && !( self::$instance instanceof ProfilerStub ) ) {
			throw new MWException( 'Could not replace non-stub profiler instance.' );
		} else {
			self::$instance = $profiler;
		}
	}

	/**
	 * @param string $id
	 */
	public function setProfileID( $id ) {
		$this->profileID = $id;
	}

	/**
	 * @return string
	 */
	public function getProfileID() {
		if ( $this->profileID === false ) {
			return wfWikiID();
		} else {
			return $this->profileID;
		}
	}

	/**
	 * Sets the context for this Profiler
	 *
	 * @param IContextSource $context
	 * @since 1.25
	 */
	public function setContext( $context ) {
		$this->context = $context;
	}

	/**
	 * Gets the context for this Profiler
	 *
	 * @return IContextSource
	 * @since 1.25
	 */
	public function getContext() {
		if ( $this->context ) {
			return $this->context;
		} else {
			wfDebug( __METHOD__ . " called and \$context is null. " .
				"Return RequestContext::getMain(); for sanity\n" );
			return RequestContext::getMain();
		}
	}

	// Kept BC for now, remove when possible
	public function profileIn( $functionname ) {
	}

	public function profileOut( $functionname ) {
	}

	/**
	 * Mark the start of a custom profiling frame (e.g. DB queries).
	 * The frame ends when the result of this method falls out of scope.
	 *
	 * @param string $section
	 * @return ScopedCallback|null
	 * @since 1.25
	 */
	abstract public function scopedProfileIn( $section );

	/**
	 * @param SectionProfileCallback $section
	 */
	public function scopedProfileOut( SectionProfileCallback &$section = null ) {
		$section = null;
	}

	/**
	 * @return TransactionProfiler
	 * @since 1.25
	 */
	public function getTransactionProfiler() {
		return $this->trxProfiler;
	}

	/**
	 * Close opened profiling sections
	 */
	abstract public function close();

	/**
	 * Get all usable outputs.
	 *
	 * @throws MWException
	 * @return array Array of ProfilerOutput instances.
	 * @since 1.25
	 */
	private function getOutputs() {
		$outputs = [];
		foreach ( $this->params['output'] as $outputType ) {
			// The class may be specified as either the full class name (for
			// example, 'ProfilerOutputStats') or (for backward compatibility)
			// the trailing portion of the class name (for example, 'stats').
			$outputClass = strpos( $outputType, 'ProfilerOutput' ) === false
				? 'ProfilerOutput' . ucfirst( $outputType )
				: $outputType;
			if ( !class_exists( $outputClass ) ) {
				throw new MWException( "'$outputType' is an invalid output type" );
			}
			$outputInstance = new $outputClass( $this, $this->params );
			if ( $outputInstance->canUse() ) {
				$outputs[] = $outputInstance;
			}
		}
		return $outputs;
	}

	/**
	 * Log the data to some store or even the page output
	 *
	 * @since 1.25
	 */
	public function logData() {
		$request = $this->getContext()->getRequest();

		$timeElapsed = $request->getElapsedTime();
		$timeElapsedThreshold = $this->params['threshold'];
		if ( $timeElapsed <= $timeElapsedThreshold ) {
			return;
		}

		$outputs = $this->getOutputs();
		if ( !$outputs ) {
			return;
		}

		$stats = $this->getFunctionStats();
		foreach ( $outputs as $output ) {
			$output->log( $stats );
		}
	}

	/**
	 * Output current data to the page output if configured to do so
	 *
	 * @throws MWException
	 * @since 1.26
	 */
	public function logDataPageOutputOnly() {
		foreach ( $this->getOutputs() as $output ) {
			if ( $output instanceof ProfilerOutputText ) {
				$stats = $this->getFunctionStats();
				$output->log( $stats );
			}
		}
	}

	/**
	 * Get the content type sent out to the client.
	 * Used for profilers that output instead of store data.
	 * @return string
	 * @since 1.25
	 */
	public function getContentType() {
		foreach ( headers_list() as $header ) {
			if ( preg_match( '#^content-type: (\w+/\w+);?#i', $header, $m ) ) {
				return $m[1];
			}
		}
		return null;
	}

	/**
	 * Mark this call as templated or not
	 *
	 * @param bool $t
	 */
	public function setTemplated( $t ) {
		$this->templated = $t;
	}

	/**
	 * Was this call as templated or not
	 *
	 * @return bool
	 */
	public function getTemplated() {
		return $this->templated;
	}

	/**
	 * Get the aggregated inclusive profiling data for each method
	 *
	 * The percent time for each time is based on the current "total" time
	 * used is based on all methods so far. This method can therefore be
	 * called several times in between several profiling calls without the
	 * delays in usage of the profiler skewing the results. A "-total" entry
	 * is always included in the results.
	 *
	 * When a call chain involves a method invoked within itself, any
	 * entries for the cyclic invocation should be be demarked with "@".
	 * This makes filtering them out easier and follows the xhprof style.
	 *
	 * @return array List of method entries arrays, each having:
	 *   - name     : method name
	 *   - calls    : the number of invoking calls
	 *   - real     : real time elapsed (ms)
	 *   - %real    : percent real time
	 *   - cpu      : CPU time elapsed (ms)
	 *   - %cpu     : percent CPU time
	 *   - memory   : memory used (bytes)
	 *   - %memory  : percent memory used
	 *   - min_real : min real time in a call (ms)
	 *   - max_real : max real time in a call (ms)
	 * @since 1.25
	 */
	abstract public function getFunctionStats();

	/**
	 * Returns a profiling output to be stored in debug file
	 *
	 * @return string
	 */
	abstract public function getOutput();
}
