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
 * @author Aaron Schulz
 */

namespace MediaWiki\Exception;

use Exception;
use HttpStatus;
use Message;
use MessageSpecifier;
use MWException;
use Throwable;
use Wikimedia\Rdbms\DBError;
use Wikimedia\Rdbms\DBReadOnlyError;
use Wikimedia\Rdbms\DBExpectedError;

/**
 * Interface to expose exceptions to the client (API bots, users, admins using CLI scripts).
 * @since 1.30
 */
abstract class Renderer {

	/** Render exception as plain text */
	const AS_RAW = 1;

	/** Render exception as HTML */
	const AS_PRETTY = 2;

	/**
	 * Get the HTML for the error.
	 *
	 * This will be called in situations where giving the exception renderer full control over
	 * the page is not appropriate, such as appending an error to HTML loaded from file cache.
	 * (The default implementation also uses this internally.)
	 *
	 * @param Exception|Throwable $e
	 * @return string Html to output
	 */
	abstract public function getHTML( $e );

	/**
	 * Get the plain text for the error.
	 *
	 * @param Exception|Throwable $e
	 * @return string Text to output
	 */
	abstract public function getText( $e );

	/**
	 * Render the exception for web display.
	 *
	 * This method is expected to assume full control over the response (HTML headers etc).
	 * @param Exception|Throwable $e Original exception
	 * @param int $mode MWExceptionRenderer::AS_* constant
	 * @param Exception|Throwable|null $eNew New exception from attempting to show the first
	 */
	abstract protected function doOutput( $e, $mode, $eNew = null );

	/**
	 * Render the exception for display.
	 *
	 * This method is expected to assume full control over the response (HTML headers etc).
	 * @param Exception|Throwable $e Original exception
	 * @param int $mode MWExceptionRenderer::AS_* constant
	 * @param Exception|Throwable|null $eNew New exception from attempting to show the first
	 */
	public function output( $e, $mode, $eNew = null ) {
		if ( defined( 'MW_API' ) ) {
			// Unhandled API exception, we can't be sure that format printer is alive
			$this->header( 'MediaWiki-API-Error: internal_api_error_' . get_class( $e ) );
			wfHttpError( 500, 'Internal Server Error', $this->getText( $e ) );
		} elseif ( $this->isCommandLine() ) {
			$this->printError( $this->getText( $e ) );
		} else {
			$this->doOutput( $e, $mode, $eNew );
		}
	}

	/**
	 * Add a status header, but only if it is safe to do.
	 * @param integer $code
	 */
	protected function statusHeader( $code ) {
		if ( !headers_sent() ) {
			HttpStatus::header( $code );
		}
	}

	/**
	 * Add a header, but only if it is safe to do.
	 * @param string $header
	 */
	protected function header( $header ) {
		if ( !headers_sent() ) {
			header( $header );
		}
	}

	/**
	 * Checks whether it is appropriate to use stdout or a similar console-oriented display method.
	 * @return bool
	 */
	protected function isCommandLine() {
		return !empty( $GLOBALS['wgCommandLineMode'] );
	}

	/**
	 * Print a message, if possible to STDERR.
	 * Use this in command line mode only (see isCommandLine)
	 *
	 * @param string $message Failure text
	 */
	protected function printError( $message ) {
		// NOTE: STDERR may not be available, especially if php-cgi is used from the
		// command line (T17602). Try to produce meaningful output anyway. Using
		// echo may corrupt output to STDOUT though.
		if ( defined( 'STDERR' ) ) {
			fwrite( STDERR, $message );
		} else {
			echo $message;
		}
	}

	/**
	 * Check whether it the site admin enabled the display of backtraces fo the given exception type.
	 * @param Exception|Throwable $e
	 * @return bool
	 */
	protected function showBackTrace( $e ) {
		global $wgShowExceptionDetails, $wgShowDBErrorBacktrace;

		return (
			$wgShowExceptionDetails &&
			( !( $e instanceof DBError ) || $wgShowDBErrorBacktrace )
		);
	}

	/**
	 * Helper function to generate a message explaining how to enable displaying stack traces
	 * for this exception.
	 * @param Exception|Throwable $e
	 * @return string
	 */
	protected function getShowBacktraceError( $e ) {
		global $wgShowExceptionDetails, $wgShowDBErrorBacktrace;
		$vars = [];
		if ( !$wgShowExceptionDetails ) {
			$vars[] = '$wgShowExceptionDetails = true;';
		}
		if ( $e instanceof DBError && !$wgShowDBErrorBacktrace ) {
			$vars[] = '$wgShowDBErrorBacktrace = true;';
		}
		$vars = implode( ' and ', $vars );
		return "Set $vars at the bottom of LocalSettings.php to show detailed debugging information\n";
	}

	/**
	 * Get the OutputPage object to use with a given exception.
	 * @param Exception|Throwable $e
	 * @return \OutputPage|null OutputPage object or null if it is not available, or not advisable
	 *   to use given the type of the exception.
	 */
	protected function getOutputPage( $e ) {
		global $wgOut;

		// If the exception happened somewhere inside LocalisationCache we cannot use i18n
		// so it's probably better to fall back to a more barebones display.
		foreach ( $e->getTrace() as $frame ) {
			if ( isset( $frame['class'] ) && $frame['class'] === 'LocalisationCache' ) {
				return null;
			}
		}

		// make sure $wgOut is available
		if (
			empty( $GLOBALS['wgFullyInitialised'] ) ||
			empty( $GLOBALS['wgOut'] ) ||
			defined( 'MEDIAWIKI_INSTALL' )
		) {
			return null;
		}

		return $wgOut;
	}

	/**
	 * Return the title of the error page for certain common exception types.
	 * @param Exception|Throwable $e
	 * @return string
	 */
	protected function getErrorPageTitle( $e ) {
		if ( $e instanceof MWException ) {
			return $e->getPageTitle();
		} elseif ( $e instanceof DBReadOnlyError ) {
			return $this->msg( 'readonly', 'Database is locked' );
		} elseif ( $e instanceof DBExpectedError ) {
			return $this->msg( 'databaseerror', 'Database error' );
		} else {
			return $this->msg( 'internalerror', 'Internal error' );
		}
	}

	/**
	 * Try to get an extra message about this exception that's appropriate to display before showing
	 * the exception details.
	 * @param Exception|Throwable $e
	 * @return Message|null
	 */
	protected function getCustomMessage( $e ) {
		if ( $e instanceof MessageSpecifier ) {
			return Message::newFromSpecifier( $e );
		}
		return null;
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
	protected function msg( $key, $fallback /*[, params...] */ ) {
		$args = array_slice( func_get_args(), 2 );
		try {
			return wfMessage( $key, $args )->text();
		} catch ( Exception $e ) {
			return wfMsgReplaceArgs( $fallback, $args );
		}
	}

}
