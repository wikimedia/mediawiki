<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\WikiMap\WikiMap;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\TransactionProfiler;

/**
 * @defgroup Profiler Profiler
 */

/**
 * Profiler base class that defines the interface and some shared
 * functionality.
 *
 * @ingroup Profiler
 */
abstract class Profiler {
	/** @var string|false Profiler ID for bucketing data */
	protected $profileID = false;
	/** @var array All of the params passed from $wgProfiler */
	protected $params = [];
	/** @var TransactionProfiler */
	protected $trxProfiler;
	/** @var LoggerInterface */
	protected $logger;
	/** @var bool */
	private $allowOutput = false;

	/** @var Profiler */
	private static $instance = null;

	/**
	 * @param array $params See $wgProfiler.
	 */
	public function __construct( array $params ) {
		if ( isset( $params['profileID'] ) ) {
			$this->profileID = $params['profileID'];
		}
		$this->params = $params;
		$this->trxProfiler = new TransactionProfiler();
		$this->logger = LoggerFactory::getInstance( 'profiler' );
	}

	/**
	 * @internal For use by Setup.php
	 * @param array $profilerConf Value from $wgProfiler
	 */
	final public static function init( array $profilerConf ): void {
		$params = $profilerConf + [
			'class'     => ProfilerStub::class,
			'sampling'  => 1,
			'threshold' => 0.0,
			'output'    => [],
			'cliEnable' => false,
		];

		// Avoid global func wfIsCLI() during setup
		$isCLI = ( PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg' );
		$inSample = $params['sampling'] === 1 || mt_rand( 1, $params['sampling'] ) === 1;
		if (
			!$inSample ||
			// On CLI, profiling is disabled by default, and can be explicitly enabled
			// via the `--profiler` option, which MediaWiki\Maintenance\MaintenanceRunner::setup
			// translates into 'cliEnable'.
			// See also $wgProfiler docs.
			//
			// For this to work, Setup.php must call Profiler::init() after handling of
			// MW_FINAL_SETUP_CALLBACK, which is what doMaintenance.php uses to call
			// MaintenanceRunner::setup.
			( $isCLI && !$params['cliEnable'] )
		) {
			$params['class'] = ProfilerStub::class;
		}

		if ( !is_array( $params['output'] ) ) {
			$params['output'] = [ $params['output'] ];
		}

		self::$instance = new $params['class']( $params );
	}

	/**
	 * @return Profiler
	 */
	final public static function instance() {
		if ( !self::$instance ) {
			trigger_error( 'Called Profiler::instance before settings are loaded', E_USER_WARNING );
			self::init( [] );
		}

		return self::$instance;
	}

	/**
	 * @deprecated since 1.41, unused. Can override this base class.
	 * @param string $id
	 */
	public function setProfileID( $id ) {
		wfDeprecated( __METHOD__, '1.41' );
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
	 * Mark the start of a custom profiling frame (e.g. DB queries).
	 * The frame ends when the result of this method falls out of scope.
	 *
	 * @param string $section
	 * @since 1.25
	 */
	abstract public function scopedProfileIn( $section ): ?SectionProfileCallback;

	/**
	 * @param SectionProfileCallback|null &$section
	 */
	public function scopedProfileOut( ?SectionProfileCallback &$section = null ) {
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
	 * @return ProfilerOutput[]
	 * @since 1.25
	 */
	private function getOutputs() {
		$outputs = [];
		foreach ( $this->params['output'] as $outputType ) {
			// The class may be specified as either the full class name (for
			// example, 'ProfilerOutputStats') or (for backward compatibility)
			// the trailing portion of the class name (for example, 'stats').
			$outputClass = !str_contains( $outputType, 'ProfilerOutput' )
				? 'ProfilerOutput' . ucfirst( $outputType )
				: $outputType;
			if ( !class_exists( $outputClass ) ) {
				throw new UnexpectedValueException( "'$outputType' is an invalid output type" );
			}
			$outputInstance = new $outputClass( $this, $this->params );
			if ( $outputInstance->canUse() ) {
				$outputs[] = $outputInstance;
			}
		}
		return $outputs;
	}

	/**
	 * Log data to all the applicable backing stores
	 *
	 * This logs the profiling data to the backing store for each configured ProfilerOutput
	 * instance. It also logs any request data for the TransactionProfiler instance.
	 *
	 * @since 1.25
	 */
	public function logData() {
		if ( $this->params['threshold'] > 0.0 ) {
			// Note, this is also valid for CLI processes.
			$timeElapsed = microtime( true ) - $_SERVER['REQUEST_TIME_FLOAT'];
			if ( $timeElapsed <= $this->params['threshold'] ) {
				return;
			}
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
	 * @deprecated since 1.41. Unused.
	 *
	 * @since 1.34
	 * @return bool
	 */
	public function getAllowOutput() {
		wfDeprecated( __METHOD__, '1.41' );
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
	 * entries for the cyclic invocation should be demarked with "@".
	 * This makes filtering them out easier and follows the xhprof style.
	 *
	 * @return array[] List of method entries arrays, each having:
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
