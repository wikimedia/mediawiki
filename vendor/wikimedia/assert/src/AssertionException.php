<?php

namespace Wikimedia\Assert;

/**
 * Marker interface for exceptions thrown by Assert. Since the exceptions thrown by Assert
 * use different standard exceptions as base classes, the marker interface is needed to be
 * able to catch them all at once.
 *
 * @license MIT
 * @author Daniel Kinzler
 * @copyright Wikimedia Deutschland e.V.
 */
interface AssertionException {

}
