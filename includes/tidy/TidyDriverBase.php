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
	 * @deprecated since 1.36, always returns false
	 */
	public function supportsValidate() {
		wfDeprecated( __METHOD__, '1.36' );
		return false;
	}

	/**
	 * Clean up HTML
	 *
	 * @param string $text HTML document fragment to clean up
	 * @param ?callable $textProcessor A callback to run on the contents of
	 *   text nodes (not elements or attribute values).  This can be used to
	 *   apply text modifications like french spacing or smart quotes, without
	 *   affecting element or attribute markup.
	 * @return string The corrected HTML output
	 */
	abstract public function tidy( $text, ?callable $textProcessor = null );
}
