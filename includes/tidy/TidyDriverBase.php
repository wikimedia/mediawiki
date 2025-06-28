<?php

namespace MediaWiki\Tidy;

/**
 * Base class for HTML cleanup utilities
 */
abstract class TidyDriverBase {
	/** @var array */
	protected $config;

	public function __construct( array $config ) {
		$this->config = $config;
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
