<?php

namespace OOUI;

class ElementMixin {

	/* Properties */

	/**
	 * Tag being targeted.
	 *
	 * @var Tag
	 */
	public $target = null;

	/**
	 * Element being mixed into.
	 *
	 * @var Element
	 */
	protected $element = null;

	/**
	 * Property name for accessing the target on the element.
	 *
	 * @var string
	 */
	public static $targetPropertyName = '';

	/* Methods */

	/**
	 * Create element.
	 *
	 * @param Element $element Element being mixed into
	 * @param Tag $tag Tag being targeted
	 * @param array $config Configuration options
	 */
	public function __construct( Element $element, Tag $target, array $config = array() ) {
		$this->element = $element;
		$this->target = $target;
	}

	/**
	 * Add properties to the given `$config` array to allow reconstruction
	 * of this widget via its constructor.  This method is meant to be
	 * overridden by subclasses of `ElementMixin`.
	 *
	 * @return array A configuration array.
	 * @see Element::getConfig()
	 */
	public function getConfig( &$config ) {
		return $config;
	}
}
