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
	public function useOutputPage() {
		return $this->useMessageCache() &&
		!empty( $GLOBALS['wgFullyInitialised'] ) &&
		!empty( $GLOBALS['wgOut'] ) &&
		!defined( 'MEDIAWIKI_INSTALL' );
	}

	/**
	 * Whether to log this exception in the exception debug log.
	 *
	 * @since 1.23
	 * @return bool
	 */
	public function isLoggable() {
		return true;
	}

	/**
	 * Can the extension use the Message class/wfMessage to get i18n-ed messages?
	 *
	 * @return bool
	 */
	public function useMessageCache() {
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
	 * @param string $name Class name of the exception
	 * @param array $args Arguments to pass to the callback functions
	 * @return string|null String to output or null if any hook has been called
	 */
	public function runHooks( $name, $args = [] ) {
		return MWExceptionRenderer::runHooks( $this, $name, $args );
	}

	/**
	 * Get a message from i18n
	 *
	 * @param string $key Message name
	 * @param string $fallback Default message if the message cache can't be
	 *                  called by the exception
	 * The function also has other parameters that are arguments for the message
	 * @return string Message with arguments replaced
	 */
	public function msg( $key, $fallback /*[, params...] */ ) {
		$args = array_slice( func_get_args(), 2 );

		if ( $this->useMessageCache() ) {
			try {
				return wfMessage( $key, $args )->text();
			} catch ( Exception $e ) {
			}
		}
		return wfMsgReplaceArgs( $fallback, $args );
	}

	/**
	 * If $wgShowExceptionDetails is true, return a HTML message with a
	 * backtrace to the error, otherwise show a message to ask to set it to true
	 * to show that information.
	 *
	 * @return string Html to output
	 */
	public function getHTML() {
		global $wgShowExceptionDetails;

		if ( $wgShowExceptionDetails ) {
			return '<p>' . nl2br( htmlspecialchars( MWExceptionHandler::getLogMessage( $this ) ) ) .
			'</p><p>Backtrace:</p><p>' .
			nl2br( htmlspecialchars( MWExceptionHandler::getRedactedTraceAsString( $this ) ) ) .
			"</p>\n";
		} else {
			$logId = WebRequest::getRequestId();
			$type = get_class( $this );
			return "<div class=\"errorbox\">" .
			'[' . $logId . '] ' .
			gmdate( 'Y-m-d H:i:s' ) . ": " .
			$this->msg( "internalerror-fatal-exception",
				"Fatal exception of type $1",
				$type,
				$logId,
				MWExceptionHandler::getURL( $this )
			) . "</div>\n" .
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
	public function getText() {
		global $wgShowExceptionDetails;

		if ( $wgShowExceptionDetails ) {
			return MWExceptionHandler::getLogMessage( $this ) .
			"\nBacktrace:\n" . MWExceptionHandler::getRedactedTraceAsString( $this ) . "\n";
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
	public function getPageTitle() {
		return $this->msg( 'internalerror', 'Internal error' );
	}

	/**
	 * Output the exception report using HTML.
	 */
	public function reportHTML() {
		global $wgOut, $wgSitename;
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
			self::header( 'Content-Type: text/html; charset=utf-8' );
			echo "<!DOCTYPE html>\n" .
				'<html><head>' .
				// Mimick OutputPage::setPageTitle behaviour
				'<title>' .
				htmlspecialchars( $this->msg( 'pagetitle', "$1 - $wgSitename", $this->getPageTitle() ) ) .
				'</title>' .
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
	public function report() {
		global $wgMimeType;

		if ( defined( 'MW_API' ) ) {
			// Unhandled API exception, we can't be sure that format printer is alive
			self::header( 'MediaWiki-API-Error: internal_api_error_' . get_class( $this ) );
			wfHttpError( 500, 'Internal Server Error', $this->getText() );
		} elseif ( self::isCommandLine() ) {
			$message = $this->getText();
			// T17602: STDERR may not be available
			if ( defined( 'STDERR' ) ) {
				fwrite( STDERR, $message );
			} else {
				echo $message;
			}
		} else {
			self::statusHeader( 500 );
			self::header( "Content-Type: $wgMimeType; charset=utf-8" );

			$this->reportHTML();
		}
	}

	/**
	 * Check whether we are in command line mode or not to report the exception
	 * in the correct format.
	 *
	 * @return bool
	 */
	public static function isCommandLine() {
		return !empty( $GLOBALS['wgCommandLineMode'] );
	}

	/**
	 * Send a header, if we haven't already sent them. We shouldn't,
	 * but sometimes we might in a weird case like Export
	 * @param string $header
	 */
	private static function header( $header ) {
		if ( !headers_sent() ) {
			header( $header );
		}
	}
	private static function statusHeader( $code ) {
		if ( !headers_sent() ) {
			HttpStatus::header( $code );
		}
	}
}
