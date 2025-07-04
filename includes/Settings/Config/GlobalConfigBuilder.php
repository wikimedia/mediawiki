<?php

namespace MediaWiki\Settings\Config;

use MediaWiki\Config\Config;
use MediaWiki\Config\GlobalVarConfig;
use function array_key_exists;

class GlobalConfigBuilder extends ConfigBuilderBase {

	public const DEFAULT_PREFIX = 'wg';

	/** @var string */
	private $prefix;

	/**
	 * @param string $prefix
	 */
	public function __construct( string $prefix = self::DEFAULT_PREFIX ) {
		$this->prefix = $prefix;
	}

	protected function has( string $key ): bool {
		$var = $this->getVarName( $key );
		// (T317951) Don't call array_key_exists unless we have to, as it's slow
		// on PHP 8.1+ for $GLOBALS. When the key is set but is explicitly set
		// to null, we still need to fall back to array_key_exists, but that's
		// rarer.
		return isset( $GLOBALS[$var] ) || array_key_exists( $var, $GLOBALS );
	}

	/** @inheritDoc */
	public function get( string $key ) {
		$var = $this->getVarName( $key );
		return $GLOBALS[ $var ] ?? null;
	}

	/** @inheritDoc */
	protected function update( string $key, $value ) {
		$var = $this->getVarName( $key );
		$GLOBALS[ $var ] = $value;
	}

	public function setMulti( array $values, array $mergeStrategies = [] ): ConfigBuilder {
		// NOTE: It is tempting to do $GLOBALS = array_merge( $GLOBALS, $values ).
		//       But that no longer works in PHP 8.1!
		//       See https://wiki.php.net/rfc/restrict_globals_usage

		foreach ( $values as $key => $newValue ) {
			$var = $this->prefix . $key; // inline getVarName() to avoid function call

			// Optimization: Inlined logic from set() for performance
			if ( isset( $GLOBALS[$var] ) && array_key_exists( $key, $mergeStrategies ) ) {
				$mergeStrategy = $mergeStrategies[$key];
				if ( $mergeStrategy && is_array( $newValue ) ) {
					$oldValue = $GLOBALS[$var];
					if ( $oldValue && is_array( $oldValue ) ) {
						$newValue = $mergeStrategy->merge( $oldValue, $newValue );
					}
				}
			}

			$GLOBALS[$var] = $newValue;
		}
		return $this;
	}

	private function getVarName( string $key ): string {
		return $this->prefix . $key;
	}

	public function build(): Config {
		return new GlobalVarConfig( $this->prefix );
	}
}
