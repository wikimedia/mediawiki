<?php

namespace Wikimedia\Assert;

use RuntimeException;

/**
 * Exception indicating that an precondition assertion failed.
 * This generally means a disagreement between the caller and the implementation of a function.
 *
 * @license MIT
 * @author Daniel Kinzler
 * @copyright Wikimedia Deutschland e.V.
 */
class PreconditionException extends RuntimeException implements AssertionException {

}
