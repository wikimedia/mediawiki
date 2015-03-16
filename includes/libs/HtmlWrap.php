<?php

/**
 * @since 1.25
 */
class HtmlWrap {
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
	 * @return HtmlWrap
	 */
	protected function extend( $content ) {
		$wrap = clone $this;
		$wrap->content .= $content;
		return $wrap;
	}

	/**
	 * @param HtmlWrap[] $wraps
	 * @return HtmlWrap[]
	 */
	protected static function consolidate( Array $wraps ) {
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
	 * @param string $glue
	 * @param HtmlWrap[] $wraps
	 * @return HtmlWrap[]
	 */
	public static function join( $glue, Array $wraps ) {
		return implode( $glue, self::consolidate( $wraps ) );
	}

	/** @return string */
	public function __toString() {
		return $this->before . $this->content . $this->after;
	}
}
