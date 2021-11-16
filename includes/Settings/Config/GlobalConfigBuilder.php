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
		$this->config = &$GLOBALS;
		$this->prefix = $prefix;
	}

	public function set( string $key, $value, MergeStrategy $mergeStrategy = null ): ConfigBuilder {
		return parent::set( $this->prefix . $key, $value, $mergeStrategy );
	}

	public function setDefault( string $key, $value, MergeStrategy $mergeStrategy = null ): ConfigBuilder {
		return parent::setDefault( $this->prefix . $key, $value, $mergeStrategy );
	}

	public function build(): Config {
		return new GlobalVarConfig( $this->prefix );
	}
}
