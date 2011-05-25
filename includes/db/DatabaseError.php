<?php

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
	 * @param $error String A simple error message to be used for debugging
	 */
	function __construct( DatabaseBase &$db, $error ) {
		$this->db =& $db;
		parent::__construct( $error );
	}

	protected function getContentMessage( $html ) {
		if ( $html ) {
			return nl2br( htmlspecialchars( $this->getMessage() ) );
		} else {
			return $this->getMessage();
		}
	}

	function getText() {
		global $wgShowDBErrorBacktrace;

		$s = $this->getContentMessage( false ) . "\n";

		if ( $wgShowDBErrorBacktrace ) {
			$s .= "Backtrace:\n" . $this->getTraceAsString() . "\n";
		}

		return $s;
	}

	function getHTML() {
		global $wgShowDBErrorBacktrace;

		$s = $this->getContentMessage( true );

		if ( $wgShowDBErrorBacktrace ) {
			$s .= '<p>Backtrace:</p><p>' . nl2br( htmlspecialchars( $this->getTraceAsString() ) );
		}

		return $s;
	}
}

/**
 * @ingroup Database
 */
class DBConnectionError extends DBError {
	public $error;

	function __construct( DatabaseBase &$db, $error = 'unknown error' ) {
		$msg = 'DB connection error';

		if ( trim( $error ) != '' ) {
			$msg .= ": $error";
		}

		$this->error = $error;

		parent::__construct( $db, $msg );
	}

	function useOutputPage() {
		// Not likely to work
		return false;
	}

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

	function getLogMessage() {
		# Don't send to the exception log
		return false;
	}

	function getPageTitle() {
		global $wgSitename;
		return htmlspecialchars( $this->msg( 'dberr-header', "$wgSitename has a problem" ) );
	}

	function getHTML() {
		global $wgShowDBErrorBacktrace;

		$sorry = htmlspecialchars( $this->msg( 'dberr-problems', 'Sorry! This site is experiencing technical difficulties.' ) );
		$again = htmlspecialchars( $this->msg( 'dberr-again', 'Try waiting a few minutes and reloading.' ) );
		$info  = htmlspecialchars( $this->msg( 'dberr-info', '(Can\'t contact the database server: $1)' ) );

		# No database access
		MessageCache::singleton()->disable();

		if ( trim( $this->error ) == '' ) {
			$this->error = $this->db->getProperty( 'mServer' );
		}

		$this->error = Html::element( 'span', array( 'dir' => 'ltr' ), $this->error );

		$noconnect = "<h1>$sorry</h1><p>$again</p><p><small>$info</small></p>";
		$text = str_replace( '$1', $this->error, $noconnect );

		if ( $wgShowDBErrorBacktrace ) {
			$text .= '<p>Backtrace:</p><p>' . nl2br( htmlspecialchars( $this->getTraceAsString() ) );
		}

		$extra = $this->searchForm();

		return "$text<hr />$extra";
	}

	public function reportHTML(){
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
					$cache .= '<div style="color:red;font-size:150%;font-weight:bold;">'.
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
		return parent::reportHTML();
	}

	function searchForm() {
		global $wgSitename, $wgServer;

		$usegoogle = htmlspecialchars( $this->msg( 'dberr-usegoogle', 'You can try searching via Google in the meantime.' ) );
		$outofdate = htmlspecialchars( $this->msg( 'dberr-outofdate', 'Note that their indexes of our content may be out of date.' ) );
		$googlesearch = htmlspecialchars( $this->msg( 'searchbutton', 'Search' ) );

		$search = htmlspecialchars( @$_REQUEST['search'] );

		$server = htmlspecialchars( $wgServer );
		$sitename = htmlspecialchars( $wgSitename );

		$trygoogle = <<<EOT
<div style="margin: 1.5em">$usegoogle<br />
<small>$outofdate</small></div>
<!-- SiteSearch Google -->
<form method="get" action="http://www.google.com/search" id="googlesearch">
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

	private function fileCachedPage() {
		global $wgTitle, $wgOut;

		if ( $wgOut->isDisabled() ) {
			return; // Done already?
		}

		if ( $wgTitle ) {
			$t =& $wgTitle;
		} else {
			$t = Title::newFromText( $this->msg( 'mainpage', 'Main Page' ) );
		}

		$cache = new HTMLFileCache( $t );
		if ( $cache->isFileCached() ) {
			return $cache->fetchPageText();
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

	function __construct( DatabaseBase &$db, $error, $errno, $sql, $fname ) {
		$message = "A database error has occurred.  Did you forget to run maintenance/update.php after upgrading?  See: http://www.mediawiki.org/wiki/Manual:Upgrading#Run_the_update_script\n" .
		  "Query: $sql\n" .
		  "Function: $fname\n" .
		  "Error: $errno $error\n";
		global $wgShowDBErrorBacktrace;
		if( $wgShowDBErrorBacktrace ) {
			$message .= $this->getTraceAsString();
		}
		parent::__construct( $db, $message );

		$this->error = $error;
		$this->errno = $errno;
		$this->sql = $sql;
		$this->fname = $fname;
	}

	function getContentMessage( $html ) {
		if ( $this->useMessageCache() ) {
			$msg = $html ? 'dberrortext' : 'dberrortextcl';
			$ret = wfMsg( $msg, $this->getSQL(),
				$this->fname, $this->errno, $this->error );
			if ( $html ) {
				$ret = htmlspecialchars( $ret );
			}
			return $ret;
		} else {
			return parent::getContentMessage( $html );
		}
	}

	function getSQL() {
		global $wgShowSQLErrors;

		if ( !$wgShowSQLErrors ) {
			return $this->msg( 'sqlhidden', 'SQL hidden' );
		} else {
			return $this->sql;
		}
	}

	function getLogMessage() {
		# Don't send to the exception log
		return false;
	}

	function getPageTitle() {
		return $this->msg( 'databaseerror', 'Database error' );
	}
}

/**
 * @ingroup Database
 */
class DBUnexpectedError extends DBError {}
