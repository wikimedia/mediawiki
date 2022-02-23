<?php

namespace MediaWiki\Settings\Config;

use Config;
use GlobalVarConfig;

class GlobalConfigBuilder extends ConfigBuilderBase {

	/** @var string */
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
		return array_key_exists( $var, $GLOBALS );
	}

	protected function get( string $key ) {
		$var = $this->getVarName( $key );
		return $GLOBALS[ $var ] ?? null;
	}

	protected function update( string $key, $value ) {
		$var = $this->getVarName( $key );
		$GLOBALS[ $var ] = $value;
	}

	private function getVarName( string $key ): string {
		return $this->prefix . $key;
	}

	public function build(): Config {
		return new GlobalVarConfig( $this->prefix );
	}
}
