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
use MessageCache;
use MWExceptionHandler;
use Throwable;
use WebRequest;
use Wikimedia\Rdbms\DBConnectionError;

/**
 * Legacy Renderer implementation
 * @since 1.30
 */
class StandardRenderer extends Renderer {

	/**
	 * {@inheritdoc}
	 *
	 * If $wgShowExceptionDetails is true, return a HTML message with a
	 * backtrace to the error, otherwise show a message to ask to set it to true
	 * to show that information.
	 */
	public function getHTML( $e ) {
		if ( $this->showBackTrace( $e ) ) {
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
					$this->msg( "internalerror-fatal-exception",
						"Fatal exception of type $1",
						get_class( $e ),
						$logId,
						MWExceptionHandler::getURL()
					) . "</div>\n" .
					"<!-- " . wordwrap( $this->getShowBacktraceError( $e ), 50 ) . " -->";
		}

		return $html;
	}

	/**
	 * @inheritdoc
	 */
	public function getText( $e ) {
		if ( $this->showBackTrace( $e ) ) {
			return MWExceptionHandler::getLogMessage( $e ) .
				   "\nBacktrace:\n" .
				   MWExceptionHandler::getRedactedTraceAsString( $e ) . "\n";
		} else {
			return $this->getShowBacktraceError( $e );
		}
	}

	/**
	 * @inheritdoc
	 */
	protected function doOutput( $e, $mode, $eNew = null ) {
		global $wgMimeType;

		if ( $mode === self::AS_PRETTY ) {
			$this->statusHeader( 500 );
			if ( $e instanceof DBConnectionError ) {
				$this->reportOutageHTML( $e );
			} else {
				$this->header( "Content-Type: $wgMimeType; charset=utf-8" );
				$this->reportHTML( $e );
			}
		} else {
			if ( $eNew ) {
				$message = "MediaWiki internal error.\n\n";
				if ( $this->showBackTrace( $e ) ) {
					$message .= 'Original exception: ' . MWExceptionHandler::getLogMessage( $e ) .
								"\nBacktrace:\n" .
								MWExceptionHandler::getRedactedTraceAsString( $e ) .
								"\n\nException caught inside exception handler: " .
								MWExceptionHandler::getLogMessage( $eNew ) . "\nBacktrace:\n" .
								MWExceptionHandler::getRedactedTraceAsString( $eNew );
				} else {
					$message .= 'Original exception: ' .
								MWExceptionHandler::getPublicLogMessage( $e );
					$message .= "\n\nException caught inside exception handler.\n\n" .
								$this->getShowBacktraceError( $e );
				}
				$message .= "\n";
			} else {
				if ( $this->showBackTrace( $e ) ) {
					$message =
						MWExceptionHandler::getLogMessage( $e ) . "\nBacktrace:\n" .
						MWExceptionHandler::getRedactedTraceAsString( $e ) . "\n";
				} else {
					$message = MWExceptionHandler::getPublicLogMessage( $e );
				}
			}
			echo nl2br( htmlspecialchars( $message ) ) . "\n";
		}
	}

	/**
	 * Output the exception report using HTML
	 *
	 * @param Exception|Throwable $e
	 */
	private function reportHTML( $e ) {
		global $wgSitename;

		$outputPage = $this->getOutputPage( $e );
		$pageTitle = $this->getErrorPageTitle( $e );
		$customMessage = $this->getCustomMessage( $e );
		if ( $outputPage ) {
			$outputPage->prepareErrorPage( $pageTitle );

			if ( $customMessage ) {
				$outputPage->addHTML( $customMessage->escaped() );
			}
			$outputPage->addHTML( $this->getHTML( $e ) );

			$outputPage->output();
		} else {
			$this->header( 'Content-Type: text/html; charset=utf-8' );
			$pageTitle = $this->msg( 'internalerror', 'Internal error' );
			echo "<!DOCTYPE html>\n" .
				'<html><head>' .
				// Mimick OutputPage::setPageTitle behaviour
				'<title>' .
				htmlspecialchars( $this->msg( 'pagetitle', "$1 - $wgSitename", $pageTitle ) ) .
				'</title>' .
				'<style>body { font-family: sans-serif; margin: 0; padding: 0.5em 2em; }</style>' .
				"</head><body>\n";

			echo $this->getHTML( $e );

			echo "</body></html>\n";
		}
	}

	/**
	 * @param Exception|Throwable $e
	 */
	private function reportOutageHTML( $e ) {
		global $wgShowDBErrorBacktrace, $wgShowHostnames, $wgShowSQLErrors;

		$sorry = htmlspecialchars( $this->msg(
			'dberr-problems',
			'Sorry! This site is experiencing technical difficulties.'
		) );
		$again = htmlspecialchars( $this->msg(
			'dberr-again',
			'Try waiting a few minutes and reloading.'
		) );

		if ( $wgShowHostnames || $wgShowSQLErrors ) {
			$info = str_replace(
				'$1',
				Html::element( 'span', [ 'dir' => 'ltr' ], htmlspecialchars( $e->getMessage() ) ),
				htmlspecialchars( $this->msg( 'dberr-info', '($1)' ) )
			);
		} else {
			$info = htmlspecialchars( $this->msg(
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
		$html .= $this->googleSearchForm();

		echo $html;
	}

	/**
	 * @return string
	 */
	private function googleSearchForm() {
		global $wgSitename, $wgCanonicalServer, $wgRequest;

		$usegoogle = htmlspecialchars( $this->msg(
			'dberr-usegoogle',
			'You can try searching via Google in the meantime.'
		) );
		$outofdate = htmlspecialchars( $this->msg(
			'dberr-outofdate',
			'Note that their indexes of our content may be out of date.'
		) );
		$googlesearch = htmlspecialchars( $this->msg( 'searchbutton', 'Search' ) );
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
