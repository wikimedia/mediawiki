<?php

/**
 * Trait for SearchResult subclasses to share non-obvious behaviors or methods
 * that rarely specialized
 */
trait SearchResultTrait {
	/**
	 * A function returning a set of extension data.
	 * @var Closure|null
	 */
	protected $extensionData;

	/**
	 * Get the extension data as:
	 * augmentor name => data
	 * @return array[]
	 */
	public function getExtensionData() {
		if ( $this->extensionData ) {
			return ( $this->extensionData )();
		} else {
			return [];
		}
	}

	/**
	 * Set extension data for this result.
	 * The data is:
	 * augmentor name => data
	 * @param Closure|array $extensionData Takes no arguments, returns
	 *  either array of extension data or null.
	 */
	public function setExtensionData( $extensionData ) {
		if ( $extensionData instanceof Closure ) {
			$this->extensionData = $extensionData;
		} elseif ( is_array( $extensionData ) ) {
			wfDeprecated( __METHOD__ . ' with array argument', '1.32' );
			$this->extensionData = static function () use ( $extensionData ) {
				return $extensionData;
			};
		} else {
			$type = get_debug_type( $extensionData );
			throw new InvalidArgumentException(
				__METHOD__ . " must be called with Closure|array, but received $type" );
		}
	}
}
