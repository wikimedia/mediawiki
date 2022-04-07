<?php

namespace MediaWiki\Settings\Source;

use MediaWiki\Settings\SettingsBuilderException;
use ReflectionClass;
use ReflectionException;

/**
 * Constructs a settings array based on a PHP class by inspecting class
 * members to construct a schema.
 *
 * @since 1.39
 */
class ReflectionSchemaSource implements SettingsSource {

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
	 * @throws SettingsBuilderException
	 * @return array
	 */
	public function load(): array {
		$schemas = [];
		$jsonTypeHelper = new JsonTypeHelper();

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

				if ( $this->includeDoc ) {
					$doc = $const->getDocComment();
					if ( $doc ) {
						$schema['description'] = $this->normalizeComment( $doc );
					}
				}

				$schema = $jsonTypeHelper->normalizeJsonSchema( $schema );

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
			'config-schema' => $schemas
		];
	}

	/**
	 * Returns this file source as a string.
	 *
	 * @return string
	 */
	public function __toString(): string {
		return 'class ' . $this->class;
	}

	private function normalizeComment( string $doc ) {
		$doc = preg_replace( '/^\s*\/\*+\s*|\s*\*+\/\s*$/s', '', $doc );
		$doc = preg_replace( '/^\s*\**$/m', " ", $doc );
		$doc = preg_replace( '/^\s*\**[ \t]?/m', '', $doc );
		return $doc;
	}

}
