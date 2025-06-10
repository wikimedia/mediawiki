<?php

namespace MediaWiki\Settings\Source;

use Error;
use InvalidArgumentException;

/**
 * Utility for resolving references ($ref) in JSON-schemas
 * and building a collection of the referenced definitions.
 *
 * @since 1.42
 * @unstable
 */
class JsonSchemaReferenceResolver {
	/**
	 * Returns a URI relative to a JSON-schema document
	 * for a given definition name
	 *
	 * @param array $schema A $ref sub-schema
	 * @return string Definition relative URI
	 */
	public static function normalizeRef( array $schema ): string {
		return '#/$defs/' . self::getDefinitionName( $schema );
	}

	/**
	 * Destructures and validates a JSON-schema "$ref" definition of the
	 * form [ "class" => SomePHPClass:class, "field" => "someExistingPublicConsInTheClass" ]
	 *
	 * @param array $schema A $ref sub-schema
	 * @return array An array with the class name as the first element, and the field name as the second.
	 */
	private static function unpackRef( array $schema ): array {
		$className = $schema['class'];
		$field = $schema['field'];
		if ( !$className || !$field ) {
			throw new InvalidArgumentException( 'The schema $ref must have "class" and "field" defined.' );
		}
		return [
			$className,
			$field
		];
	}

	/**
	 * Builds a definition name based on the specified "$ref" definition
	 *
	 * @param array $schema A $ref sub-schema
	 * @return string A definition name using both the name of the class and the field
	 */
	public static function getDefinitionName( array $schema ): string {
		[ $className, $field ] = self::unpackRef( $schema );
		// Avoid using "\" or "/" in the definition name as they are interpreted as fragment
		// separators in the URI and make consumers of the schema unable to resolve a document
		// relative schema. Use "." as the word separator for the namespace, resulting in
		// eg: SomeExtension.Namespace.MyClass::MyConstant
		return str_replace( "\\", '.', $className ) . '::' . $field;
	}

	/**
	 * Returns the value of a given reference "$ref".
	 *
	 * @param array $schema A $ref sub-schema
	 * @return array The value of the class field referenced by the definition
	 */
	public static function resolveRef( array $schema, string $rootClass ): array {
		[ $className, $fieldName ] = self::unpackRef( $schema );
		try {
			$value = constant( "$className::$fieldName" );
		} catch ( Error ) {
			throw new RefNotFoundException(
				"Failed resolving reference $fieldName in $className. Root schema location: $rootClass"
			);
		}
		return $value;
	}

}
