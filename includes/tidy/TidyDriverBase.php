<?php

namespace MediaWiki\Tidy;

/**
 * Base class for HTML cleanup utilities
 */
abstract class TidyDriverBase {
	protected $config;

	function __construct( $config ) {
		$this->config = $config;
	}

	/**
	 * Return true if validate() can be used
	 * @return bool
	 */
	public function supportsValidate() {
		return false;
	}

	/**
	 * Check HTML for errors, used if $wgValidateAllHtml = true.
	 *
	 * @param string $text
	 * @param string &$errorStr Return the error string
	 * @throws \MWException
	 * @return bool Whether the HTML is valid
	 */
	public function validate( $text, &$errorStr ) {
		throw new \MWException( static::class . ' does not support validate()' );
	}

	/**
	 * Clean up HTML
	 *
	 * @param string $text HTML document fragment to clean up
	 * @return string The corrected HTML output
	 */
	abstract public function tidy( $text );
}
