<?php

namespace MediaWiki\Rest\PathTemplateMatcher;

use Exception;

/**
 * @newable
 */
class PathSegmentException extends Exception {
	/** @var string */
	public $template;
	/** @var mixed */
	public $userData;

	/**
	 * @stable to call
	 *
	 * @param string $template
	 * @param mixed $userData
	 */
	public function __construct( $template, $userData ) {
		$this->template = $template;
		$this->userData = $userData;
		parent::__construct( "Unable to add path template \"$template\" since it contains " .
			"an empty path segment." );
	}
}
