<?php

namespace MediaWiki\Tidy;

abstract class RaggettBase extends TidyDriverBase {
	/**
	 * Generic interface for wrapping and unwrapping HTML for Dave Raggett's tidy.
	 *
	 * @param string $text Hideous HTML input
	 * @return string Corrected HTML output
	 */
	public function tidy( $text ) {
		$wrapper = new RaggettWrapper;
		$wrappedtext = $wrapper->getWrapped( $text );

		$retVal = null;
		$correctedtext = $this->cleanWrapped( $wrappedtext, false, $retVal );

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

	public function validate( $text, &$errorStr ) {
		$retval = 0;
		$errorStr = $this->cleanWrapped( $text, true, $retval );
		return ( $retval < 0 && $errorStr == '' ) || $retval == 0;
	}

	/**
	 * Perform a clean/repair operation
	 * @param string $text HTML to check
	 * @param bool $stderr Whether to read result from STDERR rather than STDOUT
	 * @param int &$retval Exit code (-1 on internal error)
	 * @return null|string
	 * @throws MWException
	 */
	abstract protected function cleanWrapped( $text, $stderr = false, &$retval = null );
}
