<?php

namespace MediaWiki\Settings\Config;

use Config;
use GlobalVarConfig;

class GlobalConfigBuilder implements ConfigBuilder {
	use ConfigBuilderTrait;

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

	public function set( string $key, $value, MergeStrategy $mergeStrategy = null ): ConfigBuilder {
		$var = $this->getVarName( $key );

		$GLOBALS[ $var ] =
			$this->getNewValue( $key, $GLOBALS[ $var ] ?? null, $value, $mergeStrategy );
		return $this;
	}

	public function setDefault( string $key, $value, MergeStrategy $mergeStrategy = null ): ConfigBuilder {
		$var = $this->getVarName( $key );

		if ( $mergeStrategy ) {
			$this->set( $key, $value, $mergeStrategy->reverse() );
		} elseif ( !array_key_exists( $var, $GLOBALS ) ) {
			$GLOBALS[ $var ] = $value;
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
