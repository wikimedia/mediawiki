<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @license GPL-2.0-or-later
 * @author Kunal Mehta <legoktm@debian.org>
 */

namespace Wikimedia\HtmlArmor;

/**
 * Marks HTML that shouldn't be escaped
 *
 * @newable
 *
 * @since 1.28
 */
class HtmlArmor {

	/**
	 * @var string|null
	 */
	private $value;

	/**
	 * @stable to call
	 *
	 * @param string|null $value
	 * @param-taint $value exec_html
	 */
	public function __construct( $value ) {
		$this->value = $value;
	}

	/**
	 * Provide a string or HtmlArmor object
	 * and get safe HTML back
	 *
	 * @param string|HtmlArmor $input
	 * @return string|null safe for usage in HTML, or null
	 *         if the HtmlArmor instance was wrapping null.
	 */
	public static function getHtml( $input ) {
		if ( $input instanceof HtmlArmor ) {
			return $input->value;
		} else {
			return htmlspecialchars( $input, ENT_QUOTES );
		}
	}
}

/** @deprecated class alias since 1.44 */
class_alias( HtmlArmor::class, 'HtmlArmor' );
