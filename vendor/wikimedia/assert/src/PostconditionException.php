<?php

namespace Wikimedia\Assert;

use LogicException;

/**
 * Exception indicating that a postcondition assertion failed.
 * This generally means an error in the internal logic of a function, or a serious problem
 * in the runtime environment.
 *
 * @license MIT
 * @author Daniel Kinzler
 * @copyright Wikimedia Deutschland e.V.
 */
class PostconditionException extends LogicException implements AssertionException {

}
