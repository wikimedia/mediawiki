<?php
/**
 * Exception class and handler.
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
 */

/**
 * @defgroup Exception Exception
 */

/**
 * MediaWiki exception
 *
 * @ingroup Exception
 */
class MWException extends Exception {

	/**
	 * Should the exception use $wgOut to output the error?
	 *
	 * @return bool
	 */
	function useOutputPage() {
		return $this->useMessageCache() &&
			!empty( $GLOBALS['wgFullyInitialised'] ) &&
			!empty( $GLOBALS['wgOut'] ) &&
			!empty( $GLOBALS['wgTitle'] );
	}

	/**
	 * Can the extension use the Message class/wfMessage to get i18n-ed messages?
	 *
	 * @return bool
	 */
	function useMessageCache() {
		global $wgLang;

		foreach ( $this->getTrace() as $frame ) {
			if ( isset( $frame['class'] ) && $frame['class'] === 'LocalisationCache' ) {
				return false;
			}
		}

		return $wgLang instanceof Language;
	}

	/**
	 * Run hook to allow extensions to modify the text of the exception
	 *
	 * @param string $name class name of the exception
	 * @param array $args arguments to pass to the callback functions
	 * @return string|null string to output or null if any hook has been called
	 */
	function runHooks( $name, $args = array() ) {
		global $wgExceptionHooks;

		if ( !isset( $wgExceptionHooks ) || !is_array( $wgExceptionHooks ) ) {
			return null; // Just silently ignore
		}

		if ( !array_key_exists( $name, $wgExceptionHooks ) || !is_array( $wgExceptionHooks[$name] ) ) {
			return null;
		}

		$hooks = $wgExceptionHooks[$name];
		$callargs = array_merge( array( $this ), $args );

		foreach ( $hooks as $hook ) {
			if ( is_string( $hook ) || ( is_array( $hook ) && count( $hook ) >= 2 && is_string( $hook[0] ) ) ) { // 'function' or array( 'class', hook' )
				$result = call_user_func_array( $hook, $callargs );
			} else {
				$result = null;
			}

			if ( is_string( $result ) ) {
				return $result;
			}
		}
		return null;
	}

	/**
	 * Get a message from i18n
	 *
	 * @param string $key message name
	 * @param string $fallback default message if the message cache can't be
	 *                  called by the exception
	 * The function also has other parameters that are arguments for the message
	 * @return string message with arguments replaced
	 */
	function msg( $key, $fallback /*[, params...] */ ) {
		$args = array_slice( func_get_args(), 2 );

		if ( $this->useMessageCache() ) {
			return wfMessage( $key, $args )->plain();
		} else {
			return wfMsgReplaceArgs( $fallback, $args );
		}
	}

	/**
	 * If $wgShowExceptionDetails is true, return a HTML message with a
	 * backtrace to the error, otherwise show a message to ask to set it to true
	 * to show that information.
	 *
	 * @return string html to output
	 */
	function getHTML() {
		global $wgShowExceptionDetails;

		if ( $wgShowExceptionDetails ) {
			return '<p>' . nl2br( htmlspecialchars( $this->getMessage() ) ) .
				'</p><p>Backtrace:</p><p>' . nl2br( htmlspecialchars( MWExceptionHandler::formatRedactedTrace( $this ) ) ) .
				"</p>\n";
		} else {
			return "<div class=\"errorbox\">" .
				'[' . MWExceptionHandler::getLogId( $this ) . '] ' .
				gmdate( 'Y-m-d H:i:s' ) .
				": Fatal exception of type " . get_class( $this ) . "</div>\n" .
				"<!-- Set \$wgShowExceptionDetails = true; " .
				"at the bottom of LocalSettings.php to show detailed " .
				"debugging information. -->";
		}
	}

	/**
	 * Get the text to display when reporting the error on the command line.
	 * If $wgShowExceptionDetails is true, return a text message with a
	 * backtrace to the error.
	 *
	 * @return string
	 */
	function getText() {
		global $wgShowExceptionDetails;

		if ( $wgShowExceptionDetails ) {
			return $this->getMessage() .
				"\nBacktrace:\n" . MWExceptionHandler::formatRedactedTrace( $this ) . "\n";
		} else {
			return "Set \$wgShowExceptionDetails = true; " .
				"in LocalSettings.php to show detailed debugging information.\n";
		}
	}

	/**
	 * Return the title of the page when reporting this error in a HTTP response.
	 *
	 * @return string
	 */
	function getPageTitle() {
		global $wgSitename;
		return $this->msg( 'pagetitle', "$1 - $wgSitename", $this->msg( 'internalerror', 'Internal error' ) );
	}

	/**
	 * Get a the ID for this error.
	 *
	 * @since 1.20
	 * @deprecated since 1.22 Use MWExceptionHandler::getLogId instead.
	 * @return string
	 */
	function getLogId() {
		wfDeprecated( __METHOD__, '1.22' );
		return MWExceptionHandler::getLogId( $this );
	}

	/**
	 * Return the requested URL and point to file and line number from which the
	 * exception occurred
	 *
	 * @since 1.8
	 * @deprecated since 1.22 Use MWExceptionHandler::getLogMessage instead.
	 * @return string
	 */
	function getLogMessage() {
		wfDeprecated( __METHOD__, '1.22' );
		return MWExceptionHandler::getLogMessage( $this );
	}

	/**
	 * Output the exception report using HTML.
	 */
	function reportHTML() {
		global $wgOut;
		if ( $this->useOutputPage() ) {
			$wgOut->prepareErrorPage( $this->getPageTitle() );

			$hookResult = $this->runHooks( get_class( $this ) );
			if ( $hookResult ) {
				$wgOut->addHTML( $hookResult );
			} else {
				$wgOut->addHTML( $this->getHTML() );
			}

			$wgOut->output();
		} else {
			header( 'Content-Type: text/html; charset=utf-8' );
			echo "<!DOCTYPE html>\n" .
				'<html><head>' .
				'<title>' . htmlspecialchars( $this->getPageTitle() ) . '</title>' .
				'<style>body { font-family: sans-serif; margin: 0; padding: 0.5em 2em; }</style>' .
				"</head><body>\n";

			$hookResult = $this->runHooks( get_class( $this ) . 'Raw' );
			if ( $hookResult ) {
				echo $hookResult;
			} else {
				echo $this->getHTML();
			}

			echo "</body></html>\n";
		}
	}

	/**
	 * Output a report about the exception and takes care of formatting.
	 * It will be either HTML or plain text based on isCommandLine().
	 */
	function report() {
		global $wgMimeType;

		MWExceptionHandler::logException( $this );

		if ( defined( 'MW_API' ) ) {
			// Unhandled API exception, we can't be sure that format printer is alive
			header( 'MediaWiki-API-Error: internal_api_error_' . get_class( $this ) );
			wfHttpError( 500, 'Internal Server Error', $this->getText() );
		} elseif ( self::isCommandLine() ) {
			MWExceptionHandler::printError( $this->getText() );
		} else {
			header( 'HTTP/1.1 500 MediaWiki exception' );
			header( 'Status: 500 MediaWiki exception', true );
			header( "Content-Type: $wgMimeType; charset=utf-8", true );

			$this->reportHTML();
		}
	}

	/**
	 * Check whether we are in command line mode or not to report the exception
	 * in the correct format.
	 *
	 * @return bool
	 */
	static function isCommandLine() {
		return !empty( $GLOBALS['wgCommandLineMode'] );
	}
}

/**
 * Exception class which takes an HTML error message, and does not
 * produce a backtrace. Replacement for OutputPage::fatalError().
 *
 * @since 1.7
 * @ingroup Exception
 */
class FatalError extends MWException {

	/**
	 * @return string
	 */
	function getHTML() {
		return $this->getMessage();
	}

	/**
	 * @return string
	 */
	function getText() {
		return $this->getMessage();
	}
}

/**
 * An error page which can definitely be safely rendered using the OutputPage.
 *
 * @since 1.7
 * @ingroup Exception
 */
class ErrorPageError extends MWException {
	public $title, $msg, $params;

	/**
	 * Note: these arguments are keys into wfMessage(), not text!
	 *
	 * @param string|Message $title Message key (string) for page title, or a Message object
	 * @param string|Message $msg Message key (string) for error text, or a Message object
	 * @param array $params with parameters to wfMessage()
	 */
	function __construct( $title, $msg, $params = null ) {
		$this->title = $title;
		$this->msg = $msg;
		$this->params = $params;

		// Bug 44111: Messages in the log files should be in English and not
		// customized by the local wiki. So get the default English version for
		// passing to the parent constructor. Our overridden report() below
		// makes sure that the page shown to the user is not forced to English.
		if ( $msg instanceof Message ) {
			$enMsg = clone( $msg );
		} else {
			$enMsg = wfMessage( $msg, $params );
		}
		$enMsg->inLanguage( 'en' )->useDatabase( false );
		parent::__construct( $enMsg->text() );
	}

	function report() {
		global $wgOut;

		$wgOut->showErrorPage( $this->title, $this->msg, $this->params );
		$wgOut->output();
	}
}

/**
 * Show an error page on a badtitle.
 * Similar to ErrorPage, but emit a 400 HTTP error code to let mobile
 * browser it is not really a valid content.
 *
 * @since 1.19
 * @ingroup Exception
 */
class BadTitleError extends ErrorPageError {
	/**
	 * @param string|Message $msg A message key (default: 'badtitletext')
	 * @param array $params parameter to wfMessage()
	 */
	function __construct( $msg = 'badtitletext', $params = null ) {
		parent::__construct( 'badtitle', $msg, $params );
	}

	/**
	 * Just like ErrorPageError::report() but additionally set
	 * a 400 HTTP status code (bug 33646).
	 */
	function report() {
		global $wgOut;

		// bug 33646: a badtitle error page need to return an error code
		// to let mobile browser now that it is not a normal page.
		$wgOut->setStatusCode( 400 );
		parent::report();
	}

}

/**
 * Show an error when a user tries to do something they do not have the necessary
 * permissions for.
 *
 * @since 1.18
 * @ingroup Exception
 */
class PermissionsError extends ErrorPageError {
	public $permission, $errors;

	function __construct( $permission, $errors = array() ) {
		global $wgLang;

		$this->permission = $permission;

		if ( !count( $errors ) ) {
			$groups = array_map(
				array( 'User', 'makeGroupLinkWiki' ),
				User::getGroupsWithPermission( $this->permission )
			);

			if ( $groups ) {
				$errors[] = array( 'badaccess-groups', $wgLang->commaList( $groups ), count( $groups ) );
			} else {
				$errors[] = array( 'badaccess-group0' );
			}
		}

		$this->errors = $errors;
	}

	function report() {
		global $wgOut;

		$wgOut->showPermissionsErrorPage( $this->errors, $this->permission );
		$wgOut->output();
	}
}

/**
 * Show an error when the wiki is locked/read-only and the user tries to do
 * something that requires write access.
 *
 * @since 1.18
 * @ingroup Exception
 */
class ReadOnlyError extends ErrorPageError {
	public function __construct() {
		parent::__construct(
			'readonly',
			'readonlytext',
			wfReadOnlyReason()
		);
	}
}

/**
 * Show an error when the user hits a rate limit.
 *
 * @since 1.18
 * @ingroup Exception
 */
class ThrottledError extends ErrorPageError {
	public function __construct() {
		parent::__construct(
			'actionthrottled',
			'actionthrottledtext'
		);
	}

	public function report() {
		global $wgOut;
		$wgOut->setStatusCode( 503 );
		parent::report();
	}
}

/**
 * Show an error when the user tries to do something whilst blocked.
 *
 * @since 1.18
 * @ingroup Exception
 */
class UserBlockedError extends ErrorPageError {
	public function __construct( Block $block ) {
		// @todo FIXME: Implement a more proper way to get context here.
		$params = $block->getPermissionsError( RequestContext::getMain() );
		parent::__construct( 'blockedtitle', array_shift( $params ), $params );
	}
}

/**
 * Shows a generic "user is not logged in" error page.
 *
 * This is essentially an ErrorPageError exception which by default uses the
 * 'exception-nologin' as a title and 'exception-nologin-text' for the message.
 * @see bug 37627
 * @since 1.20
 *
 * @par Example:
 * @code
 * if( $user->isAnon() ) {
 * 	throw new UserNotLoggedIn();
 * }
 * @endcode
 *
 * Note the parameter order differs from ErrorPageError, this allows you to
 * simply specify a reason without overriding the default title.
 *
 * @par Example:
 * @code
 * if( $user->isAnon() ) {
 * 	throw new UserNotLoggedIn( 'action-require-loggedin' );
 * }
 * @endcode
 *
 * @ingroup Exception
 */
class UserNotLoggedIn extends ErrorPageError {

	/**
	 * @param $reasonMsg A message key containing the reason for the error.
	 *        Optional, default: 'exception-nologin-text'
	 * @param $titleMsg A message key to set the page title.
	 *        Optional, default: 'exception-nologin'
	 * @param $params Parameters to wfMessage().
	 *        Optional, default: null
	 */
	public function __construct(
		$reasonMsg = 'exception-nologin-text',
		$titleMsg = 'exception-nologin',
		$params = null
	) {
		parent::__construct( $titleMsg, $reasonMsg, $params );
	}
}

/**
 * Show an error that looks like an HTTP server error.
 * Replacement for wfHttpError().
 *
 * @since 1.19
 * @ingroup Exception
 */
class HttpError extends MWException {
	private $httpCode, $header, $content;

	/**
	 * Constructor
	 *
	 * @param $httpCode Integer: HTTP status code to send to the client
	 * @param string|Message $content content of the message
	 * @param string|Message $header content of the header (\<title\> and \<h1\>)
	 */
	public function __construct( $httpCode, $content, $header = null ) {
		parent::__construct( $content );
		$this->httpCode = (int)$httpCode;
		$this->header = $header;
		$this->content = $content;
	}

	/**
	 * Returns the HTTP status code supplied to the constructor.
	 *
	 * @return int
	 */
	public function getStatusCode() {
		return $this->httpCode;
	}

	/**
	 * Report the HTTP error.
	 * Sends the appropriate HTTP status code and outputs an
	 * HTML page with an error message.
	 */
	public function report() {
		$httpMessage = HttpStatus::getMessage( $this->httpCode );

		header( "Status: {$this->httpCode} {$httpMessage}", true, $this->httpCode );
		header( 'Content-type: text/html; charset=utf-8' );

		print $this->getHTML();
	}

	/**
	 * Returns HTML for reporting the HTTP error.
	 * This will be a minimal but complete HTML document.
	 *
	 * @return string HTML
	 */
	public function getHTML() {
		if ( $this->header === null ) {
			$header = HttpStatus::getMessage( $this->httpCode );
		} elseif ( $this->header instanceof Message ) {
			$header = $this->header->escaped();
		} else {
			$header = htmlspecialchars( $this->header );
		}

		if ( $this->content instanceof Message ) {
			$content = $this->content->escaped();
		} else {
			$content = htmlspecialchars( $this->content );
		}

		return "<!DOCTYPE html>\n" .
			"<html><head><title>$header</title></head>\n" .
			"<body><h1>$header</h1><p>$content</p></body></html>\n";
	}
}

/**
 * Handler class for MWExceptions
 * @ingroup Exception
 */
class MWExceptionHandler {
	/**
	 * Install an exception handler for MediaWiki exception types.
	 */
	public static function installHandler() {
		set_exception_handler( array( 'MWExceptionHandler', 'handle' ) );
	}

	/**
	 * Report an exception to the user
	 */
	protected static function report( Exception $e ) {
		global $wgShowExceptionDetails;

		$cmdLine = MWException::isCommandLine();

		if ( $e instanceof MWException ) {
			try {
				// Try and show the exception prettily, with the normal skin infrastructure
				$e->report();
			} catch ( Exception $e2 ) {
				// Exception occurred from within exception handler
				// Show a simpler error message for the original exception,
				// don't try to invoke report()
				$message = "MediaWiki internal error.\n\n";

				if ( $wgShowExceptionDetails ) {
					$message .= 'Original exception: ' . self::formatRedactedTrace( $e ) . "\n\n" .
						'Exception caught inside exception handler: ' . $e2->__toString();
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
			$message = "Unexpected non-MediaWiki exception encountered, of type \"" . get_class( $e ) . "\"";

			if ( $wgShowExceptionDetails ) {
				$message .= "\nexception '" . get_class( $e ) . "' in " . $e->getFile() . ":" . $e->getLine() . "\nStack trace:\n" . self::formatRedactedTrace( $e ) . "\n";
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
		# NOTE: STDERR may not be available, especially if php-cgi is used from the command line (bug #15602).
		#      Try to produce meaningful output anyway. Using echo may corrupt output to STDOUT though.
		if ( defined( 'STDERR' ) ) {
			fwrite( STDERR, $message );
		} else {
			echo $message;
		}
	}

	/**
	 * Exception handler which simulates the appropriate catch() handling:
	 *
	 *   try {
	 *       ...
	 *   } catch ( MWException $e ) {
	 *       $e->report();
	 *   } catch ( Exception $e ) {
	 *       echo $e->__toString();
	 *   }
	 */
	public static function handle( $e ) {
		global $wgFullyInitialised;

		self::report( $e );

		// Final cleanup
		if ( $wgFullyInitialised ) {
			try {
				// uses $wgRequest, hence the $wgFullyInitialised condition
				wfLogProfilingData();
			} catch ( Exception $e ) {
			}
		}

		// Exit value should be nonzero for the benefit of shell jobs
		exit( 1 );
	}

	/**
	 * Get the stack trace from the exception as a string, redacting certain function arguments in the process
	 * @param Exception $e The exception
	 * @return string The stack trace as a string
	 */
	public static function formatRedactedTrace( Exception $e ) {
		global $wgRedactedFunctionArguments;
		$finalExceptionText = '';

		// Unique value to indicate redacted parameters
		$redacted = new stdClass();

		foreach ( $e->getTrace() as $i => $call ) {
			$checkFor = array();
			if ( isset( $call['class'] ) ) {
				$checkFor[] = $call['class'] . '::' . $call['function'];
				foreach ( class_parents( $call['class'] ) as $parent ) {
					$checkFor[] = $parent . '::' . $call['function'];
				}
			} else {
				$checkFor[] = $call['function'];
			}

			foreach ( $checkFor as $check ) {
				if ( isset( $wgRedactedFunctionArguments[$check] ) ) {
					foreach ( (array)$wgRedactedFunctionArguments[$check] as $argNo ) {
						$call['args'][$argNo] = $redacted;
					}
				}
			}

			if ( isset( $call['file'] ) && isset( $call['line'] ) ) {
				$finalExceptionText .= "#{$i} {$call['file']}({$call['line']}): ";
			} else {
				// 'file' and 'line' are unset for calls via call_user_func (bug 55634)
				// This matches behaviour of Exception::getTraceAsString to instead
				// display "[internal function]".
				$finalExceptionText .= "#{$i} [internal function]: ";
			}

			if ( isset( $call['class'] ) ) {
				$finalExceptionText .= $call['class'] . $call['type'] . $call['function'];
			} else {
				$finalExceptionText .= $call['function'];
			}
			$args = array();
			if ( isset( $call['args'] ) ) {
				foreach ( $call['args'] as $arg ) {
					if ( $arg === $redacted ) {
						$args[] = 'REDACTED';
					} elseif ( is_object( $arg ) ) {
						$args[] = 'Object(' . get_class( $arg ) . ')';
					} elseif( is_array( $arg ) ) {
						$args[] = 'Array';
					} else {
						$args[] = var_export( $arg, true );
					}
				}
			}
			$finalExceptionText .=  '(' . implode( ', ', $args ) . ")\n";
		}
		return $finalExceptionText . '#' . ( $i + 1 ) . ' {main}';
	}


	/**
	 * Get the ID for this error.
	 *
	 * The ID is saved so that one can match the one output to the user (when
	 * $wgShowExceptionDetails is set to false), to the entry in the debug log.
	 *
	 * @since 1.22
	 * @param Exception $e
	 * @return string
	 */
	public static function getLogId( Exception $e ) {
		if ( !isset( $e->_mwLogId ) ) {
			$e->_mwLogId = wfRandomString( 8 );
		}
		return $e->_mwLogId;
	}

	/**
	 * Return the requested URL and point to file and line number from which the
	 * exception occurred.
	 *
	 * @since 1.22
	 * @param Exception $e
	 * @return string
	 */
	public static function getLogMessage( Exception $e ) {
		global $wgRequest;

		$id = self::getLogId( $e );
		$file = $e->getFile();
		$line = $e->getLine();
		$message = $e->getMessage();

		if ( isset( $wgRequest ) && !$wgRequest instanceof FauxRequest ) {
			$url = $wgRequest->getRequestURL();
			if ( !$url ) {
				$url = '[no URL]';
			}
		} else {
			$url = '[no req]';
		}

		return "[$id] $url   Exception from line $line of $file: $message";
	}

	/**
	 * Log an exception to the exception log (if enabled).
	 *
	 * This method must not assume the exception is an MWException,
	 * it is also used to handle PHP errors or errors from other libraries.
	 *
	 * @since 1.22
	 * @param Exception $e
	 */
	public static function logException( Exception $e ) {
		global $wgLogExceptionBacktrace;

		$log = self::getLogMessage( $e );
		if ( $log ) {
			if ( $wgLogExceptionBacktrace ) {
				wfDebugLog( 'exception', $log . "\n" . self::formatRedactedTrace( $e ) . "\n" );
			} else {
				wfDebugLog( 'exception', $log );
			}
		}
	}

}
