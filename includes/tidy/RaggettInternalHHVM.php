<?php

namespace MediaWiki\Tidy;

class RaggettInternalHHVM extends RaggettBase {
	/**
	 * Use the HTML tidy extension to use the tidy library in-process,
	 * saving the overhead of spawning a new process.
	 *
	 * @param string $text HTML to check
	 * @param bool $stderr Whether to read result from error status instead of output
	 * @param int &$retval Exit code (-1 on internal error)
	 * @return string|null
	 */
	protected function cleanWrapped( $text, $stderr = false, &$retval = null ) {
		if ( $stderr ) {
			throw new \Exception( "\$stderr cannot be used with RaggettInternalHHVM" );
		}
		$cleansource = tidy_repair_string( $text, $this->config['tidyConfigFile'], 'utf8' );
		if ( $cleansource === false ) {
			$cleansource = null;
			$retval = -1;
		} else {
			$retval = 0;
		}

		return $cleansource;
	}
}
