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
use Wikimedia\Rdbms\TransactionProfiler;

/**
 * Profiler base class that defines the interface and some trivial
 * functionality
 *
 * @ingroup Profiler
 */
abstract class Profiler {
	/** @var string|bool Profiler ID for bucketing data */
	protected $profileID = false;
	/** @var array All of the params passed from $wgProfiler */
	protected $params = [];
	/** @var IContextSource Current request context */
	protected $context = null;
	/** @var TransactionProfiler */
	protected $trxProfiler;
	/** @var bool */
	private $allowOutput = false;

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
				'class'     => ProfilerStub::class,
				'sampling'  => 1,
				'threshold' => $wgProfileLimit,
				'output'    => [],
			];
			if ( is_array( $wgProfiler ) ) {
				$params = array_merge( $params, $wgProfiler );
			}

			$inSample = mt_rand( 0, $params['sampling'] - 1 ) === 0;
			// wfIsCLI() is not available yet
			if ( PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg' || !$inSample ) {
				$params['class'] = ProfilerStub::class;
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
			return WikiMap::getCurrentWikiDbDomain()->getId();
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

	public function profileIn( $functionname ) {
		wfDeprecated( __METHOD__, '1.33' );
	}

	public function profileOut( $functionname ) {
		wfDeprecated( __METHOD__, '1.33' );
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
	 * @param SectionProfileCallback|null &$section
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
	 * @return ProfilerOutput[]
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
	 * Log the data to the backing store for all ProfilerOutput instances that have one
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

		$outputs = [];
		foreach ( $this->getOutputs() as $output ) {
			if ( !$output->logsToOutput() ) {
				$outputs[] = $output;
			}
		}

		if ( $outputs ) {
			$stats = $this->getFunctionStats();
			foreach ( $outputs as $output ) {
				$output->log( $stats );
			}
		}
	}

	/**
	 * Log the data to the script/request output for all ProfilerOutput instances that do so
	 *
	 * @throws MWException
	 * @since 1.26
	 */
	public function logDataPageOutputOnly() {
		if ( !$this->allowOutput ) {
			return;
		}

		$outputs = [];
		foreach ( $this->getOutputs() as $output ) {
			if ( $output->logsToOutput() ) {
				$outputs[] = $output;
			}
		}

		if ( $outputs ) {
			$stats = $this->getFunctionStats();
			foreach ( $outputs as $output ) {
				$output->log( $stats );
			}
		}
	}

	/**
	 * Get the Content-Type for deciding how to format appended profile output.
	 *
	 * Disabled by default. Enable via setAllowOutput().
	 *
	 * @see ProfilerOutputText
	 * @since 1.25
	 * @return string|null Returns null if disabled or no Content-Type found.
	 */
	public function getContentType() {
		if ( $this->allowOutput ) {
			foreach ( headers_list() as $header ) {
				if ( preg_match( '#^content-type: (\w+/\w+);?#i', $header, $m ) ) {
					return $m[1];
				}
			}
		}
		return null;
	}

	/**
	 * Mark this call as templated or not
	 *
	 * @deprecated since 1.34 Use setAllowOutput() instead.
	 * @param bool $t
	 */
	public function setTemplated( $t ) {
		wfDeprecated( __METHOD__, '1.34' );
		$this->allowOutput = ( $t === true );
	}

	/**
	 * Was this call as templated or not
	 *
	 * @deprecated since 1.34 Use getAllowOutput() instead.
	 * @return bool
	 */
	public function getTemplated() {
		wfDeprecated( __METHOD__, '1.34' );
		return $this->getAllowOutput();
	}

	/**
	 * Enable appending profiles to standard output.
	 *
	 * @since 1.34
	 */
	public function setAllowOutput() {
		$this->allowOutput = true;
	}

	/**
	 * Whether appending profiles is allowed.
	 *
	 * @since 1.34
	 * @return bool
	 */
	public function getAllowOutput() {
		return $this->allowOutput;
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
