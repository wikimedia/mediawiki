<?php

declare( strict_types = 1 );

namespace Wikimedia\Tests\Message;

use LogicException;

/**
 * This class is part of a test for T377912,
 * where ScalarParam inappropriately tried to load a message param as a class.
 *
 * The class itself is irrelevant,
 * but any attempt to load it will trigger the LogicException below.
 * Mentioning the class as T377912TestCase::class is fine (does not trigger autoloading);
 * the test passes if ScalarParam does not try to load the param as a class
 * (e.g. by passing it into is_callable()).
 *
 * The file / class is called *TestCase so that it is allowed in the PHPUnit directory
 * without PHPUnit trying to load it automatically (as it would for T377912Test).
 *
 * @license GPL-2.0-or-later
 */
class T377912TestCase {

}

throw new LogicException( 'This file should never be loaded!' );
