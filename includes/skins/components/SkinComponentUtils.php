<?php

namespace MediaWiki\Skin;

class SkinComponentUtils {
	/**
	 * Adds a class to the existing class value, supporting it as a string
	 * or array.
	 *
	 * @param string|array $class to update.
	 * @param string $newClass to add.
	 * @return string|array classes.
	 * @internal
	 */
	public static function addClassToClassList( $class, string $newClass ) {
		if ( is_array( $class ) ) {
			$class[] = $newClass;
		} else {
			$class .= ' ' . $newClass;
			$class = trim( $class );
		}
		return $class;
	}
}
