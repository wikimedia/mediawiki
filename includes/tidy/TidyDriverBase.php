<?php

namespace MediaWiki\Tidy;

/**
 * Base class for HTML cleanup utilities
 */
abstract class TidyDriverBase {
	protected $config;

	public function __construct( $config ) {
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
	 * Clean up HTML
	 *
	 * @param string $text HTML document fragment to clean up
	 * @return string The corrected HTML output
	 */
	abstract public function tidy( $text );
}
