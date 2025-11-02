<?php

namespace MediaWiki\Widget;

use MediaWiki\Html\Html;
use Stringable;

/**
 * PHP version of jquery.spinner.
 *
 * If used with jquery.spinner.styles, can be used to show a
 * spinner before JavaScript has loaded.
 *
 * @copyright 2011-2020 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license MIT
 */
class SpinnerWidget implements Stringable {

	/** @var array */
	private $attributes;
	/** @var string */
	private $content;

	/**
	 * @param array $config Configuration options
	 */
	public function __construct( array $config = [] ) {
		$size = $config['size'] ?? 'small';
		$type = $config['type'] ?? 'inline';

		$this->attributes = [];

		if ( isset( $config['id'] ) ) {
			$this->attributes['id'] = $config['id'];
		}

		// Initialization
		$this->attributes['class'] = [
			'mw-spinner',
			$size === 'small' ? 'mw-spinner-small' : 'mw-spinner-large',
			$type === 'inline' ? 'mw-spinner-inline' : 'mw-spinner-block',
		];

		$this->content =
			'<div class="mw-spinner-container">' .
				str_repeat( '<div></div>', 12 ) .
			'</div>';
	}

	/**
	 * Render element into HTML.
	 * @return string HTML serialization
	 */
	public function toString() {
		return Html::rawElement( 'div', $this->attributes, $this->content );
	}

	/**
	 * Magic method implementation.
	 *
	 * Copied from OOUI\Tag
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->toString();
	}
}
