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
	 * @param callback|null $callback Optional callback which will be called with a string
	 *    describing the reason why isExpired() is returning true.
	 * @return bool
	 */
	public function isExpired( $callback = null ) {
		if ( constant( $this->name ) != $this->value ) {
			if ( is_callable( $callback ) ) {
				// @ silences "var_export does not handle circular references";
				// phpcs:disable Generic.PHP.NoSilencedErrors.Discouraged
				$old = @var_export( $this->value, true );
				$new = @var_export( constant( $this->name ), true );
				// phpcs:enable Generic.PHP.NoSilencedErrors.Discouraged
				$callback( "Value of constant {$this->name} changed from {$old} to {$new}" );
			}
			return true;
		}

		return false;
	}
}
