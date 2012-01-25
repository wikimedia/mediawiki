<?php
/**
 * Exception class and handler
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
	 * Should the exception use $wgOut to output the error ?
	 * @return bool
	 */
	function useOutputPage() {
		return $this->useMessageCache() &&
			!empty( $GLOBALS['wgFullyInitialised'] ) &&
			!empty( $GLOBALS['wgOut'] ) &&
			!empty( $GLOBALS['wgTitle'] );
	}

	/**
	 * Can the extension use wfMsg() to get i18n messages ?
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
	 * @param $name String: class name of the exception
	 * @param $args Array: arguments to pass to the callback functions
	 * @return Mixed: string to output or null if any hook has been called
	 */
	function runHooks( $name, $args = array() ) {
		global $wgExceptionHooks;

		if ( !isset( $wgExceptionHooks ) || !is_array( $wgExceptionHooks ) ) {
			return; // Just silently ignore
		}

		if ( !array_key_exists( $name, $wgExceptionHooks ) || !is_array( $wgExceptionHooks[ $name ] ) ) {
			return;
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
	}

	/**
	 * Get a message from i18n
	 *
	 * @param $key String: message name
	 * @param $fallback String: default message if the message cache can't be
	 *                  called by the exception
	 * The function also has other parameters that are arguments for the message
	 * @return String message with arguments replaced
	 */
	function msg( $key, $fallback /*[, params...] */ ) {
		$args = array_slice( func_get_args(), 2 );

		if ( $this->useMessageCache() ) {
			return wfMsgNoTrans( $key, $args );
		} else {
			return wfMsgReplaceArgs( $fallback, $args );
		}
	}

	/**
	 * If $wgShowExceptionDetails is true, return a HTML message with a
	 * backtrace to the error, otherwise show a message to ask to set it to true
	 * to show that information.
	 *
	 * @return String html to output
	 */
	function getHTML() {
		global $wgShowExceptionDetails;

		if ( $wgShowExceptionDetails ) {
			return '<p>' . nl2br( htmlspecialchars( $this->getMessage() ) ) .
				'</p><p>Backtrace:</p><p>' . nl2br( htmlspecialchars( $this->getTraceAsString() ) ) .
				"</p>\n";
		} else {
			return "<p>Set <b><tt>\$wgShowExceptionDetails = true;</tt></b> " .
				"at the bottom of LocalSettings.php to show detailed " .
				"debugging information.</p>";
		}
	}

	/**
	 * If $wgShowExceptionDetails is true, return a text message with a
	 * backtrace to the error.
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
	 * Return titles of this error page
	 * @return String
	 */
	function getPageTitle() {
		return $this->msg( 'internalerror', "Internal error" );
	}

	/**
	 * Return the requested URL and point to file and line number from which the
	 * exception occured
	 *
	 * @return String
	 */
	function getLogMessage() {
		global $wgRequest;

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

		return "$url   Exception from line $line of $file: $message";
	}

	/** Output the exception report using HTML */
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
			$hookResult = $this->runHooks( get_class( $this ) . "Raw" );
			if ( $hookResult ) {
				die( $hookResult );
			}

			echo $this->getHTML();
			die(1);
		}
	}

	/**
	 * Output a report about the exception and takes care of formatting.
	 * It will be either HTML or plain text based on isCommandLine().
	 */
	function report() {
		$log = $this->getLogMessage();

		if ( $log ) {
			wfDebugLog( 'exception', $log );
		}

		if ( self::isCommandLine() ) {
			MWExceptionHandler::printError( $this->getText() );
		} else {
			$this->reportHTML();
		}
	}

	/**
	 * @static
	 * @return bool
	 */
	static function isCommandLine() {
		return !empty( $GLOBALS['wgCommandLineMode'] );
	}
}

/**
 * Exception class which takes an HTML error message, and does not
 * produce a backtrace. Replacement for OutputPage::fatalError().
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
 * An error page which can definitely be safely rendered using the OutputPage
 * @ingroup Exception
 */
class ErrorPageError extends MWException {
	public $title, $msg, $params;

	/**
	 * Note: these arguments are keys into wfMsg(), not text!
	 */
	function __construct( $title, $msg, $params = null ) {
		$this->title = $title;
		$this->msg = $msg;
		$this->params = $params;

		if( $msg instanceof Message ){
			parent::__construct( $msg );
		} else {
			parent::__construct( wfMsg( $msg ) );
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
 */
class BadTitleError extends ErrorPageError {

	/**
	 * @param $msg string A message key (default: 'badtitletext')
	 * @param $params Array parameter to wfMsg()
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
 * something that requires write access
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
 * Show an error when the user hits a rate limit
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
		return parent::report();
	}
}

/**
 * Show an error when the user tries to do something whilst blocked
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
			$reason = wfMsg( 'blockednoreason' );
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
 * Show an error that looks like an HTTP server error.
 * Replacement for wfHttpError().
 *
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

	public function reportHTML() {
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

		print "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">\n".
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
					self::escapeEchoAndDie( $message );
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
				self::escapeEchoAndDie( $message );
			}
		}
	}

	/**
	 * Print a message, if possible to STDERR.
	 * Use this in command line mode only (see isCommandLine)
	 * @param $message String Failure text
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
	 * Print a message after escaping it and converting newlines to <br>
	 * Use this for non-command line failures
	 * @param $message String Failure text
	 */
	private static function escapeEchoAndDie( $message ) {
		echo nl2br( htmlspecialchars( $message ) ) . "\n";
		die(1);
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
