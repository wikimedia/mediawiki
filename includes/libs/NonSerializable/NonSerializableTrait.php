<?php

namespace Wikimedia\NonSerializable;

use LogicException;

/**
 * A trait that prevents serialization via php's builtin serialize() function.
 */
trait NonSerializableTrait {

	/**
	 * @throws LogicException always
	 * @return never
	 */
	public function __sleep(): never {
		throw new LogicException( 'Instances of ' . get_class( $this ) . ' are not serializable!' );
	}

}
