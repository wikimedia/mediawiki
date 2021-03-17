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
		$type = gettype( $value );
		if ( $type === 'object' ) {
			$this->desc = get_class( $value ) . '#' . spl_object_id( $value );
		} else {
			$this->desc = $type;
		}
	}
}
