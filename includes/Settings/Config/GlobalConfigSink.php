<?php

namespace MediaWiki\Settings\Config;

class GlobalConfigSink implements ConfigSink {

	/** @var string */
	private $configPrefix;

	/**
	 * @param string $configPrefix
	 */
	public function __construct( string $configPrefix ) {
		$this->configPrefix = $configPrefix;
	}

	public function set( string $key, $value ): ConfigSink {
		$GLOBALS[$this->configPrefix . $key] = $value;
		return $this;
	}

	public function setIfNotDefined( string $key, $value ): ConfigSink {
		if ( !array_key_exists( $this->configPrefix . $key, $GLOBALS ) ) {
			$this->set( $key, $value );
		}
		return $this;
	}
}
