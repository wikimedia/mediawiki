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

use MediaWiki\Html\Html;
use MediaWiki\Request\WebRequest;

/**
 * MediaWiki exception
 *
 * @newable
 * @stable to extend
 *
 * @ingroup Exception
 * @deprecated since 1.40, use native exceptions instead (either directly, or defining subclasses when appropriate)
 */
class MWException extends Exception {
	/**
	 * Should the exception use $wgOut to output the error?
	 *
	 * @return bool
	 */
	private function useOutputPage() {
		// NOTE: keep in sync with MWExceptionRenderer::useOutputPage
		return $this->useMessageCache() &&
		!empty( $GLOBALS['wgFullyInitialised'] ) &&
		!empty( $GLOBALS['wgOut'] ) &&
		!defined( 'MEDIAWIKI_INSTALL' ) &&
		// Don't send a skinned HTTP 500 page to API clients.
		!defined( 'MW_API' );
	}

	/**
	 * Whether to log this exception in the exception debug log.
	 *
	 * @stable to override
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
	 * @stable to override
	 *
	 * @return bool
	 */
	public function useMessageCache() {
		foreach ( $this->getTrace() as $frame ) {
			if ( isset( $frame['class'] ) && $frame['class'] === LocalisationCache::class ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Get a message from i18n
	 *
	 * @param string $key Message name
	 * @param string $fallback Default message if the message cache can't be
	 *                  called by the exception
	 * @param mixed ...$params To pass to wfMessage()
	 * @return string Message with arguments replaced
	 */
	public function msg( $key, $fallback, ...$params ) {
		// NOTE: Keep logic in sync with MWExceptionRenderer::msg.
		$res = false;
		if ( $this->useMessageCache() ) {
			try {
				$res = wfMessage( $key, ...$params )->text();
			} catch ( Exception $e ) {
			}
		}
		if ( $res === false ) {
			// Fallback to static message text and generic sitename.
			// Avoid live config as this must work before Setup/MediaWikiServices finish.
			$res = wfMsgReplaceArgs( $fallback, $params );
			$res = strtr( $res, [
				'{{SITENAME}}' => 'MediaWiki',
			] );
		}
		return $res;
	}

	/**
	 * Format an HTML message for the current exception object.
	 *
	 * @deprecated since 1.42 Provide the error message when constructing the Exception instead.
	 *   If you need a whole custom error page, use ErrorPageError instead.
	 * @return string HTML to output
	 */
	public function getHTML() {
		wfDeprecated( __METHOD__, '1.42' );
		if ( MWExceptionRenderer::shouldShowExceptionDetails() ) {
			return '<p>' . nl2br( htmlspecialchars( MWExceptionHandler::getLogMessage( $this ) ) ) .
			'</p><p>Backtrace:</p><p>' .
			nl2br( htmlspecialchars( MWExceptionHandler::getRedactedTraceAsString( $this ) ) ) .
			"</p>\n";
		} else {
			$logId = WebRequest::getRequestId();
			$type = static::class;
			return Html::errorBox(
			htmlspecialchars(
				'[' . $logId . '] ' .
				gmdate( 'Y-m-d H:i:s' ) . ": " .
				$this->msg( "internalerror-fatal-exception",
					"Fatal exception of type $1",
					$type,
					$logId,
					MWExceptionHandler::getURL()
				)
			) ) .
			"<!-- Set \$wgShowExceptionDetails = true; " .
			"at the bottom of LocalSettings.php to show detailed " .
			"debugging information. -->";
		}
	}

	/**
	 * Format plain text message for the current exception object.
	 *
	 * @deprecated since 1.42 Provide the error message when constructing the Exception instead.
	 *   If you need a whole custom error page, use ErrorPageError instead.
	 * @return string
	 */
	public function getText() {
		wfDeprecated( __METHOD__, '1.42' );
		if ( MWExceptionRenderer::shouldShowExceptionDetails() ) {
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
	 * @deprecated since 1.42 Provide the error message when constructing the Exception instead.
	 *   If you need a whole custom error page, use ErrorPageError instead.
	 * @return string
	 */
	public function getPageTitle() {
		wfDeprecated( __METHOD__, '1.42' );
		return $this->msg( 'internalerror', 'Internal error' );
	}

	/**
	 * Output the exception report using HTML.
	 * @deprecated since 1.42 Provide the error message when constructing the Exception instead.
	 *   If you need a whole custom error page, use ErrorPageError instead.
	 */
	public function reportHTML() {
		wfDeprecated( __METHOD__, '1.42' );
		global $wgOut;

		if ( $this->useOutputPage() ) {
			$wgOut->prepareErrorPage();
			$wgOut->setPageTitle( $this->getPageTitle() );
			// Manually set the html title, since sometimes
			// {{SITENAME}} does not get replaced for exceptions
			// happening inside message rendering.
			$wgOut->setHTMLTitle(
				$this->msg( 'pagetitle', '$1 - MediaWiki', $this->getPageTitle() )
			);

			$wgOut->addHTML( $this->getHTML() );
			// Content-Type is set by OutputPage::output
			$wgOut->output();
		} else {
			self::header( 'Content-Type: text/html; charset=UTF-8' );
			echo "<!DOCTYPE html>\n" .
				'<html><head>' .
				// Mimic OutputPage::setPageTitle behaviour
				'<title>' .
				htmlspecialchars( $this->msg( 'pagetitle', '$1 - MediaWiki', $this->getPageTitle() ) ) .
				'</title>' .
				'<style>body { font-family: sans-serif; margin: 0; padding: 0.5em 2em; }</style>' .
				"</head><body>\n";

			echo $this->getHTML();

			echo "</body></html>\n";
		}
	}

	/**
	 * Output a report about the exception and takes care of formatting.
	 * It will be either HTML or plain text based on isCommandLine().
	 *
	 * @stable to override
	 */
	public function report() {
		if ( defined( 'MW_API' ) ) {
			self::header( 'MediaWiki-API-Error: internal_api_error_' . static::class );
		}

		if ( self::isCommandLine() ) {
			$message = $this->getText();
			$this->writeToCommandLine( $message );
		} else {
			self::statusHeader( 500 );
			$this->reportHTML();
		}
	}

	/**
	 * @internal
	 */
	final public function hasOverriddenHandler(): bool {
		// No deprecation warning - report() is not deprecated, only the other methods
		if ( MWDebug::detectDeprecatedOverride( $this, __CLASS__, 'report' ) ) {
			return true;
		}

		// Check them all here to avoid short-circuiting and report all deprecations,
		// even if the function is not called in this request
		$detectedOverrides = [
			'getHTML' => MWDebug::detectDeprecatedOverride( $this, __CLASS__, 'getHTML', '1.42' ),
			'getText' => MWDebug::detectDeprecatedOverride( $this, __CLASS__, 'getText', '1.42' ),
			'getPageTitle' => MWDebug::detectDeprecatedOverride( $this, __CLASS__, 'getPageTitle', '1.42' ),
			'reportHTML' => MWDebug::detectDeprecatedOverride( $this, __CLASS__, 'reportHTML', '1.42' ),
		];

		return (bool)array_filter( $detectedOverrides );
	}

	/**
	 * Write a message to stderr falling back to stdout if stderr unavailable
	 *
	 * @param string $message
	 * @suppress SecurityCheck-XSS
	 */
	private function writeToCommandLine( $message ) {
		// T17602: STDERR may not be available
		if ( !defined( 'MW_PHPUNIT_TEST' ) && defined( 'STDERR' ) ) {
			fwrite( STDERR, $message );
		} else {
			echo $message;
		}
	}

	/**
	 * Check whether we are in command line mode or not to report the exception
	 * in the correct format.
	 *
	 * @return bool
	 */
	public static function isCommandLine() {
		return MW_ENTRY_POINT === 'cli';
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
