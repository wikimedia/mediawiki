<?php

namespace MediaWiki\Settings\Config;

/**
 * Null implementation of PhpIniSink, useful for testing and benchmarking.
 *
 * @since 1.39
 */
class NullIniSink extends PhpIniSink {

	/**
	 * @param string $option
	 * @param string $value
	 * @return void
	 */
	public function set( string $option, string $value ): void {
		// noop
	}

}
