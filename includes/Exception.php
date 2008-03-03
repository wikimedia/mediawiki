<?php

class MWException extends Exception
{
	function useOutputPage() {
		return !empty( $GLOBALS['wgFullyInitialised'] ) && 
			!empty( $GLOBALS['wgArticle'] ) && !empty( $GLOBALS['wgTitle'] );
	}

	function useMessageCache() {
		global $wgLang;
		return is_object( $wgLang );
	}

	function msg( $key, $fallback /*[, params...] */ ) {
		$args = array_slice( func_get_args(), 2 );
		if ( $this->useMessageCache() ) {
			return wfMsgReal( $key, $args );
		} else {
			return wfMsgReplaceArgs( $fallback, $args );
		}
	}

	function getHTML() {
		global $wgShowExceptionDetails;
		if( $wgShowExceptionDetails ) {
			return '<p>' . htmlspecialchars( $this->getMessage() ) . 
				'</p><p>Backtrace:</p><p>' . nl2br( htmlspecialchars( $this->getTraceAsString() ) ) .
				"</p>\n";
		} else {
			return "<p>Set <b><tt>\$wgShowExceptionDetails = true;</tt></b> " .
				"in LocalSettings.php to show detailed debugging information.</p>";
		}
	}

	function getText() {
		global $wgShowExceptionDetails;
		if( $wgShowExceptionDetails ) {
			return $this->getMessage() .
				"\nBacktrace:\n" . $this->getTraceAsString() . "\n";
		} else {
			return "<p>Set <tt>\$wgShowExceptionDetails = true;</tt> " .
				"in LocalSettings.php to show detailed debugging information.</p>";
		}
	}
	
	function getPageTitle() {
		if ( $this->useMessageCache() ) {
			return wfMsg( 'internalerror' );
		} else {
			global $wgSitename;
			return "$wgSitename error";
		}
	}
	
	function getLogMessage() {
		global $wgRequest;
		$file = $this->getFile();
		$line = $this->getLine();
		$message = $this->getMessage();
		return $wgRequest->getRequestURL() . " Exception from line $line of $file: $message";
	}
	
	function reportHTML() {
		global $wgOut;
		if ( $this->useOutputPage() ) {
			$wgOut->setPageTitle( $this->getPageTitle() );
			$wgOut->setRobotpolicy( "noindex,nofollow" );
			$wgOut->setArticleRelated( false );
			$wgOut->enableClientCache( false );
			$wgOut->redirect( '' );
			$wgOut->clearHTML();
			$wgOut->addHTML( $this->getHTML() );
			$wgOut->output();
		} else {
			echo $this->htmlHeader();
			echo $this->getHTML();
			echo $this->htmlFooter();
		}
	}
	
	function reportText() {
		echo $this->getText();
	}

	function report() {
		global $wgCommandLineMode;
		if ( $wgCommandLineMode ) {
			$this->reportText();
		} else {
			$log = $this->getLogMessage();
			if ( $log ) {
				wfDebugLog( 'exception', $log );
			}
			$this->reportHTML();
		}
	}

	function htmlHeader() {
		global $wgLogo, $wgSitename, $wgOutputEncoding;

		if ( !headers_sent() ) {
			header( 'HTTP/1.0 500 Internal Server Error' );
			header( 'Content-type: text/html; charset='.$wgOutputEncoding );
			/* Don't cache error pages!  They cause no end of trouble... */
			header( 'Cache-control: none' );
			header( 'Pragma: nocache' );
		}
		$title = $this->getPageTitle();
		echo "<html>
		<head>
		<title>$title</title>
		</head>
		<body>
		<h1><img src='$wgLogo' style='float:left;margin-right:1em' alt=''>$title</h1>
		";
	}

	function htmlFooter() {
		echo "</body></html>";
	}

}

/**
 * Exception class which takes an HTML error message, and does not
 * produce a backtrace. Replacement for OutputPage::fatalError().
 */
class FatalError extends MWException {
	function getHTML() {
		return $this->getMessage();
	}

	function getText() {
		return $this->getMessage();
	}
}

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
	 if ( $e instanceof MWException ) {
		 try {
			 $e->report();
		 } catch ( Exception $e2 ) {
			 // Exception occurred from within exception handler
			 // Show a simpler error message for the original exception,
			 // don't try to invoke report()
			 $message = "MediaWiki internal error.\n\n" .
			 "Original exception: " . $e->__toString() .
			 "\n\nException caught inside exception handler: " .
			 $e2->__toString() . "\n";

			 if ( !empty( $GLOBALS['wgCommandLineMode'] ) ) {
				 echo $message;
			 } else {
				 echo nl2br( htmlspecialchars( $message ) ). "\n";
			 }
		 }
	 } else {
		 echo $e->__toString();
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

?>
