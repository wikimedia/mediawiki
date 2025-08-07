<?php

namespace Wikimedia\DebugInfo;

/**
 * @since 1.40
 */
class DumpUtils {
	/**
	 * Convert an object to an array by casting, but filter the properties
	 * to make recursive dumping more feasible.
	 *
	 * @phpcs:ignore MediaWiki.Commenting.FunctionComment.ObjectTypeHintParam
	 * @param object $object
	 * @return array
	 * @throws \ReflectionException
	 */
	public static function objectToArray( $object ) {
		$vars = (array)$object;
		$class = new \ReflectionClass( $object );
		while ( $class ) {
			foreach ( $class->getProperties() as $property ) {
				if ( AnnotationReader::propertyHasAnnotation( $property, 'noVarDump' ) ) {
					// Ref: zend_declare_typed_property(), zend_mangle_property_name()
					if ( $property->isPrivate() ) {
						$mangledName = "\0{$class->name}\0{$property->name}";
					} elseif ( $property->isProtected() ) {
						$mangledName = "\0*\0{$property->name}";
					} else {
						$mangledName = $property->name;
					}
					if ( isset( $vars[$mangledName] ) && !is_scalar( $vars[$mangledName] ) ) {
						$vars[$mangledName] = new Placeholder( $vars[$mangledName] );
					}
				}
			}
			$class = $class->getParentClass();
		}
		return $vars;
	}
}
