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

use Wikimedia\Rdbms\DBConnectionError;
use Wikimedia\Rdbms\DBError;
use Wikimedia\Rdbms\DBReadOnlyError;
use Wikimedia\Rdbms\DBExpectedError;

/**
 * Class to expose exceptions to the client (API bots, users, admins using CLI scripts)
 * @since 1.28
 */
class MWExceptionRenderer {
	const AS_RAW = 1; // show as text
	const AS_PRETTY = 2; // show as HTML

	/**
	 * @param Exception|Throwable $e Original exception
	 * @param integer $mode MWExceptionExposer::AS_* constant
	 * @param Exception|Throwable|null $eNew New exception from attempting to show the first
	 */
	public static function output( $e, $mode, $eNew = null ) {
		global $wgMimeType;

		if ( defined( 'MW_API' ) ) {
			// Unhandled API exception, we can't be sure that format printer is alive
			self::header( 'MediaWiki-API-Error: internal_api_error_' . get_class( $e ) );
			wfHttpError( 500, 'Internal Server Error', self::getText( $e ) );
		} elseif ( self::isCommandLine() ) {
			self::printError( self::getText( $e ) );
		} elseif ( $mode === self::AS_PRETTY ) {
			self::statusHeader( 500 );
			if ( $e instanceof DBConnectionError ) {
				self::reportOutageHTML( $e );
			} else {
				self::header( "Content-Type: $wgMimeType; charset=utf-8" );
				self::reportHTML( $e );
			}
		} else {
			if ( $eNew ) {
				$message = "MediaWiki internal error.\n\n";
				if ( self::showBackTrace( $e ) ) {
					$message .= 'Original exception: ' .
						MWExceptionHandler::getLogMessage( $e ) .
						"\nBacktrace:\n" . MWExceptionHandler::getRedactedTraceAsString( $e ) .
						"\n\nException caught inside exception handler: " .
							MWExceptionHandler::getLogMessage( $eNew ) .
						"\nBacktrace:\n" . MWExceptionHandler::getRedactedTraceAsString( $eNew );
				} else {
					$message .= 'Original exception: ' .
						MWExceptionHandler::getPublicLogMessage( $e );
					$message .= "\n\nException caught inside exception handler.\n\n" .
						self::getShowBacktraceError( $e );
				}
				$message .= "\n";
			} else {
				if ( self::showBackTrace( $e ) ) {
					$message = MWExceptionHandler::getLogMessage( $e ) .
						"\nBacktrace:\n" .
						MWExceptionHandler::getRedactedTraceAsString( $e ) . "\n";
				} else {
					$message = MWExceptionHandler::getPublicLogMessage( $e );
				}
			}
			echo nl2br( htmlspecialchars( $message ) ) . "\n";
		}
	}

	/**
	 * Run hook to allow extensions to modify the text of the exception
	 *
	 * Called by MWException for b/c
	 *
	 * @param Exception|Throwable $e
	 * @param string $name Class name of the exception
	 * @param array $args Arguments to pass to the callback functions
	 * @return string|null String to output or null if any hook has been called
	 */
	public static function runHooks( $e, $name, $args = [] ) {
		global $wgExceptionHooks;

		if ( !isset( $wgExceptionHooks ) || !is_array( $wgExceptionHooks ) ) {
			return null; // Just silently ignore
		}

		if ( !array_key_exists( $name, $wgExceptionHooks ) ||
			!is_array( $wgExceptionHooks[$name] )
		) {
			return null;
		}

		$hooks = $wgExceptionHooks[$name];
		$callargs = array_merge( [ $e ], $args );

		foreach ( $hooks as $hook ) {
			if (
				is_string( $hook ) ||
				( is_array( $hook ) && count( $hook ) >= 2 && is_string( $hook[0] ) )
			) {
				// 'function' or [ 'class', 'hook' ]
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
	 * @param Exception|Throwable $e
	 * @return bool Should the exception use $wgOut to output the error?
	 */
	private static function useOutputPage( $e ) {
		// Can the extension use the Message class/wfMessage to get i18n-ed messages?
		foreach ( $e->getTrace() as $frame ) {
			if ( isset( $frame['class'] ) && $frame['class'] === 'LocalisationCache' ) {
				return false;
			}
		}

		// Don't even bother with OutputPage if there's no Title context set,
		// (e.g. we're in RL code on load.php) - the Skin system (and probably
		// most of MediaWiki) won't work.

		return (
			!empty( $GLOBALS['wgFullyInitialised'] ) &&
			!empty( $GLOBALS['wgOut'] ) &&
			RequestContext::getMain()->getTitle() &&
			!defined( 'MEDIAWIKI_INSTALL' )
		);
	}

	/**
	 * Output the exception report using HTML
	 *
	 * @param Exception|Throwable $e
	 */
	private static function reportHTML( $e ) {
		global $wgOut, $wgSitename;

		if ( self::useOutputPage( $e ) ) {
			if ( $e instanceof MWException ) {
				$wgOut->prepareErrorPage( $e->getPageTitle() );
			} elseif ( $e instanceof DBReadOnlyError ) {
				$wgOut->prepareErrorPage( self::msg( 'readonly', 'Database is locked' ) );
			} elseif ( $e instanceof DBExpectedError ) {
				$wgOut->prepareErrorPage( self::msg( 'databaseerror', 'Database error' ) );
			} else {
				$wgOut->prepareErrorPage( self::msg( 'internalerror', 'Internal error' ) );
			}

			$hookResult = self::runHooks( $e, get_class( $e ) );
			if ( $hookResult ) {
				$wgOut->addHTML( $hookResult );
			} else {
				// Show any custom GUI message before the details
				if ( $e instanceof MessageSpecifier ) {
					$wgOut->addHTML( Message::newFromSpecifier( $e )->escaped() );
				}
				$wgOut->addHTML( self::getHTML( $e ) );
			}

			$wgOut->output();
		} else {
			self::header( 'Content-Type: text/html; charset=utf-8' );
			$pageTitle = self::msg( 'internalerror', 'Internal error' );
			echo "<!DOCTYPE html>\n" .
				'<html><head>' .
				// Mimick OutputPage::setPageTitle behaviour
				'<title>' .
				htmlspecialchars( self::msg( 'pagetitle', "$1 - $wgSitename", $pageTitle ) ) .
				'</title>' .
				'<style>body { font-family: sans-serif; margin: 0; padding: 0.5em 2em; }</style>' .
				"</head><body>\n";

			$hookResult = self::runHooks( $e, get_class( $e ) . 'Raw' );
			if ( $hookResult ) {
				echo $hookResult;
			} else {
				echo self::getHTML( $e );
			}

			echo "</body></html>\n";
		}
	}

	/**
	 * If $wgShowExceptionDetails is true, return a HTML message with a
	 * backtrace to the error, otherwise show a message to ask to set it to true
	 * to show that information.
	 *
	 * @param Exception|Throwable $e
	 * @return string Html to output
	 */
	public static function getHTML( $e ) {
		if ( self::showBackTrace( $e ) ) {
			$html = "<div class=\"errorbox mw-content-ltr\"><p>" .
				nl2br( htmlspecialchars( MWExceptionHandler::getLogMessage( $e ) ) ) .
				'</p><p>Backtrace:</p><p>' .
				nl2br( htmlspecialchars( MWExceptionHandler::getRedactedTraceAsString( $e ) ) ) .
				"</p></div>\n";
		} else {
			$logId = WebRequest::getRequestId();
			$html = "<div class=\"errorbox mw-content-ltr\">" .
				'[' . $logId . '] ' .
				gmdate( 'Y-m-d H:i:s' ) . ": " .
				self::msg( "internalerror-fatal-exception",
					"Fatal exception of type $1",
					get_class( $e ),
					$logId,
					MWExceptionHandler::getURL()
				) . "</div>\n" .
				"<!-- " . wordwrap( self::getShowBacktraceError( $e ), 50 ) . " -->";
		}

		return $html;
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
	private static function msg( $key, $fallback /*[, params...] */ ) {
		$args = array_slice( func_get_args(), 2 );
		try {
			return wfMessage( $key, $args )->text();
		} catch ( Exception $e ) {
			return wfMsgReplaceArgs( $fallback, $args );
		}
	}

	/**
	 * @param Exception|Throwable $e
	 * @return string
	 */
	private static function getText( $e ) {
		if ( self::showBackTrace( $e ) ) {
			return MWExceptionHandler::getLogMessage( $e ) .
				"\nBacktrace:\n" .
				MWExceptionHandler::getRedactedTraceAsString( $e ) . "\n";
		} else {
			return self::getShowBacktraceError( $e );
		}
	}

	/**
	 * @param Exception|Throwable $e
	 * @return bool
	 */
	private static function showBackTrace( $e ) {
		global $wgShowExceptionDetails, $wgShowDBErrorBacktrace;

		return (
			$wgShowExceptionDetails &&
			( !( $e instanceof DBError ) || $wgShowDBErrorBacktrace )
		);
	}

	/**
	 * @param Exception|Throwable $e
	 * @return string
	 */
	private static function getShowBacktraceError( $e ) {
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
	 * @return bool
	 */
	private static function isCommandLine() {
		return !empty( $GLOBALS['wgCommandLineMode'] );
	}

	/**
	 * @param string $header
	 */
	private static function header( $header ) {
		if ( !headers_sent() ) {
			header( $header );
		}
	}

	/**
	 * @param integer $code
	 */
	private static function statusHeader( $code ) {
		if ( !headers_sent() ) {
			HttpStatus::header( $code );
		}
	}

	/**
	 * Print a message, if possible to STDERR.
	 * Use this in command line mode only (see isCommandLine)
	 *
	 * @param string $message Failure text
	 */
	private static function printError( $message ) {
		// NOTE: STDERR may not be available, especially if php-cgi is used from the
		// command line (bug #15602). Try to produce meaningful output anyway. Using
		// echo may corrupt output to STDOUT though.
		if ( defined( 'STDERR' ) ) {
			fwrite( STDERR, $message );
		} else {
			echo $message;
		}
	}

	/**
	 * @param Exception|Throwable $e
	 */
	private static function reportOutageHTML( $e ) {
		global $wgShowDBErrorBacktrace, $wgShowHostnames, $wgShowSQLErrors;

		$sorry = htmlspecialchars( self::msg(
			'dberr-problems',
			'Sorry! This site is experiencing technical difficulties.'
		) );
		$again = htmlspecialchars( self::msg(
			'dberr-again',
			'Try waiting a few minutes and reloading.'
		) );

		if ( $wgShowHostnames || $wgShowSQLErrors ) {
			$info = str_replace(
				'$1',
				Html::element( 'span', [ 'dir' => 'ltr' ], htmlspecialchars( $e->getMessage() ) ),
				htmlspecialchars( self::msg( 'dberr-info', '($1)' ) )
			);
		} else {
			$info = htmlspecialchars( self::msg(
				'dberr-info-hidden',
				'(Cannot access the database)'
			) );
		}

		MessageCache::singleton()->disable(); // no DB access

		$html = "<h1>$sorry</h1><p>$again</p><p><small>$info</small></p>";

		if ( $wgShowDBErrorBacktrace ) {
			$html .= '<p>Backtrace:</p><pre>' .
				htmlspecialchars( $e->getTraceAsString() ) . '</pre>';
		}

		$html .= '<hr />';
		$html .= self::googleSearchForm();

		echo $html;
	}

	/**
	 * @return string
	 */
	private static function googleSearchForm() {
		global $wgSitename, $wgCanonicalServer, $wgRequest;

		$usegoogle = htmlspecialchars( self::msg(
			'dberr-usegoogle',
			'You can try searching via Google in the meantime.'
		) );
		$outofdate = htmlspecialchars( self::msg(
			'dberr-outofdate',
			'Note that their indexes of our content may be out of date.'
		) );
		$googlesearch = htmlspecialchars( self::msg( 'searchbutton', 'Search' ) );
		$search = htmlspecialchars( $wgRequest->getVal( 'search' ) );
		$server = htmlspecialchars( $wgCanonicalServer );
		$sitename = htmlspecialchars( $wgSitename );
		$trygoogle = <<<EOT
<div style="margin: 1.5em">$usegoogle<br />
<small>$outofdate</small>
</div>
<form method="get" action="//www.google.com/search" id="googlesearch">
	<input type="hidden" name="domains" value="$server" />
	<input type="hidden" name="num" value="50" />
	<input type="hidden" name="ie" value="UTF-8" />
	<input type="hidden" name="oe" value="UTF-8" />
	<input type="text" name="q" size="31" maxlength="255" value="$search" />
	<input type="submit" name="btnG" value="$googlesearch" />
	<p>
		<label><input type="radio" name="sitesearch" value="$server" checked="checked" />$sitename</label>
		<label><input type="radio" name="sitesearch" value="" />WWW</label>
	</p>
</form>
EOT;
		return $trygoogle;
	}
}
