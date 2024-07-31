<?php

namespace Wikimedia\DebugInfo;

/**
 * A class used for replacing large objects in var_dump() output.
 *
 * @since 1.40
 */
class Placeholder {
	/** @var string A description of the replaced object */
	public $desc;

	/**
	 * @param mixed $value
	 */
	public function __construct( $value ) {
		if ( is_object( $value ) ) {
			$this->desc = get_class( $value ) . '#' . spl_object_id( $value );
		} else {
			$this->desc = get_debug_type( $value );
		}
	}
}
