<?php

namespace MediaWiki\Settings;

use LogicException;
use MediaWiki\Settings\Config\ConfigBuilder;
use MediaWiki\Settings\Config\ConfigSchema;

class DynamicDefaultValues {

	private ConfigSchema $configSchema;

	/**
	 * @var array
	 */
	private $declarations;

	/**
	 * @param ConfigSchema $configSchema
	 */
	public function __construct( ConfigSchema $configSchema ) {
		$this->configSchema = $configSchema;
		$this->declarations = $this->configSchema->getDynamicDefaults();
	}

	/**
	 * Compute dynamic defaults for settings that have them defined.
	 *
	 * @param ConfigBuilder $configBuilder
	 *
	 * @return void
	 */
	public function applyDynamicDefaults( ConfigBuilder $configBuilder ): void {
		$alreadyComputed = [];

		foreach ( $this->declarations as $key => $unused ) {
			$this->computeDefaultFor( $key, $configBuilder, $alreadyComputed );
		}
	}

	/**
	 * Compute dynamic default for a setting, recursively computing any dependencies.
	 *
	 * @param string $key Name of setting
	 * @param ConfigBuilder $configBuilder
	 * @param array &$alreadyComputed Map whose keys are the names of settings whose dynamic
	 *   defaults have already been computed
	 * @param array $currentlyComputing Ordered map whose keys are the names of settings whose
	 *   dynamic defaults are currently being computed, for cycle detection.
	 */
	private function computeDefaultFor(
		string $key,
		ConfigBuilder $configBuilder,
		array &$alreadyComputed = [],
		array $currentlyComputing = []
	): void {
		if ( !isset( $this->declarations[ $key ] ) || isset( $alreadyComputed[ $key ] ) ) {
			return;
		}
		if ( isset( $currentlyComputing[ $key ] ) ) {
			throw new LogicException(
				'Cyclic dependency when computing dynamic default: ' .
				implode( ' -> ', array_keys( $currentlyComputing ) ) . " -> $key"
			);
		}
		if (
			$configBuilder->get( $key ) !==
			$this->configSchema->getDefaultFor( $key )
		) {
			// Default was already overridden, nothing more to do
			$alreadyComputed[ $key ] = true;

			return;
		}

		$currentlyComputing[ $key ] = true;

		$callback = $this->declarations[ $key ]['callback'];
		$argNames = $this->declarations[ $key ]['use'] ?? [];
		$args = [];

		foreach ( $argNames as $argName ) {
			$this->computeDefaultFor(
				$argName,
				$configBuilder,
				$alreadyComputed,
				$currentlyComputing
			);
			$args[] = $configBuilder->get( $argName );
		}

		$configBuilder->set( $key, $callback( ...$args ) );

		$alreadyComputed[ $key ] = true;
	}
}
