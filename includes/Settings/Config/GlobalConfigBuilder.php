<?php

namespace MediaWiki\Settings\Config;

use Config;
use GlobalVarConfig;

class GlobalConfigBuilder extends ArrayConfigBuilder {

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
		parent::set( $this->makeKey( $key ), $value, $mergeStrategy );
		$this->propagateGlobal( $key );
		return $this;
	}

	public function setDefault( string $key, $value, MergeStrategy $mergeStrategy = null ): ConfigBuilder {
		parent::setDefault( $this->makeKey( $key ), $value, $mergeStrategy );
		$this->propagateGlobal( $key );
		return $this;
	}

	private function propagateGlobal( string $key ): void {
		$varKey = $this->makeKey( $key );
		$GLOBALS[$varKey] = $this->config[$varKey];
	}

	private function makeKey( string $key ): string {
		return $this->prefix . $key;
	}

	public function build(): Config {
		return new GlobalVarConfig( $this->prefix );
	}
}
