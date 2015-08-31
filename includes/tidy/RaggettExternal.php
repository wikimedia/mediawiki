<?php

namespace MediaWiki\Tidy;

class RaggettExternal extends RaggettBase {
	/**
	 * Spawn an external HTML tidy process and get corrected markup back from it.
	 * Also called in OutputHandler.php for full page validation
	 *
	 * @param string $text HTML to check
	 * @param bool $stderr Whether to read result from STDERR rather than STDOUT
	 * @param int &$retval Exit code (-1 on internal error)
	 * @return string|null
	 */
	protected function cleanWrapped( $text, $stderr = false, &$retval = null ) {
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
			"{$this->config['tidyBin']} -config {$this->config['tidyConfigFile']} " .
			$this->config['tidyCommandLine'] . $opts, $descriptorspec, $pipes );

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

		return $cleansource;
	}

	public function supportsValidate() {
		return true;
	}
}
