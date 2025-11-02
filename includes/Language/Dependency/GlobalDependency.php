<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

/**
 * Depend on a PHP global variable.
 *
 * @deprecated since 1.42
 * @ingroup Language
 */
class GlobalDependency extends CacheDependency {
	/** @var string */
	private $name;
	/** @var mixed */
	private $value;

	public function __construct( string $name ) {
		$this->name = $name;
		$this->value = $GLOBALS[$name];
	}

	/** @inheritDoc */
	public function isExpired() {
		if ( !isset( $GLOBALS[$this->name] ) ) {
			return true;
		}

		return $GLOBALS[$this->name] != $this->value;
	}
}
