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
	public function isExpired( $callback = null ) {
		if ( !isset( $GLOBALS[$this->name] ) ) {
			if ( is_callable( $callback ) ) {
				$callback( "No global named {$this->name}" );
			}
			return true;
		}

		if ( $GLOBALS[$this->name] != $this->value ) {
			if ( is_callable( $callback ) ) {
				// @ silences "var_export does not handle circular references";
				// phpcs:disable Generic.PHP.NoSilencedErrors.Discouraged
				$old = @var_export( $this->value, true );
				$new = @var_export( $GLOBALS[$this->name], true );
				// phpcs:enable Generic.PHP.NoSilencedErrors.Discouraged
				$callback( "Value of global {$this->name} changed from {$old} to {$new}" );
			}
			return true;
		}

		return false;
	}
}
