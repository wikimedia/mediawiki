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
	var $logId;

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
	 * @param $name string: class name of the exception
	 * @param $args array: arguments to pass to the callback functions
	 * @return string|null string to output or null if any hook has been called
	 */
	function runHooks( $name, $args = array() ) {
		global $wgExceptionHooks;

		if ( !isset( $wgExceptionHooks ) || !is_array( $wgExceptionHooks ) ) {
			return null; // Just silently ignore
		}

		if ( !array_key_exists( $name, $wgExceptionHooks ) || !is_array( $wgExceptionHooks[ $name ] ) ) {
			return null;
		}

		$hooks = $wgExceptionHooks[ $name ];
		$callargs = array_merge( array( $this ), $args );

		foreach ( $hooks as $hook ) {
			if ( is_string( $hook ) || ( is_array( $hook ) && count( $hook ) >= 2 && is_string( $hook[0] ) ) ) {	// 'function' or array( 'class', hook' )
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
	 * @param $key string: message name
	 * @param $fallback string: default message if the message cache can't be
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
				'</p><p>Backtrace:</p><p>' . nl2br( htmlspecialchars( $this->getTraceAsString() ) ) .
				"</p>\n";
		} else {
			return
				"<div class=\"errorbox\">" .
				'[' . $this->getLogId() . '] ' .
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
				"\nBacktrace:\n" . $this->getTraceAsString() . "\n";
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
		return $this->msg( 'internalerror', "Internal error" );
	}

	/**
	 * Get a random ID for this error.
	 * This allows to link the exception to its correspoding log entry when
	 * $wgShowExceptionDetails is set to false.
	 *
	 * @return string
	 */
	function getLogId() {
		if ( $this->logId === null ) {
			$this->logId = wfRandomString( 8 );
		}
		return $this->logId;
	}

	/**
	 * Return the requested URL and point to file and line number from which the
	 * exception occurred
	 *
	 * @return string
	 */
	function getLogMessage() {
		global $wgRequest;

		$id = $this->getLogId();
		$file = $this->getFile();
		$line = $this->getLine();
		$message = $this->getMessage();

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
			header( "Content-Type: text/html; charset=utf-8" );
			echo "<!doctype html>\n" .
				'<html><head>' .
				'<title>' . htmlspecialchars( $this->getPageTitle() ) . '</title>' .
				"</head><body>\n";

			$hookResult = $this->runHooks( get_class( $this ) . "Raw" );
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
		global $wgLogExceptionBacktrace;
		$log = $this->getLogMessage();

		if ( $log ) {
			if ( $wgLogExceptionBacktrace ) {
				wfDebugLog( 'exception', $log . "\n" . $this->getTraceAsString() . "\n" );
			} else {
				wfDebugLog( 'exception', $log );
			}
		}

		if ( defined( 'MW_API' ) ) {
			// Unhandled API exception, we can't be sure that format printer is alive
			header( 'MediaWiki-API-Error: internal_api_error_' . get_class( $this ) );
			wfHttpError(500, 'Internal Server Error', $this->getText() );
		} elseif ( self::isCommandLine() ) {
			MWExceptionHandler::printError( $this->getText() );
		} else {
			header( "HTTP/1.1 500 MediaWiki exception" );
			header( "Status: 500 MediaWiki exception", true );

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
	 * @param $title string|Message Message key (string) for page title, or a Message object
	 * @param $msg string|Message Message key (string) for error text, or a Message object
	 * @param $params array with parameters to wfMessage()
	 */
	function __construct( $title, $msg, $params = null ) {
		$this->title = $title;
		$this->msg = $msg;
		$this->params = $params;

		if( $msg instanceof Message ){
			parent::__construct( $msg );
		} else {
			parent::__construct( wfMessage( $msg )->text() );
		}
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
	 * @param $msg string|Message A message key (default: 'badtitletext')
	 * @param $params Array parameter to wfMessage()
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
	public function __construct(){
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
	public function __construct(){
		parent::__construct(
			'actionthrottled',
			'actionthrottledtext'
		);
	}

	public function report(){
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
	public function __construct( Block $block ){
		global $wgLang, $wgRequest;

		$blocker = $block->getBlocker();
		if ( $blocker instanceof User ) { // local user
			$blockerUserpage = $block->getBlocker()->getUserPage();
			$link = "[[{$blockerUserpage->getPrefixedText()}|{$blockerUserpage->getText()}]]";
		} else { // foreign user
			$link = $blocker;
		}

		$reason = $block->mReason;
		if( $reason == '' ) {
			$reason = wfMessage( 'blockednoreason' )->text();
		}

		/* $ip returns who *is* being blocked, $intended contains who was meant to be blocked.
		 * This could be a username, an IP range, or a single IP. */
		$intended = $block->getTarget();

		parent::__construct(
			'blockedtitle',
			$block->mAuto ? 'autoblockedtext' : 'blockedtext',
			array(
				$link,
				$reason,
				$wgRequest->getIP(),
				$block->getByName(),
				$block->getId(),
				$wgLang->formatExpiry( $block->mExpiry ),
				$intended,
				$wgLang->timeanddate( wfTimestamp( TS_MW, $block->mTimestamp ), true )
			)
		);
	}
}

/**
 * Shows a generic "user is not logged in" error page.
 *
 * This is essentially an ErrorPageError exception which by default use the
 * 'exception-nologin' as a title and 'exception-nologin-text' for the message.
 * @see bug 37627
 * @since 1.20
 *
 * @par Example:
 * @code
 * if( $user->isAnon ) {
 * 	throw new UserNotLoggedIn();
 * }
 * @endcode
 *
 * Please note the parameters are mixed up compared to ErrorPageError, this
 * is done to be able to simply specify a reason whitout overriding the default
 * title.
 *
 * @par Example:
 * @code
 * if( $user->isAnon ) {
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
	 *        Optiona, default: null
	 */
	public function __construct(
		$reasonMsg = 'exception-nologin-text',
		$titleMsg  = 'exception-nologin',
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
	 * @param $content String|Message: content of the message
	 * @param $header String|Message: content of the header (\<title\> and \<h1\>)
	 */
	public function __construct( $httpCode, $content, $header = null ){
		parent::__construct( $content );
		$this->httpCode = (int)$httpCode;
		$this->header = $header;
		$this->content = $content;
	}

	public function report() {
		$httpMessage = HttpStatus::getMessage( $this->httpCode );

		header( "Status: {$this->httpCode} {$httpMessage}" );
		header( 'Content-type: text/html; charset=utf-8' );

		if ( $this->header === null ) {
			$header = $httpMessage;
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

		print "<!DOCTYPE html>\n".
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
					$message .= 'Original exception: ' . $e->__toString() . "\n\n" .
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
			$message = "Unexpected non-MediaWiki exception encountered, of type \"" . get_class( $e ) . "\"\n" .
				$e->__toString() . "\n";

			if ( $wgShowExceptionDetails ) {
				$message .= "\n" . $e->getTraceAsString() . "\n";
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
	 * @param $message string Failure text
	 */
	public static function printError( $message ) {
		# NOTE: STDERR may not be available, especially if php-cgi is used from the command line (bug #15602).
		#      Try to produce meaningful output anyway. Using echo may corrupt output to STDOUT though.
		if ( defined( 'STDERR' ) ) {
			fwrite( STDERR, $message );
		} else {
			echo( $message );
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
				wfLogProfilingData(); // uses $wgRequest, hence the $wgFullyInitialised condition
			} catch ( Exception $e ) {}
		}

		// Exit value should be nonzero for the benefit of shell jobs
		exit( 1 );
	}
}
