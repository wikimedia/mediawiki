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

	/**
	 * @var DatabaseBase
	 */
	public $db;

	/**
	 * Construct a database error
	 * @param $db DatabaseBase object which threw the error
	 * @param string $error A simple error message to be used for debugging
	 */
	function __construct( DatabaseBase $db = null, $error ) {
		$this->db = $db;
		parent::__construct( $error );
	}

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
			$s .= '<p>Backtrace:</p><p>' .
				nl2br( htmlspecialchars( $this->getTraceAsString() ) ) . '</p>';
		}

		return $s;
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
		return '<p>' . nl2br( htmlspecialchars( $this->getMessage() ) ) . '</p>';
	}
}

/**
 * @ingroup Database
 */
class DBConnectionError extends DBError {
	public $error;

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
	 * @param $key
	 * @param $fallback
	 * @return string
	 */
	function msg( $key, $fallback /*[, params...] */ ) {
		global $wgLang;

		$args = array_slice( func_get_args(), 2 );

		if ( $this->useMessageCache() ) {
			$message = $wgLang->getMessage( $key );
		} else {
			$message = $fallback;
		}
		return wfMsgReplaceArgs( $message, $args );
	}

	/**
	 * @return boolean
	 */
	function isLoggable() {
		// Don't send to the exception log, already in dberror log
		return false;
	}

	/**
	 * @return string
	 */
	function getPageTitle() {
		return $this->msg( 'dberr-header', 'This wiki has a problem' );
	}

	/**
	 * @return string
	 */
	function getHTML() {
		global $wgShowDBErrorBacktrace, $wgShowHostnames, $wgShowSQLErrors;

		$sorry = htmlspecialchars( $this->msg( 'dberr-problems', "Sorry!\nThis site is experiencing technical difficulties." ) );
		$again = htmlspecialchars( $this->msg( 'dberr-again', 'Try waiting a few minutes and reloading.' ) );

		if ( $wgShowHostnames || $wgShowSQLErrors ) {
			$info = str_replace(
				'$1', Html::element( 'span', array( 'dir' => 'ltr' ), $this->error ),
				htmlspecialchars( $this->msg( 'dberr-info', '(Cannot contact the database server: $1)' ) )
			);
		} else {
			$info = htmlspecialchars( $this->msg( 'dberr-info-hidden', '(Cannot contact the database server)' ) );
		}

		# No database access
		MessageCache::singleton()->disable();

		$text = "<h1>$sorry</h1><p>$again</p><p><small>$info</small></p>";

		if ( $wgShowDBErrorBacktrace ) {
			$text .= '<p>Backtrace:</p><p>' .
				nl2br( htmlspecialchars( $this->getTraceAsString() ) ) . '</p>';
		}

		$text .= '<hr />';
		$text .= $this->searchForm();

		return $text;
	}

	protected function getTextContent() {
		global $wgShowHostnames, $wgShowSQLErrors;

		if ( $wgShowHostnames || $wgShowSQLErrors ) {
			return $this->getMessage();
		} else {
			return 'DB connection error';
		}
	}

	public function reportHTML() {
		global $wgUseFileCache;

		# Check whether we can serve a file-cached copy of the page with the error underneath
		if ( $wgUseFileCache ) {
			try {
				$cache = $this->fileCachedPage();
				# Cached version on file system?
				if ( $cache !== null ) {
					# Hack: extend the body for error messages
					$cache = str_replace( array( '</html>', '</body>' ), '', $cache );
					# Add cache notice...
					$cache .= '<div style="color:red;font-size:150%;font-weight:bold;">' .
						htmlspecialchars( $this->msg( 'dberr-cachederror',
							'This is a cached copy of the requested page, and may not be up to date. ' ) ) .
						'</div>';

					# Output cached page with notices on bottom and re-close body
					echo "{$cache}<hr />{$this->getHTML()}</body></html>";
					return;
				}
			} catch ( MWException $e ) {
				// Do nothing, just use the default page
			}
		}

		# We can't, cough and die in the usual fashion
		parent::reportHTML();
	}

	/**
	 * @return string
	 */
	function searchForm() {
		global $wgSitename, $wgCanonicalServer, $wgRequest;

		$usegoogle = htmlspecialchars( $this->msg( 'dberr-usegoogle', 'You can try searching via Google in the meantime.' ) );
		$outofdate = htmlspecialchars( $this->msg( 'dberr-outofdate', 'Note that their indexes of our content may be out of date.' ) );
		$googlesearch = htmlspecialchars( $this->msg( 'searchbutton', 'Search' ) );

		$search = htmlspecialchars( $wgRequest->getVal( 'search' ) );

		$server = htmlspecialchars( $wgCanonicalServer );
		$sitename = htmlspecialchars( $wgSitename );

		$trygoogle = <<<EOT
<div style="margin: 1.5em">$usegoogle<br />
<small>$outofdate</small></div>
<!-- SiteSearch Google -->
<form method="get" action="//www.google.com/search" id="googlesearch">
	<input type="hidden" name="domains" value="$server" />
	<input type="hidden" name="num" value="50" />
	<input type="hidden" name="ie" value="UTF-8" />
	<input type="hidden" name="oe" value="UTF-8" />

	<input type="text" name="q" size="31" maxlength="255" value="$search" />
	<input type="submit" name="btnG" value="$googlesearch" />
  <div>
	<input type="radio" name="sitesearch" id="gwiki" value="$server" checked="checked" /><label for="gwiki">$sitename</label>
	<input type="radio" name="sitesearch" id="gWWW" value="" /><label for="gWWW">WWW</label>
  </div>
</form>
<!-- SiteSearch Google -->
EOT;
		return $trygoogle;
	}

	/**
	 * @return string
	 */
	private function fileCachedPage() {
		global $wgTitle, $wgOut, $wgRequest;

		if ( $wgOut->isDisabled() ) {
			return ''; // Done already?
		}

		if ( $wgTitle ) { // use $wgTitle if we managed to set it
			$t = $wgTitle->getPrefixedDBkey();
		} else {
			# Fallback to the raw title URL param. We can't use the Title
			# class is it may hit the interwiki table and give a DB error.
			# We may get a cache miss due to not sanitizing the title though.
			$t = str_replace( ' ', '_', $wgRequest->getVal( 'title' ) );
			if ( $t == '' ) { // fallback to main page
				$t = Title::newFromText(
					$this->msg( 'mainpage', 'Main Page' ) )->getPrefixedDBkey();
			}
		}

		$cache = HTMLFileCache::newFromTitle( $t, 'view' );
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
class DBQueryError extends DBError {
	public $error, $errno, $sql, $fname;

	/**
	 * @param $db DatabaseBase
	 * @param $error string
	 * @param $errno int|string
	 * @param $sql string
	 * @param $fname string
	 */
	function __construct( DatabaseBase $db, $error, $errno, $sql, $fname ) {
		$message = "A database error has occurred. Did you forget to run maintenance/update.php after upgrading?  See: https://www.mediawiki.org/wiki/Manual:Upgrading#Run_the_update_script\n" .
			"Query: $sql\n" .
			"Function: $fname\n" .
			"Error: $errno $error\n";
		parent::__construct( $db, $message );

		$this->error = $error;
		$this->errno = $errno;
		$this->sql = $sql;
		$this->fname = $fname;
	}

	/**
	 * @return boolean
	 */
	function isLoggable() {
		// Don't send to the exception log, already in dberror log
		return false;
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
		$s = Html::element( 'p', array(), $this->msg( $key, $this->getFallbackMessage( $key ) ) );

		$details = $this->getTechnicalDetails();
		if ( $details ) {
			$s .= '<ul>';
			foreach ( $details as $key => $detail ) {
				$s .= str_replace(
					'$1', call_user_func_array( 'Html::element', $detail ),
					Html::element( 'li', array(),
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
	 * @return array: Keys are message keys; values are arrays of arguments for Html::element().
	 *   Array will be empty if users are not allowed to see any of these details at all.
	 */
	protected function getTechnicalDetails() {
		global $wgShowHostnames, $wgShowSQLErrors;

		$attribs = array( 'dir' => 'ltr' );
		$details = array();

		if ( $wgShowSQLErrors ) {
			$details['databaseerror-query'] = array(
				'div', array( 'class' => 'mw-code' ) + $attribs, $this->sql );
		}

		if ( $wgShowHostnames || $wgShowSQLErrors ) {
			$errorMessage = $this->errno . ' ' . $this->error;
			$details['databaseerror-function'] = array( 'code', $attribs, $this->fname );
			$details['databaseerror-error'] = array( 'samp', $attribs, $errorMessage );
		}

		return $details;
	}

	/**
	 * @param string $key Message key
	 * @return string: English message text
	 */
	private function getFallbackMessage( $key ) {
		$messages = array(
			'databaseerror-text' => 'A database query error has occurred.
This may indicate a bug in the software.',
			'databaseerror-textcl' => 'A database query error has occurred.',
			'databaseerror-query' => 'Query: $1',
			'databaseerror-function' => 'Function: $1',
			'databaseerror-error' => 'Error: $1',
		);
		return $messages[$key];
	}

}

/**
 * @ingroup Database
 */
class DBUnexpectedError extends DBError {}
