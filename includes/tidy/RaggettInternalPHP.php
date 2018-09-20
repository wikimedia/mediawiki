<?php

namespace MediaWiki\Tidy;

/**
 * @deprecated since 1.32, use RemexDriver
 */
class RaggettInternalPHP extends RaggettBase {
	/**
	 * Use the HTML tidy extension to use the tidy library in-process,
	 * saving the overhead of spawning a new process.
	 *
	 * @param string $text HTML to check
	 * @param bool $stderr Whether to read result from error status instead of output
	 * @param int|null &$retval Exit code (-1 on internal error)
	 * @return string|null
	 */
	protected function cleanWrapped( $text, $stderr = false, &$retval = null ) {
		if ( !class_exists( 'tidy' ) ) {
			wfWarn( "Unable to load internal tidy class." );
			$retval = -1;

			return null;
		}

		$tidy = new \tidy;
		$tidy->parseString( $text, $this->config['tidyConfigFile'], 'utf8' );

		if ( $stderr ) {
			$retval = $tidy->getStatus();
			return $tidy->errorBuffer;
		}

		$tidy->cleanRepair();
		$retval = $tidy->getStatus();
		if ( $retval == 2 ) {
			// 2 is magic number for fatal error
			// https://secure.php.net/manual/en/tidy.getstatus.php
			$cleansource = null;
		} else {
			$cleansource = tidy_get_output( $tidy );
			if ( !empty( $this->config['debugComment'] ) && $retval > 0 ) {
				$cleansource .= "<!--\nTidy reports:\n" .
					str_replace( '-->', '--&gt;', $tidy->errorBuffer ) .
					"\n-->";
			}
		}

		return $cleansource;
	}

	public function supportsValidate() {
		return true;
	}
}
