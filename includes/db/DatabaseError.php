<?php
/**
 * This file contains database error classes.
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
 * @ingroup Database
 */

/**
 * Database error base class
 * @ingroup Database
 */
class DBError extends MWException {
	/** @var DatabaseBase */
	public $db;

	/**
	 * Construct a database error
	 * @param DatabaseBase $db Object which threw the error
	 * @param string $error A simple error message to be used for debugging
	 */
	function __construct( DatabaseBase $db = null, $error ) {
		$this->db = $db;
		parent::__construct( $error );
	}
}

/**
 * Base class for the more common types of database errors. These are known to occur
 * frequently, so we try to give friendly error messages for them.
 *
 * @ingroup Database
 * @since 1.23
 */
class DBExpectedError extends DBError {
	/**
	 * @return string
	 */
	function getText() {
		global $wgShowDBErrorBacktrace;

		$s = $this->getTextContent() . "\n";

		if ( $wgShowDBErrorBacktrace ) {
			$s .= "Backtrace:\n" . $this->getTraceAsString() . "\n";
		}

		return $s;
	}

	/**
	 * @return string
	 */
	function getHTML() {
		global $wgShowDBErrorBacktrace;

		$s = $this->getHTMLContent();

		if ( $wgShowDBErrorBacktrace ) {
			$s .= '<p>Backtrace:</p><pre>' . htmlspecialchars( $this->getTraceAsString() ) . '</pre>';
		}

		return $s;
	}

	function getPageTitle() {
		return $this->msg( 'databaseerror', 'Database error' );
	}

	/**
	 * @return string
	 */
	protected function getTextContent() {
		return $this->getMessage();
	}

	/**
	 * @return string
	 */
	protected function getHTMLContent() {
		return '<p>' . nl2br( htmlspecialchars( $this->getTextContent() ) ) . '</p>';
	}
}

/**
 * @ingroup Database
 */
class DBConnectionError extends DBExpectedError {
	/** @var string Error text */
	public $error;

	/**
	 * @param DatabaseBase $db Object throwing the error
	 * @param string $error Error text
	 */
	function __construct( DatabaseBase $db = null, $error = 'unknown error' ) {
		$msg = 'DB connection error';

		if ( trim( $error ) != '' ) {
			$msg .= ": $error";
		} elseif ( $db ) {
			$error = $this->db->getServer();
		}

		parent::__construct( $db, $msg );
		$this->error = $error;
	}

	/**
	 * @return bool
	 */
	function useOutputPage() {
		// Not likely to work
		return false;
	}

	/**
	 * @param string $key
	 * @param string $fallback Unescaped alternative error text in case the
	 *   message cache cannot be used. Can contain parameters as in regular
	 *   messages, that should be passed as additional parameters.
	 * @return string Unprocessed plain error text with parameters replaced
	 */
	function msg( $key, $fallback /*[, params...] */ ) {
		$args = array_slice( func_get_args(), 2 );

		if ( $this->useMessageCache() ) {
			return wfMessage( $key, $args )->useDatabase( false )->text();
		} else {
			return wfMsgReplaceArgs( $fallback, $args );
		}
	}

	/**
	 * @return bool
	 */
	function isLoggable() {
		// Don't send to the exception log, already in dberror log
		return false;
	}

	/**
	 * @return string Safe HTML
	 */
	function getHTML() {
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
				'$1', Html::element( 'span', [ 'dir' => 'ltr' ], $this->error ),
				htmlspecialchars( $this->msg( 'dberr-info', '(Cannot access the database: $1)' ) )
			);
		} else {
			$info = htmlspecialchars( $this->msg(
				'dberr-info-hidden',
				'(Cannot access the database)'
			) );
		}

		# No database access
		MessageCache::singleton()->disable();

		$html = "<h1>$sorry</h1><p>$again</p><p><small>$info</small></p>";

		if ( $wgShowDBErrorBacktrace ) {
			$html .= '<p>Backtrace:</p><pre>' . htmlspecialchars( $this->getTraceAsString() ) . '</pre>';
		}

		$html .= '<hr />';
		$html .= $this->searchForm();

		return $html;
	}

	protected function getTextContent() {
		global $wgShowHostnames, $wgShowSQLErrors;

		if ( $wgShowHostnames || $wgShowSQLErrors ) {
			return $this->getMessage();
		} else {
			return 'DB connection error';
		}
	}

	/**
	 * Output the exception report using HTML.
	 *
	 * @return void
	 */
	public function reportHTML() {
		global $wgUseFileCache;

		// Check whether we can serve a file-cached copy of the page with the error underneath
		if ( $wgUseFileCache ) {
			try {
				$cache = $this->fileCachedPage();
				// Cached version on file system?
				if ( $cache !== null ) {
					// Hack: extend the body for error messages
					$cache = str_replace( [ '</html>', '</body>' ], '', $cache );
					// Add cache notice...
					$cache .= '<div style="border:1px solid #ffd0d0;padding:1em;">' .
						htmlspecialchars( $this->msg( 'dberr-cachederror',
							'This is a cached copy of the requested page, and may not be up to date.' ) ) .
						'</div>';

					// Output cached page with notices on bottom and re-close body
					echo "{$cache}<hr />{$this->getHTML()}</body></html>";

					return;
				}
			} catch ( Exception $e ) {
				// Do nothing, just use the default page
			}
		}

		// We can't, cough and die in the usual fashion
		parent::reportHTML();
	}

	/**
	 * @return string
	 */
	function searchForm() {
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

	/**
	 * @return string
	 */
	private function fileCachedPage() {
		$context = RequestContext::getMain();

		if ( $context->getOutput()->isDisabled() ) {
			// Done already?
			return '';
		}

		if ( $context->getTitle() ) {
			// Use the main context's title if we managed to set it
			$t = $context->getTitle()->getPrefixedDBkey();
		} else {
			// Fallback to the raw title URL param. We can't use the Title
			// class is it may hit the interwiki table and give a DB error.
			// We may get a cache miss due to not sanitizing the title though.
			$t = str_replace( ' ', '_', $context->getRequest()->getVal( 'title' ) );
			if ( $t == '' ) { // fallback to main page
				$t = Title::newFromText(
					$this->msg( 'mainpage', 'Main Page' ) )->getPrefixedDBkey();
			}
		}

		$cache = new HTMLFileCache( $t, 'view' );
		if ( $cache->isCached() ) {
			return $cache->fetchText();
		} else {
			return '';
		}
	}
}

/**
 * @ingroup Database
 */
class DBQueryError extends DBExpectedError {
	public $error, $errno, $sql, $fname;

	/**
	 * @param DatabaseBase $db
	 * @param string $error
	 * @param int|string $errno
	 * @param string $sql
	 * @param string $fname
	 */
	function __construct( DatabaseBase $db, $error, $errno, $sql, $fname ) {
		if ( $db->wasConnectionError( $errno ) ) {
			$message = "A connection error occured. \n" .
				"Query: $sql\n" .
				"Function: $fname\n" .
				"Error: $errno $error\n";
		} else {
			$message = "A database error has occurred. Did you forget to run " .
				"maintenance/update.php after upgrading?  See: " .
				"https://www.mediawiki.org/wiki/Manual:Upgrading#Run_the_update_script\n" .
				"Query: $sql\n" .
				"Function: $fname\n" .
				"Error: $errno $error\n";
		}
		parent::__construct( $db, $message );

		$this->error = $error;
		$this->errno = $errno;
		$this->sql = $sql;
		$this->fname = $fname;
	}

	/**
	 * @return string
	 */
	function getPageTitle() {
		return $this->msg( 'databaseerror', 'Database error' );
	}

	/**
	 * @return string
	 */
	protected function getHTMLContent() {
		$key = 'databaseerror-text';
		$s = Html::element( 'p', [], $this->msg( $key, $this->getFallbackMessage( $key ) ) );

		$details = $this->getTechnicalDetails();
		if ( $details ) {
			$s .= '<ul>';
			foreach ( $details as $key => $detail ) {
				$s .= str_replace(
					'$1', call_user_func_array( 'Html::element', $detail ),
					Html::element( 'li', [],
						$this->msg( $key, $this->getFallbackMessage( $key ) )
					)
				);
			}
			$s .= '</ul>';
		}

		return $s;
	}

	/**
	 * @return string
	 */
	protected function getTextContent() {
		$key = 'databaseerror-textcl';
		$s = $this->msg( $key, $this->getFallbackMessage( $key ) ) . "\n";

		foreach ( $this->getTechnicalDetails() as $key => $detail ) {
			$s .= $this->msg( $key, $this->getFallbackMessage( $key ), $detail[2] ) . "\n";
		}

		return $s;
	}

	/**
	 * Make a list of technical details that can be shown to the user. This information can
	 * aid in debugging yet may be useful to an attacker trying to exploit a security weakness
	 * in the software or server configuration.
	 *
	 * Thus no such details are shown by default, though if $wgShowHostnames is true, only the
	 * full SQL query is hidden; in fact, the error message often does contain a hostname, and
	 * sites using this option probably don't care much about "security by obscurity". Of course,
	 * if $wgShowSQLErrors is true, the SQL query *is* shown.
	 *
	 * @return array Keys are message keys; values are arrays of arguments for Html::element().
	 *   Array will be empty if users are not allowed to see any of these details at all.
	 */
	protected function getTechnicalDetails() {
		global $wgShowHostnames, $wgShowSQLErrors;

		$attribs = [ 'dir' => 'ltr' ];
		$details = [];

		if ( $wgShowSQLErrors ) {
			$details['databaseerror-query'] = [
				'div', [ 'class' => 'mw-code' ] + $attribs, $this->sql ];
		}

		if ( $wgShowHostnames || $wgShowSQLErrors ) {
			$errorMessage = $this->errno . ' ' . $this->error;
			$details['databaseerror-function'] = [ 'code', $attribs, $this->fname ];
			$details['databaseerror-error'] = [ 'samp', $attribs, $errorMessage ];
		}

		return $details;
	}

	/**
	 * @param string $key Message key
	 * @return string English message text
	 */
	private function getFallbackMessage( $key ) {
		$messages = [
			'databaseerror-text' => 'A database query error has occurred.
This may indicate a bug in the software.',
			'databaseerror-textcl' => 'A database query error has occurred.',
			'databaseerror-query' => 'Query: $1',
			'databaseerror-function' => 'Function: $1',
			'databaseerror-error' => 'Error: $1',
		];

		return $messages[$key];
	}
}

/**
 * @ingroup Database
 */
class DBUnexpectedError extends DBError {
}

/**
 * @ingroup Database
 */
class DBReadOnlyError extends DBExpectedError {
	function getPageTitle() {
		return $this->msg( 'readonly', 'Database is locked' );
	}
}

/**
 * @ingroup Database
 */
class DBTransactionError extends DBExpectedError {
}
