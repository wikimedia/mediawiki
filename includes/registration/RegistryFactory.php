<?php

/**
 * RegistryFactory class
 *
 * Handles construction of BaseRegistry objects.
 *
 * @since 1.25
 */
class RegistryFactory {

	/**
	 * @return ExtensionRegistry
	 */
	public static function getExtensionRegistry() {
		static $extRegistry = null;
		if ( !$extRegistry ) {
			$extRegistry = new ExtensionRegistry();
		}

		return $extRegistry;
	}

	/**
	 * @return SkinRegistry
	 */
	public static function getSkinRegistry() {
		static $skinRegistry = null;
		if ( !$skinRegistry ) {
			$skinRegistry = new SkinRegistry();
		}

		return $skinRegistry;
	}
}
