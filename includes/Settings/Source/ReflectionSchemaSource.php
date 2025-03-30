<?php

namespace MediaWiki\Settings\Source;

use Closure;
use MediaWiki\Settings\SettingsBuilderException;
use ReflectionClass;
use ReflectionException;
use Stringable;

/**
 * Constructs a settings array based on a PHP class by inspecting class
 * members to construct a schema.
 *
 * The value of each constant must be an array structured like a JSON Schema.
 * For convenience, type declarations support PHPDoc style types in addition to
 * JSON types. To avoid confusion, use 'list' for sequential arrays and 'map'
 * for associative arrays.
 *
 * Dynamic default values can be declared using the 'dynamicDefault' key.
 * The structure of the dynamic default declaration is an array with two keys:
 * - 'callback': this is a PHP callable string or array, closures are not supported.
 * - 'use': A list of other config variables that the dynamic default depends on.
 *   The values of these variables will be passed to the callback as parameters.
 *
 * The following shorthands can be used with dynamic default declarations:
 * - if the value for 'use' is empty, it can be omitted.
 * - if 'callback' is omitted, it is assumed to be a static method "getDefault$name" on
 *   the same class where $name is the name of the variable.
 * - if the dynamic default declaration is not an array but a string, that
 *   string is taken to be the callback, with no parameters.
 * - if the dynamic default declaration is the boolean value true,
 *   the callback is assumed to be a static method "getDefault$name" on
 *   the same class where $name is the name of the variable.
 *
 * @since 1.39
 */
class ReflectionSchemaSource implements Stringable, SettingsSource {
	use JsonSchemaTrait;

	/**
	 * Name of a PHP class
	 * @var string
	 */
	private $class;

	/**
	 * @var bool
	 */
	private $includeDoc;

	/**
	 * @param string $class
	 * @param bool $includeDoc
	 */
	public function __construct( string $class, bool $includeDoc = false ) {
		$this->class = $class;
		$this->includeDoc = $includeDoc;
	}

	/**
	 * @inheritDoc
	 */
	public function load(): array {
		return $this->loadAsComponents();
	}

	/**
	 * @param bool $inlineReferences Whether the references found in the schema `$ref` should
	 * be inlined, meaning resolving its final type and embedding it as a regular schema. No
	 * definitions `$defs` will be returned.
	 * @throws SettingsBuilderException
	 * @return array
	 */
	public function loadAsComponents( bool $inlineReferences = false ): array {
		$schemas = [];
		$defs = [];
		$obsolete = [];

		try {
			$class = new ReflectionClass( $this->class );
			foreach ( $class->getReflectionConstants() as $const ) {
				if ( !$const->isPublic() ) {
					continue;
				}

				$name = $const->getName();
				$schema = $const->getValue();

				if ( !is_array( $schema ) ) {
					continue;
				}

				if ( isset( $schema['obsolete'] ) ) {
					$obsolete[ $name ] = $schema['obsolete'];
					continue;
				}

				if ( $this->includeDoc ) {
					$doc = $const->getDocComment();
					if ( $doc ) {
						$schema['description'] = $this->normalizeComment( $doc );
					}
				}

				if ( isset( $schema['dynamicDefault'] ) ) {
					$schema['dynamicDefault'] =
						$this->normalizeDynamicDefault( $name, $schema['dynamicDefault'] );
				}

				$schema['default'] ??= null;

				$schema = self::normalizeJsonSchema( $schema, $defs, $this->class, $name, $inlineReferences );

				$schemas[ $name ] = $schema;
			}
		} catch ( ReflectionException $e ) {
			throw new SettingsBuilderException(
				'Failed to load schema from class {class}',
				[ 'class' => $this->class ],
				0,
				$e
			);
		}

		return [
			'config-schema' => $schemas,
			'schema-definitions' => $defs,
			'obsolete-config' => $obsolete
		];
	}

	/**
	 * Load the data as a single top-level JSON Schema.
	 *
	 * Returned JSON Schema is for an object, which includes the individual config schemas. The
	 * returned schema may contain `$defs`, which then may be referenced internally in the schema
	 * via `$ref`.
	 *
	 * @param bool $inlineReferences Whether the references found in the schema `$ref` should
	 * be inlined, meaning resolving its final type and embedding it as a regular schema. No
	 * definitions `$defs` will be returned.
	 * @return array
	 */
	public function loadAsSchema( bool $inlineReferences = false ): array {
		$info = $this->loadAsComponents( $inlineReferences );
		$schema = [
			'type' => 'object',
			'properties' => $info['config-schema'],
		];

		if ( $info['schema-definitions'] ) {
			$schema['$defs'] = $info['schema-definitions'];
		}

		return $schema;
	}

	/**
	 * Returns this file source as a string.
	 */
	public function __toString(): string {
		return 'class ' . $this->class;
	}

	private function normalizeComment( string $doc ): string {
		$doc = preg_replace( '/^\s*\/\*+\s*|\s*\*+\/\s*$/', '', $doc );
		$doc = preg_replace( '/^\s*\**$/m', " ", $doc );
		$doc = preg_replace( '/^\s*\**[ \t]?/m', '', $doc );
		return $doc;
	}

	/**
	 * @param string $name
	 * @param true|string|array $spec
	 */
	private function normalizeDynamicDefault( string $name, $spec ): array {
		if ( $spec === true ) {
			$spec = [ 'callback' => [ $this->class, "getDefault{$name}" ] ];
		}

		if ( is_string( $spec ) ) {
			$spec = [ 'callback' => $spec ];
		}

		if ( !isset( $spec['callback'] ) ) {
			$spec['callback'] = [ $this->class, "getDefault{$name}" ];
		}

		if ( $spec['callback'] instanceof Closure ) {
			throw new SettingsBuilderException(
				"dynamicDefaults callback for $name must be JSON serializable. " .
				"Closures are not supported."
			);
		}

		if ( !is_callable( $spec['callback'] ) ) {
			$pretty = var_export( $spec['callback'], true );
			$pretty = preg_replace( '/\s+/', ' ', $pretty );

			throw new SettingsBuilderException(
				"dynamicDefaults callback for $name is not callable: " .
				$pretty
			);
		}

		return $spec;
	}

}
