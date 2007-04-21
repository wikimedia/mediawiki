<?php
/**
 * Interface with html tidy, used if $wgUseTidy = true from Parser::parse.
 * If tidy isn't able to correct the markup, the original will be
 * returned in all its glory with a warning comment appended.
 *
*/
class Tidy {
	 /*
	 * Either the external tidy program or the in-process tidy extension
	 * will be used depending on availability. Override the default
	 * $wgTidyInternal setting to disable the internal if it's not working.
	 * @param string $text Hideous HTML input
	 * @return string Corrected HTML output
	 * @public
	 * @static
	 */
	public static function RunOn( $text ) {
		global $wgTidyInternal;
		$wrappedtext = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"'.
' "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html>'.
'<head><title>test</title></head><body>'.$text.'</body></html>';
		if( $wgTidyInternal ) {
			$correctedtext = Tidy::internal( $wrappedtext );
		} else {
			$correctedtext = Tidy::external( $wrappedtext );
		}
		if( is_null( $correctedtext ) ) {
			wfDebug( "Tidy error detected!\n" );
			return $text . "\n<!-- Tidy found serious XHTML errors -->\n";
		}
		return $correctedtext;
	}

	/**
	 * Spawn an external HTML tidy process and get corrected markup back from it.
	 */
	private static function external( $text ) {
		global $wgTidyConf, $wgTidyBin, $wgTidyOpts;
		$fname = 'Parser::externalTidy';
		wfProfileIn( $fname );

		$cleansource = '';
		$opts = ' -utf8';

		$descriptorspec = array(
			0 => array('pipe', 'r'),
			1 => array('pipe', 'w'),
			2 => array('file', '/dev/null', 'a')  // FIXME: this line in UNIX-specific, it generates a warning on Windows, because /dev/null is not a valid Windows file.
		);
		$pipes = array();
		$process = proc_open("$wgTidyBin -config $wgTidyConf $wgTidyOpts$opts", $descriptorspec, $pipes);
		if (is_resource($process)) {
			// Theoretically, this style of communication could cause a deadlock
			// here. If the stdout buffer fills up, then writes to stdin could
			// block. This doesn't appear to happen with tidy, because tidy only
			// writes to stdout after it's finished reading from stdin. Search
			// for tidyParseStdin and tidySaveStdout in console/tidy.c
			fwrite($pipes[0], $text);
			fclose($pipes[0]);
			while (!feof($pipes[1])) {
				$cleansource .= fgets($pipes[1], 1024);
			}
			fclose($pipes[1]);
			proc_close($process);
		}

		wfProfileOut( $fname );

		if( $cleansource == '' && $text != '') {
			// Some kind of error happened, so we couldn't get the corrected text.
			// Just give up; we'll use the source text and append a warning.
			return null;
		} else {
			return $cleansource;
		}
	}

	/**
	 * Use the HTML tidy PECL extension to use the tidy library in-process,
	 * saving the overhead of spawning a new process. Currently written to
	 * the PHP 4.3.x version of the extension, may not work on PHP 5.
	 *
	 * 'pear install tidy' should be able to compile the extension module.
	 */
	private static function internal( $text ) {
		global $wgTidyConf;
		$fname = 'Parser::internalTidy';
		wfProfileIn( $fname );

		tidy_load_config( $wgTidyConf );
		tidy_set_encoding( 'utf8' );
		tidy_parse_string( $text );
		tidy_clean_repair();
		if( tidy_get_status() == 2 ) {
			// 2 is magic number for fatal error
			// http://www.php.net/manual/en/function.tidy-get-status.php
			$cleansource = null;
		} else {
			$cleansource = tidy_get_output();
		}
		wfProfileOut( $fname );
		return $cleansource;
	}
}
?>
