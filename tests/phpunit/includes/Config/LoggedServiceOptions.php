<?php

use MediaWiki\Config\ServiceOptions;

/**
 * Helper for TestAllServiceOptionsUsed.
 */
class LoggedServiceOptions extends ServiceOptions {
	/** @var array */
	private $accessLog;

	/**
	 * @param array &$accessLog Pass self::$serviceOptionsAccessLog from the class implementing
	 *   TestAllServiceOptionsUsed.
	 * @param string[] $keys
	 * @param mixed ...$args Forwarded to parent as-is.
	 */
	public function __construct( array &$accessLog, array $keys, ...$args ) {
		$this->accessLog = &$accessLog;
		if ( !$accessLog ) {
			$accessLog = [ $keys, [] ];
		}

		parent::__construct( $keys, ...$args );
	}

	/**
	 * @param string $key
	 * @return mixed
	 */
	public function get( $key ) {
		$this->accessLog[1][$key] = true;

		return parent::get( $key );
	}
}
