<?php

namespace Wikimedia\DebugInfo;

/**
 * Utility class for reading doc comment annotations
 *
 * @since 1.40
 */
class AnnotationReader {
	/** @var bool[] */
	private static $cache = [];

	/**
	 * Determine whether a ReflectionProperty has a specified annotation
	 *
	 * @param \ReflectionProperty $property
	 * @param string $annotationName
	 * @return bool
	 */
	public static function propertyHasAnnotation(
		\ReflectionProperty $property,
		string $annotationName
	) {
		$cacheKey = "$annotationName@{$property->class}::{$property->name}";
		if ( !isset( self::$cache[$cacheKey] ) ) {
			$comment = $property->getDocComment();
			if ( $comment === false ) {
				self::$cache[$cacheKey] = false;
			} else {
				$encAnnotation = preg_quote( $annotationName, '!' );
				self::$cache[$cacheKey] =
					(bool)preg_match( "!^[ \t]*(/\*\*|\*)[ \t]*@$encAnnotation\b!im", $comment );
			}
		}
		return self::$cache[$cacheKey];
	}
}
