<?php
/**
 * HTML validation and correction
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
 * @ingroup Parser
 */

/**
 * Class used to hide mw:editsection tokens from Tidy so that it doesn't break them
 * or break on them. This is a bit of a hack for now, but hopefully in the future
 * we may create a real postprocessor or something that will replace this.
 * It's called wrapper because for now it basically takes over MWTidy::tidy's task
 * of wrapping the text in a xhtml block
 *
 * This re-uses some of the parser's UNIQ tricks, though some of it is private so it's
 * duplicated. Perhaps we should create an abstract marker hiding class.
 *
 * @ingroup Parser
 */
class MWTidyWrapper {

	/**
	 * @var ReplacementArray
	 */
	protected $mTokens;

	protected $mUniqPrefix;

	protected $mMarkerIndex;

	public function __construct() {
		$this->mTokens = null;
		$this->mUniqPrefix = null;
	}

	/**
	 * @param $text string
	 * @return string
	 */
	public function getWrapped( $text ) {
		$this->mTokens = new ReplacementArray;
		$this->mUniqPrefix = "\x7fUNIQ" .
			dechex( mt_rand( 0, 0x7fffffff ) ) . dechex( mt_rand( 0, 0x7fffffff ) );
		$this->mMarkerIndex = 0;

		// Replace <mw:editsection> elements with placeholders
		$wrappedtext = preg_replace_callback( ParserOutput::EDITSECTION_REGEX,
			array( &$this, 'replaceCallback' ), $text );
		// ...and <mw:toc> markers
		$wrappedtext = preg_replace_callback( '/\<\\/?mw:toc\>/',
			array( &$this, 'replaceCallback' ), $wrappedtext );

		// Modify inline Microdata <link> and <meta> elements so they say <html-link> and <html-meta> so
		// we can trick Tidy into not stripping them out by including them in tidy's new-empty-tags config
		$wrappedtext = preg_replace( '!<(link|meta)([^>]*?)(/{0,1}>)!', '<html-$1$2$3', $wrappedtext );

		// Wrap the whole thing in a doctype and body for Tidy.
		$wrappedtext = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"' .
			' "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html>' .
			'<head><title>test</title></head><body>' . $wrappedtext . '</body></html>';

		return $wrappedtext;
	}

	/**
	 * @param $m array
	 *
	 * @return string
	 */
	function replaceCallback( $m ) {
		$marker = "{$this->mUniqPrefix}-item-{$this->mMarkerIndex}" . Parser::MARKER_SUFFIX;
		$this->mMarkerIndex++;
		$this->mTokens->setPair( $marker, $m[0] );
		return $marker;
	}

	/**
	 * @param $text string
	 * @return string
	 */
	public function postprocess( $text ) {
		// Revert <html-{link,meta}> back to <{link,meta}>
		$text = preg_replace( '!<html-(link|meta)([^>]*?)(/{0,1}>)!', '<$1$2$3', $text );

		// Restore the contents of placeholder tokens
		$text = $this->mTokens->replace( $text );

		return $text;
	}

}

/**
 * Class to interact with HTML tidy
 *
 * Either the external tidy program or the in-process tidy extension
 * will be used depending on availability. Override the default
 * $wgTidyInternal setting to disable the internal if it's not working.
 *
 * @ingroup Parser
 */
class MWTidy {
	/**
	 * Interface with html tidy, used if $wgUseTidy = true.
	 * If tidy isn't able to correct the markup, the original will be
	 * returned in all its glory with a warning comment appended.
	 *
	 * @param string $text hideous HTML input
	 * @return String: corrected HTML output
	 */
	public static function tidy( $text ) {
		global $wgTidyInternal;

		$wrapper = new MWTidyWrapper;
		$wrappedtext = $wrapper->getWrapped( $text );

		$retVal = null;
		if ( $wgTidyInternal ) {
			$correctedtext = self::execInternalTidy( $wrappedtext, false, $retVal );
		} else {
			$correctedtext = self::execExternalTidy( $wrappedtext, false, $retVal );
		}

		if ( $retVal < 0 ) {
			wfDebug( "Possible tidy configuration error!\n" );
			return $text . "\n<!-- Tidy was unable to run -->\n";
		} elseif ( is_null( $correctedtext ) ) {
			wfDebug( "Tidy error detected!\n" );
			return $text . "\n<!-- Tidy found serious XHTML errors -->\n";
		}

		$correctedtext = $wrapper->postprocess( $correctedtext ); // restore any hidden tokens

		return $correctedtext;
	}

	/**
	 * Check HTML for errors, used if $wgValidateAllHtml = true.
	 *
	 * @param $text String
	 * @param &$errorStr String: return the error string
	 * @return Boolean: whether the HTML is valid
	 */
	public static function checkErrors( $text, &$errorStr = null ) {
		global $wgTidyInternal;

		$retval = 0;
		if ( $wgTidyInternal ) {
			$errorStr = self::execInternalTidy( $text, true, $retval );
		} else {
			$errorStr = self::execExternalTidy( $text, true, $retval );
		}

		return ( $retval < 0 && $errorStr == '' ) || $retval == 0;
	}

	/**
	 * Spawn an external HTML tidy process and get corrected markup back from it.
	 * Also called in OutputHandler.php for full page validation
	 *
	 * @param string $text HTML to check
	 * @param $stderr Boolean: Whether to read result from STDERR rather than STDOUT
	 * @param &$retval int Exit code (-1 on internal error)
	 * @return mixed String or null
	 */
	private static function execExternalTidy( $text, $stderr = false, &$retval = null ) {
		global $wgTidyConf, $wgTidyBin, $wgTidyOpts;
		wfProfileIn( __METHOD__ );

		$cleansource = '';
		$opts = ' -utf8';

		if ( $stderr ) {
			$descriptorspec = array(
				0 => array( 'pipe', 'r' ),
				1 => array( 'file', wfGetNull(), 'a' ),
				2 => array( 'pipe', 'w' )
			);
		} else {
			$descriptorspec = array(
				0 => array( 'pipe', 'r' ),
				1 => array( 'pipe', 'w' ),
				2 => array( 'file', wfGetNull(), 'a' )
			);
		}

		$readpipe = $stderr ? 2 : 1;
		$pipes = array();

		$process = proc_open(
			"$wgTidyBin -config $wgTidyConf $wgTidyOpts$opts", $descriptorspec, $pipes );

		//NOTE: At least on linux, the process will be created even if tidy is not installed.
		//      This means that missing tidy will be treated as a validation failure.

		if ( is_resource( $process ) ) {
			// Theoretically, this style of communication could cause a deadlock
			// here. If the stdout buffer fills up, then writes to stdin could
			// block. This doesn't appear to happen with tidy, because tidy only
			// writes to stdout after it's finished reading from stdin. Search
			// for tidyParseStdin and tidySaveStdout in console/tidy.c
			fwrite( $pipes[0], $text );
			fclose( $pipes[0] );
			while ( !feof( $pipes[$readpipe] ) ) {
				$cleansource .= fgets( $pipes[$readpipe], 1024 );
			}
			fclose( $pipes[$readpipe] );
			$retval = proc_close( $process );
		} else {
			wfWarn( "Unable to start external tidy process" );
			$retval = -1;
		}

		if ( !$stderr && $cleansource == '' && $text != '' ) {
			// Some kind of error happened, so we couldn't get the corrected text.
			// Just give up; we'll use the source text and append a warning.
			$cleansource = null;
		}

		wfProfileOut( __METHOD__ );
		return $cleansource;
	}

	/**
	 * Use the HTML tidy extension to use the tidy library in-process,
	 * saving the overhead of spawning a new process.
	 *
	 * @param string $text HTML to check
	 * @param $stderr Boolean: Whether to read result from error status instead of output
	 * @param &$retval int Exit code (-1 on internal error)
	 * @return mixed String or null
	 */
	private static function execInternalTidy( $text, $stderr = false, &$retval = null ) {
		global $wgTidyConf, $wgDebugTidy;
		wfProfileIn( __METHOD__ );

		if ( !class_exists( 'tidy' ) ) {
			wfWarn( "Unable to load internal tidy class." );
			$retval = -1;

			wfProfileOut( __METHOD__ );
			return null;
		}

		$tidy = new tidy;
		$tidy->parseString( $text, $wgTidyConf, 'utf8' );

		if ( $stderr ) {
			$retval = $tidy->getStatus();

			wfProfileOut( __METHOD__ );
			return $tidy->errorBuffer;
		}

		$tidy->cleanRepair();
		$retval = $tidy->getStatus();
		if ( $retval == 2 ) {
			// 2 is magic number for fatal error
			// http://www.php.net/manual/en/function.tidy-get-status.php
			$cleansource = null;
		} else {
			$cleansource = tidy_get_output( $tidy );
			if ( $wgDebugTidy && $retval > 0 ) {
				$cleansource .= "<!--\nTidy reports:\n" .
					str_replace( '-->', '--&gt;', $tidy->errorBuffer ) .
					"\n-->";
			}
		}

		wfProfileOut( __METHOD__ );
		return $cleansource;
	}
}
