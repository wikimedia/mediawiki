<?php
/**
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

use MediaWiki\Debug\MWDebug;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Json\FormatJson;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Request\WebRequest;
use Psr\Log\LogLevel;
use Wikimedia\NormalizedException\INormalizedException;
use Wikimedia\Rdbms\DBError;
use Wikimedia\Rdbms\DBQueryError;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Services\RecursiveServiceDependencyException;

/**
 * Handler class for MWExceptions
 * @ingroup Exception
 */
class MWExceptionHandler {
	/** Error caught and reported by this exception handler */
	public const CAUGHT_BY_HANDLER = 'mwe_handler';
	/** Error caught and reported by a script entry point */
	public const CAUGHT_BY_ENTRYPOINT = 'entrypoint';
	/** Error reported by direct logException() call */
	public const CAUGHT_BY_OTHER = 'other';

	/** @var string|null */
	protected static $reservedMemory;

	/**
	 * Error types that, if unhandled, are fatal to the request.
	 * These error types may be thrown as Error objects, which implement Throwable (but not Exception).
	 *
	 * The user will be shown an HTTP 500 Internal Server Error.
	 * As such, these should be sent to MediaWiki's "exception" channel.
	 * Normally, the error handler logs them to the "error" channel.
	 */
	private const FATAL_ERROR_TYPES = [
		E_ERROR,
		E_PARSE,
		E_CORE_ERROR,
		E_COMPILE_ERROR,
		E_USER_ERROR,

		// E.g. "Catchable fatal error: Argument X must be Y, null given"
		E_RECOVERABLE_ERROR,
	];

	/**
	 * Whether exception data should include a backtrace.
	 *
	 * @var bool
	 */
	private static $logExceptionBacktrace = true;

	/**
	 * Whether to propagate errors to PHP's built-in handler.
	 *
	 * @var bool
	 */
	private static $propagateErrors;

	/**
	 * Install handlers with PHP.
	 * @internal
	 * @param bool $logExceptionBacktrace Whether error handlers should include a backtrace
	 *        in the log.
	 * @param bool $propagateErrors Whether errors should be propagated to PHP's built-in handler.
	 */
	public static function installHandler(
		bool $logExceptionBacktrace = true,
		bool $propagateErrors = true
	) {
		self::$logExceptionBacktrace = $logExceptionBacktrace;
		self::$propagateErrors = $propagateErrors;

		// This catches:
		// * Exception objects that were explicitly thrown but not
		//   caught anywhere in the application. This is rare given those
		//   would normally be caught at a high-level like MediaWiki::run (index.php),
		//   api.php, or ResourceLoader::respond (load.php). These high-level
		//   catch clauses would then call MWExceptionHandler::logException
		//   or MWExceptionHandler::handleException.
		//   If they are not caught, then they are handled here.
		// * Error objects for issues that would historically
		//   cause fatal errors but may now be caught as Throwable (not Exception).
		//   Same as previous case, but more common to bubble to here instead of
		//   caught locally because they tend to not be safe to recover from.
		//   (e.g. argument TypeError, division by zero, etc.)
		set_exception_handler( [ self::class, 'handleUncaughtException' ] );

		// This catches recoverable errors (e.g. PHP Notice, PHP Warning, PHP Error) that do not
		// interrupt execution in any way. We log these in the background and then continue execution.
		set_error_handler( [ self::class, 'handleError' ] );

		// This catches fatal errors for which no Throwable is thrown,
		// including Out-Of-Memory and Timeout fatals.
		// Reserve 16k of memory so we can report OOM fatals.
		self::$reservedMemory = str_repeat( ' ', 16384 );
		register_shutdown_function( [ self::class, 'handleFatalError' ] );
	}

	/**
	 * Report a throwable to the user
	 * @param Throwable $e
	 */
	protected static function report( Throwable $e ) {
		try {
			// Try and show the exception prettily, with the normal skin infrastructure
			if ( $e instanceof MWException && $e->hasOverriddenHandler() ) {
				// Delegate to MWException until all subclasses are handled by
				// MWExceptionRenderer and MWException::report() has been
				// removed.
				$e->report();
			} else {
				MWExceptionRenderer::output( $e, MWExceptionRenderer::AS_PRETTY );
			}
		} catch ( Throwable $e2 ) {
			// Exception occurred from within exception handler
			// Show a simpler message for the original exception,
			// don't try to invoke report()
			MWExceptionRenderer::output( $e, MWExceptionRenderer::AS_RAW, $e2 );
		}
	}

	/**
	 * Roll back any open database transactions
	 *
	 * This method is used to attempt to recover from exceptions
	 */
	private static function rollbackPrimaryChanges() {
		if ( !MediaWikiServices::hasInstance() ) {
			// MediaWiki isn't fully initialized yet, it's not safe to access services.
			// This also means that there's nothing to roll back yet.
			return;
		}

		$services = MediaWikiServices::getInstance();
		$lbFactory = $services->peekService( 'DBLoadBalancerFactory' );
		'@phan-var LBFactory $lbFactory'; /* @var LBFactory $lbFactory */
		if ( !$lbFactory ) {
			// There's no need to roll back transactions if the LBFactory is
			// disabled or hasn't been created yet
			return;
		}

		// Roll back DBs to avoid transaction notices. This might fail
		// to roll back some databases due to connection issues or exceptions.
		// However, any sensible DB driver will roll back implicitly anyway.
		try {
			$lbFactory->rollbackPrimaryChanges( __METHOD__ );
			$lbFactory->flushPrimarySessions( __METHOD__ );
		} catch ( DBError $e ) {
			// If the DB is unreachable, rollback() will throw an error
			// and the error report() method might need messages from the DB,
			// which would result in an exception loop. PHP may escalate such
			// errors to "Exception thrown without a stack frame" fatals, but
			// it's better to be explicit here.
			self::logException( $e, self::CAUGHT_BY_HANDLER );
		}
	}

	/**
	 * Roll back any open database transactions and log the stack trace of the throwable
	 *
	 * This method is used to attempt to recover from exceptions
	 *
	 * @since 1.37
	 * @param Throwable $e
	 * @param string $catcher CAUGHT_BY_* class constant indicating what caught the error
	 */
	public static function rollbackPrimaryChangesAndLog(
		Throwable $e,
		$catcher = self::CAUGHT_BY_OTHER
	) {
		self::rollbackPrimaryChanges();

		self::logException( $e, $catcher );
	}

	/**
	 * Callback to use with PHP's set_exception_handler.
	 *
	 * @since 1.31
	 * @param Throwable $e
	 */
	public static function handleUncaughtException( Throwable $e ) {
		self::handleException( $e, self::CAUGHT_BY_HANDLER );

		// Make sure we don't claim success on exit for CLI scripts (T177414)
		if ( wfIsCLI() ) {
			register_shutdown_function(
				/**
				 * @return never
				 */
				static function () {
					exit( 255 );
				}
			);
		}
	}

	/**
	 * Exception handler which simulates the appropriate catch() handling:
	 *
	 *   try {
	 *       ...
	 *   } catch ( Exception $e ) {
	 *       $e->report();
	 *   } catch ( Exception $e ) {
	 *       echo $e->__toString();
	 *   }
	 *
	 * @since 1.25
	 * @param Throwable $e
	 * @param string $catcher CAUGHT_BY_* class constant indicating what caught the error
	 */
	public static function handleException( Throwable $e, $catcher = self::CAUGHT_BY_OTHER ) {
		self::rollbackPrimaryChangesAndLog( $e, $catcher );
		self::report( $e );
	}

	/**
	 * Handler for set_error_handler() callback notifications.
	 *
	 * Receive a callback from the interpreter for a raised error, create an
	 * ErrorException, and log the exception to the 'error' logging
	 * channel(s).
	 *
	 * @since 1.25
	 * @param int $level Error level raised
	 * @param string $message
	 * @param string|null $file
	 * @param int|null $line
	 * @return bool
	 */
	public static function handleError(
		$level,
		$message,
		$file = null,
		$line = null
	) {
		// E_STRICT is deprecated since PHP 8.4 (T375707).
		// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
		if ( defined( 'E_STRICT' ) && $level == @constant( 'E_STRICT' ) ) {
			$level = E_USER_NOTICE;
		}

		// Map PHP error constant to a PSR-3 severity level.
		// Avoid use of "DEBUG" or "INFO" levels, unless the
		// error should evade error monitoring and alerts.
		//
		// To decide the log level, ask yourself: "Has the
		// program's behaviour diverged from what the written
		// code expected?"
		//
		// For example, use of a deprecated method or violating a strict standard
		// has no impact on functional behaviour (Warning). On the other hand,
		// accessing an undefined variable makes behaviour diverge from what the
		// author intended/expected. PHP recovers from an undefined variables by
		// yielding null and continuing execution, but it remains a change in
		// behaviour given the null was not part of the code and is likely not
		// accounted for.
		switch ( $level ) {
			case E_WARNING:
			case E_CORE_WARNING:
			case E_COMPILE_WARNING:
				$prefix = 'PHP Warning: ';
				$severity = LogLevel::ERROR;
				break;
			case E_NOTICE:
				$prefix = 'PHP Notice: ';
				$severity = LogLevel::ERROR;
				break;
			case E_USER_NOTICE:
				// Used by wfWarn(), MWDebug::warning()
				$prefix = 'PHP Notice: ';
				$severity = LogLevel::WARNING;
				break;
			case E_USER_WARNING:
				// Used by wfWarn(), MWDebug::warning()
				$prefix = 'PHP Warning: ';
				$severity = LogLevel::WARNING;
				break;
			case E_DEPRECATED:
				$prefix = 'PHP Deprecated: ';
				$severity = LogLevel::WARNING;
				break;
			case E_USER_DEPRECATED:
				$prefix = 'PHP Deprecated: ';
				$severity = LogLevel::WARNING;
				$real = MWDebug::parseCallerDescription( $message );
				if ( $real ) {
					// Used by wfDeprecated(), MWDebug::deprecated()
					// Apply caller offset from wfDeprecated() to the native error.
					// This makes errors easier to aggregate and find in e.g. Kibana.
					$file = $real['file'];
					$line = $real['line'];
					$message = $real['message'];
				}
				break;
			default:
				$prefix = 'PHP Unknown error: ';
				$severity = LogLevel::ERROR;
				break;
		}

		// @phan-suppress-next-line PhanTypeMismatchArgumentNullableInternal False positive
		$e = new ErrorException( $prefix . $message, 0, $level, $file, $line );
		self::logError( $e, $severity, self::CAUGHT_BY_HANDLER );

		// If $propagateErrors is true return false so PHP shows/logs the error normally.
		// Ignore $propagateErrors if track_errors is set
		// (which means someone is counting on regular PHP error handling behavior).
		return !( self::$propagateErrors || ini_get( 'track_errors' ) );
	}

	/**
	 * Callback used as a registered shutdown function.
	 *
	 * This is used as callback from the interpreter at system shutdown.
	 * If the last error was not a recoverable error that we already reported,
	 * and log as fatal exception.
	 *
	 * Special handling is included for missing class errors as they may
	 * indicate that the user needs to install 3rd-party libraries via
	 * Composer or other means.
	 *
	 * @since 1.25
	 * @return bool Always returns false
	 */
	public static function handleFatalError() {
		// Free reserved memory so that we have space to process OOM
		// errors
		self::$reservedMemory = null;

		$lastError = error_get_last();
		if ( $lastError === null ) {
			return false;
		}

		$level = $lastError['type'];
		$message = $lastError['message'];
		$file = $lastError['file'];
		$line = $lastError['line'];

		if ( !in_array( $level, self::FATAL_ERROR_TYPES ) ) {
			// Only interested in fatal errors, others should have been
			// handled by MWExceptionHandler::handleError
			return false;
		}

		$msgParts = [
			'[{reqId}] {exception_url}   PHP Fatal Error',
			( $line || $file ) ? ' from' : '',
			$line ? " line $line" : '',
			( $line && $file ) ? ' of' : '',
			$file ? " $file" : '',
			": $message",
		];
		$msg = implode( '', $msgParts );

		// Look at message to see if this is a class not found failure (Class 'foo' not found)
		if ( preg_match( "/Class '\w+' not found/", $message ) ) {
			// phpcs:disable Generic.Files.LineLength
			$msg = <<<TXT
{$msg}

MediaWiki or an installed extension requires this class but it is not embedded directly in MediaWiki's git repository and must be installed separately by the end user.

Please see <a href="https://www.mediawiki.org/wiki/Download_from_Git#Fetch_external_libraries">mediawiki.org</a> for help on installing the required components.
TXT;
			// phpcs:enable
		}

		$e = new ErrorException( "PHP Fatal Error: {$message}", 0, $level, $file, $line );
		$logger = LoggerFactory::getInstance( 'exception' );
		$logger->error( $msg, self::getLogContext( $e, self::CAUGHT_BY_HANDLER ) );

		return false;
	}

	/**
	 * Generate a string representation of a throwable's stack trace
	 *
	 * Like Throwable::getTraceAsString, but replaces argument values with
	 * their type or class name, and prepends the start line of the throwable.
	 *
	 * @param Throwable $e
	 * @return string
	 * @see prettyPrintTrace()
	 */
	public static function getRedactedTraceAsString( Throwable $e ) {
		$from = 'from ' . $e->getFile() . '(' . $e->getLine() . ')' . "\n";
		return $from . self::prettyPrintTrace( self::getRedactedTrace( $e ) );
	}

	/**
	 * Generate a string representation of a stacktrace.
	 *
	 * @since 1.26
	 * @param array $trace
	 * @param string $pad Constant padding to add to each line of trace
	 * @return string
	 */
	public static function prettyPrintTrace( array $trace, $pad = '' ) {
		$text = '';

		$level = 0;
		foreach ( $trace as $level => $frame ) {
			if ( isset( $frame['file'] ) && isset( $frame['line'] ) ) {
				$text .= "{$pad}#{$level} {$frame['file']}({$frame['line']}): ";
			} else {
				// 'file' and 'line' are unset for calls from C code
				// (T57634) This matches behaviour of
				// Throwable::getTraceAsString to instead display "[internal
				// function]".
				$text .= "{$pad}#{$level} [internal function]: ";
			}

			if ( isset( $frame['class'] ) && isset( $frame['type'] ) && isset( $frame['function'] ) ) {
				$text .= $frame['class'] . $frame['type'] . $frame['function'];
			} else {
				$text .= $frame['function'] ?? 'NO_FUNCTION_GIVEN';
			}

			if ( isset( $frame['args'] ) ) {
				$text .= '(' . implode( ', ', $frame['args'] ) . ")\n";
			} else {
				$text .= "()\n";
			}
		}

		$level++;
		$text .= "{$pad}#{$level} {main}";

		return $text;
	}

	/**
	 * Return a copy of a throwable's backtrace as an array.
	 *
	 * Like Throwable::getTrace, but replaces each element in each frame's
	 * argument array with the name of its class (if the element is an object)
	 * or its type (if the element is a PHP primitive).
	 *
	 * @since 1.22
	 * @param Throwable $e
	 * @return array
	 */
	public static function getRedactedTrace( Throwable $e ) {
		return static::redactTrace( $e->getTrace() );
	}

	/**
	 * Redact a stacktrace generated by Throwable::getTrace(),
	 * debug_backtrace() or similar means. Replaces each element in each
	 * frame's argument array with the name of its class (if the element is an
	 * object) or its type (if the element is a PHP primitive).
	 *
	 * @since 1.26
	 * @param array $trace Stacktrace
	 * @return array Stacktrace with argument values converted to data types
	 */
	public static function redactTrace( array $trace ) {
		return array_map( static function ( $frame ) {
			if ( isset( $frame['args'] ) ) {
				$frame['args'] = array_map( 'get_debug_type', $frame['args'] );
			}
			return $frame;
		}, $trace );
	}

	/**
	 * If the exception occurred in the course of responding to a request,
	 * returns the requested URL. Otherwise, returns false.
	 *
	 * @since 1.23
	 * @return string|false
	 */
	public static function getURL() {
		if ( MW_ENTRY_POINT === 'cli' ) {
			return false;
		}
		return WebRequest::getGlobalRequestURL();
	}

	/**
	 * Get a message formatting the throwable message and its origin.
	 *
	 * Despite the method name, this is not used for logging.
	 * It is only used for HTML or CLI output by MWExceptionRenderer.
	 *
	 * @since 1.22
	 * @param Throwable $e
	 * @return string
	 */
	public static function getLogMessage( Throwable $e ) {
		$id = WebRequest::getRequestId();
		$type = get_class( $e );
		$message = $e->getMessage();
		$url = self::getURL() ?: '[no req]';

		if ( $e instanceof DBQueryError ) {
			$message = "A database query error has occurred. Did you forget to run"
				. " your application's database schema updater after upgrading"
				. " or after adding a new extension?\n\nPlease see"
				. " https://www.mediawiki.org/wiki/Special:MyLanguage/Manual:Upgrading and"
				. " https://www.mediawiki.org/wiki/Special:MyLanguage/Manual:How_to_debug"
				. " for more information.\n\n"
				. $message;
		}

		return "[$id] $url   $type: $message";
	}

	/**
	 * Get a normalised message for formatting with PSR-3 log event context.
	 *
	 * Must be used together with `getLogContext()` to be useful.
	 *
	 * @since 1.30
	 * @param Throwable $e
	 * @return string
	 */
	public static function getLogNormalMessage( Throwable $e ) {
		if ( $e instanceof INormalizedException ) {
			$message = $e->getNormalizedMessage();
		} else {
			$message = $e->getMessage();
		}
		if ( !$e instanceof ErrorException ) {
			// ErrorException is something we use internally to represent
			// PHP errors (runtime warnings that aren't thrown or caught),
			// don't bother putting it in the logs. Let the log message
			// lead with "PHP Warning: " instead (see ::handleError).
			$message = get_class( $e ) . ": $message";
		}

		return "[{reqId}] {exception_url}   $message";
	}

	/**
	 * @param Throwable $e
	 * @return string
	 */
	public static function getPublicLogMessage( Throwable $e ) {
		$reqId = WebRequest::getRequestId();
		$type = get_class( $e );
		return '[' . $reqId . '] '
			. gmdate( 'Y-m-d H:i:s' ) . ': '
			. 'Fatal exception of type "' . $type . '"';
	}

	/**
	 * Get a PSR-3 log event context from a Throwable.
	 *
	 * Creates a structured array containing information about the provided
	 * throwable that can be used to augment a log message sent to a PSR-3
	 * logger.
	 *
	 * @since 1.26
	 * @param Throwable $e
	 * @param string $catcher CAUGHT_BY_* class constant indicating what caught the error
	 * @return array
	 */
	public static function getLogContext( Throwable $e, $catcher = self::CAUGHT_BY_OTHER ) {
		$context = [
			'exception' => $e,
			'exception_url' => self::getURL() ?: '[no req]',
			// The reqId context key use the same familiar name and value as the top-level field
			// provided by LogstashFormatter. However, formatters are configurable at run-time,
			// and their top-level fields are logically separate from context keys and cannot be,
			// substituted in a message, hence set explicitly here. For WMF users, these may feel,
			// like the same thing due to Monolog V0 handling, which transmits "fields" and "context",
			// in the same JSON object (after message formatting).
			'reqId' => WebRequest::getRequestId(),
			'caught_by' => $catcher
		];
		if ( $e instanceof INormalizedException ) {
			$context += $e->getMessageContext();
		}
		return $context;
	}

	/**
	 * Get a structured representation of a Throwable.
	 *
	 * Returns an array of structured data (class, message, code, file,
	 * backtrace) derived from the given throwable. The backtrace information
	 * will be redacted as per getRedactedTraceAsArray().
	 *
	 * @param Throwable $e
	 * @param string $catcher CAUGHT_BY_* class constant indicating what caught the error
	 * @return array
	 * @since 1.26
	 */
	public static function getStructuredExceptionData(
		Throwable $e,
		$catcher = self::CAUGHT_BY_OTHER
	) {
		$data = [
			'id' => WebRequest::getRequestId(),
			'type' => get_class( $e ),
			'file' => $e->getFile(),
			'line' => $e->getLine(),
			'message' => $e->getMessage(),
			'code' => $e->getCode(),
			'url' => self::getURL() ?: null,
			'caught_by' => $catcher
		];

		if ( $e instanceof ErrorException &&
			( error_reporting() & $e->getSeverity() ) === 0
		) {
			// Flag suppressed errors
			$data['suppressed'] = true;
		}

		if ( self::$logExceptionBacktrace ) {
			$data['backtrace'] = self::getRedactedTrace( $e );
		}

		$previous = $e->getPrevious();
		if ( $previous !== null ) {
			$data['previous'] = self::getStructuredExceptionData( $previous, $catcher );
		}

		return $data;
	}

	/**
	 * Serialize a Throwable object to JSON.
	 *
	 * The JSON object will have keys 'id', 'file', 'line', 'message', and
	 * 'url'. These keys map to string values, with the exception of 'line',
	 * which is a number, and 'url', which may be either a string URL or
	 * null if the throwable did not occur in the context of serving a web
	 * request.
	 *
	 * If $wgLogExceptionBacktrace is true, it will also have a 'backtrace'
	 * key, mapped to the array return value of Throwable::getTrace, but with
	 * each element in each frame's "args" array (if set) replaced with the
	 * argument's class name (if the argument is an object) or type name (if
	 * the argument is a PHP primitive).
	 *
	 * @par Sample JSON record ($wgLogExceptionBacktrace = false):
	 * @code
	 *  {
	 *    "id": "c41fb419",
	 *    "type": "Exception",
	 *    "file": "/var/www/mediawiki/includes/cache/MessageCache.php",
	 *    "line": 704,
	 *    "message": "Example message",
	 *    "url": "/wiki/Main_Page"
	 *  }
	 * @endcode
	 *
	 * @par Sample JSON record ($wgLogExceptionBacktrace = true):
	 * @code
	 *  {
	 *    "id": "dc457938",
	 *    "type": "Exception",
	 *    "file": "/var/www/mediawiki/includes/cache/MessageCache.php",
	 *    "line": 704,
	 *    "message": "Example message",
	 *    "url": "/wiki/Main_Page",
	 *    "backtrace": [{
	 *      "file": "/var/www/mediawiki/includes/OutputPage.php",
	 *      "line": 80,
	 *      "function": "get",
	 *      "class": "MessageCache",
	 *      "type": "->",
	 *      "args": ["array"]
	 *    }]
	 *  }
	 * @endcode
	 *
	 * @since 1.23
	 * @param Throwable $e
	 * @param bool $pretty Add non-significant whitespace to improve readability (default: false).
	 * @param int $escaping Bitfield consisting of FormatJson::.*_OK class constants.
	 * @param string $catcher CAUGHT_BY_* class constant indicating what caught the error
	 * @return string|false JSON string if successful; false upon failure
	 */
	public static function jsonSerializeException(
		Throwable $e,
		$pretty = false,
		$escaping = 0,
		$catcher = self::CAUGHT_BY_OTHER
	) {
		return FormatJson::encode(
			self::getStructuredExceptionData( $e, $catcher ),
			$pretty,
			$escaping
		);
	}

	/**
	 * Log a throwable to the exception log (if enabled).
	 *
	 * This method must not assume the throwable is an MWException,
	 * it is also used to handle PHP exceptions or exceptions from other libraries.
	 *
	 * @since 1.22
	 * @param Throwable $e
	 * @param string $catcher CAUGHT_BY_* class constant indicating what caught the error
	 * @param array $extraData (since 1.34) Additional data to log
	 */
	public static function logException(
		Throwable $e,
		$catcher = self::CAUGHT_BY_OTHER,
		$extraData = []
	) {
		if ( !( $e instanceof MWException ) || $e->isLoggable() ) {
			$logger = LoggerFactory::getInstance( 'exception' );
			$context = self::getLogContext( $e, $catcher );
			if ( $extraData ) {
				$context['extraData'] = $extraData;
			}
			$logger->error(
				self::getLogNormalMessage( $e ),
				$context
			);

			$json = self::jsonSerializeException( $e, false, FormatJson::ALL_OK, $catcher );
			if ( $json !== false ) {
				$logger = LoggerFactory::getInstance( 'exception-json' );
				$logger->error( $json, [ 'private' => true ] );
			}

			self::callLogExceptionHook( $e, false );
		}
	}

	/**
	 * Log an exception that wasn't thrown but made to wrap an error.
	 *
	 * @param ErrorException $e
	 * @param string $level
	 * @param string $catcher CAUGHT_BY_* class constant indicating what caught the error
	 */
	private static function logError(
		ErrorException $e,
		$level,
		$catcher
	) {
		// The set_error_handler callback is independent from error_reporting.
		$suppressed = ( error_reporting() & $e->getSeverity() ) === 0;
		if ( $suppressed ) {
			// Instead of discarding these entirely, give some visibility (but only
			// when debugging) to errors that were intentionally silenced via
			// the error silencing operator (@) or Wikimedia\AtEase.
			// To avoid clobbering Logstash results, set the level to DEBUG
			// and also send them to a dedicated channel (T193472).
			$channel = 'silenced-error';
			$level = LogLevel::DEBUG;
		} else {
			$channel = 'error';
		}
		$logger = LoggerFactory::getInstance( $channel );
		$logger->log(
			$level,
			self::getLogNormalMessage( $e ),
			self::getLogContext( $e, $catcher )
		);

		self::callLogExceptionHook( $e, $suppressed );
	}

	/**
	 * Call the LogException hook, suppressing some exceptions.
	 *
	 * @param Throwable $e
	 * @param bool $suppressed
	 */
	private static function callLogExceptionHook( Throwable $e, bool $suppressed ) {
		try {
			// It's possible for the exception handler to be triggered during service container
			// initialization, e.g. if an autoloaded file triggers deprecation warnings.
			// To avoid a difficult-to-debug autoload loop, avoid attempting to initialize the service
			// container here. (T380456).
			if ( !MediaWikiServices::hasInstance() ) {
				return;
			}

			( new HookRunner( MediaWikiServices::getInstance()->getHookContainer() ) )
				->onLogException( $e, $suppressed );
		} catch ( RecursiveServiceDependencyException $e ) {
			// An error from the HookContainer wiring will lead here (T379125)
		}
	}
}
