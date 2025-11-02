<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

/**
 * Depend on a PHP constant.
 *
 * @ingroup Language
 */
class ConstantDependency extends CacheDependency {
	/** @var string */
	private $name;
	/** @var mixed */
	private $value;

	public function __construct( string $name ) {
		$this->name = $name;
		$this->value = constant( $name );
	}

	/**
	 * @return bool
	 */
	public function isExpired() {
		return constant( $this->name ) != $this->value;
	}
}
