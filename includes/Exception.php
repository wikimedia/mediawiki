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
			( !empty( $GLOBALS['wgArticle'] ) || ( !empty( $GLOBALS['wgOut'] ) && !$GLOBALS['wgOut']->isArticle() ) ) &&
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

		return is_object( $wgLang );
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
			return;	// Just silently ignore
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

			if ( is_string( $result ) )
				return $result;
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
			return wfMsgReal( $key, $args );
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

	/* Return titles of this error page */
	function getPageTitle() {
		if ( $this->useMessageCache() ) {
			return wfMsg( 'internalerror' );
		} else {
			global $wgSitename;

			return "$wgSitename error";
		}
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

		if ( isset( $wgRequest ) ) {
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
			$wgOut->setPageTitle( $this->getPageTitle() );
			$wgOut->setRobotPolicy( "noindex,nofollow" );
			$wgOut->setArticleRelated( false );
			$wgOut->enableClientCache( false );
			$wgOut->redirect( '' );
			$wgOut->clearHTML();

			if ( $hookResult = $this->runHooks( get_class( $this ) ) ) {
				$wgOut->addHTML( $hookResult );
			} else {
				$wgOut->addHTML( $this->getHTML() );
			}

			$wgOut->output();
		} else {
			if ( $hookResult = $this->runHooks( get_class( $this ) . "Raw" ) ) {
				die( $hookResult );
			}

			if ( defined( 'MEDIAWIKI_INSTALL' ) || $this->htmlBodyOnly() ) {
				echo $this->getHTML();
			} else {
				echo $this->htmlHeader();
				echo $this->getHTML();
				echo $this->htmlFooter();
			}
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
			wfPrintError( $this->getText() );
		} else {
			$this->reportHTML();
		}
	}

	/**
	 * Send headers and output the beginning of the html page if not using
	 * $wgOut to output the exception.
	 */
	function htmlHeader() {
		global $wgLogo, $wgOutputEncoding;

		if ( !headers_sent() ) {
			header( 'HTTP/1.0 500 Internal Server Error' );
			header( 'Content-type: text/html; charset=' . $wgOutputEncoding );
			/* Don't cache error pages!  They cause no end of trouble... */
			header( 'Cache-control: none' );
			header( 'Pragma: nocache' );
		}

		$title = $this->getPageTitle();
		return "<html>
		<head>
		<title>$title</title>
		</head>
		<body>
		<h1><img src='$wgLogo' style='float:left;margin-right:1em' alt=''/>$title</h1>
		";
	}

	/**
	 * print the end of the html page if not using $wgOut.
	 */
	function htmlFooter() {
		return "</body></html>";
	}

	/**
	 * headers handled by subclass?
	 */
	function htmlBodyOnly() {
		return false;
	}

	static function isCommandLine() {
		return !empty( $GLOBALS['wgCommandLineMode'] ) && !defined( 'MEDIAWIKI_INSTALL' );
	}
}

/**
 * Exception class which takes an HTML error message, and does not
 * produce a backtrace. Replacement for OutputPage::fatalError().
 * @ingroup Exception
 */
class FatalError extends MWException {
	function getHTML() {
		return $this->getMessage();
	}

	function getText() {
		return $this->getMessage();
	}
}

/**
 * @ingroup Exception
 */
class ErrorPageError extends MWException {
	public $title, $msg;

	/**
	 * Note: these arguments are keys into wfMsg(), not text!
	 */
	function __construct( $title, $msg ) {
		$this->title = $title;
		$this->msg = $msg;
		parent::__construct( wfMsg( $msg ) );
	}

	function report() {
		global $wgOut;

		$wgOut->showErrorPage( $this->title, $this->msg );
		$wgOut->output();
	}
}

/**
 * Install an exception handler for MediaWiki exception types.
 */
function wfInstallExceptionHandler() {
	set_exception_handler( 'wfExceptionHandler' );
}

/**
 * Report an exception to the user
 */
function wfReportException( Exception $e ) {
	global $wgShowExceptionDetails;

	$cmdLine = MWException::isCommandLine();

	if ( $e instanceof MWException ) {
		try {
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
				wfPrintError( $message );
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
			wfPrintError( $message );
		} else {
			echo nl2br( htmlspecialchars( $message ) ) . "\n";
		}
	}
}

/**
 * Print a message, if possible to STDERR.
 * Use this in command line mode only (see isCommandLine)
 */
function wfPrintError( $message ) {
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
function wfExceptionHandler( $e ) {
	global $wgFullyInitialised;

	wfReportException( $e );

	// Final cleanup, similar to wfErrorExit()
	if ( $wgFullyInitialised ) {
		try {
			wfLogProfilingData(); // uses $wgRequest, hence the $wgFullyInitialised condition
		} catch ( Exception $e ) {}
	}

	// Exit value should be nonzero for the benefit of shell jobs
	exit( 1 );
}
