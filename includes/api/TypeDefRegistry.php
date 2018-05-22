<?php

namespace MediaWiki\Api;

/**
 * This class registers TypeDef objects
 * @since 1.32
 * @ingroup API
 */
class TypeDefRegistry {

	/** @var TypeDef[] Type to definition mappings */
	private $typeDefs = [];

	/**
	 * Register a type def
	 * @param string $type Type name
	 * @param TypeDef $def Type definition
	 */
	public function registerType( $type, TypeDef $def ) {
		$this->typeDefs[$type] = $def;
	}

	/**
	 * Get all known type names
	 * @return string[]
	 */
	public function getKnownTypes() {
		return array_keys( $this->typeDefs );
	}

	/**
	 * Get the TypeDef for a parameter type
	 * @param array|string $type Passing an array is equivalent to the string 'enum'.
	 * @return TypeDef|null
	 */
	public function getTypeDef( $type ) {
		if ( is_array( $type ) ) {
			$type = 'enum';
		}

		return isset( $this->typeDefs[$type] ) ? $this->typeDefs[$type] : null;
	}

}
