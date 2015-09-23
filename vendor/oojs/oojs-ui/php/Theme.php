<?php

namespace OOUI;

/**
 * Theme logic.
 *
 * @abstract
 */
abstract class Theme {

	/* Properties */

	private static $singleton;

	/* Static Methods */

	/**
	 * @param Theme|null $theme
	 */
	public static function setSingleton( Theme $theme = null ) {
		self::$singleton = $theme;
	}

	/**
	 * @return Theme
	 * @throws Exception
	 */
	public static function singleton() {
		if ( !self::$singleton ) {
			throw new Exception( __METHOD__ . ' was called with no singleton theme set.' );
		}

		return self::$singleton;
	}

	/**
	 * Get a list of classes to be applied to a widget.
	 *
	 * The 'on' and 'off' lists combined MUST contain keys for all classes the theme adds or removes,
	 * otherwise state transitions will not work properly.
	 *
	 * @param Element $element Element for which to get classes
	 * @return array Categorized class names with `on` and `off` lists
	 */
	public function getElementClasses( Element $element ) {
		return array( 'on' => array(), 'off' => array() );
	}

	/**
	 * Update CSS classes provided by the theme.
	 *
	 * For elements with theme logic hooks, this should be called any time there's a state change.
	 *
	 * @param Element $element Element for which to update classes
	 * @return array Categorized class names with `on` and `off` lists
	 */
	public function updateElementClasses( Element $element ) {
		$classes = $this->getElementClasses( $element );

		if ( isset( $element->icon ) ) {
			$element->icon
				->removeClasses( $classes['off'] )
				->addClasses( $classes['on'] );
		}
		if ( isset( $element->indicator ) ) {
			$element->indicator
				->removeClasses( $classes['off'] )
				->addClasses( $classes['on'] );
		}
	}
}
