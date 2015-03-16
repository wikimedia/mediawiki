<?php
/**
 * @since 1.25
 */
class WrappedString {
	/** @var string */
	protected $before;

	/** @var string */
	protected $content;

	/** @var string */
	protected $after;

	/**
	 * @param string $before
	 * @param string $content
	 * @param string $after
	 */
	public function __construct( $before, $content = '', $after = '' ) {
		$this->before = $before;
		$this->content = $content;
		$this->after = $after;
	}

	/**
	 * @param string $content
	 * @return WrappedString Newly wrapped stringg
	 */
	protected function extend( $content ) {
		$wrap = clone $this;
		$wrap->content .= $content;
		return $wrap;
	}

	/**
	 * Merge consecutive wrapped strings with the same before/after values.
	 *
	 * Does not modify the array or the WrappedString objects.
	 *
	 * @param WrappedString[] $wraps
	 * @return WrappedString[]
	 */
	protected static function compact( Array &$wraps ) {
		$consolidated = array();
		$prev = array_shift( $wraps );
		foreach ( $wraps as $wrap ) {
			if ( $prev->before === $wrap->before && $prev->after === $wrap->after ) {
				$prev = $prev->extend( $wrap->content );
			} else {
				$consolidated[] = $prev;
				$prev = $wrap;
			}
		}
		// Add last one
		$consolidated[] = $prev;

		return $consolidated;
	}

	/**
	 * Join a several wrapped strings with a separator between each.
	 *
	 * @param string $sep
	 * @param WrappedString[] $wraps
	 * @return string
	 */
	public static function join( $sep, Array $wraps ) {
		return implode( $sep, self::compact( $wraps ) );
	}

	/** @return string */
	public function __toString() {
		return $this->before . $this->content . $this->after;
	}
}
