<?php

namespace Wikimedia\NonSerializable;

use LogicException;

/**
 * A trait that prevents serialization via php's builtin serialize() function.
 */
trait NonSerializableTrait {

	/**
	 * @throws LogicException always
	 */
	public function __sleep() {
		throw new LogicException( 'Instances of ' . get_class( $this ) . ' are not serializable!' );
	}

}
