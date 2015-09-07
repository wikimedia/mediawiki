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

use MediaWiki\Logger\LoggerFactory;

/**
 * Handler class for MWExceptions
 * @ingroup Exception
 */
class MWExceptionHandler {

	/**
	 * @var string $reservedMemory
	 */
	protected static $reservedMemory;
	/**
	 * @var array $fatalErrorTypes
	 */
	protected static $fatalErrorTypes = array(
		E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR,
		/* HHVM's FATAL_ERROR level */ 16777217,
	);
	/**
	 * @var bool $handledFatalCallback
	 */
	protected static $handledFatalCallback = false;

	/**
	 * Install handlers with PHP.
	 */
	public static function installHandler() {
		set_exception_handler( 'MWExceptionHandler::handleException' );
		set_error_handler( 'MWExceptionHandler::handleError' );

		// Reserve 16k of memory so we can report OOM fatals
		self::$reservedMemory = str_repeat( ' ', 16384 );
		register_shutdown_function( 'MWExceptionHandler::handleFatalError' );
	}

	/**
	 * Report an exception to the user
	 * @param Exception|Throwable $e
	 */
	protected static function report( $e ) {
		global $wgShowExceptionDetails;

		$cmdLine = MWException::isCommandLine();

		if ( $e instanceof MWException ) {
			try {
				// Try and show the exception prettily, with the normal skin infrastructure
				$e->report();
			} catch ( Exception $e2 ) {
				// Exception occurred from within exception handler
				// Show a simpler message for the original exception,
				// don't try to invoke report()
				$message = "MediaWiki internal error.\n\n";

				if ( $wgShowExceptionDetails ) {
					$message .= 'Original exception: ' . self::getLogMessage( $e ) .
						"\nBacktrace:\n" . self::getRedactedTraceAsString( $e ) .
						"\n\nException caught inside exception handler: " . self::getLogMessage( $e2 ) .
						"\nBacktrace:\n" . self::getRedactedTraceAsString( $e2 );
				} else {
					$message .= "Exception caught inside exception handler.\n\n" .
						"Set \$wgShowExceptionDetails = true; at the bottom of LocalSettings.php " .
						"to show detailed debugging information.";
				}

				$message .= "\n";

				if ( $cmdLine ) {
					self::printError( $message );
				} else {
					echo nl2br( htmlspecialchars( $message ) ) . "\n";
				}
			}
		} else {
			$message = "Exception encountered, of type \"" . get_class( $e ) . "\"";

			if ( $wgShowExceptionDetails ) {
				$message .= "\n" . self::getLogMessage( $e ) . "\nBacktrace:\n" .
					self::getRedactedTraceAsString( $e ) . "\n";
			}

			if ( $cmdLine ) {
				self::printError( $message );
			} else {
				echo nl2br( htmlspecialchars( $message ) ) . "\n";
			}

		}
	}

	/**
	 * Print a message, if possible to STDERR.
	 * Use this in command line mode only (see isCommandLine)
	 *
	 * @param string $message Failure text
	 */
	public static function printError( $message ) {
		# NOTE: STDERR may not be available, especially if php-cgi is used from the
		# command line (bug #15602). Try to produce meaningful output anyway. Using
		# echo may corrupt output to STDOUT though.
		if ( defined( 'STDERR' ) ) {
			fwrite( STDERR, $message );
		} else {
			echo $message;
		}
	}

	/**
	 * If there are any open database transactions, roll them back and log
	 * the stack trace of the exception that should have been caught so the
	 * transaction could be aborted properly.
	 *
	 * @since 1.23
	 * @param Exception|Throwable $e
	 */
	public static function rollbackMasterChangesAndLog( $e ) {
		$factory = wfGetLBFactory();
		if ( $factory->hasMasterChanges() ) {
			$logger = LoggerFactory::getInstance( 'Bug56269' );
			$logger->warning(
				'Exception thrown with an uncommited database transaction: ' .
				self::getLogMessage( $e ),
				self::getLogContext( $e )
			);
			$factory->rollbackMasterChanges();
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
	 * @param Exception|Throwable $e
	 */
	public static function handleException( $e ) {
		try {
			// Rollback DBs to avoid transaction notices. This may fail
			// to rollback some DB due to connection issues or exceptions.
			// However, any sane DB driver will rollback implicitly anyway.
			self::rollbackMasterChangesAndLog( $e );
		} catch ( DBError $e2 ) {
			// If the DB is unreacheable, rollback() will throw an error
			// and the error report() method might need messages from the DB,
			// which would result in an exception loop. PHP may escalate such
			// errors to "Exception thrown without a stack frame" fatals, but
			// it's better to be explicit here.
			self::logException( $e2 );
		}

		self::logException( $e );
		self::report( $e );

		// Exit value should be nonzero for the benefit of shell jobs
		exit( 1 );
	}

	/**
	 * Handler for set_error_handler() callback notifications.
	 *
	 * Receive a callback from the interpreter for a raised error, create an
	 * ErrorException, and log the exception to the 'error' logging
	 * channel(s). If the raised error is a fatal error type (only under HHVM)
	 * delegate to handleFatalError() instead.
	 *
	 * @since 1.25
	 *
	 * @param int $level Error level raised
	 * @param string $message
	 * @param string $file
	 * @param int $line
	 *
	 * @see logError()
	 */
	public static function handleError(
		$level, $message, $file = null, $line = null
	) {
		if ( in_array( $level, self::$fatalErrorTypes ) ) {
			return call_user_func_array(
				'MWExceptionHandler::handleFatalError', func_get_args()
			);
		}

		// Map error constant to error name (reverse-engineer PHP error
		// reporting)
		switch ( $level ) {
			case E_RECOVERABLE_ERROR:
				$levelName = 'Error';
				break;
			case E_WARNING:
			case E_CORE_WARNING:
			case E_COMPILE_WARNING:
			case E_USER_WARNING:
				$levelName = 'Warning';
				break;
			case E_NOTICE:
			case E_USER_NOTICE:
				$levelName = 'Notice';
				break;
			case E_STRICT:
				$levelName = 'Strict Standards';
				break;
			case E_DEPRECATED:
			case E_USER_DEPRECATED:
				$levelName = 'Deprecated';
				break;
			default:
				$levelName = 'Unknown error';
				break;
		}

		$e = new ErrorException( "PHP $levelName: $message", 0, $level, $file, $line );
		self::logError( $e, 'error' );

		// This handler is for logging only. Return false will instruct PHP
		// to continue regular handling.
		return false;
	}


	/**
	 * Dual purpose callback used as both a set_error_handler() callback and
	 * a registered shutdown function. Receive a callback from the interpreter
	 * for a raised error or system shutdown, check for a fatal error, and log
	 * to the 'fatal' logging channel.
	 *
	 * Special handling is included for missing class errors as they may
	 * indicate that the user needs to install 3rd-party libraries via
	 * Composer or other means.
	 *
	 * @since 1.25
	 *
	 * @param int $level Error level raised
	 * @param string $message Error message
	 * @param string $file File that error was raised in
	 * @param int $line Line number error was raised at
	 * @param array $context Active symbol table point of error
	 * @param array $trace Backtrace at point of error (undocumented HHVM
	 *     feature)
	 * @return bool Always returns false
	 */
	public static function handleFatalError(
		$level = null, $message = null, $file = null, $line = null,
		$context = null, $trace = null
	) {
		// Free reserved memory so that we have space to process OOM
		// errors
		self::$reservedMemory = null;

		if ( $level === null ) {
			// Called as a shutdown handler, get data from error_get_last()
			if ( static::$handledFatalCallback ) {
				// Already called once (probably as an error handler callback
				// under HHVM) so don't log again.
				return false;
			}

			$lastError = error_get_last();
			if ( $lastError !== null ) {
				$level = $lastError['type'];
				$message = $lastError['message'];
				$file = $lastError['file'];
				$line = $lastError['line'];
			} else {
				$level = 0;
				$message = '';
			}
		}

		if ( !in_array( $level, self::$fatalErrorTypes ) ) {
			// Only interested in fatal errors, others should have been
			// handled by MWExceptionHandler::handleError
			return false;
		}

		$msg = "[{exception_id}] PHP Fatal Error: {$message}";

		// Look at message to see if this is a class not found failure
		// HHVM: Class undefined: foo
		// PHP5: Class 'foo' not found
		if ( preg_match( "/Class (undefined: \w+|'\w+' not found)/", $msg ) ) {
			// @codingStandardsIgnoreStart Generic.Files.LineLength.TooLong
			$msg = <<<TXT
{$msg}

MediaWiki or an installed extension requires this class but it is not embedded directly in MediaWiki's git repository and must be installed separately by the end user.

Please see <a href="https://www.mediawiki.org/wiki/Download_from_Git#Fetch_external_libraries">mediawiki.org</a> for help on installing the required components.
TXT;
			// @codingStandardsIgnoreEnd
		}

		// We can't just create an exception and log it as it is likely that
		// the interpreter has unwound the stack already. If that is true the
		// stacktrace we would get would be functionally empty. If however we
		// have been called as an error handler callback *and* HHVM is in use
		// we will have been provided with a useful stacktrace that we can
		// log.
		$trace = $trace ?: debug_backtrace();
		$logger = LoggerFactory::getInstance( 'fatal' );
		$logger->error( $msg, array(
			'exception' => array(
				'class' => 'ErrorException',
				'message' => "PHP Fatal Error: {$message}",
				'code' => $level,
				'file' => $file,
				'line' => $line,
				'trace' => static::redactTrace( $trace ),
			),
			'exception_id' => wfRandomString( 8 ),
		) );

		// Remember call so we don't double process via HHVM's fatal
		// notifications and the shutdown hook behavior
		static::$handledFatalCallback = true;
		return false;
	}

	/**
	 * Generate a string representation of an exception's stack trace
	 *
	 * Like Exception::getTraceAsString, but replaces argument values with
	 * argument type or class name.
	 *
	 * @param Exception|Throwable $e
	 * @return string
	 * @see prettyPrintTrace()
	 */
	public static function getRedactedTraceAsString( $e ) {
		return self::prettyPrintTrace( self::getRedactedTrace( $e ) );
	}

	/**
	 * Generate a string representation of a stacktrace.
	 *
	 * @param array $trace
	 * @param string $pad Constant padding to add to each line of trace
	 * @return string
	 * @since 1.26
	 */
	public static function prettyPrintTrace( array $trace, $pad = '' ) {
		$text = '';

		foreach ( $trace as $level => $frame ) {
			if ( isset( $frame['file'] ) && isset( $frame['line'] ) ) {
				$text .= "{$pad}#{$level} {$frame['file']}({$frame['line']}): ";
			} else {
				// 'file' and 'line' are unset for calls via call_user_func
				// (bug 55634) This matches behaviour of
				// Exception::getTraceAsString to instead display "[internal
				// function]".
				$text .= "{$pad}#{$level} [internal function]: ";
			}

			if ( isset( $frame['class'] ) ) {
				$text .= $frame['class'] . $frame['type'] . $frame['function'];
			} else {
				$text .= $frame['function'];
			}

			if ( isset( $frame['args'] ) ) {
				$text .= '(' . implode( ', ', $frame['args'] ) . ")\n";
			} else {
				$text .= "()\n";
			}
		}

		$level = $level + 1;
		$text .= "{$pad}#{$level} {main}";

		return $text;
	}

	/**
	 * Return a copy of an exception's backtrace as an array.
	 *
	 * Like Exception::getTrace, but replaces each element in each frame's
	 * argument array with the name of its class (if the element is an object)
	 * or its type (if the element is a PHP primitive).
	 *
	 * @since 1.22
	 * @param Exception|Throwable $e
	 * @return array
	 */
	public static function getRedactedTrace( $e ) {
		return static::redactTrace( $e->getTrace() );
	}

	/**
	 * Redact a stacktrace generated by Exception::getTrace(),
	 * debug_backtrace() or similar means. Replaces each element in each
	 * frame's argument array with the name of its class (if the element is an
	 * object) or its type (if the element is a PHP primitive).
	 *
	 * @since 1.26
	 * @param array $trace Stacktrace
	 * @return array Stacktrace with arugment values converted to data types
	 */
	public static function redactTrace( array $trace ) {
		return array_map( function ( $frame ) {
			if ( isset( $frame['args'] ) ) {
				$frame['args'] = array_map( function ( $arg ) {
					return is_object( $arg ) ? get_class( $arg ) : gettype( $arg );
				}, $frame['args'] );
			}
			return $frame;
		}, $trace );
	}

	/**
	 * Get the ID for this exception.
	 *
	 * The ID is saved so that one can match the one output to the user (when
	 * $wgShowExceptionDetails is set to false), to the entry in the debug log.
	 *
	 * @since 1.22
	 * @param Exception|Throwable $e
	 * @return string
	 */
	public static function getLogId( $e ) {
		if ( !isset( $e->_mwLogId ) ) {
			$e->_mwLogId = wfRandomString( 8 );
		}
		return $e->_mwLogId;
	}

	/**
	 * If the exception occurred in the course of responding to a request,
	 * returns the requested URL. Otherwise, returns false.
	 *
	 * @since 1.23
	 * @return string|false
	 */
	public static function getURL() {
		global $wgRequest;
		if ( !isset( $wgRequest ) || $wgRequest instanceof FauxRequest ) {
			return false;
		}
		return $wgRequest->getRequestURL();
	}

	/**
	 * Get a message formatting the exception message and its origin.
	 *
	 * @since 1.22
	 * @param Exception|Throwable $e
	 * @return string
	 */
	public static function getLogMessage( $e ) {
		$id = self::getLogId( $e );
		$type = get_class( $e );
		$file = $e->getFile();
		$line = $e->getLine();
		$message = $e->getMessage();
		$url = self::getURL() ?: '[no req]';

		return "[$id] $url   $type from line $line of $file: $message";
	}

	/**
	 * Get a PSR-3 log event context from an Exception.
	 *
	 * Creates a structured array containing information about the provided
	 * exception that can be used to augment a log message sent to a PSR-3
	 * logger.
	 *
	 * @param Exception|Throwable $e
	 * @return array
	 */
	public static function getLogContext( $e ) {
		return array(
			'exception' => $e,
			'exception_id' => static::getLogId( $e ),
		);
	}

	/**
	 * Get a structured representation of an Exception.
	 *
	 * Returns an array of structured data (class, message, code, file,
	 * backtrace) derived from the given exception. The backtrace information
	 * will be redacted as per getRedactedTraceAsArray().
	 *
	 * @param Exception|Throwable $e
	 * @return array
	 * @since 1.26
	 */
	public static function getStructuredExceptionData( $e ) {
		global $wgLogExceptionBacktrace;
		$data = array(
			'id' => self::getLogId( $e ),
			'type' => get_class( $e ),
			'file' => $e->getFile(),
			'line' => $e->getLine(),
			'message' => $e->getMessage(),
			'code' => $e->getCode(),
			'url' => self::getURL() ?: null,
		);

		if ( $e instanceof ErrorException &&
			( error_reporting() & $e->getSeverity() ) === 0
		) {
			// Flag surpressed errors
			$data['suppressed'] = true;
		}

		if ( $wgLogExceptionBacktrace ) {
			$data['backtrace'] = self::getRedactedTrace( $e );
		}

		$previous = $e->getPrevious();
		if ( $previous !== null ) {
			$data['previous'] = self::getStructuredExceptionData( $previous );
		}

		return $data;
	}

	/**
	 * Serialize an Exception object to JSON.
	 *
	 * The JSON object will have keys 'id', 'file', 'line', 'message', and
	 * 'url'. These keys map to string values, with the exception of 'line',
	 * which is a number, and 'url', which may be either a string URL or or
	 * null if the exception did not occur in the context of serving a web
	 * request.
	 *
	 * If $wgLogExceptionBacktrace is true, it will also have a 'backtrace'
	 * key, mapped to the array return value of Exception::getTrace, but with
	 * each element in each frame's "args" array (if set) replaced with the
	 * argument's class name (if the argument is an object) or type name (if
	 * the argument is a PHP primitive).
	 *
	 * @par Sample JSON record ($wgLogExceptionBacktrace = false):
	 * @code
	 *  {
	 *    "id": "c41fb419",
	 *    "type": "MWException",
	 *    "file": "/var/www/mediawiki/includes/cache/MessageCache.php",
	 *    "line": 704,
	 *    "message": "Non-string key given",
	 *    "url": "/wiki/Main_Page"
	 *  }
	 * @endcode
	 *
	 * @par Sample JSON record ($wgLogExceptionBacktrace = true):
	 * @code
	 *  {
	 *    "id": "dc457938",
	 *    "type": "MWException",
	 *    "file": "/vagrant/mediawiki/includes/cache/MessageCache.php",
	 *    "line": 704,
	 *    "message": "Non-string key given",
	 *    "url": "/wiki/Main_Page",
	 *    "backtrace": [{
	 *      "file": "/vagrant/mediawiki/extensions/VisualEditor/VisualEditor.hooks.php",
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
	 * @param Exception|Throwable $e
	 * @param bool $pretty Add non-significant whitespace to improve readability (default: false).
	 * @param int $escaping Bitfield consisting of FormatJson::.*_OK class constants.
	 * @return string|false JSON string if successful; false upon failure
	 */
	public static function jsonSerializeException( $e, $pretty = false, $escaping = 0 ) {
		$data = self::getStructuredExceptionData( $e );
		return FormatJson::encode( $data, $pretty, $escaping );
	}

	/**
	 * Log an exception to the exception log (if enabled).
	 *
	 * This method must not assume the exception is an MWException,
	 * it is also used to handle PHP exceptions or exceptions from other libraries.
	 *
	 * @since 1.22
	 * @param Exception|Throwable $e
	 */
	public static function logException( $e ) {
		if ( !( $e instanceof MWException ) || $e->isLoggable() ) {
			$logger = LoggerFactory::getInstance( 'exception' );
			$logger->error(
				self::getLogMessage( $e ),
				self::getLogContext( $e )
			);

			$json = self::jsonSerializeException( $e, false, FormatJson::ALL_OK );
			if ( $json !== false ) {
				$logger = LoggerFactory::getInstance( 'exception-json' );
				$logger->error( $json, array( 'private' => true ) );
			}

			Hooks::run( 'LogException', array( $e, false ) );
		}
	}

	/**
	 * Log an exception that wasn't thrown but made to wrap an error.
	 *
	 * @since 1.25
	 * @param ErrorException $e
	 * @param string $channel
	*/
	protected static function logError( ErrorException $e, $channel ) {
		// The set_error_handler callback is independent from error_reporting.
		// Filter out unwanted errors manually (e.g. when
		// MediaWiki\suppressWarnings is active).
		$suppressed = ( error_reporting() & $e->getSeverity() ) === 0;
		if ( !$suppressed ) {
			$logger = LoggerFactory::getInstance( $channel );
			$logger->error(
				self::getLogMessage( $e ),
				self::getLogContext( $e )
			);
		}

		// Include all errors in the json log (surpressed errors will be flagged)
		$json = self::jsonSerializeException( $e, false, FormatJson::ALL_OK );
		if ( $json !== false ) {
			$logger = LoggerFactory::getInstance( "{$channel}-json" );
			$logger->error( $json, array( 'private' => true ) );
		}

		Hooks::run( 'LogException', array( $e, $suppressed ) );
	}
}
